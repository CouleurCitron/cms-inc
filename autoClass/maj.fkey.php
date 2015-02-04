<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
$aOps=array();
$direct_call = false;
$exit_display = false;
$switchValue = '';

global $bCms_site;
global $classeName;
global $default_cms_site_name;
global $isRecursive;
global $aOps;
global $sql;

if (empty($aNodeToSort)) {
	 
	// AJAX or direct call
	// Added by Luc
	$direct_call = true;
	include_once($_SERVER['DOCUMENT_ROOT']."/include/cms-inc/include_cms.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/include/cms-inc/include_class.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

	// translation engine
	//if (DEF_APP_USE_TRANSLATIONS) {
		$translator =& TslManager::getInstance();
		$langpile = $translator->getLanguages();
	//}
	
	if (is_get('display')){
		$display = $_GET['display'];	
		$displayField = $_GET['field'];	
	}

	if (is_get('class') && is_get('id') && is_get('field')) {
		// get an instance of currently displaying class
		if ($_GET['id'] == -1){
			eval("$"."oRes = new ".$_GET['class']."();");
		}
		else{
			eval("$"."oRes = new ".$_GET['class']."(".$_GET['id'].");");
		}
		
		if (!is_null($oRes->XML_inherited))
			$sXML = $oRes->XML_inherited;
		else	$sXML = $oRes->XML;

		//$sXML = $oRes->XML;
		xmlClassParse($sXML);

		$classeName = $stack[0]["attrs"]["NAME"];
		if (isset($stack[0]["attrs"]["LIBELLE"]) && ($stack[0]["attrs"]["LIBELLE"] != ""))
			$classeLibelle = stripslashes($stack[0]["attrs"]["LIBELLE"]);
		else	$classeLibelle = $classeName;

		$classePrefixe = $stack[0]["attrs"]["PREFIX"];
		$aNodeToSort = $stack[0]["children"];

		/**
		 * Classe récursive sur elle même
		 * Check sur la la classe associée aussi
		 * Guillaume / 2014
		 */
		$isRecursive = false;
		if(isset($stack[0]["attrs"]["ORDONABLE"]) && isset($stack[0]["attrs"]["DEPTH"]) && isset($stack[0]["attrs"]["PARENT"])) {
			$isRecursive = true;
		}

		for ($i=0; $i<count($aNodeToSort); $i++) {
			if ($aNodeToSort[$i]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["NAME"] == $_GET['field']) {
				$classe = $aNodeToSort[$i]["attrs"]["FKEY"];
				eval("$"."oRes = new ".$classe."();");
				if (!is_null($oRes->XML_inherited))
					$sXML = $oRes->XML_inherited;
				else	$sXML = $oRes->XML;
				xmlClassParse($sXML);

				if(isset($stack[0]["attrs"]["ORDONABLE"]) && isset($stack[0]["attrs"]["DEPTH"]) && isset($stack[0]["attrs"]["PARENT"])) {
					$isRecursive = true;
				}
			}
		}

		if ($_GET['id'] == -1){
			eval("$"."oRes = new ".$_GET['class']."();");
		}
		else{
			eval("$"."oRes = new ".$_GET['class']."(".$_GET['id'].");");
		}
		if (!is_null($oRes->XML_inherited))
			$sXML = $oRes->XML_inherited;
		else	$sXML = $oRes->XML;

		//$sXML = $oRes->XML;
		xmlClassParse($sXML);

		$currentStackClassParent = $stack[0]["attrs"]["PARENT"];

		// get the linkage field node

		for ($i=0; $i<count($aNodeToSort); $i++) {
			if ($aNodeToSort[$i]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["NAME"] == $_GET['field'])
				break;
		}
		
		if (is_as_get('forceValue')){ // cas liste reloadée en ajax après ajout new value, cad: forceValue != undefined
			$eKeyValue = $_GET['forceValue'];
		} else { // cas typique
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
		}
		
		 

	} else	$exit_display = true;	
}
// end AJAX or direct call



$isFkeyFilter = false;
if (isset($aNodeToSort[$i]["attrs"]["FKEY"]) && ($aNodeToSort[$i]["attrs"]["FKEY"] != "") && ($aNodeToSort[$i]["attrs"]["FKEY"] != 'null') && isset($aNodeToSort[$i]["attrs"]["ISFKEYFILTER"]) && $aNodeToSort[$i]["attrs"]["ISFKEYFILTER"])
	$isFkeyFilter = true;

if ($aNodeToSort[$i]["attrs"]["FKEY_SWITCH"]) {
	// switchable fkey
	// find type switch field
	$found = false;
	for ($j=0; $j<count($aNodeToSort); $j++) {
		if ($aNodeToSort[$j]["name"] == "ITEM" && $aNodeToSort[$j]["attrs"]["NAME"] == $aNodeToSort[$i]["attrs"]["FKEY_SWITCH"]) {
			// switch is forcing display value
			if (!empty($_GET['fkey_switch']))
				$switchValue = $_GET['fkey_switch'];
			// default or current switch value
			elseif ($_GET['id'] > 0)
				// record exists (edit mode)
				$switchValue = getItemValue($oRes, $aNodeToSort[$j]["attrs"]["NAME"]);
			else	// new record (create mode)
				$switchValue = $aNodeToSort[$j]["attrs"]["DEFAULT"];
			foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode) {
				if ($childNode["name"] == "OPTION" && $childNode["attrs"]["TYPE"] == $switchValue) {
					$foreignName = $childNode["attrs"]["TABLE"];
					$found;
					break;
				}
			}
		}
		if ($found)
			break;
	}
} elseif ($aNodeToSort[$i]["attrs"]["FKEY"]) {
	// standard fkey
	$foreignName = $aNodeToSort[$i]["attrs"]["FKEY"];
}

 
if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == 'true') {
	// edition is locked
	if ($eKeyValue != -1)
		eval("$"."oTemp = new ".$foreignName."(".$eKeyValue.");");
	else	eval("$"."oTemp = new ".$foreignName."();");
//} elseif (!empty($aNodeToSort[$i]["attrs"]["RESTRICT"]) && $foreignName == 'bo_users' && $_SESSION["rank"] != 'ADMIN') {
//	// edition is locked
//	if ($eKeyValue!= -1){
//		eval("$"."oTemp = new ".$foreignName."(".$eKeyValue.");");
//	else	eval("$"."oTemp = new ".$foreignName."();");
} else {
	// edition is available
	eval("$"."oTemp = new ".$foreignName."();");
	
	$sql = "select * from ".$foreignName." ";
	 	  
	$resForeign = $db->Execute($sql);
	
	//$aForeign = dbGetObjects($foreignName);

	$aValue = array();
	$aWhereField = array();
	$default ="";
	// test de condtion where
	$DoWhere = false; 
	// tst de condition - type WHERE
	if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)) {
		foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
			if ($childNode["name"] == "OPTION") { // on a un node d'option				
				if ($childNode["attrs"]["TYPE"] == "where") {
					$DoWhere = true;
					$whereField = $childNode["attrs"]["ITEM"];
					if (isset ($childNode["attrs"]["OPTION"]) && $childNode["attrs"]["OPTION"] != "") {
						// test sur value passée par session 
						if ($childNode["attrs"]["OPTION"] == "session")  {
							$whereValue= $_SESSION[$childNode["attrs"]["VALUE"]];
							if (isset($childNode["attrs"]["ASSO"]) && $childNode["attrs"]["ASSO"]!= "")  {
								$whereClasse = $childNode["attrs"]["ASSO"];
								$def = $childNode["attrs"]["DEFAULT"];
								//echo $whereValue."-".DEF_ID_ADMIN_DEFAUT."".$whereField." ".$whereClasse;
								$aWhere = dbGetObjects($whereClasse);
								//var_dump($aWhere);
								for ($a=0; $a<sizeof($aWhere); $a++){
									$oWhere = $aWhere[$a];
									// test sur valeur par defaut
									if (isset($childNode["attrs"]["DEFAULT"]) && $childNode["attrs"]["DEFAULT"]!= "" && DEF_ID_ADMIN_DEFAUT == $whereValue) {
										$default = $childNode["attrs"]["DEFAULT"];
										$aValue[] = $oWhere->get_id();	
									} else {
										eval("$"."currentWhereFieldValue = $"."oWhere->get_".$whereField."();");
										if ($currentWhereFieldValue == $whereValue){
											//$aValue[] = $oWhere->get_id();	
											eval("$"."aValue[] = $"."oWhere->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");			
										}
									}
								}
								$whereField = $childNode["attrs"]["FKEY"];
							}
							else{
								$aValue[] = $whereValue;
							}
						}							
					}
					else{
						$aValue[] = $childNode["attrs"]["VALUE"];
					}
					// fin value par session
					$aWhereField[] = $whereField;
					//break;
				}
			}
		}
	}
	// fin test WHERE
	
	//pre_dump($aValue);
	//pre_dump($aWhereField);

	// debut traitement WHERE
	 
	if ($DoWhere == true){
		//var_dump($aValue);
		$fieldInURL =  $classeName."_".$foreignName;
		$aForeignNew = array();
		for ($ii=0; $ii<count($aForeign); $ii++) {
			$oForeign = $aForeign[$ii];
			if (!isset($_GET[$fieldInURL]) || $_GET[$fieldInURL]=="") {
				// valeur par défaut
				if ($default !="") {
					$aForeignNew[] = $aForeign[$ii];	
				} else { 
					for ($b=0; $b<sizeof($aValue); $b++) {
						eval("$"."currentWhereFieldValue = $"."oForeign->get_".$whereField."();");
						if ($currentWhereFieldValue == $aValue[$b])
							$aForeignNew[] = $aForeign[$ii];					
					}
				}
			} else {
				$chaine_result = strstr($listParam, $fieldInURL);
				$chaine_result = strstr($chaine_result, "comparateur");
				$tableau = explode("&", $chaine_result);
				$tableau = explode("=", $tableau[0]);
				eval("$"."currentWhereFieldValue = $"."oForeign->get_id();");
				if ($_GET[$tableau[0]] == "=") {
					if ($currentWhereFieldValue == $_GET[$fieldInURL])
						$aForeignNew[] = $aForeign[$ii];					
				} elseif ($_GET[$tableau[0]] == "<>") {
					if ($currentWhereFieldValue != $_GET[$fieldInURL])
						$aForeignNew[] = $aForeign[$ii];					
				}
			}
		}
		$aForeign = $aForeignNew;
	}
	// fin traitement where
}

