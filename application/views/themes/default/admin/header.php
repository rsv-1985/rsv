<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AutoXadmin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo theme_url();?>admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo theme_url();?>admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo theme_url();?>admin/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo theme_url();?>admin/dist/css/skins/_all-skins.min.css">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo theme_url();?>admin/plugins/colorpicker/bootstrap-colorpicker.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <!-- jQuery 2.1.4 -->
    <script src="<?php echo theme_url();?>admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">

        <!-- Logo -->
        <a href="/autoxadmin" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>X</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>AutoX</b>admin</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li>
                        <a onclick="alert('В комментарии укажите название услуги.');" target="_blank" href="https://send.monobank.com.ua/9R6XYW3Q">Оплата</a>
                    </li>
                    <li>
                        <a target="_blank" href="https://t.me/joinchat/E8H7Q0QeDmQY7iul11PIFg">Складчина</a>
                    </li>
                    <li>
                        <a target="_blank" href="https://t.me/autox_pro">Обновления</i></a>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="<?php echo base_url('autoxadmin/user/logout');?>" title="<?php echo lang('text_logout');?>" data-toggle="tooltip" class="dropdown-toggle">
                            <span class="hidden-xs"><?php echo lang('text_logout');?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="<?php echo base_url();?>"><?php echo lang('text_site');?></a>
                    </li>
                </ul>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <div class="user-panel">
                <div class="info">
                    <p><?php echo $this->session->firstname.' '.$this->session->lastname;?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i> <span><?php echo lang('text_shop');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/order"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_order');?></a></li>
                        <li><a href="/autoxadmin/category"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_category');?></a></li>
                        <li><a href="/autoxadmin/product"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_product');?></a></li>
                        <li><a href="/autoxadmin/supplier"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_supplier');?></a></li>
                        <li><a href="/autoxadmin/customer"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_customer');?></a></li>
                        <li><a href="/autoxadmin/customergroup"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_customergroup');?></a></li>
                        <li><a href="/autoxadmin/customerbalance"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_customerbalance');?></a></li>
                        <li><a href="/autoxadmin/newsletter"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_newsletter');?></a></li>
                        <li><a href="/autoxadmin/vin"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_vin');?></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-building" aria-hidden="true"></i> <span><?php echo lang('text_store');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/waybill"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_waybill');?></a></li>
                        <li><a href="/autoxadmin/order_ttn"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_order_ttn');?></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-wrench"></i> <span><?php echo lang('text_nav_sto');?>
                        </span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/sto"><i class="fa fa-circle-o"></i>Заявки</a></li>
                        <li><a href="/autoxadmin/sto/settings"><i class="fa fa-circle-o"></i>Настройки</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-pencil-square-o"></i> <span><?php echo lang('text_content');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/page"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_page');?></a></li>
                        <li><a href="/autoxadmin/news"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_news');?></a></li>
                        <li><a href="/autoxadmin/important_news"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_important_news');?></a></li>
                        <li><a href="/autoxadmin/banner"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_banner');?></a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-wrench"></i> <span><?php echo lang('text_instruments');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/synonym"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_synonym');?></a></li>
                        <li><a href="/autoxadmin/brand_group"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_brand_group');?></a></li>
                        <li><a href="/autoxadmin/synonym_name"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_synonym_name');?></a></li>
                        <li><a href="/autoxadmin/import"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_import');?></a></li>
                        <li><a href="/autoxadmin/cross"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_cross');?></a></li>
                        <li><a href="/autoxadmin/price"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_price');?></a></li>
                        <li><a href="/autoxadmin/sending"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_sending');?></a></li>

                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-area-chart" aria-hidden="true"></i> Отчеты</a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/report/cart"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_cart');?></a></li>
                        <li><a href="/autoxadmin/report/search_history"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_search_history');?></a></li>
                        <li><a href="/autoxadmin/report/sale_order"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_report_sale_order');?></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>SEO настройки</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/seo_settings/product"><i class="fa fa-circle-o"></i>Товара</a></li>
                        <li><a href="/autoxadmin/seo_settings/brand"><i class="fa fa-circle-o"></i>Производитель</a></li>
                        <li><a href="/autoxadmin/seo_settings/robots"><i class="fa fa-circle-o"></i>Robots.txt</a></li>
                        <li><a href="/autoxadmin/seo_settings/sitemap"><i class="fa fa-circle-o"></i>Sitemap</a></li>
                        <li><a href="/autoxadmin/seo_settings/tecdoc"><i class="fa fa-circle-o"></i>Каталог Tecdoc</a></li>
                        <li><a href="/autoxadmin/seo_settings/tecdoc_manufacturer"><i class="fa fa-circle-o"></i>Tecdoc производители</a></li>
                        <li><a href="/autoxadmin/seo_settings/tecdoc_model"><i class="fa fa-circle-o"></i>Tecdoc модели</a></li>
                        <li><a href="/autoxadmin/seo_settings/tecdoc_type"><i class="fa fa-circle-o"></i>Tecdoc модификации</a></li>
                        <li><a href="/autoxadmin/seo_settings/tecdoc_tree"><i class="fa fa-circle-o"></i>Tecdoc категории</a></li>
                        <li><a href="/autoxadmin/seo_settings/hook"><i class="fa fa-circle-o"></i>SEO hook</a></li>
                        <li><a href="/autoxadmin/seo_settings/redirect"><i class="fa fa-circle-o"></i>Redirect</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cog"></i> <span>Tecdoc настройки</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/tecdoc_settings/manufacturer"><i class="fa fa-circle-o"></i>Производители</a></li>
                        <li><a href="/autoxadmin/tecdoc_settings/tree"><i class="fa fa-circle-o"></i>Категории</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span><?php echo lang('text_settings');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/settings"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_settings');?></a></li>
                        <li><a href="/autoxadmin/language"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_language');?></a></li>
                        <li><a href="/autoxadmin/currency"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_currency');?></a></li>
                        <li><a href="/autoxadmin/payment"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_payment');?></a></li>
                        <li><a href="/autoxadmin/delivery"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_delivery');?></a></li>
                        <li><a href="/autoxadmin/orderstatus"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_orderstatus');?></a></li>
                        <li><a href="/autoxadmin/user"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_user');?></a></li>
                        <li><a href="/autoxadmin/usergroup"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_usergroup');?></a></li>
                        <li><a href="/autoxadmin/apikey"><i class="fa fa-circle-o"></i>API ключи</a></li>
                        <li><a href="/autoxadmin/message_template"><i class="fa fa-circle-o"></i><?php echo lang('text_nav_message_template');?></a></li>
                        <li><a href="/autoxadmin/index/cache"><i class="fa fa-circle-o"></i>Очистить кэш</a></li>
                        <li><a target="_blank" class="confirm" data-confirm="Вы ознакомились с обновлениями и хотите продолжить ?" href="/updatesystem.php"><i class="fa fa-circle-o"></i>Обновить систему</a></li>
                    </ul>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php if($this->error){?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Warning!</h4>
                <?php echo $this->error;?>
            </div>
        <?php } ?>
        <?php if($this->success){?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4>	<i class="icon fa fa-check"></i> Success!</h4>
                <?php echo $this->success;?>.
            </div>
        <?php } ?>
