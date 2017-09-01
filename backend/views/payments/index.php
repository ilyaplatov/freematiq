<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\PaymentSearch */
/* @var $user common\models\User */

use common\models\Payment;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Users', ['users/index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'created_at:datetime',
            [
                'attribute' => 'user_id',
                'value' => 'user.email',
            ],
            [
                'attribute' => 'from_account_id',
                'value' => 'fromAccount.user.email',
            ],
            [
                'attribute' => 'to_account_id',
                'value' => 'toAccount.user.email',
            ],
            'amount:currency',
        ]
    ]) ?>

</div>
