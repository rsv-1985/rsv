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
                    <h1><?php echo $h1;?></h1>
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
                <?php if($this->category){?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo lang('text_category');?></div>
                        <div class="panel-body">
                            <ul>
                                <?php foreach($this->category as $category){?>
                                    <li><a href="/category/<?php echo $category['slug'];?>"><?php echo $category['name'];?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-8">
                <?php echo $description;?>
                <hr>
                <?php if($products){?>
                    <div class="row">
                        <?php foreach($products as $product){?>
                            <?php //print_r($product);?>
                            <div class="col-md-3 col-sm-6">
                                <div class="single-shop-product">
                                    <div class="product-upper">
                                        <?php if($product['tecdoc_info']){?>
                                            <img src="/image?img=<?php echo $product['tecdoc_info']['article']['Image'];?>&width=165" alt="">
                                        <?php }else{ ?>
                                            <img src="/image?width=165" alt="">
                                        <?php } ?>
                                    </div>
                                    <small><?php echo $product['brand'].' '.$product['sku'];?></small>
                                    <h2><a href="/product/<?php echo $product['slug'];?>"><?php echo character_limiter($product['name'],25);?></a></h2>
                                    <div class="product-carousel-price">
                                        <ins><?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']);?></ins>
                                        <?php if($product['saleprice'] > 0){?>
                                            <del><?php echo format_currency($product['price']);?></del>
                                        <?php } ?>
                                    </div>

                                    <div class="product-option-shop">
                                        <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\''.md5($product['slug']).'\')']);?>
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control" value="1">
                                            <input type="hidden" name="slug" value="<?php echo $product['slug'];?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit"><i class="fa fa-shopping-cart"></i></button>
                                            </span>
                                        </div>
                                        </form>
                                        <a href="/cart" id="<?php echo md5($product['slug']);?>"
                                            <?php if(!key_exists(md5($product['slug']),$this->cart->contents())){?>
                                                style="display: none;"
                                            <?php } ?>
                                        ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart');?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <div class="row">
                    <div class="pull-right">
                        <?php echo $this->pagination->create_links();?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

