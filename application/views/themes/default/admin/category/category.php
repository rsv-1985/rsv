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
                            <th><?php echo lang('text_sort');?></th>
                            <th><?php echo lang('text_status');?></th>
                            <th><a href="/autoxadmin/category/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                        </tr>
                        <?php echo form_open('/autoxadmin/category', ['method' => 'get', 'id' => 'filter-form']);?>
                        <tr>
                            <td>
                                <input type="text" name="id" value="<?php echo $this->input->get('id',true);?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="name" value="<?php echo $this->input->get('name',true);?>" class="form-control">
                            </td>
                            <td>

                            </td>
                            <td>
                                <select name="status" class="form-control">
                                    <option></option>
                                    <option value="1" <?php if($this->input->get('status',true) == 1){?>selected<?php } ?>><?php echo lang('text_status');?></option>
                                    <option value="0" <?php if($this->input->get('status',true) === '0'){?>selected<?php } ?>>Отключен</option>
                                </select>
                            </td>
                            <td>
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <?php if($this->input->get()){?>
                                            <a href="/autoxadmin/category" class="btn btn-default" title="<?php echo lang('button_reset');?>"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                        <?php } ?>
                                        <button type="submit" class="btn btn btn-info" title="<?php echo lang('button_search');?>"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php echo form_close();?>
                        <?php if($categorys){?>
                            <?php foreach($categorys as $category){?>
                                <tr>
                                    <td><?php echo $category['id'];?></td>
                                    <td><?php echo $category['name'];?></td>
                                    <td><?php echo $category['sort'];?></td>
                                    <td>
                                        <?php if ($category['status']){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/category/delete/<?php echo $category['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                            <a href="/autoxadmin/category/edit/<?php echo $category['id'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
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
