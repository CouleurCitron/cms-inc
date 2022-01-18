<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once("cms-inc/include_cms.php");
include_once("cms-inc/include_class.php");

if ($eKeyValue > -1) {
	$oTemp = cacheObject($sTempClasse, $eKeyValue);

	// Fkey display in record sheet
	$sXML = $oTemp->XML;

	unset($stack);
	$stack = array();
	xmlClassParse($sXML);


	$foreignName = $stack[0]["attrs"]["NAME"];
	$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
	$foreignNodeToSort = $stack[0]["children"];

	$tempIsAbstractForeign = false;
	$tempForeignAbstract = "";
	$tempIsDisplayForeign = false;
	$tempForeignDisplay = "";
	$bCms_site = false;
	$tempIsAbstractEnum = false;
	$tempIsDisplayEnum = false;
	$tempIsAbstractAnonymous = false;
	$tempIsDisplayAnonymous = false;

	$is_linkable = ($aNodeToSort[$i]["attrs"]["NOLINK"] == 'true' ? false : true);
	if (is_array($foreignNodeToSort)) { 
		foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
			if ($nodeValue["name"] == 'ITEM' && $nodeValue["attrs"]["FKEY"] == 'bo_users' && !empty($nodeValue["attrs"]["RESTRICT"]) && $_SESSION["rank"] != 'ADMIN') {
				// Cas over mega pas typique du tout
				// Cloisonnement sur administrateur loggué
				// Evite de remonter vers des éléments parents non cloisonnés
				$meth = 'get_'.$nodeValue["attrs"]["NAME"];
				if ($oTemp->$meth() != $_SESSION["userid"])
					$is_linkable = false;
			}
			if ($nodeValue["attrs"]["NAME"] == $oTemp->getAbstract()) {	
				$valueAbstract = $nodeValue["attrs"]["NAME"];
				$typeAbstract = $nodeValue["attrs"]["TYPE"];	 
				// Translation data
				$translateAbstract = $nodeValue["attrs"]["TRANSLATE"]; 			
				// end translation data
				if (!empty($foreignNodeToSort[$nodeId]["attrs"]["ANONYMOUS"])){
					// cas anonymous
					$fld_check = 'get_'.$foreignNodeToSort[$nodeId]["attrs"]["ANONYMOUS"];
					if ($oTemp->$fld_check() == 'Y') {
						$tempIsAbstractAnonymous = true;
					}
				} // fin cas anonymous
				if (isset($nodeValue["attrs"]["FKEY"]) &&	$nodeValue["attrs"]["FKEY"] != '' && $nodeValue["attrs"]["FKEY"] != 'null'	&& class_exists($nodeValue["attrs"]["FKEY"])){
					$tempIsAbstractForeign = true;
					$tempForeignAbstract = $nodeValue["attrs"]["FKEY"]; 
					//break;
					
					eval("$"."oForeignAbstract = new ".$tempForeignAbstract."();");
					if (!is_null($oForeignAbstract->XML_inherited))
						$sXML = $oForeignAbstract->XML_inherited;
					else	$sXML = $oForeignAbstract->XML;
					//$sXML = $oTemp->XML;
					 
					unset($stack);
					$stack = array();
					xmlClassParse($sXML);
					 
					$abstractNodeToSort = $stack[0]["children"]; 

					if(is_array($abstractNodeToSort)){ 
						foreach ($abstractNodeToSort as $nodeId => $nodeValue) {	
							if ($nodeValue["attrs"]["NAME"] == strval($oForeignAbstract->getAbstract())) {
								$valueAbstract = $nodeValue["attrs"]["NAME"];
								$typeAbstract = $nodeValue["attrs"]["TYPE"];  
								$translateAbstract = $nodeValue["attrs"]["TRANSLATE"]; 
							}	
						}
					}
					
				}
				else if ($nodeValue["attrs"]["OPTION"] == "enum") { // cas enum 
					$tempIsAbstractEnum = true;	
					$tempForeignAbstract = $nodeValue["attrs"]["NAME"];  
					if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)) {
						eval("$"."enum".ucfirst($nodeValue["attrs"]["NAME"])." = array()".";");
						foreach ($nodeValue["children"] as $childKey => $childNode){
							if ($childNode["name"] == "OPTION"){ // on a un node d'option	 
								if ($childNode["attrs"]["TYPE"] == "value"){ 
									eval("$"."enum".ucfirst($nodeValue["attrs"]["NAME"])."[".intval($childNode["attrs"]["VALUE"])."] "." = \"".stripslashes($childNode["attrs"]["LIBELLE"])."\"; "); 
								}  			
							}
						}
					}	
				}
			}
			if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){	
				$valueDisplay = $nodeValue["attrs"]["NAME"];
				$typeDisplay = $nodeValue["attrs"]["TYPE"]; 
				$typeForeignDisplay  = $nodeValue["attrs"]["TYPE"]; 	
				// translation data
				// Added by Luc - 13 oct. 2009
				$translateDisplay = $nodeValue["attrs"]["TRANSLATE"]; 			
				// end translation data
				if (!empty($foreignNodeToSort[$nodeId]["attrs"]["ANONYMOUS"])){
					// cas anonymous
					$fld_check = 'get_'.$foreignNodeToSort[$nodeId]["attrs"]["ANONYMOUS"];
					if ($oTemp->$fld_check() == 'Y') {
						$tempIsDisplayAnonymous = true;
					}
				} // fin cas anonymous
				if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
					$tempIsDisplayForeign = true;
					$tempForeignDisplay = $nodeValue["attrs"]["FKEY"]; 
					eval("$"."oForeignDisplay = new ".$tempForeignDisplay."();");
					if (!is_null($oForeignDisplay->XML_inherited))
						$sXML = $oForeignDisplay->XML_inherited;
					else	$sXML = $oForeignDisplay->XML;
					//$sXML = $oTemp->XML;
					 
					unset($stack);
					$stack = array();
					xmlClassParse($sXML);
					 
					$displayNodeToSort = $stack[0]["children"]; 

					if(is_array($displayNodeToSort)){ 
						foreach ($displayNodeToSort as $nodeId => $nodeValue) {	
							if ($nodeValue["attrs"]["NAME"] == strval($oForeignDisplay->getDisplay())) {
								$valueForeignDisplay = $nodeValue["attrs"]["NAME"];
								$typeForeignDisplay = $nodeValue["attrs"]["TYPE"]; 
								$translateDisplay = $nodeValue["attrs"]["TRANSLATE"]; 
							}	
						}
					}
					
					//break;
				} else if ($nodeValue["attrs"]["OPTION"] == "enum") { // cas enum
					$tempIsDisplayEnum = true;	
					$tempForeignDisplay = $nodeValue["attrs"]["NAME"];  
					if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)) {
						eval("$"."enum".ucfirst($nodeValue["attrs"]["NAME"])." = array()".";");
						foreach ($nodeValue["children"] as $childKey => $childNode) {
							if ($childNode["name"] == "OPTION"){ // on a un node d'option	 
								if ($childNode["attrs"]["TYPE"] == "value") { 
									eval("$"."enum".ucfirst($nodeValue["attrs"]["NAME"])."[".intval($childNode["attrs"]["VALUE"])."] "." = \"".stripslashes($childNode["attrs"]["LIBELLE"])."\"; "); 
								}  			
							}
						}
					}	
				}
			}
			if (strtolower(stripslashes($nodeValue["attrs"]["FKEY"])) == "cms_site") { 
				$bCms_site = true;
				$cms_site_name = $nodeValue["attrs"]["NAME"];
				if (isset($nodeValue["attrs"]["DEFAULT"])&& $nodeValue["attrs"]["DEFAULT"]!="") {
					$default_cms_site_name = $nodeValue["attrs"]["DEFAULT"];
				} else	$default_cms_site_name = "";
			}

		}
	}
	// echo "Classe : ".$oTemp->getClasse()."<br/>";
	// echo "Display : ".$oTemp->getDisplay()."<br/>";
	// echo "Abstract : ".$oTemp->getAbstract()."<br/>";
	// echo "IsDisplayForeign : ".$tempIsDisplayForeign."<br/>";
	// echo "translateDisplay : ".$translateDisplay."<br/>";
	// echo "DEF_APP_USE_TRANSLATIONS : ".DEF_APP_USE_TRANSLATIONS."<br/>";

