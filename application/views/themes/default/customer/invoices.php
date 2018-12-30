<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="single-product-area" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php $this->load->view('customer/menu'); ?>
            </div>
            <div class="col-md-9">
                <h2><?php echo lang('text_menu_invoice');?></h2>
                <?php if ($invoices) { ?>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th>Сумма</th>
                            <th>Дата добавления</th>
                            <th>Статус</th>
                            <th>ТТН</th>
                            <th></th>
                        </tr>

                            <?php foreach ($invoices as $invoice) { ?>
                                <tr>
                                    <td><?php echo lang('text_invoice').' '.$invoice['id']; ?></td>
                                    <td>
                                        <?php echo $invoice['total']; ?>
                                    </td>
                                    <td>
                                        <small><?php echo format_time($invoice['created_at']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo $statuses[$invoice['status_id']];?>
                                    </td>
                                    <td>
                                        <?php echo $invoice['ttn']; ?>
                                    </td>
                                    <td>
                                        <a target="_blank" class="btn btn-info" href="/customer/invoice/<?php echo $invoice['id'];?>">Печать</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links(); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>