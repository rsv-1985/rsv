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
                    <h1><?php echo lang('text_news_heading_title');?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($news as $new){?>
                    <div class="media">
                        <div class="media-left">
                            <?php if(preg_match('/src="([^"]*)"/', $new['description'], $matches)){?>
                                <a href="/news/<?php echo $new['slug'];?>">
                                    <img width="100" class="media-object" src="<?php echo $matches[1];?>" alt="<?php echo $new['name'];?>">
                                </a>
                            <?php }else{ ?>
                                <a href="/news/<?php echo $new['slug'];?>">
                                    <img width="100" class="media-object" src="/image" alt="<?php echo $new['name'];?>">
                                </a>
                            <?php } ?>
                        </div>
                        <div class="media-body">
                            <a href="/news/<?php echo $new['slug'];?>">
                                <h4 class="media-heading"><?php echo $new['name'];?></h4>
                            </a>
                            <small><?php echo word_limiter(strip_tags($new['description']),20);?></small>
                            <br>
                            <a href="/news/<?php echo $new['slug'];?>">
                                <?php echo lang('text_news_href');?>
                            </a>
                        </div>
                    </div>
                <?php } ?>

                <?php echo $this->pagination->create_links();?>
            </div>
        </div>
    </div>
</div>

