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
        <small>Производитель в категориях</small>
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
            <h3 class="box-title">SEO шаблон производителей в категории</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label>SEO title</label>
                <input type="text" name="seo_brand[title]" value="<?php echo set_value('title',@$seo_brand['title']);?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO description</label>
                <input type="text" name="seo_brand[description]" value="<?php echo set_value('title',@$seo_brand['description']);?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO keywords</label>
                <input type="text" name="seo_brand[keywords]" value="<?php echo set_value('title',@$seo_brand['keywords']);?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO h1</label>
                <input type="text" name="seo_brand[h1]" value="<?php echo set_value('title',@$seo_brand['h1']);?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO text</label>
                <textarea class="textarea" name="seo_brand[text]"><?php echo set_value('title',@$seo_brand['text']);?></textarea>
            </div>
            <p class="help-block">
                {category} - название категории<br>
                {brand} - производитель<br>
            </p>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Сохранить</button>
        </div>
    </div>
</section>
</form>
