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
                            <th><?php echo lang('text_product_attribute_name'); ?></th>
                            <th><?php echo lang('text_product_attribute_value'); ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <td><input type="text" id="new_attribute_name" class="form-control"></td>
                            <td><input type="text" id="new_attribute_value" class="form-control"></td>
                            <td><a href="#" onclick="productAttribute(); return false;"
                                   class="btn btn-info btn-xs"><?php echo lang('button_add'); ?></a></td>
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
                            <input id="input-sku" onkeyup="search($(this).val())" type="text" name="sku" value="<?php echo set_value('sku'); ?>"
                                   class="form-control" required>
                            <div id="autocomplite" style="display: none;position: absolute;background: white;width: 100%;z-index: 9;">
                            </div>
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
                                <?php foreach ($category as $category) { ?>
                                    <option
                                        value="<?php echo $category['id']; ?>" <?php echo set_select('category_id', $category['id']); ?>><?php echo $category['name']; ?></option>
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
                        <select name="prices[0][supplier_id]" class="form-control" required onchange="getPricing($(this).val())">
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
                            <td><input type="text" name="prices[0][price]"
                                       id="price"
                                       value="<?php echo set_value('price'); ?>"
                                       class="form-control"></td>
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
    var attribute_row = 0;
    function productAttribute() {
        var attribute_name = $("#new_attribute_name").val();
        var attribute_value = $("#new_attribute_value").val();
        if (attribute_name.length && attribute_value.length) {
            var html = '<tr id="attribute' + attribute_row + '"><td>' + attribute_name + '<input type="hidden" name="attributes[' + attribute_row + '][attribute_name]" value="' + attribute_name + '"></td><td>' + attribute_value + '<input type="hidden" name="attributes[' + attribute_row + '][attribute_value]" value="' + attribute_value + '"></td><td><a href="#" onclick="$(\'#attribute' + attribute_row + '\').remove(); return false;" class="btn btn-danger btn-xs"><?php echo lang('button_delete');?></a></td></tr>';
            $('#attributes_form > tbody').append(html);
            $("#new_attribute_name").val('');
            $("#new_attribute_value").val('');
            attribute_row++;
        }
    }
    function search(sku){
        if(sku.length >= 3){
            $.ajax({
                url: '/ajax/pre_search',
                method: 'POST',
                data: {search:sku},
                dataType: 'json',
                success: function(json){
                     if(json['brand'].length > 0){
                         var html = '';
                         $.each(json['brand'], function( index, brand ) {
                            html += '<a href="#" onclick="writeInput(\''+brand['sku']+'\',\''+brand['brand']+'\',\''+brand['name']+'\', event)">'+brand['brand']+'<br><small>'+brand['name']+'</small></a><hr>';
                         });
                         $("#autocomplite").html(html).show();
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

    var pricing;
    function getPricing(supplier_id){
        $.ajax({
            url:'/autoxadmin/product/get_supplier_prices',
            data:{supplier_id:supplier_id},
            method: 'post',
            success: function (json) {
                pricing = json;
            }
        });
    }
</script>