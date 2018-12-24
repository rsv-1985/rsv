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
                            <label>ID расходной</label>
                            <input type="text" name="id" value="<?php echo $this->input->get('id',true);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>ID клиента</label>
                            <input type="text" name="customer_id" value="<?php echo $this->input->get('customer_id',true);?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Дата с</label>
                                <input type="date" name="date_from" value="<?php echo $this->input->get('date_from',true);?>" class="form-control">
                            </div>
                            <label>Дата по</label>
                            <input type="date" name="date_to" value="<?php echo $this->input->get('date_to',true);?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Статус</label>
                            <select name="status_id" class="form-control">
                                <option></option>
                                <?php foreach ($statuses as $id => $status){?>
                                    <option <?php if($this->input->get('status_id') === (string)$id){?>selected<?php } ?> value="<?php echo $id;?>"><?php echo $status;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="pull-right">
                            <?php if($this->input->get()){?>
                                <?php if($this->input->get('status_id') != 1){?>
                                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#change-status" title="Сменить статус"><i class="fa fa-edit"></i></a>
                                <?php } ?>

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
                    <th>Дата</th>
                    <th>Клиент</th>
                    <th>Баланс клиента</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th></th>
                </tr>


                <?php if($invoices){?>
                    <?php foreach ($invoices as $group => $items){?>
                        <tr><td colspan="7"><b><?php echo $group;?></b></td></tr>
                        <?php foreach ($items as $invoice){?>
                            <tr <?php if($invoice['status_id'] == 1){?>style="opacity: 0.5" <?php } ?>>
                                <td><?php echo $invoice['id'];?></td>
                                <td>
                                    <?php echo format_date($invoice['created_at']);?><br/>
                                </td>
                                <td>
                                    ID<?php echo $invoice['customer_id'];?>
                                    <a target="_blank" href="/autoxadmin/customer/edit/<?php echo $invoice['customer_id'];?>">
                                        <?php echo $invoice['customer_name'];?></a><br/>
                                </td>
                                <td><?php echo format_balance($invoice['balance']);?></td>
                                <td><?php echo $this->invoice_model->getTotal($invoice['id']);?></td>
                                <td><?php echo $statuses[$invoice['status_id']];?></td>
                                <td class="text-right">
                                    <a href="/autoxadmin/invoice/delete/<?php echo $invoice['id'];?>" class="confirm btn btn-danger" title="Удалить"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    <a class="btn btn-info" href="/autoxadmin/invoice/edit/<?php echo $invoice['id'];?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>

                </tbody></table>
        </div><!-- /.box-body -->
        <div class="box-footer">
            <?php echo $this->pagination->create_links();?>
        </div><!-- /.box-footer-->
    </div><!-- /.box -->

</section><!-- /.content -->
<div class="modal" id="change-status" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Применить к отфильтрованным</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo form_open('/autoxadmin/invoice/change_status?' . http_build_query($this->input->get()), ['id' => 'change_status']); ?>
                            <div class="form-group">
                                <label>Статус</label>
                                <select required name="status_id" class="form-control" onchange="checkVal($(this).val());" >
                                    <option></option>
                                    <?php foreach ($statuses as $status_id => $status_name){?>
                                        <option value="<?php echo $status_id;?>"><?php echo $status_name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="product_status_id" class="form-group" style="display:none;">
                                <label>Статус деталей</label>
                                <select name="product_status_id" class="form-control">
                                    <?php foreach ($order_statuses as $status){?>
                                        <option value="<?php echo $status['id'];?>"><?php echo $status['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                <button type="submit" form="change_status" class="btn btn-primary">Применить</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    $("#change_status").submit(function(event){
        event.preventDefault();
       $.ajax({
           url: $(this).attr('action'),
           data: $(this).serialize(),
           method: 'post',
           success: function (response) {
               location.reload();
           }
       })
    });

    function checkVal(status_id) {
        if(status_id == 1){
                $('#product_status_id').show();
        }else{
                $('#product_status_id').hide();
        }
    }
</script>