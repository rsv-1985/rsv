<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_065 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE ax_product` DROP INDEX `name`");
        $this->db->query("ALTER TABLE `ax_product_price` DROP `price`");
        $this->db->query("ALTER TABLE `ax_product` ADD `static_price` FLOAT(10,2) NOT NULL AFTER `description`;");
        $this->db->query("ALTER TABLE `ax_product` ADD `static_currency_id` INT NOT NULL AFTER `static_price`;");
    }
}