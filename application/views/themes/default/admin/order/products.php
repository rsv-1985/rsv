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
                    <h3 class="box-title">Товары в заказах</h3>
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
                    <a href="/autoxadmin/order">В разрезе Заказов</a> /
                    <a style="color: green" href="/autoxadmin/order/products">В разрезе товаров</a>
                    <table class="table table-condensed">
                        <tbody><tr>
                            <th>#</th>
                            <th>Название</th>
                            <th>Артикул</th>
                            <th>Производитель</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Поставщик</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                        <?php echo form_open('/autoxadmin/order/products',['method' => 'GET']);?>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="order_id" class="form-control" value="<?php echo $this->input->get('order_id', true);?>" style="width: 60px">
                                </div>
                            </td>

                            <td>
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get('name', true);?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="sku" class="form-control" value="<?php echo $this->input->get('sku', true);?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="brand" class="form-control" value="<?php echo $this->input->get('brand', true);?>">
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="form-group">
                                    <select name="supplier_id" class="form-control">
                                        <option></option>
                                        <?php foreach($suppliers as $supplier){?>
                                            <option value="<?php echo $supplier['id'];?>" <?php echo set_select('supplier_id',$supplier['id'],$supplier['id'] == $this->input->get('supplier_id'));?>><?php echo $supplier['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select name="status_id" class="form-control">
                                        <option></option>
                                        <?php foreach($status as $s){?>
                                            <option value="<?php echo $s['id'];?>" <?php echo set_select('status_id',$s['id'], $s['id'] == $this->input->get('status_id'));?>><?php echo $s['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="/autoxadmin/order/products" type="button" class="btn btn-link"><?php echo lang('button_reset');?></a>
                                    <button type="submit" class="btn btn-link pull-right"><?php echo lang('button_search');?></button>
                                </div>
                            </td>
                        </tr>
                        </form>
                        <?php if($products){?>
                            <?php foreach($products as $product){?>
                                <?php echo form_open();?>
                                <input type="hidden" name="slug" value="<?php echo $product['slug'];?>">
                                <input type="hidden" name="order_id" value="<?php echo $product['order_id'];?>">
                                <tr>
                                    <td>
                                        <a href="/autoxadmin/order/edit/<?php echo $product['order_id'];?>">
                                            <b>#<?php echo $product['order_id'];?><br></b>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="/product/<?php echo $product['slug'];?>" target="_blank">
                                            <?php echo $product['name'];?>
                                        </a>
                                    </td>
                                    <td><?php echo $product['sku'];?></td>
                                    <td><?php echo $product['brand'];?></td>
                                    <td><?php echo $product['price'];?></td>
                                    <td><?php echo $product['quantity'];?></td>
                                    <td><?php echo @$suppliers[$product['supplier_id']]['name'];?></td>
                                    <td>

                                        <div class="form-group">
                                            <select name="status_id" class="form-control">
                                                <option></option>
                                                <?php foreach($status as $s){?>
                                                    <option value="<?php echo $s['id'];?>" <?php echo set_select('status_id', $s['id'], $s['id'] == $product['status_id']);?>><?php echo $s['name'];?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-link pull-right" value="<?php echo lang('button_submit');?>">
                                    </td>
                                </tr>
                                </form>
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
