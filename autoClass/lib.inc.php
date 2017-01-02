<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/xml.parser.inc.php');

/*
function getObjetTree($aO, $id=0){
function getObjetChildren($aO, $id=0){
function NumToLetter($Col)
function getUILink($className, $sAction){
function isClassCMS($className){
function assoOrderFieldMatches($tempAssoPrefixe, $tempAssoInName, $tempAssoOutName, $associationid)
function assoOrderFieldValue($tempAssoPrefixe, $tempAssoInName, $tempAssoOutName, $associationid)
function isCmsClass($foreignName){
function getDiaporamaDiapos($oDiaporama){
function sortDiaporamaDiapos($aDiapo){
function getValidResizedImage($fileNameList, $filePath){
function isFieldTranslate($oO, $sFied){
function getClassLibelle($classeName){
function rewriteIfNeeded($str){
function cacheObject($sObject, $eId){
function displayItemForeign($oRes, $itemName, $aNodeToSort){
function displayItemIn($oRes, $itemName, $aNodeToSort){
function displayItemList($oRes, $itemName, $aNodeToSort){
function displayItem($oRes, $itemName, $aNodeToSort){
function displayItemIf($oRes, $itemName, $itemValue, $aNodeToSort){
function displayNoneItemIf($oRes, $itemName, $itemValue, $aNodeToSort){
function displayItemAssoIf($oRes, $itemName, $itemValue, $aNodeToSort){
function formatItemIn($oRes, $itemName, $aNodeToSort){
function formatItemList($oRes, $itemName, $aNodeToSort, $db, $typeAsso){
function formatItemForeign($oRes, $itemName, $aNodeToSort){
function formatItem($oRes, $itemName, $aNodeToSort){ 
function formatItemRaw($oRes, $itemName, $aNodeToSort){
function getItemValue($oO, $itemName){
function setItemValue($oRes, $itemName, $itemValue){	
function isItemUTF8($oO, $itemName){
function controlLinkValue($sLink, $oClass){
function critereIfdisplay($aNode, $oO, $valeur){
function nouveauGroup($aNode, $precGroup){
function finGroup($aNode, $precGroup){
function getItemByName($aNodes, $sName){
function getItemsByOption($aNodes, $sOption){
function getItemsByAttribute($aNodes, $sAttribute){
function getNodeByName($aNodes, $sName){
function getFilterPosts($needle="filter"){
function initFilterSession($needle="filter"){
function ScanDirs($Directory, $classeName){
function scanNode($nodeValue, $stack, $oClasse, $classeName, $divName) {
function isKey ($eKeyValue, $nodeValue) {
function isEnum ($eKeyValue, $nodeValue) {
function isFilename ($eKeyValue, $nodeValue) {
function isFilePlus ($eKeyValue, $nodeValue, $classeName) {
function isBool ($eKeyValue) {
function isDate ($eKeyValue) {
function isLink ($eKeyValue, $nodeValue) {
function isFiledir ($eKeyValue, $nodeValue) {
function debutDiv ($divName) {
function debutDivs ($divName) {
function finDiv ($divName) {
function getInclude($oRes, $itemName,  $aNodeToSort) {
function getTagPosts($needle="fAssoCms_tag_Classe_"){
function get_list_inscrits($sListeInscrit) { 	
function export_list_inscrits($sRep, $sListeInscrit, $aChamps, $sTypeExport){ 
function chars2htmlcodes($strAccents){
function getFileByName($Directory, $keyword){ 
function ScanForFilemanager($Directory){
function chars2htmlcodesAccents($strHTML){
function getListeChampsForObject($o0){
function xmlClassParse($sXML){
function cacheClasseList($classeName) {
function cacheClasseXML($classeName){
function cacheClasseXMLAndObjects($classeName){
function getCacheListFields(){
function doesFieldExist($aListeChamps, $sField){	
function getCorrectField ($aListeChamps, $prefix, $sField) {
function getValidHref ($link , $text) {
*/

// $aO : array of objects
// $id : object id whose children are to be returned
// returns : array of objets en arbre / false
function getObjetTree($aO, $id=0){
	$aChildren = getObjetChildren($aO, $id);

	foreach($aChildren as $k => $oO){
		$aChildren[$k]->children=getObjetTree($aO, $oO->get_id());
	}
	
	if (count($aChildren)>0){
		return $aChildren;
	}
	else{
		return false;
	}
}

// $aO : array of objects
// $id : object id whose children are to be returned
// returns : array of objets / false
function getObjetChildren($aO, $id=0){
	$aReturn = array();
	foreach($aO as $k => $oO){
		if ($oO->get_parent()==$id){
			$aReturn[]=$oO;	
		}
	}
	
	if (count($aReturn)>0){
		return $aReturn;
	}
	else{
		return false;
	}
}

function NumToLetter($Col){
	if ($Col <= 26) return chr($Col + 64);
	
	//puts us on Zero Bound Index… tis where my math is 1337.
	$Col–;
	return NumToLetter($Col / 26) . NumToLetter(($Col % 26) + 1);
}

function getUILink($classeName, $sAction){
	//echo "getUILink($classeName, $sAction)";
	if (isClassCMS($classeName)){ 
		if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/'.$classeName.'/'.$sAction.'_'.$classeName.'.php')){
			$sLink = '/backoffice/cms/'.$classeName.'/'.$sAction.'_'.$classeName.'.php'; 
		}
		elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/adss/'.$classeName.'/'.$sAction.'_'.$classeName.'.php')){
			$sLink = '/backoffice/adss/'.$classeName.'/'.$sAction.'_'.$classeName.'.php'; 
		}
		elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/'.$classeName.'/'.$sAction.'_'.$classeName.'.php')){
			$sLink = '/backoffice/'.$classeName.'/'.$sAction.'_'.$classeName.'.php'; 
		}
		else{
			$sLink = false;
		}
	}
	elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/'.$classeName.'/'.$sAction.'_'.$classeName.'.php')){
		$sLink = '/backoffice/'.$classeName.'/'.$sAction.'_'.$classeName.'.php'; 
	}
	elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/'.$classeName.'/'.$sAction.'_'.$classeName.'.php')){
		$sLink = '/backoffice/cms/'.$classeName.'/'.$sAction.'_'.$classeName.'.php'; 
		$aClasse = dbGetObjectsFromFieldValue3('classe', array('get_nom'), array('equals'), array($className), NULL, NULL);
		if ((count($aClasse) > 0)&&($aClasse!=false)){
			foreach($aClasse as $oClasse){
				$_SESSION['cms_classes'][$oClasse->get_id()]=$className;
				break;
			}
		}
	}
	elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/adss/'.$classeName.'/'.$sAction.'_'.$classeName.'.php')){
		$sLink = '/backoffice/adss/'.$classeName.'/'.$sAction.'_'.$classeName.'.php'; 
	}
	else{
		$sLink = false;
	}

	return $sLink;
}


