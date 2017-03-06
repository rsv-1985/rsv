<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if($products){?>
    <table class="table table-condensed">
        <tbody>
        <?php if($products){?>
            <tr>
                <td colspan="7" class="heading"><?php echo lang('text_exact');?></td>
            </tr>
            <?php foreach($products as $product){?>
                <tr>
                    <td>
                       <?php echo $this->supplier_model->suppliers[$product['supplier_id']]['name'];?>
                    </td>
                    <td class="name">
                        <a target="_blank" href="/product/<?php echo $product['slug'];?>"><?php echo $product['brand'].' '. $product['sku'];?></a>
                        <br>
                        <small><?php echo $product['name'];?></small><br>
                    </td>
                    <td class="price">
                        <?php echo format_currency($product['price']);?><br>
                        <small><?php echo $product['delivery_price'].$this->currency_model->currencies[$product['currency_id']]['name'];?></small>
                    </td>
                    <td class="quan"><?php echo format_quantity($product['quantity']);?></td>
                    <td class="excerpt"><?php echo $product['excerpt'];?></td>
                    <td class="term"><?php echo format_term($product['term']);?></td>
                    <td class="cart">
                        <button onclick="add_product('<?php echo $product['product_id'];?>','<?php echo $product['supplier_id'];?>','<?php echo $product['term'];?>');" class="btn btn-default" type="submit"><i class="fa fa-shopping-cart"></i></button>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
<?php }else{?>
    Нет товаров
<?php } ?>

