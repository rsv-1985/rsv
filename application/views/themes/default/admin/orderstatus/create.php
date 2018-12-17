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
        <li><a href="/autoxadmin/orderstatus"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo lang('button_add');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <?php echo form_open();?>
            <div class="box">
                <div class="box-body">
                    <!-- Color Picker -->
                    <div class="form-group">
                        <label><?php echo lang('text_name'); ?></label>
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name'); ?>" maxlength="32">
                    </div><!-- /.form group -->

                    <!-- Color Picker -->
                    <div class="form-group">
                        <label><?php echo lang('text_color'); ?></label>
                        <div class="input-group my-colorpicker2">
                            <input required type="text" class="form-control" name="color" value="<?php echo set_value('color'); ?>" maxlength="32">
                            <div class="input-group-addon">
                                <i></i>
                            </div>
                        </div><!-- /.input group -->
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label>Сортировка</label>
                        <input type="text" class="form-control" name="sort_order" value="<?php echo set_value('sort_order');?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_new" value="<?php echo set_value('is_new', true); ?>">
                                <?php echo lang('text_is_new'); ?>
                            </label>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_complete" value="<?php echo set_value('is_complete', true); ?>">
                                <?php echo lang('text_is_complete'); ?>
                            </label>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_return" value="<?php echo set_value('is_return', true); ?>">
                                <?php echo lang('text_is_return'); ?>
                            </label>
                        </div>
                    </div>
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