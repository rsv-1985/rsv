<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="single-product-area">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php $this->load->view('customer/menu');?>
            </div>
            <div class="col-md-9">
                <h2><?php echo lang('text_orders_history');?></h2>
                <?php if($orders){?>
                    <?php foreach ($orders as $order){?>
                        <table class="table table-condensed table-bordered">
                            <thead>
                            <tr class="active">
                                <th colspan="2"><?php echo lang('text_orders_id');?>: <?php echo $order['id'];?></th>

                                <th colspan="3"><?php echo lang('text_orders_date');?>: <?php echo format_time($order['created_at']);?></th>

                                <th colspan="4">
                                    <a target="_blank" href="/customer/orderinfo/<?php echo $order['id'];?>" class="pull-right">Подробнее</a>
                                    <a href="/customer/products?order_id=<?php echo $order['id'];?>">Детали в работе</a>
                                </th>
                            </tr>
                            <?php if($order['products']){ ?>
                            <tr>
                                <td>#</td>
                                <td>Код запчасти</td>
                                <td>Производитель</td>
                                <td>Наименование</td>
                                <td>Доп. инфо</td>
                                <td>Количество</td>
                                <td>Цена</td>
                                <td>Сумма</td>
                                <td>Статус</td>
                            </tr>
                            </thead>
                            <tbody>

                                <?php $q = 1; foreach ($order['products'] as $product){?>
                                    <tr>
                                        <td><?php echo $q;?></td>
                                        <td><?php echo $product['sku'];?></td>
                                        <td><?php echo $product['brand'];?></td>
                                        <td><?php echo $product['name'];?></td>
                                        <td><?php echo $product['excerpt'];?></td>
                                        <td><?php echo $product['quantity'];?></td>
                                        <td><?php echo format_currency($product['price']);?></td>
                                        <td><?php echo format_currency($product['price'] * $product['quantity']);?></td>
                                        <td><?php echo @$status[$product['status_id']]['name'];?></td>
                                    </tr>
                                <?php $q++; } ?>
                            <?php } ?>
                            <tr class="info">
                                <td colspan="5"><?php echo lang('text_orders_status');?>: <b style="color: <?php echo $status[$order['status']]['color'];?>"><?php echo $status[$order['status']]['name'];?></b></td>
                                <td colspan="4"><?php echo lang('text_orders_total');?>: <b><?php echo format_currency($order['total']);?></b></td>
                            </tr>
                            </tbody>
                        </table>
                        <hr>
                    <?php } ?>
                    <?php echo $this->pagination->create_links();?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

