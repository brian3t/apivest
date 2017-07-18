<?php

namespace app\commands;

use yii\console\Controller;
use yii\db\Exception;

/**
 * Hermes the messenger, the trade expert. Deals with outside data
 *
 */
class HermesController extends Controller
{
    /**
     * @inheritdoc
     * Pulls nasdaq data into stock table
     */
    public function actionPullNasdaq()
    {
        echo "Pulling";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://www.nasdaq.com/screening/companies-by-industry.aspx?exchange=NASDAQ&render=download");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $company_list = curl_exec($ch);
        $company_list_lines = explode(PHP_EOL, $company_list);
        $symbols = [];
        foreach ($company_list_lines as $company_list_line) {
            $symbols[] = str_getcsv($company_list_line);
        }
        curl_close($ch);
        //symbols ["Symbol","Name","LastSale","MarketCap","ADR TSO","IPOyear","Sector","Industry","Summary Quote"]

        $db = \Yii::$app->db;
        $symbol = $name = '';
        $last_sale = $market_cap = $ipo_year = $adr_tso = null;
        $sector = $industry = $summary_quote = '';
        $db_command = $db->createCommand("REPLACE INTO vest.stock(symbol, `name`, last_sale, market_cap, ipo_year, sector, industry, summary_quote, country) 
VALUES (:symbol, :name, :last_sale, :market_cap,:ipo_year,:sector,:industry,:summary_quote,'us')")
            ->bindValues([':symbol' => $symbol, ':name' => $name, ':last_sale' => $last_sale, ':market_cap' => $market_cap, ':ipo_year' => $ipo_year, ':sector' => $sector, ':industry' => $industry, ':summary_quote' => $summary_quote]);
        $headers = array_shift($symbols);
        foreach ($symbols as $s){
            list($symbol,$name,$last_sale,$market_cap,$adr_tso,$ipo_year,$sector,$industry,$summary_quote) = $s;
            try {
                $result = $db_command->query();
                var_dump($result);
                how to do bindvalues?
                $a=1;
            } catch (Exception $exception){
                \Yii::error("Cant pull nasdaq: " . $exception->getMessage());
            }
        }
    }
}
