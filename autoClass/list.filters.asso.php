<?php
$assoNodes = getItemsByAsso($aNodeToSort, $oRes);
		
if ($assoNodes != false){
	$sel = '';
	$aOps=array();
	
	foreach ($assoNodes as $classeAsso => $sTempClasse){ 
	
		eval("$"."oTemp = new ".$sTempClasse."();");
		
		
		
		//$aForeign = dbGetObjects($sTempClasse);
		if (!empty($oTemp->XML_inherited))
			$sXML = $oTemp->XML_inherited;
		else	$sXML = $oTemp->XML;
		unset($stack);
		$stack = NULL;
		$stack = array();
		xmlClassParse($sXML);
		$oForeignXMLChildren = $stack[0]["children"];
		
		$foreignName = $stack[0]["attrs"]["NAME"];
		$foreignLibelle = $stack[0]["attrs"]["LIBELLE"];
		$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
		$foreignNodeToSort = $stack[0]["children"];
 
		foreach ($foreignNodeToSort as $nodeId => $nodeValue) {		
			if (strtolower(stripslashes($nodeValue["attrs"]["NAME"])) == "cms_site")
				$bCms_site = true;
		}
		 
		if ($sTempClasse == "cms_site" && $classeName != "classe" ) {
			// filtre selon l'idsite sur lequel on travaille
			$_POST['filter'.ucfirst($classePrefixe).'_'.$foreignName.''] = $_SESSION['idSite_travail'];

		} else { 
			// sinon affiche liste déroulante	
			echo "<div id= \"".$foreignName."Filter\" class=\"".$foreignName."Filter blocItem\">\n";
			echo "<div id=\"".$foreignName."FilterLabel\" class=\"".$foreignName."FilterLabel\">";
			if (isset($foreignLibelle) && ($foreignLibelle != ""))
					echo strtolower($translator->getText(stripslashes($foreignLibelle)));
				else	echo strtolower(stripslashes($foreignName));
			echo "</div>\n";

			echo "<div id=\"".$foreignName."FilterField\" class=\"".$foreignName."FilterField\">\n";

			//$inherited = getCorrectInheritedClass($oRes->inherited_list, $foreignName);
			//if (!is_null($inherited))
			//	$classePrefixe = $inherited->getPrefix();
		 	  
			$needle = "assofiltre";
			$aReturn = array ();
			$aName = array ();  
			 
			foreach ($_SESSION as $key => $postedvar){ 
				if (strpos($key, $needle) === 0){
					$aKeyVar = array();
					//echo "---".$key ." ".$postedvar."<br />";
					$_POST[$key] = str_replace ("assofiltre", "assoFiltre", $_SESSION[$key]); 
					$aKeyVar[strtolower(str_replace("assofiltre", "", $key))] = $postedvar; 
					
					
					if (!in_array(strtolower(str_replace("assofiltre", "", $key)), $aName)) {
						$aReturn[] = $aKeyVar;
					}
					
				}
			}   
			
			$eKeyValue = $_POST["assoFiltre".ucfirst($classeAsso)]; 
			if (empty($eKeyValue) && !empty($_SESSION["postFilters"])) {
				// Check for session stored filters (ie returning from record page)
				foreach ($_SESSION["postFilters"] as $filter) {
					$field = key($filter);
					//$inherited = getCorrectInheritedClass($oRes->inherited_list, $foreignName);
					//if (!is_null($inherited))
					//	$classePrefixe = $inherited->getPrefix();
					//echo ">>>> POST FILTER ".$field." : ".$classePrefixe."_".$foreignName."<br/>";
					if ($field == $classePrefixe."_".$foreignName) {
						$eKeyValue = $filter[$field];
						break;
					}
				}
			}

			$_GET['custom'] = $classeAsso;
			 
			
			include('list.fkey.php');
		
			echo "</div>\n";	
			echo "</div>\n";
		}
	} // foreach
}
?>