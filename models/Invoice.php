<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property string $issue_date
 * @property string $due_date
 * @property string $subject
 * @property int $shipping_address_from_id
 * @property int $shipping_address_for_id
 * @property float $sub_total
 * @property float $tax_amount
 * @property float $total_amount
 * @property float $payment
 * @property float $amount_due
 * @property int $payment_status
 * @property string $created_at
 *
 * @property ShippingAddress $shippingAddressFor
 * @property ShippingAddress $shippingAddressFrom
 * @property InvoiceItem[] $invoiceItems
 */
class Invoice extends \yii\db\ActiveRecord
{
    public static $PAYMENT_STATUS_UNPAID = 1;
    public static $PAYMENT_STATUS_PAID = 2;

    public $InvItems;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['issue_date', 'due_date', 'subject', 'shipping_address_from_id', 'shipping_address_for_id', 'sub_total', 'tax_amount', 'total_amount', 'payment', 'amount_due', 'payment_status', 'created_at'], 'required'],
            [['issue_date', 'due_date', 'created_at', 'InvItems', 'id'], 'safe'],
            [['shipping_address_from_id', 'shipping_address_for_id', 'payment_status'], 'integer'],
            [['sub_total', 'tax_amount', 'total_amount', 'payment', 'amount_due'], 'number'],
            [['subject'], 'string', 'max' => 255],
            [['shipping_address_for_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShippingAddress::className(), 'targetAttribute' => ['shipping_address_for_id' => 'id']],
            [['shipping_address_from_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShippingAddress::className(), 'targetAttribute' => ['shipping_address_from_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Invoice ID',
            'issue_date' => 'Issue Date',
            'due_date' => 'Due Date',
            'subject' => 'Subject',
            'shipping_address_from_id' => 'Shipping Address From ID',
            'shipping_address_for_id' => 'Shipping Address For ID',
            'sub_total' => 'Sub Total',
            'tax_amount' => 'Tax Amount',
            'total_amount' => 'Total Amount',
            'payment' => 'Payment',
            'amount_due' => 'Amount Due',
            'payment_status' => 'Payment Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[ShippingAddressFor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShippingAddressFor()
    {
        return $this->hasOne(ShippingAddress::className(), ['id' => 'shipping_address_for_id']);
    }

    /**
     * Gets query for [[ShippingAddressFrom]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShippingAddressFrom()
    {
        return $this->hasOne(ShippingAddress::className(), ['id' => 'shipping_address_from_id']);
    }

    /**
     * Gets query for [[InvoiceItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceItems()
    {
        return $this->hasMany(InvoiceItem::className(), ['invoice_id' => 'id']);
    }

    public function getInvoiceItemById() {
        $modelInvoiceItem = InvoiceItem::find()->joinWith('item')->joinWith('item.itemType')
            ->where(['invoice_id' => $this->id])->all();

        $this->InvItems = [];
        if ($modelInvoiceItem) {
            $count = 0;
            foreach ($modelInvoiceItem as $detail) {
                $this->InvItems[$count]['invoice_item_id'] = $detail->id;
                $this->InvItems[$count]['invoice_id'] = $detail->invoice_id;
                $this->InvItems[$count]['item_id'] = $detail->item_id;
                $this->InvItems[$count]['description'] = $detail->item->description;
                $this->InvItems[$count]['item_type'] = $detail->item->itemType->name;
                $this->InvItems[$count]['unit_price'] = $detail->unit_price;
                $this->InvItems[$count]['quantity'] = $detail->quantity;
                $this->InvItems[$count]['total_amount'] = $detail->total_amount;
                $count++;
            }
        }
    }
}
