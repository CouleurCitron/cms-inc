<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if (!isset($classeName))
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));

//------------------------------------------------------------------------------------------------------
  

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

unset($_SESSION['BO']['CACHE']);

// Fiche visu
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbopage.lib.php');
 
if (function_exists('activateMenu')){
	activateMenu('gestion'.$classeName);
} 

//provoque erreur s'il est à NULL
if ($_SESSION['id']== NULL)
	$_SESSION['id'] = "";

if ($_SESSION['listParam']!="")
	$listParam = $_SESSION['listParam'];
else {
	$_SESSION['listParam']!=$_SERVER['QUERY_STRING'];
	$listParam=$_SESSION['listParam'];
}

$listParam = str_replace('id=&', '', $listParam); // parce que ce serait inepte
// second controle, si id=X dans l'url on vira l'occurence eventuelle en session
if (preg_match('/id=([0-9]+)/msi', $listParam, $idMatches)==1){
	if ((is_get('id') && ($idMatches[1]!=$_GET['id']))	||	(is_post('id') && ($idMatches[1]!=$_POST['id']))){
		$listParam = str_replace($idMatches[0], '', $listParam);
		$listParam = str_replace('&&', '&', $listParam);
	}
}
 
$listParamSsId = $listParam;
// récupération de l'id 
if ($id == "") {
	if (isset($_GET['adodb_next_page'])) 
		$idPage=$_GET['adodb_next_page']; 
	elseif (isset($_GET['adodb_prev_page']))
		$idPage=$_GET['adodb_prev_page'];
	$qryref = dbGetArrayOneFieldFromRequete($_SESSION['sqlpag']);
	$id=$qryref[$idPage-1];
	for ($i=0;$i<count($qryref);$i++) {
		if ($qryref[$i]==$id)
			$_SESSION['pag']=$i+1;
	}
} 
// Pour calcul de l'id par rapport à la recherche et récupération de la position dans la navigation par rapport à cet id



// Pour récupération de la position dans la navigation de get_id
if ($_GET['id']) {
	$id=$_GET['id'];
	$qryref = dbGetArrayOneFieldFromRequete($_SESSION['sqlpag']);
	for ($i=0;$i<count($qryref);$i++) {
		if ($qryref[$i]==$id) {
		$_SESSION['pag']=$i+1;
		}
	}
}

// si l'opération est une suppression revenir à la page précédente
if ($operation=="DELETE") {
?>
<script type="text/javascript">
window.location="show_<?php echo $classeName; ?>.php?adodb_next_page=<?php echo $_GET['adodb_next_page'];; ?>"
</script>
<?php
}
?>
<script language="javascript" type="text/javascript">
	// retour à la liste
	function retour(){
		document.location.href="list_<?php echo  $classeName ; ?>.php?<?php echo str_replace("id=-1", "", $listParamSsId);?>";
	}
	
	// ajout d'un enregistrement
	function addEmp(){
		document.<?php echo $classeName; ?>_form.actiontodo.value = "MODIF";
		document.<?php echo $classeName; ?>_form.display.value = null;
		document.<?php echo $classeName; ?>_form.id.value = -1;
		document.<?php echo $classeName; ?>_form.action = "maj_<?php echo $classeName; ?>.php?id=-1&adodb_next_page=-1<?php if($listParamSsId!="") echo "&".preg_replace("/(id=)([0-9]+)/msi", "previd=$2", $listParamSsId);?>";
		document.<?php echo $classeName; ?>_form.submit();		
	}
	
	// modification de l'enregistrement
	function modifEmp(){
		document.<?php echo $classeName; ?>_form.display.value = null;
		document.<?php echo $classeName; ?>_form.actiontodo.value = "MODIF";
		document.<?php echo $classeName; ?>_form.action = "maj_<?php echo $classeName; ?>.php?id=<?php echo $id; ?><?php if($listParamSsId!="") echo "&".preg_replace("/([\?&]{0,1})id=\-1/msi", "$1", $listParamSsId);?>";
		document.<?php echo $classeName; ?>_form.submit();
	}
	
	// suppression de l'enregtistrement
	function deleteEmp(){
		sMessage = "Etes vous sur(e) de vouloir supprimer cet enregistrement ?";
  		if (confirm(sMessage)) {
			document.<?php echo $classeName; ?>_form.operation.value = "DELETE";
			document.<?php echo $classeName; ?>_form.id.value = "<?php echo $id; ?>";
			document.<?php echo $classeName; ?>_form.display.value = null;
			document.<?php echo $classeName; ?>_form.submit();
		}
	}
