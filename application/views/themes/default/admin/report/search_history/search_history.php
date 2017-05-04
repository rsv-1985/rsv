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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
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
                        <?php if($search_history){?>
                            <?php foreach($search_history as $sh){?>
                                <tr>
                                    <td><?php echo $sh['customer'];?></td>
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
