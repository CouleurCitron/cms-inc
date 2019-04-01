<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/chars.lib.php');

// TRAITEMENT du POST XML -------------------
$sPOST = file_get_contents('php://input') ;
//$sPOST = "";

error_log("------------");

if (strlen($sPOST)==0){
	error_log('php://input ne retourne rien');
	$aPOST = $GLOBALS['HTTP_POST_VARS'];
	$sPOST = "";
	if (count($aPOST) > 0){
		foreach ($aPOST as $key => $value){
			$sPOST .= str_replace("_", " ", $key)."=".$value.'&';
		}
	}
	$sPOST = stripslashes($sPOST);
	//$sPOST = "";////// simule comportement hosting michelin
}
if (strlen($sPOST)==0){
	error_log('GLOBALS[\'HTTP_POST_VARS\'] ne retourne rien');
	global $HTTP_POST_VARS;
	
	$aPOST = $HTTP_POST_VARS;
	$sPOST = "";
	if (count($aPOST) > 0){
		foreach ($aPOST as $key => $value){
			$sPOST .= str_replace("_", " ", $key)."=".$value;
		}
	}
	$sPOST = stripslashes($sPOST);
	//$sPOST = "";////// simule comportement hosting michelin
}
if (strlen($sPOST)==0){
	error_log('HTTP_POST_VARS ne retourne rien');
	
	$aPOST = $_POST;
	$sPOST = "";
	foreach ($aPOST as $key => $value){
		$sPOST .= "&".str_replace("_", " ", $key)."=".$value;
	}
}

if(is_post('XMLHUB')){ // as3
	$sPOST = $_POST['XMLHUB'];
	if (preg_match('/^%.+$/msi', $sPOST)==1){
		$sPOST = urldecode($sPOST);
	}
}


$sPOST = str_replace(chr(226).chr(128).chr(153), '\'', $sPOST);

$sPOST = preg_replace('/[\x5C]{2,}xe2[\x5C]{2,}x80[\x5C]{2,}x99/msi', '\'', $sPOST); // \x5C = \

$sPOST = preg_replace('/^&(.+)/msi', '$1', $sPOST); // leading &
$sPOST = preg_replace('/[\x5C]{2,}x/msi', '___x', $sPOST); // \x5C = \
$sPOST = preg_replace('/[\x5C]{1,}x/msi', '___x', $sPOST); // \x5C = \
$aEsc = array('___x22', '___x2C', '___x3C', '___x26');
$aChars = array('&quot;', '&apos;', '&lt;', '&amp;');
$sPOST = str_replace($aEsc, $aChars, $sPOST); // replace les hexa échappés par les codes HTML
$aEscApos = array(chr(226).chr(128).chr(153), '%E2%80%98', '%E2%80%99', '___xe2___x80___x98', '%e2___x80___x98', '___xe2___x80___x99', '%e2___x80___x99');
$sPOST = str_replace($aEscApos, '\'', $sPOST); // replace les apos échappés par les '



if (strlen($sPOST)==0){
	//error_log('_POST ne retourne rien');
}

$sPOST = utf8_decode($sPOST);

//----------------------

$stack = array();
//xmlStringParse('<?xml version="1.0" encoding="utf-8" ?'.'>'.$sPOST);
if(preg_match('/</msi', $sPOST)==1){
	//error_log('xml ');
	//error_log($sPOST);
	//xmlStringParse(extendedAsciiToHtml($sPOST));
	xmlStringParse($sPOST);
}
else{
	//error_log('not xml ');
	//error_log($sPOST);
}

$_HUB = array();

if (isset($stack[0]['attrs'])){
	foreach ($stack[0]['attrs'] as $attName => $attValue){
		$attValue = str_replace('___x0D', "\n", $attValue);
		$_HUB[$attName] = $attValue;
	}
	
	if (is_array($stack[0]['children'])){
		foreach ($stack[0]['children'] as $childId => $childValue){
			$_HUB[$childValue['name']] = $childValue;
		}
	}
	if (isset($stack[0]['cdata'])){
		if (preg_match('/^%.*/msi', $stack[0]['cdata'])==1){
			//$_HUB['cdata'] = utf8dec(urldecode($stack[0]['cdata']));
			$_HUB['cdata'] = mb_convert_encoding( rawurldecode($stack[0]['cdata']), 'Windows-1252', 'UTF-8');
			if((int)intval(PHP_VERSION) >= 5){
				// do nothing
			}
			else{
				//$_HUB['cdata'] = utf8_decode($_HUB['cdata']);
				$_HUB['cdata'] = mb_convert_encoding( $_HUB['cdata'], 'Windows-1252', 'UTF-8');
			}
		}
		else{
			$_HUB['cdata'] = $stack[0]['cdata'];
		}
		
		$_HUB['cdata'] = str_replace('&euro;', '€', $_HUB['cdata']);
	}
	
	if (!isset($_HUB['FORMNAME'])){
		$_HUB['FORMNAME'] = $stack[0]['name'];
	}
}
//error_log('----------XXXXXXXXXXXXXXXXXXXXX------------');
//log_dump($_HUB);
?>