<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaction;

/**
 * app\models\TransactionSearch represents the model behind the search form about `app\models\Transaction`.
 */
 class TransactionSearch extends Transaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'stock_id', 'is_buying', 'qty_bought'], 'integer'],
            [['created_at'], 'safe'],
            [['unit_cost'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Transaction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'stock_id' => $this->stock_id,
            'is_buying' => $this->is_buying,
            'created_at' => $this->created_at,
            'qty_bought' => $this->qty_bought,
            'unit_cost' => $this->unit_cost,
        ]);
        if (Yii::$app->request->get('sort') !== 'created_at'){
            $query->orderBy(['created_at' => SORT_DESC]);
        }
        return $dataProvider;
    }
}
