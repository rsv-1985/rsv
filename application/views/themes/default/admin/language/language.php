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
                    <div class="pull-right">
                        <a href="/autoxadmin/language/truncate" class="btn btn-danger confirm">Сбросить все переводы</a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">

                        <thead>
                        <tr>
                            <th><?php echo lang('text_language_line');?></th>
                            <th><?php echo lang('text_language_text');?></th>
                            <th></th>
                        </tr>
                        <?php echo form_open('/autoxadmin/language',['method' => 'get']);?>
                            <tr>
                                <td>
                                    <input type="text" name="filter_key" value="<?php echo $this->input->get('filter_key',true);?>" class="form-control">
                                </td>
                                <td>
                                    <input placeholder="<?php echo lang('text_language_filter_placeholder');?>" value="<?php echo $this->input->get('filter_text',true);?>" type="text" name="filter_text" class="form-control">
                                </td>
                                <td>
                                    <?php if($this->input->get()){?>
                                        <a href="/autoxadmin/language" class="btn btn-danger">Сброс</a>
                                    <?php } ?>
                                    <button type="submit" class="btn btn-info">Фильтр</button>
                                </td>
                            </tr>
                        </form>
                        </thead>
                        <tbody>
                        <?php if($languages){?>
                            <?php foreach($languages as $language){?>
                                <tr>
                                    <td><?php echo $language['line'];?></td>
                                    <td>
                                        <input onkeyup="$('#button<?php echo $language['id'];?>').show();" id="text<?php echo $language['id'];?>" class="form-control" type="text" value="<?php echo html_escape($language['text']);?>">
                                        <button onclick="save_text('<?php echo $language['id'];?>');" style="display: none;" id="button<?php echo $language['id'];?>" class="btn btn-default pull-right">Сохранить</button>
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
    function save_text(text_id) {
        var text = $("#text"+text_id).val();

            $.ajax({
                url: '/autoxadmin/language/update',
                data: {id:text_id,text:text},
                method: 'post',
                success: function(response){
                    $("#button"+text_id).hide();
                    alert(response);
                }
            })

    }
</script>