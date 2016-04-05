<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['email_must_be_array'] = 'Метод проверки электронной почты должен быть передан массив.';
$lang['email_invalid_address'] = 'Неправильный email адрес: %s';
$lang['email_attachment_missing'] = 'Не удалось найти следующее вложение электронной почты: %s';
$lang['email_attachment_unreadable'] = 'Не удается открыть это вложение: %s';
$lang['email_no_from'] = 'Не удается отправить почту с "From" заголовока.';
$lang['email_no_recipients'] = 'Вы должны включить получателей: To, Cc, or Bcc';
$lang['email_send_failure_phpmail'] = 'Невозможно отправить электронную почту, используя PHP mail(). Ваш сервер не настроен для отправки почты с помощью этого метода.';
$lang['email_send_failure_sendmail'] = 'Невозможно отправить электронную почту PHP Sendmail. Ваш сервер не настроен для отправки почты с помощью этого метода.';
$lang['email_send_failure_smtp'] = 'Невозможно отправить электронную почту PHP SMTP. Ваш сервер не настроен для отправки почты с помощью этого метода.';
$lang['email_sent'] = 'Ваше сообщение было успешно отправлено , используя следующий протокол: %s';
$lang['email_no_socket'] = 'Невозможно открыть сокет Sendmail. Проверьте настройки.';
$lang['email_no_hostname'] = 'Вы не указали SMTP Имя хоста.';
$lang['email_smtp_error'] = 'Ниже SMTP произошла ошибка: %s';
$lang['email_no_smtp_unpw'] = 'Ошибка: Вы должны задать SMTP логин и пароль.';
$lang['email_failed_smtp_login'] = 'Не удалось отправить команду AUTH LOGIN. Ошибка: %s';
$lang['email_smtp_auth_un'] = 'Не удалось проверить подлинность пользователя. Ошибка: %s';
$lang['email_smtp_auth_pw'] = 'Не удалось проверить подлинность пароль. Ошибка: %s';
$lang['email_smtp_data_failure'] = 'Не удалось отправить данные: %s';
$lang['email_exit_status'] = 'Выходной код состояния: %s';
