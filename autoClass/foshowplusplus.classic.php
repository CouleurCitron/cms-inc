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
//echo "-----------".$id."--".$classeName;
eval("$"."oRes = new ".$classeName."($"."id);");



if($oRes) {

$tempStyles = ".".replaceBadCarsInStr($classeName)."{\n";
$tempStyles .= "}\n";

echo "<div class=\"".replaceBadCarsInStr($classeName)."\" id=\"".replaceBadCarsInStr($classeName)."\">\n";

$tempGroup = "nogroup";

$sXML = $oRes->XML;
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];
$classeMain = $classeName;

// classe principale
for ($i=0;$i<count($aNodeToSort);$i++){

	if ($aNodeToSort[$i]["name"] == "ITEM"){	
		// - test group debut ---------------------------------------
		if(nouveauGroup($aNodeToSort[$i], $tempGroup) != false){
			echo " # new group ";
			$tempGroup = nouveauGroup($aNodeToSort[$i], $tempGroup);
			$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."{\n";
			$tempStyles .= "}\n";
			echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."\">\n";
		}
		
		scanNode($aNodeToSort[$i], $stack, $oRes, $classeName, replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"]));
		
		
		// test fin de groupe
		if (finGroup($aNodeToSort[$i+1], $tempGroup) == true){
			$tempGroup = "nogroup";
			//echo " # fin de group #";
			echo "</div>\n";
		}
	} // item 
} // for 


//-------------------------
// recherche d'eventuelles asso
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if ($aNodeToSort[$i]["attrs"]["ASSO"]){ // cas d'asso
			echo "<!-- debut des champs d'association -->\n";
		}	
	}
}

