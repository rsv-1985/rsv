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

    img{
        max-height: 280px;
    }

    .product p.price {
        font-size: 38px;
        font-weight: bold;
    }
</style>
<div itemscope itemtype="http://schema.org/Product">
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h1 itemprop="name"><?php echo $h1; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product">
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
                <div class="col-md-4 col-sm-12" style="text-align: center;">
                    <?php if ($image) { ?>
                        <a href="/uploads/product/<?php echo $image; ?>" data-fancybox="quick-view-1"
                           data-type="image">
                            <img src="/uploads/product/<?php echo $image; ?>"/>
                        </a>
                    <?php } else if ($tecdoc_info['images']) { ?>
                        <a href="<?php echo $tecdoc_info['images'][0]->Image; ?>" data-fancybox="quick-view-1"
                           data-type="image">
                            <img src="<?php echo $tecdoc_info['images'][0]->Image; ?>"/>
                        </a>
                    <?php } else { ?>
                        <a href="/image" data-fancybox="quick-view-1" data-type="image">
                            <img src="/image"/>
                        </a>
                    <?php } ?>
                    <div class="product-images">
                        <?php if ($image || $tecdoc_info['images']) { ?>
                            <?php if ($image) { ?>
                                <a href="/uploads/product/<?php echo $image; ?>" data-fancybox="quick-view-1"
                                   data-type="image">
                                    <img src="/uploads/product/<?php echo $image; ?>"/>
                                </a>
                            <?php } ?>
                            <?php if ($tecdoc_info['images']) { ?>
                                <?php foreach ($tecdoc_info['images'] as $tc_image) { ?>
                                    <a href="<?php echo $tc_image->Image; ?>" data-fancybox="quick-view-1"
                                       data-type="image">
                                        <img src="<?php echo $tc_image->Image; ?>"/>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="/image" data-fancybox="quick-view-1" data-type="image">
                                <img src="/image"/>
                            </a>
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
                                            <button class="btn btn-штащ" type="submit"><i
                                                        class="fa fa-shopping-cart"></i> Купить</button>
                                            </span>
                            </div>
                            </form>
                            <a href="/cart" class="<?php echo $key; ?>"
                                <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                    style="display: none;"
                                <?php } ?>>
                                <i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                            </a>
                            <br><a href="#" onclick="fastOrder('<?php echo $_SERVER['REQUEST_URI'];?>',event);"><?php echo lang('text_fast_order_link');?></a>
                        <?php } else { ?>
                            <p><?php echo lang('text_not_available'); ?></p>
                        <?php } ?>
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
                                            <table class="table table-condensed">
                                                <thead>
                                                <tr>
                                                    <th><?php echo lang('text_manufacturer'); ?></th>
                                                    <th><?php echo lang('text_model'); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($ap as $applicability) { ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $applicability->Brand; ?><br/>
                                                            <small><?php echo $applicability->DateMake; ?></small>
                                                        </td>
                                                        <td>
                                                            <?php echo $applicability->Model; ?><br>
                                                            <small><?php echo $applicability->Name; ?><?php echo $applicability->Fuel; ?></small>
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
                </div>
                <div class="col-md-8 col-sm-12" id="prices">
                    <?php if ($prices || $cross) { ?>
                        <h3>
                            Всего <?php echo plural_form(count($prices), [lang('text_offer_1'), lang('text_offer_2'), lang('text_offer_5')]); ?></h3>

                        <?php if ($prices) { ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-condensed table-responsive">
                                    <thead>
                                    <tr>
                                        <th><?php echo lang('text_brand'); ?>
                                            / <?php echo lang('text_sku'); ?></th>
                                        <th><?php echo lang('text_price'); ?></th>
                                        <th><?php echo lang('text_qty'); ?></th>
                                        <th><?php echo lang('text_excerpt'); ?></th>
                                        <th colspan="2"><?php echo lang('text_term'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($prices as $product) {
                                        $key = $product['product_id'] . $product['supplier_id'] . $product['term']; ?>
                                        <tr>
                                            <td class="name">
                                                <?php echo $brand . ' ' . $sku; ?></a>
                                                <br>
                                                <small><?php echo $name; ?></small>
                                            </td>
                                            <td class="price">
                                                <?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']); ?>
                                                <?php if ($product['saleprice'] > 0) { ?>
                                                    <del><?php echo format_currency($product['price']); ?></del>
                                                <?php } ?>
                                            </td>
                                            <td class="quan"><?php echo format_quantity($product['quantity']); ?></td>
                                            <td class="excerpt"><?php echo $product['excerpt']; ?></td>
                                            <td class="term">
                                                <?php echo format_term($product['term']); ?>
                                            </td>
                                            <td class="cart">
                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                                <div class="input-group">
                                                    <input type="number" name="quantity"
                                                           class="form-control" value="1">
                                                    <input type="hidden" name="product_id"
                                                           value="<?php echo $product['product_id']; ?>">
                                                    <input type="hidden" name="supplier_id"
                                                           value="<?php echo $product['supplier_id']; ?>">
                                                    <input type="hidden" name="term"
                                                           value="<?php echo $product['term']; ?>">
                                                    <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><i
                                                        class="fa fa-shopping-cart"></i></button>
                                            </span>
                                                </div>
                                                </form>
                                                <a href="/cart" class="<?php echo $key; ?>"
                                                    <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                                        style="display: none;"
                                                    <?php } ?>>
                                                    <i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                </a>

                                            </td>
                                        </tr>
                                        <?php if ($this->is_admin) { ?>
                                            <tr>
                                                <td class="name"><?php echo $this->supplier_model->suppliers[$product['supplier_id']]['name']; ?></td>
                                                <td class="price">
                                                    <?php echo $product['delivery_price']; ?>
                                                    <?php echo $this->currency_model->currencies[$product['currency_id']]['name']; ?>
                                                </td>
                                                <td class="quan"><?php echo $product['quantity']; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <?php if ($cross) { ?>
                            <h3><?php echo lang('text_product_cross'); ?></h3>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-condensed table-responsive">
                                    <thead>
                                    <tr>
                                        <th><?php echo lang('text_brand'); ?>
                                            / <?php echo lang('text_sku'); ?></th>
                                        <th><?php echo lang('text_price'); ?></th>
                                        <th><?php echo lang('text_qty'); ?></th>
                                        <th><?php echo lang('text_excerpt'); ?></th>
                                        <th colspan="2"><?php echo lang('text_term'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($cross as $product) { ?>
                                        <?php if ($product['prices']) { ?>
                                            <?php foreach ($product['prices'] as $item) { ?>
                                                <?php $key = $item['product_id'] . $item['supplier_id'] . $item['term'];
                                                if (!$product['prices']) continue; ?>
                                                <tr>
                                                    <td class="name">
                                                        <a href="/product/<?php echo $product['slug']; ?>"><?php echo $product['brand'] . ' ' . $product['sku']; ?></a>
                                                        <br>
                                                        <small><?php echo $product['name']; ?></small>
                                                        <br>
                                                        <label class="label label-warning">
                                                            <?php echo lang('text_cross_type_1'); ?>
                                                        </label>
                                                    </td>
                                                    <td class="price"><?php echo format_currency($item['saleprice'] > 0 ? $item['saleprice'] : $item['price']); ?></td>
                                                    <td class="quan"><?php echo format_quantity($item['quantity']); ?></td>
                                                    <td class="excerpt"><?php echo $item['excerpt']; ?></td>
                                                    <td class="term"><?php echo format_term($item['term']); ?>
                                                    </td>
                                                    <td class="cart">
                                                        <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                                        <div class="input-group">
                                                            <input type="number" name="quantity"
                                                                   class="form-control"
                                                                   value="1">
                                                            <input type="hidden" name="product_id"
                                                                   value="<?php echo $item['product_id']; ?>">
                                                            <input type="hidden" name="supplier_id"
                                                                   value="<?php echo $item['supplier_id']; ?>">
                                                            <input type="hidden" name="term"
                                                                   value="<?php echo $item['term']; ?>">
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
                                                        ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <?php echo lang('text_not_available'); ?>
                    <?php } ?>

                    <div class="col-md-12">
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
                    </div>
                    <div class="col-md-12">
                        <?php echo $description; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
    $('[data-fancybox^="quick-view"]').fancybox({
        animationEffect: "fade",
        animationDuration: 300,
        margin: 0,
        gutter: 0,
        touch: {
            vertical: false
        },
    });
</script>