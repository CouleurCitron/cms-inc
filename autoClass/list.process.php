<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 

$aRecherche = array();

if ($idSite == "") {
	if (isset($_SESSION["idSite"]))
		$idSite = $_SESSION["idSite"];
}


	
$_SESSION['listParam']=$_SERVER['QUERY_STRING'];
$listParam=$_SESSION['listParam'];

$bDebug = false;
$sMessage="";

// DEBUT menage session -----------------------------------------------------------------
if (!isset($_SESSION['classeName']) || ($_SESSION['classeName'] == "")){
	$_SESSION['classeName'] = $classeName;
}
elseif ($_SESSION['classeName'] != $classeName){
	// on change d'objet, purger les criteres de tri et filter

	unset($_SESSION["sqlpag"]);
	unset($_SESSION["adodb_curr_page"]);
	unset($_SESSION["sTexte"]);
	unset($_SESSION["eStatut"]);
	
	// toutes var de session commencant par le prefix de la precedente classe
	// attention si la classe en session est non chargée, il faut skipper
	//$aClasse = dbGetObjectsFromFieldValue("classe", array("get_nom","get_statut"),  array($_SESSION['classeName'],DEF_ID_STATUT_LIGNE), NULL);
	if (!in_array($_SESSION['classeName'], $_SESSION["classes"])){
	//if (count($aClasse) == 0){// la classe en session n'est pas valide	
		$_SESSION['classeName'] = $classeName;
	} else {// la classe en session est bien valide	
		eval("$"."oTemp = new ".$_SESSION['classeName']."();");
		$sXML = $oTemp->XML;
		xmlClassParse($sXML);
		unset($sXML);
		$classePrefixe = $stack[0]["attrs"]["PREFIX"];
		$stack = array();		
		unset($oTemp);
		foreach ($_SESSION as $sKey => $sVal){
			//if ($_SERVER['REMOTE_ADDR']=='82.234.79.170'){
			//	echo $sKey.' / '.$classePrefixe.'_ '.strpos($sKey, $classePrefixe.'_').'<br  />';
			//}
			if (strpos($sKey, $classePrefixe.'_')===0){ //cherche les $_SESSION[$classePrefixe.'_'*];
				unset($_SESSION[$sKey]);
			}
			
		}
			
		// virer les critère liés à d'autres classes
		if (strpos($_SESSION['champTri_res'], $classePrefixe.'_')===false){ 
			unset($_SESSION['champTri_res']);
		}
		if (strpos($_SESSION['champTri_res_cache'], $classePrefixe.'_')===false){ 
			unset($_SESSION['champTri_res_cache']);
		}
		
		//pre_dump( $_SESSION);
		$_SESSION['classeName'] = $classeName;
	}
} else{ // $_SESSION['classeName'] == $classeName
	// RAS
}

// controle la presence de champ statut
eval("$"."oTemp = new ".$_SESSION['classeName']."();");
if ($oTemp->getGetterStatut() =="none"){
	unset($_SESSION["eStatut"]);
}
unset($oTemp);

// FIN menage session -----------------------------------------------------------------

// objet 
eval("$"."oRes = new ".$classeName."();");
/*
if (!empty($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else*/	$sXML = $oRes->XML;
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
$classeLogStatus = ($stack[0]["attrs"]["LOG_STATUS_CHANGE"] == 'true' ? true : false);
$aNodeToSort = $stack[0]["children"];
$aListeChamps=$oRes->getListeChamps();
$sOrderField = $stack[0]["attrs"]["DEF_ORDER_FIELD"];
$sOrderDir = $stack[0]["attrs"]["DEF_ORDER_DIRECTION"];

//===============================
// operations de BDD
//===============================

// suppression

$operation = $_POST['operation'];

