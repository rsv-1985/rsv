<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="single-product-area" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php $this->load->view('customer/menu');?>
                <?php if($recharge){?>
                    <div class="well">
                        <h4>Пополнить счет</h4>
                        <?php echo $recharge;?>
                    </div>
                <?php } ?>
                <?php echo form_open();?>
                    <div class="well">
                        <h4>Сообщить об оплате</h4>
                        <div class="form-group">
                            <label>Сумма</label>
                            <input type="text" name="sum" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Комментарий</label>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-xs pull-right">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </form>

            </div>
            <div class="col-md-9">
                <h2>История по балансу</h2>
                <?php if($balances){?>
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th style="width: 10px">#</th>
                            <th>Тип</th>
                            <th>Сумма</th>
                            <th>Описание</th>
                            <th>Дата транзакции</th>
                            <th>Дата добавления</th>
                        </tr>
                        <?php if($balances){?>
                            <?php foreach ($balances as $balance){?>
                                <tr style="border-left: 2px solid <?php if($balance['type'] == 1){?>green<?php }else{?>red<?php } ?>">
                                    <td><?php echo $balance['id'];?></td>
                                    <td><?php echo $types[$balance['type']];?></td>
                                    <td>
                                        <?php if($balance['type'] == 1){?>
                                            <span class="label label-success"><?php echo $balance['value'];?></span>
                                        <?php }else{?>
                                            <span class="label label-danger">-<?php echo $balance['value'];?></span>
                                        <?php } ?>
                                    </td>
                                    <td><small><?php echo $balance['description'];?></small></td>
                                    <td>
                                        <?php echo $balance['transaction_created_at'];?><br/>
                                    </td>
                                    <td>
                                        <?php echo $balance['created_at'];?><br/>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links();?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>