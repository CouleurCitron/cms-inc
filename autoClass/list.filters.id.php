<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($translator)){
	$translator =& TslManager::getInstance(); 
} 

//visu link
if (!isset($visuLink)){
	$visuLink = getUILink($classeName, 'show');
}
	
//edit link
if (!isset($editLink)){
	$editLink = getUILink($classeName, 'maj');
}

if (isset($aCustom["JS"]) && ($aCustom["JS"] != "")){ 
	preg_match_all("/function\s(.*)\(id\)/x", $aCustom["JS"], $reg);
	$function_js = $reg[1][0];
}	 

?>
<script type="text/javascript">
 // visu de l'enregistrement
 
	function formCheckValidInteger (val){	
		var filter=/^(\+|-)?[0-9]+$/;
		if (filter.test(val)) {  
			return true;
			
			
		} else { 
			return false;
		}
	}
 
	$(document).ready(function(){
<?php
if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/r/', $customActionControl[$_SESSION['rank']])) {
?>
		$("#visuSearch").click(function(){    
			 
			id = $('input#searchId').val();  
			if (formCheckValidInteger (id) ) {
				$.ajax({ // ajax
					type: 'GET',
					data: { id : id , classeName : '<?php echo $classeName; ?>' },
					url: '/backoffice/cms/call_list.filters.id.php', // url de la page à charger
					cache: false, // pas de mise en cache
					success:function(data){ // si la requête est un succès 
						if (data > 0) {
							visuEmp(id); 
						}
						else {
							alert('<?php echo $translator->getText('id_invalid', $_SESSION["id_langue"]) ;?>');   // on execute la fonction afficher(donnees) 
						}
					},
					error:function(XMLHttpRequest, textStatus, errorThrows){ // erreur durant la requete
					}
				}); 
				return false; // on desactive le lien
			}
			else {
				alert('<?php echo $translator->getText('not_integer', $_SESSION["id_langue"]) ;?>');
			}
		});
<?php
}
if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/e/', $customActionControl[$_SESSION['rank']])) {
?>	
		$("#modifSearch").click(function(){    
			 
			id = $('input#searchId').val();  
			if (formCheckValidInteger (id) ) {
				$.ajax({ // ajax
					type: 'GET',
					data: { id : id , classeName : '<?php echo $classeName; ?>' },
					url: '/backoffice/cms/call_list.filters.id.php', // url de la page à charger
					cache: false, // pas de mise en cache
					success:function(data){ // si la requête est un succès 
						if (data > 0) {
							modifEmp(id); 
						}
						else {
							alert('<?php echo $translator->getText('id_invalid', $_SESSION["id_langue"]) ;?>');   // on execute la fonction afficher(donnees) 
						}
					},
					error:function(XMLHttpRequest, textStatus, errorThrows){ // erreur durant la requete
					}
				}); 
				return false; // on desactive le lien
			}
			else {
				alert('<?php echo $translator->getText('not_integer', $_SESSION["id_langue"]);?>');
			}
		});
<?php
}
if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/c/', $customActionControl[$_SESSION['rank']])) {
?>
		$("#dupliSearch").click(function(){    
			 
			id = $('input#searchId').val();  
			if (formCheckValidInteger (id) ) {
				$.ajax({ // ajax
					type: 'GET',
					data: { id : id , classeName : '<?php echo $classeName; ?>' },
					url: '/backoffice/cms/call_list.filters.id.php', // url de la page à charger
					cache: false, // pas de mise en cache
					success:function(data){ // si la requête est un succès 
						if (data > 0) {
							//dupliEmp(id); 
							<?php
							 
							 
							if($_SERVER['QUERY_STRING']!=""){ 
								$editLink2 = "&".str_replace("id=", "idprev=",preg_replace('/idprev=[^&]*&/msi', '', $_SERVER['QUERY_STRING']));
							}
							
							?>
							$(location).attr('href','<?php echo $editLink.'?id='; ?>'+id+'&newid=-1<?php echo $editLink2; ?>');
						}
						else {
							alert('<?php echo $translator->getText('id_invalid', $_SESSION["id_langue"]) ;?>');   // on execute la fonction afficher(donnees) 
						}
					},
					error:function(XMLHttpRequest, textStatus, errorThrows){ // erreur durant la requete
					}
				}); 
				return false; // on desactive le lien
			}
			else {
				alert('<?php echo $translator->getText('not_integer', $_SESSION["id_langue"]);?>');
			}
		});		

