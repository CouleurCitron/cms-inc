<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if ($bStatusControl){
	include('cms-inc/autoClass/list.statut.php');
}

// Custom list global actions
if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/a/', $customActionControl[$_SESSION['rank']])) {
	if (!empty($aListCustom)) {
		foreach ($aListCustom as $custom) {
			$activate_custom = false;
			if (!empty($custom['Filter'])) {
				if (empty($custom["Filter"]['mode']))
					$custom["Filter"]['mode'] = 'AND';
				$test_res = Array();
				foreach ($custom['Filter']['pile'] as $filter) {
					if (isset($filter['getter'])) {
						eval("$"."curent_val = "."$"."oRes->".$filter['getter']."();");
						switch ($filter['test']) {
							case 'equals'		:	if ($curent_val == $filter['value'])
												$test_res[] = true;
											break;
							case 'differ'		:	if ($curent_val != $filter['value'])
												$test_res[] = true;
											break;
							case 'lower_than'	:	if ($curent_val < $filter['value'])
												$test_res[] = true;
											break;
							case 'higher_than'	:	if ($curent_val > $filter['value'])
												$test_res[] = true;
											break;
							case 'lower_or_equals'	:	if ($curent_val <= $filter['value'])
												$test_res[] = true;
											break;
							case 'higher_or_equals'	:	if ($curent_val >= $filter['value'])
												$test_res[] = true;
											break;
						}
					}
				}
				if ($custom["Filter"]['mode'] == 'AND' && sizeof($test_res) == sizeof($aCustom['Filter']['pile']))
					$activate_custom = true;
				elseif ($custom["Filter"]['mode'] == 'OR' && sizeof($test_res) > 0)
					$activate_custom = true;
			} else	$activate_custom = true;
			if ($activate_custom) {
				// ##id## => id
				$search = array("##classePrefixe##", "##classeName##", "##id##");
				$replace = array($classePrefixe, $classeName, $oRes->get_id());
				echo str_replace($search, $replace, $custom["Action"]);
			}
		}
	}
}


$rand = rand();
?>
<table border="0" align="center" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="<?php echo 'sorter'.$rand;?> tablesorter" width="100%" id="sortable" style="float:left">
<thead>
<tr class="col_titre">
<?php
if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/a/', $customActionControl[$_SESSION['rank']])) {
	if (($bStatusControl || !empty($aListCustom)) && isAllowed ($rankUser, "ADMIN;GEST")){
?>
<th align="center" nowrap>&nbsp;
	<input type="checkbox" name="user-all" id="user-all" title="<?php $translator->echoTransByCode('cocherdecochertout') ?>" onclick="toggleAll('cb_');" />
</th>
<?php
	}
}
?>
 <th align="center" nowrap>&nbsp;<b>Actions</b>&nbsp;</th>
