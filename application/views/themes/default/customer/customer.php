<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div role="tabpanel">
                    <ul class="product-tab" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php echo lang('text_orders');?></a></li>
                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><?php echo lang('text_profile');?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="home">
                            <h2><?php echo lang('text_orders_history');?></h2>
                            <?php if($orders){?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('text_orders_id');?></th>
                                        <th><?php echo lang('text_orders_status');?></th>
                                        <th><?php echo lang('text_orders_total');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($orders as $order){?>
                                    <tr>
                                        <td><a target="_blank" href="/customer/orderinfo/<?php echo $order['id'];?>"><?php echo $order['id'];?></a></td>
                                        <td><b style="color: <?php echo $status[$order['status']]['color'];?>"><?php echo $status[$order['status']]['name'];?></b></td>
                                        <td><?php echo format_currency($order['total']);?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                                <?php echo $this->pagination->create_links();?>
                            <?php } ?>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="profile">
                            <h2><?php echo lang('text_profile');?></h2>
                            <b><?php echo lang('text_group');?>:</b> <?php echo $customer_group['name'];?><br>
                            <?php if(@$this->options['show_customer_group_type']){?>
                                <?php if($customer_group['type'] == '+'){?>
                                    <b><?php echo lang('text_margin');?>:</b> <?php echo $customer_group['value'];?>%<br>
                                <?php }else{?>
                                    <b><?php echo lang('text_discount');?>:</b> <?php echo $customer_group['value'];?>%<br>
                                <?php } ?>
                                <?php if($customer_group['fix_value'] > 0){?>
                                    <b><?php echo lang('text_fix');?>:</b>  +<?php echo format_currency($customer_group['fix_value']);?>
                                <?php } ?>
                            <?php } ?>

                            <?php echo form_open();?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo lang('text_address');?></label>
                                    <input type="text" name="address" class="form-control" value="<?php echo set_value('address',$customer['address']);?>">
                                    <label><?php echo lang('text_login');?></label>
                                    <input type="text" name="login" class="form-control" value="<?php echo set_value('login',$customer['login']);?>" maxlength="32">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('text_password');?></label>
                                    <input type="password" name="password" class="form-control" value="<?php echo set_value('password');?>">
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_first_name');?></label>
                                    <input type="text" name="first_name" class="form-control" value="<?php echo set_value('first_name', $customer['first_name']);?>">
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_patronymic');?></label>
                                    <input type="text" name="patronymic" class="form-control" value="<?php echo set_value('patronymic', $customer['patronymic']);?>">
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_email');?></label>
                                    <input type="email" name="email" class="form-control" value="<?php echo set_value('email', $customer['email']);?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('text_confirm_password');?></label>
                                    <input type="password" name="confirm_password" value="<?php echo set_value('confirm_password');?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_second_name');?></label>
                                    <input type="text" name="second_name" class="form-control" value="<?php echo set_value('second_name', $customer['second_name']);?>">
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_phone');?></label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo set_value('phone', $customer['phone']);?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" value="<?php echo lang('button_submit');?>" class="pull-right">
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

