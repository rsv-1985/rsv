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
                        <tbody>
                        <tr>
                            <th>Email</th>
                            <th>
                                <div class="btn-group pull-right">
                                    <a href="/autoxadmin/newsletter/delete_all" class="btn btn-danger"><?php echo lang('button_delete_all');?></a>
                                    <a href="/autoxadmin/newsletter/export" class="btn btn-info">Export CSV</a>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <?php echo form_open('/autoxadmin/newsletter', ['method' => 'get']);?>
                            <td><input placeholder="email" type="text" name="email" class="form-control" value="<?php echo $this->input->get('email', true);?>"/> </td>
                            <td>
                                <div class="pull-right">
                                    <button class="btn btn-link" type="submit"><?php echo lang('button_search');?></button>
                                    <a href="/autoxadmin/newsletter"><?php echo lang('button_reset');?></a>
                                </div>
                            </td>
                            </form>
                        </tr>
                        <?php if($newsletteres){?>
                            <?php foreach($newsletteres as $newsletter){?>
                                <tr>
                                    <td><?php echo $newsletter['email'];?></td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/newsletter/delete/<?php echo $newsletter['id'];?>" type="button" class="btn btn-link confirm"><?php echo lang('button_delete');?></a>
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
