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
        <li><a href="/autoxadmin/news"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $news['name'];?></a></li>
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
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name', $news['name']); ?>" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_description'); ?></label>
                        <textarea class="textarea" name="description"><?php echo set_value('description',$news['description']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_h1'); ?></label>
                        <input type="text" class="form-control" name="h1" value="<?php echo set_value('h1',$news['h1']); ?>" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_title'); ?></label>
                        <input type="text" class="form-control" name="title" value="<?php echo set_value('title',$news['title']); ?>" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_meta_description'); ?></label>
                        <textarea class="form-control" name="meta_description"><?php echo set_value('meta_description',$news['meta_description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_meta_keywords'); ?></label>
                        <input type="text" class="form-control" name="meta_keywords" value="<?php echo set_value('meta_keywords',$news['meta_keywords']); ?>" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_slug'); ?></label>
                        <input type="text" class="form-control" name="slug" value="<?php echo set_value('slug', $news['slug']); ?>" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_sort'); ?></label>
                        <input type="number" class="form-control" name="sort" value="<?php echo set_value('sort', $news['sort']); ?>" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_created_at'); ?></label>
                        <input type="date" class="form-control" name="created_at" value="<?php echo set_value('created_at', date('Y-m-d',strtotime($news['created_at']))); ?>">
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="status" value="1" <?php echo set_checkbox('status', 1, (bool)$news['status']);?>>
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