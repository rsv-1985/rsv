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
                Панель управления
                <small>Version 2.0</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
                <li class="active">Панель управления</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-4 col-xs-6">
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
                <div class="col-lg-4 col-xs-6">
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
                <div class="col-lg-4 col-xs-6">
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
            </div>
            <div class="row">
                <div class="col-md-8">
                    <?php if($new_orders){?>
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Заказы со статусом <?php echo $this->orderstatus_model->get_default()['name'];?></h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table no-margin">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Фио</th>
                                            <th>Телефон</th>
                                            <th>Сумма</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($new_orders as $order){?>
                                            <tr>
                                                <td><a href="/autoxadmin/order/edit/<?php echo $order['id'];?>"><?php echo $order['id'];?></a></td>
                                                <td><?php echo $order['last_name'] . ' ' . $order['first_name'];?></td>
                                                <td><?php echo $order['telephone'];?></td>
                                                <td>
                                                    <?php echo $order['total'];?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-4">

                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Подписка на обновления CMS</span>
                                <span class="info-box-number">
                                    <?php if($cms_updates->balance > 0){ ?>
                                        <span class="label label-success">Активна | Баланс: <?php echo $cms_updates->balance;?></span>
                                    <?php }else{ ?>
                                        <span class="label label-danger">Не активна | Баланс: <?php echo $cms_updates->balance;?></span>
                                        <br><small><a onclick="alert('В комментарии укажите название услуги.');" target="_blank" href="https://send.monobank.com.ua/9R6XYW3Q"> Пополнить баланс</a></small>
                                        <br><a style="font-size: 8px" class="pull-right" href="https://docs.google.com/document/d/1Ou203X8O2sTkOVB3Ahp1XWDrW-N-dcEkIRx8KfJmpVE/edit?usp=sharing" target="_blank">Условия и стоимость подписки</a>
                                    <?php } ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                </div>
            </div>
            <div class="clearfix"></div>
        </section><!-- /.content -->

