<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle shopping kart content rendering through chosen rendering

// include WebShop model
include_once('class.WebShopModel.php');
// include Customer model
include_once('class.CustomerModel.php');
// include Order model
include_once('class.OrderModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class OrderConfirmController extends BaseModuleController {

	var $mod_name = 'webshop';


	// constructor
	function OrderConfirmController () {

		$this->models['shop'] = new WebShopModel();
		$this->models['customer'] = new CustomerModel();
		$this->models['order'] = new OrderModel();

	}
	
	function build ($view) {
		$params = Array(); 
		if ((strpos($_SERVER['REQUEST_URI'], "/content/") === false ) && (strpos($_SERVER['PHP_SELF'], "/content/") === false ) && ($_SERVER['REQUEST_URI']!='/')) {} else {
			// check customer account update
			//$this->models['customer']->update();
			
			if (!defined('ACCOUNT_EDITABLE') || !ACCOUNT_EDITABLE) {
				include_once('class.CustomerAccountController.php');
				$controller = new CustomerAccountController();
				$controller->updateAccount();
			}

			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$view.'.php')) {
				include_once('modules/webshop/custom/class.'.$view.'.php');
				$this->view = new $view($this);
				$this->view->render($params);
			} else	echo "OrderConfirmController.build > Incorrect View given for display : /modules/webshop/custom/class.".$view.".php<br/>";
		}
	}

	function getProductsProps ($ids) {
		return (Array) $this->models['shop']->getProductsProps($ids);
	}

	function getCountries ($lang='fr', $all = false) {
		return (Array) $this->models['customer']->getCountryPile($lang, $all);
	}
	
	
	function getOrderingAddress ($customer) {
		return  $this->models['customer']->getOrderingAddress($customer);
		 
	}
	
	function setOrderingAddress ($customer, $id_address) { 
		return  $this->models['customer']->setOrderingAddress($customer, $id_address);
		 
	}

	function getAllShippingAddresses ($customer) {
		return  $this->models['customer']->getAllShippingAddresses($customer);
		 
	}
	
	function getShippingRates ($poids, $id_pays, $cp) {
		return $this->models['order']->getShippingRates ($poids, $id_pays, $cp);
	}
	
	function getShippingRateByMethod ($poids, $id_pays, $cp, $id_method) {
		return $this->models['order']->getShippingRateByMethod($poids, $id_pays, $cp, $id_method);
	}
	
	function getCountryLibelle ($lang='fr', $id_pays) { 
		return  $this->models['customer']->getCountryLibelle($lang, $id_pays);
	}
	
	function getShippingType ($poids, $id_pays, $cp) {
		return $this->models['order']->getShippingType($poids, $id_pays, $cp);
	}
	
	function compareZipcode ($cp, $zone, $type, $match) {
		return $this->models['order']->compareZipcode ($cp, $zone, $type, $match);
	}

}

?>
