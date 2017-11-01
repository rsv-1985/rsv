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
                <h2>Отправки</h2>
                <?php if($parcels){?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Номер посылки</th>
                            <th>Способ доставки</th>
                            <th>Способ оплаты</th>
                            <th>ТТН</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($parcels as $parcel){?>
                            <tr>
                                <td><a target="_blank" href="/customer/print_parcel/<?php echo $parcel['id'];?>"><?php echo $parcel['id'];?></a></td>
                                <td><?php echo $parcel['delivery_method'];?></td>
                                <td><?php echo $parcel['payment_method'];?></td>
                                <td><?php echo $parcel['ttn'];?></td>
                                <td><?php echo $parcel['updated_at'];?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links();?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

