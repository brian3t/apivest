<?php

namespace app\models;

/**
 * Class Portfolio
 * @package app\models
 * @property array $stocks
 * @property integer $total_points
 * @property integer $user_id
 * @property double $average_change_since_last_traded
 * @property double average_unit_cost
 * @property double average_real_time_value
 */
class Portfolio
{
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->total_points = 0;
        $this->average_change_since_last_traded = 0;
        $this->average_unit_cost = 0;
        $this->average_real_time_value = 0;
        $stocks = [];
        $txn = Transaction::findAll(['user_id' => $this->user_id]);
        foreach ($txn as $t) {
            $this->total_points += $t->points_gained;
            if (!isset($stocks[$t->stock_id])) {
                $stocks[$t->stock_id] = ['transaction' => $t, 'qty' => 0];
            }
            if ($t->is_buying)
                $stocks[$t->stock_id]['qty']++;
            else {
                $stocks[$t->stock_id]['qty']--;
            }
            $stocks[$t->stock_id]['model'] = $t->stock;
        }
        $total_unit_cost = 0;
        $total_real_time_value = 0;
        $total_change_since_last_traded = 0;
        foreach ($stocks as $stock_id => &$stock) {
            if ($stock['qty'] == 0) {
                unset($stocks[$stock_id]);
                continue;
            }
            $total_unit_cost += ($stock['transaction'])->unit_cost;
            $total_real_time_value += ($stock['transaction'])->stock->real_time_value;
            $total_change_since_last_traded += ($stock['transaction'])->change_since_last_traded;
        }
        $this->stocks = $stocks;
        $this->average_unit_cost = (count($stocks) > 0) ? $total_unit_cost / (count($stocks)) : 0;
        $this->average_real_time_value = (count($stocks) > 0) ? $total_real_time_value / (count($stocks)) : 0;
        $this->average_real_time_value = round($this->average_real_time_value, 4);
        $this->average_change_since_last_traded = (count($stocks) > 0) ? $total_change_since_last_traded / (count($stocks)) : 0;
        $this->average_change_since_last_traded = round($this->average_change_since_last_traded, 4);
    }
}