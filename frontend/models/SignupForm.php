<?php
namespace frontend\models;

use common\models\Account;
use SebastianBergmann\GlobalState\RuntimeException;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function signup(): void
    {
        $user = User::requestSignup($this->email, $this->password);

        \Yii::$app->db->transaction(function () use ($user) {

            if (!$user->save()) {
                throw new RuntimeException('User saving error');
            }

            $account = Account::create($user->id);

            if (!$account->save()) {
                throw new RuntimeException('Account saving error');
            }

            $auth = \Yii::$app->authManager;
            $auth->assign($auth->getRole('user'), $user->id);

            $sent = \Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'signup/confirm-html', 'text' => 'signup/confirm-text'],
                    ['user' => $user]
                )
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setTo($user->email)
                ->setSubject('Signup confirm for ' . \Yii::$app->name)
                ->send();

            if (!$sent) {
                throw new RuntimeException('Email sending error');
            }
        });
    }
}
