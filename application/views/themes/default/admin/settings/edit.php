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
                    <li><a href="#seo" data-toggle="tab"><?php echo lang('text_tab_seo');?></a></li>
                    <li><a href="#contact" data-toggle="tab"><?php echo lang('text_tab_contact');?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="main">
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_name');?></label>
                            <input type="text" name="settings[main_settings][name]" value="<?php echo set_value('settings[main][name]', $settings['main_settings']['name']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_description');?></label>
                            <textarea class="textarea" name="settings[main_settings][description]"><?php echo set_value('settings[main][description]', $settings['main_settings']['description']);?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_title');?></label>
                            <input type="text" name="settings[main_settings][title]" value="<?php echo set_value('settings[main][title]', $settings['main_settings']['title']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_meta_description');?></label>
                            <textarea class="form-control" name="settings[main_settings][meta_description]"><?php echo set_value('settings[main][meta_description]', $settings['main_settings']['meta_description']);?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_meta_keywords');?></label>
                            <textarea class="form-control" name="settings[main_settings][meta_keywords]"><?php echo set_value('settings[main][meta_keywords]', $settings['main_settings']['meta_keywords']);?></textarea>
                        </div>
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane" id="seo">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Product SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_product][title]" value="<?php echo @$settings['seo_product']['title'];?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO description</label>
                                    <input type="text" name="settings[seo_product][description]" value="<?php echo @$settings['seo_product']['description'];?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_product][keywords]" value="<?php echo @$settings['seo_product']['keywords'];?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_product][h1]" value="<?php echo @$settings['seo_product']['h1'];?>" class="form-control">
                                </div>
                                <p class="help-block">
                                    {name} - name<br>
                                    {brand} - brand<br>
                                    {sku} - sku<br>
                                    {description} - description<br>
                                    {excerpt} - excerpt
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h4>Brand SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_brand][title]" value="<?php echo @$settings['seo_brand']['title'];?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO Description</label>
                                    <input type="text" name="settings[seo_brand][description]" value="<?php echo @$settings['seo_brand']['description'];?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_brand][keywords]" value="<?php echo @$settings['seo_brand']['keywords'];?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_brand][h1]" value="<?php echo @$settings['seo_brand']['h1'];?>" class="form-control">
                                </div>
                                <p class="help-block">
                                    {brand} - brand<br>
                                </p>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Sitemap</label>
                                    <?php echo base_url('map/sitemap.xml');?> <a href="<?php echo base_url('sitemap');?>">generate new</a>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane" id="contact">
                        <div class="form-group">
                            <label>Email</label>
                            <input  type="email" name="settings[contact_settings][email]" value="<?php echo set_value('settings[contact_settings][email]', @$settings['contact_settings']['email']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>phone</label>
                            <input  type="text" name="settings[contact_settings][phone]" value="<?php echo set_value('settings[contact_settings][phone]', @$settings['contact_settings']['phone']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input  type="text" name="settings[contact_settings][city]" value="<?php echo set_value('settings[contact_settings][city]', @$settings['contact_settings']['city']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input  type="text" name="settings[contact_settings][address]" value="<?php echo set_value('settings[contact_settings][address]', @$settings['contact_settings']['address']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Map</label>
                            <input  type="text" name="settings[contact_settings][map]" value="<?php echo set_value('settings[contact_settings][map]', @$settings['contact_settings']['map']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>TExt</label>
                            <textarea class="textarea" name="settings[contact_settings][description]"><?php echo set_value('settings[contact_settings][description]', @$settings['contact_settings']['description']);?></textarea>

                        </div>
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
