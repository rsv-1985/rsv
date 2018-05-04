<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_066 extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_cart_deferred` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_cart_deferred`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);");
        $this->db->query("ALTER TABLE `ax_cart_deferred`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }
}