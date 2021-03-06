<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php if (@$this->options['google_tag_head']) {
        echo $this->options['google_tag_head'];
    } ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo str_replace('"', '', $this->title); ?></title>
    <meta name="description" content="<?php echo str_replace('"', '', $this->description); ?>">
    <meta name="keywords" content="<?php echo str_replace('"', '', $this->keywords); ?>">
    <script src="<?php echo theme_url(); ?>js/jquery-1.12.3.min.js"></script>
    <link rel="shortcut icon" href="/favicon.ico" type="image/ico">
    <?php if($this->og){?>
        <?php foreach ($this->og as $key => $value){?>
            <meta property="og:<?php echo $key;?>" content="<?php echo $value;?>"/>
        <?php } ?>
    <?php } ?>
    <?php if ($this->rel_prev) { ?>
        <link rel="prev" href="<?php echo $this->rel_prev; ?>">
    <?php } ?>
    <?php if ($this->rel_next) { ?>
        <link rel="next" href="<?php echo $this->rel_next; ?>">
    <?php } ?>
    <?php if ($this->canonical) { ?>
        <link rel="canonical" href="<?php echo $this->canonical; ?>"/>
    <?php } ?>
    <?php if ($this->structure) { ?>
        <script type="application/ld+json">
            <?php echo $this->structure; ?>

        </script>
    <?php } ?>
    <link rel="stylesheet" href="<?php echo theme_url(); ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo theme_url(); ?>style.css?v=7">
    <?php if ($this->config->item('my_style')) { ?>
        <?php foreach ($this->config->item('my_style') as $style) { ?>
            <link rel="stylesheet" href="<?php echo $style; ?>">
        <?php } ?>
    <?php } ?>
    <?php if (@$this->options['style']) { ?>
        <style>
            <?php echo $this->options['style'];?>
        </style>
    <?php } ?>
</head>
<body>
<?php if($this->customer_model->checkNegativeBalance($this->is_login)){?>
    <noindex>
        <div class="head_negative_balance">
            <p><?php echo lang('text_head_negative_balance'); ?></p>
        </div>
    </noindex>
<?php } ?>
<?php if($this->customer_model->checkDefermentPayment($this->is_login)){?>
    <noindex>
        <div class="head_negative_balance">
            <p><?php echo lang('text_head_deferment_payment'); ?></p>
        </div>
    </noindex>
<?php } ?>
<?php if ($this->options['head_text_messages']) { ?>
    <noindex>
        <div class="head_messages">
            <p><?php echo $this->options['head_text_messages']; ?></p>
        </div>
    </noindex>
<?php } ?>

<?php if (@$this->options['google_tag_body']) {
    echo $this->options['google_tag_body'];
} ?>

