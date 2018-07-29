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
                    <h3 class="box-title">VIN запросы</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th>ID</th>
                            <th>VIN</th>
                            <th>Клиент</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                        <?php echo form_open('/autoxadmin/vin',['method' => 'get']);?>
                        <tr>
                            <td></td>
                            <td>
                                <input type="text" name="vin" class="form-control" value="<?php echo $this->input->get('vin',true);?>">
                            </td>
                            <td>
                                <input type="text" name="name" class="form-control" value="<?php echo $this->input->get('name',true);?>">
                            </td>
                            <td></td>
                            <td>
                                <a href="/autoxadmin/vin" class="btn btn-danger">Сборс</a>
                                <button type="submit" class="btn btn-info">Фильтр</button>
                            </td>
                        </tr>
                        </form>
                        <?php if($vins){?>
                            <?php foreach($vins as $vin){?>
                                <tr>
                                    <td><?php echo $vin['id'];?></td>
                                    <td><?php echo $vin['vin'];?></td>
                                    <td><?php if($vin['customer_name']){?>
                                            <a href="/autoxadmin/customer/edit/<?php echo $vin['cid'];?>"><?php echo $vin['customer_name'];?></a>
                                        <?php }else{?>
                                            <?php echo $vin['name'];?>
                                        <?php } ;?>
                                    </td>
                                    <td><?php echo $vin['status'] ? 'Обработан' : 'Новый';?></td>
                                    <td>
                                        <a href="/autoxadmin/vin/edit/<?php echo $vin['id'];?>">Просмотр</a>
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
