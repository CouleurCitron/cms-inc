<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');


$sql = "delete from ".$classeName. "";
$bRetour = dbExecuteQuery($sql);

if ($bRetour) {
	$listParam = "";
	if($_SERVER['QUERY_STRING']!="") 
		$listParam = "?".$_SERVER['QUERY_STRING'];
	echo "<script>document.location='list_".$classeName.".php".$listParam."'</script>";
}