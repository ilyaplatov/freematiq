<?php

namespace backend\models\search;

use common\models\Account;
use common\models\Payment;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
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
    public function search($params): ActiveDataProvider
    {
        $query = User::find()
            ->alias('u')
            /*->select([
                'u.*',
                'SUM(case when p.from_account_id=a.id then p.amount else 0 end) OVER (ORDER BY p.id) as out_amount',
                'SUM(case when p.from_account_id=a.id then 0 else p.amount end) OVER (ORDER BY p.id) as in_amount',
            ])
            ->leftJoin(Account::tableName() . ' a', 'a.id = u.id')
            ->leftJoin(Payment::tableName() . ' p', 'p.from_account_id = a.id OR p.to_account_id = a.id')
            ->groupBy('u.id')*/
        ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'u.id' => $this->id,
            'u.status' => $this->status,
            'u.created_at' => $this->created_at,
        ]);

        $query
            ->andFilterWhere(['like', 'u.email', $this->email]);

        return $dataProvider;
    }
}
