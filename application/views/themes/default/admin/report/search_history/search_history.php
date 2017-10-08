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
                    <a href="/autoxadmin/report/search_history/delete" class="btn btn-danger pull-right">Очистить историю</a>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th><?php echo lang('text_customer');?></th>
                            <th><?php echo lang('text_search_history_sku');?></th>
                            <th><?php echo lang('text_search_history_brand');?></th>
                            <th><?php echo lang('text_search_history_created_at');?></th>
                        </tr>
                        <?php echo form_open('/autoxadmin/report/search_history',['method' => 'get']);?>
                        <tr>
                            <td>
                                <input type="text" name="name" class="form-control" value="<?php echo $this->input->get('name',true);?>">
                            </td>
                            <td>
                                <input type="text" name="sku" class="form-control" value="<?php echo $this->input->get('sku',true);?>">
                            </td>
                            <td>
                                <input type="text" name="brand" class="form-control" value="<?php echo $this->input->get('brand',true);?>">
                            </td>
                            <td>
                                <a href="/autoxadmin/report/search_history" class="btn btn-danger">Сборс</a>
                                <button type="submit" class="btn btn-info">Фильтр</button>
                            </td>
                        </tr>
                        </form>
                        <?php if($search_history){?>
                            <?php foreach($search_history as $sh){?>
                                <tr>
                                    <td><?php if($sh['login']){?>
                                            <a href="/autoxadmin/customer/edit/<?php echo $sh['cid'];?>"><?php echo $sh['login'];?></a>
                                        <?php }else{?>
                                            Незарегистрированный
                                        <?php } ;?>
                                    </td>
                                    <td><?php echo $sh['sku'];?></td>
                                    <td><?php echo $sh['brand'];?></td>
                                    <td><?php echo $sh['created_at'];?></td>
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
