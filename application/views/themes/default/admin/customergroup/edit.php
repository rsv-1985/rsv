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
        <li><a href="/autoxadmin/customergroup"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $customergroup['name'];?>;?></a></li>
    </ol>
</section>
<?php echo form_open();?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label><?php echo lang('text_name'); ?></label>
                        <input required type="text" class="form-control" name="name" value="<?php echo set_value('name', $customergroup['name']); ?>" maxlength="32">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_type'); ?></label>
                        <select name="type" class="form-control">
                            <?php foreach($types as $key => $value){?>
                                <option value="<?php echo $key;?>" <?php echo set_select('type', $key, $customergroup['type']==$key);?>><?php echo $value;?></option>
                            <?php } ?>
                        </select>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_value'); ?></label>
                        <input type="number" class="form-control" name="value" value="<?php echo set_value('value', $customergroup['value']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label><?php echo lang('text_fix_value'); ?></label>
                        <input type="text" class="form-control" name="fix_value" value="<?php echo set_value('fix_value', $customergroup['fix_value']); ?>">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_default" value="1" <?php echo set_checkbox('is_default', 1, (bool)$customergroup['is_default']);?>>
                                <?php echo lang('text_use_default');?>
                            </label>
                            <label>
                                <input type="checkbox" name="is_unregistered" value="1" <?php echo set_checkbox('is_unregistered', 1, (bool)$customergroup['is_unregistered']);?>>
                                <?php echo lang('text_use_all');?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info pull-right"><?php echo lang('button_submit');?></button>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Ценообразование</h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Цена от</th>
                            <th>Цена до</th>
                            <th>Производитель</th>
                            <th>Метод</th>
                            <th>Процент</th>
                            <th>Фиксированная сумма</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="pricing">

                        <?php if (!empty($pricing)) { ?>
                            <?php $q = 0; ?>
                            <?php foreach ($pricing as $price) { ?>
                                <tr id="row<?php echo $q; ?>">
                                    <td></td>
                                    <td><input type="text" name="pricing[<?php echo $q; ?>][price_from]"
                                               value="<?php echo $price['price_from']; ?>" class="form-control"></td>
                                    <td><input type="text" name="pricing[<?php echo $q; ?>][price_to]"
                                               value="<?php echo $price['price_to']; ?>" class="form-control"></td>
                                    <td><input type="text" name="pricing[<?php echo $q; ?>][brand]"
                                               value="<?php echo $price['brand']; ?>" class="form-control"></td>
                                    <td>
                                        <select name="pricing[<?php echo $q; ?>][method_price]" class="form-control">
                                            <option value="+"
                                                    <?php if ($price['method_price'] == '+'){ ?>selected<?php } ?>>Наценка</option>
                                            <option value="-"
                                                    <?php if ($price['method_price'] == '-'){ ?>selected<?php } ?>>Скидка</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="pricing[<?php echo $q; ?>][value]"
                                               value="<?php echo $price['value']; ?>" class="form-control"></td>
                                    <td><input type="text" name="pricing[<?php echo $q; ?>][fix_value]"
                                               value="<?php echo $price['fix_value']; ?>" class="form-control"></td>
                                    <td>
                                        <a href="#" class="btn btn-danger"
                                           onclick="delete_row(<?php echo $q; ?>, event);"><?php echo lang('button_delete'); ?></a>
                                    </td>
                                </tr>
                                <?php $q++;
                            } ?>
                        <?php } else { ?>
                            <?php $q = 1; ?>
                            <tr id="row0">
                                <td></td>
                                <td><input type="text" name="pricing[0][price_from]" class="form-control"></td>
                                <td><input type="text" name="pricing[0][price_to]" class="form-control"></td>
                                <td><input type="text" name="pricing[0][brand]" class="form-control"></td>
                                <td>
                                    <select name="pricing[0][method_price]" class="form-control">
                                        <option value="+">Наценка</option>
                                        <option value="-">Скидка</option>
                                    </select>
                                </td>
                                <td><input type="text" name="pricing[0][value]" class="form-control"></td>
                                <td><input type="text" name="pricing[0][fix_value]" class="form-control"></td>
                                <td>
                                    <a href="#" class="btn btn-danger"
                                       onclick="delete_row(0, event);"><?php echo lang('button_delete'); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <br>
                    <a href="#" class="btn btn-info pull-right" onclick="add_row(event);">Добавить</a>
                </div><!-- /.box-body -->
            </div>
        </div>
    </div>
</section><!-- /.content -->
</form>
<script>
    var row = <?php echo $q;?>

    function add_row(event){
        event.preventDefault();
        html = '';
        html += '<tr id="row'+row+'">';
        html += '<td></td>';
        html += '<td><input type="text" name="pricing['+row+'][price_from]" class="form-control"></td>';
        html += '<td><input type="text" name="pricing['+row+'][price_to]" class="form-control"></td>';
        html += '<td><input type="text" name="pricing['+row+'][brand]" class="form-control"></td>';
        html += '<td>';
        html += '<select name="pricing['+row+'][method_price]" class="form-control">';
        html += '<option value="+">Наценка></option>';
        html += '<option value="-">Скидка</option>';
        html += '</select>';
        html += '</td>';
        html += '<td><input type="text" name="pricing['+row+'][value]" class="form-control"></td>';
        html += '<td><input type="text" name="pricing['+row+'][fix_value]" value="" class="form-control"></td>'
        html += '<td>';
        html += '<a href="#" class="btn btn-danger" onclick="delete_row('+row+', event);"><?php echo lang('button_delete');?></a>';
        html += '</td>';
        html += '</tr>';

        $("#pricing").append(html);
        row++;
    }

    function delete_row(id, event){
        event.preventDefault();
        $("#row"+id).remove();
    }
</script>