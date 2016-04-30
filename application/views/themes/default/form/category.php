<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if ($this->category) { ?>
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo lang('text_category');?></div>
        <ul id="mega-1" class="mega-menu">
            <?php foreach ($this->category as $category){?>
            <li><a href="/category/<?php echo $category['slug'];?>"><?php echo $category['name'];?></a>
                <?php if($category['children']){?>
                    <?php echo format_category($category['children']);?>
                    
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
