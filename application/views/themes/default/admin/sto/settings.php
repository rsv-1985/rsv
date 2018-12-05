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
        <li><a href="/autoxadmin/sto/settings"><?php echo lang('text_heading'); ?></a></li>
        <li><a href="#"><?php echo lang('text_settings_heading'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Настройки</h3>
                </div>
                <div class="box-body">
                    <div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab1" aria-controls="home" role="tab"
                                                                      data-toggle="tab">Основное</a></li>
                            <li role="presentation"><a href="#tab2" aria-controls="profile" role="tab"
                                                       data-toggle="tab">Услуги</a></li>
                            <li role="presentation"><a href="#tab3" aria-controls="messages" role="tab"
                                                       data-toggle="tab">Статусы</a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tab1">
                                <?php echo form_open(); ?>
                                <div class="form-group">
                                    <label>h1 заголовок</label>
                                    <input type="text" name="h1" class="form-control"
                                           value="<?php echo set_value('h1', @$settings['h1']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>title</label>
                                    <input type="text" name="title"
                                           value="<?php echo set_value('title', @$settings['title']); ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>meta_description</label>
                                    <input type="text" name="meta_description"
                                           value="<?php echo set_value('meta_description', @$settings['meta_description']); ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>meta_keywords</label>
                                    <input type="text" name="meta_keywords"
                                           value="<?php echo set_value('meta_keywords', @$settings['meta_keywords']); ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Текст</label>
                                    <textarea class="textarea"
                                              name="description"><?php echo set_value('description', @$settings['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Email для уведомлений о новых заявках</label>
                                    <input type="text" name="email_notification" value="<?php echo set_value('email_notification', @$settings['email_notification']); ?>" placeholder="test@test.com;test2@test.com" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Телефоны для уведомлений о новых заявках</label>
                                    <input type="text" name="telephone_notification" value="<?php echo set_value('telephone_notification', @$settings['telephone_notification']); ?>" placeholder="380991231212;380991333333" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-info pull-right">Сохранить</button>
                                <div class="clearfix"></div>
                                <?php echo form_close(); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Название</th>
                                            <th>Сортировка</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ($services) { ?>
                                            <?php foreach ($services as $service) { ?>
                                                <tr>
                                                    <td><?php echo $service['name']; ?></td>
                                                    <td><?php echo $service['sort_order']; ?></td>
                                                    <td>
                                                        <div class="btn-group pull-right">
                                                            <a href="#"
                                                               onclick="deleteService(<?php echo $service['id']; ?>); return false;"
                                                               class="btn btn-danger" title="Удалить"><i
                                                                        class="fa fa-trash-o"
                                                                        aria-hidden="true"></i></a>
                                                            <a href="#"
                                                               onclick="getService(<?php echo $service['id']; ?>); return false;"
                                                               class="btn btn-info" title="Редактировать"><i
                                                                        class="fa fa-pencil-square-o"
                                                                        aria-hidden="true"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary pull-right" data-toggle="modal"
                                        data-target="#sto-service" onclick="$('#service-form')[0].reset();">Добавить
                                </button>
                                <div id="sto-service" class="modal fade">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <?php echo form_open('/autoxadmin/sto/service', ['method' => 'post', 'id' => 'service-form']); ?>
                                            <input type="hidden" name="id">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input type="text" name="name" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Сортировка</label>
                                                    <input type="text" name="sort_order" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Закрыть
                                                </button>
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab3">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Название</th>
                                            <th>Статус новой заявки</th>
                                            <th>Сортировка</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ($statuses) { ?>
                                            <?php foreach ($statuses as $status) { ?>
                                                <tr>
                                                    <td><?php echo $status['name']; ?></td>
                                                    <td><?php echo $status['is_new'] ? 'Да' : '';?></td>
                                                    <td><?php echo $status['sort_order']; ?></td>
                                                    <td>
                                                        <div class="btn-group pull-right">
                                                            <a href="#"
                                                               onclick="deleteStatus(<?php echo $status['id']; ?>); return false;"
                                                               class="btn btn-danger" title="Удалить"><i
                                                                        class="fa fa-trash-o"
                                                                        aria-hidden="true"></i></a>
                                                            <a href="#"
                                                               onclick="getStatus(<?php echo $status['id']; ?>); return false;"
                                                               class="btn btn-info" title="Редактировать"><i
                                                                        class="fa fa-pencil-square-o"
                                                                        aria-hidden="true"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary pull-right" data-toggle="modal"
                                        data-target="#sto-status" onclick="$('#status-form')[0].reset();">Добавить
                                </button>
                                <div id="sto-status" class="modal fade">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <?php echo form_open('/autoxadmin/sto/status', ['method' => 'post', 'id' => 'status-form']); ?>
                                            <input type="hidden" name="id">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input type="text" name="name" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="is_new">
                                                            Статус для новой заявки
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Цвет</label>
                                                    <input type="text" name="color" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Шаблон SMS</label>
                                                    <textarea name="sms_template" class="form-control"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Шаблон Email</label>
                                                    <textarea name="email_template" class="form-control"></textarea>
                                                    <p>
                                                        <b>Переменные</b><br>
                                                        {name} - ФИО<br>
                                                        {phone} - Телефон<br>
                                                        {email} - Email<br>
                                                        {comment} - Комментарий<br>
                                                        {service_id} - Услуга<br>
                                                        {date} - Дата и время<br>
                                                        {carnumber} - Номер автомобиля<br>
                                                        {manufacturer} - Производитель<br>
                                                        {model} - Модель<br>
                                                        {status_id} - Статус
                                                    </p>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Закрыть
                                                </button>
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function deleteService(service_id) {
        $.get('/autoxadmin/sto/delete_service/' + service_id, function (response) {
            location.reload();
        });
    }

    function getService(service_id) {
        $.ajax({
            url: '/autoxadmin/sto/get_service/' + service_id,
            method: 'get',
            dataType: 'json',
            success: function (json) {
                $("#service-form [name='name']").val(json['name']);
                $("#service-form [name='id']").val(json['id']);
                $("#service-form [name='sort_order']").val(json['sort_order']);
                $("#sto-service").modal('show');
            }
        });
    }

    function deleteStatus(status_id) {
        $.get('/autoxadmin/sto/delete_status/' + status_id, function (response) {
            location.reload();
        });
    }

    function getStatus(status_id) {
        $.ajax({
            url: '/autoxadmin/sto/get_status/' + status_id,
            method: 'get',
            dataType: 'json',
            success: function (json) {
                console.log(json);
                $("#status-form [name='name']").val(json['name']);
                $("#status-form [name='color']").val(json['color']);
                $("#status-form [name='sms_template']").val(json['sms_template']);
                $("#status-form [name='email_template']").val(json['email_template']);
                if(json['is_new'] == '1'){
                    $("#status-form [name='is_new']").prop('checked', true);
                }
                $("#status-form [name='id']").val(json['id']);
                $("#status-form [name='sort_order']").val(json['sort_order']);
                $("#sto-status").modal('show');
            }
        });
    }
</script>