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
        <small>hook</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="/autoxadmin/seo_settings">SEO настройки</a></li>
        <li class="active">hook</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">SEO hook</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label>Ссылка на страницу</label>
                <input type="text" name="url" value="<?php echo $this->input->get('edit',true);?>" placeholder="/page/klientam" class="form-control">
                <p class="help-block">Укажите ссылку на страницу для которой хотите добавить сео настройки</p>
            </div>

            <div class="form-group">
                <label>SEO title</label>
                <input id="hook-title" type="text" name="hook[title]" value="<?php echo @$hook['title'];?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO Description</label>
                <input id="hook-description" type="text" name="hook[description]" value="<?php echo @$hook['description'];?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO keywords</label>
                <input id="hook-keywords" type="text" name="hook[keywords]" value="<?php echo @$hook['keywords'];?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO h1</label>
                <input id="hook-h1" type="text" name="hook[h1]" value="<?php echo @$hook['h1'];?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO text</label>
                <textarea id="hook-text" class="textarea" name="hook[text]"><?php echo @$hook['text'];?></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Сохранить</button>
            </div>
        </div>

        <div class="box-footer">
            <?php if($seo_hooks){?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ссылка</th>
                            <th>title</th>
                            <th>h1</th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php foreach ($seo_hooks as $url => $hook){?>
                        <tr>
                            <td><a target="_blank" href="<?php echo $url;?>"><?php echo $url;?></a> </td>
                            <td><?php echo $hook['title'];?></td>
                            <td><?php echo $hook['h1'];?></td>
                            <td>
                                <a href="/autoxadmin/seo_settings/hook?delete=<?php echo $url;?>">Удалить</a> |
                                <a href="/autoxadmin/seo_settings/hook?edit=<?php echo $url;?>">Изменить</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

        </div>
    </div>
</section>
</form>