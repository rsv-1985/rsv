<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> <?php echo lang('text_home'); ?></a></li>
        <li><a href="#"><?php echo lang('text_heading'); ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo lang('text_heading'); ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="well">
                        <?php echo form_open('/autoxadmin/supplier_pay', ['method' => 'get', 'id' => 'filter-form']); ?>
                        <input type="hidden" name="sort" value="<?php echo $this->input->get('sort'); ?>">
                        <input type="hidden" name="order" value="<?php echo $this->input->get('order'); ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Поставщик</label>
                                    <select name="supplier_id" class="form-control">
                                        <option></option>
                                        <?php foreach ($suppliers as $supplier){?>
                                            <option <?php echo set_select('supplier_id', $supplier['id'], (bool)$supplier['id'] == $this->input->get('supplier_id'));?> value="<?php echo $supplier['id'];?>"><?php echo $supplier['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ID транзакции</label>
                                    <input type="text" name="id" value="<?php echo $this->input->get('id', true); ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Сумма транзакции</label>
                                    <input type="text" name="amount"
                                           value="<?php echo $this->input->get('amount', true); ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Дата от</label>
                                    <input type="date" name="date_from"
                                           value="<?php echo $this->input->get('date_from', true); ?>"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Дата до</label>
                                    <input type="date" name="date_to"
                                           value="<?php echo $this->input->get('date_to', true); ?>"
                                           class="form-control">
                                </div>
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <?php if ($this->input->get()) { ?>
                                            <a href="/autoxadmin/supplier_pay" class="btn btn-default"
                                               title="<?php echo lang('button_reset'); ?>"><i class="fa fa-refresh"
                                                                                              aria-hidden="true"></i></a>
                                        <?php } ?>
                                        <button type="submit" class="btn btn btn-info"
                                                title="<?php echo lang('button_search'); ?>"><i class="fa fa-search"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 10px">
                                <a href="" class="sort" data-sort="id">
                                    #
                                </a>
                            </th>
                            <th>
                                <a href="" class="sort" data-sort="customer_id">
                                    Поставщик
                                </a>
                            </th>
                            <th>
                                <a href="" class="sort" data-sort="amount">
                                    Сумма
                                </a>
                            </th>
                            <th>
                                <a href="" class="sort" data-sort="transaction_date">
                                    Дата транзакции
                                </a>
                            </th>
                            <th>
                                Комментарий
                            </th>
                            <th>
                                <a href="/autoxadmin/supplier_pay/create" class="btn btn-info pull-right">Добавить</a>
                            </th>
                        </tr>


                        <?php if ($pays) { ?>
                            <?php foreach ($pays as $pay) { ?>
                                <tr>
                                    <td><?php echo $pay['id']; ?></td>
                                    <td>
                                        <a target="_blank" href="/autoxadmin/supplier/edit/<?php echo $pay['supplier_id']; ?>">
                                            <?php echo $pay['supplier_name']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $pay['amount']; ?></td>
                                    <td><?php echo format_time($pay['transaction_date']); ?></td>
                                    <td style="text-align: center; width:90px;">
                                        <?php echo $pay['comment']; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            <a href="/autoxadmin/supplier_pay/delete/<?php echo $pay['id']; ?>"
                                               type="button"
                                               class="btn btn-danger confirm"><?php echo lang('button_delete'); ?></a>

                                                <a href="/autoxadmin/supplier_pay/edit/<?php echo $pay['id']; ?>"
                                                   type="button"
                                                   class="btn btn-info"><?php echo lang('button_edit'); ?></a>

                                        </div>
                                    </td>
                                </tr>

                            <?php } ?>

                        <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
<script>
    $(document).ready(function () {
        $(".sort").click(function (e) {
            e.preventDefault();
            var sort = $(this).attr('data-sort');
            $("[name='sort']").val(sort);
            if ($("[name='order']").val() == 'ASC') {
                $("[name='order']").val('DESC');
            } else {
                $("[name='order']").val('ASC');
            }

            $("#filter-form").submit();
        })
    })
</script>
