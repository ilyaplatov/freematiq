<?php
namespace backend\models\user;

use SebastianBergmann\GlobalState\RuntimeException;
use yii\base\Model;
use common\models\User;
use yii\helpers\ArrayHelper;

class UserEditForm extends Model
{
    public $email;
    public $password;
    public $role;

    private $_user;

    public function __construct(User $user, array $config = [])
    {
        $this->email = $user->email;

        $roles = \Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;

        $this->_user = $user;

        parent::__construct($config);
    }

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
            ['email', 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id], 'message' => 'This email address has already been taken.'],

            ['password', 'string', 'min' => 6],

            ['role', 'required'],
            ['role', 'in', 'range' => array_keys($this->getRolesList())],
        ];
    }

    public function getRolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    public function edit(): void
    {
        $user = $this->_user;

        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->setPassword($this->password);
        }

        \Yii::$app->db->transaction(function () use ($user) {
            if (!$user->save()) {
                throw new RuntimeException('User saving error');
            }
            $auth = \Yii::$app->authManager;
            $auth->revokeAll($user->id);
            $auth->assign($auth->getRole($this->role), $user->id);
        });
    }
}
