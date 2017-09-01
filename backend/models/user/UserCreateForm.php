<?php
namespace backend\models\user;

use common\models\Account;
use SebastianBergmann\GlobalState\RuntimeException;
use yii\base\Model;
use common\models\User;
use yii\helpers\ArrayHelper;

class UserCreateForm extends Model
{
    public $email;
    public $password;
    public $role;

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

            ['role', 'required'],
            ['role', 'in', 'range' => array_keys($this->getRolesList())],
        ];
    }

    public function getRolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    public function create(): User
    {
        $user = User::createByAdmin($this->email, $this->password);

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
        });

        return $user;
    }
}
