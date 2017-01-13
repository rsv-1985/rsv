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

					<?  if($index == end($breadcrumb)) {
        ?>
   	  <li itemprop="itemListElement" itemscope
                            itemtype="http://schema.org/ListItem" class="breadcrumb-item">
                            <a itemprop="item" href="<?php echo $breadcrumb['href'];?>">
                                <span itemprop="name"><?php echo $breadcrumb['text'];?></span>
                            </a>
                            <meta itemprop="position" content="<?php echo $index;?>" />
                        </li>
   <?
  }
  else {
	  ?>
	   <li><?php echo $breadcrumb['text']; ?></li>


	   <?

  }
	?>
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
                                    <img  onerror="imgError(this);"
                                         src="/uploads/banner/<?php echo $banner['image']; ?>">
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-8 col-sm-12">
                <?php if ($prices) { ?>
                    <div class="list-group">
                        <?php $q = false; foreach ($prices as $product) {
                            $key = $product['product_id'] . $product['supplier_id'] . $product['term']; ?>
                            <div class="product-inner <?php if($q){?>well other<?php } ?>">
                                <?php if ($this->is_admin) { ?>
                                    <div class="well well-sm">
                                        <div class="col-md-6">
                                            <small><?php echo lang('text_supplier'); ?>:</small> <?php echo $product['sup_name']; ?><br/>
                                            <small><?php echo lang('text_supplier_description'); ?>:</small> <?php echo $product['sup_description']; ?><br/>
                                        </div>
                                        <div class="col-md-6">
                                            <small><?php echo lang('text_qty'); ?>:</small> <?php echo $product['quantity']; ?><br/>
                                            <small><?php echo lang('text_delivery_price'); ?>:</small> <?php echo $product['delivery_price'].' '.$product['cur_name']; ?><br/>
                                            <small><?php echo lang('text_updated_at');?></small> <?php echo $product['sup_updated_at'];?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php } ?>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <span class="badge" itemprop="brand"><?php echo $brand; ?></span>
                                        <?php echo lang('text_brand');?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge" itemprop="sku"><?php echo $sku; ?></span>
                                        <?php echo lang('text_sku');?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge"><?php echo format_quantity($product['quantity']); ?></span>
                                        <?php echo lang('text_qty');?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge"><?php echo format_term($product['term']); ?></span>
                                        <?php echo lang('text_term');?>
                                    </li>
                                    <?php if($product['excerpt']){?>
                                        <li class="list-group-item">
                                            <small><?php echo $product['excerpt'];?></small>
                                        </li>
                                    <?php } ?>
                                </ul>

                                <div class="product-inner-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <meta itemprop="priceCurrency" content="<?php echo $this->default_currency['code'];?>" />
                                    <ins itemprop="price" content="<?php echo $product['saleprice'] > 0 ? $product['saleprice'] : $product['price']; ?>">
                                        <?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']); ?>
                                    </ins>
                                    <?php if ($product['saleprice'] > 0) { ?>
                                        <del><?php echo format_currency($product['price']); ?></del>
                                    <?php } ?>
                                </div>

                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                <div class="quantity">
                                    <input type="number" size="4" class="input-text qty text" value="1" name="quantity"
                                           min="1" step="1">
                                </div>
                                <input type="hidden" name="product_id"
                                       value="<?php echo $product['product_id']; ?>">
                                <input type="hidden" name="supplier_id"
                                       value="<?php echo $product['supplier_id']; ?>">
                                <input type="hidden" name="term" value="<?php echo $product['term']; ?>">

                                <button class="add_to_cart_button" type="submit">В корзину</button>
                                <a rel="nofollow" href="/cart" class="<?php echo $key; ?>"
                                    <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                        style="display: none;"
                                    <?php } ?>
                                ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?></a>
                                </form>

                            </div>
                            <br />
                            <?php if(count($prices) > 1 && !$q){?>
                                <a href="#" rel="nofollow" onclick="$('.other').toggle(); return false;"><?php echo sprintf(lang('text_this_products'),plural_form(count($prices) - 1,['предложение', 'предложения', 'предложений']));?></a>
                            <?php } ?>
                        <?php $q = true;} ?>
                    </div>
                    <div role="tabpanel">
                        <ul class="product-tab" role="tablist">
                            <?php if($description){?>
                                <li role="presentation" class="active"><a href="#description"
                                                                          aria-controls="home" role="tab"
                                                                          data-toggle="tab"><?php echo lang('text_description'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if($attributes){?>
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
                            <div role="tabpanel" class="tab-pane fade in active" id="description" itemprop="description">
                                <?php echo $description; ?>
                            </div>
                            <?php if($attributes){?>
                                <div role="tabpanel" class="tab-pane fade fade" id="attributes">
                                    <table class="table table-striped">
                                        <?php foreach ($attributes as $attribute){;?>
                                            <tr>
                                                <td><?php echo $attribute['attribute_name'];?></td>
                                                <td><?php echo $attribute['attribute_value'];?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            <?php } ?>
                            <?php if ($applicability) {?>
                                <div role="tabpanel" class="tab-pane fade" id="applicability">

                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <?php $q = 0;
                                        foreach ($applicability as $brand_name => $ap) { ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="heading<?php echo $q; ?>">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
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
                                                                <th>Производитель</th>
                                                                <th>Моель</th>
                                                                <th>Описание</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach ($ap as $applicability) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $applicability->Brand; ?><br/>
                                                                        <?php echo $applicability->DateMake;?>
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
                                            <th>Brand</th>
                                            <th>Article</th>
                                            <th>Name</th>
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
                                        <?php foreach ($cross as $product) {
                                            $key = $product['product_id'] . $product['supplier_id'] . $product['term'];?>
                                            <tr>
                                                <td class="name">
                                                    <a href="/product/<?php echo $product['slug']; ?>"><?php echo $product['brand'] . ' ' . $product['sku']; ?></a>
                                                    <br>
                                                    <small><?php echo $product['name']; ?></small>
                                                    <br>
                                                </td>
                                                <td class="price"><?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']); ?></td>
                                                <td class="quan"><?php echo format_quantity($product['quantity']); ?></td>
                                                <td class="excerpt"><?php echo $product['excerpt']; ?></td>
                                                <td class="term"><i class="fa fa-road"
                                                                    title="<?php echo lang('text_search_term'); ?>"></i><?php echo format_term($product['term']); ?>
                                                </td>
                                                <td class="cart">
                                                    <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                                    <div class="input-group">
                                                        <input type="number" name="quantity" class="form-control" value="1">
                                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                        <input type="hidden" name="supplier_id" value="<?php echo $product['supplier_id']; ?>">
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
                                                </td>
                                            </tr>
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
