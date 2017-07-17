<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo lang('text_heading');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Товары в заказах</h3>
                    <?php if($status_totals){?>
                        <div class="pull-right" style="text-align: right">
                            <?php foreach ($status as $stid => $v){
                                if(isset($status_totals['sum_'.$stid])){
                                    echo '<small style="color:'.$v['color'].'">'.$v['name'].':</small>'.$status_totals['sum_'.$stid].' ';
                                }
                            }?>
                            <button onclick="downloadXls()" class="btn btn-success">Скачать XLS</button>
                            <a href="#"  data-toggle="modal" data-target="#import-status" class="btn btn-info">Импорт статусов</a>
                        </div>
                    <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <a href="/autoxadmin/order">Отобразить заказы</a> /
                    <a style="color: green" href="/autoxadmin/order/products">Товары в заказах</a>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <tbody>
                            <tr>
                                <th class="text-center">Заказ №</th>
                                <th class="text-center">Название</th>
                                <th class="text-center">Артикул</th>
                                <th class="text-center">Производитель</th>
                                <th class="text-center">Цена</th>
                                <th class="text-center">Количество</th>
                                <th class="text-center">Поставщик</th>
                                <th class="text-center">Статус</th>
                                <th class="text-center"></th>
                            </tr>
                            <?php echo form_open('/autoxadmin/order/products',['method' => 'GET']);?>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="order_id" class="form-control" value="<?php echo $this->input->get('order_id', true);?>" style="width: 60px">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" value="<?php echo $this->input->get('name', true);?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="sku" class="form-control" value="<?php echo $this->input->get('sku', true);?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="brand" class="form-control" value="<?php echo $this->input->get('brand', true);?>">
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div class="form-group">
                                        <input style="width: 80px" type="text" name="quantity" class="form-control" value="<?php echo $this->input->get('quantity', true);?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="supplier_id" class="form-control">
                                            <option></option>
                                            <?php foreach($suppliers as $supplier){?>
                                                <option value="<?php echo $supplier['id'];?>" <?php echo set_select('supplier_id',$supplier['id'],$supplier['id'] == $this->input->get('supplier_id'));?>><?php echo $supplier['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="status_id" class="form-control">
                                            <option></option>
                                            <?php foreach($status as $s){?>
                                                <option value="<?php echo $s['id'];?>" <?php echo set_select('status_id',$s['id'], $s['id'] == $this->input->get('status_id'));?>><?php echo $s['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group pull-right">
                                        <a href="/autoxadmin/order/products" type="button" class="btn btn-link"><?php echo lang('button_reset');?></a>
                                        <button type="submit" class="btn btn-link pull-right"><?php echo lang('button_search');?></button>
                                    </div>
                                </td>
                            </tr>
                            </form>
                            <?php if($products){?>
                                <?php foreach($products as $product){?>
                                    <?php echo form_open(null,['class' => 'product-form']);?>
                                    <input type="hidden" name="id" value="<?php echo $product['id'];?>">
                                    <tr style="border-left: 5px solid <?php echo @$status[$product['status_id']]['color'];?>">
                                        <td>
                                            <a href="/autoxadmin/order/edit/<?php echo $product['order_id'];?>">
                                                <b>#<?php echo $product['order_id'];?><br></b>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="/product/<?php echo $product['slug'];?>" target="_blank">
                                                <?php echo $product['name'];?>
                                            </a>
                                        </td>
                                        <td><?php echo $product['sku'];?></td>
                                        <td><?php echo $product['brand'];?></td>
                                        <td><?php echo format_currency($product['price']);?></td>
                                        <td><?php echo $product['quantity'];?></td>
                                        <td><?php echo @$suppliers[$product['supplier_id']]['name'];?></td>
                                        <td>
                                            <div class="form-group">
                                                <select name="status_id" class="form-control">
                                                    <option></option>
                                                    <?php foreach($status as $s){?>
                                                        <option value="<?php echo $s['id'];?>" <?php echo set_select('status_id', $s['id'], $s['id'] == $product['status_id']);?>><?php echo $s['name'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="submit" class="btn btn-link pull-right" value="<?php echo lang('button_submit');?>">
                                        </td>
                                    </tr>
                                    </form>
                                <?php } ?>
                            <?php } ?>
                            </tbody>
                            <tfoot class="well well-sm">
                            <?php echo form_open('/autoxadmin/order/change_status_products?'.http_build_query($this->input->get()),['class' => 'change_status_products']);?>
                                <tr>
                                    <td colspan="7" class="text-right">
                                        <div class="form-group">
                                            <?php if($this->input->get()){?>
                                                <label>Применить к отфильтрованным:</label>
                                            <?php }else{?>
                                                <label>Применить ко всем:</label>
                                            <?php } ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="status_id" class="form-control">
                                                <option></option>
                                                <?php foreach($status as $s){?>
                                                    <option value="<?php echo $s['id'];?>" <?php echo set_select('status_id');?>><?php echo $s['name'];?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-link pull-right" value="<?php echo lang('button_submit');?>">
                                    </td>
                                </tr>
                            </form>
                            </tfoot>
                        </table>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">


                    <?php echo $this->pagination->create_links();?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
<div class="modal" id="import-status">
    <?php echo form_open_multipart('/autoxadmin/order/importstatus');?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
                                <?php foreach ($status as $st){?>
                                    <option value="<?php echo $st['id'];?>"><?php echo $st['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Новый статус товара</label>
                            <select name="new_status_id" class="form-control">
                                <?php foreach ($status as $st){?>
                                    <option value="<?php echo $st['id'];?>"><?php echo $st['name'];?></option>
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
                                <?php foreach ($suppliers as $supplier){?>
                                    <option value="<?php echo $supplier['id'];?>"><?php echo $supplier['name'];?></option>
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
<script>
    $(document).ready(function(){
        $(".product-form").submit(function(e){
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
        $(".change_status_products").submit(function(e){
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                method: 'post',
                success: function (response) {
                    if(response == 'success'){
                        location.reload();
                    }
                }
            });
        });
    });


    function downloadXls(){
        var data = '<?php echo http_build_query($this->input->get());?>';
        location.href='/autoxadmin/order/export_xls?<?php echo http_build_query($this->input->get());?>'
    }
</script>