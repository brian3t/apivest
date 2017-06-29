<?php
// Check this namespace:
namespace app\api\modules\v1;

use app\api\base\RequestBody;
use Yii;
use yii\rest\Serializer;
use yii\web\HeaderCollection;
use yii\web\Response;
use HttpResponse;

class Module extends Yii\base\Module
{
    public function init()
    {
        parent::init();
        
        // ...  other initialization code ...
    }
    
    public function beforeAction($action)
    {
        $request = Yii::$app->request;
        return parent::beforeAction($action);
    }
    
    public function afterAction($action, $result)
    {
        $controller_id = Yii::$app->controller->id;
        $response_header = Yii::$app->response->getHeaders();
        /** @var HeaderCollection $response_header */
        
        if ($result === false) {
            Yii::$app->response->setStatusCode(400);
            return Yii::$app->controller->message ? ['error' => Yii::$app->controller->message] : '';
        }
        
        if (!is_array($result)) {
            $result = (array)$result;
        }
        array_walk($result, function (&$item) {
            if (is_null($item)) {
                $item = strval($item);
            };
        });
        switch ($action->id) {
            case 'pull':
                $page_count = $response_header->get('X-Pagination-Page-Count');
                $cur_page = $response_header->get('X-Pagination-Current-Page');
                if ($cur_page == $page_count) {
                    $next_page_token = 'N/A';
                } else {
                    $next_page_token = $cur_page + 1;
                }
                $new_result = ['next_page_token' => strval($next_page_token), "{$controller_id}s" => $result];
                return parent::afterAction($action, $new_result);
                break;
            default:
                return parent::afterAction($action, $result);
                break;
        }
    }
}