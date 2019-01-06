<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
<head>
    <link rel="stylesheet" href="<?php echo theme_url(); ?>admin/bootstrap/css/bootstrap.min.css">

</head>
<body <?php if ($this->input->get('print')){ ?>onload="print();" <?php } ?>
<!-- Main content -->
<section class="content">
    <?php if ($results) { ?>
        <?php foreach ($results as $delivery) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $delivery['delivery_method']; ?></h3>
                </div>
                <div class="panel-body">
                    <?php foreach ($delivery['addresses'] as $address) { ?>
                        <div class="row">
                            <div class="col-md-1 col-xs-1">
                                <a href="#" onclick="invoice_id = <?php echo $address['invoice']; ?>"
                                   class="btn btn-success btn-xs" data-toggle="modal" data-target="#change-status"
                                   title="Сменить статус"><i class="fa fa-edit"></i></a>
                            </div>
                            <div class="col-md-3 col-xs-3">
                                <b>Получатель</b><br>
                                <?php echo $address['telephone']; ?><br>
                                <?php echo $address['last_name']; ?> <?php echo $address['first_name']; ?> <br>
                                <?php echo $address['address']; ?><br>
                                Баланс в работе: <?php echo format_balance($this->customer_model->getWorkBalance($address['products'][0]['customer_id']));?>
                                <br><?php echo $address['products'][0]['comment'];?>
                            </div>
                            <div class="col-md-8 col-xs-8">
                                <table border="1px" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>Артикул</th>
                                        <th>Бренд</th>
                                        <th>Название</th>
                                        <th>Количество</th>
                                        <th>Цена</th>
                                        <th>Сумма</th>
                                    </tr>
                                    </thead>
                                    <?php $sub_total = 0; foreach ($address['products'] as $product){?>
                                        <tr>
                                            <td><?php echo $product['sku'];?></td>
                                            <td><?php echo $product['brand'];?></td>
                                            <td><?php echo $product['name'];?></td>
                                            <td><?php echo $product['quantity'];?></td>
                                            <td><?php echo $product['price'];?></td>
                                            <td><?php $sub_total += $product['quantity'] * $product['price']; echo $product['quantity'] * $product['price'];?></td>
                                        </tr>
                                    <? } ?>
                                    <tr>
                                        <td colspan="5"></td>
                                        <td><b><?php echo $sub_total;?></b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <hr>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <a class="btn btn-default pull-right" href="/autoxadmin/waybill?print=1" target="_blank">Печать</a>


</section><!-- /.content -->
<div class="modal" id="change-status" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title">Провести расходную накладную</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo form_open('', ['id' => 'change_status']); ?>
                        <div class="form-group">
                            <label>Статус</label>
                            <select required name="status_id" class="form-control" onchange="checkVal($(this).val());">
                                <option></option>
                                <?php foreach ($invoice_statuses as $status_id => $status_name) { ?>
                                    <option <?php if ($status_id == 1){ ?>selected<?php } ?>
                                            value="<?php echo $status_id; ?>"><?php echo $status_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="product_status_id">
                            <div class="form-group">
                                <label>Статус деталей</label>
                                <select name="product_status_id" class="form-control">
                                    <?php foreach ($order_statuses as $status) { ?>
                                        <option <?php if ($status['is_complete']){ ?>selected<?php } ?>
                                                value="<?php echo $status['id']; ?>"><?php echo $status['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>ТТН</label>
                                <input type="text" name="ttn" class="form-control">
                            </div>
                        </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                <button type="submit" form="change_status" class="btn btn-primary">Применить</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    var invoice_id = 0;

    $(document).ready(function () {
        $("#change_status").submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '/autoxadmin/invoice/change_status?id=' + invoice_id,
                method: 'post',
                data: $(this).serialize(),
                success: function (response) {
                    location.reload();
                }
            })
        });
    });

    function checkVal(status_id) {
        if (status_id == 1) {
            $('#product_status_id').show();
        } else {
            $('#product_status_id').hide();
        }
    }
</script>
</body>
</html>
