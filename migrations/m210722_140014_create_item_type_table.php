<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item_type}}`.
 */
class m210722_140014_create_item_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%item_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%item_type}}');
    }
}
