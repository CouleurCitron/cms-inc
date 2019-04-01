<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
 
$id = getItemValue($oRes, "id"); 


$aAssoInfos = array();
 
for ($j=0; $j<count($aNodeToSort); $j++) {
	if ($aNodeToSort[$j]["name"] == "ITEM") {
		 
		if ($aNodeToSort[$j]["attrs"]["ASSO"] || $aNodeToSort[$j]["attrs"]["ASSO_VIEW"] || $aNodeToSort[$j]["attrs"]["ASSO_EDIT"]) { // cas d'asso 
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$j]["attrs"]["NAME"]);			
			
			$aTempClasse = array();
			if ($aNodeToSort[$j]["attrs"]["ASSO"])
				$aTempClasse = split(',', $aNodeToSort[$j]["attrs"]["ASSO"]);		
			elseif ($aNodeToSort[$j]["attrs"]["ASSO_VIEW"])
				$aTempClasse = split(',', $aNodeToSort[$j]["attrs"]["ASSO_VIEW"]);		
			elseif ($aNodeToSort[$j]["attrs"]["ASSO_EDIT"])
				$aTempClasse = split(',', $aNodeToSort[$j]["attrs"]["ASSO_EDIT"]);		
			
			for ($m=0; $m<sizeof($aTempClasse);$m++) {
				
				//$sTempClasse = $aNodeToSort[$j]["attrs"]["ASSO"];
				$sTempClasse = $aTempClasse[$m]; 
				
				
				
				$myAssoClasse = $aTempClasse[$m];  
				eval("$"."oTemp = new ".$sTempClasse."();");
				eval("$"."oMyAsso = new ".$sTempClasse."();"); 
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
	
				$foreignName = $stack[0]["attrs"]["NAME"];
				$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
				$foreignNodeToSort = $stack[0]["children"];
				
				$tempIsAbstractForeign = false;
				$tempForeignAbstract = "";
				$tempIsDisplayForeign = false;
				$tempForeignDisplay = "";
				$tempAsso = $stack[0]["attrs"]["NAME"];
				$tempAssoPrefixe = $stack[0]["attrs"]["PREFIX"];
				$tempAssoIn = "";
				$tempAssoOut = "";
				if(is_array($foreignNodeToSort)){
					foreach ($foreignNodeToSort as $nodeId => $nodeValue) {				
						if ($nodeValue["attrs"]["NAME"] == $oTemp->getAbstract()){					
							if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
								$tempIsAbstractForeign = true;
								$tempForeignAbstract = $nodeValue["attrs"]["FKEY"];
								//break;
							}
						}
						if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){					
							if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
								$tempIsDisplayForeign = true;
								$tempForeignDisplay = $nodeValue["attrs"]["FKEY"];
								//break;
							}
						}
						if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
							if ($nodeValue["attrs"]["FKEY"] == $classeName){	
								$tempAssoIn = $nodeValue["attrs"]["FKEY"]; // obvious
								$tempAssoInName = $nodeValue["attrs"]["NAME"]; // obvious
								 
							}
							else{
								$tempAssoOut = $nodeValue["attrs"]["FKEY"]; // 
								$tempAssoOutName = $nodeValue["attrs"]["NAME"]; // obvious
							}
						}
					}
				} 
				if ($tempAssoOut != ""){
				 
					// debut affichage asso sur table d'asso ----------------------
					

					 
					// on connait $tempAssoOut -- on recommence la recherche de foreign vers $tempAssoOut
					
					$sTempClasse = $tempAssoOut; 
					
					eval("$"."oTemp = new ".$sTempClasse."();");
					
					//pre_dump($aForeign);
					 
					
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
		
					$foreignName = $stack[0]["attrs"]["NAME"];
					$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
					$foreignNodeToSort = $stack[0]["children"];
					
					$tempIsAbstractForeign = false;
					$tempForeignAbstract = "";
					$tempIsDisplayForeign = false;
					$tempForeignDisplay = "";
				
					if(is_array($foreignNodeToSort)){
						foreach ($foreignNodeToSort as $nodeId => $nodeValue) {		
								//echo $nodeValue["attrs"]["NAME"]." - ".$nodeValue["attrs"]["FKEY"]." : ".$oTemp->getAbstract()." ".$oTemp->getDisplay()."<br />";		
							if ($nodeValue["attrs"]["NAME"] == $oTemp->getAbstract()){					
								if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
									$tempIsAbstractForeign = true;
									$tempForeignAbstract = $nodeValue["attrs"]["FKEY"];
									//break;
								}
							}
							if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){					
								if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
									$tempIsDisplayForeign = true;
									$tempForeignDisplay = $nodeValue["attrs"]["FKEY"];
									//break;
								}
							}
						}
					} 
					if ($_GET["Type"]  == '') {  
						$sql = "select ".$sTempClasse.".* from ".$sTempClasse.", ".$myAssoClasse." "; 
						
						if ((preg_match("/rau_/msi", $tempAssoIn) || preg_match("/shp_/msi", $tempAssoIn)) && $myAssoClasse != "shp_asso_produitgamme"  &&  preg_match("/shp_/msi", $myAssoClasse) ) { 
							 $sql.= " where ".$tempAssoPrefixe."_".str_replace("shp_", "id_", str_replace("rau_", "id_", $tempAssoIn))." = ".$id." ";
							 $sql.= " and ".$myAssoClasse.".".$tempAssoPrefixe."_".str_replace("shp_", "id_", str_replace("rau_", "id_", $tempAssoOut))." = ".$foreignName.".".$foreignPrefixe."_id ";  
						}
						else { 
							$sql.= " where ".$tempAssoPrefixe."_".$tempAssoInName." = ".$id." ";
							$sql.= " and ".$myAssoClasse.".".$tempAssoPrefixe."_".$tempAssoOutName." = ".$foreignName.".".$foreignPrefixe."_id ";  
						}
					}
					else {
						$sql = "select DISTINCT ".$sTempClasse.".* from ".$sTempClasse.", ".$myAssoClasse." "; 
						if ((preg_match("/rau_/msi", $tempAssoIn) || preg_match("/shp_/msi", $tempAssoIn)) && $myAssoClasse != "shp_asso_produitgamme" &&  preg_match("/shp_/msi", $myAssoClasse) ) { 
							$sql.= " where   ".$myAssoClasse.".".$tempAssoPrefixe."_".str_replace("shp_", "id_", str_replace("rau_", "id_", $tempAssoOut))." = ".$foreignName.".".$foreignPrefixe."_id ";  
						}
						else {
							$sql.= " where ".$myAssoClasse.".".$tempAssoPrefixe."_".$tempAssoOutName." = ".$foreignName.".".$foreignPrefixe."_id "; 
						}
					}
					
					
					if ($oTemp->getGetterStatut() != "none"){  		
						$sql.= " and ".$foreignPrefixe."_statut = ".DEF_ID_STATUT_LIGNE;
					} 
					//print $sql;
					
					//echo $sTempClasse."<br />";
					$aForeign = dbGetObjectsFromRequete ($sTempClasse, $sql);
					//$aForeign = dbGetObjects($sTempClasse);
					  
					$aValues = array (); 
					if (count($aForeign) < 200 && count($aForeign)>0) {
						
						for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){ 
							$oForeign = $aForeign[$iForeign];   
							if ($tempIsAbstractForeign){   
								$value = getItemValue($oForeign, $oForeign->getAbstract());	
							}
							else{  
								$value = getItemValue($oForeign, $oForeign->getDisplay());		
								
							} 
							
							if (array_key_exists ( $sTempClasse, $aAssoInfos )) {
								$aAssoInfos[$sTempClasse]["typeDisplay"] = $typeDisplay ; 
								$aAssoInfos[$sTempClasse]["translateDisplay"] = $translateDisplay ; 			 	
							}
							else {
								$aAssoInfos[$sTempClasse] = array ();
								// traduction ????
								if(!is_null($oForeign->XML_inherited))
									$sXML = $oForeign->XML_inherited;
								else
									$sXML = $oForeign->XML;
								//$sXML = $oTemp->XML;
								 
								unset($stack);
								$stack = array();
								xmlClassParse($sXML);
		
								$foreignNodeToSort = $stack[0]["children"];
								$tempIsAbstractForeign = false;
								$tempForeignAbstract = "";
								$tempIsDisplayForeign = false;
								$tempForeignDisplay = "";
		
								if(is_array($foreignNodeToSort)){ 
									foreach ($foreignNodeToSort as $nodeId => $nodeValue) {		
										if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){	
											$valueDisplay = $nodeValue["attrs"]["NAME"];
											$typeDisplay = $nodeValue["attrs"]["TYPE"]; 
											$translateDisplay = $nodeValue["attrs"]["TRANSLATE"]; 	
											$fkeyDisplay = $nodeValue["attrs"]["FKEY"];
											
											$aAssoInfos[$sTempClasse]["typeDisplay"] = $typeDisplay ; 
											$aAssoInfos[$sTempClasse]["translateDisplay"] = $translateDisplay ; 	
										}									 
									}
								} 
								
							}
							
							$typeDisplay = $aAssoInfos[$sTempClasse]["typeDisplay"]; 
							$translateDisplay = $aAssoInfos[$sTempClasse]["translateDisplay"]; 	
							
							
							if (DEF_APP_USE_TRANSLATIONS && $translateDisplay!='') {	 		
								if ($typeDisplay == "int") {
									if ($translateDisplay == 'reference')
										$value = $translator->getByID($value);
								} elseif ($typeDisplay == "enum") {
									if ($translateDisplay == "value")
										$value =  $translator->getText($value);
								} else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";
							} 
							 
							
							if( $myAssoClasse == "news_assoinscrittheme" ) {
								 
								if ($oMyAsso->get_statut() == DEF_ID_STATUT_LIGNE) { 
									//$eKeyValue =  $value; 
									array_push ($aValues, $value);
								} 
							}
							else {
								array_push ($aValues, $value);
							}
							// uniquemque pour les tags
							if ($classeName == "cms_tag") {
								$sql = "select * from ".$tempAsso." where ".$tempAssoPrefixe."_".$tempAssoIn." = ".$id." and ".$tempAssoPrefixe."_".$tempAssoOut." = ".$tempId.""; 
	 
								$aClasseId = dbGetObjectsFromRequete ($tempAsso, $sql);
								if (sizeof($aClasseId) > 0) {
									for ($l=0; $l<sizeof($aClasseId); $l++) {
										$oClasseId = $aClasseId[$l]; 
										$oClasse = new Classe ($oClasseId->get_classe());
										$sClasse = $oClasse->get_nom();
										
										// on teste si le tag est choisi pour des enregistrements particulier
										if ($oClasseId->get_classeid()!=-1) {
											eval ("$"."oClasse = new ".$sClasse." (".$oClasseId->get_classeid().");"); 
											//display
											//eval ("echo  $"."oClasse->get_".strval($oClasse->getDisplay())."();");
											//echo " - ";
											//abstract
											//eval ("echo $"."oClasse->get_".strval($oClasse->getAbstract())."();");
											//echo "<br />\n";
											
										}
										else {
											//echo "** pour tous les enregistrements";
											//echo "<br />\n";
											
										}
										
									}
								} 
							}
							
						} 
							
													
						//}						
					} 
					$eKeyValue = implode (",", $aValues);
					
					$letter = NumToLetter($l+1+$m);
					$case = $letter.($k+2);
					//echo $i." ".$case." ";
					$objPHPExcel->setActiveSheetIndex(0)		
								->setCellValue($case, (utf8_encode(stripslashes((($eKeyValue))))));
					 
					//echo utf8_encode(stripslashes($eKeyValue));
					//echo $eKeyValue.";";
					// debut affichage asso sur table d'asso ----------------------		
				}
				 
			}
		}
	}
	
}  
?>