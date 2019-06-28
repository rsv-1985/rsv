<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="dropdown">
    <button class="btn btn-link" id="dLabel" type="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
        <i class="glyphicon glyphicon-collapse-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dLabel">
        <li><a href="#" data-toggle="modal" data-target="#add-customer-balance<?php echo $customer_id; ?>">Пополнить
                баланс</a></li>
        <li><a href="#" data-toggle="modal" data-target="#requisites<?php echo $customer_id; ?>">Отправить реквизиты</a>
        </li>
    </ul>
</div>


<div class="modal fade" id="add-customer-balance<?php echo $customer_id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Пополнение баланса</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Сумма</label>
                    <input id="amount<?php echo $customer_id; ?>" type="text" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" onclick="add_customer_balance(<?php echo $customer_id; ?>)"
                        class="btn btn-primary">Пополнить
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="requisites<?php echo $customer_id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title">Реквизиты</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Сумма</label>
                    <input id="requisites-amount<?php echo $customer_id; ?>" value="<?php echo @$total; ?>" type="text"
                           class="form-control">
                </div>
                <a href="/autoxadmin/message_template/edit/5" target="_blank">Редактировать шаблон сообщения</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" onclick="requisites(<?php echo $customer_id; ?>)" class="btn btn-primary">
                    Отправить
                </button>
            </div>
        </div>
    </div>
</div>

<script>

    function requisites(customer_id) {
        var amount = $("#requisites-amount" + customer_id).val();

        $.ajax({
            url: '/autoxadmin/message_template/send_requisites',
            method: 'POST',
            data: {
                customer_id: customer_id,
                amount: amount
            },
            success: function (response) {
                alert('Реквизиты отправлены.');
                $("#requisites" + customer_id).modal('hide');
            }
        })

    }

    function add_customer_balance(customer_id) {
        var amount = $("#amount" + customer_id).val();
        if (amount > 0) {
            $.ajax({
                url: '/autoxadmin/customer_pay/create',
                method: 'POST',
                data: {
                    customer_id: customer_id,
                    date: '<?php echo date('Y-m-d');?>',
                    time: '<?php echo date('H:i');?>',
                    status_id: 1,
                    comment: '',
                    amount: amount
                },
                success: function () {
                    location.reload();
                }
            })
        }

    }
</script>
