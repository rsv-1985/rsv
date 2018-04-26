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
    <?php if($parcel){?>
            <table>
                <thead>
                <tr>
                    <th>Посылка</th>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Способ доставки</th>
                    <th>Способ оплаты</th>
                    <th>Адрес доставки</th>
                    <th>ТТН</th>
                </tr>
                </thead>
                <tr>
                    <td><?php echo $parcel['id'];?></td>
                    <td><?php echo $parcel['first_name'].' '.$parcel['last_name'].' '.$parcel['patronymic'];?><br></td>
                    <td><?php echo $parcel['telephone'];?><br></td>
                    <td><?php echo $parcel['delivery_method'];?></td>
                    <td><?php echo $parcel['payment_method'];?></td>
                    <td><?php echo $parcel['address'];?></td>
                    <td><?php echo $parcel['ttn'];?></td>
                </tr>
                <?php if($products){ $t = 0;?>
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; border: 1px solid black;">
                                <tr>
                                    <td>Название запчасти</td>
                                    <td>Артикул</td>
                                    <td>Бренд</td>
                                    <td>Количество</td>
                                    <td>Цена</td>
                                    <td>Итого</td>
                                </tr>
                                <?php foreach ($products as $product){?>
                                    <tr>
                                        <td><?php echo $product['name'];?></td>
                                        <td><?php  echo $product['sku'];?></td>
                                        <td><?php echo $product['brand'];?></td>
                                        <td><?php echo $product['quantity'];?>шт</td>
                                        <td><?php echo $product['price'];?></td>
                                        <td><?php $t += $product['quantity'] * $product['price'];  echo $product['quantity'] * $product['price'];?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                <?php }?>
                <tr>
                    <td colspan="6" style="border: 1px solid; text-align: right;"><b>Итого: </b><?php echo $t;?></td>
                </tr>
            </table>
            <br>
    <?php } ?>
</div>
</body>
</html>

