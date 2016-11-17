
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_unregistered extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer_group` ADD `is_unregistered` BOOLEAN NOT NULL AFTER `is_default`;");
    }
    public function down()
    {
        return;
    }
}