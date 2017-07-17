<section class="content-header">
    <h1>
        Импорт статусов
        <small>товаров</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="/autoxadmin/order">Заказы</a></li>
        <li class="active">Импорт статусов</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <h4>Точное совпадение</h4>
        <?php if($products){?>
            <table class="table table-bordered">
                <thead>
                <tr class="success">
                    <th>№ заказа</th>
                    <th>Артикул в заказе</th>
                    <th>Производитель в заказе</th>
                    <th>Количестов в заказе</th>
                    <th>Артикул в файле</th>
                    <th>Производитель в файле</th>
                    <th>Количестов в файле</th>
                    <th>Статус в заказе</th>
                    <th>Новый статус</th>
                    <th><button class="btn btn-info btn-xs" onclick="updateStatus('product_form')">Обновить все статусы</button></th>
                </tr>
                </thead>
                <tbody>
                <?php echo form_open(null,['id' => 'product_form']);?>
                <?php $qp = 0; foreach ($products as $product){?>
                    <tr class="success products" id="product<?php echo $qp;?>">
                        <input type="hidden" name="products[<?php echo $qp;?>][product_id]" value="<?php echo $product['product_id'];?>"/>
                        <input type="hidden" name="products[<?php echo $qp;?>][status_id]" value="<?php echo $product['new_status_id'];?>"/>

                        <td><a target="_blank" href="/autoxadmin/order/edit/<?php echo $product['order_id'];?>"><?php echo $product['order_id'];?></a></td>
                        <td><?php echo $product['sku_order'];?></td>
                        <td><?php echo $product['brand_order'];?></td>
                        <td><?php echo $product['quan_order'];?></td>
                        <td><?php echo $product['sku_file'];?></td>
                        <td><?php echo $product['brand_file'];?></td>
                        <td><?php echo $product['quan_file'];?></td>
                        <td><?php echo $statuses[$product['status_id']]['name'];?></td>
                        <td><?php echo $statuses[$product['new_status_id']]['name'];?></td>
                        <td><a href="#" onclick="$('#product<?php echo $qp;?>').remove();return false;">Удалить</a></td>
                    </tr>
                <?php $qp++; } ?>
                </form>
                </tbody>
            </table>

        <?php }else{?>
            Нет точных совпадений
        <?php } ?>

        <h4>Похожие</h4>
        <?php if($similar_products){?>
            <table class="table table-bordered">
                <thead>
                <tr class="warning">
                    <th>№ заказа</th>
                    <th>Артикул в заказе</th>
                    <th>Производитель в заказе</th>
                    <th>Количестов в заказе</th>
                    <th>Артикул в файле</th>
                    <th>Производитель в файле</th>
                    <th>Количестов в файле</th>
                    <th>Статус в заказе</th>
                    <th>Новый статус</th>
                    <th><button class="btn btn-info btn-xs" onclick="updateStatus('similar_products_form')">Обновить все статусы</button></th>
                </tr>
                </thead>
                <?php echo form_open(null,['id' => 'similar_products_form']);?>
                <?php $sp = 0; foreach ($similar_products as $product){?>
                    <tr class="warning similar_products" id="similar_products<?php echo $sp;?>">
                        <input type="hidden" name="products[<?php echo $sp;?>][product_id]" value="<?php echo $product['product_id'];?>"/>
                        <input type="hidden" name="products[<?php echo $sp;?>][status_id]" value="<?php echo $product['new_status_id'];?>"/>
                        <td><a target="_blank" href="/autoxadmin/order/edit/<?php echo $product['order_id'];?>"><?php echo $product['order_id'];?></a></td>
                        <td><?php echo $product['sku_order'];?></td>
                        <td><?php echo $product['brand_order'];?></td>
                        <td><?php echo $product['quan_order'];?></td>
                        <td><?php echo $product['sku_file'];?></td>
                        <td><?php echo $product['brand_file'];?></td>
                        <td><?php echo $product['quan_file'];?></td>
                        <td><?php echo $statuses[$product['status_id']]['name'];?></td>
                        <td><?php echo $statuses[$product['new_status_id']]['name'];?></td>
                        <td><a href="#" onclick="$('#similar_products<?php echo $sp;?>').remove();return false;">Удалить</a></td>
                    </tr>
                <?php $sp++; } ?>
                </form>
                </tbody>
            </table>
        <?php }else{?>
            Нет похожих товаров
        <?php } ?>

        <h4>Не найдено</h4>
        <?php if($error_products){?>
            <table class="table table-bordered">
                <thead>
                <tr class="danger">
                    <th>Артикул</th>
                    <th>Количестов</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($error_products as $product){?>
                    <tr class="danger">
                        <td><?php echo $product['sku'];?></td>
                        <td><?php echo $product['quan'];?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php }else{?>
            Ошибок нет
        <?php } ?>

    </div>
</section><!-- /.content -->
<script>
    function updateStatus(form_id){
        $.ajax({
            url:'/autoxadmin/order/update_status',
            data: $("#"+form_id).serialize(),
            method: 'post',
            success: function(response){
                $("#"+form_id).remove();
                alert('Все статусы обновлены')
            }
        })
    }
</script>