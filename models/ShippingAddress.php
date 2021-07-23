<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "shipping_address".
 *
 * @property int $id
 * @property string $name
 * @property string $street
 * @property string $street_no
 * @property string $city
 * @property string $postal_code
 * @property string $country
 *
 * @property Invoice[] $invoices
 * @property Invoice[] $invoices0
 */
class ShippingAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipping_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'street', 'street_no', 'city', 'postal_code', 'country'], 'required'],
            [['name', 'street', 'street_no', 'city', 'postal_code', 'country'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'street' => 'Street',
            'street_no' => 'Street No',
            'city' => 'City',
            'postal_code' => 'Postal Code',
            'country' => 'Country',
        ];
    }

    /**
     * Gets query for [[Invoices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['shipping_address_for_id' => 'id']);
    }

    /**
     * Gets query for [[Invoices0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices0()
    {
        return $this->hasMany(Invoice::className(), ['shipping_address_from_id' => 'id']);
    }

    public function getAll()
    {
        $shippingAddress = ShippingAddress::find()->all();
        return ArrayHelper::map($shippingAddress, 'id', 'name');
    }
}
