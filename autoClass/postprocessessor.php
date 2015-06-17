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
	//activateMenu('gestion'.$classeName);
//}  

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
			if (is_array($aNodeToSort[$iFile]['children'])){
				$aImageOptions = array();
				$aImageOutputs = array(basename($filePath));
				foreach($aNodeToSort[$iFile]['children'] as  $kO => $nOption){
					if ($nOption['attrs']['TYPE']=='image'){
					$aImageOptions[]=$nOption['attrs'];
					}
				}
			}
		}
	}
}

//pre_dump($aImageOptions);


//---------------------------------------------------------------

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
			
			
				if (preg_match("/png|jpeg|jpg|gif|bmp/msi",  	$Upload-> Infos[$aUploadIndexes[$i]]['nom'])==1) {		
				
					//echo "image";
					$filePath = $Upload->Infos[$aUploadIndexes[$i]]['chemin'];
					
					if (count($aImageOptions)>0){
						foreach($aImageOptions as $kO => $aOption){
							$aOption['src']=$filePath;
							//echo 'traiter l\'image '.$aOption['src'].' en X '.$aOption["MAXWIDTH"].' et Y '.$aOption["MAXHEIGHT"].'<br />';
							
							$oIm = imagecreatefromAnyFile($aOption['src']);
							$bDoResize=false;
							if(isset($aOption['WIDTH'])&&($aOption['WIDTH']!='')&&isset($aOption['HEIGHT'])&&($aOption['HEIGHT']!='')){											
								if((imagesx($oIm)!=$aOption['WIDTH']) || (imagesy($oIm)!=$aOption['HEIGHT'])){
									$oIm = resizeImageObjectWidthHeightStrict($oIm, $aOption['WIDTH'], $aOption['HEIGHT']);
									$bDoResize=true;
								}
							}	
							elseif(isset($aOption['MAXWIDTH'])&&($aOption['MAXWIDTH']!='')&&isset($aOption['MAXHEIGHT'])&&($aOption['MAXHEIGHT']!='')){
								if ((imagesx($oIm)>$aOption['MAXWIDTH'])||(imagesy($oIm)>$aOption['MAXHEIGHT'])){
									$oIm = resizeImageObjectWidthHeightWise($oIm, $aOption['MAXWIDTH'], $aOption['MAXHEIGHT']);
									$bDoResize=true;
								}
							}
							if ($bDoResize==true){
								if (count($aImageOptions)>1){
									if (preg_match('/^.+\.bmp$/msi', $aOption['src'])==1){
										$aOption['resize']=preg_replace('/^(.+)\.bmp$/', '$1-size-'.($kO+1).'.jpg', basename($aOption['src']));
									}
									else{							
										$aOption['resize']=preg_replace('/^(.+)\.([png|jpeg|jpg|gif]+)$/', '$1-size-'.($kO+1).'.$2', basename($aOption['src']));
									}
								}
								else {
									$aOption['resize'] = $Upload-> Infos[$aUploadIndexes[$i]]['nom'];
								}
								$aImageOutputs[]=$aOption['resize'];
								$newFilePath=$_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$aOption['resize'];
								//echo $newFilePath.'<br />';												
								imageoutputtoAnyFile($oIm, $newFilePath);
							}	
							else {
							 
								$newFilePath=$_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$Upload-> Infos[$aUploadIndexes[$i]]['nom'];
								//echo $newFilePath.'<br />';												
								imageoutputtoAnyFile($oIm, $newFilePath);
							
							}	
							unlink($Upload-> Infos[$aUploadIndexes[$i]]['chemin']);
					 
							$_POST[strval($_POST[strval('fUpload'.$aUploadIndexes[$i])])] = $Upload->Infos[$aUploadIndexes[$i]]['nom'];
													
							
						}  
					}
					
				}
				
				
				/*if (!copy($Upload->Infos[$aUploadIndexes[$i]]['chemin'], $DIR_MEDIA.$Upload-> Infos[$aUploadIndexes[$i]]['nom'])) {
				   //echo "failed to copy ".$Upload->Infos[$aUploadIndexes[$i]]['chemin']."...\n";
				}
				else{
					//echo "copied ".$Upload-> Infos[$aUploadIndexes[$i]]['chemin']."...\n";
					unlink($Upload-> Infos[$aUploadIndexes[$i]]['chemin']);
					// var à mettre à jour
					$_POST[strval($_POST[strval('fUpload'.$aUploadIndexes[$i])])] = $Upload->Infos[$aUploadIndexes[$i]]['nom'];
				}*/
			}// for
				
		}// if
	
	} // fin if ($numUploadFields > 0){
	//------------- fin upload --------------------------------------------------
	
	// ------------ delete fichiers cochés --------------------------------------	
	for ($iDel = 1;$iDel <= $numUploadFields;$iDel++){	
		if (strval($_POST['fDeleteFile'.$iDel]) == "true"){		
			$_POST[strval($_POST['fUpload'.$iDel])] = "";
			
			//$tempGetter = "$"."tempFile = $"."oRes->get_".ereg_replace("[^_]+_(.*)", "\\1", strval($_POST['fUpload'.$iDel]))."();";
			$tempGetter = "$"."tempFile = $"."oRes->get_".eregi_replace(".+".$classePrefixe."_(.*)", "\\1", strval($_POST['fUpload'.$iDel]))."();";
			
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
		$_POST["f".ucfirst($classePrefixe)."_id"] = $id;
	}
	
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int"){ // cas des int, ne pas inscrire de value vide dans la base
				if ($_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] == ""){
					$_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] = -1;
				}
				else{
					$_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]] = intval($_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]]);
				}	
			}		
			
			setItemValue(&$oRes, $aNodeToSort[$i]["attrs"]["NAME"], $_POST["f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]]);
			
			// posts check pour les champs réservés comme statut ou datecrea			
			if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){
				if (($oRes->get_statut() == NULL) || ($oRes->get_statut() == "") || ($oRes->get_statut() == -1)){
					// prendre la value défault
					eval("$"."oTemp = new ".$classeName."();");
					$oRes->set_statut($oTemp->get_statut());
					unset($oTemp);			
				}
			}
			elseif ($aNodeToSort[$i]["attrs"]["NAME"] == "datecrea"){
				if (($oRes->get_datecrea() == NULL) || ($oRes->get_datecrea() == "")){
					// prendre la date du jour
					$oRes->set_datecrea(date("d/m/Y"));
				}
			}
			// FIN  ----------- posts check pour les champs réservés comme statut ou datecrea			
		}
	}

	// maj BDD
	$bRetour = dbSauve($oRes);

	if($bRetour != false) {
		if (isset($aCustom["OnOK"])){
			$aCustom["OnOK"] = str_replace("##returnValue##", $bRetour, $aCustom["OnOK"]);
			eval($aCustom["OnOK"]);
		}
	
		header("Location: ".$_POST["urlOK"]);
	}
	else{
		if (isset($aCustom["OnKO"])){
			$aCustom["OnKO"] = str_replace("##returnValue##", $bRetour, $aCustom["OnKO"]);
			eval($aCustom["OnKO"]);
		}
		
		header("Location: ".$_POST["urlKO"]);
	}

}
?>