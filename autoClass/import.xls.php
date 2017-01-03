<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
$data->read($saveFile);

$bIsFirstLine = true;

for ($irow=1;$irow<$data->sheets[0]['numRows'];$irow++){
//while(!feof($fh)) {
	
	if (($bIsFirstLine == true) && ($startLine == 1)){
		// on skippe la premiere ligne si elle contient les noms des champs
		$bIsFirstLine = false;
		//echo "skipp";
		$aLigne = $data->sheets[0]["cells"][$irow];
		//pre_dump($aLigne);
	}
	elseif(($bSignature == true)&&(strpos($sRawLigne, "FIN".date("Y-m-d")) !== false)){
		
		// ligne de signature, on skippe
		//echo "signature<br>";		
		break;	
	}
	else{ // sinon on traite
		 
		$bIsFirstLine = false;
		//echo "<br />avant explode".$sRawLigne."<br><br>";
		
		$aLigne = $data->sheets[0]["cells"][$irow];
		//pre_dump($aLigne);

		unset($oPrev);
		if (isset($eRes)){
			eval("$"."oPrev = new ".$classeName."(".$eRes.");");
			//echo "oprev is set<br />";
			//pre_dump($oPrev);
		}
		else{
			//echo "oprev is default<br />";
			eval("$"."oPrev = new ".$classeName."();");
		}
		unset($oRes);
		eval("$"."oRes = new ".$classeName."();");
		
		$bMailDejaPresent=false; // test unicité mail
		//pre_dump($aNodeToSort);
		
		
		for ($i=0;$i<count($aNodeToSort);$i++){
			
			if ($aNodeToSort[$i]["name"] == "ITEM" ){	 
				eval("$"."eKeyValue = "."$"."oRes->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();"); 
				
				
				if ($bImportIds == false){
					$csvKeyValue = $aLigne[$i];
				}
				else{ // la premiere colonne CSV contient les IDs
					$csvKeyValue = $aLigne[$i+1];
				}							
				
										
				if (isset($csvKeyValue) && (trim($csvKeyValue) != "") && ($csvKeyValue != " ")){					
					//$csvKeyValue = utf8_decode($csvKeyValue);
					$csvKeyValue = str_replace('"', '\\"', $csvKeyValue);
					$csvKeyValue = str_replace(array(chr(96)), '\'', $csvKeyValue);
					$eKeyValue = str_replace(array(chr(190),chr(191)), '"', $csvKeyValue);
					//echo ord(substr( $eKeyValue, 0,1));
												
				}							
				else{								
					if ($bFillNull == true){
						//print("$"."eKeyValue = "."$"."oPrev->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");
						eval("$"."eKeyValue = "."$"."oPrev->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");
						//echo "prev value is ".$eKeyValue. " ####<br>";								
					}
					else{
						//echo "default value is ".$eKeyValue. " ####<br>";								
					}
				}							
				
				//echo "<hr />";
				
				if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key  
					if ($eKeyValue == "n/a" || $eKeyValue == "") $eKeyValue = -1;
					$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
					if (trim($eKeyValue)!="n/a") {
						if ($eKeyValue > -1){ 
							//print("$"."oTemp = new ".$sTempClasse."();");
							eval("$"."oTemp = new ".$sTempClasse."();");
							////////////// Test Luc > No bug with '_' in prefix (ex cms_pays)
							
							$tmpXML = $oTemp->XML;
							xmlClassParse($tmpXML);
							$tempPrefix = $stack[0]["attrs"]["PREFIX"];
							
							/////////////
							//echo "tester la presence de ".$eKeyValue." dans la table ".$sTempClasse." : ";
							//$tempPrefix = ereg_replace("([^_]*)_.*", "\\1", $oTemp->getFieldPK());
							$tempFieldWhere = $tempPrefix."_".$oTemp->getDisplay();
							$tempGetterWhere = preg_replace("/([^_]*)_.*/msi", "get", $oTemp->getFieldPK())."_".$oTemp->getDisplay();
							$eKeyValue_ = str_replace('\\', '\"',	$eKeyValue);
							$tempCount = getCount2($oTemp, $tempFieldWhere, $eKeyValue_, "TEXT");

							if ($tempCount == 0){
								echo "inserer  ".$eKeyValue." dans la table ".$sTempClasse."<br>";
								eval( "$"."oTemp->set_".$oTemp->getDisplay()."(\"".$eKeyValue_."\");");
								$eKeyValue = dbInsertWithAutoKey($oTemp);
								//echo $eKeyValue;
								//echo " (fk vers valeur insérée dans ".$sTempClasse.")";
							}
							else{
								$aTempWhere = array($tempGetterWhere);
								$aTempValue = array($eKeyValue_);
								$aTempRes = dbGetObjectsFromFieldValue($sTempClasse, $aTempWhere, $aTempValue, "");
								$otempRes = $aTempRes[0];
								$eKeyValue = $otempRes->get_id();
								//echo $eKeyValue;
								//echo " (fk vers valeur existante dans ".$sTempClasse.")";
							}
							unset($oTemp);
						} else {
							//echo "n/a";
						}
					} else {
						$eKeyValue = -1;
					}
				}// fin fkey
				elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum" || $aNodeToSort[$i]["attrs"]["TYPE"] == "enum"){ // cas enum	
					$eNewKeyValue = 0;
					///echo "TEST LENGTH : ".$aNodeToSort[$i]["attrs"]["TYPE"]." : ".$childNode["attrs"]["LENGTH"]."<br/>";
					if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "value"){
									//echo "TEST : ".$childNode["attrs"]["TYPE"]."<br/>";
									if (strval($eKeyValue) == strval($childNode["attrs"]["LIBELLE"])){		
										$eNewKeyValue = $childNode["attrs"]["VALUE"];	
										//echo "value : ".$eKeyValue." is ".$eNewKeyValue."<br />\n";
										break;			
									}
								} //fin type  == value				
							}
						}
					////////////// Test Luc > Handle type="enum"
					} else if (!empty($aNodeToSort[$i]["attrs"]["LENGTH"])) {
						$tmp_values = explode(',', $aNodeToSort[$i]["attrs"]["LENGTH"]);
						foreach ($tmp_values as $key => $val)
							$tmp_values[$key] = str_replace("'", '', $val);
						if (in_array(strval($eKeyValue), $tmp_values))
							$eNewKeyValue = strval($eKeyValue);
					/////////////
					}
					$eKeyValue = $eNewKeyValue;
				} // fin cas enum
				else{ // cas typique 
					if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){ // cas statut
						//echo lib($eKeyValue); 
						//traite id
						if ($eKeyValue == DEF_ID_STATUT_ATTEN) $eKeyValue = DEF_ID_STATUT_ATTEN; 
						else if ($eKeyValue == DEF_ID_STATUT_LIGNE) $eKeyValue = DEF_ID_STATUT_LIGNE; 
						else if ($eKeyValue == DEF_ID_STATUT_ARCHI) $eKeyValue = DEF_ID_STATUT_ARCHI; 
						//traite libelle
						else if (strtolower($eKeyValue) == "en attente") $eKeyValue = DEF_ID_STATUT_ATTEN; 
						else if (strtolower($eKeyValue) == "abonné") $eKeyValue = DEF_ID_STATUT_LIGNE; 
						else if (strtolower($eKeyValue) == "abonne") $eKeyValue = DEF_ID_STATUT_LIGNE; 
						else if (strtolower($eKeyValue) == "désabonné") $eKeyValue = DEF_ID_STATUT_ARCHI; 
						else if (strtolower($eKeyValue) == "desabonne") $eKeyValue = DEF_ID_STATUT_ARCHI; 
						
						else if (strtolower($eKeyValue) == "en ligne") $eKeyValue = DEF_ID_STATUT_LIGNE; 
						else if (strtolower($eKeyValue) == "en attente") $eKeyValue = DEF_ID_STATUT_ATTEN; 
						else if (strtolower($eKeyValue) == "archivé") $eKeyValue = DEF_ID_STATUT_ARCHI; 
						else $eKeyValue = DEF_CODE_STATUT_DEFAUT;  
					}
					else{
						if ($eKeyValue > -1){ // cas typique typique
							if ($aNodeToSort[$i]["attrs"]["TYPE"] == "date"){ // cas date
								
								// cas particulier xls 
								// ex : 22/09/2009 s'affiche 09/22/2009
								if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/msi",$eKeyValue)==1){ // traduction date FR to FR
									$eKeyValue = preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/msi", "$2/$1/$3", $eKeyValue);  
								}
								elseif (preg_match("/[0-9]{2}.{1}[0-9]{2}.{1}[0-9]{4}/msi",$eKeyValue)==1){ // traduction date FR to FR
									$eKeyValue = preg_replace("/([0-9]{2}).{1}([0-9]{2}).{1}([0-9]{4})/msi", "$1/$2/$3", $eKeyValue);  
								}
								elseif (preg_match("/[0-9]{4}.{1}[0-9]{2}.{1}[0-9]{2}/msi",$eKeyValue)==1){ // reformattage date US to FR
									$eKeyValue = preg_replace("/([0-9]{4}).{1}([0-9]{2}).{1}([0-9]{2})/msi", "$3/$2/$1", $eKeyValue); 
								}
								elseif (preg_match("/[0-9]{2}.{1}[0-9]{2}.{1}[0-9]{2}/msi",$eKeyValue)==1){ // traduction date FR to FR
									$eKeyValue = preg_replace("/([0-9]{2}).{1}([0-9]{2}).{1}([0-9]{2})/msi", "$1/$2/20$", $eKeyValue); 
								}
								else{ // date impossible à reconnaitre
									$eKeyValue = "n/a";
								}
								//echo $eKeyValue;
							}
							else{// cas typique typique typique
								if ($aNodeToSort[$i]["attrs"]["NAME"] == "id") {
									$id = $eKeyValue;
									
								}
								//echo $eKeyValue;
							}
						}
						else{
							
							//echo "n/a";
						}
					} // fin if status
				} // fin if fk
			} // fin if item
			// set des attributs de l'objet
			
			if ($aNodeToSort[$i]["attrs"]["NAME"]!="") {
				 
				if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") || ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "decimal")){ // cas texte
					//print( "$"."oRes->set_".$aNodeToSort[$i]["attrs"]["NAME"]."(\"".$eKeyValue."\");");
					eval( "$"."oRes->set_".$aNodeToSort[$i]["attrs"]["NAME"]."(\"".$eKeyValue."\");");
				}
				elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "date"){ // cas date
					//print( "$"."oRes->set_".$aNodeToSort[$i]["attrs"]["NAME"]."('".$eKeyValue."');");
					eval( "$"."oRes->set_".$aNodeToSort[$i]["attrs"]["NAME"]."('".$eKeyValue."');");
				}
				else{ 
					//echo "--".$aNodeToSort[$i]["attrs"]["NAME"]."<br>"; 
					eval( "$"."oRes->set_".$aNodeToSort[$i]["attrs"]["NAME"]."(".(int)$eKeyValue.");");
				}
				if ($iLigne == 1) $aChamps[] = $aNodeToSort[$i]["attrs"]["NAME"];
 
			}
			
			//recherche doublon de mail
			
			if ($aNodeToSort[$i]["attrs"]["OPTION"]){
				if ($aNodeToSort[$i]["attrs"]["OPTION"]=="email"){
					$bIsinscrit = true;
					$eKeyValue = trim(strtolower($eKeyValue));
					//echo $eKeyValue;
					if (getCount($classeName, $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $eKeyValue, "TEXT") > 0 ) {
						$bMailDejaPresent=true;
						//echo "- ".$eKeyValue." deja<br>";
					}
					if ($eKeyValue=="") {
						$bMailDejaPresent=true;
						//echo "- ".$eKeyValue." deja<br>";
					}
					$bIsEmail=true;
					if (!isEmail($eKeyValue)){
						$bIsEmail=false;
						//echo "- ".$eKeyValue." syntaxe<br>";
					}
					if (!$bMailDejaPresent && $bIsEmai) {
						//echo "- ".$eKeyValue." ok<br>";
					
					}	 
				}
			}
			$nbColonneI = $i;
		} // fin for
		//pre_dump($oRes);
		// save de l'objet
		
		// recherche d'eventuelles asso
		//include("import.association.php");  // SIF
		
		if ($bIsinscrit) {
			if ($bIsEmail) {
				
				if ($bMailDejaPresent==false) {
					if ($bImportIds == false){
						$eRes = dbInsertWithAutoKey($oRes);
						$eMailSucces++;
					}
					else{
						$eRes = dbInsert($oRes);
						$eMailSucces++;
					}
				}
				else {
					$sRapportMailDejaPresent.= $sRawLigne;
					$eMailDejaPresent++;
				
				}
			}
			else {
				$bIsEmail=false;
				$sRapportMailMauvaiseSyntaxe.= $sRawLigne;
				$eMailMauvaiseSyntaxe++;
			}
		}
		else {
			/*$eRes = dbInsertWithAutoKey($oRes);
			echo $id." ok<br>";
			*/
			if ((isset($id))&&(getCount_where($classeName, array($classePrefixe."_id"), array($id), array("NUMBER"))>0)) {
				$eRes = dbUpdate($oRes);
				//echo "$id = update ".$oRes->get_id()."<br>"; 
				if ($eRes) $eImportSucces++;
			}	
			else {
				if (($id == -1)||($id == NULL)){
					//$eRes = dbInsertWithAutoKey($oRes);
					$eRes = dbSauve($oRes);
				}
				else{
					$eRes = dbSauve($oRes);
				}
				
				//echo "$id = insert ".$oRes->get_id()."<br>"; 
				if ($eRes) $eImportSucces++;
			} 
		}
		
		//echo "Insert entrée id ".$eRes."<br>";
	} // skip line 1
}// fin while


//unlink($uploadRep.'import.xls');
?>