<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!defined('DEF_APP_USE_TRANSLATIONS')){
	define('DEF_APP_USE_TRANSLATIONS', false);
}


// Return valid inherited class and prefix for the given field or null
function getCorrectInheritedClass ($inherited, $field) {
	if (!empty($inherited)) {
		// Look for field in inherited table
		foreach ($inherited as $class) {
			if (method_exists($class, 'get_'.$field)) {
				$oInherited = new $class();
				return $oInherited;
			}
		}
	}
	return null;	
}



// function dbGetAssocProps
// renvoie les éléments liés d'une association avec 'display' et 'abstract' éventuellement traduits...
//
// @param $sClasseRef	nom de la classe de l'enregistrement de référence
// @param $sClasseAssoc	nom de la classe de liaison (n,m) ou enfant (1,n)
//
// @return Array		Les propriétés de l'association/liaison

function dbGetAssocProps($oObjet, $sClasseAssoc) {
	global $stack;

	//echo 'TEST : '.$sClasseAssoc.'<br/>';
	eval("$"."oTemp = new ".$sClasseAssoc."();");

	if (!is_null($oTemp->XML_inherited))
		$sXML = $oTemp->XML_inherited;
	else	$sXML = $oTemp->XML;
	$stackAssoc = xmlClassParse($sXML);
	$foreignNodeToSort = $stackAssoc[0]["children"];

	//viewArray($stackAssoc[0]);
	$tempAsso = $stackAssoc[0]["attrs"]["NAME"];
	$tempAssoFull = $stackAssoc[0]["attrs"]["IS_ASSO"] == 'true' ? true : false;
	$tempAssoPrefixe = $stackAssoc[0]["attrs"]["PREFIX"];
	$tempSwitchField = "";
	$tempSwitchValue = "";
	$tempAssoIn = "";
	$tempAssoOut = "";
	$tempAsymetric = ($tempAssoFull &&	isset($stackAssoc[0]["attrs"]["IS_ASYMETRIC"])	&& $stackAssoc[0]["attrs"]["IS_ASYMETRIC"] == 'true') ? true : false; 
	$tempAddItem = (isset($stackAssoc[0]["attrs"]["ADDITEM"])	&& $stackAssoc[0]["attrs"]["ADDITEM"] == 'false') ? false : true;
	$tempStatus = ($oTemp->getGetterStatut() != "none" ? true : false);
	$tempAssoOutPrefixe = "";
	$tempAssoInPrefixe = "";
	  

	if (is_array($foreignNodeToSort)) {
		// associate different record from the SAME table
		$track_key = 0;
		$track_translate = 0;
		foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
			if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] == $oObjet->getTable())
				$track_key++;
			if (isset($nodeValue["attrs"]["IS_TRANSLATE"]) && $nodeValue["attrs"]["IS_TRANSLATE"])
				$track_translate++;
		}
		if ($track_key > 1) {
			$cnt = 0;
			foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
				// Add fkey_switch capability some day.....
				if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] == $oObjet->getTable()) {
					$tempAssoIn = $tempAssoOut = $nodeValue["attrs"]["FKEY"];
					if ($cnt == 0)
						$tempAssoInName = $nodeValue["attrs"]["NAME"];
					else	$tempAssoOutName = $nodeValue["attrs"]["NAME"];
					$cnt++;
				}
			}
		// end associate different record from the SAME table
		} else {
			$found = false;
			foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
				if (isset($nodeValue["attrs"]["FKEY_SWITCH"])) {
					// switchable fkey
					// find type switch field
					for ($j=0; $j<count($foreignNodeToSort); $j++) {
						if ($foreignNodeToSort[$j]["name"] == "ITEM" && $foreignNodeToSort[$j]["attrs"]["NAME"] == $nodeValue["attrs"]["FKEY_SWITCH"]) {
							foreach ($nodeValue["children"] as $childKey => $childNode) {
								if ($childNode["name"] == "OPTION" && $childNode["attrs"]["TABLE"] == $oObjet->getTable()) {
									$tempSwitchField = $nodeValue["attrs"]["FKEY_SWITCH"];
									$tempSwitchValue = $childNode["attrs"]["TYPE"];
									$tempAssoIn = $oObjet->getClasse();
									$tempAssoInName = $nodeValue["attrs"]["NAME"];
									$found;
									break;
								}
							}
						}
						if ($found)
							break;
					}
				} elseif (!$found && isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] != '') {
					// Fkey possibly links to inherited table
					$oTemp2 = new $nodeValue["attrs"]["FKEY"]();
					if ($nodeValue["attrs"]["FKEY"] == $oObjet->getTable()
							|| (!empty($oObjet->inherited_list) && in_array($nodeValue["attrs"]["FKEY"], $oObjet->inherited_list))
							 || (!empty($oTemp2->inherited_list) && in_array($oObjet->getTable(), $oTemp2->inherited_list))) {							
						$tempAssoIn = $nodeValue["attrs"]["FKEY"];
						$tempAssoInName = $nodeValue["attrs"]["NAME"];
					} elseif ($tempAssoFull) {
						$tempAssoOut = $nodeValue["attrs"]["FKEY"]; 
						$tempAssoOutName = $nodeValue["attrs"]["NAME"];
					}
					if (!empty($tempAssoIn) && !empty($tempAssoOut))
						// Full asso will search for first FKey following the one linking current table
						// Beware while building complex multiple FKeyied associations table
						$found = true;
				}
				// assymetric mode is only for table asso-linking to itself
				// so in case it was unwantedly set in asso table XML :
				$tempAsymetric = false;
				if ($found)
					break;
			}
		}
	}
	//echo "tempAsso : ".$tempAsso."<br/>";
	//echo "tempAssoFull : ".$tempAssoFull."<br/>";
	//echo "tempAssoIn : ".$tempAssoIn."<br/>";
	//echo "tempAssoOut : ".$tempAssoOut."<br/>";
	//echo "tempAssoSwitch : ".$tempSwitchField."<br/>";
	 
	if ($tempAssoIn != '') {
		eval("$"."oTemp = new ".$tempAssoIn."();"); 
		if (isset($oTemp->XML_inherited)  &&  !is_null($oTemp->XML_inherited))
			$sXML = $oTemp->XML_inherited;
		else	$sXML = $oTemp->XML;
		$stack = xmlClassParse($sXML); 
		$tempAssoInPrefixe = $stack[0]["attrs"]["PREFIX"];
	}
	if ($tempAssoOut != '') {
		eval("$"."oTemp = new ".$tempAssoOut."();"); 
		if (!is_null($oTemp->XML_inherited))
			$sXML = $oTemp->XML_inherited;
		else	$sXML = $oTemp->XML;
		$stack = xmlClassParse($sXML); 
		$tempAssoOutPrefixe = $stack[0]["attrs"]["PREFIX"];
	} 
	return Array(	'class'		=> $tempAsso,
			'prefix'		=> $tempAssoPrefixe,
			'full'		=> $tempAssoFull,
			'in'		=> $tempAssoIn,
			'in_name'	=> $tempAssoInName,
			'in_prefix'=> $tempAssoInPrefixe,
			'out'		=> $tempAssoOut,
			'out_name'	=> $tempAssoOutName,
			'out_prefix'=> $tempAssoOutPrefixe,
			'switch'		=> $tempSwitchField,
			'asymetric'	=> $tempAsymetric,
			'additem'	=> $tempAddItem,
			'status'		=> $tempStatus);

}


