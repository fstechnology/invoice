<?php

use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = "Invoice: ".$model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<input type="button" class="btn btn-primary pull-right" value="Print" id="btn-print" />
<div class="invoice-view" id="print-area">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 pull-right">
                        <div class="form-group">
                            <?php
                            if ($model->payment_status == 2) {
                                echo "<button class=\"btn btn-success btn-lg\" disabled>PAID</button>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label" for="invoice-subject">Subject</label>
                        <input type="text" id="invoice-subject" class="form-control" value="<?= $model->subject?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" for="invoice-issue_date">Issue Date</label>
                        <input type="text" id="invoice-issue_date" class="form-control" value="<?= $model->issue_date?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="invoice-due_date">Due Date</label>
                        <input type="text" id="invoice-due_date" class="form-control" value="<?= $model->due_date?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="invoice-shipping_address_from">From</label>
                        <input type="text" id="invoice-shipping_address_from" class="form-control" value="<?= $model->shippingAddressFrom->name?>" disabled>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="invoice-shipping_address_from_detail">&nbsp;</label>
                            <textarea class="form-control" id="invoice-shipping_address_from_detail" rows="3" readonly>
                                <?=
                                    $addressFrom = $model->shippingAddressFrom->street_no." ".$model->shippingAddressFrom->street."\n"
                                        .$model->shippingAddressFrom->city." ".$model->shippingAddressFrom->postal_code."\n"
                                        .$model->shippingAddressFrom->country;

                                    echo $addressFrom;
                                ?>
                            </textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="invoice-shipping_address_for">For</label>
                        <input type="text" id="invoice-shipping_address_for" class="form-control" value="<?= $model->shippingAddressFor->name?>" disabled>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="invoice-shipping_address_for_detail">&nbsp;</label>
                            <textarea class="form-control" id="invoice-shipping_address_for_detail" rows="3" readonly>
                                <?=
                                $addressFrom = $model->shippingAddressFor->street_no." ".$model->shippingAddressFor->street."\n"
                                    .$model->shippingAddressFor->city." ".$model->shippingAddressFor->postal_code."\n"
                                    .$model->shippingAddressFor->country;

                                echo $addressFrom;
                                ?>
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Items</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="table" id="tbl-item">
                                    <thead>
                                    <tr>
                                        <th>Item Type</th>
                                        <th>Description</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbl-item-body">
                                        <?php foreach ($model->InvItems as $invItem) : ?>
                                            <tr>
                                                <td><?= $invItem['item_type'] ?></td>
                                                <td><?= $invItem['description'] ?></td>
                                                <td><?= $invItem['unit_price'] ?></td>
                                                <td><?= $invItem['quantity'] ?></td>
                                                <td><?= $invItem['total_amount'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 pull-right">
                        <div class="form-group">
                            <label class="control-label" for="invoice-sub_total">Sub Total</label>
                            <input type="text" id="invoice-sub_total" value="<?= $model->sub_total ?>" class="form-control" disabled />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="invoice-tax">Tax (10%)</label>
                            <input type="text" id="invoice-tax" value="<?= $model->tax_amount ?>" class="form-control" disabled />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="invoice-payments">Payments</label>
                            <input type="text" id="invoice-payments" value="<?= $model->payment ?>" class="form-control" disabled />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="invoice-amount_due">Amount Due</label>
                            <input type="text" id="invoice-amount_due" value="<?= $model->amount_due ?>" class="form-control" disabled />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php JSRegister::begin(); ?>
    <script>
        $(document).ready(function() {
            $('#btn-print').off('click').on('click', function() {
                let content = document.getElementById('print-area').innerHTML;
                let originalContent = document.body.innerHTML;
                document.body.innerHTML = content;
                window.print();
                document.body.innerHTML = originalContent;
            });
        });
    </script>
<?php JSRegister::end(); ?>