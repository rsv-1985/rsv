<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h3><?php echo $message_template['title']; ?></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="/autoxadmin/message_template"><?php echo lang('text_heading'); ?></a></li>
        <li><a href="#"><?php echo lang('button_add'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open(); ?>
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label><?php echo lang('text_subject'); ?></label>
                        <input required type="text" class="form-control" name="subject"
                               value="<?php echo set_value('subject', $message_template['subject']); ?>"
                               maxlength="255">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_text'); ?></label>
                        <textarea class="textarea"
                                  name="text"><?php echo set_value('text', $message_template['text']); ?></textarea>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_sms'); ?></label>
                        <textarea class="form-control"
                                  name="text_sms"><?php echo set_value('text_sms', $message_template['text_sms']); ?></textarea>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label>Доступные переменные</label><br/>
                        <?php if ($message_template['id'] == 1) { ?>
                            <strong>Номер заказа:&nbsp;</strong>{order_id}<br/>
                            <strong>ID клиента:</strong>{customer_id}<br/>
                            <strong>Имя:&nbsp;</strong>{first_name}<br/>
                            <strong>Фамилия:&nbsp;</strong>{last_name}<br/>
                            <strong>Отчество:&nbsp;</strong>{patronymic}<br/>
                            <strong>Телефон:&nbsp;</strong>{telephone}<br/>
                            <strong>Email:&nbsp;</strong>{email}<br/>
                            <strong>Комментарий:&nbsp;</strong>{comments}<br/>
                            <strong>Дата:&nbsp;</strong>{created_at}<br/>
                            <strong>Способ доставки:&nbsp;</strong>{delivery_method}<br/>
                            <strong>Способ оплаты:&nbsp;</strong>{payment_method}<br/>
                            <strong>Товары:&nbsp;</strong>{products}<br/>
                            <strong>Cтоимость доставки:&nbsp;</strong>{delivery_price}<br/>
                            <strong>Комиссия:&nbsp;</strong>{commission}<br/>
                            <strong>Сумма:&nbsp;</strong>{total}
                        <?php }else if($message_template['id'] == 2){?>
                            <strong>Номер заказа:&nbsp;</strong>{order_id}<br/>
                            <strong>ID клиента:</strong>{customer_id}<br/>
                            <strong>Имя:&nbsp;</strong>{first_name}<br/>
                            <strong>Фамилия:&nbsp;</strong>{last_name}<br/>
                            <strong>Телефон:&nbsp;</strong>{telephone}<br/>
                            <strong>Отчество:&nbsp;</strong>{patronymic}<br/>
                            <strong>Email:&nbsp;</strong>{email}<br/>
                            <strong>Дата:&nbsp;</strong>{created_at}<br/>
                            <strong>Способ доставки:&nbsp;</strong>{delivery_method}<br/>
                            <strong>Способ оплаты:&nbsp;</strong>{payment_method}<br/>
                            <strong>Товары:&nbsp;</strong>{products}<br/>
                            <strong>Cтоимость доставки:&nbsp;</strong>{delivery_price}<br/>
                            <strong>Комиссия:&nbsp;</strong>{commission}<br/>
                            <strong>Сумма:&nbsp;</strong>{total}<br/>
                            <strong>Статус:&nbsp;</strong>{status}
                        <?php }else if($message_template['id'] == 3){?>
                            <strong>ID клиента:</strong>{customer_id}<br/>
                            <strong>Имя:</strong>{first_name}<br/>
                            <strong>Фамилия:</strong>{second_name}<br/>
                            <strong>Отчество:</strong>{patronymic}<br/>
                            <strong>Email:</strong>{email}<br/>
                            <strong>Адрес:</strong>{address}<br/>
                            <strong>Пароль:&nbsp;</strong>{pass}<br/>
                            <strong>Телефон:&nbsp;</strong>{phone}<br/>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <button type="submit"
                                class="btn btn-info pull-right"><?php echo lang('button_submit'); ?></button>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
        </form>
    </div>
</section><!-- /.content -->