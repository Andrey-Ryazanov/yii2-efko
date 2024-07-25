<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%raw_types}}`.
 */
class m230707_173124_create_raw_types_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('raw_types',
        [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(10)->unique()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()')->notNull()->append('ON UPDATE CURRENT_TIMESTAMP()')
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('raw_types');
    }
}
