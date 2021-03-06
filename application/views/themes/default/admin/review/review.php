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
                        <tbody><tr>
                            <th style="width: 10px">#</th>
                            <th>Автор</th>
                            <th>Рейтинг</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                        <?php if($reviews){?>
                            <?php foreach($reviews as $review){?>
                                <tr>
                                    <td><?php echo $review['id'];?></td>
                                    <td><?php echo $review['author'];?></td>
                                    <td><?php echo $review['rating'];?></td>
                                    <td><?php echo $review['status'] ? 'Отображается' : 'Новый';?></td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/review/delete/<?php echo $review['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                            <a href="/autoxadmin/review/edit/<?php echo $review['id'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
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
