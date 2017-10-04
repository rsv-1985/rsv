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
    }

    public function index()
    {
        if($this->input->get('id_tree')){
            $settings = $this->settings_model->get_by_key('seo_tecdoc_with_tree');
            $tree_info = $this->tecdoc->getTreeNode($this->input->get('id_tree'));
            $tree_name = $tree_info[0]->Name;

            $settings_tecdoc_tree = $this->settings_model->get_by_key('tecdoc_tree');
            if(isset($settings_tecdoc_tree[$tree_info[0]->ID_tree]) && $settings_tecdoc_tree[$tree_info[0]->ID_tree]['name'] != ''){
                $tree_name = $settings_tecdoc_tree[$tree_info[0]->ID_tree]['name'];
            }

            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = strip_tags(str_replace([
                        '{cat}',
                    ],[
                        $tree_name
                    ], $value));
                }
            }
        }else{
            $settings = $this->settings_model->get_by_key('seo_tecdoc');
            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = strip_tags($value);
                }
            }
        }


        $this->setTitle(@$seo['title'] ? $seo['title'] : lang('text_heading'));
        $this->setDescription(@$seo['description'] ? $seo['description'] : lang('text_meta_description'));
        $this->setKeywords(@$seo['keywords'] ? $seo['keywords'] : lang('text_meta_keywords'));
        $this->setH1(@$seo['h1'] ? $seo['h1'] : lang('text_h1'));
        $this->setSeotext(@$seo['text']);

        if(!$this->config->item('catalog')){
            $manufacturers = $this->tecdoc->getManufacturer();
            if($manufacturers){
                $settings_tecdoc_manufacturer = $this->settings_model->get_by_key('tecdoc_manufacturer');
                $array_manuf = [];
                foreach($manufacturers as $item){
                    if($settings_tecdoc_manufacturer){
                        if(isset($settings_tecdoc_manufacturer[url_title($item->Name)]) && @$settings_tecdoc_manufacturer[url_title($item->Name)]['status']){
                            $array_manuf[] = [
                                'slug' => url_title($item->Name).'_'.$item->ID_mfa,
                                'ID_mfa' => $item->ID_mfa,
                                'name' => $settings_tecdoc_manufacturer[url_title($item->Name)]['name'] ? $settings_tecdoc_manufacturer[url_title($item->Name)]['name'] : $item->Name,
                                'logo' => $settings_tecdoc_manufacturer[url_title($item->Name)]['logo'] ? $settings_tecdoc_manufacturer[url_title($item->Name)]['logo'] : '/uploads/model/'.str_replace('Ë','E',$item->Name).'.png',
                            ];
                        }
                    }else{
                        if(file_exists('./uploads/model/'.str_replace('Ë','E',$item->Name).'.png')){
                            $array_manuf[] = [
                                'slug' => url_title($item->Name).'_'.$item->ID_mfa,
                                'ID_mfa' => $item->ID_mfa,
                                'name' => $item->Name,
                                'logo' => strlen($item->Logo) > 0 ? $item->Logo : '/uploads/model/'.str_replace('Ë','E',$item->Name).'.png',
                            ];
                        }
                    }
                }
                $data['catalog'] = $this->load->view('widget/catalog',['manufacturers' => $array_manuf], true);
            }
        }else{
            $catalog_settings = $this->config->item('catalog');
            if(isset($catalog_settings['views']) && isset($catalog_settings['manufacturers'])){
                $data['catalog'] = $this->load->view($catalog_settings['views'],['manufacturers' => $catalog_settings['manufacturers']],true);
            }
        }
        
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

        if($this->input->get('id_tree')){
            $settings = $this->settings_model->get_by_key('seo_tecdoc_manufacturer_with_tree');
            $tree_info = $this->tecdoc->getTreeNode($this->input->get('id_tree'));
            $tree_name = $tree_info[0]->Name;

            $settings_tecdoc_tree = $this->settings_model->get_by_key('tecdoc_tree');
            if(isset($settings_tecdoc_tree[$tree_info[0]->ID_tree]) && $settings_tecdoc_tree[$tree_info[0]->ID_tree]['name'] != ''){
                $tree_name = $settings_tecdoc_tree[$tree_info[0]->ID_tree]['name'];
            }

            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = strip_tags(str_replace([
                        '{manuf}',
                        '{cat}'
                    ],[
                        $manufacturer_info[0]->Name,
                        $tree_name
                    ], $value));
                }
            }
        }else{
            $settings = $this->settings_model->get_by_key('seo_tecdoc_manufacturer');
            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = strip_tags(str_replace([
                        '{manuf}',
                    ],[
                        $manufacturer_info[0]->Name
                    ], $value));
                }
            }
        }


        $this->setTitle(@$seo['title'] ? $seo['title'] : lang('text_heading'));
        $this->setDescription(@$seo['description'] ? $seo['description'] : lang('text_meta_description'));
        $this->setKeywords(@$seo['keywords'] ? $seo['keywords'] : lang('text_meta_keywords'));
        $this->setH1( @$seo['h1'] ? $seo['h1'] : lang('text_h1'));
        $this->setSeotext(@$seo['text']);

        $data['h1'] = $this->h1;
        $data['breadcrumb'][] = ['href' => $data['breadcrumb'][0]['href'].'/'.url_title($manufacturer_info[0]->Name).'_'.$ID_mfa, 'title' => $manufacturer_info[0]->Name];
    
        $data['models_type'] = [];
        foreach ($models_type as $model_type) {
            $data['models_type'][] = [
                'name' => $model_type->Name,
                'date_start' => $model_type->DateStart,
                'date_end' => $model_type->DateEnd,
                'slug' => url_title($model_type->Name). '_' . $model_type->ID_mod . ($this->input->get('id_tree') ? '?id_tree='.$this->input->get('id_tree') : '')
            ];
        }
        
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

        if($this->input->get('id_tree')){
            $settings = $this->settings_model->get_by_key('seo_tecdoc_model_with_tree');

            $tree_info = $this->tecdoc->getTreeNode($this->input->get('id_tree'));
            $tree_name = $tree_info[0]->Name;

            $settings_tecdoc_tree = $this->settings_model->get_by_key('tecdoc_tree');
            if(isset($settings_tecdoc_tree[$tree_info[0]->ID_tree]) && $settings_tecdoc_tree[$tree_info[0]->ID_tree]['name'] != ''){
                $tree_name = $settings_tecdoc_tree[$tree_info[0]->ID_tree]['name'];
            }

            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = strip_tags(str_replace([
                        '{manuf}',
                        '{model}',
                        '{cat}'
                    ],[
                        $manufacturer_info[0]->Name,
                        $model_info[0]->Name,
                        $tree_name
                    ], $value));
                }
            }
        }else{
            $settings = $this->settings_model->get_by_key('seo_tecdoc_model');

            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = strip_tags(str_replace([
                        '{manuf}',
                        '{model}'
                    ],[
                        $manufacturer_info[0]->Name,
                        $model_info[0]->Name
                    ], $value));
                }
            }
        }



        $models_type = $this->tecdoc->getModel($ID_mfa, $ID_mod);


        $this->setTitle(@$seo['title'] ? $seo['title'] : $manufacturer_info[0]->Name.' '.$models_type[0]->Name);
        $this->setDescription(@$seo['description'] ? $seo['description'] : $this->title);
        $this->setKeywords(@$seo['keywords'] ? $seo['keywords'] : '');
        $this->setH1(@$seo['h1'] ? $seo['h1'] : $this->title);
        $this->setSeotext(@$seo['text']);

        $data['h1'] = $this->h1;

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
            'slug' => url_title($type->Name). '_' . $type->ID_typ . ($this->input->get('id_tree') ? '?id_tree='.$this->input->get('id_tree') : '')
            ];
        }

        $this->load->view('header');
        $this->load->view('catalog/typ', $data);
        $this->load->view('footer');
    }

    public function tree($ID_mfa, $ID_mod, $ID_typ, $ID_tree = 10001){
        $settings_tecdoc_tree = $this->settings_model->get_by_key('tecdoc_tree');

        $this->load->model('product_model');
        $ID_mfa = $this->int($ID_mfa);
        $ID_mod = $this->int($ID_mod);
        $ID_typ = $this->int($ID_typ);

        $data['popular_category'] = [];
        $data['trees'] = [];
        $tecdoc_trees = $this->tecdoc->getTreeAll($ID_typ);
        if($tecdoc_trees){
            if($settings_tecdoc_tree){
                foreach ($tecdoc_trees as $item){
                    //Скрываем скрытые категории
                    if(isset($settings_tecdoc_tree[$item->ID_tree]) && !@$settings_tecdoc_tree[$item->ID_tree]['hide']){
                        $data['trees'][] = [
                            'ID_tree' => $item->ID_tree,
                            'ID_parent' => $item->ID_parent,
                            'Name' => $settings_tecdoc_tree[$item->ID_tree]['name'] ?  $settings_tecdoc_tree[$item->ID_tree]['name'] : $item->Name,
                            'Level' => $item->Level,
                            'Path' => $item->Path,
                            'Childs' => $item->Childs
                        ];
                    }
                    //Отпбираем популярные
                    if(isset($settings_tecdoc_tree[$item->ID_tree]) && @$settings_tecdoc_tree[$item->ID_tree]['express']){
                        $data['popular_category'][] = [
                            'ID_tree' => $item->ID_tree,
                            'name' => $settings_tecdoc_tree[$item->ID_tree]['name'] ?  $settings_tecdoc_tree[$item->ID_tree]['name'] : $item->Name,
                            'image' => $settings_tecdoc_tree[$item->ID_tree]['logo'] ?  $settings_tecdoc_tree[$item->ID_tree]['logo'] : '',
                        ];
                    }
                }
            }else{
                foreach ($tecdoc_trees as $item){
                    $data['trees'][] = [
                        'ID_tree' => $item->ID_tree,
                        'ID_parent' => $item->ID_parent,
                        'Name' => $settings_tecdoc_tree[$item->ID_tree]['name'] ?  $settings_tecdoc_tree[$item->ID_tree]['name'] : $item->Name,
                        'Level' => $item->Level,
                        'Path' => $item->Path,
                        'Childs' => $item->Childs
                    ];
                }
            }
        }

        if($this->input->get('id_tree')){
            $ID_tree = $this->input->get('id_tree', true);
        }

        $data['filters'] = false;

        $model_info = $this->tecdoc->getModel($ID_mfa, $ID_mod);
        $manufacturer_info = $this->tecdoc->getManufacturer($ID_mfa);
        $typ_info = $this->tecdoc->getType($ID_mod, $ID_typ);

        $settings = $this->settings_model->get_by_key('seo_tecdoc_type');

        if($settings){
            $seo = [];
            foreach($settings as $field => $value){
                $seo[$field] = strip_tags(str_replace([
                    '{manuf}',
                    '{model}',
                    '{type}'
                ],[
                    $manufacturer_info[0]->Name,
                    $model_info[0]->Name,
                    $typ_info[0]->Name
                ], $value));
            }
        }

        if($ID_tree == 10001){
            $this->setTitle(@$seo['title'] ? $seo['title'] : $manufacturer_info[0]->Name.' '.$model_info[0]->Name.' '.$typ_info[0]->Name);
            $this->setDescription(@$seo['description'] ? $seo['description'] : $this->title);
            $this->setKeywords(@$seo['keywords'] ? $seo['keywords'] : '');
            $this->setH1( @$seo['h1'] ? $seo['h1'] : $this->title);
            $this->setSeotext(@$seo['text']);
        }

        $data['h1'] = $this->h1;

        $data['breadcrumb'][] = ['href' => '/catalog', 'title' => lang('text_index')];
        $data['breadcrumb'][] = ['href' => base_url('catalog') . '/' . url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/', 'title' => $manufacturer_info[0]->Name];
        $data['breadcrumb'][] = ['href' => base_url('catalog').'/'.url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/'.url_title( $model_info[0]->Name).'_'. $model_info[0]->ID_mod, 'title' => $model_info[0]->Name];
        $data['breadcrumb'][] = ['href' => base_url('catalog').'/'.url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/'.url_title( $model_info[0]->Name).'_'. $model_info[0]->ID_mod.'/'.url_title($typ_info[0]->Name).'_'.$typ_info[0]->ID_typ, 'title' => $typ_info[0]->Name];

        $data['name'] = $manufacturer_info[0]->Name.' '.$model_info[0]->Name.' '.$typ_info[0]->Name;

        if($ID_tree != 10001){
            $tree_info = $this->tecdoc->getTreeNode($ID_tree);
            $tree_name = $tree_info[0]->Name;

            $settings_tecdoc_tree = $this->settings_model->get_by_key('tecdoc_tree');
            if(isset($settings_tecdoc_tree[$tree_info[0]->ID_tree]) && $settings_tecdoc_tree[$tree_info[0]->ID_tree]['name'] != ''){
                $tree_name = $settings_tecdoc_tree[$tree_info[0]->ID_tree]['name'];
            }


            if($this->input->get('add_tree')){
                $category = $this->garage[md5($data['name'])]['category'];
                $category[$ID_tree] = $tree_info[0]->Name;
                $this->garage[md5($data['name'])]['category'] = $category;

                $cookie = array(
                    'name'   => 'garage',
                    'value'  => serialize($this->garage),
                    'expire' => 60*60*24*365*10,
                );

                $this->input->set_cookie($cookie);

                $this->session->set_flashdata('success', 'Категория добавлена в гараж');

                redirect($this->uri->uri_string().'?id_tree='.$ID_tree);
            }

            $data['breadcrumb'][] = ['href' => false, 'title' => $tree_name];
            $settings = $this->settings_model->get_by_key('seo_tecdoc_tree');

            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = strip_tags(str_replace([
                        '{manuf}',
                        '{model}',
                        '{type}',
                        '{tree}'
                    ],[
                        $manufacturer_info[0]->Name,
                        $model_info[0]->Name,
                        $typ_info[0]->Name,
                        $tree_name
                    ], $value));
                }
            }

            $this->setTitle(@$seo['title'] ? $seo['title'] : $manufacturer_info[0]->Name.' '.$model_info[0]->Name.' '.$typ_info[0]->Name.' '.$tree_info[0]->Name);
            $this->setDescription(@$seo['description'] ? $seo['description'] : $this->title);
            $this->setKeywords(@$seo['keywords'] ? $seo['keywords'] : '');
            $this->setH1(@$seo['h1'] ? $seo['h1'] : $this->title);
            $this->setSeotext(@$seo['text']);

            $data['h1'] = $this->h1;

            $data['parts'] = $this->tecdoc->getParts($ID_typ, $ID_tree);
            if($data['parts']){
                foreach($data['parts'] as &$tecdoc_part){
                    $key = md5($tecdoc_part->Brand);
                    $data['filters']['Производитель'][$key] = $tecdoc_part->Brand;
                    $tecdoc_part->filter_key[] = $key;
                    $tecdoc_part->product = $this->product_model->get_search_products($tecdoc_part->Search, $this->product_model->clear_brand($tecdoc_part->Brand));

                    if($tecdoc_part->Info){
                        $info = explode("<br>",$tecdoc_part->Info);
                        if($info){
                            foreach ($info as $inf){
                                $inf = explode(':',$inf);
                                if(@$inf[0] && @$inf[1]){
                                    $key = md5($inf[0].@$inf[1]);
                                    $data['filters'][$inf[0]][$key] = @$inf[1];
                                    $tecdoc_part->filter_key[] = $key;
                                }
                            }
                        }
                    }
                }
            }
        }

        $data['info'] = $typ_info[0];

        if($this->input->get('add_garage')){
            $this->garage[md5($data['name'])] = [
                'name' => $data['name'],
                'href' => $this->uri->uri_string(),
                'category' => []
            ];

            $cookie = array(
                'name'   => 'garage',
                'value'  => serialize($this->garage),
                'expire' => 60*60*24*365*10,
            );

            $this->input->set_cookie($cookie);

            $this->session->set_flashdata('success', 'Автомобиль добавлен в гараж');

            redirect($this->uri->uri_string());
        }


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