// cas des deroulant d'id, pointage vers foreign
//$sXML = $aForeign[0]->XML;
if(!is_null($oTemp->XML_inherited))
	$sXML = $oTemp->XML_inherited;
else
	$sXML = $oTemp->XML;
//$sXML = $oTemp->XML;

unset($stack);
$stack = array();
xmlClassParse($sXML);

// no need to override
//$foreignName = $stack[0]["attrs"]["NAME"];
$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
$foreignNodeToSort = $stack[0]["children"];

$tempIsAbstractForeign = false;
$tempForeignAbstract = "";
$tempIsDisplayForeign = false;
$tempForeignDisplay = "";
$bCms_site = false;
$tempIsAbstractEnum = false;
$tempIsDisplayEnum = false;

//pre_dump($foreignNodeToSort);
$valueAbstract ="";
$typeAbstract ="";	
$valueDisplay ="";
$typeDisplay ="";
		
if(is_array($foreignNodeToSort)){ 
	foreach ($foreignNodeToSort as $nodeId => $nodeValue) {		
		if ($nodeValue["attrs"]["NAME"] == $oTemp->getAbstract()){	
			$valueAbstract =$nodeValue["attrs"]["NAME"];
			$typeAbstract =$nodeValue["attrs"]["TYPE"];	 
			if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
				$tempIsAbstractForeign = true;
				$tempForeignAbstract = $nodeValue["attrs"]["FKEY"]; 
				eval("$"."oTempForeignAbstract = new ".$tempForeignAbstract."();");

				if(!is_null($oTempForeignAbstract->XML_inherited))
					$sXML = $oTempForeignAbstract->XML_inherited;
				else
					$sXML = $oTempForeignAbstract->XML;
				//$sXML = $oTempForeignAbstract->XML; 
				unset($stack);
				$stack = array();
				xmlClassParse($sXML);
				
				$foreignAbstractName = $stack[0]["attrs"]["NAME"];
				$foreignAbstractPrefixe = $stack[0]["attrs"]["PREFIX"];
				$foreignAbstractNodeToSort = $stack[0]["children"];
				//break;
				 
				if(is_array($foreignAbstractNodeToSort)){ 
					foreach ($foreignAbstractNodeToSort as $nodeId => $nodeValue) {	
						
						 if ($nodeValue["attrs"]["NAME"] == strval($oTempForeignAbstract->getDisplay())) {  
							$translateAbstract = $nodeValue["attrs"]["TRANSLATE"]; 
							 
						}	
					}
				}
				 
			}
			else if ($nodeValue["attrs"]["OPTION"] == "enum"){ // cas enum 
				$tempIsAbstractEnum = true;	
				$tempForeignAbstract = $nodeValue["attrs"]["NAME"];  
				if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
					eval("$"."enum".ucfirst($nodeValue["attrs"]["NAME"])." = array()".";");
					foreach ($nodeValue["children"] as $childKey => $childNode){
						if($childNode["name"] == "OPTION"){ // on a un node d'option	 
							if ($childNode["attrs"]["TYPE"] == "value"){ 
								eval("$"."enum".ucfirst($nodeValue["attrs"]["NAME"])."[".intval($childNode["attrs"]["VALUE"])."] "." = \"".stripslashes($childNode["attrs"]["LIBELLE"])."\"; "); 
							}  			
						}
					}
				}	
			}
		}
		
		
		if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){	
			$valueDisplay =$nodeValue["attrs"]["NAME"];
			$typeDisplay =$nodeValue["attrs"]["TYPE"]; 			
			if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
				$tempIsDisplayForeign = true;
				$tempForeignDisplay = $nodeValue["attrs"]["FKEY"]; 
				eval("$"."oTempForeignDisplay = new ".$tempForeignDisplay."();");
				
				if(!is_null($oTempForeignDisplay->XML_inherited))
					$sXML = $oTempForeignDisplay->XML_inherited;
				else
					$sXML = $oTempForeignDisplay->XML;
				//$sXML = $oTempForeignDisplay->XML; 
				unset($stack);
				$stack = array();
				xmlClassParse($sXML);
				 
				$foreignDisplayPrefixe = $stack[0]["attrs"]["PREFIX"];
				$foreignDisplayNodeToSort = $stack[0]["children"];
				
				if(is_array($foreignDisplayNodeToSort)){ 
					foreach ($foreignDisplayNodeToSort as $nodeId => $nodeValue) {	
						 if ($nodeValue["attrs"]["NAME"] == strval($oTempForeignDisplay->getDisplay())) { 
						 	$valueForeignDisplay = $nodeValue["attrs"]["NAME"];
							$typeForeignDisplay = $nodeValue["attrs"]["TYPE"]; 
							$translateDisplay = $nodeValue["attrs"]["TRANSLATE"]; 
							 
						}	
					}
				}
				
				
				//break;
			}
			else if ($nodeValue["attrs"]["OPTION"] == "enum"){ // cas enum
				$tempIsDisplayEnum = true;	
				$tempForeignDisplay = $nodeValue["attrs"]["NAME"];  
				if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
					eval("$"."enum".ucfirst($nodeValue["attrs"]["NAME"])." = array()".";");
					foreach ($nodeValue["children"] as $childKey => $childNode){
						if($childNode["name"] == "OPTION"){ // on a un node d'option	 
							if ($childNode["attrs"]["TYPE"] == "value"){ 
								eval("$"."enum".ucfirst($nodeValue["attrs"]["NAME"])."[".intval($childNode["attrs"]["VALUE"])."] "." = \"".stripslashes($childNode["attrs"]["LIBELLE"])."\"; "); 
							}  			
						}
					}
				}	
			}
		}
		
		if (($aWhereField!=NULL) && !in_array("id_site", $aWhereField)){
			// si une option WHERE pointe sur le cms_site, on laisse tombe ce critère par défaut
			if (strtolower(stripslashes($nodeValue["attrs"]["FKEY"])) == "cms_site"){ 
				$bCms_site = true;
				$cms_site_name = $nodeValue["attrs"]["NAME"];
				if (isset($nodeValue["attrs"]["DEFAULT"])&& $nodeValue["attrs"]["DEFAULT"]!="")
					$default_cms_site_name = $nodeValue["attrs"]["DEFAULT"];
				else	$default_cms_site_name = "";
			}			
		}
		

	}
}


