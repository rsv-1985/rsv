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
                <?php if ($recharge) { ?>
                    <div class="well well-sm">
                        <h4>Пополнить счет</h4>
                        <?php echo $recharge; ?>
                    </div>
                <?php } ?>
                <?php $this->load->view('customer/menu'); ?>

            </div>
            <div class="col-md-9">
                <div class="pull-right">
                    <?php echo lang('text_work_balance');?>: <?php echo format_balance($this->customer_model->getWorkBalance($this->customer_model->id));?>
                    <?php echo lang('text_balance');?>: <?php echo format_balance($this->customer_model->balance); ?>
                </div>
                <h2>История по балансу</h2>
                <?php echo form_open('/customer/balance',['method' => 'get']);?>
                <div class="row">
                    <div class="well well-sm">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Дата начало</label>
                                <input type="date" name="date_from" class="form-control" value="<?php echo $this->input->get('date_from', true);?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Дата конец</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo $this->input->get('date_to', true);?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Тип</label>
                                <select name="type" class="form-control">
                                    <option <?php if($this->input->get('type') == '*'){?>selected<?php } ?> value="*">Все</option>
                                    <option <?php if($this->input->get('type') == '1'){?>selected<?php } ?> value="1">Дебет</option>
                                    <option <?php if($this->input->get('type') == '2'){?>selected<?php } ?> value="2">Кредит</option>
                                </select>
                            </div>
                            <div class="pull-right">
                                <?php if($this->input->get()){?>
                                    <a href="/customer/balance" class="btn btn-danger">Сброс</a>
                                <?php } ?>
                                <button type="submit" name="csv" value="1" class="btn btn-success btn-xs">Скачать CSV</button>
                                <button type="submit" class="btn btn-info btn-xs">Фильтр</button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php echo form_close();?>
                <?php if ($balances) { ?>

                    <table class="table table-bordered table-condensed">
                        <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Дебет</th>
                            <th>Кредит</th>
                            <th>Баланс</th>
                            <th>Описание</th>
                            <th>Дата</th>
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