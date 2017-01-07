<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if ($this->category) { ?>
    <div class="panel">
        <div class="panel-heading">
            <?php echo lang('text_category'); ?>
            <i class="glyphicon glyphicon-th-list category pull-right hidden-lg"></i>
        </div>
        <div class="cd-dropdown-wrapper">
            <nav class="cd-dropdown">
                <a href="#0" class="cd-close">Close</a>
                <ul class="cd-dropdown-content">
                    <?php foreach ($this->category as $category) { ?>
                        <li <?php if ($category['children']){ ?>class="has-children"<?php } ?>>
                            <a href="/category/<?php echo $category['slug']; ?>"><?php echo $category['name']; ?></a>
                            <?php if ($category['children']) { ?>
                                <ul class="cd-secondary-dropdown is-hidden">
                                    <li class="go-back"><a href="#0"><?php echo $category['name']; ?></a></li>
                                    <?php foreach ($category['children'] as $child){?>
                                        <li class="has-children">
                                        <a href="/category/<?php echo $child['slug'];?>"><?php echo $child['name'];?></a>

                                        <ul class="is-hidden">
                                            <li class="go-back"><a href="#0"><?php echo $child['name'];?></a></li>
                                            <?php if($child['brands']){?>
                                                <?php foreach ($child['brands'] as $url_brand => $name_brand){?>
                                                    <li><a href="/category/<?php echo $child['slug'];?>/brand/<?php echo $url_brand;?> "><?php echo $name_brand;?></a></li>
                                                <?php } ?>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <?php } ?>
                                </ul> <!-- .cd-secondary-dropdown -->
                            <?php } ?>
                        </li> <!-- .has-children -->
                    <?php } ?>
                </ul> <!-- .cd-dropdown-content -->
            </nav> <!-- .cd-dropdown -->
        </div> <!-- .cd-dropdown-wrapper -->
    </div>

<?php } ?>

