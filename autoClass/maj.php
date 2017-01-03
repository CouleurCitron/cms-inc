<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------
// Formulaire de saisie 

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

unset($_SESSION['BO']['CACHE']);

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbopage.lib.php');

$rep = $_SESSION['rep_travail'];
if (is_file($_SERVER['DOCUMENT_ROOT'].'/include/modules/'.$rep.'/mod_rewrite.inc.php')){	
	include_once('modules/'.$rep.'/mod_rewrite.inc.php');		
}

// Chargement de la classe Upload
require_once('cms-inc/lib/fileUpload/upload.class.php');

$_SESSION['listParam']= http_build_query($_GET);

$listParam = $_SERVER['QUERY_STRING'];

$listParam = str_replace('id=&', '', $listParam); // parce que ce serait inepte
// second controle, si id=X dans l'url on vira l'occurence eventuelle en session
if (preg_match('/id=([0-9]+)/msi', $listParam, $idMatches)==1){
	if ((is_get('id') && ($idMatches[1]!=$_GET['id']))	||	(is_post('id') && ($idMatches[1]!=$_POST['id']))){
		$listParam = str_replace($idMatches[0], '', $listParam);
		$listParam = str_replace(array('&&&', '&&'), '&', $listParam);
	}
}

//php.ini max sise
$MaxFilesize = (int)intval(str_replace('M', '', ini_get('upload_max_filesize')));

// Instanciation d'un nouvel objet "upload"
$Upload = new Upload();
$Upload-> MaxFilesize = strval($MaxFilesize*1024);
// -----------------------------------

$actiontodo = ( $_POST['actiontodo']!="SAUVE" ) ? "MODIF" : "SAUVE" ;


$display=intval($_GET['display']);
if(!isset($display)){
	$display=intval($_POST['display']);
}

if (is_get('newid')) {
	$newid=$_GET['newid'];
}
elseif(is_post('newid')) {
	$newid=$_POST['newid'];
}

if (is_get("id")) {
	$id=$_GET['id'];
	$_POST['id']=$id;
	if (isset($_SESSION['sqlpag'])&&($_SESSION['sqlpag']!='')){
		$qryref = dbGetArrayOneFieldFromRequete($_SESSION['sqlpag']);
		for ($i=0;$i<count($qryref);$i++) {
			if ($qryref[$i]==$id) {
			//echo $qryref[$i].$i."<br>";
			$_SESSION['pag']=$i+1;
			}
		}
	}
}
elseif (is_post('id')) { 
	$id=$_POST['id'];
}
// Pour calcul de l'id par rapport à la recherche et récupération de la position dans la navigation par rapport à cet id
else {
	if (isset($_SESSION['sqlpag'])&&($_SESSION['sqlpag']!='')){
		$qryref = dbGetArrayOneFieldFromRequete($_SESSION['sqlpag']);
		$id=$qryref[$id-1];
		for ($i=0;$i<count($qryref);$i++) {
			if ($qryref[$i]==$id) {
				$_SESSION['pag']=$i+1;
			}
		}
	}
	if (is_get('adodb_next_page')) {
		$id=$_GET['adodb_next_page'];
	}
	else {
		if (isset($_SESSION['sqlpag'])&&($_SESSION['sqlpag']!='')){
			$qryref = dbGetArrayOneFieldFromRequete($_SESSION['sqlpag']);
			$id=intval($_POST['id']);
			for ($i=0;$i<count($qryref);$i++) {
				if ($qryref[$i]==$id) {
					$_SESSION['pag']=$i+1;
				}
			}
		}
	}
}

// activation du menu : déroulement
if (function_exists('activateMenu')){
	activateMenu('gestion'.$classeName);
}  

