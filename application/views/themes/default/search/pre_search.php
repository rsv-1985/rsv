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
        <div class="col-md-12 brands" style="min-height: 500px">
            <ul class="list-group">
                <?php if($group_brands){?>
                    <?php foreach ($group_brands as $brand){?>
                        <li class="list-group-item" onclick="location.href='/search?search=<?php echo $brand['sku'];?>&id_group=<?php echo $brand['id_group'];?>'" style="cursor: pointer">
                            <a href="/search?search=<?php echo $brand['sku'];?>&id_group=<?php echo $brand['id_group'];?>">
                                <img src="<?php echo $brand['image'];?>">
                                <b><?php echo $brand['brand'];?></b> <?php echo $brand['name'];?>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php foreach ($brands as $brand){?>
                    <li class="list-group-item" onclick="location.href='/search?search=<?php echo $brand['sku'];?>&brand=<?php echo $brand['brand'];?>'" style="cursor: pointer">
                        <a href="/search?search=<?php echo $brand['sku'];?>&brand=<?php echo urlencode($brand['brand']);?>">
                            <img src="<?php echo $brand['image'];?>">
                            <b><?php echo $brand['brand'];?></b> <?php echo $brand['name'];?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
