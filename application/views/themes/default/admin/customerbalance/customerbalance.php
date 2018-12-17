<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>

<section class="content-header">
    <h1>
        Баланс
        <small>история</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li class="active">Баланс</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <div class="pull-right">
                <a href="/autoxadmin/customerbalance/create" class="btn btn-info">Транзакция</a>
            </div>
        </div>
        <div class="box-body">
            <?php echo form_open('/autoxadmin/customerbalance',['method' => 'get']);?>
            <div class="well">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Тип</label>
                            <select name="type" class="form-control">
                                <option></option>
                                <?php foreach ($types as $type_id => $type_name){?>
                                    <option value="<?php echo $type_id;?>" <?php if($type_id == $this->input->get('type')){?>selected<?php } ?>><?php echo $type_name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Дата с</label>
                            <input type="date" name="date_from" value="<?php echo $this->input->get('date_from',true);?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Сумма</label>
                            <input type="text" name="value" value="<?php echo $this->input->get('value', true);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Дата по</label>
                            <input type="date" name="date_to" value="<?php echo $this->input->get('date_to',true);?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Описание</label>
                            <input type="text" name="description" value="<?php echo $this->input->get('description',true);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>ID клиента</label>
                            <input type="text" name="customer_id" value="<?php echo $this->input->get('customer_id',true);?>" class="form-control">
                        </div>
                        <div class="pull-right">
                            <?php if($this->input->get()){?>
                                <a href="/autoxadmin/customerbalance" class="btn btn-danger">Сброс</a>
                            <?php } ?>

                            <button type="submit" class="btn btn-info">Фильтр</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>

            <table class="table table-bordered">
                <tbody><tr>
                    <th style="width: 10px">ID транзакции</th>
                    <th>Сумма</th>
                    <th>Баланс</th>
                    <th>Описание</th>
                    <th>Клиент</th>
                    <th>Дата транзакции</th>
                    <th>Дата добавления</th>
                    <th>Автор</th>
                    <th></th>
                </tr>


                <?php if($balances){?>
                    <?php foreach ($balances as $balance){?>
                        <tr style="border-left: 2px solid <?php if($balance['type'] == 1){?>green<?php }else{?>red<?php } ?>">
                            <td><?php echo $balance['id'];?></td>
                            <td>
                                <?php if($balance['type'] == 1){?>
                                    <span class="label label-success"><?php echo $balance['value'];?></span>
                                <?php }else{?>
                                    <span class="label label-danger">-<?php echo $balance['value'];?></span>
                                <?php } ?>
                            </td>
                            <td><?php echo $balance['balance'];?></td>
                            <td><?php echo $balance['description'];?></td>
                            <td>
                                ID<?php echo $balance['customer_id'];?>
                                <a target="_blank" href="/autoxadmin/customer/edit/<?php echo $balance['customer_id'];?>">
                                    <?php echo $balance['customer_name'];?></a><br/>
                            </td>
                            <td>
                                <?php echo $balance['transaction_created_at'];?><br/>
                            </td>
                            <td>
                                <?php echo $balance['created_at'];?><br/>
                            </td>
                            <td>
                                <?php if($balance['user']){?>
                                    <small><?php echo $balance['user'];?></small>
                                <?php }else{?>
                                    <small style="color: red">system auto</small>
                                <?php } ?>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-danger confirm" href="/autoxadmin/customerbalance/delete/<?php echo $balance['id'];?>">Отменить</a>
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
