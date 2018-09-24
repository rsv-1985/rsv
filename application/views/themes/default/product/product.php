<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css"
      xmlns="http://www.w3.org/1999/html"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
<style>
    .product .product-images {
        display: none;
    }

    img {
        max-height: 280px;
    }

    .product p.price {
        font-size: 38px;
        font-weight: bold;
    }

    .table {
        font-size: 14px;
    }

    td.search-product-cart {
        width: 105px;
    }

    .widget > .panel-body {
        height: 125px;
        overflow: hidden;
    }

    .widget span.brand {
        font-weight: bold;
    }

    .row.item {
        border: 1px solid #e5e5e5;
        margin: 5px;
        padding: 5px;
    }

    .table-condensed > tbody > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > thead > tr > th {
        padding: 1px;
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        padding: 1px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 0px;
    }

    td.search-product-price {
        width: 100px;
    }

    td.search-product-excerpt {
        font-size: 12px;
        text-align: left;
        width: auto;
    }

    td.search-product-fast {
        width: 81px;
    }

    @media (min-width: 500px) {
        .table_info_item .search-product-excerpt {
            width: 115px;
            line-height: 15px;
        }

        .table_info_item .search-product-term {
            padding: 2px 10px;
            width: 86px;
            line-height: 14px;
        }
    }

    @media (max-width: 991px) {
        .row.item {
            margin: 0 0 15px;
            border: 1px solid #acaaaa;
        }

        .table_info_item .table-responsive {
            border: none;
        }

        .table_info_item {
            padding: 12px 0 0;
            margin-top: 13px;
            border-top: 1px solid #efefef;
        }

        .table_info_item td.search-product-term {
            width: 60px;
            white-space: normal !important;
            line-height: 13px;
            padding-right: 11px !important;
        }

        .label_info_detail {
            padding-left: 3px;
        }

        .table_info_item td:not(:first-child) {
            display: block;
            float: left;
            width: 24%;
            line-height: 20px;
        }

        .table_info_item tbody {
            width: 100%;
            display: block;
        }

        .table_info_item .search-product-term,
        .table_info_item .search-product-quantity,
        .table_info_item .search-product-price {
            padding-top: 5px;
        }

        .table_info_item .search-product-cart input {
            width: 56px;
            min-width: 56px;
            padding: 6px;
        }
    }

    @media (max-width: 500px) {
        .table_info_item table, .table_info_item tr, .table_info_item tr > td:nth-child(1) {
            display: block;
        }

        .table_info_item tr:not(:first-child) > td:nth-child(1) {
            margin-top: 15px;
            padding-top: 5px;
            border-top: 1px solid #efefef;
        }

        .table-hover > tbody > tr:hover {
            background-color: #ffffff;
        }

        .table_info_item td:not(.search-product-excerpt) {
            font-size: 14px !important;
            letter-spacing: -1.1px;
        }

        .label_info_detail .label {
            margin-bottom: 6px;
            display: inline-block;
        }

        .label_info_detail .glyphicon {
            font-size: 20px;
            margin-right: 9px;
        }

        .table_info_item .search-product-cart {
            width: 76px;
        }

        .table_info_item .search-product-cart input {
            width: 53px;
            min-width: 53px;
            padding: 6px;
        }
    }

    @media (max-width: 365px) {
        .table_info_item .search-product-cart input {
            width: 12px;
            min-width: 39px;
            padding: 6px;
        }
    }
