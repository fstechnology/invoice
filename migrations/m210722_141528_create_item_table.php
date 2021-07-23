<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item}}`.
 */
class m210722_141528_create_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'item_type_id' => $this->integer()->notNull(),
            'description' => $this->string()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
        ]);

        // creates index for column `item_type_id`
        $this->createIndex(
            '{{%idx-item-item_type_id}}',
            '{{%item}}',
            'item_type_id'
        );

        // add foreign key for table `{{%item_type}}`
        $this->addForeignKey(
            '{{%fk-item-item_type_id}}',
            '{{%item}}',
            'item_type_id',
            '{{%item_type}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%item_type}}`
        $this->dropForeignKey(
            '{{%fk-item-item_type_id}}',
            '{{%item}}'
        );

        // drops index for column `item_type_id`
        $this->dropIndex(
            '{{%idx-item-item_type_id}}',
            '{{%item}}'
        );

        $this->dropTable('{{%item}}');
    }
}
