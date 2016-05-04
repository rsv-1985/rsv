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
        <li><a href="/autoxadmin/supplier"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo lang('button_add');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <?php echo form_open();?>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo lang('text_supplier');?></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label><?php echo lang('text_name'); ?></label>
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name', $supplier['name']); ?>" maxlength="250">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_description'); ?></label>
                        <textarea name="description" maxlength="3000" class="form-control"><?php echo set_value('description', $supplier['description']); ?></textarea>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_api'); ?></label>
                        <input type="text" class="form-control" name="api" value="<?php echo set_value('api',$supplier['api']); ?>" maxlength="12">
                    </div><!-- /.form group -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="stock" value="1" <?php echo set_checkbox('stock', true, (bool)$supplier['stock']);?>> <?php echo lang('text_stock');?>
                        </label>
                    </div>
                    <hr>
                    <b><?php echo lang('text_statistics');?></b><br>
                    <ul>
                        <li><?php echo lang('text_statistics_updated_at');?>:<b><?php echo $supplier['updated_at'];?></b></li>
                        <li><?php echo lang('text_statistics_count_all');?>:<b><?php echo $count;?></b></li>
                        <li><?php echo lang('text_statistics_quan');?>:<b><?php echo $quan;?></b></li>
                        <li><?php echo lang('text_statistics_visible');?>:<b><?php echo $visible;?></b></li>
                    </ul>
                    <?php if($count > 0){?>
                        <a href="/autoxadmin/supplier/delete_products/<?php echo $supplier['id'];?>" id="delete-product" class="btn btn-danger pull-right"><?php echo lang('button_delete_products');?></a>
                    <?php } ?>
                    </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo lang('text_pricing');?></h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo lang('text_pricing_from');?></th>
                            <th><?php echo lang('text_pricing_to');?></th>
                            <th><?php echo lang('text_pricing_method');?></th>
                            <th><?php echo lang('text_pricing_value');?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="pricing">
                        <?php $q = 0;?>
                        <?php if(!empty($pricing)){?>
                            <?php foreach($pricing as $price){?>
                                <tr id="row<?php echo $q;?>">
                                    <td></td>
                                    <td><input type="text" name="pricing[<?php echo $q;?>][price_from]" value="<?php echo $price['price_from'];?>" class="form-control"></td>
                                    <td><input type="text" name="pricing[<?php echo $q;?>][price_to]" value="<?php echo $price['price_to'];?>" class="form-control"></td>
                                    <td>
                                        <select name="pricing[<?php echo $q;?>][method_price]" class="form-control">
                                            <option value="+" <?php if($price['method_price'] == '+'){?>selected<?php } ?>><?php echo lang('text_pricing_margin');?></option>
                                            <option value="-" <?php if($price['method_price'] == '-'){?>selected<?php } ?>><?php echo lang('text_pricing_discount');?></option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="pricing[<?php echo $q;?>][value]" value="<?php echo $price['value'];?>" class="form-control"></td>
                                    <td>
                                        <a href="#" class="btn btn-danger" onclick="delete_row(<?php echo $q;?>, event);"><?php echo lang('button_delete');?></a>
                                    </td>
                                </tr>
                            <?php $q++; } ?>
                        <?php }else{?>
                            <tr id="row0">
                                <td></td>
                                <td><input type="text" name="pricing[0][price_from]" class="form-control"></td>
                                <td><input type="text" name="pricing[0][price_to]" class="form-control"></td>
                                <td>
                                    <select name="pricing[0][method_price]" class="form-control">
                                        <option value="+"><?php echo lang('text_pricing_margin');?></option>
                                        <option value="-"><?php echo lang('text_pricing_discount');?></option>
                                    </select>
                                </td>
                                <td><input type="text" name="pricing[0][value]" class="form-control"></td>
                                <td>
                                    <a href="#" class="btn btn-danger" onclick="delete_row(0, event);"><?php echo lang('button_delete');?></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <br>
                    <a href="#" class="btn btn-info pull-right" onclick="add_row(event);"><?php echo lang('button_add');?></a>
                </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <div class="pull-right">
                    <button type="submit" class="btn btn-info"><?php echo lang('button_submit');?></button>
                    <a href="/autoxadmin/supplier" class="btn btn-default"><?php echo lang('button_close');?></a>
                </div>
            </div>
        </div>
    </div>
    </form>
</section><!-- /.content -->
<script>
    var row = 1;

    function add_row(event){
        event.preventDefault();
        html = '';
        html += '<tr id="row'+row+'">';
        html += '<td></td>';
        html += '<td><input type="text" name="pricing['+row+'][price_from]" class="form-control"></td>';
        html += '<td><input type="text" name="pricing['+row+'][price_to]" class="form-control"></td>';
        html += '<td>';
        html += '<select name="pricing['+row+'][method_price]" class="form-control">';
        html += '<option value="+"><?php echo lang('text_pricing_margin');?></option>';
        html += '<option value="-"><?php echo lang('text_pricing_discount');?></option>';
        html += '</select>';
        html += '</td>';
        html += '<td><input type="text" name="pricing['+row+'][value]" class="form-control"></td>';
        html += '<td>';
        html += '<a href="#" class="btn btn-danger" onclick="delete_row('+row+', event);"><?php echo lang('button_delete');?></a>';
        html += '</td>';
        html += '</tr>';

        $("#pricing").append(html);
        row++;
    }

    function delete_row(id, event){
        event.preventDefault();
        $("#row"+id).remove();
    }
</script>