function isClassCMS($className){  
	if (is_array ($_SESSION['cms_classes'])) {
		if (in_array($className, $_SESSION['cms_classes'])){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}

function assoOrderFieldMatches($tempAssoPrefixe, $tempAssoInName, $tempAssoOutName, $associationid){
	if (isset($_POST["fAsso".ucfirst($tempAssoInName)."_".ucfirst($tempAssoPrefixe."_".$tempAssoOutName)."_".$associationid."_ordre"])){
		return true;
	}
	elseif (isset($_POST["fAsso".ucfirst($tempAssoInName)."_".ucfirst($tempAssoOutName)."_".$associationid."_ordre"])){
		return true;
	}
	else{
		return false;
	}
}

function assoOrderFieldValue($tempAssoPrefixe, $tempAssoInName, $tempAssoOutName, $associationid){
	if (isset($_POST["fAsso".ucfirst($tempAssoInName)."_".ucfirst($tempAssoPrefixe."_".$tempAssoOutName)."_".$associationid."_ordre"])){
		//echo "fAsso".ucfirst($tempAssoInName)."_".ucfirst($tempAssoPrefixe."_".$tempAssoOutName)."_".$associationid."_ordre<br />";
		return $_POST["fAsso".ucfirst($tempAssoInName)."_".ucfirst($tempAssoPrefixe."_".$tempAssoOutName)."_".$associationid."_ordre"];
	}
	elseif (isset($_POST["fAsso".ucfirst($tempAssoInName)."_".ucfirst($tempAssoOutName)."_".$associationid."_ordre"])){
		//echo "fAsso".ucfirst($tempAssoInName)."_".ucfirst($tempAssoOutName)."_".$associationid."_ordre<br />";
		return $_POST["fAsso".ucfirst($tempAssoInName)."_".ucfirst($tempAssoOutName)."_".$associationid."_ordre"];
	}
	else{
		//echo 'nahh<br />';
		return false;
	}
}

function isCmsClass($foreignName){
	return isClassCMS($foreignName);
	/*
	$aClasse = dbGetObjectsFromFieldValue3('classe', array('get_statut', 'get_nom'), array('equals', 'equals'), array(DEF_ID_STATUT_LIGNE, $foreignName), NULL, NULL);
	if ((count($aClasse) > 0)&&($aClasse!=false)){
		$isCms = $aClasse[0]->get_iscms();
	}
	else{ // pas en base
		if (preg_match('/^cms_.*$/msi', $foreignName)==1){
			$isCms = 1;
		}
		elseif (preg_match('/^shp_.*$/msi', $foreignName)==1){
			$isCms = 1;
		}
		elseif (preg_match('/^nws_.*$/msi', $foreignName)==1){
			$isCms = 1;
		}
		elseif (preg_match('/^pa_.*$/msi', $foreignName)==1){
			$isCms = 1;
		}
		elseif (preg_match('/^bo_.*$/msi', $foreignName)==1){
			$isCms = 1;
		}
		elseif (preg_match('/^ss3_.*$/msi', $foreignName)==1){
			$isCms = 1;
		}
		else{
			$isCms = 0;
		}
	}
	return $isCms;*/
}

function getDiaporamaDiapos($oDiaporama){
	$sql = 'SELECT DISTINCT cms_diapo.* FROM cms_diapo, cms_diaporama d, cms_assodiapodiaporama xdp WHERE xdp.xdp_cms_diaporama = '.$oDiaporama->get_id().' AND xdp.xdp_cms_diapo = cms_diapo.img_id order by xdp.xdp_ordre ASC';
	
	$aDiapos = dbGetObjectsFromRequete('cms_diapo', $sql);	
	return $aDiapos;
}

function sortDiaporamaDiapos($aDiapo){
	$aDiapoTemp = array();
	foreach($aDiapo as $cKey => $oDiapo){	
		$imgSrc = $oDiapo->get_src();
		$aDiapoTemp[$imgSrc]=$oDiapo;
	}
	ksort($aDiapoTemp);
	return array_values($aDiapoTemp);
}

function getValidResizedImage($fileNameList, $filePath, $rang = -1){
	//die($fileNameList);
	// $rang : permet de définir quelle image on souhaite récupérer  
	if (preg_match('/;/msi', $fileNameList)){
		$aFiles = explode(';', $fileNameList);
		if ($rang == -1) {
			for ($im=(count($aFiles)-1);$im>=0;$im--){						
				if (is_file($_SERVER['DOCUMENT_ROOT'].$filePath.$aFiles[$im])){ // le fichier existe 
					$fileNameList=$aFiles[$im];
					return $fileNameList;
				}
			}
		}
		else { 
			if (is_file($_SERVER['DOCUMENT_ROOT'].$filePath.$aFiles[$rang])){ // le fichier existe 
				$fileNameList=$aFiles[$rang]; 
				return $fileNameList;
			}
			else if (is_file($_SERVER['DOCUMENT_ROOT'].$filePath.$aFiles[0])){ // le fichier existe
				$fileNameList=$aFiles[0];  
				return $fileNameList;
			}
		} 
	}	
	elseif (is_file($_SERVER['DOCUMENT_ROOT'].$filePath.$fileNameList)){ // le fichier existe
		return $fileNameList;
	}
	error_log('missing file: '.$fileNameList.' from dir: '.$filePath);
	return '';
}

function isFieldTranslate($oO, $sField){
	global $stack;
	$sXML = $oO->XML;
	xmlClassParse($sXML);
	$aNodeToSort = $stack[0]["children"];
	$classePrefix = $stack[0]["attrs"]["PREFIX"];
	$sField = str_replace($classePrefix.'_', '', $sField);

	$node = getItemByName($aNodeToSort, $sField);
	if (isset($node['attrs']['TRANSLATE']) && ($node['attrs']['TRANSLATE']=='reference')){
		return true;
	}
	else{
		return false;
	}
}

function getClassLibelle($classeName){
	global $stack;
	// objet 
	eval("$"."oRes = new ".$classeName."();");
	
	$sXML = $oRes->XML;
	xmlClassParse($sXML);
	
	$classeName = $stack[0]["attrs"]["NAME"];
	if (isset($stack[0]["attrs"]["LIBELLE"]) && ($stack[0]["attrs"]["LIBELLE"] != "")){
		$classeLibelle = stripslashes($stack[0]["attrs"]["LIBELLE"]);
	}
	else{
		$classeLibelle = $classeName;
	}
	return $classeLibelle;
}


function rewriteIfNeeded($str){
	
	if (function_exists('do_rewrite_rule')){
		// oui, on a de quoi faire du rewrite		
		$str = do_rewrite_rule($str);		
	}
		
	return $str;
}

// cacheObject
// cherche un objet en cache memoire, 
// le retourne si trouver
// sinon, le cherche en base, le stocke en cache etle retourne
// remplacement de :
// $oTemp = cacheObject($sTempClasse, $eKeyValue);

function cacheObject($sObject, $eId){
	if ($sObject==''){
		return false;	
	}
	
	$bFound=NULL;

	if (!isset($_SESSION['BO']['CACHE'])){
		$_SESSION['BO']['CACHE'] = array();
		$bFound=false;
	}
	elseif (!isset($_SESSION['BO']['CACHE'][$sObject])){
		$_SESSION['BO']['CACHE'][$sObject] = array();
		$bFound=false;
	}
	elseif (!isset($_SESSION['BO']['CACHE'][$sObject][$eId])){
		$bFound=false;
	}
	elseif (isset($_SESSION['BO']['CACHE'][$sObject][$eId])){ // FOUND !!
		$bFound=true;
		
	}
	else{ // ne devrait jamais se produire
		$bFound=false;
	}
	
	// retour
	if ($bFound==false){
		eval('$'.'oCache = new '.$sObject.'('.$eId.');');
		$_SESSION['BO']['CACHE'][$sObject][$eId] = $oCache;
	}
	else{
		eval('$'.'oCache = new '.$sObject.'();');
		$oTemp = $_SESSION['BO']['CACHE'][$sObject][$eId];
		foreach ($oTemp as $tempKey => $tempValue){
			$oCache->$tempKey = $tempValue;
		}
	}
	
	return $oCache;
}

function displayItemForeign($oRes, $itemName, $aNodeToSort){
	
	global $stack;
	$localItemName = preg_replace('/([^\.]+)\.[^\.]+/','$1',$itemName);
	$foreignItemName = preg_replace('/[^\.]+\.([^\.]+)/','$1',$itemName);
	$eLocalKeyValue = getItemValue($oRes, $localItemName);
	//echo $localItemName."(".$eLocalKeyValue.")".".".$foreignItemName;
	$oForeign = new $localItemName($eLocalKeyValue);
	//pre_dump($oForeign);

	$sXML = $oForeign->XML;
	xmlClassParse($sXML);
	
	$aNodeToSort = $stack[0]["children"];
	$stack = array();
	
	if(formatItemRaw($oForeign, $foreignItemName, $aNodeToSort) == ""){
		return " style=\"display:none;\" ";
	}
	else{
		return "";
	}

}

function displayItemIn($oRes, $itemName, $aNodeToSort){
	
	global $stack;
	$localItemName = preg_replace('/([^\.]+)\.in\.[^\.]+/','$1',$itemName);
	$foreignObjectName = preg_replace('/[^\.]+\.in\.([^\.]+)/','$1',$itemName);
	$eLocalKeyValue = getItemValue($oRes, 'id');
	
	$aForeign = dbGetObjectsFromFieldValue($foreignObjectName, array("get_".$localItemName),  array($eLocalKeyValue), NULL);
	
	if(count($aForeign) == 0){
		return " style=\"display:none;\" ";
	}
	else{
		return "";
	}

}

function displayItemList($oRes, $itemName, $aNodeToSort){
	
	global $stack;
	$localItemName = preg_replace('/([^\.]+)\.asso\.[^\.]+/','$1',$itemName);
	$foreignObjectName = preg_replace('/[^\.]+\.asso\.([^\.]+)/','$1',$itemName);
	$eLocalKeyValue = getItemValue($oRes, $localItemName);
	
	$aForeign = dbGetObjectsFromFieldValue($foreignObjectName, array('get_'.$localItemName),  array($eLocalKeyValue), NULL);
	
	if(count($aForeign) == 0){
		return " style=\"display:none;\" ";
	}
	else{
		return "";
	}

}

function displayItem($oRes, $itemName, $aNodeToSort){
	
	$aItem = getItemByName($aNodeToSort, $itemName);
	$classeName = $oRes->getClasse();
	$eKeyValue = getItemValue($oRes, $itemName);
	
	$echoStr = "";
	
	if ($eKeyValue == "") {
		$echoStr = "";
	}
	else  {
		if ($aItem["attrs"]["FKEY"]){ // cas de foregin key			
			$sTempClasse = $aItem["attrs"]["FKEY"];
			if ($eKeyValue > -1){
				$oTemp = cacheObject($sTempClasse, $eKeyValue);
				// check Temp viewer page
				if (is_file("../".$oTemp->getClasse()."/index.php")){
					$tempViewerPage = "../".$oTemp->getClasse()."/index.php";							
				}
				elseif (is_file("../".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php")){
					$tempViewerPage = "../".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php";							
				}
				else{
					$tempViewerPage = "";
				}
				
				if ($tempViewerPage != ""){
					$echoStr .= "<a href=\"".$tempViewerPage."?id=".$oTemp->get_id()."\">";
					$echoStr .= getItemValue($oTemp, $oTemp->getDisplay());
					$echoStr .= "</a>";
				}
				else{
					$echoStr .= getItemValue($oTemp, $oTemp->getDisplay());
				}
			}
			else{
				$echoStr .= "";
			}
		}
		else if ($aItem["attrs"]["OPTION"] && $aItem["attrs"]["OPTION"] == "bool"){ // cas de booleen		
			if ($eKeyValue == 0) $echoStr = ""; 	
			else $echoStr = $eKeyValue;
		}
		else {
			$echoStr = $eKeyValue;
		}
	}
	if(trim($echoStr) == "" || $echoStr==-1){
		return " style=\"display:none;\" ";
	}
	else{
		return "";
	}

	
	

}


function displayItemIf($oRes, $itemName, $itemValue, $aNodeToSort){
	$aValue = array();
	if (ereg (",", $itemValue)) {
		$aValue = explode(",", $itemValue);
	}
	else {
		$aValue[0]=$itemValue;
	}
	
	$aItem = getItemByName($aNodeToSort, $itemName);
	$classeName = $oRes->getClasse();

	$eKeyValue = getItemValue($oRes, $itemName);
	if ($eKeyValue == "") $eKeyValue = "''";
	//echo $eKeyValue." ".$itemValue;
	 
	if (!in_array($eKeyValue, $aValue)) {
		return " style=\"display:none;\" ";
	}
	else{
		return "";
	}

}


function displayNoneItemIf($oRes, $itemName, $itemValue, $aNodeToSort){ 
	$aValue = array();
	if (ereg (",", $itemValue)) {
		$aValue = explode(",", $itemValue);
	}
	else {
		$aValue[0]=$itemValue;
	} 
	$aItem = getItemByName($aNodeToSort, $itemName);
	$classeName = $oRes->getClasse();

	$eKeyValue = getItemValue($oRes, $itemName);
	if ($eKeyValue == "") $eKeyValue = "''";
	 
	
	if (!in_array($eKeyValue, $aValue)) {
		return "";
	}
	else{
		return " style=\"display:none;\" ";
	}

}

function displayItemAssoIf($oRes, $itemName, $itemValue, $aNodeToSort){
	//echo $itemName;
	$nameClasse = explode("\.", $itemName);
	$classeNameAsso=$nameClasse[0];
	$classeNameaAsso=$nameClasse[2];
	$aItem = getItemByName($aNodeToSort, $itemName);
	$classeName = $oRes->getClasse();
	
	$eKeyValue = getItemValue($oRes, "id");
	//echo $classeName.$classeNameAsso." ".$eKeyValue;
	global $stack;	
	$stack = array();
	$oForeign = new $classeNameAsso();
	$sXML = $oForeign->XML;
	xmlClassParse($sXML);
	$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];

	if (getCount_where($classeNameAsso, array($foreignPrefixe."_".$classeName, $foreignPrefixe."_".$classeNameaAsso), array($eKeyValue,$itemValue), array("NUMBER", "NUMBER")) >  0){

		return "";
	}
	else{
		return " style=\"display:none;\" ";
	}

}

function formatItemIn($oRes, $itemName, $aNodeToSort){
	global $stack;
	$localItemName = preg_replace('/([^\.]+)\.in\.[^\.]+\.[^\.]+/','$1',$itemName);
	$foreignObjectName = preg_replace('/[^\.]+\.in\.([^\.]+)\.[^\.]+/','$1',$itemName);
	$foreignItemName = preg_replace('/[^\.]+\.in\.[^\.]+\.([^\.]+)/','$1',$itemName);

	$oForeign = new $foreignObjectName();
	
	$sXML = $oForeign->XML;
	xmlClassParse($sXML);
	
	$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
	
	$aNodeToSort = $stack[0]["children"];
	$stack = array();
	
	$eKeyValue = getItemValue($oRes, "id");
	
	
	$aForeign = dbGetObjectsFromFieldValue($foreignObjectName, array('get_'.$localItemName),  array($eKeyValue), NULL);
	$returnStr = "";
	foreach($aForeign as $kForeign => $oVForeign){
		 
		$returnStr .= formatItem($oVForeign, $foreignItemName, $aNodeToSort);
	
	}

	
	return $returnStr;
}

function formatItemList($oRes, $itemName, $aNodeToSort, $db, $typeAsso){
	
	if ($typeAsso == 'asso') {
		// on change de oRes
		global $stack;
		$localItemName = preg_replace('/([^\.]+)\.asso\.[^\.]+/','$1',$itemName);
		$foreignObjectName = preg_replace('/[^\.]+\.asso\.([^\.]+)/','$1',$itemName);
		$eKeyValue = formatItemForeign($oRes, $localItemName.'.id', $aNodeToSort);
	}
	else if ($typeAsso == 'in') {
		global $stack;
		$localItemName = preg_replace('/([^\.]+)\.in\.[^\.]+/','$1',$itemName);
		$foreignObjectName = preg_replace('/[^\.]+\.in\.([^\.]+)/','$1',$itemName);
		$eKeyValue = getItemValue($oRes, 'id');
	}
	
	$pageList = "frontoffice/".$foreignObjectName."/folist_".$foreignObjectName.".php";
	include($pageList);
}


function formatItemForeign($oRes, $itemName, $aNodeToSort){
	global $stack;
	$localItemName = preg_replace('/([^\.]+)\.[^\.]+/','$1',$itemName);
	$foreignItemName = preg_replace('/[^\.]+\.([^\.]+)/','$1',$itemName);
	$eLocalKeyValue = getItemValue($oRes, $localItemName);
	$oForeign = new $localItemName($eLocalKeyValue);
	
	$sXML = $oForeign->XML;
	xmlClassParse($sXML);
	
	$aNodeToSort = $stack[0]["children"];
	$stack = array();
	
	return formatItem($oForeign, $foreignItemName, $aNodeToSort);
}

function formatItem($oRes, $itemName, $aNodeToSort){ 
	$aItem = getItemByName($aNodeToSort, $itemName);
	
	$classeName = $oRes->getClasse();
	$eKeyValue = getItemValue($oRes, $itemName);
	 
	$echoStr = "";
	
	
	if (!isset($translator)){
		$translator =& TslManager::getInstance(); 
	}
	if (!isset($_SESSION['BO']['cms_texte'])){
		$translator->loadAllTransToSession();
	}


	if ($aItem["attrs"]["FKEY"]){ // cas de foregin key			
		$sTempClasse = $aItem["attrs"]["FKEY"];
		if ($eKeyValue > -1){
			 
			/*
			else if ($aItem["attrs"]["OPTION"] == "diaporama"){ // cas link
				 $echoStr .= "diaporama";
			}
			*/
			 
			$oTemp = cacheObject($sTempClasse, $eKeyValue);
			// check Temp viewer page
			// cas d'un diaporama
			if (is_file("../".$oTemp->getClasse()."/index.php")){
				$tempViewerPage = "../".$oTemp->getClasse()."/index.php";							
			}
			elseif (is_file("../".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php")){
				$tempViewerPage = "../".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php";							
			}
			elseif (is_file("../".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php")){
				$tempViewerPage = "../".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php";							
			}
			else{
				$tempViewerPage = "";
			}
		
			if ($tempViewerPage != ""){
				 
				$echoStr .= "<a href=\"".$tempViewerPage."?id=".$oTemp->get_id()."\">";
				$echoStr .= getItemValue($oTemp, $oTemp->getDisplay());
				$echoStr .= "</a>"; 
			}
			else{
				if ($aItem["attrs"]["OPTION"] == "diaporama"){ // cas link
				 
					$aTemp_diapo = dbGetObjectsFromFieldValue("cms_assodiapodiaporama", array("get_cms_diaporama"), array($oRes->get_diaporama()), NULL);
					$oTemp_diapo = $aTemp_diapo[0];	 
					$oDiapo = new Cms_diapo($oTemp_diapo->get_cms_diapo());
					
					
					eval( "$"."diapo_display = "."$"."oRes->get_".$oRes->getDisplay()."();"); 
					$echoStr .= '<img src="/custom/upload/cms_diapo/'.$oDiapo->get_src().'" alt="'.$diapo_display.'"/>';
				} 
				else { 
					$echoStr .= getItemValue($oTemp, $oTemp->getDisplay());
				}
			}
		}
		else{
			$echoStr .= "n/a";
		}
	}// fin fkey
	elseif ($aItem["attrs"]["OPTION"] == "enum"){ // cas enum		
		if (isset($aItem["children"]) && (count($aItem["children"]) > 0)){
			foreach ($aItem["children"] as $childKey => $childNode){
				if($childNode["name"] == "OPTION"){ // on a un node d'option				
					if ($childNode["attrs"]["TYPE"] == "value"){
						if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
							$echoStr .= $childNode["attrs"]["LIBELLE"];
							break;
						}
					} //fin type  == value				
				}
			}
		}		
	} // fin cas enum
	else{ // cas typique
		if ($eKeyValue > -1){ // cas typique typique
			if ($aItem["attrs"]["OPTION"] == "file"){ // cas file				
				if (preg_match('/;/msi', $eKeyValue)){					
					$aFiles = explode(';', $eKeyValue);
					for ($im=(count($aFiles)-1);$im>=0;$im--){
						if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$aFiles[$im])){ // le fichier existe
							$eKeyValue=$aFiles[$im];
							break;
						}
					}
				}
				
				if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue)){ // le fichier existe
					if (eregi("\.gif$",$eKeyValue) || eregi("\.png$",$eKeyValue) || eregi("\.jpg$",$eKeyValue) || eregi("\.jpeg$",$eKeyValue)){ // image	
						if (isset ($aItem["attrs"]["CLASS"]) && $aItem["attrs"]["CLASS"]) {
							$sClasse=" class=\"".$aItem["attrs"]["CLASS"]."\"";
						}
							
						if (isset($aItem["children"]) && (count($aItem["children"]) > 0)){
							foreach ($aItem["children"] as $childKey => $childNode){
								if (isset($childNode["attrs"]["WIDTH"]) && isset($childNode["attrs"]["HEIGHT"]) && $childNode["attrs"]["WIDTH"]!="" && $childNode["attrs"]["HEIGHT"]!="") {
									 
									$widthMax=$childNode["attrs"]["WIDTH"];
									$heightMax=$childNode["attrs"]["HEIGHT"];
									$aInfos = getimagesize($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue);
									$width = $aInfos[0];
									$height = $aInfos[1];
									if ($widthMax == $heightMax ) {
										if ($width > $height) {
											if ($width > $widthMax) {
												$widthNew = $widthMax;
												$heightNew = ($widthMax * $height) / $width;
											}					
											else {
												if ($height > $heightMax) {
												$heightNew = $heightMax;
												$widthNew = ($heightNew * $width) / $height;
												}
											}
										}
										else {
										}
										 
										
									}					
									elseif ($widthMax > $heightMax) {
										if ($width > $widthMax) {
											$widthNew = $widthMax;
											$heightNew = ($widthMax * $height) / $width;
										}
									}
									else {
										if ($height > $heightMax) {
											$heightNew = $heightMax;
											$widthNew = ($heightNew * $width) / $height;
										}
									}
								} 
							} 
							$heightNew =(int)$heightNew; 
							$widthNew =(int)$widthNew;
							($heightNew == 0) ? $heightNew="" : $heightNew="height=\"".$heightNew."\"";
							($widthNew == 0) ? $widthNew="" : $widthNew="width=\"".$widthNew."\"";
							$echoStr .= "<img src=\"/custom/upload/".$classeName."/".$eKeyValue."\" border=\"0\" ".$heightNew." ".$widthNew." alt=\"".$eKeyValue."\" ".$sClasse."/>";
						}
						else {
							$echoStr .= "<img src=\"/custom/upload/".$classeName."/".$eKeyValue."\" border=\"0\" alt=\"".$eKeyValue."\" ".$sClasse." />";
						}
					}
					elseif (eregi("\.flv$",$eKeyValue)){ // video
						/*						
						$file = $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue;									
						require_once('flv4php/FLV.php'); // Path to flv.php / (flv4php)									
						$flv = new FLV($file);
						$metadata = $flv->metadata;
						if (isset($metadata)){
							$flvW = $metadata["width"];
							$flvH = $metadata["height"]+32;
						}
						else{
							$flvW = 384;
							$flvH = 384;
						}*/
						list($flvW, $flvH, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/utils/scrubber.swf");
					
						$echoStr .= "<script src=\"/backoffice/cms/js/AC_RunActiveContent.js\" type=\"text/javascript\"></script>\n";							
						$echoStr .= "<script type=\"text/javascript\">\n";
						$echoStr .= "swfSrc = \"/backoffice/cms/utils/scrubber\"+\"?_vidName=".$eKeyValue."&_vidURL=/custom/upload/".$classeName."/".$eKeyValue."&_phpURL=http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/flvprovider.php&\";\n";						
						$echoStr .= "AC_FL_RunContent('codebase','https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0','width','".$flvW."','height','".$flvH."','src',swfSrc,'quality','high','pluginspage','https://get.adobe.com/flashplayer/','movie',swfSrc, 'scale', 'default', 'wmode', 'transparent');\n";
						$echoStr .= "</script>\n";								
					}					
					else if (isset($aItem["children"]) && (count($aItem["children"]) > 0)){
						foreach ($aItem["children"] as $childKey => $childNode){
							$itemLbl=$childNode["attrs"]["ITEMLIBELLE"];
						}
						$countoption=count($itemLbl);
						$libelle = $eKeyValue;
						foreach ($aItem["children"] as $childKey => $childNode){
							if ($childNode["name"] == "OPTION")  { // on a un node d'option
								if (($countoption==1) && isset($childNode["attrs"]["ITEMLIBELLE"]) && (($childNode["attrs"]["TYPE"]=="link")||($childNode["attrs"]["TYPE"]=="pdf"))) {	// on a un node d'option link ou pdf  avec un ITEMLIBELLE
									$tempItemLibelle = getItemByName($aNodeToSort, $childNode["attrs"]["ITEMLIBELLE"]);
									if ($tempItemLibelle != false){
										$tempItemLibelleKeyValue = getItemValue($oRes, $tempItemLibelle["attrs"]["NAME"]);
										if (isset($tempItemLibelleKeyValue) && ($tempItemLibelleKeyValue != "")){
											$libelle = $tempItemLibelleKeyValue;
										}
										elseif (isset($tempItemLibelle["attrs"]["LIBELLE"]) && ($tempItemLibelle["attrs"]["LIBELLE"] != "")){
											$libelle = $tempItemLibelle["attrs"]["LIBELLE"];
										}
										else{
											$libelle = $tempItemLibelle["attrs"]["NAME"];
										}											
									}	
									else{
										if (isset($aItem["attrs"]["LIBELLE"]) && ($aItem["attrs"]["LIBELLE"] != "")){
											$libelle = $aItem["attrs"]["LIBELLE"];
										}
										else{
											$libelle = $itemName;
										}
									}			
								}
								else if (($countoption!=1) && isset($childNode["attrs"]["LIBELLE"]) && (($childNode["attrs"]["TYPE"]=="link")||($childNode["attrs"]["TYPE"]=="pdf"))) {	// on a un node d'option link ou pdf avec un LIBELLE
									$libelle = $childNode["attrs"]["LIBELLE"];												
								}
								else if ($countoption!=1){
									if (isset($aItem["attrs"]["LIBELLE"]) && ($aItem["attrs"]["LIBELLE"] != "")){
										$libelle = $aItem["attrs"]["LIBELLE"];
									}
									else{
										$libelle = $itemName;
									}
								}
								//test sur Type
								$tempLink = "/custom/upload/".$classeName."/".$eKeyValue;
								$tempExt= strtolower(strrchr(basename($tempLink), "."));
								if ($tempExt == ".pdf") {
									
										$tempFile = basename($tempLink);
										$tempChemin = str_replace($tempFile, "", $tempLink);
										$tempLink = "/modules/utils/telecharger.php?chemin=".$tempChemin."&file=".$tempFile."&";	
										$temptarget = "_self";							
								}	
								else{
									$temptarget = "_blank";
									$tempLink = "/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName;
								}										
							}										
						}
						if (isset($aItem["attrs"]["NOHTML"]) && ($aItem["attrs"]["NOHTML"] == "true")){
							$echoStr .= "/custom/upload/".$classeName."/".$eKeyValue;
						}
						else {
						$echoStr .= "<a href=\"".$tempLink."\" target=\"".$temptarget."\" title=\"".$libelle."\">".$libelle."</a>\n";
						}
					}
					else{
						$tempLink = "/custom/upload/".$classeName."/".$eKeyValue;
						$tempFile = basename($tempLink);
						$tempChemin = str_replace($tempFile, "", $tempLink);
						$tempLink = "/modules/utils/telecharger.php?chemin=".$tempChemin."&file=".$tempFile."&";		
						$echoStr .= $tempLink;
					}
				} // if (is_file(
				else{
					$echoStr .= "<!-- fichier manquant : ".$_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue." -->\n";
				}
			}
			else if ($aItem["attrs"]["OPTION"] == "bool"){ // boolean
				if (intval($eKeyValue) == 1){
					$echoStr .= "oui";
				}
				else{
					$echoStr .= "non";
				}						
			}
			else if ($aItem["attrs"]["TYPE"] == "date"){ // date
				// expected : jj/mm/aaaa
				if (preg_match('/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/', $eKeyValue)==1){	
					$jj =   preg_replace('/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/', '$1', $eKeyValue);	
					$mm =   preg_replace('/[0-9]{2}\/([0-9]{2})\/[0-9]{4}/', '$1', $eKeyValue);
					$aaaa = preg_replace('/[0-9]{2}\/[0-9]{2}\/([0-9]{4})/', '$1', $eKeyValue);						
				
				}
				else if (preg_match('/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/', $eKeyValue)==1){// expected : aaaa/mm/jj
					$aaaa = preg_replace('/([0-9]{4})/[0-9]{2}/[0-9]{2}/', '$1', $eKeyValue);	
					$mm =   preg_replace('/[0-9]{4}/([0-9]{2})/[0-9]{2}/', '$1', $eKeyValue);
					$jj =   preg_replace('/[0-9]{4}/[0-9]{2}/([0-9]{2})/', '$1', $eKeyValue);							
				
				}
				if ($mm != "00"){	//00/00/1999 devient 1999 - 00/02/1998 devient 02/1998						
					if ($jj != "00"){
						$sDate .= $jj."/";
					}
					$sDate .= $mm."/";
				}
				$sDate .= $aaaa;
				
				if (isset($aItem["attrs"]["FORMAT"]) && $aItem["attrs"]["FORMAT"]!="") { 
					if (isset($aItem["attrs"]["LANGUE"]) && $aItem["attrs"]["LANGUE"]=="fr") {
						list($jour, $mois, $annee) = explode('/', $sDate);
						$sDate = date($aItem["attrs"]["FORMAT"] , mktime(0, 0, 0, $mois, $jour, $annee)); 
						$sDate = getDateFR ($sDate);
					}
				}
				$echoStr .= $sDate;
			}
			else if ($aItem["attrs"]["OPTION"] == "link"){ // cas link
				 
				if ($eKeyValue != ""){
					$href=$eKeyValue;
					$libelle=$eKeyValue;
					if (isset($aItem["children"]) && (count($aItem["children"]) > 0)){									foreach ($aItem["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "link"){// on a un node d'option link
									 
									if (isset($childNode["attrs"]["LIBELLE"]) && ($childNode["attrs"]["LIBELLE"] != "")){
										$libelle =$childNode["attrs"]["LIBELLE"];
									} 
								} //fin type  == link	
								else if ($childNode["attrs"]["TYPE"] == "image"){// on a un node d'option link
									 
									if (isset($childNode["attrs"]["ITEMLIBELLE"]) && ($childNode["attrs"]["ITEMLIBELLE"] != ""))  {
										 
										$nom_image = getItemValue($oRes, $childNode["attrs"]["ITEMLIBELLE"]);
										
										if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$nom_image)){ // le fichier existe
											$libelle ='<img src="/custom/upload/'.$classeName.'/'.$nom_image.'" />';
										}
 
										 
										 
									}
								} //fin type  == image			
							}
						}
					}
					// limite la taille du lien	
					if (isset($aItem["attrs"]["MAXLENGTH"]) && ($aItem["attrs"]["MAXLENGTH"] != "")) {
						if (strlen($libelle) > $aItem["attrs"]["MAXLENGTH"]) {
							$libelle = substr( $libelle, 0, $aItem["attrs"]["MAXLENGTH"])." ...";
						}
					}
					(ereg("content", $href)) ? $target='' : $target='target=\"_blank\"' ;
					$echoStr .= "<a href=\"".$href."\" ".$target." title=\"Lien édité\">".$libelle."</a><br />\n";	
				}	//if ($eKeyValue != ""){		
			}
			
			else{// cas typique typique typique
				 
				if (isFieldTranslate($oRes, $aItem["attrs"]["NAME"])){
					$echoStr .= $translator->getByID($eKeyValue, $_SESSION["id_langue"]);	
				
				}
				else {
					$echoStr .= $eKeyValue;
				}
							
			}
		}
		else{
			$echoStr .= "n/a";
		}				
	}
	
	return $echoStr;

}

