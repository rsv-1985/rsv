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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
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
                        <span class="badge bg-aqua pull-right">Шаг 1</span>
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
                        <span class="badge bg-aqua pull-right">Шаг 2</span>
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
                        <a href="/autoxadmin/supplier" target="_blank"><?php echo lang('button_add');?> поставщика</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo lang('text_sample');?></h3>
                        <span class="badge bg-aqua pull-right">Шаг 3</span>
                    </div>
                    <div class="box-body" >
                        <div class="form-group" id="sample">

                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="#" id="sample_add"><?php echo lang('button_add');?> шаблон</a>
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
                            <label><?php echo lang('text_sample_default_excerpt');?></label>
                            <input type="text" class="form-control" name="sample[default_excerpt]">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_sample_default_term');?></label>
                                <input type="text" class="form-control" name="sample[default_term]" value="<?php echo set_value('sample[default_term]');?>" placeholder="+3 или -1 или 3">
                            </div>
                            <div class="form-group">
                            <label><?php echo lang('text_sample_term_unit');?></label><br>
                                <input type="radio" name="sample[default_term_unit]" value="hour" checked> <?php echo lang('text_sample_default_term_unit_hour');?>
                                <input type="radio" name="sample[default_term_unit]" value="day"> <?php echo lang('text_sample_default_term_unit_day');?>
                            </div>
                            <div class="form-group">
                                <label>Очистка номера <small><a target="_blank" href="http://www.nncron.ru/help/RU/add_info/regexp.htm">регулярное выражение</a></small> </label>
                                <input type="text" name="sample[default_regular]" value="<?php echo set_value('sample[default_regular]');?>" placeholder="BM|^00" class="form-control">
                            </div>
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
                                    <th><?php echo lang('text_sample_attributes');?></th>
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
                                    <td>
                                        <div class="form-group">
                                            <input type="number"  class="form-control" name="sample[attributes]" value="<?php echo set_value('sample[attributes]');?>">
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
            $('html, body').animate({
                scrollTop: $("#sample_form").offset().top
            }, 1000);
        });

        $("#supplier").on("change", function(){
            $.ajax({
                url: '/autoxadmin/supplier/get_sample',
                method: 'POST',
                data: {supplier_id:$(this).val()},
                success: function(response){
                    if(response.length){
                        $("#sample").html(response).removeAttr('disabled');
                        $("#sample_form").hide();
                    }else{
                        $("#sample").empty().attr('disabled', true);
                        $("#sample_form").show();
                    }
                }
            });
        });
    });

    function delete_sample(id, e){
        e.preventDefault();
        $.ajax({
            url: '/autoxadmin/supplier/delete_sample',
            method: 'POST',
            data: {sample_id: id},
            success: function(){
                $("#sample_"+id).remove();
            }
        });
    }

    function edit_sample(id, e) {
        e.preventDefault();
        $.ajax({
            url: '/autoxadmin/supplier/edit_sample',
            method: 'POST',
            data: {sample_id: id},
            dataType: 'json',
            success: function(json){
                if(json){
                    console.log(json);
                    $("[name='sample_name']").val(json['name']);
                    $("[name='sample_save']").prop('checked', true);

                    $("[name='sample[currency_id]'] option[value='"+json['value']['currency_id']+"']").attr('selected','selected');
                    $("[name='sample[default_category_id]'] option[value='"+json['value']['default_category_id']+"']").attr('selected','selected');
                    $("[name='sample[default_excerpt]'").val(json['value']['default_excerpt']);
                    $("[name='sample[default_term]'").val(json['value']['default_term']);
                    $("input[name='sample[default_term_unit]'][value=" + json['value']['default_term_unit'] + "]").attr('checked', 'checked');
                    $("[name='sample[default_regular]'").val(json['value']['default_regular']);

                    $("[name='sample[sku]'").val(json['value']['sku']);
                    $("[name='sample[brand]'").val(json['value']['brand']);
                    $("[name='sample[name]'").val(json['value']['name']);
                    $("[name='sample[description]'").val(json['value']['description']);
                    $("[name='sample[excerpt]'").val(json['value']['excerpt']);
                    $("[name='sample[delivery_price]'").val(json['value']['delivery_price']);
                    $("[name='sample[saleprice]'").val(json['value']['saleprice']);
                    $("[name='sample[quantity]'").val(json['value']['quantity']);
                    $("[name='sample[term]'").val(json['value']['term']);
                    $("[name='sample[category]'").val(json['value']['category']);
                    $("[name='sample[image]'").val(json['value']['image']);
                    $("[name='sample[attributes]'").val(json['value']['attributes']);



                    $("#sample_form").show();
                    $('html, body').animate({
                        scrollTop: $("#sample_form").offset().top
                    }, 1000);
                }
            }
        });
    }
</script>