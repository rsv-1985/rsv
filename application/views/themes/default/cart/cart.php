<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2><?php echo $this->h1; ?></h2>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End Page title area -->


<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="product-content-right">
                    <div class="woocommerce">
                        <?php if ($deferred) { ?>
                            <button class="btn btn-success" type="button" data-toggle="collapse"
                                    data-target="#collapsedeferred" aria-expanded="false"
                                    aria-controls="collapseExample">
                                Отложенные товары
                            </button>
                            <hr/>
                            <div class="collapse in" id="collapsedeferred">
                                <div class="table-responsive">
                                    <table cellspacing="0" class="shop_table cart">
                                        <thead>
                                        <tr>
                                            <th class="product-remove">&nbsp</th>
                                            <th>&nbsp</th>
                                            <th><?php echo lang('text_sku'); ?></th>
                                            <th><?php echo lang('text_brand'); ?></th>
                                            <th class="product-name"><?php echo lang('text_product'); ?></th>
                                            <th class="product-price"><?php echo lang('text_price'); ?></th>
                                            <th class="product-quantity"><?php echo lang('text_quantity'); ?></th>
                                            <th class="product-subtotal"><?php echo lang('text_subtotal'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($deferred as $item) { ?>
                                            <tr>
                                                <td>
                                                    <a class="remove" style="cursor: pointer"
                                                       onclick="deferred_delete('<?php echo $item['id']; ?>')">×</a>
                                                </td>

                                                <td>
                                                    <input type="checkbox"
                                                           onchange="deferred_delete('<?php echo $item['id']; ?>',<?php echo $item['product_id']; ?>,<?php echo $item['supplier_id']; ?>,<?php echo $item['term']; ?>,<?php echo $item['quantity']; ?>);">
                                                </td>

                                                <td>
                                                    <a href="/product/<?php echo $item['slug']; ?>"><?php echo $item['sku']; ?></a>
                                                </td>
                                                <td>
                                                    <a href="/product/<?php echo $item['slug']; ?>"><?php echo $item['brand']; ?></a>
                                                </td>
                                                <td>
                                                    <a href="/product/<?php echo $item['slug']; ?>"><?php echo $item['name']; ?></a>
                                                    <?php if ($item['excerpt']) { ?>
                                                        <br/><?php echo $item['excerpt']; ?>
                                                    <?php } ?>
                                                </td>

                                                <td>
                                                    <span class="amount"><?php echo format_currency($item['price']); ?></span>
                                                </td>

                                                <td>
                                                    <?php echo $item['quantity']; ?>
                                                </td>

                                                <td>
                                                    <?php echo $item['subtotal']; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <hr/>
                            </div>
                        <?php } ?>
                        <?php if ($this->cart->total_items() > 0) { ?>
                            <div class="table-responsive">
                                <table cellspacing="0" class="shop_table cart">
                                    <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp</th>
                                        <?php if ($this->is_login) { ?>
                                            <th>&nbsp</th>
                                        <?php } ?>
                                        <th><?php echo lang('text_sku'); ?></th>
                                        <th><?php echo lang('text_brand'); ?></th>
                                        <th class="product-name"><?php echo lang('text_product'); ?></th>
                                        <th class="product-price"><?php echo lang('text_price'); ?></th>
                                        <th class="product-quantity"><?php echo lang('text_quantity'); ?></th>
                                        <th class="product-subtotal"><?php echo lang('text_subtotal'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($this->cart->contents() as $key => $item) { ?>
                                        <tr class="cart_item" id="<?php echo $key; ?>">
                                            <td class="product-remove">
                                                <a class="remove" href="#"
                                                   onclick="remove_cart('<?php echo $key; ?>', event)">×</a>
                                            </td>
                                            <?php if ($this->is_login) { ?>
                                                <td>
                                                    <input type="checkbox" checked
                                                           onchange="deferred_add('<?php echo $key; ?>');">
                                                </td>
                                            <?php } ?>
                                            <td>
                                                <a href="/product/<?php echo $item['slug']; ?>"><?php echo $item['sku']; ?></a>
                                            </td>
                                            <td>
                                                <a href="/product/<?php echo $item['slug']; ?>"><?php echo $item['brand']; ?></a>
                                            </td>
                                            <td class="product-name">
                                                <a href="/product/<?php echo $item['slug']; ?>"><?php echo $item['name']; ?></a>
                                                <?php if ($item['excerpt']) { ?>
                                                    <br/><?php echo $item['excerpt']; ?>
                                                <?php } ?>
                                            </td>

                                            <td class="product-price">
                                                <span class="amount"><?php echo format_currency($item['price']); ?></span>
                                            </td>

                                            <td class="product-quantity">
                                                <div class="quantity buttons_added">
                                                    <input type="button" class="minus" value="-"
                                                           onclick="minus('<?php echo $key; ?>')">
                                                    <input style="width: 51px" type="text" id="quan<?php echo $key; ?>"
                                                           onchange="quan('<?php echo $key; ?>')" size="4"
                                                           class="input-text qty" value="<?php echo $item['qty']; ?>"
                                                           min="0" step="1">
                                                    <input type="button" class="plus" value="+"
                                                           onclick="plus('<?php echo $key; ?>')">
                                                </div>
                                            </td>

                                            <td class="product-subtotal">
                                            <span class="amount"
                                                  id="subtotal<?php echo $key; ?>"><?php echo format_currency($item['subtotal']); ?></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                            <?php echo form_open('', ['id' => 'cart']); ?>
                            <div class="cart-collaterals">
                                <div class="cross-sells">
                                    <h2><?php echo lang('text_customer_info'); ?></h2>
                                    <div class="form-group">
                                        <label><?php echo lang('text_delivery_method'); ?></label>
                                        <select id="delivery" class="form-control" name="delivery_method"
                                                onchange="get_delivery();"
                                                required>
                                            <option></option>
                                            <?php foreach ($delivery as $delivery) { ?>
                                                <option
                                                        id="delivery-<?php echo $delivery['id']; ?>"
                                                        value="<?php echo $delivery['id']; ?>" <?php echo set_select('delivery_method', $delivery['id']); ?>><?php echo $delivery['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <small id="delivery_description"></small>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_payment_method'); ?></label>
                                        <select disabled id="payment" class="form-control" name="payment_method"
                                                onchange="get_payment();"
                                                required>
                                            <option></option>
                                            <?php if ($this->is_login) { ?>
                                                <option selected value="0" id="payment-0">С личного баланса</option>
                                            <?php } ?>
                                            <?php foreach ($payment as $payment) { ?>
                                                <option
                                                        id="payment-<?php echo $payment['id']; ?>"
                                                        value="<?php echo $payment['id']; ?>" <?php echo set_select('payment_method', $payment['id']); ?>><?php echo $payment['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <small id="payment_description"></small>
                                    </div>
                                    <br>
                                    <hr>
                                    <div class="form-group">
                                        <label><?php echo lang('text_first_name'); ?></label>
                                        <input required type="text" class="form-control" name="first_name"
                                               value="<?php echo set_value('first_name', @$customer['first_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_last_name'); ?></label>
                                        <input required type="text" class="form-control" name="last_name"
                                               value="<?php echo set_value('last_name', @$customer['second_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_patronymic'); ?></label>
                                        <input type="text" class="form-control" name="patronymic"
                                               value="<?php echo set_value('patronymic', @$customer['patronymic']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_telephone'); ?></label>
                                        <input required type="text" class="form-control" name="telephone"
                                               value="<?php echo set_value('telephone', @$customer['phone']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email"
                                               value="<?php echo set_value('email', @$customer['email']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('text_address'); ?></label>
                                        <input type="text" class="form-control" name="address"
                                               value="<?php echo set_value('address', @$customer['address']); ?>">
                                    </div>

                                </div>
                                <div class="cart_totals ">
                                    <h2><?php echo lang('text_cart_total'); ?></h2>
                                    <table cellspacing="0">
                                        <tbody>
                                        <tr class="cart-subtotal">
                                            <th><?php echo lang('text_subtotal'); ?></th>
                                            <td><span class="amount"
                                                      id="subtotal"><?php echo format_currency($this->cart->total()); ?></span>
                                            </td>
                                        </tr>

                                        <tr class="shipping">
                                            <th><?php echo lang('text_shipping'); ?></th>
                                            <td><span id="shipping-cost"><?php echo format_currency(0); ?></span></td>
                                        </tr>

                                        <tr class="payment">
                                            <th><?php echo lang('text_commissionpay'); ?></th>
                                            <td><span id="commission-pay"><?php echo format_currency(0); ?></span></td>
                                        </tr>

                                        <tr class="order-total">
                                            <th><?php echo lang('text_cart_total'); ?></th>
                                            <td><strong><span
                                                            class="total"><?php echo format_currency($this->cart->total()); ?></span></strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="form-group">
                                        <label><?php echo lang('text_comment'); ?></label>
                                        <textarea name="comment" style="height: 342px;"
                                                  class="form-control"><?php echo set_value('comment'); ?></textarea>
                                    </div>
                                    <?php if ($terms_of_use) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="terms_of_use" value="1" required> <a
                                                        data-toggle="modal" data-target="#myModal">Пользовательское
                                                    соглашение</a>
                                            </label>
                                        </div>
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Пользовательское
                                                            соглашение</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo $terms_of_use; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <a href="/cart/clear_cart"
                                       class="btn btn-danger pull-left"><?php echo lang('text_clear_cart'); ?></a>
                                    <button class="btn pull-right"
                                            type="submit"><?php echo lang('button_order'); ?></button>
                                </div>
                            </div>
                            </form>
                        <?php } else { ?>
                            <?php echo lang('text_empty_cart'); ?>
                        <?php } ?>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<script>

    function remove_cart(key, event) {
        event.preventDefault();
        $.ajax({
            url: '/cart/remove_cart',
            method: 'POST',
            dataType: 'json',
            data: {key: key},
            success: function (json) {
                $("#" + key).remove();
                total();
            }
        });
    }

    function plus(key) {
        var quan = $("#quan" + key).val();
        quan++;
        $("#quan" + key).val(quan);
        $.ajax({
            url: '/cart/update_cart',
            method: 'POST',
            dataType: 'json',
            data: {key: key, quan: quan},
            success: function (json) {
                $("#subtotal" + key).html(json['product_subtotal']);
                total();
            }
        });

    }

    function minus(key) {
        var quan = $("#quan" + key).val();
        quan--;
        if (quan < 1) {
            quan = 1;
        }
        $("#quan" + key).val(quan);
        $.ajax({
            url: '/cart/update_cart',
            method: 'POST',
            dataType: 'json',
            data: {key: key, quan: quan},
            success: function (json) {
                $("#subtotal" + key).html(json['product_subtotal']);
                total();
            }
        });
    }

    function quan(key) {
        var quan = $("#quan" + key).val();
        $.ajax({
            url: '/cart/update_cart',
            method: 'POST',
            dataType: 'json',
            data: {key: key, quan: quan},
            success: function (json) {
                $("#subtotal" + key).html(json['product_subtotal']);
                total();
            }
        });
    }

    function get_delivery() {
        $.ajax({
            url: '/cart/total_cart',
            method: 'POST',
            dataType: 'json',
            data: $("#cart").serialize(),
            success: function (json) {
                $(".total").html(json['total']);
                $(".cart-amunt").html(json['total']);
                $("#subtotal").html(json['subtotal']);
                $("#shipping-cost").html(json['delivery_price']);
                $("#delivery_description").html(json['delivery_description']);
                if (json['link_payments'].length) {
                    //Связка способов оплаты с выбранным способом доставки
                    $("#payment option").each(function () {
                        if (jQuery.inArray($(this).attr('value'), json['link_payments']) == -1) {
                            $("#payment-" + $(this).attr('value')).attr('disabled', 'disabled');
                        } else {
                            $("#payment-" + $(this).attr('value')).removeAttr('disabled');
                        }
                    });
                }

                if (<?php echo (int)@$this->customer_model->negative_balance;?> || <?php echo (int)@$this->customer_model->balance;?> >=
                json['total_val']
            )
                {
                    $("#payment-0").removeAttr('disabled');
                }
            else
                {
                    $("#payment-0").attr('disabled', 'disabled');
                    if ($("#payment-0").is(':selected')) {
                        $('#payment').prop('selectedIndex', 0);
                    }
                }

                $("#payment").removeAttr('disabled');
            }
        });
    }

    function get_payment() {
        $.ajax({
            url: '/cart/total_cart',
            method: 'POST',
            dataType: 'json',
            data: $("#cart").serialize(),
            success: function (json) {
                $(".total").html(json['total']);
                $(".cart-amunt").html(json['total']);
                $("#subtotal").html(json['subtotal']);
                $("#commission-pay").html(json['commissionpay']);
                $(".product-count").html(json['total_items']);
                $("#payment_description").html(json['payment_description']);
            }
        });
    }

    function total() {
        $.ajax({
            url: '/cart/total_cart',
            method: 'POST',
            dataType: 'json',
            data: $("#cart").serialize(),
            success: function (json) {
                if (json['total_items'] == 0) {
                    location.reload();
                }

                if (<?php echo (int)@$this->customer_model->negative_balance;?> || <?php echo (int)@$this->customer_model->balance;?> >=
                json['total_val']
            )
                {
                    $("#payment-0").removeAttr('disabled');
                }
            else
                {
                    $("#payment-0").attr('disabled', 'disabled');
                    if ($("#payment-0").is(':selected')) {
                        $('#payment').prop('selectedIndex', 0);
                    }
                }

                $(".total").html(json['total']);
                $(".cart-amunt").html(json['total']);
                $("#subtotal").html(json['subtotal']);
                $("#shipping-cost").html(json['delivery_price']);
                $("#commission-pay").html(json['commissionpay']);
                $(".product-count").html(json['total_items']);
                $("#delivery_description").html(json['delivery_description']);
            }
        });
    }

    //В корзине отложоть товары
    function deferred_add(cart_key) {
        $.ajax({
            url: '/cart/deferred_add',
            data: {cart_key: cart_key},
            success: function (response) {
                remove_cart(cart_key, event);
                location.reload();
            }
        })
    }

    function deferred_delete(deferred_id, product_id, supplier_id, term, quantity) {
        $.ajax({
            url: '/cart/deferred_delete',
            data: {deferred_id: deferred_id},
            success: function () {
                if (product_id) {
                    $.ajax({
                        url: '/cart/add_cart',
                        method: 'POST',
                        data: {product_id: product_id, supplier_id: supplier_id, term: term, quantity: quantity}
                    });
                }
                location.reload();
            }
        });
    }
</script>