function formatItemRaw($oRes, $itemName, $aNodeToSort){
	$itemName = str_replace(".raw", "", $itemName);
	$aItem = getItemByName($aNodeToSort, $itemName);
	$classeName = $oRes->getClasse();
	$eKeyValue = getItemValue($oRes, $itemName);
	//pre_dump($eKeyValue);
	$echoStr = "";
	
	if ($aItem["attrs"]["FKEY"]){ // cas de foregin key			
		$sTempClasse = $aItem["attrs"]["FKEY"];
		$echoStr .= $eKeyValue;
	}// fin fkey
	elseif ($aItem["attrs"]["OPTION"] == "enum"){ // cas enum		
		if (isset($aItem["children"]) && (count($aItem["children"]) > 0)){
			foreach ($aItem["children"] as $childKey => $childNode){
				if($childNode["name"] == "OPTION"){ // on a un node d'option				
					if ($childNode["attrs"]["TYPE"] == "value"){
						if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
							$echoStr .= $childNode["attrs"]["LIBELLE"];
							break;
						}
					} //fin type  == value						
				}
			}
		}		
	} // fin cas enum
	else{ // cas typique
		if ($eKeyValue > -1){ // cas typique typique
			if ($aItem["attrs"]["OPTION"] == "file"){ // cas file
				if (preg_match('/;/msi', $eKeyValue)){
					$aFiles = explode(';', $eKeyValue);
					for ($im=(count($aFiles)-1);$im>=0;$im--){						
						if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$aFiles[$im])){ // le fichier existe
							$eKeyValue=$aFiles[$im];
							break;
						}
					}
				}
				if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue)){ // le fichier existe
					if (eregi("\.gif$",$eKeyValue) || eregi("\.png$",$eKeyValue) || eregi("\.jpg$",$eKeyValue) || eregi("\.jpeg$",$eKeyValue)){ // image					
						if (isset($aItem["children"]) && (count($aItem["children"]) > 0)){
							foreach ($aItem["children"] as $childKey => $childNode){
								$widthMax=$childNode["attrs"]["WIDTH"];
								$heightMax=$childNode["attrs"]["HEIGHT"];
								$aInfos = getimagesize($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue);
								$width = $aInfos[0];
								$height = $aInfos[1];
								if ($widthMax == $heightMax ) {
									if ($width > $height) {
										if ($width > $widthMax) {
											$widthNew = $widthMax;
											$heightNew = ($widthMax * $height) / $width;
										}					
										else {
											if ($height > $heightMax) {
											$heightNew = $heightMax;
											$widthNew = ($heightNew * $width) / $height;
											}
										}
									}
								}					
								elseif ($widthMax > $heightMax) {
									if ($width > $widthMax) {
										$widthNew = $widthMax;
										$heightNew = ($widthMax * $height) / $width;
									}
								}
								else {
									if ($height > $heightMax) {
										$heightNew = $heightMax;
										$widthNew = ($heightNew * $width) / $height;
									}
								}
							}
							$echoStr .= "/custom/upload/".$classeName."/".$eKeyValue;
						}
						else {
							$echoStr .= "/custom/upload/".$classeName."/".$eKeyValue;
						}
					}// fin types supportés par le navigateur									
					else if (isset($aItem["children"]) && (count($aItem["children"]) > 0)){
						foreach ($aItem["children"] as $childKey => $childNode){
							$itemLbl=$childNode["attrs"]["ITEMLIBELLE"];
						}
						$countoption=count($itemLbl);
						foreach ($aItem["children"] as $childKey => $childNode){
							if ($childNode["name"] == "OPTION")  { // on a un node d'option
								if (($countoption==1) && isset($childNode["attrs"]["ITEMLIBELLE"]) && ($childNode["attrs"]["TYPE"]=="link")) {	// on a un node d'option link avec un ITEMLIBELLE
								$tempItemLibelle = getItemByName($aNodeToSort, $childNode["attrs"]["ITEMLIBELLE"]);
								if ($tempItemLibelle != false){
									$tempItemLibelleKeyValue = getItemValue($oRes, $tempItemLibelle["attrs"]["NAME"]);
									if (isset($tempItemLibelleKeyValue) && ($tempItemLibelleKeyValue != "")){
										$libelle = $tempItemLibelleKeyValue;
									}
									elseif (isset($tempItemLibelle["attrs"]["LIBELLE"]) && ($tempItemLibelle["attrs"]["LIBELLE"] != "")){
										$libelle = $tempItemLibelle["attrs"]["LIBELLE"];
									}
									else{
										$libelle = $tempItemLibelle["attrs"]["NAME"];
									}											
								}	
								else{
									if (isset($aItem["attrs"]["LIBELLE"]) && ($aItem["attrs"]["LIBELLE"] != "")){
										$libelle = $aItem["attrs"]["LIBELLE"];
									}
									else{
										$libelle = $itemName;
									}
								}			
								
								}
								else if ($countoption!=1){
									if (isset($aItem["attrs"]["LIBELLE"]) && ($aItem["attrs"]["LIBELLE"] != "")){
										$libelle = $aItem["attrs"]["LIBELLE"];
									}
									else{
										$libelle = $itemName;
									}
								}
								//test sur Type
								$tempLink = "/custom/upload/".$classeName."/".$eKeyValue;
								$tempExt= strtolower(strrchr(basename($tempLink), "."));
								if ($tempExt == ".pdf") {
									$tempFile = basename($tempLink);
									$tempChemin = str_replace($tempFile, "", $tempLink);
									$tempLink = "/modules/utils/telecharger.php?chemin=".$tempChemin."&file=".$tempFile."&";	
									$temptarget = "_self";								
								}	
								else{
									$temptarget = "_blank";
									$tempLink = "/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName;
								}										
							}										
						}
						$echoStr .= $tempLink;
					}// fin types non supportés mais sans child d'option
					else{
						$tempLink = "/custom/upload/".$classeName."/".$eKeyValue;
						$tempFile = basename($tempLink);
						$tempChemin = str_replace($tempFile, "", $tempLink);
						$tempLink = "/modules/utils/telecharger.php?chemin=".$tempChemin."&file=".$tempFile."&";		
						$echoStr .= $tempLink;
					}
				} // if (is_file(
				else{
					$echoStr .= "<!-- fichier manquant : ".$_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue." -->\n";
				}
			}
			else if ($aItem["attrs"]["OPTION"] == "bool"){ // boolean
				if (intval($eKeyValue) == 1){
					$echoStr .= "oui";
				}
				else{
					$echoStr .= "non";
				}						
			}
			else if ($aItem["attrs"]["TYPE"] == "date"){ // date
				// expected : jj/mm/aaaa
				if (preg_match('/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/', $eKeyValue)==1){	
					$jj =   preg_replace('/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/', '$1', $eKeyValue);	
					$mm =   preg_replace('/[0-9]{2}\/([0-9]{2})\/[0-9]{4}/', '$1', $eKeyValue);
					$aaaa = preg_replace('/[0-9]{2}\/[0-9]{2}\/([0-9]{4})/', '$1', $eKeyValue);						
				
				}
				elseif (preg_match('/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/', $eKeyValue)==1){// expected : aaaa/mm/jj
					$aaaa = preg_replace('/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/', '$1', $eKeyValue);	
					$mm =   preg_replace('/[0-9]{4}\/([0-9]{2})\/[0-9]{2}/', '$1', $eKeyValue);
					$jj =   preg_replace('/[0-9]{4}\/[0-9]{2}\/([0-9]{2})/', '$1', $eKeyValue);							
				
				}
				if ($mm != "00"){	//00/00/1999 devient 1999 - 00/02/1998 devient 02/1998						
					if ($jj != "00"){
						$echoStr .= $jj."/";
					}
					$echoStr .= $mm."/";
				}
				$echoStr .= $aaaa;
			}
			else if ($aItem["attrs"]["OPTION"] == "link"){ // cas link
				if ($eKeyValue != ""){
					$href=$eKeyValue;
					$libelle=$eKeyValue;
					if (isset($aItem["children"]) && (count($aItem["children"]) > 0)){									foreach ($aItem["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "link"){// on a un node d'option link
									if (isset($childNode["attrs"]["LIBELLE"]) && ($childNode["attrs"]["LIBELLE"] != "")){
										$libelle =$childNode["attrs"]["LIBELLE"];
									}
								} //fin type  == link				
							}
						}
					}		
					$echoStr .= $href;	
				}	//if ($eKeyValue != ""){		
			}
			else{// cas typique typique typique
				$echoStr .= $eKeyValue;
			}
		}
		else{
			$echoStr .= "n/a";
		}				
	}
	
	return $echoStr;

}

