<?php

namespace app\models;

use Yii;
use \app\models\base\Transaction as BaseTransaction;

/**
 * This is the model class for table "transaction".
 */
class Transaction extends BaseTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['user_id', 'stock_id', 'qty_bought', 'unit_cost'], 'required'],
            [['user_id', 'stock_id', 'qty_bought'], 'integer'],
            [['created_at'], 'safe'],
            [['unit_cost'], 'number'],
            [['user_id', 'stock_id', 'created_at'], 'unique', 'targetAttribute' => ['user_id', 'stock_id', 'created_at'], 'message' => 'The combination of User ID, Stock ID and Created At has already been taken.']
        ]);
    }
	
}
