<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirmpassword;
    public $role;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['username', 'email', 'password', 'confirmpassword', 'role']; 
        $scenarios['update'] = ['username', 'email', 'role']; 
    
        return $scenarios;
    }

    public function rules()
    {
        return 
        [
            [['username', 'email', 'password', 'confirmpassword', 'role'], 'required', 'on' => 'create', 'message' => '{attribute} не может быть пустым!'],
            [['username', 'email', 'role'], 'required', 'on' => 'update', 'message' => '{attribute} не может быть пустым!'],

            ['username', 'match', 'pattern' => '/^[A-Za-zА-Яа-я]+$/u', 'message' => 'Имя может содержать только буквы A-z или А-я в любом регистре'],
            
            ['email', 'email', 'message' => 'Введите действительный адрес электронной почты!'],
            ['email', 'unique', 'targetClass' => User::class, 'except' => 'update', 'message' => 'Введённая почта уже занята!'],

            ['password', 'match', 'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/','on' => 'create', 'message' => 'Пароль должен содержать буквы A-z и минимум 1 цифру'],
            ['confirmpassword', 'compare', 'compareAttribute' => 'password', 'on' => 'create', 'message' => 'Подтверждение пароля не совпадает!'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'email' => 'Эл.почта',
            'password' => 'Пароль',
            'confirmpassword' => 'Подтверждение пароля',
            'role' => 'Роль',
        ];
    }

    public function loadUser($user)
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($user->id), 'name');
    }

    public function createUser()
    {
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        
        if ($user->save()) 
        {
            $roleName = $this->role;
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($roleName);
            $auth->assign($role, $user->id);
            return true;
        }
        
        return false;
    }

    public function updateUser($user)
    {
        $user->username = $this->username;
        $user->email = $this->email;
        
        if ($user->save()) 
        {
            Yii::$app->authManager->revokeAll($user->id);
            
            $roleName = $this->role;
            $role = Yii::$app->authManager->getRole($roleName);
            Yii::$app->authManager->assign($role, $user->id);

            $user->touch('updated_at');
            
            return true;
        }
        
        return false;
    }

    public function printErrors()
    {
        $errorMessages = 'Возникли следующие ошибки: <br>';
        $errorMessages .= '<ul>';
        $errorMessages .= '<li>' . implode('</li><li>', $this->getFirstErrors()) . '</li>';
        $errorMessages .= '</ul>';

        return $errorMessages;
    }

}