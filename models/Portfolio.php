<?php

namespace app\models;

/**
 * Class Portfolio
 * @package app\models
 * @property array $stocks
 * @property integer $total_points
 * @property integer $user_id
 */
class Portfolio
{
    public function __construct($user_id)
    {
        $this->total_points = 0;
        $stocks = [];
        $txn = Transaction::findAll(['user_id' => $this->user_id]);
        foreach ($txn as $t) {
            $this->total_points += $t->getChange_since_last_traded_percent();
            if (!isset($stocks[$t->stock_id])) {
                $stocks[$t->stock_id] = ['stock' => $t->stock, 'change_since_last_traded' => $t->change_since_last_traded];
            } else {
if ($t->is_buying)
                $stocks[$t->stock_id];
            }
        }
        $this->stocks = $stocks;
    }
}