<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_073 extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_canonical` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `canonical` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_canonical`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url_from` (`url`);");
        $this->db->query("ALTER TABLE `ax_canonical`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }
}