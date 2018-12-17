<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home'); ?></a></li>
        <li><a href="/autoxadmin/customer"><?php echo lang('text_heading'); ?></a></li>
        <li><a href="#"><?php echo $customer['id']; ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <?php echo form_open(); ?>
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3  <?php if ($black_list_info) { ?>style="color: red" <?php } ?>><?php echo $customer['first_name'] . ' ' . $customer['second_name']; ?>
                                <div class="pull-right">
                                    <?php echo format_balance($customer['balance']);?>
                                </div>
                            </h3>
                            <?php if ($black_list_info) { ?>
                                <span class="label label-danger" style="text-align: center; width: 100%">
                                    <?php echo $black_list_info['comment']; ?>
                                </span>
                            <?php } ?>
                            <hr>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_customer_group_id'); ?></label>

                            <select name="customer_group_id" class="form-control" required>

                                <?php foreach ($customergroup as $group) { ?>
                                    <option value="<?php echo $group['id']; ?>" <?php echo set_select('customer_group_id', $group['id'], $customer['customer_group_id'] == $group['id']); ?>><?php echo $group['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_first_name'); ?></label>
                            <input type="text" class="form-control" name="first_name"
                                   value="<?php echo set_value('first_name', $customer['first_name']); ?>" maxlength="250">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_second_name'); ?></label>
                            <input type="text" class="form-control" name="second_name"
                                   value="<?php echo set_value('second_name', $customer['second_name']); ?>" maxlength="250">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_patronymic'); ?></label>
                            <input type="text" class="form-control" name="patronymic"
                                   value="<?php echo set_value('patronymic', $customer['patronymic']); ?>" maxlength="255">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_email'); ?></label>
                            <input type="email" class="form-control" name="email"
                                   value="<?php echo set_value('email', $customer['email']); ?>" maxlength="96">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_phone'); ?></label>
                            <input type="text" class="form-control" name="phone"
                                   value="<?php echo set_value('phone', $customer['phone']); ?>" maxlength="32" minlength="10">
                        </div>

                        <div class="form-group">
                            <label><?php echo lang('text_password'); ?></label>
                            <input type="password" class="form-control" name="password"
                                   value="<?php echo set_value('password'); ?>" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_confirm_password'); ?></label>
                            <input type="password" class="form-control" name="confirm_password"
                                   value="<?php echo set_value('confirm_password'); ?>" maxlength="255">
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="status"
                                           value="1" <?php echo set_checkbox('status', 1, (bool)$customer['status']); ?>>
                                    <?php echo lang('text_status'); ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="negative_balance"
                                           value="1" <?php echo set_checkbox('negative_balance', 1, (bool)$customer['negative_balance']); ?>>
                                    <?php echo lang('text_negative_balance'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php echo lang('text_address'); ?></label>
                            <textarea class="form-control"
                                      name="address"><?php echo set_value('address', $customer['address']); ?></textarea>
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <a href="/autoxadmin/customer/login/<?php echo $customer['id'];?>" class="btn btn-warning" title="Авторизоваться под клиентом" target="_blank"><i class="fa fa-sign-in" aria-hidden="true"></i></a>
                            <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit'); ?></button>
                        </div><!-- /.form group -->
                    </div>
                </div><!-- /.box-body -->
            </div>
            <?php echo form_close();?>
        </div>
        <div class="col-md-4">
            <?php if($orders){?>
            <div class="box box-default">
                <div class="box-header with-border">
                    <a class="pull-right" href="/autoxadmin/order?customer_id=<?php echo $customer['id'];?>">все</a>
                    <h3 class="box-title">Заказы</h3>
                </div>
                <div class="box-body">
                    <ul class="nav nav-stacked">
                        <?php foreach ($orders as $order){?>
                            <li>
                                <a href="/autoxadmin/order/edit/<?php echo $order['id'];?>">ID: <?php echo $order['id'];?>
                                 сумма: <b><?php echo $order['total'];?></b>
                                <span class="pull-right badge" style="background-color: <?php echo $statuses[$order['status']]['color'];?>"><?php echo $statuses[$order['status']]['name'];?></span></a>
                            </li>
                        <?php } ?>
                   </ul>
                </div>
            </div>
                <div class="box box-default">
                    <div class="box-header with-border">
                        <a class="pull-right" href="/autoxadmin/order/products?customer_id=<?php echo $customer['id'];?>">все</a>
                        <h3 class="box-title">Детали в работе</h3>
                    </div>
                    <div class="box-body">
                        <ul class="nav nav-stacked">
                            <?php if($statuses && $status_totals){?>
                                <?php foreach ($statuses as $status){?>
                                    <?php if($status_totals[$status['id']]['total'] > 0){?>
                                        <li><a href="/autoxadmin/order/products?customer_id=<?php echo $customer['id'];?>&product_status_id=<?php echo $status['id'];?>"> Количество: <?php echo $status_totals[$status['id']]['qty'];?> сумма: <b><?php echo $status_totals[$status['id']]['total'];?></b> <span class="pull-right badge" style="background-color: <?php echo $status['color'];?>"><?php echo $status['name'];?></span></a></li>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
            <?php } ?>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Быстрые ссылки</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="nav nav-stacked">
                        <li>
                            <?php if ($black_list_info) { ?>
                                <a href="#" onclick="deleteBlack(event)">Удалить из черного списка</a>
                            <?php }else{ ?>
                                <a href="#" onclick="addBlack(event)">В черный список</a>
                            <?php } ?>
                        </li>
                        <li> <a href="/autoxadmin/customer_pay?customer_id=<?php echo $customer['id']; ?>">Оплаты</a></li>
                        <li><a href="/autoxadmin/customerbalance/create?customer_id=<?php echo $customer['id']; ?>">Пополнить баланс</a></li>
                        <li> <a href="/autoxadmin/customerbalance?customer_id=<?php echo $customer['id']; ?>">История баланса</a></li>
                        <li><a href="/autoxadmin/invoice?customer_id=<?php echo $customer['id']; ?>">Расходные накладные</a></li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

</section><!-- /.content -->
<script>
    function deleteBlack(e) {
        e.preventDefault();
        $.ajax({
            url: '/autoxadmin/blacklist/delete',
            data: {customer_id: '<?php echo $customer['id'];?>'},
            method: 'post',
            success: function () {
                location.reload();
            }
        });
    }

    function addBlack(e){
        e.preventDefault();
        var comment = prompt('Комментарий');
        $.ajax({
            url: '/autoxadmin/blacklist/add',
            data: {comment:comment, customer_id:'<?php echo $customer['id'];?>'},
            method: 'post',
            success: function(response){
                location.reload();
            }
        });
    }
</script>