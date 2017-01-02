<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

$direct_call = false;
$exit_display = false;

if (empty($aNodeToSort)) {
	// AJAX or direct call
	// Added by Luc
	$direct_call = true;
	include_once("cms-inc/include_cms.php");
	include_once("cms-inc/include_class.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

	// translation engine
	//if (DEF_APP_USE_TRANSLATIONS) {
		$translator =& TslManager::getInstance();
		$langpile = $translator->getLanguages();
	//}

	if (!empty($_GET['class']) && !empty($_GET['id']) && !empty($_GET['field'])) {
		// get an instance of currently displaying class
		eval("$"."oRes = new ".$_GET['class']."(".$_GET['id'].");");
		if (!is_null($oRes->XML_inherited))
			$sXML = $oRes->XML_inherited;
		else	$sXML = $oRes->XML;
		xmlClassParse($sXML);

		$classeName = $stack[0]["attrs"]["NAME"];
		if (isset($stack[0]["attrs"]["LIBELLE"]) && ($stack[0]["attrs"]["LIBELLE"] != ""))
			$classeLibelle = stripslashes($stack[0]["attrs"]["LIBELLE"]);
		else	$classeLibelle = $classeName;

		$classePrefixe = $stack[0]["attrs"]["PREFIX"];
		$aNodeToSort = $stack[0]["children"];

		$id = $_GET['id'];

	} else	$exit_display = true;	
}
// end AJAX or direct call
 

for ($i=0; $i<count($aNodeToSort); $i++) {
	if ($aNodeToSort[$i]["name"] == "ITEM") {
		if ($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_VIEW"]) { // cas d'asso
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);			

			$aTempClasse = array();
			if ($aNodeToSort[$i]["attrs"]["ASSO"])
				$aTempClasse = explode(',', $aNodeToSort[$i]["attrs"]["ASSO"]);		
			elseif ($aNodeToSort[$i]["attrs"]["ASSO_VIEW"])
				$aTempClasse = explode(',', $aNodeToSort[$i]["attrs"]["ASSO_VIEW"]);		

			if (!empty($aTempClasse)) {
				foreach ($aTempClasse as $assoc) {

					$asso_block = '';
					$asso_list = dbGetAssocies($oRes, $assoc);

					//viewArray($asso_list, 'ASSOS');
					//$tempAssoFull = $asso_list['asso']["attrs"]["IS_ASSO"] == 'true' ? true : false;
					//$tempAsymetric = ($tempAssoFull && $asso_list['asso']["attrs"]["IS_ASYMETRIC"] == 'true') ? true : false;
					$tempClass = $asso_list['asso']['class'];
					$tempAsymetric = $asso_list['asso']['assymetric'];

					if (is_array($asso_list['asso']['children'])) {
						// associate another record from the SAME table
						$track_key = 0;
						//foreach ($asso_list['asso']['children'] as $nodeId => $nodeValue) {
						foreach ($asso_list['XML']['children'] as $nodeId => $nodeValue) {
							if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] == $classeName)
								$track_key++;
						}
						if ($track_key = 0) {
							// assymetric mode is only for table asso-linking to itself
							// so in case it was unwantedly set in asso table XML :
							$tempAsymetric = false;
						}
					}

					//if (!empty($asso_list['XML']["attrs"]["LIBELLE"]))
					//	$libelleAsso = stripslashes($asso_list['XML']["attrs"]["LIBELLE"]);
					if (!empty($asso_list['asso']['libelle']))
						$libelleAsso = stripslashes($asso_list['asso']['libelle']);
					else	$libelleAsso = $asso_list['XML']["attrs"]["NAME"];

					// display results
					echo "<table>\n";			
					echo "<tr>\n";
					echo "<td width=\"141\" align=\"right\" style=\"padding: 4px 8px; border:solid #999999 1px;\">";
					echo "&nbsp;<b>".$translator->getText($libelleAsso)."</b>&nbsp;*";
					echo "</td>\n";
					echo "<td width=\"494\" style=\"padding: 4px 8px; border:solid #999999 1px;\">\n";
	
					//viewArray($asso_list['list']);
					$bCms_site = false;
					if (!empty($asso_list['XML']['children'])) {
						foreach ($asso_list['XML']['children'] as $nodeId => $nodeValue) {				
							if (strtolower(stripslashes($nodeValue["attrs"]["NAME"])) == "cms_site")
								$bCms_site = true;
						}
					}
			
					$cnt_associated = 0;
					$cnt_asso_total = 0;
					if (!empty($asso_list['list'])) {
						foreach($asso_list['list'] as $row) { 
							$displayValueShort = "";
							$abstractValueShort = "";
							//viewArray($row);
							if ($bCms_site == true && $classeName != "classe") {
								$temp_cms_site = $row[$foreignPrefixe.'_cms_site'];
								if ((isset($_SESSION['idSite_travail']) && $_SESSION['idSite_travail'] != "" &&  preg_match("/backoffice/msi", $_SERVER['PHP_SELF']) && $temp_cms_site == $_SESSION['idSite_travail']) || ($temp_cms_site == $idSite)) {
									// Only show direct asso link (fkey_1) if assymetric mode is active
									if ($tempAsymetric && ($row['fkey_1'] == $row['ref_id'] && $row['fkey_2'] == $id))
										continue;
						
									if (!empty($row['display']) && $row['display'] != '' && $row['display'] > -1) {
										$displayValueShort = substr($row['display'], 0, 50);
										if (strlen($row['display']) > 50 ) 
											$displayValueShort .= " ... ";
									}
									if (!empty($row['abstract']) && $row['abstract'] != '' && $row['abstract'] > -1) {
										$abstractValueShort = substr($row['abstract'], 0, 50);
										if (strlen($row['abstract']) > 50 ) 
											$abstractValueShort .= " ... ";
									}
									$asso_block .= $displayValueShort.(($displayValueShort != '' && $abstractValueShort != '') ? ' - ' : '').$abstractValueShort;
									if (isset($row['asso_statut']))
										$asso_block .= " - ".lib($row['asso_statut']);
									$asso_block .= "<br />\n";
									if ($classeName == "cms_tag" || $classeName == "cms_title" || $classeName == "cms_description")  {
										 $oClasse = new Classe ($row['fkey_2']);
										$sClasse = $oClasse->get_nom();
										
										// on teste si le tag est choisi pour des enregistrements particulier
										if ($row['fkey_3'] != -1) {
											eval ("$"."oClasse = new ".$sClasse." (".$row['fkey_3'].");");
											$asso_block.= "** ";
											//display
											eval ("$"."asso_block.=  $"."oClasse->get_".strval($oClasse->getDisplay())."();");
											$asso_block.= " - ";
											//abstract
											eval ("$"."asso_block.= $"."oClasse->get_".strval($oClasse->getAbstract())."();");
											$asso_block.= "<br />\n";
											$asso_block .= "<hr>\n";
										} else {
											$asso_block.= "** pour tous les enregistrements";
											$asso_block.= "<br />\n";
										}	
									}
									$cnt_associated++;	
								}								
							} else {
								// Only show direct asso link (fkey_1) if assymetric mode is active
								if ($tempAsymetric && ($row['fkey_1'] == $row['ref_id'] && $row['fkey_2'] == $id)) 
									continue;
						
								if (!$asso_list['asso']['full'])
									$asso_block .= '<a href="/backoffice/cms/'.$asso_list['asso']['class'].'/show_'.$asso_list['asso']['class'].'.php?id='.$row['ref_id'].'&amp;menuOpen=true">';
								// fkey of fkey_switch association (1,n)
								if (!empty($row['display']) && $row['display'] != '' && $row['display'] > -1) {
									$displayValueShort = substr($row['display'], 0, 50);
									if (strlen($row['display']) > 50 ) 
										$displayValueShort .= " ... ";
								}
								if (!empty($row['abstract']) && $row['abstract'] != '' && $row['abstract'] > -1) {
									$abstractValueShort = substr($row['abstract'], 0, 50);
									if (strlen($row['abstract']) > 50 ) 
										$abstractValueShort .= " ... ";
								}
								$asso_block .= $displayValueShort.(($displayValueShort != $abstractValueShort  ) ? (($displayValueShort != '' && $abstractValueShort != '') ? ' - ' : ''). $abstractValueShort : '');
								if (isset($row['asso_statut']) )
									$asso_block .= " - ".lib($row['asso_statut']);
								//elseif (isset($row['ref_statut']) )
								//	$asso_block .= " - ".lib($row['ref_statut']);
									
								if (isset($row['ordre']) )
									$asso_block .= "&nbsp;(".$row['ordre'].")";	
								
								if (!$asso_list['asso']['full'])
									$asso_block .= '</a>';

								$asso_block = "\n".$asso_block.'<br />';

								$cnt_associated++;	
							}
							$cnt_asso_total++;
						}
						 
						if ($cnt_associated > 12) {
							//echo "TEST : ".$tempClass."<br/>";
							$piled = "<script type=\"text/javascript\">
							document.toggle_".$tempClass."_Associations = function (_visible) {
								document.getElementById('toggleAsso_".$tempClass."_OFF').style.display = (_visible ? 'none' : 'block');
								document.getElementById('toggleAsso_".$tempClass."_ON').style.display = (_visible ? 'block' : 'none');
								document.getElementById('blockAsso_".$tempClass."_ON').style.display = (_visible ? 'block' : 'none');
								document.getElementById('blockAsso_".$tempClass."_OFF').style.display = (_visible ? 'none' : 'block');
							}
							</script>\n";
							$piled .= "<div id=\"toggleAsso_".$tempClass."_OFF\" style=\"display: block; float: right;\"><a href=\"#_\" onclick=\"toggle_".$tempClass."_Associations(true);\">".$translator->getTransByCode('Voir_les_associations')."</a></div>\n";
							$piled .= "<div id=\"toggleAsso_".$tempClass."_ON\" style=\"display: none; float: right;\"><a href=\"#_\" onclick=\"toggle_".$tempClass."_Associations(false);\">".$translator->getTransByCode('Masquer_les_associations')."</a></div>\n";
							$piled .= "<div id=\"blockAsso_".$tempClass."_ON\" style=\"display: none;\"><br/>\n".$asso_block."\n</div>\n";
							$piled .= "<div id=\"blockAsso_".$tempClass."_OFF\" style=\"display: block;\">\n".$cnt_associated." associé(e)(s) sur ".$cnt_asso_total." disponibles</div>\n";
							echo $piled;
						} elseif ($cnt_associated > 0)
							echo $asso_block;
						else	$translator->echoTransByCode('Aucune_association_en_cours');

					} else	$translator->echoTransByCode('Aucune_association_en_cours');

					// fin affichage asso sur table d'asso ----------------------		
	
					echo "</td>\n</tr>\n";
					echo "<table>\n";
				}
			}
		}
	}
}
?>