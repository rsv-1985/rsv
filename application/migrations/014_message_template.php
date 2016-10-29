
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_message_template extends CI_Migration {

    public function up()
    {
        $this->db->query("DROP TABLE IF EXISTS `ax_message`;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `ax_message` (
  `id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `text_sms` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");
        $this->db->query("INSERT INTO `ax_message` (`id`, `title`, `subject`, `text`, `text_sms`) VALUES
(1, 'Новый заказ', 'Новый заказ №{order_id}', '<p>{first_name} {last_name}, спасибо за заказ №{order_id} на нашем сайте!<br>\r\n<br>\r\n<strong>Имя: </strong>{first_name}<br>\r\n<strong>Фамилия: </strong>{last_name}<br>\r\n<strong>Телефон: </strong>{telephone}<br>\r\n<strong>Email: </strong>{email}<br>\r\n<strong>Комментарий: </strong>{comments}<br>\r\n<strong>Дата: </strong>{created_at}</p>\r\n\r\n<p><strong>Способ доставки: </strong>{delivery_method}<br>\r\n<strong>Способ оплаты: </strong>{payment_method}</p>\r\n\r\n<p>{products}<br>\r\n<br>\r\n<strong>Cтоимость доставки: </strong>{delivery_price}<br>\r\n<strong>Комиссия: </strong>{commission}<br>\r\n<strong>Сумма: </strong>{total}</p>', 'Заказа № {order_id} сума {total}'),
(2, 'Смена статуса заказа', 'Смена статуса заказа №{order_id}', '<p><strong>Статус: </strong>{status}<br>\r\n<br>\r\n<strong>Имя: </strong>{first_name}<br>\r\n<strong>Фамилия: </strong>{last_name}<br>\r\n<strong>Телефон: </strong>{telephone}<br>\r\n<strong>Email: </strong>{email}<br>\r\n<strong>Дата: </strong>{created_at}</p>\r\n\r\n<p><strong>Способ доставки: </strong>{delivery_method}<br>\r\n<strong>Способ оплаты: </strong>{payment_method}</p>\r\n\r\n<p>{products}<br>\r\n<br>\r\n<strong>Cтоимость доставки: </strong>{delivery_price}<br>\r\n<strong>Комиссия: </strong>{commission}<br>\r\n<strong>Сумма: </strong>{total}</p>', ' статуса заказа №{order_id} - {status}'),
(3, 'Регистрация', 'Регистрация на сайте', '<p>Вы прошли успешную регистрацию на сайте под логином {login}</p>', 'Вы прошли успешную регистрацию на сайте под логином {login}');
");
        $this->db->query("ALTER TABLE `ax_message`
  ADD PRIMARY KEY (`id`);");
        $this->db->query("ALTER TABLE `ax_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;");
    }

    public function down()
    {

    }
}