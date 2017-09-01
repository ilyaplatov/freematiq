<?php

namespace backend\models\payment;

use common\validators\AmountValidator;
use yii\base\Model;

class EnrollForm extends Model
{
    public $amount;

    public function rules(): array
    {
        return [
            ['amount', 'required'],
            ['amount', 'integer', 'min' => 0],
            ['amount', AmountValidator::class],
        ];
    }
}