<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($slider) { ?>
    <div class="slider-area">
        <!-- Slider -->
        <div class="block-slider block-slider4">
            <ul class="" id="bxslider-home4">
                <?php foreach ($slider as $slider) { ?>
                    <li>
                        <a href="<?php echo $slider['link']; ?>"
                           <?php if ($slider['new_window']){ ?>target="_blank" <?php } ?>>
                            <img src="/image?img=/uploads/banner/<?php echo $slider['image']; ?>&height=300"
                                 alt="<?php echo $slider['name']; ?>">
                            <div class="caption-group">
                                <h2><?php echo $slider['name']; ?></h2>
                                <?php echo $slider['description']; ?>
                            </div>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- ./Slider -->
    </div> <!-- End slider area -->
<?php } ?>
<?php if ($box) { ?>
    <div class="promo-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <?php foreach ($box as $box) { ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="single-promo promo1">
                            <img src="/image?img=/uploads/banner/<?php echo $box['image']; ?>&width=263"
                                 alt="<?php echo $box['name']; ?>">
                        <span>
                            <?php echo $box['name']; ?><br>
                            <small><?php echo $box['description']; ?></small>
                        </span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div> <!-- End promo area -->
<?php } ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <?php echo $this->load->view('form/category', '', true); ?>
                <?php if ($news) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo lang('text_news'); ?></div>
                            <div class="list-group">
                            <?php foreach ($news as $news) { ?>
                                <a href="/news/<?php echo $news['slug'];?>" class="list-group-item"><?php echo $news['name'];?></a>
                            <?php } ?>
                            </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-8">
                <h3>Каталог</h3>
                <div class="catalog">
                    <?php if ($manufacturers) { ?>
                        <?php foreach ($manufacturers as $manufacturer) { ?>
                            <a href="/catalog/<?php echo $manufacturer['slug']; ?>">
                                <img src="<?php echo $manufacturer['logo']; ?>"
                                     alt="<?php echo $manufacturer['name']; ?>">
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="product-widget-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <hr>
            <div class="row">
                <?php if ($top_sellers) { ?>
                    <div class="col-md-4">
                        <div class="single-product-widget">
                            <h2 class="product-wid-title"><?php echo lang('text_top_sales'); ?></h2>
                            <?php foreach ($top_sellers as $top_sellers) { ?>
                                <div class="single-wid-product">
                                    <a href="/product/<?php echo $top_sellers['slug']; ?>">
                                        <?php if ($top_sellers['image']){ ?>
                                        <img src="/image?img=<?php echo $top_sellers['image']; ?>&width=100"
                                             alt="<?php echo $top_sellers['name']; ?>" class="product-thumb"></a>
                                    <?php } ?>
                                    <h2>
                                        <a href="/product/<?php echo $top_sellers['slug']; ?>"><?php echo $top_sellers['name']; ?></a>
                                    </h2>
                                    <div class="product-wid-price">
                                        <ins><?php echo format_currency($top_sellers['saleprice'] > 0 ? $top_sellers['saleprice'] : $top_sellers['price']); ?></ins>
                                        <?php if ($top_sellers['saleprice'] > 0) { ?>
                                            <del><?php echo format_currency($top_sellers['price']); ?></del>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($novelty) { ?>
                    <div class="col-md-4">
                        <div class="single-product-widget">
                            <h2 class="product-wid-title"><?php echo lang('text_novetly'); ?></h2>
                            <?php foreach ($novelty as $novelty) { ?>
                                <div class="single-wid-product">
                                    <a href="/product/<?php echo $novelty['slug']; ?>">
                                        <?php if ($novelty['image']){ ?>
                                        <img src="/image?img=<?php echo $novelty['image']; ?>&width=100"
                                             alt="<?php echo $novelty['name']; ?>" class="product-thumb"></a>
                                    <?php } ?>
                                    <h2>
                                        <a href="/product/<?php echo $novelty['slug']; ?>"><?php echo $novelty['name']; ?></a>
                                    </h2>
                                    <div class="product-wid-price">
                                        <ins><?php echo format_currency($novelty['saleprice'] > 0 ? $novelty['saleprice'] : $novelty['price']); ?></ins>
                                        <?php if ($novelty['saleprice'] > 0) { ?>
                                            <del><?php echo format_currency($novelty['price']); ?></del>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-4">
                    <h2 class="product-wid-title"><?php echo $name; ?></h2>
                    <?php echo $description; ?>
                </div>
            </div>
        </div>
    </div> <!-- End product widget area -->

<?php if ($carousel) { ?>
    <div class="brands-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="brand-wrapper">
                        <div class="brand-list">
                            <?php foreach ($carousel as $carousel) { ?>
                                <img src="/uploads/banner/<?php echo $carousel['image']; ?>"
                                     alt="<?php echo $carousel['name']; ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End brands area -->
<?php } ?>