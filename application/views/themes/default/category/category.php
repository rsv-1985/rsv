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
                <?php echo $this->load->view('form/category', '',true);?>
                <?php if($brands){?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo lang('text_filter_brand');?>
                            <?if($this->uri->segment(4)){?>
                                <small class="pull-right"><a href="/category/<?php echo $slug;?>"><?php echo lang('text_filter_reset');?></a> </small>
                            <?php } ?>
                        </div>
                        <div id="filter-brand">
                            <ul class="list-group">
                                <?php foreach ($brands as $brand){?>
                                    <li class="list-group-item"><a href="/category/<?php echo $slug;?>/brand/<?php echo str_replace('/','_',$brand['brand']);?>"><?php echo $brand['brand'];?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-8">
                <?php if($products){?>
                    <div class="row">
                        <?php foreach($products as $product){?>
                            <div class="col-md-4 col-sm-6">
                                <div class="single-shop-product">
                                    <div class="product-upper">
                                        <a href="/product/<?php echo $product['slug'];?>">
                                            <?php if($product['image']){?>
                                                <img onerror="imgError(this, 165, 165);" src="/image?img=/uploads/product/<?php echo $product['image'];?>&width=165&height=165" alt="<?php echo $product['name'];?>">
                                            <?php }elseif($product['tecdoc_info']){?>
                                                <img onerror="imgError(this, 165, 165);" src="/image?img=<?php echo $product['tecdoc_info']['article']['Image'];?>&width=165&height=165" alt="<?php echo $product['name'];?>">
                                            <?php }else{ ?>
                                                <img onerror="imgError(this, 165);" src="/image?width=165" alt="<?php echo $product['name'];?>">
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <small><?php echo $product['brand'].' '.$product['sku'];?></small>
                                    <h2><a href="/product/<?php echo $product['slug'];?>"><?php echo character_limiter($product['name'],75);?></a></h2>
                                    <div class="product-carousel-price">
                                        <ins><?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']);?></ins>
                                        <?php if($product['saleprice'] > 0){?>
                                            <del><?php echo format_currency($product['price']);?></del>
                                        <?php } ?><br />
                                        <span class="label label-success">
                                            <?php echo lang('text_term');?>: <?php echo format_term($product['term']);?>
                                            <?php echo lang('text_quantity');?>: <?php echo format_quantity($product['quantity']);?>
                                        </span>
                                    </div>

                                    <div class="product-option-shop">
                                        <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\''.md5($product['slug']).'\', event)']);?>
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
                <hr>
                <?php echo $description;?>
            </div>
        </div>
    </div>
</div>