$disabled = "";
$sql = "select  ";

$aSelect = array(); 
$aWhere = array();
$aOrder = array();
$aListeChamps = $oTemp->getListeChamps();

//ID
array_push($aSelect, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $oTemp->getFieldPK())." as ID  ");
//array_push($aSelect, "fn.".$foreignPrefixe."_id as ID  ");
// ABSTRACT
if ($valueAbstract!="")
	array_push($aSelect, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $valueAbstract)." as ABSTRACT  ");
	//array_push($aSelect, "fn.".$foreignPrefixe."_".$valueAbstract." as ABSTRACT  ");
// DISPLAY
if ($valueDisplay!="")
	array_push($aSelect, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $valueDisplay)." as DISPLAY  ");
	//array_push($aSelect, "fn.".$foreignPrefixe."_".$valueDisplay." as DISPLAY  "); 
// STATUT 
if ($foreignName != 'cms_page' && $oTemp->getGetterStatut() != "none")
	array_push($aSelect, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, 'statut')." as STATUT  ");

	//array_push($aSelect, "fn.".$foreignPrefixe."_statut as STATUT  ");
//if ($oTemp->getGetterStatut() != "none") array_push($aSelect, "fn.".$foreignPrefixe."_statut as STATUT  "); 	

if ($tempIsAbstractForeign )
	array_push($aSelect, "fa.".getCorrectField ($oTempForeignAbstract->getListeChamps(), $foreignAbstractPrefixe, $oTempForeignAbstract->getDisplay())." as ABSTRACT_LIB  ");
	//array_push($aSelect, " fa.".$foreignAbstractPrefixe."_".$oTempForeignAbstract->getDisplay()." as ABSTRACT_LIB ");
