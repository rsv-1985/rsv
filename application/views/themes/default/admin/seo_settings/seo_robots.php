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
        SEO настройки
        <small>robots.txt</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="/autoxadmin/seo_settings">SEO настройки</a></li>
        <li class="active">robots.txt</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">SEO шаблон товара</h3>
        </div>
        <div class="box-body">

            <div class="form-group">
                <label>Robots.txt</label>
                <textarea rows="10" class="form-control" name="robots"><?php echo @$robots;?></textarea>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Сохранить</button>
        </div>
    </div>
</section>
</form>
