<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Tonnage extends ActiveRecord {

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
        return "{{%tonnages}}";
    }

    public function rules(){

    }

    public function attributeLabels(){
        
    }

    public function getPrices()
    {
        return $this->hasMany(Price::class, ['tonnage_id' => 'id']);
    }
}