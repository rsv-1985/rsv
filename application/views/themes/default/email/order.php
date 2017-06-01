<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

    <?php $q = 1; foreach($products as $key => $item){?>
       <?php echo lang('text_product').$q;?>:<?php echo $item['sku'];?>===<?php echo $item['brand'];?>===<?php echo $item['quantity'].lang('text_st');?>===<?php echo format_currency($item['price']);?>===<?php echo format_currency($item['price']*$item['quantity']);?><?php if(isset($suppliers)){?>===<?php echo @$suppliers[$item['supplier_id']]['name'];?><?php } ?><br/>
    <?php $q++;} ?>
