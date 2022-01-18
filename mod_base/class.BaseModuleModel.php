<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// base model with standard functionnality for all modules models


class BaseModuleModel {

	var $debug = false;

	// constructor
	function BaseModuleModel () {}

	// setDebugMode
	function setDebugMode($_active=true) {

		if ($_active)
			$this->debug = true;
		else	$this->debug = false;
	}

}

?>
