<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <style>
        td.search-product-cart {
            width: 105px;
        }

        .widget > .panel-body {
            height: 125px;
            overflow: hidden;
        }

        .widget span.brand {
            font-weight: bold;
        }

        .row.item {
            border: 1px solid #e5e5e5;
            margin: 5px;
            padding: 5px;
        }
        .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
            padding: 1px;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 1px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 0px;
        }
        td.search-product-price {
            width: 100px;
        }
        td.search-product-excerpt {
            font-size: 12px;
        }
        td.search-product-fast {
            width: 81px;
        }
    </style>

    <div class="search-product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="search-product-bit-title text-center">
                        <h1><?php echo $this->h1; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <?php if ($products) { ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-default filter">
                        <div class="panel-heading">
                            Фильтр
                        </div>
                        <div class="panel-body">
                            <?php if ($filter_brands) { ?>
                                <div class="form-group filter-brand">
                                    <label>Производитель</label>
                                    <?php foreach ($filter_brands as $fb) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <input class="filter-brand" type="checkbox" value="<?php echo md5($fb); ?>">
                                                <?php echo $fb; ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php if ($min_price || $min_cross_price || $min_term) { ?>
                        <div class="row">
                            <?php if ($min_price) { ?>
                                <div class="col-md-4">
                                    <div class="panel panel-info widget">
                                        <div class="panel-heading" style="cursor: pointer;" onclick="go('<?php echo $min_price['key'];?>');">
                                            <i class="glyphicon glyphicon-euro"></i> Минимальная цена<br>
                                            <b><?php echo format_currency($min_price['price']); ?></b>
                                            <small class="term"><?php echo lang('text_term'); ?>
                                                :<?php echo format_term($min_price['term']); ?></small>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($min_price_cross) { ?>
                                <div class="col-md-4">
                                    <div class="panel panel-warning widget">
                                        <div class="panel-heading" style="cursor: pointer;" onclick="go('<?php echo $min_price_cross['key'];?>');">
                                            <i class="glyphicon glyphicon-euro"></i> Минимальная цена аналог<br>
                                            <b><?php echo format_currency($min_price_cross['price']); ?></b>
                                            <small class="term"><?php echo lang('text_term'); ?>
                                                :<?php echo format_term($min_price_cross['term']); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($min_term) { ?>
                                <div class="col-md-4">
                                    <div class="panel panel-info widget">
                                        <div class="panel-heading" style="cursor: pointer;" onclick="go('<?php echo $min_term['key'];?>');">
                                            <i class="glyphicon glyphicon-time"></i> Минимальный срок<br>
                                            <b><?php echo format_term($min_term['term']); ?></b>
                                            <small class="term"><?php echo lang('text_price'); ?>
                                                :<?php echo format_currency($min_term['price']); ?></small>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-3 col-md-offset-9">
                            <div class="form-group">
                                <label>Сортировать</label>
                                <select class="form-control" onchange="sort($(this).val());">
                                    <option <?php if($this->input->get('sort') == 'price'){?>selected<?php } ?> value="price">Цена</option>
                                    <option <?php if($this->input->get('sort') == 'term'){?>selected<?php } ?> value="term">Срок доставки</option>
                                    <option <?php if($this->input->get('sort') == 'qty'){?>selected<?php } ?> value="qty">Наличие</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <?php foreach ($products as $product){?>
                        <div class="row item brand <?php echo md5($product['brand']);?>">
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <?php if ($product['is_cross'] == 0) { ?>
                                    <label class="label label-success">
                                        <?php echo lang('text_cross_type_0'); ?>
                                    </label>
                                <?php } elseif ($product['is_cross'] == 1) { ?>
                                    <label class="label label-warning">
                                        <?php echo lang('text_cross_type_1'); ?>
                                    </label>
                                <?php } else { ?>
                                    <label class="label label-default">
                                        <?php echo lang('text_cross_type_2'); ?>
                                    </label>
                                <?php } ?>
                                <br>

                                <?php if($product['image']){?>
                                    <a data-trigger="hover" data-container="body" data-html="true"
                                       data-toggle="popover" data-placement="left"
                                       data-content="<?php echo htmlspecialchars('<img src="'.$product['image'].'"/>'); ?>"
                                       >
                                        <i class="glyphicon glyphicon-picture"></i>
                                    </a>
                                <?php } ?>
                                <?php if($product['info']){?>
                                    <a data-trigger="hover" data-container="body" data-html="true"
                                       data-toggle="popover" data-placement="left"
                                       data-content="<?php echo htmlspecialchars($product['info']); ?>"
                                    >
                                        <i class="glyphicon glyphicon-info-sign"></i>
                                    </a>
                                <?php } ?>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <b><?php echo $product['brand'];?></b> <?php echo $product['sku'];?>
                                <br><a href="/product/<?php echo $product['slug'];?>"><?php echo $product['name'];?></a>
                            </div>
                            <div class="col-md-7 table-responsive" style="text-align: center;">
                                <table class="table table-hover table-condensed">
                                    <?php $q =0; foreach ($product['prices'] as $price){ ?>
                                        <tr id="<?php echo $price['key'];?>" class="product-<?php echo $product['id'];?>" <?php if($q > 5){?>style="display: none" <?php } ?>>
                                            <td class="search-product-excerpt">
                                                <?php if ($this->is_admin) { ?>
                                                    <a style="color: red;" data-trigger="hover" data-container="body" data-html="true"
                                                       data-toggle="popover" data-placement="left"
                                                       data-content="<?php echo htmlspecialchars('Это видит только админ.<br/>'.$this->supplier_model->suppliers[$price['supplier_id']]['name'].' '.$price['delivery_price'].' '.$this->currency_model->currencies[$price['currency_id']]['name'].' '.$price['quantity'].'шт.'); ?>"
                                                    >
                                                        <i class="glyphicon glyphicon-road"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if($price['term'] < 24){?>
                                                    <a style="color: green;" data-trigger="hover" data-container="body" data-html="true"
                                                       data-toggle="popover" data-placement="left"
                                                       data-content="<?php echo htmlspecialchars(lang('text_term').' '.format_term($price['term'])); ?>"
                                                    >
                                                        <i class="glyphicon glyphicon-unchecked"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php echo $price['excerpt'];?>
                                            </td>
                                            <td><?php echo format_term($price['term']);?></td>
                                            <td><?php echo format_quantity($price['quantity']);?></td>
                                            <td class="search-product-price"><?php echo format_currency($price['price']);?></td>
                                            <td class="search-product-fast">
                                                <small>
                                                    <a href="/cart" class="<?php echo $price['key']; ?>"
                                                        <?php if (!key_exists(md5($price['key']), $this->cart->contents())) { ?>
                                                            style="display: none;"
                                                        <?php } ?>
                                                    ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                    </a>
                                                </small>
                                                <a href="#" onclick="fastOrder('/product/<?php echo $product['slug']; ?>',event);"><?php echo strip_tags(lang('text_fast_order_link')); ?></a>
                                            </td>
                                            <td class="search-product-cart">
                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                                <div class="input-group">
                                                    <input placeholder="кол." type="number"
                                                           name="quantity"
                                                           class="form-control">
                                                    <input type="hidden" name="product_id"
                                                           value="<?php echo $product['id']; ?>">
                                                    <input type="hidden" name="supplier_id"
                                                           value="<?php echo $price['supplier_id']; ?>">
                                                    <input type="hidden" name="term"
                                                           value="<?php echo $price['term']; ?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="submit"><i
                                                                    class="fa fa-shopping-cart"></i></button>
                                                    </span>
                                                </div>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php $q++; } ?>
                                </table>
                                <?php if($q > 5){ ?>
                                    <button id="show-buttom-<?php echo $product['id'];?>" class="btn btn-link" onclick="show(<?php echo $product['id'];?>)">Показать еще (<?php echo $q - 5;?>)</button>
                                    <button style="display: none;" id="hide-buttom-<?php echo $product['id'];?>" class="btn btn-link" onclick="hide(<?php echo $product['id'];?>)">Скрыть</button>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div style="text-align: center;font-size: 24px;margin: 0 0 15px;"><?php echo lang('text_no_results'); ?></div>
                <p class="alert-warning"><?php echo lang('text_no_results_description'); ?></p>
                <?php echo form_open('ajax/vin', ['class' => 'vin_request', 'onsubmit' => 'send_request(event)']); ?>
                <div class="col-md-6">
                    <div class="well">

                        <div class="alert alert-danger" role="alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                            </button>
                        </div>
                        <div class="alert alert-success" role="alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                            </button>
                        </div>

                        <div class="form-group">
                            <label><?php echo lang('text_vin_manufacturer'); ?></label>
                            <input type="text" class="form-control" name="manufacturer" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_vin_model'); ?></label>
                            <input type="text" class="form-control" name="model" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_vin_engine'); ?></label>
                            <input type="text" class="form-control" name="engine" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_vin_vin'); ?></label>
                            <input type="text" class="form-control" name="vin">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_vin_parts'); ?></label>
                            <textarea class="form-control" name="parts" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('text_vin_name'); ?></label>
                        <input type="text" name="name" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_vin_telephone'); ?></label>
                        <input type="text" name="telephone" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_vin_email'); ?></label>
                        <input type="email" name="email" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group pull-right">
                    <button type="submit"><?php echo lang('button_send'); ?></button>
                </div>
                </form>
            </div>
        <?php } ?>
    </div>

    <div class="modal fade" id="image-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<script>
    $(document).ready(function(){
        $(function () {
            $('[data-toggle="popover"]').popover()
        });

        $(".filter-brand input").click(function(){
            var checked = 0;
            $(".item").hide();
           $(".filter-brand input").each(function(index,item){
                if( $(item).is( ":checked" )){
                    $("."+$(item).val()).show();
                    checked++;
                }
           });
           if(checked == 0){
               $(".item").show();
           }
        });
    });

    function sort(field){
        var redirect = '/search?sort='+field;
        <?php unset($_GET['sort']);?>
        <?php foreach ($this->input->get() as $key => $value){?>
            redirect += '&<?php echo $key;?>=<?php echo $value;?>';
        <?php } ?>
        location.href=redirect;
     }

    function go(price_key) {
        $("#"+price_key).css('background-color','#f443360a');
        $('html, body').animate({
            scrollTop: parseInt($("#"+price_key).offset().top)-200
        }, 500);
        setTimeout(function() { $("#"+price_key).css('background-color','#ffffff'); }, 5000);
    }

    function show(product_id) {
        $(".product-"+product_id).fadeIn();
        $("#hide-buttom-"+product_id).show();
        $("#show-buttom-"+product_id).hide();
    }

    function hide(product_id) {
        $(".product-"+product_id).each(function(index,item){
            if(index > 5){
                $(item).hide();
            }
        });
        $("#hide-buttom-"+product_id).hide();
        $("#show-buttom-"+product_id).show();
    }
</script>

<?php if (false) { ?>
    <link rel="stylesheet" href="<?php echo theme_url(); ?>css/dataTables.bootstrap.min.css">
    <script src="<?php echo theme_url(); ?>js/jquery.dataTables.min.js"></script>
    <script src="<?php echo theme_url(); ?>js/dataTables.bootstrap.min.js"></script>
    <style>

        button.btn.btn-default {
            padding: 7px 11px;
            background: #e5e5e5;
            color: #000;
        }

        td.search-product-name {
            word-break: break-all;
            width: 210px;
        }

        .brands {
            text-align: center;
        }

        .brands b {
            color: #31708f
        }

        .brands b.active {
            color: white;
        }

        .filter input[type='number'] {
            width: 45%;
        }

        .filter-brand {
            max-height: 400px;
            overflow: auto;
        }
    </style>
    <div class="search-product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="search-product-bit-title text-center">
                        <h1><?php echo $this->h1; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php if ($products){ ?>
            <div class="col-md-2">
                <div class="panel panel-default filter">
                    <div class="panel-heading">
                        Фильтр
                    </div>
                    <div class="form-group">
                        <label>Цена</label><br/>
                        <input id="price-min" type="number" placeholder="от">-
                        <input id="price-max" type="number" placeholder="до">
                    </div>

                    <div class="form-group">
                        <label>Срок поставки (час.)</label><br/>
                        <input id="term-min" type="number" placeholder="от">-
                        <input id="term-max" type="number" placeholder="до">
                    </div>
                    <?php if ($filter_brands) { ?>
                        <div class="form-group filter-brand">
                            <label>Производитель</label>
                            <?php foreach ($filter_brands as $fb) { ?>
                                <div class="checkbox">
                                    <label>
                                        <input class="filter-brand" type="checkbox" value="<?php echo $fb; ?>">
                                        <?php echo $fb; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-10">
                <?php }else{ ?>
                <div class="col-md-12">
                    <?php } ?>
                    <?php if ($products) { ?>
                        <div class="table-responsive">
                            <table id="example" class="table table-condensed" cellspacing="0" width="100%"
                                   style="display: none">
                                <thead>
                                <tr>
                                    <th>Тип</th>
                                    <th>Код</th>
                                    <th>Бренд</th>
                                    <th>Название</th>
                                    <th>Цена</th>
                                    <th>Кол.</th>
                                    <th>Срок</th>
                                    <th>Купить</th>
                                    <?php if ($this->is_admin) { ?>
                                        <th>Поставщик</th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Тип</th>
                                    <th>Код</th>
                                    <th>Бренд</th>
                                    <th>Название</th>
                                    <th>Цена</th>
                                    <th>Кол.</th>
                                    <th>Срок</th>
                                    <th>Купить</th>
                                    <?php if ($this->is_admin) { ?>
                                        <th>Поставщик</th>
                                    <?php } ?>
                                </tr>
                                </tfoot>
                                <tbody>
                                <?php foreach ($products as $product) { ?>
                                    <tr data-tr="<?php echo $product['id']; ?>"
                                        class="<?php echo format_term_class($product['term']); ?> tr-<?php echo $product['id']; ?>">
                                        <td data-order="<?php echo $product['cross']; ?>" class="search-product-type">
                                            <?php if ($product['cross'] == 0) { ?>
                                                <label class="label label-success">
                                                    <?php echo lang('text_cross_type_0'); ?>
                                                </label>
                                            <?php } elseif ($product['cross'] == 1) { ?>
                                                <label class="label label-warning">
                                                    <?php echo lang('text_cross_type_1'); ?>
                                                </label>
                                            <?php } else { ?>
                                                <label class="label label-default">
                                                    <?php echo lang('text_cross_type_2'); ?>
                                                </label>
                                            <?php } ?>
                                        </td>
                                        <td class="search-product-sku"><a
                                                    href="/product/<?php echo $product['slug']; ?>"
                                                    onclick="goTo(<?php echo $product['supplier_id']; ?>,<?php echo $product['term']; ?>)"><?php echo $product['sku']; ?></a>
                                        </td>
                                        <td class="search-product-brand"><a
                                                    href="/product/<?php echo $product['slug']; ?>"
                                                    onclick="goTo(<?php echo $product['supplier_id']; ?>,<?php echo $product['term']; ?>)"><?php echo $product['brand']; ?></a>
                                        </td>
                                        <td class="search-product-name">
                                            <a class="name" href="/product/<?php echo $product['slug']; ?>"
                                               onclick="goTo(<?php echo $product['supplier_id']; ?>,<?php echo $product['term']; ?>)"><?php echo $product['name']; ?></a>
                                            <?php if ($product['excerpt']) { ?>
                                                <br>
                                                <small class="search-product-excerpt"><?php echo $product['excerpt']; ?></small>
                                            <?php } ?>
                                        </td>

                                        <td data-search="<?php echo $product['price']; ?>"
                                            data-order="<?php echo $product['price']; ?>" class="search-product-price">
                                            <b><?php echo format_currency($product['price']); ?></b>
                                        </td>
                                        <td data-search="<?php echo $product['quantity']; ?>"
                                            data-order="<?php echo $product['quantity']; ?>"
                                            class="search-product-quan">
                                            <b><?php echo format_quantity($product['quantity']); ?></b></td>
                                        <td data-search="<?php echo $product['term']; ?>"
                                            data-order="<?php echo $product['term']; ?>" class="search-product-term"><b
                                                    class="<?php echo format_term_class($product['term']); ?>"><?php echo format_term($product['term']); ?></b>
                                        </td>
                                        <td class="search-product-cart">
                                            <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                            <div class="input-group">
                                                <input style="width: 50px;" placeholder="кол." type="text"
                                                       name="quantity"
                                                       class="form-control">
                                                <input type="hidden" name="product_id"
                                                       value="<?php echo $product['id']; ?>">
                                                <input type="hidden" name="supplier_id"
                                                       value="<?php echo $product['supplier_id']; ?>">
                                                <input type="hidden" name="term"
                                                       value="<?php echo $product['term']; ?>">
                                            </div>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <button class="btn btn-default" type="submit"><i
                                                            class="fa fa-shopping-cart"></i></button>
                                                <a title="<?php echo strip_tags(lang('text_fast_order_link')); ?>"
                                                   class="btn btn-info" href="#"
                                                   onclick="fastOrder('/product/<?php echo $product['slug']; ?>',event);"><i
                                                            class="glyphicon glyphicon-send"></i></a>

                                            </div>
                                            </form>
                                            <small>
                                                <a href="/cart" class="<?php echo $product['key']; ?>"
                                                    <?php if (!key_exists(md5($product['key']), $this->cart->contents())) { ?>
                                                        style="display: none;"
                                                    <?php } ?>
                                                ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                </a>
                                            </small>
                                        </td>
                                        <?php if ($this->is_admin) { ?>
                                            <td>
                                                <small>
                                                    <a target="_blank"
                                                       href="/autoxadmin/supplier/edit/<?php echo $product['supplier_id']; ?>"><?php echo $this->supplier_model->suppliers[$product['supplier_id']]['name']; ?></a>
                                                    <b><?php echo $product['delivery_price'] . ' ' . $this->currency_model->currencies[$product['currency_id']]['name']; ?></b>
                                                </small>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    <?php } else { ?>
                        <div style="text-align: center;font-size: 24px;margin: 0 0 15px;"><?php echo lang('text_no_results'); ?></div>
                        <p class="alert-warning"><?php echo lang('text_no_results_description'); ?></p>
                        <?php echo form_open('ajax/vin', ['class' => 'vin_request', 'onsubmit' => 'send_request(event)']); ?>
                        <div class="col-md-6">
                            <div class="well">

                                <div class="alert alert-danger" role="alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                    </button>
                                </div>
                                <div class="alert alert-success" role="alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                    </button>
                                </div>

                                <div class="form-group">
                                    <label><?php echo lang('text_vin_manufacturer'); ?></label>
                                    <input type="text" class="form-control" name="manufacturer" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_vin_model'); ?></label>
                                    <input type="text" class="form-control" name="model" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_vin_engine'); ?></label>
                                    <input type="text" class="form-control" name="engine" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_vin_vin'); ?></label>
                                    <input type="text" class="form-control" name="vin">
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('text_vin_parts'); ?></label>
                                    <textarea class="form-control" name="parts" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('text_vin_name'); ?></label>
                                <input type="text" name="name" class="form-control" required/>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_vin_telephone'); ?></label>
                                <input type="text" name="telephone" class="form-control" required/>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_vin_email'); ?></label>
                                <input type="email" name="email" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group pull-right">
                            <button type="submit"><?php echo lang('button_send'); ?></button>
                        </div>
                        </form>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
    <script>
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = parseInt($('#price-min').val(), 10);
                var max = parseInt($('#price-max').val(), 10);
                var price = parseFloat(data[4]) || 0; // use data for the price column

                if ((isNaN(min) && isNaN(max)) ||
                    (isNaN(min) && price <= max) ||
                    (min <= price && isNaN(max)) ||
                    (min <= price && price <= max)) {
                    return true;
                }
                return false;
            }
        );

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = parseInt($('#term-min').val(), 10);
                var max = parseInt($('#term-max').val(), 10);
                var term = parseFloat(data[6]) || 0; // use data for the price column

                if ((isNaN(min) && isNaN(max)) ||
                    (isNaN(min) && term <= max) ||
                    (min <= term && isNaN(max)) ||
                    (min <= term && term <= max)) {
                    return true;
                }
                return false;
            }
        );

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var search_brand = [];
                $(".filter-brand:checked").each(function (index) {
                    search_brand[index] = $(this).val();
                });


                var brand = data[2] || 0; // use data for the price column

                if (search_brand.indexOf(brand) != '-1' || !search_brand.length) {
                    return true;
                }
                return false;
            }
        );

        $(document).ready(function () {
            var table = $('#example').DataTable({
                paging: false,
                ordering: true,
                info: false,
                searching: true,
                order: [[0, "asc"], [4, 'asc'], [6, 'asc']]
            });

            // Event listener to the two range filtering inputs to redraw on input
            $('#price-min, #price-max, #term-min, #term-max').keyup(function () {
                table.draw();
            });

            $('input[type="checkbox"]').change(function () {
                table.draw();
            });

            $("#example").fadeIn('slow');

        });


        function goTo(supplier_id, term) {
            $.post('/ajax/gotoproduct', {supplier_id: supplier_id, term: term});
            return true;
        }
    </script>
<?php } ?>