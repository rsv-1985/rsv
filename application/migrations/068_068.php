<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_068 extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_order_ttn` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `ttn` varchar(55) NOT NULL,
  `library` varchar(155) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_order_ttn`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`) USING BTREE;");

        $this->db->query("ALTER TABLE `ax_order_ttn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;");

        $this->db->query("CREATE TABLE `ax_np` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `RecipientCityName` varchar(255) NOT NULL,
  `RecipientArea` varchar(255) NOT NULL,
  `RecipientAreaRegions` varchar(255) NOT NULL,
  `RecipientAddressName` varchar(255) NOT NULL,
  `RecipientAddressName2` varchar(255) NOT NULL,
  `RecipientHouse` varchar(32) NOT NULL,
  `RecipientFlat` char(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_np`
  ADD PRIMARY KEY (`id`);");

        $this->db->query("ALTER TABLE `ax_np`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }
}