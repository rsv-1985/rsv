<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="product-big-title-area">
    <div class="container">
        <?php if($breadcrumbs){?>
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                        <?php $q = 0; foreach ($breadcrumbs as $breadcrumb){?>
                            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="breadcrumb-item">
                                <a itemprop="item" href="<?php echo $breadcrumb['href'];?>">
                                    <span itemprop="name"><?php echo $breadcrumb['name'];?></span>
                                </a>
                                <meta itemprop="position" content="<?php echo $q;?>">
                            </li>
                            <?php $q++; } ?>
                    </ol>
                </div>
            </div>
        <?php } ?>
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
                <?php if ($brands || $attributes) { $q = 0;?>
                    <?php if($checked_values){?>
                        <div class="well well-sm">
                            <p>
                                <?php if($brands){?>
                                    <?php foreach ($brands as $brand) {?>
                                        <?php if($brand['checked']){?>
                                            <span  onclick="$('#<?php echo $brand['slug'];?>').prop('checked',false).change();return false;" class="btn btn-default btn-xs"><?php echo $brand['name']; ?> <i class="glyphicon glyphicon-remove"></i></span>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if($attributes){?>
                                    <?php foreach ($attributes as $attribute){?>
                                        <?php if($attribute){?>
                                            <?php foreach ($attribute['values'] as $value){?>
                                                <?php if($value['checked']){?>
                                                    <span  onclick="$('#<?php echo $value['slug'];?>').prop('checked',false).change();return false;" class="btn btn-default btn-xs"><?php echo $value['text']; ?> <i class="glyphicon glyphicon-remove"></i></span>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </p>

                            <p>
                                <a href="/category/<?php echo $slug;?>">Сбросить все</a>
                            </p>

                        </div>
                    <?php } ?>

                    <div id="filter" class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <?php if($brands){?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <?php echo lang('text_filter_brand');?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body" style="max-height: 250px; overflow: auto;">
                                        <?php foreach ($brands as $brand) {?>
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <label>
                                                        <input
                                                                id="<?php echo $brand['slug']; ?>"
                                                                onchange="filterProducts()"
                                                                type="checkbox"
                                                                name="brand"
                                                                value="<?php echo $brand['slug']; ?>"
                                                                <?php if($brand['checked']){?>
                                                                    checked
                                                                <?php } ?>
                                                        >
                                                        <?php echo $brand['name']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php } ?></div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if($attributes){?>
                            <?php foreach ($attributes as $attribute){?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <?php echo $attribute['name'];?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse <?php if($attribute['max_height']){?>in<?php } ?>" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body" style="max-height: <?php echo $attribute['max_height'] ? $attribute['max_height'] : 430;?>px; overflow: auto;">
                                            <?php foreach ($attribute['values'] as $attr) { ?>
                                                <div class="form-group">
                                                    <div class="checkbox <?php if(!$attr['possible']){?>
                                                                        disabled
                                                                    <?php } ?>">
                                                        <label>
                                                            <input
                                                                    id="<?php echo $attr['slug']; ?>"
                                                                    onchange="filterProducts()"
                                                                    type="checkbox"
                                                                    name="<?php echo $attribute['slug']; ?>"
                                                                    value="<?php echo $attr['slug']; ?>"
                                                                <?php if($attr['checked']){?>
                                                                    checked
                                                                <?php } ?>
                                                                    <?php if(!$attr['possible']){?>
                                                                        disabled
                                                                    <?php } ?>
                                                            >
                                                            <?php echo $attr['text']; ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-8">
                <?php if($categories){?>
                    <div class="row">
                        <div class="col-md-12">
                            <b><?php echo lang('text_subcategory');?></b>
                            <ul>
                                <?php foreach ($categories as $category){?>
                                    <li><a href="/category/<?php echo $category['slug'];?>"><?php echo $category['name'];?></a> </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($products) { ?>
                    <div class="row">
                        <?php foreach ($products as $product) { ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="single-shop-product">
                                    <div class="product-upper">
                                        <a href="/product/<?php echo $product['slug']; ?>">
                                            <?php if ($product['image']) { ?>
                                                <img onerror="imgError(this, 165, 165);"
                                                     src="/image?img=<?php echo $product['image']; ?>&width=165&height=165"
                                                     alt="<?php echo $product['name']; ?>">
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <small><?php echo $product['brand'] . ' ' . $product['sku']; ?></small>
                                    <div class="name">
                                        <a href="/product/<?php echo $product['slug']; ?>"><?php echo $product['name']; ?></a>
                                    </div>

                                    <div class="product-carousel-price">
                                        <b><?php echo $product['prices_text']; ?></b>
                                    </div>
                                    <div class="product-option-shop">
                                        <a rel="nofollow" class="btn btn-default" data-toggle="modal" data-target="#modal-price-<?php echo $product['id'];?>" href="#"><?php echo lang('button_cart'); ?></a>
                                    </div>

                                    <div class="modal fade" id="modal-price-<?php echo $product['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"><?php echo plural_form(count($product['prices']),[lang('text_offer_1'),lang('text_offer_2'),lang('text_offer_5')]);?></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php foreach ($product['prices'] as $price){ $key = $product['id'] . $price['supplier_id'] . $price['term'] ?>
                                                        <div class="row item">
                                                            <div class="col-md-3">
                                                                <?php echo format_currency($price['saleprice'] > 0 ? $price['saleprice'] : $price['price']);?>
                                                                <?php if($price['excerpt']){?>
                                                                    <br/>
                                                                    <small>
                                                                        <?php echo $price['excerpt'];?>
                                                                    </small>
                                                                <?php } ?>

                                                            </div>
                                                            <div class="col-md-3">
                                                                <?php echo format_term($price['term']);?>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <?php echo format_quantity($price['quantity']);?>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                                                <div class="input-group">
                                                                    <input placeholder="кол." type="number"
                                                                           name="quantity"
                                                                           class="form-control">
                                                                    <input type="hidden" name="product_id"
                                                                           value="<?php echo $product['id']; ?>">
                                                                    <input type="hidden" name="supplier_id"
                                                                           value="<?php echo $price['supplier_id']; ?>">
                                                                    <input type="hidden" name="term"
                                                                           value="<?php echo $price['term']; ?>">
                                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="submit"><i
                                                                    class="fa fa-shopping-cart"></i></button>
                                                    </span>
                                                                </div>
                                                                </form>
                                                                <small>
                                                                    <a href="/cart" class="<?php echo $key; ?>"
                                                                        <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                                                            style="display: none;"
                                                                        <?php } ?>
                                                                    ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                                    </a>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
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

<script>


    function filterProducts(){
        var Filters = {};

        $("#filter input:checked").each(function(index,item){
            Filters[$(item).attr('name')] = [];
        });

        $("#filter input:checked").each(function(index,item){
            if($(item).attr('value').length){
                Filters[$(item).attr('name')].push($(item).attr('value'));
            }
        });

        var url_filters = [];

        if(Filters){
            $.each(Filters, function (key, items)  {
                if(items){
                    url_filters.push(key+'='+items.join(','));
                }
            });
        }

        location.href = '/category/<?php echo $slug;?>/'+url_filters.join(';');
    }

    $(document).ready(function(){

        $("form#filter input:checked").each(function(index,item){
            console.log(item);
            $("#collapse"+$(item).attr("id")).addClass('in');
        });
    });
</script>
