<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .row.item {
        border-bottom: 1px solid whitesmoke;
        margin-bottom: 16px;
        padding-bottom: 12px;
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
            <?php if ($brands){ ?>
            <div class="col-md-3">
                <h4><?php echo lang('text_select_manufacturer'); ?></h4>
                <div id="popover"></div>
                <div class="list-group">
                    <?php foreach ($brands as $brand) { ?>
                        <a href="/search?search=<?php echo $brand['sku']; ?>&ID_art=<?php echo $brand['ID_art']; ?>&brand=<?php echo $brand['brand']; ?>"
                           class="list-group-item <?php if ($this->input->get('brand') == $brand['brand']) { ?> active<?php } ?>"><?php echo $brand['brand']; ?>
                            <br>
                            <small><?php echo $brand['name']; ?></small>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-9">
                <?php }else{ ?>
                <div class="col-md-12">
                    <?php } ?>
                    <?php if ($products || $cross || $about) { ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php if ($products && $products['prices']['items']) { ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion"
                                               href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <span class="badge pull-left">Точное</span>
                                                <?php echo $products['brand'].' '.$products['sku']; ?>
                                                <small class="pull-right">
                                                    Цена:
                                                    <b>
                                                        <?php if ($products['prices']['min_price'] != $products['prices']['max_price']) { ?>
                                                            <?php echo format_currency($products['prices']['min_price']); ?> ... <?php echo format_currency($products['prices']['max_price']); ?>
                                                        <?php }else{?>
                                                            <?php echo format_currency($products['prices']['min_price']); ?>
                                                        <?php } ?></b>
                                                    Доставка:
                                                    <?php if ($products['prices']['min_term'] != $products['prices']['max_term']) { ?>
                                                        <?php echo format_term($products['prices']['min_term']); ?> ... <?php echo format_term($products['prices']['max_term']); ?>
                                                    <?php }else{?>
                                                        <?php echo format_term($products['prices']['min_term']); ?>
                                                    <?php } ?>
                                                </small>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                         aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <?php foreach ($products['prices']['items'] as $item) {
                                                $key = $item['product_id'] . $item['supplier_id'] . $item['term']?>
                                                <?php if ($this->is_admin){?>
                                                    <div class="row">
                                                        <div class="col-md-12 well well-sm">
                                                            <small class="pull-right">Эти данные видит только администратор сайта</small>
                                                            <b>Поставщик: </b><?php echo $this->supplier_model->suppliers[$item['supplier_id']]['name'];?><br/>
                                                            <b>Количество: </b><?php echo $item['quantity'];?><br/>
                                                            <b>Закупочная цена: </b><?php echo $item['delivery_price'].' '.$this->currency_model->currencies[$item['currency_id']]['name'];?><br/>
                                                            <b>Дата обновления: </b><?php echo $item['updated_at'];?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="row item">
                                                    <div class="col-md-4">
                                                        <i onclick="tecdoc_info('<?php echo $products['sku']; ?>', '<?php echo $products['brand']; ?>','info<?php echo $key;?>')" class="fa fa-info-circle"></i>
                                                        <a href="/product/<?php echo $products['slug']; ?>"><?php echo $products['brand'] . ' ' . $products['sku']; ?></a><br>
                                                        <small><?php echo $products['name']; ?></small>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <?php if($item['saleprice'] > 0){?>
                                                            <b><?php echo format_currency($item['saleprice']); ?></b>
                                                            <br/>
                                                            <small><s><?php echo format_currency($item['price']); ?></s></small>
                                                        <?php }else{?>
                                                            <b><?php echo format_currency($item['price']); ?></b>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <b><?php echo format_quantity($item['quantity']); ?></b>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <b><?php echo format_term($item['term']); ?></b><br>
                                                        <small><?php echo $item['excerpt'];?></small>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                                        <div class="input-group">
                                                            <input type="number" name="quantity"
                                                                   class="form-control" value="1">
                                                            <input type="hidden" name="product_id"
                                                                   value="<?php echo $products['id']; ?>">
                                                            <input type="hidden" name="supplier_id"
                                                                   value="<?php echo $item['supplier_id']; ?>">
                                                            <input type="hidden" name="term"
                                                                   value="<?php echo $item['term']; ?>">
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
                                                    </div>
                                                </div>
                                                <div class="row info<?php echo $key;?>"></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($cross) { ?>
                                <?php foreach ($cross as $products) { ?>
                                    <?php if($products['prices']){?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="heading<?php echo $products['id'];?>">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse"
                                                       data-parent="#accordion" href="#collapse<?php echo $products['id'];?>" aria-expanded="false"
                                                       aria-controls="<?php echo $products['id'];?>">
                                                        <span class="badge pull-left">Аналог</span>
                                                        <?php echo $products['brand'].' '.$products['sku']; ?>
                                                        <small class="pull-right">
                                                            Цена:
                                                            <b>
                                                                <?php if ($products['prices']['min_price'] != $products['prices']['max_price']) { ?>
                                                                    <?php echo format_currency($products['prices']['min_price']); ?> ... <?php echo format_currency($products['prices']['max_price']); ?>
                                                                <?php }else{?>
                                                                    <?php echo format_currency($products['prices']['min_price']); ?>
                                                                <?php } ?></b>
                                                            Доставка:
                                                            <?php if ($products['prices']['min_term'] != $products['prices']['max_term']) { ?>
                                                                <?php echo format_term($products['prices']['min_term']); ?> ... <?php echo format_term($products['prices']['max_term']); ?>
                                                            <?php }else{?>
                                                                <?php echo format_term($products['prices']['min_term']); ?>
                                                            <?php } ?>
                                                        </small>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse<?php echo $products['id'];?>" class="panel-collapse collapse" role="tabpanel"
                                                 aria-labelledby="heading<?php echo $products['id'];?>">
                                                <div class="panel-body">
                                                    <?php foreach ($products['prices']['items'] as $item) {
                                                        $key = $item['product_id'] . $item['supplier_id'] . $item['term']?>
                                                        <?php if ($this->is_admin){?>
                                                            <div class="row">
                                                                <div class="col-md-12 well well-sm">
                                                                    <small class="pull-right">Эти данные видит только администратор сайта</small>
                                                                    <b>Поставщик: </b><?php echo $this->supplier_model->suppliers[$item['supplier_id']]['name'];?><br/>
                                                                    <b>Количество: </b><?php echo $item['quantity'];?><br/>
                                                                    <b>Закупочная цена: </b><?php echo $item['delivery_price'].' '.$this->currency_model->currencies[$item['currency_id']]['name'];?><br/>
                                                                    <b>Дата обновления: </b><?php echo $item['updated_at'];?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <div class="row item">
                                                            <div class="col-md-4">
                                                                <i onclick="tecdoc_info('<?php echo $products['sku']; ?>', '<?php echo $products['brand']; ?>','info<?php echo $key;?>')" class="fa fa-info-circle"></i>
                                                                <a href="/product/<?php echo $products['slug']; ?>"><?php echo $products['brand'] . ' ' . $products['sku']; ?></a><br>
                                                                <small><?php echo $products['name']; ?></small>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <?php if($item['saleprice'] > 0){?>
                                                                    <b><?php echo format_currency($item['saleprice']); ?></b>
                                                                    <br/>
                                                                    <small><s><?php echo format_currency($item['price']); ?></s></small>
                                                                <?php }else{?>
                                                                    <b><?php echo format_currency($item['price']); ?></b>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <b><?php echo format_quantity($item['quantity']); ?></b>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <b><?php echo format_term($item['term']); ?></b><br>
                                                                <small><?php echo $item['excerpt'];?></small>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                                                <div class="input-group">
                                                                    <input type="number" name="quantity"
                                                                           class="form-control" value="1">
                                                                    <input type="hidden" name="product_id"
                                                                           value="<?php echo $products['id']; ?>">
                                                                    <input type="hidden" name="supplier_id"
                                                                           value="<?php echo $item['supplier_id']; ?>">
                                                                    <input type="hidden" name="term"
                                                                           value="<?php echo $item['term']; ?>">
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
                                                            </div>
                                                        </div>
                                                        <div class="row info<?php echo $key;?>"></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if($about){?>
                            <table class="table table-condensed">
                                <tbody>
                                <tr>
                                    <td colspan="7" class="heading"><?php echo lang('text_about');?> <small>(<?php echo count($about);?>)</small></td>
                                </tr>
                                <?php foreach($about as $product){
                                    $key = $product['product_id'] . $product['supplier_id'] . $product['term'];?>
                                    <tr>
                                        <td>
                                            <i onclick="tecdoc_info('<?php echo $product['sku'];?>', '<?php echo $product['brand'];?>')" class="fa fa-info-circle"></i>
                                        </td>
                                        <td class="name">
                                            <a target="_blank" href="/product/<?php echo $product['slug'];?>"><?php echo $product['brand'].' '. $product['sku'];?></a>
                                            <br>
                                            <small><?php echo $product['name'];?></small><br>
                                        </td>
                                        <td class="price"><?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice'] : $product['price']);?></td>
                                        <td class="quan"><?php echo format_quantity($product['quantity']);?></td>
                                        <td class="excerpt"><?php echo $product['excerpt'];?></td>
                                        <td class="term"><i class="fa fa-road" title="<?php echo lang('text_search_term');?>"></i><?php echo format_term($product['term']);?></td>
                                        <td class="cart">
                                            <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)']); ?>
                                            <div class="input-group">
                                                <input type="number" name="quantity" class="form-control" value="1">
                                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                <input type="hidden" name="supplier_id" value="<?php echo $product['supplier_id']; ?>">
                                                <input type="hidden" name="term" value="<?php echo $product['term']; ?>">
                                                <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i
                                                    class="fa fa-shopping-cart"></i></button>
                                        </span>
                                            </div>
                                            </form>
                                            <a href="/cart" class="<?php echo $key; ?>"
                                                <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                                    style="display: none;"
                                                <?php } ?>
                                            ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?></a>
                                        </td>
                                    </tr>
                                    <?php if($this->is_admin){?>
                                        <tr>
                                            <td></td>
                                            <td class="name"><?php echo $this->supplier_model->suppliers[$product['supplier_id']]['name'];?></td>
                                            <td class="price">
                                                <?php echo $product['delivery_price'];?>
                                                <?php echo $this->currency_model->currencies[$product['currency_id']]['name'];?>
                                            </td>
                                            <td class="quan"><?php echo $product['quantity'];?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <?php if (!$brands || ($this->input->get('brand') && !$products)) { ?>
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
                            </div>
                            <div class="form-group pull-right">
                                <button type="submit"><?php echo lang('button_send'); ?></button>
                            </div>
                            </form>
                        <?php } ?>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>