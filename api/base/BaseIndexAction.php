<?php

namespace app\api\base;

use yii\rest\ActiveController;
use yii\rest\IndexAction;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\data\ActiveDataProvider;

class BaseIndexAction extends IndexAction
{
    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        $order_by_col='id';
        $modelClass=new $this->modelClass();
        if(property_exists($modelClass,'order_by_col'))
        {
            $order_by_col=$modelClass::$order_by_col;
        }
        if($this->prepareDataProvider!==null)
        {
            return call_user_func($this->prepareDataProvider,$this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass=$this->modelClass;
        $ap=new ActiveDataProvider([
            'query'=>$modelClass::find()->where(\Yii::$app->request->queryParams),
            'pagination'=>false
        ]);
        $ap->setSort(['defaultOrder'=>[$order_by_col=>SORT_ASC]]);
        return $ap;
    }

}