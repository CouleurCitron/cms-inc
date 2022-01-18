<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour gérer les données commande

// structures des données de formulaires
// voici comment nommer les champs du formulaire de soumission de commande

// - customer_id		ID client
// - total_no_tax	total HT
// - total_tax		montant de la TVA
// - total_inc_tax	total TTC
// - shipping_id		ID transporteur
// - shipping_cost	cout du transport
// - country_id		ID pays
// - total_pay		total payé (incl.transport)
// - address_pay		adresse de facturation
// - address_ship	adresse d'expédition
// - comment		commentaire du client


class OrderModel {

	// constructor
	function OrderModel () {}


	// prepare
	/**
	 * Record customer order in database
	 *
	 * @param	Array		$structure		The shopping kart structure
	 * @return	Int	new order record ID
	 */
	function record ($structure) {

		$_SESSION['order']['payment_type'] = $_POST['pay_mode']; 
		if (empty($_SESSION['order']['id'])) $order = new shp_commande();
		else $order = new shp_commande($_SESSION['order']['id']);
		if (isset($_POST['id_statut'])) $order->set_id_statut($_POST['id_statut']);
		else  $order->set_id_statut(OR_PAY_STD);
		$order->set_id_client($_POST['customer_id']);
		$order->set_id_pays($_POST['country_id']);
		if ($_POST['shipping_id'] == '')
			$order->set_id_transporteur(-1);
		else	$order->set_id_transporteur($_POST['shipping_id']);
		$order->set_reference('TEMP_'.date('Ymd-His').'_'.rand(0,1000));
		$order->set_mode_paiement($_POST['pay_mode']);
		$order->set_total_ht($_POST['total_no_tax']);
		$order->set_tva($_POST['total_tax']);
		$order->set_total_ttc($_POST['total_inc_tax']);
		if (!isset($_POST['shipping_cost']) || $_POST['shipping_cost'] == '')
			$order->set_port('NULL');
		else	$order->set_port($_POST['shipping_cost']);
		if ($_POST['total_pay'] == 'NC')
			$order->set_total_pay(-1);
		else	$order->set_total_pay($_POST['total_pay']);
		$order->set_pay_adresse($_POST['address_pay']);
		$order->set_exp_adresse($_POST['address_ship']);
		$order->set_structure(serialize($structure));
		$order->set_message($_POST['comment']);
		$order->set_date_commande(date('Y-m-d H:i:s'));
		$order->set_cdate(date('Y-m-d H:i:s'));

		$o_id = dbSauve($order);

		// free shopping kart for security and order ubiquity reasons ?
		//unset($_SESSION['shopping_kart']);
		if ($o_id > 0)
			return $o_id;
		else	die ('Error while recording order');

	}


	// prepare
	/**
	 * Terminal returned first visual online confirmation for customer
	 *
	 * @param	Int		$id_order		The order record ID
	 * @param	Int		$id_request		A transaction ID for the payment procedure
	 * @return	Void
	 */
	function prepare ($id_order, $id_request) {
		$order = new shp_commande($id_order);
		$current = $order->get_infos_tpi();
		if ($current != '') {
			$props = explode('|', $current);
			$cnt = $props[0];
		} else	$cnt = 0;
		$order->set_infos_tpi(++$cnt.'|'.$id_request);
		dbUpdate($order);
	}


	// confirm
	/**
	 * Terminal returned first visual online confirmation for customer
	 *
	 * @param	Int		$id_order		The order record ID
	 * @return	Void
	 */
	function confirm ($id_order) {
		$order = new shp_commande($id_order);
		if ($order->get_id_statut() == OR_PAY_STD) {
			// update status only if IPN wasn't recieved before success call
			$order->set_id_statut(OR_PAY_RET);
			dbUpdate($order);
		}
		//viewArray($_SESSION);

		logCheckFileExists($_SERVER['DOCUMENT_ROOT'].'/log/payment.log');
		$f = fopen($_SERVER['DOCUMENT_ROOT'].'/log/payment.log', 'a+');
		fwrite($f, "\n".'['.date('d/m/Y H:i:s').'] - Commande : '.$id_order.' - Paiement '.$order->get_mode_paiement().' : retour <success>');
		fclose($f);

	}


	// cancel
	/**
	 * Terminal returned cancel status
	 *
	 * @param	Int		$id_order		The order record ID
	 * @return	Void
	 */
	function cancel ($id_order) {
		$order = new shp_commande($id_order);
		$order->set_id_statut(OR_PAY_FAI);
		dbUpdate($order);

		logCheckFileExists($_SERVER['DOCUMENT_ROOT'].'/log/payment.log');
		$f = fopen($_SERVER['DOCUMENT_ROOT'].'/log/payment.log', 'a+');
		fwrite($f, "\n".'['.date('d/m/Y H:i:s').'] - Commande : '.$id_order.' - Paiement '.$order->get_mode_paiement().' : retour <cancel>');
		fclose($f);

	}


