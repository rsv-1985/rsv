<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php echo form_open();?>

<section class="content-header">
    <h1>
        Tecdoc настройки
        <small>Категории</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="/autoxadmin/tecdoc_settings">tecdoc настройки</a></li>
        <li class="active">Категории</li>
    </ol>
</section>
<section class="content">
    <?php echo form_open();?>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Категории</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Уровень</th>
                    <th>Название</th>
                    <th>Свое название</th>
                    <th>Логотип</th>
                    <th>Отображать в экспресс навигации</th>
                    <th>Отбражать на главной</th>
                    <th>Скрыть</th>
                </tr>
                </thead>
                <tbody>
                    <?php if($trees){?>
                        <?php foreach ($trees as $item){?>
                            <?php if($item->Level > 0){ ?>
                            <tr>
                                <td><?php echo $item->Level;?></td>
                                <td><?php echo $item->Name;?></td>
                                <td><input type="text" class="form-control"  name="tecdoc_tree[<?php echo $item->ID_tree;?>][name]" value="<?php echo @$settings_tecdoc_tree[$item->ID_tree]['name'];?>"></td>
                                <td><input type="text" class="form-control"  name="tecdoc_tree[<?php echo $item->ID_tree;?>][logo]" value="<?php echo @$settings_tecdoc_tree[$item->ID_tree]['logo'];?>"></td>
                                <td><input type="checkbox" name="tecdoc_tree[<?php echo $item->ID_tree;?>][express]" value="1" <?php echo (@$settings_tecdoc_tree[$item->ID_tree]['express'] ? 'checked' :'');?>></td>
                                <td><input type="checkbox" name="tecdoc_tree[<?php echo $item->ID_tree;?>][home]" value="1" <?php echo (@$settings_tecdoc_tree[$item->ID_tree]['home'] ? 'checked' :'');?>></td>
                                <td><input type="checkbox" name="tecdoc_tree[<?php echo $item->ID_tree;?>][hide]" value="1" <?php echo (@$settings_tecdoc_tree[$item->ID_tree]['hide'] ? 'checked' :'');?>></td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Сохранить</button>
        </div>
    </div>
    </form>
</section>
</form>

