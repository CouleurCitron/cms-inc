<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

//visu link
	if (!isset($translator)) $translator =& TslManager::getInstance(); 
 

	 // réinitialisation de la page  	 	
	if (preg_match('/backoffice/', $_SERVER['PHP_SELF'])==1) { 
		echo "<div class='bloc_bt_search'>";
                echo "<input type=\"button\" name=\"btChercher\" id=\"btChercher\" onclick=\"rechercher();\" value=\"".$translator->getTransByCode('Chercher')."\" class=\"arbo bt_search\" />\n";
		echo "<input type='button' name='btreinit' id='btreinit' onclick='reinit();' value='".$translator->getTransByCode('Reinitiliser')."' class='arbo bt_search' />\n"; 	 
		
		echo "</div>"; // template
		echo ""; // template
	}	 
	
?>