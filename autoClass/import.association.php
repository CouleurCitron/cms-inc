<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 

$eKeyValue = getItemValue($oRes, "id");			
$aTempClasse = array();
$aTempClasse = explode(',', $liste_asso);		
if ($bImportIds == false){
	$nb_champs =  sizeof($oRes->getListeChamps()) - 1 ;	
}
else {
	$nb_champs =  sizeof($oRes->getListeChamps())  ;	
}

for ($m=0; $m<sizeof($aTempClasse);$m++) {
	$sTempClasse = trim($aTempClasse[$m]);  
	
	
	
	eval("$"."_oTemp = new ".$sTempClasse."();");
	
	// cas des deroulant d'id, pointage vers foreign
	//$sXML = $aForeign[0]->XML;
	$sXML = $_oTemp->XML;

	unset($stack);
	$stack = array();
	xmlClassParse($sXML);

	$_foreignName = $stack[0]["attrs"]["NAME"];
	$_foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
	$_foreignNodeToSort = $stack[0]["children"];
	$_tempIsAbstractForeign = false;
	$_tempForeignAbstract = "";
	$_tempIsDisplayForeign = false;
	$_tempForeignDisplay = "";
	
	// je récupère la valeur dans le csv
	$aChamps[] =  $_foreignName;
	//echo "aLigne[$nbColonneI+$m-1] ".$aLigne[$nbColonneI+$m-1]."<br>";
	//echo "aLigne[$nbColonneI+$m] ".$aLigne[$nbColonneI+$m]."<br>";
	//echo "aLigne[$nbColonneI+$m+1] ".$aLigne[$nbColonneI+$m+1]."<br>";
	
	//pre_dump($aLigne);	
	
	$aKeyValue = array(); 
	
	if (isset($sheetTout)){ // XLSX
		$csvKeyValue = utf8_decode($aLigne[NumToLetter($nb_champs+1+$m)]);
	}
	else{ // CSV 
		$csvKeyValue = preg_replace("/^\"(.*)\"$/msi", "$1", $aLigne[$nb_champs+$m]);	
	}
	
	if (isset($csvKeyValue) && (trim($csvKeyValue) != "") && ($csvKeyValue != " ")){
		$csvKeyValue = str_replace("\r", "", $csvKeyValue);
		$csvKeyValue = str_replace("\n", "", $csvKeyValue);
		$csvKeyValue = str_replace("\"", "", $csvKeyValue);
		$eKeyValue = $csvKeyValue;
		$aKeyValue = explode(',', $csvKeyValue);		
		
	}
	else {
		$eKeyValue = "n/a";
	}
	//echo $csvKeyValue."<br />";
	
	//colonne statut des asso		 
	$statutAssoValue = preg_replace("/^\"(.*)\"$/msi", "\\1", $aLigne[$nb_champs+$m+1]); 	 
	
	if (isset($statutAssoValue) && (trim($statutAssoValue) != "") && ($statutAssoValue != " ")){ 
		$statutAssoValue = str_replace("\r", "", $statutAssoValue);
		$statutAssoValue = str_replace("\n", "", $statutAssoValue);
		$statutAssoValue = str_replace("\"", "", $statutAssoValue);
		if ($statutAssoValue!=4 && $statutAssoValue!=5 && $statutAssoValue!=1) { 
			$aStatut_default = array(DEF_LIB_STATUT_ATTEN => DEF_ID_STATUT_ATTEN, DEF_LIB_STATUT_LIGNE => DEF_ID_STATUT_LIGNE,  DEF_LIB_STATUT_ARCHI => DEF_ID_STATUT_ARCHI);
			$aStatut_email = array(DEF_LIB_STATUT_ATTEN => DEF_ID_STATUT_ATTEN, "abonné" => DEF_ID_STATUT_LIGNE,  "desabonné" => DEF_ID_STATUT_ARCHI);
			if ($bIsEmail) {
				$statutAssoValue = $aStatut_email[$statutAssoValue]; 
			}
			else {
				$statutAssoValue = $aStatut_default[$statutAssoValue]; 
			} 
		}
		$statutValue = $statutAssoValue; 	
		
	}
	else { ;
		$statutValue = "";
	}  
	
	 echo $statutValue;
	//echo "statutValue ".$statutValue; 
	// je récupère la valeur dans le csv
	
	//echo $sTempClasse." ".$csvKeyValue."<br>"; 
	if(is_array($_foreignNodeToSort)){
		foreach ($_foreignNodeToSort as $nodeId => $nodeValue) {	 
			if ($nodeValue["attrs"]["NAME"] == $_oTemp->getAbstract()){					
				if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
					$_tempIsAbstractForeign = true;
					$_tempForeignAbstract = $nodeValue["attrs"]["FKEY"];
					$_tempForeignAbstractSetter = 'set_'.$nodeValue["attrs"]["NAME"];
					$_tempForeignAbstractField = $nodeValue["attrs"]["NAME"];
					//break;
				}
			}
			if ($nodeValue["attrs"]["NAME"] == $_oTemp->getDisplay()){					
				if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
					$_tempIsDisplayForeign = true;
					$_tempForeignDisplay = $nodeValue["attrs"]["FKEY"]; 
					$_tempForeignDisplaySetter = 'set_'.$nodeValue["attrs"]["NAME"];
					$_tempForeignDisplayField = $nodeValue["attrs"]["NAME"];
					//break;
				}
			}
			if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
				if ($nodeValue["attrs"]["FKEY"] == $classeName){	
					$_tempAssoIn = $nodeValue["attrs"]["FKEY"]; // obvious 
				}
				else{
					$_tempAssoOut = $nodeValue["attrs"]["FKEY"]; //  
				}
			}
		}
	}
	
	if ($_tempForeignAbstract==$classeName){
		$autreClasseName = $_tempForeignDisplay;
		$autreClasseSetter = $_tempForeignDisplaySetter;
		$autreClasseField = $_tempForeignDisplayField;
	}
	else{
		$autreClasseName = $_tempForeignAbstract;
		$autreClasseSetter = $_tempForeignAbstractSetter;
		$autreClasseField = $_tempForeignAbstractField;
	}
	 
	eval("$"."oClasse_ = new ".$autreClasseName."();");
	// PAS UTILISE $aClasse_ = dbGetObjects($autreClasseName);
	
	// cas des deroulant d'id, pointage vers foreign
	//$sXML = $aForeign[0]->XML;
	$sXML = $oClasse_->XML;

	unset($stack);
	$stack = array();
	xmlClassParse($sXML);

	$_Name = $stack[0]["attrs"]["NAME"];
	$_Prefixe = $stack[0]["attrs"]["PREFIX"];
	$_NodeToSort = $stack[0]["children"];
	$_Display = $oClasse_->getDisplay();
	$_Abstract = $oClasse_->getAbstract();
	if (sizeof($aKeyValue) > 0) {
		for ($l = 0; $l <sizeof($aKeyValue); $l++) {
			if ($aKeyValue[$l]!="") {
				// on traite l'autre classe
				$sKeyValue = trim($aKeyValue[$l]);
				$sKeyValue_  = str_replace ("'", "''", $sKeyValue);  
				
				if (!isset($aFkeyCache[$autreClasseName][$sKeyValue_])){							
					$sql = "select * from ".$autreClasseName." where ".$_Prefixe."_".$_Display." = '".$sKeyValue_."' "; 
					//echo $sql;
					$aClasseId = dbGetObjectsFromRequete ($autreClasseName, $sql);
					// on vérifie que la valeur existe 
					if (sizeof($aClasseId) >  0){
						 
						$_oClasseId = $aClasseId[0];
						$_Id = $_oClasseId->get_id();
					}
					else {
						eval ("$"."_Obj = new ".$autreClasseName."();");
						//echo $_Display." ".$sKeyValue;	
						eval ("$"."_Obj->set_".$_Display."(\"".$sKeyValue."\");");	
						eval ("$"."_Obj->set_statut(".DEF_ID_STATUT_LIGNE.");"); 
						$_Id = dbInsertWithAutoKey($_Obj);
					}
					$aFkeyCache[$autreClasseName][$sKeyValue_] = $_Id;
				}
				else{
					$_Id = $aFkeyCache[$autreClasseName][$sKeyValue_];
				}
				
				// on vérifie que l'asso existe	
				if (getCount_where($_foreignName, array($_foreignPrefixe."_".$classeName, $_foreignPrefixe."_".$autreClasseField), array($id, $_Id), array("NUMBER", "NUMBER")) >  0){
					/*eval ("$"."_Obj = new ".$_foreignName."();");	
					eval ("$"."_Obj->set_".$classeName."(".$id.");");	
					eval ("$"."_Obj->set_".$autreClasseName."(".$_Id.");");	 
					if ($statutValue!="") eval ("$"."_Obj->set_statut(".$statutValue.");");	 
					$res = dbUpdate($_Obj);	 
					echo "update ";
					pre_dump ($_Obj);*/								 
				}
				else { 
					eval ("$"."_Obj = new ".$_foreignName."();");	
					eval ("$"."_Obj->set_".$classeName."(".$id.");");	 
					eval ("$"."_Obj->".$autreClasseSetter."(".$_Id.");");	 
					
					if ($statutValue!="") eval ("$"."_Obj->set_statut(".$statutValue.");");	 
					else {
						if (method_exists($_Obj, "get_statut")) {
							eval ("$"."_Obj->set_statut(".DEF_ID_STATUT_LIGNE.");");	
						}
					}								
					 
					$res = dbInsertWithAutoKey($_Obj);
				}
				
			}
		} // fin for
	} // fin if	
}
?>