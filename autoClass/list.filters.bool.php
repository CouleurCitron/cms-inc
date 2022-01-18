<?php
$boolNodes = getItemsByOption($aNodeToSort, "bool");
	 
if ($boolNodes != false){
	foreach ($boolNodes as $nkey => $node){
		if (isset($node["attrs"]["SKIP"])	&&	($node["attrs"]["SKIP"]=='true')){
			// skip
		}
		else{
	 
			echo "<div id=\"".$node["attrs"]["NAME"]."Filter\" class=\"".$node["attrs"]["NAME"]."Filter blocItem\">\n";
				echo "<div id=\"".$node["attrs"]["NAME"]."FilterLabel\" class=\"".$node["attrs"]["NAME"]."FilterLabel\">";
					if (isset($node["attrs"]["LIBELLE"]) && ($node["attrs"]["LIBELLE"] != "")){
						echo strtolower(stripslashes($node["attrs"]["LIBELLE"]));
					}
					else{
						echo strtolower(stripslashes($node["attrs"]["NAME"]));
					}
			
				echo " </div>\n";
				echo "<div id=\"".$node["attrs"]["NAME"]."FilterField\" class=\"".$node["attrs"]["NAME"]."FilterField\">\n";
			
					echo "<select id=\"filter".ucfirst($classePrefixe)."_".$node["attrs"]["NAME"]."\" name=\"filter".ucfirst($classePrefixe)."_".$node["attrs"]["NAME"]."\" class=\"arbo\"  onChange=\"filterChange()\">\n";
						$eKeyValue = $_POST["filter".ucfirst($classePrefixe)."_".$node["attrs"]["NAME"]]; 
						
						
						  
						if ($translator->getText('oui') == '') $translator->addTranslation ('oui', array("1" => "oui", "2" => "yes"));
						if ($translator->getText('non') == '') $translator->addTranslation ('non', array("1" => "non", "2" => "no"));
						
						if (!isset( $_POST["filter".ucfirst($classePrefixe)."_".$node["attrs"]["NAME"]]) || $eKeyValue == -1 )   {
							$selected_all = "selected" ; 
							$selected_no = "";
							$selected_yes = "";
							
						}
						else if ($eKeyValue == 0 )  {
							$selected_no = "selected"  ; 
							$selected_yes = "";
							$selected_all = "";
						}
						else  if ($eKeyValue == 1 ) {
							$selected_yes = "selected"  ; 
							$selected_no = "";
							$selected_all = "";
						}
						echo "<option value=\"-1\" ".$selected_all.">-- ".$translator->getTransByCode('tous')." --</option>\n";
						echo "<option value=\"0\" ".$selected_no.">".$translator->getText('non')."</option>\n";
						echo "<option value=\"1\" ".$selected_yes.">".$translator->getText('oui')."</option>\n";
						 
					echo "</select>\n";		
			
				echo "</div>\n";
			echo "</div>\n"; 
		}
	}
}   
?>