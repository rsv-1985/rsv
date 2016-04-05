<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo lang('text_tmptable');?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="/autoxadmin/import"><?php echo lang('text_heading');?></a></li>
        <li class="active"><?php echo lang('text_tmptable');?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo lang('text_tmp_total').':'.$total;?></h3>
        </div>
        <div class="box-body">
            <table class="table">
                <thead>
                <tr>
                    <th><?php echo lang('text_sample_sku');?></th>
                    <th><?php echo lang('text_sample_brand');?></th>
                    <th><?php echo lang('text_sample_name');?></th>
                    <th><?php echo lang('text_sample_description');?></th>
                    <th><?php echo lang('text_sample_excerpt');?></th>
                    <th><?php echo lang('text_sample_delivery_price');?></th>
                    <th><?php echo lang('text_sample_saleprice');?></th>
                    <th><?php echo lang('text_sample_quantity');?></th>
                    <th><?php echo lang('text_sample_term');?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($importtmp as $item){?>
                    <tr>
                        <td><?php echo $item['sku'];?></td>
                        <td><?php echo $item['brand'];?></td>
                        <td><?php echo $item['name'];?></td>
                        <td><?php echo $item['description'];?></td>
                        <td><?php echo $item['excerpt'];?></td>
                        <td><?php echo $item['delivery_price'];?></td>
                        <td><?php echo $item['saleprice'];?></td>
                        <td><?php echo $item['quantity'];?></td>
                        <td><?php echo $item['term'];?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer">
            <a href="/autoxadmin/import/cancel" class="btn btn-danger"><?php echo lang('button_delete');?></a>
            <div class="pull-right">
                <?php echo form_open('/autoxadmin/import/add', ['method' => 'get']);?>
                <div class="form-group">
                    <label><?php echo lang('text_import_settings');?></label>
                    <select name="settings" class="form-control">
                        <option value="1"><label><?php echo lang('text_import_settings_add');?></label></option>
                        <option value="2"><label><?php echo lang('text_import_settings_delete');?></label></option>
                    </select>
                </div>
                <?php if($this->input->get('supplier_id')){?>
                    <input type="hidden" name="supplier_id" value="<?php echo (int)$this->input->get('supplier_id');?>">
                <?php }else{?>
                    <div class="form-group">
                        <label><?php echo lang('text_supplier');?></label>
                        <select name="supplier_id" class="form-control" required>
                            <?php foreach($supplier as $supplier){?>
                                <option value="<?php echo $supplier['id'];?>"><?php echo $supplier['name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php }?>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="1" name="disable">
                            <label><?php echo lang('text_import_settings_disble');?></label>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><?php echo lang('button_import');?></button>
                </form>
            </div>

        </div><!-- /.box-footer-->
    </div><!-- /.box -->

</section><!-- /.content -->