function getItemValue($oO, $itemName){
	$eKeyValue = call_user_func(array($oO, "get_".$itemName));
	if (isItemUTF8($oO, $itemName) == true){           
		$eKeyValue = utf8_decode($eKeyValue);
	}	
				
	return $eKeyValue;
}
// setItemValue
function setItemValue($oRes, $itemName, $itemValue){	
	global $oRes;
	if (isItemUTF8($oRes,$itemName) == true){        
		$itemValue = utf8_encode($itemValue);
	}
	//echo "$"."oRes->set_".$itemName."(".$itemValue.");<br/>";
	eval("$"."oRes->set_".$itemName."($"."itemValue);");
	//call_user_func(array(&$oO, "set_".$itemName),$itemValue); // DEPRECATED
	
	return true;
}

function isItemUTF8($oO, $itemName){
	global $stack;
	$stack = array();
	xmlClassParse($oO->XML);
	$classNodes = $stack[0]["children"];
	unset($stack);	
	$aNode = getItemByName($classNodes, $itemName);
	if (isset($aNode["attrs"]["ENCODING"]) == true){
		if ($aNode["attrs"]["ENCODING"] == "utf8"){           
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}


function controlLinkValue($sLink, $oClass){
	if(ereg("http://", $sLink)){
		// nada, c'est une irl
		$sLink = trim($sLink);
	}
	elseif (is_file($sLink)){
		// nada, fichier en relatif
	}
	elseif (is_file($_SERVER['DOCUMENT_ROOT'].$sLink)){
		// nada, fichier en absolu
	}
	elseif (is_file($_SERVER['DOCUMENT_ROOT']."/frontoffice/".$oClass->getClasse()."/".$sLink)){
		//  fichier en fo dossier de la classe
		$sLink = "/frontoffice/".$oClass->getClasse()."/".$sLink;
	}
	elseif (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$oClass->getClasse()."/".$sLink)){
		//  fichier en custom upload dossier de la classe
		$sLink = "/custom/upload/".$oClass->getClasse()."/".$sLink;
	}
	else{
		$sLink = "";
	}
	return $sLink;
}

function critereIfdisplay($aNode, $oO, $valeur){
	if (!isset($aNode["attrs"]["DISPLAYIF"])){
		return true;	
	}
	elseif($aNode["attrs"]["DISPLAYIF"] == ""){
		return true;
	}
	elseif (getItemValue($oO, $aNode["attrs"]["DISPLAYIF"]) == 1){
		return true;	
	}
	elseif($aNode["attrs"]["DISPLAYIF"] == $aNode["attrs"]["NAME"]){
		if (strlen($valeur) > 0){
			return true;
		}
	}
	else{
		return false;
	}
}

function nouveauGroup($aNode, $precGroup){
	if (!isset($aNode["attrs"]["GROUP"])){
		return false;	
	}
	elseif($aNode["attrs"]["GROUP"] == ""){
		return false;
	}
	elseif($aNode["attrs"]["GROUP"] == $precGroup){
		return false;
	}
	else{
		return $aNode["attrs"]["GROUP"];
	}
}

function finGroup($aNode, $precGroup){
	if ($precGroup == "nogroup"){
		return false;
	}
	elseif (!isset($aNode["attrs"]["GROUP"])){
		return true;	
	}
	elseif($aNode["attrs"]["GROUP"] == ""){
		return true;
	}
	elseif($aNode["attrs"]["GROUP"] != $precGroup){
		return true;
	}
	else{
		return false;
	}
}

function getItemByName($aNodes, $sName){
	if (is_array($aNodes)){
		foreach ($aNodes as $key => $node){
			if ($node["attrs"]["NAME"] == $sName){
				return $node;
			}
		}
	}
	return false;
}

function getItemsByOption($aNodes, $sOption){
	$aReturn = array();
	foreach ($aNodes as $key => $node){
		if ($node["attrs"]["OPTION"] == $sOption){
			$aReturn[] = $node;
		}
	}
	if (count($aReturn) == 0){
		return false;
	}
	else{
		return $aReturn;
	}
}

function getItemsByAttribute($aNodes, $sAttribute){
	$aReturn = array();
	foreach ($aNodes as $key => $node){
		if (isset($node["attrs"][strtoupper($sAttribute)])){
			$aReturn[] = $node;
		}
	}
	if (count($aReturn) == 0){
		return false;
	}
	else{
		return $aReturn;
	}
}

function getItemsByAsso($aNodes, $oObjet){
	$aReturn = array();
	$aClasseAssoc = array();
	foreach ($aNodes as $key => $node){
		if ($node["attrs"]["ASSO"] && (!isset($node["attrs"]["NOSEARCH"]) || $node["attrs"]["NOSEARCH"] == false)) { 
			$sClasseAssoc =  $node["attrs"]["ASSO"]; 
		}
		
	} 
	if ($sClasseAssoc != '') {
		$aClasseAssoc = explode (",", $sClasseAssoc); 
		foreach ($aClasseAssoc as $sClasseAssoc) { 
			$asso = dbGetAssocProps($oObjet, $sClasseAssoc);
			
			if ($asso["out"] != '' && $asso["out"] != $oObjet->getClasse()) {
				 
				$aReturn[$sClasseAssoc] = $asso["out"]; 
			}
		}
	}
	
	$aReturn = array_unique($aReturn);
	
	if (count($aReturn) == 0){
		return false;
	}
	else{
		return $aReturn;
	}
}

function getNodeByName($aNodes, $sName){
	foreach ($aNodes as $key => $node){
		if ($node["name"] == $sName){
			return $node;
		}
	}
	return false;
}

function getFilterPosts($needle="filter"){
	 
	$aReturn = array();
	$aName = array();
	foreach ($_POST as $key => $postedvar){
		if (ereg($needle, $key) == true){
			$aKeyVar = array();
			$aKeyVar[strtolower(str_replace("filter", "", $key))] = $postedvar;
			$aReturn[] = $aKeyVar;
			$aName[] = strtolower(str_replace("filter", "", $key)); 
			//if (ereg("backoffice", $_SERVER['PHP_SELF']))
			if (!preg_match ("/assoFiltre/", $key) )$_SESSION["filter".ucfirst(str_replace("filter", "", $key))] = $postedvar;
			//else 
				//initFilterSession();
				
		}
	} 
	//if (ereg("backoffice", $_SERVER['PHP_SELF'])) {
		// session qui permet de récupérer la recherche quand on revient à la page
		// de liste suite à une modification de fiche
		foreach ($_SESSION as $key => $postedvar){ 
			if (ereg($needle, $key) == true){
				$aKeyVar = array();
				$aKeyVar[strtolower(str_replace("filter", "", $key))] = $postedvar;
				
				if (!in_array(strtolower(str_replace("filter", "", $key)), $aName)) {
					$aReturn[] = $aKeyVar;
				}
				
			}
		} 
//	}
 
	if (count($aReturn) == 0){
		return false;
	}
	else{
		return $aReturn;
	}
}

function getFilterPostsAsso(){
	
	$needle = "assoFiltre";  
	$aReturn = array();
	$aName = array();
	foreach ($_POST as $key => $postedvar){
		if (ereg($needle, $key) == true && $postedvar != -1){
			$aKeyVar = array();
			$aKeyVar[strtolower($key)] = $postedvar;
			$aReturn[] = $aKeyVar;
			$aName[] = strtolower($key); 
			//if (ereg("backoffice", $_SERVER['PHP_SELF']))
			/*$_SESSION["filter".ucfirst(str_replace("filter", "", $key))] = $postedvar;*/
			//else 
				//initFilterSession();
				
		}
	}  
	if (count($aReturn) == 0){
		return false;
	}
	else{
		return $aReturn;
	}
}

function initFilterSession($needle="filter"){
	// DEPRECATED !!
	// session qui permet de récupérer la recherche quand on revient à la page
	// de liste suite à une modification de fiche
	foreach ($_POST as $key => $postedvar){
		if (ereg($needle, $key) == true){
			unset ($_POST[$key]); 
			unset ($_POST[strtolower(str_replace("filter", "", $key))]); 
		}
	} 
	
	foreach ($_SESSION as $key => $postedvar){
		if (ereg($needle, $key) == true){ 
			unset ($_SESSION[$key]); 
			unset ($_SESSION[strtolower(str_replace("filter", "", $key))]); 
		}
	}  
	
	unset ($_SESSION["postFilters"]); 
	
}

function logObjectStatusChange ($obj) {
	global $stack;

	$translator =& TslManager::getInstance();

	$stack = array();
	$sXML = $obj->XML;
	xmlClassParse($sXML);
	$classeName = $stack[0]["attrs"]["NAME"];
	if (isset($stack[0]["attrs"]["LIBELLE"]) && ($stack[0]["attrs"]["LIBELLE"] != ""))
		$classeLibelle = stripslashes($stack[0]["attrs"]["LIBELLE"]);
	else	$classeLibelle = $classeName;
	$aNodeToSort = $stack[0]["children"];

	$tmp_getter = $obj->getGetterStatut();
	$statusDisplay = '';
	$statusAbstract = '';
	foreach ($aNodeToSort as $node) {
		
		if ("get_".$node['attrs']['NAME'] == $tmp_getter) {
			foreach ($node['children'] as $_option) {
				if ($_option['attrs']['VALUE'] == $obj->$tmp_getter()) {
					$keep_status = $_option['attrs']['LIBELLE'];
					break;
				}
			}
			//break;
		} elseif ($node['attrs']['NAME'] == $obj->getDisplay()) {
			// Retrieve display
			$keepField = 'get_'.$node['attrs']['NAME'];
			if (!empty($node['attrs']['FKEY']) && $obj->$keepField() > 0) {
				//FKey display
				$sStatusClasse = $node['attrs']['FKEY'];
				eval("$"."oStatus = new ".$sStatusClasse."(".$obj->$keepField().");");
				$sXML = $oStatus->XML;
				// on vide le tableau stack
				$stack = array();
				xmlClassParse($sXML);	
				$foreignNodeToSort = $stack[0]["children"];
				$keepDisplay = '';
				$keepAbstract = '';
				for ($i=0;$i<count($foreignNodeToSort);$i++){
					if ($foreignNodeToSort[$i]["name"] == "ITEM"){
						if ($foreignNodeToSort[$i]["attrs"]["NAME"] == $oStatus->getDisplay()) {
							// Foreign display
							$keepField = 'get_'.$foreignNodeToSort[$i]["attrs"]["NAME"];
							if ($foreignNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference')
								$keepDisplay .= $translator->getByID($oStatus->$keepField(), $_SESSION['id_langue']);
							elseif ($foreignNodeToSort[$i]["attrs"]["TRANSLATE"] == 'value')
								$keepDisplay .= $translator->getText($oStatus->$keepField(), $_SESSION['id_langue']);
							else	$keepDisplay .= $oStatus->$keepField();
						} elseif ($foreignNodeToSort[$i]["attrs"]["NAME"] == $oStatus->getAbstract()) {
							// Foreign abstract
							$keepField = 'get_'.$foreignNodeToSort[$i]["attrs"]["NAME"];
							if ($foreignNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference')
								$keepAbstract .= $translator->getByID($oStatus->$keepField(), $_SESSION['id_langue']);
							elseif ($foreignNodeToSort[$i]["attrs"]["TRANSLATE"] == 'value')
								$keepAbstract .= $translator->getText($oStatus->$keepField(), $_SESSION['id_langue']);
							else	$keepAbstract .= $oStatus->$keepField();
						}
					}
				}
				$statusDisplay = $keepDisplay.' - '.$keepAbstract;			
			} else {
				// Standard display
				if ($node['attrs']["TRANSLATE"] == 'reference')
					$statusDisplay = $translator->getByID($obj->$keepField(), $_SESSION['id_langue']);
				elseif ($node["attrs"]["TRANSLATE"] == 'value')
					$statusDisplay .= $translator->getText($obj->$keepField(), $_SESSION['id_langue']);
				else	$statusDisplay .= $obj->$keepField();
			}
		} elseif ($node['attrs']['NAME'] == $obj->getAbstract()) {
			// Retrieve abstract
			$keepField = 'get_'.$node['attrs']['NAME'];
			if (!empty($node['attrs']['FKEY']) && $obj->$keepField() > 0) {
				//FKey display
				$sStatusClasse = $node['attrs']['FKEY'];
				eval("$"."oStatus = new ".$sStatusClasse."(".$obj->$keepField().");");
				$sXML = $oStatus->XML;
				// on vide le tableau stack
				$stack = array();
				xmlClassParse($sXML);	
				$foreignNodeToSort = $stack[0]["children"];
				$keepDisplay = '';
				$keepAbstract = '';
				for ($i=0;$i<count($foreignNodeToSort);$i++){
					if ($foreignNodeToSort[$i]["name"] == "ITEM"){
						if ($foreignNodeToSort[$i]["attrs"]["NAME"] == $oStatus->getDisplay()) {
							// Foreign display
							$keepField = 'get_'.$foreignNodeToSort[$i]["attrs"]["NAME"];
							if ($foreignNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference')
								$keepDisplay .= $translator->getByID($oStatus->$keepField(), $_SESSION['id_langue']);
							elseif ($foreignNodeToSort[$i]["attrs"]["TRANSLATE"] == 'value')
								$keepDisplay .= $translator->getText($oStatus->$keepField(), $_SESSION['id_langue']);
							else	$keepDisplay .= $oStatus->$keepField();
						} elseif ($foreignNodeToSort[$i]["attrs"]["NAME"] == $oStatus->getAbstract()) {
							// Foreign abstract
							$keepField = 'get_'.$foreignNodeToSort[$i]["attrs"]["NAME"];
							if ($foreignNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference')
								$keepAbstract .= $translator->getByID($oStatus->$keepField(), $_SESSION['id_langue']);
							elseif ($foreignNodeToSort[$i]["attrs"]["TRANSLATE"] == 'value')
								$keepAbstract .= $translator->getText($oStatus->$keepField(), $_SESSION['id_langue']);
							else	$keepAbstract .= $oStatus->$keepField();
						}
					}
				}
				$statusAbstract = $keepDisplay.' - '.$keepAbstract;			
			} else {
				// Standard display
				if ($node['attrs']["TRANSLATE"] == 'reference')
					$statusAbstract = $translator->getByID($obj->$keepField(), $_SESSION['id_langue']);
				elseif ($node["attrs"]["TRANSLATE"] == 'value')
					$statusAbstract .= $translator->getText($obj->$keepField(), $_SESSION['id_langue']);
				else	$statusAbstract .= $obj->$keepField();
			}
		}
	}
	$log_string = sprintf($translator->getText("Statut fixé à [%s - ID %s] pour [%s - ID %s] [%s]", $_SESSION['id_langue']), $keep_status, $obj->$tmp_getter(), $classeLibelle, $obj->get_id(), $statusDisplay.' / '.$statusAbstract);
	$oLog = new cms_log_statut();
	$oLog->set_bo_users($_SESSION["userid"]);
	$oLog->set_classe(array_search($classeName, $_SESSION['cms_classes']));
	$oLog->set_record($obj->get_id());
	$oLog->set_texte($log_string);
	$oLog->set_cdate(date('Y-m-d H:i:s'));
	$log_id = dbInsertWithAutoKey($oLog);
}

//===============================
// fonction permettant de scanner le dossier class
// récupère le nom de toutes les classes ayant une association $classeName
//===============================
function ScanDirs($Directory, $classeName){
	$aTempClass = array();
	$k=0;
	if (is_dir($Directory) && is_readable($Directory)) {
		if($MyDirectory = opendir($Directory)) {
			while($Entry = readdir($MyDirectory)) {
				if (!is_dir($Directory."/".$Entry)) {
					// fait un tri sur les noms de fichiers susceptibles de ne pas être intéressant
					if ((substr_count($Entry,"cms")) == 0 && /*(substr_count($Entry,$classeName)==0) &&*/ (substr_count($Entry,"avis.class")==0) && (substr_count($Entry,"ressource")==0) && (substr_count($Entry,"__")==0)) {
						$fichierNom=$Directory."/".$Entry;
						$file = fopen($fichierNom, "r");
						while(!feof($file)){
							$buffer= fgets($file);
							$buffer=str_replace('<?', '[balisePHPDeb]', $buffer);
							$buffer=str_replace('?>', '[balisePHPFin]', $buffer);
							
							$nombre = substr_count($buffer,"var $".$classeName.";");
							if ($nombre > 0) {
								$sTempClass = substr($Entry, 0, strlen($Entry)-10);
								$aTempClass[$k]=$sTempClass; // rempli le tableau des noms des classes 
								$k++;
								break;
							}
						}	//while(!feof($file))
					} //if (substr_count($Entry,"cms")) == 0) {
				}//if (!is_dir($Directory."/".$Entry)) {
			}
			closedir($MyDirectory);
		}
	}
	return ($aTempClass);
} //function ScanDirs($Directory, $classeName){


function scanNode($nodeValue, $stack, $oClasse, $classeName, $divName) {
	if (isset($nodeValue["attrs"]["NAME"]) && !ereg("statut|ordre|id|".$classeName."", $nodeValue["attrs"]["NAME"])){ // cas pas statut|ordre|id	
		
		$eKeyValue = getItemValue($oClasse, $nodeValue["attrs"]["NAME"]);
		
		if ($eKeyValue != ""){	//
			if (critereIfdisplay($nodeValue, $aAssoClasse, $eKeyValue) == true){	// displayif			
				debutDiv($divName);
				
				if ($nodeValue["attrs"]["FKEY"]){ // cas de foregin key	
					isKey ($eKeyValue, $nodeValue);
				}
				elseif ($nodeValue["attrs"]["OPTION"] == "enum"){ // cas enum		
					isEnum ($eKeyValue, $nodeValue);
				} 
				else{   
					// cas typique typique
					if ($eKeyValue > -1){ 
						if ($nodeValue["attrs"]["OPTION"] == "filename"){ // cas filename
							isFilename ($eKeyValue, $nodeValue);
						}
						else if ($nodeValue["attrs"]["OPTION"] == "file"){ // cas file
							isFilePlus ($eKeyValue, $nodeValue, $classeName);
						}
						else if ($nodeValue["attrs"]["OPTION"] == "bool"){ // boolean
							isBool ($eKeyValue);		
						} 
						else if ($nodeValue["attrs"]["TYPE"] == "date"){ // date
							isDate ($eKeyValue);		
						}
						else if ($nodeValue["attrs"]["OPTION"] == "link"){ // cas link
							isLink ($eKeyValue, $nodeValue);
						}
						else if ($nodeValue["attrs"]["OPTION"] == "filedir"){ // cas link
							isFiledir( eKeyValue, $nodeValue);
						}
						else{// cas typique typique typique	
							echo $eKeyValue;
						}
					}
					else{
						echo "";
					}				
				}			
				finDiv($nodeValue["attrs"]["NAME"]);
				
			} // if (critereIfdisplay($nodeValue, $aAssoClasse, $eKeyValue) == true){
		}
	}
} // fin fonction

function isKey ($eKeyValue, $nodeValue) {
	$classeName = $nodeValue["attrs"]["FKEY"];
	if ($eKeyValue > -1){
		eval("$"."oTemp = new ".$classeName."(".$eKeyValue.");");
		echo "<a href=\"../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php?id=".$oTemp->get_id()."\">";
		echo getItemValue($oTemp, $oTemp->getDisplay());
		echo "</a>";
	}
	else{
		echo "";
	}
}

function isEnum ($eKeyValue, $nodeValue) {
	if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
		foreach ($nodeValue["children"] as $childKey => $childNode){
			if($childNode["name"] == "OPTION"){ // on a un node d'option				
				if ($childNode["attrs"]["TYPE"] == "value"){
					if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
						echo $childNode["attrs"]["LIBELLE"];
						break;
					}
				} //fin type  == value				
			}
		}
	}		
}



function isFilename ($eKeyValue, $nodeValue) {
	if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
		foreach ($nodeValue["children"] as $childKey => $childNode){
			if ($eKeyValue != "") {
				$nameFile=$eKeyValue;
				$nameFileType=$childNode["attrs"]["TYPE"];
			}
		}
	} 
}


function isFilePlus ($eKeyValue, $nodeValue, $classeName) {
	
	if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue)){ // le fichier existe
		if (eregi("\.gif$",$eKeyValue) || eregi("\.png$",$eKeyValue) || eregi("\.jpg$",$eKeyValue) || eregi("\.jpeg$",$eKeyValue)){ // image					
			//echo $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$tempAsso."/".$eKeyValue."<br>";
			ResizeImg($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue, 400,100, $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue);
			if(!unlink($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue)) {
				$status .= 'Erreur : Impossible de renommer le fichier temporaire '. $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue;
			}
			echo "<img src=\"/custom/upload/".$classeName."/".$eKeyValue."\" border=\"0\" alt=\"".$eKeyValue."\" />";
			echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a>\n";
		}
		else if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
			
			foreach ($nodeValue["children"] as $childKey => $childNode){
				$itemLbl=$childNode["attrs"]["ITEMLIBELLE"];
			}
			$countoption=count($itemLbl);
			foreach ($nodeValue["children"] as $childKey => $childNode){
				
				if ($childNode["name"] == "OPTION")  { // on a un node d'option
					if (($countoption==1) && isset($childNode["attrs"]["ITEMLIBELLE"]) && ($childNode["attrs"]["TYPE"]=="link")) {	// on a un node d'option link avec un ITEMLIBELLE
						echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" target=\"_blank\" title=\"".$libelle."\">".$libelle."</a>\n";
						echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a><br />\n";
					}
					else if ($countoption!=1){
						/*if ($nameFile!="" && $childNode["attrs"]["TYPE"]==$nameFileType) {
							$nameFile = $nameFile;
						}
						else {
							$nameFile = $eKeyValue;
						}*/
						echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" target=\"_blank\" title=\"".$eKeyValue."\">".$eKeyValue."</a><br />\n";
					}
				}
			}
		}
	} 
	
}


