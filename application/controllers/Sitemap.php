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
        $this->load->model('category_model');
        $this->load->model('news_model');
        $this->load->model('page_model');
    }

    public function write_file($urls,$index = '', $priority = '0.5'){
        $xml = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $url){
            $xml .= '<url>';
            $xml .= '<loc>'.$url['url'].'</loc>';
            $xml .= '<lastmod>'.date('Y-m-d',strtotime($url['updated_at'])).'</lastmod>';
            $xml .= '<priority>'.$priority.'</priority>';
            $xml .= '</url>';
        }
        $xml .= '</urlset>';
        write_file('./map/map'.$index.'.xml', $xml);
    }
    
    public function index()
    {
        $url_page = [];
        $url_news = [];
        $url_category = [];
        $url_product = [];

        if (!$this->uri->segment(3)) {
            delete_files('./map/', true);

            $url_page = $this->page_model->get_sitemap();
            if($url_page){
                $this->write_file($url_page, 'url_page','0.8');
            }

            $url_news = $this->news_model->get_sitemap();
            if($url_news){
                $this->write_file($url_news, 'url_news','0.7');
            }

            $url_category = $this->category_model->get_sitemap();
            if($url_category){
                $this->write_file($url_category, 'url_category','0.9');
            }

        }

        $result = $this->product_model->get_sitemap((int)$this->uri->segment(3));

        if($result){
            $file_number = (int)$this->input->get('file_number');
            $html = '<a id="next" href="/sitemap/index/'.$result['id'].'?file_number='.($file_number + 1).'">Загрузка</a>';
            $html .= '<script type="text/javascript">document.getElementById("next").click();</script>';

            $this->write_file($result['urls'], 'product'.$file_number,'0.6');
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
                $this->session->set_flashdata('success', 'Генерация <a target="_blank" href="'.base_url('map/sitemap.xml').'">карты сайта</a> закончена ');
                redirect(base_url('autoxadmin/settings'));
            }
        }
    }
}
