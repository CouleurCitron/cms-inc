<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

// Formulaire de saisie 

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
 include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

// Chargement de la classe Upload
require_once('cms-inc/lib/fileUpload/upload.class.php');

// Instanciation d'un nouvel objet "upload"
$Upload = new Upload();

$Upload-> MaxFilesize = strval(12*1024);
// -----------------------------------

$actiontodo = ( $_POST['actiontodo']!="SAUVE" ) ? "MODIF" : "SAUVE" ;

// enregistrement à modifier
$id=$_GET['id'];
if(!isset($id)) $id=$_POST['id'];

// activation du menu : déroulement
//if (function_exists('activateMenu')){
	activateMenu('gestion'.$classeName);
}  

// objet 
if ( $id > 0 ) $operation = "UPDATE";
else $operation = "INSERT";

if ( $operation == "INSERT" ) { // Mode ajout
	eval("$"."oRes = new ".$classeName."();");
} else { // Mode mise à jour
	eval("$"."oRes = new ".$classeName."($"."id);");
}

$sXML = $oRes->XML;
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

// nombre de champs upload désiré
$numUploadFields = 0;
for ($iFile=0;$iFile<count($aNodeToSort);$iFile++){
	if ($aNodeToSort[$iFile]["name"] == "ITEM"){
		if ($aNodeToSort[$iFile]["attrs"]["OPTION"] == "file"){ 
			$numUploadFields++;
		}
	}
}
// gestion popup wysiwyg
/*
<a href="javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', 600, 600, 'scrollbars=yes', 'true','f_texte', 'eve_add_form');" title="HTML editor"><img src="/backoffice/cms/img/bt_popup_wysiwyg.gif" id="wysiwyg" style="cursor: pointer; border: 1px solid red;" alt="HTML editor" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" /></a>*/
if(is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/utils/popup_wysiwyg.php") && is_file($_SERVER['DOCUMENT_ROOT']."/lib/FCKeditor/fckconfig.js")){
	$bPopupWysiwyg = true;
}

// gestion calendrier jscalendar
if(is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/lib/jscalendar")){
	$bJScalendar = true;
	?>
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" media="all" href="/backoffice/cms/lib/jscalendar/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<!-- main calendar program -->
<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/calendar.js"></script>
<!-- language for the calendar -->
<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/lang/calendar-fr.js"></script>
<!-- the following script defines the Calendar.setup helper function -->
<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/calendar-setup.js"></script>
<?php
}
else{
	$bJScalendar = false;
}
//---------------------------------------------------------------

?>
<script>
	// retour à la page précédente
	// ce retour doit être posté pour conserver le type
	function retour()
	{
		document.add_<?php echo $classePrefixe; ?>_form.action = "<?php echo $_POST['urlRetour']; ?><?php if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>"; 
		document.add_<?php echo $classePrefixe; ?>_form.submit(); 
	}
</script>
<form name="add_<?php echo $classePrefixe; ?>_form" id="add_<?php echo $classePrefixe; ?>_form" enctype="multipart/form-data" method="post">

<input type="hidden" name="postnumberone" value="XXXX">
<input type="hidden" name="postnumber2" value="XXXX2">

<?php
//////////////////////////////////
// A VOIR
//////////////////////////////////
// le premier champ posté est "supprimé" quand il y a un caractère spécial dans la description
// (en l'occurence un ’)
// pourtant dans l'auprepend tous les champs postés sont correctement nettoyés
// j'ai mis ici deux champ postnumberone et postnumber2
// ils ne servent à rien sinon à tester que le premier champ est systématiquement supprimé 
// quand il y a un caractère spécial dans la description
//////////////////////////////////
// A VOIR
/////////////////////////////////

