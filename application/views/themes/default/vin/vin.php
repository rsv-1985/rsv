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
                    <h1><?php echo $h1;?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <?php echo form_open('ajax/vin', ['class' => 'vin_request']);?>
            <div class="col-md-6">
                <div class="well">
                    <div class="alert alert-danger" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    </div>
                    <div class="alert alert-success" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('text_vin_manufacturer');?></label>
                        <input type="text" class="form-control" name="manufacturer" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_vin_model');?></label>
                        <input type="text" class="form-control" name="model" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_vin_engine');?></label>
                        <input type="text" class="form-control" name="engine" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_vin_vin');?></label>
                        <input type="text" class="form-control" name="vin">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_vin_parts');?></label>
                        <textarea class="form-control" name="parts" required></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><?php echo lang('text_vin_name');?></label>
                    <input type="text" name="name" class="form-control" required/>
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_vin_telephone');?></label>
                    <input type="text" name="telephone" class="form-control" required/>
                </div>
                <div class="form-group">
                    <label><?php echo lang('text_vin_email');?></label>
                    <input type="email" name="email" class="form-control" required/>
                </div>
                <div class="form-group pull-right">
                    <button type="submit"><?php echo lang('button_send');?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>