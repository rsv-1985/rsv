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
        <li><a href="/autoxadmin/synonym"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo lang('button_add');?></a></li>
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
                        <label><?php echo lang('text_brand1'); ?></label>
                        <input required type="text" class="form-control" name="brand1" value="<?php echo set_value('name'); ?>" maxlength="60">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_brand2'); ?></label>
                        <input required type="text" class="form-control" name="brand2" value="<?php echo set_value('code'); ?>" maxlength="60">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
        </form>
    </div>
</section><!-- /.content -->
<script>

</script>