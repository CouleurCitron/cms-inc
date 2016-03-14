<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// translation data fields
// Added by Luc - 6 oct. 2009
if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") {
	if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference') {
		if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
			foreach ($langpile as $lang_id => $lang_props) {
				if ($operation == 'UPDATE')
					$eTslValue = str_replace('"', '&quot;', $translator->getByID($eKeyValue, $lang_id));
				elseif ($operation == 'INSERT')
					$eTslValue = '';					
					
				echo $eTslValue.(sizeof($langpile) > 1 ? "&nbsp;".$lang_props['libellecourt'] : "").'<br />';
			}
		}
		else{
			echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
			foreach ($langpile as $lang_id => $lang_props) {
				
				$eTslValue = '';
			
				if ($operation == 'UPDATE')
					$eTslValue = str_replace('"', '&quot;', $translator->getByID($eKeyValue, $lang_id));
				elseif ($operation == 'INSERT')
					$eTslValue = '';
				 
				
				if ($aNodeToSort[$i]["attrs"]["OPTION"] == "node") { // cas node  
				 
					if ($eTslValue != '') {
						if (getCount_where("cms_arbo_pages", array("node_id"), array($eTslValue), array("NUMBER")) ==  1){
							if (getNodeInfos($db, $eTslValue)){
								$infosNode = getNodeInfos($db, $eTslValue); 
								$eKeyValue_libelle = $infosNode["path"];
								
							}
							else {
								$eKeyValue_libelle = "n/a";
							}
						}
						else {
							$eKeyValue_libelle = "n/a";
						}
					}
					else {
						$eKeyValue_libelle = "n/a";
					}
					 
					
					$aSite = siteByidLangue ($lang_id); 
					
					if ($aSite) {
						if (sizeof($aSite) > 1) {
							$idSiteToBrowse = $_SESSION["idSite"];	
						}
						else {
							$idSiteToBrowse = $aSite[0]->get_id();
						}
					}
					else {
						$idSiteToBrowse = $_SESSION["idSite"];	
					}
					 
					 
					
					if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "where"){
									$ActiveIf = true;
									$whereField = $childNode["attrs"]["ITEM"];
									$whereValue = $childNode["attrs"]["VALUE"];
									$idSiteToBrowse = $whereValue;
									break;
								} //fin type  == if			
							}
						}
					}
					 
					
					(isset($aNodeToSort[$i]["attrs"]["NODEVALUE"]) && $aNodeToSort[$i]["attrs"]["NODEVALUE"] != "") ? $nodevalue = "&v_comp_path=".$aNodeToSort[$i]["attrs"]["NODEVALUE"] : $nodevalue = ""; 
					echo "<input   type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle_".$lang_props['libellecourt']."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle_".$lang_props['libellecourt']."\" class=\"arbo ".$lang_props['libellecourt']."\" size=\"80\" value=\"".$eKeyValue_libelle."\" disabled />\n";
					echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$lang_props['libellecourt']."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$lang_props['libellecourt']."\" class=\"arbo\" size=\"80\" value=\"".$eTslValue."\" ".$disabled." />\n";
					
					//echo "<input type=\"button\"  class=\"arbo\" value=\"parcourir l'arborescence\" onclick=\"javascript:openBrWindow('/backoffice/cms/popup_arbo_browse_node.php?idSite=".$idSiteToBrowse."&idField=f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."&idForm=add_".$classePrefixe."_form".$nodevalue."&source=".$lang_props['libellecourt']."', '', 600, 400, 'scrollbars=yes', 'true')\" class=\"arbo\">";
					
					echo "<input type=\"button\"  class=\"arbo ".$lang_props['libellecourt']."\" value=\"parcourir l'arborescence\" onclick=\"javascript:openBrWindow('/backoffice/cms/popup_arbo_browse_node.php?idSite=".$idSiteToBrowse."&idField=f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."&idForm=add_".$classePrefixe."_form".$nodevalue."&source=".$lang_props['libellecourt']."', '', 600, 400, 'scrollbars=yes', 'true')\" class=\"arbo\">";
					
					
					echo "&nbsp;<input type=\"button\" class=\"arbo ".$lang_props['libellecourt']."\" value=\"effacer\" onclick=\"javascript:resetField('f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle_".$lang_props['libellecourt']."', 'f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."')\" class=\"arbo\">";
		
				} 
					
				elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "textarea")
					echo "<textarea id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$lang_props['libellecourt']."\" class=\"".$lang_props['libellecourt']."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$lang_props['libellecourt']."\" cols=\"60\" rows=\"4\" style=\"font-size:11px\">".$eTslValue."</textarea>";
				else	echo "<input type=\"text\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$lang_props['libellecourt']."\" class=\"".$lang_props['libellecourt']."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$lang_props['libellecourt']."\" value=\"".$eTslValue."\" size=\"69\"/>";
				
				if (sizeof($langpile) > 1)
					echo "<span class='".$lang_props['libellecourt']."'>&nbsp;".$lang_props['libellecourt']."</span>";
				// gestion popup wysiwyg
				if ((($aNodeToSort[$i]["attrs"]["OPTION"] == "link")	||	($aNodeToSort[$i]["attrs"]["OPTION"] == "url"))  && ($bPopupLinks == true) ){ // cas link						
					// non editable field 
					echo "&nbsp;<a class='".$lang_props['libellecourt']."' href=\"javascript:openLinkWindow('/backoffice/cms/utils/popup/dir.php', 'links', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$lang_props['libellecourt']."', 'add_".$classePrefixe."_form');\" title=\"Link picker\"><img src=\"/backoffice/cms/img/bt_popup_url.png\" id=\"wysiwyg\" style=\"cursor: pointer; border: 1px solid red;\" alt=\"Link picker\" onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" /></a>\n"; 
					
				}
				
				
				
		
				
				
				elseif ( ($bPopupWysiwyg == true) && (($aNodeToSort[$i]["attrs"]["NOHTML"] != "true")||(!isset($aNodeToSort[$i]["attrs"]["NOHTML"])))	&& $aNodeToSort[$i]["attrs"]["OPTION"] != "node" ){ // cas wysiwyg
					echo " <a class='".$lang_props['libellecourt']."' href=\"javascript:openWYSYWYGWindow('//".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$lang_props['libellecourt']."', 'add_".$classePrefixe."_form');\" title=\"HTML editor\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer; border: 1px solid red;\" alt=\"HTML editor\" onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" /></a>\n";
				} // wysiwyg
				
				else {
					echo "<br />"; 
				}
				
				
				
				
				
				// required field
				if ($aNodeToSort[$i]["attrs"]["OBLIG"] == "true" && $lang_id == DEF_APP_LANGUE)
					echo "&nbsp;*<br />\n";
					
			}// if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
		}
	} elseif ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'value') {
		echo $aNodeToSort[$i]["attrs"]["LENGTH"];
	}
} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") {
	if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'value') {
		$tsl_values = explode(',', $aNodeToSort[$i]["attrs"]["LENGTH"]);
		if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
			foreach ($tsl_values as $tsl_val) {
				$tsl_val = substr($tsl_val, 1,-1);
				if ($eKeyValue == $tsl_val){
					echo $translator->getText($tsl_val, $_SESSION['id_langue']);
					break;
				}
			}
		}
		else{
			echo "<select id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\">\n";
			foreach ($tsl_values as $tsl_val) {
				$tsl_val = substr($tsl_val, 1,-1);
				echo "<option value=\"".$tsl_val."\"".($eKeyValue == $tsl_val ? " selected=\"true\"" : "").">".$translator->getText($tsl_val, $_SESSION['id_langue'])."</option>\n";
			}
			echo "</select>\n";
		}//if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
		
	} else	echo "Error - <b><i>".$aNodeToSort[$i]["attrs"]["TRANSLATE"]."</i></b> 'translate' attribute value is not valid for translation engine applied to <b><i>ENUM</i></b> type fields !!";

} else	echo "Error - Translation engine can not be applied to <b><i>".$aNodeToSort[$i]["attrs"]["TYPE"]."</i></b> type fields !!";

?>
