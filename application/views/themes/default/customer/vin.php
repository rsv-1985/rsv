<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="single-product-area">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php $this->load->view('customer/menu');?>
            </div>
            <div class="col-md-9">
                <h2>VIN запросы</h2>
                <table class="table table-bordered">
                    <tbody><tr>
                        <th>Автомобиль</th>
                        <th>Дата создания</th>
                        <th>Дата обвнолени</th>
                        <th>Статус</th>
                        <th></th>
                    </tr>
                    <?php if($vins){?>
                        <?php foreach($vins as $vin){?>
                            <tr>
                                <td><?php echo $vin['manufacturer'] . ' ' . $vin['model'];?></td>
                                <td><?php echo $vin['created_at'];?></td>
                                <td><?php echo $vin['updated_at'];?></td>
                                <td><?php echo $vin['status'] ? 'Обработан' : 'Новый';?></td>
                                <td><a href="/customer/vin_info/<?php echo $vin['id'];?>">Подробнее</a></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
                <?php echo $this->pagination->create_links();?>
            </div>
        </div>
    </div>
</div>