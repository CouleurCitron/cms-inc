<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
 	
	require_once 'cms-inc/include_cms.php';
	require_once 'cms-inc/include_class.php';
	
	
	  
	if (isset ($_POST["_id"]))  { 
		$id = $_POST["_id"];
		$oObj = getObjectById ("cms_glossary", $id); 
		if ((count($oObj) > 0)&&($oObj!=false)) {
			echo $oObj->get_soustitre();
		}
		
	}
	else {
		echo "false";	 
	}
	
	 
 
?>


