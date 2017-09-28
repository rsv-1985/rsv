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
        <li><a href="/autoxadmin/settings"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo lang('button_add');?></a></li>
    </ol>
</section>
<!-- Main content -->
<?php echo form_open();?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab"><?php echo lang('text_tab_main');?></a></li>
                    <li style="display: none;"><a href="#seo" data-toggle="tab"><?php echo lang('text_tab_seo');?></a></li>
                    <li style="display: none;"><a href="#contact" data-toggle="tab"><?php echo lang('text_tab_contact');?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="main">
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_name');?></label>
                            <input type="text" name="settings[main_settings][name]" value="<?php echo set_value('settings[main][name]');?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_description');?></label>
                            <textarea class="textarea" name="settings[main_settings][description]"><?php echo set_value('settings[main][description]');?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_meta_description');?></label>
                            <textarea class="form-control" name="settings[main_settings][meta_description]"><?php echo set_value('settings[main][meta_description]');?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_meta_keywords');?></label>
                            <textarea class="form-control" name="settings[main_settings][meta_keywords]"><?php echo set_value('settings[main][meta_keywords]');?></textarea>
                        </div>
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane" id="seo">
                        2
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane" id="contact">
                        1
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
    </div>
</section><!-- /.content -->
</form>
