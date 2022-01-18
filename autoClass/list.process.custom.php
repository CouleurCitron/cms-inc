<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// Filter for custom FKey search fields
$aCustomFilters = $_SESSION["customFilters"];
$refreshSearchFilters = ($_POST['urlRetour'] == $_SERVER['PHP_SELF'] && !in_array($operation, Array("DELETE", "CHANGE_STATUT")) ?  true : false);
if (!empty($aSearchCustom)) {
	$fkeyNodes = getItemsByAttribute($aNodeToSort, "fkey");
	if (!empty($fkeyNodes)){
		foreach ($fkeyNodes as $nkey => $node){
			$sTempClasse = $node["attrs"]["FKEY"];

			if ($node["attrs"]["FKEY"] == 'bo_users' && $node["attrs"]["RESTRICT"] == 'true' && $_SESSION["rank"] != 'ADMIN') {
				// Cas over mega pas typique du tout
				// Cloisonnement sur administrateur loggué
				continue;
			}
			if ($sTempClasse=='null' || $sTempClasse=='' || !class_exists($sTempClasse)){
				continue;
			}
						
			$inherited = getCorrectInheritedClass($oRes->inherited_list, $node['attrs']['NAME']);
			//if (!is_null($inherited))
			//	$sTableName = $inheritedForeign->getTable();
			//else 	$sTableName = $sTempClasse;
			foreach ($aSearchCustom as $custom) {
				if ($custom['parent'] == $sTempClasse) {
					if (!empty($custom['fkey'])) {

						// Custom search with FKey related parent
						eval("$"."oTemp = new ".$sTempClasse."();");
						
						if (!empty($oTemp->XML_inherited))
							$sXML = $oTemp->XML_inherited;
						else	$sXML = $oTemp->XML;
						unset($stack);
						$stack = NULL;
						$stack = array();
						xmlClassParse($sXML);
						$oForeignXMLChildren = $stack[0]["children"];
						
						//$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
						$foreignNodeToSort = $stack[0]["children"];

						$inheritedForeign = getCorrectInheritedClass($oTemp->inherited_list, $custom['fkey']);
						if (!is_null($inheritedForeign)) {
							$sTableName = $inheritedForeign->getTable();
							$sTableFieldName = $inheritedForeign->getPrefix().'_'.$custom['fkey'];
						} else {
							$sTableName = $sTempClasse;
							$sTableFieldName = $oTemp->getPrefix().'_'.$custom['fkey'];
						}
						$sSearchFieldName = 'custom'.ucfirst($sTableFieldName);
						
						foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
							if ($nodeValue["attrs"]["NAME"] == $custom['fkey']) {
								if (!empty($nodeValue['attrs']['FKEY'])) {
									// Deported FKey
									if ($_POST[$sSearchFieldName] > 0) {
										//echo ">>>> ADD FKEY filter ".$sTableName." : ".$sTableFieldName." : ".$_POST[$sSearchFieldName]."<br/>";
										$aCustomFilters['foreign'][$sTableName]['filters'][$sTableFieldName] = $_POST[$sSearchFieldName];
									} else {
										//echo ">>>> Cleanup FKEY filter : ".$sTableFieldName." : ".$aCustomFilters['foreign'][$sTableName]['filters'][$sTableFieldName]."<br/>";
										if ($refreshSearchFilters && !empty($aCustomFilters['foreign'][$sTableName]['filters'][$sTableFieldName]))
											unset($aCustomFilters['foreign'][$sTableName]['filters'][$sTableFieldName]);
									}
								} elseif (!empty($_POST[$sSearchFieldName])) {
									//echo ">>>> ADD filter ".$sTableName." : ".$sTableFieldName." : ".$_POST[$sSearchFieldName]."<br/>";
									$aCustomFilters['foreign'][$sTableName]['filters'][$sTableFieldName] = $_POST[$sSearchFieldName];
								} elseif ($refreshSearchFilters && !empty($aCustomFilters['foreign'][$sTableName]['filters'][$sTableFieldName])) {
									// Clear search value
									//echo ">>>> Cleanup FKEY filter : ".$sTableName." : ".$sTableFieldName." : ".$aCustomFilters['foreign'][$sTableName]['filters'][$sTableFieldName]."<br/>";
									unset($aCustomFilters['foreign'][$sTableName]['filters'][$sTableFieldName]);
								}
								if (newSizeOf($aCustomFilters['foreign'][$sTableName]['filters']) == 0) {
									// Global cleanup custom search for this class fields
									//echo ">>>> Cleanup local filter : ".$sTableName." : ".newSizeOf($aCustomFilters['foreign'][$sTableName]['filters'])."<br/>";
									unset($aCustomFilters['foreign'][$sTableName]['filters']);
								}
							}
						}
						if (!empty($aCustomFilters['foreign'][$sTableName]['filters'])) {
							//echo ">>>> Check MATCH ".$sTempClasse."<br/>";
							if (!is_null($inheritedForeign)) {
								//echo ">>>> ADD match ".$sTempClasse." : ".$oTemp->getFieldPK()." : ".$sTableName." : ".$inheritedForeign->getFieldPK()."<br/>";
								$aCustomFilters['foreign'][$sTempClasse]['match'] = Array(	'm_key'		=> $oTemp->getFieldPK(),
															'linked'		=> $sTableName,
															'l_key'		=> $inheritedForeign->getFieldPK() );
							}// else	$sTableName = $sTempClasse;
							if (empty($aCustomFilters['foreign'][$sTableName]['props'])) {
								//echo ">>>> ADD props ".$sTableName."<br/>";
								if (!is_null($inheritedForeign)) {
									$props = Array( 	'fkey'	=> (!is_null($inherited) ? $inherited->getPrefix() : $classePrefixe).'_'.$node['attrs']['NAME'],
											'pkey'	=> $inheritedForeign->getFieldPK() );
									// Add heritage relation
									//$aCustomFilters['foreign'][$sTableName]['match'] = Array(	'm_key'		=> $inherited->getFieldPK(),
									//							'linked'		=> $sTempClasse,
									//							'l_key'		=> $oTemp->getFieldPK() );
									//$aCustomFilters['foreign'][$sTempClasse]['match'] = Array(	'm_key'		=> $oTemp->getFieldPK(),
									//							'linked'		=> $sTableName,
									//							'l_key'		=> $inherited->getFieldPK() );
								} else	$props = Array( 	'fkey'	=> $classePrefixe.'_'.$node['attrs']['NAME'],
											'pkey'	=> $oTemp->getFieldPK() );
								
								//echo ">>>> SET PROPS : ".$sTableName."<br/>";
								/*if (empty($aCustomFilters['foreign'][$sTableName]))
									$aCustomFilters['foreign'][$sTableName] = Array(	'props'		=> $props,
															'filters'	=> Array() );
								else*/	$aCustomFilters['foreign'][$sTableName]['props'] = $props;
							}
						} else {
							if (!empty($aCustomFilters['foreign'][$sTempClasse]['match'])) {
								// Global cleanup custom search for this class fields
								//echo ">>>> Cleanup global filter : ".$sTempClasse."<br/>";
								unset($aCustomFilters['foreign'][$sTempClasse]);
							}
							//echo ">>>> Cleanup global filter : ".$sTableName."<br/>";
							unset($aCustomFilters['foreign'][$sTableName]);
						}

					} elseif (!empty($custom['asso'])) {

						// Custom search with Association related parent
						eval("$"."oTemp = new ".$sTempClasse."();");
						$asso_props = dbGetAssocProps($oTemp, $custom['asso']);
						//viewArray($asso_props, $sSearchFieldName." : ".$_POST[$sSearchFieldName]);

						$sSearchFieldName = 'custom'.ucfirst($asso_props['out']);
						if ($_POST[$sSearchFieldName] > 0) {

							if ($asso_props['in'] != $sTempClasse && in_array($asso_props['in'], $oTemp->inherited_list)) {
								eval("$"."inheritedForeign = new ".$asso_props['in']."();");
								$sTableName = $inheritedForeign->getTable();
								$sTableFieldName = $inheritedForeign->getPrefix().'_'.$asso_props['in_name'];
							} else {
								$sTableName = $oTemp->getTable();
								$sTableFieldName = $oTemp->getPrefix().'_'.$asso_props['in_name'];
							}
							//echo ">>>> ADD filter ".$asso_props['class']." : ".$asso_props['prefix'].'_'.$asso_props['out_name']." : ".$_POST[$sSearchFieldName]."<br/>";
							$aCustomFilters['asso'][$asso_props['class']]['filters'][$asso_props['prefix'].'_'.$asso_props['out_name']] = $_POST[$sSearchFieldName];
							
							//echo ">>>> ADD match ".$sTableName." : ".(!is_null($inheritedForeign) ? $inheritedForeign->getFieldPK() : $oTemp->getFieldPK())." : ".$asso_props['class']." : ".$asso_props['prefix'].'_'.$asso_props['in_name']."<br/>";
							$aCustomFilters['asso'][$sTableName]['match'] = Array(	'm_key'		=> (!is_null($inheritedForeign) ? $inheritedForeign->getFieldPK() : $oTemp->getFieldPK()),
														'linked'		=> $asso_props['class'],
														'l_key'		=> $asso_props['prefix'].'_'.$asso_props['in_name'] );

						} elseif ($refreshSearchFilters) {
							if (!empty($aCustomFilters['asso'][$asso_props['class']]['filters'][$asso_props['prefix'].'_'.$asso_props['out_name']])) {
								//echo ">>>> Cleanup filter : ".$asso_props['class']." : ".$asso_props['prefix'].'_'.$asso_props['out_name']." : ".$aCustomFilters['asso'][$asso_props['class']]['filters'][$asso_props['prefix'].'_'.$asso_props['out_name']]."<br/>";
								unset($aCustomFilters['asso'][$asso_props['class']]['filters'][$asso_props['prefix'].'_'.$asso_props['out_name']]);
								//echo ">>>> Cleanup match filter : ".$sTableName."<br/>";
								unset($aCustomFilters['asso'][$sTableName]['match']);
							}
						}
						if (is_array($aCustomFilters['asso'][$asso_props['class']]['filters']) && newSizeOf($aCustomFilters['asso'][$asso_props['class']]['filters']) == 0) {
							// Global cleanup custom search for this class fields
							//echo ">>>> Cleanup local filter : ".$asso_props['class']." : ".newSizeOf($aCustomFilters['asso'][$asso_props['class']]['filters'])."<br/>";
							unset($aCustomFilters['asso'][$asso_props['class']]['filters']);
						}
						if (!empty($aCustomFilters['asso'][$asso_props['class']]['filters'])) {
							if (!is_null($inheritedForeign)) {
								//echo ">>>> ADD match ".$sTempClasse." : ".$oTemp->getFieldPK()." : ".$sTableName." : ".$inheritedForeign->getFieldPK()."<br/>";
								$aCustomFilters['asso'][$sTempClasse]['match'] = Array(	'm_key'		=> $oTemp->getFieldPK(),
															'linked'		=> $sTableName,
															'l_key'		=> $inheritedForeign->getFieldPK() );
							}
							if (empty($aCustomFilters['asso'][$sTableName]['props'])) {
								//echo ">>>> ADD props ".$sTableName."<br/>";
								if (!is_null($inheritedForeign))
									$props = Array( 	'fkey'	=> (!is_null($inheritedForeign) ? $inheritedForeign->getPrefix() : $classePrefixe).'_'.$node['attrs']['NAME'],
											'pkey'	=> $inheritedForeign->getFieldPK() );
								else	$props = Array( 	'fkey'	=> $oTemp->getPrefix().'_'.$node['attrs']['NAME'],
											'pkey'	=> $oTemp->getFieldPK() );
								$aCustomFilters['asso'][$sTableName]['props'] = $props;
							}
						} else {
							//echo ">>>> Cleanup global filter : ".$asso_props['class']."<br/>";
							unset($aCustomFilters['asso'][$asso_props['class']]);
							if (!empty($aCustomFilters['asso'][$sTempClasse]['match']) || newSizeOf($aCustomFilters['asso']) == 1 && key($aCustomFilters['asso']) == $sTableName) {
								//echo ">>>> Cleanup global filter : ".$sTempClasse."<br/>";
								unset($aCustomFilters['asso'][$sTempClasse]);
								//echo ">>>> Cleanup global filter : ".$sTableName."<br/>";
								unset($aCustomFilters['asso'][$sTableName]);  
							}
						}

					} elseif (!empty($custom['text'])) {

						// Custom search related parent text field
						eval("$"."oTemp = new ".$sTempClasse."();");
						
						if (!empty($oTemp->XML_inherited))
							$sXML = $oTemp->XML_inherited;
						else	$sXML = $oTemp->XML;
						unset($stack);
						$stack = NULL;
						$stack = array();
						xmlClassParse($sXML);
						$oForeignXMLChildren = $stack[0]["children"];
						
						//$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
						$foreignNodeToSort = $stack[0]["children"];
						$sSearchFieldName = 'custom'.ucfirst($sTempClasse.'_text');

						$flds = array_map('trim', explode(',', $custom['text']));
						foreach ($flds as $fld) {
							$inheritedForeign = getCorrectInheritedClass($oTemp->inherited_list, $fld);
							if (!is_null($inheritedForeign)) {
								$sTableName = $inheritedForeign->getTable();
								$sTableFieldName = $inheritedForeign->getPrefix().'_'.$fld;
							} else {
								$sTableName = $sTempClasse;
								$sTableFieldName = $oTemp->getPrefix().'_'.$fld;
							}
							foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
								if ($nodeValue["attrs"]["NAME"] == $fld) {
									if (!empty($_POST[$sSearchFieldName])) {
										//echo ">>>> ADD filter ".$sTableName." : ".$sTableFieldName." : ".$_POST[$sSearchFieldName]."<br/>";
										$aCustomFilters['text'][$sTableName]['filters'][$sTableFieldName] = Array(	'translation'	=> (!empty($nodeValue["attrs"]["TRANSLATE"]) ? $nodeValue["attrs"]["TRANSLATE"] : false),
																			'value'		=> $_POST[$sSearchFieldName] );
									} elseif ($refreshSearchFilters && !empty($aCustomFilters['text'][$sTableName]['filters'][$sTableFieldName])) {
										// Clear search value
										//echo ">>>> Cleanup FKEY filter : ".$sTableName." : ".$sTableFieldName." : ".$aCustomFilters['text'][$sTableName]['filters'][$sTableFieldName]."<br/>";
										unset($aCustomFilters['text'][$sTableName]['filters'][$sTableFieldName]);
									}
									if (newSizeOf($aCustomFilters['text'][$sTableName]['filters']) == 0) {
										// Global cleanup custom search for this class fields
										//echo ">>>> Cleanup local filter : ".$sTableName." : ".newSizeOf($aCustomFilters['text'][$sTableName]['filters'])."<br/>";
										unset($aCustomFilters['text'][$sTableName]['filters']);
									}
								}
							}
						}
						if (!empty($aCustomFilters['text'][$sTableName]['filters'])) {
							//echo ">>>> Check MATCH ".$sTempClasse."<br/>";
							if (!is_null($inheritedForeign)) {
								//echo ">>>> ADD match ".$sTempClasse." : ".$oTemp->getFieldPK()." : ".$sTableName." : ".$inheritedForeign->getFieldPK()."<br/>";
								$aCustomFilters['text'][$sTempClasse]['match'] = Array(	'm_key'		=> $oTemp->getFieldPK(),
															'linked'		=> $sTableName,
															'l_key'		=> $inheritedForeign->getFieldPK() );
							}// else	$sTableName = $sTempClasse;
							if (empty($aCustomFilters['foreign'][$sTableName]['props'])) {
								//echo ">>>> ADD props ".$sTableName."<br/>";
								if (!is_null($inheritedForeign)) {
									$props = Array( 	'fkey'	=> (!is_null($inherited) ? $inherited->getPrefix() : $classePrefixe).'_'.$node['attrs']['NAME'],
											'pkey'	=> $inheritedForeign->getFieldPK() );
								} else	$props = Array( 	'fkey'	=> $classePrefixe.'_'.$node['attrs']['NAME'],
											'pkey'	=> $oTemp->getFieldPK() );
								
								//echo ">>>> SET PROPS : ".$sTableName."<br/>";
								$aCustomFilters['text'][$sTableName]['props'] = $props;
							}
						} else {
							if (!empty($aCustomFilters['text'][$sTempClasse]['match'])) {
								// Global cleanup custom search for this class fields
								//echo ">>>> Cleanup global filter : ".$sTempClasse."<br/>";
								unset($aCustomFilters['text'][$sTempClasse]);
							}
							//echo ">>>> Cleanup global filter : ".$sTableName."<br/>";
							unset($aCustomFilters['text'][$sTableName]);
						}
						
					}
				}
			}
		}
	}
}