function isBool ($eKeyValue) {
	if (intval($eKeyValue) == 1){
		echo "oui";
	}
	else{
		echo "non";
	}	 
}

function isDate ($eKeyValue) {

	if (preg_match('/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/', $eKeyValue)==1){	
		$jj =   preg_replace('/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/', '$1', $eKeyValue);	
		$mm = 	preg_replace('/[0-9]{2}\/([0-9]{2})\/[0-9]{4}/', '$1', $eKeyValue);
		$aaaa = preg_replace('/[0-9]{2}\/[0-9]{2}\/([0-9]{4})/', '$1', $eKeyValue);						
	
	}
	elseif (preg_match('/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/', $eKeyValue)==1){// expected : aaaa/mm/jj
		$aaaa = preg_replace('/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/', '$1', $eKeyValue);	
		$mm =   preg_replace('/[0-9]{4}\/([0-9]{2})\/[0-9]{2}/', '$1', $eKeyValue);
		$jj =   preg_replace('/[0-9]{4}\/[0-9]{2}\/([0-9]{2})/', '$1', $eKeyValue);							
	
	}
	if ($mm != "00"){	//00/00/1999 devient 1999 - 00/02/1998 devient 02/1998						
		if ($jj != "00"){
			echo $jj."/";
		}
		echo $mm."/";

	}
	echo $aaaa; 
}
	
