<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// fkey caching
/*
$aFkeyCache = array [classename][displayvalue] = id
*/
$aFkeyCache = array();


//controle le nombre de champs de la classe
// 
	
	 
if ($bImportIds == false){ 
	$nb_champs =  newSizeOf($oRes->getListeChamps()) - 1 ;	
	if ($liste_asso != '')  $aTempClasse = explode(',', $liste_asso);	  
	$nb_champs  = $nb_champs + newSizeOf($aTempClasse);  					 
}
else{ // la premiere colonne CSV contient les IDs
	$nb_champs =  newSizeOf($oRes->getListeChamps());
	if ($liste_asso != '')  $aTempClasse = explode(',', $liste_asso);	
	$nb_champs  = $nb_champs + newSizeOf($aTempClasse);  
}
 
//
 

$fh = fopen($saveFile,'r'); 
if ($fh){
	//pre_dump(htmlentities($oRes->XML));
	$bIsFirstLine = true;
	$testColonne = false;
	
	while(!feof($fh)) {
		$sRawLigne = fgets($fh);
		//echo $sRawLigne."***".strpos($sRawLigne, "FIN".date("Y-m-d"))."<br><br>";
		$aLigne = explode(";",$sRawLigne);
		
		if (($bIsFirstLine == true) && ($startLine == 1)){
			// on skippe la premiere ligne si elle contient les noms des champs
			$bIsFirstLine = false;
			//echo "skipp";
			$aLigne = explode(";",$sRawLigne);
			//pre_dump($aLigne);
		}
		// on teste le nombre de colonne 
		elseif ($nb_champs >  newSizeOf($aLigne) && $testColonne == false ){  
			$error = "Aucun import n'a été effectué, merci de vérifier la structure de votre fichier <br />ou de télécharger le modèle de fichier d'import ci-dessous.";
			break;	
		}
		elseif(($bSignature == true)&&( preg_match ("/FIN/", $sRawLigne) )){
			
			// ligne de signature, on skippe
			//echo "signature<br>";		
			break;	
		} 
		else{ // sinon on traite
			$testColonne = true; 
			$bIsFirstLine = false;
			//echo "<br />avant explode".$sRawLigne."<br><br>";
			

			if ($sRawLigne != "") {
				$aLigne = explode(";",$sRawLigne);
				
				 
				//pre_dump($aLigne);
				// controle sur surexplode du a ; dans champs text
				for ($iLigne=0;$iLigne<(count($aLigne)-1);$iLigne++){				
					//echo ">> ".$aLigne[$iLigne]." - ".$aLigne[$iLigne+1]." >> " ;	
					if ((preg_match('/^".*[^"]$/msi', $aLigne[$iLigne])==1) && (preg_match('/^[^"].*/msi', $aLigne[$iLigne+1])==1)){						
						$tempMerge = $aLigne[$iLigne].";".$aLigne[$iLigne+1];
						$aLigne[$iLigne] = $tempMerge;
						$aLigne[$iLigne+1] = $tempMerge;
						$tempArrayDebut = array_slice($aLigne, 0, $iLigne); 
						$tempArrayFin = array_slice($aLigne, $iLigne+1); 						
						$aLigne = array_merge($tempArrayDebut, $tempArrayFin);
						$iLigne = $iLigne - 1;
						//pre_dump($aLigne);
					}
				}
				// controle sur double "" dans champs txt
				for ($iLigne=0;$iLigne<count($aLigne);$iLigne++){		
					$aLigne[$iLigne] = str_replace('""', '\"',	$aLigne[$iLigne]);
				}
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
				//pre_dump($oRes);
				$bMailDejaPresent=false; // test unicité mail
				//pre_dump($aNodeToSort);
				for ($i=0;$i<count($aNodeToSort);$i++){
					//echo "ici";
					if ($aNodeToSort[$i]["name"] == "ITEM" ){	 
						eval("$"."eKeyValue = "."$"."oRes->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();"); 
						//echo "eKeyValue: ".$aNodeToSort[$i]["attrs"]["NAME"]." ".$eKeyValue."<br>";
						if ($bImportIds == false){
							$csvKeyValue = preg_replace("/^\"(.*)\"$/msi", "$1", $aLigne[$i-1]);
						}
						else{ // la premiere colonne CSV contient les IDs
							$csvKeyValue = preg_replace("/^\"(.*)\"$/msi", "$1", $aLigne[$i]);
						}
	
						if (isset($csvKeyValue) && (trim($csvKeyValue) != "") && ($csvKeyValue != " ")){
							$csvKeyValue = str_replace("\r", "", $csvKeyValue);
							$csvKeyValue = str_replace("\n", "", $csvKeyValue);
							$csvKeyValue = str_replace("\"", "", $csvKeyValue);
							$eKeyValue = $csvKeyValue;
						}						
						else{								
							if ($bFillNull == true ){
								//print("$"."eKeyValue = "."$"."oPrev->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");
								if ($aNodeToSort[$i]["attrs"]["NAME"]!="id")eval("$"."eKeyValue = "."$"."oPrev->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");
								//echo "prev value is ".$eKeyValue. " ####<br>";								
							}
							else{
								//echo "default value is ".$eKeyValue. " ####<br>";								
							}
						}							
						
						$eKeyValue = trim ($eKeyValue);
						//echo $aNodeToSort[$i]["attrs"]["NAME"]. " ".$eKeyValue."<br>";
						//echo "<hr />";
						//echo "******************************id value :".$eKeyValue."<br>";
						if ($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_VIEW"] || $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]) {
							/*if ($aNodeToSort[$i]["attrs"]["ASSO"])
								$liste_asso = $aNodeToSort[$i]["attrs"]["ASSO"];
							elseif ($aNodeToSort[$i]["attrs"]["ASSO_VIEW"])
								$liste_asso = $aNodeToSort[$i]["attrs"]["ASSO_VIEW"];
							elseif ($aNodeToSort[$i]["attrs"]["ASSO_EDIT"])
								$liste_asso = $aNodeToSort[$i]["attrs"]["ASSO_EDIT"];*/
						}
						else if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key  
							//echo "<br /><br />".$aNodeToSort[$i]["attrs"]["FKEY"]." : FKEY".$eKeyValue."<br />" ;
							if ($eKeyValue == "n/a" || $eKeyValue == "") {
								$eKeyValue = -1;
							}
							$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
							
							//echo $aNodeToSort[$i]["attrs"]["FKEY"]." ".$eKeyValue." <br />";
							if (trim($eKeyValue)!="n/a") {
								if ($eKeyValue > -1){ 
									$eKeyValue_ = str_replace('\\', '\"',	$eKeyValue);
									
									if (!isset($aFkeyCache[$sTempClasse][$eKeyValue_])){
								
										//print("$"."oTemp = new ".$sTempClasse."();");
										eval("$"."oTemp = new ".$sTempClasse."();");
										//echo "tester la presence de ".$eKeyValue." dans la table ".$sTempClasse." : ";
										 	
										 
										$nb_underscore = substr_count($oTemp->getFieldPK(), "_") ; 
										//echo "<br />nb_underscore ".$oTemp->getFieldPK()." ".$nb_underscore."<br />";
										 
										  
										if ($nb_underscore == 1) {
											$tempPrefix = preg_replace("/([^_]*)_.*/msi", "$1", $oTemp->getFieldPK());
											$tempFieldWhere = $tempPrefix."_".$oTemp->getDisplay();
											$tempGetterWhere = preg_replace("/([^_]*)_.*/msi", "get", $oTemp->getFieldPK())."_".$oTemp->getDisplay();
											$tempFieldWhere_Id = $tempPrefix."_id";
											$tempGetterWhere_Id = preg_replace("/([^_]*)_.*/msi", "get", $oTemp->getFieldPK())."_id";
										}
										else {
											$tempPrefix = preg_replace("/([^_]*_.[^_]*)_.*/msi", "$1", $oTemp->getFieldPK());
											$tempFieldWhere = $tempPrefix."_".$oTemp->getDisplay();
											$tempGetterWhere = preg_replace("/([^_]*_.[^_]*)_.*/msi", "get", $oTemp->getFieldPK())."_".$oTemp->getDisplay();
											$tempFieldWhere_Id = $tempPrefix."_id";
											$tempGetterWhere_Id = preg_replace("/([^_]*_.[^_]*)_.*/msi", "get", $oTemp->getFieldPK())."_id";
										}
										
										//echo ( '$tempPrefix : '.$tempPrefix."<br/>\n");
										//echo ( '$eKeyValue_ : '.$eKeyValue_."<br/>\n");
										//echo ( 'tempFieldWhere : '.$tempFieldWhere."<br/>\n"); 
										//echo ( 'tempFieldWhere_Id : '.$tempFieldWhere_Id."<br/>\n");
										 
										$tempCount = getCount2($oTemp, $tempFieldWhere, $eKeyValue_, "TEXT");
										if (preg_match('/^[0-9]+$/si', $eKeyValue_)){ // count sur id only sur val num.
											$tempCount_id = getCount2($oTemp, $tempFieldWhere_Id, $eKeyValue_, "TEXT");
										}
										else{
											$tempCount_id = 0;
										}
																				 
										if ($tempCount == 0 && $tempCount_id == 0){ 
											//echo "cinserer  ".$eKeyValue." dans la table ".$sTempClasse."<br>";
											eval( "$"."oTemp->set_".$oTemp->getDisplay()."(\"".$eKeyValue_."\");");
											$eKeyValue = dbInsertWithAutoKey($oTemp);
											//echo $eKeyValue;
											//echo " (fk vers valeur insérée dans ".$sTempClasse.")";
										} 
										else if ( $tempCount_id > 0 ){ 
											$aTempWhere = array($tempGetterWhere_Id);
											$aTempValue = array($eKeyValue_);
											$aTempRes = dbGetObjectsFromFieldValue($sTempClasse, $aTempWhere, $aTempValue, "");
											if(($aTempRes!==false)&&($aTempRes!==NULL)){
												$otempRes = $aTempRes[0];	
												if (method_exists($otempRes, 'get_id')){																		
													$eKeyValue = $otempRes->get_id();
												}
												else{
													$eKeyValue=-1;
													var_dump($aTempRes);
												}
											}
											else{
												echo '#202 ';
												echo dbGetSQLFromFieldValue3($sTempClasse, $aTempWhere, NULL, $aTempValue, NULL, NULL);
												var_dump($tempCount_id);
												var_dump($aTempWhere);
												$eKeyValue=-1;
											}
											
										} 
										else{ 
											$aTempWhere = array($tempGetterWhere);
											$aTempValue = array($eKeyValue_);
											$aTempRes = dbGetObjectsFromFieldValue($sTempClasse, $aTempWhere, $aTempValue, "");
											if($aTempRes!==false){
												$otempRes = $aTempRes[0];											
												if (method_exists($otempRes, 'get_id')){																		
													$eKeyValue = $otempRes->get_id();
												}
												else{
													$eKeyValue=-1;
													var_dump($aTempRes);
												}
											}
											else{
												echo '#217 ';
												echo dbGetSQLFromFieldValue($sTempClasse, $aTempWhere, $aTempValue, "");
												$eKeyValue=-1;
											}
										}
										unset($oTemp);
										$aFkeyCache[$sTempClasse][$eKeyValue_] = $eKeyValue; // caching
									}
									else{
										$eKeyValue = $aFkeyCache[$sTempClasse][$eKeyValue_]; // cache reading
									}
								}
								else{
									//echo "n/a";
								}
							}
							else {
								$eKeyValue = -1;
							}
						}// fin fkey
						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum	
							$eNewKeyValue = 0;	
							if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
								foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
									if($childNode["name"] == "OPTION"){ // on a un node d'option				
										if ($childNode["attrs"]["TYPE"] == "value"){
											if (strval($eKeyValue) == strval($childNode["attrs"]["LIBELLE"])){		
												$eNewKeyValue = $childNode["attrs"]["VALUE"];	
												//echo "value : ".$eKeyValue." is ".$eNewKeyValue."<br />\n";
												break;			
											}
										} //fin type  == value				
									}
								}
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
										if (preg_match("/[0-9]{2}.{1}[0-9]{2}.{1}[0-9]{4}/msi",$eKeyValue)==1){ // traduction date FR to FR
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
						$eKeyValue = trim ($eKeyValue); 
						if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "decimal")){ // cas texte
							$eKeyValue = str_replace('\\', '\"', $eKeyValue); // SID
							$evalRes = eval( "$"."oRes->set_".$aNodeToSort[$i]["attrs"]["NAME"]."(\"".$eKeyValue."\");");
							if ($evalRes===false){
								echo "PARSE ERROR IN : $"."oRes->set_".$aNodeToSort[$i]["attrs"]["NAME"]."(\"".$eKeyValue."\");<br />";
							}
						}
						elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "date"){ // cas date
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
							//echo $eKeyValue."c'est un email<br>";
							$bIsinscrit = true;
							$eKeyValue = trim(strtolower($eKeyValue));
							//echo $eKeyValue;
							//echo $classeName.' '.$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"].' '.$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"].' '.$eKeyValue.'<br />';
							if (getCount($classeName, $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $eKeyValue, "TEXT") > 0 ) {
								$id = dbGetUniqueValueFromRequete("select ".$classePrefixe."_id from ".$classeName." where ".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." = '".$eKeyValue."' ");
								$bMailDejaPresent=true;
								//echo "- ".$eKeyValue." dejaA<br>"; 
								eval("$"."oRes = new ".$classeName."(".$id.");");
							}
							if ($eKeyValue=="") {
								$bMailDejaPresent=true;
								//echo "- ".$eKeyValue." dejaB<br>"; 
							}
							$bIsEmail=true;
							if (!isEmail($eKeyValue)){
								$bIsEmail=false;
								//echo "- ".$eKeyValue." syntaxe<br>"; 
							}
							if (!$bMailDejaPresent && $bIsEmail) {
								//echo "- ".$eKeyValue." okC<br>";
							
							}	 
						}
					}
					$nbColonneI = $i;
					 
				} // fin for
				//pre_dump($oRes);
				// save de l'objet
				
				// recherche d'eventuelles asso
				//include("import.association.php"); 
				 
				if ($bIsinscrit) {
					if ($bIsEmail) { 
						if ($bMailDejaPresent==false) {
							if ($bImportIds == false){
								$eRes = dbInsertWithAutoKey($oRes);
								$id = $eRes;
								//echo "$id = dbInsertWithAutoKey ".$oRes->get_id()."<br>"; 
								$eMailSucces++;
							}
							else{
								$eRes = dbInsert($oRes);
								$id = $eRes; 
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
					/*$eRes = dbInsertWithAutoKey($oRes);*/
					if ($oRes->get_id() == -1) {
						$eRes = dbInsertWithAutoKey($oRes);
						$id = $eRes; 
						//echo "1. $id = dbInsertWithAutoKey ".$oRes->get_id()."<br>"; 
						if ($eRes) $eImportSucces++;
					}
					else {
						if (getCount_where($classeName, array($classePrefixe."_id"), array($id), array("NUMBER"))>0) {
							$eRes = dbUpdate($oRes);
							$id = $eRes; 
							//echo "2. $id = update ".$oRes->get_id()."<br>"; 
							if ($eRes) $eImportSucces++;
						}	
						else {
							$eRes = dbInsert($oRes);
							$id = $eRes; 
							//echo "3. $id = insert ".$oRes->get_id()."<br>"; 
							if ($eRes) $eImportSucces++;
						} 
					}
				}
				// recherche d'eventuelles asso
				//echo "bMailDejaPresent ".$bMailDejaPresent." ".$eKeyValue." <br>";
				//echo "bIsEmail ".$bIsEmail." ".$eKeyValue." <br>";
				
				
				//associations
				if ($liste_asso != ''){
				
					//echo 'traitement des associations'.$iLigne.'<br />';
					include("import.association.php"); 
					
				}
			
			}
			
			//echo "Insert entrée id ".$eRes."<br>";
		} // skip line 1
		
		//echo "<br />";
	}// fin while
}

//unlink($uploadRep.'import.xls');
?>