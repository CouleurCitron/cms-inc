<?php
$enumNodes = getItemsByOption($aNodeToSort, "enum");

if ($enumNodes != false){
	foreach ($enumNodes as $nkey => $node){	
		if (isset($node["attrs"]["SKIP"])	&&	($node["attrs"]["SKIP"]=='true')){
			// skip
		}
		else{
			if (preg_match("/backoffice/", $_SERVER['PHP_SELF'])==1) echo ""; // template
			echo "<div id=\"".$node["attrs"]["NAME"]."Filter\" class=\"".$node["attrs"]["NAME"]."Filter blocItem\">\n";
				echo "<div id=\"".$node["attrs"]["NAME"]."FilterLabel\" class=\"".$node["attrs"]["NAME"]."FilterLabel\">";
					if (isset($node["attrs"]["LIBELLE"]) && $node["attrs"]["LIBELLE"] != "")
						echo strtolower($translator->getText(stripslashes($node["attrs"]["LIBELLE"])));
					else	echo strtolower(stripslashes($node["attrs"]["NAME"]));
				echo " </div>\n";
				echo "<div id=\"".$node["attrs"]["NAME"]."FilterField\" class=\"".$node["attrs"]["NAME"]."FilterField\">\n";
			
					echo "<select id=\"filter".ucfirst($classePrefixe)."_".$node["attrs"]["NAME"]."\" name=\"filter".ucfirst($classePrefixe)."_".$node["attrs"]["NAME"]."\" class=\"arbo\"  onChange=\"filterChange()\">\n";
						echo "<option value=\"-1\">-- ".$translator->getTransByCode('tous')." --</option>\n";
						
						if (isset($node["children"]) && (count($node["children"]) > 0)){
							foreach ($node["children"] as $childKey => $childNode){
								if($childNode["name"] == "OPTION"){ // on a un node d'option				
									if ($childNode["attrs"]["TYPE"] == "value"){
										$eKeyValue = $_POST["filter".ucfirst($classePrefixe)."_".$node["attrs"]["NAME"]];
										
										if ($eKeyValue == ""){
											$eKeyValue = -1;
										} 
										if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"]) || (isset ($_SESSION[$classePrefixe."_".$node["attrs"]["NAME"]]) && intval($_SESSION[$classePrefixe."_".$node["attrs"]["NAME"]]) == intval($childNode["attrs"]["VALUE"]))){							
											$enumSelected = "selected";
										}
										else {
											$enumSelected = "";
										}
										echo "<option value=\"".$childNode["attrs"]["VALUE"]."\" ".$enumSelected.">".$translator->getText(stripslashes($childNode["attrs"]["LIBELLE"]), $_SESSION['id_langue'])."</option>\n";
									} //fin type  == value				
								}
							}
						}
					echo "</select>\n";		
			
				echo "</div>\n";
			echo "</div>\n";
			if (preg_match("/backoffice/", $_SERVER['PHP_SELF'])==1) echo ""; // template
		}//skip
	}
}  
?>