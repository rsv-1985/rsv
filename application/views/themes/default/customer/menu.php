
<div class="well well-sm">
    <?php echo lang('text_balance');?>: <?php echo format_balance($this->customer_model->balance); ?><br/>
    <?php echo lang('text_work_balance');?>: <?php echo format_balance($this->customer_model->getWorkBalance($this->customer_model->id));?>
    <hr>
    <div class="btn-group" role="group" aria-label="...">
        <?php if($this->settings_model->get_by_key('liqpay')){?>
            <button onclick="payOnline()" class="btn btn-success btn-xs"><?php echo lang('text_pay_online');?></button>
            <script>
                function payOnline() {
                    var amount = prompt('<?php echo lang('text_prompt_amount');?>');
                    if(amount > 0){
                        location.href = '/payment/liqpay?amount='+amount;
                    }
                }
            </script>
        <?php } ?>
        <button class="btn btn-info btn-xs" onclick="$('#pay-form').toggle();"><?php echo lang('text_report_payment');?></button>
    </div>

    <div class="clearfix"></div>
</div>
<?php echo form_open('/customer/balance'); ?>
<div class="well well-sm" id="pay-form" style="display: none">
    <div class="form-group">
        <label><?php echo lang('text_balance_sum');?></label>
        <input value="<?php echo set_value('sum');?>" type="text" name="sum" class="form-control" required>
    </div>
    <div class="form-group">
        <label><?php echo lang('text_balance_date_time');?></label>
        <input name="date" value="<?php echo set_value('date');?>" type="date" class="form-control input-sm">
        <br>
        <input name="time" value="<?php echo set_value('time');?>" type="time" class="form-control input-sm">
    </div>
    <div class="form-group">
        <label><?php echo lang('text_balance_comment');?></label>
        <textarea name="comment" class="form-control"><?php echo set_value('comment');?></textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-xs pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<?php echo form_close();?>
<div class="list-group">
    <a href="/customer" class="list-group-item <?php if($this->uri->segment(2) == ''){?>active<?php } ?>">Заказы</a>
    <a href="/customer/products" class="list-group-item <?php if($this->uri->segment(2) == 'products'){?>active<?php } ?>">Детали в работе</a>
    <a href="/customer/balance" class="list-group-item <?php if($this->uri->segment(2) == 'balance'){?>active<?php } ?>">Баланс</a>
    <a href="/customer/invoices" class="list-group-item <?php if($this->uri->segment(2) == 'invoices'){?>active<?php } ?>"><?php echo lang('text_menu_invoice');?></a>

    <a href="/customer/profile" class="list-group-item <?php if($this->uri->segment(2) == 'profile'){?>active<?php } ?>">Профиль</a>
    <a href="/customer/search_history" class="list-group-item <?php if($this->uri->segment(2) == 'search_history'){?>active<?php } ?>">История поиска</a>
    <a href="/customer/vin" class="list-group-item <?php if($this->uri->segment(2) == 'vin'){?>active<?php } ?>">VIN запросы</a>
    <?php if($this->customergroup_model->customer_group['download_folder']){?>
        <a target="_blank" href="<?php echo $this->customergroup_model->customer_group['download_folder'];?>" class="list-group-item">Файлы для скачивания</a>
    <?php } ?>
</div>