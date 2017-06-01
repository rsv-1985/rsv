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
    }

    public function down()
    {
        return;
    }
}