if ($operation == "DELETE") { 
	$id = $_POST['id'];

	if ($id != "") {
		// compte les objets avec cet id
		// pour voir si cet objet existe
		$eEmp = getCount($classeName, ucfirst($classePrefixe)."_id", ucfirst($classePrefixe)."_id", $id);
	
		if ($eEmp == 1) {

			
			
			// recherche des fichiers uploader dans le dossier custom et les supprime s'ils existent
			
			eval("$"."oRes = new ".$classeName."($"."id);");
			
			$sXML = $oRes->XML;
			xmlClassParse($sXML);
			
			$classeName = $stack[0]["attrs"]["NAME"];
			$classePrefixe = $stack[0]["attrs"]["PREFIX"];
			$aNodeToSort = $stack[0]["children"];
			for ($i=0;$i<count($aNodeToSort);$i++){
				if ($aNodeToSort[$i]["name"] == "ITEM"){
					//var_dump($aNodeToSort);
					if (isset($aNodeToSort[$i]["attrs"]["OPTION"])&& $aNodeToSort[$i]["attrs"]["OPTION"]!= "" && isset($aNodeToSort[$i]["attrs"]["DIR"]) && $aNodeToSort[$i]["attrs"]["DIR"]!= "" ) {
						//echo ($aNodeToSort[$i]["attrs"]["NAME"])." ".$aNodeToSort[$i]["attrs"]["DIR"]."<br><br>";
						$nameField = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
						$dirField = $aNodeToSort[$i]["attrs"]["DIR"];
						$dirField2 = $_SERVER['DOCUMENT_ROOT'].$aNodeToSort[$i]["attrs"]["DIR"];
						
						$eFile = getCount($classeName, ucfirst($classePrefixe)."_id", ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"], "'".$nameField."'");
						if($eFile == 1 && is_file($dirField2.$nameField)) {
							unlink($dirField2.$nameField);
						}
						
					}
				}
			}
		
			dbDelete($oRes);
			$sMessage = $classeName." ".$oRes->get_id()." supprimé ";
			
			//****************modif thao**********************
			// récup de toutes les asso à $classeName
			
			$urlClass= "../../include/bo/class";
			//table contenant les classes liés
			$aTempClas=ScanDirs($urlClass, $classeName);
			for ($j=0; $j<sizeof($aTempClas);$j++) {
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
								for ($a=0; $a<sizeof($aResponseDisplay); $a++) {
									$oResponseDisplay = $aResponseDisplay[$a];
									$idResponseDisplay = $oResponseDisplay->get_id();
									eval("$"."oRes3 = new ".$foreignName."($".idResponseDisplay.");");
									if ($oRes3->getGetterStatut()!="none") {
										if ($foreignNodeToSort[$i]["attrs"]["DEFAULT"] != "")
											$foreignDefault=$foreignNodeToSort[$i]["attrs"]["DEFAULT"];
										else	$foreignDefault="";
										
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
										if ($foreignDefault != -2)
											$bAssoRetour = dbUpdate($oRes3);
										else 
											$bAssoRetour = dbDelete($oRes3);
									}
									else {
										$bAssoRetour = dbDelete($oRes3);
									} //if ($oRes3->getGetterStatut()!="none") {
								} //for ($a=0; $a<sizeof($aResponseDisplay); $a++) {
							}// if ($eEmp > 0) {
							
						}// if ($foreignNodeToSort[$i]["attrs"]["NAME"] == $classeName){
					}// if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
				} //if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
			} //for ($i=0;$i<count($foreignNodeToSort);$i++){
			
			//*************************************************
			
			//*********** Nettoyage de cms_assoclassepage *****
			//on récup l'id de la classe
			$sql = "DELETE FROM cms_assoclassepage WHERE xcp_objet=".$id." AND xcp_classe=(SELECT cms_id FROM classe WHERE cms_nom='".$classeName."')";
			dbExecuteQuery($sql);
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

} else if ($operation == "CHANGE_STATUT") {

	// toutes les cc sélectionnées
	$aEmp = split(";", $_POST['cbToChange']);
	
	for ($p=0; $p<sizeof($aEmp); $p++) {

		if ($aEmp[$p] != "") {
			// objet 
			eval("$"."oRes = new ".$classeName."($"."aEmp[$"."p]);");
			
			
			//echo $_POST['idStatut'];
			
			
			if (method_exists($oRes, 'set_statut') && !isset ($stack[0]["attrs"]["STATUT"])) { 
				// nouveau statut
				$oRes->set_statut($_POST['idStatut']);
				
				// maj objet 
				$success = dbUpdateStatut($oRes);
				
				if ($success && $classeLogStatus){					
					// Handle status change logs
					logObjectStatusChange($oRes);
				}	

			} elseif(method_exists($oRes, 'set_inscrit')) {
				 
				 if ($_POST['idStatut']==DEF_ID_STATUT_LIGNE){
					$oRes->set_inscrit(1);					
				}
				else{
					$oRes->set_inscrit(0);
				}
				
				// maj objet 
				dbUpdateStatut($oRes);
			} else {
				// skip
				
				/*--------------------------------------------*/
				if (isset ($stack[0]["attrs"]["STATUT"]) && $stack[0]["attrs"]["STATUT"] != '' ) { 
					eval("$"."oRes->set_".$stack[0]["attrs"]["STATUT"]."(".$_POST['idStatut'].");");
					
					// maj date
					if (method_exists($oRes, 'set_datem')){
						$oRes->set_datem(from_dbdate_TIMESTAMP(date('Y-m-d H:i:s')));
					}
					elseif (method_exists($oRes, 'set_mdate')){
						$oRes->set_mdate(from_dbdate_TIMESTAMP(date('Y-m-d H:i:s')));
					}
					
					dbUpdate($oRes);
				}
				/* ---------------------------------------------------*/ 
				
			}
		}
	}	

}


//===============================
// TRIS
//===============================


