<?php

namespace backend\models\search;

use common\models\Payment;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserPaymentSearchForm extends Model
{
    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';

    public $type;
    public $account;

    public function rules(): array
    {
        return [
            [['type', 'account'], 'safe'],
            ['type', 'in', 'range' => array_keys($this->getTypesList())],
        ];
    }

    public function search(int $accountId, array $params): ActiveDataProvider
    {
        $query = Payment::find()
            ->select([
                '*',
                'SUM(case when from_account_id=' . $accountId . ' then -amount else amount end) OVER (ORDER BY id) as current_balance',
            ])
            ->andWhere([
                'or',
                ['from_account_id' => $accountId],
                ['to_account_id' => $accountId]
            ])
        ->with(['fromAccount', 'toAccount']);

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

        if (!empty($this->account)) {
            $query->andWhere([
                'or',
                ['from_account_id' => $this->account],
                ['to_account_id' => $this->account]
            ]);
        }

        if (!empty($this->type)) {
            if ($this->type == self::TYPE_IN) {
                $query->andWhere(['to_account_id' => $accountId]);
            } elseif ($this->type == self::TYPE_OUT) {
                $query->andWhere(['from_account_id' => $accountId]);
            }
        }

        return $dataProvider;
    }

    public function getTypesList(): array
    {
        return [
            self::TYPE_IN => 'Поступления',
            self::TYPE_OUT => 'Списания',
        ];
    }

    public function formName(): string
    {
        return 's';
    }
}