</script>
<?php
// permet de dérouler le menu contextuellement
if (function_exists('activateMenu')){
	activateMenu('gestion'.$classeName);
}  

if ($id != -1) {
// objet 
eval("$"."oRes = new ".$classeName."($"."id);");

if(!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else	$sXML = $oRes->XML;
unset($stack);
$stack = array();
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];

if (is_get('titre')){
	$classeLibelle = $_GET['titre'];
}
else{
	if (isset($stack[0]["attrs"]["LIBELLE"]) && ($stack[0]["attrs"]["LIBELLE"] != ""))
		$classeLibelle = stripslashes($stack[0]["attrs"]["LIBELLE"]);
	else	$classeLibelle = $classeName;
}

$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

if($oRes) {
?>
<span class="arbo2">MODULE >&nbsp;</span><span class="arbo3"><?php echo  $classeLibelle ; ?>&nbsp;>&nbsp;
<?php $translator->echoTransByCode('Visualiser'); ?>
</span>
<?php
if ($_SESSION['sTexte']) echo '<br /><br />'.$translator->getTransByCode('Visualiser').' <strong>'.$_SESSION['sTexte']."</strong>";
?>
<br /><br />
<div align="center" class="arbo">
<?php
//===============================
// operations de BDD
//===============================

// suppression

// translation engine
// Added by Luc - 6 oct. 2009
//if (DEF_APP_USE_TRANSLATIONS) {
	$translator =& TslManager::getInstance();
	if (defined('DEF_EDIT_INACTIVE_LANG')	&&	DEF_EDIT_INACTIVE_LANG)
		$langpile = $translator->getLanguages();
	else	$langpile = $translator->getLanguages(true);
//}

$operation = $_POST['operation'];
//if ($operation == "DELETE" && strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) === true) {		???????
if ($operation == "DELETE" && strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) !== false) {

	$id = $_POST['id'];

	if ($id != " ") {	
		// compte les objets avec cet id
		// pour voir si cet objet existe
		$eEmp = getCount($classeName, ucfirst($classePrefixe)."_id", ucfirst($classePrefixe)."_id", $id);
	
		if ($eEmp == 1) {
			eval("$"."oRes = new ".$classeName."($"."id);");
		
			dbDelete($oRes, true, true);
			$sMessage = $classeName." ".$oRes->get_id()." supprimé ";
			
			//****************modif thao**********************
			// récup de toutes les asso à $classeName
			
			$urlClass= "../../include/bo/class";
			//table contenant les classes liés
			$aTempClas=ScanDirs($urlClass, $classeName);
			for ($j=0; $j<newSizeOf($aTempClas);$j++) {
				$sAssoClasse = $aTempClas[$j];
				eval("$"."oAsso = new ".$sAssoClasse."();");
				$aForeign = dbGetObjects($sAssoClasse);
				$sXML = $oAsso->XML;
				// on vide le tableau stack
				unset($stack);
				$stack = array();
				xmlClassParse($sXML);	
				$foreignName = $stack[0]["attrs"]["NAME"];
				$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
				$foreignNodeToSort = $stack[0]["children"];
				for ($i=0;$i<count($foreignNodeToSort);$i++){
					if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
						if ($foreignNodeToSort[$i]["attrs"]["NAME"] == $classeName){
							$eEmp = getCount($foreignName, ucfirst($foreignPrefixe)."_id", ucfirst($foreignPrefixe)."_".ucfirst($classeName), $id);
							if ($eEmp > 0) {
								
								$sqlDisplay =  "select ".ucfirst($foreignPrefixe)."_id from ".$foreignName." where ".ucfirst($foreignPrefixe)."_".ucfirst($classeName)."=".$id; 
								$aResponseDisplay = dbGetObjectsFromRequeteID($foreignName, $sqlDisplay);
								for ($a=0; $a<newSizeOf($aResponseDisplay); $a++) {
									$oResponseDisplay = $aResponseDisplay[$a];
									$idResponseDisplay = $oResponseDisplay->get_id();
									eval("$"."oRes3 = new ".$foreignName."($".idResponseDisplay.");");
									if ($oRes3->getGetterStatut()!="none") {
										if ($foreignNodeToSort[$i]["attrs"]["DEFAULT"] != ""){
											$foreignDefault=$foreignNodeToSort[$i]["attrs"]["DEFAULT"];
										}
										else {
											$foreignDefault="";
										}
										eval("$"."oRes3->set_".$classeName."($".foreignDefault.");"); 
										
										for ($l=0;$l<count($foreignNodeToSort);$l++){
											if ($foreignNodeToSort[$l]["name"] == "ITEM"){	
											
												if ($foreignNodeToSort[$l]["attrs"]["NAME"] == "statut") {
													if (isset($foreignNodeToSort[$l]["children"]) && (count($foreignNodeToSort[$l]["children"]) > 0))
														$eCodeStatut = 5; // libelle écartée, code à reprendre pour le libellé "ecarté" ou autre
													else	$eCodeStatut = DEF_CODE_STATUT_DEFAUT;
												} // if ($foreignNodeToSort[$i]["attrs"]["name"] == "STATUT") {
											}
										}
										$oRes3->set_statut($eCodeStatut);
										
										$bAssoRetour = dbUpdate($oRes3);
										
									}
									else {
										$bAssoRetour = dbDelete($oRes3);
									} //if ($oRes3->getGetterStatut()!="none") {
									
								} //for ($a=0; $a<newSizeOf($aResponseDisplay); $a++) {
							}// if ($eEmp > 0) {
							
						}// if ($foreignNodeToSort[$i]["attrs"]["NAME"] == $classeName){
					}// if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
				} //if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
			} //for ($i=0;$i<count($foreignNodeToSort);$i++){
			
			//*************************************************
		}
	}
	
	// on reinitialise la table stack
	eval("$"."oRes = new ".$classeName."();");
	unset($stack);
	$stack = array();
	$sXML = $oRes->XML;
	xmlClassParse($sXML);

	$classeName = $stack[0]["attrs"]["NAME"];
	$classePrefixe = $stack[0]["attrs"]["PREFIX"];
	$aNodeToSort = $stack[0]["children"];

	echo "Enregistrement ".$id." supprimé<br><br>";

} // fin DELETE

