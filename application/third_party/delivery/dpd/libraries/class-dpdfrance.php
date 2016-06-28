<?php
/**
 * DPD France shipping PHP classes
 *
 * @category   DPDFrance
 * @package    DPDFrance_SolutionWebmaster
 * @author     DPD S.A.S. <ensavoirplus.ecommerce@dpd.fr>
 * @copyright  2015 DPD S.A.S., société par actions simplifiée, au capital de 18.500.000 euros, dont le siège social est situé 27 Rue du Colonel Pierre Avia - 75015 PARIS, immatriculée au registre du commerce et des sociétés de Paris sous le numéro 444 420 830 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class DPDFrance
{
	/* Retrieve Pickup points list from an address 
	 * @params	$address, $zipcode, $city
	 * @return	array of relaypoints
	 */
	public static function getPickupPoints($address, $zipcode, $city)
	{
		// Init vars
		$i 					= 0;
		$relais_list 		= array();
		$date 	 			= date('d/m/Y');

		// Webservice call
		$mypudo			= file_get_contents('http://mypudo.pickup-services.com/mypudo/mypudo.asmx/GetPudoList?carrier=EXA&key=deecd7bc81b71fcc0e292b53e826c48f&address='.urlencode($address).'&zipCode='.urlencode($zipcode).'&city='.urlencode($city).'&countrycode=fr&requestID=0&date_from='.urlencode($date).'&max_pudo_number=&max_distance_search=&weight=&category=&holiday_tolerant=');
		$xml 			= new SimpleXMLElement($mypudo);
		$relais_items 	= $xml->PUDO_ITEMS;

		// Loop through each pudo
		foreach ($relais_items->PUDO_ITEM as $item)
		{
			$point = array();
			$opening_day = array();
			$item = (array)$item;
			$point['relay_id']		 = $item['PUDO_ID'];
			$point['shop_name']		 = $item['NAME'];
			$point['address1']		 = $item['ADDRESS1'];
			if (!empty($item['ADDRESS2']))
				$point['address2']	 = $item['ADDRESS2'];
			if (!empty($item['ADDRESS3']))
				$point['address3']	 = $item['ADDRESS3'];
			if (!empty($item['LOCAL_HINT']))
				$point['local_hint'] = $item['LOCAL_HINT'];
			$point['zipcode']	 	 = $item['ZIPCODE'];
			$point['city']			 = $item['CITY'];
			$point['distance']		 = number_format($item['DISTANCE'] / 1000, 2);
			$point['coord_lat']		 = (float)strtr($item['LATITUDE'], ',', '.');
			$point['coord_long']	 = (float)strtr($item['LONGITUDE'], ',', '.');
			
			$days = array(1=>'monday',2=>'tuesday',3=>'wednesday',4=>'thursday',5=>'friday',6=>'saturday',7=>'sunday');
			
			if(count($item['OPENING_HOURS_ITEMS']->OPENING_HOURS_ITEM)>0)
				foreach($item['OPENING_HOURS_ITEMS']->OPENING_HOURS_ITEM as $k => $oh_item)
				{
					$oh_item = (array)$oh_item;
					$opening_day[$days[$oh_item['DAY_ID']]][] = $oh_item['START_TM'].' - '.$oh_item['END_TM'];
				}
			
			if(empty($opening_day['monday'])){$h1 = 'Fermé';}
				else{if(empty($opening_day['monday'][1])){$h1 = $opening_day['monday'][0];}
					else{$h1 = $opening_day['monday'][0].' & '.$opening_day['monday'][1];}}
					
			if(empty($opening_day['tuesday'])){$h2 = 'Fermé';}
				else{if(empty($opening_day['tuesday'][1])){$h2 = $opening_day['tuesday'][0];}
					else{$h2 = $opening_day['tuesday'][0].' & '.$opening_day['tuesday'][1];}}
					
			if(empty($opening_day['wednesday'])){$h3 = 'Fermé';}
				else{if(empty($opening_day['wednesday'][1])){$h3 = $opening_day['wednesday'][0];}
					else{$h3 = $opening_day['wednesday'][0].' & '.$opening_day['wednesday'][1];}}
					
			if(empty($opening_day['thursday'])){$h4 = 'Fermé';}
				else{if(empty($opening_day['thursday'][1])){$h4 = $opening_day['thursday'][0];}
					else{$h4 = $opening_day['thursday'][0].' & '.$opening_day['thursday'][1];}}
					
			if(empty($opening_day['friday'])){$h5 = 'Fermé';}
				else{if(empty($opening_day['friday'][1])){$h5 = $opening_day['friday'][0];}
					else{$h5 = $opening_day['friday'][0].' & '.$opening_day['friday'][1];}}
					
			if(empty($opening_day['saturday'])){$h6 = 'Fermé';}
				else{if(empty($opening_day['saturday'][1])){$h6 = $opening_day['saturday'][0];}
					else{$h6 = $opening_day['saturday'][0].' & '.$opening_day['saturday'][1];}}
					
			if(empty($opening_day['sunday'])){$h7 = 'Fermé';}
				else{if(empty($opening_day['sunday'][1])){$h7 = $opening_day['sunday'][0];}
					else{$h7 = $opening_day['sunday'][0].' & '.$opening_day['sunday'][1];}}
			
			$point['opening_hours'] = array('monday' => $h1, 'tuesday' => $h2, 'wednesday' => $h3, 'thursday' => $h4, 'friday' => $h5, 'saturday' => $h6, 'sunday' => $h7);
			
			if (count($item['HOLIDAY_ITEMS']->HOLIDAY_ITEM) > 0)
				$x = 0;
				foreach ($item['HOLIDAY_ITEMS']->HOLIDAY_ITEM as $holiday_item)
				{
					$holiday_item = (array)$holiday_item;
					$point['closing_period'][$x] = $holiday_item['START_DTM'].' - '.$holiday_item['END_DTM'];
					++$x;
				}
			// Push data in array
			array_push($relais_list, $point);
			// Set number of pudos, max 10
			if (++$i == 5)
				break;
		}

		// Return array of Pickup points
		return $relais_list;
	}
	
	/* Validate French GSM number for Predict service 
	 * @params	input_gsm, iso_code
	 * @return 	bool
	 */
	public static function validatePredictGSM($input_gsm, $iso_code)
	{
		if ($iso_code == 'FR' || $iso_code == 'F')
		{
			if (!empty($input_gsm) && preg_match('/^(\+33|0)[67][0-9]{8}$/', $input_gsm) == 1)
				return true;
		}
		else
			return false;
	}
}

