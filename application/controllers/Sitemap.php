<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends Front_controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->model('product_model');
        $this->load->model('news_model');
        $this->load->model('page_model');
    }

    public function write_file($text,$i = ''){
        write_file('./map/map'.$i.'.txt', $text);
    }
    
    public function index()
    {
        $url_page = [];
        $url_news = [];
        $url_product = [];

        if (!$this->uri->segment(3)) {
            delete_files('./map/', true);
            $url_news = $this->news_model->get_sitemap();
            $url_page = $this->page_model->get_sitemap();
        }

        $url_product = $this->product_model->get_sitemap(50000, $this->uri->segment(3));
        if($url_product){
            $urls = array_merge($url_page, $url_news, $url_product);
            $pagin = $this->uri->segment(3) + 50000;
            $html = '<a id="next" href="/sitemap/index/'.$pagin.'">Загрузка</a>';
            $html .= '<script type="text/javascript">document.getElementById("next").click();</script>';
            $text = implode(chr(10), $urls);
            $this->write_file($text, time());
            echo $html;
            die();
        } else {
            $files = get_filenames('./map/');
            if (!empty($files)) {
                $text = '';
                $text = '<?xml version="1.0" encoding="UTF-8"?>';
                $text .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
                foreach ($files as $file) {
                    $text .= '<sitemap>';
                    $text .= '<loc>' . base_url('map') . '/' . $file . '</loc>';
                    $text .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
                    $text .= '</sitemap>';
                }
                $text .= '</sitemapindex>';
                write_file('./map/sitemap.xml', $text);
                $this->session->set_flashdata('message', 'Генерация карты сайта закончена');
                redirect(base_url('autoxadmin'));
            }
        }
    }
}
