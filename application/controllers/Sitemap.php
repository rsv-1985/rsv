<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends Admin_controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('news_model');
        $this->load->model('page_model');
    }

    public function write_file($urls, $index = '', $priority = false, $changefreq = false){
        $xml = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $url){
            $xml .= '<url>';

            $xml .= '<loc>'.$url['url'].'</loc>';

            if($priority){
                $xml .= '<priority>'.$priority.'</priority>';
            }

            if($changefreq){
                $xml .= '<changefreq>'.$changefreq.'</changefreq>';
            }

            if(isset($url['updated_at'])){
                $xml .= '<lastmod>'.date('Y-m-d', strtotime($url['updated_at'])).'</lastmod>';
            }

            $xml .= '</url>';
        }
        $xml .= '</urlset>';
        write_file('./map/map'.$index.'.xml', $xml);
    }
    
    public function index()
    {
        $seo_map = $this->settings_model->get_by_key('seo_map');

        if (!$this->uri->segment(3)) {
            //Удаляем старые файлы
            delete_files('./map/', true);

            if($seo_map['home_status']){
                //Главная страница
                $url[]=[
                    'url' => site_url(),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $this->write_file($url, 'url_home', $seo_map['home_priopity'], $seo_map['home_changefreq']);
            }

            if($seo_map['page_status']){
                //Страницы
                $url_page = $this->page_model->get_sitemap();
                if($url_page){
                    $this->write_file($url_page, 'url_page',  $seo_map['page_priopity'], $seo_map['page_changefreq']);
                }
            }

            if($seo_map['news_status']){
                //Новости
                $url_news = $this->news_model->get_sitemap();
                if($url_news){
                    $this->write_file($url_news, 'url_news',  $seo_map['news_priopity'], $seo_map['news_changefreq']);
                }
            }

            if($seo_map['category_status']){
                //Категории
                $url_category = $this->category_model->get_sitemap();
                if($url_category){
                    $this->write_file($url_category, 'url_category', $seo_map['category_priopity'], $seo_map['category_changefreq']);
                }
            }

            if($seo_map['td_status']){
                $url_manuf = [];
                $url_model = [];
                $manufacturers = $this->tecdoc->getManufacturer();
                if($manufacturers) {
                    $settings_tecdoc_manufacturer = $this->settings_model->get_by_key('tecdoc_manufacturer');
                    $array_manuf = [];
                    foreach ($manufacturers as $item) {
                        if ($settings_tecdoc_manufacturer) {
                            if (isset($settings_tecdoc_manufacturer[url_title($item->Name)]) && @$settings_tecdoc_manufacturer[url_title($item->Name)]['status']) {

                                $manuf_url = base_url('catalog').'/'.url_title($item->Name) . '_' . $item->ID_mfa;
                                $url_manuf[] = ['url' => $manuf_url];

                                $models_type = $this->tecdoc->getModel($item->ID_mfa);

                                if($models_type){
                                    foreach ($models_type as $model_type){
                                        $url_model[] = ['url' => $manuf_url.'/'.url_title($model_type->Name). '_' . $model_type->ID_mod];
                                    }
                                }
                            }
                        } else {
                            if (file_exists('./uploads/model/' . str_replace('Ë', 'E', $item->Name) . '.png')) {
                                $manuf_url = base_url('catalog').'/'.url_title($item->Name) . '_' . $item->ID_mfa;
                                $url_manuf[] = ['url' => $manuf_url];

                                $models_type = $this->tecdoc->getModel($item->ID_mfa);

                                if($models_type){
                                    foreach ($models_type as $model_type){
                                        $url_model[] = ['url' => $manuf_url.'/'.url_title($model_type->Name). '_' . $model_type->ID_mod];
                                    }
                                }
                            }
                        }
                    }
                }

                if($url_manuf){
                    $this->write_file($url_manuf, 'url_manuf', $seo_map['td_priopity'], $seo_map['td_changefreq']);
                }

                if($url_model){
                    $this->write_file($url_model, 'url_model', $seo_map['td_priopity'], $seo_map['td_changefreq']);
                }
            }
        }
        if($seo_map['product_status']){
            $result = $this->product_model->get_sitemap((int)$this->uri->segment(3));

            if($result){
                $file_number = (int)$this->input->get('file_number');
                $html = '<a id="next" href="/sitemap/index/'.$result['id'].'?file_number='.($file_number + 1).'">Загрузка</a>';
                $html .= '<script type="text/javascript">document.getElementById("next").click();</script>';

                $this->write_file($result['urls'], 'product'.$file_number, $seo_map['product_priopity'], $seo_map['product_changefreq']);
                echo $html;
                die();
            }
        }


        $files = get_filenames('./map/');
        if (!empty($files)) {
            $text = '';
            $text = '<?xml version="1.0" encoding="UTF-8"?>';
            $text .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($files as $file) {
                $info = pathinfo($file);
                if($info['extension'] == 'xml' && $info['basename'] != 'sitemap.xml'){
                    $text .= '<sitemap>';
                    $text .= '<loc>' . base_url('map') . '/' . $file . '</loc>';
                    $text .= '</sitemap>';
                }
            }
            $text .= '</sitemapindex>';
            write_file('./map/sitemap.xml', $text);
            $this->session->set_flashdata('success', 'Генерация <a target="_blank" href="'.base_url('map/sitemap.xml').'">карты сайта</a> закончена ');
            redirect(base_url('/autoxadmin/seo_settings/sitemap'));
        }
    }
}
