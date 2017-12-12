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
                <h2>История поиска</h2>
                <table class="table table-bordered">
                    <tbody><tr>
                        <th>Артикул</th>
                        <th>Производитель</th>
                        <th>Дата</th>
                    </tr>
                    <?php if($search_history){?>
                        <?php foreach($search_history as $sh){?>
                            <tr>
                                <td><?php echo $sh['sku'];?></td>
                                <td><?php echo $sh['brand'];?></td>
                                <td><?php echo $sh['created_at'];?></td>
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