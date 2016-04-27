<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_order_edit extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order_product` ADD `status_id` INT NOT NULL AFTER `supplier_id`;");
        $this->db->query("CREATE TABLE `ax_order_history` ( `order_id` INT NOT NULL , `date` DATETIME NOT NULL , `text` TEXT NOT NULL , `send_sms` BOOLEAN NOT NULL , `send_email` BOOLEAN NOT NULL , `user_id` INT NOT NULL , INDEX `order_id` (`order_id`)) ENGINE = MyISAM;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `ax_order_product` DROP `status_id`;");
    }
}