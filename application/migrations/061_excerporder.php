<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_excerporder extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order_product` ADD `excerpt` VARCHAR(255) NOT NULL AFTER `term`;");
    }
}