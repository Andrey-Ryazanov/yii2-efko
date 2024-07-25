<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class CalculationHistory extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
        ];
    }

    public static function tableName()
    {
        return "{{%calculation_history}}";
    }

    public function rules()
    {
        return [
            [['created_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID расчета',
            'user_id' => 'ID пользователя',
            'month_name' => 'Месяц',
            'tonnage_value' => 'Тоннаж',
            'raw_type_name' => 'Тип сырья',
            'price' => 'Цена',
            'created_at' => 'Дата создания',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    public function deleteWithSnapshots()
    {
        CalculationSnapshot::deleteAll(['calculation_history_id' => $this->id]);
        $this->delete();
    }
}
