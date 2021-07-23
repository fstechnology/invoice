<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form of `app\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    public $shippingAddressFromName;
    public $shippingAddressForName;
    public $paymentStatus;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shipping_address_from_id', 'shipping_address_for_id', 'payment_status'], 'integer'],
            [['issue_date', 'due_date', 'subject', 'created_at', 'shippingAddressFromName', 'shippingAddressForName', 'paymentStatus'], 'safe'],
            [['sub_total', 'tax_amount', 'total_amount', 'payment', 'amount_due'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Invoice::find();
        $query->leftJoin('shipping_address AS sa_from', 'sa_from.id = invoice.shipping_address_from_id')
            ->leftJoin('shipping_address AS sa_for', 'sa_for.id = invoice.shipping_address_for_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'shipping_address_from_id' => $this->shipping_address_from_id,
            'shipping_address_for_id' => $this->shipping_address_for_id,
            'sub_total' => $this->sub_total,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'payment' => $this->payment,
            'amount_due' => $this->amount_due,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'sa_from.name', $this->shippingAddressFromName])
            ->andFilterWhere(['like', 'sa_for.name', $this->shippingAddressForName]);

        return $dataProvider;
    }
}
