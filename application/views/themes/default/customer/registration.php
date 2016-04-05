<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2><?php echo lang('text_heading_registration');?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <?php echo form_open();?>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?php echo lang('text_login');?></label>
                        <input type="text" name="login" class="form-control" value="<?php echo set_value('login');?>" required maxlength="32">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_password');?></label>
                        <input type="password" name="password" class="form-control" value="<?php echo set_value('password');?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_confirm_password');?></label>
                        <input type="password" name="confirm_password" value="<?php echo set_value('confirm_password');?>" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="submit" value="<?php echo lang('button_register');?>" class="pull-right">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
