<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php echo sprintf(lang('text_email_subject'), $order_id);?><br>
<?php echo sprintf(lang('text_email_delivery_method'), $delivery);?><br>
<?php echo sprintf(lang('text_email_payment_method'), $payment);?><br>
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
                <a href="/product/<?php echo $item['slug'];?>"><?php echo $item['sku'].' '.$item['brand'].' '.$item['name'];?></a>
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
<?php echo lang('text_cart_total');?>:<?php echo format_currency($total);?>