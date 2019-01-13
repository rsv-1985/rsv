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
        <li><a href="#"><?php echo lang('text_heading'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="well well-sm">
                <?php if($this->input->get('customer_id')){?>
                    Клиент ID <?php echo$this->input->get('customer_id');?>
                <?php } ?>
                <?php echo $this->load->view('admin/widget/_status_totals', ['statuses' => $status, 'status_totals' => $status_totals], true);?>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Товары в заказах</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <div class="well well-sm">
                        <?php echo form_open('/autoxadmin/order/products', ['method' => 'GET']); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Номер заказа</label>
                                    <input type="text" name="order_id" class="form-control"
                                           value="<?php echo $this->input->get('order_id', true); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Артикул</label>
                                    <input type="text" name="sku" class="form-control"
                                           value="<?php echo $this->input->get('sku', true); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Статус детали</label>
                                    <select name="product_status_id" class="form-control">
                                        <option></option>
                                        <?php foreach ($status as $s) { ?>
                                            <option value="<?php echo $s['id']; ?>" <?php echo set_select('status_id', $s['id'], $s['id'] == $this->input->get('product_status_id')); ?>><?php echo $s['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Статус заказа</label>
                                    <select name="status_id" class="form-control">
                                        <option></option>
                                        <?php foreach ($status as $s) { ?>
                                            <option value="<?php echo $s['id']; ?>" <?php echo set_select('status_id', $s['id'], $s['id'] == $this->input->get('status_id')); ?>><?php echo $s['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Поставщик</label>
                                    <select multiple name="supplier_id[]" class="form-control" style="height: 108px;">
                                        <option></option>
                                        <?php foreach ($suppliers as $supplier) { ?>
                                            <option value="<?php echo $supplier['id']; ?>" <?php echo set_select('supplier_id', $supplier['id'], @in_array($supplier['id'], $this->input->get('supplier_id'))); ?>><?php echo $supplier['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>ID клиента</label>
                                    <input type="text" name="customer_id" class="form-control"
                                           value="<?php echo $this->input->get('customer_id', true); ?>">
                                </div>
                                <div class="pull-right">
                                    <?php if ($this->input->get()) { ?>
                                        <a href="/autoxadmin/order/products" type="button"
                                           class="btn btn-danger"><?php echo lang('button_reset'); ?></a>
                                    <?php } ?>
                                    <button type="submit"
                                            class="btn btn-info"><?php echo lang('button_search'); ?></button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <?php if ($this->input->get()) { ?>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-success" data-toggle="modal"
                                           data-target="#change-status"><i class="fa fa-edit"></i> </a>
                                        <button onclick="downloadXls()" class="btn btn-success" title="Скачать в XLS"><i
                                                    class="fa fa-file-excel-o"></i></button>
                                        <a onclick="addInvoiceByFilter();" href="" class="btn btn-success" title="Добавить в расходную"><i
                                                    class="fa fa-file-text-o"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-condensed table-hover">
                            <tbody>
                            <tr>
                                <th>Дата</th>
                                <th>Заказ №</th>
                                <th>Клиент</th>
                                <th>Артикул</th>
                                <th>Производитель</th>
                                <th>Название</th>
                                <th><?php echo lang('text_delivery_price');?></th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th><?php echo lang('text_subtotal');?></th>
                                <th>Поставщик</th>
                                <th>Статус товара</th>
                                <th></th>
                            </tr>

                            <?php if ($products) { ?>
                                <?php foreach ($products as $product) { ?>
                                    <?php echo form_open(null, ['class' => 'product-form']); ?>
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <tr style="border-left: 5px solid <?php echo @$status[$product['status_id']]['color']; ?>">
                                        <td>
                                            <?php echo format_date($product['created_at']);?>
                                        </td>
                                        <td>
                                            <a
                                               href="/autoxadmin/order/edit/<?php echo $product['order_id']; ?>">
                                                <?php echo $product['order_id']; ?>
                                            </a>
                                            <small><br><?php echo $status[$product['status']]['name']; ?></small>
                                        </td>
                                        <td>
                                            <?php if($text_negative_balance = $this->customer_model->checkNegativeBalance($product['customer_id'])){ ?>
                                                <i class="glyphicon glyphicon-info-sign" style="color: red;" title="<?php echo $text_negative_balance;?>"></i>
                                            <?php } ?>
                                            <?php if($text_вeferment_payment = $this->customer_model->checkDefermentPayment($product['customer_id'])){ ?>
                                                <i class="glyphicon glyphicon-info-sign" style="color: red;" title="<?php echo $text_вeferment_payment;?>"></i>
                                            <?php } ?>
                                            <a href="/autoxadmin/customer/edit/<?php echo $product['customer_id']; ?>">
                                                <?php echo $product['customer_name']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $product['sku']; ?></td>
                                        <td><?php echo $product['brand']; ?></td>
                                        <td>
                                            <a style="color: <?php echo @$status[$product['status_id']]['color']; ?>"
                                               href="/autoxadmin/product/edit/<?php echo $product['product_id']; ?>"
                                               target="_blank">
                                                <?php echo character_limiter($product['name'], 30); ?>
                                            </a>
                                        </td>

                                        <td><?php echo $product['delivery_price']; ?></td>
                                        <td><?php echo $product['price']; ?></td>
                                        <td><?php echo $product['quantity']; ?></td>
                                        <td><?php echo $product['price'] * $product['quantity']; ?></td>
                                        <td><?php echo @$suppliers[$product['supplier_id']]['name']; ?></td>
                                        <td>
                                            <div class="form-group">
                                                <select name="status_id" class="form-control">
                                                    <option></option>
                                                    <?php foreach ($status as $s) { ?>
                                                        <option value="<?php echo $s['id']; ?>" <?php echo set_select('status_id', $s['id'], $s['id'] == $product['status_id']); ?>><?php echo $s['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width: 100px">
                                            <div class="btn-group">
                                                <?php if(!$product['invoice_id']){?>
                                                    <a onclick="addInvoiceByItem(<?php echo $product['id'];?>)" href="" class="btn btn-success"
                                                       title="<?php echo lang('button_invoice'); ?>"><i
                                                                class="fa fa-file-text-o"></i></a>
                                                    <button type="submit" class="btn btn-info"
                                                            title="<?php echo lang('button_submit'); ?>"><i
                                                                class="fa fa-save"></i></button>
                                                <?php }else{?>
                                                    <a href="/autoxadmin/invoice/edit/<?php echo $product['invoice_id'];?>">
                                                        <?php echo lang('text_invoice');?><?php echo $product['invoice_id'];?>
                                                    </a>
                                                <?php } ?>


                                            </div>
                                        </td>
                                    </tr>
                                    </form>
                                <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">


                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
<div class="modal" id="import-status">
    <?php echo form_open_multipart('/autoxadmin/order/importstatus'); ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title">Импорт статусов</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <a href="/uploads/example/status.xls" class="pull-right">Скачать пример файла</a>
                        <div class="form-group">
                            <label>Фийл</label>
                            <input type="file" name="userfile" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Статус товара для сравнения</label>
                            <select name="status_id" class="form-control">
                                <?php foreach ($status as $st) { ?>
                                    <option value="<?php echo $st['id']; ?>"><?php echo $st['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Новый статус товара</label>
                            <select name="new_status_id" class="form-control">
                                <?php foreach ($status as $st) { ?>
                                    <option value="<?php echo $st['id']; ?>"><?php echo $st['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Поставщик</label>
                            <select name="supplier_id" class="form-control">
                                <?php foreach ($suppliers as $supplier) { ?>
                                    <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-primary">Загрузить</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </form>
</div>
<div class="modal" id="change-status">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title">Смена статусов</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo form_open('/autoxadmin/order/change_status_products?' . http_build_query($this->input->get()), ['id' => 'change_status_products']); ?>
                        <div class="form-group">
                            <?php if ($this->input->get()) { ?>
                                <label>Применить к отфильтрованным:</label>
                            <?php } else { ?>
                                <label>Применить ко всем:</label>
                            <?php } ?>
                            <select name="status_id" class="form-control">
                                <?php foreach ($status as $s) { ?>
                                    <option value="<?php echo $s['id']; ?>" <?php echo set_select('status_id'); ?>><?php echo $s['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                <button type="submit" form="change_status_products" class="btn btn-primary">Загрузить</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function () {
        $(".product-form").submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                method: 'post',
                success: function (response) {
                    alert(response);
                }
            });
        });
        $("#change_status_products").submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                method: 'post',
                success: function (response) {
                    if (response == 'success') {
                        location.reload();
                    }
                }
            });
        });
    });
    function addInvoiceByFilter(){
        if(confirm('Продолжить ?')){
            $.ajax({
                url: '/autoxadmin/invoice/add?<?php echo http_build_query($this->input->get());?>',
                data: {type:'filter'},
                method: 'post',
                success: function (response) {
                    alert(response);
                    location.reload();
                }
            })
        }else{
            event.preventDefault();
        }

    }

    function downloadXls() {
        var data = '<?php echo http_build_query($this->input->get());?>';
        location.href = '/autoxadmin/order/export_xls?<?php echo http_build_query($this->input->get());?>'
    }
</script>