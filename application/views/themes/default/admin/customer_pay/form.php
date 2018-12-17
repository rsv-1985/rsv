<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>

<section class="content-header">
    <h1>
        <?php echo lang('text_customer_pay');?>
        <small><?php echo $id;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="/autoxadmin/customer_pay"><i class="fa fa-dashboard"></i> Оплата</a></li>
        <li class="active">Создать</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <?php echo form_open($action,['method' => 'post']);?>
    <!-- Default box -->
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ID клиента</label>
                        <input required type="text" name="customer_id" value="<?php echo $customer_id;?>" class="form-control" >
                    </div>

                    <div class="form-group">
                        <label>Сумма</label>
                        <input required type="text" name="amount" value="<?php echo $amount ;?>" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label>Дата транзакции</label>
                        <input required type="date" name="date" value="<?php echo $date;?>" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label>Время транзакции</label>
                        <input required type="time" name="time" value="<?php echo $time;?>" class="form-control" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Комментарий</label>
                        <textarea rows="8" class="form-control" name="comment"><?php echo $comment;?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Статус</label>
                        <select required name="status_id" class="form-control">
                            <?php foreach ($statuses as $id => $status){?>
                                <?php if((string)$id == $status_id){?>
                                    <option value="<?php echo $id;?>"selected><?php echo $status;?></option>
                                <?php }else{ ?>
                                    <option value="<?php echo $id;?>"><?php echo $status;?></option>
                                <?php } ?>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

        </div><!-- /.box-body -->
        <div class="box-footer">
            <button class="btn btn-info pull-right" type="submit">Сохранить</button>
        </div><!-- /.box-footer-->
    </div><!-- /.box -->
    </form>
    <script>

    </script>
</section><!-- /.content -->
