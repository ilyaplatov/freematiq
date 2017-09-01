<?php

namespace common\validators;

use yii\validators\RegularExpressionValidator;

class AmountValidator extends RegularExpressionValidator
{
    public $pattern = '#\d+(\.\d{2})?#is';
    public $message = 'Allowed only 0000 or 0000.00 format.';
}