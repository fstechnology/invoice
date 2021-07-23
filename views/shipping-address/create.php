<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ShippingAddress */

$this->title = 'Create Shipping Address';
$this->params['breadcrumbs'][] = ['label' => 'Shipping Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipping-address-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
