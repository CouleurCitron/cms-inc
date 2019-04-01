<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
 include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

// Chargement de la classe Upload
require_once('cms-inc/lib/fileUpload/upload.class.php');
$classeMain = $_GET['classeMain'];
$classeMainPrefixe = $_GET['classeMainPrefixe'];
$classeName = $_GET['classeName'];
$idMain = $_GET['idMain'];
// Formulaire de saisie 

// Instanciation d'un nouvel objet "upload"
$Upload = new Upload();

$Upload-> MaxFilesize = strval(12*1024);
// -----------------------------------

$actiontodo = ( $_POST['actiontodo']!="SAUVE" ) ? "MODIF" : "SAUVE" ;

// objet 
if ( $id > 0 ){
	 $operation = "UPDATE";
}
else {
	$operation = "INSERT";
}

if ( $operation == "INSERT" ) { // Mode ajout
	eval("$"."oRes = new ".$classeName."();");
}
elseif ( $operation == "UPDATE" ) { // Mode mise à jour
	eval("$"."oRes = new ".$classeName."($"."id);");
}
else{ // Mode acces par display
	eval("$"."oRes = new ".$classeName."();");
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

//popup link
if(is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/utils/popup/dir.php")){
	$bPopupLinks = true;
}

// gestion calendrier jscalendar
if(is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/lib/jscalendar")){
	$bJScalendar = true;
	?>
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" media="all" href="/backoffice/cms/css/bo.css"/>
<link rel="stylesheet" type="text/css" media="all" href="/backoffice/cms/lib/jscalendar/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<!-- main calendar program -->
<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/calendar.js"></script>
<!-- language for the calendar -->
<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/lang/calendar-fr.js"></script>
<!-- the following script defines the Calendar.setup helper function -->
<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/calendar-setup.js"></script>

 <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/openBrWindow.js" type="text/javascript"></script>
<?php
}
else{
	$bJScalendar = false;
}
//---------------------------------------------------------------

?>
<form name="add_<?php echo $classePrefixe; ?>_form" id="add_<?php echo $classePrefixe; ?>_form" enctype="multipart/form-data" method="post">
<input type="hidden" name="postnumberone" value="XXXX">
<input type="hidden" name="postnumber2" value="XXXX2">
<input type="hidden" name="classeName" value="<?php echo $_POST['classeName']; ?>">
<input type="hidden" name="classeMain" value="<?php echo $_POST['classeMain']; ?>">
<?php

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
		?><script>setTimeout("update2()",0);</script><?php
	}
	
	// post des champs normaux
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){	
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int"){ // cas des int, ne pas inscrire de value vide dans la base
				if ($_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] == ""){
					$_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] = -1;
				}
			}
			if ($aNodeToSort[$i]["attrs"]["OPTION"] == "password"){ // cas password, on cryte 
				if (is_post("f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"])){
					setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], password_hash($_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]], PASSWORD_DEFAULT));
				}
			}
			elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "url"){ // cas url, on ajoute le protocole http:// si manque
				if (isset($_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]])){
					$tempUrl = trim($_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]]);
					if (!preg_match("/^http|ftp]:\/\/.*/msi", $tempUrl) && ($tempUrl != "")){
						$tempUrl = "http://".$tempUrl;
					}					
					setItemValue(&$oRes, $aNodeToSort[$i]["attrs"]["NAME"], $tempUrl);
				}
			}
			else {
				setItemValue(&$oRes, $aNodeToSort[$i]["attrs"]["NAME"], $_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]]);
			}			
		}
	}
	$oRes->set_id($id);
	// post des checkboxes d'asso	
	$sAssoClasse = $classeName;
	eval("$"."oAsso = new ".$sAssoClasse."();");
	$aForeign = dbGetObjects($sAssoClasse);
	
	
	// cas des deroulant d'id, pointage vers foreign
	//$sXML = $aForeign[0]->XML;
	$sXML = $oAsso->XML;
	unset($stack);
	$stack = array();
	xmlClassParse($sXML);

	$foreignName = $stack[0]["attrs"]["NAME"];
	$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
	$foreignNodeToSort = $stack[0]["children"];
	
	//var_dump($foreignNodeToSort);
	
	if(is_array($foreignNodeToSort)){
		foreach ($foreignNodeToSort as $nodeId => $nodeValue) {	
			if ($nodeValue["attrs"]["NAME"] == "id"){
				if (isset($nodeValue["attrs"]["ASSO"]) && $nodeValue["attrs"]["ASSO"] != "" ){
					$sTempClasse = $nodeValue["attrs"]["ASSO"];
				}
			}	
		}
	}
	for ($i=0;$i<count($aForeign);$i++){
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
	<table border="0" cellpadding="0" cellspacing="0" bordercolor="#3B3635"  bgcolor="#EEEEEE"  >
	
	<?php
	if($bRetour) {
	
		// Récapitulatif de la saisie
		$id = $bRetour;
		eval("$"."oRes = new ".$classeName."($"."bRetour);");
		$qryref = dbGetArrayOneFieldFromRequete($_SESSION['sqlpag']);
		for ($i=0;$i<count($qryref);$i++) {
			if ($qryref[$i]==$id) {
				$_SESSION['pag']=$i+1;
			}
		}

?>
<script>


function update2() {

	var url1;
	url1 = 'http://<?php echo $_SERVER['HTTP_HOST']; ?>/include/cms-inc/autoClass/maj_select.php';
	var xhr_object = null; 
	if(window.XMLHttpRequest) xhr_object = new XMLHttpRequest(); 
	else if(window.ActiveXObject) xhr_object = new ActiveXObject("Microsoft.XMLHTTP"); 
	else { 
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
		return; 
	}
	xhr_object.open("POST", url1, true); 
	
	xhr_object.onreadystatechange = function() { 
  		if(xhr_object.readyState == 4) {
			//alert(xhr_object.responseText)
	 		eval(xhr_object.responseText);
			//parent.document.getElementById('addAssoCheckbox_sstexte').innerHTML = xhr_object.responseText;
		} 
	} 
 
	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
	var data = "classeName=<?php echo $classeName; ?>&classeMain=<?php echo $classeMain; ?>&form=add_<?php echo $classeMainPrefixe; ?>_form&select=addAssoCheckbox_<?php echo $classeName; ?>&action=add";
	xhr_object.send(data);
}



function Timer() {
    window.location='<?php echo $_SERVER['PHP_SELF']; ?>?classeName=<?php echo $classeName; ?>&classeMain=<?php echo $classeMain; ?>&classeMainPrefixe=<?php echo $classeMainPrefixe; ?>&idMain=<?php echo $idMain; ?>';
}
setTimeout("Timer()",1000);

</script>
 <?php if ($aCustom["Sendmail"] == true)  { ?>
			<tr><td colspan="2" class="arbo"><?php include ("send_".$classeName.".php"); ?>&nbsp;</td></tr> 
		<?php } //if ($aCustom["Sendmail"] == true) 
	} // Fin si pas d'erreur d'ajout

?>
</table>
<?php


}
else {// MODE EDITION

// Pour ajouter des attributs aux champs de type file
$Upload-> FieldOptions = 'style="border-color:black;border-width:1px;"';

// Pour indiquer le nombre de champs désiré
$Upload-> Fields = $numUploadFields;

// Initialisation du formulaire
$Upload-> InitForm();
?>

<!-- MODE EDITION -->
<input type="hidden" name="classeName" value="<?php echo $classeName; ?>">
<input type="hidden" name="classeMain" value="<?php echo $classeMain; ?>">
<input type="hidden" name="operation" value="<?php echo $operation; ?>">
<input type="hidden" name="urlRetour" value="<?php echo $_POST['urlRetour']; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="actiontodo" value="SAUVE">
<input type="hidden" name="sChamp" value="">
<script src="/backoffice/cms/js/validForm.js" type="text/javascript"></script>
<script>

	///////////////////////////////////////////////////
	// contrôle la validité du formulaire
	///////////////////////////////////////////////////
	
	function ifFormValid()
	{
		erreur=0;
		sMessage = "Valeur(s) incorrecte(s) : \n\n";
		
	

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
	
	function validerForm2()
	{
	  	
		// si le formulaire est valide
		if (ifFormValid()) {

			if (validate_form(0) && validerPattern()) 
			{ 
				/*document.add_<?php echo $classeMainPrefixe; ?>_form.operation.value = "<?php echo $operation; ?>"; 
				//document.add_<?php echo $classeMainPrefixe; ?>_form.action = "maj_<?php echo $classeMain; ?>.php<?php if($listParam!="") echo "?".$listParam;?>"; 
				document.add_<?php echo $classeMainPrefixe; ?>_form.target = "_self";
				document.add_<?php echo $classeMainPrefixe; ?>_form.submit(); */
				document.add_<?php echo $classePrefixe; ?>_form.operation.value = "<?php echo $operation; ?>"; 
				document.add_<?php echo $classePrefixe; ?>_form.action = "<?php echo $_SERVER['PHP_SELF']; ?>?classeName=<?php echo $classeName; ?>&classeMain=<?php echo $classeMain; ?>&idMain=<?php echo $idMain; ?>"; 
				document.add_<?php echo $classePrefixe; ?>_form.target = "_self";
				document.add_<?php echo $classePrefixe; ?>_form.submit(); 
				
			}
		}
	}

</script>

<table border="0" cellpadding="0" cellspacing="0" bordercolor="#3B3635" bgcolor="#EEEEEE" width="100%">
<?php
//------------------------------------------------------------------------------------------------

$sTempClasse = $classeName;
eval("$"."oTemp = new ".$sTempClasse."();");
$aForeign = dbGetObjects($sTempClasse);

// cas des deroulant d'id, pointage vers foreign
//$sXML = $aForeign[0]->XML;
$sXML = $oTemp->XML;

unset($stack);
$stack = array();
xmlClassParse($sXML);

$foreignName = $stack[0]["attrs"]["NAME"];
$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
$foreignNodeToSort = $stack[0]["children"];

/*
for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){
	$oForeign = $aForeign[$iForeign];
	if ($oForeign->getGetterStatut() != "none"){
		eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");					
	}
	else{
		$tempStatus = DEF_ID_STATUT_LIGNE;
	}
	eval ("$"."tempId = $"."oForeign->get_id();");
	
	if ($tempStatus == DEF_ID_STATUT_LIGNE){
		// test sur select. chercher id de la fiche en cours ($id) et id du foreign en cours ($tempId) dans Asso.
 		echo "<tr>";
  		echo "<td width=\"141\" bgcolor=\"#EEEEEE\" class=\"arbo\" colspan=\"2\">";
		echo "<input type=\"checkbox\" name=\"fAsso".ucfirst($classeMain)."_".ucfirst($sTempClasse)."_".$tempId."\" id=\"fAsso".ucfirst($classeMain)."_".ucfirst($sTempClasse)."_".$tempId."\" value=\"".$tempId."\" ";
		
		//if (getCount_where($tempAsso, array($foreignPrefixe."_".$tempAssoIn, $foreignPrefixe."_id"), array($id,$tempId), array("NUMBER","NUMBER")) ==  1){
			//echo " checked";
		//}						
		echo ">";
		
	
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
		echo "<br /></td></tr>\n";
		
	}				
}	
*/

//-------------------------------------------------------------------------------------------
?>

<tr><td colspan="2" bgcolor="#EEEEEE" class="arbo">

</td></tr>
<tr>
   <td align="right" bgcolor="#EEEEEE" class="arbo">&nbsp;</td>
   <td align="left" bgcolor="#EEEEEE" class="arbo">&nbsp;</td>
</tr>
<?php
$indexUpload = 0;
// Affichage du champ MAX_FILE_SIZE
print $Upload-> Field[$indexUpload];

// tableau contenant les champs et pattern à vérifier
$ControlePattern = false;
$ControlePatternFields = array();
$ControlePatternValues = array();
//echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"addAsso_".$classeName."\" bgcolor=\"#EEEEEE\" ><tr><td>";
	
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
		if ($aNodeToSort[$i]["attrs"]["NAME"] != "statut") {	
			echo "<tr>\n";
			echo "<td width=\"141\" align=\"right\" bgcolor=\"#EEEEEE\" class=\"arbo\">";
			if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
				echo "&nbsp;<u><b>".stripslashes($aNodeToSort[$i]["attrs"]["LIBELLE"])."</b></u>&nbsp;";
			}
			else{
				if ($aNodeToSort[$i]["attrs"]["NAME"] == "id"){
					echo "";
				} 
				else {
					echo "&nbsp;<u><b>".stripslashes($aNodeToSort[$i]["attrs"]["NAME"])."</b></u>&nbsp;";
				}
			}
			echo "</td>\n";
			echo "<td width=\"535\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"arbo\">";
			
			// tableau contenant les champs et pattern à vérifier
			if (isset($aNodeToSort[$i]["attrs"]["PATTERN"])){
				$ControlePattern = true;
				$ControlePatternFields[] = $aNodeToSort[$i]["attrs"]["NAME"];
				$ControlePatternValues[] = $aNodeToSort[$i]["attrs"]["PATTERN"];
			}
			
			// test de conditionnement - esclave
			$ActiveIf = false;
			if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if($childNode["name"] == "OPTION"){ // on a un node d'option				
						if ($childNode["attrs"]["TYPE"] == "if"){
							$ActiveIf = true;
							$whereField = $childNode["attrs"]["ITEM"];
							$whereValue = $childNode["attrs"]["VALUE"];
							break;
						} //fin type  == if			
					}
				}
			}
			// fin test condion
			
			
			// tst de condition - maitre
			$ControlIf = false;
			$ControledFields = array();
			$ControlValues = array();
			for ($ii=0;$ii<count($aNodeToSort);$ii++){
				if ($aNodeToSort[$ii]["name"] == "ITEM"){
					if (isset($aNodeToSort[$ii]["children"]) && (count($aNodeToSort[$ii]["children"]) > 0)){
						foreach ($aNodeToSort[$ii]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "if"){ // test maitre = conditonner du item selected
									if ($childNode["attrs"]["ITEM"] == $aNodeToSort[$i]["attrs"]["NAME"]){
										$ControlIf = true;
										$ControledFields[] = $aNodeToSort[$ii]["attrs"]["NAME"];
										$ControlValues[] = $childNode["attrs"]["VALUE"];							
									}
								} //fin type  == if			
							}
						}
					}
				}
			}	
			// fin tst de condition - maitre
			
			if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key
				if ($aNodeToSort[$i]["attrs"]["NAME"] == $classeMain)  {
					eval("$"."oTemp = new ".$classeMain."(".$idMain.");");
					eval ("echo substr($"."oTemp->get_".strval($oTemp->getDisplay())."(), 0, 100);");
					echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$idMain."\">\n";
						
					
				} else {
					$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
					eval("$"."oTemp = new ".$sTempClasse."();");
					$aForeign = dbGetObjects($sTempClasse);
					$aValue = array();
					$default ="";
					// test de condtion where
					$DoWhere = false;
					
					// tst de condition - type WHERE
					if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "where"){
									$DoWhere = true;
									$whereField = $childNode["attrs"]["ITEM"];
									if (isset ($childNode["attrs"]["OPTION"]) && $childNode["attrs"]["OPTION"] != "") {
										// test sur value passée par session 
										if ($childNode["attrs"]["OPTION"] == "session")  {
											$whereValue= $_SESSION[$childNode["attrs"]["VALUE"]];
											if (isset($childNode["attrs"]["ASSO"]) && $childNode["attrs"]["ASSO"]!= "")  {
												$whereClasse = $childNode["attrs"]["ASSO"];
												$def = $childNode["attrs"]["DEFAULT"];
												//echo $whereValue."-".DEF_ID_ADMIN_DEFAUT."".$whereField." ".$whereClasse;
												$aWhere = dbGetObjects($whereClasse);
												//var_dump($aWhere);
												for($a=0;$a<sizeof($aWhere);$a++){
													$oWhere = $aWhere[$a];
													// test sur valeur par defaut
													if (isset($childNode["attrs"]["DEFAULT"]) && $childNode["attrs"]["DEFAULT"]!= "" && DEF_ID_ADMIN_DEFAUT == $whereValue) {
														//echo "toto";
														$default = $childNode["attrs"]["DEFAULT"];
														$aValue[] = $oWhere->get_id();	
													} 
													
													else {
														eval("$"."currentWhereFieldValue = $"."oWhere->get_".$whereField."();");
														if ($currentWhereFieldValue == $whereValue){
															//$aValue[] = $oWhere->get_id();	
															eval("$"."aValue[] = $"."oWhere->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");	
																	
														}
													}
												}
												$whereField = $childNode["attrs"]["FKEY"];
											}
											else {
												$aValue[] =$whereValue;
											}
										}							
									}
									else {
										$aValue[] = $childNode["attrs"]["VALUE"];
									}// fin value par session
									break;
								} 		
							}
						}
					}
					// fin test WHERE
					
					// debut traitement WHERE
					if ($DoWhere == true){
						//var_dump($aValue);
						
						$aForeignNew = array();
						//echo "------".$whereField."<br>";
						//var_dump($aForeign);
						for($ii=0;$ii<count($aForeign);$ii++){
							$oForeign = $aForeign[$ii];
							// valeur par défaut
							if ($default !="") {
								$aForeignNew[] = $aForeign[$ii];	
							} 
							else { 
								for($b=0;$b<sizeof($aValue);$b++) {
									eval("$"."currentWhereFieldValue = $"."oForeign->get_".$whereField."();");
									if ($currentWhereFieldValue == $aValue[$b]){
										$aForeignNew[] = $aForeign[$ii];					
									}
								}
							}
						}
						$aForeign = $aForeignNew;
					}
					// fin traitement where
					
					// cas des deroulant d'id, pointage vers foreign
					//$sXML = $aForeign[0]->XML;
					$sXML = $oTemp->XML;
		
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
					$disabled = "";
					if (($aNodeToSort[$i]["attrs"]["NAME"] == $displayField) && isset($display) && ($display!="")){
					
						$disabled = "disabled";
						echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
						
						for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){
							$oForeign = $aForeign[$iForeign];
							if ($oTemp->getGetterStatut() != "none"){
								eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");					
							}
							else{
								$tempStatus = DEF_ID_STATUT_LIGNE;
							}
							//eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");
							eval ("$"."tempId = $"."oForeign->get_id();");
							
							if ($tempStatus == DEF_ID_STATUT_LIGNE){						
								if ($eKeyValue == $tempId){
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
								}					
							}	 // fin if statut					
						}// fin for
							
					}
					else{
						echo "<select name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" ".$disabled.">\n";
						echo "<option value=\"-1\">".$translator->getTransByCode('Choisirunitem')."</option>\n";
						for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){
							$oForeign = $aForeign[$iForeign];
							//if ($iForeign==0) var_dump($oForeign);
							if ($oTemp->getGetterStatut() != "none"){
								eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");					
							}
							else{
								$tempStatus = DEF_ID_STATUT_LIGNE;
							}
							//eval ("$"."tempStatus = $"."oForeign->".strval($oTemp->getGetterStatut())."();");
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
							}	 // fin if statut					
						}// fin for
						echo "</select>\n";
					}
				}
	
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
					//echo "voir boutons radios";
				}
				else{
					if ($eKeyValue > -1){ // cas typique typique
						if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
							echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
							$indexUpload++;
							echo "<!-- upload field # ".$indexUpload."/".$numUploadFields." -->\n";
							echo "<input type=\"hidden\" id=\"fUpload".$indexUpload."\" name=\"fUpload".$indexUpload."\" value=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" />\n";
							
							// Affichage du champ de type FILE						
							print $Upload-> Field[$indexUpload];
							if ($eKeyValue != ""){
								echo "&nbsp;(actuellement <a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" target=\"_blank\" title=\"Visualiser le fichier : '".$eKeyValue."'\">".$eKeyValue."</a>";
								echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" title=\"Télécharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"Télécharger le fichier : '".$eKeyValue."\" /></a>)<br />\n";
								echo "<input type=\"checkbox\" id=\"fDeleteFile".$indexUpload."\" name=\"fDeleteFile".$indexUpload."\" value=\"true\" />&nbsp;supprimer le fichier \n";
							}	
							else{
								echo "&nbsp;(pas de fichier)<br />";
							}
							if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
								foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
									if($childNode["name"] == "OPTION"){ // on a un node d'option	
										echo "<br />\n";
										if (($childNode["attrs"]["TYPE"] != "") && ($childNode["attrs"]["TYPE"] != "if")){
											echo "Type de fichier&nbsp;: ".$childNode["attrs"]["TYPE"]."<br />\n";
										}
										if ($childNode["attrs"]["WIDTH"] != ""){
											echo "Largeur nominale de l'image&nbsp;: ".$childNode["attrs"]["WIDTH"]." pixels<br />\n";
										}
										if ($childNode["attrs"]["HEIGHT"] != ""){
											echo "Hauteur nominale de l'image&nbsp;: ".$childNode["attrs"]["HEIGHT"]." pixels<br />\n";
										}
										if ($childNode["attrs"]["MAXWIDTH"] != ""){
											echo "Largeur maximale de l'image&nbsp;: ".$childNode["attrs"]["MAXWIDTH"]." pixels<br />\n";
										}
										if ($childNode["attrs"]["MAXHEIGHT"] != ""){
											echo "Hauteur maximale de l'image&nbsp;: ".$childNode["attrs"]["MAXHEIGHT"]." pixels<br />\n";
										}							
									}
								}
							}
						}
						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "textarea"){ // cas textarea						
							echo "<textarea name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" cols=\"50\" rows=\"6\" >".$eKeyValue."</textarea>\n";
							// gestion popup wysiwyg
							if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupWysiwyg == true) && (($aNodeToSort[$i]["attrs"]["NOHTML"] != "true")||(!isset($aNodeToSort[$i]["attrs"]["NOHTML"])))){ // cas wysiwyg
								echo "<a href=\"javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"".$translator->getTransByCode('Editeur_html')."\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Editeur_html')."\" /></a>\n";
							} // wysiwyg
						}
						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "link"){ // cas link						
							echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
							// gestion popup link
							if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupLinks == true)){ // cas link						
								echo "<a href=\"javascript:openLinkWindow('/backoffice/cms/utils/popup/dir.php', 'links', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"".$translator->getTransByCode('Selectionner_un_lien')."\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Selectionner_un_lien')."\" /></a>\n";
							} // link
						}
						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "filedir"){ // cas filedir						
						
							echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
							// gestion popup link
							if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){ // cas filedir						
								echo "<a href=\"javascript:openLinkWindow('/backoffice/cms/utils/popup/dir.php', 'links', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"".$translator->getTransByCode('Selectionner_un_lien')."\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Selectionner_un_lien')."\" /></a>\n";
							} // link
						}
						else{// cas typique typique typique
							if ($aNodeToSort[$i]["attrs"]["OPTION"] != "bool"){ // pas boolean
								if ($aNodeToSort[$i]["attrs"]["NAME"] == "id"){
									echo $eKeyValue;
									echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"".$eKeyValue."\" ".$disabled." />\n";
								}
								else{
									echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
								}
								
								
								if (($aNodeToSort[$i]["attrs"]["TYPE"] == "date") && ($bJScalendar == true)){ // cas date
									echo "<img src=\"/backoffice/cms/lib/jscalendar/img.gif\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_trigger\" style=\"cursor: pointer;\" title=\"".$translator->getTransByCode('Selectionner_une_date')."\" />\n";
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
								elseif ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupWysiwyg == true) && (($aNodeToSort[$i]["attrs"]["NOHTML"] != "true")||(!isset($aNodeToSort[$i]["attrs"]["NOHTML"])))){ // cas wysiwyg
								//elseif ($bPopupWysiwyg == true){ // cas wysiwyg
									echo "<a href=\"javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"".$translator->getTransByCode('Editeur_html')."\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Editeur_html')."\" /></a>\n";
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
							//echo "id généré automatiquement";
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
								
								if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
									foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
										if($childNode["name"] == "OPTION"){ // on a un node d'option	
											echo "<br />\n";
											if (($childNode["attrs"]["TYPE"] != "") && ($childNode["attrs"]["TYPE"] != "if")){
												echo "Type de fichier&nbsp;: ".$childNode["attrs"]["TYPE"]."<br />\n";
											}
											if ($childNode["attrs"]["WIDTH"] != ""){
												echo "Largeur nominale de l'image&nbsp;: ".$childNode["attrs"]["WIDTH"]." pixels<br />\n";
											}
											if ($childNode["attrs"]["HEIGHT"] != ""){
												echo "Hauteur nominale de l'image&nbsp;: ".$childNode["attrs"]["HEIGHT"]." pixels<br />\n";
											}
											if ($childNode["attrs"]["MAXWIDTH"] != ""){
												echo "Largeur maximale de l'image&nbsp;: ".$childNode["attrs"]["MAXWIDTH"]." pixels<br />\n";
											}
											if ($childNode["attrs"]["MAXHEIGHT"] != ""){
												echo "Hauteur maximale de l'image&nbsp;: ".$childNode["attrs"]["MAXHEIGHT"]." pixels<br />\n";
											}							
										}
									}
								}
							}
							elseif ($aNodeToSort[$i]["attrs"]["OPTION"] != "bool"){ // cas pas bool
								if ($aNodeToSort[$i]["attrs"]["OPTION"] == "textarea"){ // cas textarea						
									echo "<textarea name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" cols=\"50\" rows=\"6\" >".$eKeyValue."</textarea>\n";
									// gestion popup wysiwyg
									if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupWysiwyg == true) && (($aNodeToSort[$i]["attrs"]["NOHTML"] != "true")||(!isset($aNodeToSort[$i]["attrs"]["NOHTML"])))){ // cas wysiwyg
									//elseif ($bPopupWysiwyg == true){ // cas wysiwyg
										echo "<a href=\"javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"".$translator->getTransByCode('Editeur_html')."\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Editeur_html')."\" /></a>\n";
									} // wysiwyg
							
								}
								else{ // pas textarea
									echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" size=\"80\" value=\"\" />\n";
								}
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
			
			// retour test de conditionnement - esclave
			if ($ActiveIf == true){
				echo "<script language=\"javascript\" type=\"text/javascript\">\n";
				//echo "alert(document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").value);\n";
				//echo "alert(document.getElementById(\"f".ucfirst($classePrefixe)."_".$whereField."\").value);\n";
				echo "if(document.getElementById(\"f".ucfirst($classePrefixe)."_".$whereField."\").value != \"".$whereValue."\"){\n";
				echo "document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").disabled = true;\n";
				echo "}\n";
				//	$whereField = $childNode["attrs"]["ITEM"];
				//	$whereValue = $childNode["attrs"]["VALUE"];
				echo "</script>\n";		
			}
			
			//-------
			
			// retour tst de condition - maitre
			if ($ControlIf == true){
				echo "<script language=\"javascript\" type=\"text/javascript\">\n";
				echo "document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").onchange = function(){\n";
				for ($ii=0;$ii<count($ControledFields);$ii++){
					//echo "if pas egal ".$ControlValues[$ii]." then inactiver ".$ControledFields[$ii]."- ";
					echo "	if(document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").value != \"".$ControlValues[$ii]."\"){\n";
					echo "		document.getElementById(\"f".ucfirst($classePrefixe)."_".$ControledFields[$ii]."\").disabled = true;\n";
					echo "	}\n";
					echo "	else{\n";
					echo "		document.getElementById(\"f".ucfirst($classePrefixe)."_".$ControledFields[$ii]."\").disabled = false;\n";
					echo "	}\n";
				}
				echo "}\n";
				echo "</script>\n";	
			}
			
			// appel javascript qui vérifie les pattern
			
				echo "<script language=\"javascript\" type=\"text/javascript\">\n";
				echo "function validerPattern() {\n";
				echo " var eCountPattern = 0;\n";
				echo " var sMessagePattern;\n";
				echo " sMessagePattern=\"\";\n";
			if ($ControlePattern == true){	
				for ($iii=0;$iii<count($ControlePatternValues);$iii++){
					echo "var regexp = new RegExp(\"^".$ControlePatternValues[$iii]."+$\");\n"; 
					echo "if(!regexp.exec(document.getElementById(\"f".ucfirst($classePrefixe)."_".$ControlePatternFields[$iii]."\").value)){\n";
					echo " eCountPattern++;\n";
					echo " sMessagePattern+= \"Le champs ".$ControlePatternFields[$iii]." ne doit contenir que  les expressions suivantes: ".$ControlePatternValues[$iii]."\\n\";\n";
	
					echo "}\n";
				}	}
					echo "if (eCountPattern == 0) {\n";
					echo "  return true; \n";
					echo "}\n";
					echo "else{\n";
					echo "alert(sMessagePattern);";		
					echo "	return false;\n";
					echo "}\n";
				echo "}";
				echo "</script>\n";	
		
			// fin  appel javascript qui vérifie les pattern
						
			//-----
			echo "</td>\n";
			echo "</tr>\n";
		} // fin if NAME = statut
		echo "<!-- fin des champs de la classe -->\n\n";
		
	}
}