//viewArray($aCustomFilters, 'processed custom filters');
//viewArray($_SESSION["customFilters"], 'session custom filters before');


if ($refreshSearchFilters)
	$_SESSION["customFilters"] = $aCustomFilters;

//viewArray($_SESSION["customFilters"], 'session custom filters after');

// Process custom FKey search fields
if (!empty($aCustomFilters)) {
	$aRechCustom = Array();
	$cnt = 0;
	if (!empty($aCustomFilters['foreign'])) {
		foreach ($aCustomFilters['foreign'] as $linked => $aCustomFilter) {
			if (!empty($aCustomFilter['match'])) {
				$aRechCustom[$cnt] = new dbRecherche();				
				$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
				$aRechCustom[$cnt]->setTableBD($linked);
				$aRechCustom[$cnt]->setJointureBD(" {$linked}.{$aCustomFilter['match']['m_key']}={$aCustomFilter['match']['linked']}.{$aCustomFilter['match']['l_key']} ");
				$aRechCustom[$cnt]->setPureJointure(1);
				$aRecherche[] = $aRechCustom[$cnt];
				$cnt++;
			} else {
				$aRechCustom[$cnt] = new dbRecherche();				
				$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
				$aRechCustom[$cnt]->setTableBD($linked);
				$aRechCustom[$cnt]->setJointureBD(" {$linked}.{$aCustomFilter['props']['pkey']}={$classeName}.{$aCustomFilter['props']['fkey']} ");
				$aRechCustom[$cnt]->setPureJointure(1);				
				$aRecherche[] = $aRechCustom[$cnt];
				$cnt++;
				foreach ($aCustomFilter['filters'] as $customKey => $customVal) {
					if (!empty($customVal) || $customVal == 0) { 	
						$aRechCustom[$cnt] = new dbRecherche();				
						$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
						$aRechCustom[$cnt]->setTableBD($linked);
						$aRechCustom[$cnt]->setJointureBD(" {$linked}.{$customKey}='{$customVal}' ");
						$aRechCustom[$cnt]->setPureJointure(1);				
						$aRecherche[] = $aRechCustom[$cnt];
						$cnt++;
					}
				}
			}
		}
	}
	if (!empty($aCustomFilters['asso'])) {
		foreach ($aCustomFilters['asso'] as $linked => $aCustomFilter) {
			if (!empty($aCustomFilter['match'])) {
				$aRechCustom[$cnt] = new dbRecherche();				
				$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
				$aRechCustom[$cnt]->setTableBD($linked);
				$aRechCustom[$cnt]->setJointureBD(" {$linked}.{$aCustomFilter['match']['m_key']}={$aCustomFilter['match']['linked']}.{$aCustomFilter['match']['l_key']} ");
				$aRechCustom[$cnt]->setPureJointure(1);
				$aRecherche[] = $aRechCustom[$cnt];
				$cnt++;
			}
			if (!empty($aCustomFilter['props'])) {
				$aRechCustom[$cnt] = new dbRecherche();				
				$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
				$aRechCustom[$cnt]->setTableBD($linked);
				$aRechCustom[$cnt]->setJointureBD(" {$linked}.{$aCustomFilter['props']['pkey']}={$classeName}.{$aCustomFilter['props']['fkey']} ");
				$aRechCustom[$cnt]->setPureJointure(1);				
				$aRecherche[] = $aRechCustom[$cnt];
				$cnt++;

			}
			if (!empty($aCustomFilter['filters'])) {
				foreach ($aCustomFilter['filters'] as $customKey => $customVal) {
					if (!empty($customVal) || $customVal == 0) { 	
						$aRechCustom[$cnt] = new dbRecherche();				
						$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
						$aRechCustom[$cnt]->setTableBD($linked);
						$aRechCustom[$cnt]->setJointureBD(" {$linked}.{$customKey}='{$customVal}' ");
						$aRechCustom[$cnt]->setPureJointure(1);				
						$aRecherche[] = $aRechCustom[$cnt];
						$cnt++;
					}
				}
			}
		}
	}
	if (!empty($aCustomFilters['text'])) {
		foreach ($aCustomFilters['text'] as $linked => $aCustomFilter) {
			if (!empty($aCustomFilter['match'])) {
				$aRechCustom[$cnt] = new dbRecherche();				
				$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
				$aRechCustom[$cnt]->setTableBD($linked);
				$aRechCustom[$cnt]->setJointureBD(" {$linked}.{$aCustomFilter['match']['m_key']}={$aCustomFilter['match']['linked']}.{$aCustomFilter['match']['l_key']} ");
				$aRechCustom[$cnt]->setPureJointure(1);
				$aRecherche[] = $aRechCustom[$cnt];
				$cnt++;
			} else {
				$aRechCustom[$cnt] = new dbRecherche();				
				$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
				$aRechCustom[$cnt]->setTableBD($linked);
				$aRechCustom[$cnt]->setJointureBD(" {$linked}.{$aCustomFilter['props']['pkey']}={$classeName}.{$aCustomFilter['props']['fkey']} ");
				$aRechCustom[$cnt]->setPureJointure(1);				
				$aRecherche[] = $aRechCustom[$cnt];
				$cnt++;
				$wrds_where = Array();
				//$tsls = Array(	'reference'	=> Array(),
				//		'value'		=> Array() );
				$tsls = Array();
				foreach ($aCustomFilter['filters'] as $customKey => $customVal) {
					if (!empty($customVal) && $customVal['translation'])
						//$tsls[$customVal['translation']][] = $customKey;
						$tsls[] = $customKey;
				}
				//if (!empty($tsls['reference']) || !empty($tsls['value'])) {
				if (!empty($tsls)) {
					// ------------------------------------ TRADUCTION ---------------------------------- // 	
					$sqlTLS = '';
					$aCondTLS = array ();
					//$sqlTLS = 'SELECT DISTINCT * FROM cms_chaine_reference WHERE ';
					$sqlTLS = 'SELECT DISTINCT * FROM cms_chaine_reference LEFT OUTER JOIN cms_chaine_traduite ON cms_chaine_traduite.cms_ctd_id_reference = cms_chaine_reference.cms_crf_id WHERE ';
					foreach ($tsls as $ref) {
						$texts = array_map('trim', explode(',', $aCustomFilter['filters'][$ref]['value']));
						foreach ($texts as $txt) {
							$aCondTLS[] = "cms_crf_chaine LIKE '%".$txt."%'";
							$aCondTLS[] = "cms_ctd_chaine LIKE '%".$txt."%'";
						}
					}
					$sqlTLS.= "(".implode(' OR ', $aCondTLS).")";
					$aCacheIdTLS_ref =  array();
					$aCacheIdTLS_trad =  array();
					$aObjects = dbGetObjectsFromRequete('cms_chaine_reference', $sqlTLS);	
					if (!empty($aObjects) > 0) {
						foreach ($aObjects as $oObject)
							$aCacheIdTLS_ref[] = $oObject->get_id();
						$in_select_ref = implode(",", $aCacheIdTLS_ref);
					}
					$aObjects = dbGetObjectsFromRequete('cms_chaine_traduite', $sqlTLS);	
					if (!empty($aObjects) > 0) {
						foreach ($aObjects as $oObject)
							$aCacheIdTLS_trad[] = $oObject->get_id();
						$in_select_trad = implode(",", $aCacheIdTLS_trad);
					}
					 
					// ------------------------------------ TRADUCTION ---------------------------------- //  
				}
				foreach ($aCustomFilter['filters'] as $customKey => $customVal) {
					if (!empty($customVal)) {
						if ($customVal['translation'] == 'reference') {
							$wrds_where[] = "{$linked}.{$customKey} IN ({$in_select_ref})";
						} elseif ($customVal['translation'] == 'value') {
							$wrds_where[] = "{$linked}.{$customKey} IN ({$in_select_trad})";
						} else {
							$wrds = explode(' ', $customVal['value']);
							foreach ($wrds as $wrd)
								$wrds_where[] = "{$linked}.{$customKey} LIKE '%{$wrd}%'";
						}
					}
				}
				if (!empty($wrds_where)) {
					$aRechCustom[$cnt] = new dbRecherche();				
					$aRechCustom[$cnt]->setValeurRecherche("declencher_recherche");
					$aRechCustom[$cnt]->setTableBD($linked);
					$aRechCustom[$cnt]->setJointureBD("(".implode(' OR ', $wrds_where).")");
					$aRechCustom[$cnt]->setPureJointure(1);				
					$aRecherche[] = $aRechCustom[$cnt];
					$cnt++;
				}
			}
		}
	}
	// Free...
	unset($aCustomFilters);
}




?>
