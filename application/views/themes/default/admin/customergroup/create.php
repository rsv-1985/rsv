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
        <li><a href="/autoxadmin/customergroup"><?php echo lang('text_heading');?></a></li>
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
                        <label><?php echo lang('text_type'); ?></label>
                        <select name="type" class="form-control">
                            <?php foreach($types as $key => $value){?>
                                <option value="<?php echo $key;?>" <?php echo set_select('type', $key);?>><?php echo $value;?></option>
                            <?php } ?>
                        </select>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_value'); ?></label>
                        <input type="number" class="form-control" name="value" value="<?php echo set_value('value', 0); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_fix_value'); ?></label>
                        <input type="text" class="form-control" name="fix_value" value="<?php echo set_value('fix_value', 0); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_default" value="1" <?php echo set_checkbox('is_default', 1);?>>
                                <?php echo lang('text_use_default');?>
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