// objet 
if ( $display > 0 ){
	 $operation = "UPDATEORINSERT";
}
elseif ( $id > 0 ){
	 $operation = "UPDATE";
}
elseif (($id >= 0 )&&($classeName == 'cms_arbo_pages')){
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

if (!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else	$sXML = $oRes->XML;
//$sXML = $oRes->XML;

/// / ***** cas diapo
if ($classeName=='cms_diapo'){
	if (is_get('setDiaporamaType')){
		$oDiaporamaTypeRef = new cms_diaporama_type($_GET['setDiaporamaType']);
		if ($listParam!=''){
			$listParam .= '&setDiaporamaType='.$_GET['setDiaporamaType'];
		}
		else{
			$listParam = '?setDiaporamaType='.$_GET['setDiaporamaType'];
		}
	}
	elseif (is_get('addToDiaporama')){
		if (isObjectById ("cms_diaporama_type", $_GET['addToDiaporama']) ) { 
			$oDiaporamaRef = new cms_diaporama($_GET['addToDiaporama']);
			$oDiaporamaTypeRef = new cms_diaporama_type($oDiaporamaRef->get_diaporama_type());
			if ($listParam!=''){
				$listParam .= '&addToDiaporama='.$_GET['addToDiaporama'];
			}
			else{
				$listParam = '?addToDiaporama='.$_GET['addToDiaporama'];
			}
		}
		else {
			$oDiaporamaTypeRef = false; 
		}
	}		
	if($oDiaporamaTypeRef){
		$eWidthRef = $oDiaporamaTypeRef->get_width();
		$eHeightRef = $oDiaporamaTypeRef->get_height();
		if(preg_match('/[0-9]+;[0-9]+/', $eWidthRef.';'.$eHeightRef)==1){ // si on trouvé des valeurs on modifie le XML
			$sXML = preg_replace('/^(.*<item name="src"[^>]+>).+(<\/item>.*)$/msi', '$1<option type="image" maxwidth="'.$oDiaporamaTypeRef->get_width().'" maxheight="'.$oDiaporamaTypeRef->get_height().'" />$2', $sXML);
		}
	}
}

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
$classeLogStatus = ($stack[0]["attrs"]["LOG_STATUS_CHANGE"] == 'true' ? true : false);
$classeChangeStatus = false;


// création auto des repertoire

$DIR_UPLOAD = $_SERVER['DOCUMENT_ROOT'].'/custom/upload/';

if (!is_dir($DIR_UPLOAD)) // on le crée
	mkdir($DIR_UPLOAD);

$DIR_MEDIA = $_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/';

// controle l'existence du dir dest
if (!is_dir($DIR_MEDIA)){ // on le crée
	mkdir($DIR_MEDIA);
}
		

// test le champ "statut"
$other_statut = '';
if (isset ($stack[0]["attrs"]["STATUT"]) ) {
	$other_statut = $stack[0]["attrs"]["STATUT"];
}
if (defined("DEF_FCK_VERSION") && DEF_FCK_VERSION == "ckeditor" ) {
	echo "<script type=\"text/javascript\">\n
		function SetUrl(fileUrl, w , h, field){\n 
		
			//alert (fileUrl +' '+ w +' '+ h+' '+field);
			var field_conteneur = field+\"_conteneur\";
			var fileName = basename(fileUrl);
			var monHtml = '';
			//alert(document.getElementById(field).value);
			
			// test si champ multiple
			var isMultiple ;
			var field_multiple = field+\"_multiple\";
			isMultiple = document.getElementById(field_multiple).value;	
			
			if (isMultiple == 'true') {
				if (document.getElementById(field).value != '') {
					if (document.getElementById(field).value.indexOf('{') > -1 ) 
						document.getElementById(field).value = document.getElementById(field).value+'{'+fileUrl+'}';
					else
						document.getElementById(field).value = '{'+document.getElementById(field).value+'}{'+fileUrl+'}';
				}
				else {
					document.getElementById(field).value='{'+fileUrl+'}';\n
				}
			}
			else {
				document.getElementById(field).value=fileUrl;\n
			}
			 
			 
			var regex = new RegExp( \"fDia_image_([0-9]*)\"  ); 
			var id = 0 ;
			
			$.each( $(\"div[id^='fDia_image_']\"), function () {  
			  var aId = $(this).attr('id').match(regex);
			  id = aId[1];
			});
			//console.log( 'id trouvé : ' + id );
			id = parseInt(id) + 1 ; ";
			
			
		 
			echo " if (isMultiple == 'true') {  monHtml+= document.getElementById(field_conteneur).innerHTML; } ";
			
			echo " if (isMultiple == 'true') { var count = 0; $('#' + field_conteneur + ' li').each(function(){ count++; });  id = count; console.log( id ) } ";
			
			/*echo "monHtml+= \"<div id='\"+field+\"_\"+id+\"'><a href='/backoffice/cms/utils/viewer.php?file=\"+fileUrl+\"'  target='_blank' title='".$translator->getTransByCode('visualiserlefichier')." \"+fileUrl+\"'><img src='\"+fileUrl+\"' width='75'   border='0' alt='".$translator->getTransByCode('telechargerlefichier')." \"+fileUrl+\"' /></a>&nbsp;-&nbsp;<a href='/backoffice/cms/utils/viewer.php?file=\"+fileUrl+\"'  target='_blank' title='".$translator->getTransByCode('visualiserlefichier')." \"+fileUrl+\"'>\"+fileName+\"</a>&nbsp;-&nbsp;<a href='/backoffice/cms/utils/viewer.php?file=\"+fileUrl+\"'  target='_blank' title='".$translator->getTransByCode('visualiserlefichier')." \"+fileUrl+\"'><img src='/backoffice/cms/img/telecharger.gif' width='14' height='16' border='0' alt='".$translator->getTransByCode('telechargerlefichier')." \"+fileUrl+\"' /></a>&nbsp;-&nbsp;";*/
			
                        
                        echo 'monHtml+= \'<li id="\'+field+\'_\'+id+\'"><div><a rel="scle_produit_diaporama" href="\'+fileUrl+\'" target="_blank" title="'.$translator->getTransByCode('visualiserlefichier').' \'+fileUrl+\'" class="visuel"><img src="\'+fileUrl+\'" width="70"></a><a href="/backoffice/cms/utils/viewer.php?file=\'+fileUrl+\'" target="_blank" title="'.$translator->getTransByCode('visualiserlefichier').' \'+fileUrl+\'" class="name_img_diapo">\'+fileName+\'</a><a href="/backoffice/cms/utils/telecharger.php?file=\'+fileUrl+\'" title="'.$translator->getTransByCode('telechargerlefichier').' \'+fileUrl+\'" class="picto_download" "=""><img src="/backoffice/cms/img/2013/icone/right.png" alt="'.$translator->getTransByCode('telechargerlefichier').' \'+fileUrl+\'" border="0"></a><input type="hidden" id="\'+field+\'_delrecipient_\'+id+\'_name" name="\'+field+\'_delrecipient_\'+id+\'_name" value="\'+id+\'_\'+fileUrl+\'"><a id="\'+field+\'_delrecipient_\'+id+\'" href="#_" class="picto_del" title="delete recipient"><img src="/backoffice/cms/img/2013/icone/supprimer.png" border="0" alt="Suppression de l\\\'enregistrement"></a><a id="\'+field+\'_edit_\'+id+\'" href="#_" title="edit" class="picto_edit"><img src="/backoffice/cms/img/2013/icone/modifier.png" border="0" alt="Modifier"></a><input type="hidden" id="\'+field+\'_listfile_\'+id+\'" name="\'+field+\'_listfile_\'+id+\'" value="\'+fileUrl+\'"></div></li>\'; ';
                        
			
			//echo "<input type='hidden' id='\"+field+\"_listfile_\"+id+\"' name='\"+field+\"_listfile_\"+id+\"'  value='\"+fileName+\"' />";
			//echo "<input type='hidden' id='\"+field+\"_delrecipient_\"+id+\"_name' name='\"+field+\"_delrecipient_\"+id+\"_name'  value='\"+id+\"_\"+fileName+\"' />";
			 
			//echo "<a id='\"+field+\"_delrecipient_\"+id+\"' href='#' onClick='javascript:\"+field+\"_delrecipient(\"+id+\");' title='delete recipient'>[del]</a>&nbsp;</div>\";  
			//echo "<a id='\"+field+\"_delrecipient_\"+id+\"' href='#' title='delete recipient'>[del]</a>&nbsp;";
			//echo "&nbsp;-&nbsp;<a id='\"+field+\"_edit_\"+id+\"' href='#' title='edit'>[edit]</a>&nbsp;";
			
			//echo "</div>\";  ";
			
			echo "document.getElementById(field_conteneur).innerHTML = monHtml ;";
			 
		
		echo "	
		}\n
		
		 
		
		
		
		</script>\n";
}
else {
	echo "<script type=\"text/javascript\">\n
		function SetUrl(fileUrl, field){\n 
			var field_conteneur = field+\"_conteneur\";
			var fileName = basename(fileUrl);
			document.getElementById(field).value=fileUrl;\n
			
			document.getElementById(field_conteneur).innerHTML = \"<a href='/backoffice/cms/utils/viewer.php?file=\"+fileUrl+\"'  target='_blank' title='".$translator->getTransByCode('visualiserlefichier')." \"+fileUrl+\"'>\"+fileName+\"</a>&nbsp;-&nbsp;<a href='/backoffice/cms/utils/viewer.php?file=\"+fileUrl+\"'  target='_blank' title='".$translator->getTransByCode('visualiserlefichier')." \"+fileUrl+\"'><img src='/backoffice/cms/img/telecharger.gif' width='14' height='16' border='0' alt='".$translator->getTransByCode('telechargerlefichier')." \"+fileUrl+\"' /></a>\"; 
			
			
		}\n
		</script>\n";
}
// enregistrement à modifier par son display si display isset 


if(isset($display) && ($display!="")){
	// recherche le record 
	$displayField = $stack[0]["attrs"]["DISPLAY"];
	
	$aRechercheDisplay = array();
	$oRechDisplay = new dbRecherche();

	$oRechDisplay->setValeurRecherche("declencher_recherche");
	$oRechDisplay->setTableBD($classeName);	
	$oRechDisplay->setJointureBD($classePrefixe."_".$displayField." = ".$display);
	$oRechDisplay->setPureJointure(1);
	$aRechercheDisplay[] = $oRechDisplay;
	$sqlDisplay = "SELECT count(".$classeName.".".$classePrefixe."_id) ";
	$sqlDisplay.= dbMakeRequeteWithCriteres($classeName, $aRechercheDisplay, "");
	$recordNum = intval(dbGetUniqueValueFromRequete($sqlDisplay));
	
	if ($recordNum == 0){
		//echo "insert";
		$operation = "INSERT";
		eval("$"."oRes->set_".$displayField."(".$display.");");
		//pre_dump($oRes);
	}
	elseif ($recordNum == 1){ // tout va bien
		//echo "update";
		$operation = "UPDATE";
		$sqlDisplay = "SELECT ".$classeName.".* ";
		$sqlDisplay .= dbMakeRequeteWithCriteres($classeName, $aRechercheDisplay, " ".$classePrefixe."_id DESC ");
		$aResponseDisplay = dbGetObjectsFromRequete($classeName, $sqlDisplay);

		$oRes = $aResponseDisplay[0];
		$id = $oRes->get_id();
	}
	else{
		echo "trop de résultats!";
	}

}


// nombre de champs upload désiré
$numUploadFields = 0;
for ($iFile=0;$iFile<count($aNodeToSort);$iFile++){
	if ($aNodeToSort[$iFile]["name"] == "ITEM"){
		if ($aNodeToSort[$iFile]["attrs"]["OPTION"] == "file" || $aNodeToSort[$iFile]["attrs"]["OPTION"] == "geomapfile"){ 
			$numUploadFields++;
		}
	}
}
// gestion popup wysiwyg
if(is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/utils/popup_wysiwyg.php") && is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/lib/FCKeditor/fckconfig.js")){
	$bPopupWysiwyg = true;
}

if (defined("DEF_FCK_VERSION") && DEF_FCK_VERSION == "ckeditor" ) {
	if(is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/utils/popup_wysiwyg.php") && is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/lib/ckeditor/ckeditor.js")){
		$bPopupWysiwyg = true;
	} 
} 

//popup link
if(is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/utils/popup/dir.php")){
	$bPopupLinks = true;
}

//popup gmaps
if(is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/utils/popup_gmaps.php")){
	$bPopupMaps = true;
}

//popup liste d'objets
if(is_file($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/utils/popup_objectset.php")){
	$bPopupObjectset = true;
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
<script type="text/javascript">
	<?php // OBSOLETE  ??? ?>
	// retour à la page précédente
	// ce retour doit être posté pour conserver le type
	function retour()
	{
		document.add_<?php echo $classePrefixe; ?>_form.action = "<?php echo $_POST['urlRetour']; ?><?php if($listParam!="") echo "&".$listParam;?>"; 
		document.add_<?php echo $classePrefixe; ?>_form.submit(); 
	}
	
	function basename(path) {
		var b = path.replace(/^.*[\/\\]/g, '');
		 if (typeof(suffix) == 'string' && b.substr(b.length - suffix.length) == suffix) {
			b = b.substr(0, b.length - suffix.length);
		}
	 
		return b;
	}

</script>

<p class="choiceLang">
    <?php $aListLang = dbGetObjects('cms_langue'); //pre_dump($aListLang);
    $aLangCourt = array();
    foreach($aListLang as $oLang){
        $aLangCourt[] = $oLang->libellecourt;
        ?>
    <a href="javascript:chooseLang('<?php echo $oLang->libellecourt; ?>');" class="chooseLang" id="<?php echo $oLang->libellecourt; ?>"><?php echo $oLang->libelle; ?></a>
    <?php
    }
    if(count($aListLang) > 1){
        ?>
        <a href="javascript:chooseLang('ALL');" class="chooseLang actif" id="ALL">Tous</a>
       <?php
    }
    
    ?>
</p>

<form name="add_<?php echo $classePrefixe; ?>_form" id="add_<?php echo $classePrefixe; ?>_form" enctype="multipart/form-data" method="post">
<input type="hidden" name="classeName" id="classeName"  value="<?php echo $classeName; ?>" />
<input type="hidden" name="postnumberone" id="postnumberone" value="XXXX" />
<input type="hidden" name="postnumber2" id="postnumber2" value="XXXX2" />
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

// translation engine
// Added by Luc - 6 oct. 2009
//if (DEF_APP_USE_TRANSLATIONS) {
	$translator =& TslManager::getInstance();
	if (DEF_EDIT_INACTIVE_LANG)
		$langpile = $translator->getLanguages();
	else	$langpile = $translator->getLanguages(true);
//}
 
$status = '';
if($actiontodo == "SAUVE") { // MODE ENREGISTREMENT

	
	//-------------- upload --------------------------------------------------
	if ($numUploadFields > 0){
	
		// Pour ne pas écraser un fichier existant
		$Upload->WriteMode  = '1';		
		
		 // Définition du répertoire de destination
		$Upload->DirUpload = $_SERVER['DOCUMENT_ROOT'].'/tmp/';
		dirExists("/tmp/");
			
		// controle l'existence du dir tmp
		if (!is_dir($Upload->DirUpload)) // on le crée
			mkdir($Upload->DirUpload);

		// Pour limiter la taille d'un  fichier (exprimée en ko)
		$Upload->MaxFilesize  = strval($MaxFilesize*1024);

		//pre_dump($Upload);

		// On lance la procédure d'upload
		$Upload->Execute();    

		$DIR_UPLOAD = $_SERVER['DOCUMENT_ROOT'].'/custom/upload/';

		if (!is_dir($DIR_UPLOAD)) // on le crée
			mkdir($DIR_UPLOAD);

		$DIR_MEDIA = $_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/';

		// controle l'existence du dir dest
		if (!is_dir($DIR_MEDIA)){ // on le crée
			mkdir($DIR_MEDIA);
		}
		$URL_MEDIA = '/custom/upload/'.$classeName.'/';
			
		if (count($Upload->Infos) >= 1)  {
		 
			$aUploadIndexes[] = 0;
			while ($currInfos = current($Upload->Infos)) {
				$aUploadIndexes[] = key($Upload->Infos);
				next($Upload->Infos);
			}
				
			for ($i = 1;$i <= count($Upload->Infos);$i++) { 
				//echo "test : ".$_POST[strval('fUpload'.$aUploadIndexes[$i])]." : ".$Upload->Infos[$aUploadIndexes[$i]]['nom']."<br/>";
				//echo "test : ".$Upload->Infos[$aUploadIndexes[$i]]['chemin']." : ".$DIR_MEDIA.$Upload->Infos[$aUploadIndexes[$i]]['nom']."<br/>";
				$file = $Upload->Infos[$aUploadIndexes[$i]]['nom']; 
				
				$cpt_file = 0;
				while (is_file ($DIR_MEDIA.$file) && $cpt_file < 10) {  
					$file=preg_replace('/^(.+)\.([png|jpeg|jpg|gif]+)$/', '$1-copie.$2', $file);
					$cpt_file++;
				}
				
				if (!copy($Upload->Infos[$aUploadIndexes[$i]]['chemin'], $DIR_MEDIA.$file)) {
					//echo "failed to copy ".$Upload->Infos[$aUploadIndexes[$i]]['chemin']."...\n";
				} else {
					//echo "copied ".$Upload-> Infos[$aUploadIndexes[$i]]['chemin']."...\n";
					unlink($Upload->Infos[$aUploadIndexes[$i]]['chemin']);
					// var à mettre à jour
					$_POST[strval($_POST[strval('fUpload'.$aUploadIndexes[$i])])] = $file;
					 
					//echo "test : ".strval($_POST[strval('fUpload'.$aUploadIndexes[$i])])." ".$Upload->Infos[$aUploadIndexes[$i]]['nom']."<br />";
					
				}
			}// for

		}// if

	} // fin if ($numUploadFields > 0){

	//------------- fin upload --------------------------------------------------

	// ------------ delete ou copy (GoggleMaps) fichiers cochés --------------------------------------	
	for ($iDel=1; $iDel <= $numUploadFields; $iDel++) {	 
		if (strval($_POST['fDeleteFile'.$iDel]) == "true") {		 
			$_POST[strval($_POST['fUpload'.$iDel])] = ""; 
			//$tempGetter = "$"."tempFile = $"."oRes->get_".ereg_replace("[^_]+_(.*)", "\\1", strval($_POST['fUpload'.$iDel]))."();";
			$tempGetter = "$"."tempFile = $"."oRes->get_".eregi_replace(".+".$classePrefixe."_(.*)", "\\1", strval($_POST['fUpload'.$iDel]))."();";

			$tempGetter = str_replace("get_".$classePrefixe."_", "get", $tempGetter);
			eval($tempGetter);
			 
			if (preg_match("/;/msi", $tempFile)) {
				$aTempFile = explode (";", $tempFile);
				foreach ($aTempFile as $tempFile) {
					$tempFile = $DIR_MEDIA.$tempFile; 
					@unlink($tempFile);	
				}
			}
			else {
				$tempFile = $DIR_MEDIA.$tempFile;
				@unlink($tempFile);
			}
			
		} else {
			// generate GoggleMap
			$mapscale = 12;
			for ($i=0;$i<count($aNodeToSort);$i++) {
				if ($aNodeToSort[$i]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["OPTION"] == "geomapfile") {
					if (strval($_POST['fGenerateFile'.$iDel]) == "true") {
						$map_pivot = '';
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode) {
							if ($childNode["name"] == "OPTION" && $childNode["attrs"]["TYPE"] == "if") {
								for ($j=0;$j<count($aNodeToSort);$j++) {
									if ($aNodeToSort[$j]["name"] == "ITEM" && $aNodeToSort[$j]["attrs"]["NAME"] == $childNode["attrs"]["ITEM"]) {
										if ($aNodeToSort[$j]["attrs"]["OPTION"] == "geomapcenter") {
											// map is based on center pivot coordinates
											$map_pivot = $_POST['f'.ucfirst($classePrefixe).'_'.$childNode["attrs"]["ITEM"]];
											//break;
										}
									}
									// map scale
									if ($aNodeToSort[$j]["attrs"]["OPTION"] == 'geomapscale')
										$mapscale = $_POST['f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$j]["attrs"]["NAME"]];
								}
							}
						}
						if ($map_pivot == '') {
							// map is based on begin and end points coordinates
							$points = Array();
							foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode) {
								if ($childNode["name"] == "OPTION" && $childNode["attrs"]["TYPE"] == "if")							
									$points[] = explode(',', $_POST['f'.ucfirst($classePrefixe).'_'.$childNode["attrs"]["ITEM"]]);
							}
							$c_lat = floatVal($points[1][0])-(floatVal($points[1][0])-floatVal($points[0][0]))/2;
							$c_lng = floatVal($points[1][1])-(floatVal($points[1][1])-floatVal($points[0][1]))/2;
						} else {
							$center = explode(',', $map_pivot);
							$c_lat = floatVal($center[0]);
							$c_lng = floatVal($center[1]);
						}
						// map size
						if ($aNodeToSort[$i]["attrs"]["GEOMAPSIZE"] == 'DEF_GMAP_SIZE' && DEF_GMAP_SIZE != '')
							$mapsize = DEF_GMAP_SIZE;
						elseif ($aNodeToSort[$i]["attrs"]["GEOMAPSIZE"] != '')
							$mapsize = $aNodeToSort[$i]["attrs"]["GEOMAPSIZE"];
						else	$mapsize = '400x400';
						// api key
						$aKey = dbGetObjectsFromFieldValue("cms_mapskey", array("get_host"),  array($_SERVER['HTTP_HOST']), NULL);
						if ((count($aKey) == 1)&&($aKey!=false))
							$sKey = $aKey[0]->get_key();
                	                	
						$url = 'http://maps.google.com/maps/api/staticmap?center='.$c_lat.','.$c_lng.'&zoom='.$mapscale.'&size='.$mapsize.'&format=png&key='.$sKey.'&sensor=false';
						$newfilename = 'geomap_'.date('Ymd-His').'.png';
						$newfiledest = '/custom/upload/'.$classeName.'/'.$newfilename;
						//echo "source : ".$url."<br/><br/>";
						//echo "destination : ".$_SERVER['DOCUMENT_ROOT'].$newfiledest."<br/>";
						if (!copy($url, $_SERVER['DOCUMENT_ROOT'].$newfiledest))
							echo "La copie du fichier carte depuis $url a échoué...\n";
						else	$_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] = $newfilename;
					} else {
						$current = eval("$"."oRes->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");
						if ($_POST['f'.ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] == '' && $current != '')
							$_POST['f'.ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] = $current;
					}
				}
			}
		}
	}
	//----------------------------------------------------------------------------
	 	
	if (isset($newid)	&&	($newid==-1)){
		$operation = 'INSERT';	
	}
	 
	// Récupération des infos saisies dans l'objet
	if ($operation == "UPDATE") {
		//$oRes->set_id($id);
	}
	else{
		$bRetour = dbInsertWithAutoKey($oRes);
		$id = $bRetour;
	}

	global $oRes;
	
	include('maj.saveposteditems.php');

	include('maj.savepostedassos.php');
	
		


	// maj BDD
	if ($operation == "UPDATE") {
					
		// modif 
		//viewArray($oRes, 'UPDATE');
		$bRetour = dbUpdate($oRes);
		//echo "udpate";
			
	} else if ($operation == "INSERT") {
		$bRetour = true;
		// recherche si un enr avec même FIELD existe déjà
		// $bDeja_present = (getCount2($oRes, "Tyres_FIELD", $oRes->getTyres_FIELD(), "TEXT")>0);
		// recherche du FIELD identique 

		// cas 1 : email
		$bMailDejaPresent=false;
		for ($i=0;$i<count($aNodeToSort);$i++){
			if ($aNodeToSort[$i]["attrs"]["OPTION"]){
				if ($aNodeToSort[$i]["attrs"]["OPTION"]=="email"){ // si option
					// si unique=true ou non défini
					if (($aNodeToSort[$i]["attrs"]["UNIQUE"]=="true")||(!isset($aNodeToSort[$i]["attrs"]["UNIQUE"]))){ 					
						eval("$"."eKeyValue = "."$"."oRes->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");
						if (getCount($classeName, $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $eKeyValue, "TEXT") > 0 ) {
							$bMailDejaPresent=true;
							$status.="\nUn enregistrement avec cet email est déjà présent.<br /><br />";
						}
						if ($eKeyValue=="") {
							$bMailDejaPresent=true;
							$status.="\nUn enregistrement avec cet email est déjà présent.<br /><br />";
						}
					}
				}
			}
		}

		// cas 2 : generiqe, unique=true
		$bDeja_present = false;
		for ($i=0;$i<count($aNodeToSort);$i++){
			if ($aNodeToSort[$i]["attrs"]["UNIQUE"]){		
				// si unique=true
				if ($aNodeToSort[$i]["attrs"]["UNIQUE"]=="true"){ 				
					eval("$"."eKeyValue = "."$"."oRes->get_".$aNodeToSort[$i]["attrs"]["NAME"]."();");
					if (getCount($classeName, $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $eKeyValue, "TEXT") > 0 ) {
						$bDeja_present=true;
						$status.="\nUn enregistrement avec cette valeur de '".$aNodeToSort[$i]["attrs"]["NAME"]."' est déjà présent.<br /><br />";
					}
					if ($eKeyValue=="") {
						$bDeja_present=true;
						$status.="\nUn enregistrement avec cette valeur de '".$aNodeToSort[$i]["attrs"]["NAME"]."' est déjà présent.<br /><br />";
					}
				}				
			}
		}

		if($bDeja_present!=false || $bMailDejaPresent!=false) {
			$bRetour = false;
			dbDelete($oRes);
		} else { // tout est ok => on enregistre
			// on envoie id dans retour pour la redir vers show
			$bRetour = dbUpdate($oRes);
			$bRetour = $id;
		}
	}

	if ($bRetour) {
		if ($classeLogStatus && $classeChangeStatus)
			// Handle status change logs
			logObjectStatusChange($oRes);
		
		// type de record enregistré

		// j'envoie un mail à l'admin pour signaler la modif s'il s'agit d'un REDAC ou d'un VALID 
		// liste des utilisateurs du site + les admin
		// ajout 22/06
		// gestion des alertes pour les admins
		$aUser = listUsersWidthAdmin($_SESSION['idSite_travail']);

		$oUserLogged = unserialize($_SESSION['BO']['LOGGED']);
		$idUser_logged = $oUserLogged->get_id();

		$sRankId = $oUserLogged->get_rank();
		$oRank = new Bo_rank($sRankId);
		$sRank = $oRank->get_libelle();
		if ($sRank == "GEST" || $sRank == "REDACT") {   
			$aStatut = getStatutByRankOperation($sRankId,-1);

			if (sizeof($aStatut) > 0) {
				// je récupère la première valeur
				$idStatutContent = $aStatut[0]->get_id();

				foreach ($aUser as $oUser) {

					if (getCount_where("cms_assobo_userscms_statut_content", array("xus_bo_users", "xus_cms_statut_content"), array($oUser->getUser_id(),$idStatutContent), array("NUMBER","NUMBER")) > 0){
						// on vérifie email et on envoit
						if (isEmail( $oUser->getUser_mail())) {
							$aXus = dbGetObjectsFromFieldValue("cms_assobo_userscms_statut_content", array("get_bo_users", "get_cms_statut_content"), array($oUser->getUser_id(),$idStatutContent), "") ;
							$bRetour = sendAlerteModuleToAdmin ($aXus[0], $idUser_logged, $_POST['id'], $classeName, $_SESSION['idSite_travail']);
						}
					}

				}

			}
		}
	} else	$status.= $classeName.' - Erreur lors de '. ( ($operation == "INSERT") ? "l'ajout" : "la modification" );

	include('maj.result.php');
}
else { // MODE EDITION

// Pour ajouter des attributs aux champs de type file
$Upload-> FieldOptions = 'style="border-color:black;border-width:1px;"';

// Pour indiquer le nombre de champs désiré
$Upload-> Fields = $numUploadFields;

// Initialisation du formulaire
$Upload-> InitForm();
?>
<span class="arbo2">MODULE >&nbsp;</span><span class="arbo3"><?php
echo $translator->getText($classeLibelle); 
?>&nbsp;>&nbsp;
<?php
if($operation == "INSERT"){
	$translator->echoTransByCode('ajouter');
}
elseif(isset($newid)	&&	($newid == -1)){
	$translator->echoTransByCode('Dupliquer');
}
else{
	$translator->echoTransByCode('Modifier');
}
?></span><br><br>
<?php

if(isset($newid)	&&	($newid == -1)){
	echo '<p class="error14" >Attention, vous êtes sur le point de dupliquer une fiche !</p>';
}
?>
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
		if (validate_form("add_<?php echo $classePrefixe; ?>_form") && validerPattern() && validerChampsOblig()){ 
			document.add_<?php echo $classePrefixe; ?>_form.operation.value = "<?php echo $operation; ?>";
			<?php if(is_get('noMenu')){?>
				document.add_<?php echo $classePrefixe; ?>_form.action = "maj_<?php echo $classeName; ?>.php?noMenu=true<?php if($listParam!="") echo "&".$listParam; ?>"; 
			<?php }else{ ?>
				document.add_<?php echo $classePrefixe; ?>_form.action = "maj_<?php echo $classeName; ?>.php<?php if($listParam!="") echo "?".$listParam;?>"; 
			<?php } ?>
			document.add_<?php echo $classePrefixe; ?>_form.target = "_self";
			document.add_<?php echo $classePrefixe; ?>_form.submit(); 
		}		
	}
	
	function annulerForm(){
		<?php if(is_get('noMenu')){?>
			parent.$.fancybox.close(); 
		<?php }else{ ?>
			if (window.name.indexOf("if")==0){	// ifframe fancybox	
				window.parent.$.fancybox.close();
			}
			else{
				history.back();
			}
		<?php } ?>
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

<input type="hidden" name="operation" id="operation" value="<?php echo $operation; ?>" />
<input type="hidden" name="urlRetour" id="urlRetour" value="<?php echo $_POST['urlRetour']; ?>" />
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
<?php if (isset($newid)){ ?>
<input type="hidden" name="newid" id="newid" value="<?php echo $newid; ?>" />
<?php } ?>
<input type="hidden" name="actiontodo" id="actiontodo" value="SAUVE" />
<input type="hidden" name="sChamp" id="sChamp" value="" />

<table class="arbo form_edit">
<?php
$indexUpload = 0;
// Affichage du champ MAX_FILE_SIZE
print $Upload-> Field[$indexUpload];


//champs obligatoires

echo "<script type=\"text/javascript\">\n";
	echo "function validerChampsOblig() {\n";
	echo "erreur=0;\n";
	echo "lib=\"\";\n"; 

for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){	
		if (isset ($aNodeToSort[$i]["attrs"]["OBLIG"]) &&  $aNodeToSort[$i]["attrs"]["OBLIG"]!="false"){ // cas des int, ne pas inscrire de value vide dans la base

			// translation data check
			// Added by Luc - 6 oct. 2009
			
			if (DEF_APP_USE_TRANSLATIONS && $aNodeToSort[$i]["attrs"]["TRANSLATE"]){
				echo "localErreur=0;\n";
				foreach($langpile as $kLang => $aLangue){				
					//$chk_field_name = "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$langpile[DEF_APP_LANGUE]['libellecourt'];
					$chk_field_name = "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$aLangue['libellecourt'];				
					
					echo "if(document.getElementById(\"".$chk_field_name."\") != undefined){\n";
					echo "	if(document.getElementById(\"".$chk_field_name."\").disabled != true){\n";
					echo "		if(document.getElementById(\"".$chk_field_name."\").value== \"\" || document.getElementById(\"".$chk_field_name."\").value==-1){\n";
					echo " 			localErreur++;\n";					
					echo "		}\n";
					echo "	}\n";
					echo "}\n";				
				}
				if (isset ($aNodeToSort[$i]["attrs"]["LIBELLE"]) && $aNodeToSort[$i]["attrs"]["LIBELLE"] != "") $nomChamp = $aNodeToSort[$i]["attrs"]["LIBELLE"];
				else  $nomChamp = $aNodeToSort[$i]["attrs"]["NAME"];
				echo "if(localErreur==".count($langpile)."){\n";
				echo " 	erreur++;\n";
				echo " 	lib+=\" - ".$nomChamp." \\n\";\n";
				echo "}\n";
			}
			else{
				$chk_field_name = "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"];

				echo "if(document.getElementById(\"".$chk_field_name."\") != undefined){\n";
				echo "	if(document.getElementById(\"".$chk_field_name."\").disabled != true){\n";
				echo "		if(document.getElementById(\"".$chk_field_name."\").value== \"\" || document.getElementById(\"".$chk_field_name."\").value==-1){\n";
				echo " 			erreur++;\n";
				if (isset ($aNodeToSort[$i]["attrs"]["LIBELLE"]) && $aNodeToSort[$i]["attrs"]["LIBELLE"] != "") $nomChamp = $aNodeToSort[$i]["attrs"]["LIBELLE"];
				else  $nomChamp = $aNodeToSort[$i]["attrs"]["NAME"];
				echo " 			lib+=\" - ".$nomChamp." \\n\";\n";
				echo "		}\n";
				echo "	}\n";
				echo "}\n";
			}
		}	
		
	}
}
echo "if (erreur == 0) {\n";
echo "  return true; \n";
echo "}\n";
echo "else{\n";
echo "alert(\"Les champs suivants sont obligatoires : \\n\"+lib);";		
echo "	return false;\n";
echo "}\n";
echo "}";
echo "</script>\n";	






