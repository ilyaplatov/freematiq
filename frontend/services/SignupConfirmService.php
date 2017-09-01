<?php

namespace frontend\services;

use common\models\User;
use yii\base\InvalidParamException;

class SignupConfirmService
{
    public function confirm($token): ?User
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $user = User::findBySignupConfirmToken($token);
        if (!$user) {
            throw new InvalidParamException('Wrong password reset token.');
        }

        $user->confirmSignup();

        return $user->save(false) ? $user : null;
    }
}