<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                        <table id="example" class="table table-condensed" cellspacing="0" width="100%" style="display: none">
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
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach ($products as $product) { ?>
                                <tr data-tr="<?php echo $product['id'];?>" class="<?php echo format_term_class($product['term']); ?> tr-<?php echo $product['id'];?>">
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
                                                href="/product/<?php echo $product['slug']; ?>?supplier_id=<?php echo $product['supplier_id']; ?>&term=<?php echo $product['term']; ?>"><?php echo $product['sku']; ?></a>
                                    </td>
                                    <td class="search-product-brand"><a
                                                href="/product/<?php echo $product['slug']; ?>?supplier_id=<?php echo $product['supplier_id']; ?>&term=<?php echo $product['term']; ?>"><?php echo $product['brand']; ?></a>
                                    </td>
                                    <td class="search-product-name">
                                        <a href="/product/<?php echo $product['slug']; ?>?supplier_id=<?php echo $product['supplier_id']; ?>&term=<?php echo $product['term']; ?>"><?php echo $product['name']; ?></a>
                                        <?php if ($product['excerpt']) { ?>
                                            <br>
                                            <small class="search-product-excerpt"><?php echo $product['excerpt']; ?></small>
                                        <?php } ?>
                                        <?php if ($this->is_admin) { ?>
                                            <hr/>
                                            <div style="font-size: 11px">
                                                Поставщик: <a target="_blank"
                                                              href="/autoxadmin/supplier/edit/<?php echo $product['supplier_id']; ?>"><?php echo $this->supplier_model->suppliers[$product['supplier_id']]['name']; ?></a><br/>
                                                Закупочная:
                                                <b><?php echo $product['delivery_price'] . ' ' . $this->currency_model->currencies[$product['currency_id']]['name']; ?></b><br/>
                                                Дата обновления: <b><?php echo $product['updated_at']; ?></b>
                                            </div>
                                        <?php } ?>
                                    </td>

                                    <td data-search="<?php echo $product['price']; ?>"
                                        data-order="<?php echo $product['price']; ?>" class="search-product-price">
                                        <b><?php echo format_currency($product['price']); ?></b>
                                    </td>
                                    <td data-search="<?php echo $product['quantity']; ?>"
                                        data-order="<?php echo $product['quantity']; ?>" class="search-product-quan">
                                        <b><?php echo format_quantity($product['quantity']); ?></b></td>
                                    <td data-search="<?php echo $product['term']; ?>"
                                        data-order="<?php echo $product['term']; ?>" class="search-product-term"><b
                                                class="<?php echo format_term_class($product['term']); ?>"><?php echo format_term($product['term']); ?></b>
                                    </td>
                                    <td class="search-product-cart">
                                        <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                        <div class="input-group">
                                            <input style="width: 50px;" placeholder="кол." type="text" name="quantity"
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
                                            <a title="<?php echo strip_tags(lang('text_fast_order_link'));?>" class="btn btn-info" href="#"
                                               onclick="fastOrder('/product/<?php echo $product['slug']; ?>?supplier_id=<?php echo $product['supplier_id']; ?>&term=<?php echo $product['term']; ?>',event);"><i class="glyphicon glyphicon-send"></i></a>

                                        </div>
                                        </form>
                                        <small>
                                            <a href="/cart" class="<?php echo $product['key']; ?>"
                                                <?php if (!key_exists(md5($product['key']), $this->cart->contents())) { ?>
                                                    style="display: none;"
                                                <?php } ?>
                                            ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?></a>
                                        </small>
                                    </td>
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

    $(document).click(function(){
        hideTd();
    });


    $(document).ready(function () {
        $("tr").mouseover(function(){
           $(this).find("td").css('visibility','');
           //$(this).
        });

        $("tr").mouseout(function(){
            hideTd();
        });
        var table = $('#example').DataTable({
            paging: false,
            ordering: true,
            info: false,
            searching: true,
            //order: [[ 0, "asc" ],[4,'asc'],[6, 'asc']]
        });

        // Event listener to the two range filtering inputs to redraw on input
        $('#price-min, #price-max, #term-min, #term-max').keyup(function () {
            table.draw();
        });

        $('input[type="checkbox"]').change(function () {
            table.draw();
        });

        hideTd();

        $("#example").fadeIn('slow');

    });



    function hideTd() {
        var tr_product = [];

        $("[data-tr]").each(function(index, item){
            var product_id = $(item).attr('data-tr');
            if(jQuery.inArray(product_id, tr_product) == -1){
                tr_product.push(product_id);
                $(item).children('td').slice(0,4).css('visibility','');
            }else{
                $(item).children('td').slice(0,4).css('visibility','hidden');
            }
        });
    }
</script>