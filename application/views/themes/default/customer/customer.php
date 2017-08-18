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
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><?php echo lang('text_orders_id');?></th>
                            <th><?php echo lang('text_orders_date');?></th>
                            <th><?php echo lang('text_orders_status');?></th>
                            <th><?php echo lang('text_orders_total');?></th>
                            <th>Статус оплаты</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($orders as $order){?>
                            <tr>
                                <td><a target="_blank" href="/customer/orderinfo/<?php echo $order['id'];?>"><?php echo $order['id'];?></a></td>
                                <td><small><?php echo $order['created_at'];?></small></td>
                                <td><b style="color: <?php echo $status[$order['status']]['color'];?>"><?php echo $status[$order['status']]['name'];?></b></td>
                                <td><?php echo format_currency($order['total']);?></td>
                                <td>
                                    <?php if(!$order['paid']){?>
                                        <a href="/customer/pay/<?php echo $order['id'];?>">Оплатить с баланса</a>
                                    <?php }else{?>
                                        Оплачен
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links();?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

