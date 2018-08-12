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
                    <h3 class="box-title">Canonical</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php echo form_open_multipart('/autoxadmin/seo_settings/canonical?add=1', ['method' => 'post']);?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Импорт с CSV</label>
                                <input type="file" name="userfile">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>URL</label>
                                <input  type="text" name="url" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label>Canonical</label>
                                <input  type="text" name="canonical" class="form-control" value="">
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
                                    <th>URL</th>
                                    <th>Canonical</th>
                                    <th></th>
                                </tr>
                                <?php echo form_open('/autoxadmin/seo_settings/canonical', ['method' => 'get']); ?>
                                <tr>
                                    <th>
                                        <input type="text" name="url" class="form-control" value="<?php echo $this->input->get('url', true);?>">
                                    </th>
                                    <th>
                                        <input type="text" name="canonical" class="form-control" value="<?php echo $this->input->get('canonical', true);?>">
                                    </th>
                                    <th>
                                        <button type="submit" class="btn btn-info">Поиск</button>
                                        <?php if($this->input->get()){?>
                                            <a href="/autoxadmin/seo_settings/canonical" class="btn btn-danger">Сброс</a>
                                        <?php } ?>
                                    </th>
                                </tr>
                                <?php echo form_close();?>
                                </thead>

                                <tbody>
                                <?php if($canonicals){?>
                                    <?php foreach ($canonicals as $canonical){?>
                                        <?php echo form_open('/autoxadmin/seo_settings/canonical?edit='.$canonical['id'], ['method' => 'post']);?>
                                        <tr>
                                            <td>
                                                <input type="text" name="url" value="<?php echo $canonical['url'];?>" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="canonical" value="<?php echo $canonical['canonical'];?>" class="form-control">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-info ">Сохранить</button>
                                                <a class="btn btn-danger " href="/autoxadmin/seo_settings/canonical?delete=<?php echo $canonical['id'];?>">Удалить</a>
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