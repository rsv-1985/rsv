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
        <li><a href="#"><?php echo lang('text_heading');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">VIN <?php echo $id;?> <small><?php echo $created_at;?></small></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php echo form_open();?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Марка автомобиля</label>
                                <input type="text" name="manufacturer" value="<?php echo set_value('manufacturer',$manufacturer);?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Модель и модификация</label>
                                <input type="text" name="model" value="<?php echo set_value('model',$model);?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Объем и тип двигателя</label>
                                <input type="text" name="engine" value="<?php echo set_value('engine',$engine);?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>VIN автомобиля</label>
                                <input type="text" name="vin" value="<?php echo set_value('vin',$vin);?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Нужные запчасти</label>
                                <textarea name="parts" class="form-control"><?php echo set_value('parts',$parts);?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Имя</label>
                                <input type="text" name="name" value="<?php echo set_value('name',$name);?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Телефон</label>
                                <input type="text" name="telephone" value="<?php echo set_value('telephone',$telephone);?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" value="<?php echo set_value('email',$email);?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Комментарий менеджера</label>
                                <textarea rows="5" name="comment" class="form-control"><?php echo set_value('comment',$comment);?></textarea>
                                <input type="checkbox" value="1" name="send_sms"> Отправить в SMS
                                <input type="checkbox" value="1" name="send_email"> Отправить в EMAIL
                            </div>
                            <div class="form-group">
                                <label>Статус</label>
                                <select name="status" class="form-control">
                                    <option value="0" <?php if($status == 0){?>selected<?php } ?>>Новый</option>
                                    <option value="1" <?php if($status == 1){?>selected<?php } ?>>Обработан</option>
                                </select>
                            </div>
                            <button class="btn btn-info pull-right">Сохранить</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
