<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "stock".
 *
 * @property integer $id
 * @property string $symbol
 * @property string $name
 * @property double $last_sale
 * @property string $last_sale_text
 * @property double $market_cap
 * @property string $ipo_year_text
 * @property integer $ipo_year
 * @property string $sector
 * @property string $industry
 * @property string $summary_quote
 * @property string $exchange
 * @property string $country
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \app\models\Transaction[] $transactions
 */
class Stock extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['symbol','name'],'required'],
            [['last_sale','market_cap'],'number'],
            [['ipo_year'],'integer'],
            [['created_at','updated_at'],'safe'],
            [['symbol'],'string','max' => 12],
            [['name','industry'],'string','max' => 80],
            [['last_sale_text'],'string','max' => 30],
            [['ipo_year_text'],'string','max' => 20],
            [['sector'],'string','max' => 100],
            [['summary_quote'],'string','max' => 255],
            [['exchange'],'string','max' => 15],
            [['country'],'string','max' => 2],
            [['symbol'],'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'symbol' => 'Symbol',
            'name' => 'Name',
            'last_sale' => 'Last Sale',
            'last_sale_text' => 'Last Sale Text',
            'market_cap' => 'Market Cap',
            'ipo_year_text' => 'Ipo Year Text',
            'ipo_year' => 'Ipo Year',
            'sector' => 'Sector',
            'industry' => 'Industry',
            'summary_quote' => 'Summary Quote',
            'exchange' => 'Exchange',
            'country' => 'Country',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(\app\models\Transaction::className(),['stock_id' => 'id'])->inverseOf('stock');
    }

}