else {  // operation autre DELETE
?>
<table border="1" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo" width="100%">
<?php
for ($i=0;$i<count($aNodeToSort);$i++) {
	if (($aNodeToSort[$i]["name"] == "ITEM")&&($aNodeToSort[$i]["attrs"]["SKIP"] != 'true') && (($aNodeToSort[$i]["attrs"]["FKEY"]!="cms_site")	|| ($classeName =="classe") || ($classeName =="cms_site"))){	
		 
		if ($aNodeToSort[$i]["attrs"]["FKEY"] == 'bo_users' && !empty($aNodeToSort[$i]["attrs"]["RESTRICT"]) && $_SESSION["rank"] != 'ADMIN')
			// Cas over mega pas typique du tout
			// Cloisonnement sur administrateur loggué
			continue;

		echo "<tr>\n";
		echo "<td width=\"141\" align=\"right\" bgcolor=\"#E6E6E6\" class=\"arbo\">";		
		if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != ""))
			echo "&nbsp;<u><b>".$translator->getText(stripslashes($aNodeToSort[$i]["attrs"]["LIBELLE"]))."</b></u>";
		else	echo "&nbsp;<u><b>".$translator->getText(stripslashes($aNodeToSort[$i]["attrs"]["NAME"]))."</b></u>";

		if (isset ($aNodeToSort[$i]["attrs"]["OBLIG"]) &&  $aNodeToSort[$i]["attrs"]["OBLIG"]!="")
			echo "&nbsp;*";
		echo "</td>\n";
		echo "<td width=\"535\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"arbo\">";
	 
		$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);

		if (!empty($aNodeToSort[$i]["attrs"]["ANONYMOUS"])){ // cas anonymous
			$fld_check = 'get_'.$aNodeToSort[$i]["attrs"]["ANONYMOUS"];
			if ($oRes->$fld_check() == 'Y') {
				if ($_SESSION['login'] != 'ccitron') {
					echo "*** ".$translator->getText("anonyme")." ***";
					continue;
				} else	echo "[".$translator->getText("anonyme")."] ";
			}
		} // fin cas anonymous
		if ((isset($aNodeToSort[$i]["attrs"]["FKEY"]) && $aNodeToSort[$i]["attrs"]["FKEY"] != '' && $aNodeToSort[$i]["attrs"]["FKEY"] != 'null' && class_exists($aNodeToSort[$i]["attrs"]["FKEY"])) || $aNodeToSort[$i]["attrs"]["FKEY_SWITCH"]) {
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

		}// fin fkey
		// translation data
		elseif (DEF_APP_USE_TRANSLATIONS && $aNodeToSort[$i]["attrs"]["TRANSLATE"]){ // cas traduction
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") {
				if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference') {
					foreach ($langpile as $lang_id => $lang_props) { 
					
						$tsl_chain = $translator->getByID($eKeyValue, $lang_id);  
						
						if ($aNodeToSort[$i]["attrs"]["OPTION"] == "node"){ // cas password	
							if ($tsl_chain != '') {
								if (getCount_where("cms_arbo_pages", array("node_id"), array($tsl_chain), array("NUMBER")) ==  1){
									if (getNodeInfos($db, $tsl_chain)){
										$infosNode = getNodeInfos($db, $tsl_chain); 
										$tsl_chain = $infosNode["path"];
										
									} else $tsl_chain = "n/a";
							
								} else	$tsl_chain = "n/a"; 
							}
							else	$tsl_chain = "n/a"; 
						
						}
						
						if ($tsl_chain != '')
							echo (newSizeOf($langpile) > 1 ? "[".$lang_props['libellecourt']."] > " : "").$tsl_chain."<br/>";
					}
					
					
					
					
				}
			} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") {
				if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == "value")
					echo $translator->getText($eKeyValue, $_SESSION['id_langue']);
			    	else	echo "Error - <b><i>".$aNodeToSort[$i]["attrs"]["TRANSLATE"]."</i></b> 'translate' attribute value is not valid for translation engine applied to <b><i>ENUM</i></b> type fields !!";
			} else	echo "Error - Translation engine can not be applied to <b><i>".$aNodeToSort[$i]["attrs"]["TYPE"]."</i></b> type fields !!";

		} // end translation data
		// fin fkey
		elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum	sur options
			if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if($childNode["name"] == "OPTION"){ // on a un node d'option				
						if ($childNode["attrs"]["TYPE"] == "value"){
							if (trim($eKeyValue) == trim($childNode["attrs"]["VALUE"])){							
								echo $translator->getText(stripslashes($childNode["attrs"]["LIBELLE"]), $_SESSION['id_langue']);
								break;
							}
						} //fin type  == value				
					}
				}
			}
		} // fin cas enum sur options
		else{ // cas typique
			if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){ // cas statut
				  if (!isset($aNodeToSort[$i]["children"])){ // cas typique		  
				  	  //echo lib($eKeyValue);
					  $translator->echoTransByCode('statut'.$eKeyValue);
				  } else { // cas statut custom
				  //<option type="value" value="0" libelle="en attente" />
					for ($iSta=0; $iSta<count($aNodeToSort[$i]["children"]);$iSta++){
						if ($aNodeToSort[$i]["children"][$iSta]["attrs"]["TYPE"] == "value"){
							if($eKeyValue==intval($aNodeToSort[$i]["children"][$iSta]["attrs"]["VALUE"])) {
								echo $translator->getText($aNodeToSort[$i]["children"][$iSta]["attrs"]["LIBELLE"], $_SESSION['id_langue']);	
								break;
							}
						}
					}
				  
				  }
			} else {
				if ($eKeyValue > -1){ // cas typique typique 
					if (isset($aNodeToSort[$i]["attrs"]["OPTION"])	&&	$aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
						
						include ("show.file.php");
						
					}
					elseif (isset($aNodeToSort[$i]["attrs"]["OPTION"])	&&	$aNodeToSort[$i]["attrs"]["OPTION"] == "bool"){ // boolean
						if (intval($eKeyValue) == 1)
							echo "oui";
						else	echo "non";						
					}
					elseif (isset($aNodeToSort[$i]["attrs"]["OPTION"])	&&	$aNodeToSort[$i]["attrs"]["OPTION"] == "link"){ // cas link			
						echo "<a href=\"".$eKeyValue."\" target=\"_blank\" title=\"Lien édité\">".$eKeyValue."</a><br />\n";					
					}
					elseif (isset($aNodeToSort[$i]["attrs"]["OPTION"])	&&	$aNodeToSort[$i]["attrs"]["OPTION"] == "filedir"){ // cas link                  
						echo $eKeyValue."<br><img src=\"".$eKeyValue."\" title=\"Image éditée\"><br>\n";                                 
					}
					elseif (isset($aNodeToSort[$i]["attrs"]["OPTION"])	&&	$aNodeToSort[$i]["attrs"]["OPTION"] == "password"){ // cas password	
						echo "*******";
						
					}
					elseif (isset($aNodeToSort[$i]["attrs"]["OPTION"])	&&	$aNodeToSort[$i]["attrs"]["OPTION"] == "node"){ // cas password	
						if (getCount_where("cms_arbo_pages", array("node_id"), array($eKeyValue), array("NUMBER")) ==  1){
							if (getNodeInfos($db, $eKeyValue)){
								$infosNode = getNodeInfos($db, $eKeyValue); 
								$eKeyValue = $infosNode["path"];
								
							} else $eKeyValue = "n/a";

						} else	$eKeyValue = "n/a";

						echo $eKeyValue;

					} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "datetime" || $aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp") { // cas timestamp
						//echo timestampFormat($eKeyValue);
						echo ($eKeyValue);
					} elseif (isset($aNodeToSort[$i]["attrs"]["OPTION"])	&&	$aNodeToSort[$i]["attrs"]["OPTION"] == "xml"){
						echo htmlentities($eKeyValue);
					}
					elseif (isset($aNodeToSort[$i]["attrs"]["OPTION"])	&&	$aNodeToSort[$i]["attrs"]["OPTION"] == "color"){ // RVB							
						if (preg_match('/^#[0-9A-F]{6}$/msi', $eKeyValue)==1){
							echo $eKeyValue.'<div style="width:55px; height:12px; background-color:'.$eKeyValue.'"></div>';
						}
						else{
							echo 'n/a';
						}						
					}
					elseif ($oRes->getClasse() == "newsletter" &&  $aNodeToSort[$i]["attrs"]["NAME"] == "html"){ // html newsletter		 
						
						include_once("show.newsletter.php");		
						 					
					}
					else {// cas typique typique typique
						if (isset($aNodeToSort[$i]["attrs"]["SERIALIZED"])	&&	$aNodeToSort[$i]["attrs"]["SERIALIZED"] == "true") {
							echo "<script type=\"text/javascript\">
							document.toggle_".$aNodeToSort[$i]["attrs"]["NAME"]."_Serial = function (_visible) {
								document.getElementById('toggleSerial_{$aNodeToSort[$i]["attrs"]["NAME"]}_OFF').style.display = (_visible ? 'none' : 'block');
								document.getElementById('toggleSerial_{$aNodeToSort[$i]["attrs"]["NAME"]}_ON').style.display = (_visible ? 'block' : 'none');
								document.getElementById('blockSerial_{$aNodeToSort[$i]["attrs"]["NAME"]}_ON').style.display = (_visible ? 'block' : 'none');
								document.getElementById('blockSerial_{$aNodeToSort[$i]["attrs"]["NAME"]}_OFF').style.display = (_visible ? 'none' : 'block');
							}
							</script>\n";
							echo "<div id=\"toggleSerial_{$aNodeToSort[$i]["attrs"]["NAME"]}_OFF\" style=\"display: block; float: right;\"><a href=\"#_\" onclick=\"toggle_{$aNodeToSort[$i]["attrs"]["NAME"]}_Serial(true);\">".$translator->getTransByCode('displayDataTable')."</a></div>\n";
							echo "<div id=\"toggleSerial_{$aNodeToSort[$i]["attrs"]["NAME"]}_ON\" style=\"display: none; float: right;\"><a href=\"#_\" onclick=\"toggle_{$aNodeToSort[$i]["attrs"]["NAME"]}_Serial(false);\">".$translator->getTransByCode('HideDataTable')."</a></div>\n";
							echo "<div id=\"blockSerial_{$aNodeToSort[$i]["attrs"]["NAME"]}_ON\" style=\"display: none;\">\n";
							viewArray(unserialize($eKeyValue), 'Table');
							echo "</div>\n";
							echo "<div id=\"blockSerial_{$aNodeToSort[$i]["attrs"]["NAME"]}_OFF\" style=\"display: block;\">\n".$translator->getTransByCode('serializedData')."</div>\n";
						} else	echo $eKeyValue;
					}
				}
				else if ( $aNodeToSort[$i]["attrs"]["TYPE"] == "varchar" && isset($aNodeToSort[$i]["attrs"]["ISMINUS"] ) && ($aNodeToSort[$i]["attrs"]["ISMINUS"]  == true)){
					echo $eKeyValue;	 
				} 				
				else{
					echo "n/a";
				}
			}
		}
		echo "</td>\n";
		echo "</tr>\n";
		echo "<!-- fin des champs de la classe -->\n\n";
	}
}

