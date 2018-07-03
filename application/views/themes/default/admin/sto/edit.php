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
        <li><a href="/autoxadmin/sto">СТО</a></li>
        <li><a href="#"><?php echo $record['id'];?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open();?>
            <div class="box">
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ФИО</label>
                            <input required type="text" class="form-control" name="name" value="<?php echo set_value('name', $record['name']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Телефон</label>
                            <input required type="text" class="form-control" name="phone" value="<?php echo set_value('phone', $record['phone']); ?>" maxlength="32">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo set_value('email', $record['email']); ?>" maxlength="32">
                        </div><!-- /.form group -->
                        <div class="well well-sm">
                            <div class="form-group">
                                <label>Комментарий</label>
                                <textarea name="comment" class="form-control"><?php echo $record['comment'];?></textarea>
                            </div><!-- /.form group -->
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="send_sms">
                                    Отправить в SMS
                                </label>
                                <label>
                                    <input type="checkbox" value="1" name="send_email">
                                    Отправить в Email
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Услуга</label>
                            <select name="service" class="form-control">
                                <?php foreach ($services as $service){?>
                                    <option value="<?php echo $service;?>" <?php echo set_select('service',$service,$service == $record['service']);?>><?php echo $service;?></option>
                                <?php } ?>
                            </select>
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Дата</label>
                            <input type="text" class="form-control" name="date" value="<?php echo set_value('date', $record['date']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Время</label>
                            <input type="text" class="form-control" name="time" value="<?php echo set_value('time', $record['time']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Номер автомобиля</label>
                            <input type="text" class="form-control" name="carnumber" value="<?php echo set_value('carnumber', $record['carnumber']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>VIN автомобиля</label>
                            <input type="text" class="form-control" name="vin" value="<?php echo set_value('vin', $record['vin']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Производитель</label>
                            <input type="manufacturer" class="form-control" name="manufacturer" value="<?php echo set_value('manufacturer', $record['manufacturer']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Модель</label>
                            <input type="model" class="form-control" name="model" value="<?php echo set_value('model', $record['model']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Тип</label>
                            <input type="typ" class="form-control" name="typ" value="<?php echo set_value('typ', $record['typ']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Статус</label>
                            <select name="status" class="form-control">
                                <?php foreach ($statuses as $status){?>
                                    <option value="<?php echo $status;?>" <?php echo set_select('status',$status,$status == $record['status']);?>><?php echo $status;?></option>
                                <?php } ?>
                            </select>
                        </div><!-- /.form group -->
                        <div class="form-group pull-right">
                            <a href="/autoxadmin/sto/delete/<?php echo $record['id'];?>">Удалить</a>
                            <button type="submit" class="btn btn-info "><?php echo lang('button_submit');?></button>
                        </div>
                    </div>


                </div><!-- /.box-body -->
            </div>
        </div>
        </form>
    </div>
</section><!-- /.content -->
<script>

</script>