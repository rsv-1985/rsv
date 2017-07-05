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
                <?php echo form_open(null,['id' => 'registration_form']);?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_first_name');?></label>
                        <input id="input_registration_first_name" type="text" name="first_name" class="form-control" value="<?php echo set_value('first_name');?>" required maxlength="32">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_second_name');?></label>
                        <input id="input_registration_second_name" type="text" name="second_name" class="form-control" value="<?php echo set_value('second_name');?>" required maxlength="32">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_patronymic');?></label>
                        <input id="input_registration_patronomic" type="text" name="patronymic" class="form-control" value="<?php echo set_value('patronymic');?>" required maxlength="255">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_email');?></label>
                        <input id="input_registration_email" type="email" name="email" class="form-control" value="<?php echo set_value('email');?>" required maxlength="32">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_address');?></label>
                        <input id="input_registration_address" type="text" name="address" class="form-control" value="<?php echo set_value('address');?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_login');?></label>
                        <input id="input_registration_login" type="text" name="login" class="form-control" value="<?php echo set_value('login');?>" required maxlength="32">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_phone');?></label>
                        <input id="input_registration_phone" type="text" name="phone" class="form-control" value="<?php echo set_value('phone');?>" required maxlength="32">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_password');?></label>
                        <input id="input_registration_password" type="password" name="password" class="form-control" value="<?php echo set_value('password');?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_confirm_password');?></label>
                        <input id="input_registration_confirm_password" type="password" name="confirm_password" value="<?php echo set_value('confirm_password');?>" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Проверочный код <?php echo $captcha_image;?></label>

                        <input class="form-control" id="cmsautox" name="captcha" type="text" value="" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <a href="#" data-toggle="modal" data-target="#login"><?php echo lang('text_login_link');?></a>
                        <input id="button_registration_submit" type="submit" value="<?php echo lang('button_register');?>" class="pull-right">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
