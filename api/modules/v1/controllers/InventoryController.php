<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\models\Inventory;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;


class InventoryController extends BaseActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Inventory';
    
    public function actionPush()
    {
        $result = [
            'status' => 'failed',
        ];
        
        if (empty($this->requestbody->inventory_updates)) {
            return $result;
        }
        
        try {
            foreach ($this->requestbody->inventory_updates as $inventory_update) {
                $inventory = Inventory::find()->where(['sku' => $inventory_update->sku])->one();
                if (!is_object($inventory)) {
                    $inventory = new Inventory(['sku' => $inventory_update->sku]);
                }
                $inventory_details = $inventory_update->quantity_detail;
                unset($inventory_update->quantity_detail);
                if (!$inventory->loadAll(['Inventory' => $inventory_update, 'InventoryDetail' => ArrayHelper::toArray($inventory_details)])) {
                    return $inventory->errors;
                };
                if (!$inventory->saveAll()) {
                    return $inventory->errors;
                };
            }
            
            $result['status'] = 'successful';
            \Yii::$app->response->setStatusCode(200);
        } catch (\Exception $e) {
            \Yii::$app->response->setStatusCode(500);
            throw $e;
        }
        
        return $result;
        
    }
}