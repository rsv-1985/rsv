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
        <?php echo lang('text_heading');?>
        <small>xls / csv</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo lang('text_heading');?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <?php echo form_open_multipart('', ['id' => 'import_form']);?>
        <div class="row">
            <div class="col-md-4">
                <!-- Default box -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo lang('text_price');?></h3>
                        <span class="badge bg-aqua pull-right">1</span>
                    </div>
                    <div class="box-body">
                        <input type="file" name="filename" class="form-control" value="<?php echo set_value('filename');?>" required>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <?php echo lang('text_price_info');?>
                    </div><!-- /.box-footer-->
                </div><!-- /.box -->
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo lang('text_supplier');?></h3>
                        <span class="badge bg-aqua pull-right">2</span>
                    </div>
                    <div class="box-body">
                        <?php if($suppliers){?>
                            <select required name="supplier_id" class="form-control" id="supplier">
                                <option></option>
                                <?php foreach($suppliers as $supplier){?>
                                    <option value="<?php echo $supplier['id'];?>" <?php echo set_select('supplier_id', $supplier['id']);?>><?php echo $supplier['name'];?></option>
                                <?php } ?>
                            </select>
                        <?php } ?>
                    </div>
                    <div class="box-footer">
                        <a href="/autoxadmin/supplier" target="_blank"><?php echo lang('button_add');?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo lang('text_sample');?></h3>
                        <span class="badge bg-aqua pull-right">3</span>
                    </div>
                    <div class="box-body" >
                        <div class="form-group" id="sample">

                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="#" id="sample_add"><?php echo lang('button_add');?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="sample_form" style="display: none;">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo lang('text_sample');?></h3>
                        <span class="badge bg-aqua pull-right">NEW </span>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <label><?php echo lang('text_save_sample');?></label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                  <input type="checkbox" name="sample_save" value="1">
                                </span>
                                <input type="text" class="form-control" name="sample_name" placeholder="<?php echo lang('text_save_sample_name');?>">
                            </div><!-- /input-group -->
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <label><?php echo lang('text_sample_currency');?></label>
                            <select class="form-control" name="sample[currency_id]">
                                <?php if($currency){?>
                                    <?php foreach($currency as $cur){?>
                                        <option value="<?php echo $cur['id'];?>" <?php echo set_select('sample[currency_id]', $cur['id']);?>><?php echo $cur['name'];?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <label><?php echo lang('text_sample_default_category');?></label>
                            <select class="form-control" name="sample[default_category_id]">
                                <?php if($category){?>
                                    <option value="0"></option>
                                    <?php foreach($category as $cat){?>
                                        <option value="<?php echo $cat['id'];?>" <?php echo set_select('sample[category_id]', $cat['id']);?>><?php echo $cat['name'];?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label><?php echo lang('text_sample_default_term');?></label>
                            <input type="number" class="form-control" name="sample[default_term]" value="<?php echo set_value('sample[default_term]');?>">
                        </div>
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
                                    <th><?php echo lang('text_sample_category');?></th>
                                    <th><?php echo lang('text_sample_image');?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group has-error">
                                            <input type="number"  class="form-control" name="sample[sku]" value="<?php echo set_value('sample[sku]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group has-error">
                                            <input type="number" class="form-control" name="sample[brand]" value="<?php echo set_value('sample[brand]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="sample[name]" value="<?php echo set_value('sample[name]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number"  class="form-control" name="sample[description]" value="<?php echo set_value('sample[description]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number"  class="form-control" name="sample[excerpt]" value="<?php echo set_value('sample[excerpt]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group  has-error">
                                            <input type="number"  class="form-control" name="sample[delivery_price]" value="<?php echo set_value('sample[delivery_price]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number"  class="form-control" name="sample[saleprice]" value="<?php echo set_value('sample[saleprice]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group has-error">
                                            <input type="number"  class="form-control" name="sample[quantity]" value="<?php echo set_value('sample[quantity]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number"  class="form-control" name="sample[term]" value="<?php echo set_value('sample[term]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number"  class="form-control" name="sample[category]" value="<?php echo set_value('sample[category]');?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number"  class="form-control" name="sample[image]" value="<?php echo set_value('sample[image]');?>">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer">
                        <?php echo lang('text_sample_info');?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                </div>
            </div>
        </div>
    </form>
</section><!-- /.content -->
<script>
    $(document).ready(function(){
        $("#sample_add").click(function(event){
            event.preventDefault();
            $("#sample_form").toggle();
        });

        $("#supplier").on("change", function(){
            $.ajax({
                url: '/autoxadmin/supplier/get_sample',
                method: 'POST',
                data: {supplier_id:$(this).val()},
                dateType: 'json',
                success: function(json){
                    if(json['samples']){
                        var html = '';
                        $.each(json['samples'], function( index, item ) {
                            html += '<div class="radio" id="sample_'+item['id']+'">';
                            html += '    <i class="fa fa-remove pull-right" onclick="delete_sample('+item['id']+')"></i>';
                            html += '    <label>';
                            html += '        <input type="radio" name="sample_id" value="'+item['id']+'">';
                            html += '            <b>'+item['name']+'</b>';
                            html += '            <p class="help-block">';
                            $.each(item['value'], function( index, value ) {
                                html += index+':'+value+'<br>';
                            });
                            html += '</p>';
                            html += '    </label>';
                            html += '</div>';
                        });
                        $("#sample").html(html).removeAttr('disabled');
                        $("#sample_form").hide();
                    }else{
                        $("#sample").empty().attr('disabled', true);
                        $("#sample_form").show();
                    }
                }
            });
        });
    });

    function delete_sample(id){
        $.ajax({
            url: '/autoxadmin/supplier/delete_sample',
            method: 'POST',
            data: {sample_id: id},
            success: function(){
                $("#sample_"+id).remove();
            }
        });
    }
</script>