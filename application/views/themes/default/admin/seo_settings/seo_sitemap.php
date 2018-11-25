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
<?php echo form_open('', ['method' => 'post', 'id' => 'seo_map']);?>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Sitemap</h3>
            <b class="pull-right">
                Ссылка на карту сайта: <a href="<?php echo base_url('map/sitemap.xml'); ?>"><?php echo base_url('map/sitemap.xml'); ?></a>
            </b>
        </div>
        <div class="box-body">

            <div class="row">
                <div class="col-md-4">
                    <label>Главная priopity</label>
                    <input value="<?php echo @$seo_map['home_priopity'];?>" type="text" name="seo_map[home_priopity]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Главная changefreq</label>
                    <input value="<?php echo @$seo_map['home_changefreq'];?>" type="text" name="seo_map[home_changefreq]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Главная выгружать</label>
                    <select name="seo_map[home_status]" class="form-control">
                        <option value="1" <?php if(@$seo_map['home_status']){?>selected<?php } ?>>Да</option>
                        <option value="0" <?php if(!@$seo_map['home_status']){?>selected<?php } ?>>Нет</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Страницы priopity</label>
                    <input value="<?php echo @$seo_map['page_priopity'];?>" type="text" name="seo_map[page_priopity]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Страницы changefreq</label>
                    <input value="<?php echo @$seo_map['page_changefreq'];?>" type="text" name="seo_map[page_changefreq]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Страницы выгружать</label>
                    <select name="seo_map[page_status]" class="form-control">
                        <option value="1" <?php if(@$seo_map['page_status']){?>selected<?php } ?>>Да</option>
                        <option value="0" <?php if(!@$seo_map['page_status']){?>selected<?php } ?>>Нет</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Новости priopity</label>
                    <input value="<?php echo @$seo_map['news_priopity'];?>" type="text" name="seo_map[news_priopity]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Новости changefreq</label>
                    <input value="<?php echo @$seo_map['news_changefreq'];?>" type="text" name="seo_map[news_changefreq]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Новости выгружать</label>
                    <select name="seo_map[news_status]" class="form-control">
                        <option value="1" <?php if(@$seo_map['news_status']){?>selected<?php } ?>>Да</option>
                        <option value="0" <?php if(!@$seo_map['news_status']){?>selected<?php } ?>>Нет</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Свои категории priopity</label>
                    <input value="<?php echo @$seo_map['category_priopity'];?>"  type="text" name="seo_map[category_priopity]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Свои категории changefreq</label>
                    <input value="<?php echo @$seo_map['category_changefreq'];?>" type="text" name="seo_map[category_changefreq]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Свои категории выгружать</label>
                    <select name="seo_map[category_status]" class="form-control">
                        <option value="1"  <?php if(@$seo_map['category_status']){?>selected<?php } ?>>Да</option>
                        <option value="0" <?php if(!@$seo_map['category_status']){?>selected<?php } ?>>Нет</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Товары priopity</label>
                    <input  value="<?php echo @$seo_map['product_priopity'];?>" type="text" name="seo_map[product_priopity]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Товары changefreq</label>
                    <input value="<?php echo @$seo_map['product_changefreq'];?>" type="text" name="seo_map[product_changefreq]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Товары выгружать</label>
                    <select name="seo_map[product_status]" class="form-control">
                        <option value="1" <?php if(@$seo_map['product_status']){?>selected<?php } ?>>Да</option>
                        <option value="0" <?php if(!@$seo_map['product_status']){?>selected<?php } ?>>Нет</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Марки и модели priopity</label>
                    <input  value="<?php echo @$seo_map['td_priopity'];?>" type="text" name="seo_map[td_priopity]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Марки и модели changefreq</label>
                    <input value="<?php echo @$seo_map['td_changefreq'];?>" type="text" name="seo_map[td_changefreq]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Марки и модели выгружать</label>
                    <select name="seo_map[td_status]" class="form-control">
                        <option value="1" <?php if(@$seo_map['td_status']){?>selected<?php } ?>>Да</option>
                        <option value="0" <?php if(!@$seo_map['td_status']){?>selected<?php } ?>>Нет</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-info"><?php echo lang('button_submit');?></button>
            <a onclick="$('#seo_map').submit()" class="btn btn-info pull-right" href="<?php echo base_url('sitemap'); ?>">Сгенирировать новую</a>
        </div>
    </div>
</section>
<?php echo form_close();?>
