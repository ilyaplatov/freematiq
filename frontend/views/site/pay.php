<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PayForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Pay';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'pay-form']); ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'amount') ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'pay-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>