function isLink ($eKeyValue, $nodeValue) {
	if ($eKeyValue != ""){
		$href=$eKeyValue;
		$libelle=$eKeyValue;
		if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){									
		foreach ($nodeValue["children"] as $childKey => $childNode){
				if($childNode["name"] == "OPTION"){ // on a un node d'option				
					if ($childNode["attrs"]["TYPE"] == "link"){// on a un node d'option link
						if (isset($childNode["attrs"]["LIBELLE"]) && ($childNode["attrs"]["LIBELLE"] != "")){
							$libelle =$childNode["attrs"]["LIBELLE"];
						}
					} //fin type  == link				
				}
			}
		}	
		
		echo "<a href=\"".$href."\" target=\"_blank\" title=\"Lien édité\">".$libelle."</a><br />\n";		
	}	//if ($eKeyValue != ""){		
}		



function isFiledir ($eKeyValue, $nodeValue) {
	if ($eKeyValue != ""){
		if (is_file($_SERVER['DOCUMENT_ROOT'].$eKeyValue)){ // le fichier existe
			if (eregi("\.gif$",$eKeyValue) || eregi("\.png$",$eKeyValue) || eregi("\.jpg$",$eKeyValue) || eregi("\.jpeg$",$eKeyValue)){ // image					
				if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){									
					foreach ($nodeValue["children"] as $childKey => $childNode){
						if($childNode["name"] == "OPTION"){ // on a un node d'option	
							if ($afficheClasse !="" && $valueAfficheClasse=="non") {
								echo "";
								$afficheClasse = "";
							}
							else {
								if ($childNode["attrs"]["RESIZE"]=="true" && $eCountResizeImg >= $childNode["attrs"]["BEGIN"]) {
									ResizeImg($_SERVER['DOCUMENT_ROOT'].$eKeyValue, $childNode["attrs"]["WIDTH"],$childNode["attrs"]["WIDTH"]+100, $_SERVER['DOCUMENT_ROOT'].$eKeyValue);
									$ligneImg="<img src=\"".$eKeyValue."\" width=\"".$childNode["attrs"]["WIDTH"]."\" height=\"100%\" title=\"Image édité\" border=\"0\"><br>\n";						
								}
								else {
									$eCountResizeImg++;
									$aSize = getimagesize("../..".$eKeyValue);
									$ligneImg="<img src=\"".$eKeyValue."\" ".$aSize[3]." title=\"Image édité\" border=\"0\"><br>\n";						
								}
								
								if ($childNode["attrs"]["ACTION"]=="lien" || isset($childNode["attrs"]["ACTIONSRC"])) {
									$linkImg = $ligneImg;	
								}
								else {
									echo $ligneImg;
								}
								
							}	//if ($afficheClasse !="" && $valueAfficheClasse=="non") {
							if ($linkImg!="") {
								if (isset($childNode["attrs"]["ACTIONSRC"])) {
									if ($childNode["attrs"]["ACTIONSRCTYPE"] == "file") {
										eval ("$"."eKeyValueFile = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRC"]."();");	
										
									}
									else if ($childNode["attrs"]["ACTIONSRCTYPE"] == "link") {
										eval ("$"."eKeyValueBool = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRCBOOL"]."();");	
										eval ("$"."eKeyValueLink = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRC"]."();");		
									}
								}
								
							}
							
							
						} //if($childNode["name"] == "OPTION"){ // on a un node d'option	
					}
					if ($eKeyValueFile!="" && $eKeyValueLink=="") { 
						echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$tempAsso."/".$eKeyValueFile."\" target=\"_blank\" title=\"".$eKeyValueLink."\">".$linkImg."</a>\n";
						echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$tempAsso."/".$eKeyValueFile."\" title=\"Télécharger le fichier : '".$eKeyValueFile."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValueFile."\" /></a>\n";
					}
					else if ($eKeyValueFile=="" && $eKeyValueLink!="" &&  $eKeyValueBool==1) {
						echo "<a href=\"".$eKeyValueLink."\" target=\"_blank\" title=\"Lien édité\">".$linkImg."</a>\n";	
						$showLink = false;
					}		
					else {
						echo $linkImg."\n";	
					}
				}
				
			}			
		}
	} // if (is_file(
}

