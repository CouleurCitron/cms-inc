<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle order invoice rendering through chosen rendering

// include Order model
include_once('class.OrderModel.php');

class OrderInvoiceController {

	var $mod_name = 'webshop';


	// constructor
	function OrderInvoiceController () {

		$this->models['order'] = new OrderModel();
	}
	
	function build($view) {
		if ((strpos($_SERVER['REQUEST_URI'], "/content/") === false )&& ($_SERVER['REQUEST_URI']!='/')) {} else {

			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$view.'.php')) {
				include_once('modules/webshop/custom/class.'.$view.'.php');
				$this->view = new $view($this);
				$this->view->render($_POST['order_id']);
			} else	echo "OrderInvoiceController.build > Incorrect View given for display : /modules/webshop/custom/class.".$view.".php<br/>";
		}
	}

}

?>
