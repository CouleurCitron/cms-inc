<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle product root product content rendering through chosen rendering

// include webshop model
include_once('class.WebShopModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class ProductDetailController extends BaseModuleController {

	var $mod_name = 'webshop';


	// constructor
	function ProductDetailController () {
		
		$this->models['shop'] = new WebShopModel();

	}


	function build($view) {

		$id = is_as_get("product") ? $_GET['product'] : WEBSHOP_COLL_SEGMENT_ID;
		 
		//if ((strpos($_SERVER['SCRIPT_FILENAME'], "/content/") === false )&& ($_SERVER['REQUEST_URI']!='/')) {
		//} else {
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$view.'.php')) {

				include_once('modules/webshop/custom/class.'.$view.'.php');
				$this->view = new $view($this);
				$params = Array();
				if (WEBSHOP_IS_TYPEPRODUIT == false)
					$params['product'] = $this->models['shop']->getProductDetailsWithoutType($id);
				else	$params['product'] = $this->models['shop']->getProductDetails($id);
				 
				
				foreach ($params['product'] as $key => $product) {
					// check for associated diaporama
					if ($product['shp_pdt_id_diaporama'] > 0)
						$params['product'][$key]['diaporama'] = $this->models['shop']->getProductDiaporama($product['shp_pdt_id_diaporama']);
				}	
				  
				$associated = $this->models['shop']->getAssociatedProducts ($id);
				 
				$params['associated'] = $associated ;
				
				if ($this->debug)
					viewArray($params['product'], 'ProductDetailController.build > product');

				$this->view->render($params);

			} else	echo "ProductDetailController.build > Incorrect View given for display : /modules/webshop/custom/class.".$view.".php<br/>";
		//}
	}

}

?>
