<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>

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
    <div class="container">
        <div class="row">
            <?php if($brands){?>
                <div class="col-md-3">
                    <h4><?php echo lang('text_select_manufacturer');?></h4>
                    <div id="popover"></div>
                    <div class="list-group">
                        <?php foreach ($brands as $brand){?>
                            <a href="/search?sku=<?php echo $brand['sku'];?>&ID_art=<?php echo $brand['ID_art'];?>&brand=<?php echo $brand['brand'];?>&search_type=<?php echo $this->input->get('search_type');?>" class="list-group-item <?php if($this->input->get('brand') == $brand['brand']){?> active<?php } ?>"><?php echo $brand['brand'];?><br>
                                <small><?php echo $brand['name'];?></small>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                 <div class="col-md-9">
            <?php }else{?>
                     <div class="col-md-12">
            <?php } ?>
                    <?php if($products || $cross || $about){?>
                        <?php if($min_price || $min_price_cross || $min_term){?>
                                <?php if($min_price){
                                    $key = $min_price['product_id'].$min_price['supplier_id'].$min_price['term'];?>
                                    <div class="col-md-4 item-min">
                                        <label>Самая низкая цена</label>
                                        <div class="thumbnail">
                                            <i onclick="tecdoc_info('<?php echo $min_price['sku'];?>', '<?php echo $min_price['brand'];?>')" class="fa fa-info-circle"></i>
                                            <a href="/product/<?php echo $min_price['slug'];?>" class="">
                                                <?php echo $min_price['brand'].' '. $min_price['sku'];?></a>
                                            <br>
                                            <small><?php echo $min_price['name'];?></small>
                                            </a>

                                            <br><small>Срок доставки:</small> <?php echo format_term($min_price['term']);?>
                                            <div class="product-inner-price">
                                                <ins><?php echo format_currency($min_price['saleprice'] > 0 ? $min_price['saleprice'] : $min_price['price']);?></ins>
                                            </div>
                                            <div class="add-cart">
                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                                <div class="input-group">
                                                    <input type="number" name="quantity" class="form-control" value="1">
                                                    <input type="hidden" name="product_id" value="<?php echo $min_price['product_id']; ?>">
                                                    <input type="hidden" name="supplier_id" value="<?php echo $min_price['supplier_id']; ?>">
                                                    <input type="hidden" name="term" value="<?php echo $min_price['term']; ?>">
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
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if($min_price_cross){
                                    $key = $min_price_cross['product_id'].$min_price_cross['supplier_id'].$min_price_cross['term'];?>
                                    <div class="col-md-4 item-min">
                                        <label>Самая низкая цена аналог</label>
                                        <div class="thumbnail">
                                            <i onclick="tecdoc_info('<?php echo $min_price_cross['sku'];?>', '<?php echo $min_price_cross['brand'];?>')" class="fa fa-info-circle"></i>
                                            <a href="/product/<?php echo $min_price_cross['slug'];?>" class="">
                                                <?php echo $min_price_cross['brand'].' '. $min_price_cross['sku'];?></a>
                                            <br>
                                            <small><?php echo $min_price_cross['name'];?></small>
                                            </a>

                                            <br><small>Срок доставки:</small> <?php echo format_term($min_price_cross['term']);?>
                                            <div class="product-inner-price">
                                                <ins><?php echo format_currency($min_price_cross['saleprice'] > 0 ? $min_price_cross['saleprice'] : $min_price_cross['price']);?></ins>
                                            </div>

                                            <div class="add-cart">
                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                                <div class="input-group">
                                                    <input type="number" name="quantity" class="form-control" value="1">
                                                    <input type="hidden" name="product_id" value="<?php echo $min_price_cross['product_id']; ?>">
                                                    <input type="hidden" name="supplier_id" value="<?php echo $min_price_cross['supplier_id']; ?>">
                                                    <input type="hidden" name="term" value="<?php echo $min_price_cross['term']; ?>">
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
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if($min_term){
                                    $key = $min_term['product_id'].$min_term['supplier_id'].$min_term['term'];?>
                                    <div class="col-md-4 item-min">
                                        <label>Наименьший срок</label>
                                        <div class="thumbnail">
                                            <i onclick="tecdoc_info('<?php echo $min_term['sku'];?>', '<?php echo $min_term['brand'];?>')" class="fa fa-info-circle"></i>
                                            <a href="/product/<?php echo $min_term['slug'];?>" class="">
                                                <?php echo $min_term['brand'].' '. $min_term['sku'];?></a>
                                            <br>
                                            <small><?php echo $min_term['name'];?></small>
                                            </a>

                                            <br><small>Срок доставки:</small> <?php echo format_term($min_term['term']);?>
                                            <div class="product-inner-price">
                                                <ins><?php echo format_currency($min_term['saleprice'] > 0 ? $min_term['saleprice'] : $min_term['price']);?></ins>
                                            </div>
                                            <div class="add-cart">
                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                                <div class="input-group">
                                                    <input type="number" name="quantity" class="form-control" value="1">
                                                    <input type="hidden" name="product_id" value="<?php echo $min_term['product_id']; ?>">
                                                    <input type="hidden" name="supplier_id" value="<?php echo $min_term['supplier_id']; ?>">
                                                    <input type="hidden" name="term" value="<?php echo $min_term['term']; ?>">
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
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <?php } ?>
                        <?php } ?>
                        <table class="table table-condensed">
                            <tbody>
                            <?php if($products){?>
                                <tr>
                                    <td colspan="7" class="heading"><?php echo lang('text_exact');?> <small>(<?php echo count($products);?>)</small></td>
                                </tr>
                                <?php foreach($products as $product){
                                    $key = $product['product_id'] . $product['supplier_id'] . $product['term'];?>
                                    <tr>
                                        <td>
                                            <i onclick="tecdoc_info('<?php echo $product['sku'];?>', '<?php echo $product['brand'];?>')" class="fa fa-info-circle"></i>
                                        </td>
                                        <td class="name">
                                            <a target="_blank" href="/product/<?php echo $product['slug'];?>">
                                                <?php echo $product['brand'].' '. $product['sku'];?></a>
                                            <br>
                                            <small><?php echo $product['name'];?></small>
                                        </td>
                                        <td class="price"><?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']);?></td>
                                        <td class="quan"><?php echo format_quantity($product['quantity']);?></td>
                                        <td class="excerpt"><?php echo $product['excerpt'];?></td>
                                        <td class="term"><i class="fa fa-road" title="<?php echo lang('text_search_term');?>"></i><?php echo format_term($product['term']);?></td>
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
                                    <?php if($this->is_admin){?>
                                        <tr>
                                            <td></td>
                                            <td class="name"><?php echo $product['sup_name'];?></td>
                                            <td class="price">
                                                <?php echo $product['delivery_price'];?>
                                                <?php echo $product['cur_name'];?>
                                            </td>
                                            <td class="quan"><?php echo $product['quantity'];?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if($cross){?>
                                <tr>
                                    <td colspan="7" class="heading"><?php echo lang('text_cross');?> <small>(<?php echo count($cross);?>)</small></td>
                                </tr>
                                <?php foreach($cross as $product){
                                    $key = $product['product_id'] . $product['supplier_id'] . $product['term'];?>
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
                                    <?php if($this->is_admin){?>
                                        <tr>
                                            <td></td>
                                            <td class="name"><?php echo $product['sup_name'];?></td>
                                            <td class="price">
                                                <?php echo $product['delivery_price'];?>
                                                <?php echo $product['cur_name'];?>
                                            </td>
                                            <td class="quan"><?php echo $product['quantity'];?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if($about){?>
                                <tr>
                                    <td colspan="7" class="heading"><?php echo lang('text_about');?> <small>(<?php echo count($about);?>)</small></td>
                                </tr>
                                <?php foreach($about as $product){
                                    $key = $product['product_id'] . $product['supplier_id'] . $product['term'];?>
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
                                    <?php if($this->is_admin){?>
                                        <tr>
                                            <td></td>
                                            <td class="name"><?php echo $product['sup_name'];?></td>
                                            <td class="price">
                                                <?php echo $product['delivery_price'];?>
                                                <?php echo $product['cur_name'];?>
                                            </td>
                                            <td class="quan"><?php echo $product['quantity'];?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php }else{?>
                        <?php if(!$brands || ($this->input->get('brand') && !$products)){?>
                            <div style="text-align: center;font-size: 24px;margin: 0 0 15px;"><?php echo lang('text_no_results');?></div>
                            <p class="alert-warning"><?php echo lang('text_no_results_description');?></p>
                            <?php echo form_open('ajax/vin', ['class' => 'vin_request', 'onsubmit' => 'send_request(event)']);?>
                            <div class="col-md-6">
                                <div class="well">

                                    <div class="alert alert-danger" role="alert" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    </div>
                                    <div class="alert alert-success" role="alert" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    </div>

                                    <div class="form-group">
                                        <label><?php echo lang('text_vin_manufacturer');?></label>
                                        <input type="text" class="form-control" name="manufacturer" required>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_vin_model');?></label>
                                        <input type="text" class="form-control" name="model" required>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_vin_engine');?></label>
                                        <input type="text" class="form-control" name="engine" required>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_vin_vin');?></label>
                                        <input type="text" class="form-control" name="vin">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_vin_parts');?></label>
                                        <textarea class="form-control" name="parts" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('text_vin_name');?></label>
                                    <input type="text" name="name" class="form-control" required/>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_vin_telephone');?></label>
                                    <input type="text" name="telephone" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group pull-right">
                                <button type="submit"><?php echo lang('button_send');?></button>
                            </div>
                            </form>
                        <?php } ?>

                    <?php } ?>
            </div>
        </div>
    </div>
</div>