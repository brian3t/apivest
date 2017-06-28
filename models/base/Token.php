<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "token".
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $created_at
 * @property integer $type
 *
 * @property \app\models\User $user
 */
class Token extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'code', 'created_at', 'type'], 'required'],
            [['user_id', 'created_at', 'type'], 'integer'],
            [['code'], 'string', 'max' => 32],
            [['user_id', 'code', 'type'], 'unique', 'targetAttribute' => ['user_id', 'code', 'type'], 'message' => 'The combination of User ID, Code and Type has already been taken.']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'code' => 'Code',
            'type' => 'Type',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id'])->inverseOf('tokens');
    }
    }
