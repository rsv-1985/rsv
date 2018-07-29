<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_069 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer` ADD INDEX(`email`);");
        $this->db->query("ALTER TABLE `ax_customer` ADD INDEX(`phone`);");
        $this->db->query("ALTER TABLE `ax_customer` DROP `login`;");
        $this->db->query("UPDATE `ax_customer` SET `phone` = REPLACE(REPLACE(REPLACE(REPLACE(phone,' ',''),'(',''),')',''),'-','')");
        $this->db->query("UPDATE `ax_order` SET `telephone` = REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''),'(',''),')',''),'-','')");
        $this->db->query("UPDATE `ax_vin` SET `telephone` = REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''),'(',''),')',''),'-','')");
        $this->db->query("UPDATE `ax_waybill_parcel` SET `telephone` = REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''),'(',''),')',''),'-','')");
        $this->db->query("ALTER TABLE `ax_customer` ADD `payment_method_id` INT NOT NULL AFTER `negative_balance`;");
        $this->db->query("ALTER TABLE `ax_customer` ADD `delivery_method_id` INT NOT NULL AFTER `payment_method_id`;");
        $this->db->query("ALTER TABLE `ax_customer` ADD `additional_information` LONGTEXT NOT NULL AFTER `delivery_method_id`;");
    }
}