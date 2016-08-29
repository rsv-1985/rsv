<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<style>
    .add-cart{
        background: #e5e5e5;
        padding: 2%;
        margin: -4px;
    }
    .item-min{
        text-align: center;
    }
    .min{
        color: green;
        font-weight: bold;
    }
</style>
<?php if($products || $cross || $about){?>
    <?php if($min_price || $min_price_cross || $min_term){?>
        <hr>
        <div class="row-fluid">
            <?php if($min_price){?>
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
                            <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\''.md5($min_price['slug']).'\', event)']);?>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" value="1">
                                <input type="hidden" name="slug" value="<?php echo $min_price['slug'];?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="fa fa-shopping-cart"></i></button>
                            </span>
                            </div>
                            </form>
                            <a href="/cart" class="<?php echo md5($min_price['slug']);?>"
                                <?php if(!key_exists(md5($min_price['slug']),$this->cart->contents())){?>
                                    style="display: none;"
                                <?php } ?>
                            ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart');?></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php } ?>
            <?php if($min_price_cross){?>
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
                            <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\''.md5($min_price_cross['slug']).'\', event)']);?>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" value="1">
                                <input type="hidden" name="slug" value="<?php echo $min_price_cross['slug'];?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="fa fa-shopping-cart"></i></button>
                            </span>
                            </div>
                            </form>
                            <a href="/cart" class="<?php echo md5($min_price_cross['slug']);?>"
                                <?php if(!key_exists(md5($min_price_cross['slug']),$this->cart->contents())){?>
                                    style="display: none;"
                                <?php } ?>
                            ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart');?></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php } ?>
            <?php if($min_term){?>
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
                            <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\''.md5($min_term['slug']).'\', event)']);?>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" value="1">
                                <input type="hidden" name="slug" value="<?php echo $min_term['slug'];?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="fa fa-shopping-cart"></i></button>
                            </span>
                            </div>
                            </form>
                            <a href="/cart" class="<?php echo md5($min_term['slug']);?>"
                                <?php if(!key_exists(md5($min_term['slug']),$this->cart->contents())){?>
                                    style="display: none;"
                                <?php } ?>
                            ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart');?></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php } ?>

        </div>
    <?php } ?>
    <table class="table table-condensed">
        <tbody>
        <?php if($products){?>
            <tr>
                <td colspan="7" class="heading"><?php echo lang('text_exact');?> <small>(<?php echo count($products);?>)</small></td>
            </tr>
            <?php foreach($products as $product){?>
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
            <?php foreach($about as $product){?>
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
<h3 style="text-align: center"><?php echo lang('text_no_results');?></h3>
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
<?php $this->output->enable_profiler(FALSE);?>