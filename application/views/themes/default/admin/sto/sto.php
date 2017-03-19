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
        height: 150px;
        text-align: center;
        width: 100px;
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
            <div class="box-header with-border">
                <h3 class="box-title">Календарь</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php echo $calendar;?>
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Списком</h3>
            </div>
            <?php if($records){?>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>

                            <tr>
                                <th>ID</th>
                                <th>Клиент</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th>Услуга</th>
                                <th></th>
                            </tr>
                            <?php foreach ($records as $record){?>
                                <tr style="border-left: 10px solid <?php echo @$statuses[$record['status']]['color'] ?? 'white';?>">
                                    <td><?php echo $record['id'];?></td>
                                    <td><?php echo $record['name'];?><br>
                                    <small><?php echo $record['phone'];?><br>
                                    <?php echo $record['email'];?></small></td>
                                    <td>
                                        <?php echo $record['date'].' '.$record['time'];?>
                                    </td>
                                    <td>
                                        <b style="color: <?php echo @$statuses[$record['status']]['color'] ?? 'white';?>"><?php echo $record['status'];?></b>
                                    </td>
                                    <td><?php echo $record['service'];?></td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/sto/delete/<?php echo $record['id'];?>" class="btn btn-danger confirm">Удалить</a>
                                            <a href="/autoxadmin/sto/edit/<?php echo $record['id'];?>" class="btn btn-info">Изменить</a>
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
    </section>



