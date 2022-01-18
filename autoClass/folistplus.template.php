<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
$currentClasseName=$classeName;

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

///////////////////////////////////////////////
// sponthus 29/11/2005
//
// 	Affichage d'une table générique
///////////////////////////////////////////////

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

cacheClasseXML($classeName);
cacheClasseList($classeName);

if (!isset($idSite)){
// param du referer --------------------------------------------
	if ((isset($_REQUEST['refer'])) and ($_REQUEST['refer'] != "")){
		$referUrl = $_REQUEST['refer'];
	}
	else{
		$referUrl = $_SERVER['PHP_SELF'];
		$referUrl = "http://".$_SERVER['HTTP_HOST']."/content/afg/produits.php?id=10";
	}
	
	$idSite = path2idside($db, $referUrl);
} //-------------------------------------------------------------

$oSite = new Cms_site($idSite);
$rep = $oSite->get_rep(); 
$oLg = new Cms_langue($oSite->get_langue());
$slg = strtolower($oLg->get_libellecourt());
//------------------------------------------------------------------------------------------------------


$bDebug = false;
$sMessage="";
// objet 
eval("$"."oRes = new ".$classeName."();");
$sXML = $oRes->XML;
//unset($stack);
$stack = array();
xmlClassParse($sXML);
$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];




foreach ($aNodeToSort as $key => $node){	
	if ($node["name"] == "LANGPACK" ){ 
		if (isset($node["attrs"]["LANG"])) {
			if ($node["attrs"]["LANG"] == $slg) {
				$classLang = $node["attrs"]["LANG"];
				$langPack = $node["children"];
			}
		}
		else {
			$classLang = $node["attrs"]["LANG"];
			$langPack = $node["children"];
		}
		
		
	}
}	
if($oRes) {	
	$fh = fopen($template,'r');
	$sBodyHTML="";
	if ($fh){
		while(!feof($fh)) {
			$sBodyHTML.=fgets($fh);
		}
		fclose($fh);
		
		
		//scan les item affichés
		$aItems = array();
		preg_match_all  ("/<autoclass item=\"([^\"]+)\">/", $sBodyHTML, $aItems);
		$aItems = $aItems[1];
		
		// scan les display
		$aDisplays = array();
		preg_match_all  ("/<autoclass displayList=\"([^\"]+)\">/", $sBodyHTML, $aDisplays);
		$aDisplays = $aDisplays[1];
		
		// scan les display
		$aDisplayItems = array();
		preg_match_all  ("/<autoclass display=\"([^\"]+)\">/", $sBodyHTML, $aDisplayItems);
		$aDisplayItems = $aDisplayItems[1];
		
		// scan les display if
		$aDisplaysIf = array();
		preg_match_all  ("/<autoclass displayif=\"([^\"]+)\" value=\"([^\"]+)\">/", $sBodyHTML, $aDisplaysIf);
		$aDisplaysIf2 = $aDisplaysIf[1];
		$aValues = $aDisplaysIf[2];
		
		
		// scan les display none if
		$aDisplaysNoneIf = array();
		preg_match_all  ("/<autoclass displaynoneif=\"([^\"]+)\" value=\"([^\"]+)\">/", $sBodyHTML, $aDisplaysNoneIf);
		$aDisplaysNoneIf2 = $aDisplaysNoneIf[1];
		$aNoneValues = $aDisplaysNoneIf[2];
	}
}

$sFilledBodyHTML = $sBodyHTML;
// test les displays de pagination / filters / fermer
// variables en SESSIONS

for ($i=0;$i<count($aDisplaysIf2);$i++){

	$itemName = $aDisplaysIf2[$i];
	$itemValue = $aValues[$i];
	if (preg_match("/^[^\.]+\.[^\.]+$/msi",$itemName) == true){//local.foreign
		$compoItems = explode ("[.]", $itemName);
		if ($compoItems[1] == "pagination") {
			$_SESSION['paginationDisplay_'.$compoItems[0].''] = $itemValue;
			$sFilledBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sFilledBodyHTML);

		}
		if ($compoItems[1] == "filters") {
			$_SESSION['filtersDisplay_'.$compoItems[0].''] = $itemValue;
			$sFilledBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sFilledBodyHTML);

		}
		if ($compoItems[1] == "fermer") {
			$_SESSION['fermerDisplay_'.$compoItems[0].''] = $itemValue;
			$sFilledBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sFilledBodyHTML);

		}
		else {
		}
	}
	elseif(preg_match("/^[^\.]+\.asso\.[^\.]+\.[^\.]+$/msi", $itemName) == true){// local.asso.foreign
		
		$compoItems = explode ("[.]", $itemName);
		if ($compoItems[3] == "pagination") {
			$_SESSION['paginationDisplay_'.$compoItems[2].''] = $itemValue;
			$sFilledBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sFilledBodyHTML);
		}	
		else if ($compoItems[3] == "filters") {
			$_SESSION['filtersDisplay_'.$compoItems[2].''] = $itemValue;
			$sFilledBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sFilledBodyHTML);
		}
		else if ($compoItems[3] == "fermer") {
			$_SESSION['fermerDisplay_'.$compoItems[2].''] = $itemValue;
			$sFilledBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sFilledBodyHTML);
		}
		else{
			// cas autre -> split
		}
		
	}
	
}