if ($tempIsDisplayForeign )
	array_push($aSelect, "fd.".getCorrectField ($oTempForeignDisplay->getListeChamps(), $foreignDisplayPrefixe, $oTempForeignDisplay->getDisplay())." as DISPLAY_LIB  ");
	//array_push($aSelect, " fd.".$foreignDisplayPrefixe."_".$oTempForeignDisplay->getDisplay()." as DISPLAY_LIB ");

if($isRecursive) {
	array_push($aSelect, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $stack[0]["attrs"]["PARENT"])." as PARENT  ");	
}
	 
// CMS_SITE 
if ($bCms_site == true){
	if (doesFieldExist($aListeChamps, 'id_site')){
		array_push($aSelect, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, 'id_site')." as CMS_SITE  ");	
	}
	elseif (doesFieldExist($aListeChamps, 'cms_site')){
		array_push($aSelect, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, 'id_site')." as CMS_SITE  ");	
	}	
}

if (sizeof($aSelect) > 0)
	$sql.=" ".join(" , ", $aSelect);

$sql.= " FROM ".getCorrectTable ($oTemp)." fn ";

if ($tempIsAbstractForeign)
	$sql.= " , ".$tempForeignAbstract." fa ";
if ($tempIsDisplayForeign)
	$sql.= " , ".$tempForeignDisplay." fd ";
		
	
// si il y a une fkey pour abstract ou display
if ($tempIsAbstractForeign && $valueAbstract != "cms_site")
	array_push($aWhere, " fa.".getCorrectField ($oTempForeignAbstract->getListeChamps(), $foreignAbstractPrefixe, 'id')." = fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $valueAbstract)." ");
	//array_push($aWhere, " fa.".$foreignAbstractPrefixe."_id = fn.".$foreignPrefixe."_".$valueAbstract);
if ($tempIsDisplayForeign && $valueDisplay != "cms_site")
	array_push($aWhere, " fd.".getCorrectField ($oTempForeignDisplay->getListeChamps(), $foreignDisplayPrefixe, 'id')." = fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $valueDisplay)." ");
	//array_push($aWhere, " fd.".$foreignDisplayPrefixe."_id = fn.".$foreignPrefixe."_".$valueDisplay);

if($isRecursive) {
	//Uniquement premier niveau pour le moment
	array_push($aWhere, " fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $stack[0]["attrs"]["PARENT"])." = -1 ");
}
 
//aFkeyFilter_name
if ($isFkeyFilter) { 
	$oRech_custom = new dbRecherche();
	$oRech_custom->setValeurRecherche("declencher_recherche");
	$oRech_custom->setTableBD("cms_custom");
	$oRech_custom->setJointureBD("  cus_param = '".isfkeyfilter."' and cus_valeur = '".$foreignName."' ");
	$oRech_custom->setPureJointure(1);
	$aRecherche_custom[] = $oRech_custom; 
	$sql_custom = "SELECT * ";
	$sql_custom .= dbMakeRequeteWithCriteres("cms_custom", $aRecherche_custom, "");   
	$aRes_custom = dbGetObjectsFromRequete("cms_custom", $sql_custom); 

	if (sizeof(aRes_custom)>0) {
		$aOr = array();
		foreach ($aRes_custom as $key => $ofkeyValue) { 
			array_push($aOr, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, 'id')." = ".$ofkeyValue->get_classe()." ");
			//array_push($aOr, "fn.".$foreignPrefixe."_id = ".$ofkeyValue->get_classe());   
		} 
		if (sizeof($aOr) > 0)
			array_push($aWhere, " ( ".join(" OR ", $aOr)." ) ");
	}
} 

if ($bCms_site && $foreignName != "classe") 
	array_push($aWhere, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $cms_site_name)." = ".$_SESSION['idSite_travail']." ");
	//array_push($aWhere, "fn.".$foreignPrefixe."_".$cms_site_name." = ".$_SESSION['idSite_travail']." ");

if ($valueAbstract!="" || $valueDisplay!="") {
	if ($typeDisplay == "date" || $typeAbstract == "date") {
		if ($typeDisplay == "date" && $valueDisplay != ""  )
			array_push($aOrder, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $valueDisplay)." DESC ");
			//array_push($aOrder, "fn.".$foreignPrefixe."_".$valueDisplay." DESC "); 
		if ($typeAbstract == "date" && $valueAbstract != "" )  
			array_push($aOrder, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $valueAbstract)." DESC ");
			//array_push($aOrder, "fn.".$foreignPrefixe."_".$valueAbstract." DESC "); 
	} else {
		if($isRecursive) {
			array_push($aOrder, $foreignPrefixe."_".$stack[0]["attrs"]["ORDONABLE"]." ASC ");
		} else {
			if ($typeDisplay != "date" && $valueDisplay != "" )
				array_push($aOrder, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $valueDisplay)." ASC ");
				//array_push($aOrder, "fn.".$foreignPrefixe."_".$valueDisplay." ASC ");  
			if ($typeAbstract != "date" && $valueAbstract != "" )
				array_push($aOrder, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $valueAbstract)." ASC ");
				//array_push($aOrder, "fn.".$foreignPrefixe."_".$valueAbstract." ASC ");  
		}
	} 

	// controle groupe wise pour les non admin
	//getListeChamps()
	if ($_SESSION['rank'] != 'ADMIN') {
		$oForeignTemp = new $foreignName();
		$aListeChamps = $oForeignTemp->getListeChamps();
		unset($oForeignTemp);
		foreach($aListeChamps as $kChamps => $vChamps) {
			if ($vChamps->Getter == 'get_bo_groupes') {
				array_push($aWhere, "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, 'bo_groupes')." = ".$_SESSION['groupe']." ");
				//array_push($aWhere,  ' '.$foreignPrefixe.'_bo_groupes = '.$_SESSION['groupe'].' ');  
				break;
			}
		}
	}
	
	// rétabliseement de la prise en compte des WHERE
	if ($DoWhere==true) {
		for ($iw=0;$iw<count($aValue);$iw++){
			if (isset($aWhereField[$iw])){
				$sWhereField=$aWhereField[$iw];
			
				
				// add check getCorrectField ?
				//$aWhere[] = ' fn.'.$classePrefixe.'_'.$sWhereField.' = '.$aValue[$iw].' ';
				if (preg_match ("/,/", $aValue[$iw]))  
					$aWhere[] = ' (fn.'.getCorrectField ($aListeChamps, $foreignPrefixe, $sWhereField).' IN ('.$aValue[$iw].')) ';
				else
					$aWhere[] = ' (fn.'.getCorrectField ($aListeChamps, $foreignPrefixe, $sWhereField).' = '.$aValue[$iw].') ';
				
			
			
			}
			else{
					$aWhere[count($aWhere)-1] = str_replace(')',  ' OR fn.'.getCorrectField ($aListeChamps, $foreignPrefixe, $sWhereField).' = '.$aValue[$iw].') ', $aWhere[count($aWhere)-1]);
			}
			
		}
	}
	
	if (is_get('display')&&is_get('field')){
		$aWhere[] =  "fn.".getCorrectField ($aListeChamps, $foreignPrefixe, $oTemp->getFieldPK())." = ".$display.' ';						
	}
	
	
	
	// cas particulier cms_page 
	if ( $foreignName == "cms_page") {
		$aWhere[] =  "fn.valid_page = 1 ";		 
		$aWhere[] =  "fn.id_site = ".$_SESSION["idSite_travail"] ;		 
	}
	
	$aName = array ();
	$needle = "filter";
	foreach ($_SESSION as $key => $postedvar){ 
		if (ereg($needle, $key) == true){
			$aKeyVar = array();
			$aKeyVar[strtolower(str_replace("filter", "", $key))] = $postedvar;
			
			if (!in_array(strtolower(str_replace("filter", "", $key)), $aName)) {
				$aReturn[] = $aKeyVar;
			}
			
		}
	} 
	 
	
	if (!empty($aReturn)) {
		foreach ($aReturn as $kFilter => $aPostFilter) {
			foreach ($aPostFilter as $filterName => $filterValue) {
				 
				 //echo $filterName. " ". $filterValue."<br />"; 
				 //echo $classePrefixe.'_'.$_GET['field']."<br />"; 
				if ($filterName ==  $classePrefixe.'_'.$_GET['field']) {
				 
				if ($filterValue!=-1 &&  $filterValue != "" || $filterValue == 0 && doesFieldExist($aListeChamps, $filterName)) { 	
						if (preg_match ("/,/", $filterValue))  {
							$aWhere[] =  " fn.{$foreignPrefixe}_id IN (".$filterValue.") ";
						}
						else {
							$aWhere[] = " fn.{$foreignPrefixe}_id =".$filterValue." ";
						}
								 
					}
				}
			}
		}
	}  
	
	if (sizeof($aWhere) > 0)
		$sql.=" WHERE ".join(" AND ", $aWhere);
	if (sizeof($aOrder) > 0)
		$sql.=" ORDER BY ".join(" , ", $aOrder);
	if (strrpos($sql, ",") == (strlen($sql)-1))
		$sql = substr($sql, 0, strrpos($sql, ","));

	//print ( $sql);

	//$res = dbGetObjectsFromRequete($foreignName, $sql);
	$res = $db->Execute($sql);
	
} 

