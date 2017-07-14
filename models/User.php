<?php

namespace app\models;

/**
 * Class User
 * @package app\models
 *
 * @property Portfolio $portfolio
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

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->portfolio = new Portfolio($this->id);
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
        foreach ($portfolio->stocks as $stock) {
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
        $html .= "You can buy another ". (10 - count($portfolio['stocks'])) ." stocks to your portfolio. Each person can own at most 10 stocks at a time.";
        $html .= '</div>';
        return $html;
    }

    public function getIs_admin()
    {
        return true;
    }
}
