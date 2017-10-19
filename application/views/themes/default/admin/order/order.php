<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<style>
    td.email{
        word-break: break-all;
    }
</style>
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
                    <?php if($status_totals){?>
                        <div class="pull-right" style="text-align: right">
                            <?php foreach ($status as $stid => $v){
                                if(isset($status_totals['sum_'.$stid])){
                                    echo '<small style="color:'.$v['color'].'">'.$v['name'].':</small>'.$status_totals['sum_'.$stid].' ';
                                }
                            }?>
                        </div>
                    <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">

                        <a style="color: green" href="/autoxadmin/order">Заказы</a> /
                        <a href="/autoxadmin/order/products">Отобразить товары в заказах</a>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <tbody><tr>
                                <th>#</th>
                                <th><?php echo lang('text_first_name');?></th>
                                <th><?php echo lang('text_last_name');?></th>
                                <th><?php echo lang('text_email');?></th>
                                <th><?php echo lang('text_telephone');?></th>
                                <th><?php echo lang('text_delivery_method');?></th>
                                <th><?php echo lang('text_payment_method');?></th>
                                <th><?php echo lang('text_total');?>/<?php echo lang('text_login');?></th>
                                <th><?php echo lang('text_status');?></th>
                                <th><?php echo lang('text_paid');?></th>
                                <th><?php echo lang('text_product_status');?></th>
                                <th><a style="display: none;" href="/autoxadmin/order/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                            </tr>
                            <?php echo form_open('/autoxadmin/order/index',['method' => 'GET']);?>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="id" class="form-control" value="<?php echo $this->input->get('id', true);?>" style="width: 60px">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group">
                                        <input type="text" name="first_name" class="form-control" value="<?php echo $this->input->get('first_name', true);?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="last_name" class="form-control" value="<?php echo $this->input->get('last_name', true);?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="email" class="form-control" value="<?php echo $this->input->get('email', true);?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="telephone" class="form-control" value="<?php echo $this->input->get('telephone', true);?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="delivery_method_id" class="form-control">
                                            <option></option>
                                            <?php foreach($delivery as $delivery_method){?>
                                                <option value="<?php echo $delivery_method['id'];?>" <?php if($this->input->get('delivery_method_id') && $this->input->get('delivery_method_id') == $delivery_method['id']){?>selected<?php } ?>><?php echo $delivery_method['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="payment_method_id" class="form-control">
                                            <option></option>
                                            <?php foreach($payment as $payment_method){?>
                                                <option value="<?php echo $payment_method['id'];?>" <?php if($this->input->get('payment_method_id') && $this->input->get('payment_method_id') == $payment_method['id']){?>selected<?php } ?>><?php echo $payment_method['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="login" class="form-control" value="<?php echo $this->input->get('login', true);?>" style="width: 86px">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="status" class="form-control">
                                            <option></option>
                                            <?php foreach($status as $s){?>
                                                <option value="<?php echo $s['id'];?>" <?php if($this->input->get('status') && $this->input->get('status') == $s['id']){?>selected<?php } ?>><?php echo $s['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>1</td>
                                <td></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/autoxadmin/order" type="button" class="btn btn-link" title="<?php echo lang('button_reset');?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                                        <button type="submit" class="btn btn-link pull-right" title="<?php echo lang('button_search');?>"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </td>
                            </tr>
                            </form>
                            <?php if($orders){?>
                                <?php foreach($orders as $order){?>
                                    <tr style="border-left: 5px solid <?php echo @$status[$order['status']]['color'];?>">
                                        <td>
                                            <b>#<?php echo $order['id'];?><br></b>
                                            <small><?php echo $order['created_at'];?></small>
                                        </td>
                                        <td><?php echo $order['first_name'];?></td>
                                        <td><?php echo $order['last_name'];?></td>
                                        <td class="email"><?php echo $order['email'];?></td>
                                        <td>
                                            <?php echo $order['telephone'];?><br/>
                                        </td>
                                        <td><?php echo $delivery[$order['delivery_method_id']]['name'];?></td>
                                        <td><?php echo $payment[$order['payment_method_id']]['name'];?></td>
                                        <td>
                                            <b><?php echo $order['total'];?></b><br />
                                            <small><?php echo $order['login'];?> <?php echo $order['login'] ? '(<b>'.$order['balance'].'</b>)' : '';?></small>
                                        </td>
                                        <td>
                                            <b style="color: <?php echo $status[$order['status']]['color'];?>"><?php echo @$status[$order['status']]['name'];?></b>
                                        </td>
                                        <td>
                                            <?php if($order['paid']){?>
                                                <small style="color: green;"><?php echo lang('text_paid');?></small>
                                            <?php } ?>
                                            <?php if($order['prepayment'] > 0){?>
                                                <br><small style="color: grey;">Предоплата:<?php echo $order['prepayment'];?></small>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if($order['products_status']){?>
                                                <small>
                                                    <?php foreach ($order['products_status'] as $product_status){?>
                                                        <b style="color: <?php echo $product_status['color'];?>"><?php echo $product_status['name'];?></b><br/>
                                                    <?php } ?>
                                                </small>
                                            <?php } ?>

                                        </td>
                                        <td style="width: 92px;">
                                            <div class="btn-group">
                                                <a href="/autoxadmin/order/delete/<?php echo $order['id'];?>" class="confirm btn btn-danger" title="<?php echo lang('button_delete');?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                <a href="/autoxadmin/order/edit/<?php echo $order['id'];?>" class="btn btn-info" title="<?php echo lang('button_view');?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links();?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