// tableau contenant les champs et pattern à vérifier
$ControlePattern = false;
$ControlePatternFields = array();
$ControlePatternValues = array();

for ($i=0;$i<count($aNodeToSort);$i++){
	//viewArray($aNodeToSort[$i]["attrs"]);
	if ($aNodeToSort[$i]["name"] == 'ITEM' && $aNodeToSort[$i]["attrs"]["FKEY"] == 'bo_users' && !empty($aNodeToSort[$i]["attrs"]["RESTRICT"]) && $_SESSION["rank"] != 'ADMIN') {
		// Cas over mega pas typique du tout
		// Cloisonnement sur administrateur loggué
		if ($operation == "INSERT"){
			echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$_SESSION["userid"]."\" />\n";
		}
		else{
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);	
			echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
		}
		continue;
		
	} elseif ($aNodeToSort[$i]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["SKIP"] != 'true' && $aNodeToSort[$i]["attrs"]["NAME"] != "statut" && $aNodeToSort[$i]["attrs"]["NAME"] != $other_statut) {

	
		$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);	
		
		if (isset($aNodeToSort[$i]["attrs"]["FKEY"]) && $aNodeToSort[$i]["attrs"]["FKEY"]=="cms_site" && $classeName !="classe" && $classeName !="cms_site") {
				echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$_SESSION["idSite"]."\" />\n";
		} else {	
			echo "<tr>\n";
			echo "<td class=\"arbo left_cell\">";
			if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")) 
				echo $translator->getText(stripslashes($aNodeToSort[$i]["attrs"]["LIBELLE"]));	
			else	echo $translator->getText(stripslashes($aNodeToSort[$i]["attrs"]["NAME"]));

			if (isset($aNodeToSort[$i]["attrs"]["OBLIG"]) && $aNodeToSort[$i]["attrs"]["OBLIG"]!="false")
				echo " *";
			echo "</td>\n";
			echo "<td class=\"arbo right_cell\">";
		} 

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
		
		if (($aNodeToSort[$i]["attrs"]["FKEY"] && ($aNodeToSort[$i]["attrs"]["FKEY"]!='null') && ($aNodeToSort[$i]["attrs"]["FKEY"]!='')) || $aNodeToSort[$i]["attrs"]["FKEY_SWITCH"]) { // cas de foreign key

			// AJAX delayed call for fkey select display
			// first define fields not applying to AJAX display
			$excluded = Array('cms_site');
			if (!in_array($aNodeToSort[$i]["attrs"]["NAME"], $excluded)) {
				if($id==0){
					$id=-1;
				}
				
				if ($id == -1 && isset($_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"].""])) {
					$forceId = $_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"].""];
				}
				else {
					$forceId = '';
				}
				
				if (is_get('refClass')	&& ($_GET['refClass']==$aNodeToSort[$i]["attrs"]["FKEY"])){ // appel en add item depuis assos
					echo '<input type="hidden" name="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]['attrs']['NAME'].'" id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]['attrs']['NAME'].'" class="arbo" value="'.$_GET['refId'].'">';
          
          $sTempClasse = $_GET['refClass'];
          
					include('show.fkey.php');
				}
				 else{	// appel normal dans maj items			
					// AJAX delayed process
					echo "\n".'<div id="delayed_'.$classePrefixe.'_'.$aNodeToSort[$i]["attrs"]["NAME"].'" style="display: inline;"><input type="hidden" name="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]['attrs']['NAME'].'" id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]['attrs']['NAME'].'" class="arbo" value="'.$eKeyValue.'"></div>';
					if ($aNodeToSort[$i]["attrs"]["NAME"]==$displayField){
						$call = '/backoffice/cms/call_maj_fkey.php?class='.$classeName.'&display='.$display.'&field='.$aNodeToSort[$i]["attrs"]["NAME"].'&id='.$id.'&forceValue=';
					}
					else{
						$call = '/backoffice/cms/call_maj_fkey.php?class='.$classeName.'&field='.$aNodeToSort[$i]["attrs"]["NAME"].'&id='.$id.'&forceValue=';
					}
					//echo "test : ".$call."<br/>";
					
					$tmp_load = 'Chargement de la liste...<input type="hidden" name="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]['attrs']['NAME'].'" id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]['attrs']['NAME'].'" class="arbo" value="'.$eKeyValue.'">';
					echo "\n".'<script type="text/javascript">';
					echo "\n".'function ajax'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]['attrs']['NAME'].'(forceId){';
					echo "\n".'callStr="'.$call.'"+forceId;';
					//echo "\n".'alert(callStr);';
					echo "\n".'XHRConnector.sendAndLoad(callStr, \'GET\', \''.$tmp_load.'\', \'delayed_'.$classePrefixe.'_'.$aNodeToSort[$i]['attrs']['NAME'].'\');';
					echo "\n".'}'; 
					echo "\n".'ajax'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]['attrs']['NAME'].'('.$forceId.');';
					echo "\n".'</script>';
				 }
				
			} else {
				// inline process
				include('maj.fkey.php');
			}

		}// fin fkey

		// translation fields
		// Added by Luc - 6 oct. 2009
		elseif (DEF_APP_USE_TRANSLATIONS && $aNodeToSort[$i]["attrs"]["TRANSLATE"]) { // cas d'une traduction

			include('maj.translation.php');

		} // end translation fields

		// time tracking fields
		// Added by Luc - 6 oct. 2009
		elseif ($aNodeToSort[$i]["attrs"]["NAME"] == 'cdate' || $aNodeToSort[$i]["attrs"]["NAME"] == 'datec' || $aNodeToSort[$i]["attrs"]["NAME"] == 'mdate' || $aNodeToSort[$i]["attrs"]["NAME"] == 'datem' || $aNodeToSort[$i]["attrs"]["NAME"] == 'dtmod'){ // cas de date à mettre à jour

			//echo "[non editable]&nbsp;";
			if ($operation == 'UPDATE') {
				if ($aNodeToSort[$i]["attrs"]["NAME"] == 'mdate' || $aNodeToSort[$i]["attrs"]["NAME"] == 'datem'|| $aNodeToSort[$i]["attrs"]["NAME"] == 'dtmod'){
				    $eKeyValue = from_dbdate_TIMESTAMP(date('Y-m-d H:i:s'));
				    echo $eKeyValue;
				}
				elseif ($aNodeToSort[$i]["attrs"]["NAME"] == "cdate" || $aNodeToSort[$i]["attrs"]["NAME"] == "datec") {
					echo $eKeyValue;
				}
				
			} elseif ($operation == 'INSERT') {
				if ($aNodeToSort[$i]["attrs"]["NAME"] == "cdate" || $aNodeToSort[$i]["attrs"]["NAME"] == "datec") {
				    $eKeyValue = from_dbdate_TIMESTAMP(date('Y-m-d H:i:s'));
				    echo $eKeyValue;
				} else	$eKeyValue = '';
			}
			echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

		} // end time tracking fields

		// enum type fields with fixed values from DB
		elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") {
			// non editable field
			if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == 'true') {
				echo $eKeyValue;
				//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
			} // end non editable field
			else {
				// check if this select triggers an FKEY_switch
				$fkey_switch = false;
				for ($j=0; $j<count($aNodeToSort); $j++) {
					if ($aNodeToSort[$j]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["NAME"] == $aNodeToSort[$j]["attrs"]["FKEY_SWITCH"]) {
						$fkey_switch = true;
						//$fkey_field = $aNodeToSort[$j]["attrs"]["NAME"];
						echo "\n".'<script type="text/javascript" >';
						echo "\n\t".'function trigger_'.$aNodeToSort[$i]["attrs"]["NAME"].'_FKeySwitch (_sel) {';
						//echo "\n\t\t".'var types = [];';
						//foreach ($aNodeToSort[$j]["children"] as $childKey => $childNode) {
						//	if ($childNode["name"] == "OPTION")
						//		echo "\n\t\ttypes['".$childNode["attrs"]["TYPE"]."'] = '".$childNode["attrs"]["TABLE"]."';";
						//}
						echo "\n\n\t\tXHRConnector.sendAndLoad('/backoffice/cms/call_maj_fkey.php?class={$classeName}&id={$_GET['id']}&field={$aNodeToSort[$j]["attrs"]["NAME"]}&fkey_switch='+_sel, 'GET', 'Chargement de la liste...', 'delayed_{$classePrefixe}_{$aNodeToSort[$j]["attrs"]["NAME"]}')";
						echo "\n\n\t}";
						echo "\n</script>\n";
						//$call = '/backoffice/cms/call_maj_fkey.php?class='.$classeName.'&id='.$_GET['id'].'&field='.$aNodeToSort[$i]["attrs"]["NAME"];
					}
				}

				echo "<select id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\"";
				if ($fkey_switch)
					echo ' onChange="trigger_'.$aNodeToSort[$i]["attrs"]["NAME"].'_FKeySwitch(this.value)"';
				echo " class=\"arbo\">\n";
				
				$enum_values = explode(',', $aNodeToSort[$i]["attrs"]["LENGTH"]);
				foreach ($enum_values as $enum_val) {
					$enum_val = substr($enum_val, 1,-1);
					echo "<option value=\"".$enum_val."\"".($eKeyValue == $enum_val ? " selected=\"true\"" : "").">".$enum_val."</option>\n";
	    			}
	    			echo "</select>\n";
				if ($fkey_switch) {
					echo "\n".'<script type="text/javascript" >';
					echo "\nvar fkey_sw_field = document.getElementById('f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."');";
					echo "\nfor (var i=0; i<fkey_sw_field.options.length; i++) {";
					echo "\n\tif (fkey_sw_field.options[i].value == '{$eKeyValue}')"; 
					echo "\n\t\tfkey_sw_field.selectedIndex = i;";
					echo "\n}";
					echo "\n</script>";
				}
	    		}
		} // end enum type fields with fixed values from DB

		elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum") { // cas enum sur options
			 
			$enum_options = $enum_noedit = '';
			if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if($childNode["name"] == "OPTION"){ // on a un node d'option				
						if ($childNode["attrs"]["TYPE"] == "value"){ 
							if ($eKeyValue == $childNode["attrs"]["VALUE"]){							
								$enumSelected = "selected";
								$enum_noedit = stripslashes($childNode["attrs"]["LIBELLE"]);
							}
							else {
								$enumSelected = "";
							}
							$enum_options .= "<option value=\"".$childNode["attrs"]["VALUE"]."\" ".$enumSelected.">".$translator->getText(stripslashes($childNode["attrs"]["LIBELLE"]), $_SESSION['id_langue'])."</option>\n"; 
						} //fin type  == value				
					}
				}
			}

			// non editable field
			if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
				echo $eKeyValue;
				//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

			} // end non editable field
			else {
				echo "<select id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\" >\n";
				echo "<option value=\"-1\">".$translator->getTransByCode('Choisirunitem')."</option>\n";
				echo $enum_options;
				echo "</select>\n";
			}
		} // fin cas enum sur options
		elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "node") { // cas node
			if (getCount_where("cms_arbo_pages", array("node_id"), array($eKeyValue), array("NUMBER")) ==  1){
				if (getNodeInfos($db, $eKeyValue)){
					$infosNode = getNodeInfos($db, $eKeyValue); 
					$eKeyValue_libelle = $infosNode["path"];
					
				}
				else {
					$eKeyValue_libelle = "n/a";
				}
			}
			else {
				$eKeyValue_libelle = "n/a";
			}
			
			$idSiteToBrowse = $_SESSION["idSite"];
			
			if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
				foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
					if($childNode["name"] == "OPTION"){ // on a un node d'option				
						if ($childNode["attrs"]["TYPE"] == "where"){
							$ActiveIf = true;
							$whereField = $childNode["attrs"]["ITEM"];
							$whereValue = $childNode["attrs"]["VALUE"];
							$idSiteToBrowse = $whereValue;
							break;
						} //fin type  == if			
					}
				}
			}
			 
			
			(isset($aNodeToSort[$i]["attrs"]["NODEVALUE"]) && $aNodeToSort[$i]["attrs"]["NODEVALUE"] != "") ? $nodevalue = "&v_comp_path=".$aNodeToSort[$i]["attrs"]["NODEVALUE"] : $nodevalue = ""; 
			echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle\" class=\"arbo inputEdit\" value=\"".$eKeyValue_libelle."\" disabled />\n";
			echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".$eKeyValue."\" ".$disabled." />\n";
			echo "<input type=\"button\"  class=\"arbo\" value=\"parcourir l'arborescence\" onclick=\"javascript:openBrWindow('/backoffice/cms/popup_arbo_browse_node.php?idSite=".$idSiteToBrowse."&idField=f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."&idForm=add_".$classePrefixe."_form".$nodevalue."', '', 600, 400, 'scrollbars=yes', 'true')\" class=\"arbo\">";
			
			echo "&nbsp;<input type=\"button\" class=\"arbo\" value=\"effacer\" onclick=\"javascript:resetField('f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle', 'f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."')\" class=\"arbo\">";

		} // fin cas node
		else { // cas typique 
			

			if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut") { // cas statut
				//echo lib($eKeyValue);
				echo "voir boutons radios";
			} else {
				
				if ($eKeyValue != -1){ // cas typique typique  
					if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file" || $aNodeToSort[$i]["attrs"]["OPTION"] == "geomapfile") { // cas file ou geomapfile
					
						include ("maj.file.php");	
					}
					elseif (($aNodeToSort[$i]["attrs"]["OPTION"] == "textarea")||($aNodeToSort[$i]["attrs"]["OPTION"] == "xml")) { // cas textarea	
						// non editable field
						// Added by Luc - 6 oct. 2009
						if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
							if ($aNodeToSort[$i]["attrs"]["SERIALIZED"] == "true") {
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
							//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
	
						} // end non editable field
						else {
							$maxlength="";
							if 	(isset($aNodeToSort[$i]["attrs"]["MAXLENGTH"]) && $aNodeToSort[$i]["attrs"]["MAXLENGTH"]!="") {
								$maxlength="onkeyup=\"this.value = this.value.slice(0, ".$aNodeToSort[$i]["attrs"]["MAXLENGTH"].")\" onchange=\"this.value = this.value.slice(0, ".$aNodeToSort[$i]["attrs"]["MAXLENGTH"].")\"";
							}
							echo "<textarea name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo textareaEdit\" ".$maxlength.">".$eKeyValue."</textarea>\n";
							// gestion popup wysiwyg
							if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupWysiwyg == true) && (($aNodeToSort[$i]["attrs"]["NOHTML"] != "true")||(!isset($aNodeToSort[$i]["attrs"]["NOHTML"])))){ // cas wysiwyg
								echo "<a href=\"javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', null, null, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"".$translator->getTransByCode('Editeur_html')."\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Editeur_html')."\" /></a>\n";
							} // wysiwyg
						}
					}
					elseif (in_array($aNodeToSort[$i]["attrs"]["OPTION"], array("geocoords", "geomapcenter", "geomapin", "geomapout"))) { // cas geocoords						
						// non editable field
						if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
							echo $eKeyValue;
							//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

						} // end non editable field
						else {
							echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
							// gestion popup link
							if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupMaps == true)){ // cas link						
								if ($aNodeToSort[$i]["attrs"]["GEOMAPSIZE"] == 'DEF_GMAP_SIZE' && DEF_GMAP_SIZE != '')
									$msize = explode('x', DEF_GMAP_SIZE);
								else	$msize = Array(804, 658);
								$msize[1] += 46;	// add edit fields margin in popup dimensions
								echo "<a href=\"javascript:openMapsWindow('/backoffice/cms/utils/popup_gmaps.php', 'links', {$msize[0]}, {$msize[1]}, 'scrollbars=yes', 'true','".$classeName."','".$aNodeToSort[$i]["attrs"]["NAME"]."');\" title=\"Maps picker\"><img src=\"/backoffice/cms/img/bt_popup_geo.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Selectionner_une_carte')."\" /></a>\n";
							} // link
						}
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "geopath") { // cas geopath						
						// non editable field
						if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
							echo $eKeyValue;
							//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

						} // end non editable field
						else {
							echo "<textarea name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo textareaEdit\" ".$maxlength.">".$eKeyValue."</textarea>\n";
							// gestion popup link
							if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupMaps == true)){ // cas link						
								echo "<a href=\"javascript:openMapsWindow('/backoffice/cms/utils/popup_gpaths.php', 'links', 920, 572, 'scrollbars=yes', 'true','".$classeName."','".$aNodeToSort[$i]["attrs"]["NAME"]."');\" title=\"Maps picker\"><img src=\"/backoffice/cms/img/bt_popup_geo.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Selectionner_une_carte')."\" /></a>\n";
							} // link
						}
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "geomapfile") { // cas geomapfile						
						// non editable field
						if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
							echo $eKeyValue;
							//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

						} // end non editable field
						else {
							echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
							// gestion popup link
							//echo "test : ".$aNodeToSort[$i]["attrs"]["GEOMAPSIZE"]." : ".DEF_GMAP_SIZE."<br/>";
							if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupMaps == true)){ // cas link						
								if ($aNodeToSort[$i]["attrs"]["GEOMAPSIZE"] == 'DEF_GMAP_SIZE' && DEF_GMAP_SIZE != '')
									$msize = explode('x', DEF_GMAP_SIZE);
								else	$msize = Array(804, 658);
								$msize[1] += 46;	// add edit fields margin in popup dimensions
								echo "<a href=\"javascript:openMapsWindow('/backoffice/cms/utils/popup_gmaps.php', 'links', {$msize[0]}, {$msize[1]}, 'scrollbars=yes', 'true','".$classeName."','".$aNodeToSort[$i]["attrs"]["NAME"]."');\" title=\"Maps picker\"><img src=\"/backoffice/cms/img/bt_popup_geo.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Selectionner_une_carte')."\" /></a>\n";
							} // link
						}
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "objectset"){ // cas liste d'objets						
						// non editable field
						if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
							echo $eKeyValue;
							//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
	
						} // end non editable field
						else {
							echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
							// gestion popup 
							if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupObjectset == true)){ 
								if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
									foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
										if($childNode["name"] == "OBJECT"){ // on a un node d'objet	
											echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_object\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_object\" class=\"arbo inputEdit\" value=\"".$childNode["cdata"]."\" disabled />\n";
											//pre_dump($childNode);
										}
									}
								}				
								echo "<a href=\"javascript:openMapsWindow('/backoffice/cms/utils/popup_objectset.php', 'links', 800, 625, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"".$translator->getTransByCode('Selectionner_une_carte')."\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"Object picker\" /></a>\n";
							} // link
						}
					}
					elseif (($aNodeToSort[$i]["attrs"]["OPTION"] == "link")	||	($aNodeToSort[$i]["attrs"]["OPTION"] == "url")){ // cas link						
						// non editable field
						if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
							echo $eKeyValue;
							//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
	
						} // end non editable field
						else {
							echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
							// gestion popup link
							if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupLinks == true)){ // cas link						
								echo "<a href=\"javascript:openLinkWindow('/backoffice/cms/utils/popup/dir.php', 'links', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"Link picker\"><img src=\"/backoffice/cms/img/bt_popup_url.png\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Selectionner_un_lien')."\" /></a>\n";
							} // link
						}
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "filedir"){ // cas filedir						
						echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
						// gestion popup link
						if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){ // cas filedir						
							echo "<a href=\"javascript:openLinkWindow('/backoffice/cms/utils/popup/dir.php', 'links', 600, 600, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"Link picker\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Selectionner_un_lien')."\" /></a>\n";
						} // link
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "password"){ // cas password	
						// non editable field
						if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
							echo $eKeyValue;
							//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
	
						} // end non editable field
						else{
							if ($eKeyValue=="") {				
							
							}
							else {
								echo "réinitialiser le mot de passe<br />(laisser les champs vide pour conserver le mot de passe actuel)<br />";
							}
							echo "<input type=\"password\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"\" ".$disabled." /><br />\n";
							echo "<input type=\"password\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."conf\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."conf\" class=\"arbo inputEdit\" value=\"\" ".$disabled." onblur=\"checkPwd('f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."')\" /> (confirmer le mot de passe)\n";
						}
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "module"){ // cas password	
						if (isset($aNodeToSort[$i]["attrs"]["MODULE"]) && $aNodeToSort[$i]["attrs"]["MODULE"]!="")  {
							echo "<a href=\"".$aNodeToSort[$i]["attrs"]["MODULE"]."_".$classeName.".php?id=".$_GET["id"]."\" title=\"".$aNodeToSort[$i]["attrs"]["MODULE"]."\">Accéder au module de modification de : ".$aNodeToSort[$i]["attrs"]["LIBELLE"]."</a>\n";
						}
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "filemanager"){ // cas wysiwyg
						//elseif ($bPopupWysiwyg == true){ // cas wysiwyg
						$path = "../..".$aNodeToSort[$i]["attrs"]["DIR"]; 
						$aFileManager = ScanForFilemanager($path);
						echo "<select name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\">\n";
							echo "<option value=\"0\" selected>".$translator->getTransByCode('Choisirunitem')."</option>\n";
							for ($l=0; $l<sizeof($aFileManager); $l++) {
							
							echo "<option value=\"".$aFileManager[$l]."\">".$aFileManager[$l]."</option>\n";
							
						}
						 echo "</select>\n";
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "node"){ // cas wysiwyg 

						if (getCount_where("cms_arbo_pages", array("node_id"), array($eKeyValue), array("NUMBER")) ==  1){
							if (getNodeInfos($db, $eKeyValue)){
								$infosNode = getNodeInfos($db, $eKeyValue); 
								$eKeyValue_libelle = $infosNode["path"];
								
							}
							else {
								$eKeyValue_libelle = "n/a";
							}
						}
						else {
							$eKeyValue_libelle = "n/a";
						}
						echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_libelle\" class=\"arbo inputEdit\" value=\"".$eKeyValue_libelle."\" disabled />\n";
						echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".$eKeyValue."\" ".$disabled." />\n";
						echo "<input type=\"button\"  class=\"arbo\" value=\"réutiliser brique existante\" onClick=\"javascript:openBrWindow('/backoffice/cms/popup_arbo_browse_node.php?idSite=".$_SESSION["idSite"]."&idField=f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."&idForm=add_".$classePrefixe."_form', '', 600, 400, 'scrollbars=yes', 'true')\" class=\"arbo\">";

					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "color"){ // cas couleur
						echo "<script type=\"text/javascript\"> 
									$(function() { 
										$('input.myColorPicker').myColorPicker(); 
									}); 
								</script>";				  
						
						($eKeyValue !="") ?  $style = 'style="background-color:'.$eKeyValue.'"' : $style = '' ;
						echo "<input type=\"text\" ".$style." name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo myColorPicker inputEdit\" value=\"".$eKeyValue."\"  />\n";
						 
						
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "drag" && $aNodeToSort[$i]["attrs"]["REFER"] != ""){ // cas drag  
					
						
						$champ_refer = $aNodeToSort[$i]["attrs"]["REFER"];
						
						if (method_exists($oRes, "get_".$champ_refer)) {
							eval ("$"."valueImg = "."$"."oRes->get_".$champ_refer."();");
							if ($valueImg != '') {
								echo "<script language=\"Javascript\">
								var serialiazeTab_f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"].";
								$(document).ready(function(){ 
									serialiazeTab_f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." = '".$eKeyValue."';
									$(\"#editdrag\").click(function() { 
										$.fancybox({
												'padding'		: 10,
												'scrolling'		: 'auto',																						
												'title'			: this.title,																								
												'href'			: '/backoffice/cms/utils/popup_drag.php?id=".$id."&idField=f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."&classeName=".$classeName."&refer=".$champ_refer."&source=".$aNodeToSort[$i]["attrs"]["NAME"]."&idForm=add_".$classePrefixe."_form'
											});
									
										return false;
									});
								})	;
								</script>
								";
								echo "<a href=\"#_\" title=\"DRAG editor\"  id=\"editdrag\"><img src=\"/backoffice/cms/img/filemanager/image2.gif\" style=\"cursor: pointer; border : none; \" alt=\"Points forts editor\" /></a>\n";
							}
						}
						//unset($_POST); 
						if ($_POST["operation"] == "submitdrag")  {
							include_once($_SERVER['DOCUMENT_ROOT']."/include/cms-inc/autoClass/maj.saveposteddrag.php");
						}
							
					}
					else{ // cas typique typique typique

						if ($aNodeToSort[$i]["attrs"]["OPTION"] != "bool"){ // pas boolean
							if ($aNodeToSort[$i]["attrs"]["NAME"] == "id"){
								echo $eKeyValue;
								
								echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".$eKeyValue."\" ".$disabled." />\n";
							}
							elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp" || $aNodeToSort[$i]["attrs"]["TYPE"] == "datetime"){ // cas timestamp
								// non editable field
								// Added by Luc - 6 oct. 2009 
								if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
									if ($id == -1)
										$eKeyValue = timestampFormat(date('Y-m-d H:i:s'));
									else	
										$eKeyValue =  timestampFormat($eKeyValue);
									echo $eKeyValue;
									//echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".$eKeyValue."\" ".$disabled." />\n";
						
								} // end non editable field
								else {
									if ($id == -1) { 
										/*if ($aNodeToSort[$i]["attrs"]["TYPE"] == "NOW")
											$eKeyValue = date('Y-m-d H:i:s');
									    else	$eKeyValue = '0000-00-00 00:00:00';*/
										if ($aNodeToSort[$i]["attrs"]["RSS"] == "pubendDate") {
											$eKeyValue = date('Y-m-d H:i:s', mktime(0, 0, 0, date("n"), date("j"), date("Y")+1));
										}
										else {
											$eKeyValue = date('Y-m-d H:i:s');
										}
										
										//$eKeyValue = date('Y-m-d H:i:s');
										echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".$eKeyValue."\" ".$disabled." />\n";
									} 
									else	echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".($eKeyValue)."\" ".$disabled." />\n";
								}

							}
							else{
								// non editable field
								if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
									if ($aNodeToSort[$i]["attrs"]["SERIALIZED"] == "true") {
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
									//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

								} // end non editable field
								else	echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".str_replace('"', '&quot;', $eKeyValue)."\" ".$disabled." />\n";
							}

							if ($aNodeToSort[$i]["attrs"]["NOEDIT"] != "true") {
								// JScalendar
								if (($aNodeToSort[$i]["attrs"]["TYPE"] == "date") && ($bJScalendar == true)){ // cas date
									echo "<img src=\"/backoffice/cms/lib/jscalendar/img.gif\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_trigger\" style=\"cursor: pointer;\" title=\"".$translator->getTransByCode('Selectionner_la_date')."\" />\n";
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
								// Handle datetime data type
								else if (($aNodeToSort[$i]["attrs"]["TYPE"] == "datetime") && ($bJScalendar == true)){ // cas datetime
									echo "<img src=\"/backoffice/cms/lib/jscalendar/img.gif\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_trigger\" style=\"cursor: pointer;\" title=\"".$translator->getTransByCode('Selectionner_la_date')."\" />\n";
									?>
									<script type="text/javascript" language="javascript">
									Calendar.setup({
										inputField     :    "<?php echo "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]; ?>",  // id of the input field
										ifFormat       :    "%d/%m/%Y %H:%M:%S",      // format of the input field
										button         :    "<?php echo "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_trigger"; ?>", // trigger ID
										align          :    "Tl",           // alignment (defaults to "Bl")
										showsTime      :    true,
										time24         :    true,
										singleClick    :    true
									});
									</script>
									<?php
								} // JScalendar

								//No calendar for timestamps
								/*
								else if (($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp") && ($bJScalendar == true)){ // cas date
									echo "<img src=\"/backoffice/cms/lib/jscalendar/img.gif\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_trigger\" style=\"cursor: pointer; border: 1px solid red;\" title=\"Date selector\" onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" />\n";
									?>
									<script type="text/javascript" language="javascript">
									Calendar.setup({
										inputField     :    "<?php echo "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]; ?>",  // id of the input field
										ifFormat       :    "%d/%m/%Y %H:%M:%S",      // format of the input field
										button         :    "<?php echo "f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_trigger"; ?>", // trigger ID
										align          :    "Tl",           // alignment (defaults to "Bl")
										showsTime      :    true,
										singleClick    :    true
									});
									</script>
									<?php
								}*/

								// gestion popup wysiwyg
								elseif ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupWysiwyg == true) && (($aNodeToSort[$i]["attrs"]["NOHTML"] != "true")||(!isset($aNodeToSort[$i]["attrs"]["NOHTML"])))){ // cas wysiwyg
								//elseif ($bPopupWysiwyg == true){ // cas wysiwyg
									echo "<a href=\"javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', null, null, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"".$translator->getTransByCode('Editeur_html')."\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Editeur_html')."\" /></a>\n";
								} // wysiwyg
							}

						} else { // option="bool"
							// non editable field
							if ($translator->getText('oui') == '') $translator->addTranslation ('oui', array("1" => "oui", "2" => "yes"));
							if ($translator->getText('non') == '') $translator->addTranslation ('non', array("1" => "non", "2" => "no"));
							
							if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
								echo (intval($eKeyValue) == 0 ? $translator->getText('non') : $translator->getText('oui'));
								//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
	
							} // end non editable field
							else {
								echo "<select name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\">\n";
								if (intval($eKeyValue) == 0){
									echo "<option value=\"0\" selected>".$translator->getText('non')."</option>\n";
									echo "<option value=\"1\">".$translator->getText('oui')."</option>\n";
								} else{
									echo "<option value=\"0\">".$translator->getText('non')."</option>\n";
									echo "<option value=\"1\" selected>".$translator->getText('oui')."</option>\n";
								}							
								echo "</select>\n";
							}
						}
					}
				} else {
					// cas not set
					if ($aNodeToSort[$i]["attrs"]["NAME"] == "id") { // cas id = -1
						//echo lib($eKeyValue);
						$translator->echoTransByCode('idgenereautomatiquement');
					}
					else { // cas autre texte not set
						if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file" || $aNodeToSort[$i]["attrs"]["OPTION"] == "geomapfile"){ // cas file ou geomapfile
							echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
							$indexUpload++;
							echo "<div id=\"div".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\">\n";
							echo "<!-- upload field # ".$indexUpload."/".$numUploadFields." -->\n";
							echo "<input type=\"hidden\" id=\"fUpload".$indexUpload."\" name=\"fUpload".$indexUpload."\" value=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" />\n";
							
							// Affichage du champ de type FILE						
							print $Upload->Field[$indexUpload];	
							echo '&nbsp;'.$translator->getTransByCode('taillemax').': '.$MaxFilesize.' Mo ';					
							echo ' - ('.$translator->getTransByCode('pasdefichier').')<br />';
							
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
							echo "</div>\n";
						}

						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] != "bool"){ // cas pas bool
							if (($aNodeToSort[$i]["attrs"]["OPTION"] == "textarea")||($aNodeToSort[$i]["attrs"]["OPTION"] == "xml")){ // cas textarea						
								// non editable field
								if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
									if ($aNodeToSort[$i]["attrs"]["SERIALIZED"] == "true")
										viewArray(unserialize($eKeyValue), 'Table');
									else	echo $eKeyValue;
									//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
								} // end non editable field
								else {
									echo "<textarea name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo textareaEdit\">".$eKeyValue."</textarea>\n";
									// gestion popup wysiwyg
									if ((($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")) && ($bPopupWysiwyg == true) && (($aNodeToSort[$i]["attrs"]["NOHTML"] != "true")||(!isset($aNodeToSort[$i]["attrs"]["NOHTML"])))){ // cas wysiwyg
									//elseif ($bPopupWysiwyg == true){ // cas wysiwyg
										echo "<a href=\"javascript:openWYSYWYGWindow('http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/popup_wysiwyg.php', 'wysiwyg', null, null, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"HTML editor\"><img src=\"/backoffice/cms/img/bt_popup_wysiwyg.gif\" id=\"wysiwyg\" style=\"cursor: pointer;\" alt=\"".$translator->getTransByCode('Editeur_html')."\" /></a>\n";
									} // wysiwyg
								}

							} else { 								
								// pas textarea
								// non editable field
								if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
									if ($aNodeToSort[$i]["attrs"]["SERIALIZED"] == "true") {
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
									//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
								} // end non editable field
								else {
									 
									if (isset ($aNodeToSort[$i]["attrs"]["DEFAULT"]) && $aNodeToSort[$i]["attrs"]["DEFAULT"]!="") $default = $aNodeToSort[$i]["attrs"]["DEFAULT"];
									else if ( $aNodeToSort[$i]["attrs"]["TYPE"] == "varchar" && isset($aNodeToSort[$i]["attrs"]["ISMINUS"] ) && ($aNodeToSort[$i]["attrs"]["ISMINUS"]  == true)){
										$default = $eKeyValue;	 
									} else { 
										($aNodeToSort[$i]["attrs"]["TYPE"] == "int" || $aNodeToSort[$i]["attrs"]["TYPE"] == "float") ? $default = -1 : $default ="";
									}
									echo "<input type=\"text\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo inputEdit\" value=\"".$default."\" />\n";
								}
							}
						} else { // option="bool"
							// non editable field
							if ($aNodeToSort[$i]["attrs"]["NOEDIT"] == "true") {
								echo (intval($eKeyValue) == 1 ? 'oui' : 'non');
								//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
							} // end non editable field
							else {
								echo "<select name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"arbo\">\n";

								echo "<option value=\"0\" selected>non</option>\n";
								echo "<option value=\"1\">oui</option>\n";
				                		
								echo "</select>\n";
							}
						}

					}
				}
			}
		}			

		// retour test de conditionnement - esclave
		if ($ActiveIf == true){

			$test2 = array();
			for ($ii=0;$ii<count($aNodeToSort);$ii++){
				if ($aNodeToSort[$ii]["name"] == "ITEM"){ 
					if ($aNodeToSort[$ii]["attrs"]["NAME"]==$aNodeToSort[$i]["attrs"]["NAME"]) {
						if (isset($aNodeToSort[$ii]["children"]) && (count($aNodeToSort[$ii]["children"]) > 0)){ 
							foreach ($aNodeToSort[$ii]["children"] as $childKey => $childNode){
								if($childNode["name"] == "OPTION"){ // on a un node d'option	
									if ($childNode["attrs"]["TYPE"] == "if"){ // test maitre = conditonner du item selected
										//if ($childNode["attrs"]["ITEM"] == $aNodeToSort[$i]["attrs"]["NAME"] ){ 
											$test2[] = $childNode["attrs"]["VALUE"];							
										//}
									} //fin type  == if			
								}
							}
						}
					}
				}
			}
			echo "<script language=\"javascript\" type=\"text/javascript\">\n";
			echo "// retour test de conditionnement - esclave\n";
			$k=0;
			if (sizeof($test2)>1) { 
				echo "// plusieurs \n";
				echo "if (";
				echo " document.getElementById(\"f".ucfirst($classePrefixe)."_".$whereField."\").value != \"".$test2[$k]."\" ";
					while ($k<sizeof($test2)) {
						$k++;
						echo "	&& document.getElementById(\"f".ucfirst($classePrefixe)."_".$whereField."\").value != \"".$test2[$k]."\"";
					}
				echo ")";
				echo "{\n";
				echo "document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").disabled = true;\n";
				if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
					echo "document.getElementById(\"div".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").style.display = \"none\";\n";
				}
				echo "}\n";
			
			}
			else {
				echo "// unique \n";
				echo "if(document.getElementById(\"f".ucfirst($classePrefixe)."_".$whereField."\").value != \"".$whereValue."\"){\n";
				echo "document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").disabled = true;\n";
				if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
					echo "document.getElementById(\"div".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").style.display = \"none\";\n";
				}
				echo "}\n";
			}
			echo "</script>\n";

		}

		//-------

		// retour tst de condition - maitre
		if ($ControlIf == true) {
			echo "<script language=\"javascript\" type=\"text/javascript\">\n";
			echo "// retour tst de condition - maitre\n";
			echo "document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").onchange = function(){\n";
			for ($ii=0;$ii<count($ControledFields);$ii++){
				echo "	if(";
				echo "	 document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").value != \"".$ControlValues[$ii]."\"";
				while ($ControledFields[$ii] == $ControledFields[($ii+1)]) {
					$ii++;
					echo "	&& document.getElementById(\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\").value != \"".$ControlValues[$ii]."\"";
				}

				echo " ){\n";
				echo "		document.getElementById(\"f".ucfirst($classePrefixe)."_".$ControledFields[$ii]."\").disabled = true;\n";

				$tempControledNode =getItemByName($aNodeToSort, $ControledFields[$ii]);
				//pre_dump($tempControledNode);
				if ($tempControledNode["attrs"]["OPTION"] == "file"){ // cas file
					echo "document.getElementById(\"div".ucfirst($classePrefixe)."_".$tempControledNode["attrs"]["NAME"]."\").style.display = \"none\";\n";
				}
				echo "	}\n";
				echo "	else{\n";
				echo "		document.getElementById(\"f".ucfirst($classePrefixe)."_".$ControledFields[$ii]."\").disabled = false;\n";

				if ($tempControledNode["attrs"]["OPTION"] == "file"){ // cas file
					echo "document.getElementById(\"div".ucfirst($classePrefixe)."_".$tempControledNode["attrs"]["NAME"]."\").style.display = \"inline\";\n";
				}
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
				echo "var regexp = new RegExp(\"^".$ControlePatternValues[$iii]."$\");\n"; 
				echo "if (!regexp.exec(document.getElementById(\"f".ucfirst($classePrefixe)."_".$ControlePatternFields[$iii]."\").value)){\n";
				echo " eCountPattern++;\n";
				echo " sMessagePattern+= \"Le champs ".$ControlePatternFields[$iii]." ne doit contenir que  les expressions suivantes: ".$ControlePatternValues[$iii]."\\n\";\n";
				echo "}\n";
			}	
		}
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
		if (isset($aNodeToSort[$i]["attrs"]["FKEY"]) && $aNodeToSort[$i]["attrs"]["FKEY"]=="cms_site" && $classeName !="classe" && $classeName !="cms_site") {
		} else {	
			echo "</td>\n";
			echo "</tr>\n";
		}	
		echo "<!-- fin des champs de la classe -->\n\n";
	}
}