</style>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <?php foreach ($breadcrumbs as $index => $breadcrumb) { ?>
                        <?php if ($breadcrumb['href']) { ?>
                            <li itemprop="itemListElement" itemscope
                                itemtype="http://schema.org/ListItem" class="breadcrumb-item">
                                <a itemprop="item" href="<?php echo $breadcrumb['href']; ?>">
                                    <span itemprop="name"><?php echo $breadcrumb['text']; ?></span>
                                </a>
                                <meta itemprop="position" content="<?php echo $index; ?>"/>
                            </li>
                        <?php } else { ?>
                            <li><?php echo $breadcrumb['text']; ?></li>
                        <?php } ?>
                    <?php } ?>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="product">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div style="text-align: center;">
                    <?php if ($images) { ?>
                        <a href="<?php echo $images[0]['src']; ?>" data-fancybox="quick-view-1"
                           data-type="image">
                            <img src="<?php echo $images[0]['src']; ?>" alt="<?php echo $images[0]['alt']; ?>"/>
                        </a>
                        <?php unset($images[0]);
                    } else { ?>
                        <a href="/image" data-fancybox="quick-view-1" data-type="image">
                            <img src="/image"/>
                        </a>
                    <?php } ?>
                    <div class="product-images">
                        <?php if ($images) { ?>
                            <?php foreach ($images as $image) { ?>
                                <a href="<?php echo $image['src']; ?>" data-fancybox="quick-view-1"
                                   data-type="image">
                                    <img src="<?php echo $image['src']; ?>" alt="<?php echo $image['alt']; ?>"/>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php if ($banner) { ?>
                        <div class="single-sidebar">
                            <?php foreach ($banner as $banner) { ?>
                                <div class="thubmnail-recent">
                                    <a href="<?php echo $banner['link']; ?>"
                                       <?php if ($banner['new_window']){ ?>target="_blank" <?php } ?>>
                                        <img onerror="imgError(this);"
                                             src="/uploads/banner/<?php echo $banner['image']; ?>">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="jumbotron" style="text-align: center;">
                    <?php if ($one_price) { ?>
                        <?php echo lang('text_term'); ?>
                        <small title="<?php echo lang('text_term'); ?>"><?php echo format_term($one_price['term']); ?></small>
                        <br>
                        <?php echo lang('text_quantity'); ?>
                        <small title="<?php echo lang('text_quantity'); ?>"><?php echo format_quantity($one_price['quantity']); ?></small>
                        <hr>
                        <?php if ($one_price['saleprice'] > 0) { ?>
                            <p class="price">
                                <?php echo format_currency($one_price['saleprice']); ?>
                            </p>
                            <p>
                                <strike><?php echo format_currency($one_price['price']); ?></strike>
                            </p>
                        <?php } else { ?>
                            <p class="price">
                                <?php echo format_currency($one_price['price']); ?>
                            </p>
                        <?php } ?>
                        <hr>
                        <?php $key = $one_price['product_id'] . $one_price['supplier_id'] . $one_price['term'];
                        echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                        <div class="input-group">
                            <input style="padding: 20px;" type="number" name="quantity"
                                   class="form-control" value="1">
                            <input type="hidden" name="product_id"
                                   value="<?php echo $one_price['product_id']; ?>">
                            <input type="hidden" name="supplier_id"
                                   value="<?php echo $one_price['supplier_id']; ?>">
                            <input type="hidden" name="term"
                                   value="<?php echo $one_price['term']; ?>">
                            <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit"><i
                                                        class="fa fa-shopping-cart"></i> <?php echo lang('button_cart'); ?></button>
                                            </span>
                        </div>
                        </form>
                        <a href="/cart" class="<?php echo $key; ?>"
                            <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                style="display: none;"
                            <?php } ?>>
                            <i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                        </a>
                        <br><a href="#"
                               onclick="fastOrder('<?php echo $_SERVER['REQUEST_URI']; ?>',event);"><?php echo lang('text_fast_order_link'); ?></a>
                    <?php } else { ?>
                        <p><?php echo lang('text_not_available'); ?></p>
                    <?php } ?>
                    <div class="reviews">
                        <?php if ($count_reviews > 0) { ?>
                            <br>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <?php if ($avg_rating < $i) { ?>
                                    <i class="fa fa-star fa-lg" aria-hidden="true"></i>
                                <?php } else { ?>
                                    <i class="fa fa-star fa-lg active" aria-hidden="true"></i>
                                <?php } ?>
                            <?php } ?>
                            <br>
                        <?php } ?>
                        <a href="#review-h"><?php echo lang('text_review');?>: <?php echo $count_reviews; ?></a>
                    </div>

                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <?php if ($one_price['excerpt']) { ?>
                    <b class="label label-warning"><?php echo lang('text_excerpt'); ?></b>
                    <ul>
                        <li><?php echo $one_price['excerpt']; ?></li>
                    </ul>
                <?php } ?>
                <?php if ($delivery_methods) { ?>
                    <b><?php echo lang('text_product_delivery_label'); ?></b>
                    <ul>
                        <?php foreach ($delivery_methods as $delivery_method) { ?>
                            <li><?php echo $delivery_method['name']; ?>
                                <?php if ($delivery_method['description']) { ?>
                                    <a data-trigger="hover" data-container="body" data-html="true"
                                       data-toggle="popover" data-placement="right"
                                       data-content="<?php echo htmlspecialchars($delivery_method['description']); ?>"
                                       title="<?php echo $delivery_method['name']; ?>">
                                        <small>
                                            <i class="glyphicon glyphicon-info-sign"></i>
                                        </small>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
                <?php if ($payment_methods) { ?>
                    <b><?php echo lang('text_product_payment_label'); ?></b>
                    <ul>
                        <?php foreach ($payment_methods as $payment_method) { ?>
                            <li>
                                <?php echo $payment_method['name']; ?>
                                <?php if ($payment_method['description']) { ?>
                                    <a data-trigger="hover" data-container="body" data-html="true"
                                       data-toggle="popover" data-placement="right"
                                       data-content="<?php echo htmlspecialchars($payment_method['description']); ?>"
                                       title="<?php echo $payment_method['name']; ?>">
                                        <small>
                                            <i class="glyphicon glyphicon-info-sign"></i>
                                        </small>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3><?php echo lang('text_attributes'); ?></h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <td><?php echo lang('text_sku'); ?></td>
                            <td><?php echo $sku; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('text_brand'); ?></td>
                            <td><?php echo $brand; ?></td>
                        </tr>
                        <?php if ($attributes) { ?>
                            <?php foreach ($attributes as $attribute) {
                                ; ?>
                                <tr>
                                    <td><?php echo $attribute['attribute_name']; ?></td>
                                    <td><?php echo $attribute['attribute_value']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($tecdoc_attributes) { ?>
                            <?php foreach ($tecdoc_attributes as $attribute) {
                                ; ?>
                                <tr>
                                    <td><?php echo $attribute['attribute_name']; ?></td>
                                    <td><?php echo $attribute['attribute_value']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                </div>
                <?php if ($applicability) { ?>
                    <h3><?php echo lang('text_applicability'); ?></h3>
                    <?php $q = 0;
                    foreach ($applicability as $brand_name => $ap) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading<?php echo $q; ?>">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse"
                                       data-parent="#accordion"
                                       href="#collapse<?php echo $q; ?>" aria-expanded="false"
                                       aria-controls="collapse<?php echo $q; ?>">
                                        <?php echo $brand_name; ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse<?php echo $q; ?>" class="panel-collapse collapse"
                                 role="tabpanel" aria-labelledby="heading<?php echo $q; ?>">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-striped">
                                            <thead>
                                            <tr>
                                                <th><?php echo lang('text_model'); ?></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($ap as $applicability) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $applicability->Model; ?>
                                                    </td>
                                                    <td>
                                                        <small><?php echo $applicability->DateMake; ?></small>
                                                    </td>
                                                    <td>
                                                        <?php echo $applicability->Name; ?><?php echo $applicability->Fuel; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $q++;
                    } ?>
                <?php } ?>
                <h3 id="review-h"><?php echo lang('text_review');?></h3>
                <?php if ($count_reviews > 0) { ?>
                    <?php foreach ($reviews as $review) { ?>
                        <div class="media">
                            <div class="media-body">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <?php if ($review['rating'] < $i) { ?>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-star active" aria-hidden="true"></i>
                                    <?php } ?>
                                <?php } ?>
                                <br><?php echo $review['author'];?>

                                <small class="pull-right">
                                    <?php echo $review['created_at']; ?>
                                </small>
                                <p>
                                    <?php echo $review['text']; ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <a class="btn btn-info" style="width: 100%" role="button" data-toggle="collapse" href="#review">
                    <?php echo lang('text_write_review');?>
                </a>
                <div class="clearfix"></div>
                <div class="collapse" id="review">
                    <form id="review_form">
                        <input type="hidden" name="product_id" value="<?php echo $id;?>">
                        <div class="well well-sm">
                            <div class="form-group">
                                <label><?php echo lang('text_review_rating');?></label>
                                <div class="rating_form">
                                    <label data-rate="1"><input required id="r1" name="rating" value="1" type="radio"><i class="fa fa-star r1" aria-hidden="true"></i></label>
                                    <label data-rate="2"><input required id="r2"  name="rating" value="2" type="radio"><i class="fa fa-star r2" aria-hidden="true"></i></label>
                                    <label data-rate="3"><input required id="r3"  name="rating" value="3" type="radio"><i class="fa fa-star r3" aria-hidden="true"></i></label>
                                    <label data-rate="4"><input required id="r4"  name="rating" value="4" type="radio"><i class="fa fa-star r4" aria-hidden="true"></i></label>
                                    <label data-rate="5"><input required id="r5"  name="rating" value="5" type="radio"><i class="fa fa-star r5" aria-hidden="true"></i></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_review_author');?></label>
                                <input type="text" name="author" class="form-control" maxlength="64">
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_review_contact');?></label>
                                <input type="text" name="contact" class="form-control" maxlength="64">
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_review_text');?></label>
                                <textarea required minlength="10" maxlength="3000" name="text" rows="8" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info pull-right">
                                    <?php echo lang('button_send');?>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-md-8 col-sm-12">
                <?php if ($prices || $cross) { ?>
                    <h3>
                        Всего <?php echo plural_form(count($prices), [lang('text_offer_1'), lang('text_offer_2'), lang('text_offer_5')]); ?></h3>

                    <?php if ($prices) { ?>
                        <div class="row item">
                            <div class="col-md-4 col-sm-12 col-xs-7">
                                <b><?php echo $brand; ?></b> <?php echo $sku; ?>
                            </div>
                            <div class="col-md-8 col-xs-12 table_info_item" style="text-align: center;">
                                <div class="table-responsive">
                                    <table class="table table-hover table-condensed">
                                        <?php $q = 1;
                                        foreach ($prices as $price) {
                                            $price['key'] = $price['product_id'] . $price['supplier_id'] . $price['term']; ?>
                                            <tr id="<?php echo $price['key']; ?>"
                                                class="<?php echo format_term_class($price['term']); ?> product-<?php echo $price['product_id']; ?>"
                                                <?php if ($q > 5){ ?>style="display: none" <?php } ?>>
                                                <?php if ($this->is_admin) { ?>
                                                    <td>
                                                        <?php echo $this->supplier_model->suppliers[$price['supplier_id']]['name'] . '<br>' . $price['delivery_price'] . ' ' . $this->currency_model->currencies[$price['currency_id']]['name'] . ' ' . $price['quantity'] . 'шт.'; ?>
                                                        "
                                                    </td>
                                                <?php } ?>
                                                <td class="search-product-excerpt">
                                                    <?php echo $price['excerpt']; ?>
                                                </td>
                                                <td class="search-product-term"><?php echo format_term($price['term']); ?></td>
                                                <td class="search-product-quantity"><?php echo format_quantity($price['quantity']); ?></td>
                                                <td class="search-product-price">
                                                    <?php if ($price['saleprice'] > 0) { ?>
                                                        <?php echo format_currency($price['saleprice']); ?><br>
                                                        <small>
                                                            <strike><?php echo format_currency($price['price']); ?></strike>
                                                        </small>
                                                    <?php } else { ?>
                                                        <?php echo format_currency($price['price']); ?>
                                                    <?php } ?>
                                                </td>
                                                <?php if (@$this->options['show_fast_order_search']) { ?>
                                                    <td class="search-product-fast">
                                                        <a href="#"
                                                           onclick="fastOrder('/product/<?php echo $slug; ?>',event);"><?php echo strip_tags(lang('text_fast_order_link')); ?></a>
                                                    </td>
                                                <?php } ?>
                                                <td class="search-product-cart">
                                                    <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                                    <div class="input-group">
                                                        <input placeholder="кол." type="number"
                                                               name="quantity"
                                                               class="form-control">
                                                        <input type="hidden" name="product_id"
                                                               value="<?php echo $price['product_id']; ?>">
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
                                                        <a href="/cart" class="<?php echo $price['key']; ?>"
                                                            <?php if (!key_exists(md5($price['key']), $this->cart->contents())) { ?>
                                                                style="display: none;"
                                                            <?php } ?>
                                                        ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                        </a>
                                                    </small>
                                                </td>
                                            </tr>
                                            <?php $q++;
                                        } ?>
                                    </table>
                                    <?php if ($q > 6) { ?>
                                        <button id="show-buttom-<?php echo $price['product_id']; ?>"
                                                class="btn btn-link"
                                                onclick="show(<?php echo $price['product_id']; ?>)">Показать еще
                                            (<?php echo $q - 6; ?>)
                                        </button>
                                        <button style="display: none;"
                                                id="hide-buttom-<?php echo $price['product_id']; ?>"
                                                class="btn btn-link"
                                                onclick="hide2(<?php echo $price['product_id']; ?>)">Скрыть
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($cross) { ?>
                        <h3><?php echo lang('text_product_cross'); ?></h3>
                        <?php foreach ($cross as $product) { ?>
                            <?php if (!$product['prices']) {
                                continue;
                            } ?>
                            <div class="row item brand <?php echo md5($product['brand']); ?>">
                                <div class="col-md-4 col-sm-12 col-xs-7">
                                    <a href="/product/<?php echo $product['slug']; ?>">
                                        <b><?php echo $product['brand']; ?></b>
                                        <?php echo $product['sku']; ?>
                                        <br><?php echo $product['name']; ?>
                                    </a>
                                </div>
                                <div class="col-md-8 col-xs-12 table_info_item" style="text-align: center;">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-condensed">
                                            <?php $q = 1;
                                            foreach ($product['prices'] as $price) {
                                                $price['key'] = $product['id'] . $price['supplier_id'] . $price['term']; ?>
                                                <tr id="<?php echo $price['key']; ?>"
                                                    class="<?php echo format_term_class($price['term']); ?> product-<?php echo $product['id']; ?>"
                                                    <?php if ($q > 1){ ?>style="display: none" <?php } ?>>
                                                    <?php if ($this->is_admin) { ?>
                                                        <td>
                                                            <?php echo $this->supplier_model->suppliers[$price['supplier_id']]['name'] . '<br>' . $price['delivery_price'] . ' ' . $this->currency_model->currencies[$price['currency_id']]['name'] . ' ' . $price['quantity'] . 'шт.'; ?>
                                                            "
                                                        </td>
                                                    <?php } ?>
                                                    <td class="search-product-excerpt">
                                                        <?php echo $price['excerpt']; ?>
                                                    </td>
                                                    <td class="search-product-term"><?php echo format_term($price['term']); ?></td>
                                                    <td class="search-product-quantity"><?php echo format_quantity($price['quantity']); ?></td>
                                                    <td class="search-product-price">
                                                        <?php if ($price['saleprice'] > 0) { ?>
                                                            <?php echo format_currency($price['saleprice']); ?><br>
                                                            <small>
                                                                <strike><?php echo format_currency($price['price']); ?></strike>
                                                            </small>
                                                        <?php } else { ?>
                                                            <?php echo format_currency($price['price']); ?>
                                                        <?php } ?>
                                                    </td>
                                                    <?php if (@$this->options['show_fast_order_search']) { ?>
                                                        <td class="search-product-fast">
                                                            <a href="#"
                                                               onclick="fastOrder('/product/<?php echo $product['slug']; ?>',event);"><?php echo strip_tags(lang('text_fast_order_link')); ?></a>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="search-product-cart">
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
                                                            <a href="/cart" class="<?php echo $price['key']; ?>"
                                                                <?php if (!key_exists(md5($price['key']), $this->cart->contents())) { ?>
                                                                    style="display: none;"
                                                                <?php } ?>
                                                            ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                            </a>
                                                        </small>
                                                    </td>
                                                </tr>
                                                <?php $q++;
                                            } ?>
                                        </table>
                                        <?php if ($q > 2) { ?>
                                            <button id="show-buttom-<?php echo $product['id']; ?>"
                                                    class="btn btn-link"
                                                    onclick="show(<?php echo $product['id']; ?>)">Показать еще
                                                (<?php echo $q - 2; ?>)
                                            </button>
                                            <button style="display: none;"
                                                    id="hide-buttom-<?php echo $product['id']; ?>"
                                                    class="btn btn-link"
                                                    onclick="hide(<?php echo $product['id']; ?>)">Скрыть
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <?php echo lang('text_not_available'); ?>
                <?php } ?>


                <?php if ($components) { ?>
                    <h3><?php echo lang('text_components'); ?></h3>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th><?php echo lang('text_brand'); ?></th>
                                <th><?php echo lang('text_sku'); ?></th>
                                <th><?php echo lang('text_name'); ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($components as $components) { ?>
                                <tr>
                                    <td><?php echo $components->Brand; ?></td>
                                    <td><?php echo $components->Display; ?></td>
                                    <td><?php echo $components->Name; ?></td>
                                    <td>
                                        <a href="/search?search=<?php echo $components->Display; ?>&ID_art=<?php echo $components->ID_art; ?>&brand=<?php echo $components->Brand; ?>"><?php echo lang('button_search'); ?></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>

                <?php echo $description; ?>

            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        var one_day = [];
        $(".one_day").each(function (index, item) {
            one_day.push(item);
        });
        var in_stock = [];
        $(".in_stock").each(function (index, item) {
            in_stock.push(item);
        });

        if (one_day) {
            one_day = one_day.reverse();
            $(one_day).each(function (index, i) {
                $(i).parent('tbody').prepend(i).show();
            });
            $(".one_day").show();
        }

        if (in_stock) {
            in_stock = in_stock.reverse();
            $(in_stock).each(function (index, i) {
                $(i).parent('tbody').prepend(i).show();
            });

            $(".in_stock").show();
        }


    });
    $(function () {
        $('[data-toggle="popover"]').popover()
    });
    $('[data-fancybox^="quick-view"]').fancybox({
        animationEffect: "fade",
        animationDuration: 300,
        margin: 0,
        gutter: 0,
        touch: {
            vertical: false
        },
    });

    function show(product_id) {
        $(".product-" + product_id).fadeIn();
        $("#hide-buttom-" + product_id).show();
        $("#show-buttom-" + product_id).hide();
    }

    function hide(product_id) {
        $(".product-" + product_id).each(function (index, item) {
            if (index >= 1) {
                $(item).hide();
            }
        });
        $("#hide-buttom-" + product_id).hide();
        $("#show-buttom-" + product_id).show();
    }

    function hide2(product_id) {
        $(".product-" + product_id).each(function (index, item) {
            if (index >= 5) {
                $(item).hide();
            }
        });
        $("#hide-buttom-" + product_id).hide();
        $("#show-buttom-" + product_id).show();
    }
</script>