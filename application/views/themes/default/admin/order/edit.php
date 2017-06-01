<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Content Wrapper. Contains page content -->
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <small>#<?php echo $order['id']; ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="/autoxadmin/order"><?php echo lang('text_heading'); ?></a></li>
        <li class="active">#<?php echo $order['id']; ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="invoice">
    <?php echo form_open('', ['id' => 'order_form']); ?>
    <input type="hidden" name="customer_id" value="<?php echo $order['customer_id']; ?>">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                Заказ #<?php echo $order['id']; ?>
                <small class="pull-right"><?php echo $order['created_at']; ?></small>
            </h2>
        </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <div class="form-group">
                <label><?php echo lang('text_first_name'); ?></label>
                <input type="text" name="first_name"
                       value="<?php echo set_value('first_name', $order['first_name']); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label><?php echo lang('text_last_name'); ?></label>
                <input type="text" name="last_name" value="<?php echo set_value('last_name', $order['last_name']); ?>"
                       class="form-control">
            </div>
            <div class="form-group">
                <label><?php echo lang('text_patronymic'); ?></label>
                <input type="text" name="patronymic" value="<?php echo set_value('patronymic', $order['patronymic']); ?>"
                       class="form-control">
            </div>
        </div><!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <div class="form-group">
                <label><?php echo lang('text_address'); ?></label>
                <input type="text" name="address" value="<?php echo set_value('address', $order['address']); ?>"
                       class="form-control">
            </div>
            <div class="form-group">
                <label><?php echo lang('text_telephone'); ?></label>
                <input type="text" name="telephone" value="<?php echo set_value('telephone', $order['telephone']); ?>"
                       class="form-control">
                <?php if ($scamdb_info) {
                    echo $scamdb_info;
                } ?>
            </div>
            <div class="form-group">
                <label><?php echo lang('text_email'); ?></label>
                <input type="email" name="email" value="<?php echo set_value('email', $order['email']); ?>"
                       class="form-control">
            </div>
        </div><!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <div class="form-group">
                <label><?php echo lang('text_delivery_method'); ?></label>
                <select name="delivery_method" class="form-control">
                    <?php foreach ($delivery as $delivery) { ?>
                        <option
                            value="<?php echo $delivery['id']; ?>" <?php echo set_select('delivery_method_id', $delivery['id'], $delivery['id'] == $order['delivery_method_id']); ?>><?php echo $delivery['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo lang('text_payment_method'); ?></label>
                <select name="payment_method" class="form-control">
                    <?php foreach ($payment as $payment) { ?>
                        <option
                            value="<?php echo $payment['id']; ?>" <?php echo set_select('payment_method_id', $payment['id'], $payment['id'] == $order['payment_method_id']); ?>><?php echo $payment['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <hr>
        <div class="col-lg-4 pull-right">
            <div class="input-group">
                <input autocomplete="off" id="search_val" type="text" class="form-control" placeholder="add products">
                <span class="input-group-btn">
                            <button id="search" class="btn btn-default"
                                    type="button"><?php echo lang('button_search'); ?></button>
                          </span>
            </div><!-- /input-group -->
            <div class="search-results"></div>
        </div><!-- /.col-lg-6 -->
        <div class="col-xs-12 table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th><?php echo lang('text_supplier'); ?></th>
                    <th><?php echo lang('text_product'); ?></th>
                    <th><?php echo lang('text_sku'); ?></th>
                    <th><?php echo lang('text_brand'); ?></th>
                    <th><?php echo lang('text_term'); ?></th>
                    <th><?php echo lang('text_qty'); ?></th>
                    <th><?php echo lang('text_delivery_price'); ?></th>
                    <th><?php echo lang('text_price'); ?></th>
                    <th><?php echo lang('text_subtotal'); ?></th>
                    <th><?php echo lang('text_status'); ?></th>
                </tr>
                </thead>
                <tbody id="order-products">
                <?php $row = 0;
                $subtotal = 0;
                foreach ($products as $product) { ?>
                    <tr id="row<?php echo $row; ?>">
                        <input type="hidden" name="products[<?php echo $row; ?>][slug]" value="<?php echo $product['slug']; ?>">
                        <input type="hidden" name="products[<?php echo $row; ?>][product_id]" value="<?php echo $product['product_id']; ?>"/>
                        <td><a href="#" onclick="remove_item(<?php echo $row; ?>, event)"><i
                                    class="fa fa-fw fa-remove"></i></a></td>
                        <td>
                            <a data-toggle="tooltip" data-placement="right"
                               title="<?php echo @$supplier[$product['supplier_id']]['description']; ?>" target="_blank"
                               href="/autoxadmin/supplier/edit/<?php echo $product['supplier_id']; ?>">
                            <?php echo @$supplier[$product['supplier_id']]['name']; ?></td>
                        </a>
                        <input type="hidden" name="products[<?php echo $row; ?>][supplier_id]"
                               value="<?php echo $product['supplier_id']; ?>">
                        <td>
                            <?php echo $product['name']; ?>
                            <input type="hidden" name="products[<?php echo $row; ?>][name]"
                                   value="<?php echo $product['name']; ?>">
                        </td>
                        <td>
                            <?php echo $product['sku']; ?>
                            <input type="hidden" name="products[<?php echo $row; ?>][sku]"
                                   value="<?php echo $product['sku']; ?>">
                        </td>
                        <td>
                            <?php echo $product['brand']; ?>
                            <input type="hidden" name="products[<?php echo $row; ?>][brand]"
                                   value="<?php echo $product['brand']; ?>">
                        </td>
                        <td>
                            <?php echo @format_term($product['term']); ?>
                            <input type="hidden" name="products[<?php echo $row; ?>][term]"
                                   value="<?php echo $product['term']; ?>">
                        </td>
                        <td>
                            <input onkeyup="row_subtotal(<?php echo $row; ?>)" id="qty<?php echo $row; ?>"
                                   name="products[<?php echo $row; ?>][quantity]" type="text"
                                   value="<?php echo $product['quantity']; ?>" class="form-control"
                                   style="width: 80px;">
                        </td>
                        <td>
                            <input onkeyup="row_subtotal(<?php echo $row; ?>)" id="price<?php echo $row; ?>"
                                   name="products[<?php echo $row; ?>][delivery_price]" type="text"
                                   value="<?php echo $product['delivery_price']; ?>" class="form-control" style="width: 100px;">
                        </td>
                        <td>
                            <input onkeyup="row_subtotal(<?php echo $row; ?>)" id="price<?php echo $row; ?>"
                                   name="products[<?php echo $row; ?>][price]" type="text"
                                   value="<?php echo $product['price']; ?>" class="form-control" style="width: 100px;">
                        </td>
                        <td><span
                                id="row_subtotal<?php echo $row; ?>"><?php echo $product['quantity'] * $product['price'];
                                $subtotal += $product['quantity'] * $product['price']; ?></span></td>
                        <td>
                            <select name="products[<?php echo $row; ?>][status_id]" class="form-control">
                                <?php foreach ($status as $st) { ?>
                                    <option
                                        value="<?php echo $st['id']; ?>" <?php echo set_select('products[' . $row . '][status_id]', $st['id'], $st['id'] == $product['status_id']); ?>><?php echo $st['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <?php $row++;
                } ?>
                </tbody>
            </table>
        </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
            <?php if (trim($order['comments']) != '') { ?>
                <b><?php echo lang('text_comments'); ?></b>
                <textarea disabled rows="3" name="comments" class="form-control"
                          style="margin-top: 10px;"><?php echo $order['comments']; ?></textarea>
                <hr>
            <?php } ?>
            <b><?php echo lang('text_manager_comments'); ?></b>
            <textarea rows="3" name="history" class="form-control"></textarea>
            <input type="checkbox" value="1" name="send_sms"><?php echo lang('text_send_sms'); ?>
            <input type="checkbox" value="1" name="send_email"><?php echo lang('text_send_email'); ?>
            <?php if ($history) { ?>
                <hr>
                <b>Order history</b>
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>date</th>
                        <th>text</th>
                        <th>sms</th>
                        <th>email</th>
                        <th>manager</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($history as $history) { ?>
                        <tr>
                            <td><?php echo $history['date']; ?></td>
                            <td><?php echo $history['text']; ?></td>
                            <td align="center">
                                <?php if ($history['send_sms']) { ?>
                                    <i class="fa fa-check-circle-o"></i>
                                <?php } ?>
                            </td>
                            <td align="center">
                                <?php if ($history['send_email']) { ?>
                                    <i class="fa fa-check-circle-o"></i>
                                <?php } ?>
                            </td>
                            <td><?php echo $history['manager']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>

        </div><!-- /.col -->
        <div class="col-xs-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%"><?php echo lang('text_subtotal'); ?>:</th>
                        <td><span id="subtotal"><?php echo $subtotal; ?></span></td>
                    </tr>
                    <tr>
                        <th><?php echo lang('text_shipping'); ?>:</th>
                        <td>
                                <span id="delivery_price">
                                    <input type="text" name="delivery_price" class="form-control"
                                           value="<?php echo set_value('delivery_price', $order['delivery_price']); ?>">
                                </span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo lang('text_commission'); ?></th>
                        <td><span id="commission"><?php echo $order['commission']; ?></span></td>
                    </tr>

                    <tr>
                        <th><?php echo lang('text_total'); ?>:</th>
                        <td><span id="total"><?php echo $order['total']; ?></span></td>
                    </tr>
                    <tr>
                        <th><?php echo lang('text_status'); ?></th>
                        <td>
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <?php foreach ($status as $st) { ?>
                                        <option
                                            value="<?php echo $st['id']; ?>" <?php echo set_select('status', $st['id'], $st['id'] == $order['status']); ?>><?php echo $st['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo lang('text_paid'); ?></th>
                        <td>
                            <div class="form-group">
                                <input type="checkbox"
                                       name="paid" <?php echo set_checkbox('paid', true, (bool)$order['paid']); ?>/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo lang('text_revenue'); ?></th>
                        <td>
                            <div class="form-group" id="revenue"></div>
                        </td>
                    </tr>
                </table>
                <div class="pull-right">
                    <button class="btn btn-info btn-flat" type="submit"><?php echo lang('button_submit'); ?></button>
                    <a target="_blank" class="btn btn-default btn-flat"
                       href="/customer/orderinfo/<?php echo $order['id']; ?>">Invoice</a>
                    <a class="btn btn-default btn-flat" href="/autoxadmin/order"><?php echo lang('button_close'); ?></a>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </form>
</section><!-- /.content -->
<div class="clearfix"></div>
<script>
    var row = '<?php echo $row;?>';

    $(document).ready(function () {
        total();

        $("input,select").change(function () {
            total();
        });

        $("select").change(function () {
            $("[type='submit']").show();
        });

        $("#search").click(function (event) {
            var search = $("#search_val").val();
            event.preventDefault();
            $.ajax({
                url: '/autoxadmin/order/search_products',
                method: 'POST',
                data: {search: search},
                success: function (html) {
                    $(".search-results").html(html);
                }
            });
        });
    });
    function remove_item(row, event) {
        event.preventDefault();
        $("#row" + row).remove();
        total();
    }

    function total() {
        $.ajax({
            url: '/autoxadmin/order/get_total',
            data: $("#order_form").serialize(),
            method: 'POST',
            success: function (json) {
                $("#subtotal").html(json['subtotal'].toFixed(2));
                $("[name='delivery_price']").val(json['delivery_price']);
                $("#commission").html(json['commission'].toFixed(2));
                $("#total").html(json['total'].toFixed(2));
                var revenue = json['total'] - json['delivery_total'];
                $("#revenue").html(revenue.toFixed(2));
            }
        });
    }

    function row_subtotal(row_id) {
        var price = $("#price" + row_id).val();
        var qty = $("#qty" + row_id).val();
        var sub_total = price * qty;
        $("#row_subtotal" + row_id).html(sub_total.toFixed(2));
        total();
    }

    //Добавдение товара к заказу
    function add_product(product_id, supplier_id, term) {
        $.ajax({
            url: '/autoxadmin/order/add_product',
            data: {product_id: product_id, supplier_id: supplier_id, term: term,order_id:'<?php echo $order['id'];?>'},
            method: 'POST',
            success: function (response) {
               if(response == 'success'){
                   location.reload();
               }else{
                   alert(response);
               }
            }
        });
    }
</script>