// si on change de page, on reset
if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) === false) { 
	unset($aGetterOrderBy);
	unset($_SESSION['champTri_res']);
	unset($aGetterSensOrderBy);
	unset($_SESSION['sensTri_res']);
	$_SESSION['adodb_curr_page'] = "1";
	
	unset($_SESSION['champTri_res_cache']);
	unset($_SESSION['sensTri_res_cache']);
	
}

if (is_get("adodb_next_page")) {
	$_SESSION['champTri_res'] = $_SESSION['champTri_res_cache'];
	$_SESSION['sensTri_res'] = $_SESSION['sensTri_res_cache'];
}
 
if ($_SESSION['champTri_res'] != "" && $_POST['champTri'] == "")
	$_POST['champTri'] = $_SESSION['champTri_res'];
if ($_SESSION['sensTri_res'] != "" && $_POST['sensTri'] == "")
	$_POST['sensTri'] = $_SESSION['sensTri_res'];


// appliquer un éventuel tri par défaut
$default_filter = false;

/*
$sOrderField = $stack[0]["attrs"]["DEF_ORDER_FIELD"];
$sOrderDir = $stack[0]["attrs"]["DEF_ORDER_DIRECTION"];*/


if (empty($_POST['champTri']) && empty($_GET['champTri']) && !empty($sOrderField)) {
	$_POST['champTri'] = $classePrefixe."_".$sOrderField;
        
	$default_filter = true;
}
if (empty($_POST['sensTri']) && empty($_GET['sensTri']) && !empty($sOrderDir))
	$_POST['sensTri'] = $sOrderDir;

//elseif ($default_filter)
	//$_POST['sensTri'] = "ASC";
 

// rebalancer les valeurs dans le form de la page liste pour permutation éventuelle (re-click)
$champTri = $_POST['champTri'];
if ($champTri == "") {
	$champTri = $_POST['champTri'] = $_GET['champTri'];
}

$sensTri = $_POST['sensTri'];
if ($sensTri == "") {
	$sensTri = $_POST['sensTri'] = $_GET['sensTri'];
}

/////////////////////////
// SESSION //////////////
if ($_POST['champTri'] != "") {
	$_SESSION['champTri_res'] = $_POST['champTri']; 
}
if ($_POST['sensTri'] != "") {
	$_SESSION['sensTri_res'] = $_POST['sensTri']; 
}

if (!is_get("adodb_next_page")) {
	if ($_POST['champTri'] != "") { 
		$_SESSION['champTri_res_cache'] = $_POST['champTri'];
	}
	if ($_POST['sensTri'] != "") { 
		$_SESSION['sensTri_res_cache'] = $_POST['sensTri'];
	}
}
///////////////////////// 

//////////////////////////
// TRIS

// le tri utilisateur est fait en premier
// les autres tris sont faits même si c non visible dans l'interface
// l'ordre des tris est défini ici

$aGetterDateOrderBy = array();
$aGetterDateSensOrderBy = array();
$aGetterOrderBy  = array();
$aGetterSensOrderBy  = array();


