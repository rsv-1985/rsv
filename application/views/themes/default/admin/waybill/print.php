<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
<style>
    table{
        border: 2px solid black;
        border-collapse: collapse;
        width: 100%;
    }

    thead{
        font-weight: bold;
    }

    .customer{
        background: #D0D0D0;
    }

    .customer td{
        border-top: 2px solid black;
    }

</style>
<body onload="print()">
<div>
    <?php if ($parcels) { ?>
        <table id="t01">
            <tbody>
            <thead>
            <tr style="text-align: center;">
                <td>№</td>
                <td>ФИО</td>
                <td>Телефон</td>
                <td>Доставка</td>
                <td>Оплата</td>
                <td>Адрес</td>
                <td>ТТН</td>
            </tr>
            </thead>
            <?php foreach ($parcels as $parcel){
            $products_total = 0; ?>
            <tr class="customer" style="text-align: center">
                <td><?php echo $parcel['id']; ?></td>
                <td>
                    <?php echo $parcel['first_name'] . ' ' . $parcel['last_name'] . ' ' . $parcel['patronymic']; ?><br>
                    <?php if ($parcel['customer_id']) { ?>
                        (<?php echo $parcel['customer_info']['balance']; ?>)
                    <?php } ?>
                </td>
                <td><?php echo $parcel['telephone']; ?><br></td>
                <td><?php echo $parcel['delivery_method']; ?></td>
                <td><?php echo $parcel['payment_method']; ?></td>
                <td><?php echo $parcel['address']; ?></td>
                <td><?php echo $parcel['ttn']; ?></td>
            </tr>
            <?php if ($parcel['products']) { ?>
                    <tr style="text-align: center">
                        <td>Артикул</td>
                        <td>Бренд</td>
                        <td>Название запчасти</td>
                        <td>Количество</td>
                        <td>Поставщик</td>
                        <td colspan="2">Итого</td>
                    </tr>
                <?php foreach ($parcel['products'] as $product) { ?>
                    <tr>
                        <td><?php echo $product['sku']; ?></td>
                        <td><?php echo $product['brand']; ?></td>
                        <td><?php echo substr($product['name'], 0, 20); ?></td>
                        <td><?php echo $product['quantity']; ?>шт</td>
                        <td><?php echo $product['sname']; ?></td>
                        <td colspan="2"><?php $products_total += $product['quantity'] * $product['price'];
                            echo $product['quantity'] * $product['price']; ?></td>

                    </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td colspan="7" style="border: 1px solid; text-align: right;">
                    <b>Итого: </b><?php echo $products_total; ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
</body>
</html>

