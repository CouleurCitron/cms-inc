<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle shopping kart content rendering through chosen rendering

// include WebShop model
include_once('class.WebShopModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class ShoppingKartController extends BaseModuleController {

	var $mod_name = 'webshop';


	// constructor
	function ShoppingKartController () {

		$this->models['shop'] = new WebShopModel();

	}


	function build($view) {

		if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$view.'.php')) {
			include_once('modules/webshop/custom/class.'.$view.'.php');
			$this->view = new $view($this);
			$this->view->render();
		} else	echo "ShoppingKartController.build > Incorrect View given for display : /modules/webshop/custom/class.".$view.".php<br/>";
	}


	function getProductListProps ($target='products') {
		if (!empty($_SESSION['shopping_kart'])) {
			$prod_id_pile = Array();
			$coll_id_pile = Array();
			foreach ($_SESSION['shopping_kart'] as $segment => $types) {
	    			foreach ($types as $type_id => $type) {
	    				foreach ($type['items'] as $product)
	    					if ($segment == WEBSHOP_COLL_SEGMENT_ID && $type_id == WEBSHOP_COLL_TYPE_ID)
	    						$coll_id_pile[] = $product['props']['id'];
	    					else	$prod_id_pile[] = $product['props']['id'];
	    			}
	    	    	}
	    	}
	    	if ($target == 'products' && !empty($prod_id_pile))
	    		$properties = $this->models['shop']->getProductsProps($prod_id_pile);
	    	elseif ($target == 'collections' && !empty($coll_id_pile) && function_exists('getCollectionsProps'))
	    		// FROM CUSTOM WEBSHOP LIBRARY
	    		$properties = getCollectionsProps($coll_id_pile);
	    	else	return Array();
	    		
	    	foreach ($_SESSION['shopping_kart'] as $segment => $types) {
	    		foreach ($types as $type_id => $type) {
	    			foreach ($type['items'] as $index => $product) {					
						$reference = explode('|', $product['props']['ref']);
						$gamme = explode('_', $reference[0]);
						$kart_elem = $_SESSION['shopping_kart'][$segment][$type_id]['items'][$index];
						$prop_elem = $properties[$product['props']['id']];
						// freeze prices and such
						if ($target == 'collections' && $gamme[0] == 'COLLECTION') {
							// FROM CUSTOM WEBSHOP LIBRARY
							// we want to store prices and such props for custom shopping kart items
							if (empty($kart_elem['props']['price']))
								$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['props']['price'] = floatval($prop_elem['rau_col_prix']);
							// No samples for COLLECTIONS
							foreach ($product['elements'] as $_index => $element) {
								if (empty($kart_elem['props']['weight']))
									$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['props']['weight'] = 21;
								if (empty($kart_elem['props']['ship_weight']))
									$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['props']['ship_weight'] = 21;
								if (empty($kart_elem['elements'][$_index]['props']['pieces']))
									$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['elements'][$_index]['props']['pieces'] = 42;
							}
						} elseif ($target == 'products') {										
							$prop_elem['props']['price'] = floatval($prop_elem['props']['price']);
							if (empty($kart_elem['props']['weight']))
								$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['props']['weight'] = $prop_elem['shp_pdt_poids_unite'];
							if (empty($kart_elem['props']['ship_weight']))
								$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['props']['ship_weight'] = $prop_elem['shp_pdt_poids_brut'];
							if ($product['measure'] == 'sample') {
								if (empty($kart_elem['props']['price'])) {
									$price_unit = correctCeilDisplay($prop_elem['price']/$prop_elem['shp_pdt_pieces_unite']);
									$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['props']['price'] = $price_unit;
								}
								foreach ($product['elements'] as $_index => $element) {
									if (empty($kart_elem['elements'][$_index]['props']['pieces']))
										$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['elements'][$_index]['props']['pieces'] = 1;
								}
							} else{
								if (empty($kart_elem['props']['price']))
									$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['props']['price'] = $prop_elem['price'];
								foreach ($product['elements'] as $_index => $element) {
									if (empty($kart_elem['elements'][$_index]['props']['pieces']))
										$_SESSION['shopping_kart'][$segment][$type_id]['items'][$index]['elements'][$_index]['props']['pieces'] = friendlyRoundDisplay($prop_elem['shp_pdt_pieces_unite']);
								}
							}
						}
					}
				}
			}
	    	return (Array) $properties;

	}


	function getProductsProps($ids) {

		$properties = $this->models['shop']->getProductsProps($ids);
	}


}

?>
