<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "transaction".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $stock_id
 * @property integer $is_buying
 * @property string $created_at
 * @property integer $qty_bought
 * @property string $unit_cost
 *
 * @property \app\models\PointHistory[] $pointHistories
 * @property \app\models\User $user
 * @property \app\models\Stock $stock
 */
class Transaction extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'stock_id', 'unit_cost'], 'required'],
            [['user_id', 'stock_id', 'is_buying', 'qty_bought'], 'integer'],
            [['created_at'], 'safe'],
            [['unit_cost'], 'number'],
            [['user_id', 'stock_id', 'created_at'], 'unique', 'targetAttribute' => ['user_id', 'stock_id', 'created_at'], 'message' => 'The combination of User ID, Stock ID and Created At has already been taken.']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'stock_id' => 'Stock ID',
            'is_buying' => 'Is Buying',
            'qty_bought' => 'Qty Bought',
            'unit_cost' => 'Unit Cost',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPointHistories()
    {
        return $this->hasMany(\app\models\PointHistory::className(), ['transaction_id' => 'id'])->inverseOf('transaction');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id'])->inverseOf('transactions');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(\app\models\Stock::className(), ['id' => 'stock_id'])->inverseOf('transactions');
    }
    }