<?php echo @$this->options['analytics']; ?>
<?php if ($this->show_modal && @$this->important_news['status']) { ?>

    <div class="modal fade" id="important_news" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-modal-show" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <?php echo $this->important_news['description']; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger cookie-modal-show" data-dismiss="modal">Больше не
                        показывать
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        $(document).ready(function () {
            $("#important_news").modal('show');

            $(".cookie-modal-show").click(function (event) {
                event.preventDefault();
                $.ajax({
                    url: '/ajax/cookie_modal',
                    method: 'get',
                    success: function () {
                        $(".close-modal-show").click();
                    }
                });
            });
        });
    </script>

<?php } ?>
<div class="header-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->options['show_currency_rate']) { ?>
                    <div class="pull-left currency_rate">
                        <?php foreach ($this->currency_model->currencies as $cur) { ?>
                            <?php if ($cur['value'] != 1) { ?>
                                <?php echo $cur['name']; ?> : <span><?php echo round($cur['value'], 2); ?></span>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php foreach (explode(';', $this->contacts['phone']) as $phone) { ?>
                    <a id="callback-button" href="tel:<?php echo format_phone($phone); ?>">
                        <i class="fa fa-phone-square"></i> <?php echo $phone; ?>&nbsp;
                    </a>
                <?php } ?>

                <div class="pull-right">
                    <?php if ($this->is_login) { ?>
                        <a href="/customer">
                            <i class="glyphicon glyphicon-user"></i>
                            <?php echo $this->customer_model->first_name; ?> (<?php echo format_balance($this->customer_model->balance); ?>)
                        </a>/
                        <a href="/customer/logout"><?php echo lang('text_logout'); ?></a>
                    <?php } else { ?>
                        <a rel="nofollow" href="#" data-toggle="modal"
                           data-target="#login"><?php echo lang('text_login_link'); ?></a>
                        /
                        <a rel="nofollow" href="/customer/registration"><?php echo lang('text_registration'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End header area -->

<div class="site-branding-area">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="logo">
                    <?php if ($this->config->item('company_logo')) { ?>
                        <a href="/"><img onerror="imgError(this);"
                                         src="<?php echo $this->config->item('company_logo'); ?>"/></a>
                    <?php } else { ?>
                        <a href="/"><?php echo $this->config->item('company_name'); ?></a>
                    <?php } ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="search">
                    <?php echo form_open('search/pre_search', ['method' => 'get', 'class' => 'search_form', 'id' => 'search_form']); ?>
                    <div class="wrapper_search">
                        <input required type="text" id="search_input" name="search" class="input-text"
                               placeholder="<?php echo lang('text_placeholder_search'); ?>"
                               value="<?php echo set_value('search', $this->input->get('search')); ?>">
                    </div>
                    <input id="button_search" type="submit" data-value="<?php echo lang('button_search'); ?>"
                           value="<?php echo lang('button_search'); ?>" class="button alt">
                    </form>
                </div>
            </div>

            <div class="col-sm-3 col-xs-12 cart_m">
                <div class="shopping-item">
                    <a id="button-cart" rel="nofollow" href="/cart"><?php echo lang('text_cart'); ?> <span
                                class="cart-amunt"><?php echo format_currency($this->cart->total()); ?></span></i>
                        <span
                            <?php if ($this->cart->total_items() == 0) { ?>
                                style="display: none;"
                            <?php } ?>
                                class="product-count"><?php echo $this->cart->total_items(); ?></span>

                    </a>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End site branding area -->

<div class="mainmenu-area">
    <div class="container">
        <div class="row">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse">
                <?php if ($this->category) { ?>
                    <div class="nav_catalog">
                        <span class="nav_catalog_title"><?php echo lang('text_header_nav_catalog'); ?></span>
                        <ul class="nav_catalog_dropdown">
                            <?php echo $this->category; ?>
                        </ul>
                    </div>
                <?php } ?>
                <ul class="nav navbar-nav">
                    <?php if ($this->header_page) { ?>
                        <?php foreach ($this->header_page as $hpage) { ?>
                            <?php if (!$this->is_login && $hpage['show_for_user']) {
                                continue;
                            } ?>
                            <li>
                                <a target="<?php echo $hpage['target']; ?>" href="<?php echo $hpage['href']; ?>"
                                   title="<?php echo $hpage['title']; ?>"><?php echo $hpage['menu_title']; ?></a>
                                <?php if ($hpage['children']) { ?>
                                    <ul>
                                        <?php foreach ($hpage['children'] as $child) { ?>
                                            <?php if (!$this->is_login && $child['show_for_user']) {
                                                continue;
                                            } ?>
                                            <li>
                                                <a target="<?php echo $child['target']; ?>"
                                                   href="<?php echo $child['href']; ?>"
                                                   title="<?php echo $child['title']; ?>"><?php echo $child['menu_title']; ?></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div> <!-- End mainmenu area -->
<a href="#top" class="top"><img src="/assets/themes/default/img/up-arrow-icon-top.png" alt="top"/></a>
<?php if ($this->error) { ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i></h4>
        <?php echo $this->error; ?>
    </div>
<?php } ?>
<?php if ($this->success) { ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i></h4>
        <?php echo $this->success; ?>
    </div>
<?php } ?>
