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
                            <th><?php echo lang('text_type');?></th>
                            <th><?php echo lang('text_value');?></th>
                            <th><?php echo lang('text_fix_value');?></th>
                            <th><?php echo lang('text_use_default');?></th>
                            <th><?php echo lang('text_use_all');?></th>
                            <th><a href="/autoxadmin/customergroup/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                        </tr>
                        <?php if($customergroupes){?>
                            <?php foreach($customergroupes as $customergroup){?>
                                <tr>
                                    <td><?php echo $customergroup['id'];?></td>
                                    <td><?php echo $customergroup['name'];?></td>
                                    <td><?php echo $types[$customergroup['type']];?></td>
                                    <td><?php echo $customergroup['value'];?></td>
                                    <td><?php echo $customergroup['fix_value'];?></td>
                                    <td>
                                        <?php if ($customergroup['is_default']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($customergroup['is_unregistered']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/customergroup/delete/<?php echo $customergroup['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                            <a href="/autoxadmin/customergroup/edit/<?php echo $customergroup['id'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
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
