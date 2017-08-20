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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="/autoxadmin/cross"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo lang('button_add');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <?php echo form_open_multipart();?>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" class="form-control" name="code" value="<?php echo set_value('code'); ?>" maxlength="32">
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <input type="text" class="form-control" name="brand" value="<?php echo set_value('brand'); ?>" maxlength="32">
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label>Code2</label>
                        <input type="text" class="form-control" name="code2" value="<?php echo set_value('code2'); ?>" maxlength="32">
                    </div>
                    <div class="form-group">
                        <label>Brand2</label>
                        <input type="text" class="form-control" name="brand2" value="<?php echo set_value('brand2'); ?>" maxlength="32">
                    </div>

                </div><!-- /.box-body -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label><?php echo lang('text_import');?></label>
                        <input type="file" name="userfile">
                        <a download="download" href="/uploads/cross.xls"><?php echo lang('text_template');?></a>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="xcross" value="1">
                                Создать обратный кросс
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
            </div>
        </div>
    </div>
    </form>
</section><!-- /.content -->