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
        <small>tecdoc производители</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="/autoxadmin/seo_settings">SEO настройки</a></li>
        <li class="active">tecdoc производители</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="row">
            <div class="col-md-6">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO шаблон tecdoc производители</h3>
                </div>
                <div class="box-body">

                    <div class="form-group">
                        <label>SEO title</label>
                        <input type="text" name="seo_tecdoc_manufacturer[title]" value="<?php echo $seo_tecdoc_manufacturer['title'];?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO Description</label>
                        <input type="text" name="seo_tecdoc_manufacturer[description]" value="<?php echo $seo_tecdoc_manufacturer['description'];?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO keywords</label>
                        <input type="text" name="seo_tecdoc_manufacturer[keywords]" value="<?php echo $seo_tecdoc_manufacturer['keywords'];?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO h1</label>
                        <input type="text" name="seo_tecdoc_manufacturer[h1]" value="<?php echo $seo_tecdoc_manufacturer['h1'];?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO text</label>
                        <textarea class="textarea" name="seo_tecdoc_manufacturer[text]"><?php echo set_value('text',@$seo_tecdoc_manufacturer['text']);?></textarea>
                    </div>
                    <p class="help-block">
                        {manuf} - Название производителя<br>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO шаблон tecdoc производители c выбранной категорией</h3>
                </div>
                <div class="box-body">

                    <div class="form-group">
                        <label>SEO title</label>
                        <input type="text" name="seo_tecdoc_manufacturer_with_tree[title]" value="<?php echo $seo_tecdoc_manufacturer_with_tree['title'];?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO Description</label>
                        <input type="text" name="seo_tecdoc_manufacturer_with_tree[description]" value="<?php echo $seo_tecdoc_manufacturer_with_tree['description'];?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO keywords</label>
                        <input type="text" name="seo_tecdoc_manufacturer_with_tree[keywords]" value="<?php echo $seo_tecdoc_manufacturer_with_tree['keywords'];?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO h1</label>
                        <input type="text" name="seo_tecdoc_manufacturer_with_tree[h1]" value="<?php echo $seo_tecdoc_manufacturer_with_tree['h1'];?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SEO text</label>
                        <textarea class="textarea" name="seo_tecdoc_manufacturer_with_tree[text]"><?php echo set_value('text',@$seo_tecdoc_manufacturer_with_tree['text']);?></textarea>
                    </div>
                    <p class="help-block">
                        {manuf} - Название производителя<br>
                        {cat} - Название категории
                    </p>
                </div>
            </div>
        </div>


        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Сохранить</button>
        </div>
    </div>
</section>
</form>
