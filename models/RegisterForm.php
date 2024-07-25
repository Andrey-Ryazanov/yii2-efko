<?php

namespace app\models;

use Yii;
use yii\base\Model;


class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirmpassword;

    public function rules()
    {
        return 
        [
            [['username', 'email', 'password','confirmpassword'], 'required', 'message' => '{attribute} не может быть пустым!'],
            ['username', 'match', 'pattern' => '/^[A-Za-zА-Яа-я]+$/u', 'message' => 'Имя может содержать только буквы A-z или А-я в любом регистре'],
            ['password', 'match', 'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', 'message' => 'Пароль должен содержать буквы A-z и минимум 1 цифру'],
            ['email', 'email', 'message' => 'Введите действительный адрес электронной почты!'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Введённая почта уже занята!'],
            ['confirmpassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Подтверждение пароля не совпадает!'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'email' => 'Эл.почта',
            'password' => 'Пароль',
            'confirmpassword' => 'Подтверждение пароля',
        ];
    }

    public function register()
    {
        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        
        if ($user->save()) 
        {
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('user');
            $auth->assign($authorRole, $user->getId());

            Yii::$app->session->setFlash('registrationSuccess', true); 
            Yii::$app->session->setFlash('email', $this->email); 
            Yii::$app->session->setFlash('password', $this->password); 
            return true;
        } 
        
        return false;
    }

}