	// validate
	/**
	 * Terminal returned real payment confirmation : order IS PAID OK or NOT OK
	 *
	 * @param	Array		$ipn		The payment confirmation info ('id_order', 'error', and 'track')
	 * @return	Objet		$order 		L'order updaté de sa validation
	 */
	function validate ($ipn) {

		$order = new shp_commande($ipn['id_order']);
		if ($ipn['success']) {
			$order->set_id_statut(OR_PAY_OK);
			if (defined('WEBSHOP_ORDER_PREFIX') && WEBSHOP_ORDER_PREFIX != '')
				$prefix = WEBSHOP_ORDER_PREFIX;
			else	$prefix = 'ETPI';
			$order->set_reference($prefix.'_'.date('Ymd-His').'_'.rand(0,1000));
			$order->set_date_paiement(date('Y-m-d H:i:s'));
		} else	$order->set_id_statut(OR_PAY_FAI);
		$order->set_infos_tpi(serialize($ipn['track']));
		dbUpdate($order);

		logCheckFileExists($_SERVER['DOCUMENT_ROOT'].'/log/payment.log');
		$f = fopen($_SERVER['DOCUMENT_ROOT'].'/log/payment.log', 'a+');
		fwrite($f, "\n".'['.date('d/m/Y H:i:s').'] - Commande : '.$ipn['id_order'].' - Montant '.$order->get_total_pay().' - Paiement '.$order->get_mode_paiement().' : '.($ipn['success'] ? 'IPN OK' : ($ipn['error'] != '' ? $ipn['error'] : 'IPN response '.$ipn['track']['response_code'])));
		fclose($f);
		
		return $order;

	}


///////////// helpers functions ////////////


	// makeRandomKey
	/**
	 * Generate a random transaction_id, password or validation key
	 *
	 * Examples :
	 * echo makeRandomKey(); 
	 * > 51d8b448ad289a8b2ff50219ddd8e67936f4a555
	 * echo makeRandomKey('numeric', 80); 
	 * > 13969129691829473350905578362711065674284852774190392980483833740698116793831161
	 *
	 * @param	Int		$type		The desired output type (basic, alpha, numeric, nozero, md5 or sha1)
	 * @param	Bool		$len		The desired output length
	 * @return	String		The random chain
	 */
	function makeRandomKey ($type='sha1', $len=20) {
		if (phpversion() >= 4.2)
			mt_srand();
		else	mt_srand(hexdec(substr(md5(microtime()), - $len)) & 0x7fffffff);

		switch ($type) {
			case 'basic':	return mt_rand();
					break;
			case 'alpha':
			case 'numeric':
			case 'nozero':	switch ($type) {
						case 'alpha':	$param = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
								break;
						case 'numeric':	$param = '0123456789';
								break;
						case 'nozero':	$param = '123456789';
								break;
					}
					$str = '';
					for ($i = 0; $i < $len; $i ++)
						$str .= substr($param, mt_rand(0, strlen($param) - 1), 1);
					return $str;
					break;
			case 'md5':	return md5(uniqid(mt_rand(), TRUE));
					break;
			case 'sha1':	return sha1(uniqid(mt_rand(), TRUE));
					break;
		}
	}


