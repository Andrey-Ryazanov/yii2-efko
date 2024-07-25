<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Price extends ActiveRecord {
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
        return "{{%prices}}";
    }

    public function rules(){

    }

    public function attributeLabels(){
        
    }

    public function getMonth()
    {
        return $this->hasOne(Month::class, ['id' => 'month_id']);
    }

    public function getTonnage()
    {
        return $this->hasOne(Tonnage::class, ['id' => 'tonnage_id']);
    }

    public function getRawType()
    {
        return $this->hasOne(RawType::class, ['id' => 'raw_type_id']);
    }
}