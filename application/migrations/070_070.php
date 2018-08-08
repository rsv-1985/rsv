<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_070 extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_redirect` (
  `id` int(11) NOT NULL,
  `url_from` varchar(255) NOT NULL,
  `url_to` varchar(255) NOT NULL,
  `status_code` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_redirect`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url_from` (`url_from`);");
        $this->db->query("ALTER TABLE `ax_redirect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        }
}