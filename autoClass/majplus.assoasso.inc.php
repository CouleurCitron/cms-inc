<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// ------------------------------------------------- ASSO TYPE ASSO --------------------------------------------------- //

// recherche d'eventuelles asso un ou plusieurs
$assoArray = array();

$idNode = getItemByName($aNodeToSort, "id");
if (isset($idNode["children"]) && (count($idNode["children"]) > 0)){
	foreach ($idNode["children"] as $childKey => $childNode){
		if($childNode["name"] == "OPTION"){ // on a un node d'option				
			if (isset ($childNode["attrs"]["ASSO"]) && $childNode["attrs"]["TYPE"]=="asso" ){
				$assoArray[] = $childNode["attrs"]["ASSO"];
			} 			
		}
	}
}

// vérifie nb asso
if (newSizeOf($assoArray) > 0) {
	echo "<!-- debut des champs d'association -->\n";
	echo "<tr>\n";
	echo "<td width=\"141\" align=\"right\" bgcolor=\"#E6E6E6\" class=\"arbo\">";
	echo "<br /><b>Associations</b>";
	echo "</td>\n";
	echo "<td width=\"535\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"arbo\">&nbsp;</td></tr>";
}


for ($i=0;$i<count($assoArray);$i++){

	//$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);			
	$sTempClasse = $assoArray[$i];
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
			if (isset($nodeValue["attrs"]["NAME"]) && !preg_match("/statut|ordre|id/msi", $nodeValue["attrs"]["NAME"])){ 
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
					}
				} 
				else {
					$tempAssoOut = $foreignName;
				}
			}
			
		}
		
	}
	

	//eval ("$"."arrayAddCheck_".$tempAssoOut." = \"\";");
	echo "<input type=\"hidden\" name=\"arrayAddCheck_".$tempAssoOut."\" id=\"arrayAddCheck_".$tempAssoOut."\" value=\"\">";
	if ($tempAssoOut != ""){
		// debut traitement asso par table asso

		echo "<tr>\n";
		echo "<td width=\"141\" align=\"right\" bgcolor=\"#E6E6E6\" class=\"arbo\">";
		echo "&nbsp;<u><b>".$tempAssoOut."</b></u>&nbsp;*";
		echo "</td>\n";
		echo "<td width=\"535\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"arbo\">\n";
		
		// --------------------------------------------------------------------------------------------------

		echo "<div id=\"addAssoCheckbox_".$tempAssoOut."\" name=\"addAssoCheckbox_".$tempAssoOut."\">";
		
		$sTempClasse = $tempAssoOut;
		
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
		$bCms_site = false;
		
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
				if (strtolower(stripslashes($nodeValue["attrs"]["NAME"])) == "cms_site"){
					$bCms_site = true;
				}
			}
		}
		
?>
<script type="text/javascript">
function deleteId (id, classe)
	{
		var url1;
		url1 = 'http://<?php echo $_SERVER['HTTP_HOST']; ?>/lib/autoClass/maj_select.php';
		var xhr_object = null; 
		if(window.XMLHttpRequest) xhr_object = new XMLHttpRequest(); 
		else if(window.ActiveXObject) xhr_object = new ActiveXObject("Microsoft.XMLHTTP"); 
		else { 
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
			return; 
		}
		xhr_object.open("POST", url1, true); 
		
		xhr_object.onreadystatechange = function() { 
			if(xhr_object.readyState == 4) {
				//alert(xhr_object.responseText);
				eval(xhr_object.responseText);
				//parent.document.getElementById('addAssoCheckbox_sstexte').innerHTML = xhr_object.responseText;
			} 
		} 
	 
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
		var data = "classeName="+classe+"&classeMain=<?php echo $classeName; ?>&form=add_<?php echo $classePrefixe; ?>_form&select=addAssoCheckbox_"+classe+"&action=del&id="+id;

		xhr_object.send(data);
	}
