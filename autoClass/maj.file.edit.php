 
<?php
 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/prepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

$translator =& TslManager::getInstance();
$langpile = $translator->getLanguages();

$sXML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"image\" libelle=\"Images\" prefix=\"img\" display=\"src\" abstract=\"titre\">
<item name=\"titre\" libelle=\"Titre\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\"/>
<item name=\"metadata\" libelle=\"meta-données\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\" option=\"textarea\" />  
<item name=\"url\" libelle=\"URL\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\" option=\"link\"/>
</class> ";
  
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

$bPopupWysiwyg = true ;
$bPopupLinks = true ; 
 

$idrang =  0; 
$source = $_GET["source"] ;

$source = $source_init = str_replace ("**", ".",  $source);

//echo $source."<br />"; 

if (isset($_POST["operation"]) && $_POST["operation"] == "INSERT") {


	//"{imagetest**gif}{imagetest**gif}{chat**jpg;chat-size-1**jpg}{imagetest**gif}{chat**jpg;chat-size-1**jpg}{chat-size-1**jpg}";
	$idrang = 0;

	//echo "old_source : ".$_POST["source"]."<br />";	
	if (preg_match ("/{/", $_POST["source"]) ) {
		$source = substr ($_POST["source"], 1, strlen ($_POST["source"])) ; 
		$source = substr ($source, 0, strlen ($source)-1) ; 
		$aFile = explode ("}{", $source);
	}
	else {
		$source = $_POST["source"];
		$aFile[] = $source; 
	}	
	  
	$aTab = array();  
	for ($i=0;$i<count($aNodeToSort);$i++){ 
		
	 
		foreach ($langpile as $lang_id => $lang_props) {
		
			$form_field = "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]; 
			echo $form_field."_".$lang_props['libellecourt']."<br />"; 
			
			if (is_post($form_field."_".$lang_props['libellecourt'])){
				$tsl_placeholder = 'no input // '. $lang_props['libellecourt']. ' = '.$_POST[$form_field."_".$lang_props['libellecourt']];
				break;
			}
		}
		// default language
		//echo "TEST : ".$tsl_default." : ".$form_field."_".$langpile[DEF_APP_LANGUE]['libellecourt']."<br />";
		$tsl_default = $_POST[$form_field."_".$langpile[DEF_APP_LANGUE]['libellecourt']];
		if ($tsl_default == '') {// pas de saisie de la langue defautl								
			// chercher la premiere langue saisie
			$tsl_default = $tsl_placeholder;
			//echo 'pas de saisie de la langue defautl = '.$tsl_default;
		}
							
		if ($tsl_default != ''){ // la langue par défaut a été saisie
			//echo ' la langue par défaut a été saisie';
			$tsl_table = Array();
			foreach ($langpile as $lang_id => $lang_props) {
				if ($lang_id != DEF_APP_LANGUE) {
					//echo $lang_id. " [form_fieldlang_props['libellecourt'] : ".$_POST[$form_field."_".$lang_props['libellecourt']]."<br />";
					//if ($_POST[$form_field."_".$lang_props['libellecourt']] != '')
					//if (!isset($_POST[$form_field."_".$lang_props['libellecourt']]))
					if ($_POST[$form_field."_".$lang_props['libellecourt']]==''){
						$tsl_table[$lang_id] = $tsl_placeholder;
					}
					else{
						$tsl_table[$lang_id] = $_POST[$form_field."_".$lang_props['libellecourt']];
					}
						
				}
			}
			//error_log('addTranslation maj '.$form_field);
			$_POST[$form_field] = $translator->addTranslation($tsl_default, $tsl_table);
			
			unset($tsl_placeholder);
			unset($tsl_default);
			
		} else {
			// unsset reference when updating to an empty text
			$_POST[$form_field] = -1;
		}
		$aTab[$aNodeToSort[$i]["attrs"]["NAME"]] = $_POST[$form_field];
		//echo $_POST[$form_field]."<br />";
		
	}	
	 
	
	$machaine = "";
	foreach ($aTab as $k => $tab) {
		$machaine.= "[".$k."::".$tab."]";
	}
	
	//echo "////////////////////////////<br />".$idrang.'<br />'; 
	//echo $machaine.'<br />'; 
	//echo $aFile[$idrang].'<br />////////////////////////////<br />'; 
	$aFile[$idrang] = $sFile = preg_replace ("/\[.*\]/", "", $aFile[$idrang]).$machaine;
	
	
	//$new_source = "{".implode("}{", $aFile) ."}";
        
        $new_source = implode("}{", $aFile);
	
	//echo "new_source : ".$new_source; 
	
	echo '<script>parent.$_returnvalue = "'.$new_source.'";parent.$.fancybox.close();</script>';
	/*echo '<script>parent.$_returnvalue = "'.$new_source.'";</script>';*/
	
	
	
	/*$aFile*/
	
}
else {
	$operation = 'UPDATE';
	//echo $idrang." ". $source; 
	if (preg_match ("/{/", $source) ) {
		$source = substr ($source, 1, strlen ($source)) ; 
		$source = substr ($source, 0, strlen ($source)-1) ; 
		$aFile = explode ("}{", $source);
	}
	else { 
		$aFile[] = $source; 
	}	
	 
	preg_match_all ("#.*\[titre::(.*)\]\[metadata::(.*)\]\[url::(.*)\]#", $aFile[$idrang], $matches); 
	
	
	$aTab = array();
	$aTab['titre'] = $matches[1][0];
	$aTab['metadata'] = $matches[2][0];
	$aTab['url'] = $matches[3][0];
	 
	
}


 
?>
 
