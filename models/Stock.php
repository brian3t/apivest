<?php

namespace app\models;

use Yii;
use \app\models\base\Stock as BaseStock;

/**
 * This is the model class for table "stock".
 */
class Stock extends BaseStock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['symbol', 'name'], 'required'],
            [['last_sale', 'market_cap'], 'number'],
            [['ipo_year'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['symbol'], 'string', 'max' => 12],
            [['name', 'industry'], 'string', 'max' => 80],
            [['last_sale_text'], 'string', 'max' => 30],
            [['ipo_year_text'], 'string', 'max' => 20],
            [['sector'], 'string', 'max' => 100],
            [['summary_quote'], 'string', 'max' => 255],
            [['exchange'], 'string', 'max' => 15],
            [['country'], 'string', 'max' => 2],
            [['symbol'], 'unique']
        ]);
    }
	
}
