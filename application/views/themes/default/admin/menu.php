<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<ul class="sidebar-menu">
    <li class="treeview">
        <a href="#">
            <i class="fa fa-shopping-cart"></i> <span><?php echo lang('text_shop');?></span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/order"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_order');?></a></li>
            <li><a href="/autoxadmin/order/products"><i class="fa fa-angle-right"></i>Детали в работе</a></li>
            <li><a href="/autoxadmin/supplier"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_supplier');?></a></li>
            <li><a href="/autoxadmin/customer"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_customer');?></a></li>
            <li><a href="/autoxadmin/customergroup"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_customergroup');?></a></li>
            <li><a href="/autoxadmin/newsletter"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_newsletter');?></a></li>
            <li><a href="/autoxadmin/vin"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_vin');?></a></li>
            <li><a href="/autoxadmin/review"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_review');?></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-tags fa-fw"></i> <span>Каталог</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/category"><i class="fa  fa-angle-right"></i><?php echo lang('text_nav_category');?></a></li>
            <li><a href="/autoxadmin/product"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_product');?></a></li>
            <li><a href="/autoxadmin/attribute"><i class="fa fa-angle-right"></i>Атрибуты</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-usd"></i> <span><?php echo lang('text_balance');?></span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/customerbalance"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_customerbalance');?></a></li>
            <li><a href="/autoxadmin/customer_pay"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_customer_pay');?></a></li>
            <li><a href="/autoxadmin/invoice"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_invoice');?></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-building" aria-hidden="true"></i> <span><?php echo lang('text_store');?></span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a target="_blank" href="/autoxadmin/waybill"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_waybill');?></a></li>
            <li><a href="/autoxadmin/order_ttn"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_order_ttn');?></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-wrench"></i> <span><?php echo lang('text_nav_sto');?>
                        </span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/sto"><i class="fa fa-angle-right"></i>Заявки</a></li>
            <li><a href="/autoxadmin/sto/settings"><i class="fa fa-angle-right"></i>Настройки</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-pencil-square-o"></i> <span><?php echo lang('text_content');?></span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/page"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_page');?></a></li>
            <li><a href="/autoxadmin/news"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_news');?></a></li>
            <li><a href="/autoxadmin/important_news"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_important_news');?></a></li>
            <li><a href="/autoxadmin/banner"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_banner');?></a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-wrench"></i> <span><?php echo lang('text_instruments');?></span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/synonym"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_synonym');?></a></li>
            <li><a href="/autoxadmin/brand_group"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_brand_group');?></a></li>
            <li><a href="/autoxadmin/synonym_name"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_synonym_name');?></a></li>
            <li><a href="/autoxadmin/import"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_import');?></a></li>
            <li><a href="/autoxadmin/cross"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_cross');?></a></li>
            <li><a href="/autoxadmin/price"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_price');?></a></li>
            <li><a href="/autoxadmin/sending"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_sending');?></a></li>

        </ul>
    </li>
    <li class="treeview">
        <a href="#"><i class="fa fa-area-chart" aria-hidden="true"></i> Отчеты</a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/report/cart"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_cart');?></a></li>
            <li><a href="/autoxadmin/report/search_history"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_search_history');?></a></li>
            <li><a href="/autoxadmin/report/sale_order"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_report_sale_order');?></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-dashboard"></i> <span>SEO настройки</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/seo_settings/product"><i class="fa fa-angle-right"></i>Страница товара</a></li>
            <li><a href="/autoxadmin/seo_settings/brand"><i class="fa fa-angle-right"></i>Фильтр в категории</a></li>
            <li><a href="/autoxadmin/seo_settings/robots"><i class="fa fa-angle-right"></i>Robots.txt</a></li>
            <li><a href="/autoxadmin/seo_settings/sitemap"><i class="fa fa-angle-right"></i>Sitemap</a></li>
            <li><a href="/autoxadmin/seo_settings/tecdoc"><i class="fa fa-angle-right"></i>Каталог Tecdoc</a></li>
            <li><a href="/autoxadmin/seo_settings/tecdoc_manufacturer"><i class="fa fa-angle-right"></i>Tecdoc производители</a></li>
            <li><a href="/autoxadmin/seo_settings/tecdoc_model"><i class="fa fa-angle-right"></i>Tecdoc модели</a></li>
            <li><a href="/autoxadmin/seo_settings/tecdoc_type"><i class="fa fa-angle-right"></i>Tecdoc модификации</a></li>
            <li><a href="/autoxadmin/seo_settings/tecdoc_tree"><i class="fa fa-angle-right"></i>Tecdoc категории</a></li>
            <li><a href="/autoxadmin/seo_settings/hook"><i class="fa fa-angle-right"></i>SEO hook</a></li>
            <li><a href="/autoxadmin/seo_settings/redirect"><i class="fa fa-angle-right"></i>Redirect</a></li>
            <li><a href="/autoxadmin/seo_settings/canonical"><i class="fa fa-angle-right"></i>Canonical</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-cog"></i> <span>Tecdoc настройки</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/tecdoc_settings/manufacturer"><i class="fa fa-angle-right"></i>Производители</a></li>
            <li><a href="/autoxadmin/tecdoc_settings/tree"><i class="fa fa-angle-right"></i>Категории</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-cogs"></i> <span><?php echo lang('text_settings');?></span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/autoxadmin/settings"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_settings');?></a></li>
            <li><a href="/autoxadmin/language"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_language');?></a></li>
            <li><a href="/autoxadmin/currency"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_currency');?></a></li>
            <li><a href="/autoxadmin/payment"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_payment');?></a></li>
            <li><a href="/autoxadmin/delivery"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_delivery');?></a></li>
            <li><a href="/autoxadmin/orderstatus"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_orderstatus');?></a></li>
            <li><a href="/autoxadmin/user"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_user');?></a></li>
            <li><a href="/autoxadmin/usergroup"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_usergroup');?></a></li>
            <li><a href="/autoxadmin/apikey"><i class="fa fa-angle-right"></i>API ключи</a></li>
            <li><a href="/autoxadmin/message_template"><i class="fa fa-angle-right"></i><?php echo lang('text_nav_message_template');?></a></li>
            <li><a href="/autoxadmin/index/cache"><i class="fa fa-angle-right"></i>Очистить кэш</a></li>
            <li><a target="_blank" class="confirm" data-confirm="Вы ознакомились с обновлениями и хотите продолжить ?" href="/autoxadmin/index/updatesystem"><i class="fa fa-angle-right"></i>Обновить систему</a></li>
        </ul>
    </li>

</ul>
