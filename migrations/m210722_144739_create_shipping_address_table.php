<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shipping_address}}`.
 */
class m210722_144739_create_shipping_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shipping_address}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'street' => $this->string()->notNull(),
            'street_no' => $this->string()->notNull(),
            'city' => $this->string()->notNull(),
            'postal_code' => $this->string()->notNull(),
            'country' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipping_address}}');
    }
}
