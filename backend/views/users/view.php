<?php

use common\models\Payment;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\UserPaymentSearchForm */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Enroll', ['enroll', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Pay', ['pay', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            'status',
            'created_at:datetime',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'created_at:datetime',
            [
                'attribute' => 'account',
                'value' => function (Payment $payment) use ($model, $searchModel) {
                    $account = null;
                    if ($payment->isOutFor($model->account->id)) {
                        $account = $payment->toAccount;
                    }
                    if ($payment->isInFor($model->account->id)) {
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
                'value' => function (Payment $payment) use ($model) {
                    if ($payment->isOutFor($model->account->id)) {
                        return '-' . Yii::$app->formatter->asCurrency($payment->amount);
                    }
                    if ($payment->isInFor($model->account->id)) {
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
