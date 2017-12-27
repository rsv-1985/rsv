<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header" xmlns="http://www.w3.org/1999/html">
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
                    <?php echo form_open('', ['method' => 'get']);?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Дата начала</label>
                                <input type="date" name="date_start" value="<?php echo set_value('date_start',$this->input->get('date_start'));?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Дата окончания</label>
                                <input type="date" name="date_end"  value="<?php echo set_value('date_end',$this->input->get('date_end'));?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Сортировать по</label>
                                <select name="filter_group" id="input-group" class="form-control">
                                    <option></option>
                                    <option value="year" <?php echo set_select('filter_group','year','year' == $this->input->get('filter_group'));?>>Год</option>
                                    <option value="month" <?php echo set_select('filter_group','month','month' == $this->input->get('filter_group'));?>>Месяц</option>
                                    <option value="week" <?php echo set_select('filter_group','week','week' == $this->input->get('filter_group'));?>>Неделя</option>
                                    <option value="day" <?php echo set_select('filter_group','day','day' == $this->input->get('filter_group'));?>>День</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Статус заказа</label>
                                <select name="status_id" class="form-control">
                                    <option></option>
                                    <?php foreach ($statuses as $status){?>
                                        <option value="<?php echo $status['id'];?>" <?php echo set_select('status_id',$status['id'],$status['id'] == $this->input->get('status_id'));?>><?php echo $status['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="pull-right">
                                <?php if($this->input->get()){?>
                                    <a href="/autoxadmin/report/sale_order" class="btn btn-danger">Сбросить</a>
                                <?php } ?>
                                <button type="submit" class="btn btn-info">Фильтр</button>
                            </div>
                        </div>
                    </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Дата начала</th>
                                <th>Дата окончания</th>
                                <th>Количество заказов</th>
                                <th>Сумма расходов</th>
                                <th>Сумма заказов</th>
                                <th>Доход</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($sale_orders){?>
                            <?php foreach ($sale_orders as $sale_order){?>
                                <tr>
                                    <td><?php echo $sale_order['date_start'];?></td>
                                    <td><?php echo $sale_order['date_end'];?></td>
                                    <td><?php echo $sale_order['orders'];?></td>
                                    <td><?php echo $sale_order['total_delivery'];?></td>
                                    <td><?php echo $sale_order['total'] - $sale_order['total_commission'];?></td>
                                    <td><?php echo $sale_order['total'] - $sale_order['total_delivery'] - $sale_order['total_commission'];?></td>
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