	// getShippingRates
	/**
	 * Choose the correct available shipping methods and prices according to weight and destination
	 *
	 * @param	Number		$poids		The wheight to ship
	 * @param	Int		$id_pays		The destination country
	 * @param	String		$cp		The destination zipcode
	 * @return	Array		The chosen shipping info
	 */
	function getShippingRates ($poids, $id_pays, $cp) {
		global $db;
		$shippings = Array();

		$sql = "	SELECT	t.shp_tsp_id as id,
				t.shp_tsp_libelle as name,
				t.shp_tsp_delai as delay,
				g.shp_fpg_id as grid,
				g.shp_fpg_type as m_type,
				g.shp_fpg_match as m_value,
				g.shp_fpg_unite_poids as unit,
				g.shp_fpg_zone as zone,
				v.shp_fpv_minimum as min,
				v.shp_fpv_maximum as max,
				v.shp_fpv_coef_poids as coef,
				v.shp_fpv_valeur as price
			FROM	`shp_transporteur` t,
				`shp_frais_port_grille` g,
				`shp_frais_port_valeur` v
			WHERE	g.shp_fpg_id_transporteur = t.shp_tsp_id
			AND	v.shp_fpv_id_grille = g.shp_fpg_id
			AND	(v.shp_fpv_minimum < {$poids} OR v.shp_fpv_minimum = {$poids})
			AND	v.shp_fpv_maximum > {$poids}
			AND	g.shp_fpg_id_pays = {$id_pays}
			ORDER BY t.shp_tsp_ordre ASC;";

		//echo $sql."<br/>";
		$rs = $db->Execute($sql);
		$pile = Array();
		if ($rs) {
			$track_type = null;
			$track_unit = null;
			while(!$rs->EOF) {
				$pile[] = $rs->fields;
				$rs->MoveNext();
			}
		}
		//viewArray($pile, 'rates');
		// first try defined values
		foreach ($pile as $row) {
			if ($row['zone'] <> '') {
				$zones = explode(',', $row['zone']);
				//viewArray($zones, 'zones for : '.$cp);
				foreach ($zones as $zone) {
					//echo 'test zone '.$zone.' for : '.$cp.'<br/>';
					if ($this->compareZipcode($cp, trim($zone), $row['m_type'], $row['m_value'])) {
						//viewArray($row, 'defined row for : '.$cp);
						if ($row['coef'] ==  'Y')
							$row['price'] = correctRoundDisplay($row['price']*round($poids/100, 2));
						$shippings[] = Array(	'id'	=> $row['id'],
									'name'	=> $row['name'],
									'delay'	=> $row['delay'],
									'price'	=> $row['price']);
					}
				}
			}
		}
		// then try open values
		foreach ($pile as $row) {
			if ($row['zone'] == '') {
				//viewArray($row, 'open row for : '.$cp);
				if ($row['coef'] ==  'Y')
					$row['price'] = correctRoundDisplay($row['price']*round($poids/100, 2));
				$shippings[] = Array(	'id'	=> $row['id'],
							'name'	=> $row['name'],
							'delay'	=> $row['delay'],
							'price'	=> $row['price']);
			}
		}
		if (count($shippings) > 0)
			return $shippings;
		// finally no rate was found
		return null;
	}




	
	
	// getShippingType
	/**
	 * Select all the correct shipping method according to weight and destination
	 *
	 * @param	Number		$poids		The wheight to ship
	 * @param	Int		$id_pays		The destination country
	 * @param	String		$cp		The destination zipcode
	 * @return	Array		The chosen shipping info
	 */
	function getShippingType ($poids, $id_pays, $cp) {
	 

		$sql = "	SELECT	* 
			FROM	`shp_transporteur` t,
				`shp_frais_port_grille` g,
				`shp_frais_port_valeur` v
			WHERE	g.shp_fpg_id_transporteur = t.shp_tsp_id
			AND	v.shp_fpv_id_grille = g.shp_fpg_id";
			if ($poids > 0) $sql.= " AND	(v.shp_fpv_minimum < {$poids} OR v.shp_fpv_minimum = {$poids})
			AND	v.shp_fpv_maximum > {$poids}";
			$sql.= " AND	g.shp_fpg_id_pays = {$id_pays}
			ORDER BY t.shp_tsp_ordre ASC;";
		
		//echo $sql;
		$aTransporteur = dbGetObjectsFromRequete('shp_transporteur', $sql);
		if (newSizeOf($aTransporteur) > 0) { 
			return ($aTransporteur); 
		}
		 
		// finally no rate was found
		return null;
	}
	
	
	function getShippingRateByMethod ($poids, $id_pays, $cp, $id_method) {
		global $db;

		$sql = "	SELECT	t.shp_tsp_id as id,
				t.shp_tsp_libelle as name,
				t.shp_tsp_delai as delay,
				g.shp_fpg_id as grid,
				g.shp_fpg_type as m_type,
				g.shp_fpg_match as m_value,
				g.shp_fpg_unite_poids as unit,
				g.shp_fpg_zone as zone,
				v.shp_fpv_minimum as min,
				v.shp_fpv_maximum as max,
				v.shp_fpv_coef_poids as coef,
				v.shp_fpv_valeur as price
			FROM	`shp_transporteur` t,
				`shp_frais_port_grille` g,
				`shp_frais_port_valeur` v
			WHERE	g.shp_fpg_id_transporteur = t.shp_tsp_id
			AND	v.shp_fpv_id_grille = g.shp_fpg_id";
			if ($poids > 0) $sql.= " AND	(v.shp_fpv_minimum < {$poids} OR v.shp_fpv_minimum = {$poids})
			AND	v.shp_fpv_maximum > {$poids}";
			$sql.= " AND	g.shp_fpg_id_pays = {$id_pays} 
			AND t.shp_tsp_id = {$id_method} 
			ORDER BY t.shp_tsp_ordre ASC; ";

		//echo $sql."<br/>";
		$rs = $db->Execute($sql);
		$pile = Array();
		if ($rs) {
			$track_type = null;
			$track_unit = null;
			while(!$rs->EOF) {
				$pile[] = $rs->fields;
				$rs->MoveNext();
			}
		}
		//viewArray($pile, 'rates');
		// first try defined values
		foreach ($pile as $row) {
			if ($row['zone'] <> '') {
				$zones = explode(',', $row['zone']);
				//viewArray($zones, 'zones for : '.$cp);
				foreach ($zones as $zone) {
					//echo 'test zone '.$zone.' for : '.$cp.'<br/>';
					if ($this->compareZipcode($cp, trim($zone), $row['m_type'], $row['m_value'])) {
						//viewArray($row, 'defined row for : '.$cp);
						if ($row['coef'] ==  'Y')
							$row['price'] = correctRoundDisplay($row['price']*round($poids/100, 2));
						return Array(	'id'	=> $row['id'],
								'name'	=> $row['name'],
								'delay'	=> $row['delay'],
								'price'	=> $row['price']);
					}
				}
			}
		}
		// then try open values
		foreach ($pile as $row) {
			if ($row['zone'] == '') {
				//viewArray($row, 'open row for : '.$cp);
				if ($row['coef'] ==  'Y')
					$row['price'] = correctRoundDisplay($row['price']*round($poids/100, 2));
				return Array(	'id'	=> $row['id'],
						'name'	=> $row['name'],
						'delay'	=> $row['delay'],
						'price'	=> $row['price']);
			}
		}
		// finally no rate was found
		return null;
	}
	// compareZipcode
	/**
	 * Compare to select correct shipping option from shipping grid
	 *
	 * @param	String		$cp		The destination zipcode
	 * @param	String		$zone		The grid value to test
	 * @param	String		$type		The comparison type
	 * @param	String		$match		The comparison string length
	 * @return	Bool		Wether or not the reference zone matches the destination zipcode
	 */

