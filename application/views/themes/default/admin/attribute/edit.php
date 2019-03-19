<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="/autoxadmin/attribute"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $attribute['name'];?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open();?>
            <div class="box">
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Название опции</label>
                            <input type="text" class="form-control" name="name" value="<?php echo set_value('name', $attribute['name']); ?>" maxlength="255">
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <label>Высота блока в фильтре</label>
                            <input type="text" class="form-control" name="max_height" value="<?php echo set_value('max_height', $attribute['max_height']); ?>">
                        </div><!-- /.form group -->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Сортировка</label>
                            <input type="text" name="sort_order" class="form-control" value="<?php echo set_value('sort_order', (int)$attribute['sort_order']);?>">
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input name="in_filter" type="checkbox" value="1" <?php echo set_checkbox('in_filter', 1, (bool)$attribute['in_filter']);?>>
                                    Отображать в фильтре
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="in_short_description" type="checkbox" value="1" <?php echo set_checkbox('in_short_description', 1, (bool)$attribute['in_short_description']);?>>
                                    Отображать в кратком описании в категории
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">

                        <table class="table table-condensed table-striped">
                            <thead>
                            <tr>
                                <th>Значение опции</th>
                                <th>Сортировка</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $q = 0; if($values){?>
                                <?php  foreach ($values as $value){?>
                                    <tr id="row<?php echo $q;?>">
                                        <td>
                                            <input type="hidden" name="attributes[<?php echo $q;?>][id]" value="<?php echo $value['id'];?>">
                                            <input type="text" name="attributes[<?php echo $q;?>][value]" value="<?php echo $value['value'];?>" class="form-control">
                                        </td>
                                        <td>
                                            <input name="attributes[<?php echo $q;?>][sort_order]" type="text" value="<?php echo $value['sort_order'];?>" class="form-control">
                                        </td>
                                        <td class="text-right">
                                            <a class="btn btn-danger" onclick="deleteValue(<?php echo $value['id'];?>, <?php echo $q;?>, event)" href="#">Удалить</a>
                                        </td>
                                    </tr>
                                    <?php $q++; } ?>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <td class="text-right">
                                    <button class="btn btn-default" onclick="addAttr(event)">Добавить</button>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
        <?php echo form_close();?>
    </div>
</section><!-- /.content -->
<script>
    var attr_id = <?php echo $q;?>;
    function deleteValue(id, row_id, e) {
        e.preventDefault();
        $.get('/autoxadmin/attribute/delete_value/'+id,function (response) {
           $("#row"+row_id).remove();
        })
    }

    function addAttr(e) {
        e.preventDefault();

        var html = '';
        html += '<tr id="row'+attr_id+'">';
        html += '<td>';
        html += '<input type="text" name="attributes['+attr_id+'][value]" class="form-control">';
        html += '</td>';
        html += '<td>';
        html += '<input name="attributes['+attr_id+'][sort_order]" type="text" class="form-control">';
        html += '</td>';
        html += '<td class="text-right">';
        html += '<a class="btn btn-danger" onclick="$(\'#row'+attr_id+'\').remove(); return false;" href="#">Удалить</a>';
        html += '</td>';
        html += '</tr>';

        $('tbody').append(html);
        attr_id++;
    }
</script>