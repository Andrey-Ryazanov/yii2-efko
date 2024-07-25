<?php

use yii\db\Migration;

/**
 * Class m230718_202955_create_history_calculation
 */
class m230718_202955_create_history_calculation extends Migration
{

    public function safeUp()
    {
        $this->createTable('calculation_history', 
        [
            'id' => $this->primaryKey(11)->unsigned(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'month_name' => $this->string(10)->notNull(),
            'tonnage_value' => $this->tinyInteger()->unsigned()->notNull(),
            'raw_type_name' => $this->string(10)->notNull(),
            'price' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->addForeignKey(
            'fk_calculation_history_users', 
            'calculation_history', 
            'user_id', 
            'users', 
            'id', 
            'CASCADE', 
            'NO ACTION'
        );
    } 

    public function safeDown()
    {
        $this->dropForeignKey('fk_calculation_history_users', 'calculation_history');

        $this->dropTable('calculation_history');
    }
}
