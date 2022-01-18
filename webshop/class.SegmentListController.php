<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle product root segment content rendering through chosen rendering

// include webshop model
include_once('class.WebShopModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class SegmentListController extends BaseModuleController {

	var $mod_name = 'webshop';


	// constructor
	function SegmentListController () {

		$this->models['shop'] = new WebShopModel();
	}


	function build($view) {

		if (is_as_get('gamme') && $_GET['gamme'] > 0)
			$segment = $_GET['gamme'];
		elseif (is_as_get('product') && $_GET['product'] > 0) {
			$product = $this->models['shop']->getProductDetails($_GET['product']);
			$segment = $product[0]['id_gamme'];
		} else	$segment = null;

		if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$view.'.php')) {

			include_once('modules/webshop/custom/class.'.$view.'.php');
			$this->view = new $view($this);
			$params['segment'] = $segment;
			$params['structure'] = $this->models['shop']->retrieve();
			$this->view->render($params);

		} else	echo "SegmentListController.build > Incorrect View given for display : /modules/webshop/custom/class.".$view.".php<br/>";
	}

}

?>
