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
        $settings = $this->settings_model->get_by_key('seo_tecdoc');
        if($settings){
            $seo = [];
            foreach($settings as $field => $value){
                $seo[$field] = strip_tags($value);
            }
        }

        $this->title = @$seo['title'] ? $seo['title'] : lang('text_heading');
        $this->description = @$seo['description'] ? $seo['description'] : lang('text_meta_description');
        $this->keywords = @$seo['keywords'] ? $seo['keywords'] : lang('text_meta_keywords');

        $data['h1'] = @$seo['h1'] ? $seo['h1'] : lang('text_h1');
        $data['text'] = @$seo['text'];
        if(!$this->config->item('catalog')){
            $manufacturers = $this->tecdoc->getManufacturer();
            if($manufacturers){
                $settings_tecdoc_manufacturer = $this->settings_model->get_by_key('tecdoc_manufacturer');
                $array_manuf = [];
                foreach($manufacturers as $item){
                    if($settings_tecdoc_manufacturer){
                        if(isset($settings_tecdoc_manufacturer[$item->ID_mfa])){
                            $array_manuf[] = [
                                'slug' => url_title($item->Name).'_'.$item->ID_mfa,
                                'ID_mfa' => $item->ID_mfa,
                                'name' => $item->Name,
                                'logo' => strlen($item->Logo) > 0 ? $item->Logo : '/uploads/model/'.str_replace('Ë','E',$item->Name).'.png',
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

        $this->title = @$seo['title'] ? $seo['title'] : lang('text_heading');
        $this->description = @$seo['description'] ? $seo['description'] : lang('text_meta_description');
        $this->keywords = @$seo['keywords'] ? $seo['keywords'] : lang('text_meta_keywords');

        $data['h1'] = @$seo['h1'] ? $seo['h1'] : lang('text_h1');
        $data['text'] = @$seo['text'];
        $data['breadcrumb'][] = ['href' => $data['breadcrumb'][0]['href'].'/'.url_title($manufacturer_info[0]->Name).'_'.$ID_mfa, 'title' => $manufacturer_info[0]->Name];
    
        $data['models_type'] = [];
        foreach ($models_type as $model_type) {
            $data['models_type'][] = ['name' => $model_type->Name, 'date_start' => $model_type->
                DateStart, 'date_end' => $model_type->DateEnd, 'slug' => url_title($model_type->
                Name). '_' . $model_type->ID_mod];
        }
        
        if($data['models_type']){
            $this->output->cache(131400);
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

        $models_type = $this->tecdoc->getModel($ID_mfa, $ID_mod);


        $this->title = @$seo['title'] ? $seo['title'] : $manufacturer_info[0]->Name.' '.$models_type[0]->Name;
        $this->description = @$seo['description'] ? $seo['description'] : $this->title;
        $this->keywords = @$seo['keywords'] ? $seo['keywords'] : '';
        $data['h1'] = @$seo['h1'] ? $seo['h1'] : $this->title;
        $data['text'] = @$seo['text'];
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
        if($data['typs']){
            $this->output->cache(131400);
        }

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
        $data['filters'] = false;
        //Популярные категории
        $data['popular_category'] = [
            10233 => ['name' => 'Стеклоочиститель', 'image' => '/uploads/category/ochestitel.png'],
            10151 => ['name' => '', 'image' => '/uploads/category/kpmplekt-sc.png'],
            //10447 => ['name' => '', 'image' => ''],
            //10893 => ['name' => '', 'image' => ''],
            10553 => ['name' => '', 'image' => '/uploads/category/komplekt-remney.png'],
            10531 => ['name' => '', 'image' => '/uploads/category/rqmqn-grm.png'],
            10835 => ['name' => '', 'image' => '/uploads/category/opora.png'],
            10361 => ['name' => 'Топливный фильтр', 'image' => '/uploads/category/toplivnyy-filtr.png'],
            10359 => ['name' => '', 'image' => ''],
            10360 => ['name' => '', 'image' => ''],
            10362 => ['name' => 'Гидравлический фильтр', 'image' => '/uploads/category/gidravlicheskiy-filtr.png'],
            10363 => ['name' => '', 'image' => '/uploads/category/filtr-salona.png'],
            10907 => ['name' => 'Тормозной суппорт', 'image' => '/uploads/category/support.png'],
            10131 => ['name' => 'Тормозные колодки', 'image' => '/uploads/category/kolodki-barabannye.png'],
            10735 => ['name' => '', 'image' => '/uploads/category/rychagi.png'],
            10132 => ['name' => 'Тормозные диски', 'image' => '/uploads/category/tormoznye-diski.png'],
            10130 => ['name' => 'Тормозные колодки дисковые', 'image' => '/uploads/category/tormoznye-kolodki.png'],
            10195 => ['name' => 'Термостат', 'image' => '/uploads/category/termostat.png'],
            10204 => ['name' => 'Радиатор печки', 'image' => '/uploads/category/radiator-pechki.png'],
            10203 => ['name' => 'Радиатор', 'image' => '/uploads/category/radiator.png'],
            10191 => ['name' => 'Водяной насос', 'image' => '/uploads/category/vodyanoy-nasos.png'],
            10251 => ['name' => '', 'image' => '/uploads/category/svecha-zajiganiya.png'],
            10250 => ['name' => '', 'image' => '/uploads/category/katushka.png'],
            10253 => ['name' => 'Провода высоковольтные', 'image' => '/uploads/category/provoda.png'],
            10459 => ['name' => '', 'image' => '/uploads/category/starter.png'],
            10450 => ['name' => 'Генератор', 'image' => '/uploads/category/generator.png'],
            10221 => ['name' => '', 'image' => '/uploads/category/amortizator.png'],
            10213 => ['name' => '', 'image' => '/uploads/category/podveska.png'],
            10298 => ['name' => 'Шаровая опора', 'image' => '/uploads/category/sharnir.png'],
            10174 => ['name' => 'Пыльник', 'image' => '/uploads/category/pylnik.png'],
            10171 => ['name' => 'Шарнирный комплект', 'image' => '/uploads/category/sharnirnyy-komplekt.png'],
            10324 => ['name' => '', 'image' => '/uploads/category/komplekt-prokladok-dvigatelya.png'],
            10360 => ['name' => '', 'image' => '/uploads/category/vozdushny-filtr.png'],
            10359 => ['name' => 'Фильтр масла', 'image' => '/uploads/category/maslyannyy-filtr.png']
        ];


        $model_info = $this->cache->file->get('model_info_id_mfa_'.$ID_mfa.'_id_mod_'.$ID_mod);

        if(!$model_info){
            $model_info = $this->tecdoc->getModel($ID_mfa, $ID_mod);
            if($model_info){
                $this->cache->file->save('model_info_id_mfa_'.$ID_mfa.'_id_mod_'.$ID_mod,$model_info,604800);
            }
        }

        $manufacturer_info = $this->cache->file->get('manufacturer_info_id_mfa_'.$ID_mfa);
        if(!$manufacturer_info){
            $manufacturer_info = $this->tecdoc->getManufacturer($ID_mfa);
            if($manufacturer_info){
                $this->cache->file->save('manufacturer_info_id_mfa_'.$ID_mfa,$manufacturer_info,604800);
            }
        }
        $typ_info = $this->cache->file->get('typ_info_id_mod_'.$ID_mod.'_id_typ_'.$ID_typ);
        if(!$typ_info){
            $typ_info = $this->tecdoc->getType($ID_mod, $ID_typ);
            if($typ_info){
                $this->cache->file->save('typ_info_id_mod_'.$ID_mod.'_id_typ_'.$ID_typ,$typ_info,604800);
            }
        }

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

        $this->title = @$seo['title'] ? $seo['title'] : $manufacturer_info[0]->Name.' '.$model_info[0]->Name.' '.$typ_info[0]->Name;
        $this->description = @$seo['description'] ? $seo['description'] : $this->title;
        $this->keywords = @$seo['keywords'] ? $seo['keywords'] : '';
        $data['h1'] = @$seo['h1'] ? $seo['h1'] : $this->title;
        $data['text'] = @$seo['text'];


        $data['breadcrumb'][] = ['href' => '/catalog', 'title' => lang('text_index')];
        $data['breadcrumb'][] = ['href' => base_url('catalog') . '/' . url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/', 'title' => $manufacturer_info[0]->Name];
        $data['breadcrumb'][] = ['href' => base_url('catalog').'/'.url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/'.url_title( $model_info[0]->Name).'_'. $model_info[0]->ID_mod, 'title' => $model_info[0]->Name];
        $data['breadcrumb'][] = ['href' => base_url('catalog').'/'.url_title($manufacturer_info[0]->Name) . '_' . $ID_mfa . '/'.url_title( $model_info[0]->Name).'_'. $model_info[0]->ID_mod.'/'.url_title($typ_info[0]->Name).'_'.$typ_info[0]->ID_typ, 'title' => $typ_info[0]->Name];

        $data['name'] = $manufacturer_info[0]->Name.' '.$model_info[0]->Name.' '.$typ_info[0]->Name;

        if($ID_tree != 10001){
            $tree_info = $this->cache->file->get('tree_info_id_tree_'.$ID_tree);
            if(!$tree_info){
                $tree_info = $this->tecdoc->getTreeNode($ID_tree);
                if($tree_info){
                    $this->cache->file->save('tree_info_id_tree_'.$ID_tree,$tree_info,604800);
                }
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

            $data['breadcrumb'][] = ['href' => false, 'title' => $tree_info[0]->Name];
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
                        $tree_info[0]->Name
                    ], $value));
                }
            }

            $this->title = @$seo['title'] ? $seo['title'] : $manufacturer_info[0]->Name.' '.$model_info[0]->Name.' '.$typ_info[0]->Name.' '.$tree_info[0]->Name;
            $this->description = @$seo['description'] ? $seo['description'] : $this->title;
            $this->keywords = @$seo['keywords'] ? $seo['keywords'] : '';

            $data['h1'] = @$seo['h1'] ? $seo['h1'] : $this->title;
            $data['text'] = @$seo['text'];
            $data['parts'] = $this->tecdoc->getParts($ID_typ, $ID_tree);

            if($data['parts']){
                foreach($data['parts'] as &$tecdoc_part){
                    $key = md5($tecdoc_part->Brand);
                    $data['filters']['Производитель'][$key] = $tecdoc_part->Brand;
                    $tecdoc_part->filter_key[] = $key;

                    $tecdoc_part->product = $this->product_model->get_search_products($tecdoc_part->Search, $tecdoc_part->Brand);

                    if($tecdoc_part->Info){
                        $info = explode("</br>",$tecdoc_part->Info);
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

        $data['trees'] = $this->tecdoc->getTreeAll($ID_typ);
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
