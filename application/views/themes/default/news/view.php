<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1; ?></h1>
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
                <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <li>
                        <a href="/">
                            <span><?php echo lang('text_home'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="/news">
                            <span><?php echo lang('text_news_heading_title'); ?></span>
                        </a>
                    </li>
                    <li><?php echo $h1; ?></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php echo $description; ?>
            </div>
        </div>
    </div>
</div>

