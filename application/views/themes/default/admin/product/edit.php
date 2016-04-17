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
        <li><a href="/autoxadmin/product"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $product['name'];?></a></li>
    </ol>
</section>
<?php echo form_open_multipart();?>
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <b><?php echo lang('text_product_image');?></b>
                    </div>
                    <div class="box-body">
                        <div style="text-align: center;">
                            <?php if(mb_strlen($product['image']) > 0){?>
                                <img id="product-image" src="/image?img=/uploads/product/<?php echo $product['image'];?>&width=200&height=200"/><br />
                                <a href="#" onclick="$('#image').val('');$('#product-image').attr('src', '/image?width=200');return false;"><?php echo lang('button_delete');?></a>
                            <?php }else{?>
                                <img id="product-image" src="/image?img=<?php echo $product['image'];?>&width=200&height=200"/><br />
                            <?php } ?>
                        </div>
                        <input id="image" type="hidden" name="image" value="<?php echo set_value('image', $product['image']);?>">
                        <input type="file" name="userfile">
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <b><?php echo lang('text_product_prices');?></b>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label><?php echo lang('text_delivery_price');?></label>
                            <input type="text" name="delivery_price" value="<?php echo set_value('delivery_price',$product['delivery_price']);?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_price');?></label>
                            <input type="text" name="price" value="<?php echo set_value('price',$product['price']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_saleprice');?></label>
                            <input type="text" name="saleprice" value="<?php echo set_value('saleprice',$product['saleprice']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_currency_id');?></label>
                            <select class="form-control" name="currency_id" required>
                                <?php foreach ($currency as $currency){?>
                                    <option value="<?php echo $currency['id'];?>" <?php echo set_select('currency_id', $currency['id'], $currency['id'] == $product['currency_id']);?>><?php echo $currency['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_supplier_id');?></label>
                            <select name="supplier_id" class="form-control" required>
                                <?php foreach ($supplier as $supplier){?>
                                    <option value="<?php echo $supplier['id'];?>" <?php echo set_select('supplier_id', $supplier['id'], $supplier['id'] == $product['supplier_id']);?>><?php echo $supplier['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <b><?php echo lang('text_product_seo');?></b>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>H1</label>
                            <input type="text" name="h1" value="<?php echo set_value('h1', $product['h1']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" value="<?php echo set_value('title', $product['title']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Meata description</label>
                            <input type="text" name="meta_description" value="<?php echo set_value('meta_description', $product['meta_description']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Meta keywords</label>
                            <input type="text" name="meta_keywords" value="<?php echo set_value('meta_keywords', $product['meta_keywords']);?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>SEO url</label>
                            <input type="text" name="slug" value="<?php echo set_value('slug', $product['slug']);?>" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <b><?php echo lang('text_product_main');?></b>
                        <div class="pull-right">
                            <?php echo lang('text_created_at');?>:<?php echo $product['created_at'];?>
                            <?php echo lang('text_updated_at');?>:<?php echo $product['updated_at'];?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_sku');?></label>
                                <input type="text" name="sku" value="<?php echo set_value('sku', $product['sku']);?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_brand');?></label>
                                <input type="text" name="brand" value="<?php echo set_value('brand', $product['brand']);?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_name');?></label>
                                <input type="text" name="name" value="<?php echo set_value('name', $product['name']);?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_quantity');?></label>
                                <input type="number" name="quantity" value="<?php echo set_value('quantity', $product['quantity']);?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_category_id');?></label>
                                <select name="category_id" class="form-control">
                                    <option></option>
                                    <?php foreach ($category as $category) {?>
                                        <option value="<?php echo $category['id'];?>" <?php echo set_select('category_id', $category['id'], $category['id'] == $product['category_id']);?>><?php echo $category['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_status');?></label>
                                <select name="status" class="form-control">
                                    <option value="0" <?php echo set_select('status',0, 0 == $product['status']);?>><?php echo lang('text_status_off');?></option>
                                    <option value="1" <?php echo set_select('status',0, 1 == $product['status']);?>><?php echo lang('text_status_on');?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <b><?php echo lang('text_product_info');?></b>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo lang('text_description');?></label>
                                <textarea class="textarea" name="description"><?php echo set_value('description', $product['description']);?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_excerpt');?></label>
                                <input type="text" name="excerpt" value="<?php echo set_value('excerpt', $product['excerpt']);?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_term');?></label>
                                <input type="text" name="term" value="<?php echo set_value('term', $product['term']);?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_viewed');?></label>
                                <input type="text" name="viewed" value="<?php echo set_value('viewed', $product['viewed']);?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_bought');?></label>
                                <input type="text" name="bought" value="<?php echo set_value('bought', $product['bought']);?>" class="form-control">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="pull-right">
                            <a href="/autoxadmin/product/delete/<?php echo $product['slug'];?>" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                            <button type="submit" class="btn btn-info"><?php echo lang('button_submit');?></button>
                            <a href="/autoxadmin/product" class="btn btn-default"><?php echo lang('button_close');?></a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
</form>