<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update06032017 extends CI_Migration {

    public function up()
    {
        $this->db->query("UPDATE `ax_migrations` SET `version`= 28;");
        $this->db->query("UPDATE ax_product_price SET price = 0;");
    }
    public function down()
    {
        return;
    }
}