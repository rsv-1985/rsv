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
        <li><a href="/autoxadmin/review"><?php echo lang('text_heading');?></a></li>
        <li><a href="#"><?php echo $review['id'];?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open();?>
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label>Автор</label>
                        <input type="text" class="form-control" name="author" value="<?php echo set_value('author', $review['author']); ?>" maxlength="250">
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label>Отзыв</label>
                        <textarea name="text" class="textarea"><?php echo set_value('text', $review['text']);?></textarea>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label>Товар</label>
                        <?php if($review['product_id']){ ?>
                            <a href="/autoxadmin/product/edit/<?php echo $review['product_id'];?>" target="_blank"><?php echo $review['product_id'];?></a>
                        <?php } ?>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label>Статус</label>
                        <select name="status" class="form-control">
                            <option value="0" <?php if(!$review['status']){?>selected<?php } ?>>Новый</option>
                            <option value="1" <?php if($review['status']){?>selected<?php } ?>>Отображается</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Клиент</label>
                        <?php if($review['customer_id']){ ?>
                            <a href="/autoxadmin/customer/edit/<?php echo $review['customer_id'];?>" target="_blank"><?php echo $review['customer_id'];?></a>
                        <?php } ?>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <label>Контакты</label>
                        <?php echo $review['contact']; ?>
                        <button  type="submit" class="btn btn-info pull-right">Сохранить</button>
                    </div><!-- /.form group -->
                </div><!-- /.box-body -->
            </div>
        </div>
        </form>
    </div>
</section><!-- /.content -->
<script>

</script>