<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_079 extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_attribute` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `in_filter` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Отображать в фильтре',
  `in_short_description` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Отображать в кратком описании в каталоге',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `max_height` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_attribute`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_filter` (`in_filter`),
  ADD KEY `in_short_description` (`in_short_description`),
  ADD KEY `sort_order` (`sort_order`);");
        $this->db->query("ALTER TABLE `ax_attribute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        $this->db->query("CREATE TABLE `ax_attribute_value` (
  `id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_attribute_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`),
  ADD KEY `sort_order` (`sort_order`);");
        $this->db->query("ALTER TABLE `ax_attribute_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        $this->db->query("INSERT INTO `ax_attribute`(`name`, `in_filter`, `in_short_description`, `sort_order`, `max_height`)
(SELECT DISTINCT attribute_name, 1, 1, 0, 450 FROM ax_product_attributes)");
        $this->db->query("INSERT INTO `ax_attribute_value`(`attribute_id`, `value`, `sort_order`)
(SELECT DISTINCT a.id, pa.attribute_value, 0 FROM ax_product_attributes pa LEFT JOIN ax_attribute a ON a.name = pa.attribute_name)");
        $this->db->query("CREATE TABLE `ax_product_attribute` (
  `product_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_value_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_product_attribute`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `attribute_id` (`attribute_id`,`attribute_value_id`),
  ADD KEY `attribute_value_id` (`attribute_value_id`);");

        $this->db->query("INSERT INTO `ax_product_attribute`(`product_id`, `attribute_id`, `attribute_value_id`)
(SELECT pa.product_id, a.id, av.id FROM ax_product_attributes pa LEFT JOIN ax_attribute a ON a.name = pa.attribute_name
LEFT JOIN ax_attribute_value av ON av.value = pa.attribute_value AND av.attribute_id = a.id)");

        $this->db->query("DROP TABLE ax_product_attributes");
    }

    public function down(){

    }
}