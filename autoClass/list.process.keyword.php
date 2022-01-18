<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


$oRech = new dbRecherche();

//////////////////////////
// recherche par mot clé
//////////////////////////
// who rules ?
// 1 POST, 2 GET, 3 SESSION
if (is_post('sTexte', false)){
	$sTexte=trim($_POST['sTexte']);
	$_SESSION['sTexte']=$sTexte;
} elseif (is_get('sTexte', false)){
	$sTexte=trim($_GET['sTexte']);
	$_SESSION['sTexte']=$sTexte;
} else	$sTexte = $_SESSION['sTexte'];

$sTexte = addslashes ($sTexte);
if ($operation == "REINIT")
	unset($_SESSION['sTexte']);


if ($sTexte != "") {
	//$_SESSION['sTexte']=$sTexte;
	$aTable = array();
	$oRech = new dbRecherche();
	
	$oRech->setValeurRecherche("declencher_recherche");
	$aTable[$classeName] = array();  
	  
	$cptvarchar=0;
	$cptfkey=0;
	$cptcms_chaine_reference=0;
	$cptcms_chaine_traduite=0;
	$cpt_texte_in_classe=0;
	$cptcms_arbo_pages=0;
	$aFKey = array();
	//on compte le nombre de varchar dans la classe
	for ($i=0; $i<count($aNodeToSort); $i++) {
		if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) {
			$cptvarchar++;
		} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "int" && isset($aNodeToSort[$i]["attrs"]["FKEY"]) && $aNodeToSort[$i]["attrs"]["FKEY"] != $classeName && $aNodeToSort[$i]["attrs"]["FKEY"] != "null") {
			$cptfkey++;
			$aTable[$aNodeToSort[$i]["attrs"]["FKEY"]] = array();  
			eval ("$"."oFkClasse = new  ".$aNodeToSort[$i]["attrs"]["FKEY"]."();");
			if(!is_null($oFkClasse->XML_inherited))
				$sXML = $oFkClasse->XML_inherited;
			else
				$sXML = $oFkClasse->XML;
			//$sXML = $oTempForeignDisplay->XML; 
			unset($stack);
			$stack = array();
			xmlClassParse($sXML);
			 
			$fkeyPrefixe = $stack[0]["attrs"]["PREFIX"];
			$fkeyNodeToSort = $stack[0]["children"];
			$valueFkDisplay = "";
			$typeFkDisplay = ""; 
			$translateFkDisplay = "";   
			$valueFkAbstract = "";
			$typeFkAbstract = ""; 
			$translateFkAbstract = "";  
			$aFKey [$aNodeToSort[$i]["attrs"]["FKEY"]] = array();	
			//echo  "-------------------".$oFkClasse->getDisplay()." ".$oFkClasse->getAbstract()."<br />"; 
			foreach ($fkeyNodeToSort as $nodeId => $nodeValue) {	
				 if ($nodeValue["attrs"]["NAME"] == strval($oFkClasse->getDisplay())) { 
					$valueFkDisplay = $nodeValue["attrs"]["NAME"];
					$typeFkDisplay = $nodeValue["attrs"]["TYPE"]; 
					$translateFkDisplay = $nodeValue["attrs"]["TRANSLATE"]; 
					if ($translateFkDisplay == 'reference') { 
						$cptcms_chaine_reference++;
						$cptcms_chaine_traduite++; 
					}
					$aFKey [$aNodeToSort[$i]["attrs"]["FKEY"]]["DISPLAY_NAME"] = $valueFkDisplay;
					$aFKey [$aNodeToSort[$i]["attrs"]["FKEY"]]["DISPLAY_TYPE"] = $typeFkDisplay;
					$aFKey [$aNodeToSort[$i]["attrs"]["FKEY"]]["DISPLAY_TRANSLATE"] = $translateFkDisplay;
				}	
				 if ($nodeValue["attrs"]["NAME"] == strval($oFkClasse->getAbstract()) && strval($oFkClasse->getAbstract()) != strval($oFkClasse->getDisplay())) { 
					$valueFkAbstract = $nodeValue["attrs"]["NAME"];
					$typeFkAbstract = $nodeValue["attrs"]["TYPE"]; 
					$translateFkAbstract = $nodeValue["attrs"]["TRANSLATE"]; 
					if ($translateFkAbstract == 'reference') { 
						$cptcms_chaine_reference++;
						$cptcms_chaine_traduite++; 
					}
					$aFKey [$aNodeToSort[$i]["attrs"]["FKEY"]]["ABSTRACT_NAME"] = $valueFkAbstract;
					$aFKey [$aNodeToSort[$i]["attrs"]["FKEY"]]["ABSTRACT_TYPE"] = $typeFkAbstract;
					$aFKey [$aNodeToSort[$i]["attrs"]["FKEY"]]["ABSTRACT_TRANSLATE"] = $translateFkAbstract;
				}	
			}
		} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "int" && isset($aNodeToSort[$i]["attrs"]["TRANSLATE"])) {
			$cptcms_chaine_reference++;
			$cptcms_chaine_traduite++; 
		} elseif ( ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") && ($aNodeToSort[$i]["attrs"]["OPTION"] == "node") /*&& preg_match ("/".$_SESSION["site_langue"]."/i",  $aNodeToSort[$i]["attrs"]["NAME"])*/) {
			 $cptcms_arbo_pages++;
		}
	}
	
	 
	// ------------------------------------ TRADUCTION ---------------------------------- // 
	
	$sqlTLS = '';
	$aCondTLS = array ();

	if ($cptcms_chaine_reference > 0) {
		$sqlTLS = 'select distinct * from cms_chaine_reference where ';
		$aCondTLS = array ();
		array_push ($aCondTLS, "cms_crf_chaine LIKE '%".$sTexte."%'");
	}
	if ($cptcms_chaine_traduite > 0) {
		$sqlTLS = 'select distinct * from cms_chaine_reference LEFT OUTER JOIN cms_chaine_traduite ON cms_chaine_traduite.cms_ctd_id_reference = cms_chaine_reference.cms_crf_id where  ';
		$aCondTLS = array ();  
		array_push ($aCondTLS, "cms_crf_chaine LIKE '%".$sTexte."%'");
		array_push ($aCondTLS, "cms_ctd_chaine LIKE '%".$sTexte."%'");

	}
	
	if (($cptcms_chaine_reference > 0 || $cptcms_chaine_traduite > 0) && newSizeOf($aCondTLS) > 0 ) {
		$sqlTLS.= "(".join(" OR ", $aCondTLS).")";
		
		//echo $sqlTLS."<br /><br />";
		$aCacheIdTLS_ref =  array();
		$aCacheIdTLS_trad =  array();
		
		$aObjects = dbGetObjectsFromRequete('cms_chaine_reference', $sqlTLS);	
		
		if (newSizeOf( $aObjects ) > 0) {
			foreach ($aObjects as $oObject) {
				//echo $oObject->get_id()."<br />";	
				array_push ($aCacheIdTLS_ref, $oObject->get_id());
			}
			$in_select_ref = implode(",", $aCacheIdTLS_ref);
			//echo "<br />REF : ".sizeof ($aCacheIdTLS_ref)."<br />";
		}
		
		$aObjects = dbGetObjectsFromRequete('cms_chaine_traduite', $sqlTLS);	
		
		if (newSizeOf( $aObjects ) > 0) {
			foreach ($aObjects as $oObject) {
				//echo $oObject->get_id()."<br />";	
				array_push ($aCacheIdTLS_trad, $oObject->get_id());
			}
			$in_select_trad = implode(",", $aCacheIdTLS_trad);
			//echo "<br />TRAD ".sizeof ($aCacheIdTLS_trad)."<br />";
		}
	}
	//echo "<br />".$sqlTLS."<br /><br />";
	 
	// ------------------------------------ TRADUCTION ---------------------------------- //  
	if ($cptcms_arbo_pages > 0)
		$aTable["cms_arbo_pages"] = array();  

	//construction de la requete dynamique
	//echo "--".DEF_APP_LANGUE ."--".$_SESSION['id_langue'];
	$aCond = array(); 
	for ($i=0; $i<count($aNodeToSort); $i++) {

		if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) {
			$aCond[$classeName][] = "{$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]} like '%".$sTexte."%' ";
			$cpt_texte_in_classe++;

		} elseif (($aNodeToSort[$i]["attrs"]["TYPE"] == "int")  && isset ($aNodeToSort[$i]["attrs"]["FKEY"])  &&  ($aNodeToSort[$i]["attrs"]["FKEY"] != $classeName)	&&  $aNodeToSort[$i]["attrs"]["FKEY"] != "null") {  
		
			eval ("$"."oFkClasse = new  ".$aNodeToSort[$i]["attrs"]["FKEY"]."();");
			if (!is_null($oFkClasse->XML_inherited))
				$sXML = $oFkClasse->XML_inherited;
			else	$sXML = $oFkClasse->XML;
			//$sXML = $oTempForeignDisplay->XML; 
			unset($stack);
			$stack = array();
			xmlClassParse($sXML);
			 
			$fkeyClasseName = $stack[0]["attrs"]["NAME"];
			$fkeyPrefixe = $stack[0]["attrs"]["PREFIX"];
			$fkeyNodeToSort = $stack[0]["children"];
			$valueFkDisplay = "";
			$typeFkDisplay = ""; 
			$translateFkDisplay = "";   
			$valueFkAbstract = "";
			$typeFkAbstract = ""; 
			$translateFkAbstract = "";  
			
			$aListeChamps=getObjetListeChamps($oFkClasse);
			 
			
			/*
				
			//echo  "-------------------".$oFkClasse->getDisplay()." ".$oFkClasse->getAbstract()."<br />"; 
			foreach ($fkeyNodeToSort as $nodeId => $nodeValue) {	
				 if ($nodeValue["attrs"]["NAME"] == strval($oFkClasse->getDisplay())) { 
					$valueFkDisplay = $nodeValue["attrs"]["NAME"];
					$typeFkDisplay = $nodeValue["attrs"]["TYPE"]; 
					$translateFkDisplay = $nodeValue["attrs"]["TRANSLATE"]; 
				}	
				 if ($nodeValue["attrs"]["NAME"] == strval($oFkClasse->getAbstract()) && strval($oFkClasse->getAbstract()) != strval($oFkClasse->getDisplay())) { 
					$valueFkAbstract = $nodeValue["attrs"]["NAME"];
					$typeFkAbstract = $nodeValue["attrs"]["TYPE"]; 
					$translateFkAbstract = $nodeValue["attrs"]["TRANSLATE"]; 
				}	
			}*/
			
			
			$valueFkDisplay = $aFKey [$fkeyClasseName]["DISPLAY_NAME"];
			$typeFkDisplay = $aFKey [$fkeyClasseName]["DISPLAY_TYPE"];
			$translateFkDisplay = $aFKey [$fkeyClasseName]["DISPLAY_TRANSLATE"];
			$valueFkAbstract = $aFKey [$fkeyClasseName]["ABSTRACT_NAME"];
			$typeFkAbstract = $aFKey [$fkeyClasseName]["ABSTRACT_TYPE"];
			$translateFkAbstract = $aFKey [$fkeyClasseName]["ABSTRACT_TRANSLATE"];
			 
			$aTable[$fkeyClasseName][] = "{$fkeyClasseName}.".getCorrectField($aListeChamps, $fkeyPrefixe, $oFkClasse->getFieldPK())." = {$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]} ";
			//$aTable[$fkeyClasseName][] = "{$fkeyClasseName}.{$fkeyPrefixe}_id = {$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]} ";

			// display
			if ($typeFkDisplay == "varchar" || $typeFkDisplay == "text"){
				$aCond[$fkeyClasseName][] = "{$fkeyClasseName}.".getCorrectField ($aListeChamps, $fkeyPrefixe, $oFkClasse->getDisplay())." like '%".$sTexte."%' ";
				$cpt_texte_in_classe++;
				//$aCond[$fkeyClasseName][] = "{$fkeyClasseName}.{$fkeyPrefixe}_".$oFkClasse->getDisplay()." like '%".$sTexte."%' ";
			}
			else if ($typeFkDisplay == "int") {
				if ($translateFkDisplay == 'reference' && $in_select_ref != '')
					$aCond[$fkeyClasseName][] = "{$fkeyClasseName}.{$fkeyPrefixe}_{$valueFkDisplay} IN ({$in_select_ref}) ";
			} elseif ($typeFkDisplay == "enum") {
				if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == "value" && $in_select_trad != '')
					$aCond[$fkeyClasseName][] = "{$fkeyClasseName}.{$fkeyPrefixe}_{$valueFkDisplay} IN ({$in_select_trad}) ";
			}
			
			// abstract 
			if ($typeFkAbstract == "varchar" || $typeFkAbstract == "text"){
				$aCond[$fkeyClasseName][] = "{$fkeyClasseName}.".getCorrectField ($aListeChamps, $fkeyPrefixe, $oFkClasse->getAbstract())." like '%".$sTexte."%' ";
				$cpt_texte_in_classe++;
				//$aCond[$fkeyClasseName][] = "{$fkeyClasseName}.{$fkeyPrefixe}_{$oFkClasse->getAbstract()} like '%".$sTexte."%' ";
			}
			else if ($typeFkAbstract == "int") {
				if ($translateFkAbstract == 'reference' && $in_select_ref != '')
					$aCond[$fkeyClasseName][] = "{$fkeyClasseName}.{$fkeyPrefixe}_{$valueFkAbstract} IN ({$in_select_ref}) ";
			} elseif ($typeFkAbstract == "enum") {
				if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == "value" && $in_select_trad != '')
					$aCond[$fkeyClasseName][] = "{$fkeyClasseName}.{$fkeyPrefixe}_{$valueFkAbstract} IN ({$in_select_trad}) ";
			}

		} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "int" && isset($aNodeToSort[$i]["attrs"]["TRANSLATE"])) {
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") {
				if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference' && $in_select_ref != '')
					$aCond[$classeName][] = "{$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]} IN ({$in_select_ref}) ";
			} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") {
				if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == "value" && $in_select_trad != '')
					$aCond[$classeName][] = "{$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]} IN ({$in_select_trad}) ";
			}

		} elseif ( ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") && ($aNodeToSort[$i]["attrs"]["OPTION"] == "node") ) { 
			
			$aTable['cms_arbo_pages'][] = "node_id = {$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]} ";
			$aCond['cms_arbo_pages'][] = "cms_arbo_pages.node_absolute_path_name like'%".$sTexte."%' and {$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]} != -1 ";
			$aCond['cms_arbo_pages'][] = "cms_arbo_pages.node_absolute_path_name like'%".$sTexte."%' and {$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]} != -1 ";
			$cpt_texte_in_classe++;
		}
	}
	   
	 
	$sRechercheTable = $classeName;
	 
	//viewArray($aTable, 'table link conditions');
	//viewArray($aCond, 'table search conditions');
	foreach ($aTable as $t => $conds) {
		if ($t != $classeName && !empty($aCond[$t])) {
			if (newSizeOf($aTable) > 1)
				$sRechercheTable .= " LEFT OUTER JOIN ".$t." ON " ;
			if (!empty($conds))
				$sRechercheTable .= implode(" AND ", $conds); 
		}
	}  
	 
	
	$tmpconds = Array();
	foreach ($aCond as $t => $conds)
		$tmpconds = array_merge($tmpconds, $conds);
	
	if (newSizeOf($aCond) > 0) $oRech->setJointureBD("(".implode(" OR ", $tmpconds).")");
	else $oRech->setJointureBD(  $fkeyPrefixe."_id = -1");
	$oRech->setTableBD($sRechercheTable);
	$oRech->setPureJointure(1);
	  
	$aRecherche[] = $oRech; 
		
		
}//fin if ($sTexte != "")


?>