// recherche d'eventuelles asso
for ($i=0; $i<count($aNodeToSort); $i++) {
	if ($aNodeToSort[$i]["name"] == "ITEM") {
		if ((isset($aNodeToSort[$i]["attrs"]["ASSO"]) || isset($aNodeToSort[$i]["attrs"]["ASSO_VIEW"]))	&&	($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_VIEW"])) { // cas d'asso
			echo "<tr>\n";
			echo "<td bgcolor=\"#E6E6E6\" class=\"arbo\">&nbsp;</td>\n";
			echo "<td bgcolor=\"#EEEEEE\" class=\"arbo\">&nbsp;</td>\n";
			echo "</tr>\n";
			echo "<!-- debut des champs d'association -->\n";
			echo "<tr>\n";
			echo "<td width=\"141\" align=\"right\" bgcolor=\"#E6E6E6\" class=\"arbo\" style=\"padding-top: 8px;\">";
			echo "<b>".$translator->getTransByCode('Associations')."</b>";
			echo "</td>\n";
			echo "<td width=\"535\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"arbo\">";
			

			// AJAX delayed call for association list display
			// Added by Luc
			// first define fields not applying to AJAX display
			$excluded = Array('cms_site');
			if (!in_array($aNodeToSort[$i]["attrs"]["NAME"], $excluded)) {
				// AJAX delayed process
				echo "\n".'<div id="delayed_'.$classePrefixe.'_associations" style="display: inline;"></div>';
				$call = '/backoffice/cms/call_show_association.php?class='.$classeName.'&id='.$id.'&field='.$aNodeToSort[$i]["attrs"]["NAME"];
				//echo "test : ".$call."<br/>";
				echo "\n".'<script type="text/javascript">';
				echo "\n".'XHRConnector.sendAndLoad(\''.$call.'\', \'GET\', \'Chargement de la liste...\', \'delayed_'.$classePrefixe.'_associations\');';
				echo "\n".'</script>';
			} else {
				// inline process
				include_once("show.association.php");
			}
			echo "</td>\n</tr>\n";
		}	
	}
}

?>
 
</table>
<?php
} // fin operation autre DELETE

