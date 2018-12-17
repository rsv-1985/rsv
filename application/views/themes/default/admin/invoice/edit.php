<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home'); ?></a></li>
        <li><a href="/autoxadmin/invoice">Расходные накладные</a></li>
        <li><a href="#"><?php echo $invoice_info['id']; ?></a></li>
    </ol>
</section>
<?php echo form_open(); ?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <h3>Инвойс: <?php echo $invoice_info['id']; ?>
                        <small class="pull-right">Клиент: <a
                                    href="/autoxadmin/customer/edit/<?php echo $customer_info['id']; ?>"><?php echo $customer_info['first_name']. ' ' .$customer_info['second_name']; ?> <?php echo format_balance($customer_info['balance']); ?></a>
                        </small>
                    </h3>
                    <?php if ($products) { ?>
                        <table class="table table-condensed table-striped">
                            <thead>
                            <tr>
                                <th>Заказ</th>
                                <th>Артикул</th>
                                <th>Бренд</th>
                                <th>Название</th>
                                <th>Статус</th>
                                <th>Количество</th>
                                <th>Цена</th>
                                <th>Итого</th>
                                <th></th>
                            </tr>
                            </thead>
                            <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td>
                                        <a href="/autoxadmin/order/edit/<?php echo $product['order_id']; ?>">
                                            <?php echo $product['order_id']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $product['sku']; ?></td>
                                    <td><?php echo $product['brand']; ?></td>
                                    <td><?php echo $product['name']; ?></td>
                                    <td><?php echo $product_statuses[$product['status_id']]['name'];?></td>
                                    <td>
                                        <input max="<?php echo $product['quantity'];?>"
                                               name="products[<?php echo $product['order_product_id'];?>][qty]"
                                               style="width: 110px"
                                               type="number"
                                               value="<?php echo $product['iqty']; ?>"
                                               class="form-control">
                                    </td>
                                    <td><?php echo $product['price']; ?></td>
                                    <td><?php echo $product['price'] * $product['iqty']; ?></td>
                                    <td>
                                        <?php if($invoice_info['status_id'] == 0){?>
                                            <a href="#" onclick="deleteProduct(<?php echo $product['order_product_id'];?>)" class="btn btn-danger" title="Удалить"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <td><b>Доставка:</b></td>
                                <td>
                                    <input class="form-control" type="text" name="delivery_price" value="<?php echo $invoice_info['delivery_price'];?>">

                                </td>
                            </tr>
                            <tr>
                                <td><b>Итого:</b></td>
                                <td><b><?php echo $total;?></b></td>
                            </tr>
                        </table>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="row">
                        <?php if($invoice_info['status_id'] != 1){?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Статус инвойса</label>
                                    <select name="status_id" class="form-control">
                                        <?php foreach ($statuses as $id => $status){?>
                                            <option <?php if($invoice_info['status_id'] == $id){?>selected<?php } ?> value="<?php echo $id;?>"><?php echo $status;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="product_status_id" style="display: none">
                                    <label>Статус деталей</label>
                                    <select name="product_status_id" class="form-control">
                                        <?php foreach ($product_statuses as $status){?>
                                            <option value="<?php echo $status['id'];?>"><?php echo $status['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="col-md-8"></div>
                        <?php } ?>
                        <div class="col-md-4">
                            <div class="pull-right">
                                <a target="_blank" href="/autoxadmin/invoice/view/<?php echo $invoice_info['id'];?>" class="btn btn-default">Печать</a>
                                <?php if($invoice_info['status_id'] == 1){?>
                                    <a href="/autoxadmin/invoice/cancel/<?php echo $invoice_info['id'];?>" class="confirm btn btn-danger">Отменить проводку</a>
                                <?php }else{ ?>
                                    <button type="submit" class="btn btn-info">Сохранить</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section><!-- /.content -->
<?php echo form_close(); ?>

<script>
    $(document).ready(function(){
       $("[name='status_id']").change(function(){
           if($(this).val() == '1'){
               $("#product_status_id").show();
           }else{
               $("#product_status_id").hide();
           }
       }) ;
    });
    function deleteProduct(product_id) {
        $.get('/autoxadmin/invoice/delete_product/'+product_id,function (response) {
           location.reload();
        });
    }
</script>