for ($i=0;$i<count($aNodeToSort);$i++){
						
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if (ereg("id", $aNodeToSort[$i]["attrs"]["NAME"])){ 
			
			
			// asso
			if ($aNodeToSort[$i]["attrs"]["OPTION"] == "asso"){ // cas file
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if ($childNode["name"] == "OPTION")  {	
						
							
						$sTempClasse = $childNode["attrs"]["ASSO"];
						eval("$"."oTemp = new ".$sTempClasse."();");
						
						$sXML = $oTemp->XML;
			
						unset($stack);
						$stack = array();
						xmlClassParse($sXML);
			
						$assoName = $stack[0]["attrs"]["NAME"];
						$assoPrefixe = $stack[0]["attrs"]["PREFIX"];
						$assoNodeToSort = $stack[0]["children"];
						$sAssoInOut = $childNode["attrs"]["TYPE"];
						
						$sAssoIn = "";
						$sAssoOut = "";
						
					
						// debut affichage asso SANS table d'asso ----------------------		
					
						echo "<div class=\"".replaceBadCarsInStr($assoName)."\" id=\"".replaceBadCarsInStr($assoName)."\">\n";
						echo "<div class=\"".replaceBadCarsInStr($assoName)."Label\" id=\"".replaceBadCarsInStr($assoName)."Label\">\n";
						//echo $assoName;
						echo "</div>";
						echo "<div class=\"".replaceBadCarsInStr($assoName)."Values\" id=\"".replaceBadCarsInStr($assoName)."Values\">\n";
						$tempStyles .= ".".replaceBadCarsInStr($assoName)."{\n";
						$tempStyles .= "}\n";
						$tempStyles .= ".".replaceBadCarsInStr($assoName)."Label{\n";
						$tempStyles .= "}\n";
						$tempStyles .= ".".replaceBadCarsInStr($assoName)."Values{\n";
						$tempStyles .= "}\n";
						// on connait $sAssoOut -- on recommence la recherche de foreign vers $sAssoOut
						
						
						$eOrdre = 1;
						// vérifie s'il y a un champ ordre
						foreach ($assoNodeToSort as $nodeId => $nodeValue) {	
							if ($nodeValue["attrs"]["NAME"] == "ordre"){ // cas pas statut|ordre|id	
								$eOrdre = 0;
							}
						}
						$sql = "select * from ".$assoName." where ".$assoPrefixe."_";
						if ($sAssoInOut == "in") {
							$sql.= $classeName." = ".$id." and ".$assoPrefixe."_statut = ".DEF_ID_STATUT_LIGNE;
						}
						else if ($sAssoInOut == "out") {
							$idTemp = getItemValue($oRes, $assoName);
							$sql.= "id = ".$idTemp." and ".$assoPrefixe."_statut = ".DEF_ID_STATUT_LIGNE;
							
						}
						else if ($sAssoInOut == "asso") {
							$idTemp = $oRes->get_id();
							$sql.= $classeMain." = ".$idTemp;
					
						}
						if ($eOrdre == 0) $sql.= " order by ".$assoPrefixe."_ordre";
						
						$oTempClasse = dbGetObjectsFromRequete($assoName, $sql);
						
						
						if ($sAssoInOut == "asso") {
						
							if ( $oTemp->getDisplay() != $classeMain) $tempDisplay = $oTemp->getDisplay();
							else $tempDisplay = $oTemp->getAbstract();
							
							eval("$"."oTemp = new ".$tempDisplay."();");
							$sXML = $oTemp->XML;
							unset($stack);
							$stack = array();
							xmlClassParse($sXML);
							$assoName = $stack[0]["attrs"]["NAME"];
							$assoPrefixe = $stack[0]["attrs"]["PREFIX"];
							$assoNodeToSort = $stack[0]["children"];	
							
							$idTemp = getItemValue($oTempClasse[0], $tempDisplay);
							$sql = "select * from ".$tempDisplay." where ".$assoPrefixe."_id = ".$idTemp;
							$oTempClasse = dbGetObjectsFromRequete($assoName, $sql);
							
						}
					
						$eCountResizeImg = 0;
						for ($a = 0; $a < sizeof($oTempClasse); $a++) {
							$aTempClasse = $oTempClasse[$a];
							$idTemp = $aTempClasse->get_id();
							eval("$"."oTemp = new ".$assoName."(".$idTemp.");");
							
							if(is_array($assoNodeToSort)){
								foreach ($assoNodeToSort as $nodeId => $nodeValue) {
									scanNode($nodeValue, $stack, $oTemp, $assoName, replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$assoName);
								}
							}
							$tempStyles .= ".".replaceBadCarsInStr($assoName)."Value{\n";
							$tempStyles .= "}\n";
							$tempStyles .= ".".replaceBadCarsInStr($assoName)."ValueDisplay{\n";
							$tempStyles .= "}\n";
							$tempStyles .= ".".replaceBadCarsInStr($assoName)."ValueAbstract{\n";
							$tempStyles .= "}\n";
									
						
							///////////////////////////// ASSO des ASSO BIS /////////////////////////
						
							foreach ($assoNodeToSort as $nodeId => $nodeAssoValue) {	
								if ($nodeAssoValue["attrs"]["NAME"] == "id" && $nodeAssoValue["attrs"]["OPTION"] == "asso"){	
									foreach ($nodeAssoValue["children"] as $childKey => $childAssoNode){
										if ($childAssoNode["name"] == "OPTION")  {
											$sAssoPlusClasse = $childAssoNode["attrs"]["ASSO"];
											eval("$"."oTempsAssoPlus = new ".$sAssoPlusClasse."();");
											
											
											$sXML = $oTempsAssoPlus->XML;
											unset($stack);
											$stack = array();
											xmlClassParse($sXML);
								
											$assoPlusName = $stack[0]["attrs"]["NAME"];
											$assoPlusPrefixe = $stack[0]["attrs"]["PREFIX"];
											$assoPlusNodeToSort = $stack[0]["children"];					
											
											$eOrdre = 1;
											// vérifie s'il y a un champ ordre
											foreach ($assoPlusNodeToSort as $nodeId => $nodeValue) {	
												if ($nodeValue["attrs"]["NAME"] == "ordre"){ // cas pas statut|ordre|id	
													$eOrdre = 0;
												}
											}
											
											//eval("$"."foreignId = ".$oTempClasse."->get_id();");
											
											if ($childAssoNode["attrs"]["TYPE"] == "in") {
												$foreignId = $aTempClasse->get_id();
												$sql = "select * from ".$sAssoPlusClasse." where ".$assoPlusPrefixe."_".$assoName." = ".$foreignId." and ".$assoPlusPrefixe."_statut = ".DEF_ID_STATUT_LIGNE;
												if ($eOrdre == 0) $sql.= " order by ".$sAssoPlusClasse."_ordre";
											}
											elseif ($childAssoNode["attrs"]["TYPE"] == "out") {
												$foreignId = getItemValue(aTempClasse, $sAssoPlusClasse);
												$sql = "select * from ".$sAssoPlusClasse." where ".$assoPlusPrefixe."_id = ".$foreignId." and ".$assoPlusPrefixe."_statut = ".DEF_ID_STATUT_LIGNE;
												if ($eOrdre == 0) $sql.= " order by ".$sAssoPlusClasse."_ordre";
											} 
											elseif ($childAssoNode["attrs"]["TYPE"] == "asso") {
												$sql = "select * from ".$sAssoPlusClasse." where ".$assoPlusPrefixe."_".$assoName." = ".$idTemp;
											}
											
											$aAssoPlusClasse = dbGetObjectsFromRequete($sAssoPlusClasse, $sql);
											
											if (sizeof($aAssoPlusClasse)>0) {
												debutDivs ($assoName.replaceBadCarsInStr($sAssoPlusClasse));
											
												for ($c = 0; $c < sizeof($aAssoPlusClasse); $c++) {	
													$oAssoPlusClasse = $aAssoPlusClasse[$c];
													$idAssoTemp = $oAssoPlusClasse->get_id();
													eval("$"."oTempasso = new ".$sAssoPlusClasse."(".$idAssoTemp.");");
													if(is_array($assoPlusNodeToSort) ){
														foreach ($assoPlusNodeToSort as $nodeId => $assoPlusNodeValue) {
															
															
															if (isset($assoPlusNodeValue["attrs"]["NAME"]) && !ereg("statut|ordre|id|".$assoName."", $assoPlusNodeValue["attrs"]["NAME"])){ 
																$eKeyValue = getItemValue($oTempasso, $assoPlusNodeValue["attrs"]["NAME"]);
																if (critereIfdisplay($assoPlusNodeValue, $aAssoPlusClasse, $eKeyValue) == true){	// displayif	
										
																	if ($assoPlusNodeValue["attrs"]["FKEY"]){ // cas de foregin key	
																		
																		if ($childAssoNode["attrs"]["TYPE"] == "asso") {	
																			
																			$sTempClasse = $assoPlusNodeValue["attrs"]["FKEY"];
																				
																			if ($eKeyValue > -1){
																				$sAssoClasse3 = $sTempClasse;
																				debutDiv ($assoName.replaceBadCarsInStr($sAssoPlusClasse));
																				
																				eval("$"."oTempasso3 = new ".$sAssoClasse3."(".$eKeyValue.");");
																				
																				$sXML = $oTempasso3->XML;
																				unset($stack);
																				$stack = array();
																				xmlClassParse($sXML);
																				
																				$classeName = $stack[0]["attrs"]["NAME"];
																				$classePrefixe = $stack[0]["attrs"]["PREFIX"];
																				$classeNodeToSort = $stack[0]["children"];
			
																				if(is_array($classeNodeToSort)){
																					foreach ($classeNodeToSort as $nodeId => $nodeValue) {
																						scanNode($nodeValue, $stack, $oTempasso3, $sAssoPlusClasse, replaceBadCarsInStr($classeName).replaceBadCarsInStr($nodeValue["attrs"]["NAME"]));
																					}
																				
																				}
																				finDiv (replaceBadCarsInStr($assoPlusNodeValue["attrs"]["NAME"]));
																			}
																			else{
																				echo "";
																			}
																		}
																		else {
																			isKey($eKeyValue, $assolusNodeValue);
																		}
																	}// fin fkey
																	
																	elseif ($assoPlusNodeValue["attrs"]["OPTION"] == "enum"){ // cas enum		
																		isEnum ($eKeyValue, $assoPlusNodeValue);
																	} // fin cas enum
																	else{ // cas typique
																		debutDiv ($assoName.replaceBadCarsInStr($sAssoPlusClasse));
																		if ($eKeyValue > -1){ // cas typique typique
																		
																			
																			if ($assoNodeValue["attrs"]["OPTION"] == "filename"){ // cas filename
																				isFilename ($eKeyValue, $assoPlusNodeValue);
																			}// if (filename(
																			else if ($assoNodeValue["attrs"]["OPTION"] == "file"){ // cas file
																				isFilePlus ($eKeyValue, $assoPlusNodeValue, $assoName);
																			}
																			else if ($assoPlusNodeValue["attrs"]["OPTION"] == "bool"){
																				isBool ($eKeyValue);		
																			} // fin boolean
																			else if ($assoPlusNodeValue["attrs"]["TYPE"] == "date"){ 
																				isDate ($eKeyValue);
																			}
																			else if ($assoPlusNodeValue["attrs"]["OPTION"] == "link"){ // cas link
																				isLink ($eKeyValue, $assoPlusNodeValue);
																			}
																			else if ($assoPlusNodeValue["attrs"]["OPTION"] == "filedir"){ // cas link
																				isFiledir ($eKeyValue, $nodeValue);
																			}
																			else{// cas typique typique typique	
																				echo $eKeyValue;
																			}
																		}
																		else{
																			echo "";
																		}	
																		finDiv (replaceBadCarsInStr($assoPlusNodeValue["attrs"]["NAME"]));
																	}			
																	
																	
																} // if (critereIfdisplay($assoNodeValue, $aAssoClasse, $eKeyValue) == true){	
																
																
															 }	
															}
														}
													 
													
													
												} //for ($c = 0; $c < sizeof($aAssoClasse); $c++) {
												finDiv (replaceBadCarsInStr($assoPlusNodeValue["attrs"]["NAME"]));
											} //if (sizeof($aAssoPlusClasse)>0) {	
										}
									}
								}
			
						
						/////////////////////////////  ASSO des ASSO BIS /////////////////////////
							
						
							} // fin for ($i = 0; $i < sizeof($oTempClasse); $i++) {
					
				
							
						// fin affichage asso SANS table d'asso ----------------------		
						} //fin if ($childNode["name"] == "OPTION") 
						echo "</div>\n";
						echo "</div>\n";
					} //if ($childNode["name"] == "OPTION")  {	
					
				}	// if ($aNodeToSort[$i]["attrs"]["OPTION"] == "asso"){ // cas file
			}
		}
	}
}


//-------------------------
?>

<!--<div class="fermer" id="fermer"><a href="javascript:window.close()">Fermer</a></div>-->
<?php
echo "</div>\n";
?>
</table>
<?php
echo "<!-- styles -- sample --\n";
echo "<style type=\"text/css\">\n";
echo $tempStyles;
echo ".fermer{\n";
echo "}\n";
echo "</style>\n";
echo "-- styles -- sample -->\n";
} else {
	die("Erreur ".$classeName." non trouvé");
}
?>