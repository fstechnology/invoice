<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'subject',
            [
                'attribute' => 'from',
                'value' => function ($model) {
                    return $model->shippingAddressFrom->name;
                }
            ],
            [
                'attribute' => 'for',
                'value' => function ($model) {
                    return $model->shippingAddressFor->name;
                }
            ],
            'issue_date',
            'due_date',
            [
                'attribute' => 'payment_status',
                'value' => function ($model) {
                    switch ($model->payment_status) {
                        case 1:
                            return "UNPAID";
                        case 2:
                            return "PAID";
                        default:
                            return "UNKNOWN";
                    }
                }
            ],
            //'sub_total',
            //'tax_amount',
            //'total_amount',
            //'payment',
            //'amount_due',
            //'payment_status',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