// recherche d'eventuelles asso
for ($i=0;$i<count($aNodeToSort);$i++) {
	if ($aNodeToSort[$i]["name"] == "ITEM") {
		if ($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]) { // cas d'asso 
			echo "<tr>\n";
			echo "<td class=\"arbo left_cell\">&nbsp;</td>\n";
			echo "<td class=\"arbo right_cell\">&nbsp;</td>\n";
			echo "</tr>\n";
			echo "<!-- debut des champs d'association -->\n";
			echo "<tr>\n";
			echo "<td class=\"arbo left_cell cell_asso\">";
			echo "<b>".$translator->getTransByCode('Associations')."</b>";
			echo "</td>\n";
			echo "<td class=\"arbo right_cell\">";
			

			// AJAX delayed call for association list display
			// Added by Luc
			// first define fields not applying to AJAX display
			$excluded = Array('cms_site');
			if (!in_array($aNodeToSort[$i]["attrs"]["NAME"], $excluded)) {
				// AJAX delayed process
				echo "\n".'<div id="delayed_'.$classePrefixe.'_associations" style="display: inline;"></div>';
				$call = '/backoffice/cms/call_maj_association.php?class='.$classeName.'&id='.$_GET['id'].'&field='.$aNodeToSort[$i]["attrs"]["NAME"];
				//echo "test : ".$call."<br/>";
				echo "\n".'<script type="text/javascript">';
				echo "\n".'function ajaxDelayed_associations(id, key){';
				//echo "\n".'alert(id+\' \'+key);';
				echo "\n".'XHRConnector.sendAndLoad(\''.$call.'&idObject=\'+id+\'&key=\'+key, \'GET\', \'Chargement de la liste...\', \'delayed_'.$classePrefixe.'_associations\');';
				echo "\n".'}';
				echo "\n".'ajaxDelayed_associations();';
				echo "\n".'</script>';
			} else {
				// inline process
				include_once("maj.association.php");
			}
			echo "</td>\n</tr>\n";
		}	
	}
}
//E6E6E6
//EEEEEE
//D2D2D2
?>
<tr>
   <td class="arbo left_cell">&nbsp;</td>
   <td class="arbo right_cell">&nbsp;</td>
