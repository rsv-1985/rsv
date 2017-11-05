<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_grouppricing extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_customer_group_pricing` (
  `customer_group_id` int(11) DEFAULT NULL,
  `brand` varchar(32) NOT NULL,
  `price_from` float(10,2) DEFAULT NULL,
  `price_to` float(10,2) DEFAULT NULL,
  `method_price` char(1) DEFAULT NULL,
  `value` float DEFAULT NULL,
  `fix_value` float(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_customer_group_pricing`
  ADD KEY `customer_group_id` (`customer_group_id`);");
    }

    public function down()
    {
        $this->db->query("DROP TABLE `ax_customer_group_pricing`");
    }
}