// le premier tri est ôté de la liste pour être placé en premier par la suite
for ($i=0;$i<count($aNodeToSort);$i++) {
	if ($aNodeToSort[$i]["name"] == "ITEM") {
		
		if (($aNodeToSort[$i]["attrs"]["ORDER"] == "true") && ($aNodeToSort[$i]["attrs"]["TYPE"] != "text")) {
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "date") {
				$sAscDesc = "DESC";
				$aGetterDateOrderBy[]  = $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"];
				$aGetterDateSensOrderBy[] = $sAscDesc;
			} else {
				
				$sAscDesc = "ASC";
				if ($_SESSION['champTri_res'] != $classePrefixe."_ref"){
					
					//echo "SESSION['champTri_res']".$_SESSION['champTri_res'].$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]."<br />";		 
					if (isset($aNodeToSort[$i]["attrs"]["FKEY"]) && $_SESSION['champTri_res'] == $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"] ) 		 {
					 
						 
						//echo $classePrefixe."_ref";
						//echo $aNodeToSort[$i]["attrs"]["NAME"]."--".$aNodeToSort[$i]["attrs"]["FKEY"]."<br />";		
						eval("$"."oTemp = new ".$aNodeToSort[$i]["attrs"]["FKEY"]."();");
						//echo $oTemp->getDisplay()."<br />";	
						//echo $oTemp->getDisplay()."<br />";	
						
						if (!is_null($oTemp->XML_inherited))
							$sXML = $oTemp->XML_inherited;
						else	$sXML = $oTemp->XML;
						//$sXML = $oRes->XML;
						xmlClassParse($sXML);
						 
						$tempClassePrefixe = $stack[0]["attrs"]["PREFIX"]; 
						$tempAnodeToSort = $stack[0]["children"];
		
						$cptcms_chaine_reference = 0;
						$cptcms_chaine_traduite = 0; 
						
						// translate 
						for ($j=0;$j<count($tempAnodeToSort);$j++) {
						
							
							if ($tempAnodeToSort[$j]["name"] == "ITEM" && $tempAnodeToSort[$j]["attrs"]["NAME"] == $oTemp->getDisplay()) {
								//echo "//".$tempAnodeToSort[$j]["attrs"]["NAME"]."//".$oTemp->getDisplay().$tempAnodeToSort[$j]["attrs"]["TRANSLATE"]."<br />";
								if (isset($tempAnodeToSort[$j]["attrs"]["TRANSLATE"])) {
									$cptcms_chaine_reference++;
									$cptcms_chaine_traduite++; 		
								}
							}
						}
	
					 
					
		
						$oRech = new dbRecherche();				
						$oRech->setValeurRecherche("declencher_recherche");
						$oRech->setTableBD($aNodeToSort[$i]["attrs"]["FKEY"]);				
						$oRech->setJointureBD("(".$classeName.".".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." = ".$aNodeToSort[$i]["attrs"]["FKEY"].".".$tempClassePrefixe."_id OR ".$classeName.".".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." = -1 )");
						$oRech->setPureJointure(1);				
						$aRecherche[] = $oRech;
						
						if ($cptcms_chaine_reference > 0) {
						
							//LEFT OUTER JOIN ".$t." ON " ; 
							$oRech = new dbRecherche();				
							$oRech->setValeurRecherche("declencher_recherche");
							if ($_SESSION["tsl_langue"] == 1) 		
								$oRech->setTableBD("cms_chaine_reference");		
							else
								$oRech->setTableBD("cms_chaine_reference, cms_chaine_traduite");		
							if ($_SESSION["tsl_langue"] == 1) 		
								$oRech->setJointureBD(" cms_crf_id = ".$aNodeToSort[$i]["attrs"]["FKEY"].".".$tempClassePrefixe."_".$oTemp->getDisplay()." ");
							else
								$oRech->setJointureBD("cms_chaine_traduite.cms_ctd_id_reference = cms_chaine_reference.cms_crf_id and cms_crf_id = ".$aNodeToSort[$i]["attrs"]["FKEY"].".".$tempClassePrefixe."_".$oTemp->getDisplay()." AND cms_ctd_id_langue = ".$_SESSION["tsl_langue"]);
							$oRech->setPureJointure(1);				
							$aRecherche[] = $oRech;	
							$aListeTri[] = new dbTri("cms_crf_chaine", $sAscDesc);
							if ($_SESSION['champTri_res'] == $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]) 
								$_GET['champTri'] = $_SESSION['champTri_res'];	 
								if ($_SESSION["tsl_langue"] == 1) 	
									$_SESSION['champTri_res'] = "cms_crf_chaine";	 
								else
									$_SESSION['champTri_res'] = "cms_ctd_chaine";	 
	
						}
						else { 
						 
							$aListeTri[] = new dbTri($aNodeToSort[$i]["attrs"]["FKEY"].".".$tempClassePrefixe."_".$oTemp->getDisplay(), $sAscDesc);
							if ($_SESSION['champTri_res'] == $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]) 
								$_GET['champTri'] = $_SESSION['champTri_res'];	 
								$_SESSION['champTri_res'] = $aNodeToSort[$i]["attrs"]["FKEY"].".".$tempClassePrefixe."_".$oTemp->getDisplay();	 
						}
						
						
						
						//if ((strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) !== false) || ($_SERVER['HTTP_REFERER']=="")) { 
						
						
						
					}
					else {
					
						if (doesFieldExist($aListeChamps, $classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]))
						 	$aListeTri[] = new dbTri($classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $sAscDesc);
						elseif (doesFieldExist($aListeChamps, $aNodeToSort[$i]["attrs"]["NAME"]))
						 	$aListeTri[] = new dbTri($aNodeToSort[$i]["attrs"]["NAME"], $sAscDesc);	
						 
					}
				}
			}
		}
		
		if ($aNodeToSort[$i]["attrs"]["FKEY"] == "cms_site" && $classeName != "classe" && $classeName != "cms_site" ) {
			// recuperer le nom du champ cms_site en table
			$sCmsSiteChamp = '';			
			if (doesFieldExist($aListeChamps, $aNodeToSort[$i]["attrs"]["NAME"]))
				$sCmsSiteChamp = $aNodeToSort[$i]["attrs"]["NAME"];
			elseif (doesFieldExist($aListeChamps, ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"]))
				$sCmsSiteChamp = ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"];			
		
			if ($sCmsSiteChamp != '') {
				if (isset($_SESSION["idSite_travail"]) && $_SESSION["idSite_travail"]!= "" &&  ereg("backoffice", $_SERVER['PHP_SELF']))
					$_POST['filter'.$sCmsSiteChamp] = $_SESSION["idSite_travail"];
				else	$_POST['filter'.$sCmsSiteChamp] = $idSite;
			}
		}
	}
}
 
