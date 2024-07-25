<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%prices}}`.
 */
class m230707_173161_create_prices_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('prices', 
        [
            'id' => $this->primaryKey(11)->unsigned(),
            'raw_type_id' => $this->integer(11)->unsigned()->notNull(),
            'tonnage_id' => $this->integer(11)->unsigned()->notNull(),
            'month_id' => $this->integer(11)->unsigned()->notNull(),
            'price' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex(
            'idx_prices_tonnage_raw_type_month', 
            'prices', 
            ['tonnage_id', 'raw_type_id', 'month_id'], 
            true
        );

        $this->addForeignKey(
            'fk_prices_tonnages', 
            'prices', 
            'tonnage_id', 
            'tonnages', 
            'id', 
            'CASCADE', 
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk_prices_months', 
            'prices', 
            'month_id', 
            'months', 
            'id', 
            'CASCADE',
            'NO ACTION' 
        );
        $this->addForeignKey(
            'fk_prices_raw_types', 
            'prices', 
            'raw_type_id', 
            'raw_types', 
            'id',
            'CASCADE', 
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_prices_tonnages', 'prices');
        $this->dropForeignKey('fk_prices_months', 'prices');
        $this->dropForeignKey('fk_prices_raw_types', 'prices');

        $this->dropIndex('idx_prices_tonnage_raw_type_month', 'prices');

        $this->dropTable('prices');
    }
}
