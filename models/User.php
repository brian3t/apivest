<?php

namespace app\models;

/**
 * Class User
 * @package app\models
 *
 */
class User extends \app\models\base\User
{
    const INITIAL_MONEY = 1000;
    protected $portfolio;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // add field to scenarios
//        $scenarios['create'][] = 'field';
//        $scenarios['update'][] = 'field';
//        $scenarios['register'][] = 'field';
        return $scenarios;
    }

    /**
     * @return array Fields for REST API calls
     */
    public function fields()
    {
        return [
            'id',
            'username',
            'email',
            'last_login_at',
            'is_ai',
            'is_admin',
            'portfolio'=>function(){
                return $this->getPortfolio();
            }
        ];
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
        $this->portfolio = new Portfolio($this->id);
        return $this->portfolio;
    }

    public function portfolio_html($mode = 'short')
    {
        $portfolio = $this->getPortfolio();
        $html = '<div class="table-responsive no-padding">
<table class="table table-hover"><tbody>
<tr>
                                            <th>#</th>
                                            <th>Stock</th>
                                            <th>Company</th>
                                            <th>Value When Bought</th>
                                            <th>Real-time Value</th>
                                            <th>Change since last traded (Estimated profit)</th>
                                        </tr>';
        $index = 0;
        foreach ($portfolio->stocks as $stock_id => $stock_detail) {
            $index++;
            $txn = $stock_detail['transaction'];
            /** @var Transaction $txn */
            $html .= '<tr>';
            $html .= '<td>' . $index . '</td>';
            $html .= '<td>' . $txn->stock->symbol . '</td>';
            $html .= '<td>' . $txn->stock->name . '</td>';
            $html .= '<td>$' . money_format('%6.4n', ($txn->unit_cost)) . '</td>';
            $html .= '<td>$' . money_format('%6.4n', $txn->stock->real_time_value) . '</td>';
            $html .= '<td>' . $txn->getChange_since_last_traded() . '</td>';
            $html .= '</tr>';
        }
        //summary
        $html .= '<tr class="text-blue">';
        $html .= '<td>Average</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td>$'. $portfolio->average_unit_cost .'</td>';
        $html .= '<td>$'. $portfolio->average_real_time_value .'</td>';
        $html .= '<td>' . $portfolio->average_change_since_last_traded . '</td>';
        $html .= '</tr>';


        $html .= '</tbody></table>';
        $html .= "You can buy another " . (10 - count($portfolio->stocks)) . " stocks to your portfolio. Each person can own at most 10 stocks at a time.";
        $html .= "<br/>You have <b>" . $portfolio->total_points . "</b> points. Click on Buy/Sell Stocks for point history";
        return $html;
    }

    public function getIs_admin()
    {
        return true;
    }
}
