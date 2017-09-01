<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['site/signup-confirm', 'token' => $user->email_confirm_token]);
?>
<div class="password-reset">
    <p>Hello,</p>

    <p>Follow the link below to confirm your signup:</p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
