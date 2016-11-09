
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_attributes2 extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_importtmp` ADD `attributes` LONGTEXT NOT NULL AFTER `image`;");
    }
    public function down()
    {

    }
}