//print ( $sql);
/*
$temps = microtime();
$temps = explode(' ', $temps);
$fin = $temps[1] + $temps[0];
echo '<br/>Page exécutée en '.round(($fin - $debut),6).' secondes.<br/>';*/

if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == 'true') {
	// edition is locked
	$sel.= "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

	if ($res) {
		$res->MoveFirst();	  
		while(!$res->EOF) {
			$row = $res->fields;

			(isset($row["STATUT"])) ?  $tempStatus = $row["STATUT"]  :  $tempStatus = DEF_ID_STATUT_LIGNE;
			if ($aNodeToSort[$i]["attrs"]["NAME"] == "candidature") $tempStatus = DEF_ID_STATUT_LIGNE;
			$tempId = $row["ID"] ;
					 
			if ($tempStatus == DEF_ID_STATUT_LIGNE || $aNodeToSort[$i]["attrs"]["FKEY"] == "newsletter" || is_get('display')){
						
				if ($eKeyValue == $tempId){
					($tempIsDisplayForeign) ? $itemValue = $row["DISPLAY_LIB"] : $itemValue = $row["DISPLAY"];
					(strlen($itemValue) > 50 ) ? $sel.= substr($itemValue, 0, 50)." ... " : $sel.= $itemValue;
	
					if ($oTemp->getDisplay() != $oTemp->getAbstract() ) {
						$sel.= " - ";
						($tempIsAbstractForeign) ? $itemValue = $row["ABSTRACT_LIB"] : $itemValue = $row["ABSTRACT"];
					}  
					(strlen($itemValue) > 50 ) ? $sel.= substr($itemValue, 0, 50)." ... " : $sel.= $itemValue;
				}					
			}	 // fin if statut		
			$res->MoveNext();
		}			
	}// fin for

} else {
	 
	//viewArray($aNodeToSort[$i]);	

	// edition is available
	if (($aNodeToSort[$i]["attrs"]["NAME"] == $displayField) && isset($display) && ($display!="")){
		$disabled = "disabled";
		
		$sel.= "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$display."\" />\n";

		if ($res) {
			$res->MoveFirst();  
			while(!$res->EOF) {
				$row = $res->fields;				
				 
				(isset($row["STATUT"])) ?  $tempStatus = $row["STATUT"]  :  $tempStatus = DEF_ID_STATUT_LIGNE;
				if ($aNodeToSort[$i]["attrs"]["NAME"] == "candidature"){
					$tempStatus = DEF_ID_STATUT_LIGNE;
					
				}
				$tempId = $row["ID"] ;
				
				if ($tempStatus == DEF_ID_STATUT_LIGNE || $aNodeToSort[$i]["attrs"]["FKEY"] == "newsletter" || is_get('display')){	
					if (($eKeyValue == $tempId)||($tempId==$display)){
						($tempIsDisplayForeign) ? $itemValue = $row["DISPLAY_LIB"] : $itemValue = $row["DISPLAY"];
						(strlen($itemValue) > 50 ) ? $sel.= substr($itemValue, 0, 50)." ... " : $sel.= $itemValue;
	
						if ($oTemp->getDisplay() != $oTemp->getAbstract() ) {
							$sel.= " - ";
							($tempIsAbstractForeign) ? $itemValue = $row["ABSTRACT_LIB"] : $itemValue = $row["ABSTRACT"];
						} 
						(strlen($itemValue) > 50 ) ? $sel.= substr($itemValue, 0, 50)." ... " : $sel.= $itemValue;
					}					
				}	 // fin if statut
				$res->MoveNext();
			}						
		}// fin for
			
	} else {
		if ($aNodeToSort[$i]["attrs"]["FKEY"] == "cms_site" && $classeName != "classe" && $classeName != "cms_site") {
			
		} else {			
			// translation data
			// Added by Luc - 13 oct. 2009
			if(!is_null($aForeign[0]->XML_inherited))
				$sXML = $aForeign[0]->XML_inherited;
			else
				$sXML = $aForeign[0]->XML;
			//$tmpXML = $aForeign[0]->XML;
			$tmp_stack = array();// init stack
			$aForeignXMLChildren = $stack[0]["children"];
			$FkeyStack = $stack;
			
			$aExpectedStatuses = array();
			// tst de condition - type WHERE
			if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if ($childNode["name"] == "OPTION") { // on a un node d'option								
						if ($childNode["attrs"]["TYPE"] == "statut") {	
							$aExpectedStatuses = explode(',', $childNode["attrs"]["VALUE"]);
						}
					}
				}
			} else	$aExpectedStatuses[] = DEF_ID_STATUT_LIGNE;

			$sel.= "<select name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" ".$disabled.">\n";
			$sel.= "<option value=\"-1\">".$translator->getTransByCode('Choisir_un_item')."</option>\n";

			// user 
			$oUser = new bo_users($_SESSION['userid']);
			if ($res) {
				// use buffer table to avoid record ubiquity 
				$eCountClasseToFind = getCount_where("classe", array("cms_nom"), array($oTemp->getAbstract()), array("TEXT"));
				$res->MoveFirst();
				while(!$res->EOF) {
					displayOptionSelect($sql, $res, $FkeyStack, $oTemp, $aNodeToSort[$i], $eKeyValue, '-1');
					$res->MoveNext();
				}
			}  
		}
	}
} 

