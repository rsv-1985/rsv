<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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

    <div class="single-product-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                        <?php foreach ($breadcrumbs as $index => $breadcrumb) { ?>
                            <?php if ($breadcrumb['href']) {?>
                                <li itemprop="itemListElement" itemscope
                                    itemtype="http://schema.org/ListItem" class="breadcrumb-item">
                                    <a itemprop="item" href="<?php echo $breadcrumb['href']; ?>">
                                        <span itemprop="name"><?php echo $breadcrumb['text']; ?></span>
                                    </a>
                                    <meta itemprop="position" content="<?php echo $index; ?>"/>
                                </li>
                            <?php } else {?>
                                <li><?php echo $breadcrumb['text']; ?></li>
                            <?php } ?>
                        <?php } ?>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <img itemprop="image" onerror="imgError(this, 300);" src="/image?img=<?php echo $image; ?>"
                         alt="<?php echo $h1; ?>" style="max-height: 340px;">
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
                <div class="col-md-8 col-sm-12">
                    <?php if ($prices) { ?>
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th><?php echo lang('text_brand'); ?> / <?php echo lang('text_sku'); ?></th>
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
                                                <input type="number" name="quantity" class="form-control" value="1">
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
                        <div role="tabpanel">
                            <ul class="product-tab" role="tablist">
                                <?php if ($description) { ?>
                                    <li role="presentation" class="active"><a href="#description"
                                                                              aria-controls="home" role="tab"
                                                                              data-toggle="tab"><?php echo lang('text_description'); ?></a>
                                    </li>
                                <?php } ?>
                                <?php if ($attributes) { ?>
                                    <li role="presentation"><a href="#attributes" aria-controls="profile"
                                                               role="tab"
                                                               data-toggle="tab"><?php echo lang('text_attributes'); ?></a>
                                    </li>
                                <?php } ?>
                                <?php if ($applicability) { ?>
                                    <li role="presentation"><a href="#applicability" aria-controls="profile"
                                                               role="tab"
                                                               data-toggle="tab"><?php echo lang('text_applicability'); ?></a>
                                    </li>
                                <?php } ?>
                                <?php if ($components) { ?>
                                    <li role="presentation"><a href="#components" aria-controls="profile"
                                                               role="tab"
                                                               data-toggle="tab"><?php echo lang('text_components'); ?></a>
                                    </li>
                                <?php } ?>
                                <?php if ($cross) { ?>
                                    <li role="presentation"><a href="#cross" aria-controls="profile"
                                                               role="tab"
                                                               data-toggle="tab"><?php echo lang('text_cross'); ?></a>
                                    </li>
                                <?php } ?>

                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="description"
                                     itemprop="description">
                                    <?php echo $description; ?>
                                </div>
                                <?php if ($attributes) { ?>
                                    <div role="tabpanel" class="tab-pane fade fade" id="attributes">
                                        <table class="table table-striped">
                                            <?php foreach ($attributes as $attribute) {
                                                ; ?>
                                                <tr>
                                                    <td><?php echo $attribute['attribute_name']; ?></td>
                                                    <td><?php echo $attribute['attribute_value']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                <?php } ?>
                                <?php if ($applicability) { ?>
                                    <div role="tabpanel" class="tab-pane fade" id="applicability">

                                        <div class="panel-group" id="accordion" role="tablist"
                                             aria-multiselectable="true">
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
                                                            <table class="table table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th><?php echo lang('text_manufacturer');?></th>
                                                                    <th><?php echo lang('text_model');?></th>
                                                                    <th><?php echo lang('text_description');?></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php foreach ($ap as $applicability) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo $applicability->Brand; ?><br/>
                                                                            <?php echo $applicability->DateMake; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $applicability->Model; ?><br>
                                                                            <?php echo $applicability->Name; ?> <?php echo $applicability->Fuel; ?>
                                                                            <br>
                                                                        </td>
                                                                        <td><?php echo str_replace(';', '<br>', $applicability->Description); ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $q++;
                                            } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($components) { ?>
                                    <div role="tabpanel" class="tab-pane fade" id="components">
                                        <table class="table table-condensed">
                                            <thead>
                                            <tr>
                                                <th><?php echo lang('text_brand');?></th>
                                                <th><?php echo lang('text_sku');?></th>
                                                <th><?php echo lang('text_name');?></th>
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
                                                        <a href="#"
                                                           onclick="catalog_search('<?php echo $components->ID_art; ?>', '<?php echo $components->Display; ?>','<?php echo $components->Brand; ?>', event)"><?php echo lang('button_search'); ?></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                                <?php if ($cross) { ?>
                                    <div role="tabpanel" class="tab-pane fade" id="cross">
                                        <table class="table">
                                            <?php foreach ($cross as $product) { ?>
                                                <?php if($product['prices']){?>
                                                    <?php foreach ($product['prices'] as $item){?>
                                                        <?php $key = $item['product_id'] . $item['supplier_id'] . $item['term'];
                                                        if(!$product['prices']) continue;?>
                                                        <tr>
                                                            <td class="name">
                                                                <a href="/product/<?php echo $product['slug']; ?>"><?php echo $product['brand'] . ' ' . $product['sku']; ?></a>
                                                                <br>
                                                                <small><?php echo $product['name']; ?></small>
                                                                <br>
                                                            </td>
                                                            <td class="price"><?php echo format_currency($item['saleprice'] > 0 ? $item['saleprice'] : $item['price']); ?></td>
                                                            <td class="quan"><?php echo format_quantity($item['quantity']); ?></td>
                                                            <td class="excerpt"><?php echo $item['excerpt']; ?></td>
                                                            <td class="term"><?php echo format_term($item['term']); ?>
                                                            </td>
                                                            <td class="cart">
                                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                                                <div class="input-group">
                                                                    <input type="number" name="quantity" class="form-control"
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
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <?php echo lang('text_not_available'); ?>
                    <?php } ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
        </div>
    </div>
</div>
