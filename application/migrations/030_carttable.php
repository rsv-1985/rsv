<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Carttable extends CI_Migration {

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_cart` (
  `customer_id` int(11) NOT NULL,
  `cart_data` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
        $this->db->query("ALTER TABLE `ax_cart`
  ADD UNIQUE KEY `user_id` (`customer_id`) USING BTREE;");
    }
    public function down()
    {
        $this->db->query("DROP TABLE ax_cart");
    }
}