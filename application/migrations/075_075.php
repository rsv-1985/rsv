<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_075 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer_balance` ADD `balance` DECIMAL(10,2) NOT NULL AFTER `user_id`;");
        $this->db->query("ALTER TABLE `ax_customer_balance` ADD `invoice_id` INT NOT NULL AFTER `balance`;");
        $this->db->query("ALTER TABLE `ax_customer_balance` ADD `pay_id` INT NOT NULL AFTER `invoice_id`;");
        $this->db->query("ALTER TABLE `ax_customer_balance` ADD INDEX (`invoice_id`);");
        $this->db->query("ALTER TABLE `ax_customer_balance` ADD INDEX (`pay_id`);");
        $this->db->query("ALTER TABLE `ax_order` DROP `paid`, DROP `prepayment`;");
        $this->db->query("ALTER TABLE `ax_order_status` ADD `sort_order` INT NOT NULL AFTER `is_return`;");
        $this->db->query("CREATE TABLE `ax_customer_pay` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `comment` text NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Сообщение об оплате';");
        $this->db->query("ALTER TABLE `ax_customer_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);");
        $this->db->query("ALTER TABLE `ax_customer_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        $this->db->query("DROP TABLE `ax_waybill`, `ax_waybill_parcel`, `ax_waybill_product`;");
        $this->db->query("CREATE TABLE `ax_invoice` (
  `id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `customer_id` int(11) NOT NULL,
  `delivery_price` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE `ax_invoice_product` (
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `customer_id` (`customer_id`);");
        $this->db->query("ALTER TABLE `ax_invoice_product`
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `invoice_id` (`invoice_id`);
");
        $this->db->query("ALTER TABLE `ax_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }

    public function down(){

    }
}