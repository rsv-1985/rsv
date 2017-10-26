<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="#"><?php echo lang('text_heading');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo lang('text_heading');?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th><?php echo lang('text_customer');?></th>
                            <th><?php echo lang('text_cart_total');?></th>
                            <th><?php echo lang('text_cart_products');?></th>
                            <th><?php echo lang('text_cart_comment');?></th>
                        </tr>
                        <?php if($carts){?>
                            <?php foreach($carts as $cart){?>
                                <tr>
                                    <td><?php echo $cart['customer'];?></td>
                                    <td><?php echo $cart['cart_total'];?></td>
                                    <td>
                                        <?php $q = 1; foreach ($cart['products'] as $product){?>
                                            <b>Артикул:</b><?php echo $product['sku'];?><br>
                                            <b>Бренд:</b><?php echo $product['brand'];?><br>
                                            <b>Название:</b><?php echo $product['name'];?><br>
                                            <b>Количество:</b><?php echo $product['qty'];?><br>
                                            <b>Цена:</b><?php echo $product['price'];?><br>
                                            <b>Дата добавления</b>:<?php echo @$product['created_at'];?><hr>
                                        <?php $q++;} ?>
                                    </td>
                                    <td>
                                        <textarea id="comment<?php echo $cart['customer_id'];?>" class="form-control"><?php echo $cart['comment'];?></textarea>
                                        <div class="pull-right">
                                            <a href="/autoxadmin/report/cart/delete/<?php echo $cart['customer_id'];?>" class="btn btn-danger">Очистить корзину</a>
                                            <button onclick="addComment(<?php echo $cart['customer_id'];?>,event)" class="btn btn-info">Сохранить</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links();?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
<script>
    function addComment(customer_id,e) {
        e.preventDefault();
        $.ajax({
            url: '/autoxadmin/report/cart/addcomment',
            data: {
                customer_id:customer_id,
                comment:$("#comment"+customer_id).val()
            },
            method:'post',
            success:function(){
                alert('Ok');
            }
        });
    }
</script>
