<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
} 
if (!isset($templateName)){
	$template = $_SERVER['DOCUMENT_ROOT']."/frontoffice/".$classeName."/foshow_".$classeName.".html";
}
else{
	if (ereg($_SERVER['DOCUMENT_ROOT'],$templateName)) 
		$template = $templateName;
	else 
		$template = $_SERVER['DOCUMENT_ROOT']."/frontoffice/".$classeName."/".$templateName;
}

if (is_file($template)){
 
	include("foshowplusplus.template.php");
}
else{

	include("foshowplusplus.classic.php");
}
?>