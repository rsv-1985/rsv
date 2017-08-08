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
        <small>Производители</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="/autoxadmin/tecdoc_settings">tecdoc настройки</a></li>
        <li class="active">Производители</li>
    </ol>
</section>
<section class="content">
    <?php echo form_open();?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Производители</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Свое название</th>
                            <th>Логотип</th>
                            <th>Сортировка</th>
                            <th>Отображать</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($manufacturers as $manufacturer){?>
                        <tr>
                            <td><?php echo $manufacturer->Name;?></td>
                            <td><input class="form-control" type="text" name="tecdoc_manufacturer[<?php echo url_title($manufacturer->Name);?>][name]" value="<?php echo @$tecdoc_manufacturer[url_title($manufacturer->Name)]['name'];?>"></td>
                            <td><input class="form-control" placeholder="/uploads/model/AUDI.png" type="text" name="tecdoc_manufacturer[<?php echo url_title($manufacturer->Name);?>][logo]" value="<?php echo @$tecdoc_manufacturer[url_title($manufacturer->Name)]['logo'];?>"></td>
                            <td><input class="form-control" placeholder="100" type="text" name="tecdoc_manufacturer[<?php echo url_title($manufacturer->Name);?>][sort]" value="<?php echo @$tecdoc_manufacturer[url_title($manufacturer->Name)]['sort'];?>"></td>
                            <td><input type="checkbox" name="tecdoc_manufacturer[<?php echo url_title($manufacturer->Name);?>][status]" value="1" <?php if(@$tecdoc_manufacturer[url_title($manufacturer->Name)]['status']){?>checked<?php } ?>></td>
                        </tr>
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