function debutDiv ($divName) {
	echo "<div class=\"".$divName."Label\" id=\"".$divName."Label\">\n";								
	echo "<div class=\"".$divName."Value\" id=\"".$divName."Value\">\n";
}

function debutDivs ($divName) {
	echo "<div class=\"".$divName."Labels\" id=\"".$divName."Labels\">\n";								
	echo "<div class=\"".$divName."Values\" id=\"".$divName."Values\">\n";
}

function finDiv ($divName) {
	$tempStyles .= ".".replaceBadCarsInStr($divName)."{\n";
	$tempStyles .= "}\n";
	$tempStyles .= ".".replaceBadCarsInStr($divName)."Label{\n";
	$tempStyles .= "}\n";
	$tempStyles .= ".".replaceBadCarsInStr($divName)."Value{\n";
	$tempStyles .= "}\n";
	
	echo "</div>\n";
	echo "</div>\n";
}


function getInclude($oRes, $itemName,  $aNodeToSort) {
	$file = "http://".$_SERVER['HTTP_HOST']."/".$itemName.".php";
	
	$f = fopen($file,'r');
	$sTexte="";
	if ($f){
		while(!feof($f)) {
			$sTexte.=fgets($f);
		}
	
	fclose($f);
	}
	return $sTexte;
}

function getTagPosts($aPOST, $needle="fAssoCms_tag_Classe_"){

	$aReturn = array();
	$aName = array();
	foreach ($aPOST as $key => $postedvar){ 
		if (preg_match("/".$needle."/", $key) == true){  
			$aReturn[] = $postedvar; 
		}
	} 
	if (count($aReturn) == 0){
		return false;
	}
	else{
		return $aReturn;
	}
}


//fonction pour les exports des inscrits

function get_list_inscrits($sListeInscrit) { 
	$aResult = explode("\n", $sListeInscrit);   
	return ($aResult);
}
 
function export_list_inscrits($sRep, $sListeInscrit, $aChamps, $sTypeExport)
{ 
 
	$aResult = get_list_inscrits($sListeInscrit); 
	 
	// indice des fichiers
	
	$sDate = date("Y")."".date("m")."".date("d");
	$sFilename = $sRep."export_inscrit_".$sTypeExport.".csv"; 
	if (sizeof($aResult) == 0) $sDate=0; 
	if (!$handle = fopen($sFilename, 'w+')) {
	   echo "Impossible d'ouvrir le fichier ($filename)";
	   exit;
	}
		
	// Assurons nous que le fichier est accessible en écriture
	if (is_writable($sFilename)) {
		// ok tout va bien le fichier est accessible en écriture
	} else {
	   echo "Le fichier $sFilename n'est pas accessible en écriture.";
	}
	
	//pre_dump($aChamps);
	
	$sContent ="";  
	for ($i=0; $i<sizeof($aChamps); $i++) { 
		if (strtolower($aChamps[$i]) != "id") 
			$sContent.=$aChamps[$i].";";
	}
	$sContent.= ""."\n";
	// requete d'insertion pour la nouvelle BDD
	// echo sizeof($aResult);
	for ($k=0; $k < sizeof($aResult); $k++)
	{  
		$sContent.= "".$aResult[$k]."";  
		$sContent.= ""."\n"; 
	
	}  
	if (fwrite($handle, $sContent) == FALSE) {
		   echo "Impossible d'écrire dans le fichier (".$sFilename.")";
		   exit;
	 }
	// fermeture des fichiers
	fclose($handle);
	//print("br><div class='arbo'>Export réussi :: $sFilename</div>");
	//echo $sContent;
	// on renvoie le nombre de fichiers d'export créés
	return $sDate; 
}


function chars2htmlcodes($strAccents){

$htmlcodes = array("&lsquo;", 
"&rsquo;", 
"&sbquo;", 
"&ldquo;", 
"&rdquo;", 
"&bdquo;", 
"&dagger;", 
"&Dagger;", 
"&permil;", 
"&lsaquo;", 
"&rsaquo;", 
"&spades;", 
"&clubs;", 
"&hearts;", 
"&diams;", 
"&oline;", 
"&larr;", 
"&uarr;", 
"&rarr;", 
"&darr;", 
"&trade;", 
"&quot;", 
"&amp;", 
"&frasl;", 
"&lt;", 
"&gt;", 
"&ndash;", 
"&mdash;", 
"&nbsp;", 
"&iexcl;", 
"&cent;", 
"&pound;", 
"&curren;", 
"&yen;", 
"&brvbar;",
"&sect;", 
"&uml;", 
"&copy;", 
"&ordf;", 
"&laquo;", 
"&not;", 
"&shy;", 
"&reg;", 
"&macr;",
"&deg;", 
"&plusmn;", 
"&sup2;", 
"&sup3;", 
"&acute;", 
"&micro;", 
"&para;", 
"&middot;", 
"&cedil;", 
"&sup1;", 
"&ordm;", 
"&raquo;", 
"&frac14;", 
"&frac12;", 
"&frac34;", 
"&iquest;", 
"&Agrave;", 
"&Aacute;", 
"&Acirc;", 
"&Atilde;", 
"&Auml;", 
"&Aring;", 
"&AElig;", 
"&Ccedil;", 
"&Egrave;", 
"&Eacute;", 
"&Ecirc;", 
"&Euml;", 
"&Igrave;", 
"&Iacute;", 
"&Icirc;", 
"&Iuml;", 
"&ETH;", 
"&Ntilde;", 
"&Ograve;", 
"&Oacute;", 
"&Ocirc;", 
"&Otilde;", 
"&Ouml;", 
"&times;", 
"&Oslash;", 
"&Ugrave;", 
"&Uacute;", 
"&Ucirc;", 
"&Uuml;", 
"&Yacute;", 
"&THORN;", 
"&szlig;", 
"&agrave;", 
"&aacute;", 
"&acirc;", 
"&atilde;", 
"ä", 
"å", 
"æ", 
"ç", 
"è", 
"é", 
"&ecirc;", 
"&euml;", 
"&igrave;", 
"&iacute;", 
"&icirc;", 
"&iuml;", 
"&eth;", 
"&ntilde;", 
"&ograve;", 
"&oacute;", 
"&ocirc;", 
"&otilde;", 
"&ouml;", 
"&divide;", 
"&oslash;", 
"&ugrave;", 
"&uacute;", 
"&ucirc;", 
"&uuml;", 
"&yacute;", 
"&thorn;", 
"&yuml;");

$replaces = array("‘", 
"’", 
"‚", 
"“", 
"”", 
"„", 
"†", 
"‡", 
"‰", 
"‹", 
"›", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"™", 
"\"", 
"&", 
"/", 
"<", 
">", 
"–", 
"—", 
" ", 
"¡", 
"¢", 
"£", 
"¤", 
"¥", 
"¦", 
"§", 
"¨", 
"©", 
"ª", 
"«", 
"¬", 
"­", 
"®", 
"¯", 
"°", 
"±", 
"²", 
"³", 
"´", 
"µ", 
"¶", 
"·", 
"¸", 
"¹", 
"º", 
"»", 
"¼", 
"½", 
"¾", 
"¿", 
"À", 
"Á", 
"Â", 
"Ã", 
"Ä", 
"Å", 
"Æ", 
"Ç", 
"È", 
"É", 
"Ê", 
"Ë", 
"Ì", 
"Í", 
"Î", 
"Ï", 
"Ð", 
"Ñ", 
"Ò", 
"Ó", 
"Ô", 
"Õ", 
"Ö", 
"×", 
"Ø", 
"Ù", 
"Ú", 
"Û", 
"Ü", 
"Ý", 
"Þ", 
"ß", 
"à", 
"á", 
"â", 
"ã", 
"ä", 
"å", 
"æ", 
"ç", 
"è", 
"é", 
"ê", 
"ë", 
"ì", 
"í", 
"î", 
"ï", 
"ð", 
"ñ", 
"ò", 
"ó", 
"ô", 
"õ", 
"ö", 
"÷", 
"ø", 
"ù", 
"ú", 
"û", 
"ü", 
"ý", 
"þ", 
"ÿ");

return str_replace($replaces, $htmlcodes, $strAccents);
}

function getFileByName($Directory, $keyword){ 
	$aTempResults = array();
	$k=0;
	if (is_dir($Directory) && is_readable($Directory)) {
		if($MyDirectory = opendir($Directory)) {
			while($Entry = readdir($MyDirectory)) {
				
				if (!is_dir($Directory."/".$Entry)) { 
					//echo strtolower($Entry)." ".strtolower($keyword);
					// fait un tri sur les noms de fichiers susceptibles de ne pas être intéressant
					if ((substr_count(strtolower($Entry),strtolower($keyword))) > 0 ) { 
						//echo "_____________________ok";
						$aTempResults[]=$Entry; // rempli le tableau des noms des classes 
						
					} //if (substr_count($Entry,"cms")) == 0) { 
					//echo "<br />";
				}//if (!is_dir($Directory."/".$Entry)) {
			}
			closedir($MyDirectory);
			
		}
	}
	return ($aTempResults);
} //function ScanDirs($Directory, $classeName){


//===============================
// fonction permettant de scanner le dossier filemanager
// afin de retourner tous les noms des fichiers contenus
//===============================
function ScanForFilemanager($Directory){
	$aTempFile = array();
	$k=0;  
	if (is_dir($Directory) && is_readable($Directory)) { 
		if($MyDirectory = opendir($Directory)) { 
			 while (false !== ($file = readdir($MyDirectory))) {
				if ($file != "." && $file != ".." && !ereg("CVS", $file)) { 
					array_push($aTempFile, "$file");
				}
			}
			closedir($MyDirectory);
		}
	}
	return ($aTempFile);
} //function ScanDirs($Directory, $classeName){



