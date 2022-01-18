<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';


if(isset($_POST['orderid']) && $_POST['orderid'] != ''){
	$ids = explode(',',$_POST['orderid']);
	
	foreach($ids as $key=>$id){
		$elem = new cms_assoclassepage($id);
		$elem->set_order( ($key+1) );
		dbUpdate($elem);
	}
}

?>