<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// base controller with standard functionnality for all modules controllers


class BaseModuleController {

	var $view = null;
	var $models = Array();
	var $debug = false;

	// constructor
	function BaseModuleController () {}

	// setDebugMode
	function setDebugMode($_active=true) {

		if ($_active)
			$this->debug = true;
		else	$this->debug = false;
	}

	// setModelDebugMode
	function setModelDebugMode($_model=null, $_active=true) {

		if (is_null($_model)) {
			foreach ($this->models as $model)
				$model->setDebugMode($_active);
		} elseif (!is_null($this->models[$_model]))
			$this->models[$_model]->setDebugMode($_active);
	}

}

?>
