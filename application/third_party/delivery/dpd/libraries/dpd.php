<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('class-dpdfrance.php');
class Dpd{
    public $CI;
    public $delivery_price;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function get_form(){
        $data = [];
        $data['response'] = false;
        $html  = 'По Вашему запросу нет отделений';
        if(isset($_POST['additional']['dpd'])){
            //$relais_list = DPDFrance::getPickupPoints('27 Rue du colonel pierre avia', '75015', 'Paris');
            $relais_list = DPDFrance::getPickupPoints($_POST['additional']['dpd']['address'], $_POST['additional']['dpd']['zip'], $_POST['additional']['dpd']['city']);
            if($relais_list){
                $html = '<div>';
                /* Write each Pickup point */
                foreach ($relais_list as $offset => $pointRelais)
                {
                    $html .= '
                    <label class="item-dpd">
                        <div class="lignepr" for="lignepr'.$offset.'">
                            <div class="dpdrelais_info" id="dpdrelais'.$offset.'_info"><strong>' . $pointRelais['shop_name'] . '</strong><br/>' . $pointRelais['address1'] . ' <br/> ' . $pointRelais['zipcode'] . ' ' . $pointRelais['city'] . '</div>
                            <div class="dpdrelais_radio"><input id="lignepr'.$offset.'" type="radio" name="dpdfrance_pickup" value="'.$pointRelais['shop_name'].' '.$pointRelais['address1'].' '.$pointRelais['zipcode'].' '.$pointRelais['city'].'"></input></div>
                            <div class="dpdrelais_popup"><a href="http://www.dpd.fr/dpdrelais/id_'.$pointRelais['relay_id'].'" target="_blank">Plus de détails</a></div>
                            <div class="dpdrelais_distance">' . $pointRelais['distance'] . ' km  <br/> ID: ' . $pointRelais['relay_id'] . '</div>
                        </div>
                    </label>
                ';
                }

                $html .= '</div>';
            }

            $data['response'] =  $html;
        }
        return $data;
    }

    public function get_comment(){
        $additiona_comment = '';
        if(isset($_POST['dpdfrance_pickup'])){
            $additiona_comment = $_POST['dpdfrance_pickup'];
        }
        return $additiona_comment;
    }
}