<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

$direct_call = false;
$exit_display = false;

$isOrderer = false;

if (empty($aNodeToSort)) {
	// AJAX or direct call
	// Added by Luc
	 
	$direct_call = true;
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

	// translation engine
	//if (DEF_APP_USE_TRANSLATIONS) {
		$translator =& TslManager::getInstance();
		$langpile = $translator->getLanguages();
	//} 
	if ($_GET['id'] =='') $_GET['id']  = -1;
	if (is_get('class') && is_get('id') && is_get('field')) { 
		// get an instance of currently displaying class
		if ($_GET['id'] == -1){
			eval("$"."oRes = new ".$_GET['class']."();");
		}
		else{
			eval("$"."oRes = new ".$_GET['class']."(".$_GET['id'].");");
		}
		if (!is_null($oRes->XML_inherited))
			$sXML = $oRes->XML_inherited;
		else	$sXML = $oRes->XML;
		$stack = xmlClassParse($sXML);

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
		if ($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]){ // cas d'asso
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
			 
			$aTempClasse = array();
			if ($aNodeToSort[$i]["attrs"]["ASSO"])
				$aTempClasse = explode(',', $aNodeToSort[$i]["attrs"]["ASSO"]);		
			elseif ($aNodeToSort[$i]["attrs"]["ASSO_EDIT"])
				$aTempClasse = explode(',', $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]);	

			if (!empty($aTempClasse)) {
				foreach ($aTempClasse as $assoc) {	

					$asso_block = '';
					$asso_block_checked = '';
					$asso_list = dbGetAssocies($oRes, $assoc, true);
					//pre_dump($asso_list); die();
					//pre_dump($asso_list);
					
					//viewArray($asso_list);
					if (!empty($asso_list['XML']["attrs"]["LIBELLE"]))
						$libelleAsso = stripslashes($asso_list['XML']["attrs"]["LIBELLE"]);
					else	$libelleAsso = $asso_list['XML']["attrs"]["NAME"];
					
			
					eval("$"."oTemp = new ".$assoc."();"); 
					$sXML = $oTemp->XML;
					xmlClassParse($sXML);
					$isOrderer = getItemByName($stack[0]["children"],'ordre');
					
					$tempAsso = $asso_list['asso']['class'];
					$tempAssoFull = $asso_list['asso']['full'];
					$tempAsymetric = $asso_list['asso']['asymetric'];
					$tempAssoPrefixe = $asso_list['asso']['prefix'];
					$tempAssoIn = $asso_list['asso']['in'];
					$tempAssoOut = $asso_list['asso']['out'];
					$tempAddItem = $asso_list['asso']['additem'];
					//pre_dump($asso_list);

					// display results
					echo "<table class='association'>\n";			
					echo "<tr>\n";
					echo "<td class='left_cell'>";
					echo $translator->getText($libelleAsso);
					echo "</td>\n";
					echo "<td class='right_cell'>\n";

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
					$asso_pile = array();
					if (!empty($asso_list['list'])) {
						//pre_dump($asso_list);
						foreach($asso_list['list'] as $row) {
							//viewArray($row, 'row');
							$displayValueShort = "";  
							if ($oTemp->getGetterStatut() != "none"){
								$tempStatus = $row['ref_statut'];
							} else	$tempStatus = DEF_ID_STATUT_LIGNE;

							if ($tempStatus == DEF_ID_STATUT_LIGNE) {
								$tempId = $row['ref_id'];
								if ($tempAssoFull && $tempAssoOut != ''){ // par table d'asso
									$asso_fld = "fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId;
								}
								else{ // ssans table d'asso, par fkey en reverse
									$asso_fld = "fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAsso)."_".$tempId;
								}
								
								if ($bCms_site == true && $classeName != "classe") { // class comportant un champs CMS SITE
									$temp_cms_site = $row[$foreignPrefixe.'_cms_site'];
                                                        	
									if ((isset($_SESSION['idSite_travail']) && $_SESSION['idSite_travail']!= "" &&  preg_match("/backoffice/si", $_SERVER['PHP_SELF']) && $temp_cms_site == $_SESSION['idSite_travail']) || ($temp_cms_site == $idSite)) {
										if ($operation != "INSERT" && $row['ref_fkey'] != -1)  {
											// only process while editing
											if (!$tempAssoFull && $row['ref_fkey'] == $id && $id != -1) {
												$asso_pile[$asso_fld]['checked'] = true;
												//echo ' 1 +++ ';
												$cnt_associated++;
											} elseif ($row['fkey_1'] == $id && $row['fkey_2'] == $row['ref_id'] && $id != -1) {
												$asso_pile[$asso_fld]['checked'] = true;
												//echo ' 2 +++ ';
												$cnt_associated++;
											} elseif (!$tempAsymetric && $row['fkey_1'] == $row['ref_id'] && $row['fkey_2'] == $id && $id != -1) {
												$asso_pile[$asso_fld]['checked'] = true;
												//echo ' 3 +++ ';
												$cnt_associated++;
											}						
										}
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
										$asso_pile[$asso_fld]['id'] = $tempId;
										$asso_pile[$asso_fld]['display'] = $displayValueShort.(($displayValueShort != '' && $displayValueShort != '') ? ' - ' : '').$abstractValueShort;
										if (isset($row['asso_statut']))
											$asso_pile[$asso_fld]['display'] .= " - ".lib($row['asso_statut']);
										
										$cnt_asso_total++;
									}				

								} else {	// class ne comportant PAS un champs CMS SITE		
												
									if ($operation != "INSERT" && $row['ref_fkey'] != -1)  {																			
										//echo 'only process while editing<br>';		
										if (!$tempAssoFull && $row['ref_fkey'] == $id  && $id != -1) {
											$asso_pile[$asso_fld]['checked'] = true;
											//echo ' 1 +++ ';
											$cnt_associated++;
										} elseif ($row['fkey_1'] == $id && $row['fkey_2'] == $row['ref_id']  && $id != -1) {
											$asso_pile[$asso_fld]['checked'] = true;
											//echo ' 2 +++ ';
											$cnt_associated++;
										} elseif (!$tempAsymetric && $row['fkey_1'] == $row['ref_id'] && $row['fkey_2'] == $id  && $id != -1) {
											$asso_pile[$asso_fld]['checked'] = true;
											//echo ' 3 +++ ';
											$cnt_associated++;
										}	
									}
									else{ // UPDATE
										if (!$tempAssoFull && is_get('idObject')	&&	($row['ref_id'] ==$_GET['idObject'])){
											$asso_pile[$asso_fld]['checked'] = true;
											//echo ' 4 +++ ';
											$cnt_associated++;
										}
										
										
									}
									
									if (!empty($row['display']) && $row['display'] != '' && $row['display'] > -1) {
										$displayValueShort = substr($row['display'], 0, 50);
										if (strlen($row['display']) > 50 ) 
											$displayValueShort .= " ... ";
									}
									$abstractValueShort = "";
									if (!empty($row['abstract']) && $row['abstract'] != '' && $row['abstract'] > -1) 
										(preg_match('/\/'.$_SESSION["rep_travail"].'\//si', $row['abstract']))  ? $abstractValueShort = str_replace ("/".$_SESSION["rep_travail"]."", "", $row['abstract']) : ((strlen($row['abstract']) > 50 ) ? $abstractValueShort = substr($row['abstract'], 0, 50)."..." : $abstractValueShort = substr($row['abstract'], 0, 50));
									$asso_pile[$asso_fld]['id'] = $tempId;
									$asso_pile[$asso_fld]['display'] = $displayValueShort.(($displayValueShort != $abstractValueShort  ) ? (($displayValueShort != '' && $abstractValueShort != '') ? ' - ' : ''). $abstractValueShort : '');
									if (isset($row['asso_statut']))
										$asso_pile[$asso_fld]['display'] .= " - ".lib($row['asso_statut']);
										
									$cnt_asso_total++;
								}			
								
								
								
								if ($isOrderer ) { 
									//viewArray($row, 'row'); 									 
									if (isset($row['ordre']) && $row['ordre']!= '') { 
										if ($row['fkey_1'] == $id || $row['fkey_2'] == $id) {
											$asso_pile[$asso_fld]['ordre'] = $row['ordre']; 
										}
									} 									 
								}								
							}							
						}
					}
					
					$cnt_asso_total = sizeof($asso_pile);
					
					if ($tempAssoFull && $tempAssoOut != ''){ // par table d'asso
						$idPlus = "Asso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut);
					}
					else{ // ssans table d'asso, par fkey en reverse
						$idPlus = "Asso".ucfirst($tempAssoIn)."_".ucfirst($tempAsso);
					}
					//viewArray($asso_pile, 'pile');
															
					//$idPlus = preg_replace('/^f(.+)_[0-9]+$/', '$1', array_shift(array_keys($asso_pile)));
					?>
					<script type="text/javascript">
					 $(document).ready(function(){				
						$(".<?php echo $idPlus; ?>").fancybox({
							'padding'		  : 0,
							'width'			  : 800,
							'height'		  : 600,
							'scrolling'       : 'no',
							'showCloseButton' : true,
							'titleShow'       : false,
							'transitionIn'	  : 'elastic',
							'transitionOut'	  : 'elastic' 
						});	
					});	
					</script>
					<?php
					if ($tempAssoOut!=''){
						$isCms = isCmsClass($tempAssoOut); 
						if (preg_match('/^ss3_/si', $tempAssoOut)){
							$ifUrl = '/backoffice/adss/'.$tempAssoOut.'/maj_'.$tempAssoOut.'.php?id=-1&noMenu=true&refClass='.$classeName.'&refId='.$id;
							$ifUrl_ = '/backoffice/adss/'.$tempAssoOut.'/maj_'.$tempAssoOut.'.php';	
						}
						elseif ($isCms){
							$ifUrl = '/backoffice/cms/'.$tempAssoOut.'/maj_'.$tempAssoOut.'.php?id=-1&noMenu=true&refClass='.$classeName.'&refId='.$id;
							$ifUrl_ = '/backoffice/cms/'.$tempAssoOut.'/maj_'.$tempAssoOut.'.php';	
						}
						else{
							$ifUrl = '/backoffice/'.$tempAssoOut.'/maj_'.$tempAssoOut.'.php?id=-1&noMenu=true&refClass='.$classeName.'&refId='.$id;
							$ifUrl_ = '/backoffice/'.$tempAssoOut.'/maj_'.$tempAssoOut.'.php';	
						}
					}
					else{
						$isCms = isCmsClass($tempAsso); 
						if (preg_match('/^ss3_/si', $tempAsso)){
							$ifUrl = '/backoffice/adss/'.$tempAsso.'/maj_'.$tempAsso.'.php?id=-1&noMenu=true&refClass='.$classeName.'&refId='.$id;
							$ifUrl_ = '/backoffice/adss/'.$tempAsso.'/maj_'.$tempAsso.'.php';	
						}
						elseif ($isCms){
							$ifUrl = '/backoffice/cms/'.$tempAsso.'/maj_'.$tempAsso.'.php?id=-1&noMenu=true&refClass='.$classeName.'&refId='.$id;
							$ifUrl_ = '/backoffice/cms/'.$tempAsso.'/maj_'.$tempAsso.'.php';	
						}
						else{
							$ifUrl = '/backoffice/'.$tempAsso.'/maj_'.$tempAsso.'.php?id=-1&noMenu=true&refClass='.$classeName.'&refId='.$id;
							$ifUrl_ = '/backoffice/'.$tempAsso.'/maj_'.$tempAsso.'.php';	
						}
					}

					if ($classeName == 'cms_diaporama'){ // cas particulier diaporama
						$ifUrl .= '&addToDiaporama='.$id;					
					} 
					
					if (is_file($_SERVER['DOCUMENT_ROOT'].$ifUrl_) && $tempAddItem){					
						echo '<a href="#'.$idPlus.'_plus" id="a'.$idPlus.'" class="arbo '.$idPlus.' picto_add" onclick="loadIframe(\''.$idPlus.'\')">'.$translator->getTransByCode('ajouterunitem').'</a>'."\n";
					}
					
					echo '<div style="display: none;">'."\n";
					echo '<div id="'.$idPlus.'_plus" style="width:800px; height:600px">'."\n";					
					
					echo '<input type="hidden" id="ifUrl'.$idPlus.'" name="ifUrl'.$idPlus.'" value="'.$ifUrl.'" />';
					echo '<iframe id="if'.$idPlus.'" name="if'.$idPlus.'" width="100%" height="100%" ></iframe>';
					?>								
					</div>										
					</div>	
					<?php
					
					$idTous = preg_replace('/^(.+)_[0-9]+$/', '$1', array_shift(array_keys($asso_pile)));
					$asso_block .= "<div class='check_all'><input type=\"checkbox\" name=\"".$idTous."\" id=\"".$idTous."\" value=\"\" onchange=\"toggleTous(this);\" /> <label for='".$idTous."'>".$translator->getTransByCode('tous')."</label></div>"; 
					
					
					// gestion des nouvelles associations passées en GET					
					$aNewGet = array();
					
					if (is_get("idObject") && $_GET["idObject"]!= "undefined" && is_get("key") && ($_GET["key"] == $tempAssoOut || $_GET["key"] == $tempAssoIn)) {						 
						//unset ($_SESSION["AWS_".$assoc."_idObject"]);
						//echo "AWS_".$assoc."_idObject"; 
						//pre_dump($_SESSION["AWS_".$assoc."_idObject"]);
						$idObject = $_GET["idObject"];
						
						if (!isset ($_SESSION["AWS_".$assoc."_idObject"])) {
							
							$_SESSION["AWS_".$assoc."_idObject"] = array();	
						}
						else {
							$aNewGet = $_SESSION['AWS_'.$assoc.'_idObject']; 	
						}
						array_push ( $aNewGet , $idObject);
						$_SESSION['AWS_'.$assoc.'_idObject'] = $aNewGet;						 
					}									 
					
					foreach ($asso_pile as $fld => $association) {	
									
						$asso_block .= "<div class='field_association class_clear' id='".str_replace('fAsso', '', $fld)."'><input type=\"checkbox\" name=\"".$fld."\" id=\"".$fld."\" value=\"".$association['id']."\" ";
						if ($association['checked']) {
							$asso_block .= ' checked="true"';
					}
						$asso_block .= "  onclick='check_".$assoc."_ONOFF(1);' > ".strip_tags($association['display']); 
						
						if ($association['checked'] || in_array ($association['id'], $aNewGet)  ) {
							$asso_block_checked .= "<div id='".str_replace('fAsso', '', $fld)."' class=\"class_clear\"><input type=\"checkbox\" name=\"".$fld."\" id=\"".$fld."\" value=\"".$association['id']."\" "; 
							$asso_block_checked .= ' checked="true"';
							$asso_block_checked .= " onclick='check_".$assoc."_ONOFF(0);' > <label for='".$fld."'>".strip_tags($association['display']); 
							if ($tempAddItem) $asso_block_checked .= '</label> <a id="'.preg_replace('/^(f)/si', 'a', $fld).'" class="arbo '.$idPlus.'" href="#'.$idPlus.'_plus" onclick="editAssoItem(\''.$fld.'\')" class="picto_edit"><img src="/backoffice/cms/img/2013/icone/modifier.png" /></a>';
						}						
						
						if ($isOrderer )  {
							//print_r($isOrderer); 
							//pre_dump($association);
							if ($association['ordre'] == '') $association['ordre'] = 0;
							$asso_block .= "<img src=\"/backoffice/cms/img/2013/icone/sort.png\" alt=\"Sort\" title=\"Ordre\" style=\"float: right;\" /> <input type=\"text\" name=\"".$fld."_ordre\" id=\"".$fld."_ordre\" class=\"ordre_field\" value=\"".$association['ordre']."\" size='1' style=\"float: right;\" />";
							if ($association['checked'] || in_array ($association['id'], $aNewGet) ) $asso_block_checked .= "<img src=\"/backoffice/cms/img/2013/icone/sort.png\" alt=\"Sort\" title=\"Ordre\" style=\"float: right;\" /> <input type=\"text\" name=\"".$fld."_ordre\" id=\"".$fld."_ordre\" class=\"ordre_field\" value=\"".$association['ordre']."\" size='1' style=\"float: right;\"/>";
						}	
						
						if ($tempAddItem) $asso_block .= ' <a id="'.preg_replace('/^(f)/si', 'a', $fld).'" class="arbo '.$idPlus.'" href="#'.$idPlus.'_plus" onclick="editAssoItem(\''.$fld.'\')" class="picto_edit"><img src="/backoffice/cms/img/2013/icone/modifier.png" /></a>';
												
						//$asso_block .= "<br />\n"; 
						if ($association['checked'] || in_array ($association['id'], $aNewGet) ) $asso_block_checked .= "</div>\n"; 
						$asso_block .= '</div>';
					}
					
					$piled = "<script type=\"text/javascript\">
					document.toggle_".$assoc."_Associations = function (_visible) {
						document.getElementById('toggleAsso_".$assoc."_OFF').style.display = (_visible ? 'none' : 'block');
						document.getElementById('toggleAsso_".$assoc."_ON').style.display = (_visible ? 'block' : 'none');
						document.getElementById('blockAsso_".$assoc."_ON').style.display = (_visible ? 'block' : 'none');
						document.getElementById('blockAsso_".$assoc."_OFF').style.display = (_visible ? 'none' : 'block');
						//document.getElementById('fAssoTnt_spectacle_Tnt_spectacletype').style.display = (_visible ? 'block' : 'none');
						//document.getElementById('fAssoTnt_spectacle_Tnt_spectacletype').style.display = (_visible ? 'none' : 'block');
						
						if (!_visible) {
							// il faudrait recharger la contenu du OFF
						}						
					}					
					
					document.check_".$assoc."_ONOFF = function (onoff) { 					 	 
						if (onoff == 0) { 
							$('#blockAsso_".$assoc."_OFF').find('input').each ( function () { 
								monid =  $(this).attr('id');
								$('#blockAsso_".$assoc."_ON #'+monid+'').attr('checked', $(this).is(':checked'));
							});
						}
						else {
							$('#blockAsso_".$assoc."_ON').find('input').each ( function () { 
								monid =  $(this).attr('id');
								$('#blockAsso_".$assoc."_OFF #'+monid+'').attr('checked', $(this).is(':checked'));
							});
						}
					}	
	
					</script>\n";
					$piled .= "<div id=\"toggleAsso_".$assoc."_OFF\" style=\"display: block; float: right;\"><a href=\"#_\" onclick=\"toggle_".$assoc."_Associations(true);\" class='picto_view'>".$translator->getTransByCode('Voir_les_associations')."</a></div>\n";
					$piled .= "<div id=\"toggleAsso_".$assoc."_ON\" style=\"display: none; float: right;\"><a href=\"#_\" onclick=\"toggle_".$assoc."_Associations(false);\" class='hide picto_hide'>".$translator->getTransByCode('masquerlesassos')."</a></div>\n";
					$piled .= "<div id=\"blockAsso_".$assoc."_ON\" style=\"display: none;\" class=\"class_clear\">\n".$asso_block."\n</div>\n";
					//$piled .= "<div id=\"blockAsso_".$assoc."_OFF\" style=\"display: block;\"><br/>\n".$cnt_associated." ".$translator->getTransByCode('associations_sur_un_total_de')." ".$cnt_asso_total."</div>\n";
					$piled .= "<div id=\"blockAsso_".$assoc."_OFF\" style=\"display: block;\" class=\"class_clear\">".$asso_block_checked."</div>\n";
					echo $piled;					
					

			
				echo "</td>\n</tr>\n";
				echo "<table>\n";
				}
			}
		}
	}
}
?>