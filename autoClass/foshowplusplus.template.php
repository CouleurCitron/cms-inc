<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}


//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

////////////////////////////////////////////////////////

// Fiche visu

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');


if ($id == "") {
	$id=$_GET['id'];
	if(!isset($id)) $id=$_POST['id'];
}


// objet 
eval("$"."oRes = new ".$classeName."($"."id);");
unset($stack);
$stack = array();
$sXML = $oRes->XML;
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

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
		$aItemsToList = array();
		preg_match_all  ("/<autoclass item=\"([^\"]+)\">/", $sBodyHTML, $aItems);
		$aItems = $aItems[1];
		
		// scan les display
		$aDisplays = array();
		preg_match_all  ("/<autoclass display=\"([^\"]+)\">/", $sBodyHTML, $aDisplays);
		$aDisplays = $aDisplays[1];
		
		
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
		
		// scan les includes
		$aIncludes = array();
		preg_match_all  ("/<autoclass include=\"([^\"]+)\">/", $sBodyHTML, $aIncludes);
		$aIncludes = $aIncludes[1]; 
		
		
		for ($i=0;$i<count($aDisplays);$i++){
			$itemName = $aDisplays[$i];
			if (preg_match("/^[^\.]+\.[^\.]+$/msi",$itemName) == true){//local.foreign
				$sBodyHTML = str_replace("<autoclass display=\"".$itemName."\">", displayItemForeign($oRes, $itemName, $aNodeToSort), $sBodyHTML);
			}
			else if (preg_match("/^[^\.]+$/msi",$itemName) == true){//local.foreign
				$sBodyHTML = str_replace("<autoclass display=\"".$itemName."\">", displayItem($oRes, $itemName, $aNodeToSort), $sBodyHTML);
			}
			elseif(preg_match("/^[^\.]+\.asso\.[^\.]+$/msi", $itemName) == true){
				$sBodyHTML = str_replace("<autoclass display=\"".$itemName."\">", displayItemList($oRes, $itemName, $aNodeToSort), $sBodyHTML);
			}
			else{
				// cas autre -> split
			}
			
		} // A FAIRE
		
		
		
		// test les displays de pagination / filters / fermer
		// variables en SESSIONS
		for ($i=0;$i<count($aDisplaysIf2);$i++){
			$itemName = $aDisplaysIf2[$i];
			$itemValue = $aValues[$i];
			
			if (preg_match("/^[^\.]+\.[^\.]+$/msi",$itemName) == true){//local.foreign
				$compoItems = explode ("[.]", $itemName);
				if ($compoItems[1] == "pagination") {
					eval("$"."paginationDisplay_".$compoItems[0]." = $"."itemValue;");
					//eval("echo $"."paginationDisplay_".$compoItems[0].";");
					$sBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sBodyHTML);
				}
				if ($compoItems[1] == "filters") {
					eval("$"."filtersDisplay_".$compoItems[0]." = $"."itemValue;");
					//eval("echo $"."paginationDisplay_".$compoItems[0].";");
					$sBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sBodyHTML);
				}
				if ($compoItems[1] == "fermer") {
					eval("$"."fermerDisplay_".$compoItems[0]." = $"."itemValue;");
					//eval("echo $"."paginationDisplay_".$compoItems[0].";");
					$sBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sBodyHTML);
				}
				else {
					//
				}
			}
			 
			elseif (preg_match("/^[^\.]+$/msi",$itemName) == true){
				$sBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", displayItemIf($oRes, $itemName, $itemValue, $aNodeToSort), $sBodyHTML);
			}
			/*elseif(ereg("^[^\.]+\.asso\.[^\.]+$", $itemName) == true){
				$sBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", displayItemAssoIf($oRes, $itemName, $itemValue, $aNodeToSort), $sBodyHTML);
			} */
			/* // A FAIRE
			elseif(preg_match("/^[^\.]+$/msi",$itemName) == true){//local
				$sBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItem($oRes, $itemName, $aNodeToSort), $sBodyHTML);
			}
			elseif(ereg("^[^\.]+\.in\.[^\.]+$", $itemName) == true){// local.in.foreign
			// stocke le nom des items listes
				$aItemsToList[][0] = $itemName; 
				$aItemsToList[][1] = "in"; 
			}*/
			elseif(preg_match("/^[^\.]+\.asso\.[^\.]+\.[^\.]+$/msi", $itemName) == true){// local.asso.foreign
			
				$compoItems = explode ("[.]", $itemName);
				if ($compoItems[3] == "pagination") {
					$_SESSION['paginationDisplay_'.$compoItems[2].''] = $itemValue;
					$sBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sBodyHTML);
				}	
				else if ($compoItems[3] == "filters") {
					$_SESSION['filtersDisplay_'.$compoItems[2].''] = $itemValue;
					$sBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sBodyHTML);
				}
				else if ($compoItems[3] == "fermer") {
					$_SESSION['fermerDisplay_'.$compoItems[2].''] = $itemValue;
					$sBodyHTML = str_replace("<autoclass displayif=\"".$itemName."\" value=\"".$itemValue."\">", "", $sBodyHTML);
				}					
				
				/*$aItemsToList[][0] = $itemName; 
				$aItemsToList[][1] = "asso"; */
				
			}
			else{
				// cas autre -> split
			}
			
		}
		for ($i=0;$i<count($aDisplaysNoneIf2);$i++){
			$itemName = $aDisplaysNoneIf2[$i];
			$itemValue = $aNoneValues[$i];
			 
			if (preg_match("/^[^\.]+$/msi",$itemName) == true){
				$sBodyHTML = str_replace("<autoclass displaynoneif=\"".$itemName."\" value=\"".$itemValue."\">", displayNoneItemIf($oRes, $itemName, $itemValue, $aNodeToSort), $sBodyHTML);
			}
			else{
				// cas autre -> split
			}
			
		}
		
		// traite les item display
		$aItemsToList = array();
		
		// traite les item 
		for ($i=0;$i<count($aItems);$i++){
			$itemName = $aItems[$i];
			if(preg_match("/^[^\.]+\.in\.[^\.]+\.[^\.]+$/msi",$itemName) == true){ // local.in.foreign.champs
				$sBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItemIn($oRes, $itemName, $aNodeToSort), $sBodyHTML);
			}
			elseif (preg_match("/^[^\.]+\.[^\.]+$/msi",$itemName) == true){//local.foreign
				$sBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItemForeign($oRes, $itemName, $aNodeToSort), $sBodyHTML);
			}
			elseif(preg_match("/^[^\.]+$/msi",$itemName) == true){//local
				$sBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItem($oRes, $itemName, $aNodeToSort), $sBodyHTML);
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
				// cas autre -> split
			}
			
		}
		
		//traite les includes
		for ($i=0;$i<count($aIncludes);$i++){
			  $itemName = $aIncludes[$i];
			if(preg_match("/^[^\.]+$/msi",$itemName) == true){//local
				eval("$"."_SESSION['include_".$classeName."_id'] =".$id.";"); 
				$sBodyHTML = str_replace("<autoclass include=\"".$itemName."\">", getInclude($oRes, $itemName, $aNodeToSort) , $sBodyHTML);
				
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
					$sBodyHTMLSplit = explode("<autoclass item=\"".$nameItemToList."\">", $sBodyHTML);
					echo $sBodyHTMLSplit[0];
					formatItemList($oRes, $nameItemToList, $aNodeToSort, $db, $typeAssoToList);
					$sBodyHTML = $sBodyHTMLSplit[1];
				}
				
			}
			
			echo $sBodyHTMLSplit[1];
		}
		else {
			echo $sBodyHTML;
		}
	}
}

?>