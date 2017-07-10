<?php

namespace app\models;

use Yii;
use \app\models\base\Stock as BaseStock;

/**
 * This is the model class for table "stock".
 *
 * @property int $real_time_value
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

    /**
     * Input: stock symbol
     * @return int Real time stock value
     * 07 09: get from YQL
     */
    public function getReal_time_value()
    {
        $time_since_last_updated = date_diff(new \DateTime($this->updated_at), new \DateTime());
        if ($time_since_last_updated->d == 0 && $time_since_last_updated->h == 0 && $time_since_last_updated->m < 15) {
            return $this->last_sale;
        }
        $ch = curl_init();

// set URL and other appropriate options
        $query = curl_escape($ch, 'SELECT * FROM yahoo.finance.quotes WHERE symbol IN ("' . $this->symbol . '")');
        curl_setopt($ch, CURLOPT_URL, "http://query.yahooapis.com/v1/public/yql?q={$query}&format=json&env=store://datatables.org/alltableswithkeys");
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);
        curl_close($ch);

        try {
            $res_array = json_decode($res);
        } catch (\Exception $e) {
            Yii::info("YQL failed: " . $e->getMessage());
            return $this->last_sale;
        }
        if (isset($res_array['query'])&& isset($res_array['query']['results'])&& isset($res_array['query']['results']['quote'])&& isset($res_array['query']['results']['quote']['symbol'])){
            $this->full_info = json_encode($res_array['query']['results']['quote']);
            $this->last_sale = $res_array['query']['results']['quote']['Ask'];
            $this->save();
        }
        return $this->last_sale;
    }

    public function getYahoo_finance_url()
    {
        return "https://finance.yahoo.com/quote/" . $this->symbol . "?p=" . $this->symbol . "&output=embed";
    }
}