// tri numéro 1 => celui demandé dans l'interface ou définit par défaut
//if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) !== false) {
if ($default_filter || (strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) !== false) || ($_SERVER['HTTP_REFERER']=="")) {
//if ((strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) !== false) || ($_SERVER['HTTP_REFERER']=="")) {
	if ($_SESSION['champTri_res'] != "")
		$aGetterOrderBy[] = $_SESSION['champTri_res'];
	if ($_SESSION['sensTri_res']  != "")
		$aGetterSensOrderBy[] = $_SESSION['sensTri_res'];
}

// autres tris 
for ($i=0; $i < sizeof($aListeTri); $i++){
	$oTri = $aListeTri[$i]; 
	$aGetterOrderBy[] = $oTri->getNom();
	$aGetterSensOrderBy[] = $oTri->getSens();
} 
if (sizeof($aGetterOrderBy)>0)
	unset($aGetterDateOrderBy);
 
	
if (count($aGetterDateOrderBy)>0) {
	$aGetterOrderBy = array_merge($aGetterDateOrderBy, $aGetterOrderBy);
	$aGetterSensOrderBy = array_merge($aGetterDateSensOrderBy, $aGetterSensOrderBy);
}
 
// check des doublons dans les tris
for ($iOrder = 0;$iOrder < count($aGetterOrderBy);$iOrder++){
	//pre_dump(array_slice($aGetterOrderBy,$iOrder+1));
	$key = array_search($aGetterOrderBy[$iOrder], array_slice($aGetterOrderBy,$iOrder+1));	
	if ($key !== false){
		//echo "search : ".$aGetterOrderBy[$iOrder]."<br>found : ";
		//pre_dump($key);
		//echo "on splice";	
		array_splice ($aGetterOrderBy, $key+1, 1);
		array_splice ($aGetterSensOrderBy, $key+1, 1);
	}
} 
//////////////////////////

//===============================
// REQUETTE
//===============================

// obtention de la requete
$sql = "SELECT DISTINCT ".$classeName.".* ";
//$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);

if ($bDebug)
	print("<br>list.process--$sql");
/* Spe reference AWS
$sTexte = $_SESSION['S_BO_sTexte_ref'];
$eStatut = $_SESSION['S_BO_select3_ref'];
$eType = $_SESSION['S_BO_select2_ref'];
$eHomepage = $_SESSION['S_BO_select_ref'];

*/



//include_once ("list.process.keyword.php");

// Menu forced filters
include_once ("list.process.menu.php");

//////////////////////////
// recherche par statut
//////////////////////////
if (isset($_POST['eStatut'])){
	$eStatut=$_POST['eStatut'];
	$_SESSION['eStatut']=$eStatut;
}
if($eStatut==""){
	$eStatut=$_SESSION['eStatut'];
}

if (!ereg("backoffice", $_SERVER['PHP_SELF'])){ // hors BO only "en ligne"
	$eStatut = DEF_ID_STATUT_LIGNE;
}


// old if ($eStatut != -1 && $eStatut != "") {
if ($eStatut != -1 && $eStatut != "" && $nbSub == 0) {
	 
	$oRech2 = new dbRecherche();
	$oRech2->setValeurRecherche("declencher_recherche");
	$oRech2->setTableBD($classeName);
	$oRech2->setJointureBD(" {$classeName}.".ucfirst($classePrefixe)."_statut={$eStatut} ");
	$oRech2->setPureJointure(1);
	
	$aRecherche[] = $oRech2;
}

///////////////////////////////
// recherche par asso
///////////////////////////////
$classeNameAsso = "";

