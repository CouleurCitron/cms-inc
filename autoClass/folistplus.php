<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}

if (!isset($templateName)){
	$template = $_SERVER['DOCUMENT_ROOT']."/frontoffice/".$classeName."/folist_".$classeName.".html";
}
else{
	$template = $_SERVER['DOCUMENT_ROOT']."/frontoffice/".$classeName."/".$templateName;
}

if (is_file($template)){
	include("folistplus.template.php");
}
else{
	include("folist.php");
}
?>