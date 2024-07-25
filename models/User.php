<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class User extends ActiveRecord implements IdentityInterface
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
        ];
    }

    public static function tableName(){
        return "{{%users}}";
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя',
            'email' => 'Почта',
            'password' => 'Пароль',
            'created_at' => 'Дата создания',
            'updated_at'=> 'Дата обновления',
        ];
    }
    
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function deleteWithRelatedData()
{
    Yii::$app->authManager->revokeAll($this->id);
    $calculationHistory = CalculationHistory::find()->where(['user_id' => $this->id])->all();
    if ($calculationHistory) 
    {
        foreach ($calculationHistory as $history) {
            CalculationSnapshot::deleteAll(['calculation_history_id' => $history->id]);
            $history->delete();
        }      
    } 
    $this->delete();
}
}