$sCms_site = "";
$sCms_sitePrefixe = "";
for ($i=0;$i<count($aNodeToSort);$i++){
	
	if (($aNodeToSort[$i]["attrs"]["NAME"] == "id") && isset($aNodeToSort[$i]["attrs"]["ASSO"])) {
		
		
		$aTempClasse = array();
		$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO"]);		
		
		for ($m=0; $m<sizeof($aTempClasse);$m++) { 
			$classeNameAsso = $aTempClasse[$m];
			// recherche des infos
			
			if($classeNameAsso!=''){
				eval("$"."oResAsso = new ".$classeNameAsso."();");
				$sXML = $oResAsso->XML;
				$stack = array();
				xmlClassParse($sXML);
				$classeNameAsso = $stack[0]["attrs"]["NAME"];
				$classePrefixeAsso = $stack[0]["attrs"]["PREFIX"];
				$aNodeToSortAsso = $stack[0]["children"];
				
				$itemToCheckForAsso = array ();
				for ($i=0;$i<count($aNodeToSortAsso);$i++){
					if (($aNodeToSortAsso[$i]["name"] == "ITEM") && (!ereg("statut|ordre|id", $aNodeToSortAsso[$i]["attrs"]["NAME"]))) {
						$itemToCheckForAsso[] =  $aNodeToSortAsso[$i]["attrs"]["NAME"];
					}
					elseif (($aNodeToSortAsso[$i]["name"] == "ITEM") && ($aNodeToSortAsso[$i]["attrs"]["NAME"] == "ordre")) {
						if (!ereg("/backoffice/", $_SERVER['PHP_SELF'])){	
							$aGetterOrderByAsso[] = $classePrefixeAsso."_ordre";
							$aGetterSensOrderByAsso[] = "ASC";
							
							// on fusionne tous les champs à ordonner avec le champ ordre en premier sur la liste des ORDER BY
							$aGetterOrderBy = array_merge($aGetterOrderByAsso, $aGetterOrderBy);
							$aGetterSensOrderBy = array_merge($aGetterSensOrderByAsso, $aGetterSensOrderBy);
						}
					}
				}
			}//if($classeNameAsso!=''){
		}
	}
	else if  (($aNodeToSort[$i]["attrs"]["NAME"] == "id") && isset($aNodeToSort[$i]["attrs"]["OPTION"])&& ($aNodeToSort[$i]["attrs"]["OPTION"] == "asso")) {

		for ($i=0;$i<count($aNodeToSort);$i++){
			if (($aNodeToSort[$i]["name"] == "OPTION") && ($aNodeToSort[$i]["attrs"]["TYPE"] == "asso")) {
				$classeNameAsso2 = $aNodeToSort[$i]["attrs"]["ASSO"];
				// recherche des infos
				eval("$"."oResAsso = new ".$classeNameAsso2."();");
				$sXML = $oResAsso->XML;
				$stack = array();
				xmlClassParse($sXML);
				$classeNameAsso2 = $stack[0]["attrs"]["NAME"];
				$classePrefixeAsso = $stack[0]["attrs"]["PREFIX"];
				$aNodeToSortAsso = $stack[0]["children"];
				
				foreach ($aNodeToSortAsso as $nodeId => $nodeValue) {		
					if (strtolower(stripslashes($nodeValue["attrs"]["NAME"])) == "cms_site" && preg_match('/backoffice/si', $_SERVER['PHP_SELF'])){
						$sCms_site = $classeNameAsso2;
						$sCms_sitePrefixe = $classePrefixeAsso;
					}
				}
			}
		}
	}
}

if ($sCms_site!="") {
	$oRech3 = new dbRecherche();				
	$oRech3->setValeurRecherche("declencher_recherche");
	$oRech3->setTableBD($sCms_site);
	$requete = " {$classeName}.{$sCms_sitePrefixe}_cms_site = {$_SESSION["idSite_travail"]} ";
	$requete.= " AND {$classeName}.{$classePrefixe}_{$sCms_site}={$sCms_sitePrefixe}_id ";
	$oRech3->setJointureBD($requete);
	$oRech3->setPureJointure(1);				
	$aRecherche[] = $oRech3;
}

///////////////////////////////
// recherche par filter (enum) ???
///////////////////////////////


$aPostFilters = getFilterPosts();
//viewArray($aPostFilters, 'FILTERS BEFORE CLEANUP');
 
// Cleanup filters according to posted fields
if (!empty($aPostFilters)) {
	$fkeyNodes = getItemsByAttribute($aNodeToSort, "fkey");
	if (!empty($fkeyNodes)) {
		// Cleanup FKey filters
		$tmpDelPosted = Array();
		foreach ($fkeyNodes as $nkey => $node){
			foreach ($aPostFilters as $pos => $filter) {
				$field = key($filter);
				if (($field == $node["attrs"]["NAME"] || $field == $classePrefixe.'_'.$node["attrs"]["NAME"]) && in_array($filter[$field], array(-1, '')) )
					$tmpDelPosted[] = $pos;
			}
		}
		//viewArray($tmpDelPosted, 'DELETE');
		foreach ($tmpDelPosted as $pos)
			unset($aPostFilters[$pos]);
	}
	
	$enumNodes = getItemsByOption($aNodeToSort, "enum");
	if (!empty($enumNodes)) {
		// Cleanup ENUM filters
		$tmpDelPosted = Array();
		foreach ($enumNodes as $nkey => $node){
			foreach ($aPostFilters as $pos => $filter) {
				$field = key($filter);
				if ($field == $classePrefixe.'_'.$node["attrs"]["NAME"] && $filter[$field] == -1)
					$tmpDelPosted[] = $pos;
			}
		}
		//viewArray($tmpDelPosted, 'DELETE');
		foreach ($tmpDelPosted as $pos)
			unset($aPostFilters[$pos]);
	}

	$boolNodes = getItemsByOption($aNodeToSort, "bool");
	if (!empty($boolNodes)) {
		// Cleanup BOOL filters
		$tmpDelPosted = Array();
		foreach ($boolNodes as $nkey => $node){
			foreach ($aPostFilters as $pos => $filter) {
				$field = key($filter);
				if ($field == $classePrefixe.'_'.$node["attrs"]["NAME"] && $filter[$field] == -1)
					$tmpDelPosted[] = $pos;
			}
		}
		//viewArray($tmpDelPosted, 'DELETE');
		foreach ($tmpDelPosted as $pos)
			unset($aPostFilters[$pos]);
	}
}
 
