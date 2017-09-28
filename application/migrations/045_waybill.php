<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_waybill extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_waybill` (
  `id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE `ax_waybill_parcel` (
  `id` int(11) NOT NULL,
  `waybill_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `patronymic` varchar(255) NOT NULL,
  `delivery_method_id` int(11) NOT NULL,
  `delivery_method` varchar(255) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `total` float(10,2) NOT NULL,
  `paid` tinyint(4) NOT NULL,
  `notes` text NOT NULL,
  `packed` datetime DEFAULT NULL,
  `ttn` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE `ax_waybill_product` (
  `id` int(11) NOT NULL,
  `waybill_parcel_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_waybill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`);");
        $this->db->query("ALTER TABLE `ax_waybill_parcel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `waybill_id` (`waybill_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `delivery_method_id` (`delivery_method_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `patronymic` (`patronymic`),
  ADD KEY `address` (`address`);");
        $this->db->query("ALTER TABLE `ax_waybill_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_product_id` (`order_product_id`),
  ADD KEY `waybill_parcel_id` (`waybill_parcel_id`);");
        $this->db->query("ALTER TABLE `ax_waybill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        $this->db->query("ALTER TABLE `ax_waybill_parcel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        $this->db->query("ALTER TABLE `ax_waybill_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }

    public function down()
    {
        $this->db->query("DROP TABLE ax_waybill");
        $this->db->query("DROP TABLE ax_waybill_parcel");
        $this->db->query("DROP TABLE ax_waybill_product");
    }
}