<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="invoice-form">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin([
            'id' => 'invoice-form',
        ]); ?>

        <div class="x_panel">
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        echo $form->field($model, 'issue_date')->widget(DatePicker::classname(), [
                            'value' => date('dd/mm/yyyy'),
                            'removeButton' => false,
                            'options' => ['placeholder' => 'Issue Date'],
                            'pluginOptions' => [
                                'format' => 'dd/mm/yyyy',
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ]
                        ])->label('Issue Date');
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        echo $form->field($model, 'due_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'Due date'],
                            'removeButton' => false,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd/mm/yyyy',
                            ]
                        ])->label('Due Date');
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, "shipping_address_from_id")->widget(Select2::classname(), [
                            'data' => $shippingAddress,
                            'options' => ['placeholder' => 'Select Shipping Address from'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'pluginEvents' => [
                                'change' => 'function(e){
                                    $("#shipping-address-from").val("");
                                    let shippingAddressFrom = e.currentTarget.value;
                                    if (shippingAddressFrom != "") {
                                        let url = "' . Url::to(['shipping-address/get-by-id']) . '&id=" + shippingAddressFrom;
                                        $.post(url,function(res){
                                            let fullAddressFrom = res.street_no + " " + res.street + "\n" + res.city + " " + res.postal_code + "\n" + res.country;
                                            $("#shipping-address-from").val(fullAddressFrom);
                                        });
                                    }
                                }'
                            ]
                        ])->label('From'); ?>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="shipping-address-from">&nbsp;</label>
                            <textarea class="form-control" id="shipping-address-from" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, "shipping_address_for_id")->widget(Select2::classname(), [
                            'data' => $shippingAddress,
                            'options' => ['placeholder' => 'Select Shipping Address for'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'pluginEvents' => [
                                'change' => 'function(e){
                                    $("#shipping-address-for").val("");
                                    let shippingAddressFor = e.currentTarget.value;
                                    if (shippingAddressFor != "") {
                                        let url = "' . Url::to(['shipping-address/get-by-id']) . '&id=" + shippingAddressFor;
                                        $.post(url,function(res){
                                            let fullAddressFor = res.street_no + " " + res.street + "\n" + res.city + " " + res.postal_code + "\n" + res.country;
                                            $("#shipping-address-for").val(fullAddressFor);
                                        });
                                    }
                                }'
                            ]
                        ])->label('For'); ?>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="shipping-address-for">&nbsp;</label>
                            <textarea class="form-control" id="shipping-address-for" rows="3" readonly></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Items <a class="btn btn-sm btn-primary" id="btn-add-item">Add Item</a></h2>
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
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbl-item-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 pull-right">
                        <?= $form->field($model, 'sub_total')->textInput(['type' => 'number', 'maxlength' => true, 'readonly' => 'true']) ?>
                        <?= $form->field($model, 'tax_amount')->textInput(['type' => 'number', 'maxlength' => true, 'readonly' => 'true']) ?>
                        <?= $form->field($model, 'payment')->textInput(['type' => 'number', 'step' => 'any', 'maxlength' => true]) ?>
                        <br />
                        <?= $form->field($model, 'amount_due')->textInput(['type' => 'number', 'maxlength' => true, 'readonly' => 'true']) ?>
                        <div class="col-md-3 pull-right">
                            <?= Html::submitButton('Update', ['class' => 'btn btn-success btn-lg']) ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php JSRegister::begin(); ?>
