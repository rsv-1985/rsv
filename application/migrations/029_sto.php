<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Sto extends CI_Migration {

    public function up()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `ax_sto` (
  `id` int(11) NOT NULL,
  `service` varchar(255) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `typ` varchar(255) NOT NULL,
  `vin` varchar(255) NOT NULL,
  `date` varchar(32) NOT NULL,
  `time` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `carnumber` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `comment` varchar(3000) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_sto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `date` (`date`);");
        $this->db->query("ALTER TABLE `ax_sto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }
    public function down()
    {
        return;
    }
}