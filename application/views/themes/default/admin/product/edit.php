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
        <li><a href="#"><?php echo $product['name']; ?></a></li>
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
                        <?php if (mb_strlen($product['image']) > 0) { ?>
                            <img onerror="imgError(this);" id="product-image"
                                 src="/image?img=/uploads/product/<?php echo $product['image']; ?>&width=200&height=200"/>
                            <br/>
                            <a href="#"
                               onclick="$('#image').val('');$('#product-image').attr('src', '/image?width=200');return false;"><?php echo lang('button_delete'); ?></a>
                        <?php } else { ?>
                            <img onerror="imgError(this);" id="product-image"
                                 src="/image?img=<?php echo $product['image']; ?>&width=200&height=200"/><br/>
                        <?php } ?>
                    </div>
                    <input id="image" type="hidden" name="image"
                           value="<?php echo set_value('image', $product['image']); ?>">
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
                        <input type="text" name="h1" value="<?php echo set_value('h1', $product['h1']); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo set_value('title', $product['title']); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Meata description</label>
                        <input type="text" name="meta_description"
                               value="<?php echo set_value('meta_description', $product['meta_description']); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Meta keywords</label>
                        <input type="text" name="meta_keywords"
                               value="<?php echo set_value('meta_keywords', $product['meta_keywords']); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO url</label>
                        <input type="text" name="slug" value="<?php echo set_value('slug', $product['slug']); ?>"
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
                        <?php $attribute_row = 0;?>
                        <?php if($attributes){?>
                            <?php foreach ($attributes as $attribute){?>
                                <tr id="attribute<?php echo $attribute_row;?>">
                                    <td>
                                        <a href="#" onclick="delete_attribute('<?php echo $attribute['attribute_name'];?>');$('#attribute<?php echo $attribute_row;?>').remove(); return false;" class="btn btn-warning btn-xs" rel="tooltip" title="Удалить у всех товарах">x</a>
                                        <?php echo $attribute['attribute_name'];?>

                                        <input type="hidden" name="attributes[<?php echo $attribute_row;?>][attribute_name]" value="<?php echo $attribute['attribute_name'];?>">
                                    </td>
                                    <td>
                                        <?php echo $attribute['attribute_value'];?>
                                        <input type="hidden" name="attributes[<?php echo $attribute_row;?>][attribute_value]" value="<?php echo $attribute['attribute_value'];?>">
                                    </td>
                                    <td><a href="#" onclick="$('#attribute<?php echo $attribute_row;?>').remove(); return false;" class="btn btn-danger btn-xs"><?php echo lang('button_delete');?></a></td>
                                </tr>
                            <?php $attribute_row++; } ?>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><input type="text" id="new_attribute_name" class="form-control"></td>
                                <td><input type="text" id="new_attribute_value" class="form-control"></td>
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
                            <label><?php echo lang('text_sku'); ?>

                                <?php if(!$tecdoc_info){?>
                                    <span class="label label-warning"><?php echo lang('error_tecdoc');?></span>
                                <?php } ?></label>
                            <input type="text" name="sku" value="<?php echo set_value('sku', $product['sku']); ?>"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_brand'); ?></label>
                            <input type="text" name="brand" value="<?php echo set_value('brand', $product['brand']); ?>"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_name'); ?></label>
                            <input type="text" name="name" value="<?php echo set_value('name', $product['name']); ?>"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_category_id'); ?></label>
                            <select name="category_id" class="form-control">
                                <option></option>
                                <?php foreach ($category as $category) { ?>
                                    <option
                                        value="<?php echo $category['id']; ?>" <?php echo set_select('category_id', $category['id'], $category['id'] == $product['category_id']); ?>><?php echo $category['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_viewed'); ?></label>
                            <input type="text" name="viewed"
                                   value="<?php echo set_value('viewed', $product['viewed']); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('text_bought'); ?></label>
                            <input type="text" name="bought"
                                   value="<?php echo set_value('bought', $product['bought']); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php echo lang('text_description'); ?></label>
                            <textarea class="textarea"
                                      name="description"><?php echo set_value('description', $product['description']); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($prices) { ?>
                <?php $q = 1; foreach ($prices as $price) {?>
                    <div class="box box-primary" id="row<?php echo $q; ?>">
                        <input type="hidden" name="prices[<?php echo $q;?>][supplier_id]" value="<?php echo $price['supplier_id'];?>"/>
                        <div class="box-header with-border">
                            <b><?php echo lang('text_supplier_id').': '.$supplier[$price['supplier_id']]['name'];?></b>
                            <button class="btn btn-danger pull-right"
                                    onclick="$('#row<?php echo $q; ?>').remove();return false;"><?php echo lang('button_delete'); ?></button>

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
                                    <td><input type="text" name="prices[<?php echo $q; ?>][delivery_price]"
                                               value="<?php echo set_value('delivery_price', $price['delivery_price']); ?>"
                                               class="form-control" required></td>
                                    <td><input type="text" name="prices[<?php echo $q; ?>][price]"
                                               value="<?php echo set_value('price', $price['price']); ?>"
                                               class="form-control"></td>
                                    <td><input type="text" name="prices[<?php echo $q; ?>][saleprice]"
                                               value="<?php echo set_value('saleprice', $price['saleprice']); ?>"
                                               class="form-control"></td>
                                    <td>
                                        <select class="form-control" name="prices[<?php echo $q; ?>][currency_id]"
                                                required>
                                            <?php foreach ($currency as $cur) { ?>
                                                <option
                                                    value="<?php echo $cur['id']; ?>" <?php echo set_select('currency_id', $cur['id'], $cur['id'] == $price['currency_id']); ?>><?php echo $cur['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td><input type="number" name="prices[<?php echo $q; ?>][quantity]"
                                               value="<?php echo set_value('quantity', $price['quantity']); ?>"
                                               class="form-control">
                                    </td>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <a href="#" onclick="$('.product_info_<?php echo $q;?>').toggle();return false;"><?php echo lang('text_product_info');?></a>
                            <div class="product_info_<?php echo $q;?>" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo lang('text_excerpt'); ?></label>
                                        <input type="text" name="prices[<?php echo $q;?>][excerpt]"
                                               value="<?php echo set_value('excerpt', $price['excerpt']); ?>"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo lang('text_term'); ?></label>
                                        <input type="text" name="prices[<?php echo $q;?>][term]" value="<?php echo set_value('term', $price['term']); ?>"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <?php $q++;} ?>
            <?php } ?>
            <div class="box box-primary" id="row">
                <div class="box-header with-border">
                    <div class="form-group">
                        <label><?php echo lang('text_supplier_id');?></label>
                        <select name="prices[0][supplier_id]" class="form-control" onchange="getPricing($(this).val())">
                            <option></option>
                            <?php foreach ($supplier as $sup){?>
                                <option value="<?php echo $sup['id'];?>"><?php echo $sup['name'];?></option>
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
                                ></td>
                            <td><input type="text" name="prices[0][price]"
                                       id="price"
                                       value="<?php echo set_value('price'); ?>"
                                       class="form-control"></td>
                            <td><input type="text" name="prices[0][saleprice]"
                                       value="<?php echo set_value('saleprice'); ?>"
                                       class="form-control"></td>
                            <td>
                                <select class="form-control" name="prices[0][currency_id]"
                                >
                                    <option></option>
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
    var attribute_row = '<?php echo $attribute_row;?>';
    function productAttribute(){
        var attribute_name = $("#new_attribute_name").val();
        var attribute_value = $("#new_attribute_value").val();
        if(attribute_name.length && attribute_value.length){
            var html = '<tr id="attribute'+attribute_row+'"><td>'+attribute_name+'<input type="hidden" name="attributes['+attribute_row+'][attribute_name]" value="'+attribute_name+'"></td><td>'+attribute_value+'<input type="hidden" name="attributes['+attribute_row+'][attribute_value]" value="'+attribute_value+'"></td><td><a href="#" onclick="$(\'#attribute'+attribute_row+'\').remove(); return false;" class="btn btn-danger btn-xs"><?php echo lang('button_delete');?></a></td></tr>';
            $('#attributes_form > tbody').append(html);
            $("#new_attribute_name").val('');
            $("#new_attribute_value").val('');
            attribute_row++;
        }
    }

    function delete_attribute(attribute_name) {
        $.ajax({
            url: '/autoxadmin/product/delete_attribute',
            data:{attribute_name:attribute_name},
            method: 'post',
            success: function(response){
                alert(response);
            }
        });
    }
</script>