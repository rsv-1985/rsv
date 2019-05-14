<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_081 extends CI_Migration
{

    public function up()
    {
        $this->db->query("INSERT INTO `ax_message` (`id`, `title`, `subject`, `text`, `text_sms`) VALUES (NULL, 'Подтверждение оплаты', '', '', '');");
        $this->db->query("INSERT INTO `ax_message` (`id`, `title`, `subject`, `text`, `text_sms`) VALUES (NULL, 'Реквизиты', '', '', '');");
    }

    public function down(){

    }
}