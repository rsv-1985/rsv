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
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home');?></a></li>
        <li><a href="#"><?php echo lang('text_heading');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo lang('text_heading');?></h3>
                    <div class="pull-right">
                        <a href="/autoxadmin/product/delete_product_carts" class="btn btn-danger"><?php echo lang('button_empty_product');?></a>
                        <a href="/autoxadmin/product/create" class="btn btn-info"><?php echo lang('button_add');?></a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID Категории</th>
                                <th><?php echo lang('text_sku');?></th>
                                <th><?php echo lang('text_brand');?></th>
                                <th><?php echo lang('text_name');?></th>
                                <th><?php echo lang('text_supplier_id');?></th>
                                <th><?php echo lang('text_delivery_price');?></th>
                                    <th><?php echo lang('text_price');?></th>
                                <th><?php echo lang('text_saleprice');?></th>
                                <th><a style="display: none;" href="/autoxadmin/product/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                            </tr>
                            <?php echo form_open('', ['method' => 'GET', 'id' => 'filter-form']);?>
                            <tr>
                                <td>
                                    <input type="text" name="category_id" class="form-control" value="<?php echo $this->input->get('category_id');?>">
                                </td>
                                <td>
                                    <input type="text" name="sku" class="form-control" value="<?php echo $this->input->get('sku');?>">
                                </td>
                                <td>
                                    <input type="text" name="brand" class="form-control" value="<?php echo $this->input->get('brand');?>">
                                </td>
                                <td>
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get('name');?>">
                                </td>
                                <td>
                                    <select name="supplier_id" class="form-control">
                                        <option></option>
                                        <?php foreach ($supplier as $sup){?>
                                            <option value="<?php echo $sup['id'];?>" <?php echo set_select('supplier_id',$sup['id'],$sup['id'] == (int)$this->input->get('supplier_id'));?>><?php echo $sup['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" disabled></td>
                                <td><input type="text" class="form-control" disabled></td>
                                <td><input type="text" class="form-control" disabled></td>
                                <td>
                                    <div class="btn-group">
                                        <?php if($this->input->get()){?>
                                            <a href="#" onclick="delete_filter(event)" class="btn btn-danger" title="Удалить по фильтру"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                            <a href="/autoxadmin/product" class="btn btn-default" title="<?php echo lang('button_reset');?>"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                        <?php } ?>
                                        <button type="submit" class="btn btn btn-info" title="<?php echo lang('button_search');?>"><i class="fa fa-search" aria-hidden="true"></i></button>

                                    </div>
                                </td>
                            </tr>
                            </form>
                            <?php if($products){?>

                                <?php foreach($products as $product){?>
                                    <?php echo form_open();?>
                                    <input type="hidden" name="product_id" value="<?php echo $product['id'];?>">
                                    <input type="hidden" name="supplier_id" value="<?php echo $product['supplier_id'];?>">
                                    <input type="hidden" name="term" value="<?php echo $product['term'];?>">
                                        <tr>
                                            <td><?php echo $product['category_name'];?></td>
                                        <td>
                                            <?php echo $product['sku'];?>
                                            <?php if(!$this->tecdoc->getIDart($product['sku'],$product['brand'])){?>
                                                <br>
                                                <span class="label label-warning"><?php echo lang('error_tecdoc');?></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php echo $product['brand'];?>
                                        </td>
                                        <td><?php echo $product['name'];?></td>
                                        <td><?php echo isset($supplier[$product['supplier_id']]) ? $supplier[$product['supplier_id']]['name'] : '';?></td>
                                        <td>
                                            <input type="text" name="delivery_price" value="<?php echo $product['delivery_price'];?>" class="form-control">
                                            <small><?php echo isset($currency[$product['currency_id']]) ? $currency[$product['currency_id']]['name'] : '';?></small>
                                        </td>
                                            <td>
                                                <input type="text" name="price"  value="<?php echo $product['price'];?>" class="form-control">
                                            </td>
                                        <td>
                                            <input type="text" name="saleprice"  value="<?php echo $product['saleprice'];?>" class="form-control">

                                        </td>
                                        <td>
                                            <div class="btn-group" style="width: 113px">
                                                <a href="/autoxadmin/product/delete?product_id=<?php echo $product['id'];?>&supplier_id=<?php echo $product['supplier_id'];?>&term=<?php echo $product['term'];?>" class="btn btn-danger" title="<?php echo lang('button_delete');?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                <button type="submit" class="btn btn-default" title="<?php echo lang('button_submit');?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                                                <a href="/autoxadmin/product/edit/<?php echo $product['id'];?>" class="btn btn-info" title="<?php echo lang('button_edit');?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php echo form_close();?>
                                <?php } ?>
                            <?php } ?>
                            </tbody>
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
<script>
    $("input, select").change(function () {
        $(".btn-default").show();
    });
    function delete_filter(e){
        e.preventDefault();
        var d = prompt('Удалить карточки даже если у них остались цены?\n 1 - ДА 0 - НЕТ');
        if(confirm('Продолжить удаление?')){
            $.ajax({
                url:'/autoxadmin/product/delete_by_filter?delete_product_card='+d,
                data: $("#filter-form").serialize(),
                method:'post',
                success:function (response) {
                    alert(response);
                }
            });
        }
    }
</script>