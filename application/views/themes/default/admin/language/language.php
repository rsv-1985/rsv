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
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <td></td>
                                <td>
                                    <?php echo form_open('/autoxadmin/language',['method' => 'get']);?>
                                        <input placeholder="<?php echo lang('text_language_filter_placeholder');?>" type="text" name="search" class="form-control">
                                    </form>
                                </td>
                            </tr>
                        </thead>

                        <tbody><tr>
                            <th><?php echo lang('text_language_line');?></th>
                            <th><?php echo lang('text_language_text');?></th>
                        </tr>
                        <?php if($languages){?>
                            <?php foreach($languages as $language){?>
                                <tr>
                                    <td><?php echo $language['line'];?></td>
                                    <td>
                                        <input onkeyup="$('#button<?php echo $language['id'];?>').show();" id="text<?php echo $language['id'];?>" class="form-control" type="text" value="<?php echo $language['text'];?>">
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
        if(text.length > 0){
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
    }
</script>