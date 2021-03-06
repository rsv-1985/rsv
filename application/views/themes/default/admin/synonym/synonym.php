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
                            <th><?php echo lang('text_brand1');?></th>
                            <th><?php echo lang('text_brand2');?></th>
                            <th><a href="/autoxadmin/synonym/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                        </tr>
                        <?php if($synonymes){?>
                            <?php echo form_open('/autoxadmin/synonym', ['method' => 'get']);?>
                            <tr>
                                <td>
                                    <input type="text" name="id" value="<?php echo $this->input->get('id');?>" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="brand1" value="<?php echo $this->input->get('brand1');?>" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="brand2" value="<?php echo $this->input->get('brand2');?>" class="form-control">
                                </td>
                                <td>
                                    <div class="btn-group pull-right">
                                        <?php if($this->input->get()){?>
                                            <a href="/autoxadmin/synonym" class="btn btn-default" title="Сброс"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                        <?php } ?>
                                        <button type="submit" class="btn btn btn-info" title="Поиск"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php echo form_close();?>
                            <?php foreach($synonymes as $synonym){?>
                                <tr>
                                    <td><?php echo $synonym['id'];?></td>
                                    <td><?php echo $synonym['brand1'];?></td>
                                    <td><?php echo $synonym['brand2'];?></td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/synonym/delete/<?php echo $synonym['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                            <a href="/autoxadmin/synonym/edit/<?php echo $synonym['id'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
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
