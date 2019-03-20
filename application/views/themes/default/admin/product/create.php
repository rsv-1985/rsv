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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="/autoxadmin/product"><?php echo lang('text_heading'); ?></a></li>
        <li><a href="#"><?php echo lang('button_add'); ?></a></li>
    </ol>
</section>
<?php echo form_open_multipart(); ?>
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <b><?php echo lang('text_product_image'); ?></b>
                </div>
                <div class="box-body">
                    <div style="text-align: center;">
                        <img id="product-image" style="width:50%;"
                             src="/image?img=/assets/themes/default/img/no_image.png"/><br/>
                    </div>
                    <input id="image" type="hidden" name="image"
                           value="<?php echo set_value('image'); ?>">
                    <input type="file" name="userfile">
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <b><?php echo lang('text_price'); ?></b>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Цена</label>
                        <input type="text" name="static_price" class="form-control" value="<?php echo set_value('static_proce');?>">
                    </div>
                    <div class="form-group">
                        <label>Валюта</label>
                        <select name="static_currency_id" class="form-control">
                            <?php foreach ($currency as $cur){?>
                                <option value="<?php echo $cur['id'];?>" <?php echo set_select('static_currency_id');?>><?php echo $cur['name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <b><?php echo lang('text_product_seo'); ?></b>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>H1</label>
                        <input type="text" name="h1" value="<?php echo set_value('h1'); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo set_value('title'); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Meata description</label>
                        <input type="text" name="meta_description"
                               value="<?php echo set_value('meta_description'); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Meta keywords</label>
                        <input type="text" name="meta_keywords"
                               value="<?php echo set_value('meta_keywords'); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO url</label>
                        <input type="text" name="slug" value="<?php echo set_value('slug'); ?>"
                               class="form-control">
                    </div>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <b><?php echo lang('text_product_attribute'); ?></b>
                </div>
                <div class="box-body">
                    <table class="table table-striped" id="attributes_form">
                        <thead>
                        <tr>
                            <th><?php echo lang('text_product_attribute_name');?></th>
                            <th><?php echo lang('text_product_attribute_value');?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>


                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td><a href="#" onclick="productAttribute(); return false;" class="btn btn-info btn-xs"><?php echo lang('button_add');?></a></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <b><?php echo lang('text_product_main'); ?></b>
                </div>
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_sku'); ?></label>
                            <input autocomplete="off" id="input-sku" onkeyup="get_brands($(this).val())" type="text" name="sku" value="<?php echo set_value('sku'); ?>"
                                   class="form-control" required>
                            <div id="autocomplite" class="collapse"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_brand'); ?></label>
                            <input id="input-brand" type="text" name="brand" value="<?php echo set_value('brand'); ?>"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_name'); ?></label>
                            <input id="input-name" type="text" name="name" value="<?php echo set_value('name'); ?>"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_category_id'); ?></label>
                            <select name="category_id" class="form-control">
                                <option></option>
                                <?php foreach ($categories as $category) { ?>
                                    <option
                                            value="<?php echo $category->id; ?>" <?php echo set_select('category_id', $category->id); ?>><?php if($path = $category->getPath($category->parent_id)){echo $path.' > ';} ?> <b><?php echo $category->name; ?></b></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_viewed'); ?></label>
                            <input type="text" name="viewed"
                                   value="<?php echo set_value('viewed'); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_bought'); ?></label>
                            <input type="text" name="bought"
                                   value="<?php echo set_value('bought'); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php echo lang('text_description'); ?></label>
                            <textarea class="textarea"
                                      name="description"><?php echo set_value('description'); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-primary" id="row">
                <div class="box-header with-border">
                    <div class="form-group">
                        <label><?php echo lang('text_supplier_id');?></label>
                        <select name="prices[0][supplier_id]" class="form-control" required">
                            <option></option>
                            <?php foreach ($supplier as $supplier){?>
                                <option value="<?php echo $supplier['id'];?>"><?php echo $supplier['name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><?php echo lang('text_delivery_price'); ?></th>
                            <th><?php echo lang('text_price'); ?></th>
                            <th><?php echo lang('text_saleprice'); ?></th>
                            <th><?php echo lang('text_currency_id'); ?></th>
                            <th><?php echo lang('text_quantity'); ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td><input type="text" name="prices[0][delivery_price]"
                                       value="<?php echo set_value('delivery_price'); ?>"
                                       class="form-control"
                                       required></td>
                            <td>
                                <input type="text" name="prices[0][price]"
                                       value="<?php echo set_value('price'); ?>"
                                       class="form-control">
                            </td>
                            <td><input type="text" name="prices[0][saleprice]"
                                       value="<?php echo set_value('saleprice'); ?>"
                                       class="form-control"></td>
                            <td>
                                <select class="form-control" name="prices[0][currency_id]"
                                        required>
                                    <?php foreach ($currency as $cur) { ?>
                                        <option
                                            value="<?php echo $cur['id']; ?>" <?php echo set_select('currency_id', $cur['id']); ?>><?php echo $cur['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><input type="number" name="prices[0][quantity]"
                                       value="<?php echo set_value('quantity'); ?>"
                                       class="form-control"></td>
                        </tr>
                        </tbody>
                    </table>
                    <a href="#"
                       onclick="$('.product_info').toggle();return false;"><?php echo lang('text_product_info'); ?></a>
                    <div class="product_info" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_excerpt'); ?></label>
                                <input type="text" name="prices[0][excerpt]"
                                       value="<?php echo set_value('excerpt'); ?>"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_term'); ?></label>
                                <input type="text" name="prices[0][term]"
                                       value="<?php echo set_value('term'); ?>"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="pull-right">
                <button type="submit" class="btn btn-info"><?php echo lang('button_submit'); ?></button>
                <a href="/autoxadmin/product" class="btn btn-default"><?php echo lang('button_close'); ?></a>
            </div>
        </div>
    </div>
</section>
</form>
<script>
    $(document).ready(function(){
        $("body").click(function(){
           $("#autocomplite").collapse('hide');
        });
    });
    var attribute_row = 0;
    function productAttribute(){
        var html = '';
        html += '<tr id="attribute'+attribute_row+'">';
        html += '<td>';
        html += '<select onchange="getValues($(this).val(),'+attribute_row+')" name="attributes['+attribute_row+'][attribute_id]" class="form-control">';
        html += '<option value="*">---</option>';
        <?php foreach ($attributes as $attribute){?>
        html += '<option value="<?php echo $attribute['id'];?>"><?php echo $attribute['name'];?></option>';
        <?php } ?>
        html += '</select>';
        html += '</td>';
        html += '<td><select id="attr_values'+attribute_row+'" name="attributes['+attribute_row+'][attribute_value_id]" class="form-control" disabled></select></td>';
        html += '<td><a href="#" onclick="$(\'#attribute'+attribute_row+'\').remove(); return false;" class="btn btn-danger btn-xs"><?php echo lang('button_delete');?></a></td>';
        $("#attributes_form tbody").append(html);
        attribute_row++;

    }

    function getValues(attr_id, row_id) {
        $.get('/autoxadmin/product/get_attribute_values/'+attr_id,function (response) {
            $("#attr_values"+row_id).html(response).removeAttr('disabled');
        });
    }

    function get_brands(sku){
        if(sku.length >= 3){
            $.ajax({
                url: '/ajax/get_brands',
                method: 'POST',
                data: {search:sku},
                dataType: 'json',
                success: function(json){
                    console.log(json);
                     if(json['brands'].length > 0){
                         var html = '';
                         $.each(json['brands'], function( index, brand ) {
                            html += '<a href="#" onclick="writeInput(\''+brand['sku']+'\',\''+brand['brand']+'\',\''+brand['name']+'\', event)">'+brand['brand']+'<br><small>'+brand['name']+'</small></a><hr>';
                         });
                         $("#autocomplite").html(html).collapse('show');
                     }
                }
            });
        }
    }

    function writeInput(sku,brand,name,event){
        event.preventDefault();
        $("#input-sku").val(sku);
        $("#input-brand").val(brand);
        $("#input-name").val(name);
        $("#autocomplite").empty().hide();
    }
</script>