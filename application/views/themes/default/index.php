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
                            <img onerror="imgError(this);"
                                 src="/image?img=/uploads/banner/<?php echo $slider['image']; ?>"
                                 alt="<?php echo $slider['name']; ?>" title="<?php echo $slider['name']; ?>">
                            <div class="caption-group">
                                <b><?php echo $slider['name']; ?></b>
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
                    <a href="<?php echo $box['link']; ?>"
                       <?php if ($box['new_window']){ ?>target="_blank" <?php } ?>>
                        <div class="col-md-3 col-sm-6">
                            <div class="single-promo promo1">
                                <img onerror="imgError(this);"
                                     src="/uploads/banner/<?php echo $box['image']; ?>"
                                     alt="<?php echo $box['name']; ?>" title="<?php echo $box['name']; ?>">
                                <span>
                                <?php echo $box['name']; ?><br>
                                <small><?php echo $box['description']; ?></small>
                            </span>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div> <!-- End promo area -->
<?php } ?>
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h4><?php echo lang('text_fast_tecdoc'); ?></h4>
                <div class="well">
                    <div class="form-inline">
                        <select class="form-control" id="year" onchange="getManufacturerYear($(this).val())">
                            <option><?php echo lang('index_text_years'); ?></option>
                            <?php foreach ($this->tecdoc->getYears() as $year_group => $years) { ?>
                                <optgroup label="<?php echo $year_group; ?>">
                                    <?php foreach ($years as $year) { ?>
                                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                    <?php } ?>
                                </optgroup>

                            <?php } ?>
                        </select>

                        <select class="form-control" id="manufacturer" onchange="getModelYear($(this).val())" disabled>
                            <option><?php echo lang('index_text_manufacturer'); ?></option>
                        </select>

                        <select class="form-control" id="model" onchange="getTypYear($(this).val())" disabled>
                            <option><?php echo lang('index_text_model'); ?></option>
                        </select>

                        <select class="form-control" id="typ" onchange="getTree($(this).val())" disabled>
                            <option><?php echo lang('index_text_typ'); ?></option>
                        </select>
                        <script>
                            function getManufacturerYear(year) {
                                $.ajax({
                                    url: '/ajax/get_manufacturer_year',
                                    data: {year: year},
                                    method: 'post',
                                    success: function (html) {
                                        if (html != '') {
                                            $("#manufacturer").html(html).removeAttr('disabled');
                                        }
                                    }
                                });
                            }

                            function getModelYear(ID_mfa) {
                                $.ajax({
                                    url: '/ajax/get_model_year',
                                    data: {ID_mfa: ID_mfa, year: $('#year option:selected').val()},
                                    method: 'post',
                                    success: function (html) {
                                        if (html != '') {
                                            $("#model").html(html).removeAttr('disabled');
                                        }
                                    }
                                });
                            }

                            function getTypYear(ID_mod) {
                                $.ajax({
                                    url: '/ajax/get_typ_year',
                                    data: {ID_mod: ID_mod, year: $('#year option:selected').val()},
                                    method: 'post',
                                    success: function (html) {
                                        if (html != '') {
                                            $("#typ").html(html).removeAttr('disabled');
                                        }
                                    }
                                });
                            }

                            function getTree(ID_typ) {
                                var ID_mfa = $('#manufacturer option:selected').val();
                                var ID_mod = $('#model option:selected').val();

                                $.ajax({
                                    url: '/ajax/get_tree',
                                    data: {ID_mfa: ID_mfa, ID_mod: ID_mod, ID_typ: ID_typ},
                                    method: 'post',
                                    success: function (response) {
                                        if (response != '') {
                                            location.href = response;
                                        }
                                    }
                                });
                            }
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <h4><?php echo lang('text_garage');?></h4>
                <?php if ($this->garage) { ?>
                    <?php $q = true;
                    foreach ($this->garage as $key => $garage) { ?>
                        <div class="panel panel-default <?php echo $key; ?>">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <a href="#" class="glyphicon glyphicon-remove pull-right"
                                   onclick="remove_garage('<?php echo $key; ?>',event)"></a>
                                <h3 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion"
                                       href="#<?php echo $key; ?>" aria-expanded="true"
                                       aria-controls="<?php echo $key; ?>">
                                        <?php echo $garage['name']; ?>
                                    </a>
                                </h3>
                            </div>
                            <div id="<?php echo $key; ?>" class="panel-collapse collapse <?php if ($q) { ?>in<?php } ?>"
                                 role="tabpanel" aria-labelledby="<?php echo $key; ?>">
                                <div class="panel-body">
                                    <?php if ($garage['category']) { ?>
                                        <b><?php echo lang('text_selected_topics'); ?></b>
                                        <ul>
                                            <?php foreach ($garage['category'] as $ID_tree => $garage_category) { ?>
                                                <li>
                                                    <a href="<?php echo $garage['href']; ?>?id_tree=<?php echo $ID_tree; ?>"><?php echo $garage_category; ?></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                    <div class="center">
                                        <a class="btn btn-default"
                                           href="<?php echo $garage['href']; ?>"><?php echo lang('text_go_catalog'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $q = false;
                    } ?>
                <?php } else { ?>
                   <div class="center">
                        <a href="/catalog" class="btn btn-info"><?php echo lang('text_add_auto'); ?></a>
                   </div>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#catalog" aria-controls="catalog" role="tab" data-toggle="tab"><?php echo lang('text_full_tecdoc'); ?></a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="catalog" style="text-align: center">
                        <?php echo $catalog; ?>
                    </div>
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
                                        <img onerror="imgError(this);"
                                             src="/image?img=<?php echo $top_sellers['image']; ?>&height=100"
                                             alt="<?php echo $top_sellers['name']; ?>"
                                             title="<?php echo $top_sellers['name']; ?>" class="product-thumb"></a>
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
                                        <img onerror="imgError(this);"
                                             src="/image?img=<?php echo $novelty['image']; ?>&height=100"
                                             alt="<?php echo $novelty['name']; ?>"
                                             title="<?php echo $novelty['name']; ?>" class="product-thumb"></a>
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
                <?php if ($news) { ?>
                <div class="col-md-4"
                <div class="single-product-widget">
                    <h2 class="product-wid-title"><?php echo lang('text_news'); ?></h2>
                    <?php foreach ($news as $news) { ?>
                        <div class="single-wid-product">
                            <h2>
                                <a href="/news/<?php echo $news['slug']; ?>"><?php echo $news['name']; ?></a>
                            </h2>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="product-wid-title"><?php echo $name; ?></h1>
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
                                <img onerror="imgError(this);" src="/uploads/banner/<?php echo $carousel['image']; ?>"
                                     alt="<?php echo $carousel['name']; ?>" title="<?php echo $carousel['name']; ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End brands area -->
<?php } ?>