<div class="list-group">
    <a href="/customer" class="list-group-item <?php if($this->uri->segment(2) == ''){?>active<?php } ?>">Заказы</a>
    <a href="/customer/products" class="list-group-item <?php if($this->uri->segment(2) == 'products'){?>active<?php } ?>">Детали в работе</a>
    <a href="/customer/balance" class="list-group-item <?php if($this->uri->segment(2) == 'balance'){?>active<?php } ?>">Финансовый модуль</a>
    <a href="/customer/profile" class="list-group-item <?php if($this->uri->segment(2) == 'profile'){?>active<?php } ?>">Профиль</a>
</div>