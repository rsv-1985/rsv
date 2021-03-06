<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="#"><?php echo lang('text_heading');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo lang('text_heading');?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Номер ТТН</th>
                            <th>Заказ</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                        <?php echo form_open('/autoxadmin/order_ttn', ['method' => 'get']);?>
                        <tr>
                            <th>
                                <input class="form-control" type="text" name="ttn" value="<?php echo $this->input->get('ttn');?>">
                            </th>
                            <th>
                                <input class="form-control" type="text" name="order_id" value="<?php echo $this->input->get('order_id');?>">
                            </th>
                            <th></th>
                            <th>
                                <button type="submit" class="btn btn-info">Поиск</button>
                                <?php if($this->input->get()){?>
                                    <a href="/autoxadmin/order_ttn" class="btn btn-danger">Сброс</a>
                                <?php } ?>
                            </th>
                        </tr>
                        <?php echo form_close();?>
                        </thead>
                        <tbody>

                        <?php if($ttns){?>
                            <?php foreach($ttns as $ttn){?>
                                <tr>
                                    <td><?php echo $ttn['ttn'];?></td>
                                    <td>
                                        <a target="_blank" href="/autoxadmin/order/edit/<?php echo $ttn['order_id'];?>"><?php echo $ttn['order_id'];?></a>
                                    </td>
                                    <td><?php echo $ttn['status'];?></td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="<?php echo $ttn['delete'];?>" class="btn btn-xs btn-danger confirm">Удалить</a>
                                            <a target="_blank" href="<?php echo $ttn['print'];?>" class="btn btn-xs btn-info">Печать</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links();?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
