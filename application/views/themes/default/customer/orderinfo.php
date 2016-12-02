<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo theme_url();?>css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo theme_url();?>/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <?php echo $this->config->item('company_name');?>
                    <small class="pull-right"><b>№: <?php echo $order_info['id'];?></b> | <?php echo $order_info['created_at'];?></small>
                </h2>
            </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <address>
                    <strong><?php echo $this->config->item('company_name');?></strong><br>
                    <?php echo $this->contacts['address'];?><br>
                    <?php foreach(explode(';',$this->contacts['phone']) as $phone){?>
                        <i class="fa fa-phone-square"></i><?php echo $phone;?><br/>
                    <?php } ?>
                    <?php foreach(explode(';',$this->contacts['email']) as $email){?>
                        <i class="fa fa-envelope"></i><?php echo $email;?><br/>
                    <?php } ?>
                </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <address>
                    <strong><?php echo $order_info['first_name'].' '.$order_info['last_name'];?></strong><br>
                    <?php echo $order_info['telephone'];?><br>
                    <?php echo $order_info['email'];?><br>
                </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b><?php echo lang('text_delivery_method');?>: </b><?php echo $order_info['delivery_name'];?><br/>
                <b><?php echo lang('text_payment_method');?>: </b><?php echo $order_info['payment_name'];?><br/>
            </div>
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th><?php echo lang('text_product');?></th>
                        <th><?php echo lang('text_sku');?></th>
                        <th><?php echo lang('text_brand');?></th>
                        <th><?php echo lang('text_qty');?></th>
                        <th><?php echo lang('text_price');?></th>
                        <th><?php echo lang('text_subtotal');?></th>
                    </tr>
                    </thead>
                    <tbody id="order-products">
                    <?php $row = 0; $subtotal = 0; foreach($order_products as $product){?>
                        <tr id="row<?php echo $row;?>">
                            <td>
                                <?php echo $product['name'];?>
                            </td>
                            <td>
                                <?php echo $product['sku'];?>
                            </td>
                            <td>
                                <?php echo $product['brand'];?>
                                </td>
                            <td>
                                <?php echo $product['quantity'];?>
                            </td>
                            <td>
                               <?php echo format_currency($product['price']);?>
                            </td>
                            <td><?php echo format_currency($product['quantity'] * $product['price']); $subtotal += $product['quantity'] * $product['price'];?></td>
                        </tr>
                        <?php $row++; } ?>
                    </tbody>
                </table>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    <?php echo $order_info['comments'];?>
                </p>
            </div><!-- /.col -->
            <div class="col-xs-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%"><?php echo lang('text_subtotal');?>:</th>
                            <td><?php echo format_currency($subtotal);?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('text_shipping');?>:</th>
                            <td><?php echo format_currency($order_info['delivery_price']);?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('text_commission');?></th>
                            <td><?php echo format_currency($order_info['commission']);?></td>
                        </tr>

                        <tr>
                            <th><?php echo lang('text_total');?>:</th>
                            <td><?php echo format_currency($order_info['total']);?></td>
                        </tr>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- ./wrapper -->

<!-- AdminLTE App -->
<script src="<?php echo theme_url();?>js/app.min.js"></script>
</body>
</html>

