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
        <li><a href="/autoxadmin/delivery"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $delivery['name'];?></a></li>
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
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name', $delivery['name']); ?>" maxlength="250">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_description'); ?></label>
                        <textarea name="description" class="textarea"><?php echo set_value('description', $delivery['description']);?></textarea>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_price'); ?></label>
                        <input type="text" class="form-control" name="price" value="<?php echo set_value('price', $delivery['price']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_free_cost'); ?></label>
                        <input type="text" class="form-control" name="free_cost" value="<?php echo set_value('free_cost', $delivery['free_cost']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_api'); ?></label>
                        <input type="text" class="form-control" name="api" value="<?php echo set_value('api', $delivery['api']); ?>" maxlength="32">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_sort'); ?></label>
                        <input type="text" class="form-control" name="sort" value="<?php echo set_value('sort', $delivery['sort']); ?>">
                    </div><!-- /.form group -->
                    <?php if($payment_methods){?>
                        <div class="form-group">
                        <label><?php echo lang('text_link_payment');?></label>
                        <?php foreach ($payment_methods as $pm){?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="<?php echo $pm['id'];?>" name="payment_methods[]" <?php echo set_checkbox('payment_methods[]', $pm['id'], (bool)@in_array($pm['id'],$delivery['payment_methods']));?>>
                                    <?php echo $pm['name'];?>
                                </label>
                            </div>
                        <?php } ?>
                        </div>
                    <?php } ?>
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