$status = '';
if($actiontodo == "SAUVE") { // MODE ENREGISTREMENT
	
	//-------------- upload --------------------------------------------------
	if ($numUploadFields > 0){

		// Pour ne pas écraser un fichier existant
		$Upload->  WriteMode  = '1';
		
		 // Définition du répertoire de destination
		$Upload-> DirUpload    = $_SERVER['DOCUMENT_ROOT'].'/tmp/';
		dirExists("/tmp/");
		
		// controle l'existence du dir tmp
		if (!is_dir($Upload-> DirUpload)){ // on le crée
			mkdir($Upload-> DirUpload);
		}
		
		// Pour limiter la taille d'un  fichier (exprimée en ko)
		$Upload-> MaxFilesize  = strval(12*1024);
		
		//pre_dump($Upload);
	
		// On lance la procédure d'upload
		$Upload-> Execute(); 
		
		$DIR_UPLOAD = $_SERVER['DOCUMENT_ROOT'].'/custom/upload/';
		
		if (!is_dir($DIR_UPLOAD)){ // on le crée
			mkdir($DIR_UPLOAD);
		}   
		
		$DIR_MEDIA = $_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/';
		
		// controle l'existence du dir dest
		if (!is_dir($DIR_MEDIA)){ // on le crée
			mkdir($DIR_MEDIA);
		}
		$URL_MEDIA = '/custom/upload/'.$classeName.'/';
		
		if (count($Upload-> Infos) >= 1)  {	
			$aUploadIndexes[] = 0;
			while ($currInfos = current($Upload-> Infos)) {
				$aUploadIndexes[] = key($Upload-> Infos);
				next($Upload-> Infos);
			}
				
			for ($i = 1;$i <= count($Upload-> Infos);$i++){
				if (!copy($Upload->Infos[$aUploadIndexes[$i]]['chemin'], $DIR_MEDIA.$Upload-> Infos[$aUploadIndexes[$i]]['nom'])) {
				   //echo "failed to copy ".$Upload->Infos[$aUploadIndexes[$i]]['chemin']."...\n";
				}
				else{
					//echo "copied ".$Upload-> Infos[$aUploadIndexes[$i]]['chemin']."...\n";
					unlink($Upload-> Infos[$aUploadIndexes[$i]]['chemin']);
					// var à mettre à jour
					$_POST[strval($_POST[strval('fUpload'.$aUploadIndexes[$i])])] = $Upload->Infos[$aUploadIndexes[$i]]['nom'];
				}
			}// for
				
		}// if
	
	} // fin if ($numUploadFields > 0){
	//------------- fin upload --------------------------------------------------
	
	// ------------ delete fichiers cochés --------------------------------------	
	for ($iDel = 1;$iDel <= $numUploadFields;$iDel++){	
		if (strval($_POST['fDeleteFile'.$iDel]) == "true"){		
			$_POST[strval($_POST['fUpload'.$iDel])] = "";
			
			$tempGetter = "$"."tempFile = $"."oRes->get_".preg_replace("/.+".$classePrefixe."_(.*)/msi", "$1", strval($_POST['fUpload'.$iDel]))."();";
			
			$tempGetter = str_replace("get_".$classePrefixe."_", "get", $tempGetter);
			eval($tempGetter);
			$tempFile = $DIR_MEDIA.$tempFile;
			@unlink($tempFile);
		}
	}
	//----------------------------------------------------------------------------

	// Récupération des infos saisies dans l'objet
	if($operation == "UPDATE"){
		//$oRes->set_id($id);
	}
	else{
		$bRetour = dbInsertWithAutoKey($oRes);
		$id = $bRetour;
		eval("$"."oRes = new ".$classeName."($"."bRetour);");
	}
	
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int"){ // cas des int, ne pas inscrire de value vide dans la base
				if ($_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] == ""){
					$_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] = -1;
				}
			}		
			
			setItemValue(&$oRes, $aNodeToSort[$i]["attrs"]["NAME"], $_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]]);
			
		}
	}

	// maj BDD
	if ($operation == "UPDATE") {
					
		// modif
		$bRetour = dbUpdate($oRes);
			
	} else if ($operation == "INSERT") {
		$bRetour = true;
			
		// recherche si un enr avec même titre existe déjà
//		$bDeja_present = (getCount2($oRes, "Tyres_titre", $oRes->getTyres_titre(), "TEXT")>0);
		// recherche du titre identhique non demandé ici
		$bDeja_present = false;
		if($bDeja_present!=false) {
			$status.="un enregistrement est déjà présent avec ce titre : ".$oRes->get_titre()."<br>";
			$bRetour = false;
			dbDelete($oRes);
		}
		else { // tout est ok => on enregistre
			//$bRetour = dbInsertWithAutoKey($oRes);
			$bRetour = dbUpdate($oRes);
		}
	}

	if($bRetour) {
		// type de record enregistré
	}
	else
		$status.= $classeName.' - Erreur lors de '. ( ($operation == "INSERT") ? "l'ajout" : "la modification" );

