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
        <li><a href="/autoxadmin/delivery"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $payment['name'];?></a></li>
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
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name', $payment['name']); ?>" maxlength="250">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_description'); ?></label>
                        <textarea name="description" class="textarea"><?php echo set_value('description', $payment['description']);?></textarea>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_comission'); ?></label>
                        <input type="text" class="form-control" name="comission" value="<?php echo set_value('comission', $payment['comission']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_fix_cost'); ?></label>
                        <input type="text" class="form-control" name="fix_cost" value="<?php echo set_value('fix_cost', $payment['fix_cost']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_api'); ?></label>
                        <input type="text" class="form-control" name="api" value="<?php echo set_value('api', $payment['api']); ?>" maxlength="32">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_sort'); ?></label>
                        <input type="text" class="form-control" name="sort" value="<?php echo set_value('sort', $payment['sort']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                    </div>
                    <?php if($delivery_methods){?>
                        <div class="form-group"  style="display: none">
                            <label><?php echo lang('text_link_delivery');?></label>
                            <?php foreach ($delivery_methods as $dm){?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="<?php echo $dm['id'];?>" name="delivery_methods[]" <?php echo set_checkbox('delivery_methods[]', $dm['id'], (bool)@in_array($dm['id'],$payment['delivery_methods']));?>>
                                        <?php echo $dm['name'];?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div><!-- /.box-body -->
            </div>
        </div>
        </form>
    </div>
</section><!-- /.content -->
<script>

</script>