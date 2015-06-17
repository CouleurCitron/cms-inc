<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}

unset($_SESSION['BO']['CACHE']);

$template = $_SERVER['DOCUMENT_ROOT']."/frontoffice/".$classeName."/foshow_".$classeName.".html";
if (is_file($template)){
	include_once("foshow.template.php");
}
else{
	include_once("foshow.classic.php");
}
?>