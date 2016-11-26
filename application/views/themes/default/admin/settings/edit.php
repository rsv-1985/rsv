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
        <li><a href="/autoxadmin/settings"><?php echo lang('text_heading'); ?></a></li>
        <li><a href="#"><?php echo lang('button_add'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<?php echo form_open(); ?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab"><?php echo lang('text_tab_main'); ?></a></li>
                    <li><a href="#seo" data-toggle="tab"><?php echo lang('text_tab_seo'); ?></a></li>
                    <li><a href="#contact" data-toggle="tab"><?php echo lang('text_tab_contact'); ?></a></li>
                    <li><a href="#additional" data-toggle="tab"><?php echo lang('text_tab_additional'); ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="main">
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_name'); ?></label>
                            <input type="text" name="settings[main_settings][name]"
                                   value="<?php echo set_value('settings[main][name]', $settings['main_settings']['name']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_description'); ?></label>
                            <textarea class="textarea"
                                      name="settings[main_settings][description]"><?php echo set_value('settings[main][description]', $settings['main_settings']['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_title'); ?></label>
                            <input type="text" name="settings[main_settings][title]"
                                   value="<?php echo set_value('settings[main][title]', $settings['main_settings']['title']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_meta_description'); ?></label>
                            <textarea class="form-control"
                                      name="settings[main_settings][meta_description]"><?php echo set_value('settings[main][meta_description]', $settings['main_settings']['meta_description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_settings_main_meta_keywords'); ?></label>
                            <textarea class="form-control"
                                      name="settings[main_settings][meta_keywords]"><?php echo set_value('settings[main][meta_keywords]', $settings['main_settings']['meta_keywords']); ?></textarea>
                        </div>
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane" id="seo">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sitemap</label>
                                    <?php echo base_url('map/sitemap.xml'); ?> <a
                                        href="<?php echo base_url('sitemap'); ?>">generate new</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Robots.txt</label>
                                    <textarea class="form-control" name="robots"><?php echo $robots;?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Product SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_product][title]"
                                           value="<?php echo @$settings['seo_product']['title']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO description</label>
                                    <input type="text" name="settings[seo_product][description]"
                                           value="<?php echo @$settings['seo_product']['description']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_product][keywords]"
                                           value="<?php echo @$settings['seo_product']['keywords']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_product][h1]"
                                           value="<?php echo @$settings['seo_product']['h1']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO text</label>
                                    <input type="text" name="settings[seo_product][text]"
                                           value="<?php echo @$settings['seo_product']['text']; ?>"
                                           class="form-control">
                                </div>
                                <p class="help-block">
                                    {name} - название<br>
                                    {brand} - производитель<br>
                                    {sku} - артикул<br>
                                    {description} - орисание
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h4>Brand SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_brand][title]"
                                           value="<?php echo @$settings['seo_brand']['title']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO Description</label>
                                    <input type="text" name="settings[seo_brand][description]"
                                           value="<?php echo @$settings['seo_brand']['description']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_brand][keywords]"
                                           value="<?php echo @$settings['seo_brand']['keywords']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_brand][h1]"
                                           value="<?php echo @$settings['seo_brand']['h1']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO text</label>
                                    <input type="text" name="settings[seo_brand][text]"
                                           value="<?php echo @$settings['seo_brand']['text']; ?>" class="form-control">
                                </div>
                                <p class="help-block">
                                    {brand} - Название произаодителя<br>
                                    {category} - Название категории<br>
                                </p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Tecdoc SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_tecdoc][title]"
                                           value="<?php echo @$settings['seo_tecdoc']['title']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO Description</label>
                                    <input type="text" name="settings[seo_tecdoc][description]"
                                           value="<?php echo @$settings['seo_tecdoc']['description']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_tecdoc][keywords]"
                                           value="<?php echo @$settings['seo_tecdoc']['keywords']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_tecdoc][h1]"
                                           value="<?php echo @$settings['seo_tecdoc']['h1']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO text</label>
                                    <input type="text" name="settings[seo_tecdoc][text]"
                                           value="<?php echo @$settings['seo_tecdoc']['text']; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Tecdoc Manufacturer SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_tecdoc_manufacturer][title]"
                                           value="<?php echo @$settings['seo_tecdoc_manufacturer']['title']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO Description</label>
                                    <input type="text" name="settings[seo_tecdoc_manufacturer][description]"
                                           value="<?php echo @$settings['seo_tecdoc_manufacturer']['description']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_tecdoc_manufacturer][keywords]"
                                           value="<?php echo @$settings['seo_tecdoc_manufacturer']['keywords']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_tecdoc_manufacturer][h1]"
                                           value="<?php echo @$settings['seo_tecdoc_manufacturer']['h1']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO text</label>
                                    <input type="text" name="settings[seo_tecdoc_manufacturer][text]"
                                           value="<?php echo @$settings['seo_tecdoc_manufacturer']['text']; ?>"
                                           class="form-control">
                                </div>
                                <p class="help-block">
                                    {manuf} - Название производителя<br>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Tecdoc Model SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_tecdoc_model][title]"
                                           value="<?php echo @$settings['seo_tecdoc_model']['title']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO Description</label>
                                    <input type="text" name="settings[seo_tecdoc_model][description]"
                                           value="<?php echo @$settings['seo_tecdoc_model']['description']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_tecdoc_model][keywords]"
                                           value="<?php echo @$settings['seo_tecdoc_model']['keywords']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_tecdoc_model][h1]"
                                           value="<?php echo @$settings['seo_tecdoc_model']['h1']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO text</label>
                                    <input type="text" name="settings[seo_tecdoc_model][text]"
                                           value="<?php echo @$settings['seo_tecdoc_model']['text']; ?>"
                                           class="form-control">
                                </div>
                                <p class="help-block">
                                    {manuf} - Название производителя<br>
                                    {model} - Название модели<br>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h4>Tecdoc Type SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_tecdoc_type][title]"
                                           value="<?php echo @$settings['seo_tecdoc_type']['title']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO Description</label>
                                    <input type="text" name="settings[seo_tecdoc_type][description]"
                                           value="<?php echo @$settings['seo_tecdoc_type']['description']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_tecdoc_type][keywords]"
                                           value="<?php echo @$settings['seo_tecdoc_type']['keywords']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_tecdoc_type][h1]"
                                           value="<?php echo @$settings['seo_tecdoc_type']['h1']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO text</label>
                                    <input type="text" name="settings[seo_tecdoc_type][text]"
                                           value="<?php echo @$settings['seo_tecdoc_type']['text']; ?>"
                                           class="form-control">
                                </div>
                                <p class="help-block">
                                    {manuf} - Manufacturer name<br>
                                    {model} - Model name<br>
                                    {type} - Type name
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Tecdoc Tree SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_tecdoc_tree][title]"
                                           value="<?php echo @$settings['seo_tecdoc_tree']['title']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO Description</label>
                                    <input type="text" name="settings[seo_tecdoc_tree][description]"
                                           value="<?php echo @$settings['seo_tecdoc_tree']['description']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_tecdoc_tree][keywords]"
                                           value="<?php echo @$settings['seo_tecdoc_tree']['keywords']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_tecdoc_tree][h1]"
                                           value="<?php echo @$settings['seo_tecdoc_tree']['h1']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO text</label>
                                    <input type="text" name="settings[seo_tecdoc_tree][text]"
                                           value="<?php echo @$settings['seo_tecdoc_tree']['text']; ?>"
                                           class="form-control">
                                </div>
                                <p class="help-block">
                                    {manuf} - Manufacturer name<br>
                                    {model} - Model name<br>
                                    {type} - Type name<br>
                                    {tree} - Tree name
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h4>VIN SEO template</h4>
                                <div class="form-group">
                                    <label>SEO title</label>
                                    <input type="text" name="settings[seo_vin][title]"
                                           value="<?php echo @$settings['seo_vin']['title']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO Description</label>
                                    <input type="text" name="settings[seo_vin][description]"
                                           value="<?php echo @$settings['seo_vin']['description']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO keywords</label>
                                    <input type="text" name="settings[seo_vin][keywords]"
                                           value="<?php echo @$settings['seo_vin']['keywords']; ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO h1</label>
                                    <input type="text" name="settings[seo_vin][h1]"
                                           value="<?php echo @$settings['seo_vin']['h1']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>SEO text</label>
                                    <textarea class="form-control"
                                              name="settings[seo_vin][text]"><?php echo @$settings['seo_vin']['text']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane" id="contact">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="settings[contact_settings][email]"
                                   value="<?php echo set_value('settings[contact_settings][email]', @$settings['contact_settings']['email']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>phone</label>
                            <input type="text" name="settings[contact_settings][phone]"
                                   value="<?php echo set_value('settings[contact_settings][phone]', @$settings['contact_settings']['phone']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="settings[contact_settings][city]"
                                   value="<?php echo set_value('settings[contact_settings][city]', @$settings['contact_settings']['city']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="settings[contact_settings][address]"
                                   value="<?php echo set_value('settings[contact_settings][address]', @$settings['contact_settings']['address']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Map</label>
                            <input type="text" name="settings[contact_settings][map]"
                                   value="<?php echo set_value('settings[contact_settings][map]', @$settings['contact_settings']['map']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>TExt</label>
                            <textarea class="textarea"
                                      name="settings[contact_settings][description]"><?php echo set_value('settings[contact_settings][description]', @$settings['contact_settings']['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>VK</label>
                            <input type="text" name="settings[contact_settings][vk]"
                                   value="<?php echo set_value('settings[contact_settings][vk]', @$settings['contact_settings']['vk']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="text" name="settings[contact_settings][fb]"
                                   value="<?php echo set_value('settings[contact_settings][fb]', @$settings['contact_settings']['fb']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Google</label>
                            <input type="text" name="settings[contact_settings][google]"
                                   value="<?php echo set_value('settings[contact_settings][google]', @$settings['contact_settings']['google']); ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Instagram</label>
                            <input type="text" name="settings[contact_settings][instagram]"
                                   value="<?php echo set_value('settings[contact_settings][instagram]', @$settings['contact_settings']['instagram']); ?>"
                                   class="form-control">
                        </div>
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane" id="additional">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            SMS
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Login</label>
                                                <input class="form-control" type="text" name="settings[sms][login]"
                                                       value="<?php echo set_value('settings[sms][login]', @$settings['sms']['login']); ?>"
                                                       placeholder="login">
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input class="form-control" type="text" name="settings[sms][password]"
                                                       value="<?php echo set_value('settings[sms][password]', @$settings['sms']['password']); ?>"
                                                       placeholder="password">
                                            </div>
                                            <div class="form-group">
                                                <label>Sender ID</label>
                                                <input class="form-control" type="text" name="settings[sms][sender]"
                                                       value="<?php echo set_value('settings[sms][sender]', @$settings['sms']['sender']); ?>"
                                                       placeholder="sender">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo lang('text_sms_description'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading2">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse2" aria-expanded="true" aria-controls="collapseOne">
                                            <?php echo lang('text_scamdb_heading'); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse2" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading2">
                                    <div class="panel-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>access-token</label>
                                                <input class="form-control" type="text"
                                                       name="settings[scamdb][access_token]"
                                                       value="<?php echo set_value('settings[sms][login]', @$settings['scamdb']['access_token']); ?>"
                                                       placeholder="access-token">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo lang('text_scamdb_description'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading3">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                            <?php echo lang('text_tecdoc_manufacturer_heading'); ?>
                                        </a>
                                    </h4>
                                </div>
                                <?php if ($tecdoc_manufacturer) { ?>
                                    <div id="collapse3" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="heading3">
                                        <div class="panel-body">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?php foreach ($tecdoc_manufacturer as $tm) { ?>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox"
                                                                       name="settings[tecdoc_manufacturer][<?php echo $tm->ID_mfa; ?>]"
                                                                       value="1" <?php echo set_checkbox('settings[tecdoc_manufacturer][' . $tm->ID_mfa . ']', 1, isset($settings['tecdoc_manufacturer'][$tm->ID_mfa])); ?>>
                                                                <?php echo $tm->Name; ?><br>
                                                                <small><b>image:</b><a target="_blank"
                                                                                       href="/uploads/model/<?php echo $tm->Name; ?>.png">/uploads/model/<?php echo $tm->Name; ?>
                                                                        .png</a></small>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo lang('text_tecdoc_manufacturer_description'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading4">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                            Опции
                                        </a>
                                    </h4>
                                </div>

                                <div id="collapse4" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading4">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Google analytics</label>
                                                    <textarea class="form-control"  name="settings[options][analytics]"><?php echo @$settings['options']['analytics'];?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Код отслеживания посетителей Google analytics
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="settings[options][order_only_registered]" value="1" <?php echo set_checkbox('settings[options][order_only_registered]',true,(bool)@$settings['options']['order_only_registered']);?>>
                                                            <?php echo lang('text_order_only_registered');?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo lang('text_order_only_registered_description');?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="settings[options][top_sellers]" value="1" <?php echo set_checkbox('settings[options][top_sellers]',true,(bool)@$settings['options']['top_sellers']);?>>
                                                            Топ продаж на главной
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Отображать блок топ продаж на главной
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="settings[options][novelty]" value="1" <?php echo set_checkbox('settings[options][novelty]',true,(bool)@$settings['options']['novelty']);?>>
                                                            Новые товары
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Отображать блок новые товары на главной
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <button type="submit"
                                class="btn btn-info pull-right"><?php echo lang('button_submit'); ?></button>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
    </div>
</section><!-- /.content -->
</form>
