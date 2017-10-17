<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
<body onload="print()">
<div>
    <?php if($parcels){?>
        <table>
            <thead>
            <tr>
                <th>Посылка</th>
                <th>ФИО</th>
                <th>Телефон</th>
                <th>Способ доставки</th>
                <th>Адрес доставки</th>
                <th>ТТН</th>
            </tr>
            </thead>
            <?php foreach ($parcels as $parcel){?>
                <tr>
                    <td><?php echo $parcel['id'];?></td>
                    <td><?php echo $parcel['first_name'].' '.$parcel['last_name'].' '.$parcel['patronymic'];?><br></td>
                    <td><?php echo $parcel['telephone'];?><br></td>
                    <td><?php echo $parcel['delivery_method'];?></td>
                    <td><?php echo $parcel['address'];?></td>
                    <td><?php echo $parcel['ttn'];?></td>

                </tr>
                <?php if($parcel['products']){?>
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; border: 1px solid black;">
                                <tr>
                                    <td>Название запчасти</td>
                                    <td>Артикул</td>
                                    <td>Бренд</td>
                                    <td>Количество</td>
                                    <td>Поставщик</td>
                                </tr>
                                <?php foreach ($parcel['products'] as $product){?>
                                    <tr>
                                        <td><?php echo $product['name'];?></td>
                                        <td><?php  echo $product['sku'];?></td>
                                        <td><?php echo $product['brand'];?></td>
                                        <td><?php echo $product['quantity'];?>шт</td>
                                        <td><?php echo $product['sname'];?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                <?php }?>
            <?php } ?>
        </table>
    <?php } ?>
</div>
</body>
</html>

