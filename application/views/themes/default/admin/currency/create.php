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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="/autoxadmin/currency"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo lang('button_add');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open();?>
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label><?php echo lang('text_name'); ?></label>
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name'); ?>" maxlength="32">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_code'); ?></label>
                        <input required type="text" class="form-control" name="code" value="<?php echo set_value('code'); ?>" maxlength="3">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_symbol_left'); ?></label>
                        <input type="text" class="form-control" name="symbol_left" value="<?php echo set_value('symbol_left'); ?>" maxlength="12">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_symbol_right'); ?></label>
                        <input type="text" class="form-control" name="symbol_right" value="<?php echo set_value('symbol_right'); ?>" maxlength="12">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_decimal_place'); ?></label>
                        <input required type="text" class="form-control" name="decimal_place" value="<?php echo set_value('decimal_place'); ?>" maxlength="1">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_value'); ?></label>
                        <input required type="text" class="form-control" name="value" value="<?php echo set_value('text_value'); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
        </form>
    </div>
</section><!-- /.content -->
<script>

</script>