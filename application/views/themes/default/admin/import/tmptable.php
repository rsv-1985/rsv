<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo lang('text_tmptable');?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="/autoxadmin/import"><?php echo lang('text_heading');?></a></li>
        <li class="active"><?php echo lang('text_tmptable');?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo lang('text_tmp_total').':'.$total;?></h3>
            <div class="button-check-tecdoc pull-right">
                <button class="btn btn-info pull-right" onclick="check_tecdoc(event)">Сверить с TECDOC</button>
                <a href="/autoxadmin/import/cancel" class="btn btn-danger"><?php echo lang('button_delete');?></a>
                <br>
                <?php echo $file;?>
            </div>
        </div>
        <div class="box-body">
            <table class="table">
                <thead>
                <tr>
                    <th><?php echo lang('text_sample_sku');?></th>
                    <th><?php echo lang('text_sample_brand');?></th>
                    <th><?php echo lang('text_sample_name');?></th>
                    <th><?php echo lang('text_sample_description');?></th>
                    <th><?php echo lang('text_sample_excerpt');?></th>
                    <th><?php echo lang('text_sample_delivery_price');?></th>
                    <th><?php echo lang('text_sample_saleprice');?></th>
                    <th><?php echo lang('text_sample_quantity');?></th>
                    <th><?php echo lang('text_sample_term');?></th>
                    <th><?php echo lang('text_sample_image');?></th>
                    <th><?php echo lang('text_sample_attributes');?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($importtmp as $item){?>
                    <tr>
                        <td><?php echo $item['sku'];?></td>
                        <td><?php echo $item['brand'];?></td>
                        <td><?php echo $item['name'];?></td>
                        <td><?php echo $item['description'];?></td>
                        <td><?php echo $item['excerpt'];?></td>
                        <td><?php echo $item['delivery_price'];?></td>
                        <td><?php echo $item['saleprice'];?></td>
                        <td><?php echo $item['quantity'];?></td>
                        <td><?php echo $item['term'];?></td>
                        <td>
                            <?php if(mb_strlen($item['image']) > 0){?>
                                <img onerror="imgError(this);" src="/image?img=/uploads/product/<?php echo $item['image'];?>&width=50&height=50"/>
                            <?php } ?>
                        </td>
                        <td><?php echo $item['attributes'];?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">
            <?php echo $this->pagination->create_links();?>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <?php echo form_open('/autoxadmin/import/add', ['method' => 'get', 'id' => 'import-form', 'onsubmit' => 'add(event)']);?>
                <div class="form-group">
                    <label><?php echo lang('text_import_settings');?></label>
                    <select name="settings" class="form-control">
                        <option value="2"><label><?php echo lang('text_import_settings_delete');?></label></option>
                        <option value="1"><label><?php echo lang('text_import_settings_add');?></label></option>
                    </select>
                </div>
                <?php if($this->input->get('supplier_id')){?>
                    <input type="hidden" name="supplier_id" value="<?php echo (int)$this->input->get('supplier_id');?>">
                <?php }else{?>
                    <div class="form-group">
                        <label><?php echo lang('text_supplier');?></label>
                        <select name="supplier_id" class="form-control" required>
                            <?php foreach($supplier as $supplier){?>
                                <option value="<?php echo $supplier['id'];?>"><?php echo $supplier['name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php }?>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="1" name="update_product_field" onchange="$('#checkbox-seo-url').toggle();">
                            <?php echo lang('text_update_product_card');?>
                        </label>
                        <p class="help-block"><small><?php echo lang('text_update_product_card_description');?></small></p>
                    </div>
                    <div class="checkbox" id="checkbox-seo-url" style="display: none;">
                        <label>
                            <input type="checkbox" value="1" name="update_seo_url">
                            <?php echo lang('text_update_seo_url');?>
                        </label>
                        <p class="help-block"><small><?php echo lang('text_update_seo_url_description');?></small></p>
                    </div>

                </div>
                <button type="submit" class="btn btn-success"><?php echo lang('button_import');?></button>
                </form>
            </div>

        </div><!-- /.box-footer-->
    </div><!-- /.box -->
    <div class="modal modal-danger" id="import-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Импорт работает</h4>
                </div>
                <div class="modal-body">
                    <p>Обработано строк: <b id="rows">0</b> </p>
                    <div class="progress">
                        <div id="progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="sr-only"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="location.reload()">Отмена</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal modal-danger" id="tecdoc-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Проверка работает</h4>
                </div>
                <div class="modal-body">
                    <p>Обработано строк: <b id="tecdoc-rows">0</b> </p>
                    <div class="progress tecdoc">
                        <div id="progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="sr-only"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="location.reload()">Отмена</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</section><!-- /.content -->
<script>

    var totalRows = '<?php echo $total;?>';
    var tecdoc_row = 0;
    function check_tecdoc(){
        event.preventDefault();
        $("#tecdoc-modal").modal({backdrop: 'static', keyboard: false});
        $.ajax({
            url: '/autoxadmin/import/checktecdoc/0',
            method: 'get',
            success: function(json){
                if(json['continue']){
                    doCheck(json['continue']);
                }else{
                    location.reload();
                }
            }
        });
    }

    function doCheck(href){
        $.ajax({
            url: href,
            method: 'get',
            success: function(json){
                if(json['continue']){
                    tecdoc_row += 100;
                    var procent = tecdoc_row * 100 / totalRows;
                    $(".tecdoc .sr-only").text(procent);
                    $(".tecdoc #progress").css('width',procent+'%');
                    $("#tecdoc-rows").text(tecdoc_row);
                    doCheck(json['continue']);
                }else{
                    location.reload();
                }
            }
        });
    }



    var row = 0;
    function add(event){
        event.preventDefault();
        $("#import-modal").modal({backdrop: 'static', keyboard: false});
        $.ajax({
            url: $('#import-form').attr('action'),
            method: 'get',
            data: $('#import-form').serialize(),
            success: function(json){
                if(json['continue']){
                    doImport(json['continue']);
                }else{
                    location.href = json['success'];
                }
            }
        });
    }

    function doImport(href){
        $.ajax({
            url: href,
            method: 'get',
            success: function(json){
                if(json['continue']){
                    row += 1000;
                    var procent = row * 100 / totalRows;
                    $(".sr-only").text(procent);
                    $("#progress").css('width',procent+'%');
                    $("#rows").text(row);
                    doImport(json['continue']);
                }else{
                    location.href = json['success'];
                }
            }
        });
    }


</script>