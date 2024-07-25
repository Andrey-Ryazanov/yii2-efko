<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user = false;

    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'message' =>  '{attribute} не может быть пустым!'],

            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' =>  'Эл.почта',
            'password' => 'Пароль',
        ];
    }

    public function loadRegistrationData()
    {
        $registrationSuccess = Yii::$app->session->getFlash('registrationSuccess');
        if ($registrationSuccess) 
        {
            $this->email = Yii::$app->session->getFlash('email');
            $this->password = Yii::$app->session->getFlash('password');
        }
    }

    public function createNoteAuthentification()
    {
        if (Yii::$app->user->identity) 
        {
            Yii::$app->session->setFlash('loginSuccess', true); 
            return true;
        } 

        return false;
    }    

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            $user = $this->getUserByEmail();
    
            if (!$user || !$this->validateHash($this->password, $user->password)) 
            {
                $this->addError($attribute, 'Неправильный логин или пароль');
            }
        }
    }

    private function validateHash($password, $hash)
    {
        return Yii::$app->security->validatePassword($password, $hash);
    }

    public function login()
    {
        $user = $this->getUserByEmail();
        if ($this->validate()) 
        {
            return Yii::$app->user->login($user); 
        }
        return false;
    }

    public function getUserByEmail() 
    {
        if ($this->_user === false) 
        {
            $this->_user = User::findByEmail($this->email); 
        }

        return $this->_user;
    }
}