?>

<?php
if ($oRes->getGetterStatut() != "none" ){
	
?>
 <!--<tr>
  <td width="141" align="right" bgcolor="#EEEEEE" class="arbo">&nbsp;<u><b>Statut</b></u></td>
  <td width="535" align="left" bgcolor="#EEEEEE" class="arbo">
 	<?php
		/*if ($oRes->get_statut() == DEF_ID_STATUT_ATTEN) $checked_ATTEN = "checked"; else $checked_ATTEN = "";
		if ($oRes->get_statut() == DEF_ID_STATUT_LIGNE) $checked_LIGNE = "checked"; else $checked_LIGNE = "";
		if ($oRes->get_statut() == DEF_ID_STATUT_ARCHI) $checked_ARCHI = "checked"; else $checked_ARCHI = "";*/
		?>
		<input type="radio" name="f<?php echo ucfirst($classePrefixe); ?>_statut" id="f<?php echo ucfirst($classePrefixe); ?>_statut" value="<?php echo DEF_ID_STATUT_ATTEN; ?>" <?php echo $checked_ATTEN; ?>  />&nbsp;<?php echo lib(DEF_ID_STATUT_ATTEN); ?>&nbsp;
		<input type="radio" name="f<?php echo ucfirst($classePrefixe); ?>_statut" id="f<?php echo ucfirst($classePrefixe); ?>_statut" value="<?php echo DEF_ID_STATUT_LIGNE; ?>" <?php echo $checked_LIGNE; ?> />&nbsp;<?php echo lib(DEF_ID_STATUT_LIGNE); ?>&nbsp;
		<input type="radio" name="f<?php echo ucfirst($classePrefixe); ?>_statut" id="f<?php echo ucfirst($classePrefixe); ?>_statut" value="<?php echo DEF_ID_STATUT_ARCHI; ?>" <?php echo $checked_ARCHI; ?> />&nbsp;<?php echo lib(DEF_ID_STATUT_ARCHI); ?>&nbsp;
  		
	</td>
 </tr>-->
 <input type="hidden" name="f<?php echo ucfirst($classePrefixe); ?>_statut" id="f<?php echo ucfirst($classePrefixe); ?>_statut" value="<?php echo DEF_ID_STATUT_LIGNE; ?>" <?php echo $checked_LIGNE; ?> />
<?php
	

}
?>
 <tr bgcolor="#EEEEEE" >
  <td  colspan="2"  align="center" class="arbo" bgcolor="#EEEEEE" ><?php if ($_POST['urlRetour'] != "") { ?>
       <!-- <input name="button" type="button" class="arbo" onClick="javascript:history.back();" value="<< Retour (annuler)"> -->    &nbsp;&nbsp;&nbsp;&nbsp; <?php } ?>
     
<br><input class="arbo" type="button" name="Ajouter" value="Valider >>" onClick="validerForm2()"></td>
 </tr>


</table>
<?php

} // FIN MODE EDITION
?>
</form>