// End Filters cleanup
//viewArray($aPostFilters, 'FILTERS AFTER CLEANUP');

include_once ("list.process.custom.php");
include_once ("list.process.asso.php");

// Store filters in session
if ($refreshSearchFilters)
	$_SESSION["postFilters"] = $aPostFilters;
	 

include_once ("list.process.keyword.php");

// Now use filters for query building
 
if (!empty($aPostFilters)) {
	foreach ($aPostFilters as $kFilter => $aPostFilter) {
		foreach ($aPostFilter as $filterName => $filterValue) {
			 
			$_SESSION[$filterName] = $filterValue;	
			 
			//if($eStatut==""){
			//$eStatut=$_SESSION['eStatut'];
			//}		
			if (isset($classeNameAsso) && $classeNameAsso != "" ) {
				
				// on récupére le préfixe de l'asso
				$filterNameTemp = ereg_replace("([^_]+)_(.*)", "\\2", $filterName);
				if (in_array($filterNameTemp, $itemToCheckForAsso)) { 
					$filterName = ereg_replace("([^_]+)_(.*)", $classePrefixeAsso."_\\2", $filterName);
				} else	$classeNameAsso = "";

				if (isset($classeNameAsso) && $classeNameAsso!="") {
						$oRech3 = new dbRecherche();				
						$oRech3->setValeurRecherche("declencher_recherche");
						$oRech3->setTableBD($classeNameAsso);
						$oRech3->setJointureBD(" {$classeName}.".ucfirst($classePrefixe)."_id={$classeNameAsso}.{$classePrefixeAsso}_{$classeName} ");
						$oRech3->setPureJointure(1);				
						$aRecherche[] = $oRech3;		
				}
			}
			//if ($filterValue != -1 &&( $filterValue != "" || $filterValue == 0) && $nbSub == 0 && ereg($classePrefixe."_", $filterName)) { 
			//echo $nbSub.'<br />';
			//if ($filterValue != -1 &&( $filterValue != "" || $filterValue == 0) && $nbSub == 0 && doesFieldExist($aListeChamps, $filterName)) { 
			//if ($filterValue != -1 &&( $filterValue != "" || $filterValue == 0) && doesFieldExist($aListeChamps, $filterName)) { 	
			if ($filterValue!=-1 &&  $filterValue != "" || $filterValue == 0 && doesFieldExist($aListeChamps, $filterName)) { 	
				$oRech3 = new dbRecherche();				
				$oRech3->setValeurRecherche("declencher_recherche");
				$oRech3->setTableBD($classeNameAsso); 
				if (preg_match ("/,/", $filterValue)) 
					$oRech3->setJointureBD(" {$classeName}.{$filterName} IN (".$filterValue.") ");  
				else
					$oRech3->setJointureBD(" {$classeName}.{$filterName}=".$filterValue." ");
						 
				$oRech3->setPureJointure(1);				
				$aRecherche[] = $oRech3;
			}
		}
	}
} else	$classeNameAsso = "";

 
//Ajout d'un filtre permettant de n'afficher que les éléments ayant FILTER_ON = FILTER_VALUE (possibilité de complexifier via FILTER_OPTION
//FILTER_RANK permet de spécifier pour quel type de rang utilisateur le filtre doit etre actif, si non spécifié celui-ci n'est actif que pour les REDACT
if(isset($stack[0]["attrs"]["FILTER_ON"])){
	if ((isset($stack[0]["attrs"]["FILTER_RANK"]) && isAllowed($_SESSION['rank'], $stack[0]["attrs"]["FILTER_RANK"])) || (!isset($stack[0]["attrs"]["FILTER_RANK"]) && isAllowed($_SESSION['rank'], "REDACT"))){
		if (strtolower($stack[0]["attrs"]["FILTER_OPTION"]) == 'session') //Si FILTER_OPTION == session, on effectue la vérification de valeur par rapport à la variable de session FILTER_VALUE
			$jointure = " {$classeName}.{$stack[0]["attrs"]["PREFIX"]}_{$stack[0]["attrs"]["FILTER_ON"]}={$_SESSION[$stack[0]["attrs"]["FILTER_VALUE"]]} ";
		else	$jointure = " {$classeName}.{$stack[0]["attrs"]["PREFIX"]}_{$stack[0]["attrs"]["FILTER_ON"]}={$stack[0]["attrs"]["FILTER_VALUE"]} ";
	
		$oRech4 = new dbRecherche();				
		$oRech4->setValeurRecherche("declencher_recherche");
		//$oRech4->setTableBD($stack[0]["attrs"]["NAME"]);
		$oRech4->setTableBD($classeName);
		$oRech4->setJointureBD($jointure);
		$oRech4->setPureJointure(1);				
		$aRecherche[] = $oRech4;
	}
}



