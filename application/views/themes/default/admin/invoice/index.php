<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>

<section class="content-header">
    <h1>
       <?php echo lang('text_nav_invoice');?>
    </h1>

    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li class="active"><?php echo lang('text_nav_invoice');?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">
            <?php echo form_open('/autoxadmin/invoice',['method' => 'get']);?>
            <div class="well">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Дата с</label>
                            <input type="date" name="date_from" value="<?php echo $this->input->get('date_from',true);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Статус</label>

                            <select name="status_id" class="form-control">
                                <option></option>
                                <?php foreach ($statuses as $id => $status){?>
                                    <option <?php if($this->input->get('status_id') === (string)$id){?>selected<?php } ?> value="<?php echo $id;?>"><?php echo $status;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Дата по</label>
                            <input type="date" name="date_to" value="<?php echo $this->input->get('date_to',true);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Клиент</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="ФИО телефон email" value="<?php echo $this->input->get('customer_name');?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ID клиента</label>
                            <input type="text" name="customer_id" value="<?php echo $this->input->get('customer_id',true);?>" class="form-control">
                        </div>
                        <div class="pull-right">
                            <?php if($this->input->get()){?>
                                <a href="/autoxadmin/invoice" class="btn btn-danger">Сброс</a>
                            <?php } ?>

                            <button type="submit" class="btn btn-info">Фильтр</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>

            <table class="table table-bordered">
                <tbody><tr>
                    <th style="width: 10px">ID</th>
                    <th>Сумма</th>
                    <th>Клиент</th>
                    <td>Баланс</td>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th></th>
                </tr>


                <?php if($invoices){?>
                    <?php foreach ($invoices as $invoice){?>
                        <tr>
                            <td><?php echo $invoice['id'];?></td>
                            <td><?php echo $this->invoice_model->getTotal($invoice['id']);?></td>
                            <td>
                                ID<?php echo $invoice['customer_id'];?>
                                <a target="_blank" href="/autoxadmin/customer/edit/<?php echo $invoice['customer_id'];?>">
                                    <?php echo $invoice['customer_name'];?></a><br/>
                            </td>
                            <td><?php echo format_balance($invoice['balance']);?></td>
                            <td>
                                <?php echo $invoice['created_at'];?><br/>
                            </td>
                            <td><?php echo $statuses[$invoice['status_id']];?></td>
                            <td class="text-right">
                                <a href="/autoxadmin/invoice/delete/<?php echo $invoice['id'];?>" class="confirm btn btn-danger" title="Удалить"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                <a class="btn btn-info" href="/autoxadmin/invoice/edit/<?php echo $invoice['id'];?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                </tbody></table>
        </div><!-- /.box-body -->
        <div class="box-footer">
            <?php echo $this->pagination->create_links();?>
        </div><!-- /.box-footer-->
    </div><!-- /.box -->

</section><!-- /.content -->
