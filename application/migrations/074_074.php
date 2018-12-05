<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_074 extends CI_Migration
{

    public function up()
    {
        $this->db->query("DROP TABLE `autox`.`ax_sto`");

        $this->db->query("CREATE TABLE `ax_sto` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `vin` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `carnumber` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `comment` varchar(3000) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_sto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status_id`),
  ADD KEY `date` (`date`);");

        $this->db->query("ALTER TABLE `ax_sto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

        $this->db->query("CREATE TABLE `ax_sto_service` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_sto_service`
  ADD PRIMARY KEY (`id`);");

        $this->db->query("ALTER TABLE `ax_sto_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

        $this->db->query("CREATE TABLE `ax_sto_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sms_template` text NOT NULL,
  `email_template` text NOT NULL,
  `color` varchar(32) NOT NULL,
  `is_new` tinyint(4) NOT NULL,
  `is_complete` tinyint(4) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_sto_status`
  ADD PRIMARY KEY (`id`);");

        $this->db->query("ALTER TABLE `ax_sto_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    }

    public function down(){

    }
}