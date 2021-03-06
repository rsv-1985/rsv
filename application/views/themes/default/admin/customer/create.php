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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="/autoxadmin/customer"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo lang('button_add');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <?php echo form_open();?>
    <div class="box">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label><?php echo lang('text_customer_group_id'); ?></label>
                    <select name="customer_group_id" class="form-control" required>
                        <?php foreach($customergroup as $group){?>
                            <option value="<?php echo $group['id'];?>" <?php echo set_select('customer_group_id',$group['id']);?>><?php echo $group['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_first_name'); ?></label>
                    <input type="text" class="form-control" name="first_name" value="<?php echo set_value('first_name'); ?>" maxlength="32">
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_second_name'); ?></label>
                    <input type="text" class="form-control" name="second_name" value="<?php echo set_value('second_name'); ?>" maxlength="32">
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_patronymic'); ?></label>
                    <input type="text" class="form-control" name="patronymic" value="<?php echo set_value('patronymic'); ?>" maxlength="255">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><?php echo lang('text_email'); ?></label>
                    <input type="email" class="form-control" name="email" value="<?php echo set_value('email'); ?>" maxlength="96">
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_phone'); ?></label>
                    <input type="text" class="form-control" name="phone" value="<?php echo set_value('phone'); ?>" maxlength="32">
                </div>

                <div class="form-group">
                    <label><?php echo lang('text_password'); ?></label>
                    <input required type="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" maxlength="255">
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_confirm_password'); ?></label>
                    <input required type="password" class="form-control" name="confirm_password" value="<?php echo set_value('confirm_password'); ?>" maxlength="255">
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="status">
                            <?php echo lang('text_status'); ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><?php echo lang('text_address'); ?></label>
                    <textarea class="form-control" name="address"><?php echo set_value('address');?></textarea>
                </div><!-- /.form group -->
                <div class="form-group">
                    <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                </div><!-- /.form group -->
            </div>
        </div><!-- /.box-body -->
    </div>
    </form>
</section><!-- /.content -->
<script>

</script>