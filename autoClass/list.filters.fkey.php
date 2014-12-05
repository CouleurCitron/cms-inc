<?php
$fkeyNodes = getItemsByAttribute($aNodeToSort, "fkey");
	
if ($fkeyNodes != false){
	$sel = '';
	$aOps=array();
	
	foreach ($fkeyNodes as $nkey => $node){ 

		if (isset($node["attrs"]["SKIP"])	&&	($node["attrs"]["SKIP"]=='true')){
			// skip
		}
		else{
			// check si cms_site défini
			$bCms_site = false;
			$sTempClasse = $node["attrs"]["FKEY"];
			
			if ($node["attrs"]["FKEY"] == 'bo_users' && in_array($node["attrs"]["RESTRICT"], array('strict', 'loose')) && $_SESSION["rank"] != 'ADMIN') {
				// Cas over mega pas typique du tout
				// Cloisonnement sur administrateur loggué
				continue;
			}
			if ($sTempClasse=='null' || $sTempClasse=='' || !class_exists($sTempClasse)){
				continue;
			}
			
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
			$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
			$foreignNodeToSort = $stack[0]["children"];
	 
			foreach ($foreignNodeToSort as $nodeId => $nodeValue) {		
				if (strtolower(stripslashes($nodeValue["attrs"]["NAME"])) == "cms_site")
					$bCms_site = true;
			}
		 
			if ($node["attrs"]["FKEY"] == "cms_site" && $classeName != "classe" ) {
	
				// filtre selon l'idsite sur lequel on travaille
				$_POST['filter'.ucfirst($classePrefixe).'_'.$node["attrs"]["NAME"].''] = $_SESSION['idSite_travail'];
	
	//			} elseif (!isset($node["attrs"]["NOEDIT"]) || $node["attrs"]["NOEDIT"] == false ) {  
			} elseif (!isset($node["attrs"]["NOSEARCH"]) || $node["attrs"]["NOSEARCH"] == false )  {  
	
				// sinon affiche liste déroulante	
				echo "<div id= \"".$node["attrs"]["NAME"]."Filter\" class=\"".$node["attrs"]["NAME"]."Filter blocItem\">\n";
				echo "<div id=\"".$node["attrs"]["NAME"]."FilterLabel\" class=\"".$node["attrs"]["NAME"]."FilterLabel\">";
				if (isset($node["attrs"]["LIBELLE"]) && ($node["attrs"]["LIBELLE"] != ""))
						echo strtolower($translator->getText(stripslashes($node["attrs"]["LIBELLE"])));
					else	echo strtolower(stripslashes($node["attrs"]["NAME"]));
				echo "</div>\n";
	
				echo "<div id=\"".$node["attrs"]["NAME"]."FilterField\" class=\"".$node["attrs"]["NAME"]."FilterField\">\n";
	
				//$inherited = getCorrectInheritedClass($oRes->inherited_list, $node["attrs"]["NAME"]);
				//if (!is_null($inherited))
				//	$classePrefixe = $inherited->getPrefix();
				$eKeyValue = $_POST["filter".ucfirst($classePrefixe)."_".$node["attrs"]["NAME"]];
				//echo "TEST >>> eKeyValue for ".$foreignName." : ".$eKeyValue."<br/>";
				if (empty($eKeyValue) && !empty($_SESSION["postFilters"])) {
					// Check for session stored filters (ie returning from record page)
					foreach ($_SESSION["postFilters"] as $filter) {
						$field = key($filter);
						//$inherited = getCorrectInheritedClass($oRes->inherited_list, $node["attrs"]["NAME"]);
						//if (!is_null($inherited))
						//	$classePrefixe = $inherited->getPrefix();
						//echo ">>>> POST FILTER ".$field." : ".$classePrefixe."_".$node["attrs"]["NAME"]."<br/>";
						if ($field == $classePrefixe."_".$node["attrs"]["NAME"]) {
							$eKeyValue = $filter[$field];
							break;
						}
					}
				}
	
				// AJAX delayed call for fkey select display
				// first define fields not applying to AJAX display
				 
				$excluded = Array('cms_site');
				if (!in_array($classeName, $excluded)) { 
					//if ($id == 0)
					//	$id = -1;
					// AJAX delayed process
					echo "\n".'<div id="delayed_'.$foreignPrefixe.'_'.$node["attrs"]["NAME"].'"  ></div>';
					if ($classeName == $displayField)
						$call = '/backoffice/cms/call_list_fkey.php?class='.$classeName.'&display='.$display.'&field='.$classeName.'&id=-1&mode=search&forceValue=';
					else	$call = '/backoffice/cms/call_list_fkey.php?class='.$classeName.'&field='.$node["attrs"]["NAME"].'&id=-1&mode=search&forceValue=';
					//echo "test : ".$call."<br/>";
					//echo "</div>\n";
					$tmp_load = 'Chargement de la liste...<input type="hidden" name="filter'.ucfirst($foreignPrefixe).'_'.$node['attrs']['NAME'].'" id="filter'.ucfirst($foreignPrefixe).'_'.$node['attrs']['NAME'].'" class="arbo" value="'.$eKeyValue.'">';
					echo "\n".'<script type="text/javascript">';
					echo "\n".'function ajax'.ucfirst($foreignPrefixe).'_'.$node["attrs"]["NAME"].'(forceId){';
					echo "\n".'callStr="'.$call.'"+forceId;';
					//echo "\n".'alert(callStr);';
					echo "\n".'XHRConnector.sendAndLoad(callStr, \'GET\', \''.$tmp_load.'\', \'delayed_'.$foreignPrefixe.'_'.$node["attrs"]["NAME"].'\');';
					echo "\n".'}';
					echo "\n".'ajax'.ucfirst($foreignPrefixe).'_'.$node["attrs"]["NAME"].'('.$eKeyValue.');';
					echo "\n".'</script>'; 
				} else {
					// inline process
					include('list.fkey.php');
				}
				echo "</div>\n";	
				echo "</div>\n";
			}
	
			// Custom search fields
			include('list.filters.custom.php');
		}
	}
} // end fkeyNodes != false 
?>