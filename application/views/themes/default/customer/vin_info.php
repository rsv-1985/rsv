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
                <h2>VIN запрос <?php echo $vin_info['id'];?> <small>Статус: <?php echo $vin_info['status'] ? 'Обработан' : 'Новый';?></small></h2>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td><?php echo lang('text_vin_manufacturer');?></td>
                        <td><?php echo $vin_info['manufacturer'];?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('text_vin_model');?></td>
                        <td><?php echo $vin_info['model'];?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('text_vin_engine');?></td>
                        <td><?php echo $vin_info['engine'];?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('text_vin_vin');?></td>
                        <td><?php echo $vin_info['vin'];?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('text_vin_parts');?></td>
                        <td><?php echo $vin_info['parts'];?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('text_vin_name');?></td>
                        <td><?php echo $vin_info['name'];?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('text_vin_telephone');?></td>
                        <td><?php echo $vin_info['telephone'];?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('text_vin_email');?></td>
                        <td><?php echo $vin_info['email'];?></td>
                    </tr>
                    <tr>
                        <td>Комментрайи менеджера</td>
                        <td><?php echo $vin_info['comment'];?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>