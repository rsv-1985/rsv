<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_idart extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_importtmp` ADD `id_art` INT NOT NULL AFTER `attributes`;");
    }

    public function down()
    {

    }
}