function chars2htmlcodesAccents($strHTML){

$htmlcodes = array( 
"&Agrave;", 
"&Aacute;", 
"&Acirc;", 
"&Atilde;", 
"&Auml;", 
"&Aring;", 
"&AElig;", 
"&Ccedil;", 
"&Egrave;", 
"&Eacute;", 
"&Ecirc;", 
"&Euml;", 
"&Igrave;", 
"&Iacute;", 
"&Icirc;", 
"&Iuml;", 
"&ETH;", 
"&Ntilde;", 
"&Ograve;", 
"&Oacute;", 
"&Ocirc;", 
"&Otilde;", 
"&Ouml;", 
"&times;", 
"&Oslash;", 
"&Ugrave;", 
"&Uacute;", 
"&Ucirc;", 
"&Uuml;", 
"&Yacute;", 
"&THORN;", 
"&szlig;", 
"&agrave;", 
"&aacute;", 
"&acirc;", 
"&atilde;", 
"&auml;", 
"&aring;", 
"&aelig;", 
"&ccedil;", 
"&egrave;", 
"&eacute;", 
"&ecirc;", 
"&euml;", 
"&igrave;", 
"&iacute;", 
"&icirc;", 
"&iuml;", 
"&eth;", 
"&ntilde;", 
"&ograve;", 
"&oacute;", 
"&ocirc;", 
"&otilde;", 
"&ouml;", 
"&divide;", 
"&oslash;", 
"&ugrave;", 
"&uacute;", 
"&ucirc;", 
"&uuml;", 
"&yacute;", 
"&thorn;", 
"&yuml;",
"&hellip;",
"&bull;",
"&deg;"
);

$replaces = array(
"À", 
"Á", 
"Â", 
"Ã", 
"Ä", 
"Å", 
"Æ", 
"Ç", 
"È", 
"É", 
"Ê", 
"Ë", 
"Ì", 
"Í", 
"Î", 
"Ï", 
"Ð", 
"Ñ", 
"Ò", 
"Ó", 
"Ô", 
"Õ", 
"Ö", 
"×", 
"Ø", 
"Ù", 
"Ú", 
"Û", 
"Ü", 
"Ý", 
"Þ", 
"ß", 
"à", 
"á", 
"â", 
"ã", 
"ä", 
"å", 
"æ", 
"ç", 
"è", 
"é", 
"ê", 
"ë", 
"ì", 
"í", 
"î", 
"ï", 
"ð", 
"ñ", 
"ò", 
"ó", 
"ô", 
"õ", 
"ö", 
"÷", 
"ø", 
"ù", 
"ú", 
"û", 
"ü", 
"ý", 
"þ", 
"ÿ",
"...",
"•",
"°"
);
 
return str_replace($replaces, $htmlcodes, $strHTML);
} 

function getListeChampsForObject($o0){
	if(!isset($o0)){
		return false;	
	}

	global $stack;
	$laListeChamps = array();
	
	/*if(isset($o0->XML_inherited)){ // surtout pas !!!!!! sinon on fait des requetes avec les champs hérités en pollution des champs parents
		$sXML = $o0->XML_inherited;
	}
	else*/
	if(isset($o0->XML)){
		$sXML = $o0->XML;
	}
	elseif (method_exists($o0, 'getListeChamps')){
		$laListeChamps = $o0->getListeChamps();
		return $laListeChamps;
	}
	else{
		// là, on n'est pas dans la merde
	}

	xmlClassParse($sXML);
	
	$aNodeToSort = $stack[0]["children"];
	$classePrefixe = $stack[0]["attrs"]["PREFIX"];
	
	for ($i=0;$i<count($aNodeToSort);$i++){
		if (($aNodeToSort[$i]["attrs"]["TYPE"] == "int")){
			$sType = 'entier';			
		}
		elseif (($aNodeToSort[$i]["attrs"]["TYPE"] == "decimal") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "float")) {
			$sType = 'decimal';
		}
		elseif(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
			$sType = 'text';
		}
		elseif($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp" || $aNodeToSort[$i]["attrs"]["TYPE"] == "datetime"){ // timestamp
			$sType = 'date_formatee_timestamp';
		}
		elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") {
			$sType = 'text';
		}
		else{ // date
			$sType = 'date_formatee';
		}
		
		$sNom = trim($aNodeToSort[$i]["attrs"]["NAME"]);
		
		if ($sNom!=''){		
			$oDbChamp = new dbChamp(ucfirst($classePrefixe).'_'.$sNom, $sType, 'get_'.$sNom, 'set_'.$sNom);
			$laListeChamps[] = $oDbChamp;
		}
	}		
	
	// en cas de fail de la méthode XML, on prend l'ancien getter
	if (count($laListeChamps)==0){
		if (method_exists($oO, 'getListeChamps')){
			$laListeChamps = $oO->getListeChamps();
		}
		else{
			//pre_dump($oO);
			//die();
		}
	}
	
	return $laListeChamps;
	
}

function xmlClassParse($sXML){
	global $stack;

	xmlStringParse($sXML);
	
	if(isset($stack[0]['attrs']['NAME'])){	
		//backup le stack	
		$stackBack = $stack;
		
		$sPathSurcharge = $_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/'.$stack[0]['attrs']['NAME'].'.class.xml';
		
		if(is_file($sPathSurcharge)){
		
			$stack = array();		
			// le parse
			xmlFileParse($sPathSurcharge);
			
			//parcours le $stack et altère le $stackBack 
			
			// noeud class
			foreach($stack[0]['attrs'] as $attrKey => $attrVal){
				$stackBack[0]['attrs'][$attrKey] = $attrVal;			
			}	
			
			// noeuds item
			foreach($stack[0]['children'] as $childKey => $childNode){			
				
				foreach ($stackBack[0]['children'] as $bakChildKey => $backChildNode){
					if ($backChildNode['attrs']['NAME'] == $childNode['attrs']['NAME']){
						foreach($childNode['attrs'] as $attrKey => $attrVal){
							$stackBack[0]['children'][$bakChildKey]['attrs'][$attrKey] = $attrVal;			
						}
						// noeuds options
						// <option type="link" 
						if(is_array($stackBack[0]['children'][$bakChildKey]['children'])){// le back a des options nodes
							if(is_array($childNode['children'])){// la surcharge a des options nodes
								foreach($childNode['children'] as $opKey => $opNode){
									$bMatched=false;
									foreach ($stackBack[0]['children'][$bakChildKey]['children'] as $bakOpKey => $backOpNode){
										if ($backOpNode['attrs']['TYPE'] == $opNode['attrs']['TYPE']){
											foreach($opNode['attrs'] as $attrKey => $attrVal){
												$stackBack[0]['children'][$bakChildKey]['children'][$bakOpKey]['attrs'][$attrKey] = $attrVal;											
											}
											$bMatched=true;
											break;
										}
									}
									if ($bMatched==false){ // cette option est nouvelle									
										$stackBack[0]['children'][$bakChildKey]['children'][]=$opNode;									
									}							
								}
							}
						}
						// le back n'a pas d'options nodes
						else{
							//echo ' pas de options nodes ';
							$stackBack[0]['children'][$bakChildKey]['children'] = $childNode['children'];
						}
						break;
					}
				}
			}		
			//retourne le res
			$stack = $stackBack;
			
		}
	}
	else{
		//echo 'parse fail';
	}
	return $stack;
}

// functions cache
function cacheClasseList($classeName) {
	
	eval("$"."a".ucfirst($classeName)."Objects = array();"); 
	//$aMainClasseObjects = array(); // object caching
	eval("global $"."a".ucfirst($classeName)."Objects;");  
	$aMainClasseObjects = array();
	$sRequete = "SELECT * FROM ".$classeName." ";
	 //echo "<br>sRequete".ucfirst($sRequete)."<br>";
	$aObject = dbGetObjectsFromRequete($classeName, $sRequete);
	foreach($aObject as $k => $oObject){	
		$aMainClasseObjects[$oObject->get_id()] = $oObject;
		//eval("$"."a".ucfirst($classeName)."Objects[".$oObject->get_id()."] = ".$oObject.";");  
	} 
	//echo sizeof($aMainClasseObjects);
	 eval("$"."a".ucfirst($classeName)."Objects = "."$"."aMainClasseObjects;"); 
	 //echo "<br>nouvelle table a".ucfirst($classeName)."Objects<br><br>";
}

function cacheClasseXML($classeName){
	cacheClasseXMLAndObjects($classeName);
}



function cacheClasseXMLAndObjects($classeName){
	$aClasseXML = array(); // object caching
	global $aClasseXML; 
	
	$aClasseFields = array(); // object caching
	global $aClasseFields; 
	
	eval("$"."oTemp = new ".$classeName."();"); 
	
	$sXML = $oTemp->XML;
	 
	unset($stack);
	$stack = array();
	global $stack;
	xmlClassParse($sXML);
	
	$foreignName = $stack[0]["attrs"]["NAME"];
	$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
	$foreignNodeToSort = $stack[0]["children"]; 
	
	
	$aClasseXML["NAME"] = $stack[0]["attrs"]["NAME"];
	$aClasseXML["PREFIX"] =  $stack[0]["attrs"]["PREFIX"]; 
	$aClasseXML["DISPLAY"] =  $oTemp->getDisplay(); 
	$aClasseXML["ABSTRACT"] =  $oTemp->getAbstract(); 
	

	$tempIsAbstractForeign = false;
	$tempForeignAbstract = "";
	$tempIsDisplayForeign = false;
	$tempForeignDisplay = "";
	$bCms_site = false;

	if (is_array($foreignNodeToSort)) { 
		foreach ($foreignNodeToSort as $nodeId => $nodeValue) {	
			$aClasseFields[$nodeId]["NAME"]  = $nodeValue["attrs"]["NAME"] ;
			if (isset( $nodeValue["attrs"]["LIBELLE"])) $aClasseFields [$nodeId]["LIBELLE"]  = $nodeValue["attrs"]["LIBELLE"] ;
			else $aClasseFields [$nodeId]["LIBELLE"]  = $nodeValue["attrs"]["NAME"] ; 
			if (isset( $nodeValue["attrs"]["TYPE"])) $aClasseFields [$nodeId]["TYPE"]  = $nodeValue["attrs"]["TYPE"] ;
			if (isset( $nodeValue["attrs"]["LIST"])) $aClasseFields [$nodeId]["LIST"]  = $nodeValue["attrs"]["LIST"] ;
			if (isset( $nodeValue["attrs"]["ORDER"])) $aClasseFields [$nodeId]["ORDER"]  = $nodeValue["attrs"]["ORDER"] ;
			
			// fkey
			if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY_CACHE"] == 'true') {
				$aClasseFields[$nodeId]["FKEY"]  = $nodeValue["attrs"]["FKEY"] ;
				cacheClasseList($nodeValue["attrs"]["FKEY"]); 
				
			}
		}
	} 
}

function getCacheListFields(){
	global $aClasseFields; 
	$aClasseFieldsList = array(); 
	
	$ka = 0; 
	foreach ($aClasseFields as $nodeId => $oField) { 
		if (isset($oField["LIST"]) && $oField["LIST"] == "true") {
			$aClasseFieldsList[$ka]["NAME"] = $oField["NAME"];
			$aClasseFieldsList[$ka]["LIBELLE"] = $oField["LIBELLE"];
			$aClasseFieldsList[$ka]["ORDER"] = $oField["ORDER"];
			$aClasseFieldsList[$ka]["FKEY"] = $oField["FKEY"];
			$ka++;
		}
	}
	
	return $aClasseFieldsList;
}

function doesFieldExist($aListeChamps, $sField){	
	foreach($aListeChamps as $chK => $oChamp){		
		if (strtolower(trim($oChamp->NomBD)) == strtolower(trim($sField))){
			return true;
		}
	}
	return false;
}

function getCorrectField ($aListeChamps, $prefix, $sField) {
	if (doesFieldExist($aListeChamps, $prefix.'_'.$sField))
		return $prefix.'_'.$sField;			
	if (doesFieldExist($aListeChamps, $sField))
		return $sField;
	return null;
}

function getCorrectTable ($oTemp) {
	if (strtolower($oTemp->getTable()) != strtolower($oTemp->getClasse())) {
		return $oTemp->getTable();
	}
	else {
		return $oTemp->getClasse();
	}
}


function getValidHref ($link , $text) {
	if ($link != '') {
		if (!ereg("^http|ftp|https]://.*", $link) ) $link = "http://".$link ; 
	
		if (!preg_match ( "/content/", $link) ) {
				$href = ' href="'.$link.'" target="blank" ';
		}
		else {
			$href = 'href="'.$link.'"';
		} 
		 
		$href =  '<a "'.$href.'">'.$text.'</a>';
	}
	else {
		$href = '';
	}
	
	return $href;
	
}
?>
