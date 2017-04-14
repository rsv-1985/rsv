<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<style>
    td{
        word-break: break-all;
    }
</style>
<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="container">
        <div class="row">
            <?php if ($brands) { ?>
                <div class="col-md-12 brands" style="text-align: center;">
                    <h3><?php echo lang('text_select_manufacturer'); ?></h3>
                    <?php foreach ($brands as $brand) { ?>
                        <a href="/search?search=<?php echo $brand['sku']; ?>&ID_art=<?php echo $brand['ID_art']; ?>&brand=<?php echo $brand['brand']; ?>"
                           class="btn <?php if ($this->input->get('brand') == $brand['brand']) { ?> btn-info<?php } else { ?> btn-default<?php } ?>"><?php echo $brand['brand']; ?>
                            <br>
                            <small><?php echo $brand['name']; ?></small><br>
                            <?php if ($this->input->get('brand') !== $brand['brand']) { ?>
                            <b style="color: #31708f">Поиск</b>
                            <?php }else{?>
                                <b>Найдено (<?php echo count($products);?>)</b>
                            <?php } ?>
                        </a>
                    <?php } ?>
                    <hr>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <?php if($products){?>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Фильтр
                    </div>
                    <div class="form-group">
                        <label>Цена</label><br/>
                        <input id="price-min" style="width: 45%;" type="number" placeholder="от">-
                        <input id="price-max" style="width: 45%;" type="number" placeholder="до">
                    </div>

                    <div class="form-group">
                        <label>Срок поставки (час.)</label><br/>
                        <input id="term-min" style="width: 45%;" type="number" placeholder="от">-
                        <input id="term-max" style="width: 45%;" type="number" placeholder="до">
                    </div>
                    <?php if($filter_brands){?>
                        <div class="form-group" style="max-height: 300px;overflow: auto;">
                            <label>Производитель</label>
                            <?php foreach ($filter_brands as $fb){?>
                            <div class="checkbox">
                                <label>
                                    <input class="filter-brand" type="checkbox" value="<?php echo $fb;?>">
                                    <?php echo $fb;?>
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-9">
                <?php }else{?>
                <div class="col-md-12">
                <?php } ?>
                <?php if($products){?>
                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Тип</th>
                        <th>Код</th>
                        <th>Бренд</th>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Наличие</th>
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
                        <th>Наличие</th>
                        <th>Срок</th>
                        <th>Купить</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php foreach ($products as $product){
                        $key = $product['id'] . $product['supplier_id'] . $product['term']?>
                        <tr>
                            <td data-order="<?php echo $product['cross'];?>">
                                <span class="badge pull-left"><?php echo $product['cross'] ? 'Аналог' : 'Точное';?></span>
                            </td>
                            <td><a href="/product/<?php echo $product['slug'];?>"><?php echo $product['sku'];?></a></td>
                            <td><a href="/product/<?php echo $product['slug'];?>"><?php echo $product['brand'];?></a></td>
                            <td>
                                <a href="/product/<?php echo $product['slug'];?>"><?php echo $product['name'];?></a>
                                <?php if($product['excerpt']){?>
                                    <br><small><?php echo $product['excerpt'];?></small>
                                <?php } ?>
                                <?php if($this->is_admin){?>
                                    <br>
                                    <div class="well well-sm" style="font-size: 11px">
                                        <p>Данный раздел видит только администратор</p>
                                        <p>
                                            Поставщик: <a target="_blank" href="/autoxadmin/supplier/edit/<?php echo $product['supplier_id'];?>"><?php echo $this->supplier_model->suppliers[$product['supplier_id']]['name'];?></a><br/>
                                            Закупочная: <b><?php echo $product['delivery_price'].' '.$this->currency_model->currencies[$product['currency_id']]['name'];?></b><br/>
                                            Дата обновления: <b><?php echo $product['updated_at'];?></b>
                                        </p>
                                    </div>
                                <?php } ?>
                            </td>
                            <td data-search="<?php echo $product['price'];?>" data-order="<?php echo $product['price'];?>">
                                <b><?php echo format_currency($product['price']);?></b>
                            </td>
                            <td data-search="<?php echo $product['quantity'];?>" data-order="<?php echo $product['quantity'];?>"><b><?php echo format_quantity($product['quantity']);?></b></td>
                            <td data-search="<?php echo $product['term'];?>" data-order="<?php echo $product['term'];?>"><b><?php echo format_term($product['term']);?></b></td>
                            <td>
                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                <div class="input-group">
                                    <input type="text" name="quantity"
                                           class="form-control">
                                    <input type="hidden" name="product_id"
                                           value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="supplier_id"
                                           value="<?php echo $product['supplier_id']; ?>">
                                    <input type="hidden" name="term"
                                           value="<?php echo $product['term']; ?>">
                                    <span class="input-group-btn">
                                                                    <button class="btn btn-default" type="submit"><i
                                                                                class="fa fa-shopping-cart"></i></button>
                                                                    </span>
                                </div>
                                </form>
                                <small>
                                    <a href="/cart" class="<?php echo $key; ?>"
                                        <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                            style="display: none;"
                                        <?php } ?>
                                    ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?></a>
                                </small>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php }else{?>
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
                            <input type="email" name="emil" class="form-control" required/>
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
        function( settings, data, dataIndex ) {
            var min = parseInt( $('#price-min').val(), 10 );
            var max = parseInt( $('#price-max').val(), 10 );
            var price = parseFloat( data[4] ) || 0; // use data for the price column

            if ( ( isNaN( min ) && isNaN( max ) ) ||
                ( isNaN( min ) && price <= max ) ||
                ( min <= price   && isNaN( max ) ) ||
                ( min <= price   && price <= max ) )
            {
                return true;
            }
            return false;
        }
    );

    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = parseInt( $('#term-min').val(), 10 );
            var max = parseInt( $('#term-max').val(), 10 );
            var term = parseFloat( data[6] ) || 0; // use data for the price column

            if ( ( isNaN( min ) && isNaN( max ) ) ||
                ( isNaN( min ) && term <= max ) ||
                ( min <= term   && isNaN( max ) ) ||
                ( min <= term   && term <= max ) )
            {
                return true;
            }
            return false;
        }
    );

    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var search_brand = [];
            $( ".filter-brand:checked" ).each(function( index ) {
                search_brand[index]= $( this ).val();
            });


            var brand = data[2]  || 0; // use data for the price column
            console.log(search_brand.indexOf(brand));
            console.log(brand);
            if (search_brand.indexOf(brand) != '-1' || !search_brand.length)
            {
                return true;
            }
            return false;
        }
    );


    $(document).ready(function() {
        var table = $('#example').DataTable({
            paging:   false,
            ordering: true,
            info:     false,
            searching: true,
            order: [[ 0, "asc" ],[4, 'asc'],[6,'asc']]
        });

        // Event listener to the two range filtering inputs to redraw on input
        $('#price-min, #price-max, #term-min, #term-max').keyup( function() {
            table.draw();
        } );

        $('input[type="checkbox"]').change(function(){
            table.draw();
        })

    } );
</script>