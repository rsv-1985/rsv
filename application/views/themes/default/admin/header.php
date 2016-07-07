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
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-institution"></i> <span><?php echo lang('text_shop');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/order"><i class="fa fa-circle-o"></i><?php echo lang('text_shop_order');?>
                                <?php if($this->new_order){?>
                                    <small class="label pull-right bg-yellow"><?php echo $this->new_order;?></small>
                                <?php } ?>
                            </a>
                        </li>
                        <li><a href="/autoxadmin/customer"><i class="fa fa-circle-o"></i><?php echo lang('text_shop_customer');?>
                                <?php if($this->new_customer){?>
                                    <small class="label pull-right bg-yellow"><?php echo $this->new_customer;?></small>
                                <?php } ?>
                            </a></li>
                        <li><a href="/autoxadmin/newsletter"><i class="fa fa-circle-o"></i><?php echo lang('text_shop_newsletter');?></a></li>
                        <li><a href="/autoxadmin/customergroup"><i class="fa fa-circle-o"></i><?php echo lang('text_shop_customer_group');?></a></li>
                    </ul>
                </li>

                <li class="treeview" style="display: none;">
                    <a href="#">
                        <i class="fa fa-files-o"></i> <span><?php echo lang('text_catalog');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.html"><i class="fa fa-circle-o"></i><?php echo lang('text_catalog_product');?></a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-table"></i> <span><?php echo lang('text_catalog');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/product"><i class="fa fa-circle-o"></i><?php echo lang('text_catalog_product');?></a></li>
                        <li><a href="/autoxadmin/category"><i class="fa fa-circle-o"></i><?php echo lang('text_catalog_category');?></a></li>
                        <li><a href="/autoxadmin/supplier"><i class="fa fa-circle-o"></i><?php echo lang('text_shop_supplier');?></a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-pencil-square-o"></i> <span><?php echo lang('text_content');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/page"><i class="fa fa-circle-o"></i><?php echo lang('text_content_page');?></a></li>
                        <li><a href="/autoxadmin/news"><i class="fa fa-circle-o"></i><?php echo lang('text_content_news');?></a></li>
                        <li><a href="/autoxadmin/banner"><i class="fa fa-circle-o"></i><?php echo lang('text_content_baner');?></a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-wrench"></i> <span><?php echo lang('text_instruments');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/synonym"><i class="fa fa-circle-o"></i><?php echo lang('text_instruments_synonym');?></a></li>
                        <li><a href="/autoxadmin/import"><i class="fa fa-circle-o"></i><?php echo lang('text_instruments_price');?></a></li>
                        <li><a href="/autoxadmin/cross"><i class="fa fa-circle-o"></i><?php echo lang('text_instruments_cross');?></a></li>
                        <li><a href="/autoxadmin/price"><i class="fa fa-circle-o"></i><?php echo lang('text_instruments_price');?></a></li>

                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span><?php echo lang('text_settings');?></span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/autoxadmin/settings"><i class="fa fa-circle-o"></i><?php echo lang('text_settings_main');?></a></li>
                        <li><a href="/autoxadmin/currency"><i class="fa fa-circle-o"></i><?php echo lang('text_settings_currency');?></a></li>
                        <li><a href="/autoxadmin/payment"><i class="fa fa-circle-o"></i><?php echo lang('text_settings_pay');?></a></li>
                        <li><a href="/autoxadmin/delivery"><i class="fa fa-circle-o"></i><?php echo lang('text_settings_ship');?></a></li>
                        <li><a href="/autoxadmin/orderstatus"><i class="fa fa-circle-o"></i><?php echo lang('text_settings_status');?></a></li>
                        <li><a href="/autoxadmin/admin"><i class="fa fa-circle-o"></i><?php echo lang('text_settings_user');?></a></li>
                        <li><a href="/autoxadmin/index/cache"><i class="fa fa-circle-o"></i>Clear Cache</a></li>
                        <li><a target="_blank" href="/updatesystem.php"><i class="fa fa-circle-o"></i>Update system</a></li>
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
