<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php echo $this->load->view('form/category', null, true);?>
                <?php if ($banner) { ?>
                    <div class="single-sidebar">
                        <?php foreach ($banner as $banner) { ?>
                            <div class="thubmnail-recent">
                                <a href="<?php echo $banner['link']; ?>" <?php if ($banner['new_window']){ ?>target="_blank" <?php } ?>>
                                    <img onerror="imgError(this);" src="/uploads/banner/<?php echo $banner['image']; ?>">
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <div class="col-md-9">
                <div class="product-content-right">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="product-images">
                                <div class="product-main-img">
                                    <img onerror="imgError(this, 300);" src="/image?img=<?php echo $image; ?>&width=300" alt="<?php echo $h1; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="product-inner">
                                <h1 class="product-name"><?php echo $h1; ?></h1>
                                <?php if($this->is_admin){?>
                                    <div class="well well-sm">
                                        <div class="col-md-6">
                                            <small><?php echo lang('text_supplier'); ?>:</small> <?php echo $supplier; ?><br/>
                                            <small><?php echo lang('text_supplier_description'); ?>:</small> <?php echo $supplier_description; ?><br/>
                                        </div>
                                        <div class="col-md-6">
                                            <small><?php echo lang('text_qty'); ?>:</small> <?php echo $quantity; ?><br/>
                                            <small><?php echo lang('text_delivery_price'); ?>:</small> <?php echo $delivery_price; ?><br/>
                                            <small><?php echo lang('text_updated_at');?></small> <?php echo $updated_at;?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php } ?>
                                <?php if($status){?>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <span class="badge"><?php echo $brand; ?></span>
                                        <?php echo lang('text_brand'); ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge"><?php echo $sku; ?></span>
                                        <?php echo lang('text_sku'); ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge"><?php echo format_quantity($quantity); ?></span>
                                        <?php echo lang('text_qty'); ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge"><?php echo format_term($term); ?></span>
                                        <?php echo lang('text_term'); ?>
                                    </li>
                                    <?php if($excerpt){?>
                                        <li class="list-group-item">
                                            <span class="badge"><?php echo $excerpt; ?></span>
                                            <?php echo lang('text_excerpt'); ?>
                                        </li>
                                    <?php } ?>
                                </ul>

                                    <div class="product-inner-price">
                                        <ins><?php echo $saleprice > 0 ? $saleprice : $price; ?></ins>
                                        <?php if ($saleprice > 0) { ?>
                                            <del><?php echo $price; ?></del>
                                        <?php } ?>
                                    </div>

                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\'' . md5($slug) . '\', event)']); ?>
                                <div class="quantity">
                                    <input type="number" size="4" class="input-text qty text" value="1" name="quantity"
                                           min="1" step="1">
                                </div>
                                <input type="hidden" name="slug" value="<?php echo $slug; ?>">
                                <button class="add_to_cart_button"
                                        type="submit"><?php echo lang('button_add_cart'); ?></button>
                                <a href="/cart" id="<?php echo md5($slug); ?>"
                                    <?php if (!key_exists(md5($slug), $this->cart->contents())) { ?>
                                        style="display: none; margin-left: 2%;"
                                    <?php } ?>
                                ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?></a>
                                </form>
                                    <?php if($products){?>
                                        <br/>
                                        <a href="#" rel="nofollow" onclick="$('.this-products').toggle();return false;"><?php echo sprintf(lang('text_this_products'), count($products));?></a>
                                        <div class="this-products" style="display: none">
                                            <?php foreach ($products as $product){?>
                                            <h3 class="center"><?php echo $product['name'];?></h3>
                                            <div class="well">
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <span class="badge"><?php echo $product['brand']; ?></span>
                                                        <?php echo lang('text_brand'); ?>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge"><?php echo $product['sku']; ?></span>
                                                        <?php echo lang('text_sku'); ?>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge"><?php echo format_quantity($product['quantity']); ?></span>
                                                        <?php echo lang('text_qty'); ?>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge"><?php echo format_term($product['term']); ?></span>
                                                        <?php echo lang('text_term'); ?>
                                                    </li>
                                                    <?php if($product['excerpt']){?>
                                                        <li class="list-group-item">
                                                            <span class="badge"><?php echo $product['excerpt']; ?></span>
                                                            <?php echo lang('text_excerpt'); ?>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                                <div class="product-inner-price">
                                                    <ins><?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']); ?></ins>
                                                    <?php if ($product['saleprice'] > 0) { ?>
                                                        <del><?php echo format_currency($product['price']); ?></del>
                                                    <?php } ?>
                                                </div>
                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\'' . md5($product['slug']) . '\', event)']); ?>
                                                <div class="quantity">
                                                    <input type="number" size="4" class="input-text qty text" value="1" name="quantity"
                                                           min="1" step="1">
                                                </div>
                                                <input type="hidden" name="slug" value="<?php echo $product['slug']; ?>">
                                                <button class="add_to_cart_button"
                                                        type="submit"><?php echo lang('button_add_cart'); ?></button>
                                                <a href="/cart" id="<?php echo md5($product['slug']); ?>"
                                                    <?php if (!key_exists(md5($product['slug']), $this->cart->contents())) { ?>
                                                        style="display: none; margin-left: 2%;"
                                                    <?php } ?>
                                                ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?></a>
                                                </form>
                                            </div>

                                        <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php }else{?>
                                    <?php echo lang('text_not_available');?>
                                <?php } ?>
                                <div role="tabpanel">
                                    <ul class="product-tab" role="tablist">
                                        <li role="presentation" class="active"><a href="#description"
                                                                                  aria-controls="home" role="tab"
                                                                                  data-toggle="tab"><?php echo lang('text_description'); ?></a>
                                        </li>
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
                                        <?php if($cross){?>
                                            <li role="presentation"><a href="#cross" aria-controls="profile"
                                                                       role="tab"
                                                                       data-toggle="tab"><?php echo lang('text_cross'); ?></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active" id="description">
                                            <p><?php echo $description; ?></p>
                                        </div>
                                        <?php if ($applicability) { ?>
                                            <div role="tabpanel" class="tab-pane fade" id="applicability">

                                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                                    <?php $q = 0; foreach ($applicability as $brand_name => $ap){?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="heading<?php echo $q;?>">
                                                                <h4 class="panel-title">
                                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $q;?>" aria-expanded="false" aria-controls="collapse<?php echo $q;?>">
                                                                        <?php echo $brand_name;?>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse<?php echo $q;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $q;?>">
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
                                                                                <td><?php echo $applicability->Brand; ?></td>
                                                                                <td>
                                                                                    <?php echo $applicability->Model; ?><br>
                                                                                    <?php echo $applicability->Name; ?> <?php echo $applicability->Fuel; ?><br>
                                                                                </td>
                                                                                <td><?php echo str_replace(';', '<br>', $applicability->Description); ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php $q++;} ?>
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
                                        <?php if($cross){?>
                                            <div role="tabpanel" class="tab-pane fade" id="cross">
                                                <table class="table">
                                                    <?php foreach($cross as $product){?>
                                                    <tr>
                                                        <td>
                                                            <i onclick="tecdoc_info('<?php echo $product['sku'];?>', '<?php echo $product['brand'];?>')" class="fa fa-info-circle"></i>
                                                        </td>
                                                        <td class="name">
                                                            <a target="_blank" href="/product/<?php echo $product['slug'];?>"><?php echo $product['brand'].' '. $product['sku'];?></a>
                                                            <br>
                                                            <small><?php echo $product['name'];?></small><br>
                                                        </td>
                                                        <td class="price"><?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']);?></td>
                                                        <td class="quan"><?php echo format_quantity($product['quantity']);?></td>
                                                        <td class="excerpt"><?php echo $product['excerpt'];?></td>
                                                        <td class="term"><i class="fa fa-road" title="<?php echo lang('text_search_term');?>"></i><?php echo format_term($product['term']);?></td>
                                                        <td class="cart">
                                                            <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\''.md5($product['slug']).'\', event)']);?>
                                                            <div class="input-group">
                                                                <input type="number" name="quantity" class="form-control" value="1">
                                                                <input type="hidden" name="slug" value="<?php echo $product['slug'];?>">
                                                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i class="fa fa-shopping-cart"></i></button>
                                </span>
                                                            </div>
                                                            </form>
                                                            <a href="/cart" class="<?php echo md5($product['slug']);?>"
                                                                <?php if(!key_exists(md5($product['slug']),$this->cart->contents())){?>
                                                                    style="display: none;"
                                                                <?php } ?>
                                                            ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart');?></a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>