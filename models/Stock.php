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
        $time_since_last_updated = date_diff(new \DateTime($this->updated_at),new \DateTime());
        if($time_since_last_updated->d==0 && $time_since_last_updated->h==0 && $time_since_last_updated->m < 15)
        {
            return $this->last_sale;
        }
        $ch = curl_init();

// set URL and other appropriate options
        $query = curl_escape($ch,'SELECT * FROM yahoo.finance.quotes WHERE symbol IN ("' . $this->symbol . '")');
        curl_setopt($ch,CURLOPT_URL,"http://query.yahooapis.com/v1/public/yql?q={$query}&format=json&env=store://datatables.org/alltableswithkeys");
        curl_setopt($ch,CURLOPT_HTTPGET,true);
        curl_setopt($ch,CURLOPT_AUTOREFERER,true);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
 
//        curl_setopt($ch,CURLOPT_HTTPHEADER,
//            array('Content-Type:application/json'));
//        ,
//                'Content-Length: ' . strlen($data_string))
//        );
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $res = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        var_dump($res);
        curl_close($ch);
        return 1;
    }

    public function getYahoo_finance_url()
    {
        return "https://finance.yahoo.com/quote/" . $this->symbol . "?p=" . $this->symbol . "&output=embed";
    }
}