<?php
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if ($aNodeToSort[$i]["attrs"]["SKIP"] == 'true'){
			// skip it !
		}
		elseif (($aNodeToSort[$i]["attrs"]["LIST"] == "true")||(($_SESSION['rank'] == 'ADMIN')&&($aNodeToSort[$i]["attrs"]["NAME"]=='id'))){
			echo "<th align=\"center\" nowrap>&nbsp;<strong>";
			if (isset($aNodeToSort[$i]["attrs"]["ORDER"]) && ($aNodeToSort[$i]["attrs"]["ORDER"] == "true") || $aNodeToSort[$i]["attrs"]["NAME"] == "id")
				echo "<a href=\"javascript:doTri('".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]."')\" >";			
			if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != ""))
				echo $translator->getText(stripslashes($aNodeToSort[$i]["attrs"]["LIBELLE"]));
			else	echo $translator->getText($aNodeToSort[$i]["attrs"]["NAME"]);
			if (isset($aNodeToSort[$i]["attrs"]["ORDER"]) && ($aNodeToSort[$i]["attrs"]["ORDER"] == "true") || $aNodeToSort[$i]["attrs"]["NAME"] == "id")
				echo "</a>";	
				
			echo '</strong>';
			echo '</th>';	
		}
	}
}
// on ajoute l'ordre dans la page
if(isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage')){
	echo "<th align=\"center\" nowrap>
		&nbsp;<strong>
			ORDRE
		</strong>";
}
//echo "</b>&nbsp;</th>\n";
?>
</tr>
<?php
// texte ddans le cas de la classe bo_users
// n'affiche pas user CCitron si username courant <> ccitron
if ($classeName == "bo_users") {
	$aListe_res_temp = array();
	for($k=0; $k<sizeof($aListe_res); $k++) {
		$oResTemp = $aListe_res[$k];
		if ($_SESSION['user']!="ccitron" && $oResTemp->nom == "ccitron") {
		
		}
		else {
			$aListe_res_temp[] = $aListe_res[$k];
		}
	}
	$aListe_res = array();
	for($k=0; $k<sizeof($aListe_res_temp); $k++) {
		
		$aListe_res[] = $aListe_res_temp[$k];
	}
}
if(isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage'))
	echo '</thead><tbody class="sortablecontent" style="cursor:pointer;" rel="sorter'.$rand.'">';
else
	echo '</thead><tbody>';
for($k=0; $k<sizeof($aListe_res); $k++) {
	$oRes = $aListe_res[$k];
?>
<tr  id="myItem_sorter<?php echo $rand;?>_<?php echo $k;?>">
<?php
	if(isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage')){
		echo '<input type="hidden" id="_sorter'.$rand.'_sortableid_'.$k.'" value="'.$aAssoObjets[$k]->get_id().'">';
	}
	
	if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/a/', $customActionControl[$_SESSION['rank']])) {
		if (($bStatusControl || !empty($aListCustom)) && isAllowed ($rankUser, "ADMIN;GEST")){	
?>
	<td align="center" nowrap class="arbo" >
		&nbsp;<input type="checkbox" class="record_check" name="cb_<?php echo ucfirst($classePrefixe); ?>_<?php echo $oRes->get_id(); ?>" id="cb_<?php echo ucfirst($classePrefixe); ?>_<?php echo $oRes->get_id(); ?>" value="<?php echo $oRes->get_id(); ?>" class="arbo" onClick="sel('<?php echo strtolower($classePrefixe); ?>_list_form','cb_<?php echo ucfirst($classePrefixe); ?>_<?php echo $oRes->get_id(); ?>','ligne<?php echo $k; ?>')" />
	</td>
<?php
		}
	}
?>
	<td align="left" nowrap class="arbo" style="padding-left:15px;">   
<?php
	//visu link
	if (!isset($visuLink))
		$visuLink = getUILink($classeName, 'show');


	if ($_SESSION['login'] == 'ccitron' || $visuLink && (empty($customActionControl) || preg_match('/r/', $customActionControl[$_SESSION['rank']]))) {
		if (isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage'))
			echo '<a href="#_" onclick="fancy_reuse(\''.$visuLink.'?id='. $oRes->get_id().'\')"' ;
		else	echo '<a href="'.$visuLink.'?id='. $oRes->get_id() ;
	
		if ($_SERVER['QUERY_STRING']!="") 
			echo "&".str_replace("id=", "idprev=",preg_replace('/idprev=[^&]*&/msi', '', $_SERVER['QUERY_STRING']));
		echo '" title="'.$translator->getTransByCode('Visualiser').'" id="actionVisu"><img src="/backoffice/cms/img/visualiser.gif" border="0" alt="'.$translator->getTransByCode('Visualiser').'" align="top" /></a>&nbsp;';
	}

	//edit link
	if (!isset($editLink))
		$editLink = getUILink($classeName, 'maj');
	
	
	// edit
	if($editLink && ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/e/', $customActionControl[$_SESSION['rank']]))) {
		if (isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage'))
			echo '<a href="#_" onclick="fancy_reuse(\''.$editLink.'?id='. $oRes->get_id().'\')"' ;
		else	echo '<a href="'.$editLink.'?id='. $oRes->get_id();
	
		if ($_SERVER['QUERY_STRING']!="")
			echo "&".str_replace("id=", "idprev=",preg_replace('/idprev=[^&]*&/msi', '', $_SERVER['QUERY_STRING']));
		echo '" title="'.$translator->getTransByCode('Modifier').'" id="actionModif"><img src="/backoffice/cms/img/modifier.gif" border="0" alt="'.$translator->getTransByCode('Modifier').'" align="top" /></a>&nbsp;';
	}
	// edit as new
	if($editLink && ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/c/', $customActionControl[$_SESSION['rank']]))){
		if(isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage'))
			echo '<a href="#_" onclick="fancy_reuse(\''.$editLink.'?id='. $oRes->get_id().'&newid=-1\')"' ;
		else	echo '<a href="'.$editLink.'?id='. $oRes->get_id().'&newid=-1';
	
		if($_SERVER['QUERY_STRING']!="")
			echo "&".str_replace("id=", "idprev=",preg_replace('/idprev=[^&]*&/msi', '', $_SERVER['QUERY_STRING']));
		echo '" title="'.$translator->getTransByCode('Dupliquer').'" id="actionDupli"><img src="/backoffice/cms/img/dupliquer.gif" border="0" alt="'.$translator->getTransByCode('Dupliquer').'" align="top" /></a>&nbsp;';
	}
	
	if (isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage')){
		//bouton permettant de "d�lier" l'association
		echo '<a href="javascript:unlinkEmp('.$aAssoObjets[$k]->get_id().')" title="'.$translator->getTransByCode('delier_l_enregistrement').'" id="actionDelier"><img src="/backoffice/cms/img/supprimer.gif" border="0" alt="'.$translator->getTransByCode('delier_l_enregistrement').'" align="top" /></a>&nbsp;';
	}
	//delete link
	if ($bDeleteButtonControl || (!empty($customActionControl) && preg_match('/d/', $customActionControl[$_SESSION['rank']]))) {	
		echo '<a href="javascript:deleteEmp('.$oRes->get_id().')" title="'.$translator->getTransByCode('Suppression_de_l_enregistrement').'" id="actionDel"><img src="/backoffice/cms/img/supprimer.gif" border="0" alt="'.$translator->getTransByCode('Suppression_de_l_enregistrement').'" align="top" /></a>&nbsp;';
	}
	if ($bSelectControl) {
?>
	<center>
		<button type="button" onclick="JavaScript:reuseThisOne(<?php echo $oRes->get_id(); ?>);">
			<?php echo $translator->getText('S�lectionner'); ?>
		</button> 
	</center>
<?php
	}
	//Bouton custom permettant de lier un objet m�tier existant � la page
	if(isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage')){
		$linkReuse = '/backoffice/cms/page_infos_reuse.php?className='.$xClassName.'&idPage='.$oPage->get_id().'&nodeId='.$oPage->get_nodeid_page().'&classId='.$xClassId.'&usedId='.$aAssoObjets[$k]->get_id();
		$aCustom["Action"] = '&nbsp;<a href="#_" onclick="javascript:fancy_reuse(\''.$linkReuse.'\');" title="Utiliser existant"><img src="/backoffice/cms/img/propriete.gif" width="16" height="16" alt="Utiliser existant" border="0" /></a>';
	}	
						
	
	if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/a/', $customActionControl[$_SESSION['rank']])) {
		if (!empty($custom_local_actions)) {
			foreach ($custom_local_actions as $custom) {
				$activate_custom = false;
				if (!empty($custom['Filter'])) {
					if (empty($custom["Filter"]['mode']))
						$custom["Filter"]['mode'] = 'AND';
					$test_res = Array();
					foreach ($custom['Filter']['pile'] as $filter) {
						if (isset($filter['getter'])) {
							eval("$"."curent_val = "."$"."oRes->".$filter['getter']."();");
							switch ($filter['test']) {
								case 'equals'		:	if ($curent_val == $filter['value'])
													$test_res[] = true;
												break;
								case 'differ'		:	if ($curent_val != $filter['value'])
													$test_res[] = true;
												break;
								case 'lower_than'	:	if ($curent_val < $filter['value'])
													$test_res[] = true;
												break;
								case 'higher_than'	:	if ($curent_val > $filter['value'])
													$test_res[] = true;
												break;
								case 'lower_or_equals'	:	if ($curent_val <= $filter['value'])
													$test_res[] = true;
												break;
								case 'higher_or_equals'	:	if ($curent_val >= $filter['value'])
													$test_res[] = true;
												break;
							}
						}
					}
					if ($custom["Filter"]['mode'] == 'AND' && sizeof($test_res) == sizeof($custom['Filter']['pile']))
						$activate_custom = true;
					elseif ($custom["Filter"]['mode'] == 'OR' && sizeof($test_res) > 0)
						$activate_custom = true;
				} else	$activate_custom = true;
				if ($activate_custom) {
					// ##id## => id
					$search = array("##classePrefixe##", "##classeName##", "##id##");
					$replace = array($classePrefixe, $classeName, $oRes->get_id());
					echo str_replace($search, $replace, $custom["Action"]);
				}
			}
		}
	}
?>
</td>
<?php   
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){
			if ($aNodeToSort[$i]["attrs"]["SKIP"] == 'true'){
				// skip it !
			}
			elseif ($aNodeToSort[$i]["attrs"]["LIST"] == "true" || ($_SESSION['rank'] == 'ADMIN' && $aNodeToSort[$i]["attrs"]["NAME"]=='id')) {
				echo "<td align=\"center\" class=\"arbo\">&nbsp;";
				$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
				$eKeyValue = strip_tags($eKeyValue);
				//echo $eKeyValue;
				if (!empty($aNodeToSort[$i]["attrs"]["ANONYMOUS"])){ // cas anonymous
					$fld_check = 'get_'.$aNodeToSort[$i]["attrs"]["ANONYMOUS"];
					if ($oRes->$fld_check() == 'Y') {
						if ($_SESSION['login'] != 'ccitron') {
							echo "*** ".$translator->getText("anonyme")." ***";
							continue;
						} else	echo "[".$translator->getText("anonyme")."] ";
					}
				} // fin cas anonymous
				if ($aNodeToSort[$i]["attrs"]["FKEY"] || $aNodeToSort[$i]["attrs"]["FKEY_SWITCH"]) {			
					// cas de foreign key
					if ($aNodeToSort[$i]["attrs"]["FKEY_SWITCH"]) {
						// switchable fkey
						// find type switch field
						$found = false;
						for ($j=0; $j<count($aNodeToSort); $j++) {
							if ($aNodeToSort[$j]["name"] == "ITEM" && $aNodeToSort[$j]["attrs"]["NAME"] == $aNodeToSort[$i]["attrs"]["FKEY_SWITCH"]) {
								foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode) {
									if ($childNode["name"] == "OPTION" && $childNode["attrs"]["TYPE"] == getItemValue($oRes, $aNodeToSort[$j]["attrs"]["NAME"])) {
										$sTempClasse = $childNode["attrs"]["TABLE"];
										$found;
										break;
									}
								}
							}
							if ($found)
								break;
						}
					} else
						// standard fkey
						$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
						
						include('show.fkey.php');
	
					
					// end fkey display in record list
	
				}// fin fkey
				elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum		
					if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "value"){
									if (trim($eKeyValue) == trim($childNode["attrs"]["VALUE"])){							
										echo stripslashes($childNode["attrs"]["LIBELLE"]);
										break;
									}
								} //fin type  == value				
							}
						}
					}		
				} // fin cas enum
				else{ // cas typique
					if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){ // cas statut	
						if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
							foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
								if($childNode["name"] == "OPTION"){ // on a un node d'option				
									if ($childNode["attrs"]["TYPE"] == "value"){
										if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
											echo $translator->getText($childNode["attrs"]["LIBELLE"]);
											break;
										}
									} //fin type  == value				
								}
							}
						} // if nodes children
						else{	
							//echo lib($eKeyValue);
							$translator->echoTransByCode('statut'.$eKeyValue);
						}
					}
					else{
						if ($eKeyValue != -1){ // cas typique typique
							if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file							
								$aFiles = explode(';', $eKeyValue);
								for($if=0;$if<count($aFiles);$if++){
									if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$aFiles[$if])){
										echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$aFiles[$if]."\" target=\"_blank\" title=\"".$translator->getTransByCode('visualiserlefichier')." '".$aFiles[$if]."'\">".$aFiles[$if]."</a>\n";
										echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$aFiles[$if]."\" title=\"".$translator->getTransByCode('telechargerlefichier')." '".$aFiles[$if]."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"".$translator->getTransByCode('telechargerlefichier')." '".$aFiles[$if]."\" /></a>\n";
									}
								}
							}							
							else if ($aNodeToSort[$i]["attrs"]["OPTION"] == "bool"){ // boolean
								if (intval($eKeyValue) == 1){
									echo "oui";
								}
								else{
									echo "non";
								}						
							}
							elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "link"){ // cas link		
								if (DEF_APP_USE_TRANSLATIONS && $aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference') {
									$refKeyValue = $eKeyValue;
									$eKeyValue = $translator->getByID($refKeyValue);
									if ($eKeyValue==''){ // cas pas de traduc pour la langue en cours
										foreach($translator->getActiveLangIds() as $kL => $IdL){ // on cherche dans toutes les langues
											$eKeyValue = $translator->getByID($refKeyValue, $IdL);
											if ($eKeyValue!=''){
												break;
											}
										}
									}	
								}
								
								$eKeyValueShort = substr($eKeyValue, 0, 50);
								if (strlen($eKeyValue) > 50 ) 
									$eKeyValueShort.= " ... ";		
								echo "<a href=\"".$eKeyValue."\" target=\"_blank\" title=\"Lien �dit�\">".$eKeyValueShort."</a><br />\n";					
							}	
							elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "node"){ // cas node	
							
								if (DEF_APP_USE_TRANSLATIONS && $aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference') {
									$refKeyValue = $eKeyValue;
									$eKeyValue = $translator->getByID($refKeyValue);
									if ($eKeyValue==''){ // cas pas de traduc pour la langue en cours
										foreach($translator->getActiveLangIds() as $kL => $IdL){ // on cherche dans toutes les langues
											$eKeyValue = $translator->getByID($refKeyValue, $IdL);
											if ($eKeyValue!=''){
												break;
											}
										}
									}	
								}
								if (getCount_where("cms_arbo_pages", array("node_id"), array($eKeyValue), array("NUMBER")) ==  1){
									if (getNodeInfos($db, $eKeyValue)){
										$infosNode = getNodeInfos($db, $eKeyValue);
										$eKeyValue = $infosNode["path"];
									}
									else {
										$eKeyValue = "n/a";
									}
								}
								else {
									$eKeyValue = "n/a";
								}
								echo $eKeyValue;	
							}						
							elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp"){ // cas timestamp	
								echo timestampFormat($eKeyValue);
							}
							elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "datetime"){ // cas datetime	
								echo timestampFormat($eKeyValue);
							}
							// translation data
							// Added by Luc - 13 oct. 2009
							elseif (DEF_APP_USE_TRANSLATIONS && $aNodeToSort[$i]["attrs"]["TRANSLATE"]) {
								if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") {
									if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference'){
										$refKeyValue = $eKeyValue;
										$eKeyValue = $translator->getByID($refKeyValue);
										if ($eKeyValue==''){ // cas pas de traduc pour la langue en cours
											foreach($translator->getActiveLangIds() as $kL => $IdL){ // on cherche dans toutes les langues
												$eKeyValue = $translator->getByID($refKeyValue, $IdL);
												if ($eKeyValue!=''){
													break;
												}
											}
										}
									}
								} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") {
								 	if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == "value"){
										$refKeyValue = $eKeyValue;
								 		$eKeyValue = $translator->getText($refKeyValue) ;
										if ($eKeyValue==''){ // cas pas de traduc pour la langue en cours
											$eKeyValue = $refKeyValue; // on retourne la chaine de r�f�rence
										}
									}
								} else	echo "Error - Translation engine can not be applied to <b><i>".$aNodeToSort[$i]["attrs"]["TYPE"]."</i></b> type fields !!";
								echo substr(html2text($eKeyValue), 0, 120);
								if (strlen($eKeyValue) > 120 ) 
									echo " ... ";	
							}
							elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "color"){ // RVB							
								if (preg_match('/^#[0-9A-F]{6}$/msi', $eKeyValue)==1){
									echo $eKeyValue.'<div style="width:55px; height:12px; background-color:'.$eKeyValue.'"></div>';
								}
								else{
									echo 'n/a';
								}						
							}
							// end translation data
							else {
								// cas typique typique typique
								if (isset($aNodeToSort[$i]["attrs"]["TRUNCATE"]) && $aNodeToSort[$i]["attrs"]["TRUNCATE"] > 0)
									$truncate = intval($aNodeToSort[$i]["attrs"]["TRUNCATE"]);
								else	$truncate = 50;
								echo substr($eKeyValue, 0, $truncate);
								if (strlen($eKeyValue) > $truncate) 
									echo " ... ";	
							}
						}
						else{
							echo "n/a";
						}
					}
				}
				echo "&nbsp;</td>\n";
			}
		}
		
	}
	//on ajoute l'ordre 'global' � la plage uniquement si nous sommes l'objet est de type 'cms_assoclassepage'
	if(isset($aAssoObjets[0]) && is_a($aAssoObjets[0],'cms_assoclassepage')){
		echo '<td class="arbo" id="_sorter'.$rand.'_sortableorder_'.$k.'" align="center">&nbsp;'.($aAssoObjets[$k]->get_order()).'&nbsp;</td>';
	}
?>
</tr>
<?php
}
?>
</tbody>
</table>
<script>
$(document).ready(function() { 
    // call the tablesorter plugin 
    $(".<?php echo 'sorter'.$rand;?>").tablesorter({ 
        // pass the headers argument and assing a object 
        headers: { 
            // assign the secound column (we start counting zero) 
			<?php
				if ($bStatusControl && isAllowed ($rankUser, "ADMIN;GEST")){
				?>

					0: { 
						// disable it by setting the property sorter to false 
						sorter: false 
					},
					1: { 
						// disable it by setting the property sorter to false 
						sorter: false 
					}
				<?php
				}else{
				?>
					0: { 
						// disable it by setting the property sorter to false 
						sorter: false 
					}
					
				<?php
				}
				?>
        },
		widgets: ["zebra"], // Alternance pair/impair automatique
		widgetZebra: { 
			css: [ "pair", "impair" ] 
		}
		//,		
		//sortList: [[$(".<?php echo 'sorter'.$rand;?> tr:first > th").length-1,0]] 
    }); 
});
</script>