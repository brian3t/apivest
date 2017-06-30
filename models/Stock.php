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
     * @return array Fields for REST API calls
     */
    public function fields()
    {
        return [
            'id',
            'symbol',
            'name',
            'last_sale',
            'market_cap',
            'ipo_year',
            'sector',
            'industry',
            'exchange'
        ];
    }
}