//	if (is_file("../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php") == true) {
	// allow heritage from core classes towards custom classes...
	$showLink = getUILink($oTemp->getClasse(), 'show');
	
	if (is_file($_SERVER['DOCUMENT_ROOT'].$showLink)) {

		if ($is_linkable) {
			
			//if (is_file("../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php"))
				echo "<a href=\"".$showLink."?id=".$oTemp->get_id()."\">";
			//else	echo "<a href=\"../cms/".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php?id=".$oTemp->get_id()."\">";
		}
		//eval("$"."eKeyValueTemp = $"."oTemp->get_".strval($oTemp->getDisplay())."();");

		if ($tempIsDisplayForeign) {
			eval('$eForeignId='.'$'.'oTemp->get_'.strval($oTemp->getDisplay()).'();');
			$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
			eval("$"."itemValue = $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
		} elseif ($tempIsDisplayAnonymous) {
			// Force anonymous field
			if ($_SESSION['login'] == 'ccitron') {
				if ($tempIsDisplayEnum)   
					eval("$"."itemValue = \"[".$translator->getText("anonyme")."]\".$"."enum".ucfirst($tempForeignDisplay)."[".$eKeyValueTemp."];");  
				else	eval("$"."itemValue = \"[".$translator->getText("anonyme")."]\".$"."oTemp->get_".strval($oTemp->getDisplay())."();");
			} else	$itemValue = "*** ".$translator->getText("anonyme")." ***";
		} elseif ($tempIsDisplayEnum) {    
			eval("$"."itemValue = $"."enum".ucfirst($tempForeignDisplay)."[".$eKeyValueTemp."];");  
		} else	eval("$"."itemValue = $"."oTemp->get_".strval($oTemp->getDisplay())."();");

		if (DEF_APP_USE_TRANSLATIONS && $translateDisplay != '') {
			if ($typeForeignDisplay == "int") {				
				if ($translateDisplay == 'reference'){
					//$itemValue = $translator->getByID($itemValue);
					$refKeyValue = $itemValue;
					$itemValue = $translator->getByID($itemValue);
					if ($itemValue==''){ // cas pas de traduc pour la langue en cours
						foreach($translator->getActiveLangIds() as $kL => $IdL){ // on cherche dans toutes les langues
							$itemValue = $translator->getByID($refKeyValue, $IdL);
							if ($itemValue!=''){
								break;
							}
						}
					}
				}
			} elseif ($typeForeignDisplay == "enum") {
			 	if ($translateDisplay == "value")
			 		$itemValue = $translator->getText($itemValue);
			} else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";
		}
		// end translation data
		$itemValueShort = substr($itemValue, 0, 50);
		if (strlen($itemValue) > 50 ) 
			$itemValueShort .= " ... ";
		echo strip_tags($itemValueShort, '<br><b><i><strong><em>');
		
		$itemValue = "";
		//eval("$"."eKeyValueTemp = $"."oTemp->get_".strval($oTemp->getAbstract())."();");
			if ($oTemp->getDisplay() != $oTemp->getAbstract() && $oTemp->getAbstract() != 'statut') {
				echo " - ";
				if ($tempIsAbstractForeign) {
					eval('$eForeignId=$oTemp->get_'.strval($oTemp->getAbstract()).'();');
					$oForeignAbstract = cacheObject($tempForeignAbstract, $eForeignId);
					eval("$"."itemValue = $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
			}
			elseif ($tempIsAbstractAnonymous) {
					// Force anonymous field
					if ($_SESSION['login'] == 'ccitron') {
						if ($tempIsAbstractEnum)   
							eval("$"."itemValue = \"[".$translator->getText("anonyme")."]\".$"."enum".ucfirst($tempForeignAbstract)."[".$eKeyValueTemp."];");  
						else	eval("$"."itemValue = \"[".$translator->getText("anonyme")."]\".$"."oTemp->get_".strval($oTemp->getAbstract())."();");
					} else	$itemValue = "*** ".$translator->getText("anonyme")." ***";
			}
			elseif ($tempIsAbstractEnum) {    
					eval("$"."itemValue = $"."enum".ucfirst($tempForeignAbstract)."[".$eKeyValueTemp."];");  
			}
			else{
				if (method_exists($oTemp, 'get_'.strval($oTemp->getAbstract()))){
					eval("$"."itemValue = $"."oTemp->get_".strval($oTemp->getAbstract())."();");
				}
				else{
					$itemValue = '';
				}
			}
		}
		// translation data
		// Added by Luc - 13 oct. 2009
		if (DEF_APP_USE_TRANSLATIONS && $translateAbstract) { 
			if ($typeAbstract == "int") {
				if ($translateAbstract == 'reference'){
					//$itemValue = $translator->getByID($itemValue);
					$refKeyValue = $itemValue;
					$itemValue = $translator->getByID($itemValue);
					if ($itemValue==''){ // cas pas de traduc pour la langue en cours
						foreach($translator->getActiveLangIds() as $kL => $IdL){ // on cherche dans toutes les langues
							$itemValue = $translator->getByID($refKeyValue, $IdL);
							if ($itemValue!=''){
								break;
							}
						}
					}
				}
			}
			elseif ($typeAbstract == "enum") {
			 	if ($translateAbstract == "value"){
			 		$itemValue = $translator->getText($itemValue);
				}
			} 
			else{
				echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";
			}
		}
		
		// end translation data
		$itemValueShort = substr($itemValue, 0, 50);
                //$itemValueShort = strip_tags(substr($itemValue, 0, 50), '<br><b><i><strong><em>');
		if (strlen($itemValue) > 50 ) 
			$itemValueShort .= " ... ";
		echo strip_tags($itemValueShort, '<br><b><i><strong><em>');


		if ($is_linkable)
			echo "</a>";
	} else {
		echo getItemValue($oTemp, $oTemp->getDisplay());
	}
                       // end fkey display in record sheet
} else	echo "n/a";


?>

