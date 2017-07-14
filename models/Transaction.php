<?php

namespace app\models;

use Yii;
use \app\models\base\Transaction as BaseTransaction;

/**
 * This is the model class for table "transaction".
 */
class Transaction extends BaseTransaction
{
    public function getLast_txn_same_stock()
    {
        if ($this->is_buying) return false;
        return \app\models\Transaction::findOne(['stock_id' => $this->stock_id, 'user_id' => $this->user_id, 'is_buying' => true]);
    }

    public function getChange_since_last_traded()
    {
        return $this->unit_cost - $this->stock->real_time_value;
    }

    public function getChange_since_last_traded_percent()
    {
        return ($this->stock->real_time_value != 0 ? ($this->unit_cost - $this->stock->real_time_value) / $this->stock->real_time_value * 100 : 0);
    }

}
