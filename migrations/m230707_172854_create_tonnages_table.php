<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tonnages}}`.
 */
class m230707_172854_create_tonnages_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('tonnages', 
        [
            'id' => $this->primaryKey(11)->unsigned(),
            'value' => $this->tinyInteger()->unsigned()->unique()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()')->notNull()->append('ON UPDATE CURRENT_TIMESTAMP()')
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('tonnages');
    }
}
