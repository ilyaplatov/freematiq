<?php

namespace common\services;

use common\models\Payment;
use common\models\User;

class PaymentService
{
    public function enroll($userId, $recipientId, $amount): void
    {
        $recipient = $this->findUser($recipientId);

        \Yii::$app->db->transaction(function () use ($recipient, $userId, $amount) {

            $recipient->account->enroll($amount);
            $payment = Payment::create($userId, null, $recipient->id, $amount);

            if (!$payment->save()) {
                throw new \RuntimeException('Payment saving error.');
            }

            if (!$recipient->account->save()) {
                throw new \RuntimeException('Account saving error.');
            }
        });
    }

    public function pay($userId, $payerId, $recipientEmail, $amount): void
    {
        $payer = $this->findUser($payerId);
        $recipient = $this->findUserByEmail($recipientEmail);

        if ($payer->id == $recipient->id) {
            throw new \DomainException('Unable to pay myself.');
        }

        \Yii::$app->db->transaction(function () use ($recipient, $payer, $userId, $amount) {

            $payer->account->charge($amount);
            $recipient->account->enroll($amount);
            $payment = Payment::create($userId, $payer->id, $recipient->id, $amount);

            if (!$payment->save()) {
                throw new \RuntimeException('Payment saving error.');
            }

            if (!$payer->account->save()) {
                throw new \RuntimeException('Payer account saving error.');
            }

            if (!$recipient->account->save()) {
                throw new \RuntimeException('Recipient account saving error.');
            }
        });
    }

    private function findUser($id): User
    {
        if (!$user = User::findOne($id)) {
            throw new \DomainException('User not found.');
        }
        return $user;
    }

    private function findUserByEmail($email): User
    {
        if (!$user = User::findOne(['email' => $email])) {
            throw new \DomainException('User not found.');
        }
        return $user;
    }
}