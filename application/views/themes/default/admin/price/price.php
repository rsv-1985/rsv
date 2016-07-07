<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header">
    <h3><?php echo lang('text_heading');?></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo lang('text_heading');?></a></li>
    </ol>
</section>
<!-- Main content -->
<?php echo form_open();?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-6">
                        <h4><?php echo lang('text_product_settings');?></h4>
                        <div class="form-group">
                            <label><?php echo lang('text_category');?></label>
                            <select name="category_id" class="form-control">
                                <option value=""><?php echo lang('text_all');?></option>
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category['id'];?>"><?php echo $category['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_supplier');?></label>
                            <select name="supplier_id" class="form-control">
                                <option value=""><?php echo lang('text_all');?></option>
                                <?php foreach ($suppliers as $supplier){?>
                                    <option value="<?php echo $supplier['id'];?>"><?php echo $supplier['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_saleprice');?></label>
                            <select name="saleprice" class="form-control">
                                <option value=""><?php echo lang('text_all');?></option>
                                <option value="1">Да</option>
                                <option value="0">Нет</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4><?php echo lang('text_export_settings');?></h4>
                        <div class="form-group">
                            <label><?php echo lang('text_price_format');?></label>
                            <select name="format" class="form-control">
                                <?php if($formats){?>
                                    <?php foreach ($formats as $value => $name){?>
                                        <option value="<?php echo $value;?>"><?php echo $name;?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-info pull-right" value="Export">
                    </div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
</form>