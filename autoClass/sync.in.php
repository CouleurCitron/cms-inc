<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}

//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/sync.in.inc.php');

ini_set ('max_execution_time', 0); // Aucune limite d'execution
ini_set("memory_limit","1024M");

unset($_SESSION['BO']['CACHE']);

if (DEF_APP_USE_TRANSLATIONS)
	$translator =& TslManager::getInstance();


$bDebug = false;
$sMessage='';

// objet 
eval('$'.'oRes = new '.$classeName.'();');

if(!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else
	$sXML = $oRes->XML;
//$sXML = $oRes->XML;

xmlClassParse($sXML);

//$sPathSurcharge = $_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/'.$stack[0]['attrs']['NAME'].'.class.xml';
//		
//if(is_file($sPathSurcharge)){ 
//	$stack = array();		
//	// le parse
//	xmlFileParse($sPathSurcharge);
//}

$classeName = $stack[0]['attrs']['NAME'];
$classePrefixe = $stack[0]['attrs']['PREFIX'];
$aListeChamps = $oRes->getListeChamps(); 


$aNodeToSort = $stack[0]['children'];
$statusGetter = $oRes->getGetterStatut();

// ----------------------------------------
echo 'will sync in '.'/tmp/syncout_'.$classeName.'_php.xml'.'<br />';
$stack = xmlFileParse($_SERVER['DOCUMENT_ROOT'].'/tmp/syncout_'.$classeName.'_php.xml');

$aO =$stack[0]['children'];

$aImportLog=array();

foreach($aO as $k => $aNodes){
	
	if (class_exists($aNodes['name'])){
		echo 'will sync in top level objet '.$aNodes['name'].'<br />';
		$oO = new $aNodes['name']();
		
		syncInObject($aNodes, $oO);
	}	
	
}

?>