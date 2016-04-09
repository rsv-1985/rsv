<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if($products || $cross || $about){?>
    <table class="table table-condensed">
        <tbody>
        <?php if($products){?>
            <tr>
                <td colspan="7" class="heading"><?php echo lang('text_exact');?></td>
            </tr>
            <?php foreach($products as $product){?>
                <tr>
                    <td>
                        <i onmouseover="tecdoc_info('<?php echo $product['sku'];?>', '<?php echo $product['brand'];?>')" class="fa fa-info-circle"></i>
                    </td>
                    <td class="name">
                        <a target="_blank" href="/product/<?php echo $product['slug'];?>"><?php echo $product['brand'].' '. $product['sku'];?></a>
                        <br>
                        <small><?php echo $product['name'];?></small>
                    </td>
                    <td class="price"><?php echo format_currency($product['price']);?></td>
                    <td class="quan"><?php echo format_quantity($product['quantity']);?></td>
                    <td class="excerpt"><?php echo $product['excerpt'];?></td>
                    <td class="term"><?php echo format_term($product['term']);?></td>
                    <td class="cart">
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

                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        <?php if($cross){?>
            <tr>
                <td colspan="7" class="heading"><?php echo lang('text_cross');?></td>
            </tr>
            <?php foreach($cross as $product){?>
                <tr>
                    <td>
                        <i onmouseover="tecdoc_info('<?php echo $product['sku'];?>', '<?php echo $product['brand'];?>')" class="fa fa-info-circle"></i>
                    </td>
                    <td class="name">
                        <a target="_blank" href="/product/<?php echo $product['slug'];?>"><?php echo $product['brand'].' '. $product['sku'];?></a>
                        <br>
                        <small><?php echo $product['name'];?></small><br>
                    </td>
                    <td class="price"><?php echo format_currency($product['price']);?></td>
                    <td class="quan"><?php echo format_quantity($product['quantity']);?></td>
                    <td class="excerpt"><?php echo $product['excerpt'];?></td>
                    <td class="term"><?php echo format_term($product['term']);?></td>
                    <td class="cart">
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
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        <?php if($about){?>
            <tr>
                <td colspan="7" class="heading"><?php echo lang('text_about');?></td>
            </tr>
            <?php foreach($about as $product){?>
                <tr>
                    <td>
                        <i onmouseover="tecdoc_info('<?php echo $product['sku'];?>', '<?php echo $product['brand'];?>')" class="fa fa-info-circle"></i>
                    </td>
                    <td class="name">
                        <a target="_blank" href="/product/<?php echo $product['slug'];?>"><?php echo $product['brand'].' '. $product['sku'];?></a>
                        <br>
                        <small><?php echo $product['name'];?></small><br>
                    </td>
                    <td class="price"><?php echo format_currency($product['price']);?></td>
                    <td class="quan"><?php echo format_quantity($product['quantity']);?></td>
                    <td class="excerpt"><?php echo $product['excerpt'];?></td>
                    <td class="term"><?php echo format_term($product['term']);?></td>
                    <td class="cart">
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

                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
<?php }else{?>
<h3><?php echo lang('text_no_results');?></h3>
    <?php echo form_open('ajax/vin', ['class' => 'vin_request']);?>
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