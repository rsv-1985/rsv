<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_synonymname extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_synonym_name` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name2` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_synonym_name`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);");
        $this->db->query("ALTER TABLE `ax_synonym_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }

    public function down()
    {
        $this->db->query("DROP TABLE `ax_synonym_name`");
    }
}