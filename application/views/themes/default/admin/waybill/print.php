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
    <?php if($parcels){ ?>

            <?php foreach ($parcels as $parcel){ $products_total = 0;?>
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
                    <td>
                        <?php echo $parcel['first_name'].' '.$parcel['last_name'].' '.$parcel['patronymic'];?><br>
                        <?php if($parcel['customer_id']){?>
                            (<?php echo $parcel['customer_info']['balance'];?>)
                        <?php } ?>
                    </td>
                    <td><?php echo $parcel['telephone'];?><br></td>
                    <td><?php echo $parcel['delivery_method'];?></td>
                    <td><?php echo $parcel['payment_method'];?></td>
                    <td><?php echo $parcel['address'];?></td>
                    <td><?php echo $parcel['ttn'];?></td>
                </tr>
                <?php if($parcel['products']){?>
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; border: 1px solid black;">
                                <tr>
                                    <td>Артикул</td>
                                    <td>Бренд</td>
                                    <td>Название запчасти</td>
                                    <td>Количество</td>
                                    <td>Поставщик</td>
                                    <td>Итого</td>
                                </tr>
                                <?php foreach ($parcel['products'] as $product){?>
                                    <tr>
                                        <td><?php  echo $product['sku'];?></td>
                                        <td><?php echo $product['brand'];?></td>
                                        <td><?php echo substr($product['name'],0,20);?></td>
                                        <td><?php echo $product['quantity'];?>шт</td>
                                        <td><?php echo $product['sname'];?></td>
                                        <td><?php $products_total += $product['quantity'] * $product['price']; echo $product['quantity'] * $product['price']; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                <?php }?>
                <tr>
                    <td colspan="6" style="border: 1px solid; text-align: right;"><b>Итого: </b><?php echo $products_total;?></td>
                </tr>
            </table>
            <br>
            <?php } ?>
    <?php } ?>
</div>
</body>
</html>

