<?php
namespace app\api\modules\v1\controllers;

// define("DEBUG", false);
define("DEBUG", true);
define('LIMIT', 18);

use app\api\base\controllers\BaseActiveController;
use app\models\Mp;
use app\models\Order;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\QueryBuilder;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Action;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\rest\IndexAction;


class TrackingController extends BaseActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Tracking';
    
    /**
     * @var callable a PHP callable that will be called when running an action to determine
     * if the current user has the permission to execute the action. If not set, the access
     * check will not be performed. The signature of the callable should be as follows,
     *
     * ```php
     * function ($action, $model = null) {
     *     // $model is the requested model instance.
     *     // If null, it means no specific model (e.g. IndexAction)
     * }
     * ```
     */
    public $checkAccess;
    
    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return an instance of [[ActiveDataProvider]].
     */
    public $prepareDataProvider;
    
    public function actions() {
        $actions = parent::actions();
        
        // disable the "delete" actions
        unset($actions['delete']);
        
        return $actions;
    }
    
    /**
     * ROP Endpoint: Tracking Push
     * @param string $mp_endpoint_name Marketplace endpoint name, e.g. loehmanns
     * Expects json_encoded Associative array of Trackings. Each tracking is an key-value pair, key is ROP Order ID, value is
     * an array containing tracking information
     *      retailops_order_id    =>
     *          {
     *              "sku": sku, e.g.     "sku": ABC123
     *              "tracking_number": Tracking Number, e.g. "tracking_number": ABC123456ABC
     *              "tracking_carrier": 'USPS'
     *              "ship_date":    2015-03-04 16:09:00
     *          }
     * @return string A json encoded array with the following information:
     * {
     *  status: successful|failed
     *  count: Number of orders updated
     *  info:   further information, such as error at ROP side, error at SME side
     * }
     * @throws \Exception if Db commit fails
     */
    public function actionPush($mp_endpoint_name = null) {
        \Yii::$app->response->headers->add('Content-type', 'application/json');
        $result = [
            'status' => 'failed',
        ];
        $data = \Yii::$app->request->post();
        
        // if (is_string($data)) {
        //
        //     try {
        //         $data = json_decode($data);
        //     } catch (Exception $e) {
        //         \Yii::warning('Data received from order confirm is not string but not json string.');
        //     }
        // }
        
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($data as $retailops_order_id => $tracking) {
                $command = \Yii::$app->db->createCommand('REPLACE INTO `tracking`
                 (`retailops_order_id`, `sku`, `tracking_number`, `tracking_carrier`, `ship_date`) 
                 VALUES (:retailops_order_id, :sku, :tracking_number, :tracking_carrier, :ship_date)')
                    ->bindValues([':retailops_order_id' => $retailops_order_id, ':sku' => $tracking['sku'], ':tracking_carrier' => $tracking['tracking_carrier'],
                        ':tracking_number' => $tracking['tracking_number'], ':ship_date' => $tracking['ship_date']]);
                $command->execute();
            }
            
            $transaction->commit();
            $result['status'] = 'successful';
            $result['count'] = count($data);
            \Yii::$app->response->setStatusCode(201);
        } catch (\Exception $e) {
            $transaction->rollBack();
            $result['info'] = "Transaction error: " . $e->getMessage();
            \Yii::$app->response->setStatusCode(500);
            throw $e;
        }
        
        echo json_encode($result);
        \Yii::$app->end(1);
    }
}

class OrderAction extends IndexAction
{
    /**
     * @return ActiveDataProvider
     * Also mark exported items's last_rop_pull
     * And increase the number of times they got pulled by ROP
     */
    public function run() {
        //todov2 pull query parameters from action index. It's the same params that prepareDataProvider (see above) uses
        $query = \Yii::$app->db->createCommand('UPDATE `order` SET last_rop_pull = NOW(), count_rop_pull = count_rop_pull + 1 WHERE retailops_order_id IS NULL OR `order`.force_rop_resend = 1 LIMIT ' . LIMIT . ';')
            ->execute();
        return parent::run();
    }
    
}