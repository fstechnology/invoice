<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_item".
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $item_id
 * @property float $unit_price
 * @property float $quantity
 * @property float $total_amount
 *
 * @property Invoice $invoice
 * @property Item $item
 */
class InvoiceItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'item_id', 'unit_price', 'quantity', 'total_amount'], 'required'],
            [['invoice_id', 'item_id'], 'integer'],
            [['unit_price', 'quantity', 'total_amount'], 'number'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'item_id' => 'Item ID',
            'unit_price' => 'Unit Price',
            'quantity' => 'Quantity',
            'total_amount' => 'Total Amount',
        ];
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
