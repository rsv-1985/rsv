<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <?php echo $this->load->view('form/category', '', true); ?>
                <?php if ($brands || $attributes) { ?>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <?php if ($brands) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <?php echo lang('text_filter_brand'); ?>
                                        </a>
                                        <?php if ($this->uri->segment(4) || $this->input->get()) { ?>
                                            <small class="pull-right"><a
                                                    href="/category/<?php echo $slug; ?>"><?php echo lang('text_filter_reset'); ?></a>
                                            </small>
                                        <?php } ?>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="panel panel-default">
                                        <div id="filter-brand">
                                            <ul class="list-group">
                                                <?php foreach ($brands as $url_brand => $name_brand) { ?>
                                                    <li class="list-group-item"><a
                                                            href="/category/<?php echo $slug; ?>/brand/<?php echo $url_brand; ?>"><?php echo $name_brand; ?></a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($attributes) { ?>
                            <?php echo form_open(null,['method' => 'get', 'id' => 'filter']);?>
                            <?php $q = 0; foreach ($attributes as $attribute_name => $attribute_values){?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $q;?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#collapse<?php echo $q;?>" aria-expanded="false"
                                               aria-controls="collapse<?php echo $q;?>">
                                                <?php echo $attribute_name;?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?php echo $q;?>" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="heading<?php echo $q;?>">
                                        <div class="panel-body">
                                           <?php foreach ($attribute_values as $attr){?>
                                               <div class="form-group">
                                                   <div class="checkbox">
                                                       <label>
                                                           <input onchange="$('#filter').submit()" type="checkbox" name="<?php echo $attr['attribute_slug'];?>" value="1" <?php echo set_checkbox( $attr['attribute_slug'],1,(bool)$this->input->get($attr['attribute_slug']));?>>
                                                           <?php echo $attr['attribute_value'];?>
                                                       </label>
                                                   </div>
                                               </div>
                                           <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            <?php $q++;} ?>
                            </form>
                        <?php } ?>
                    </div>
                <?php } ?>

            </div>
            <div class="col-md-8">
                <?php if ($products) { ?>
                    <div class="row">
                        <?php foreach ($products as $product) {
                            $key = $product['product_id'] . $product['supplier_id'] . $product['term']; ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="single-shop-product">
                                    <div class="product-upper">
                                        <a href="/product/<?php echo $product['slug']; ?>">
                                            <?php if ($product['image']) { ?>
                                                <img onerror="imgError(this, 165, 165);"
                                                     src="/image?img=/uploads/product/<?php echo $product['image']; ?>&width=165&height=165"
                                                     alt="<?php echo $product['name']; ?>">
                                            <?php } elseif ($product['tecdoc_info']) { ?>
                                                <img onerror="imgError(this, 165, 165);"
                                                     src="/image?img=<?php echo $product['tecdoc_info']['article']['Image']; ?>&width=165&height=165"
                                                     alt="<?php echo $product['name']; ?>">
                                            <?php } else { ?>
                                                <img onerror="imgError(this, 165);" src="/image?width=165"
                                                     alt="<?php echo $product['name']; ?>">
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <small><?php echo $product['brand'] . ' ' . $product['sku']; ?></small>
                                    <h2>
                                        <a href="/product/<?php echo $product['slug']; ?>"><?php echo character_limiter($product['name'], 75); ?></a>
                                    </h2>
                                    <div class="product-carousel-price">
                                        <ins><?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']); ?></ins>
                                        <?php if ($product['saleprice'] > 0) { ?>
                                            <del><?php echo format_currency($product['price']); ?></del>
                                        <?php } ?><br/>
                                        <span class="label label-success">
                                            <?php echo lang('text_term'); ?>
                                            : <?php echo format_term($product['term']); ?>
                                            <?php echo lang('text_quantity'); ?>
                                            : <?php echo format_quantity($product['quantity']); ?>
                                        </span>
                                    </div>

                                    <div class="product-option-shop">
                                        <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control" value="1">
                                            <input type="hidden" name="product_id"
                                                   value="<?php echo $product['product_id']; ?>">
                                            <input type="hidden" name="supplier_id"
                                                   value="<?php echo $product['supplier_id']; ?>">
                                            <input type="hidden" name="term" value="<?php echo $product['term']; ?>">
                                            <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i
                                                class="fa fa-shopping-cart"></i></button>
                                        </span>
                                        </div>
                                        </form>
                                        <a href="/cart" class="<?php echo $key; ?>"
                                            <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                                style="display: none;"
                                            <?php } ?>
                                        ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="pull-right">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                <?php } ?>
                <hr>
                <?php echo $description; ?>
            </div>
        </div>
    </div>
</div>
