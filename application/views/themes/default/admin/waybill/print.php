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
                <th>Получатель</th>
                <th>Способ доставки</th>
                <th>Товары</th>
                <th>ТТН</th>
            </tr>
            </thead>
            <?php foreach ($parcels as $parcel){?>
                <tr>
                    <td><?php echo $parcel['id'];?></td>
                    <td><?php echo $parcel['first_name'].' '.$parcel['last_name'].' '.$parcel['patronymic'];?><br>
                        <?php echo $parcel['telephone'];?><br>
                        <?php echo $parcel['address'];?></td>
                    <td><?php echo $parcel['delivery_method'];?></td>
                    <td>
                        <?php if($parcel['products']){?>
                            <ul>
                                <?php foreach ($parcel['products'] as $product){?>
                                    <li><?php echo $product['sku'] . ' ' . $product['brand'] . ' ' . $product['name']; ?>  (<?php echo $product['quantity'];?>шт) (<?php echo $product['sname'];?>)</li>
                                <?php } ?>
                            </ul>
                        <?php }else{?>
                            В данной посылке нет товаров
                        <?php } ?>
                    </td>
                    <td><?php echo $parcel['ttn'];?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</div>
</body>
</html>

