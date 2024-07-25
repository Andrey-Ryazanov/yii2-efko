<?php

use yii\db\Migration;

/**
 * Class m230707_190228_seed_tonnages_table
 */
class m230707_190228_seed_tonnages_table extends Migration
{
    public function safeUp()
    {    
        $this->batchInsert('tonnages', ['value'], 
        [
            ['25'],
            ['50'],
            ['75'],
            ['100'],
        ]);
    }

    public function safeDown()
    {
        $this->delete('tonnages');
    }
}