<?php
}
if (isset($aCustom["JS"]) && $aCustom["JS"] != '' && ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/a/', $customActionControl[$_SESSION['rank']]))) {		
			
	echo "$(\"#".$function_js."Search\").click(function(){ \n   
			 
					id = $('input#searchId').val();  \n
					if (formCheckValidInteger (id) ) {\n
						$.ajax({ // ajax\n
							type: 'GET',\n
							data: { id : id , classeName : '".$classeName."' },\n
							url: '/backoffice/cms/call_list.filters.id.php', // url de la page à charger\n
							cache: false, // pas de mise en cache\n
							success:function(data){ // si la requête est un succès \n
								if (data > 0) {\n
									".$function_js."(id); \n
								}\n
								else {\n
									alert('".$translator->getText('id_invalid', $_SESSION["id_langue"])."');   // on execute la fonction afficher(donnees) \n
								}\n
							},\n
							error:function(XMLHttpRequest, textStatus, errorThrows){ // erreur durant la requete\n
							}\n
						}); \n
						return false; // on desactive le lien\n
					}\n
					else {\n
						alert('".$translator->getText('not_integer', $_SESSION["id_langue"])."');\n
					}\n
		});\n";
			
}
?>
		
		
	});
	
	
<?php 
	
	if (isset($aCustom["JS"]) && $aCustom["JS"] != '' && ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/a/', $customActionControl[$_SESSION['rank']]))){  
		echo "function ".$function_js."Search (){	
			
		}";
	}
	
?>
	
</script>

<?php				

if (preg_match('/backoffice/', $_SERVER['PHP_SELF'])==1) {	

?>
		<div id="idFilter" class="idFilter blocItem">
		<div align="left" id="keywordFilterLabelId" class="keywordFilterlabelId"><?php $translator->echoTransByCode('Recherche_par'); echo " id"; ?></div>
		<div align="left" id="keywordFilterFieldId" class="keywordFilterFieldId"><input type="text" name="searchId" id="searchId" value="<?php echo $_SESSION['searchId']; ?>" class="arbo" size="40"/>&nbsp;  
			
<?php
	if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/r/', $customActionControl[$_SESSION['rank']]))
		echo "<a title='".$translator->getTransByCode('Visualiser')."' href='#'  id='visuSearch'><img border='0' align='top' alt='".$translator->getTransByCode('Visualiser')."' src='/backoffice/cms/img/visualiser.gif'></a>\n"; 
	if ($editLink && ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/e/', $customActionControl[$_SESSION['rank']])))
		echo "<a title='".$translator->getTransByCode('Modifier')."' href='#' id='modifSearch'><img border='0' align='top' alt='".$translator->getTransByCode('Modifier')."' src='/backoffice/cms/img/modifier.gif'></a>\n";
		
	if ($editLink && ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/c/', $customActionControl[$_SESSION['rank']])))
		echo '<a href="#" id="dupliSearch" title="'.$translator->getTransByCode('Dupliquer').'"><img src="/backoffice/cms/img/dupliquer.gif" border="0" alt="'.$translator->getTransByCode('Dupliquer').'" align="top" /></a>&nbsp;';
		
		 
	if (isset($aCustom["Action"]) && $aCustom["Action"] != '' && (empty($customActionControl) || preg_match('/a/', $customActionControl[$_SESSION['rank']]))){
		$activate_custom = false;
		if (!empty($aCustom['Filter'])) {
			if (empty($aCustom["Filter"]['mode']))
				$aCustom["Filter"]['mode'] = 'AND';
			$test_res = Array();
			foreach ($aCustom['Filter']['pile'] as $filter) {
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
			if ($aCustom["Filter"]['mode'] == 'AND' && count($test_res) == count($aCustom['Filter']))
				$activate_custom = true;
			elseif ($aCustom["Filter"]['mode'] == 'OR' && count($test_res) > 0)
				$activate_custom = true;
		} else	$activate_custom = true;
		if ($activate_custom) {
			// ##id## => id 
			$search = array("##classePrefixe##", "##classeName##", "(##id##);\"", "##id##");
			$replace = array($classePrefixe, $classeName, "Search();\" id='".$function_js."Search'", "");
			
			echo str_replace($search, $replace, $aCustom["Action"]);
		}
	} 

 	echo "</div>\n";
	echo "</div>\n";
	echo ""; // template 
}

?>