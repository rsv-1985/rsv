<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');


?>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
<div class="row">
    <div class="col-md-12">
        <?php if($categories){?>
            <div class="row">
                <div class="col-md-12">
                    <b><?php echo lang('text_subcategory');?></b>
                    <ul>
                        <?php foreach ($categories as $category){?>
                            <li><a href="/category/<?php echo $category['slug'];?>"><?php echo $category['name'];?></a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
    </div>
</div>

