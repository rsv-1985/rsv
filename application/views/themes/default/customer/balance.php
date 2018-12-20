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
                <?php if ($recharge) { ?>
                    <div class="well well-sm">
                        <h4>Пополнить счет</h4>
                        <?php echo $recharge; ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-9">
                <div class="pull-right">
                    Баланс в работе: <?php echo format_balance($this->customer_model->getWorkBalance($this->customer_model->id));?>
                    Баланс: <?php echo format_balance($this->customer_model->balance); ?>
                </div>
                <h2>История по балансу</h2>
                <?php if ($balances) { ?>
                    <table class="table table-bordered table-condensed">
                        <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Дебет</th>
                            <th>Кредит</th>
                            <th>Баланс</th>
                            <th>Описание</th>
                            <th>Дата добавления</th>
                        </tr>
                        <?php if ($balances) { ?>
                            <?php foreach ($balances as $balance) { ?>
                                <tr style="border-left: 2px solid <?php if ($balance['type'] == 1) { ?>green<?php } else { ?>red<?php } ?>">
                                    <td><?php echo $balance['id']; ?></td>
                                    <td>
                                        <?php if ($balance['type'] == 1) { ?>
                                            <?php echo format_balance($balance['value']); ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($balance['type'] != 1){?>
                                            <?php echo format_balance(-$balance['value']); ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo $balance['balance']; ?>
                                    </td>
                                    <td>
                                        <?php if($balance['invoice_id']){?>
                                            <a target="_blank" href="/customer/invoice/<?php echo $balance['invoice_id'];?>"><?php echo $balance['description']; ?></a>
                                        <?php }else{?>
                                            <?php echo $balance['description']; ?>
                                        <?php } ?>

                                    </td>

                                    <td>
                                        <?php echo format_time($balance['created_at']); ?><br/>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links(); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>