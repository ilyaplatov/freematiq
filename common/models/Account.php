<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property float $amount
 * @property int $version
 *
 * @property User $user
 */
class Account extends ActiveRecord
{
    public static function create($userId)
    {
        $account = new self();
        $account->id = $userId;
        $account->amount = 0;
        return $account;
    }

    public function enroll($amount)
    {
        if ($amount == 0) {
            throw new \DomainException('Amount must be > 0.');
        }
        $this->amount += $amount;
    }

    public function charge($amount)
    {
        if ($this->amount < $amount || $amount == 0) {
            throw new \DomainException('Unable to charge.');
        }
        $this->amount -= $amount;
    }

    public function getInboundPayments()
    {
        return $this->hasOne(Payment::class, ['to_account_id' => 'id']);
    }

    public function getOutboundPayments()
    {
        return $this->hasOne(Payment::class, ['from_account_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id'])->inverseOf('account');
    }

    public static function tableName(): string
    {
        return '{{%account}}';
    }

    public function optimisticLock(): string
    {
        return 'version';
    }
}