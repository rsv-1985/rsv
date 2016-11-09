
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_attributes extends CI_Migration {

    public function up()
    {
        $this->db->query("DROP TABLE IF EXISTS `ax_product_attributes`;");
        $this->db->query("CREATE TABLE `ax_product_attributes` (
  `product_id` int(11) NOT NULL,
  `attribute_name` varchar(255) NOT NULL,
  `attribute_value` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `attribute_slug` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_product_attributes`
  ADD KEY `attribute_slug` (`attribute_slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`);");
    }
    public function down()
    {

    }
}