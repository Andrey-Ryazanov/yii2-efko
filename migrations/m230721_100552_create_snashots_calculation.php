<?php

use yii\db\Migration;

/**
 * Class m230721_100552_create_snashots_calculation
 */
class m230721_100552_create_snashots_calculation extends Migration
{

    public function safeUp()
    {
        $this->createTable('calculation_snapshots', 
        [
            'id' => $this->primaryKey(11)->unsigned(),
            'calculation_history_id' => $this->integer(11)->unsigned()->notNull(),
            'month_name' => $this->string(10)->notNull(),
            'tonnage_value' => $this->tinyInteger()->unsigned()->notNull(),
            'raw_type_name' => $this->string(10)->notNull(),
            'price' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->addForeignKey(
            'fk_calculation_snapshots_calculation_history', 
            'calculation_snapshots', 
            'calculation_history_id', 
            'calculation_history', 
            'id', 
            'CASCADE', 
            'NO ACTION'
        );
    } 

    public function safeDown()
    {
        $this->dropForeignKey('fk_calculation_snapshots_calculation_history', 'calculation_snapshots');

        $this->dropTable('calculation_snapshots');
    }
}
