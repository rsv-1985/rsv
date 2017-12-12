<div class="list-group">
    <a href="/customer" class="list-group-item <?php if($this->uri->segment(2) == ''){?>active<?php } ?>">Заказы</a>
    <a href="/customer/products" class="list-group-item <?php if($this->uri->segment(2) == 'products'){?>active<?php } ?>">Детали в работе</a>
    <a href="/customer/parcels" class="list-group-item <?php if($this->uri->segment(2) == 'parcels'){?>active<?php } ?>">Отправки</a>
    <a href="/customer/balance" class="list-group-item <?php if($this->uri->segment(2) == 'balance'){?>active<?php } ?>">Финансы</a>
    <a href="/customer/profile" class="list-group-item <?php if($this->uri->segment(2) == 'profile'){?>active<?php } ?>">Профиль</a>
    <a href="/customer/search_history" class="list-group-item <?php if($this->uri->segment(2) == 'search_history'){?>active<?php } ?>">История поиска</a>
</div>