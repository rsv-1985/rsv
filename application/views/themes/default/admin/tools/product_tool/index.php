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
                    <h3 class="box-title">Массовый редактор товаров</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo form_open_multipart('', ['method' => 'post']);?>
                            <div class="form-group">
                                <label>Файл импорта</label>
                                <input type="file" name="userfile" class="form-control">
                                <a href="/uploads/product_tool.csv">Скачать пример файла</a>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-info">Отправить</button>
                            </div>
                            <?php echo form_close();?>
                        </div>
                        <div class="col-md-6">
                            <b>Формат файла CSV</b>
                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>
</section>
