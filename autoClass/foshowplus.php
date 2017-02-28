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

$sXML = $oRes->XML;
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

if($oRes) {

$tempStyles = ".".replaceBadCarsInStr($classeName)."{\n";
$tempStyles .= "}\n";

echo "<div class=\"".replaceBadCarsInStr($classeName)."\" id=\"".replaceBadCarsInStr($classeName)."\">\n";

$tempGroup = "nogroup";

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
		
			
		if (!preg_match("/statut|ordre|id/msi", $aNodeToSort[$i]["attrs"]["NAME"])){ // cas pas statut|ordre|id	
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);

			if (critereIfdisplay($aNodeToSort[$i], $oRes, $eKeyValue) == true){	// displayif	
				/*echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."\">\n";					
				echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label\">\n";			
				if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
					echo stripslashes($aNodeToSort[$i]["attrs"]["LIBELLE"]);		
				}
				else{
					echo stripslashes($aNodeToSort[$i]["attrs"]["NAME"]);		
				}			
				echo "</div>\n";*/
				echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value\">\n";
				
				
				if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key		
					$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
					/*if ($eKeyValue > -1){
						$oTemp = cacheObject($sTempClasse, $eKeyValue);
						echo "<a href=\"../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php?id=".$oTemp->get_id()."\">";
						echo getItemValue($oTemp, $oTemp->getDisplay());
						echo "</a>";
					}
					else{
						echo "n/a";
					}*/
				}// fin fkey
			elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum		
				if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
					foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
						if($childNode["name"] == "OPTION"){ // on a un node d'option				
							if ($childNode["attrs"]["TYPE"] == "value"){
								if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
									echo $childNode["attrs"]["LIBELLE"];
									break;
								}
							} //fin type  == value				
						}
					}
				}		
			} // fin cas enum
				else{ // cas typique
					if ($eKeyValue > -1){ // cas typique typique
						if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
							if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue)){ // le fichier existe
								if (preg_match("/\.gif$/msi",$eKeyValue) || preg_match("/\.png$/msi",$eKeyValue) || preg_match("/\.jpg$/msi",$eKeyValue) || preg_match("/\.jpeg$/msi",$eKeyValue)){ // image					
									echo "<img src=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" border=\"0\" alt=\"".$eKeyValue."\" />";
									echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a>\n";
								}
								else if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
									foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
									$itemLbl=$childNode["attrs"]["ITEMLIBELLE"];
									}
								$countoption=count($itemLbl);
									foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
										if ($childNode["name"] == "OPTION")  { // on a un node d'option
											if (($countoption==1) && isset($childNode["attrs"]["ITEMLIBELLE"]) && ($childNode["attrs"]["TYPE"]=="link")) {	// on a un node d'option link avec un ITEMLIBELLE
											echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" target=\"_blank\" title=\"".$libelle."\">".$libelle."</a>\n";
											echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a><br />\n";
											}
											else if ($countoption!=1){
											echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" target=\"_blank\" title=\"".$libelle."\">".$eKeyValue."</a>\n";
											echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a>\n";
											}
										}
									}
								}
							} // if (is_file(
						}
						else if ($aNodeToSort[$i]["attrs"]["OPTION"] == "bool"){ // boolean
							if (intval($eKeyValue) == 1){
								echo "oui";
							}
							else{
								echo "non";
							}							
						}
						else if ($aNodeToSort[$i]["attrs"]["TYPE"] == "date"){ // date
							// expected : jj/mm/aaaa
							if (preg_match("/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/msi", $eKeyValue)){	
								$jj = preg_replace("/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/msi", "$1", $eKeyValue);	
								$mm = preg_replace("/[0-9]{2}\/([0-9]{2})\/[0-9]{4}/msi", "$1", $eKeyValue);
								$aaaa = preg_replace("/[0-9]{2}\/[0-9]{2}\/([0-9]{4})/msi", "$1", $eKeyValue);						
							
							}
							else if (preg_match("/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/msi", $eKeyValue)){// expected : aaaa/mm/jj
								$aaaa = preg_replace("/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/msi", "$1", $eKeyValue);	
								$mm = preg_replace("/[0-9]{4}\/([0-9]{2})\/[0-9]{2}/msi", "$1", $eKeyValue);
								$jj = preg_replace("/[0-9]{4}\/[0-9]{2}\/([0-9]{2})/msi", "$1", $eKeyValue);							
							
							}
							if ($mm != "00"){	//00/00/1999 devient 1999 - 00/02/1998 devient 02/1998						
								if ($jj != "00"){
									echo $jj."/";
								}
								echo $mm."/";
							}
							echo $aaaa;
						}
						else if ($aNodeToSort[$i]["attrs"]["OPTION"] == "link"){ // cas link
							if ($eKeyValue != ""){
								$href=$eKeyValue;
								$libelle=$eKeyValue;
								if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){									foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
										if($childNode["name"] == "OPTION"){ // on a un node d'option				
											if ($childNode["attrs"]["TYPE"] == "link"){// on a un node d'option link
												if (isset($childNode["attrs"]["LIBELLE"]) && ($childNode["attrs"]["LIBELLE"] != "")){
													$libelle =$childNode["attrs"]["LIBELLE"];
												}
											} //fin type  == link				
										}
									}
								}	
								
							}	//if ($eKeyValue != ""){		
						}
						else if ($aNodeToSort[$i]["attrs"]["OPTION"] == "filedir"){ // cas link
							if ($eKeyValue != ""){
								if (is_file($_SERVER['DOCUMENT_ROOT'].$eKeyValue)){ // le fichier existe
									if (preg_match("/\.gif$/msi",$eKeyValue) || preg_match("/\.png$/msi",$eKeyValue) || preg_match("/\.jpg$/msi",$eKeyValue) || preg_match("/\.jpeg$/msi",$eKeyValue)){ // image					
										if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){									
											foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
												echo "<img src=\"".$eKeyValue."\" width=\"".$childNode["attrs"]["WIDTH"]."\" height=\"".$childNode["attrs"]["HEIGHT"]."\" title=\"Image édité\"><br>\n";			
												
											}
										}
										
									}			
								}
							} // if (is_file(
						}
						else{// cas typique typique typique	
							echo $eKeyValue;
						}
					}
					else{
						echo "";
					}				
				}			
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value{\n";
				$tempStyles .= "}\n";
				
				//echo "</div>\n";
				echo "</div>\n";
				
			} // ifdisplay
		} // cas pas statut||id	
		
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
		if (preg_match("/id/msi", $aNodeToSort[$i]["attrs"]["NAME"])){ 
			
			if ($aNodeToSort[$i]["attrs"]["OPTION"] == "asso"){ // cas file
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if ($childNode["name"] == "OPTION")  {	
								
					
			// cas d'asso
			$sTempClasse = $childNode["attrs"]["ASSO"];
			eval("$"."oTemp = new ".$sTempClasse."();");
			$aForeign = dbGetObjects($sTempClasse);
			
			// cas des deroulant d'id, pointage vers foreign
			//$sXML = $aForeign[0]->XML;
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
						}
						else{
							$tempAssoOut = $nodeValue["attrs"]["FKEY"]; // 
							if (isset($nodeValue["attrs"]["LIBELLE"]) && ($nodeValue["attrs"]["LIBELLE"] != "")){
								$tempAssoLibelle = $nodeValue["attrs"]["LIBELLE"];
							}
							else{
								$tempAssoLibelle = $tempAssoOut;
							}
						}
					}
				}
			}
			
			// asso correspondant à la classe principale
			if ($tempAssoOut != ""){
				
				// debut affichage asso sur table d'asso ----------------------
				echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."\" id=\"".replaceBadCarsInStr($tempAssoOut)."\">\n";
				echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."Label\" id=\"".replaceBadCarsInStr($tempAssoOut)."Label\">\n";
				echo $tempAssoLibelle;
				//echo "tempAssoOut";	
				echo "</div>\n";
				echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."Values\" id=\"".replaceBadCarsInStr($tempAssoOut)."Values\">\n";
				$tempStyles .= ".".replaceBadCarsInStr($tempAssoOut)."{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($tempAssoOut)."Label{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($tempAssoOut)."Values{\n";
				$tempStyles .= "}\n";
				// on connait $tempAssoOut -- on recommence la recherche de foreign vers $tempAssoOut
				
				$sTempClasse = $tempAssoOut;
				
				eval("$"."oTemp = new ".$sTempClasse."();");
				$aForeign = dbGetObjects($sTempClasse);
				
				// tri eventuel sur ordre
				if ((count($aForeign) > 0) && isset($aForeign[0]->ordre)){
					$indexScan = array();				
					for ($k=0;$k<count($aForeign);$k++){						
						$indexScan[] = $aForeign[$k]->ordre;					
					}
					asort($indexScan);				
					$indexScan = array_keys($indexScan);					
					$aForeignSorted = array();
					for ($k=0;$k<count($indexScan);$k++){						
						$aForeignSorted[] = $aForeign[$indexScan[$k]];					
					}
					$aForeign = $aForeignSorted;
				}	// 	if ((count($aForeign) > 0) && isset($aForeign[0]->ordre)){				
				
				// cas des deroulant d'id, pointage vers foreign
				//$sXML = $aForeign[0]->XML;
				$sXML = $oTemp->XML;
				unset($stack);
				$stack = array();
				xmlClassParse($sXML);
	
				$foreignName = $stack[0]["attrs"]["NAME"];
				$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
				$foreignNodeToSort = $stack[0]["children"];
				$foreignPage = $stack[0]["attrs"]["PAGE"];
				
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
				$tempStyles .= ".".replaceBadCarsInStr($tempAssoOut)."Value{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($tempAssoOut)."ValueDisplay{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($tempAssoOut)."ValueAbstract{\n";
				$tempStyles .= "}\n";
							
				for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){
					$oForeign = $aForeign[$iForeign];
					if ($oTemp->getGetterStatut() != "none"){
						eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");					
					}
					else{
						$tempStatus = DEF_ID_STATUT_LIGNE;
					}
					eval ("$"."tempId = $"."oForeign->get_id();");
					
					if ($tempStatus == DEF_ID_STATUT_LIGNE){
						// test sur select. chercher id de la fiche en cours ($id) et id du foreign en cours ($tempId) dans Asso.
						// select count from Asso where $tempAssoIn = $id and $tempAssoOut = $tempId
						
						if (getCount_where($tempAsso, array($tempAssoPrefixe."_".$tempAssoIn, $tempAssoPrefixe."_".$tempAssoOut), array($id,$tempId), array("NUMBER", "NUMBER")) ==  1){
							echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."Value\" id=\"".replaceBadCarsInStr($tempAssoOut)."Value\">\n";							
							echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."ValueDisplay\" id=\"".replaceBadCarsInStr($tempAssoOut)."ValueDisplay\">\n";							
							if (is_file($_SERVER['DOCUMENT_ROOT']."/frontoffice/".$tempAssoOut."/foshow_".$tempAssoOut.".php")){
								if (isset($foreignPage)&&$foreignPage!=null){
								echo "<a href=".$foreignPage."?id=".$oForeign->get_id()." title=\"lien vers ".$tempAssoOut."\">";
								}
								else {
								echo "<a href=\"/frontoffice/".$tempAssoOut."/foshow_".$tempAssoOut.".php?id=".$oForeign->get_id()."\" title=\"lien vers ".$tempAssoOut."\">";
								}
							}
							
							if ($tempIsDisplayForeign){
								eval('$eForeignId=$oForeign->get_'.strval($oTemp->getDisplay()).'();');
$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
								eval ("echo $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
							}
							else{
								eval ("echo substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
							}
							
							if (is_file($_SERVER['DOCUMENT_ROOT']."/frontoffice/".$tempAssoOut."/foshow_".$tempAssoOut.".php")){
								echo "</a>";		
							}
							echo "</div>\n";
							
							if ($oTemp->getDisplay() != $oTemp->getAbstract()){
								echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."ValueAbstract\" id=\"".replaceBadCarsInStr($tempAssoOut)."ValueAbstract\">\n";								
								if ($tempIsAbstractForeign){
									eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
									eval ("echo $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
								}
								else{
									eval ("echo $"."oForeign->get_".strval($oTemp->getAbstract())."();");
								}
								echo "</div>\n";
							}							
							echo "</div>\n";
						}						
					}						
				}
				echo "</div>\n";
				echo "</div>\n";
				// debut affichage asso sur table d'asso ----------------------		
			}
			else{
				// debut affichage asso SANS table d'asso ----------------------		
				if ($tempAsso != ""){ // check les records pointant vers la table sont plus que ZERO

					echo "<div class=\"".replaceBadCarsInStr($tempAsso)."\" id=\"".replaceBadCarsInStr($tempAsso)."\">\n";
					echo "<div class=\"".replaceBadCarsInStr($tempAsso)."Label\" id=\"".replaceBadCarsInStr($tempAsso)."Label\">\n";
					//echo $tempAsso;
					echo "</div>";
					echo "<div class=\"".replaceBadCarsInStr($tempAsso)."Values\" id=\"".replaceBadCarsInStr($tempAsso)."Values\">\n";
					$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."{\n";
					$tempStyles .= "}\n";
					$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."Label{\n";
					$tempStyles .= "}\n";
					$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."Values{\n";
					$tempStyles .= "}\n";
					// on connait $tempAssoOut -- on recommence la recherche de foreign vers $tempAssoOut
					
					$sTempClasse = $tempAsso;
					
					eval("$"."oTemp = new ".$sTempClasse."();");
					$aForeign = dbGetObjects($sTempClasse);
					
					// cas des deroulant d'id, pointage vers foreign
					//$sXML = $aForeign[0]->XML;
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
					
					$eOrdre = 1;
					// vérifie s'il y a un champ ordre
					foreach ($foreignNodeToSort as $nodeId => $nodeValue) {	
						if ($nodeValue["attrs"]["NAME"] == "ordre"){ // cas pas statut|ordre|id	
							$eOrdre = 0;
						}
					}
				
					$sql = "select * from ".$sTempClasse." where ".$foreignPrefixe."_".$classeName." = ".$id." and ".$foreignPrefixe."_statut = ".DEF_ID_STATUT_LIGNE;
					if ($eOrdre == 0) $sql.= " order by ".$foreignPrefixe."_ordre";
					$oTempClasse = dbGetObjectsFromRequete($sTempClasse, $sql);
					$eCountResizeImg = 0;
					
					for ($a = 0; $a < sizeof($oTempClasse); $a++) {
						$aTempClasse = $oTempClasse[$a];
						$idTemp = $aTempClasse->get_id();
						eval("$"."oTemp = new ".$sTempClasse."(".$idTemp.");");
						$nameFile="";
						$nameFileType="";
						$linkImg="";
						$showLink=true;
						if(is_array($foreignNodeToSort)){
							foreach ($foreignNodeToSort as $nodeId => $nodeValue) {	
								
								if ((isset($nodeValue["attrs"]["NAME"])) && !preg_match("/statut|ordre|id|".$classeName."/msi", $nodeValue["attrs"]["NAME"])){ // cas pas statut|ordre|id
									$eKeyValue = getItemValue($oTemp, $nodeValue["attrs"]["NAME"]);
									
									if (critereIfdisplay($nodeValue, $aTempClasse, $eKeyValue) == true){	// displayif	
									
										/*echo "<div class=\"".replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$sTempClasse."\" id=\"".replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$sTempClasse."\">\n";					
										echo "<div class=\"".replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$sTempClasse."Label\" id=\"".replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$sTempClasse."Label\">\n";			
										if (isset($nodeValue["attrs"]["LIBELLE"]) && ($nodeValue["attrs"]["LIBELLE"] != "")){
											echo stripslashes($nodeValue["attrs"]["LIBELLE"]);		
										}
										else{
											echo stripslashes($nodeValue["attrs"]["NAME"]);		
										}			
										echo "</div>\n";*/
										echo "<div class=\"".replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$sTempClasse."Value\" id=\"".replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$sTempClasse."Value\">\n";
										
					
										if ($nodeValue["attrs"]["FKEY"]){ // cas de foregin key		
											$sTempClasse = $nodeValue["attrs"]["FKEY"];
											if ($eKeyValue > -1){
												$oTemp = cacheObject($sTempClasse, $eKeyValue);
												echo "<a href=\"../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php?id=".$oTemp->get_id()."\">";
												echo getItemValue($oTemp, $oTemp->getDisplay());
												echo "</a>";
											}
											else{
												echo "";
											}
										}// fin fkey
									
										elseif ($nodeValue["attrs"]["OPTION"] == "enum"){ // cas enum		
											if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
												foreach ($nodeValue["children"] as $childKey => $childNode){
													if($childNode["name"] == "OPTION"){ // on a un node d'option				
														if ($childNode["attrs"]["TYPE"] == "value"){
															if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
																echo $childNode["attrs"]["LIBELLE"];
																break;
															}
														} //fin type  == value				
													}
												}
											}		
										} // fin cas enum
										else{ // cas typique
											if ($eKeyValue > -1){ // cas typique typique
												if ($nodeValue["attrs"]["OPTION"] == "filename"){ // cas filename
													if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
														foreach ($nodeValue["children"] as $childKey => $childNode){
															if ($eKeyValue != "") {
																$nameFile=$eKeyValue;
																$nameFileType=$childNode["attrs"]["TYPE"];
															}
														}
													} 
												}// if (filename(
												else if ($nodeValue["attrs"]["OPTION"] == "file"){ // cas file
													if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$tempAsso."/".$eKeyValue)){ // le fichier existe
														
														if (preg_match("/\.gif$/msi",$eKeyValue) || preg_match("/\.png$/msi",$eKeyValue) || preg_match("/\.jpg$/msi",$eKeyValue) || preg_match("/\.jpeg$/msi",$eKeyValue)){ // image					
															//echo $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$tempAsso."/".$eKeyValue."<br>";
															ResizeImg($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$tempAsso."/".$eKeyValue, 400,100, $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$tempAsso."/".$eKeyValue);
															if(!unlink($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$tempAsso."/".$eKeyValue)) {
																$status .= 'Erreur : Impossible de renommer le fichier temporaire '. $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$tempAsso."/".$eKeyValue;
															}
															echo "<img src=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$tempAsso."/".$eKeyValue."\" border=\"0\" alt=\"".$eKeyValue."\" />";
															echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$tempAsso."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a>\n";
														}
														else if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
															foreach ($nodeValue["children"] as $childKey => $childNode){
																$itemLbl=$childNode["attrs"]["ITEMLIBELLE"];
															}
															$countoption=count($itemLbl);
															foreach ($nodeValue["children"] as $childKey => $childNode){
																if ($childNode["name"] == "OPTION")  { // on a un node d'option
																	if (($countoption==1) && isset($childNode["attrs"]["ITEMLIBELLE"]) && ($childNode["attrs"]["TYPE"]=="link")) {	// on a un node d'option link avec un ITEMLIBELLE
																	echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$tempAsso."/".$eKeyValue."\" target=\"_blank\" title=\"".$libelle."\">".$libelle."</a><br />\n";
																	}
																	else if ($countoption!=1){
																		if ($nameFile!="" && $childNode["attrs"]["TYPE"]==$nameFileType) {
																			$nameFile = $nameFile;
																		}
																		else {
																			$nameFile = $eKeyValue;
																		}
																	}
																}
															}
														}
													} // if (is_file(
												}
												else if ($nodeValue["attrs"]["OPTION"] == "bool"){ // boolean
													if (intval($eKeyValue) == 1){
														echo "oui";
													}
													else{
														echo "non";
													}			
												} // fin boolean
												else if ($nodeValue["attrs"]["TYPE"] == "date"){ // date
													// expected : jj/mm/aaaa
													if (preg_match("/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/msi", $eKeyValue)){	
														$jj = preg_replace("/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/msi", "$1", $eKeyValue);	
														$mm = preg_replace("/[0-9]{2}\/([0-9]{2})\/[0-9]{4}/msi", "$1", $eKeyValue);
														$aaaa = preg_replace("/[0-9]{2}\/[0-9]{2}\/([0-9]{4})/msi", "$1", $eKeyValue);						
													
													}
													else if (preg_match("/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/msi", $eKeyValue)){// expected : aaaa/mm/jj
														$aaaa = preg_replace("/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/msi", "$1", $eKeyValue);	
														$mm = preg_replace("/[0-9]{4}\/([0-9]{2})\/[0-9]{2}/msi", "$1", $eKeyValue);
														$jj = preg_replace("/[0-9]{4}\/[0-9]{2}\/([0-9]{2})/msi", "$1", $eKeyValue);							
													
													}
													if ($mm != "00"){	//00/00/1999 devient 1999 - 00/02/1998 devient 02/1998						
														if ($jj != "00"){
															echo $jj."/";
														}
														echo $mm."/";
													}
													echo $aaaa;
												}
												else if ($nodeValue["attrs"]["OPTION"] == "link"){ // cas link
													if ($eKeyValue != ""){
														$href=$eKeyValue;
														$libelle=$eKeyValue;
														if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){									
														foreach ($nodeValue["children"] as $childKey => $childNode){
																if($childNode["name"] == "OPTION"){ // on a un node d'option				
																	if ($childNode["attrs"]["TYPE"] == "link"){// on a un node d'option link
																		if (isset($childNode["attrs"]["LIBELLE"]) && ($childNode["attrs"]["LIBELLE"] != "")){
																			$libelle =$childNode["attrs"]["LIBELLE"];
																		}
																	} //fin type  == link				
																}
															}
														}	
														
													}	//if ($eKeyValue != ""){		
												}
												else if ($nodeValue["attrs"]["OPTION"] == "filedir"){ // cas link
													
													if ($eKeyValue != ""){
														if (is_file($_SERVER['DOCUMENT_ROOT'].$eKeyValue)){ // le fichier existe
															if (preg_match("/\.gif$/msi",$eKeyValue) || preg_match("/\.png$/msi",$eKeyValue) || preg_match("/\.jpg$/msi",$eKeyValue) || preg_match("/\.jpeg$/msi",$eKeyValue)){ // image					
																if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){									
																	foreach ($nodeValue["children"] as $childKey => $childNode){
																		if($childNode["name"] == "OPTION"){ // on a un node d'option	
																			//echo $_SERVER['DOCUMENT_ROOT'].$eKeyValue;
																			
																			$aSize = getimagesize("../..".$eKeyValue);
																			$eWidth = $aSize[0];
																			$eHeight = $aSize[1];
																			if (isset($childNode["attrs"]["WIDTH"]) &&  $childNode["attrs"]["WIDTH"]!="" && $childNode["attrs"]["WIDTH"]!=$eWidth) {
																				$eWidth = $childNode["attrs"]["WIDTH"];
																				$eHeight = ($childNode["attrs"]["WIDTH"] * $eHeight)/ $eWidth;
																			}
																			$ligneImg="<img src=\"".$eKeyValue."\" width=\"".$eWidth."\" height=\"".$eHeight."\"  title=\"Image édité\" border=\"0\"><br>\n";						
																			if ($childNode["attrs"]["ACTION"]=="lien" || isset($childNode["attrs"]["ACTIONSRC"])) {
																				$linkImg = $ligneImg;	
																			}
																			else {
																				echo $ligneImg;
																			}
																				
																			if ($linkImg!="") {
																				if (isset($childNode["attrs"]["ACTIONSRC"])) {
																					if ($childNode["attrs"]["ACTIONSRCTYPE"] == "file") {
																						eval ("$"."eKeyValueFile = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRC"]."();");	
																						
																					}
																					else if ($childNode["attrs"]["ACTIONSRCTYPE"] == "link") {
																						eval ("$"."eKeyValueBool = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRCBOOL"]."();");	
																						eval ("$"."eKeyValueLink = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRC"]."();");		
																					}
																				}
																				
																			}
																			else {
																				$eKeyValueFile = "";
																			  	$eKeyValueLink = "";
																			}
																			
																			
																		} //if($childNode["name"] == "OPTION"){ // on a un node d'option	
																	}
																	if ($eKeyValueFile!="" && $eKeyValueLink=="") { 
																		echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$tempAsso."/".$eKeyValueFile."\" target=\"_blank\" title=\"".$eKeyValueLink."\">".$linkImg."</a>\n";
																		echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$tempAsso."/".$eKeyValueFile."\" title=\"Télécharger le fichier : '".$eKeyValueFile."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValueFile."\" /></a>\n";
																	}
																	else if ($eKeyValueFile=="" && $eKeyValueLink!="" &&  $eKeyValueBool==1) {
																		echo "<a href=\"".$eKeyValueLink."\" target=\"_blank\" title=\"Lien édité\">".$linkImg."</a>\n";	
																		$showLink = false;
																	}		
																	else {
																		echo $linkImg."\n";	
																	}
																}
																
															}			
														}
													} // if (is_file(
												}
												else{// cas typique typique typique	
													echo $eKeyValue;
												}
											}
											else{
												echo "";
											}				
										}			
										$tempStyles .= ".".replaceBadCarsInStr($nodeValue["attrs"]["NAME"])."{\n";
										$tempStyles .= "}\n";
										$tempStyles .= ".".replaceBadCarsInStr($nodeValue["attrs"]["NAME"])."Label{\n";
										$tempStyles .= "}\n";
										$tempStyles .= ".".replaceBadCarsInStr($nodeValue["attrs"]["NAME"])."Value{\n";
										$tempStyles .= "}\n";
										
										//echo "</div>\n";
										echo "</div>\n";
										
									} // ifdisplay
								} // cas pas statut|ordre|id
							
							/*if ($nodeValue["attrs"]["NAME"] == $oTemp->getAbstract()){			
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
							}*/
							
							
							}
						}
						$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."Value{\n";
						$tempStyles .= "}\n";
						$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."ValueDisplay{\n";
						$tempStyles .= "}\n";
						$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."ValueAbstract{\n";
						$tempStyles .= "}\n";
									
						/*for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){
							$oForeign = $aForeign[$iForeign];
							if ($oTemp->getGetterStatut() != "none"){
								eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");					
							}
							else{
								$tempStatus = DEF_ID_STATUT_LIGNE;
							}
							eval ("$"."tempId = $"."oForeign->get_id();");
						
							if ($tempStatus == DEF_ID_STATUT_LIGNE){
								if (getCount_where($tempAsso, array($tempAssoPrefixe."_".$tempAssoIn, $tempAssoPrefixe."_id"), array($id,$tempId), array("NUMBER","NUMBER")) ==  1){
									echo "<div class=\"".replaceBadCarsInStr($tempAsso)."Value\" id=\"".replaceBadCarsInStr($tempAsso)."Value\">\n";								
									echo "<div class=\"".replaceBadCarsInStr($tempAsso)."ValueDisplay\" id=\"".replaceBadCarsInStr($tempAsso)."ValueAbstract\">\n";								
									if ($tempIsDisplayForeign){
										eval('$eForeignId=$oForeign->get_'.strval($oTemp->getDisplay()).'();');
$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
										eval ("echo $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
									}
									else{
										eval ("echo substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
									}
									echo "</div>\n";
									
									if ($oTemp->getDisplay() != $oTemp->getAbstract()){
										echo "<div class=\"".replaceBadCarsInStr($tempAsso)."ValueAbstract\" id=\"".replaceBadCarsInStr($tempAsso)."ValueAbstract\">\n";									
										if ($tempIsAbstractForeign){
											eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
											eval ("echo $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
										}
										else{
											eval ("echo $"."oForeign->get_".strval($oTemp->getAbstract())."();");
										}
										echo "</div>\n";
									}								
									echo "</div>\n";
								}						
							}						
						}
						*/
						///////////////////////////// ASSO des ASSO BIS /////////////////////////
					
						foreach ($foreignNodeToSort as $nodeId => $nodeAssoValue) {	
							if ($nodeAssoValue["attrs"]["NAME"] == "id"){ // cas pas statut|ordre|id	
								if ($nodeAssoValue["attrs"]["OPTION"] == "asso"){	
									foreach ($nodeAssoValue["children"] as $childKey => $childAssoNode){
										if ($childAssoNode["name"] == "OPTION")  {
											$sAssoClasse = $childAssoNode["attrs"]["ASSO"];
											eval("$"."oTempasso = new ".$sAssoClasse."();");
											$aAsso = dbGetObjects($sAssoClasse);
											
											// cas des deroulant d'id, pointage vers foreign
											//$sXML = $aForeign[0]->XML;
											$sXML = $oTempasso->XML;
											unset($stack);
											$stack = array();
											xmlClassParse($sXML);
								
											$assoName = $stack[0]["attrs"]["NAME"];
											$assoPrefixe = $stack[0]["attrs"]["PREFIX"];
											$assoNodeToSort = $stack[0]["children"];					
											
											$eOrdre = 1;
											// vérifie s'il y a un champ ordre
											foreach ($assoNodeToSort as $nodeId => $nodeValue) {	
												if ($nodeValue["attrs"]["NAME"] == "ordre"){ // cas pas statut|ordre|id	
													$eOrdre = 0;
												}
											}
											//eval("$"."foreignId = ".$oTempClasse."->get_id();");
											$foreignId = $aTempClasse->get_id();
											$sql = "select * from ".$sAssoClasse." where ".$sAssoClasse."_".$sTempClasse." = ".$foreignId." and ".$sAssoClasse."_statut = ".DEF_ID_STATUT_LIGNE;
											if ($eOrdre == 0) $sql.= " order by ".$sAssoClasse."_ordre";
											$aAssoClasse = dbGetObjectsFromRequete($sAssoClasse, $sql);
											
											for ($c = 0; $c < sizeof($aAssoClasse); $c++) {
												$oAssoClasse = $aAssoClasse[$c];
												$idAssoTemp = $oAssoClasse->get_id();
												eval("$"."oTempasso = new ".$sAssoClasse."(".$idAssoTemp.");");
												if(is_array($assoNodeToSort)){
													foreach ($assoNodeToSort as $nodeId => $assoNodeValue) {	
														if (!preg_match("/statut|ordre|id|".$sTempClasse."/msi", $assoNodeValue["attrs"]["NAME"])){ // cas pas statut|ordre|id	
															/*echo $sAssoClasse."<br>";*/
															//echo $assoNodeValue["attrs"]["NAME"];
															$eKeyValue = getItemValue($oTempasso, $assoNodeValue["attrs"]["NAME"]);
															
															
															
															
																
															/*echo "<div class=\"".$sTempClasse.replaceBadCarsInStr($sAssoClasse)."Value\" id=\"".$sTempClasse.replaceBadCarsInStr($sAssoClasse)."Value\">\n";								
															$tempStyles .= ".".$sTempClasse.replaceBadCarsInStr($sAssoClasse)."Value\n";
															$tempStyles .= "}\n";
															
															// selon type
															echo $eKeyValue;
															
															
															
															echo "</div>";*/
															
															
															if (critereIfdisplay($assoNodeValue, $aAssoClasse, $eKeyValue) == true){	// displayif	
									
																
																//echo "<div class=\"".replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$sTempClasse."Value\" id=\"".replaceBadCarsInStr($nodeValue["attrs"]["NAME"]).$sTempClasse."Value\">\n";
																echo "<div class=\"".$sTempClasse.replaceBadCarsInStr($sAssoClasse)."Value\" id=\"".$sTempClasse.replaceBadCarsInStr($sAssoClasse)."Value\">\n";								
											
																if ($assoNodeValue["attrs"]["FKEY"]){ // cas de foregin key		
																	$sTempClasse = $assoNodeValue["attrs"]["FKEY"];
																	if ($eKeyValue > -1){
																		$oTemp = cacheObject($sTempClasse, $eKeyValue);
																		echo "<a href=\"../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php?id=".$oTemp->get_id()."\">";
																		echo getItemValue($oTemp, $oTemp->getDisplay());
																		echo "</a>";
																	}
																	else{
																		echo "";
																	}
																}// fin fkey
															
																elseif ($assoNodeValue["attrs"]["OPTION"] == "enum"){ // cas enum		
																	if (isset($assoNodeValue["children"]) && (count($assoNodeValue["children"]) > 0)){
																		foreach ($assoNodeValue["children"] as $childKey => $childNode){
																			if($childNode["name"] == "OPTION"){ // on a un node d'option				
																				if ($childNode["attrs"]["TYPE"] == "value"){
																					if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
																						echo $childNode["attrs"]["LIBELLE"];
																						break;
																					}
																				} //fin type  == value				
																			}
																		}
																	}		
																} // fin cas enum
																else{ // cas typique
																	if ($eKeyValue > -1){ // cas typique typique
																		if ($assoNodeValue["attrs"]["OPTION"] == "filename"){ // cas filename
																			if (isset($nodeValue["children"]) && (count($nodeValue["children"]) > 0)){
																				foreach ($nodeValue["children"] as $childKey => $childNode){
																					if ($eKeyValue != "") {
																						$nameFile=$eKeyValue;
																						$nameFileType=$childNode["attrs"]["TYPE"];
																					}
																				}
																			} 
																		}// if (filename(
																		else if ($assoNodeValue["attrs"]["OPTION"] == "file"){ // cas file
																			if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$sAssoClasse."/".$eKeyValue)){ // le fichier existe
																				
																				if (preg_match("/\.gif$/msi",$eKeyValue) || preg_match("/\.png$/msi",$eKeyValue) || preg_match("/\.jpg$/msi",$eKeyValue) || preg_match("/\.jpeg$/msi",$eKeyValue)){ // image					
																					//echo $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$tempAsso."/".$eKeyValue."<br>";
																					ResizeImg($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$sAssoClasse."/".$eKeyValue, 400,100, $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$sAssoClasse."/".$eKeyValue);
																					if(!unlink($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$sAssoClasse."/".$eKeyValue)) {
																						$status .= 'Erreur : Impossible de renommer le fichier temporaire '. $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$sAssoClasse."/".$eKeyValue;
																					}
																					echo "<img src=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$sAssoClasse."/".$eKeyValue."\" border=\"0\" alt=\"".$eKeyValue."\" />";
																					echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$sAssoClasse."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a>\n";
																				}
																				else if (isset($assoNodeValue["children"]) && (count($assoNodeValue["children"]) > 0)){
																					foreach ($nodeValue["children"] as $childKey => $childNode){
																						$itemLbl=$childNode["attrs"]["ITEMLIBELLE"];
																					}
																					$countoption=count($itemLbl);
																					foreach ($assoNodeValue["children"] as $childKey => $childNode){
																						if ($childNode["name"] == "OPTION")  { // on a un node d'option
																							if (($countoption==1) && isset($childNode["attrs"]["ITEMLIBELLE"]) && ($childNode["attrs"]["TYPE"]=="link")) {	// on a un node d'option link avec un ITEMLIBELLE
																							echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$sAssoClasse."/".$eKeyValue."\" target=\"_blank\" title=\"".$libelle."\">".$libelle."</a>\n";
																							echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$sAssoClasse."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a><br />\n";
																							}
																							else if ($countoption!=1){
																								if ($nameFile!="" && $childNode["attrs"]["TYPE"]==$nameFileType) {
																									$nameFile = $nameFile;
																								}
																								else {
																									$nameFile = $eKeyValue;
																								}
																							}
																						}
																					}
																				}
																			} // if (is_file(
																		}
																		else if ($assoNodeValue["attrs"]["OPTION"] == "bool"){ // boolean
																			if (intval($eKeyValue) == 1){
																				echo "oui";
																			}
																			else{
																				echo "non";
																			}			
																		} // fin boolean
																		else if ($assoNodeValue["attrs"]["TYPE"] == "date"){ // date
																			// expected : jj/mm/aaaa
																			if (preg_match("/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/msi", $eKeyValue)){	
																				$jj = preg_replace("/([0-9]{2})\/[0-9]{2}\/[0-9]{4}/msi", "$1", $eKeyValue);	
																				$mm = preg_replace("/[0-9]{2}\/([0-9]{2})\/[0-9]{4}/msi", "$1", $eKeyValue);
																				$aaaa = preg_replace("/[0-9]{2}\/[0-9]{2}\/([0-9]{4})/msi", "$1", $eKeyValue);						
																			
																			}
																			else if (preg_match("/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/msi", $eKeyValue)){// expected : aaaa/mm/jj
																				$aaaa = preg_replace("/([0-9]{4})\/[0-9]{2}\/[0-9]{2}/msi", "$1", $eKeyValue);	
																				$mm = preg_replace("/[0-9]{4}\/([0-9]{2})\/[0-9]{2}/msi", "$1", $eKeyValue);
																				$jj = preg_replace("/[0-9]{4}\/[0-9]{2}\/([0-9]{2})/msi", "$1", $eKeyValue);							
																			
																			}
																			if ($mm != "00"){	//00/00/1999 devient 1999 - 00/02/1998 devient 02/1998						
																				if ($jj != "00"){
																					echo $jj."/";
																				}
																				echo $mm."/";
																			}
																			echo $aaaa;
																		}
																		else if ($assoNodeValue["attrs"]["OPTION"] == "link"){ // cas link
																			if ($eKeyValue != ""){
																				$href=$eKeyValue;
																				$libelle=$eKeyValue;
																				if (isset($assoNodeValue["children"]) && (count($assoNodeValue["children"]) > 0)){									
																				foreach ($assoNodeValue["children"] as $childKey => $childNode){
																						if($assoNodeValue["name"] == "OPTION"){ // on a un node d'option				
																							if ($assoNodeValue["attrs"]["TYPE"] == "link"){// on a un node d'option link
																								if (isset($assoNodeValue["attrs"]["LIBELLE"]) && ($assoNodeValue["attrs"]["LIBELLE"] != "")){
																									$libelle =$assoNodeValue["attrs"]["LIBELLE"];
																								}
																							} //fin type  == link				
																						}
																					}
																				}		
																				if ($afficheClasse !="") {
																					if ($valueAfficheClasse=="oui") {
																						echo "<a href=\"".$href."\" target=\"_blank\" title=\"Lien édité\">".$libelle."</a><br />\n";	
																					}
																					 else if ($valueAfficheClasse=="non"){
																						echo "";
																					}
																					$afficheClasse = "";
																				}
																				else {
																					if ($showLink == true) {
																						echo "<a href=\"".$href."\" target=\"_blank\" title=\"Lien édité\">".$libelle."</a><br />\n";	
																					}
																					else {
																						$showLink == false;
																					}
																				}
																				
																			}	//if ($eKeyValue != ""){		
																		}
																		else if ($assoNodeValue["attrs"]["OPTION"] == "filedir"){ // cas link
																			
																			if ($eKeyValue != ""){
																				if (is_file($_SERVER['DOCUMENT_ROOT'].$eKeyValue)){ // le fichier existe
																					if (preg_match("/\.gif$/msi",$eKeyValue) || preg_match("/\.png$/msi",$eKeyValue) || preg_match("/\.jpg$/msi",$eKeyValue) || preg_match("/\.jpeg$/msi",$eKeyValue)){ // image					
																						if (isset($assoNodeValue["children"]) && (count($assoNodeValue["children"]) > 0)){									
																							foreach ($assoNodeValue["children"] as $childKey => $childNode){
																								if($childNode["name"] == "OPTION"){ // on a un node d'option	
																									if ($afficheClasse !="" && $valueAfficheClasse=="non") {
																										echo "";
																										$afficheClasse = "";
																									}
																									else {
																										if ($childNode["attrs"]["RESIZE"]=="true" && $eCountResizeImg >= $childNode["attrs"]["BEGIN"]) {
																											ResizeImg($_SERVER['DOCUMENT_ROOT'].$eKeyValue, $childNode["attrs"]["WIDTH"],$childNode["attrs"]["WIDTH"]+100, $_SERVER['DOCUMENT_ROOT'].$eKeyValue);
																											$ligneImg="<img src=\"".$eKeyValue."\" width=\"".$childNode["attrs"]["WIDTH"]."\" height=\"100%\" title=\"Image édité\" border=\"0\"><br>\n";						
																										}
																										else {
																											$eCountResizeImg++;
																											$aSize = getimagesize("../..".$eKeyValue);
																											$ligneImg="<img src=\"".$eKeyValue."\" ".$aSize[3]." title=\"Image édité\" border=\"0\"><br>\n";						
																										}
																										
																										if ($childNode["attrs"]["ACTION"]=="lien" || isset($childNode["attrs"]["ACTIONSRC"])) {
																											$linkImg = $ligneImg;	
																										}
																										else {
																											echo $ligneImg;
																										}
																										
																									}	//if ($afficheClasse !="" && $valueAfficheClasse=="non") {
																									if ($linkImg!="") {
																										if (isset($childNode["attrs"]["ACTIONSRC"])) {
																											if ($childNode["attrs"]["ACTIONSRCTYPE"] == "file") {
																												eval ("$"."eKeyValueFile = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRC"]."();");	
																												
																											}
																											else if ($childNode["attrs"]["ACTIONSRCTYPE"] == "link") {
																												eval ("$"."eKeyValueBool = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRCBOOL"]."();");	
																												eval ("$"."eKeyValueLink = $"."aTempClasse->get_".$childNode["attrs"]["ACTIONSRC"]."();");		
																											}
																										}
																										
																									}
																									
																									
																								} //if($childNode["name"] == "OPTION"){ // on a un node d'option	
																							}
																							if ($eKeyValueFile!="" && $eKeyValueLink=="") { 
																								echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$tempAsso."/".$eKeyValueFile."\" target=\"_blank\" title=\"".$eKeyValueLink."\">".$linkImg."</a>\n";
																							}
																							else if ($eKeyValueFile=="" && $eKeyValueLink!="" &&  $eKeyValueBool==1) {
																								echo "<a href=\"".$eKeyValueLink."\" target=\"_blank\" title=\"Lien édité\">".$linkImg."</a>\n";	
																								$showLink = false;
																							}		
																							else {
																								echo $linkImg."\n";	
																							}
																						}
																						
																					}			
																				}
																			} // if (is_file(
																		}
																		else{// cas typique typique typique	
																			echo $eKeyValue;
																		}
																	}
																	else{
																		echo "";
																	}				
																}			
																$tempStyles .= ".".replaceBadCarsInStr($assoNodeValue["attrs"]["NAME"])."{\n";
																$tempStyles .= "}\n";
																$tempStyles .= ".".replaceBadCarsInStr($assoNodeValue["attrs"]["NAME"])."Label{\n";
																$tempStyles .= "}\n";
																$tempStyles .= ".".replaceBadCarsInStr($assoNodeValue["attrs"]["NAME"])."Value{\n";
																$tempStyles .= "}\n";
																
																echo "</div>\n";
																
															} // ifdisplay
															
															
															
														}
													}
												}
											}
												
										}
									}
								}
							}
						}
						
						
						/////////////////////////////  ASSO des ASSO BIS /////////////////////////
							
						
					} // fin for ($i = 0; $i < sizeof($oTempClasse); $i++) {
					
				
					
					echo "</div>\n";
					echo "</div>\n";
				} // fin if ($tempAsso != ""){ // check les records pointant vers la table sont plus que ZERO
				// fin affichage asso SANS table d'asso ----------------------		
				} //fin if ($childNode["name"] == "OPTION")  
				} //foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
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