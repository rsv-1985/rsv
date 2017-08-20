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
        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo lang('text_supplier');?></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label><?php echo lang('text_name'); ?></label>
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name'); ?>" maxlength="250">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_description'); ?></label>
                        <textarea name="description" maxlength="3000" class="form-control"><?php echo set_value('description'); ?></textarea>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_api'); ?></label>
                        <input type="text" class="form-control" name="api" value="<?php echo set_value('api'); ?>" maxlength="12">
                    </div><!-- /.form group -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="stock" value="1"> <?php echo lang('text_stock');?>
                        </label>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-8">
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
                                <th><?php echo lang('text_pricing_brand');?></th>
                                <th><?php echo lang('text_pricing_method');?></th>
                                <th><?php echo lang('text_pricing_value');?></th>
                                <th><?php echo lang('text_pricing_fix_value');?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="pricing">
                            <tr id="row0">
                                <td></td>
                                <td><input type="text" name="pricing[0][price_from]" class="form-control"></td>
                                <td><input type="text" name="pricing[0][price_to]" class="form-control"></td>
                                <td><input type="text" name="pricing[0][brand]" class="form-control"></td>
                                <td>
                                    <select name="pricing[0][method_price]" class="form-control">
                                        <option value="+"><?php echo lang('text_pricing_margin');?></option>
                                        <option value="-"><?php echo lang('text_pricing_discount');?></option>
                                    </select>
                                </td>
                                <td><input type="text" name="pricing[0][value]" class="form-control"></td>
                                <td><input type="text" name="pricing[0][fix_value]" class="form-control"></td>
                                <td>
                                    <a href="#" class="btn btn-danger" onclick="delete_row(0, event);"><?php echo lang('button_delete');?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <a href="#" class="btn btn-info pull-right" onclick="add_row(event);"><?php echo lang('button_add');?></a>
                </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
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
        html += '<td><input type="text" name="pricing['+row+'][brand]" class="form-control"></td>';
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