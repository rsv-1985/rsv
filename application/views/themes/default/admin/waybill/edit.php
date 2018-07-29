<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home'); ?></a></li>
        <li><a href="/autoxadmin/waybill"><?php echo lang('text_heading'); ?></a></li>
        <li><a href="#"><?php echo lang('button_edit'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open('', ['method' => 'get']); ?>
            <div class="box">
                <div class="box-body">
                    <h3>Заказы на отгрузку</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Способ доставки</label>
                                <select name="delivery_method_id" class="form-control">
                                    <option></option>
                                    <?php foreach ($delivery_methods as $dm) { ?>
                                        <option <?php echo set_select('delivery_method_id', $dm['id'], $dm['id'] == $this->input->get('delivery_method_id')); ?>
                                                value="<?php echo $dm['id']; ?>"><?php echo $dm['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Статус товара</label>
                                <select name="status_id" class="form-control">
                                    <?php foreach ($order_statuses as $os) { ?>
                                        <option <?php echo set_select('status_id', $os['id'], $os['id'] == $this->input->get('status_id')); ?>
                                                value="<?php echo $os['id']; ?>"><?php echo $os['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Статус оплаты</label>
                            <select name="paid" class="form-control">
                                <option></option>
                                <option value="1" <?php echo set_select('paid', 1, '1' === $this->input->get('paid')); ?>>
                                    Оплачен
                                </option>
                                <option value="0" <?php echo set_select('paid', 0, '0' === $this->input->get('paid')); ?>>
                                    Не оплачен
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="#" class="btn btn-danger">Сброс</a>
                                <button type="submit" class="btn btn-info">Фильтр</button>
                            </div>

                        </div>
                    </div>
                    <?php if ($orders) { ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Номер заказ</th>
                                <th>Получатель</th>
                                <th>Способ доставки</th>
                                <th>Способ оплаты</th>
                                <th>Товары</th>
                                <th>Статус оплаты</th>
                                <th>
                                    <button onclick="addAllOrder(event);location.reload();" class="btn btn-default pull-right">Добавить все</button>
                                </th>
                            </tr>
                            <?php foreach ($orders as $order) { ?>
                                <tr id="row<?php echo $order['id']; ?>">
                                    <td><a href="/autoxadmin/order/edit/<?php echo $order['id']; ?>"
                                           target="_blank"><?php echo $order['id']; ?></a></td>
                                    <td>
                                        <?php echo $order['first_name'] . ' ' . $order['last_name'] . ' ' . $order['patronymic']; ?>
                                        <br>
                                        <?php echo $order['telephone']; ?><br>
                                        <?php echo $order['address']; ?>
                                        <?php if ($order['customer_info']) { ?>
                                            <br>
                                            <small><b><?php echo $order['customer_info']['id']; ?></b>
                                                (<?php echo $order['customer_info']['balance']; ?>)
                                            </small>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $delivery_methods[$order['delivery_method_id']]['name']; ?></td>
                                    <td><?php echo $payment_methods[$order['payment_method_id']]['name']; ?></td>
                                    <td>
                                        <?php if ($order['products']) { ?>
                                            <ul>
                                                <?php foreach ($order['products'] as $product) { ?>
                                                    <li><?php echo $product['sku'] . ' ' . $product['brand'] . ' ' . $product['name']; ?>
                                                        (<?php echo $product['quantity']; ?>
                                                        шт) <?php echo $product['sname']; ?></li>
                                                <?php } ?>
                                            </ul>
                                        <?php } else { ?>
                                            Все товары с этого заказа добавлены в путевой лист
                                        <?php } ?>

                                    </td>
                                    <td>
                                        <?php if ($order['paid']) { ?>
                                            <br><b style="color: green">Оплачен</b>
                                        <?php } else { ?>
                                            <b style="color: red">Не оплачен</b>
                                        <?php } ?>
                                        <br>
                                        <small>Сумма заказа:<?php echo $order['total']; ?></small>
                                        <br>
                                        <small>Предоплата:<?php echo $order['prepayment']; ?></small>
                                        <br>
                                        <small>Остаток:<?php echo $order['total'] - $order['prepayment']; ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-default pull-right add-order"
                                                onclick="addOrder(<?php echo $order['id']; ?>,<?php echo $waybill_id; ?>, <?php echo $this->input->get('status_id'); ?>, event)">
                                            Добавить в путевой
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </thead>
                        </table>
                    <?php } ?>

                </div>
            </div>
            </form>
            <?php echo form_open('', ['method' => 'post']); ?>
            <div class="box">
                <div class="box-body">
                    <h3>Заказы в путевом листе</h3>
                    <?php if ($parcels) { ?>
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Посылка</th>
                                <th>Получатель</th>
                                <th>Способ доставки</th>
                                <th>Способ оплаты</th>
                                <th>Товары</th>
                                <th>Номер ТТН</th>
                            </tr>
                            <?php foreach ($parcels as $parcel) { ?>
                                <tr>
                                    <td><a href="#" class="btn btn-danger btn-xs" title="Удалить посылку"
                                           onclick="delete_waybill_parcel(<?php echo $parcel['id']; ?>)"><i
                                                    class="fa fa-trash-o"></i></a></td>
                                    <td><?php echo $parcel['id']; ?></td>
                                    <td><?php echo $parcel['first_name'] . ' ' . $parcel['last_name'] . ' ' . $parcel['patronymic']; ?>
                                        <br>
                                        <?php echo $parcel['telephone']; ?><br>
                                        <?php echo $parcel['address']; ?>
                                        <?php if($parcel['customer_info']){?>
                                            <br><small>ID <a target="_blank" href="/autoxadmin/customer/edit/<?php echo $parcel['customer_info']['id'];?>"> <?php echo $parcel['customer_info']['id'];?></a> баланс:(<?php echo $parcel['customer_info']['balance'];?>)</small>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $parcel['delivery_method']; ?></td>
                                    <td><?php echo $parcel['payment_method']; ?></td>
                                    <td>
                                        <?php if ($parcel['products']) { ?>
                                            <ul>
                                                <?php foreach ($parcel['products'] as $product) { ?>
                                                    <li><?php echo $product['sku'] . ' ' . $product['brand'] . ' ' . $product['name']; ?>
                                                        (<?php echo $product['quantity']; ?>шт)
                                                        (<?php echo $product['sname']; ?>) <a href="#"
                                                                                              class="btn btn-danger btn-xs"
                                                                                              title="Удалить товар с посылки"
                                                                                              onclick="delete_waybill_product(<?php echo $product['id']; ?>);"><i
                                                                    class="fa fa-trash-o"></i></a></li>
                                                <?php } ?>
                                            </ul>
                                        <?php } else { ?>
                                            В данной посылке нет товаров
                                        <?php } ?>
                                    </td>
                                    <td><input name="ttn[<?php echo $parcel['id']; ?>]"
                                               value="<?php echo $parcel['ttn']; ?>" type="text" class="form-control">
                                    </td>
                                </tr>
                            <?php } ?>
                            </thead>
                        </table>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Заметки</label>
                                <textarea rows="5" name="notes"
                                          class="form-control"><?php echo $waybill['notes']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Статус путевого листа</label>
                                <select name="status_id" class="form-control">
                                    <?php foreach ($this->waybill_model->statuses as $status_id => $status_name) { ?>
                                        <option value="<?php echo $status_id; ?>" <?php echo set_select('status_id', $status_id, $status_id == $waybill['status_id']); ?>><?php echo $status_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Статус товаров после сохранения</label>
                                <select name="order_product_status_id" class="form-control">
                                    <option></option>
                                    <?php foreach ($order_statuses as $os) { ?>
                                        <option value="<?php echo $os['id']; ?>"><?php echo $os['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="set_order_status" value="1">Применить к заказам
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="pull-right">
                                    <a href="/autoxadmin/waybill/print/<?php echo $waybill_id; ?>" target="_blank"
                                       class="btn btn-default">Печать</a>
                                    <button class="btn btn-info" type="submit">Сохранить</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div>
            </form>
        </div>
    </div>
</section><!-- /.content -->
<script>
    function delete_waybill_parcel(id) {
        $.ajax({
            url: '/autoxadmin/waybill/delete_waybill_parcel',
            method: 'post',
            data: {
                id: id
            },
            success: function () {
                location.reload();
            }
        });
    }

    function delete_waybill_product(id) {
        $.ajax({
            url: '/autoxadmin/waybill/delete_waybill_product',
            method: 'post',
            data: {
                id: id
            },
            success: function () {
                location.reload();
            }
        });
    }
    var reload = true;

    function addAllOrder(e) {
        reload = false;
        e.preventDefault();
        $(".add-order").each(function (index,item) {
            $(item).click();
        });
    }

    function addOrder(order_id, waybill_id, status_id, event) {
        event.preventDefault();
        $.ajax({
            url: '/autoxadmin/waybill/add_order',
            method: 'post',
            data: {
                order_id: order_id,
                waybill_id: waybill_id,
                status_id: status_id
            },
            success: function (json) {
                if (json['success']) {
                    if(reload){
                        alert(json['success']);
                        location.reload();
                    }
                } else {
                    alert(json['error']);
                }
            }
        });

    }
</script>