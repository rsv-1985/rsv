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
        <small>sitemap</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="/autoxadmin/seo_settings">SEO настройки</a></li>
        <li class="active">sitemap</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Sitemap</h3>
        </div>
        <div class="box-body">
            Ссылка на карту сайта: <a href="<?php echo base_url('map/sitemap.xml'); ?>"><?php echo base_url('map/sitemap.xml'); ?></a>
            <br>

        </div>
        <div class="box-footer">
            <a class="btn btn-info pull-right" href="<?php echo base_url('sitemap'); ?>">Сгенирировать новую</a>
        </div>
    </div>
</section>
</form>