function displayOptionSelect($sql, $res, $stack, $oTemp, $aCurrentNode, $eKeyValue, $previousParent, $current_level = 0) {
	// error_reporting(E_ALL);
	global $translator;
	global $bCms_site;
	global $classeName;
	global $default_cms_site_name;
	global $tempIsDisplayForeign;
	global $tempIsDisplayEnum;
	global $aForeignXMLChildren;
	global $translateDisplay;
	global $isRecursive;
	global $aOps;
	global $db;

	$aListeChamps = $oTemp->getListeChamps();
	$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
	$currentStackClassParent = $stack[0]["attrs"]["PARENT"];
	 // statut
	(isset($row["STATUT"])) ?  $tempStatus = $row["STATUT"]  :  $tempStatus = DEF_ID_STATUT_LIGNE;
	$row = $res->fields;
	$tempId = $row["ID"];

	if ($tempStatus == DEF_ID_STATUT_LIGNE || $aCurrentNode["attrs"]["FKEY"] == "newsletter" || in_array($tempStatus ,$aExpectedStatuses) || in_array('*' ,$aExpectedStatuses)){					 	

		if ($bCms_site == true && $classeName != "classe" && $classeName != "cms_site" && $default_cms_site_name=="") { 

			//$temp_cms_site = $row["ABSTRACT"] ;
			$temp_cms_site = $row["CMS_SITE"] ;

			if ((isset($_SESSION['idSite_travail']) && $_SESSION['idSite_travail'] != '' &&  preg_match("/backoffice/si", $_SERVER['PHP_SELF']) && $temp_cms_site == $_SESSION['idSite_travail']) || $temp_cms_site == $idSite) {						
				 
				if ((($aCurrentNode["attrs"]["NAME"] != 'rank')&&($aCurrentNode["attrs"]["NAME"] != 'bo_groupes'))||
					(($aCurrentNode["attrs"]["NAME"] == 'rank')&&($oUser->get_rank()<=$tempId)&&($oUser->get_rank()!=-1)) ||
					(($aCurrentNode["attrs"]["NAME"] == 'bo_groupes')&&($oUser->get_bo_groupes()==$tempId)) ||
					($_SESSION['rank'] == 'ADMIN')){// fin if si rank habilité	
					$sTempSel = "<option value=\"".$tempId."\"";
					if ($eKeyValue == $tempId)
						$sTempSel.= ' selected="true"';						
					$sTempSel.= ">";

					
					$selItem = '';	
					//eval ("$sel.= substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
					// translation data
					// Added by Luc - 13 oct. 2009
					$aForeignAttributes = Array();
					if ($tempIsDisplayForeign){
						$itemValue = $row["DISPLAY_LIB"];
						$temp_to_compare = $oForeignDisplay->getDisplay();
					} else {
						$itemValue = $row["DISPLAY"];
						$temp_to_compare = $oTemp->getDisplay(); 
					}
					foreach ($aForeignXMLChildren as $children) {
						if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($temp_to_compare)) {
							$aForeignAttributes = $children['attrs'];
							break;
						}
					}
					if (DEF_APP_USE_TRANSLATIONS && $aForeignAttributes["TRANSLATE"]) {
						if ($aForeignAttributes["TYPE"] == "int") {
							if ($aForeignAttributes["TRANSLATE"] == 'reference')
								$itemValue = $translator->getByID($itemValue);
						} elseif ($aForeignAttributes["TYPE"] == "enum") {
							if ($aForeignAttributes["TRANSLATE"] == "value")
								$itemValue = $translator->getText($itemValue);
						} else	$itemValue = "Error - Translation engine can not be applied to <b><i>".$aForeignAttributes["TYPE"]."</i></b> type fields !!";
					}
					// end translation data
					$selItem.= $itemValue;

					// translation data
					// Added by Luc - 13 oct. 2009
					if ($oTemp->getDisplay() != $oTemp->getAbstract()) {
						$selItem.= " - ";
						if ($tempIsAbstractForeign){
							$itemValue = $row["ABSTRACT_LIB"];
							$temp_to_compare = $oTemp->getDisplay();  
						} else {
							$itemValue = $row["ABSTRACT"];
							$temp_to_compare = $oTemp->getAbstract(); 
						}
						foreach ($aForeignXMLChildren as $children) {
							if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($temp_to_compare)) {
								$aForeignAttributes = $children['attrs'];
								break;
							}
						}	
						if (DEF_APP_USE_TRANSLATIONS && $aForeignAttributes["TRANSLATE"]) {
							if ($aForeignAttributes["TYPE"] == "int") {
								if ($aForeignAttributes["TRANSLATE"] == 'reference')
									$itemValue = $translator->getByID($itemValue);
							} elseif ($aForeignAttributes["TYPE"] == "enum") {
								if ($aForeignAttributes["TRANSLATE"] == "value")
									$itemValue = $translator->getText($itemValue);
							} else	$itemValue = "Error - Translation engine can not be applied to <b><i>".$aForeignAttributes["TYPE"]."</i></b> type fields !!";
						}
						// end translation data
						$selItem.= $itemValue;
					}
					$sTempLib = (strlen($selItem) > 100 ? substr($selItem, 0, 100)." ... " : $selItem);
					$sTempSel.= $sTempLib;
					$sTempSel.= "</option>\n";
					$aOps[strtoupper($sTempLib)]=$sTempSel;
					
				} // fin if si rank habilité
			}

		} else {

			
			if ((($aCurrentNode["attrs"]["NAME"] != 'rank')&&($aCurrentNode["attrs"]["NAME"] != 'bo_groupes'))||
				(($aCurrentNode["attrs"]["NAME"] == 'rank')&&($oUser->get_rank()<=$tempId)&&($oUser->get_rank()!=-1)) ||
				(($aCurrentNode["attrs"]["NAME"] == 'bo_groupes')&&($oUser->get_bo_groupes()==$tempId)) ||
				($_SESSION['rank'] == 'ADMIN')){// fin if si rank habilité	 
				
				$sTempSel= "<option value=\"".$tempId."\"";
				if ($eKeyValue == $tempId)
					$sTempSel.= ' selected="true"';			
				$sTempSel.= ">";
				$selItem ="";

				// translation data
				// Added by Luc - 13 oct. 2009
				$aForeignAttributes = Array();
				if ($tempIsDisplayForeign){
					$itemValue = $row["DISPLAY_LIB"];
					//$temp_to_compare = $oForeignDisplay->getDisplay(); 
				} else if ($tempIsDisplayEnum) {   
					eval("$"."itemValue = $"."enum".ucfirst($tempForeignDisplay)."[".$row["DISPLAY"]."];"); 
					$temp_to_compare = $oTemp->getDisplay(); 
				} else {
					$itemValue = $row["DISPLAY"];
					$temp_to_compare = $oTemp->getDisplay();  
				}
				foreach ($aForeignXMLChildren as $children) {
					if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($temp_to_compare)) {
						$aForeignAttributes = $children['attrs'];
						break;
					}
				}
				//echo "TEST : ".$aForeignAttributes["TYPE"];
				if (DEF_APP_USE_TRANSLATIONS && $aForeignAttributes["TRANSLATE"]) {
					if ($aForeignAttributes["TYPE"] == "int") {
						if ($aForeignAttributes["TRANSLATE"] == 'reference')
							$itemValue = $translator->getByID($itemValue);
					} elseif ($aForeignAttributes["TYPE"] == "enum") {
					 	if ($aForeignAttributes["TRANSLATE"] == "value")
					 		$itemValue = $translator->getText($itemValue);
					} else	$itemValue = "Error - Translation engine can not be applied to <b><i>".$aForeignAttributes["TYPE"]."</i></b> type fields !!";
				}
				// end translation data
				
				// TLS display 
				if (DEF_APP_USE_TRANSLATIONS && $translateDisplay) { 
					if ($typeForeignDisplay == "int") {
						if ($translateDisplay == 'reference')
							$itemValue = $translator->getByID($itemValue);
					} elseif ($typeForeignDisplay == "enum") {
						if ($translateDisplay == "value")
							$itemValue =  $translator->getText($itemValue);
					} else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";
				}

				$selItem .= $itemValue;
				$itemValue = "";

				if ($oTemp->getDisplay() != $oTemp->getAbstract() && $oTemp->getAbstract() != "statut"){
					$selItem.= " - ";
					$itemValue = $row["ABSTRACT"]; 
					 
					$sForeignDisplay_="";
					if ($eCountClasseToFind > 0 && $sNameClasseToFind != '') {  // modif 02/11/11 => on vérifie que  $sNameClasseToFind != ''				 											 
						while (getCount_where("classe", array("cms_nom"), array($sNameClasseToFind), array("TEXT")) ==  1){ 
							//echo "sNameClasseToFind :".$sNameClasseToFind."<br />";
							//echo "itemValue :".$itemValue."<br />";
							eval("$"."oForeignDisplay = new ".$sNameClasseToFind."($"."itemValue);");
							eval("$"."sForeignDisplay = $"."oForeignDisplay->get_".$oForeignDisplay->getDisplay()."();"); 
							$sForeignDisplay_ = $sForeignDisplay." > ".$sForeignDisplay_;
							eval (" $"."itemValue = $"."oForeignDisplay->get_".strval($oForeignDisplay->getAbstract())."();"); 
						 
							$sNameClasseToFind = $oForeignDisplay->getAbstract();
						 } 
						$sel.= substr($sForeignDisplay_, 0, strlen($sForeignDisplay_)-3);										
					} 
					else {										
						$aForeignAttributes = Array();
						if ($bCms_site && $default_cms_site_name == -1) { 
						 	$itemValue = $row["ABSTRACT_LIB"];
							
							foreach ($aForeignXMLChildren as $children) {
								if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($oTemp->getAbstract())) {
									$aForeignAttributes = $children['attrs'];
									break;
								}
							}
						}
						else {  
							if ($tempIsAbstractForeign) {  
								$itemValue = $row["ABSTRACT_LIB"];  
								$temp_to_compare = $oTemp->getDisplay();  
								
							} elseif ($tempIsAbstractEnum) {     
								eval("$"."itemValue = $"."enum".ucfirst($tempForeignAbstract)."[".$row["ABSTRACT"]."];"); 
								$temp_to_compare = $oTemp->getAbstract(); 
							} else {
								$itemValue = $row["ABSTRACT"];
								$temp_to_compare = $oTemp->getAbstract();  
							}
							foreach ($aForeignXMLChildren as $children) {
								if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($temp_to_compare)) {
									$aForeignAttributes = $children['attrs'];
									break;
								}
							}
						}
						// translation data
						// Added by Luc - 13 oct. 2009
						if (DEF_APP_USE_TRANSLATIONS && $aForeignAttributes["TRANSLATE"]) {
							if ($aForeignAttributes["TYPE"] == "int") {
								if ($aForeignAttributes["TRANSLATE"] == 'reference')
									$itemValue = $translator->getByID($itemValue);
							} elseif ($aForeignAttributes["TYPE"] == "enum") {
							 	if ($aForeignAttributes["TRANSLATE"] == "value")
							 		$itemValue = $translator->getText($itemValue);
							} else	$itemValue = "Error - Translation engine can not be applied to <b><i>".$aForeignAttributes["TYPE"]."</i></b> type fields !!";
						}
						// end translation data										
						
						if (DEF_APP_USE_TRANSLATIONS && $translateDisplay) { 
							if ($typeForeignDisplay == "int") {
								if ($translateDisplay == 'reference')
									$itemValue = $translator->getByID($row["ABSTRACT_LIB"]);
							} elseif ($typeForeignDisplay == "enum") {
								if ($translateDisplay == "value")
									$itemValue =  $translator->getText($row["ABSTRACT_LIB"]);
							} else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";											 
						}										
						$selItem.= $itemValue;
					}									
				} 
				$sTempLib = (strlen($selItem) > 100 ? substr($selItem, 0, 100)." ... " : $selItem);
				for($a = 0; $a < $current_level; $a++) {
					$sTempSel .= '&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				$sTempSel.= $sTempLib;
				$sTempSel.= "</option>\n"; 

				$aOps[strtoupper($sTempLib).$row["ID"]]=$sTempSel;
				if($isRecursive) {
					$pattern = '/'.$current_field.' = '.$previousParent.'/';
					$replacement = $current_field.' = '.$row["ID"];
					$sql = preg_replace($pattern, $replacement, $sql);
					$resRec = $db->Execute($sql);
					if ($resRec) {
						$current_level++;
						$resRec->MoveFirst();
						while(!$resRec->EOF) {
							displayOptionSelect($sql, $resRec, $stack, $oTemp, $aCurrentNode, $eKeyValue, $row["ID"], $current_level);
							$resRec->MoveNext();
						}
					} else {
						$current_level = 0;
					}
				}
			} // fin if si rank habilité		
		}					
	} 
}

