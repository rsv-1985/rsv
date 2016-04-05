<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1;?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">

                <div class="col-md-4">
                    <div class="single-sidebar">
                        <h2 class="sidebar-title"><?php echo lang('text_page_navigation');?></h2>
                        <ul>
                            <?php if($main){?>
                                <li><a href="/page/<?php echo $main['slug'];?>"><i class="fa fa-mail-reply"></i> <?php echo !empty($main['menu_title']) ? $main['menu_title'] : $main['name'];?></a></li>
                            <?php } ?>
                            <?php if($parent){?>
                                <?php foreach($parent as $parent){?>
                                    <li><a href="/page/<?php echo $parent['slug'];?>"><?php echo !empty($parent['menu_title']) ? $parent['menu_title'] : $parent['name'];?></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="single-sidebar">
                        <?php echo form_open('ajax/pre_search', ['method' => 'get', 'class' => 'search_form']);?>
                            <input type="text" name="search" placeholder="OC90" required>
                            <input type="submit" value="<?php echo lang('button_search');?>">
                        </form>
                    </div>
                </div>
            <div class="col-md-8">
                <?php echo $description;?>
            </div>
        </div>
    </div>
</div>
