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
        <li><a href="/autoxadmin/news"><?php echo lang('text_heading');?></a></li>
        <li><a href="#">Важная новость</a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open();?>
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label>Контент</label>
                        <textarea id="textarea"  class="textarea" name="description"><?php echo set_value('description', $important_news['description']); ?></textarea>
                        <input type="hidden" name="modalmessage_cookie_name" value="modalmessage_cookie_name_<?php echo rand();?>">
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="status" value="1" <?php echo set_checkbox('status', 1, (bool)$important_news['status']);?>>
                                Активна
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
        </form>
    </div>
</section><!-- /.content -->