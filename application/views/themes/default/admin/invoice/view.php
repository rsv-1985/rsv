<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <h2>Накладна №<?php echo $invoice_info['id'];?> від <?php echo date('d.m.Y', strtotime($invoice_info['created_at']));?></h2>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>Постачальник:</strong> <?php echo $this->config->item('company_name');?>
                    </address>
                    <address>
                        <strong>Платник:</strong>
                        <?php echo $customer_info['second_name'].' '.$customer_info['first_name'].' '.$customer_info['phone'];?><br>
                    </address>
                </div>
                <div class="col-xs-6 text-right">

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <td><strong>#</strong></td>
                                <td class="text-left"><strong>Виробник</strong></td>
                                <td class="text-left"><strong>Артикул</strong></td>
                                <td class="text-left"><strong>Опис</strong></td>
                                <td class="text-left"><strong>Кіл.</strong></td>
                                <td class="text-left"><strong>Ціна</strong></td>
                                <td class="text-right"><strong>Сума</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $q = 1; foreach ($invoice_products as $product){?>
                                <tr>
                                    <td><?php echo $q;?></td>
                                    <td class="text-left"><?php echo $product['brand'];?></td>
                                    <td class="text-left"><?php echo $product['sku'];?></td>
                                    <td class="text-left"><?php echo $product['name'];?></td>
                                    <td class="text-left"><?php echo $product['iqty'];?></td>
                                    <td class="text-left"><?php echo $product['price'];?></td>
                                    <td class="text-right"><?php echo $product['price'] * $product['iqty'];?></td>
                                </tr>
                            <?php $q++; } ?>
                            <?php if($invoice_info['delivery_price'] > 0){?>
                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line text-center"><strong>Доставка</strong></td>
                                    <td class="thick-line text-right"><?php echo $invoice_info['delivery_price'];?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>Разом</strong></td>
                                <td class="no-line text-right"><?php echo $total;?></td>
                            </tr>
                            </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>