<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
 
$id = getItemValue($oRes, "id");

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
					
					 
					$sql = "select ".$sTempClasse.".* from ".$sTempClasse.", ".$myAssoClasse." ";
					if (ereg("rau_", $tempAssoIn) || ereg("shp_", $tempAssoIn)) {
						 $sql.= " where ".$tempAssoPrefixe."_".str_replace("shp_", "id_", str_replace("rau_", "id_", $tempAssoIn))." = ".$id." ";
						 $sql.= " and ".$myAssoClasse.".".$tempAssoPrefixe."_".str_replace("shp_", "id_", str_replace("rau_", "id_", $tempAssoOut))." = ".$foreignName.".".$foreignPrefixe."_id "; 
						
					}
					else {
						$sql.= " where ".$tempAssoPrefixe."_".$tempAssoInName." = ".$id." ";
						$sql.= " and ".$myAssoClasse.".".$tempAssoPrefixe."_".$tempAssoOutName." = ".$foreignName.".".$foreignPrefixe."_id "; 
					}
					
					if ($oTemp->getGetterStatut() != "none"){  		
						$sql.= " and ".$foreignPrefixe."_statut = ".DEF_ID_STATUT_LIGNE;
					} 
					//print $sql;
					$aForeign = dbGetObjectsFromRequeteCache ($sTempClasse, $sql, 100);
					//$aForeign = dbGetObjects($sTempClasse);
					  
					$aValues = array ();
					
					if (count($aForeign) < 200 && count($aForeign)>0) {
					
						
						
						for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){ 
							$oForeign = $aForeign[$iForeign]; 
							   
							if ($tempIsAbstractForeign){   
								$value = makeHTMLcodeXMLfriendly(stripslashes(getItemValue($oForeign, $oForeign->getAbstract())));	
							}
							else{  
								$value = makeHTMLcodeXMLfriendly(stripslashes(getItemValue($oForeign, $oForeign->getDisplay())));		
								
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
	 
								$aClasseId = dbGetObjectsFromRequeteCache ($tempAsso, $sql, 100);
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
						
						$eKeyValue = implode ("</".$sTempClasse."><".$sTempClasse.">", $aValues);
						echo "<".$myAssoClasse.">";	
						echo "<".$sTempClasse.">".$eKeyValue."</".$sTempClasse.">";	
						echo "</".$myAssoClasse.">";			 
					} 
					 
				}
				 
			}
		}
	}
	
}
?>