<?php

namespace app\models;

/**
 * Class User
 * @package app\models
 *
 * @property array $portfolio
 */
class User extends \app\models\base\User
{
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // add field to scenarios
        $scenarios['create'][]   = 'field';
        $scenarios['update'][]   = 'field';
        $scenarios['register'][] = 'field';
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        // add some rules
        $rules['fieldRequired'] = ['field', 'required'];
        $rules['fieldLength']   = ['field', 'string', 'max' => 10];

        return $rules;
    }

    public function getPortfolio()
    {
        $p = [];
        $txn = Transaction::findAll(['user_id' => $this->id]);
        foreach ($txn as $t){
            if (!isset($p[$t->stock_id])){
                $p[$t->stock_id] = ['stock'=>$t->stock];
            }
            $p[$t->stock_id]['qty'] += $t->qty_bought;
        }
        return $p;
    }

    public function portfolio_html($mode='short')
    {
        $portfolio = $this->portfolio;
        $html = '';
        return $html;
    }
}
