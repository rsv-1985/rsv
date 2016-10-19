
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_bigupdate extends CI_Migration {

    public function up()
    {
        $this->db->query("DROP TABLE IF EXISTS `ax_product`;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `ax_product` (
          `id` int(11) NOT NULL,
          `sku` varchar(32) NOT NULL DEFAULT '',
          `brand` varchar(32) NOT NULL DEFAULT '',
          `name` varchar(155) DEFAULT NULL,
          `image` varchar(255) NOT NULL,
          `h1` varchar(250) NOT NULL,
          `title` varchar(250) NOT NULL,
          `meta_description` text NOT NULL,
          `meta_keywords` varchar(250) NOT NULL,
          `slug` varchar(255) NOT NULL,
          `viewed` int(11) NOT NULL DEFAULT '0',
          `bought` int(11) NOT NULL,
          `category_id` int(11) DEFAULT '0',
          `description` text NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_product`
          ADD PRIMARY KEY (`id`),
          ADD UNIQUE KEY `slug` (`slug`),
          ADD UNIQUE KEY `sku` (`sku`,`brand`);");

        $this->db->query("ALTER TABLE `ax_product`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

        $this->db->query("DROP TABLE IF EXISTS `ax_product_price`;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `ax_product_price` (
          `product_id` int(11) NOT NULL,
          `excerpt` varchar(125) NOT NULL,
          `currency_id` int(11) NOT NULL,
          `delivery_price` float(10,2) NOT NULL,
          `saleprice` float(10,2) NOT NULL,
          `price` float(10,2) NOT NULL,
          `quantity` int(11) NOT NULL,
          `supplier_id` int(11) NOT NULL,
          `term` int(11) NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          `status` tinyint(1) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_product_price`
        ADD PRIMARY KEY (`product_id`,`supplier_id`,`term`) USING BTREE;");

        $this->db->query("ALTER TABLE `ax_order_product` ADD `term` INT NOT NULL AFTER `status_id`;");
    }

    public function down()
    {

    }
}