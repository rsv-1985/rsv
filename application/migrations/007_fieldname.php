<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_fieldname extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_importtmp` CHANGE `name` `name` VARCHAR(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
    }

    public function down()
    {

    }
}