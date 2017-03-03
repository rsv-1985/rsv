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
        <li><a href="/autoxadmin/page"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $page['name'];?></a></li>
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
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name', $page['name']); ?>" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_menu_title'); ?></label>
                        <input required type="text" class="form-control" name="menu_title" value="<?php echo set_value('menu_title', $page['menu_title']); ?>" maxlength="32">
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_parent_id'); ?></label>
                        <select name="parent_id" class="form-control">
                            <option></option>
                            <?php if($pages){?>
                                <?php foreach($pages as $p){?>
                                    <?php if($p['id'] != $page['id']){?>
                                        <option value="<?php echo $p['id'];?>" <?php echo set_select('parent_id', $p['id'], $p['id'] == $page['parent_id']);?>><?php echo $p['name'];?></option>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_description'); ?></label>
                        <textarea class="textarea" name="description"><?php echo set_value('description',$page['description']); ?></textarea>
                        {vin} - виджет формы запроса по VIN коду
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_h1'); ?></label>
                        <input type="text" class="form-control" name="h1" value="<?php echo set_value('h1',$page['h1']); ?>" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_title'); ?></label>
                        <input type="text" class="form-control" name="title" value="<?php echo set_value('title',$page['title']); ?>" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_meta_description'); ?></label>
                        <textarea class="form-control" name="meta_description"><?php echo set_value('meta_description',$page['meta_description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_meta_keywords'); ?></label>
                        <input type="text" class="form-control" name="meta_keywords" value="<?php echo set_value('meta_keywords',$page['meta_keywords']); ?>" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_slug'); ?></label>
                        <input type="text" class="form-control" name="slug" value="<?php echo set_value('slug', $page['slug']); ?>" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_link'); ?></label>
                        <input type="text" name="link" class="form-control" value="<?php echo set_value('link', $page['link']);?>">
                        <label><?php echo lang('text_new_window'); ?></label>
                        <input name="new_window" type="checkbox" value="1" <?php echo set_checkbox('new_window', 1, (bool)$page['new_window']);?>>
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_sort'); ?></label>
                        <input type="number" class="form-control" name="sort" value="<?php echo set_value('sort', $page['sort']); ?>" maxlength="255">
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="show_footer" value="1" <?php echo set_checkbox('show_footer', 1, (bool)$page['show_footer']);?>>
                                <?php echo lang('text_show_footer');?>
                            </label>
                            <label>
                                <input type="checkbox" name="show_for_user" value="1" <?php echo set_checkbox('show_for_user', 1, (bool)$page['show_for_user']);?>>
                                <?php echo lang('text_show_for_user');?>
                            </label>
                            <label>
                                <input type="checkbox" name="status" value="1" <?php echo set_checkbox('status', 1, (bool)$page['status']);?>>
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