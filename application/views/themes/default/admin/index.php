<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Version 2.0</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-2 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php echo $new_order;?></h3>
                            <p>Новые заказы</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="/autoxadmin/order" class="small-box-footer">Подробнее <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?php echo $new_vin;?></h3>
                            <p>Новые VIN</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-search"></i>
                        </div>
                        <a href="/autoxadmin/vin" class="small-box-footer">Подробнее <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?php echo $new_customer;?></h3>
                            <p>Новые клиенты</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="/autoxadmin/customer?status=false" class="small-box-footer">Подробнее <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6">
                    <iframe style="width: 100%; height: -webkit-fill-available; border: 0px;" src="https://cms-autox.blogspot.com/"></iframe>
                </div>
            </div>
            <div class="clearfix"></div>
        </section><!-- /.content -->


