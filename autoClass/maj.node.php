<?php

if (getCount_where("cms_arbo_pages", array("node_id"), array($eKeyValue), array("NUMBER")) ==  1){
	if (getNodeInfos($db, $eKeyValue)){
		$infosNode = getNodeInfos($db, $eKeyValue); 
		$eKeyValue_libelle = $infosNode["path"];
		
	}
	else {
		$eKeyValue_libelle = "n/a";
	}
}
else {
	$eKeyValue_libelle = "n/a";
}

$idSiteToBrowse = $_SESSION["idSite"];

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
echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle\" class=\"arbo\" size=\"80\" value=\"".$eKeyValue_libelle."\" disabled />\n";
echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"".$eKeyValue."\" ".$disabled." />\n";
echo "<input type=\"button\"  class=\"arbo\" value=\"parcourir l'arborescence\" onclick=\"javascript:openBrWindow('/backoffice/cms/popup_arbo_browse_node.php?idSite=".$idSiteToBrowse."&idField=f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."&idForm=add_".$classePrefixe."_form".$nodevalue."', '', 600, 400, 'scrollbars=yes', 'true')\" class=\"arbo\">";

echo "&nbsp;<input type=\"button\" class=\"arbo\" value=\"effacer\" onclick=\"javascript:resetField('f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle', 'f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."')\" class=\"arbo\">";
			
			
			
?>