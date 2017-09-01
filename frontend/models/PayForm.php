<?php

namespace frontend\models;

use common\models\User;
use common\validators\AmountValidator;
use yii\base\Model;

class PayForm extends Model
{
    public $email;
    public $amount;

    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::class],

            ['amount', 'required'],
            ['amount', 'integer', 'min' => 0],
            ['amount', AmountValidator::class],
        ];
    }
}