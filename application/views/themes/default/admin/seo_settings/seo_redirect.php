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
        <li><a href="#"><?php echo lang('text_heading'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Redirect</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php echo form_open_multipart('/autoxadmin/seo_settings/redirect?add=1', ['method' => 'post']);?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Импорт с CSV</label>
                                <input type="file" name="userfile">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>URL From</label>
                                <input  type="text" name="url_from" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label>URL to</label>
                                <input  type="text" name="url_to" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label>Status code</label>
                                <input  type="text" name="status_code" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-info pull-right" type="submit">Добавить</button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close();?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>URL From</th>
                                    <th>URL To</th>
                                    <th>Status Code</th>
                                    <th></th>
                                </tr>
                                <?php echo form_open('/autoxadmin/seo_settings/redirect', ['method' => 'get']); ?>
                                <tr>
                                    <th>
                                        <input type="text" name="url_from" class="form-control" value="<?php echo $this->input->get('url_from', true);?>">
                                    </th>
                                    <th>
                                        <input type="text" name="url_to" class="form-control" value="<?php echo $this->input->get('url_to', true);?>">
                                    </th>
                                    <th>
                                        <input type="text" name="status_code" class="form-control" value="<?php echo $this->input->get('status_code', true);?>">
                                    </th>
                                    <th>
                                        <button type="submit" class="btn btn-info">Поиск</button>
                                        <?php if($this->input->get()){?>
                                            <a href="/autoxadmin/seo_settings/redirect" class="btn btn-danger">Сброс</a>
                                        <?php } ?>
                                    </th>
                                </tr>
                                <?php echo form_close();?>
                                </thead>

                                <tbody>
                                <?php if($redirects){?>
                                    <?php foreach ($redirects as $redirect){?>
                                        <?php echo form_open('/autoxadmin/seo_settings/redirect?edit='.$redirect['id'], ['method' => 'post']);?>
                                        <tr>
                                            <td>
                                                <input type="text" name="url_from" value="<?php echo $redirect['url_from'];?>" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="url_to" value="<?php echo $redirect['url_to'];?>" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="status_code" value="<?php echo $redirect['status_code'];?>" class="form-control">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-info ">Сохранить</button>
                                                <a class="btn btn-danger " href="/autoxadmin/seo_settings/redirect?delete=<?php echo $redirect['id'];?>">Удалить</a>
                                            </td>
                                        </tr>
                                        <?php echo form_close();?>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
<script>
    function save_text(text_id) {
        var text = $("#text" + text_id).val();
        if (text.length > 0) {
            $.ajax({
                url: '/autoxadmin/language/update',
                data: {id: text_id, text: text},
                method: 'post',
                success: function (response) {
                    $("#button" + text_id).hide();
                    alert(response);
                }
            })
        }
    }
</script>