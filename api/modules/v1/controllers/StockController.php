<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;


class StockController extends BaseActiveController
{
    public $modelClass = 'app\models\Stock';

}