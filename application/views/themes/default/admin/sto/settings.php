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
        <li><a href="/autoxadmin/sto/settings"><?php echo lang('text_heading'); ?></a></li>
        <li><a href="#"><?php echo lang('text_settings_heading'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <?php echo form_open(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Настройки</h3>
                </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>h1 заголовок</label>
                            <input type="text" name="h1" class="form-control" value="<?php echo set_value('h1',@$settings['h1']);?>">
                        </div>
                        <div class="form-group">
                            <label>title</label>
                            <input type="text" name="title" value="<?php echo set_value('title',@$settings['title']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>meta_description</label>
                            <input type="text" name="meta_description" value="<?php echo set_value('meta_description',@$settings['meta_description']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>meta_keywords</label>
                            <input type="text" name="meta_keywords" value="<?php echo set_value('meta_keywords',@$settings['meta_keywords']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Текст</label>
                            <textarea class="textarea" name="description"><?php echo set_value('description',@$settings['description']);?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Услуги</label>
                            <textarea class="form-control" name="services"><?php echo set_value('services',@$settings['services']);?></textarea>
                            <p>Каждая услуга с новой строчки<br>
                            <small>Пример:Замена масла</small></p>
                        </div>
                        <div class="form-group">
                            <label>Время записи утро</label>
                            <textarea class="form-control" name="time_morning"><?php echo set_value('time_morning',@$settings['time_morning']);?></textarea>
                            <p>Каждая запись с новой строчки<br>
                                <small>Пример:8:00</small></p>
                        </div>
                        <div class="form-group">
                            <label>Время записи день</label>
                            <textarea class="form-control" name="time_afternoon"><?php echo set_value('time_afternoon',@$settings['time_afternoon']);?></textarea>
                            <p>Каждая запись с новой строчки
                            <br><small>Пример:12:00</small></p>
                        </div>
                        <div class="form-group">
                            <label>Статусы заявок</label>
                            <textarea class="form-control" name="status"><?php echo set_value('status',@$settings['status']);?></textarea>
                            <p>
                                Каждая запись с новой строчки<br>
                                <small>Пример:Название_статуса#код_цвета</small>
                            </p>
                        </div>
                        <button type="submit" class="btn btn-info pull-right">Сохранить</button>
                    </div>
            </div>
        </div>
    </div>
    </form>
</section>