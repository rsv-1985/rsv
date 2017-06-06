<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_balance extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_customer_balance` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `value` float(10,2) NOT NULL,
  `description` text NOT NULL,
  `transaction_created_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_customer_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `cutomer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `value` (`value`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `transaction_created_at` (`transaction_created_at`);");
        $this->db->query("ALTER TABLE `ax_customer_balance` ADD FULLTEXT KEY `description` (`description`);");
        $this->db->query("ALTER TABLE `ax_customer_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }

    public function down()
    {
        $this->db->query("DROP TABLE ax_customer_balance");
    }
}