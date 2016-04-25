<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Catalog extends Front_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('catalog');
        $this->load->library('user_agent');
        if($this->agent->is_robot()){
            show_404();
        }
    }

    public function index()
    {
        $settings = $this->settings_model->get_by_key('seo_tecdoc');
        if($settings){
            $seo = [];
            foreach($settings as $field => $value){
                $seo[$field] = strip_tags($value);
            }
        }

        $this->title = @$seo['title'] ? $seo['h1'] : lang('text_heading');
        $this->description = @$seo['description'] ? $seo['description'] : lang('text_meta_description');
        $this->keywords = @$seo['keywords'] ? $seo['keywords'] : lang('text_meta_keywords');

        $data['h1'] = @$seo['h1'] ? $seo['h1'] : lang('text_h1');
        $manufacturers = $this->tecdoc->getManufacturer();
        if ($manufacturers) {
            $data['manufacturers'] = [];
            foreach ($manufacturers as $item) {
                if (file_exists('./uploads/model/' . $item->Name . '.png')) {
                    $data['manufacturers'][] = ['slug' => url_title($item->Name). '_' . $item->
                        ID_mfa, 'ID_mfa' => $item->ID_mfa, 'name' => $item->Name, 'logo' => strlen($item->
                        Logo) > 0 ? $item->Logo : '/uploads/model/' . $item->Name . '.png', ];
                }
            }
        }
        $this->output->cache(131400);
        $this->load->view('header');
        $this->load->view('catalog/index', $data);
        $this->load->view('footer');
    }

    public function model($slug)
    {
        $data = [];
        $data['breadcrumb'][] = ['href' => '/catalog', 'title' => lang('text_index')];
        $ID_mfa = $this->int($slug);

        $models_type = $this->tecdoc->getModel($ID_mfa);
        $manufacturer_info = $this->tecdoc->getManufacturer($ID_mfa);

        $this->title = $manufacturer_info[0]->Name;
        $this->description = $this->title;
        $data['h1'] = $this->title;
    
        $data['breadcrumb'][] = ['href' => $data['breadcrumb'][0]['href'].'/'.url_title($manufacturer_info[0]->Name).'_'.$ID_mfa, 'title' => $this->title];
    
        $data['models_type'] = [];
        foreach ($models_type as $model_type) {
            $data['models_type'][] = ['name' => $model_type->Name, 'date_start' => $model_type->
                DateStart, 'date_end' => $model_type->DateEnd, 'slug' => url_title($model_type->
                Name). '_' . $model_type->ID_mod];
        }
        $this->output->cache(131400);
        $this->load->view('header');
        $this->load->view('catalog/model', $data);
        $this->load->view('footer');
    }

    public function typ($ID_mfa, $ID_mod)
    {
        $ID_mfa = $this->int($ID_mfa);
        $ID_mod = $this->int($ID_mod);

        $data = [];
        $model_info = $this->tecdoc->getModel($ID_mfa, $ID_mod);
        $manufacturer_info = $this->tecdoc->getManufacturer($ID_mfa);
        $data['breadcrumb'][] = ['href' => '/catalog', 'title' => lang('text_index')];
        $data['breadcrumb'][] = ['href' => base_url('catalog') . '/' . url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/', 'title' => $manufacturer_info[0]->Name];
        if($model_info){
            $data['breadcrumb'][] = ['href' => false, 'title' => $model_info[0]->Name];
        }
        

        $models_type = $this->tecdoc->getModel($ID_mfa, $ID_mod);

        $this->title = $manufacturer_info[0]->Name.' '.$models_type[0]->Name;
        $this->description = $this->title;
        $data['h1'] = $this->title;
        
        $typs = $this->tecdoc->getType($ID_mod);

        foreach ($typs as $type) {
            $data['typs'][] = [
            'ID_mod' => $type->ID_mod, 
            'ID_typ' => $type->ID_typ, 
            'Name' => $type->Name, 
            'Engines' => $type->Engines, 
            'CCM' => $type->CCM, 
            'KwHp' => $type->KwHp, 
            'Fuel' => $type->Fuel, 
            'Drive' => $type->Drive, 
            'Doors' => $type->Doors,
            'Trans' => $type->Trans, 
            'Body' => $type->Body, 
            'DateStart' => $type->DateStart,
            'DateEnd' => $type->DateEnd, 
            'Description' => $type->Description, 
            'slug' => url_title($type->Name). '_' . $type->ID_typ];
        }
        $this->output->cache(131400);
        $this->load->view('header');
        $this->load->view('catalog/typ', $data);
        $this->load->view('footer');
    }
    
    public function tree($ID_mfa, $ID_mod, $ID_typ, $ID_tree = 10001){
        $this->load->model('product_model');
        $ID_mfa = $this->int($ID_mfa);
        $ID_mod = $this->int($ID_mod);
        $ID_typ = $this->int($ID_typ);
        if($this->input->get('id_tree')){
            $ID_tree = $this->input->get('id_tree', true);
        }

        $data = [];
        $model_info = $this->tecdoc->getModel($ID_mfa, $ID_mod);
        $manufacturer_info = $this->tecdoc->getManufacturer($ID_mfa);
        $typ_info = $this->tecdoc->getType($ID_mod, $ID_typ);



        $data['breadcrumb'][] = ['href' => '/catalog', 'title' => lang('text_index')];
        $data['breadcrumb'][] = ['href' => base_url('catalog') . '/' . url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/', 'title' => $manufacturer_info[0]->Name];
        $data['breadcrumb'][] = ['href' => base_url('catalog').'/'.url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/'.url_title( $model_info[0]->Name).'_'. $model_info[0]->ID_mod, 'title' => $model_info[0]->Name];

        $this->title = $manufacturer_info[0]->Name.' '.$model_info[0]->Name.' '.$typ_info[0]->Name;
        $this->description = $this->title;
        $data['h1'] = $this->title;

        if($ID_tree != 10001){
            $tree_info = $this->tecdoc->getTreeNode($ID_tree);
            $data['breadcrumb'][] = ['href' => base_url('catalog').'/'.url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/'.url_title( $model_info[0]->Name).'_'. $model_info[0]->ID_mod.'/'.url_title($typ_info[0]->Name).'_'.$typ_info[0]->ID_typ, 'title' => $typ_info[0]->Name];
            $data['breadcrumb'][] = ['href' => false, 'title' => $tree_info[0]->Name];
            $data['h1'] .= ' '.$tree_info[0]->Name;
            $data['parts'] = $this->tecdoc->getParts($ID_typ, $ID_tree);
            foreach($data['parts'] as &$tecdoc_part){
                $tecdoc_part->available = $this->product_model->get_search($tecdoc_part->ID_art, $tecdoc_part->Brand, $tecdoc_part->Search, false, false, false);
            }
        }

        $data['trees'] = $this->tecdoc->getTreeAll($ID_typ);


        $this->load->view('header');
        $this->load->view('catalog/tree', $data);
        $this->load->view('footer');
        
    }


    private function int($string)
    {
        $array = explode('_', $string);
        return end($array);
    }
}
