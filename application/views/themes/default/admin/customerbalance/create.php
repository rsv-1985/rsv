<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>

<section class="content-header">
    <h1>
        Баланс
        <small>покупателей</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="/autoxadmin/customerbalance"><i class="fa fa-dashboard"></i> Баланс</a></li>
        <li class="active">Создать</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
<?php echo form_open('/autoxadmin/customerbalance/create',['method' => 'post']);?>
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Баланс создать</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Логин покупателя</label>
                        <input required type="text" name="login" value="<?php echo set_value('login');?>" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label>Тип</label>
                        <select required name="type" class="form-control">
                            <?php foreach ($types as $id => $type){?>
                                <option value="<?php echo $id;?>" <?php echo set_select('type');?>><?php echo $type;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Сумма</label>
                        <input required type="text" name="value" value="<?php echo set_value('value');?>" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label>Дата транзакции</label>
                        <input required type="datetime-local" data-inputmask="'alias': 'yyyy/mm/dd'" name="transaction_created_at" value="<?php echo set_value('transaction_created_at');?>" class="form-control" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Описание транзакции</label>
                        <textarea rows="8" class="form-control" name="description"><?php echo set_value('description');?></textarea>
                    </div>
                </div>
            </div>

        </div><!-- /.box-body -->
        <div class="box-footer">
            <button class="btn btn-info pull-right" type="submit"><?php echo lang('button_add');?></button>
        </div><!-- /.box-footer-->
    </div><!-- /.box -->
</form>
</section><!-- /.content -->
