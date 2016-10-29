<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

<table cellspacing="0" class="shop_table cart">
    <thead>
    <tr>
        <th class="product-name"><?php echo lang('text_product');?></th>
        <th class="product-price"><?php echo lang('text_price');?></th>
        <th class="product-quantity"><?php echo lang('text_quantity');?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($products as $key => $item){?>
        <tr class="cart_item" id="<?php echo $key;?>">
            <td>
                <a href="<?php echo base_url();?>/product/<?php echo $item['slug'];?>"><?php echo $item['sku'].' '.$item['brand'].' '.$item['name'];?></a>
            </td>
            <td>
                <span class="amount"><?php echo format_currency($item['price']);?></span>
            </td>
            <td>
              <?php echo $item['quantity'];?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>