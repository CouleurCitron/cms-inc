<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));	
}
//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

////////////////////////////////////////////////////////

// Fiche visu

include_once('php/cms-inc/include_cms.php');
include_once('php/cms-inc/include_class.php');


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

$echoStr = "";

$tempStyles = ".".replaceBadCarsInStr($classeName)."{\n";
$tempStyles .= "}\n";

$echoStr .=  "<div class=\"".replaceBadCarsInStr($classeName)."\" id=\"".replaceBadCarsInStr($classeName)."\">\n";

$tempGroup = "nogroup";

for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){	

		// - test group debut ---------------------------------------
		if(nouveauGroup($aNodeToSort[$i], $tempGroup) != false){
			//$echoStr .=  " # new group ";
			$tempGroup = nouveauGroup($aNodeToSort[$i], $tempGroup);
			$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."{\n";
			$tempStyles .= "}\n";
			$echoStr .=  "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."\">\n";
		}
		
			
		if (!preg_match("/statut|ordre|id/msi", $aNodeToSort[$i]["attrs"]["NAME"])){ // cas pas statut|ordre|id	
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
			if (critereIfdisplay($aNodeToSort[$i], $oRes, $eKeyValue) == true){	// displayif
				$echoStr .=  "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."\">\n";					
				$echoStr .=  "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label\">\n";			
				if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
					$echoStr .=  stripslashes($aNodeToSort[$i]["attrs"]["LIBELLE"]);		
				}
				else{
					$echoStr .=  stripslashes($aNodeToSort[$i]["attrs"]["NAME"]);		
				}			
				$echoStr .=  "</div>\n";
				$echoStr .=  "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value\">\n";
				
				
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
							$echoStr .=  "<a href=\"".$tempViewerPage."?id=".$oTemp->get_id()."\">";
							$echoStr .=  getItemValue($oTemp, $oTemp->getDisplay());
							$echoStr .=  "</a>";
						}
						else{
							$echoStr .=  getItemValue($oTemp, $oTemp->getDisplay());
						}
					}
					else{
						$echoStr .=  "n/a";
					}
				}// fin fkey
				elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum		
					if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "value"){
									if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
										$echoStr .=  $childNode["attrs"]["LIBELLE"];
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
										$echoStr .=  "<img src=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" border=\"0\" height=\"".$heightNew."\"  width=\"".$widthNew."\" alt=\"".$eKeyValue."\" />";
									}
									else {
										$echoStr .=  "<img src=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" border=\"0\" alt=\"".$eKeyValue."\" />";
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
									swfSrc = "/backoffice/cms/utils/scrubber"+"?_vidName=<?php $echoStr .=  $eKeyValue?>&_vidURL=/custom/upload/<?php $echoStr .=  $classeName."/".$eKeyValue?>&_phpURL=http://<?php $echoStr .=  $_SERVER['HTTP_HOST']?>/backoffice/cms/utils/flvprovider.php&";	
									AC_FL_RunContent( 'codebase','https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0','width','<?php $echoStr .=  $flvW?>','height','<?php $echoStr .=  $flvH?>','src',swfSrc,'quality','high','pluginspage','https://get.adobe.com/flashplayer/','movie',swfSrc, 'scale', 'default', 'wmode', 'transparent');
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
											
											//$echoStr .=  "<a href=\"/backoffice/cms/utils/viewer.php?file=custom/upload/".$classeName."/".$eKeyValue."\" target=\"_blank\" title=\"".$libelle."\">".$libelle."</a><br />\n";
											}
											else if ($countoption!=1){
												if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
													$libelle = $aNodeToSort[$i]["attrs"]["LIBELLE"];
												}
												else{
													$libelle = $aNodeToSort[$i]["attrs"]["NAME"];
												}
											//$echoStr .=  "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" target=\"_blank\" title=\"".$libelle."\">".$eKeyValue."</a>\n";
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
									$echoStr .=  "<a href=\"".$tempLink."\" target=\"".$temptarget."\" title=\"".$libelle."\">".$eKeyValue."</a>\n";
								}
							} // if (is_file(
						}
						else if ($aNodeToSort[$i]["attrs"]["OPTION"] == "bool"){ // boolean
							if (intval($eKeyValue) == 1){
								$echoStr .=  "oui";
							}
							else{
								$echoStr .=  "non";
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
									$echoStr .=  $jj."/";
								}
								$echoStr .=  $mm."/";
							}
							$echoStr .=  $aaaa;
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
								$echoStr .=  "<a href=\"".$href."\" target=\"_blank\" title=\"Lien édité\">".$libelle."</a><br />\n";	
							}	//if ($eKeyValue != ""){		
						}
						else{// cas typique typique typique
							$echoStr .=  $eKeyValue;
						}
					}
					else{
						$echoStr .=  "n/a";
					}				
				}			
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Label{\n";
				$tempStyles .= "}\n";
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value{\n";
				$tempStyles .= "}\n";
				
				$echoStr .=  "</div>\n";
				$echoStr .=  "</div>\n";
				
			} // ifdisplay
		} // cas pas statut|ordre|id	
		
		// test fin de groupe
		if (finGroup($aNodeToSort[$i+1], $tempGroup) == true){
			$tempGroup = "nogroup";
			//$echoStr .=  " # fin de group #";
			$echoStr .=  "</div>\n";
		}
	} // item 
} // for 
//-------------------------

//-------------------------

$echoStr .=  "</div>\n";
$echoStr .=  "<!-- styles -- sample --\n";
$echoStr .=  "<style type=\"text/css\">\n";
$echoStr .=  $tempStyles;
$echoStr .=  ".fermer{\n";
$echoStr .=  "}\n";
$echoStr .=  "</style>\n";
$echoStr .=  "-- styles -- sample -->\n";
} else {
	die("Erreur ".$classeName." non trouvé");
}
?>