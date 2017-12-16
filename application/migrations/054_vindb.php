<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_vindb extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_vin` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `manufacturer` varchar(32) NOT NULL,
  `model` varchar(255) NOT NULL,
  `engine` varchar(32) NOT NULL,
  `vin` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `parts` text NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_vin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);");

        $this->db->query("ALTER TABLE `ax_vin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }


    public function down()
    {
        $this->db->query("DROP TABLE `ax_vin`");
    }
}