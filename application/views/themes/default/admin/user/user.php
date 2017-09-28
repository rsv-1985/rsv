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
                        <thead><tr>
                            <th><?php echo lang('text_firstname');?></th>
                            <th><?php echo lang('text_lastname');?></th>
                            <th><?php echo lang('text_email');?></th>
                            <th><a href="/autoxadmin/user/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($users){?>
                            <?php foreach($users as $user){?>
                                <tr>
                                    <td><?php echo $user['firstname'];?></td>
                                    <td><?php echo $user['lastname'];?></td>
                                    <td><?php echo $user['email'];?></td>
                                    <td style="width: 190px;">
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/user/delete/<?php echo $user['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                            <a href="/autoxadmin/user/edit/<?php echo $user['id'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
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
