<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>
<style>
    .calendar{
        width: 100%;
        background: white;
    }
    .calendar th {
        background: #607D8B;
        text-align: center;
        color: white;
        height: 40px;
    }
    .calendar th a {
        color: white;
    }
    .calendar .week td {
        text-align: center;
        font-weight: bold;
        padding: 10px;
    }
    .calendar .cal td {
        border: 1px solid #ecf0f5;
        height: 50px;
        text-align: center;
        width: 50px;
    }
    .calendar .cal td:hover {
        background: #f0f3f7;
    }
    .calendar .cal a{
        font-size: 12px;
    }
    hr {
        margin-top: 5px;
        margin-bottom: 5px;
        border: 0;
        border-top: 1px solid #f5f7fa;
    }
</style>


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            СТО
            <small>заявки</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active">СТО</li>
        </ol>
    </section>
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="col-md-6">
                    <div class="box-header with-border">
                        <h3 class="box-title">Календарь</h3>
                    </div>
                    <div class="box-body">
                        <?php echo $calendar;?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box-header">
                        <h3 class="box-title">Списком</h3>
                        <div class="pull-right">
                            <a href="/autoxadmin/sto/create" class="btn btn-info">Добавить</a>
                        </div>
                    </div>
                    <?php if($records){?>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <th>Клиент</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                    <th>Услуга</th>
                                    <th></th>
                                </tr>
                                <?php foreach ($records as $record){?>
                                    <tr style="border-left: 10px solid <?php echo @$statuses[$record['status_id']]['color'];?>">
                                        <td><?php echo $record['name'];?><br>
                                            <small><?php echo $record['phone'];?><br>
                                                <?php echo $record['email'];?></small></td>
                                        <td>
                                            <?php echo $record['date'];?>
                                        </td>
                                        <td>
                                            <b style="color: <?php echo @$statuses[$record['status_id']]['color'];?>"><?php echo $statuses[$record['status_id']]['name'];?></b>
                                        </td>
                                        <td><?php echo $record['service'];?></td>
                                        <td>
                                            <div class="btn-group pull-right">
                                                <a href="/autoxadmin/sto/delete/<?php echo $record['id'];?>" class="btn btn-danger confirm"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                <a href="/autoxadmin/sto/edit/<?php echo $record['id'];?>" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php echo $this->pagination->create_links();?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>



