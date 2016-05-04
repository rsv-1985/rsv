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
            Order
            <small>#<?php echo $order['id'];?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="/autoxadmin/order"><?php echo lang('text_heading');?></a></li>
            <li class="active">Order #<?php echo $order['id'];?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="invoice">
        <?php echo form_open('', ['id' => 'order_form']);?>
        <input type="hidden" name="customer_id" value="<?php echo $order['customer_id'];?>">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    #<?php echo $order['id'];?>
                    <small class="pull-right"><?php echo $order['created_at'];?></small>
                </h2>
            </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <label><?php echo lang('text_first_name');?></label>
                            <input type="text" name="first_name" value="<?php echo set_value('first_name', $order['first_name']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_last_name');?></label>
                            <input type="text" name="last_name" value="<?php echo set_value('last_name', $order['last_name']);?>" class="form-control">
                        </div>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <div class="form-group">
                    <label><?php echo lang('text_telephone');?></label>
                    <input type="text" name="telephone" value="<?php echo set_value('telephone', $order['telephone']);?>" class="form-control">
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_email');?></label>
                    <input type="email" name="email" value="<?php echo set_value('email', $order['email']);?>" class="form-control">
                </div>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <div class="form-group">
                    <label><?php echo lang('text_delivery_method');?></label>
                    <select name="delivery_method" class="form-control">
                        <?php foreach($delivery as $delivery){?>
                            <option value="<?php echo $delivery['id'];?>" <?php echo set_select('delivery_method_id', $delivery['id'], $delivery['id'] == $order['delivery_method_id'] );?>><?php echo $delivery['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_payment_method');?></label>
                    <select name="payment_method" class="form-control">
                        <?php foreach($payment as $payment){?>
                            <option value="<?php echo $payment['id'];?>" <?php echo set_select('payment_method_id', $payment['id'], $payment['id'] == $order['payment_method_id'] );?>><?php echo $payment['name'];?></option>
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
                    <input id="search_val" type="text" class="form-control" placeholder="add products">
                          <span class="input-group-btn">
                            <button id="search" class="btn btn-default" type="button"><?php echo lang('button_search');?></button>
                          </span>
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
            <div class="col-xs-12 table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php echo lang('text_supplier');?></th>
                        <th><?php echo lang('text_product');?></th>
                        <th><?php echo lang('text_sku');?></th>
                        <th><?php echo lang('text_brand');?></th>
                        <th><?php echo lang('text_qty');?></th>
                        <th><?php echo lang('text_price');?></th>
                        <th><?php echo lang('text_status');?></th>
                        <th><?php echo lang('text_subtotal');?></th>
                    </tr>
                    </thead>
                    <tbody id="order-products">
                    <?php $row = 0; $subtotal = 0; foreach($products as $product){?>
                        <tr id="row<?php echo $row;?>">
                            <input type="hidden" name="products[<?php echo $row;?>][slug]" value="<?php echo $product['slug'];?>">
                            <td><a href="#" onclick="remove_item(<?php echo $row;?>, event)"><i class="fa fa-fw fa-remove"></i></a></td>
                            <td>
                                <a data-toggle="tooltip" data-placement="right" title="<?php echo $supplier[$product['supplier_id']]['description'];?>" target="_blank" href="/autoxadmin/supplier/edit/<?php echo $supplier[$product['supplier_id']]['id'];?>">
                                    <?php echo $supplier[$product['supplier_id']]['name'];?></td>
                                </a>
                                <input type="hidden" name="products[<?php echo $row;?>][supplier_id]" value="<?php echo $product['supplier_id'];?>">
                            <td>
                                <?php echo $product['name'];?>
                                <input type="hidden" name="products[<?php echo $row;?>][name]" value="<?php echo $product['name'];?>">
                            </td>
                            <td>
                                <?php echo $product['sku'];?>
                                <input type="hidden" name="products[<?php echo $row;?>][sku]" value="<?php echo $product['sku'];?>">
                            </td>
                            <td>
                                <?php echo $product['brand'];?>
                                <input type="hidden" name="products[<?php echo $row;?>][brand]" value="<?php echo $product['brand'];?>">
                            </td>
                            <td>
                                <input onkeyup="row_subtotal(<?php echo $row;?>)" id="qty<?php echo $row;?>" name="products[<?php echo $row;?>][quantity]" type="text" value="<?php echo $product['quantity'];?>" class="form-control" style="width: 80px;">
                            </td>
                            <td>
                                <input onkeyup="row_subtotal(<?php echo $row;?>)" id="price<?php echo $row;?>" name="products[<?php echo $row;?>][price]" type="text" value="<?php echo $product['price'];?>" class="form-control" style="width: 100px;">
                            </td>
                            <td>
                                <select name="products[<?php echo $row;?>][status_id]" class="form-control">
                                    <?php foreach($status as $st){?>
                                        <option value="<?php echo $st['id'];?>" <?php echo set_select('products['.$row.'][status_id]',$st['id'],$st['id'] == $product['status_id']);?>><?php echo $st['name'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><span id="row_subtotal<?php echo $row;?>"><?php echo $product['quantity'] * $product['price']; $subtotal += $product['quantity'] * $product['price'];?></span></td>
                        </tr>
                    <?php $row++; } ?>
                    </tbody>
                </table>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <b><?php echo lang('text_comments');?></b>
                <textarea disabled rows="3" name="comments" class="form-control" style="margin-top: 10px;"><?php echo $order['comments'];?></textarea>
                <hr>
                <b><?php echo lang('text_manager_comments');?></b>
                <textarea rows="3" name="history" class="form-control"></textarea>
                <input type="checkbox" value="1" name="send_sms" disabled>Send_sms
                <input type="checkbox" value="1" name="send_email">Send_email
                <?php if($history){?>
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
                        <?php foreach ($history as $history){?>
                            <tr>
                                <td><?php echo $history['date'];?></td>
                                <td><?php echo $history['text'];?></td>
                                <td align="center">
                                    <?php if($history['send_sms']){?>
                                        <i class="fa fa-check-circle-o"></i>
                                    <?php } ?>
                                </td>
                                <td align="center">
                                    <?php if($history['send_email']){?>
                                        <i class="fa fa-check-circle-o"></i>
                                    <?php } ?>
                                </td>
                                <td><?php echo $history['manager'];?></td>
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
                            <th style="width:50%"><?php echo lang('text_subtotal');?>:</th>
                            <td><span id="subtotal"><?php echo $subtotal;?></span></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('text_shipping');?>:</th>
                            <td><span id="delivery_price"><?php echo $order['delivery_price'];?></span></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('text_commission');?></th>
                            <td><span id="commission"><?php echo $order['commission'];?></span></td>
                        </tr>

                        <tr>
                            <th><?php echo lang('text_total');?>:</th>
                            <td><span id="total"><?php echo $order['total'];?></span></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('text_status');?></th>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" name="status">
                                        <?php foreach($status as $st){?>
                                            <option value="<?php echo $st['id'];?>" <?php echo set_select('status', $st['id'], $st['id'] == $order['status']);?>><?php echo $st['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="pull-right">
                        <button style="display: none;" class="btn btn-info btn-flat" type="submit"><?php echo lang('button_submit');?></button>
                        <a class="btn btn-default btn-flat" href="/autoxadmin/order"><?php echo lang('button_close');?></a>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </form>
    </section><!-- /.content -->
    <div class="clearfix"></div>
<!-- Modal search-->
<div class="modal fade bs-example-modal-lg" id="search_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="product-big-title-area">
                <div class="product-bit-title text-center">
                    <strong id="search_query"></strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="list-group" id="search_brand_list">

                </div>
            </div>
            <div class="col-md-9" style="overflow: auto; max-height: 600px">
                <div class="search_result">
                    <h3><?php echo lang('text_change_brand');?></h3>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script>
    var row = '<?php echo $row;?>';

    $(document).ready(function(){
        $("input").change(function(){
            total();
            $("[type='submit']").show();
        });

        $("select").change(function(){
            $("[type='submit']").show();
        });

        $("#search").click(function(event){
            var search = $("#search_val").val();
            event.preventDefault();
            $.ajax({
                url: '/ajax/pre_search',
                method: 'POST',
                data: {search:search},
                dataType: 'json',
                success: function(json){
                    $(".search_result").empty();
                    $("#search_brand_list").empty();
                    $("#search_query").text(json['search_query']);
                    if(json['brand'].length > 0){
                        var html = '';
                        $.each(json['brand'], function( index, brand ) {
                            html += '<a href="#" onclick="get_search(\''+brand['ID_art']+'\',\''+brand['brand']+'\',\''+brand['sku']+'\')" class="list-group-item">'+brand['brand']+'<br><small>'+brand['name']+'</small></a>';
                        });
                        $("#search_brand_list").html(html);
                    }else{
                        get_search(false, false, json['search_query']);
                    }
                    $("#search_modal").modal();
                }
            });
        });
    });
    function remove_item(row, event){
        event.preventDefault();
        $("#row"+row).remove();
        total();
    }

    function total(){
        $.ajax({
            url: '/autoxadmin/order/get_total',
            data: $("#order_form").serialize(),
            method: 'POST',
            success: function(json){
                $("#subtotal").html(json['subtotal'].toFixed(2));
                $("#delivery_price").html(json['delivery_price']);
                $("#commission").html(json['commission'].toFixed(2));
                $("#total").html(json['total'].toFixed(2));
            }
        });
    }

    function get_search(ID_art, brand, sku){
        $.ajax({
            url: '/ajax/get_search',
            method: 'GET',
            data: {ID_art: ID_art, brand:brand, sku:sku, is_admin:1},
            beforeSend: function(){
                $(".search_result").html('<img src="/assets/themes/default/img/loading.gif"/>');
            },
            success: function(html){
                $(".search_result").html(html);
            }
        });
    }

    function row_subtotal(row_id){
        var price = $("#price"+row_id).val();
        var qty = $("#qty"+row_id).val();
        var sub_total = price * qty;
        $("#row_subtotal"+row_id).html(sub_total.toFixed(2));
        total();
    }

    //Добавдение товара к заказу
    function add_product(slug){
        $.ajax({
            url: '/autoxadmin/order/add_product',
            data: {slug:slug},
            method: 'POST',
            success: function(product){
                html = '';
                if(product){
                    html +='<tr id="row'+row+'">';
                    html +='    <input type="hidden" name="products['+row+'][slug]" value="'+product['slug']+'">';
                    html +='       <td><a href="#" onclick="remove_item('+row+', event)"><i class="fa fa-fw fa-remove"></i></a></td>';
                    html +='        <td>';
                    html +='            <a data-toggle="tooltip" data-placement="right" title="'+product['sup_description']+'" target="_blank" href="/autoxadmin/supplier/edit/'+product['supplier_id']+'">'+product['sup_name']+'</td>';
                    html +='    </a>';
                    html +='    <input type="hidden" name="products['+row+'][supplier_id]" value="'+product['supplier_id']+'">';
                    html +='        <td>';
                    html +='            '+product['name']+'';
                    html +='            <input type="hidden" name="products['+row+'][name]" value="'+product['name']+'">';
                    html +='       </td>';
                    html +='       <td>';
                    html +='            '+product['sku']+'';
                    html +='           <input type="hidden" name="products['+row+'][sku]" value="'+product['sku']+'">';
                    html +='        </td>';
                    html +='        <td>';
                    html +='            '+product['brand']+'';
                    html +='            <input type="hidden" name="products['+row+'][brand]" value="'+product['brand']+'">';
                    html +='        </td>';
                    html +='        <td>';
                    html +='            <input onkeyup="row_subtotal('+row+')" id="qty'+row+'" name="products['+row+'][quantity]" type="text" value="1" class="form-control" style="width: 80px;">';
                    html +='        </td>';
                    html +='        <td>';
                    html +='            <input onkeyup="row_subtotal('+row+')" id="price'+row+'" name="products['+row+'][price]" type="text" value="'+product['price']+'" class="form-control" style="width: 100px;">';
                    html +='        </td>';
                    html +='<td>';
                    html +='<select name="products['+row+'][status_id]" class="form-control">';
                    <?php foreach($status as $st){?>
                    html +='<option value="<?php echo $st['id'];?>"><?php echo $st['name'];?></option>';
                    <?php } ?>
                    html +='</select>';
                    html +='</td>';
                    html +='       <td><span id="row_subtotal'+row+'">'+product['price']+'</span></td>';
                    html +='</tr>';
                }
                $("#order-products").append(html);
                $("#search_modal").modal('hide');
                row++;
                total();
            }
        });
    }
</script>