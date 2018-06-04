<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open('', ['id' => 'cart']); ?>
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
                        <?php if ($this->cart->total_items() > 0) { ?>
                            <div class="table-responsive">
                                <table cellspacing="0" class="shop_table cart">
                                    <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp</th>
                                        <th><input type="checkbox" checked onchange="select_all(this);"></th>
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

                                            <td>
                                                <input type="checkbox" checked name="deferred[]"
                                                       value="<?php echo $key; ?>">
                                            </td>

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
                                    <tr>
                                        <td colspan="6"></td>
                                        <td id="qty"></td>
                                        <td id="sum"><?php echo format_currency($this->cart->total());?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                            <a href="/cart/clear_cart"
                               class="btn btn-danger pull-left"><?php echo lang('text_clear_cart'); ?></a>
                            <button class="btn pull-right pre_cart"
                                    type="submit"><?php echo lang('button_order'); ?></button>
                        <?php } else { ?>
                            <?php echo lang('text_empty_cart'); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script>

    $(document).ready(function(){
        $("[name='deferred[]']").change(function(){
            var checked = false;
            $("[name='deferred[]']").each(function (index, item) {
                if($(item).prop('checked')){
                    checked = true;
                }
            });

            if(!checked){
                $(".pre_cart").fadeOut();
            }else{
                $(".pre_cart").fadeIn();
            }

            total();
        });
    });

    function select_all(obj) {
        $("[name='deferred[]']").each(function (index, item) {
            $(item).prop('checked', $(obj).prop('checked')).change();
        });
    }

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

    function total() {
        var sum = 0;
        var qty = 0;
        $("[name='deferred[]']").each(function (index, item) {
            if( $(item).prop('checked')){
                sum += parseFloat($("#subtotal"+$(item).val()).text());
                qty += parseFloat($("#quan"+$(item).val()).val());
            }
        });

        $("#sum").text(sum);
        $("#qty").text(qty);
    }


</script>