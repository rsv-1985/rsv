<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Delivery_price_product_order extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order_product` ADD `delivery_price` FLOAT NOT NULL AFTER `quantity`;");
        $this->db->query("ALTER TABLE `ax_order_product` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);");
    }

    public function down()
    {
        return;
    }
}