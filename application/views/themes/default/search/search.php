<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <style>
        .table{
            font-size: 14px;
        }
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
            text-align: left;
            width: auto;
        }
        td.search-product-fast {
            width: 81px;
        }
        @media (min-width:500px){
            .table_info_item .search-product-excerpt {
                width: 115px;
                line-height: 15px;
            }
            .table_info_item .search-product-term {
                padding: 2px 10px;
                width: 86px;
                line-height: 14px;
            }
        }
        @media (max-width: 991px){
            .row.item{
                margin: 0 0 15px;
                border: 1px solid #acaaaa;
            }
            .table_info_item .table-responsive {
                border: none;
            }
            .table_info_item{
                padding: 12px 0 0;
                margin-top: 13px;
                border-top: 1px solid #efefef;
            }
            .table_info_item td.search-product-term {
                width: 60px;
                white-space: normal !important;
                line-height: 13px;
                padding-right: 11px !important;
            }
            .label_info_detail {
                padding-left: 3px;
            }
			.table_info_item td:not(:first-child) {
				display: block;
				float: left;
				width: 24%;
				line-height: 20px;
			}
			.table_info_item tbody {
				width: 100%;
				display: block;
			}
			.table_info_item .search-product-term,
			.table_info_item .search-product-quantity,
			.table_info_item .search-product-price{
				padding-top: 5px;
			}
			.table_info_item .search-product-cart input {
				width: 56px;
				min-width: 56px;
				padding: 6px;
			}
        }
        @media (max-width: 500px){
            .table_info_item table, .table_info_item tr, .table_info_item tr > td:nth-child(1){
                display: block;
            }
            .table_info_item tr:not(:first-child) > td:nth-child(1) {
                margin-top: 15px;
                padding-top: 5px;
                border-top: 1px solid #efefef;
            }
            .table-hover>tbody>tr:hover {
                background-color: #ffffff;
            }
            .table_info_item td:not(.search-product-excerpt) {
                font-size: 14px !important;
                letter-spacing: -1.1px;
            }
            .label_info_detail .label {
                margin-bottom: 6px;
                display: inline-block;
            }
            .label_info_detail .glyphicon {
                font-size: 20px;
                margin-right: 9px;
            }
            .table_info_item .search-product-cart {
                width: 76px;
            }
            .table_info_item .search-product-cart input {
                width: 53px;
                min-width: 53px;
                padding: 6px;
            }
        }
		@media (max-width: 365px){
            .table_info_item .search-product-cart input {
                width: 12px;
                min-width: 39px;
                padding: 6px;
            }
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
            <div class="row ">
                <div class="col-md-2">
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
                <div class="col-md-10">
                    <?php if ($min_price || $min_price_cross || $min_term) { ?>
                        <div class="row">
                            <?php if ($min_price) { ?>
                                <div class="col-md-4">
                                    <div class="panel panel-info widget">
                                        <div class="panel-heading" style="cursor: pointer;" onclick="go('<?php echo $min_price['key'];?>');">
                                            <i class="glyphicon glyphicon-stats"></i> Минимальная цена<br>
                                            <?php echo $min_price['brand'];?><br/>
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
                                            <i class="glyphicon glyphicon-stats"></i> Минимальная цена аналог<br>
                                            <?php echo $min_price_cross['brand'];?><br/>
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
                                            <?php echo $min_term['brand'];?><br/>
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
                            <div class="col-md-1 col-sm-12 col-xs-5 label_info_detail">
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
                                       data-toggle="popover" data-placement="right"
                                       data-content="<?php echo htmlspecialchars('<img src="'.$product['image'].'"/>'); ?>"
                                    >
                                        <i class="glyphicon glyphicon-picture"></i>
                                    </a>
                                <?php } ?>
                                <?php if($product['info']){?>
                                    <a data-trigger="hover" data-container="body" data-html="true"
                                       data-toggle="popover" data-placement="right"
                                       data-content="<?php echo htmlspecialchars($product['info']); ?>"
                                    >
                                        <i class="glyphicon glyphicon-info-sign"></i>
                                    </a>
                                <?php } ?>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-7">
                                <b><?php echo $product['brand'];?></b> <?php echo $product['sku'];?>
                                <br>
                                <small>
                                    <a href="/product/<?php echo $product['slug'];?>"><?php echo $product['name'];?></a>
                                </small>

                            </div>
                            <div class="col-md-8 col-xs-12 table_info_item" style="text-align: center;">
                                <div class="table-responsive">
                                    <table class="table table-hover table-condensed">
                                        <?php $q = 1; foreach ($product['prices'] as $price){ ?>
                                            <tr id="<?php echo $price['key'];?>" class="clearfix <?php echo format_term_class($price['term']);?> product-<?php echo $product['id'];?>" <?php if($q > 5){?>style="display: none" <?php } ?>>
                                                <?php if ($this->is_admin) { ?>
                                                    <td>
                                                        <?php echo $this->supplier_model->suppliers[$price['supplier_id']]['name'].'<br>'.$price['delivery_price'].' '.$this->currency_model->currencies[$price['currency_id']]['name'].' '.$price['quantity'].'шт.'; ?>
                                                    </td>
                                                <?php } ?>
                                                <td class="search-product-excerpt">
                                                    <?php echo $price['excerpt'];?>
                                                </td>
                                                <td class="search-product-term"><?php echo format_term($price['term']);?></td>
                                                <td class="search-product-quantity"><?php echo format_quantity($price['quantity']);?></td>
                                                <td class="search-product-price">
                                                    <?php if($price['saleprice'] > 0){?>
                                                        <?php echo format_currency($price['saleprice']);?><br>
                                                        <small><strike><?php echo format_currency($price['price']);?></strike></small>
                                                    <?php }else{?>
                                                        <?php echo format_currency($price['price']);?>
                                                    <?php } ?>
                                                </td>
                                                <?php if(@$this->options['show_fast_order_search']){?>
                                                    <td class="search-product-fast">
                                                        <a href="#" onclick="fastOrder('/product/<?php echo $product['slug']; ?>',event);"><?php echo strip_tags(lang('text_fast_order_link')); ?></a>
                                                    </td>
                                                <?php } ?>
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
                                                    <small>
                                                        <a href="/cart" class="<?php echo $price['key']; ?>"
                                                            <?php if (!key_exists(md5($price['key']), $this->cart->contents())) { ?>
                                                                style="display: none;"
                                                            <?php } ?>
                                                        ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                        </a>
                                                    </small>
                                                </td>
                                            </tr>
                                            <?php $q++; } ?>
                                    </table>
                                    <?php if($q > 6){ ?>
                                        <button id="show-buttom-<?php echo $product['id'];?>" class="btn btn-link" onclick="show(<?php echo $product['id'];?>)">Показать еще (<?php echo $q - 6;?>)</button>
                                        <button style="display: none;" id="hide-buttom-<?php echo $product['id'];?>" class="btn btn-link" onclick="hide(<?php echo $product['id'];?>)">Скрыть</button>
                                    <?php } ?>
                                </div>
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

            <?php if(!isset($_GET['sort'])){?>
            var one_day = [];
            $(".one_day").each(function(index,item){
                $(item).parent('tbody').prepend(item);
            });
            var in_stock = [];
            $(".in_stock").each(function(index,item){
                $(item).parent('tbody').prepend(item);
            });
            <?php } ?>


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
            $("#"+price_key).show().css('background-color','#f443360a');
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
                if(index >= 5){
                    $(item).hide();
                }
            });
            $("#hide-buttom-"+product_id).hide();
            $("#show-buttom-"+product_id).show();
        }
    </script>