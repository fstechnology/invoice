<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice}}`.
 */
class m210722_144744_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'issue_date' => $this->date()->notNull(),
            'due_date' => $this->date()->notNull(),
            'subject' => $this->string()->notNull(),
            'shipping_address_from_id' => $this->integer()->notNull(),
            'shipping_address_for_id' => $this->integer()->notNull(),
            'sub_total' => $this->decimal(10,2)->notNull(),
            'tax_amount' => $this->decimal(10,2)->notNull(),
            'total_amount' => $this->decimal(10,2)->notNull(),
            'payment' => $this->decimal(10,2)->notNull(),
            'amount_due'  => $this->decimal(10,2)->notNull(),
            'payment_status' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        // creates index for column `shipping_address_from_id`
        $this->createIndex(
            '{{%idx-invoice-shipping_address_from_id}}',
            '{{%invoice}}',
            'shipping_address_from_id'
        );

        // add foreign key for table `{{%shipping_address}}`
        $this->addForeignKey(
            '{{%fk-invoice-shipping_address_from_id}}',
            '{{%invoice}}',
            'shipping_address_from_id',
            '{{%shipping_address}}',
            'id',
            'CASCADE'
        );

        // creates index for column `shipping_address_for_id`
        $this->createIndex(
            '{{%idx-invoice-shipping_address_for_id}}',
            '{{%invoice}}',
            'shipping_address_for_id'
        );

        // add foreign key for table `{{%shipping_address}}`
        $this->addForeignKey(
            '{{%fk-invoice-shipping_address_for_id}}',
            '{{%invoice}}',
            'shipping_address_for_id',
            '{{%shipping_address}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%shipping_address}}`
        $this->dropForeignKey(
            '{{%fk-invoice-shipping_address_from_id}}',
            '{{%invoice}}'
        );

        // drops index for column `shipping_address_from_id`
        $this->dropIndex(
            '{{%idx-invoice-shipping_address_from_id}}',
            '{{%invoice}}'
        );

        // drops foreign key for table `{{%shipping_address}}`
        $this->dropForeignKey(
            '{{%fk-invoice-shipping_address_for_id}}',
            '{{%invoice}}'
        );

        // drops index for column `shipping_address_for_id`
        $this->dropIndex(
            '{{%idx-invoice-shipping_address_for_id}}',
            '{{%invoice}}'
        );

        $this->dropTable('{{%invoice}}');
    }
}
