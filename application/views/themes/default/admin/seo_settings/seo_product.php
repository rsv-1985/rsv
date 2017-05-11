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
            <small>товара</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li><a href="/autoxadmin/seo_settings">SEO настройки</a></li>
            <li class="active">товара</li>
        </ol>
    </section>
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">SEO шаблон товара</h3>
                <p class="label label-info pull-right">
                    Если в товаре явно не указаны SEO данные, они будут генирироваться по шаблону.
                </p>
            </div>
            <div class="box-body">

                <div class="form-group">
                    <label>SEO url</label>
                    <input placeholder="{sku}-{brand}-{name}" class="form-control" type="text" name="seo_url_template" value="<?php echo $seo_url_template;?>">
                    <p class="help-block">
                        Шаблон генирации url для товара<br>
                    </p>
                    <p class="help-block">
                        {sku} - артикул товара<br>
                        {brand} - производитель <br>
                        {name} - название <br>
                        {description} - описание<br>
                        {excerpt} - доп. информация
                    </p>
                </div>

                <div class="form-group">
                    <label>SEO title</label>
                    <input type="text" name="seo_product[title]" value="<?php echo set_value('title',@$seo_product['title']);?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>SEO description</label>
                    <input type="text" name="seo_product[description]" value="<?php echo set_value('title',@$seo_product['description']);?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>SEO keywords</label>
                    <input type="text" name="seo_product[keywords]" value="<?php echo set_value('title',@$seo_product['keywords']);?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>SEO h1</label>
                    <input type="text" name="seo_product[h1]" value="<?php echo set_value('title',@$seo_product['h1']);?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>SEO text</label>
                    <textarea class="textarea" name="seo_product[text]"><?php echo set_value('title',@$seo_product['text']);?></textarea>
                </div>
                <p class="help-block">
                    {name} - название<br>
                    {brand} - производитель<br>
                    {sku} - артикул<br>
                    {description} - описание
                </p>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Сохранить</button>
            </div>
        </div>
    </section>
</form>
