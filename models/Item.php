<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property int $item_type_id
 * @property string $description
 * @property float $price
 *
 * @property ItemType $itemType
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_type_id', 'description', 'price'], 'required'],
            [['item_type_id'], 'integer'],
            [['price'], 'number'],
            [['description'], 'string', 'max' => 255],
            [['item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemType::className(), 'targetAttribute' => ['item_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_type_id' => 'Item Type ID',
            'description' => 'Description',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[ItemType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemType()
    {
        return $this->hasOne(ItemType::className(), ['id' => 'item_type_id']);
    }

    public function getAll()
    {
        return Item::find()->joinWith('itemType')->all();
    }
}
