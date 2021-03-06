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
    <script src="<?php echo theme_url();?>admin/plugins/jQueryUI/jquery-ui.js"></script>
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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-question-circle fa-lg"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a onclick="alert('В комментарии укажите название услуги.');" target="_blank" href="https://send.monobank.com.ua/9R6XYW3Q">Оплата услуг</a>
                            </li>
                            <li>
                                <a href="https://blog.autox.pro" target="_blank">Блог</a>
                            </li>
                            <li>
                                <a href="https://invite.viber.com/?g2=AQBhGLd%2FtdKJ0kkw75AjRkor33GajHcQZgIzBTMD%2BmlICzls7EsPBtY06txz%2BqKG" target="_blank">Группа viber</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a target="_blank" href="https://t.me/s_rozputnii">Поддержка в Telegram</a>
                            </li>
                            <li><a rel="nofollow" target="_blank" href="viber://chat?number=+380991306362">Поддержка в Viber</a></li>
                            <li class="divider"></li>
                        </ul>
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
            <?php $this->load->view('admin/menu');?>

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
