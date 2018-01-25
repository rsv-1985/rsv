<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="search-product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="search-product-bit-title text-center">
                    <h1><?php echo $this->h1; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 brands">
            <ul class="list-group">
                <?php foreach ($brands as $brand){?>
                    <li class="list-group-item" onclick="location.href='/search?search=<?php echo $brand['sku'];?>&ID_art=<?php echo $brand['ID_art'];?>&brand=<?php echo $brand['brand'];?>'" style="cursor: pointer">
                        <a href="/search?search=<?php echo $brand['sku'];?>&ID_art=<?php echo $brand['ID_art'];?>&brand=<?php echo $brand['brand'];?>">
                            <img src="<?php echo $brand['image'];?>&width=50" style="width:50px">
                            <b><?php echo $brand['brand'];?></b> <?php echo $brand['name'];?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
