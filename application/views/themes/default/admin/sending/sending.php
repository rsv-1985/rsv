<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="#">Рассылка</a></li>
    </ol>
</section>
<?php echo form_open();?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Рассылка</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Тема сообщения</label>
                                    <input type="text" name="subject" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Текст сообщения</label>
                                    <textarea type="text" name="text" class="form-control" rows="7" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Получатели</label>
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="newsletter" value="1">Подписчики
                                        </label>

                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="customer" value="2">Зарегистрированные пользователи
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="orders" value="3">Клиенты с заказов
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                </div><!-- /.box-body -->
                <div class="box-footer clearfix">

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="send_email" value="1" checked> Отправить в e-mail
                        </label>
                        <label>
                            <input type="checkbox" name="send_sms" value="1">Отправить в sms
                        </label>
                    </div>

                    <button type="submit" class="btn btn-info pull-right">Отправить</button>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
<?php echo form_close('</form>');?>