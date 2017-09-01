<?php

namespace backend\models\search;

use common\models\Payment;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PaymentSearch extends Model
{
    public $user_id;
    public $from_account_id;
    public $to_account_id;

    public function rules(): array
    {
        return [
            [['user_id', 'from_account_id', 'to_account_id'], 'safe'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Payment::find()
            ->with(['user', 'fromAccount', 'toAccount']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->andWhere('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'from_account_id' => $this->from_account_id,
            'to_account_id' => $this->to_account_id,
        ]);

        return $dataProvider;
    }

    public function formName(): string
    {
        return 's';
    }
}