<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_080 extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_supplier_pay` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `comment` text NOT NULL,
  `transaction_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `ax_supplier_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `transaction_date` (`transaction_date`);");

        $this->db->query("ALTER TABLE `ax_supplier_pay` ADD FULLTEXT KEY `comment` (`comment`)");

        $this->db->query("ALTER TABLE `ax_supplier_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }

    public function down(){

    }
}