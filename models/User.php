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
    const INITIAL_MONEY = 1000;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // add field to scenarios
        $scenarios['create'][] = 'field';
        $scenarios['update'][] = 'field';
        $scenarios['register'][] = 'field';
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        // add some rules
        $rules['fieldRequired'] = ['field', 'required'];
        $rules['fieldLength'] = ['field', 'string', 'max' => 10];

        return $rules;
    }

    public function getPortfolio()
    {
        $p = ['stocks' => $stocks = [], 'money_left' => $this::INITIAL_MONEY, 'sum_qty' => $sum_qty = 0, 'sum_value' => $sum_value = 0];
        $txn = Transaction::findAll(['user_id' => $this->id]);
        foreach ($txn as $t) {
            if (!isset($stocks[$t->stock_id])) {
                $stocks[$t->stock_id] = ['stock' => $t->stock, 'change_since_last_traded'=>$t->change_since_last_traded];
            }
        }
        $p['stocks'] = $stocks;
        $p['sum_value'] = $sum_value;
        return $p;
    }

    public function portfolio_html($mode = 'short')
    {
        $portfolio = $this->portfolio;
        $html = '<div class="table-responsive no-padding">
<table class="table table-hover"><tbody>
<tr>
                                            <th>#</th>
                                            <th>Stock</th>
                                            <th>Company</th>
                                            <th>Real-time Value</th>
                                            <th>Change since last traded</th>
                                        </tr>';
        $index = 0;
        foreach ($portfolio['stocks'] as $stock_id => $stock) {
            $index++;
            $html .= '<tr>';
            $html .= '<td>' . $index . '</td>';
            $html .= '<td>' . ($stock['stock'])->symbol . '</td>';
            $html .= '<td>' . ($stock['stock'])->name . '</td>';
            $html .= '<td>$' . money_format('%6.4n', ($stock['stock'])->real_time_value) . '</td>';
            $html .= '<td>'. $stock['change_since_last_traded'] . '</td>';
            $html .= '</tr>';
        }
        //summary
        $html .= '<tr class="text-blue">';
        $html .= '<td>' . $index . '</td>';
        $html .= '<td>Total</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td>0</td>';
        $html .= '</tr>';


        $html .= '</tbody></table>';
        $html .= "You can add another {x} stocks to your portfolio";
        $html .= '</div>';
        return $html;
    }
}