?>
<br />
<form name="<?php echo $classeName; ?>_form" id="<?php echo $classeName; ?>_form" method="post">
<input type="hidden" name="urlRetour" id="urlRetour" value="<?php echo  $_SERVER['REQUEST_URI'] ; ?>" />
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
<input type="hidden" name="display" id="display" value="" />
<input type="hidden" name="actionUser" id="actionUser" value="" />
<input type="hidden" name="operation" id="operation" value="<?php echo $operation; ?>" />
<input type="hidden" name="actiontodo" id="actiontodo" value="" />
<input type="hidden" name="sensTri" id="sensTri" value="<?php echo $sensTri; ?>" />
<input type="hidden" name="champTri" id="champTri" value="<?php echo $champTri; ?>" />
<input type="hidden" name="idStatut" id="idStatut" value="" />
<input type="hidden" name="cbToChange" id="cbToChange" value="" />
<input type="hidden" name="eStatut" id="eStatut" value="" />
<input type="hidden" name="sTexte" id="sTexte" value="" />
<?php
	include($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/footertools.inc.php');
	
	if(!is_get('noMenu')){
		
		//récupération de la requete
		
		if( $_SESSION['sqlpag']!=NULL){
			$sql = $_SESSION['sqlpag'];
			//viewArray($_SESSION, 'SESSION');
				
			$sParam = "";
			$pager = new Pagination($db, $sql, $sParam, $_SESSION['idSite']);
			
			$pager->Render($rows_per_page=1);
			// tableau d'id renvoyé par la fonction de pagination
			$aId = $pager->aResult;
			
			print($pager->bandeau);
		}
		else{
			error_log('pagination failed $_SESSION[\'sqlpag\'] is NULL ');
		}
	}
} else {
	die("Erreur ".$classeName." non trouvé");
		}
}
?>
</form>
</div>