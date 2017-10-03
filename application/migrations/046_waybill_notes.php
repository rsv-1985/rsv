<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_waybill_notes extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_waybill` ADD `notes` TEXT NOT NULL AFTER `updated_at`");
    }

    public function down()
    {

    }
}