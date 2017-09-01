<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Payments', ['payments/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'created_at:datetime',
            [
                'attribute' => 'email',
                'value' => function (User $user) {
                    return Html::a(Html::encode($user->email), ['view', 'id' => $user->id]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'balance',
                'value' => 'account.amount',
                'format' => 'currency',
            ],
            'out_amount',
            'in_amount',
            [
                'attribute' => 'role',
                'value' => function (User $user) {
                    return implode(', ', ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($user->id), 'description'));
                },
            ],
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