</script>
<?php
		
		
		viewArray($aForeign, 'foreign');
		for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){
			$oForeign = $aForeign[$iForeign];
			if ($oTemp->getGetterStatut() != "none"){
				eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");					
			}
			else{
				$tempStatus = DEF_ID_STATUT_LIGNE;
			}
			eval ("$"."tempId = $"."oForeign->get_id();");
			
			if (getCount_where($assoArray[$i], array($tempAssoPrefixe."_".$tempAssoIn, $tempAssoPrefixe."_".$tempAssoOut), array($id,$tempId), array("NUMBER", "NUMBER")) == 1) {
				$checked= "checked";
			}
			else {
				$checked= "";
			}
			
			if ($tempStatus == DEF_ID_STATUT_LIGNE){
				if ($bCms_site == true && $classeName != "classe" ) {
					$temp_cms_site = $oForeign->get_cms_site();
					if ((isset($_SESSION['idSite_travail']) && $_SESSION['idSite_travail']!= "" &&  preg_match("/backoffice/msi", $_SERVER['PHP_SELF']) && $temp_cms_site == $_SESSION['idSite_travail']) || ($temp_cms_site == $idSite)) {
						// test sur select. chercher id de la fiche en cours ($id) et id du foreign en cours ($tempId) dans Asso.
				echo "<div name=\"fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId."_div\" id=\"fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId."_div\" >";
				echo "<input type=\"checkbox\" name=\"fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId."\" id=\"fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId."\" value=\"".$tempId."\" ".$checked." ";
				
				echo ">";
				
			
				if ($tempIsDisplayForeign){
					eval('$eForeignId=$oForeign->get_'.strval($oTemp->getDisplay()).'();');
$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
					eval ("echo $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
				}
				else{
					eval ("echo substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
				}
				
				if ($oTemp->getDisplay() != $oTemp->getAbstract()){
					echo " - ";
					if ($tempIsAbstractForeign){
						eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
						eval ("echo $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
					}
					else{
						eval ("echo $"."oForeign->get_".strval($oTemp->getAbstract())."();");
					}
				}
				//echo "&nbsp;&nbsp<a href=\"javascript:deleteId(".$tempId.", '".$foreignName."')\" title=\"Supprimer\">x</a>";
				echo "</div>";
				echo "\n";
					}
				} 
				else {
					// test sur select. chercher id de la fiche en cours ($id) et id du foreign en cours ($tempId) dans Asso.
				echo "<div name=\"fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId."_div\" id=\"fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId."_div\" >";
				echo "<input type=\"checkbox\" name=\"fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId."\" id=\"fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId."\" value=\"".$tempId."\" ".$checked." ";
				
				echo ">";
				
			
				if ($tempIsDisplayForeign){
					eval('$eForeignId=$oForeign->get_'.strval($oTemp->getDisplay()).'();');
$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
					eval ("echo $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
				}
				else{
					eval ("echo substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
				}
				
				if ($oTemp->getDisplay() != $oTemp->getAbstract()){
					echo " - ";
					if ($tempIsAbstractForeign){
						eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
						eval ("echo $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
					}
					else{
						eval ("echo $"."oForeign->get_".strval($oTemp->getAbstract())."();");
					}
				}
				//echo "&nbsp;&nbsp<a href=\"javascript:deleteId(".$tempId.", '".$foreignName."')\" title=\"Supprimer\">x</a>";
				echo "</div>";
				echo "\n";
				}
			}						
		}	
		// fin traitement par table asso ----------	
		echo "</div>\n";
		// --------------------------------------------------------------------------------------------------
	}// fin liste checkbox
	

				
?>
<script type="text/javascript" language="javascript">

function haut(obj){
	  var h;
	  // calcul de la hauteur du contenu suivant le navigateur:
	  if (document.documentElement.clientHeight) {
	  
		if (navigator.appName=="Microsoft Internet Explorer") {
		  h = document.getElementById(obj).document.documentElement.clientHeight;
		} else {
		  h = document.getElementById(obj).contentDocument.documentElement.clientHeight;
		}
		
	  }
	  if (typeof(window.innerHeight)=='number') 
		h = document.getElementById(obj).contentDocument.body.innerHeight;
		
	  if (document.body.clientHeight && navigator.appName!="Microsoft Internet Explorer") 
		h = document.getElementById(obj).contentDocument.body.clientHeight;
		
	  // Ajustement de la hauteur de frame:
	  alert(h);
	  document.getElementById(obj).style.height = h+ "px";
	}


	function disparition(classe, id){
		
		var classeName = 'addAsso_'+classe;
		var tableauAdd = 'tableauAdd_'+classe;
		var tableauSupp = 'tableauSupp_'+classe;
		//alert(classeName);
		if(id == 0){
			document.getElementById(classeName).style.display = 'none';
			document.getElementById(tableauAdd).style.display = 'block';
			document.getElementById(tableauSupp).style.display = 'none';
		}
		else if (id == 1){
			document.getElementById(classeName).style.display = 'block';
			document.getElementById(tableauAdd).style.display = 'none';
			document.getElementById(tableauSupp).style.display = 'block';
		}
	}
</script>
 
<?php
echo "<table border=\"0\" id=\"tableauAdd_".$sTempClasse."\" class=\"arbo\"><tr><td><img src=\"/backoffice/cms/img/closed-menu.gif\" onclick=\"javascript:disparition('".$sTempClasse."', 1);\" /><a href=\"#\" onclick=\"javascript:disparition('".$sTempClasse."', 1);\"/>Ajouter </a></td></tr></table>";
echo "<table border=\"0\" id=\"tableauSupp_".$sTempClasse."\" class=\"arbo\"><tr><td><img src=\"/backoffice/cms/img/closed-menu.gif\" onclick=\"javascript:disparition('".$sTempClasse."', 0);\" /><a href=\"#\" onclick=\"javascript:disparition('".$sTempClasse."', 0);\"/>Fermer</a></td></tr></table>";
echo "<table id=\"addAsso_".$sTempClasse."\" border=\"0\" cellspacing=\"0\" cellpading=\"0\"><tr><td>";
echo "<iframe name=\"newsIframe\" title=\"newsIframe\" id=\"newsIframe\" width=\"600\" height=\"200px\" bgcolor=\"#EEEEEE\"  frameborder=\"0\" src=\"/include/cms-inc/autoClass/majmoins.php?classeName=".$sTempClasse."&classeMain=".$classeName."&classeMainPrefixe=".$classePrefixe."&idMain=".$id."\" marginheight=\"0\" marginwidth=\"0\" scrolling=\"yes\" application=\"yes\" \>";
echo "</iframe>";
echo "</td></tr></table>";

echo "<script>disparition('".$sTempClasse."', 0);</script>";
}
?>
</td></tr>