</tr>

<?php include_once ( "maj.statut.php" ); ?>
 <tr>
   <td class="arbo left_cell">&nbsp;</td>
   <td class="arbo right_cell">&nbsp;</td>
 </tr>
  <tr>
   <td class="arbo left_cell">&nbsp;</td>
   <td class="arbo right_cell">(* <?php $translator->echoTransByCode('champs_obligatoires'); ?>)</td>
 </tr>
 <tr>
   <td class="arbo left_cell">&nbsp;</td>
   <td class="arbo right_cell">&nbsp;</td>
 </tr>
 <tr>
  <td colspan="2" class="arbo bottom_valid">
  <input name="button" type="button" class="arbo" onclick="annulerForm();" value="<< <?php $translator->echoTransByCode('Retour'); ?>">&nbsp;
  <input class="arbo" type="button" name="Ajouter" value="<?php   
   if(isset($newid)	&&	($newid == -1)){
		$translator->echoTransByCode('Dupliquer');
	}
	else{
		 $translator->echoTransByCode('Enregistrer'); 
	}   
   ?> >>" onclick="validerForm()"></td>
 </tr>
</table>
<?php
if (isset($aCustom["JS"]) && ($aCustom["JS"] != "")){
	echo "<script type=\"text/javascript\">\n";
	//##classePrefixe## -> $classePrefixe
	//##classeName##    -> $classeName
	$search = array("##classePrefixe##", "##classeName##", "##id##");
	$replace = array($classePrefixe, $classeName, $oRes->get_id());
	echo str_replace($search, $replace, $aCustom["JS"]);
	echo "\n</script>\n";
}
?>
<?php

} // FIN MODE EDITION
?>
</form>

<script>
    
    function chooseLang(lang){
        //alert(lang);
        if(lang == '')
            var lang = 'ALL';
        
        
        if(lang != 'ALL'){
            <?php foreach($aLangCourt as $lang) { ?>
                        if(lang != '<?php echo $lang; ?>'){
                            $('.<?php echo $lang; ?>').hide();
                        }
           <?php } ?>
           
           $('.'+lang).show();
           $('.chooseLang').removeClass('actif');
           $('#'+lang).addClass('actif');
        } else {
            <?php foreach($aLangCourt as $lang) { ?>
               $('.<?php echo $lang; ?>').show();
           <?php } ?>
               
           $('.chooseLang').removeClass('actif');
           $('#'+lang).addClass('actif');
        }
        
    }
    
</script>