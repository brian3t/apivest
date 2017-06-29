<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\models\base\Offer;
use yii\db\Query;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;


class OfferController extends BaseActiveController
{
    public $modelClass = 'app\models\Offer';

    public function actionSearch_by_company($company_id)
    {
        if (is_null($company_id) || empty($company_id)) {
            return (\app\models\Offer::find())->all();
        }
        $result = [];
        $result = \Yii::$app->db->createCommand('SELECT * FROM offer INNER JOIN (SELECT id AS user_id FROM user WHERE user.company_id = ' . $company_id . ') user 
        ON offer.user_id = user.user_id ')
            ->queryAll();
        RETURN $result;
    }
}