<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\PaymentsSearchForm */
/* @var $user common\models\User */

use common\models\Payment;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <h1>Мой аккаунт</h1>

    <p>Email: <?= $user->email ?></p>
    <p>Баланс: <?= Yii::$app->formatter->asCurrency($user->account->amount) ?></p>

    <p><?= Html::a('Перевести', ['pay'], ['class' => ['btn btn-primary']]) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'created_at:datetime',
            [
                'attribute' => 'account',
                'value' => function (Payment $payment) use ($user, $searchModel) {
                    $account = null;
                    if ($payment->isOutFor($user->account->id)) {
                        $account = $payment->toAccount;
                    }
                    if ($payment->isInFor($user->account->id)) {
                        $account = $payment->fromAccount;
                    }
                    if ($account) {
                        return Html::a(
                            Html::encode($account->user->email),
                            ['', $searchModel->formName() => ['account' => $account->id]]
                        );
                    }
                    return null;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'type',
                'filter' => $searchModel->getTypesList(),
                'value' => function (Payment $payment) use ($user) {
                    if ($payment->isOutFor($user->account->id)) {
                        return '-' . Yii::$app->formatter->asCurrency($payment->amount);
                    }
                    if ($payment->isInFor($user->account->id)) {
                        return '+' . Yii::$app->formatter->asCurrency($payment->amount);
                    }
                    return null;
                },
                'format' => 'raw',
            ],
            'current_balance',
        ]
    ]) ?>

</div>
