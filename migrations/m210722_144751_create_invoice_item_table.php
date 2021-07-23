<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice_item}}`.
 */
class m210722_144751_create_invoice_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice_item}}', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'unit_price' => $this->decimal(10,2)->notNull(),
            'quantity' => $this->float()->notNull(),
            'total_amount' => $this->decimal(10, 2)->notNull()
        ]);

        $this->createIndex(
            '{{%idx-invoice_item-invoice_id}}',
            '{{%invoice_item}}',
            'invoice_id'
        );

        // add foreign key for table `{{%invoice}}`
        $this->addForeignKey(
            '{{%fk-invoice_item-invoice_id}}',
            '{{%invoice_item}}',
            'invoice_id',
            '{{%invoice}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-invoice_item-item_id}}',
            '{{%invoice_item}}',
            'item_id'
        );

        // add foreign key for table `{{%invoice}}`
        $this->addForeignKey(
            '{{%fk-invoice_item-item_id}}',
            '{{%invoice_item}}',
            'item_id',
            '{{%item}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%invoice}}`
        $this->dropForeignKey(
            '{{%fk-invoice_item-invoice_id}}',
            '{{%invoice_item}}'
        );

        // drops index for column `invoice_id`
        $this->dropIndex(
            '{{%idx-invoice_item-invoice_id}}',
            '{{%invoice_item}}'
        );

        // drops foreign key for table `{{%item}}`
        $this->dropForeignKey(
            '{{%fk-invoice_item-item_id}}',
            '{{%invoice_item}}'
        );

        // drops index for column `item_id`
        $this->dropIndex(
            '{{%idx-invoice_item-item_id}}',
            '{{%invoice_item}}'
        );
        
        $this->dropTable('{{%invoice_item}}');
    }
}
