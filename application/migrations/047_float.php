<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_float extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order_product` CHANGE `delivery_price` `delivery_price` FLOAT(10,2) NOT NULL;");
        $this->db->query("ALTER TABLE `ax_order_product` CHANGE `price` `price` FLOAT(10,2) NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `ax_order_product` CHANGE `price` `price` FLOAT(10,2) NULL DEFAULT NULL;");
    }

    public function down()
    {

    }
}