?>
<div class="arbo"><u><b><?php if($status!="") echo $status; ?></b></u></div><br>
	<table border="0" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo"><?php
	if($bRetour) {
	
		// Récapitulatif de la saisie
		$id = $bRetour;
?>
  <tr>
   <td colspan="2" class="arbo"><?php include ("show_".$classeName.".php"); ?>&nbsp;</td>
 </tr>
<?php
	} // Fin si pas d'erreur d'ajout


echo '<tr><td align="center" colspan="2" bgcolor="#D2D2D2" class="arbo">';

if ($operation == "INSERT") // Pour faciliter la saisie à la chaine
	echo '<input class="arbo" type="button" value="Créer une nouvelle '.$classeName.' >>" onclick="document.location=\''.$_SERVER['PHP_SELF'].'\'">';
/*
//else // On retourne à la liste 
//	echo "<script>document.location='". $_POST['urlRetour'] ."'</script>";
*/

// si une page retour est spécifiée -> lien retour
if ($_POST['urlRetour'] != "") {
	echo '<input class="arbo" type="button" value="<< Retour à la liste" onclick="retour()">';
}

?>
</table>
<?php
}
else { // MODE EDITION

// Pour ajouter des attributs aux champs de type file
$Upload-> FieldOptions = 'style="border-color:black;border-width:1px;"';

// Pour indiquer le nombre de champs désiré
$Upload-> Fields = $numUploadFields;

// Initialisation du formulaire
$Upload-> InitForm();
?>
<span class="arbo2">MODULE >&nbsp;</span><span class="arbo3">Ressources&nbsp;>&nbsp;
<?php echo  $classeName ; ?> : 
<?php echo  ($operation == "INSERT") ? "Ajouter" : "Modifier" ?></span><br><br>
<script src="/backoffice/cms/js/validForm.js" type="text/javascript"></script>
<script>

	///////////////////////////////////////////////////
	// contrôle la validité du formulaire
	///////////////////////////////////////////////////
	
	function ifFormValid()
	{
		erreur=0;
		sMessage = "Valeur(s) incorrecte(s) : \n\n";
		
		/*

// TITRE /////////
		if (document.add_<?php echo $classePrefixe; ?>_form.f<?php echo ucfirst($classePrefixe); ?>_titre.value == "") {
			sMessage+="- Le titre doit être renseigné\n";
			erreur++;
		}

// REF /////////
		if (document.add_<?php echo $classePrefixe; ?>_form.f<?php echo ucfirst($classePrefixe); ?>_src.value == "") {
			if (document.add_<?php echo $classePrefixe; ?>_form["userfile[]"].value == "") {
				sMessage+="- Le fichier doit être renseigné\n";
				erreur++;
			}
		}

// DATE /////////
		if (document.add_<?php echo $classePrefixe; ?>_form.f<?php echo ucfirst($classePrefixe); ?>_datec.value == "") {
			sMessage+="- La date de création doit être renseignée\n";
			erreur++;
		}
		if (document.add_<?php echo $classePrefixe; ?>_form.f<?php echo ucfirst($classePrefixe); ?>_datem.value == "") {
			sMessage+="- La date de modification doit être renseignée\n";
			erreur++;
		}
// TYPE de record /////////

		bType_<?php echo $classeName; ?> = false;
		<?php
		for ($i=0; $i<sizeof($aTyres); $i++)
		{
			$oTyres = $aTyres[$i];
		?>
			type_<?php echo $classeName; ?>_checked = document.add_<?php echo $classePrefixe; ?>_form.f<?php echo ucfirst($classePrefixe); ?>_Tyres_id[<?php echo $i; ?>].checked;
			if (type_<?php echo $classeName; ?>_checked == true) bType_<?php echo $classeName; ?> = true;
		<?php
		}
		?>


// DESC /////////
		if (document.add_<?php echo $classePrefixe; ?>_form.f<?php echo ucfirst($classePrefixe); ?>_desc.value == "") {
			sMessage+="- La description doit être renseignée\n";
			erreur++;
		}
*/


		// affichage des messages d'erreur
		if (erreur > 0) {
			alert(sMessage);
			return false;
		}
		else return true;
	}


	///////////////////////////////////////////////////
	// validation du formulaire
	///////////////////////////////////////////////////
	
	function validerForm()
	{
		// si le formulaire est valide
		if (ifFormValid()) {

			if (validate_form(0)) 
			{ 
				document.add_<?php echo $classePrefixe; ?>_form.operation.value = "<?php echo $operation; ?>"; 
				document.add_<?php echo $classePrefixe; ?>_form.action = "maj_<?php echo $classeName; ?>.php<?php if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>"; 
				document.add_<?php echo $classePrefixe; ?>_form.target = "_self";
				document.add_<?php echo $classePrefixe; ?>_form.submit(); 
			}
		}
	}

</script>
<!-- MODE EDITION -->

<input type="hidden" name="operation" value="<?php echo $operation; ?>">
<input type="hidden" name="urlRetour" value="<?php echo $_POST['urlRetour']; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="actiontodo" value="SAUVE">
<input type="hidden" name="sChamp" value="">

<table border="1" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo">
<?php
$indexUpload = 0;
// Affichage du champ MAX_FILE_SIZE
print $Upload-> Field[$indexUpload];

for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);			
		echo "<tr>\n";
		echo "<td width=\"141\" align=\"right\" bgcolor=\"#E6E6E6\" class=\"arbo\">";
		if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
			echo "&nbsp;<u><b>".$aNodeToSort[$i]["attrs"]["LIBELLE"]."</b></u>&nbsp;*";
		}
		else{
			echo "&nbsp;<u><b>".$aNodeToSort[$i]["attrs"]["NAME"]."</b></u>&nbsp;*";
		}
		echo "</td>\n";
		echo "<td width=\"535\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"arbo\">";
				
		if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key
			$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];

			eval("$"."oTemp = new ".$sTempClasse."();");
			$aForeign = dbGetObjects($sTempClasse);
			
			// test de condtion where
			//<option type="where" item="alerte" value="1" />
			$DoWhere = false;
			if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if($childNode["name"] == "OPTION"){ // on a un node d'option				
						if ($childNode["attrs"]["TYPE"] == "where"){
							$DoWhere = true;
							$whereField = $childNode["attrs"]["ITEM"];
							$whereValue = $childNode["attrs"]["VALUE"];
							break;
						} //fin type  == where			
					}
				}
			}
			// fin test where
			// debut traitement where
			if ($DoWhere == true){
				$aForeignNew = array();
				for($ii=0;$ii<count($aForeign);$ii++){
					$oForeign = $aForeign[$ii];
					eval("$"."currentWhereFieldValue = $"."oForeign->get_".$whereField."();");
					if ($currentWhereFieldValue == $whereValue){
						$aForeignNew[] = $aForeign[$ii];					
					}
				}
				$aForeign = $aForeignNew;
			}
			// fin traitement where
			
			// cas des deroulant d'id, pointage vers foreign
			$sXML = $aForeign[0]->XML;
			unset($stack);
			$stack = array();
			xmlClassParse($sXML);

			$foreignName = $stack[0]["attrs"]["NAME"];
			$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
			$foreignNodeToSort = $stack[0]["children"];
			
			$tempIsAbstractForeign = false;
			$tempForeignAbstract = "";
			$tempIsDisplayForeign = false;
			$tempForeignDisplay = "";
			if(is_array($foreignNodeToSort)){
				foreach ($foreignNodeToSort as $nodeId => $nodeValue) {				
					if ($nodeValue["attrs"]["NAME"] == $oTemp->getAbstract()){					
						if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
							$tempIsAbstractForeign = true;
							$tempForeignAbstract = $nodeValue["attrs"]["FKEY"];
							//break;
						}
					}
					if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){					
						if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
							$tempIsDisplayForeign = true;
							$tempForeignDisplay = $nodeValue["attrs"]["FKEY"];
							//break;
						}
					}
				}
			}
			
			echo "<select name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\">\n";
			echo "<option value=\"-1\">".$translator->getTransByCode('Choisirunitem')."</option>\n";
			for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){
				$oForeign = $aForeign[$iForeign];
				eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");
				eval ("$"."tempId = $"."oForeign->get_id();");
				
				if ($tempStatus == DEF_ID_STATUT_LIGNE){
					echo "<option value=\"".$tempId."\"";
					if ($eKeyValue == $tempId){
						echo " selected";
					}						
					echo ">";
					//eval ("echo substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
					if ($tempIsDisplayForeign){
						eval('$eForeignId=$oForeign->get_'.strval($oTemp->getDisplay()).'();');
$oForeignDisplay = cacheObject($tempForeignDisplay, $eForeignId);
						eval ("echo $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
					}
					else{
						eval ("echo substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
					}
					
					if ($oTemp->getDisplay() != $oTemp->getAbstract()){
						echo " - ";
						if ($tempIsAbstractForeign){
							eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
							eval ("echo $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
						}
						else{
							eval ("echo $"."oForeign->get_".strval($oTemp->getAbstract())."();");
						}
					}
					echo "</option>\n";
				}						
			}
			echo "</select>\n";

		}// fin fkey
		elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum
			echo "<select id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" >\n";
			echo "<option value=\"-1\">".$translator->getTransByCode('Choisirunitem')."</option>\n";		
			if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if($childNode["name"] == "OPTION"){ // on a un node d'option				
						if ($childNode["attrs"]["TYPE"] == "value"){
							if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
								$enumSelected = "selected";
							}
							else {
								$enumSelected = "";
							}
							echo "<option value=\"".$childNode["attrs"]["VALUE"]."\" ".$enumSelected.">".$childNode["attrs"]["LIBELLE"]."</option>\n";
						} //fin type  == value				
					}
				}
			}
			echo "</select>\n";		
		} // fin cas enum
		else{ // cas typique
			if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){ // cas statut
				//echo lib($eKeyValue);
				echo "voir boutons radios";
			}
			else{
				if ($eKeyValue > -1){ // cas typique typique
					//echo $eKeyValue;
					if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
						echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
						$indexUpload++;
						echo "<!-- upload field # ".$indexUpload."/".$numUploadFields." -->\n";
						echo "<input type=\"hidden\" id=\"fUpload".$indexUpload."\" name=\"fUpload".$indexUpload."\" value=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" />\n";
						
						// Affichage du champ de type FILE						
						print $Upload-> Field[$indexUpload];
						if ($eKeyValue != ""){
							echo "&nbsp;(actuellement <a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" target=\"_blank\" title=\"Visualiser le fichier : '".$eKeyValue."'\">".$eKeyValue."</a>)<br />";
							echo "<input type=\"checkbox\" id=\"fDeleteFile".$indexUpload."\" name=\"fDeleteFile".$indexUpload."\" value=\"true\" />&nbsp;supprimer le fichier \n";
						}	
						else{
							echo "&nbsp;(pas de fichier)<br />";
						}
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "textarea"){ // cas textarea						
						echo "<textarea name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" cols=\"50\" rows=\"6\" >".$eKeyValue."</textarea>\n";
						// gestion popup wysiwyg
						if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupWysiwyg == true)){ // cas wysiwyg
						//elseif ($bPopupWysiwyg == true){ // cas wysiwyg
							echo "<a href=\"javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"HTML editor\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer; border: 1px solid red;\" alt=\"HTML editor\" onMouseOver=\"this.style.background='red';\" onMouseOut=\"this.style.background=''\" /></a>\n";
						} // wysiwyg
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "link"){ // cas link						
						echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"".$eKeyValue."\" ".$disabled." />\n";
						// gestion popup link
						if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupLinks == true)){ // cas link						
							echo "<a href=\"javascript:openLinkWindow('/backoffice/cms/utils/popup/dir.php', 'links', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"Link picker\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer; border: 1px solid red;\" alt=\"Link picker\" onMouseOver=\"this.style.background='red';\" onMouseOut=\"this.style.background=''\" /></a>\n";
						} // link
					}
					else{// cas typique typique typique
						if ($aNodeToSort[$i]["attrs"]["OPTION"] != "bool"){ // pas boolean
							echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"".$eKeyValue."\" />\n";
							
							if (($aNodeToSort[$i]["attrs"]["TYPE"] == "date") && ($bJScalendar == true)){ // cas date
								echo "<img src=\"/backoffice/cms/lib/jscalendar/img.gif\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_trigger\" style=\"cursor: pointer; border: 1px solid red;\" title=\"Date selector\" onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" />\n";
								?>
								<script type="text/javascript" language="javascript">
								Calendar.setup({
									inputField     :    "<?php echo "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]; ?>",  // id of the input field
									ifFormat       :    "%d/%m/%Y",      // format of the input field
									button         :    "<?php echo "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_trigger"; ?>", // trigger ID
									align          :    "Tl",           // alignment (defaults to "Bl")
									singleClick    :    true
								});
								</script>
								<?php
							} // JScalendar
							// gestion popup wysiwyg
							elseif ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupWysiwyg == true)){ // cas wysiwyg
							//elseif ($bPopupWysiwyg == true){ // cas wysiwyg
								echo "<a href=\"javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"HTML editor\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer; border: 1px solid red;\" alt=\"HTML editor\" onMouseOver=\"this.style.background='red';\" onMouseOut=\"this.style.background=''\" /></a>\n";
							} // wysiwyg
						}
						else{ // option="bool"
							echo "<select name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\">\n";
							if (intval($eKeyValue) == 0){
								echo "<option value=\"0\" selected>non</option>\n";
								echo "<option value=\"1\">oui</option>\n";
							}
							else{
								echo "<option value=\"0\">non</option>\n";
								echo "<option value=\"1\" selected>oui</option>\n";
							}							
							echo "</select>\n";
						
						}
					}
				}
				else{
					// cas not set
					if ($aNodeToSort[$i]["attrs"]["NAME"] == "id"){ // cas id = -1
						//echo lib($eKeyValue);
						echo "id généré automatiquement";
					}
					else{ // cas autre texte not set
						if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
							echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
							$indexUpload++;
							echo "<!-- upload field # ".$indexUpload."/".$numUploadFields." -->\n";
							echo "<input type=\"hidden\" id=\"fUpload".$indexUpload."\" name=\"fUpload".$indexUpload."\" value=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" />\n";
							
							// Affichage du champ de type FILE						
							print $Upload-> Field[$indexUpload];								
							echo "&nbsp;(pas de fichier)<br />";
							
						}
						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] != "bool"){ // cas pas bool
							echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"\" />\n";
						}
						else{ // option="bool"
							echo "<select name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\">\n";

							echo "<option value=\"0\" selected>non</option>\n";
							echo "<option value=\"1\">oui</option>\n";
				
							echo "</select>\n";
						
						}
					}
				}
			}
		}			
		
		echo "</td>\n";
		echo "</tr>\n";
	}
}
?>
 <tr>
   <td bgcolor="#E6E6E6"  align="right" class="arbo">&nbsp;</td>
   <td bgcolor="#EEEEEE"  align="left" class="arbo">&nbsp;</td>
 </tr>
 <?php