// Cas over mega pas typique du tout
// Cloisonnement sur administrateur loggué
for ($i=0;$i<count($aNodeToSort);$i++) {
	if ($aNodeToSort[$i]["name"] == "ITEM") {
		
		if ($aNodeToSort[$i]["attrs"]["FKEY"] == 'bo_users' && !empty($aNodeToSort[$i]["attrs"]["RESTRICT"]) && $_SESSION["rank"] != 'ADMIN') {

			$jointure = "";
			if ($aNodeToSort[$i]["attrs"]["RESTRICT"] == 'loose')
				$jointure = " ({$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]}={$_SESSION["userid"]} OR {$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]}=-1) ";
			elseif ($aNodeToSort[$i]["attrs"]["RESTRICT"] == 'strict')
				$jointure = " {$classeName}.{$classePrefixe}_{$aNodeToSort[$i]["attrs"]["NAME"]}={$_SESSION["userid"]} ";

			if (!empty($jointure)) {
				$oRech5 = new dbRecherche();				
				$oRech5->setValeurRecherche("declencher_recherche");
				$oRech5->setTableBD($classeName);				
				//$oRech5->setTableBD($stack[0]["attrs"]["NAME"]);
				$oRech5->setJointureBD($jointure);
				$oRech5->setPureJointure(1);				
				$aRecherche[] = $oRech5;
			}
		}
	}
}


//On finalise la requete de recherche
$sql_end = dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);
$sql.= $sql_end;   
// variable de session pour alimenter la pagination de show_ 
 
$_SESSION['sqlpag'] = $sql;


//echo $sql;

$_SESSION['sqlend'] = $sql_end;

unset($aRecherche);
unset($aGetterOrderBy);
unset($aGetterSensOrderBy); 

// execution de la requette avec pagination
$sParam = ""; 
if ($_SERVER["QUERY_STRING"]!="" && (ereg("param", $_SERVER["QUERY_STRING"]) || strstr($_SERVER["PHP_SELF"],'page_infos_reuse.php') !== FALSE) ) {
	$aParam = split('&', $_SERVER["QUERY_STRING"]);
	 
	if (isset($_GET['champTri']) && !in_array ( "champTri=".$_GET['champTri'], $aParam)) $aParam[] = "champTri=".$_GET['champTri'];
	if (isset($_SESSION['sensTri_res']) && !in_array ( "sensTri=".$_SESSION['sensTri_res'], $aParam) ) $aParam[] = "sensTri=".$_SESSION['sensTri_res'];	 
	
	for ($i = 0; $i<sizeof($aParam) ; $i++) {
		if (!ereg("adodb", $aParam[$i]))
			$sParam.="&".$aParam[$i];  
	}	 
}


//echo $sql;
// die($sParam);

//echo "<br />"."<br />".$sParam;


$pager = new Pagination($db, $sql, $sParam, $_SESSION['idSite']);
$pager->Render($rows_per_page);

//////// DEBUGAGE ////////
if ($bDebug) { 
	print("<br>///////////////////////<br>");
	print("<br>".$sql);
	print("<br>///////////////////////<br>");
	print("<br>".var_dump($pager->aResult));
	print("<br>///////////////////////<br>");
}
//////// DEBUGAGE ////////

// tableau d'id renvoyé par la fonction de pagination
$aId = $pager->aResult;

// A VOIR sponthus
// la fonction de pagination devrait renvoyer un tableau d'objet
// pour l'instant je n'exploite qu'un tableau d'id
// ce ui m'oblige à re sélectionner mes objets
// à perfectionner

// liste des objets
$aListe_res = array(); 
(isset ($_POST['maxResults']) && $_POST['maxResults'] < sizeof($aId) )  ? $maxResults = $_POST['maxResults'] : $maxResults = sizeof($aId) ;
 
for ($m=0; $m< $maxResults ; $m++)
{
	eval("$"."aListe_res[] = new ".$classeName."($"."aId[$"."m]);");
	//eval("$"."aListe_res[] = $"."a".ucfirst($classeName)."Objects[$"."aId[$"."m]];");
} 
 //eval("$"."aTempObjects = $"."a".ucfirst($sTempClasse)."Objects;");
					
// new





?>