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
        <li><a href="/autoxadmin/apikey"><?php echo lang('text_heading');?></a></li>
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
                        <label><?php echo lang('text_id'); ?></label>
                        <input required type="text" class="form-control" name="login" value="<?php echo set_value('id',$keydata['id']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_key'); ?></label>
                        <input required type="text" class="form-control" name="key" value="<?php echo set_value('key',$keydata['key']); ?>" maxlength="40">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label>Level</label>
                        <input type="text" class="form-control" name="level" value="<?php echo set_value('level',$keydata['level']); ?>" maxlength="2">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label>ip_addresses</label>
                        <input type="text" class="form-control" name="ip_addresses" value="<?php echo set_value('ip_addresses',$keydata['ip_addresses']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <div class="checkbox" style="display: none;">
                            <label>
                                <label>
                                    <input type="checkbox"  name="ignore_limits" value="1" <?php echo set_checkbox('ignore_limits',1,(bool)$keydata['ignore_limits']);?>>
                                    Ignore limits
                                </label>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <label>
                                    <input type="checkbox"  name="is_private_key" value="1" <?php echo set_checkbox('is_private_key',1,(bool)$keydata['is_private_key']);?>>
                                    is_private_key
                                </label>
                            </label>
                        </div>
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