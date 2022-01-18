<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle product list content rendering through chosen rendering

// include webshop model
include_once('class.WebShopModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class ProductListController extends BaseModuleController {

	var $mod_name = 'webshop';


	// constructor
	function ProductListController () {

		$this->models['shop'] = new WebShopModel();

	}

	function build($view) {

		/*if ((strpos($_SERVER['SCRIPT_FILENAME'], "/content/") === false )&& ($_SERVER['REQUEST_URI']!='/')) {} else {*/
			if ($_GET['iscoll'] == 1 && WEBSHOP_CUSTOM_LIB != '' && function_exists('getCollection') && function_exists('listCollectionProducts')) {
				$segment = getCollection($_GET['gamme']);
				$data_pile = listCollectionProducts($_GET['gamme']);
			} else if (isset($_GET['gamme'])) {
				$segment = $this->models['shop']->segment($_GET['gamme']);
				$data_pile = $this->models['shop']->extract($_GET['gamme']);
			} else {	 
				//$data_pile = $this->models['shop']->extract_by_params();
				$data_pile = array();
			}
			 
			
			if ($this->debug) {
				
				viewArray($data_pile, 'DEBUG data');
			}
			
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$view.'.php')) {
				include_once('modules/webshop/custom/class.'.$view.'.php');
				$this->view = new $view($this);

				// FROM CUSTOM WEBSHOP LIBRARY
				// process possible custom action to add custom display on products rendering
				if (WEBSHOP_CUSTOM_LIB != '' && function_exists('getAssociatedCollections'))
					$associated = getAssociatedCollections($_GET['gamme']);
				else	$associated = Array();
                        	
				$params = Array(	'segment'	=> $segment,
						'data'		=> $data_pile,
						'associated'	=> $associated,
						'asso_products'	=> $this->models['shop']->getAssociatedProductsBySegment($_GET['gamme']) );
				$this->view->render($params);

			} else	echo "ProductListController.build > Incorrect View given for display : /modules/webshop/custom/class.".$view.".php<br/>";
		//}
	}

	function getAssociatedColors() {
		return getAssociatedColors($_GET['gamme']);
	}

}











 
?>
