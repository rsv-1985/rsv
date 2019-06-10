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
                    <h3 class="box-title">Препросмотр</h3>
                    <p>Проверьте соответствие колонок и продолжите импорт.</p>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <?php foreach ($heading as $head){?>
                                                <th><?php echo $head;?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($products as $product){?>
                                        <tr>
                                            <?php foreach ($product as $column){?>
                                                <td><?php echo $column;?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <div class="well pull-right">
                                    <?php echo form_open('/autoxadmin/tools/product_tool/import', ['method' => 'post']);?>
                                    <input type="hidden" name="file_name" value="<?php echo $file_name;?>" >
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="create_attr" value="1"> Создавать новые атрибуты
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="update_product" value="1"> Обновлять карточку товара
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="update_seo_url" value="1"> Обновлять SEO url
                                        </label>
                                    </div>
                                    <a href="/autoxadmin/tools/product_tool/cancel?file_name=<?php echo $file_name;?>" class="btn btn-danger">Отмена</a>
                                    <button type="submit" class="btn btn-success">Загрузить</button>
                                    <?php echo form_close();?>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>
</section>
