<?php

namespace app\models;

use Yii;
use \app\models\base\PointHistory as BasePointHistory;

/**
 * This is the model class for table "point_history".
 */
class PointHistory extends BasePointHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['transaction_id', 'open_date'], 'required'],
            [['transaction_id'], 'integer'],
            [['open_date', 'created_at'], 'safe'],
            [['open_price'], 'number'],
            [['transaction_id', 'open_date'], 'unique', 'targetAttribute' => ['transaction_id', 'open_date'], 'message' => 'The combination of Transaction ID and Open Date has already been taken.']
        ]);
    }
	
}
