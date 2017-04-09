<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Search_history extends CI_Migration {

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_search_history` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
        $this->db->query("ALTER TABLE `ax_search_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `brand` (`brand`),
  ADD KEY `sku` (`sku`);");
    }
    public function down()
    {
        $this->db->query("DROP TABLE ax_search_history");
    }
}