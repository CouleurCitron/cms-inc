<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle product root segment content rendering through chosen rendering

// include webshop model
include_once('class.WebShopModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class SegmentDetailController extends BaseModuleController {

	var $mod_name = 'webshop';


	// constructor
	function SegmentDetailController () {

		$this->models['shop'] = new WebShopModel();

	}
	
	
	
	function build($view) {
		
		// temporary value forced for demo mode
		//$_GET['gamme'] = 3;
		(is_as_get("gamme")) ? $gamme = $_GET['gamme'] : $gamme = WEBSHOP_COLL_SEGMENT_ID;
		if ((strpos($_SERVER['SCRIPT_FILENAME'], "/content/") === false )&& ($_SERVER['REQUEST_URI']!='/')) {} else {

			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$view.'.php')) {

				include_once('modules/webshop/custom/class.'.$view.'.php');
				$this->view = new $view($this);
				$params = Array('segment' => $this->models['shop']->segment($gamme));
				$this->view->render($params);

			} else	echo "SegmentDetailController.build > Incorrect View given for display : /modules/webshop/custom/class.".$view.".php<br/>";
		}
	}

}

?>
