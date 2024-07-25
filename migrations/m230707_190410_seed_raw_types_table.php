<?php

use yii\db\Migration;

/**
 * Class m230707_190410_seed_raw_types_table
 */
class m230707_190410_seed_raw_types_table extends Migration
{
    public function safeUp()
    {    
        $this->batchInsert('raw_types', ['name'], 
        [
            ['соя'],
            ['шрот'],
            ['жмых'],
        ]);
    }

    public function safeDown()
    {
        $this->delete('raw_types');
    }
}
