<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
//Page permettant de traiter la rupture d'association d'un objet à une page
require_once ($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

if(isset($_POST['xcp_id']) && $_POST['xcp_id'] != ''){
	$xcp = new cms_assoclassepage($_POST['xcp_id']);
	// print_r($xcp);
	// die();
	if(is_object($xcp)){
		dbDelete($xcp);
		return true;
	}else{
		return false;
	}
}else{
	return false;
}
?>