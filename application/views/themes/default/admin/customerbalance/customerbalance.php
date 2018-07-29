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
        <small>клиентов</small>
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
            <h3 class="box-title">Баланс клиентов</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tbody><tr>
                    <th style="width: 10px">#</th>
                    <th>Тип</th>
                    <th>Сумма</th>
                    <th>Описание</th>
                    <th>Клиент</th>
                    <th>Дата транзакции</th>
                    <th>Дата добавления</th>
                    <th class="text-right"><a href="/autoxadmin/customerbalance/create" class="btn btn-info">Добавить</a></th>
                </tr>
                <?php echo form_open('/autoxadmin/customerbalance',['method' => 'get']);?>
                <tr>
                    <td></td>
                    <td>
                        <select name="type" class="form-control">
                            <option></option>
                            <?php foreach ($types as $type_id => $type_name){?>
                                <option value="<?php echo $type_id;?>" <?php if($type_id == $this->input->get('type')){?>selected<?php } ?>><?php echo $type_name;?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="value" value="<?php echo $this->input->get('value', true);?>" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="description" value="<?php echo $this->input->get('description',true);?>" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="customer_name" value="<?php echo $this->input->get('customer_name',true);?>" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="created_at" value="<?php echo $this->input->get('created_at',true);?>" class="form-control">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-link">Поиск</button>
                        <a href="/autoxadmin/customerbalance">Сброс</a>
                    </td>
                </tr>
                </form>
                <?php if($balances){?>
                    <?php foreach ($balances as $balance){?>
                        <tr style="border-left: 2px solid <?php if($balance['type'] == 1){?>green<?php }else{?>red<?php } ?>">
                            <td><?php echo $balance['id'];?></td>
                            <td><?php echo $types[$balance['type']];?></td>
                            <td>
                                <?php if($balance['type'] == 1){?>
                                    <span class="label label-success"><?php echo $balance['value'];?></span>
                                <?php }else{?>
                                    <span class="label label-danger">-<?php echo $balance['value'];?></span>
                                <?php } ?>
                            </td>
                            <td><small><?php echo $balance['description'];?></small></td>
                            <td>
                                <a target="_blank" href="/autoxadmin/customer/edit/<?php echo $balance['customer_id'];?>"><?php echo $balance['customer_name'];?></a><br/>
                                <small>(<?php echo $balance['balance'];?>)</small>
                            </td>
                            <td>
                                <?php echo $balance['transaction_created_at'];?><br/>
                            </td>
                            <td>
                                <?php echo $balance['created_at'];?><br/>
                                <?php if($balance['user']){?>
                                    <small><?php echo $balance['user'];?></small>
                                <?php }else{?>
                                    <small style="color: red">system auto</small>
                                <?php } ?>

                            </td>
                            <td class="text-right">
                                <a class="btn btn-danger confirm" data-confirm="Удалить? При удалении, баланс клиента не пересчитывается" href="/autoxadmin/customerbalance/delete/<?php echo $balance['id'];?>"><?php echo lang('button_delete');?></a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php }else{?>
                    empty
                <?php } ?>

                </tbody></table>
        </div><!-- /.box-body -->
        <div class="box-footer">
            <?php echo $this->pagination->create_links();?>
        </div><!-- /.box-footer-->
    </div><!-- /.box -->

</section><!-- /.content -->
