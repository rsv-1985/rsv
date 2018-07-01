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
                            <th style="width: 10px">
                                <a href="" class="sort" data-sort="id">
                                    #
                                </a>
                            </th>
                            <th>
                                <a href="" class="sort" data-sort="name">
                                <?php echo lang('text_name');?>
                                </a>
                            </th>
                            <th>
                                <a href="" class="sort" data-sort="stock">
                                <?php echo lang('text_stock');?>
                                </a>
                            </th>
                            <th>
                                <a href="" class="sort" data-sort="updated_at">
                                    <?php echo lang('text_statistics_updated_at');?>
                                </a>
                            </th>
                            <th><a href="/autoxadmin/supplier/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                        </tr>
                        <?php echo form_open('/autoxadmin/supplier',['method' => 'get', 'id' => 'filter-form']);?>
                        <input type="hidden" name="sort" value="<?php echo $this->input->get('sort');?>">
                        <input type="hidden" name="order" value="<?php echo $this->input->get('order');?>">
                        <tr>
                            <td>
                                <input type="text" name="id" value="<?php echo $this->input->get('id',true);?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="name" value="<?php echo $this->input->get('name',true);?>" class="form-control">
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <?php if($this->input->get()){?>
                                            <a href="/autoxadmin/supplier" class="btn btn-default" title="<?php echo lang('button_reset');?>"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                        <?php } ?>
                                        <button type="submit" class="btn btn btn-info" title="<?php echo lang('button_search');?>"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php form_close();?>
                        <?php if($supplieres){?>
                            <?php foreach($supplieres as $supplier){?>
                                <tr>
                                    <td><?php echo $supplier['id'];?></td>
                                    <td><?php echo $supplier['name'];?></td>
                                    <td style="text-align: center; width:90px;">
                                        <?php if ($supplier['stock'] >= 1){?>
                                            <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $supplier['updated_at'];?></td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/supplier/delete/<?php echo $supplier['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                            <a href="/autoxadmin/supplier/edit/<?php echo $supplier['id'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
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
<script>
    $(document).ready(function(){
        $(".sort").click(function(e){
            e.preventDefault();
            var sort = $(this).attr('data-sort');
            $("[name='sort']").val(sort);
            if($("[name='order']").val() == 'ASC'){
                $("[name='order']").val('DESC');
            }else{
                $("[name='order']").val('ASC');
            }

            $("#filter-form").submit();
        })
    })
</script>
