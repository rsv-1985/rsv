<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <?php echo form_open('/customer/login'); ?>
                <div class="modal-content">
                    <div class="product-big-title-area">
                        <div class="product-bit-title text-center">
                            <?php echo lang('text_login_link'); ?>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert" style="display: none"></div>
                        <div class="form-group">
                            <label><?php echo lang('text_login'); ?></label>
                            <input type="text" name="login" placeholder="Телефон или email" required class="form-control"
                                   maxlength="96">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_password'); ?></label>
                            <input type="password" name="password" required class="form-control" maxlength="255">
                        </div>
                        <div class="form-group" style="text-align: center">
                            <a href="/customer/registration"><?php echo lang('text_registration'); ?></a> |
                            <a href="/customer/forgot">
                                <small><?php echo lang('text_remainder'); ?></small>
                            </a>
                        </div>
                        <div class="form-group">
                            <input class="pull-right" type="submit" value="<?php echo lang('text_login_link'); ?>">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

