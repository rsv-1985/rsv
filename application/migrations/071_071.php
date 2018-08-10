<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_071 extends CI_Migration
{

    public function up()
    {
        $this->db->query("
CREATE TABLE `ax_black_list` (
  `customer_id` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_black_list`
  ADD UNIQUE KEY `user_id` (`customer_id`);");
    }
}