<form name="add_<?php echo $classePrefixe; ?>_form" id="add_<?php echo $classePrefixe; ?>_form" enctype="multipart/form-data" method="post">
<input type="hidden" name="classeName" id="classeName"  value="<?php echo $classeName; ?>" />
<input type="hidden" name="idrang" id="idrang"  value="<?php echo $idrang; ?>" />
<input type="hidden" name="source" id="source"  value="<?php echo $source_init; ?>" />
<input type="hidden" name="classeName" id="classeName"  value="<?php echo $classeName; ?>" />
<span class="arbo2">MODULE >&nbsp;</span><span class="arbo3">Images des diaporamas&nbsp;>&nbsp;
Ajouter</span><br><br>
<script src="/backoffice/cms/js/validForm.js" type="text/javascript"></script>
<script src="/backoffice/cms/js/jquery.mycolorpicker.js" type="text/javascript"></script> 

<link rel="stylesheet" type="text/css" href="/backoffice/cms/css/jquery.mycolorpicker.css" />   
  
<script type="text/javascript">

	///////////////////////////////////////////////////
	// contrôle la validité du formulaire
	///////////////////////////////////////////////////	
	
	function ifFormValid(){
		return true;
	}

	///////////////////////////////////////////////////
	// validation du formulaire
	///////////////////////////////////////////////////
	
	function validerForm(){	
		if (validate_form("add_<?php echo $classePrefixe; ?>_form") &&  validerChampsOblig()){ 
			document.add_<?php echo $classePrefixe; ?>_form.operation.value = "INSERT";
			//document.add_<?php echo $classePrefixe; ?>_form.action = "/include/cms-inc/autoClass/maj.file.edit.php?id=-1"; 
			document.add_<?php echo $classePrefixe; ?>_form.target = "_self";
			document.add_<?php echo $classePrefixe; ?>_form.submit(); 
		}		
	}
	
	function annulerForm(){
			parent.$.fancybox.close();
			
	}
	
	function loadIframe(ifId, id){		
		url = document.getElementById("ifUrl"+ifId).value;
		if(id!=undefined){
			url = url.replace(/id=-1/,'id='+id);
		}
		if (document.getElementById("fDia_diaporama_type") != null){ //cas diaporama
			url += "&setDiaporamaType="+document.getElementById("fDia_diaporama_type").value;
		}
		document.getElementById("if"+ifId).setAttribute('src', url);	
	}
	
	function editAssoItem(fld){
		// fld : fAssoClasseIN_ClassOUT_ID		
		var matches = fld.match(/^f(.+)_([0-9]*)+$/);
		ifId = matches[1];		
		id = matches[2]; 
		loadIframe(ifId, id);
	}
	
	///////////////////////////////////////////////////
	// validation du mot de passe
	///////////////////////////////////////////////////
	function checkPwd(id){
		saisie = document.getElementById(id).value;
		confirmation = document.getElementById(id+"conf").value;
		if (saisie != confirmation){
			alert("Les valeurs saisies pour le mot de passe et sa confirmation ne correspondent pas.\nVeuillez corriger votre saisie");
		}
	}
	
	function resetField(){
		for(i in arguments){
			document.getElementById(arguments[i]).value = '';
		}
		
	}

</script>
<!-- MODE EDITION -->

<input type="hidden" name="operation" id="operation" value="INSERT" />
<input type="hidden" name="urlRetour" id="urlRetour" value="" />
<input type="hidden" name="id" id="id" value="-1" />
<input type="hidden" name="actiontodo" id="actiontodo" value="SAUVE" />
<input type="hidden" name="sChamp" id="sChamp" value="" />




<table border="1" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo">
<input type="hidden" name="MAX_FILE_SIZE" value="20971520"><script type="text/javascript">
function validerChampsOblig() {
erreur=0;
lib="";
if (erreur == 0) {
  return true; 
}
else{
alert("Les champs suivants sont obligatoires : \n"+lib);	return false;
}
}</script>

<?php

	for ($i=0;$i<count($aNodeToSort);$i++){
		echo "<tr>\n";
		echo "<td width=\"141\" align=\"right\" bgcolor=\"#E6E6E6\" class=\"arbo\">";
		if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")) 
			echo "&nbsp;<u><b>".$translator->getText(stripslashes($aNodeToSort[$i]["attrs"]["LIBELLE"]))."</b></u>";	
		else	echo "&nbsp;<u><b>".$translator->getText(stripslashes($aNodeToSort[$i]["attrs"]["NAME"]))."</b></u>";

		if (isset($aNodeToSort[$i]["attrs"]["OBLIG"]) && $aNodeToSort[$i]["attrs"]["OBLIG"]!="false")
			echo "&nbsp;*";
		echo "</td>\n";
		echo "<td width=\"535\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"arbo\">";
		
		$eKeyValue = $aTab[$aNodeToSort[$i]["attrs"]["NAME"]]; 
		
		include ("include/cms-inc/autoClass/maj.translation.php"); 
		
		echo "</td>\n";
		echo "</tr>\n";
	}
	
?>


 
 
  
  <tr>
   <td bgcolor="#E6E6E6"  align="right" class="arbo">&nbsp;</td>
   <td bgcolor="#EEEEEE"  align="left" class="arbo">(* champs obligatoires)</td>
 </tr>
 <tr>
   <td bgcolor="#E6E6E6"  align="right" class="arbo">&nbsp;</td>
   <td bgcolor="#EEEEEE"  align="left" class="arbo">&nbsp;</td>
 </tr>
 <tr>
  <td bgcolor="#D2D2D2" colspan="2"  align="center" class="arbo">
  <input name="button" type="button" class="arbo" onClick="annulerForm();" value="<< Retour">&nbsp;
  <input class="arbo" type="button" name="Ajouter" value="Enregistrer >>" onClick="validerForm()"></td>
 </tr>
</table>
<br /><br />
</form>
<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/append.php');

?>