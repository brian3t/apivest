<?php

namespace app\models;

use Yii;
use \app\models\base\Transaction as BaseTransaction;

/**
 * This is the model class for table "transaction".
 *
 * @property double $gain
 * @property integer $points_gained
 */
class Transaction extends BaseTransaction
{
    /**
     * @return array Fields for REST API calls
     */
    public function fields()
    {
        return [
            'id',
            'stock_id',
            'is_buying',
            'created_at',
            'unit_cost',
            'gain',
            'change_since_last_traded_percent'
        ];
    }
    public function getLast_txn_same_stock()
    {
        if ($this->is_buying) return false;
        return \app\models\Transaction::findOne(['stock_id' => $this->stock_id, 'user_id' => $this->user_id, 'is_buying' => true]);
    }

    public function getGain()
    {
        if ($this->is_buying) return 0;
        return $this->unit_cost - $this->getLast_txn_same_stock()->unit_cost;
    }

    public function getPoints_gained()
    {
        if ($this->is_buying || !$this->getLast_txn_same_stock() || ($this->getLast_txn_same_stock()->unit_cost == 0)) {
            return 0;
        }
        return round(($this->unit_cost - $this->getLast_txn_same_stock()->unit_cost) / $this->getLast_txn_same_stock()->unit_cost * 100);
    }

    public function getChange_since_last_traded()
    {
        return $this->stock->real_time_value - $this->unit_cost;
    }

    public function getChange_since_last_traded_percent()
    {
        $change = ($this->stock->real_time_value != 0 ? ($this->unit_cost - $this->stock->real_time_value) / $this->stock->real_time_value * 100 : 0);
        return round($change,1);
    }

}