if ($oRes->getGetterStatut() != "none"){
?>
 <tr>
  <td width="141" align="right" bgcolor="#E6E6E6" class="arbo">&nbsp;<u><b>Statut de publication</b></u>&nbsp;*</td>
  <td width="535" align="left" bgcolor="#EEEEEE" class="arbo"><?php
if ($oRes->get_statut() == DEF_ID_STATUT_ATTEN) $checked_ATTEN = "checked"; else $checked_ATTEN = "";
if ($oRes->get_statut() == DEF_ID_STATUT_LIGNE) $checked_LIGNE = "checked"; else $checked_LIGNE = "";
if ($oRes->get_statut() == DEF_ID_STATUT_ARCHI) $checked_ARCHI = "checked"; else $checked_ARCHI = "";
?>
<input type="radio" name="f<?php echo ucfirst($classePrefixe); ?>_statut" id="f<?php echo ucfirst($classePrefixe); ?>_statut" value="<?php echo DEF_ID_STATUT_ATTEN; ?>" <?php echo $checked_ATTEN; ?>  />&nbsp;<?php echo lib(DEF_ID_STATUT_ATTEN); ?>&nbsp;
<input type="radio" name="f<?php echo ucfirst($classePrefixe); ?>_statut" id="f<?php echo ucfirst($classePrefixe); ?>_statut" value="<?php echo DEF_ID_STATUT_LIGNE; ?>" <?php echo $checked_LIGNE; ?> />&nbsp;<?php echo lib(DEF_ID_STATUT_LIGNE); ?>&nbsp;
<input type="radio" name="f<?php echo ucfirst($classePrefixe); ?>_statut" id="f<?php echo ucfirst($classePrefixe); ?>_statut" value="<?php echo DEF_ID_STATUT_ARCHI; ?>" <?php echo $checked_ARCHI; ?> />&nbsp;<?php echo lib(DEF_ID_STATUT_ARCHI); ?>&nbsp;
  </td>
 </tr>
<?php
}
?>
 <tr>
   <td bgcolor="#E6E6E6"  align="right" class="arbo">&nbsp;</td>
   <td bgcolor="#EEEEEE"  align="left" class="arbo">&nbsp;</td>
 </tr>
  <tr>
   <td bgcolor="#E6E6E6"  align="right" class="arbo">&nbsp;</td>
   <td bgcolor="#EEEEEE"  align="left" class="arbo">&nbsp;<small>(* champs obligatoires)</small></td>
 </tr>
 <tr>
   <td bgcolor="#E6E6E6"  align="right" class="arbo">&nbsp;</td>
   <td bgcolor="#EEEEEE"  align="left" class="arbo">&nbsp;</td>
 </tr>
 <tr>
  <td bgcolor="#D2D2D2" colspan="2"  align="center" class="arbo"><?php if ($_POST['urlRetour'] != "") { ?>
     <input name="button" type="button" class="arbo" onClick="retour()" value="<< Retour (annuler)">     &nbsp;&nbsp;&nbsp;&nbsp; <?php } ?>
     <input class="arbo" type="button" name="Ajouter" value="Suite (enregistrer) >>" onClick="validerForm()"></td>
 </tr>
</table>
<br><br>
<?php

} // FIN MODE EDITION
?>
</form>