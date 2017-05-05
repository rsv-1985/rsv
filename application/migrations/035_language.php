<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Language extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_language` (
          `id` int(11) NOT NULL,
          `language` varchar(32) NOT NULL,
          `line` varchar(255) NOT NULL,
          `text` text NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_language`
          ADD PRIMARY KEY (`id`),
          ADD UNIQUE KEY `line` (`line`);");
        $this->db->query("ALTER TABLE `ax_language` ADD FULLTEXT KEY `text` (`text`);");
        $this->db->query("ALTER TABLE `ax_language` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }

    public function down()
    {
        return;
    }
}