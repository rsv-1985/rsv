<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_group_brand extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_group_brand` (
  `id` int(11) NOT NULL,
  `group_name` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE `ax_group_brand_item` (
  `group_brand_id` int(11) NOT NULL,
  `brand` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_group_brand`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_name` (`group_name`);");
        $this->db->query("ALTER TABLE `ax_group_brand_item`
  ADD UNIQUE KEY `brand` (`brand`),
  ADD KEY `group_brands_id` (`group_brand_id`);");
        $this->db->query("ALTER TABLE `ax_group_brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }
}