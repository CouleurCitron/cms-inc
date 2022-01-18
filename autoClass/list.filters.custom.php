<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

//viewArray($_SESSION);
// Process custom FKey search fields
if (!empty($aSearchCustom)) {
	foreach ($aSearchCustom as $custom) {
		if ($custom['parent'] == $sTempClasse) {
			// Only keep custom search related to the current fkey node parent table
			//echo "TEST : ".$custom['parent']." : ".$sTempClasse."<br/>";
			if (!empty($custom['fkey'])) {
				// Custom search with FKey related parent
				foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
					if ($nodeValue["attrs"]["NAME"] == $custom['fkey']) {
						//viewArray($nodeValue, $custom['fkey']);
						// Deported FKey
						if (!empty($nodeValue["attrs"]["FKEY"])) {
							
							$sTempSearchClasse = $node["attrs"]["FKEY"];
							if ($node["attrs"]["FKEY"] == 'bo_users' && $node["attrs"]["RESTRICT"] == 'true' && $_SESSION["rank"] != 'ADMIN') {
								// Cas over mega pas typique du tout
								// Cloisonnement sur administrateur loggué
								continue;
							}
							if ($sTempSearchClasse=='null' || $sTempSearchClasse=='' || !class_exists($sTempSearchClasse))
								continue;
							eval("$"."oSearchTemp = new ".$sTempSearchClasse."();");
                                                     			
							if (!empty($oSearchTemp->XML_inherited))
								$sSearchXML = $oSearchTemp->XML_inherited;
							else	$sSearchXML = $oSearchTemp->XML;
							unset($stack);
							$stack = NULL;
							$stack = array();
							xmlClassParse($sSearchXML);
							$oForeignSearchXMLChildren = $stack[0]["children"];
							
							$foreignSearchName = $stack[0]["attrs"]["NAME"];
							$foreignSearchPrefixe = $stack[0]["attrs"]["PREFIX"];
							$foreignSearchNodeToSort = $stack[0]["children"];

							if ($node["attrs"]["FKEY"] == "cms_site" && $foreignName != "classe" ) {
                     							
								// filtre selon l'idsite sur lequel on travaille
								$_POST['filter'.ucfirst($foreignPrefixe).'_'.$node["attrs"]["NAME"].''] = $_SESSION['idSite_travail'];
                     							
							//} elseif (!isset($node["attrs"]["NOEDIT"]) || $node["attrs"]["NOEDIT"] == false ) {  
							} else {
								$inherited = getCorrectInheritedClass($oSearchTemp->inherited_list, $nodeValue['attrs']['NAME']);
								if (!is_null($inherited))
									$foreignSearchPrefixe = $inherited->getPrefix();
								// sinon affiche liste déroulante	
								echo "<div id= \"".$nodeValue["attrs"]["NAME"]."Filter\" class=\"".$nodeValue["attrs"]["NAME"]."Filter blocItem\">\n";
								echo "<div id=\"".$nodeValue["attrs"]["NAME"]."FilterLabel\" class=\"".$nodeValue["attrs"]["NAME"]."FilterLabel\">";
								if (isset($nodeValue["attrs"]["LIBELLE"]) && ($nodeValue["attrs"]["LIBELLE"] != ""))
									echo strtolower($translator->getText(stripslashes($nodeValue["attrs"]["LIBELLE"])));
								else	echo strtolower(stripslashes($nodeValue["attrs"]["NAME"]));
								echo "</div>\n";
              							
								echo "<div id=\"".$nodeValue["attrs"]["NAME"]."FilterField\" class=\"".$nodeValue["attrs"]["NAME"]."FilterField\">\n";	
								$eSearchKeyValue = $_POST["custom".ucfirst($foreignSearchPrefixe)."_".$nodeValue["attrs"]["NAME"]];
								//echo "TEST >>> eSearchKeyValue for ".$foreignName." : ".$eSearchKeyValue."<br/>";
								if (empty($eSearchKeyValue) && !empty($_SESSION["customFilters"]['foreign'])) {
									// Check for session stored filters (ie returning from record page)
									foreach ($_SESSION["customFilters"]['foreign'] as $tbl => $filter) {
										$testTable = (!is_null($inherited) ? $inherited->getTable() : $oSearchTemp->getTable());
										$found = false;
										if ($tbl == $testTable && !empty($filter['filters'])) {
											foreach ($filter['filters'] as $field => $value) {
												//echo ">>>> CUSTOM FILTER ".$tbl." : ".$testTable."|".$node["attrs"]["FKEY"]." / ".$field." : ".$foreignSearchPrefixe."|".$classePrefixe." : ".$custom['fkey']."<br/>";
												if ($field == $foreignSearchPrefixe."_".$custom['fkey']) {
													$eSearchKeyValue = $value['value'];
													$found = true;
													break;
												}
												if ($found)
													break;
											}
										}
									}
								}
                     							
								// AJAX delayed call for fkey select display
								// first define fields not applying to AJAX display
								 
								$excluded = Array('cms_site');
								if (!in_array($foreignSearchName, $excluded)) { 
									//viewArray($excluded, "TEST ".$foreignSearchName." : ".$foreignSearchPrefixe);
									// AJAX delayed process
									echo "\n".'<div id="delayed_'.$foreignSearchPrefixe.'_'.$nodeValue["attrs"]["NAME"].'"  ></div>';
									$call = '/backoffice/cms/call_list_fkey.php?class='.$sTempSearchClasse.'&field='.$nodeValue["attrs"]["NAME"].'&id=-1&mode=search&custom=true&forceValue=';
									$tmp_load = 'Chargement de la liste...<input type="hidden" name="custom'.ucfirst($foreignSearchPrefixe).'_'.$nodeValue['attrs']['NAME'].'" id="custom'.ucfirst($foreignPrefixe).'_'.$nodeValue['attrs']['NAME'].'" class="arbo" value="'.$eSearchKeyValue.'">';
									echo "\n".'<script type="text/javascript">';
									echo "\n".'function ajax'.ucfirst($foreignSearchPrefixe).'_'.$nodeValue["attrs"]["NAME"].'(forceId){';
									echo "\n".'callStr="'.$call.'"+forceId;';
									echo "\n".'XHRConnector.sendAndLoad(callStr, \'GET\', \''.$tmp_load.'\', \'delayed_'.$foreignSearchPrefixe.'_'.$nodeValue["attrs"]["NAME"].'\');';
									echo "\n".'}';
									echo "\n".'ajax'.ucfirst($foreignSearchPrefixe).'_'.$nodeValue["attrs"]["NAME"].'('.$eSearchKeyValue.');';
									echo "\n".'</script>'; 
								} else {
									// inline process
									include('list.fkey.php');
								}
								echo "</div>\n";	
								echo "</div>\n";
							}
						}
					}
					//	$node["attrs"]["FKEY"]
				}

			} elseif (!empty($custom['asso'])) {
				// Custom search with Association related parent
				eval("$"."oSearchTemp = new ".$sTempClasse."();");
 				
				$asso_list = dbGetAssocies($oSearchTemp, $custom['asso'], true);
				
				//viewArray($asso_list, 'asso');
				echo "<div id= \"{$asso_list['asso']['out_name']}Filter\" class=\"{$asso_list['asso']['out_name']}Filter blocItem\">\n";
				echo "<div id=\"{$asso_list['asso']['out_name']}FilterLabel\" class=\"{$asso_list['asso']['out_name']}FilterLabel\">";
				echo $translator->getText($asso_list['asso']['libelle'], $_SESSION['id_langue']);
				echo "</div>\n";
				echo "<div id=\"{$asso_list['asso']['out_name']}FilterField\" class=\"{$asso_list['asso']['out_name']}FilterField\">\n";	

				$eSearchKeyValue = $_POST["custom".ucfirst($asso_list['asso']['out'])];
				//echo "TEST >>> eSearchKeyValue for ".$asso_list['asso']['out']." : ".$eSearchKeyValue."<br/>";
				if (empty($eSearchKeyValue) && !empty($_SESSION["customFilters"]['asso'][$custom['asso']]['filters'][$asso_list['asso']['out']]))
					// Check for session stored filters (ie returning from record page)
					$eSearchKeyValue = $_SESSION["customFilters"]['asso'][$custom['asso']]['filters'][$asso_list['asso']['out']];

 				$ident = "custom".ucfirst($asso_list['asso']['out']);
				echo "<select name=\"{$ident}\" id=\"{$ident}\" class=\"arbo\" onchange=\"filterChange()\"  >\n";
				echo "<option value=\"-1\">-- ".$translator->getTransByCode('tous')." --</option>\n";
				$track = Array();
				foreach ($asso_list['list'] as $asso) {
					//viewArray($asso, 'item for '.$sTempClasse);
					if (!in_array($asso['ref_id'], $track)) {
						$track[] = $asso['ref_id'];
						echo "<option value=\"{$asso['ref_id']}\" ".($asso['ref_id'] == $eSearchKeyValue ? ' selected="selected"' : '').">".
							(!empty($asso['display']) ? $asso['display'] : '').
							(!empty($asso['display']) && !empty($asso['abstract']) ? ' - ' : '').
							(!empty($asso['abstract']) ? $asso['abstract'] : '').
							"</option>\n";
					}
				}
				echo "</select>";
				echo "</div>\n";	
				echo "</div>\n";
			} elseif (!empty($custom['text'])) {
				// Custom search on related parent text field
 				
				$flds = array_map('trim', explode(',', $custom['text']));
				$keep = Array();
				foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
					if (in_array($nodeValue["attrs"]["NAME"], $flds))
						$keep[] = $translator->getText($nodeValue["attrs"]["NAME"], $_SESSION['id_langue']);
				}	
				if (!empty($keep)) {
					echo "<div id= \"{$sTempClasse}_textFilter\" class=\"{$sTempClasse}_textFilter blocItem\">\n";
					echo "<div id=\"{$sTempClasse}_textFilterLabel\" class=\"{$sTempClasse}_textFilterLabel\">".$translator->getText($stack[0]["attrs"]["LIBELLE"], $_SESSION['id_langue'])." (".implode(', ', $keep).")</div>\n";
					echo "<div id=\"{$sTempClasse}_textFilterField\" class=\"{$sTempClasse}_textFilterField\">\n";	
                                                     	
					$eSearchKeyValue = $_POST["custom".ucfirst($sTempClasse."_text")];
					//echo "TEST >>> eSearchKeyValue for ".$custom['parent']."_".$custom['text']." : ".$eSearchKeyValue."<br/>";
					if (empty($eSearchKeyValue) && !empty($_SESSION["customFilters"]['foreign'])) {
						// Check for session stored filters (ie returning from record page)
						$fld = $keep[0];
						$inherited = getCorrectInheritedClass($oTemp->inherited_list, $fld);
						foreach ($_SESSION["customFilters"]['foreign'] as $tbl => $filter) {
							$testTable = (!is_null($inherited) ? $inherited->getTable() : $oTemp->getTable());
							$found = false;
							if ($tbl == $testTable && !empty($filter['filters'])) {
								foreach ($filter['filters'] as $field => $value) {
									//echo ">>>> CUSTOM FILTER ".$tbl." : ".$testTable."|".$node["attrs"]["FKEY"]." / ".$field." : ".$foreignSearchPrefixe."|".$classePrefixe." : ".$custom['text']."<br/>";
									if ($field == $foreignSearchPrefixe."_".$fld) {
										$eSearchKeyValue = $value;
										$found = true;
										break;
									}
									if ($found)
										break;
								}
							}
						}
					}
                                                     	
 					$ident = "custom".ucfirst($sTempClasse."_text");
					echo "<input type=\"text\" name=\"{$ident}\" id=\"{$ident}\" value=\"{$eSearchKeyValue}\" class=\"arbo\" size=\"40\"/>";
					echo "</div>\n";	
					echo "</div>\n";
				}
			}
		}
	}
}

?>

