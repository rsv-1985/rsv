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
                            <th><?php echo lang('text_name');?></th>
                            <th><?php echo lang('text_show_slider');?></th>
                            <th><?php echo lang('text_show_box');?></th>
                            <th><?php echo lang('text_show_carousel');?></th>
                            <th><?php echo lang('text_show_product');?></th>
                            <th><?php echo lang('text_status');?></th>
                            <th><a href="/autoxadmin/banner/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                        </tr>
                        <?php if($banners){?>
                            <?php foreach($banners as $banner){?>
                                <tr>
                                    <td><?php echo $banner['id'];?></td>
                                    <td><?php echo $banner['name'];?></td>
                                    <td style="text-align: center; width:90px;">
                                        <?php if ($banner['show_slider']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td style="text-align: center; width:90px;">
                                        <?php if ($banner['show_box']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td style="text-align: center; width:90px;">
                                        <?php if ($banner['show_carousel']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td style="text-align: center; width:90px;">
                                        <?php if ($banner['show_product']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td style="text-align: center; width:90px;">
                                        <?php if ($banner['status']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/banner/delete/<?php echo $banner['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                            <a href="/autoxadmin/banner/edit/<?php echo $banner['id'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
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