if (($aNodeToSort[$i]["attrs"]["FKEY"] != "cms_site") || ($classeName == "classe") || ($classeName == "cms_site")){
	if(!$isRecursive) {
		ksort($aOps);
	}
	echo $sel;
	echo implode('', $aOps);
	unset($aOps);
	echo '</select>';
}
else{
	$idPlus = ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"];
	?>
	<script type="text/javascript">
	 $(document).ready(function(){				
		$("#<?php echo 'a'.$idPlus; ?>").fancybox({
			'padding'		  : 0,
			'width'			  : '75%',
			'height'		  : '75%',
			'scrolling'       : 'no',
			'showCloseButton' : true,
			'titleShow'       : false,
			'transitionIn'	  : 'elastic',
			'transitionOut'	  : 'elastic'
		});
	});	
	</script>
	<?php
	$isCms = isCmsClass($foreignName); 
	if (preg_match('/^ss3_/si', $foreignName)){
		$ifUrl = '/backoffice/adss/'.$foreignName.'/maj_'.$foreignName.'.php?id=-1&noMenu=true';
	}
	elseif ($isCms){
		$ifUrl = '/backoffice/cms/'.$foreignName.'/maj_'.$foreignName.'.php?id=-1&noMenu=true';		
	}
	else{
		$ifUrl = '/backoffice/'.$foreignName.'/maj_'.$foreignName.'.php?id=-1&noMenu=true';
	}
	
	if ((($aNodeToSort[$i]["attrs"]["FKEY"] != "cms_site") || ($classeName == "classe") || ($classeName == "cms_site")) && ($aNodeToSort[$i]["attrs"]["ADDITEM"] != "false")){	
	
		$ifPath= $_SERVER['DOCUMENT_ROOT'].preg_replace('/\?.*$/msi', '', $ifUrl);
		if(is_file($ifPath)){
			echo '<a href="#'.$idPlus.'_plus" id="a'.$idPlus.'" class="arbo" onclick="loadIframe(\''.$idPlus.'\')">[+] '.$translator->getTransByCode('ajouterunitem').'</a>'."\n";
		}
	}
	
	echo '<div style="display: none;">'."\n";
	echo '<div id="'.$idPlus.'_plus" style="width:750px; height:655px">'."\n";
	
	
	echo '<input type="hidden" id="ifUrl'.$idPlus.'" name="ifUrl'.$idPlus.'" value="'.$ifUrl.'" />';
	echo '<iframe id="if'.$idPlus.'" name="if'.$idPlus.'" width="100%" height="100%" ></iframe>';
	?>								
	</div>										
</div>	
<?php

}


?>