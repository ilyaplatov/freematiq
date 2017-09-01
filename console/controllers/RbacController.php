<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionAssign($email, $roleName)
    {
        $auth = \Yii::$app->authManager;

        if (!$user = User::findOne(['email' => $email])) {
            $this->stderr('User not found.' . PHP_EOL);
            return;
        }

        if (!$role = $auth->getRole($roleName)) {
            $this->stderr('Role not found.' . PHP_EOL);
            return;
        }

        $auth->revokeAll($user->id);
        $auth->assign($role, $user->id);

        $this->stdout('Done!' . PHP_EOL);
    }
}