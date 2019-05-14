<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_082 extends CI_Migration
{

    public function up()
    {
        $this->db->query("INSERT INTO `ax_message` (`id`, `title`, `subject`, `text`, `text_sms`) VALUES (NULL, 'Отправка ТТН', '', '', '');");
    }

    public function down(){

    }
}