if($oRes) {
 
include("list.process.php"); 
?>
<!--<script src="/backoffice/cms/js/openBrWindow.js" type="text/javascript" language="javascript"></script>-->
<?php
//Filters

if (isset($_SESSION['filtersDisplay_'.$classeName.'']) && $_SESSION['filtersDisplay_'.$classeName.'']!= "" && $_SESSION['filtersDisplay_'.$classeName.'']== 1) {
	// on n'affiche rien 
}
else {
	echo "<div class='filters'>\n";
	include("list.filters.php");
	echo "</div>\n";
}

//Pagination
if (isset($_SESSION['paginationDisplay_'.$classeName.'']) && $_SESSION['paginationDisplay_'.$classeName.'']!= "" && $_SESSION['paginationDisplay_'.$classeName.'']== 1) {
	// on n'affiche rien 
}
else {
	echo "<div class='pagination'>".$pager->bandeau."</div>\n";
	$tempStyles .= ".pagination"."{\n";
	$tempStyles .= "}\n";
}



// s'il y a des enregistrements à afficher
  if(newSizeOf($aListe_res)>0) {
	eval("$"."oRes = new ".$classeName."();");
	$sXML = $oRes->XML;
	unset($stack);
	$stack = array();
	xmlClassParse($sXML);
	
	$classeName = $stack[0]["attrs"]["NAME"];
	$classePrefixe = $stack[0]["attrs"]["PREFIX"];
	$aNodeToSort = $stack[0]["children"]; 



// liste
$sBodyHTML = $sFilledBodyHTML; 
for($k=0; $k<newSizeOf($aListe_res); $k++) {
	$oRes = $aListe_res[$k]; 
	$sFilledBodyHTML = $sBodyHTML;
	$aItemsToList = array();
	
	for ($i=0;$i<count($aDisplays);$i++){
		$itemName = $aDisplays[$i];
		if (preg_match("/^[^\.]+\.[^\.]+$/msi",$itemName) == true){//local.foreign
			$sFilledBodyHTML = str_replace("<autoclass displayList=\"".$itemName."\">", displayItemForeign($oRes, $itemName, $aNodeToSort), $sFilledBodyHTML);
		}
		elseif (preg_match("/^[^\.]+$/msi",$itemName) == true){//local.foreign
			$sFilledBodyHTML = str_replace("<autoclass displayList=\"".$itemName."\">", displayItem($oRes, $itemName, $aNodeToSort), $sFilledBodyHTML);
		}
		else{
			// cas autre -> split
		}
		
	} // A FAIRE
	
	for ($i=0;$i<count($aDisplayItems);$i++){
		$itemName = $aDisplayItems[$i];
		if (preg_match("/^[^\.]+\.[^\.]+$/msi",$itemName) == true){//local.foreign
			$sFilledBodyHTML = str_replace("<autoclass display=\"".$itemName."\">", displayItemForeign($oRes, $itemName, $aNodeToSort), $sFilledBodyHTML);
		}
		
		elseif (preg_match("/^[^\.]+$/msi",$itemName) == true){//local.foreign
			$sFilledBodyHTML = str_replace("<autoclass display=\"".$itemName."\">", displayItem($oRes, $itemName, $aNodeToSort), $sFilledBodyHTML);
		}
		else{
			// cas autre -> split
		}
		
	} // A FAIRE
	
	for ($i=0;$i<count($aDisplaysIf2);$i++){
		$itemName = $aDisplaysIf2[$i];
		$itemValue = $aValues[$i];
		if (preg_match("/^[^\.]+$/msi",$itemName) == true){
			$sFilledBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", displayItemIf($oRes, $itemName, $itemValue, $aNodeToSort), $sFilledBodyHTML);
		}
		elseif(preg_match("/^[^\.]+\.asso\.[^\.]+$/msi", $itemName) == true){
			$sFilledBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", displayItemAssoIf($oRes, $itemName, $itemValue, $aNodeToSort), $sFilledBodyHTML);
		}
	}
	
	for ($i=0;$i<count($aItems);$i++){
		$itemName = $aItems[$i]; 
		if(preg_match("/^[^\.]+\.in\.[^\.]+\.[^\.]+$/msi",$itemName) == true){
			$sFilledBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItemIn($oRes, $itemName, $aNodeToSort), $sFilledBodyHTML);
		}
		elseif(preg_match("/^[^\.]+\.raw$/msi",$itemName) == true){
			$sFilledBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItemRaw($oRes, $itemName, $aNodeToSort), $sFilledBodyHTML);
		}
		elseif (preg_match("/^[^\.]+\.[^\.]+$/msi",$itemName) == true){			
			$sFilledBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItemForeign($oRes, $itemName, $aNodeToSort), $sFilledBodyHTML);
		}
		elseif(preg_match("/^[^\.]+$/msi",$itemName) == true){ 
			$sFilledBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItem($oRes, $itemName, $aNodeToSort), $sFilledBodyHTML);
		}
		elseif(preg_match("/^[^\.]+\.in\.[^\.]+$/msi", $itemName) == true){// local.in.foreign
			// stocke le nom des items listes
				$aItemsToList[][0] = $itemName; 
				$aItemsToList[][1] = "in"; 
		}
		elseif(preg_match("/^[^\.]+\.asso\.[^\.]+$/msi", $itemName) == true){// local.asso.foreign
		// stocke le nom des items listes
			$aItemsToList[][0] = $itemName; 
			$aItemsToList[][1] = "asso"; 
		}
		else{
		// cas inconnu -> skip
		}
		
	}
	
	for ($i=0;$i<count($aDisplaysNoneIf2);$i++){
		$itemName = $aDisplaysNoneIf2[$i];
		$itemValue = $aNoneValues[$i];
		 
		if (preg_match("/^[^\.]+$/msi",$itemName) == true){
			$sFilledBodyHTML = str_replace("<autoclass displaynoneif=\"".$itemName."\" value=\"".$itemValue."\">", displayNoneItemIf($oRes, $itemName, $itemValue, $aNodeToSort), $sFilledBodyHTML);
		}
		else{
			// cas autre -> split
		}
		
	}
		
	if (count($aItemsToList) > 0) {
		for ($j=0;$j<count($aItemsToList);$j++){
			$nameItemToList = $aItemsToList[$j][0];
			$j++;
			$typeAssoToList = $aItemsToList[$j][1];
			if ($j%2 == 1) {
				$sBodyHTMLSplit = explode("<autoclass item=\"".$nameItemToList."\">", $sFilledBodyHTML);
				echo $sBodyHTMLSplit[0];
				formatItemList($oRes, $nameItemToList, $aNodeToSort, $db, $typeAssoToList);
				$sFilledBodyHTML = $sBodyHTMLSplit[1];
			}
			
		}
		
		echo $sBodyHTMLSplit[1];
	}
	else {
		echo $sFilledBodyHTML;
	}
	
	


	}
} else {
	
 
	if (isset($langPack)){ 
		$norecords = getNodeByName($langPack, "NORECORDS"); 
		if (isset($norecords["cdata"]) && $norecords["cdata"] == "vide"){
			echo "";
		}
		else if (isset($norecords["cdata"])){
			echo "<div>".stripslashes($norecords["cdata"])."</div>";
		}
		else{
			echo "<div>Aucun enregistrement à afficher</div>";
		}
	}
	else{
		echo "<div>Aucun enregistrement à afficher</div>";
	}

}
if (isset($_SESSION['fermerDisplay_'.$currentClasseName.'']) && $_SESSION['fermerDisplay_'.$currentClasseName.'']!= "" && $_SESSION['fermerDisplay_'.$currentClasseName.'']== 1) {
	// on n'affiche rien
	
}
else {
	echo "<!-- styles -- sample --\n";
	echo "<style type=\"text/css\">\n";
	echo $tempStyles;
	echo ".fermer{\n";
	echo "}\n";
	echo "</style>\n";
	echo "-- styles -- sample -->\n";
}
} else {
	die("Erreur ".$classeName." non trouvé");
}
?>