class DPDStation {

    var $line;
    var $contenu_fichier;

    function __construct() {
        $this->line = str_pad("", 1634);
        $this->contenu_fichier = '';
    }

    function add($txt, $position, $length) {
        $txt = $this->stripAccents($txt);
        $this->line = substr_replace($this->line, str_pad($txt, $length), $position, $length);
    }

    function convdate($date1) {
        $d1 = explode("-", $date1);
        $date2 = date("d/m/Y", mktime(0, 0, 0, (int) $d1[1], (int) $d1[2], (int) $d1[0]));
        return $date2;
    }

    function add_line() {
        if ($this->contenu_fichier != '') {
            $this->contenu_fichier = $this->contenu_fichier . "\r\n" . $this->line;
            $this->line = '';
            $this->line = str_pad("", 1634);
        } else {
            $this->contenu_fichier.=$this->line;
            $this->line = '';
            $this->line = str_pad("", 1634);
        }
    }

    function download() {
        while (@ob_end_clean());
        header('Content-type: application/dat');
		header('Content-Disposition: attachment; filename="DPDFRANCE_' . date("dmY-His") . '.dat"');
        echo '$VERSION=110' . "\r\n";
        echo $this->contenu_fichier. "\r\n";
        exit;
    }
	
    function stripAccents($str) {
		$str = preg_replace('/[\x{00C0}\x{00C1}\x{00C2}\x{00C3}\x{00C4}\x{00C5}]/u','A', $str);
		$str = preg_replace('/[\x{0105}\x{0104}\x{00E0}\x{00E1}\x{00E2}\x{00E3}\x{00E4}\x{00E5}]/u','a', $str);
		$str = preg_replace('/[\x{00C7}\x{0106}\x{0108}\x{010A}\x{010C}]/u','C', $str);
		$str = preg_replace('/[\x{00E7}\x{0107}\x{0109}\x{010B}\x{010D}}]/u','c', $str);
		$str = preg_replace('/[\x{010E}\x{0110}]/u','D', $str);
		$str = preg_replace('/[\x{010F}\x{0111}]/u','d', $str);
		$str = preg_replace('/[\x{00C8}\x{00C9}\x{00CA}\x{00CB}\x{0112}\x{0114}\x{0116}\x{0118}\x{011A}]/u','E', $str);
		$str = preg_replace('/[\x{00E8}\x{00E9}\x{00EA}\x{00EB}\x{0113}\x{0115}\x{0117}\x{0119}\x{011B}]/u','e', $str);
		$str = preg_replace('/[\x{00CC}\x{00CD}\x{00CE}\x{00CF}\x{0128}\x{012A}\x{012C}\x{012E}\x{0130}]/u','I', $str);
		$str = preg_replace('/[\x{00EC}\x{00ED}\x{00EE}\x{00EF}\x{0129}\x{012B}\x{012D}\x{012F}\x{0131}]/u','i', $str);
		$str = preg_replace('/[\x{0142}\x{0141}\x{013E}\x{013A}]/u','l', $str);
		$str = preg_replace('/[\x{00F1}\x{0148}]/u','n', $str);
		$str = preg_replace('/[\x{00D2}\x{00D3}\x{00D4}\x{00D5}\x{00D6}\x{00D8}]/u','O', $str);
		$str = preg_replace('/[\x{00F2}\x{00F3}\x{00F4}\x{00F5}\x{00F6}\x{00F8}]/u','o', $str);
		$str = preg_replace('/[\x{0159}\x{0155}]/u','r', $str);
		$str = preg_replace('/[\x{015B}\x{015A}\x{0161}]/u','s', $str);
		$str = preg_replace('/[\x{00DF}]/u','ss', $str);
		$str = preg_replace('/[\x{0165}]/u','t', $str);
		$str = preg_replace('/[\x{00D9}\x{00DA}\x{00DB}\x{00DC}\x{016E}\x{0170}\x{0172}]/u','U', $str);
		$str = preg_replace('/[\x{00F9}\x{00FA}\x{00FB}\x{00FC}\x{016F}\x{0171}\x{0173}]/u','u', $str);
		$str = preg_replace('/[\x{00FD}\x{00FF}]/u','y', $str);
		$str = preg_replace('/[\x{017C}\x{017A}\x{017B}\x{0179}\x{017E}]/u','z', $str);
		$str = preg_replace('/[\x{00C6}]/u','AE', $str);
		$str = preg_replace('/[\x{00E6}]/u','ae', $str);
		$str = preg_replace('/[\x{0152}]/u','OE', $str);
		$str = preg_replace('/[\x{0153}]/u','oe', $str);
		$str = preg_replace('/[\x{0022}\x{0025}\x{0026}\x{0027}\x{00A1}\x{00A2}\x{00A3}\x{00A4}\x{00A5}\x{00A6}\x{00A7}\x{00A8}\x{00AA}\x{00AB}\x{00AC}\x{00AD}\x{00AE}\x{00AF}\x{00B0}\x{00B1}\x{00B2}\x{00B3}\x{00B4}\x{00B5}\x{00B6}\x{00B7}\x{00B8}\x{00BA}\x{00BB}\x{00BC}\x{00BD}\x{00BE}\x{00BF}]/u',' ', $str);
		return $str;
	}
		
