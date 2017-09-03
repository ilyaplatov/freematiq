<?php

namespace common\validators;

use yii\validators\RegularExpressionValidator;

class AmountValidator extends RegularExpressionValidator
{
    public $pattern = '/^(?!0|\.00)[0-9]+(,\d{3})*(.[0-9]{0,2})$/';
    public $message = 'Allowed only 0000 or 0000.00 format.';
}