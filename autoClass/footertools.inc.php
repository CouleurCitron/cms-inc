<div align="center" class="arbo">
<?php
if(is_get('noMenu')){
	echo '<a href="javascript:parent.$.fancybox.close();" class="arbo">'.$translator->getTransByCode('Fermer').'</a>&nbsp;&nbsp;'."\n";
}
else{
	echo '<a href="javascript:retour()" class="arbo">'.$translator->getTransByCode('Retour_a_la_liste').'</a>&nbsp;&nbsp;'."\n";

	if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/a/', $customActionControl[$_SESSION['rank']])) {
		if (!empty($aCustom)) { 
			
			if (!empty($aCustom['JS'])) {
				$aCustomTemp = array();
				$aCustomTemp = $aCustom; 
				
				$aCustom = array ();
				$aCustom[] = $aCustomTemp;
			}
			
			
			foreach ($aCustom as $k => $custom) {
				$activate_custom = false;
				if (!empty($custom['JS'])) {
					echo "<script type=\"text/javascript\">\n";
					$search = array("##classePrefixe##", "##classeName##", "##id##");
					$replace = array($classePrefixe, $classeName, $oRes->get_id());
					echo str_replace($search, $replace, $custom['JS']);
					echo "\n</script>\n";
				}
				
				 
				if (!empty($custom['Filter'])) { 
					if (empty($custom["Filter"]['mode'])) {
						//$custom["Filter"]['mode'] = array(); 
						$custom["Filter"]['mode'] = 'AND';
					}
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
					if ($custom["Filter"]['mode'] == 'AND' && newSizeOf($test_res) == newSizeOf($custom['Filter']['pile']))
						$activate_custom = true;
					elseif ($custom["Filter"]['mode'] == 'OR' && newSizeOf($test_res) > 0)
						$activate_custom = true;
				} else	$activate_custom = true;
				if ($activate_custom) {
					// ##id## => id
					$search = array("##classePrefixe##", "##classeName##", "##id##");
					$replace = array($classePrefixe, $classeName, $_GET['id']);
					echo str_replace($search, $replace, $custom["Action"])."&nbsp;&nbsp;&nbsp;";
				}
			}
		}
	}


	
	if($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/c/', $customActionControl[$_SESSION['rank']])) {
		echo '<a href="javascript:addEmp()" class="arbo">'.$translator->getTransByCode('ajouterunitem').'</a>&nbsp;&nbsp;'."\n";
	}
	
	if($operation != "DELETE") {
		if($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/e/', $customActionControl[$_SESSION['rank']])) {
			echo '<a href="javascript:modifEmp()" class="arbo">'.$translator->getTransByCode('Modification_de_l_enregistement').'</a>&nbsp;&nbsp;'."\n";
		}
		
		if($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/d/', $customActionControl[$_SESSION['rank']])) {
			echo '<a href="javascript:deleteEmp()" class="arbo">'.$translator->getTransByCode('Suppression_de_l_enregistrement').'</a>&nbsp;&nbsp;'."\n";
		}
	}
}
?>
</div>