	function compareZipcode ($cp, $zone, $type, $match) {
		if (strpos($zone, '-') === false) {
			// value
			if ($match == 'PREFIX') {
				$maxlength = strlen($zone);
				if ($type == 'NUM')  
					if ( $maxlength < 2) $maxlength = 2;
				$cp = substr($cp, 0, $maxlength);
			}
			if ($zone == $cp) {
				//echo 'value match '.$zone.' : '.$cp.'<br/>';
				return (Bool) true;
			}
		} else {
			// range
			$range = explode('-', $zone);
			if ($match == 'PREFIX')
				$cp = substr($cp, 0, strlen($range[0]));
			//viewArray($range, 'range for '.$cp);
			if (intval($range[0]) <= intval($cp) && intval($range[1]) >= intval($cp)) {
				//echo 'range match '.$zone.' : '.$cp.'<br/>';
				return (Bool) true;
			}
		}
		return (Bool) false;
	}


	// updateStock
	/**
	 * deduce order products from stock quantities
	 *
	 * @param	Object		$order		A valid order
	 * @return	Void
	 * @todo		add extra low stock security alert routine
	 */
	function updateStock ( $order ) {
		
		if ($order->get_id_statut() >= OR_PAY_OK) {
			$content = unserialize($order->get_structure());
			foreach ($content as $product) {
				if (!isset($product['name'])) {
					$oPdt = new shp_produit ($product['id']);
					$oPdt->set_quantite_stock( $oPdt->get_quantite_stock() - $product['quantity'] );
					
					// add extra low stock security alert routine
					if( WEBSHOP_STOCK_ALERT && ($oPdt->get_alerte_stock() > ($oPdt->get_quantite_stock() - $product['quantity'])) ){
						// translation engine
						$translator =& TslManager::getInstance();
						if (isset($product['props']['id'])) {
							$p_ident = 'ID '.$product['props']['id'].' - '.$translator->getByID($properties[$product['props']['id']]['shp_pdt_titre_court']).' - '.$properties[$product['props']['id']]['shp_pdt_dimensions'];
							
							// service confirmation email
							$message = "Stock restant pour le produit {$p_ident} : ".$properties[$product['props']['id']]['shp_pdt_quantite_stock'];
						}
						else {
							$p_ident = 'ID '.$product['id'].' - '.$product['ref'].''; 
							$message = "Stock restant pour le produit {$p_ident} : ".$oPdt->get_quantite_stock();
						}
						 
						multiPartMail(SHP_ADMIN_ORDER_EMAIL , 'Alerte stock sur webshop' , $message , '', SHP_AUTO_EMAIL);
					}				
					
					dbUpdate($oPdt);
				}	
			}

			
		}
	}

}

?>
