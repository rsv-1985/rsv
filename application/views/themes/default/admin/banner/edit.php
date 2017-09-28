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
        <li><a href="/autoxadmin/banner"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $banner['name'];?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open_multipart();?>
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label><?php echo lang('text_name'); ?></label>
                        <input type="text" class="form-control" name="name" value="<?php echo set_value('name', $banner['name']); ?>" maxlength="255">
                    </div><!-- /.form group -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_image'); ?></label>
                            <input type="file" name="userfile" class="form-control"/>
                        </div><!-- /.form group -->
                    </div>
                    <div class="col-md-6">
                        <img onerror="imgError(this);" src="/uploads/banner/<?php echo $banner['image'];?>" style="max-height: 100px;">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_description'); ?></label>
                        <textarea class="textarea" name="description"><?php echo set_value('description', $banner['description']);?></textarea>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <div class="checkbox">
                            <b><?php echo lang('text_show');?></b><br>
                            <label>
                                <input name="show_slider" type="checkbox" value="1" <?php echo set_checkbox('show_slider', 1, (bool)$banner['show_slider']);?>>
                                <?php echo lang('text_show_slider');?>
                            </label>
                            <label>
                                <input name="show_box" type="checkbox" value="1" <?php echo set_checkbox('show_box', 1, (bool)$banner['show_box']);?>>
                                <?php echo lang('text_show_box');?>
                            </label>
                            <label>
                                <input name="show_carousel" type="checkbox" value="1" <?php echo set_checkbox('show_carousel', 1, (bool)$banner['show_carousel']);?>>
                                <?php echo lang('text_show_carousel');?>
                            </label>
                            <label>
                                <input name="show_product" type="checkbox" value="1" <?php echo set_checkbox('show_product', 1, (bool)$banner['show_product']);?>>
                                <?php echo lang('text_show_product');?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_link'); ?></label>
                        <input type="text" name="link" class="form-control" value="<?php echo set_value('link', $banner['link']);?>">
                        <label><?php echo lang('text_new_window'); ?></label>
                        <input name="new_window" type="checkbox" value="1" <?php echo set_checkbox('new_window', 1, (bool)$banner['new_window']);?>>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_sort'); ?></label>
                        <input type="number" class="form-control" name="sort" value="<?php echo set_value('sort', $banner['sort']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input name="status" type="checkbox" value="1" <?php echo set_checkbox('status', 1, (bool)$banner['status']);?>>
                                <?php echo lang('text_status');?>
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