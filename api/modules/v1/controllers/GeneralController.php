<?php

namespace app\api\modules\v1\controllers;

use Yii;
use app\api\base\controllers\BaseActiveController;
use app\models\Profile;
use app\models\User;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;


class GeneralController extends BaseActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\User';

    public function actionLeaderboard()
    {
        $result = [
            'status' => 'failed',
        ];
        $all_ai = Yii::$app->db->createCommand('SELECT id AS cuser_id,username,name,city,state_abbr,IFNULL(point,ai_point) as point FROM (SELECT username, id FROM vest.user WHERE is_ai = 1) u INNER JOIN profile p ON u.id = p.user_id ORDER BY ai_point DESC LIMIT 100')->queryAll();
        array_walk($all_ai, function (&$v, $i) {
            $v['rank'] = $i + 1;
        });
        return $all_ai;

    }
}