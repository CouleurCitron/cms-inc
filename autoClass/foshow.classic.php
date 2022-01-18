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
			//echo " # new group ";
			$tempGroup = nouveauGroup($aNodeToSort[$i], $tempGroup);
			$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."{\n";
			$tempStyles .= "}\n";
			echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."\">\n";
		}
		
			
		if (!preg_match("/statut|ordre|id/msi", $aNodeToSort[$i]["attrs"]["NAME"])){ // cas pas statut|ordre|id	
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
			if (critereIfdisplay($aNodeToSort[$i], $oRes, $eKeyValue) == true){	// displayif
				echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."\">\n";					
				echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label\">\n";			
				if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
					echo stripslashes($aNodeToSort[$i]["attrs"]["LIBELLE"]);		
				}
				else{
					echo stripslashes($aNodeToSort[$i]["attrs"]["NAME"]);		
				}			
				echo "</div>\n";
				echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value\">\n";
				
				
				if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key			
					$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
					if ($eKeyValue > -1){
						$oTemp = cacheObject($sTempClasse, $eKeyValue);
						// check Temp viewer page
						if (is_file("../".$oTemp->getClasse()."/index.php")){
							$tempViewerPage = "../".$oTemp->getClasse()."/index.php";							
						}
						elseif (is_file("../".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php")){
							$tempViewerPage = "../".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php";							
						}
						else{
							$tempViewerPage = "";
						}
						
						if ($tempViewerPage != ""){
							echo "<a href=\"".$tempViewerPage."?id=".$oTemp->get_id()."\">";
							echo getItemValue($oTemp, $oTemp->getDisplay());
							echo "</a>";
						}
						else{
							echo getItemValue($oTemp, $oTemp->getDisplay());
						}
					}
					else{
						echo "n/a";
					}
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
									if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
										foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
											$widthMax=$childNode["attrs"]["WIDTH"];
											$heightMax=$childNode["attrs"]["HEIGHT"];
											$aInfos = getimagesize($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue);
											$width = $aInfos[0];
											$height = $aInfos[1];
											if ($widthMax == $heightMax ) {
												if ($width > $height) {
													if ($width > $widthMax) {
														$widthNew = $widthMax;
														$heightNew = ($widthMax * $height) / $width;
													}					
													else {
														if ($height > $heightMax) {
														$heightNew = $heightMax;
														$widthNew = ($heightNew * $width) / $height;
														}
													}
												}
											}					
											elseif ($widthMax > $heightMax) {
												if ($width > $widthMax) {
													$widthNew = $widthMax;
													$heightNew = ($widthMax * $height) / $width;
												}
											}
											else {
												if ($height > $heightMax) {
													$heightNew = $heightMax;
													$widthNew = ($heightNew * $width) / $height;
												}
											}
										}
										echo "<img src=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" border=\"0\" height=\"".$heightNew."\"  width=\"".$widthNew."\" alt=\"".$eKeyValue."\" />";
										echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a>\n";
									}
									else {
										echo "<img src=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" border=\"0\" alt=\"".$eKeyValue."\" />";
										echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a>\n";
									}
								}
								elseif (preg_match("/\.flv$/msi",$eKeyValue)){ // video
									/*						
									$file = $_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue;									
									require_once('flv4php/FLV.php'); // Path to flv.php / (flv4php)									
									$flv = new FLV($file);
									$metadata = $flv->metadata;
									if (isset($metadata)){
										$flvW = $metadata["width"];
										$flvH = $metadata["height"]+32;
									}
									else{
										$flvW = 444;
										$flvH = 350;
									}*/
									$flvW = 444;
									$flvH = 350;
									?>
									<script src="/backoffice/cms/js/AC_RunActiveContent.js" type="text/javascript"></script>									
									<script type="text/javascript">
									swfSrc = "/backoffice/cms/utils/scrubber"+"?_vidName=<?php echo $eKeyValue; ?>&_vidURL=/custom/upload/<?php echo $classeName."/".$eKeyValue; ?>&_phpURL=http://<?php echo $_SERVER['HTTP_HOST']; ?>/backoffice/cms/utils/flvprovider.php&";	
									AC_FL_RunContent( 'codebase','https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0','width','<?php echo $flvW; ?>','height','<?php echo $flvH; ?>','src',swfSrc,'quality','high','pluginspage','https://get.adobe.com/flashplayer/','movie',swfSrc, 'scale', 'default', 'wmode', 'transparent');
									</script>									
								<?php
								}					
								else if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
									foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
									$itemLbl=$childNode["attrs"]["ITEMLIBELLE"];
									}
								$countoption=count($itemLbl);
									foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
										if ($childNode["name"] == "OPTION")  { // on a un node d'option
											if (($countoption==1) && isset($childNode["attrs"]["ITEMLIBELLE"]) && ($childNode["attrs"]["TYPE"]=="link")) {	// on a un node d'option link avec un ITEMLIBELLE
											$tempItemLibelle = getItemByName($aNodeToSort, $childNode["attrs"]["ITEMLIBELLE"]);
											if ($tempItemLibelle != false){
												$tempItemLibelleKeyValue = getItemValue($oRes, $tempItemLibelle["attrs"]["NAME"]);
												if (isset($tempItemLibelleKeyValue) && ($tempItemLibelleKeyValue != "")){
													$libelle = $tempItemLibelleKeyValue;
												}
												elseif (isset($tempItemLibelle["attrs"]["LIBELLE"]) && ($tempItemLibelle["attrs"]["LIBELLE"] != "")){
													$libelle = $tempItemLibelle["attrs"]["LIBELLE"];
												}
												else{
													$libelle = $tempItemLibelle["attrs"]["NAME"];
												}											
											}	
											else{
												if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
													$libelle = $aNodeToSort[$i]["attrs"]["LIBELLE"];
												}
												else{
													$libelle = $aNodeToSort[$i]["attrs"]["NAME"];
												}
											}			
											

											}
											else if ($countoption!=1){
												if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
													$libelle = $aNodeToSort[$i]["attrs"]["LIBELLE"];
												}
												else{
													$libelle = $aNodeToSort[$i]["attrs"]["NAME"];
												}

											}
											//test sur Type
											$tempLink = "/custom/upload/".$classeName."/".$eKeyValue;
											$tempExt= strtolower(strrchr(basename($tempLink), "."));
											if ($tempExt == ".pdf") {
												$tempFile = basename($tempLink);
												$tempChemin = str_replace($tempFile, "", $tempLink);
												$tempLink = "/modules/utils/telecharger.php?chemin=".$tempChemin."&file=".$tempFile."&";	
												$temptarget = "_self";								
											}	
											else{
												$temptarget = "_blank";
												$tempLink = "/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName;
											}										
										}										
									}
									echo "<a href=\"".$tempLink."\" target=\"".$temptarget."\" title=\"".$libelle."\">".$eKeyValue."</a>\n";
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
								echo "<a href=\"".$href."\" target=\"_blank\" title=\"Lien édité\">".$libelle."</a><br />\n";	
							}	//if ($eKeyValue != ""){		
						}
						else{// cas typique typique typique
							echo $eKeyValue;
						}
					}
					else{
						echo "n/a";
					}				
				}			
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value{\n";
				$tempStyles .= "}\n";
				
				echo "</div>\n";
				echo "</div>\n";
				
			} // ifdisplay
		} // cas pas statut|ordre|id	
		
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
		if ($aNodeToSort[$i]["attrs"]["ASSO"]){ // cas d'asso
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);			
					
			$sTempClasse = $aNodeToSort[$i]["attrs"]["ASSO"];
	
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
			
			if ($tempAssoOut != ""){
				// debut affichage asso sur table d'asso ----------------------
				echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."\" id=\"".replaceBadCarsInStr($tempAssoOut)."\">\n";
				echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."Label\" id=\"".replaceBadCarsInStr($tempAssoOut)."Label\">\n";
				echo $tempAssoLibelle;
				//echo $tempAssoOut;	
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
				$foreignOption = $stack[0]["attrs"]["OPTION"];
				
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

							if (($foreignOption == "") || ($foreignOption == NULL))	{				
								echo "	<div class=\"".replaceBadCarsInStr($tempAssoOut)."ValueDisplay\" id=\"".replaceBadCarsInStr($tempAssoOut)."ValueDisplay\">\n";							
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
								echo "	</div>\n";
								
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
							} //if (($foreignOption == "") || ($foreignOption == NULL))	{	
							else{
								echo "<div class=\"".replaceBadCarsInStr($tempAssoOut)."ValueDisplay\" id=\"".replaceBadCarsInStr($tempAssoOut)."ValueDisplay\">\n";								
								if ($tempIsDisplayForeign){
									eval('$eForeignId=$oForeign->get_'.strval($oTemp->getDisplay()).'();');
$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
									eval ("$"."tempStrDisplay = $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
								}
								else{
									eval ("$"."tempStrDisplay = substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
								}									
								
								if ($tempIsAbstractForeign){
									eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
									eval ("$"."tempStrAbstract = $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
								}
								else{
									eval ("$"."tempStrAbstract = $"."oForeign->get_".strval($oTemp->getAbstract())."();");
								}
								// test sur lien abstract
								if  (!$tempIsAbstractForeign && ($oTemp->getAbstract() == "id")){								
									// controle cas d'item d'asso ayant lui meme des assos
									$idForeignNode = getItemByName($foreignNodeToSort, "id");
									if (isset($idForeignNode["attrs"]["ASSO"]) && ($idForeignNode["attrs"]["ASSO"] != "")){
										//echo "cas d'item d'asso ayant lui meme des assos";
										//echo "chercher les ".$idForeignNode["attrs"]["ASSO"]." poitant sur la valeur ".$oTemp->getClasse()." id = ".$tempStrAbstract;
										$oTempForeignAssoAsso = new $idForeignNode["attrs"]["ASSO"]();
										$oTempForeignAssoAssoPrefixe = preg_replace("/([^_]+)_.*/msi", "$1", $oTempForeignAssoAsso->getFieldPK());
										$sRequete = "SELECT * FROM ".$idForeignNode["attrs"]["ASSO"]." WHERE ".$oTempForeignAssoAssoPrefixe."_".$oTemp->getClasse()." = ".$tempStrAbstract;
										// test cond statut
										if ($oTempForeignAssoAsso->getGetterStatut() != "none"){
											$sRequete .= " AND ".$oTempForeignAssoAssoPrefixe."_statut = ".DEF_ID_STATUT_LIGNE.";";										
										}										
										$aTempForeignAssoAsso = dbGetObjectsFromRequete($idForeignNode["attrs"]["ASSO"], $sRequete);
										if (count($aTempForeignAssoAsso) == 0){
											//echo " pas de assos assos, donc pas de lien";
											$tempStrAbstract = "";
										}
										else{
											if (is_file($_SERVER['DOCUMENT_ROOT']."/frontoffice/".$oTemp->getClasse()."/index.php")){
												$tempStrAbstract = "/frontoffice/".$oTemp->getClasse()."/index.php?id=".$tempStrAbstract."&";
											}
											elseif (is_file($_SERVER['DOCUMENT_ROOT']."/frontoffice/".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php")){
												$tempStrAbstract = "/frontoffice/".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php?id=".$tempStrAbstract."&";
											}	
										}
									}																
								}
								else{
									$tempStrAbstract = controlLinkValue($tempStrAbstract, $oTemp);								
								}
								//test sur Type
								$tempExt= strtolower(strrchr(basename($tempStrAbstract), "."));
								if ($tempExt == ".pdf") {
									$tempFile = basename($tempStrAbstract);
									$tempChemin = str_replace($tempFile, "", $tempStrAbstract);
									$tempStrAbstract = "/modules/utils/telecharger.php?chemin=".$tempChemin."&file=".$tempFile."&";
								
								}
								
								if ($tempStrAbstract != ""){
									echo "<a href=\"".$tempStrAbstract."\" title=\"".$tempStrDisplay."\">".$tempStrDisplay."</a>";
								}
								else{
									echo $tempStrDisplay;
								}									
								echo "</div>\n"; // div display	
								echo "</div>\n";
							}	 // fin $foreignOption == "link"					
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
					echo $tempAsso;
					echo "</div>\n";
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
					$foreignOption = $stack[0]["attrs"]["OPTION"];	
					
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
					$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."Value{\n";
					$tempStyles .= "}\n";
					$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."ValueDisplay{\n";
					$tempStyles .= "}\n";
					$tempStyles .= ".".replaceBadCarsInStr($tempAsso)."ValueAbstract{\n";
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
							if (getCount_where($tempAsso, array($tempAssoPrefixe."_".$tempAssoIn, $tempAssoPrefixe."_id"), array($id,$tempId), array("NUMBER","NUMBER")) ==  1){
								echo "<div class=\"".replaceBadCarsInStr($tempAsso)."Value\" id=\"".replaceBadCarsInStr($tempAsso)."Value\">\n";

								if (($foreignOption == "") || ($foreignOption == NULL))	{						
									echo "<div class=\"".replaceBadCarsInStr($tempAsso)."ValueDisplay\" id=\"".replaceBadCarsInStr($tempAsso)."ValueDisplay\">\n";								
									if ($tempIsDisplayForeign){
										eval('$eForeignId=$oForeign->get_'.strval($oTemp->getDisplay()).'();');
$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
										eval ("echo $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
									}
									else{
										eval ("echo substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
									}
									echo "</div>\n"; // div display
									
									if ($oTemp->getDisplay() != $oTemp->getAbstract()){
										echo "<div class=\"".replaceBadCarsInStr($tempAsso)."ValueAbstract\" id=\"".replaceBadCarsInStr($tempAsso)."ValueAbstract\">\n";									
										if ($tempIsAbstractForeign){
											eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
											eval ("echo $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
										}
										else{
											eval ("echo $"."oForeign->get_".strval($oTemp->getAbstract())."();");
										}
										echo "</div>\n"; // div abstract
									}	
								}	// fin if (($foreignOption == "") || ($foreignOption == NULL))	{		
								elseif ($foreignOption == "link"){
									echo "<div class=\"".replaceBadCarsInStr($tempAsso)."ValueDisplay\" id=\"".replaceBadCarsInStr($tempAsso)."ValueDisplay\">\n";								
									if ($tempIsDisplayForeign){
										eval('$eForeignId=$oForeign->get_'.strval($oTemp->getDisplay()).'();');
$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
										eval ("$"."tempStrDisplay = $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
									}
									else{
										eval ("$"."tempStrDisplay = substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
									}									
									
									if ($tempIsAbstractForeign){
										eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
										eval ("$"."tempStrAbstract = $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
									}
									else{
										eval ("$"."tempStrAbstract = $"."oForeign->get_".strval($oTemp->getAbstract())."();");
									}
									// test sur lien abstract
									if  (!$tempIsAbstractForeign && ($oTemp->getAbstract() == "id")){
										if (is_file($_SERVER['DOCUMENT_ROOT']."/frontoffice/".$oTemp->getClasse()."/index.php")){
											$tempStrAbstract = "/frontoffice/".$oTemp->getClasse()."/index.php?id=".$tempStrAbstract."&";
										}
										elseif (is_file($_SERVER['DOCUMENT_ROOT']."/frontoffice/".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php")){
											$tempStrAbstract = "/frontoffice/".$oTemp->getClasse()."/foshow_".$oTemp->getClasse().".php?id=".$tempStrAbstract."&";
										}								
									}
									if(preg_match("/http:\/\//msi", $tempStrAbstract)){
										// nada, c'est une irl
										$tempStrAbstract = trim($tempStrAbstract);
									}
									elseif (is_file($tempStrAbstract)){
										// nada, fichier en relatif
									}
									elseif (is_file($_SERVER['DOCUMENT_ROOT'].$tempStrAbstract)){
										// nada, fichier en absolu
									}
									elseif (is_file($_SERVER['DOCUMENT_ROOT']."/frontoffice/".$oTemp->getClasse()."/".$tempStrAbstract)){
										//  fichier en fo dossier de la classe
										$tempStrAbstract = "/frontoffice/".$oTemp->getClasse()."/".$tempStrAbstract;
									}
									elseif (is_file($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$oTemp->getClasse()."/".$tempStrAbstract)){
										//  fichier en custom upload dossier de la classe
										$tempStrAbstract = "/custom/upload/".$oTemp->getClasse()."/".$tempStrAbstract;
									}
									else{
										$tempStrAbstract = "";
									}
									//test sur Type
									$tempExt= strtolower(strrchr(basename($tempStrAbstract), "."));
									if ($tempExt == ".pdf") {
										$tempFile = basename($tempStrAbstract);
										$tempChemin = str_replace($tempFile, "", $tempStrAbstract);
										$tempStrAbstract = "/modules/utils/telecharger.php?chemin=".$tempChemin."&file=".$tempFile."&";
									
									}
									
									if ($tempStrAbstract != ""){
										echo "<a href=\"".$tempStrAbstract."\" title=\"".$tempStrDisplay."\">".$tempStrDisplay."</a>";
									}
									else{
										echo $tempStrDisplay;
									}									
									echo "</div>\n"; // div display	
									echo "</div>\n";
								}
							}						
						}						
					}
					echo "</div>\n";
					echo "</div>\n";
				} // fin if ($tempAsso != ""){ // check les records pointant vers la table sont plus que ZERO
				// fin affichage asso SANS table d'asso ----------------------		
			}
		}
	}
}

//-------------------------
?>
<div class="fermer" id="fermer"><a href="javascript:window.close()">Fermer</a></div>
<?php
echo "</div>\n";
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