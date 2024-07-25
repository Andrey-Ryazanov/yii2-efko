<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%months}}`.
 */
class m230707_172223_create_months_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('months', 
        [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(10)->unique()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()')->notNull()->append('ON UPDATE CURRENT_TIMESTAMP()')
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('months');
    }
}
