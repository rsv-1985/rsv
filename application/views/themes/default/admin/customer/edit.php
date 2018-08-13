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
    <?php echo form_open(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php echo $customer['first_name'] . ' ' . $customer['second_name']; ?>
                        <?php if($customer['balance'] >= 0){?>
                            <span class="label label-info pull-right" title="Баланс">
                                <?php echo($customer['balance']);?>
                            </span>
                        <?php }else{ ?>
                            <span class="label label-danger pull-right" title="Баланс">
                                <?php echo($customer['balance']);?>
                            </span>
                        <?php } ?>

                    </h2>

                        <div class="pull-right" style="text-align: right">
                            <?php if ($black_list_info) { ?>
                                 <span class="label label-danger">
                                     Черный список: <?php echo $black_list_info['comment']; ?>
                                 </span><br>
                                <a href="#" onclick="deleteBlack(event)">Удалить из списка</a>
                            <?php }else{ ?>
                                <a href="#" onclick="addBlack(event)">В черный список</a>
                            <?php } ?>
                        </div>

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
                    <a href="/autoxadmin/customerbalance/create?customer_id=<?php echo $customer['id']; ?>"
                       class="btn btn-danger pull-left">Баланс</a>
                    <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit'); ?></button>
                </div><!-- /.form group -->
            </div>
        </div><!-- /.box-body -->
    </div>
    </form>
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