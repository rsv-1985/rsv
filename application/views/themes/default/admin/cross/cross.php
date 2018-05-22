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
                            <th>sku 1</th>
                            <th>brand 1</th>
                            <th>sku 2</th>
                            <th>brand 2</th>
                            <th>
                                <div class="btn-group pull-right">
                                    <a href="/autoxadmin/cross/delete_all" class="btn btn-danger confirm"><?php echo lang('button_delete_all');?></a>
                                    <a href="/autoxadmin/cross/create" class="btn btn-info"><?php echo lang('button_add');?></a>
                                </div>
                            </th>
                        </tr>
                        <?php echo form_open('/autoxadmin/cross',['id' => 'filter-form', 'method' => 'get']);?>
                            <tr>
                                <td>
                                    <input type="text" name="code" class="form-control" value="<?php echo $this->input->get('code');?>">
                                </td>
                                <td>
                                    <input type="text" name="brand" class="form-control" value="<?php echo $this->input->get('brand');?>">
                                </td>
                                <td>
                                    <input type="text" name="code2" class="form-control" value="<?php echo $this->input->get('code2');?>">
                                </td>
                                <td>
                                    <input type="text" name="brand2" class="form-control" value="<?php echo $this->input->get('brand2');?>">
                                </td>
                                <td>
                                    <div class="btn-group pull-right">
                                        <?php if($this->input->get()){?>
                                            <a href="#" onclick="delete_filter(event)" class="btn btn-danger" title="Удалить по фильтру"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                            <a href="/autoxadmin/cross" class="btn btn-default" title="Сброс"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                        <?php } ?>
                                        <button type="submit" class="btn btn btn-info" title="Поиск"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php echo form_close();?>
                        <?php if($crosses){?>
                            <?php foreach($crosses as $cross){?>
                                <tr>
                                    <td><?php echo $cross['code'];?></td>
                                    <td><?php echo$cross['brand'];?></td>
                                    <td><?php echo $cross['code2'];?></td>
                                    <td><?php echo$cross['brand2'];?></td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/cross/delete/<?php echo $cross['id'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
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
    function delete_filter(e){
        e.preventDefault();
        if(confirm('Продолжить удаление?')){
            $.ajax({
                url:'/autoxadmin/cross/delete_by_filter',
                data: $("#filter-form").serialize(),
                method:'get',
                success:function (response) {
                    alert(response);
                }
            });
        }
    }
</script>
