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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home'); ?></a></li>
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
                        <div class="form-group">
                            <label>Copyright</label>
                            <input type="text" name="settings[contact_settings][copyright]"
                                   value="<?php echo set_value('settings[contact_settings][copyright]', @$settings['contact_settings']['copyright']); ?>"
                                   class="form-control">
                        </div>
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane" id="additional">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading5">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse6" aria-expanded="true" aria-controls="collapse6">
                                            Новая почта
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse6" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading5">
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>API Key</label>
                                                <input type="text" name="settings[np][api_key]"
                                                       value="<?php echo set_value('settings[np][api_key]', @$settings['np']['api_key']); ?>"
                                                       class="form-control">
                                            </div>
                                            <?php if (@$settings['np']['api_key']) { ?>
                                                <div class="form-group">
                                                    <label>Идентификатор города отправителя</label>
                                                    <input id="CitySender" onkeyup="getCity($(this).val())" type="text"
                                                           name="settings[np][CitySender]"
                                                           value="<?php echo set_value('settings[np][CitySender]', @$settings['np']['CitySender']); ?>"
                                                           class="form-control"
                                                    placeholder="Начните вводить для поиска">
                                                    <div class="ciry-res">

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Идентификатор отправителя</label>
                                                    <input id="Sender" onclick="getSenders()" type="text"
                                                           name="settings[np][Sender]"
                                                           value="<?php echo set_value('settings[np][Sender]', @$settings['np']['Sender']); ?>"
                                                           class="form-control" placeholder="Клик для поиска">
                                                    <div class="sender-res">

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Идентификатор контактного лица отправителя</label>
                                                    <input onclick="getContactSender($('#Sender').val())" id="ContactSender"
                                                           type="text" name="settings[np][ContactSender]"
                                                           value="<?php echo set_value('settings[np][ContactSender]', @$settings['np']['ContactSender']); ?>"
                                                           class="form-control" placeholder="Клик для поиска">
                                                    <div class="ContactSender-res">

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Идентификатор адреса отправителя</label>
                                                    <input onclick="getSenderAddress($('#CitySender').val())" id="SenderAddress" type="text"
                                                           name="settings[np][SenderAddress]"
                                                           value="<?php echo set_value('settings[np][SenderAddress]', @$settings['np']['SenderAddress']); ?>"
                                                           class="form-control" placeholder="Клик для поиска">
                                                    <div class="SenderAddress-res">

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Телефон отправителя в формате: +380660000000, 380660000000,
                                                        0660000000</label>
                                                    <input id="SendersPhone" type="text"
                                                           name="settings[np][SendersPhone]"
                                                           value="<?php echo set_value('settings[np][SendersPhone]', @$settings['np']['SendersPhone']); ?>"
                                                           class="form-control">
                                                </div>

                                                <script>
                                                    var timeout = null;

                                                    function getSenders(term) {
                                                        $.ajax({
                                                            url: '/delivery/np/getSenders',
                                                            success: function (json) {
                                                                console.log(json);
                                                                var html = '';
                                                                $(json).each(function (index, item) {
                                                                    html += '<a href="#" onclick="$(\'#Sender\').val(\'' + item['Ref'] + '\'); $(\'.sender-res\').empty();return false;">' + item['Description'] + '</a><br>';
                                                                });
                                                                $(".sender-res").html(html);
                                                            }
                                                        });
                                                    }

                                                    function getContactSender(Ref) {
                                                        $.ajax({
                                                            url: '/delivery/np/getContactSender',
                                                            data: {Ref: Ref},
                                                            success: function (json) {
                                                                var html = '';
                                                                $(json).each(function (index, item) {
                                                                    html += '<a href="#" onclick="$(\'#ContactSender\').val(\'' + item['Ref'] + '\');$(\'#SendersPhone\').val(\'' + item['Phones'] + '\'); $(\'.ContactSender-res\').empty();return false;">' + item['Description'] + '</a><br>';
                                                                });
                                                                $(".ContactSender-res").html(html);
                                                            }
                                                        })
                                                    }

                                                    function getCity(term) {
                                                        clearTimeout(timeout);
                                                        timeout = setTimeout(function () {
                                                            if (term.length > 2) {
                                                                $.ajax({
                                                                    url: '/delivery/np/searchSettlements',
                                                                    data: {city: term},
                                                                    success: function (json) {
                                                                        console.log(json);
                                                                        var html = '';
                                                                        $(json).each(function (index, item) {
                                                                            html += '<a href="#" onclick="$(\'#CitySender\').val(\'' + item['DeliveryCity'] + '\'); $(\'.ciry-res\').empty();return false;">' + item['Present'] + '</a><br>';
                                                                        });
                                                                        $(".ciry-res").html(html);
                                                                    }
                                                                })
                                                            }
                                                        }, 1000);
                                                    }

                                                    function getSenderAddress(Ref) {
                                                        $.ajax({
                                                            url: '/delivery/np/getSenderAddress',
                                                            data: {Ref: Ref},
                                                            success: function (json) {
                                                                console.log(json);
                                                                var html = '';
                                                                $(json).each(function (index, item) {
                                                                    html += '<a href="#" onclick="$(\'#SenderAddress\').val(\'' + item['Ref'] + '\');$(\'.SenderAddress-res\').empty();return false;">' + item['Description'] + '</a><br>';
                                                                });
                                                                $(".SenderAddress-res").html(html);
                                                            }
                                                        })
                                                    }
                                                </script>
                                            <?php } else { ?>
                                                Сохраните ключ API для отображения доп. настроек
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                            <h4>smsc.ru</h4>
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

                                            <hr>
                                            <h4>turbosms.ua</h4>
                                            <div class="form-group">
                                                <label>Login</label>
                                                <input class="form-control" type="text" name="settings[sms2][login]"
                                                       value="<?php echo set_value('settings[sms2][login]', @$settings['sms2']['login']); ?>"
                                                       placeholder="login">
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input class="form-control" type="text" name="settings[sms2][password]"
                                                       value="<?php echo set_value('settings[sms2][password]', @$settings['sms2']['password']); ?>"
                                                       placeholder="password">
                                            </div>
                                            <div class="form-group">
                                                <label>Sender ID</label>
                                                <input class="form-control" type="text" name="settings[sms2][sender]"
                                                       value="<?php echo set_value('settings[sms2][sender]', @$settings['sms2']['sender']); ?>"
                                                       placeholder="sender">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo lang('text_sms_description'); ?>

                                            <hr>

                                            <?php echo lang('text_sms2_description'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading2">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse3" aria-expanded="true" aria-controls="collapseOne">
                                            Форма пополнения баланса
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse3" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading2">
                                    <div class="panel-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <textarea class="textarea"
                                                          name="settings[recharge]"><?php echo set_value('settings[sms][login]', @$settings['recharge']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            Данный текст или форму будет видеть клиент в личном кабинете в разделе
                                            пополнить счет.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading2">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse5" aria-expanded="true" aria-controls="collapseOne">
                                            Пользовательское соглашение
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse5" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading2">
                                    <div class="panel-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <textarea class="textarea"
                                                          name="settings[terms_of_use]"><?php echo set_value('settings[terms_of_use]', @$settings['terms_of_use']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            Если данное поле не пустое, клиент не сможет оформить заказ , пока не
                                            согласится с ним.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading6">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse7" aria-expanded="true" aria-controls="collapse7">
                                            Autox Cross
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse7" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading7">
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>API KEY</label>
                                                <input type="text" name="settings[autox][api_key_cross]"
                                                       value="<?php echo set_value('settings[autox][api_key_cross]', @$settings['autox']['api_key_cross']); ?>"
                                                       class="form-control">
                                                <?php if($autox_cross_user_info){?>
                                                    <ul>
                                                        <li>Доступ к базе: <?php echo  $autox_cross_user_info->status ? '<b style="color:green;">Полный доступ</b>' : '<b style="color:red;">Только к своим</b> <a href="https://autox.pro/cross#price" target="_blank">Подать заявку на полный доступ</a>';?></li>
                                                    </ul>
                                                    <a href="https://api.autox.pro/user" target="_blank">Кабинет</a>
                                                <?php }else{?>
                                                    <p>Накопительная база кроссов с кэшбеком. <a href="https://api.autox.pro/user/registration" target="_blank">Получить API KEY</a> </p>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading6">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse8" aria-expanded="true" aria-controls="collapse8">
                                            Liqpay
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse8" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading7">
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>public_key</label>
                                                <input type="text" name="settings[liqpay][public_key]"
                                                       value="<?php echo set_value('settings[liqpay][public_key]', @$settings['liqpay']['public_key']); ?>"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>merchant_public_key</label>
                                                <input type="text" name="settings[liqpay][merchant_public_key]"
                                                       value="<?php echo set_value('settings[liqpay][merchant_public_key]', @$settings['liqpay']['merchant_public_key']); ?>"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Комиссия</label>
                                                <input type="text" name="settings[liqpay][commission]"
                                                       value="<?php echo set_value('settings[liqpay][commission]', @$settings['liqpay']['commission']); ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                                    <textarea class="form-control"
                                                              name="settings[options][analytics]"><?php echo @$settings['options']['analytics']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Код отслеживания посетителей Google analytics
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Google tag manager <i>head</i></label>
                                                    <textarea class="form-control"
                                                              name="settings[options][google_tag_head]"><?php echo @$settings['options']['google_tag_head']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Код Google tag manager в раздел <i>head</i>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Google tag manager <i>body</i></label>
                                                    <textarea class="form-control"
                                                              name="settings[options][google_tag_body]"><?php echo @$settings['options']['google_tag_body']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Код Google tag manager в раздел <i>body</i>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Style</label>
                                                    <textarea class="form-control"
                                                              name="settings[options][style]"><?php echo @$settings['options']['style']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Собственные стили
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Текст сообщение в шапке</label>
                                                    <textarea class="form-control"
                                                              name="settings[options][head_text_messages]"><?php echo @$settings['options']['head_text_messages']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Сообщение в шапке сайта на всех страницах
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"
                                                                   name="settings[options][order_only_registered]"
                                                                   value="1" <?php echo set_checkbox('settings[options][order_only_registered]', true, (bool)@$settings['options']['order_only_registered']); ?>>
                                                            <?php echo lang('text_order_only_registered'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo lang('text_order_only_registered_description'); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="settings[options][top_sellers]"
                                                                   value="1" <?php echo set_checkbox('settings[options][top_sellers]', true, (bool)@$settings['options']['top_sellers']); ?>>
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
                                                            <input type="checkbox" name="settings[options][novelty]"
                                                                   value="1" <?php echo set_checkbox('settings[options][novelty]', true, (bool)@$settings['options']['novelty']); ?>>
                                                            Новые товары
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Отображать блок новые товары на главной
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"
                                                                   name="settings[options][show_customer_group_type]"
                                                                   value="1" <?php echo set_checkbox('settings[options][show_customer_group_type]', true, (bool)@$settings['options']['show_customer_group_type']); ?>>
                                                            Отображать скидку или наценку клиента
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Отображать уровень скидки или наценки клиента в личном кабинете
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"
                                                                   name="settings[options][check_day_off]"
                                                                   value="1" <?php echo set_checkbox('settings[options][check_day_off]', true, (bool)@$settings['options']['check_day_off']); ?>>
                                                            Учет выходного дня
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                При отображении срока поставки уичитывать выходные дни СБ. ВС.
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"
                                                                   name="settings[options][show_currency_rate]"
                                                                   value="1" <?php echo set_checkbox('settings[options][show_currency_rate]', true, (bool)@$settings['options']['show_currency_rate']); ?>>
                                                            Отображать курс Валют
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Отображение курса Валют в шапке магазина для посетителей
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"
                                                                   name="settings[options][use_tecdoc_name]"
                                                                   value="1" <?php echo set_checkbox('settings[options][use_tecdoc_name]', true, (bool)@$settings['options']['use_tecdoc_name']); ?>>
                                                            Названия с ТекДок
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Использовать названия с текдок по умолчанию если они есть.
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"
                                                                   name="settings[options][show_fast_order_search]"
                                                                   value="1" <?php echo set_checkbox('settings[options][show_fast_order_search]', true, (bool)@$settings['options']['show_fast_order_search']); ?>>
                                                            Кнопка быстрого заказа при поиске
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Отображать кнопку быстрога заказа на странице поиска
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"
                                                                   name="settings[options][show_tecdoc_product_without_price]"
                                                                   value="1" <?php echo set_checkbox('settings[options][show_tecdoc_product_without_price]', true, (bool)@$settings['options']['show_tecdoc_product_without_price']); ?>>
                                                            Отображать товары без цен в категориях
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Отображать товары без цены в своих категориях и категориях текдок
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"
                                                                   name="settings[options][show_callback]"
                                                                   value="1" <?php echo set_checkbox('settings[options][show_callback]', true, (bool)@$settings['options']['show_callback']); ?>>
                                                            Отображать кнопку обратной связи
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Отображать кнопку обратной связи
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        <input type="text" class="form-control"
                                                               name="settings[options][phonemask]"
                                                               value="<?php echo set_value('settings[options][phonemask]', @$settings['options']['phonemask']); ?>">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                Маска ввода номера телефона
                                                <small>(пример: 38(999)999-99-99)</small>
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
