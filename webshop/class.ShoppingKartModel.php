<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// classe pour gérer le caddie et les opérations sur son contenu
// route ensuite vers l'url fournie

// include WebShop model
include_once('class.WebShopModel.php');

if (!function_exists('customRoundComputePrice')){	 
	// customRoundComputePrice
	// pour dépendance. la fonction custom doit être déclarée dans /modules/webshop/custom/lib.webshop_XXX.php
	/**
	 * display desired price
	 *
	 * @param	Float	$price		The unit price
	 * @return	Float	accounting
	 */
	function customRoundComputePrice($price) {
		$price = intVal(($price*20)+0.5)/20;
		return number_format($price, 2, '.', '');
	}
}

if (!function_exists('customRoundDisplayPrice')){	
	// customRoundDisplayPrice
	// pour dépendance. la fonction custom doit être déclarée dans /modules/webshop/custom/lib.webshop_XXX.php
	/**
	 * display desired price
	 *
	 * @param	Float	$price		The unit price
	 * @return	Float	display value
	 */
	function customRoundDisplayPrice($price) {
		$price = intVal(($price*20)+0.5)/20;
		return number_format($price, 2, ',', ' ');
	}
}

class ShoppingKartModel {

	var $s_model = null;

	//constructor
	function ShoppingKartModel () {

		$this->s_model = new WebShopModel();

	}

	// retrieve shopping kart content from order and compare to session kart
	function load ($id_commande=null) {
		if (is_null($id_commande)) {
		// à implémenter
		}
	}

	function update ($props) {
		// à implémenter
	}

	function freeze () {

		$frozen = Array();
		 

		if (!empty($_SESSION['shopping_kart'])) {
	    	    	$cnt = 0;
	    	    	foreach ($_SESSION['shopping_kart'] as $segment => $types) {
	    	    		if ($segment == 0) {
	    	    			// custom kart content
	    	    			foreach ($types as $custom)
	    	    				$frozen[] = $custom;
	    	    		} else {
	    	    			foreach ($types as $type_id => $type) {
							
							
							// promo 
							$promo_price_all  = ''; // promo sur l'ensemble du caddie
							$id_promo  = ''; // id_promo
							if (isset($type['props']["id_promo"]) && $type['props']["id_promo"] != '') $id_promo = $type['props']["id_promo"]; // identifiant de la promo
							if (isset($type['props']["price_promo"]) && $type['props']["price_promo"] != '') $price_promo_all = $type['props']["price_promo"]; // promo sur toute une commande
							
	    					foreach ($type['items'] as $product) {
							foreach ($product['elements'] as $_index => $element) {
								if ($product['props']['measure'] == 'sample') {
									$price_unit = correctCeilDisplay($product['props']['price']/$element['props']['pieces']);
									$price_prod = $price_unit;
								} else {
									//$price_unit = correctCeilDisplay($product['props']['price']);
									//$price_unit = $product['props']['price'];
									//$price_prod = correctRoundDisplay($element['props']['quantity']*$product['props']['price']);

									switch ($product['props']['measure']) {
										case '100g'	:	$price_unit = floor($product['props']['weight'])*$product['props']['price']/100;
													break;
										case 'kg'	:	$price_unit = floor($product['props']['weight'])*$product['props']['price'];
													break;
										case 'ex.'	:	$price_unit = $product['props']['price'];
													break;
										case 'lot'	:	$price_unit = $product['props']['price'];
													break;
										default		:	$price_unit = $product['props']['price'];
													break;
									}
									$price_prod = $element['props']['quantity']*$price_unit;
									//$price_unit = customRoundComputePrice($price_unit);
									//$price_prod = customRoundComputePrice($price_prod);
									$price_unit = number_format($price_unit, 2, '.', '');
									$price_prod = number_format($price_prod, 2, '.', '');


								}
								
								// promo
								
								
								$price_promo = '';
								
								if (isset($product['props']["price_promo"]) && $product['props']["price_promo"] != '') {
									$price_promo = $product['props']["price_promo"];
									$price_prod = $price_prod - ($element['props']['quantity']*$price_promo); 
									$price_prod = number_format($price_prod, 2, '.', '');  
								}
								
								
								$row = Array(	'id'		=> $product['props']['id'],
										'segment'	=> $segment,
										'type'		=> $type_id,
										'ref'		=> $product['props']['ref'],
										'quantity'	=> $element['props']['quantity'],
										'measure'	=> $product['props']['measure'],
										'weight'		=> $product['props']['weight'],
										'unit_price'	=> strval($price_unit),
										'total_price'	=> strval($price_prod),
										'promo_price'		=> $price_promo,
										'promo_price_all'		=> $price_promo_all,
										'id_promo'		=> $id_promo);
								if (!empty($element['variations']))
									$row['variations'] = $element['variations'];
								$frozen[] = $row;
							}
						}
					}
				}
			}
		}

		//viewArray($frozen, 'freeze');
		return (Array) $frozen;
	}


}

?>
