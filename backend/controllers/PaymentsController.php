<?php

namespace backend\controllers;

use Yii;
use backend\models\search\PaymentSearch;
use yii\web\Controller;

/**
 * PaymentsController implements the CRUD actions for User model.
 */
class PaymentsController extends Controller
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
