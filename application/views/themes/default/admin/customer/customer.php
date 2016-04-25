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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
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
                        <thead><tr>
                            <th><?php echo lang('text_login');?></th>
                            <th><?php echo lang('text_customer_group_id');?></th>
                            <th><?php echo lang('text_first_name');?></th>
                            <th><?php echo lang('text_second_name');?></th>
                            <th><?php echo lang('text_email');?></th>
                            <th><?php echo lang('text_phone');?></th>
                            <th><?php echo lang('text_sum');?></th>
                            <th><?php echo lang('text_status');?></th>
                            <th><a href="/autoxadmin/customer/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php echo form_open('/autoxadmin/customer',['method' => 'get']);?>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="login" value="<?php echo $this->input->get('login');?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select name="customer_group_id" class="form-control">
                                        <option></option>
                                        <?php foreach ($customergroup as $cg){?>
                                            <option value="<?php echo $cg['id'];?>" <?php echo set_select('customer_group_id',$cg['id'], $cg['id'] == $this->input->get('customer_group_id'));?>><?php echo $cg['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="first_name" value="<?php echo $this->input->get('first_name');?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="second_name" value="<?php echo $this->input->get('second_name');?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email" value="<?php echo $this->input->get('email');?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone" value="<?php echo $this->input->get('phone');?>">
                                </div>
                            </td>
                            <td><div class="form-group">
                                    <input type="text" class="form-control" disabled>
                                </div></td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="status" value="<?php echo $this->input->get('status');?>">
                                </div>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="/autoxadmin/customer" type="button" class="btn btn-danger"><?php echo lang('button_reset');?></a>
                                    <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_search');?></button>
                                </div>
                            </td>
                        </tr>
                        </form>
                        <?php if($customeres){?>
                            <?php foreach($customeres as $customer){?>
                                <tr>
                                    <td>
                                        <?php echo $customer['login'];?>
                                    </td>
                                    <td><?php echo $customergroup[$customer['customer_group_id']]['name'];?></td>
                                    <td><?php echo $customer['first_name'];?></td>
                                    <td><?php echo $customer['second_name'];?></td>
                                    <td><?php echo $customer['email'];?></td>
                                    <td><?php echo $customer['phone'];?></td>
                                    <td>
                                        <?php foreach ($orderstatus as $order_id => $value){
                                            if(isset($customer['sum_'.$order_id])){
                                                echo '<small style="color:'.$value['color'].'">'.$value['name'].'</small>:'.$customer['sum_'.$order_id].'<br/>';
                                            }
                                        }?>
                                    </td>
                                    <td>
                                        <?php if($customer['status']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td style="width: 190px;">
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/customer/delete/<?php echo $customer['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                            <a href="/autoxadmin/customer/edit/<?php echo $customer['id'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
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
