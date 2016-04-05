<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['db_invalid_connection_str'] = 'Не могу определить настройки базы данных, которые вы задали';
$lang['db_unable_to_connect'] = 'Не удается подключиться к серверу базы данных , используя предоставленные параметры.';
$lang['db_unable_to_select'] = 'Невозможно выбрать указанную базу данных: %s';
$lang['db_unable_to_create'] = 'Невозможно создать указанную базу данных: %s';
$lang['db_invalid_query'] = 'Запрос, который Вы сделали , не валидный.';
$lang['db_must_set_table'] = 'Вы должны задать таблицы базы данных, которые будут использоваться с вашего запроса.';
$lang['db_must_use_set'] = 'Вы должны использовать метод "set", чтобы обновить запись.';
$lang['db_must_use_index'] = 'Вы должны указать индекс, чтобы бы данные соответствовали обновлению.';
$lang['db_batch_missing_index'] = 'Одна или несколько строк, отсуствует индекс.';
$lang['db_must_use_where'] = 'Обновления не допускаются, если они не содержат "where" условие.';
$lang['db_del_must_use_where'] = 'Удаляет не допускаются, если они не содержат "where" или "like" параметр.';
$lang['db_field_param_missing'] = 'Чтобы выбрать поля требуется имя таблицы в качестве параметра';
$lang['db_unsupported_function'] = 'Эта функция не доступна для базы данных, которую вы используете.';
$lang['db_transaction_failure'] = 'Отказ: Откат выполняется.';
$lang['db_unable_to_drop'] = 'Невозможно удалить указанную базу данных.';
$lang['db_unsupported_feature'] = 'Поддерживается функция платформы базы данных вы ее используете.';
$lang['db_unsupported_compression'] = 'Формат сжатия файлов, вы выбрали не поддерживается сервером.';
$lang['db_filepath_error'] = 'Невозможно записать данные в пути файла, которые вы отправили.';
$lang['db_invalid_cache_path'] = 'Путь кэша, который Вы представили, не является валидным для записи.';
$lang['db_table_name_required'] = 'Имя таблицы требуется для этой операции.';
$lang['db_column_name_required'] = 'Имя столбца требуется для этой операции.';
$lang['db_column_definition_required'] = 'Определение столбца требуется для этой операции.';
$lang['db_unable_to_set_charset'] = 'Невозможно установить соединение из заданных параметром: %s';
$lang['db_error_heading'] = 'Ошибка базы данных';