// function dbGetAssocies
// renvoie les éléments liés d'une association avec 'display' et 'abstract' éventuellement traduits...
//
// @param $oObjet	enregistrement de référence
// @param $sClasseAssoc	nom de la classe de liaison (n,m) ou enfant (1,n)
// @param $edit		retourne les assos existantes ET les assos possibles (MAJ)
// @param $check_status	force le check du statut si il existe
//
// retourne Array(	'asso'	=> Array(propriétés de l'association (et XML de la classe d'association ?)
//			'XML'	=> Array XML de la classe liée
//			'list'	=> Array LISTE des résultat

function dbGetAssocies($oObjet, $sClasseAssoc, $edit=false, $check_status=false) {
	global $stack, $db;

	//echo 'TEST : '.$sClasseAssoc.'<br/>';
	//eval("$"."oTemp = new ".$sClasseAssoc."();");
	//eval("$"."oTemp2 = new ".$sClasseAssoc."();");
	//eval("$"."oMyAsso = new ".$sClasseAssoc."();"); 

	$asso_list['asso'] = dbGetAssocProps($oObjet, $sClasseAssoc);
	// Ass asociation class XML to props array (not required anymore)
	//$asso_list['asso'] = array_merge($asso_list['asso'], $stackAssoc[0]);
	//viewArray($asso_list['asso']);

	$tempAsso = $asso_list['asso']['class'];
	$tempAssoPrefixe = $asso_list['asso']['prefix'];
	$tempAssoFull = $asso_list['asso']['full'];
	$tempAssoIn = $asso_list['asso']['in'];
	$tempAssoInName = $asso_list['asso']['in_name'];
	$tempAssoOut = $asso_list['asso']['out'];
	$tempAssoOutName  =$asso_list['asso']['out_name'];
	$tempSwitchField = $asso_list['asso']['switch'];
	$tempAsymetric = $asso_list['asso']['asymetric'];
	$tempStatus = $asso_list['asso']['status'];
	
	if ($tempAssoOut != "")
		$sTempClasse = $tempAssoOut;
	else	$sTempClasse = $tempAsso;

	//echo "TEST : ".$sTempClasse."<br />";
	
	if (class_exists($sTempClasse)){
		eval("$"."oTemp = new ".$sTempClasse."();");
	}
	else{
		echo "<!-- class unknown: ".$sTempClasse."  for asso ".$sClasseAssoc." -->";
		return false;
	}
	
	
	 
	// cas des deroulant d'id, pointage vers foreign
		 
	if (!is_null($oTemp->XML_inherited))
		$sXML = $oTemp->XML_inherited;
	else	$sXML = $oTemp->XML;
	xmlClassParse($sXML);
	$stackAssoOut = $stack;
	//viewArray($stackAssoOut[0]["attrs"], 'ASSO OUT');
	$asso_list['asso']['libelle'] = $stackAssoOut[0]["attrs"]["LIBELLE"];
	$aForeignXMLAttrs = $stackAssoOut[0]["attrs"];
	$aForeignXMLChildren = $stackAssoOut[0]["children"];
	$aForeignPrefixe = $stackAssoOut[0]["attrs"]["PREFIX"];

	// on connait $tempAssoOut -- on recommence la recherche de foreign vers $tempAssoOut

	$asso_block = '';

	$tempAbstract = $oTemp->getAbstract();
	$tempIsAbstractForeign = false;
	$tempForeignAbstract = "";
	$tempDisplay = $oTemp->getDisplay();
	$tempIsDisplayForeign = false;
	$tempForeignDisplay = "";
	$bStatut = false;
	$bCms_site = false; 
	
	//echo $sClasseAssoc;
	if (is_array($aForeignXMLChildren)) {
		foreach ($aForeignXMLChildren as $nodeId => $nodeValue) {	 
			//echo $nodeId.' '.$aForeignPrefixe.' '.$nodeValue["attrs"]["NAME"]."<br />";
			
			if (isset($nodeValue["attrs"]["NAME"])){
			
				if (preg_match ('/id_/', strtolower(stripslashes($nodeValue["attrs"]["NAME"]))) && $nodeId == 0 && preg_match ('/cms/', strtolower($aForeignXMLAttrs['NAME'])))
					$champ_id = strtolower(stripslashes($nodeValue["attrs"]["NAME"]));
				
				if (!empty($tempAbstract) && $nodeValue["attrs"]["NAME"] == $tempAbstract) {					
					$valueAbstract =$nodeValue["attrs"]["NAME"];
					$typeAbstract =$nodeValue["attrs"]["TYPE"];	 
					if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] != "") {
						$tempIsAbstractForeign = true;
						$tempForeignAbstract = $nodeValue["attrs"]["FKEY"];
					}
					if (isset($nodeValue["attrs"]["OPTION"]) && $nodeValue["attrs"]["OPTION"] == "node") { // cas d'option node, on considère la fkey cms_arbo_pages
						$tempIsAbstractForeign = true;
						$tempForeignAbstract = "cms_arbo_pages";
					}
				}
				
				if (!empty($tempDisplay) && $nodeValue["attrs"]["NAME"] == $tempDisplay) {					
					$valueDisplay =$nodeValue["attrs"]["NAME"];
					$typeDisplay =$nodeValue["attrs"]["TYPE"]; 		
					if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] != "") {
						$tempIsDisplayForeign = true;
						$tempForeignDisplay = $nodeValue["attrs"]["FKEY"];
					}
					if (isset($nodeValue["attrs"]["OPTION"]) && $nodeValue["attrs"]["OPTION"] == "node") {  // cas d'option node, on considère la fkey cms_arbo_pages
						$tempIsDisplayForeign = true;
						$tempForeignDisplay = "cms_arbo_pages";
					}
				}
				if (strtolower(stripslashes($nodeValue["attrs"]["NAME"])) == "statut")
					$bStatut = true; 
				if (isset($nodeValue["attrs"]["FKEY"]) && strtolower(stripslashes($nodeValue["attrs"]["FKEY"])) == "cms_site" && $track_translate == 0 && $sTempClasse != "cms_site") { 
					
					$bCms_site = true; 
					if ($sTempClasse == "cms_page") { 
						$champCms_site = strtolower(stripslashes($nodeValue["attrs"]["NAME"])); 	 
					}
					else  { 
						$champCms_site = $aForeignPrefixe."_".strtolower(stripslashes($nodeValue["attrs"]["NAME"])); 
						
					}
				}
			}	 
			
		}
	} 
	// Optimize association display
	// prepare Display retrieval
	// echo "TEST : display ".$tempForeignDisplay." : ".$tempIsDisplayForeign." / abstract ".$tempIsAbstractForeign." : ".$tempIsAbstractForeign."<br/>";
	if ($tempIsDisplayForeign) {
		// get reference object and XML for foreign display
		eval("$"."oTempForeignDisplay = new ".$tempForeignDisplay."();");
		if (!is_null($oTempForeignDisplay->XML_inherited))
			$sXML = $oTempForeignDisplay->XML_inherited;
		else	$sXML = $oTempForeignDisplay->XML;
	
		unset($stack);
		global $stack;
		$stack = array();
		xmlClassParse($sXML);
		$stackForeignDisplay = $stack;
		$foreignDisplayName = $stackForeignDisplay[0]["attrs"]["NAME"];
		$foreignDisplayPrefixe = $stackForeignDisplay[0]["attrs"]["PREFIX"];
		//foreach ($aForeignXMLChildren as $children) {
		foreach ($stackForeignDisplay[0]["children"] as $children) {
			if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($oTempForeignDisplay->getDisplay())) {
				$oForeignDisplayAttributes = $children['attrs'];
				break;
			}
		}
	} elseif(!empty($tempDisplay)) {
		foreach ($aForeignXMLChildren as $children) {
			if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($oTemp->getDisplay())) {
				$oForeignDisplayAttributes = $children['attrs'];
				break;
			}
		}
	}
	// prepare Abstract retrieval 
	if ($tempIsAbstractForeign) {
		eval("$"."oTempForeignAbstract = new ".$tempForeignAbstract."();");
		if (!is_null($oTempForeignAbstract->XML_inherited))
			$sXML = $oTempForeignAbstract->XML_inherited;
		else	$sXML = $oTempForeignAbstract->XML;
			 
		unset($stack);
		global $stack;
		$stack = array();
		xmlClassParse($sXML);
		$stackForeignAbstract = $stack;		
		$foreignAbstractName = $stackForeignAbstract[0]["attrs"]["NAME"];
		$foreignAbstractPrefixe = $stackForeignAbstract[0]["attrs"]["PREFIX"];
		//foreach ($aForeignXMLChildren as $children) {
		foreach ($stackForeignAbstract[0]["children"] as $children) {
			if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($oTempForeignAbstract->getDisplay())) {
				$oForeignAbstractAttributes = $children['attrs'];
				break;
			}
		}
	} elseif(!empty($tempAbstract)) {
		foreach ($aForeignXMLChildren as $children) {
			if ($children['name'] == 'ITEM' && $children['attrs']['NAME'] == strval($oTemp->getAbstract())) {
				$oForeignAbstractAttributes = $children['attrs'];
				break;
			}
		}
	}
	
	
	// ------------------- 
	// Test s'il y a un champ ordre dans la classe d'association
	$isOrderer = false;
	eval("$"."oTempAsso = new ".$tempAsso."();"); 
	// cas des deroulant d'id, pointage vers foreign
	$sXML = $oTempAsso->XML;
	xmlClassParse($sXML);
	$stackAssoOut = $stack;
	$aAssoXMLAttrs = $stackAssoOut[0]["attrs"];
	$aAssoXMLChildren = $stackAssoOut[0]["children"];	
	foreach ($aAssoXMLChildren as $children) { 
		if ($children['name'] == 'ITEM' && strtolower($children['attrs']['NAME']) == "ordre") {
			$isOrderer = true;
			break;
		}
	} 
	// ----------------------
	 
		
	if (!isset($champ_id) || ($champ_id == '')) {
		$champ_id = $aForeignXMLAttrs['PREFIX']."_id"; 
		$champ_display = $aForeignXMLAttrs['PREFIX']."_".$valueDisplay;
		$champ_abstract = $aForeignXMLAttrs['PREFIX']."_".$valueAbstract;
		$champ_temp_abstract = $aForeignXMLAttrs['PREFIX']."_".strval($oTemp->getAbstract());
	} else {
		$champ_display = $valueDisplay;
		$champ_abstract = $valueAbstract;
		$champ_temp_abstract = strval($oTemp->getAbstract());
	}
	
	

	// build queries depending on association mode
	if ($tempAssoFull && $tempAssoOut != '') {

		// Build optimized query
		// LEFT JOIN with MYSQL4+, ORACLE9+, PGSQL8+
		// anticipates translation to avoid using engine (which implies subqueries) on each result row
		$sql = "	SELECT DISTINCT ref.".$champ_id." AS ref_id  "; 
		if ($bStatut) $sql .= ", ref.".$aForeignXMLAttrs['PREFIX']."_statut AS ref_statut";
		
		if ($tempStatus)
			$sql .= ", asso.".$tempAssoPrefixe."_statut AS asso_statut";
		
		if (!empty($tempDisplay)) {
			if ($tempIsDisplayForeign) {
				$sql .= ",
						dfn.".$foreignDisplayPrefixe."_".strval($oTempForeignDisplay->getDisplay())." AS display";
			} else {
				if (DEF_APP_USE_TRANSLATIONS && isset($oForeignDisplayAttributes["TRANSLATE"])){
					if (DEF_APP_LANGUE == $_SESSION['id_langue'])
						// Default language
						$sql .= ",
							tsl.cms_crf_chaine AS display";
					else	// Translated element
						$sql .= ",
							tsltd.cms_ctd_chaine AS display,
							tsltd.cms_ctd_id_langue AS display_lang";
				} else {
					if ($sTempClasse == "cms_arbo_pages")
						$sql .= ",
							ref.".$champ_display." AS display";
					else	$sql .= ",
							ref.".$champ_display." AS display";
				}
			}
		}
		if (!empty($tempAbstract)) {
			if (DEF_APP_USE_TRANSLATIONS && isset($oForeignAbstractAttributes["TRANSLATE"])){
				if (DEF_APP_LANGUE == $_SESSION['id_langue']){
					if (isset($oForeignDisplayAttributes["TRANSLATE"]))
						// Default language
						$sql .= ",
							tsl2.cms_crf_chaine AS abstract";
					else	// Translated element
						$sql .= ",
							tsl.cms_crf_chaine AS abstract";
				} else {
					if (isset($oForeignDisplayAttributes["TRANSLATE"]))
						$sql .= ",
							tsltd2.cms_ctd_chaine AS abstract,
							tsltd2.cms_ctd_id_langue AS abstract_lang";
					else	$sql .= ",
							tsltd.cms_ctd_chaine AS abstract,
							tsltd.cms_ctd_id_langue AS abstract_lang";
				}
			} else {
				if ($tempIsAbstractForeign) {
					$sql .= ",
							afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay())." AS abstract";
				} else {
					if ($sTempClasse == "cms_arbo_pages")
						$sql .= ",
							ref.".$champ_abstract." AS abstract";
					elseif ($valueDisplay == $valueAbstract) 
						$sql .= "";
					else	$sql .= ",
							ref.".$champ_abstract." AS abstract";
				}
			}
		}
		$sql .= ",
						asso.".$tempAssoPrefixe."_".$tempAssoInName." AS fkey_1,
						asso.".$tempAssoPrefixe."_".$tempAssoOutName." AS fkey_2 ";
		
		///////	FOR ORDRE		
		if ($isOrderer)
			$sql .= ",
						asso.".$tempAssoPrefixe."_ordre AS ordre ";
		/////// END FOR ORDRE			
					
					
		///////	FOR SHOW		
		if ($oObjet->getClasse() == "cms_tag" || $oObjet->getClasse() == "cms_title" || $oObjet->getClasse() == "cms_description")
			$sql .= ",
						asso.".$tempAssoPrefixe."_classeid AS fkey_3 ";		
		/////// END FOR SHOW
		$sql .= " FROM		";
		if ($tempIsDisplayForeign)
			$sql .= $tempForeignDisplay." dfn,
					";
		if ($tempIsAbstractForeign)
			$sql .= $tempForeignAbstract." afn,
					";
		if (DEF_APP_USE_TRANSLATIONS && (isset($oForeignDisplayAttributes["TRANSLATE"]) || isset($oForeignAbstractAttributes["TRANSLATE"]))){
			if (DEF_APP_LANGUE == $_SESSION['id_langue']){
				/*// Default language
				$sql .= "cms_chaine_reference tsl,
						";
				if (isset($oForeignDisplayAttributes["TRANSLATE"]) && isset($oForeignAbstractAttributes["TRANSLATE"]))
					$sql .= "cms_chaine_reference tsl2,
							";*/
			} else {
				// Translated element
//				$sql .= "cms_chaine_reference tsl, cms_chaine_traduite tsltd,
//						";
//				if (isset($oForeignDisplayAttributes["TRANSLATE"]) && isset($oForeignAbstractAttributes["TRANSLATE"]))
//					$sql .= "cms_chaine_reference tsl2, cms_chaine_traduite tsltd2,
//						";
			}
		}
		$sql .= $aForeignXMLAttrs['NAME']." ref
			LEFT JOIN 	".$tempAsso." asso
			";

		if (isset($track_key)	&&	$track_key > 1)  /// WTF  ??$track_key n'est jamais setté
			$sql .= "ON		(ref.".$champ_id." = asso.".$tempAssoPrefixe."_".$tempAssoInName." OR ref.".$champ_id." = asso.".$tempAssoPrefixe."_".$tempAssoOutName.")
			";
		else	$sql .= "ON		ref.".$champ_id." = asso.".$tempAssoPrefixe."_".$tempAssoOutName."
			";
		// WHERE clause
		$where = Array();
		$whereTSL = Array();
		
		if ($tempIsDisplayForeign) {
			// Declared display is a foreign key
			$whereTSL[] = "dfn.".$foreignDisplayPrefixe."_id = ref.".$aForeignXMLAttrs['PREFIX']."_".strval($oTemp->getDisplay());
			if (DEF_APP_USE_TRANSLATIONS && $oForeignDisplayAttributes["TRANSLATE"]) {
				// translation of foreign reference
				if ($oForeignAttributes["TYPE"] == "int") {
					if ($oForeignAttributes["TRANSLATE"] == 'reference')
						$whereTSL[] = "tsl.cms_crf_id = dfn.".$foreignDisplayPrefixe."_".strval($oTempForeignDisplay->getDisplay());
				} elseif ($oForeignAttributes["TYPE"] == "enum") {
					if ($oForeignAttributes["TRANSLATE"] == "value")
						$whereTSL[] = "tsl.cms_crf_md5 = MD5(dfn.".$foreignDisplayPrefixe."_".strval($oTempForeignDisplay->getDisplay()).")";
				}
				if (DEF_APP_LANGUE != $_SESSION['id_langue']){
					// Translated element
					$whereTSL[] = "tsltd.cms_ctd_id_reference = tsl.cms_crf_id ";
					$whereTSL[] = "tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}";
					//$whereTSL[] = "tsltd.cms_ctd_chaine != ''";
				}
			}
		} elseif (DEF_APP_USE_TRANSLATIONS && isset($oForeignDisplayAttributes["TRANSLATE"])) {
			// Translation of local value
			if ($oForeignDisplayAttributes["TYPE"] == "int") {
				if ($oForeignDisplayAttributes["TRANSLATE"] == 'reference'){
                                    $whereTSL[] = $whereTSL_WHERE = "tsl.cms_crf_id = ref.".$champ_display;
                                    //$whereTSL[] = "tsl.cms_crf_id = ref.".$champ_display;
                                }
                                        
			} elseif ($oForeignDisplayAttributes["TYPE"] == "enum") {
				if ($oForeignDisplayAttributes["TRANSLATE"] == "value")
					$whereTSL[] = "tsl.cms_crf_md5 = MD5(ref.".$champ_display.")";
			}
			if (DEF_APP_LANGUE != $_SESSION['id_langue']){
				// Translated element
				$whereTSL[] = "tsltd.cms_ctd_id_reference = tsl.cms_crf_id ";
				$whereTSL[] = "tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}";
				$whereTSL[] = "tsltd.cms_ctd_chaine != ''";
			}
		}

		if ($tempIsAbstractForeign) {
			// Declared abstract is a foreign key
			$whereTSL[] = "afn.".$foreignAbstractPrefixe."_id = ref.".$champ_temp_abstract;
			if (DEF_APP_USE_TRANSLATIONS && $oForeignAbstractAttributes["TRANSLATE"]) {
				// Translation of foreign reference
				if ($oForeignAbstractAttributes["TYPE"] == "int") {
					if ($oForeignAbstractAttributes["TRANSLATE"] == 'reference') {
						if (isset($oForeignDisplayAttributes["TRANSLATE"]))
							$whereTSL[] = "tsl2.cms_crf_id = afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay());
						else	$whereTSL[] = "tsl.cms_crf_id = afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay());
					}
				} elseif ($oForeignAbstractAttributes["TYPE"] == "enum") {
					if ($oForeignAbstractAttributes["TRANSLATE"] == "value") {
						if (isset($oForeignDisplayAttributes["TRANSLATE"]))
							$whereTSL[] = "tsl2.cms_crf_md5 = MD5(afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay()).")";
						else	$whereTSL[] = "tsl.cms_crf_md5 = MD5(afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay()).")";
					}
				}
				if (DEF_APP_LANGUE != $_SESSION['id_langue']){
					// Translated element
					if (isset($oForeignDisplayAttributes["TRANSLATE"])) {
						$whereTSL[] = "tsltd2.cms_ctd_id_reference = tsl2.cms_crf_id ";
						$whereTSL[] = "tsltd2.cms_ctd_id_langue = {$_SESSION['id_langue']}";
						$whereTSL[] = "tsltd2.cms_ctd_chaine != ''";
					} else {
						$whereTSL[] = "tsltd.cms_ctd_id_reference = tsl.cms_crf_id ";
						$whereTSL[] = "tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}";
						$whereTSL[] = "tsltd.cms_ctd_chaine != ''";
					}
				}
			}
		} elseif (DEF_APP_USE_TRANSLATIONS && isset($oForeignAbstractAttributes["TRANSLATE"])) {
			// translation of local value
			if ($oForeignAbstractAttributes["TYPE"] == "int") {
				if ($oForeignAbstractAttributes["TRANSLATE"] == 'reference'){
					if ($oForeignDisplayAttributes["TRANSLATE"]){
                        $whereTSL[] = $whereTSL2_WHERE = "tsl2.cms_crf_id = ref.".$champ_abstract;
                        //$whereTSL[] = "tsl2.cms_crf_id = ref.".$champ_abstract;
                    }
					else	$whereTSL[] = "tsl.cms_crf_id = ref.".$champ_abstract;
                                }
			} elseif ($oForeignAbstractAttributes["TYPE"] == "enum") {
				if ($oForeignAbstractAttributes["TRANSLATE"] == "value")
					if (isset($oForeignDisplayAttributes["TRANSLATE"]))
						$whereTSL[] = "tsl2.cms_crf_md5 = MD5(ref.".$champ_abstract.")";
					else	$whereTSL[] = "tsl.cms_crf_md5 = MD5(ref.".$champ_abstract.")";
			}
			if (DEF_APP_LANGUE != $_SESSION['id_langue']){
				// Translated element
				if (isset($oForeignDisplayAttributes["TRANSLATE"])) {
					$whereTSL[] = "tsltd2.cms_ctd_id_reference = tsl2.cms_crf_id ";
					$whereTSL[] = "tsltd2.cms_ctd_id_langue = {$_SESSION['id_langue']}";
					$whereTSL[] = "tsltd2.cms_ctd_chaine != ''";
				} else {
					$whereTSL[] = "tsltd.cms_ctd_id_reference = tsl.cms_crf_id ";
					$whereTSL[] = "tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}";
					$whereTSL[] = "tsltd.cms_ctd_chaine != ''";
				}
			}
		}
		//////// FROM MAJ
		if ($bCms_site && ($tempAssoOutName!='classe')) { 
			$swhere_cms_site  = " ref.".$champCms_site." =".$_SESSION['idSite_travail'];
			if ($tempAssoOutName == 'cms_arbo_pages') $swhere_cms_site .= " OR ref.node_id = 0 "; // ajout de la racine (associée uniquement à l'idsite 1) pour la classe cms_arbo_pages
			$where[] = $swhere_cms_site;
		}
		
		if ( $tempAssoOutName =='cms_page' ) {
			$where[] = "ref.valid_page = 1 and ref.isgabarit_page = 0 ";
		}
		//////// END FROM MAJ
		
		 
		if ( $aForeignXMLAttrs['NAME'] == 'cms_arbo_pages' ) {
			$where[] = "ref.node_absolute_path_name  NOT LIKE '%_GABARIT%' ";
			$where[] = "ref.node_absolute_path_name  NOT LIKE '%_brique%' ";
		} 
		
		
		//////// STATUT MAJ
		if ($bStatut) { 
			$where[] = "ref.".$aForeignXMLAttrs['PREFIX']."_statut =".DEF_ID_STATUT_LIGNE;
		}
		//////// END STATUT MAJ 
		/*if (sizeof($whereTSL) > 0)
			//array_push ($where, " (	".implode("\nOR\t", $whereTSL)." ) ");
			array_push ($where, implode("\nAND\t", $whereTSL));
		*/	
			
		if (sizeof($whereTSL) > 0) {
			//array_push ($where, " (	".implode("\nOR\t", $whereTSL)." ) "); {
			//array_push ($where, implode("\nAND\t", $whereTSL));
			$where_temp = array();
			$where_tsl1=NULL;
			$where_tsl2=NULL;
			foreach ($whereTSL as $where_tsl) {
				if (preg_match ("/tsl2\./msi", $where_tsl)	&&	($where_tsl2==NULL)){
					 $where_tsl2 = $where_tsl;
					// echo 'where_tsl2 is '.$where_tsl2.'<br>';
				}
				elseif (preg_match ("/tsl\./msi", $where_tsl) &&	($where_tsl1==NULL)){
					$where_tsl1 = $where_tsl;
					// echo 'where_tsl1 is '.$where_tsl1.'<br>';
				}
				else   $where_temp[] = $where_tsl;
			} 
                                
			if (DEF_APP_USE_TRANSLATIONS && (isset($oForeignDisplayAttributes["TRANSLATE"]) || isset($oForeignAbstractAttributes["TRANSLATE"]))){
				if (DEF_APP_LANGUE == $_SESSION['id_langue']){
					// Default language
					$sql .= "LEFT JOIN cms_chaine_reference tsl ON ".$where_tsl1." ";
					if (isset($oForeignDisplayAttributes["TRANSLATE"]) && isset($oForeignAbstractAttributes["TRANSLATE"]))
						$sql .= "LEFT JOIN  cms_chaine_reference tsl2 ON ".$where_tsl2." ";
				} else {
                                    
                                    $sql .= "LEFT JOIN cms_chaine_reference tsl ON ".$whereTSL_WHERE." ";
                                    $sql .= "LEFT JOIN cms_chaine_traduite tsltd ON ".$where_tsl1." ";
                                    
					// Translated element
					//$sql .= "cms_chaine_reference tsl, cms_chaine_traduite tsltd,";
					if (isset($oForeignDisplayAttributes["TRANSLATE"]) && isset($oForeignAbstractAttributes["TRANSLATE"])){
                                            $sql .= "LEFT JOIN cms_chaine_reference tsl2 ON ".$whereTSL2_WHERE." ";
                                            $sql .= "LEFT JOIN cms_chaine_traduite tsltd2 ON ".$where_tsl2." ";
                                        }
				}
			}
			
			if  (sizeof($where_temp) > 0) {
				array_push ($where, implode("\nAND\t", $where_temp));
			}
			
		}	
			
		if (!empty($where) && sizeof($where) > 0  )
			$sql .= "WHERE	".implode("\nAND\t", $where)." ";
			
		
		// group by 
		//$sql .= "	GROUP BY  ref_id  "; 
			
		// order clause
		if ($valueAbstract!="" || $valueDisplay!="") {
			$order_by = Array();
			if (!empty($aForeignXMLAttrs['DEF_ORDER_FIELD'])) {
				$order_by[] = "ref.".$aForeignXMLAttrs['PREFIX']."_".$aForeignXMLAttrs['DEF_ORDER_FIELD'].(!empty($aForeignXMLAttrs['DEF_ORDER_DIRECTION']) ? ' '.$aForeignXMLAttrs['DEF_ORDER_DIRECTION'] : '');
			} elseif ($typeDisplay == "date" || $typeAbstract == "date") {
				if (!empty($tempDisplay) && $typeDisplay == "date")
					$order_by[] = "ref.".$champ_display." DESC";
				if (!empty($tempAbstract) && $typeAbstract == "date")
					//$order_by[] = "ref.".$champ_abstract." DESC";
					if ($valueDisplay != $valueAbstract) 
						$order_by[] = "abstract DESC";
			} else {
				if (!empty($tempDisplay) && $typeDisplay != "date")
					if ( $valueDisplay != '') 
						$order_by[] = "display ASC"; 
					else 
						$order_by[] = "ref.".$champ_display." ASC"; 
				if (!empty($tempAbstract) && $typeAbstract != "date")
					//$order_by[] = "ref.".$champ_abstract." ASC"; 
					if ($valueDisplay != $valueAbstract) 
						$order_by[] = "abstract ASC";
			}
			if (!empty($order_by))
				$sql .= "ORDER BY ".implode(', ', $order_by);
		}
		//echo $sql."<br/>";
	} else {
		// debut traitement sans table asso ----------				
			
		if ($tempAsso != '') {
			
			// Build optimized query
			// anticipates translation to avoid using engine (which implies subqueries) on each result row
			$sql = "	SELECT		ref.".$champ_id." AS ref_id"; 
			if ($edit)
				$sql .= ",
						ref.".$aForeignXMLAttrs['PREFIX']."_".$tempAssoInName." as ref_fkey";
			if ($bStatut)
				$sql .= ", ref.".$aForeignXMLAttrs['PREFIX']."_statut AS ref_statut";
			
			/****/
			if (!empty($tempDisplay)) {
				if (DEF_APP_USE_TRANSLATIONS && isset($oForeignDisplayAttributes["TRANSLATE"])){
					if (DEF_APP_LANGUE == $_SESSION['id_langue'])
						// Default language
						$sql .= ",
							tsl.cms_crf_chaine AS display";
					else	// Translated element
						$sql .= ",
							tsltd.cms_ctd_chaine AS display,
							tsltd.cms_ctd_id_langue AS display_lang";
				} else {
					if ($tempIsDisplayForeign) {
						$sql .= ",
								dfn.".$foreignDisplayPrefixe."_".strval($oTempForeignDisplay->getDisplay())." AS display";
					} else {
						//if ($sTempClasse == "cms_arbo_pages")
						$sql .= ",
							ref.".$champ_display." AS display";
						//else	$sql .= ",
						//		ref.".$champ_display." AS display";
					}
				}
			}
                        
			if (!empty($tempAbstract)) {
				if (DEF_APP_USE_TRANSLATIONS && isset($oForeignAbstractAttributes["TRANSLATE"])){
					if (DEF_APP_LANGUE == $_SESSION['id_langue']){
						if (isset($oForeignDisplayAttributes["TRANSLATE"]))
							// Default language
							$sql .= ",
								tsl2.cms_crf_chaine AS abstract";
						else	// Translated element
							$sql .= ",
								tsl.cms_crf_chaine AS abstract";
					} else {
						if (isset($oForeignDisplayAttributes["TRANSLATE"]))
							$sql .= ",
								tsltd2.cms_ctd_chaine AS abstract,
								tsltd2.cms_ctd_id_langue AS abstract_lang";
						else	$sql .= ",
								tsltd.cms_ctd_chaine AS abstract,
								tsltd.cms_ctd_id_langue AS abstract_lang";
					}
				} else {
					if ($tempIsAbstractForeign) {
						$sql .= ",
								afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay())." AS abstract";
					} else {
						if ($sTempClasse == "cms_arbo_pages")
							$sql .= ",
								ref.".$champ_abstract." AS abstract";
						elseif ($valueDisplay == $valueAbstract) 
							$sql .= "";
						else	$sql .= ",
								ref.".$champ_abstract." AS abstract";
					}
				}
			}

			$sql .= " FROM		";
			if ($tempIsDisplayForeign)
				$sql .= $tempForeignDisplay." dfn,
						";
			if ($tempIsAbstractForeign)
				$sql .= $tempForeignAbstract." afn,
						";
			if (DEF_APP_USE_TRANSLATIONS && ($oForeignDisplayAttributes["TRANSLATE"] || $oForeignAbstractAttributes["TRANSLATE"])){
				if (DEF_APP_LANGUE == $_SESSION['id_langue']){
					// Default language
					$sql .= "cms_chaine_reference tsl,
							";
					if (isset($oForeignDisplayAttributes["TRANSLATE"]) && isset($oForeignAbstractAttributes["TRANSLATE"]))
						$sql .= "cms_chaine_reference tsl2,
							";
				} else {
					// Translated element
					$sql .= "cms_chaine_reference tsl, cms_chaine_traduite tsltd,
							";
					if (isset($oForeignDisplayAttributes["TRANSLATE"]) && isset($oForeignAbstractAttributes["TRANSLATE"]))
						$sql .= "cms_chaine_reference tsl2, cms_chaine_traduite tsltd2,
							";
				}
			}
			$sql .= $aForeignXMLAttrs['NAME']." ref
				";
			// where clause
			$where = Array();
			if ($tempSwitchField != '')
				// fkey_switch filter
				$where[] = "ref.".$aForeignXMLAttrs['PREFIX']."_".$tempSwitchField." = '".$tempSwitchValue."'";
			if ($edit)
				$where[] = "(ref.".$aForeignXMLAttrs['PREFIX']."_".$tempAssoInName." = ".$oObjet->get_id()." OR ref.".$aForeignXMLAttrs['PREFIX']."_".$tempAssoInName." = -1)";
			else	$where[] = "ref.".$aForeignXMLAttrs['PREFIX']."_".$tempAssoInName." = ".$oObjet->get_id();

			if ($tempIsDisplayForeign) {
				if ($edit	&&	$oObjet->get_id()==-1){
					// pas de where sur le les items associés matchant l'item édité.					
				}
				else{
					$where[] = "dfn.".$foreignDisplayPrefixe."_id = ref.".$aForeignXMLAttrs['PREFIX']."_".strval($oTemp->getDisplay());	
				}
				
				if (DEF_APP_USE_TRANSLATIONS && $oForeignDisplayAttributes["TRANSLATE"]) {
					// translation of foreign reference
					if ($oForeignDisplayAttributes["TYPE"] == "int") {
						if ($oForeignDisplayAttributes["TRANSLATE"] == 'reference')
							$where[] = "tsl.cms_crf_id = dfn.".$foreignDisplayPrefixe."_".strval($oTempForeignDisplay->getDisplay());
					} elseif ($oForeignDisplayAttributes["TYPE"] == "enum") {
						if ($oForeignDisplayAttributes["TRANSLATE"] == "value")
							$where[] = "tsl.cms_crf_md5 = MD5(dfn.".$foreignDisplayPrefixe."_".strval($oTempForeignDisplay->getDisplay()).")";
					}
					if (DEF_APP_LANGUE != $_SESSION['id_langue']){
						// Translated element
						$where[] = "tsltd.cms_ctd_id_reference = tsl.cms_crf_id ";
						$where[] = "tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}";
						$where[] = "tsltd.cms_ctd_chaine != ''";
					}
				}
			} elseif (DEF_APP_USE_TRANSLATIONS && $oForeignDisplayAttributes["TRANSLATE"]) {
				// translation of local value
				if ($oForeignDisplayAttributes["TYPE"] == "int") {
					if ($oForeignDisplayAttributes["TRANSLATE"] == 'reference')
						$where[] = "tsl.cms_crf_id = ref.".$champ_display;
				} elseif ($oForeignDisplayAttributes["TYPE"] == "enum") {
					if ($oForeignDisplayAttributes["TRANSLATE"] == "value")
						$where[] = "tsl.cms_crf_md5 = MD5(ref.".$champ_display.")";
				}
				if (DEF_APP_LANGUE != $_SESSION['id_langue']){
					// Translated element
					$where[] = "tsltd.cms_ctd_id_reference = tsl.cms_crf_id ";
					$where[] = "tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}";
					$where[] = "tsltd.cms_ctd_chaine != ''";
				}
			}
			if ($tempIsAbstractForeign) {
				$where[] = "afn.".$foreignAbstractPrefixe."_id = ref.".$champ_temp_abstract;
				if (DEF_APP_USE_TRANSLATIONS && $oForeignAbstractAttributes["TRANSLATE"]) {
					// translation of foreign reference
					if ($oForeignAbstractAttributes["TYPE"] == "int") {
						if ($oForeignAbstractAttributes["TRANSLATE"] == 'reference') {
							if (isset($oForeignDisplayAttributes["TRANSLATE"]))
								$where[] = "tsl2.cms_crf_id = afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay());
							else	$where[] = "tsl.cms_crf_id = afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay());
						}
					} elseif ($oForeignAbstractAttributes["TYPE"] == "enum") {
						if ($oForeignAbstractAttributes["TRANSLATE"] == "value") {
							if (isset($oForeignDisplayAttributes["TRANSLATE"]))
								$where[] = "tsl2.cms_crf_md5 = MD5(afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay()).")";
							else	$where[] = "tsl.cms_crf_md5 = MD5(afn.".$foreignAbstractPrefixe."_".strval($oTempForeignAbstract->getDisplay()).")";
						}
					}
					if (DEF_APP_LANGUE != $_SESSION['id_langue']){
						// Translated element
						if (isset($oForeignDisplayAttributes["TRANSLATE"])) {
							$where[] = "tsltd2.cms_ctd_id_reference = tsl2.cms_crf_id ";
							$where[] = "tsltd2.cms_ctd_id_langue = {$_SESSION['id_langue']}";
							$where[] = "tsltd2.cms_ctd_chaine != ''";
						} else {
							$where[] = "tsltd.cms_ctd_id_reference = tsl.cms_crf_id ";
							$where[] = "tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}";
							$where[] = "tsltd.cms_ctd_chaine != ''";
						}
					}
				}
			} elseif (DEF_APP_USE_TRANSLATIONS && $oForeignAbstractAttributes["TRANSLATE"]) {
				// translation of local value
				if ($oForeignAbstractAttributes["TYPE"] == "int") {
					if ($oForeignAbstractAttributes["TRANSLATE"] == 'reference') {
						if ($oForeignDisplayAttributes["TRANSLATE"])
							$where[] = "tsl2.cms_crf_id = ref.".$champ_abstract;
						else	$where[] = "tsl.cms_crf_id = ref.".$champ_abstract;
					}
				} elseif ($oForeignAbstractAttributes["TYPE"] == "enum") {
					if ($oForeignAbstractAttributes["TRANSLATE"] == "value") {
						if (isset($oForeignDisplayAttributes["TRANSLATE"]))
							$where[] = "tsl2.cms_crf_md5 = MD5(ref.".$champ_abstract.")";
						else	$where[] = "tsl.cms_crf_md5 = MD5(ref.".$champ_abstract.")";
					}
				}
				if (DEF_APP_LANGUE != $_SESSION['id_langue']) {
					// Translated element
					if (isset($oForeignDisplayAttributes["TRANSLATE"])) {
						$where[] = "tsltd2.cms_ctd_id_reference = tsl2.cms_crf_id ";
						$where[] = "tsltd2.cms_ctd_id_langue = {$_SESSION['id_langue']}";
						$where[] = "tsltd2.cms_ctd_chaine != ''";
					} else {
						$where[] = "tsltd.cms_ctd_id_reference = tsl.cms_crf_id ";
						$where[] = "tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}";
						$where[] = "tsltd.cms_ctd_chaine != ''";
					}
				}
			}
			if (!empty($where))
				$sql .= "WHERE	".implode("\nAND\t", $where)."
				";
			// group by 
			//$sql .= " GROUP BY ref_id ";	
				
			// order clause
			if ($valueAbstract!="" || $valueDisplay!="") { 
				$order_by = Array();
				if (!empty($aForeignXMLAttrs['DEF_ORDER_FIELD'])) {
					$order_by[] = "ref.".$aForeignXMLAttrs['PREFIX']."_".$aForeignXMLAttrs['DEF_ORDER_FIELD'].(!empty($aForeignXMLAttrs['DEF_ORDER_DIRECTION']) ? ' '.$aForeignXMLAttrs['DEF_ORDER_DIRECTION'] : '');
				} elseif ($typeDisplay == "date" || $typeAbstract == "date") {
					if ($typeDisplay == "date")
						$order_by[] = "ref.".$champ_display." DESC";
					if ($typeAbstract == "date")
						$order_by[] = "ref.".$champ_abstract." DESC";
				} else {
					if ($typeDisplay != "date")
						$order_by[] = "ref.".$champ_display." ASC"; 
					if ($typeAbstract != "date")
						$order_by[] = "ref.".$champ_abstract." ASC"; 
				}
				if (!empty($order_by))
					$sql .= "ORDER BY ".implode(', ', $order_by);
			}
			//echo 'SQL : '.$sql."<br/>";
			
		}
		// fin affichage asso SANS table d'asso ----------------------		
	}
	
	//echo $sql;
	
	$res = $db->Execute($sql);
	//$asso_list['XML'] = $stackAssoc[0];
	$asso_list['XML'] = $stackAssoOut[0];
	$asso_list['list'] = Array();
	//pre_dump($sql); die();
	$found = array();
	if ($res) {
		while(!$res->EOF) {
			$row = $res->fields;
			
			// Do not handle current record in case of association between elements of the SAME table
			if (isset($track_key)	&&	$track_key > 1 && isset($row['ref_id'])	&&	$row['ref_id'] == $oObjet->get_id()) {
				$res->MoveNext();
				continue;
			}
			// Unless specified, do not handle inactive records
			if ($check_status) {
				if ($oTemp->getGetterStatut() != "none")
					$tempStatus = $row['ref_statut'];
				else	$tempStatus = DEF_ID_STATUT_LIGNE;
				if ($tempStatus != DEF_ID_STATUT_LIGNE) {
					$res->MoveNext();
					continue;
				}
			}
			// handle row
			if ($tempAssoFull && $tempAssoOut != '') {
				//echo "TEST fkey_1 : ".$row['fkey_1']." | fkey_2 : ".$row['fkey_2']." | ref_id : ".$row['ref_id']." | OBJ ID : ".$oObjet->get_id()."<br/>";
				if (($row['fkey_1'] == $oObjet->get_id() && $row['fkey_2'] == $row['ref_id']) || ($row['fkey_1'] == $row['ref_id'] && $row['fkey_2'] == $oObjet->get_id())){					
					$asso_list['list'][] = $row;
				}
				elseif ($edit){
					$asso_list['list'][] = $row;
				}
			} elseif ($tempAsso != ''){
				$asso_list['list'][] = $row;
			}
                                
			$res->MoveNext();
		}
	}
	// Cleanup verifying languages
	//foreach ($asso_list['list'] as $key => $linked) {
	//	viewArray($linked);
	//}

	return (array) $asso_list;
}
?>