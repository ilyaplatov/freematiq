<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $created_at
 * @property int $user_id
 * @property int $from_account_id
 * @property int $to_account_id
 * @property float $amount
 *
 * @property User $user
 * @property Account $fromAccount
 * @property Account $toAccount
 */
class Payment extends ActiveRecord
{
    public $current_balance;

    public static function create($userId, $fromAccountId, $toAccountId, $amount): self
    {
        $payment = new self();
        $payment->created_at = time();
        $payment->user_id = $userId;
        $payment->from_account_id = $fromAccountId;
        $payment->to_account_id = $toAccountId;
        $payment->amount = $amount;
        return $payment;
    }

    public function isOutFor($accountId)
    {
        return $this->fromAccount && $this->fromAccount->id == $accountId;
    }

    public function isInFor($accountId)
    {
        return $this->toAccount->id == $accountId;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getFromAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'from_account_id']);
    }

    public function getToAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'to_account_id']);
    }

    public static function tableName(): string
    {
        return '{{%payment}}';
    }
}