	/* Converts ISO country code to DPD Station format */
	public static function convertISOCode($iso_code)
	{
		$iso_in = array("DE", "AD", "AT", "BE", "BA", "BG", "HR", "DK", "ES", "EE", "FI", "FR", "GB", "GR", "GG", "HU", "IM", "IE", "IT", "JE", "LV", "LI", "LT", "LU", "NO", "NL", "PL", "PT", "CZ", "RO", "RS", "SK", "SI", "SE", "CH");
		$iso_out = array("D", "AND", "A", "B", "BA", "BG", "CRO", "DK", "E", "EST", "SF", "F", "GB", "GR", "GG", "H", "IM", "IRL", "I", "JE", "LET", "LIE", "LIT", "L", "N", "NL", "PL", "P", "CZ", "RO", "RS", "SK", "SLO", "S", "CH");

		if (in_array($iso_code, $iso_in))
			$code_pays_dest = str_replace($iso_in, $iso_out, $iso_code);
		else
			$code_pays_dest = str_replace($iso_code, "INT", $iso_code);
	}

	/* Generates an interface file for DPD Station 
	 * @params	array containing orders data see below
	 * @return	.dat file
	 */
	public static function generateInterfaceFile($orders_array)
	{
		// Init file
		$record = new DPDStation();

		// Loop through each order
		foreach ($orders_array as $order_data)
		{
			// Add data to file
			$record->add($order_data['order_reference'], 0, 35);																//	Référence client N°1 - Référence Commande
			$record->add(str_pad(intval($order_data['order_weight'] * 100), 8, '0', STR_PAD_LEFT), 37, 8); 						//	Poids du colis sur 8 caractères
			if ($order_data['order_shipping_service'] == 'relais')
			{
				$record->add($order_data['customer_last_name'], 60, 35);    													//	Nom du destinataire
				$record->add($order_data['customer_first_name'], 95, 35);    													//	Prénom du destinataire
			}
			else
			{
				$record->add($order_data['customer_last_name'].' '.$order_data['customer_first_name'], 60, 35);    				//	Nom et prénom du destinataire
				$record->add($order_data['customer_company'], 95, 35);    														//	Complément d'adresse 1
			}
			$record->add($order_data['customer_address_2'], 130, 140);   														//	Complément d’adresse 2 a 5
			$record->add($order_data['customer_zipcode'], 270, 10);    															//	Code postal
			$record->add($order_data['customer_city'], 280, 35);     															//	Ville
			$record->add($order_data['customer_address_1'], 325, 35);    														//	Rue
			$record->add(DPDStation::convertISOCode($order_data['customer_iso_code']), 370, 3);          						//	Code Pays destinataire
			$record->add($order_data['customer_telephone'], 373, 30);        													//	Téléphone Destinataire
			$record->add($order_data['shipper_name'], 418, 35);        															//	Nom expéditeur
			$record->add($order_data['shipper_address_2'], 453, 35);       														//	Complément d’adresse 1
			$record->add($order_data['shipper_zipcode'], 628, 10);         														//	Code postal
			$record->add($order_data['shipper_city'], 638, 35);        															//	Ville
			$record->add($order_data['shipper_address_1'], 683, 35);       														//	Rue
			$record->add('F', 728, 3);       																					//	Code Pays
			$record->add($order_data['shipper_telephone'], 731, 30);        													//	Tél. Expéditeur
			$record->add(date("d/m/Y"), 901, 10);  																				//	Date d'expédition théorique
			$record->add(str_pad($order_data['shipper_contract_number'], 8, '0', STR_PAD_LEFT), 911, 8); 						//	N° de compte chargeur
			$record->add($order_data['order_reference'], 954, 35);        														//	N° de commande
			if (!empty($order_data['order_insurance_service']))
				$record->add(str_pad(number_format($order_data['order_amount'], 2, '.', ''), 9, '0', STR_PAD_LEFT), 1018, 9); 	//  Montant valeur colis
			$record->add('', 1035, 35);       																					//	Référence client N°2
			$record->add($order_data['shipper_email'], 1116, 80);        														//	E-mail expéditeur
			$record->add($order_data['shipper_mobile'], 1196, 35);        														//	GSM expéditeur
			$record->add($order_data['customer_email'], 1231, 80);      														//	E-mail destinataire
			$record->add($order_data['customer_mobile'], 1311, 35);   															//	GSM destinataire
			if ($order_data['order_shipping_service'] == 'relais' && $order_data['customer_iso_code'] == 'FR')
				$record->add($order_data['customer_pickup_id'], 1442, 8);         												//	Identifiant relais Pickup
			if ($order_data['order_shipping_service'] == 'predict' && $order_data['customer_iso_code'] == 'FR')
				$record->add("+", 1568, 1);																						//	Flag Predict
			$record->add($order_data['customer_last_name'], 1569, 35);    														//	Nom de famille du destinataire
			$record->add_line();
		}
		$record->download();
	}
}
?>
