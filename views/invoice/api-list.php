<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'API List';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>API List</h1>
        <hr />
        <h2>Get All</h2>
        <?= Html::a(URL::toRoute(["invoice/get-all"]), ['get-all'], ['class' => 'btn btn-success btn-sm', 'target' => '_blank']) ?>
        <hr />
        <h2>Get By Id</h2>
        <?php
        foreach ($ids as $id) {
            echo "<div style='margin: 5px;'>";
            echo Html::a(URL::toRoute(["invoice/get-all", "id" => $id]), ['get-by-id', "id" => $id], ['class' => 'btn btn-success btn-sm', 'target' => '_blank']);
            echo "<br/></div>";
        }
        ?>
    </div>
</div>
