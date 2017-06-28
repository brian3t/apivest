<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "point_history".
 *
 * @property integer $id
 * @property integer $transaction_id
 * @property string $open_date
 * @property string $open_price
 * @property string $created_at
 *
 * @property \app\models\Transaction $transaction
 */
class PointHistory extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'open_date'], 'required'],
            [['transaction_id'], 'integer'],
            [['open_date', 'created_at'], 'safe'],
            [['open_price'], 'number'],
            [['transaction_id', 'open_date'], 'unique', 'targetAttribute' => ['transaction_id', 'open_date'], 'message' => 'The combination of Transaction ID and Open Date has already been taken.']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'point_history';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_id' => 'Transaction ID',
            'open_date' => 'Open Date',
            'open_price' => 'Open Price',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(\app\models\Transaction::className(), ['id' => 'transaction_id'])->inverseOf('pointHistories');
    }
    }
