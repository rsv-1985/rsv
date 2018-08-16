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
            <div class="col-md-offset-3 col-md-6">
                <?php echo form_open();?>
                    <div class="form-group">
                        <label><?php echo lang('text_forgot_input');?></label>
                        <input type="text" name="search" placeholder="<?php echo lang('text_forgot_input_placeholder');?>" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <input type="submit"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