<script>
    let listItem = JSON.parse('<?php echo json_encode($model->InvItems)?>');

    let indexItem = 0;
    let selectItemOptions = "";

    function initSelectItemOptions() {
        $.ajax({
            url: '<?php echo Url::toRoute('item/get-all'); ?>',
            type: 'GET',
            cache: false,
            success: function(response) {
                selectItemOptions += `<option value="">Choose Item</option>`;
                $.each(response, function(i, item) {
                    selectItemOptions += `
                    <option value="${item.id}" data-price="${item.price}" data-itemtype="${item.item_type}">
                    ${item.description}
                    </option>`;
                });

                if (listItem != null && listItem.length > 0) {
                    for (let indexItem in listItem) {
                        let item = listItem[indexItem]
                        $("#tbl-item-body").append(generateFormItem(item, indexItem));
                        $('#slc-item-' + indexItem).val(item.item_id)
                        indexItem++;
                    }
                    CountAmount();
                }
            }
        });
    }

    function generateFormItem(item, indexItem) {
        return `
                <tr class="">
                    <td class=""><input class="form-control form-control-sm ipt-item-type" value="${(item == null) ? '' : item.item_type}" type="text" required readonly/></td>
                    <td class="">
                        <select class="form-control form-control-sm slc-item" id="slc-item-${indexItem}" name="Invoice[InvItems][${indexItem}][item_id]" required>
                            ${selectItemOptions}
                        </select>
                    </td>
                    <td class=""><input class="form-control form-control-sm ipt-price"  name="Invoice[InvItems][${indexItem}][unit_price]" placeholder="Unit Price" type="number" value="${(item == null) ? 0 : item.unit_price}" min="0" required readonly/></td>
                    <td class=""><input class="form-control form-control-sm ipt-quantity"  name="Invoice[InvItems][${indexItem}][quantity]" placeholder="Quantity" type="number" value="${(item == null) ? 1 : item.quantity}" min="1" required/></td>
                    <td class=""><input class="form-control form-control-sm ipt-total-amount"  name="Invoice[InvItems][${indexItem}][total_amount]" placeholder="Total Amount" type="number" value="${(item == null) ? 0 : item.total_amount}" min="0" required readonly/></td>
                    <td class=""><button class="btn btn-outline-danger btn-sm btn-delete" data-toggle="tooltip" data-placement="bottom" title="Delete Item">Delete</button></td>
                </tr>`;
    }

    function CountAmount() {
        let subTotal = 0;
        let taxAmount = 0;
        let totalAmount = 0;
        let payment = 0;
        let dueAmount = 0;

        $('#tbl-item tbody tr').each(function() {
            let quantity = $(this).find("td:eq(3) .ipt-quantity").val();
            let price = $(this).find("td:eq(2) .ipt-price").val();
            let totalAmountItem = quantity * price;
            $(this).find("td:eq(4) .ipt-total-amount").val(totalAmountItem);
            subTotal += totalAmountItem;
        });

        $("#invoice-sub_total").val(subTotal.toFixed(2))

        taxAmount = (subTotal * 10) / 100;
        $("#invoice-tax_amount").val(taxAmount.toFixed(2))

        totalAmount = subTotal + taxAmount;
        payment = parseFloat($("#invoice-payment").val())
        dueAmount = payment - totalAmount;
        $("#invoice-amount_due").val(dueAmount.toFixed(2))
    }

    $(document).ready(function () {
        initSelectItemOptions();
        $('#invoice-shipping_address_from_id').trigger('change');
        $('#invoice-shipping_address_for_id').trigger('change');
    })

    $("#btn-add-item").click(function () {
        indexItem += 1;
        $("#tbl-item-body").append(generateFormItem(null, indexItem))
        return false;
    })

    $("#tbl-item").on('change', '.slc-item', function(e) {
        let currentTarget = e.currentTarget;
        let price = currentTarget.options[currentTarget.selectedIndex].dataset.price;
        let itemType = currentTarget.options[currentTarget.selectedIndex].dataset.itemtype;
        $(this).closest('tr').find('.ipt-price').val(price);
        $(this).closest('tr').find('.ipt-item-type').val(itemType);
        $(this).closest('tr').find('.ipt-total-amount').val(price);
        CountAmount();
    }).on('change', '.ipt-quantity', function(e) {
        CountAmount();
    }).on('click', '.btn-delete', function(e) {
        $(this).closest('tr').remove();
        CountAmount();
    });

    $("#invoice-payment").on('change', function () {
        CountAmount();
    })
</script>
<?php JSRegister::end(); ?>
