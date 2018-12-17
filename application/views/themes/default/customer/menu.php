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