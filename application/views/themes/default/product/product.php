<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>



<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="single-sidebar">
                    <div class="single-sidebar">
                        <?php echo form_open('ajax/pre_search', ['method' => 'get', 'class' => 'search_form']);?>
                        <input type="text" name="search" placeholder="OC90" required>
                        <input type="submit" value="<?php echo lang('button_search');?>">
                        </form>
                    </div>
                </div>
                <?php if($banner){?>
                <div class="single-sidebar">
                    <?php foreach($banner as $banner){?>
                        <div class="thubmnail-recent">
                            <img src="/uploads/banner/<?php echo $banner['image'];?>">
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
                                    <img src="/image?img=<?php echo $image;?>&width=165" alt="<?php echo $h1;?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="product-inner">
                                <h1 class="product-name"><?php echo $h1;?></h1>
                                <div class="well well-sm">
                                    <small><?php echo lang('text_brand');?>:</small> <?php echo $brand;?><br />
                                    <small><?php echo lang('text_sku');?>:</small> <?php echo $sku;?><br />
                                    <small><?php echo lang('text_qty');?>:</small> <?php echo format_quantity($quantity);?><br />  
                                </div>
                                <div class="product-inner-price">
                                    <ins><?php echo $saleprice > 0 ? $slaeprice : $price;?></ins>
                                    <?php if($saleprice > 0){?>
                                        <del><?php echo $price;?></del>
                                    <?php } ?>
                                </div>

                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\''.md5($slug).'\')']);?>
                                    <div class="quantity">
                                        <input type="number" size="4" class="input-text qty text"  value="1" name="quantity" min="1" step="1">
                                    </div>
                                    <input type="hidden" name="slug" value="<?php echo $slug;?>">
                                    <button class="add_to_cart_button" type="submit"><?php echo lang('button_add_cart');?></button>
                                <a href="/cart" id="<?php echo md5($slug);?>"
                                    <?php if(!key_exists(md5($slug),$this->cart->contents())){?>
                                        style="display: none; margin-left: 2%;"
                                    <?php } ?>
                                ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart');?></a>
                                </form>

                                <div class="product-inner-category">
                                    <p><?php echo $excerpt;?></p>
                                </div>

                                <div role="tabpanel">
                                    <ul class="product-tab" role="tablist">
                                        <li role="presentation" class="active"><a href="#description" aria-controls="home" role="tab" data-toggle="tab"><?php echo lang('text_description');?></a></li>
                                        <?php if($applicability){?>
                                            <li role="presentation"><a href="#applicability" aria-controls="profile" role="tab" data-toggle="tab"><?php echo lang('text_applicability');?></a></li>
                                        <?php } ?>
                                        <?php if($components){?>
                                            <li role="presentation"><a href="#components" aria-controls="profile" role="tab" data-toggle="tab"><?php echo lang('text_components');?></a></li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active" id="description">
                                            <p><?php echo $description;?></p>
                                        </div>
                                        <?php if($applicability){?>
                                        <div role="tabpanel" class="tab-pane fade" id="applicability">
                                            <table class="table table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th>Brand</th>
                                                        <th>Model</th>
                                                        <th>Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($applicability as $applicability){?>
                                                        <tr>
                                                            <td><?php echo $applicability->Brand;?></td>
                                                            <td><?php echo $applicability->Model;?></td>
                                                            <td><?php echo str_replace(';','<br>',$applicability->Description);?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } ?>
                                        <?php if($components){?>
                                        <div role="tabpanel" class="tab-pane fade" id="components">
                                            <?php print_r($components);?>
                                            <?php foreach($components as $components){?>
                                                <tr>
                                                    <td><?php echo $components->Brand;?></td>
                                                    <td><?php echo $components->Display;?></td>
                                                    <td><?php echo $components->Name;?></td>
                                                    <td>
                                                        <a href="#" onclick="catalog_search('<?php echo $components->ID_art;?>', '<?php echo $components->Display;?>','<?php echo $components->Brand;?>')"><?php echo lang('text_cross'); ?></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
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