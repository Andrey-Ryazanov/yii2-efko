<?php

use yii\db\Migration;

/**
 * Class m230707_184854_seed_months_table
 */
class m230707_184854_seed_months_table extends Migration
{
    public function safeUp()
    {   
        $this->batchInsert('months', ['name'], 
        [
            ['январь'],
            ['февраль'],
            ['август'],
            ['сентябрь'],
            ['октябрь'],
            ['ноябрь'],
        ]);
    }

    public function safeDown()
    {
        $this->delete('months');
    }
}