<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$lang['text_heading'] = 'Импорт';
$lang['text_price'] = 'Прайс';
$lang['text_price_info'] = 'Форматы: <br><b>XLS</b> до 65000 строк<br> <b>CSV</b> в кодировке "UTF-8" разделитель ";"';
$lang['text_supplier'] = 'Поставщик';
$lang['text_sample'] = 'Шаблон импорта';
$lang['text_sample_info'] = 'Укажите номера колонок в соответствие колонок в Вашем прайсе';
$lang['text_sample_currency'] = 'Валюта прайс листа';
$lang['text_sample_category'] = 'Категория по умолчанию';
$lang['text_sample_default_term'] = 'Срок поставки по умолчанию для всех';
$lang['text_sample_sku'] = 'Артикул';
$lang['text_sample_brand'] = 'Производитель';
$lang['text_sample_name'] = 'Наименование';
$lang['text_sample_description'] = 'Описание';
$lang['text_sample_excerpt'] = 'Доп.инфо';
$lang['text_sample_delivery_price'] = 'Цена поставки';
$lang['text_sample_saleprice'] = 'Акционная цена';
$lang['text_sample_quantity'] = 'Количество';
$lang['text_sample_term'] = 'Срок поставки';
$lang['text_sample_category'] = 'Категория';
$lang['text_sample_image'] = 'Картинка';
$lang['text_sample_default_category'] = 'Категория по умолчанию для всех';
$lang['text_save_sample'] = 'Сохранить шаблон?';
$lang['text_save_sample_name'] = 'Название шаблона';
$lang['text_tmptable'] = 'Таблица предварительного просмотра';
$lang['text_tmp_total'] = 'Всего';
$lang['button_import'] = 'Все верно, загружаем';
$lang['text_success'] = 'Все прошло успешно. Кэш был очищен.';
$lang['text_success_cancel'] = 'Временная таблица очищена';
$lang['text_success_import'] = 'Данные обработаны и помещены во временную таблицу, проверьте их и продолжите импорт. Товары в которых Артикул пустой, Производитель пустой, Цена поставки ровна 0, Количество ровно 0 были удалены.';
$lang['text_error_import'] = 'После очистки товаров в которых Артикул = "", Производитель = "", Цена поставки = 0, Количество = 0, во временной таблице не осталось товаров. <b>Проверьте свой прайс и шаблон импорта</b>';
$lang['text_import_settings'] = 'Настройки импорта:';
$lang['text_import_settings_add'] = 'Добавить и обновить';
$lang['text_import_settings_delete'] = 'Удалить товары поставщика';
$lang['text_import_settings_disble'] = 'Скрыть товары поставщика перед импортом';
$lang['text_sample_default_term_unit_hour'] = 'В часах';
$lang['text_sample_default_term_unit_day'] = 'В днях';
$lang['text_sample_term_unit'] = 'Единица измерения срока поставки';
$lang['text_sample_default_excerpt'] = 'Доп.инфо по умолчанию для всех';
$lang['text_sample_attributes'] = 'Атрибуты';