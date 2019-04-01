<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// exemple appel de la page //
// http://thao.gerlinea.hephaistos.interne/backoffice/news_inscrit/exportcsv_news_inscrit.php?champ0=ins_nom&valeur0=bassil&operateur0==
//http://thao.gerlinea.hephaistos.interne/backoffice/news_inscrit/exportcsv_news_inscrit.php?champ0=ins_dt_crea&valeur0=2028-02-04&operateur0=%3E
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}

function sanitizeCSV($value){
	$value = preg_replace("/<[^<>]+>/msi", "", preg_replace("/<br[^<>]*>/msi", "\n", $value));
	$value = preg_replace("/;$/msi", "", $value);
	$value = str_replace("\r"," ",$value); 
	$value = str_replace("\n"," ",$value); 	
	return $value;
}

//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');


if (DEF_APP_USE_TRANSLATIONS)
	$translator =& TslManager::getInstance();
	
	
unset($_SESSION['BO']['CACHE']);

$bDebug = false;
$sMessage="";

// objet 
eval("$"."oRes = new ".$classeName."();");

if(!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else
	$sXML = $oRes->XML;
//$sXML = $oRes->XML;

xmlClassParse($sXML);
/*
xmlClassParse($sXML);

$sPathSurcharge = $_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/'.$stack[0]['attrs']['NAME'].'.class.xml';
		
if(is_file($sPathSurcharge)){ 
	$stack = array();		
	// le parse
	$stack = xmlFileParse($sPathSurcharge);
}

pre_dump($stack);
*/
$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

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
}

$champTri = $_POST['champTri'];
if ($champTri == "") $champTri = $_GET['champTri'];

$sensTri = $_POST['sensTri'];
if ($sensTri == "") $sensTri = $_GET['sensTri'];

/////////////////////////
// SESSION //////////////
if ($_POST['champTri'] != "") $_SESSION['champTri_res'] = $_POST['champTri'];
if ($_POST['sensTri'] != "") $_SESSION['sensTri_res'] = $_POST['sensTri'];
/////////////////////////


//////////////////////////
// TRIS

// le tri utilisateur est fait en premier
// les autres tris sont faits même si c non visible dans l'interface
// l'odre des tris est défini ici

// le premier tri est ôté de la liste pour être placé en premier par la suite
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if ($aNodeToSort[$i]["attrs"]["ORDER"] == "true"){
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "date"){
				$sAscDesc = "DESC";
			}
			else{
				$sAscDesc = "ASC";
			}
			if ($_SESSION['champTri_res'] != $classePrefixe."_ref") $aListeTri[] = new dbTri($classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $sAscDesc);			
		}
	}
}

// tri numéro 1 => celui demandé dans l'interface
if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) !== false) {

	if ($_SESSION['champTri_res'] != "") $aGetterOrderBy[] = $_SESSION['champTri_res'];
	if ($_SESSION['sensTri_res']  != "") $aGetterSensOrderBy[] = $_SESSION['sensTri_res'];
}
else{
	// on récupere rien
}

// autres tris
for ($i=0; $i < sizeof($aListeTri); $i++){

	$oTri = $aListeTri[$i];

	$aGetterOrderBy[] = $oTri->getNom();
	$aGetterSensOrderBy[] = $oTri->getSens();
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
//$sql = "SELECT ".$classeName.".* ";



eval("$"."oRes = new ".$classeName."();");
if(!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else
	$sXML = $oRes->XML;
//$sXML = $oRes->XML;
xmlClassParse($sXML);

if(is_file($sPathSurcharge)){ 
	$stack = array();		
	// le parse
	xmlFileParse($sPathSurcharge);
}

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];
 

$sHeader = "";
$sHeaderAsso = "";

//$_GET["Type"] = "template";


for ($i=0; $i<count($aNodeToSort); $i++) {
	if ($aNodeToSort[$i]["name"] == "ITEM") {	
		if (!isset($aNodeToSort[$i]["attrs"]["SKIP"]) || $aNodeToSort[$i]["attrs"]["SKIP"] != "true" || ($aNodeToSort[$i]["attrs"]["SKIP"] == "true" && $_GET["Type"] == 'template')) {
			if ( ( $_GET["Type"] == 'template' && $aNodeToSort[$i]["attrs"]["NAME"]  != "id" ) || $_GET["Type"] != 'template' ) {
				if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && $aNodeToSort[$i]["attrs"]["LIBELLE"] != '') 	 	
					$sHeader.= $aNodeToSort[$i]["attrs"]["LIBELLE"].";"; 	
				else
					$sHeader.= $aNodeToSort[$i]["attrs"]["NAME"].";"; 	
					
				if ($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_VIEW"] || $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]) { // cas d'asso
					$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);			
		
					$aTempClasse = array();
					if ($aNodeToSort[$i]["attrs"]["ASSO"])
						$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO"]);		
					elseif ($aNodeToSort[$i]["attrs"]["ASSO_VIEW"])
						$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO_VIEW"]);		
					elseif ($aNodeToSort[$i]["attrs"]["ASSO_EDIT"])
						$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]);		
					for ($m=0; $m<sizeof($aTempClasse);$m++) {
						$sTempClasse = $aTempClasse[$m]; 
						eval("$"."oTemp = new ".$sTempClasse."();");
						 
						if(!is_null($oTemp->XML_inherited))
							$sXML = $oTemp->XML_inherited;
						else
							$sXML = $oTemp->XML;
						//$sXML = $oTemp->XML;
				
						unset($stack);
						$stack = array();
						xmlClassParse($sXML);
				 
						if ($stack[0]["attrs"]["LIBELLE"]!='') $assoLibelle = $stack[0]["attrs"]["LIBELLE"];
						else  $assoLibelle = $stack[0]["attrs"]["NAME"];
						
						$sHeaderAsso.= $assoLibelle.";"; 		
					}
				}		
			}
		}
	}
}
 

 

$sHeader.= $sHeaderAsso; 
$sHeader.= "\n"; 


if (isset($_GET["Type"]) && $_GET["Type"] == 'template') {
 
	for ($i=0; $i<count($aNodeToSort); $i++) {
		if ($aNodeToSort[$i]["name"] == "ITEM") {	
			if ($aNodeToSort[$i]["attrs"]["NAME"] == "id") { /*$sHeader.=  "(colonne optionelle);" ; */}
			else if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut") $sHeader.=  "".lib(DEF_ID_STATUT_LIGNE).";" ;
			else if ($aNodeToSort[$i]["attrs"]["TYPE"] == "date") $sHeader.=  date("d/m/Y").";" ;
			else if (isset($aNodeToSort[$i]["attrs"]["FKEY"])) {
				$aFK = dbGetObjects($aNodeToSort[$i]["attrs"]["FKEY"]);
				$aValue = array() ; 
				foreach ($aFK as $oFK) {
					 
					array_push ($aValue, getItemValue($oFK, $oFK->getDisplay()));		
				}
				$sHeader.=  "".join ("/", $aValue).";" ;
			}
			else if (isset($aNodeToSort[$i]["attrs"]["DEFAULT"])) $sHeader.=  "".$aNodeToSort[$i]["attrs"]["DEFAULT"].";" ;
			else {
				$sHeader.= "(".$aNodeToSort[$i]["attrs"]["TYPE"].");"; 	
			} 
		}
		if ($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_VIEW"] || $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]) {
			$aTempClasse = array();
			if ($aNodeToSort[$i]["attrs"]["ASSO"])
				$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO"]);		
			elseif ($aNodeToSort[$i]["attrs"]["ASSO_VIEW"])
				$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO_VIEW"]);		
			elseif ($aNodeToSort[$i]["attrs"]["ASSO_EDIT"])
				$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]);		
			for ($m=0; $m<sizeof($aTempClasse);$m++) {
				$sTempClasse = $aTempClasse[$m]; 
				$sHeaderAsso.= "Associations ".$sTempClasse.";"; 		
			}
		}
	}
	
	if (sizeof($aTempClasse) > 0) { // cas d'asso 
		
		for ($m=0; $m<sizeof($aTempClasse);$m++) {
			$sTempClasse = $aTempClasse[$m]; 
			
			//echo "<br />".$sTempClasse."<br />";
			eval("$"."oAsso = new "."$"."sTempClasse"."();"); 
			if(!is_null($oAsso->XML_inherited))
				$sXML = $oAsso->XML_inherited;
			else
				$sXML = $oAsso->XML;
			//$sXML = $oTemp->XML;
			unset($stack);
			$stack = array();
			xmlClassParse($sXML); 
			
			$sPathSurcharge = $_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/'.$stack[0]['attrs']['NAME'].'.class.xml';
			
			if(is_file($sPathSurcharge)){ 
				 
				$stack = array();		
				// le parse
				xmlFileParse($sPathSurcharge);
			}
		
		
			$assoClassePrefixe = $stack[0]["attrs"]["PREFIX"];  
			$assoNodeToSort = $stack[0]["children"]; 
			 
			for ($j=0; $j<count($assoNodeToSort); $j++) {  
				if ($assoNodeToSort[$j]["attrs"]["NAME"] == $oAsso->getDisplay()) {
					if (isset ($assoNodeToSort[$j]["attrs"]["FKEY"]) && $assoNodeToSort[$j]["attrs"]["FKEY"] != $classeName) {
						$assoClasse = $assoNodeToSort[$j]["attrs"]["FKEY"];	
					}
				}
				else if ($assoNodeToSort[$j]["attrs"]["NAME"] == $oAsso->getAbstract()) {
					if (isset ($assoNodeToSort[$j]["attrs"]["FKEY"]) && $assoNodeToSort[$j]["attrs"]["FKEY"] != $classeName) {
						$assoClasse = $assoNodeToSort[$j]["attrs"]["FKEY"];	
					}
				}
			} 
			
			
			
			$aValue = array() ;  
			if ($assoClasse != '') {
				$aFK = dbGetObjects($assoClasse);
				foreach ($aFK as $oFK) { 
					array_push ($aValue, getItemValue($oFK, $oFK->getDisplay()));		
				} 
			}
			//echo join (",", $aValue);
			$sHeader.=  " ".join (",", $aValue).";" ;
		}
		
	}	
	$sHeader.= "\n"; 

}



$aSelect = array();
$aClasse = array();
$aCondition = array();
//array_push ($aClasse, $classeName); 


for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){			 
		//$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
		if (!isset($aNodeToSort[$i]["attrs"]["SKIP"]) || $aNodeToSort[$i]["attrs"]["SKIP"] != "true" || ($aNodeToSort[$i]["attrs"]["SKIP"] == "true" && $_GET["Type"] == 'template') ) {
			
			if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key
				$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
				//array_push ($aClasse, $sTempClasse); 
				
				eval("$"."oTemp = new ".$sTempClasse."();");
				if(!is_null($oTemp->XML_inherited))
					$sXML = $oTemp->XML_inherited;
				else
					$sXML = $oTemp->XML;
				//$sXML = $oTemp->XML;
				unset($stack);
				$stack = array();
				xmlClassParse($sXML); 
				
				if(is_file($sPathSurcharge)){ 
					$stack = array();		
					// le parse
					xmlFileParse($sPathSurcharge);
				}
	
	 
				$tempClassePrefixe = $stack[0]["attrs"]["PREFIX"]; 
				
				//array_push ($aSelect, $sTempClasse.".".$tempClassePrefixe."_".$oTemp->getDisplay()." AS FKEY_".strtoupper($sTempClasse)); 
				//array_push ($aCondition, $sTempClasse.".".$tempClassePrefixe."_id =  ".$classeName.".".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]); 
				array_push ($aSelect, $classeName.".".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." AS FKEY_".strtoupper($sTempClasse)."_".$i); 	 
				
			}
			else {
				
				if ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum		
					if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
						eval("$"."enum".ucfirst($aNodeToSort[$i]["attrs"]["NAME"])." = array()".";");
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option	 
								if ($childNode["attrs"]["TYPE"] == "value"){ 
									eval("$"."enum".ucfirst($aNodeToSort[$i]["attrs"]["NAME"])."[".intval($childNode["attrs"]["VALUE"])."] "." = \"".stripslashes($childNode["attrs"]["LIBELLE"])."\"; ");
									
									 
									/*if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
										echo stripslashes($childNode["attrs"]["LIBELLE"]);
										break;
									}*/
								} //fin type  == value				
							}
						}
					}	
					array_push ($aSelect, $classeName.".".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." AS ENUM_".strtoupper($aNodeToSort[$i]["attrs"]["NAME"])."_".$i); 	
				} // fin cas enum
				else if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas enum		 
					array_push ($aSelect, $classeName.".".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." AS FILE_".strtoupper($aNodeToSort[$i]["attrs"]["NAME"])."_".$i); 	
				}
				else if ($aNodeToSort[$i]["attrs"]["OPTION"] == "bool"){ // cas enum		 
					array_push ($aSelect, $classeName.".".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." AS BOOL_".strtoupper($aNodeToSort[$i]["attrs"]["NAME"])."_".$i); 	
				}
				else {		 
				
					array_push ($aSelect, $classeName.".".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." AS ".strtoupper($aNodeToSort[$i]["attrs"]["NAME"])."_".$i); 
				}			
			}
		}
	}
}

$sql = "SELECT  ";
$sql.= join (", ", $aSelect); 

if(isset($_SESSION["sqlend"])){
	$sql.= $_SESSION["sqlend"];
}
else{

//$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);

if ($bDebug) print("<br>$sql");

$sTexte = $_SESSION['S_BO_sTexte_ref'];
$eStatut = $_SESSION['S_BO_select3_ref'];
$eType = $_SESSION['S_BO_select2_ref'];
$eHomepage = $_SESSION['S_BO_select_ref'];
$aRecherche = array();


// Cas over mega pas typique du tout
// Cloisonnement sur administrateur loggué
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["FKEY"] == 'bo_users' && $aNodeToSort[$i]["attrs"]["RESTRICT"] == 'true' && $_SESSION["rank"] != 'ADMIN') {
		$oRech0 = new dbRecherche();				
		$oRech0->setValeurRecherche("declencher_recherche");
		$oRech0->setTableBD($classeName);
		$oRech0->setJointureBD(ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." = ".$_SESSION["userid"]);
		$oRech0->setPureJointure(1);				
		$aRecherche[] = $oRech0;
	}
}
$oRech = new dbRecherche();

//////////////////////////
// recherche par mot clé
//////////////////////////
if($sTexte==""){
$sTexte=trim($_POST['sTexte']);
$_SESSION['sTexte']=$sTexte;
}
if($sTexte==""){
$sTexte=trim($_SESSION['sTexte']);
}
if ($sTexte != "") {
$_SESSION['sTexte']=$sTexte;
	$oRech = new dbRecherche();
	
	$oRech->setValeurRecherche("declencher_recherche");
	$oRech->setTableBD(join (", ", $aClasse));
	
	$cptvarchar=0;
	
	//on compte le nombre de varchar dans la classe
	for ($i=0;$i<count($aNodeToSort);$i++){
		if(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
		$cptvarchar++;
		}
	}

	//construction de la requete dynamique
	for ($i=0;$i<count($aNodeToSort);$i++){
		if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
			$cpt++;
			
			if($cptvarchar!=$cpt){				
				if($cpt==1){$sRechercheTexte="(";}
				$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$sTexte."%' OR ";
			}
			else{	
				if($cpt==1){$sRechercheTexte="(";}
				$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$sTexte."%' )";
				//$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$sTexte."%'";
			}
		}//fin if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")
	}// fin for ($i=0;$i<count($aNodeToSort);$i++)	

	$oRech->setJointureBD($sRechercheTexte);
	$oRech->setPureJointure(1);
	$aRecherche[] = $oRech;
	
}//fin if ($sTexte != "")


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

if ($eStatut != -1 && $eStatut != "") {
$oRech2 = new dbRecherche();

$oRech2->setValeurRecherche("declencher_recherche");
$oRech2->setTableBD(join (", ", $aClasse));
$oRech2->setJointureBD(" ".ucfirst($classePrefixe)."_statut=".$eStatut." ");
$oRech2->setPureJointure(1);

$aRecherche[] = $oRech2;
}

$aPostFilters = getFilterPosts();    
if ($aPostFilters != false){
	foreach($aPostFilters as $kFilter => $aPostFilter){
		foreach($aPostFilter as $filterName => $filterValue){
			$_SESSION[$filterName]=$filterValue;		
			if (isset($classeNameAsso) && $classeNameAsso!="") {
					
				// on récupére le préfixe de l'asso
				$filterNameTemp = preg_replace("/([^_]+)_(.*)/msi", "$2", $filterName);
				if (in_array($filterNameTemp, $itemToCheckForAsso)) {
					$filterName = preg_replace("/([^_]+)_(.*)/msi", $classePrefixeAsso."_$2", $filterName);
				}
				else {
					$classeNameAsso = "";
				}
				if (isset($classeNameAsso) && $classeNameAsso!="") {

						$oRech3 = new dbRecherche();				
						$oRech3->setValeurRecherche("declencher_recherche");
						$oRech3->setTableBD($classeNameAsso);
						 
						$oRech3->setJointureBD(" ".$classeName.".".ucfirst($classePrefixe)."_id IN (".$classeNameAsso.".".$classePrefixeAsso."_".$classeName.") ");
						$oRech3->setJointureBD(" ".$classeName.".".ucfirst($classePrefixe)."_id=".$classeNameAsso.".".$classePrefixeAsso."_".$classeName." ");
						$oRech3->setPureJointure(1);				
						$aRecherche[] = $oRech3;
							
				}
			}
			if ($filterValue != -1 &&( $filterValue != "" || $filterValue == 0) && $nbSub == 0) {
				$oRech3 = new dbRecherche();				
				$oRech3->setValeurRecherche("declencher_recherche");
				$oRech3->setTableBD(join (", ", $aClasse));
				
				if (sizeof($aCondition) > 0) $sCondition = " AND " .join (" AND ", $aCondition);
				if (preg_match('/,/', $filterValue))
					$oRech3->setJointureBD(" ".$filterName." IN (".$filterValue.") ".$sCondition."  ");
				else 
					$oRech3->setJointureBD(" ".$filterName."=".$filterValue." ".$sCondition);
				$oRech3->setPureJointure(1);				
				$aRecherche[] = $oRech3;
			}
		}
	}
}
else {
	$classeNameAsso = "";
}

//paramètre 
 
$k=0;
while (isset($_GET['champ'.$k])&& $_GET['champ'.$k]!=""){ 
	if (isset($_GET['operateur'.$k]) && $_GET['operateur'.$k]!="" && isset($_GET['valeur'.$k]) && $_GET['valeur'.$k]!="") 
	$oRech4 = new dbRecherche();
	$oRech4->setValeurRecherche("declencher_recherche");
	$oRech4->setTableBD(join (", ", $aClasse));
	if (sizeof($aCondition) > 0) $sCondition = " AND " .join (" AND ", $aCondition);
	$oRech4->setJointureBD(" ".$_GET['champ'.$k]." ".urldecode($_GET['operateur'.$k])." '".$_GET['valeur'.$k]."' ".$sCondition);
	$oRech4->setPureJointure(1);
	$aRecherche[] = $oRech4;
	$k++;
}

if (sizeof($aRecherche) == 0) {
	 
	$oRech = new dbRecherche();
	$oRech->setValeurRecherche("declencher_recherche");
	$oRech->setTableBD(join (", ", $aClasse));
	if (sizeof($aCondition) > 0) $sCondition = " " .join (" AND ", $aCondition);
	$oRech->setJointureBD($sCondition);
	$oRech->setPureJointure(1);
	$aRecherche[] = $oRech;

} 
//pre_dump($aRecherche); 
 
$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);


//echo $sql;
//$aListe_res = dbGetObjectsFromRequete($classeName, $sql);
 
$sContent ="";  
 

	

//echo $sql."<br />";

}

 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"export_".$classeName.".csv\""); 
header('Cache-Control: private, max-age=0, must-revalidate'); // ajout dans le cas SSL
header('Pragma: public'); // ajout dans le cas SSL  

echo $sHeader."\n";

// si $_GET["Type"] == "template", il s'agit d'un template, on n'affiche que le header
if (!isset($_GET["Type"]) && $_GET["Type"] == '') {
	$res = $db->Execute($sql);
	if ($res) { 				 
		// use buffer table to avoid record ubiquity
		$asso_pile = Array();
		while(!$res->EOF) {  
			$row = $res->fields; 
			
			$cpt = 0; 
			foreach ($row as $libelle => $value) {
				if ($cpt % 2 ==1) {
					//echo "<br />".$libelle." : ".$value."<br />";
					$aLibelle = preg_split("/_[0-9]+/", $libelle);
					$libelle = $aLibelle[0];
					if ($libelle == "STATUT") echo lib($value).";";
					else if ($value == -1) echo "n/a;";
					else if (preg_match("/ENUM_/msi", $libelle)) {
						  
						 eval(" echo $"."enum".ucfirst(strtolower(str_replace ("ENUM_", "", $libelle)))."[".$value."].\";\";");
						  
					}
					else if (preg_match("/FKEY_/msi", $libelle)) {
						if ($value <> -1) {
							eval("$"."oTemp = new ".ucfirst(strtolower(str_replace ("FKEY_", "", $libelle)))."(".$value.");");
							
							
							// traduction ????
							if(!is_null($oTemp->XML_inherited))
								$sXML = $oTemp->XML_inherited;
							else
								$sXML = $oTemp->XML;
							//$sXML = $oTemp->XML;
							 
							unset($stack);
							$stack = array();
							xmlClassParse($sXML);
	
							$foreignNodeToSort = $stack[0]["children"];
							$tempIsAbstractForeign = false;
							$tempForeignAbstract = "";
							$tempIsDisplayForeign = false;
							$tempForeignDisplay = "";
	
							if(is_array($foreignNodeToSort)){ 
								foreach ($foreignNodeToSort as $nodeId => $nodeValue) {		
									if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){	
										$valueDisplay = $nodeValue["attrs"]["NAME"];
										$typeDisplay = $nodeValue["attrs"]["TYPE"]; 
										$translateDisplay = $nodeValue["attrs"]["TRANSLATE"]; 	
										$fkeyDisplay = $nodeValue["attrs"]["FKEY"];
									}									 
								}
							}
							// eval("$"."itemValue = "."$"."oTemp->get_".$oTemp->getDisplay()."().' - '."."$"."oTemp->get_".$oTemp->getAbstract()."();");   // SID
							eval("$"."itemValue = "."$"."oTemp->get_".$oTemp->getDisplay()."();");  
							
							
							if($fkeyDisplay!=''){
								$oFkeyTemp = new $fkeyDisplay($itemValue);
								eval("$"."itemValue = "."$"."oFkeyTemp->get_".$oFkeyTemp->getDisplay()."();");  
							
							}
							elseif (DEF_APP_USE_TRANSLATIONS && $translateDisplay!='') {								
								if ($typeDisplay == "int") {
									if ($translateDisplay == 'reference')
										$itemValue = $translator->getByID($itemValue);
								} elseif ($typeDisplay == "enum") {
									if ($translateDisplay == "value" && $itemValue != '')
										$itemValue =  $translator->getText($itemValue);
								} else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";
							} 										
							//fin traduction
							
							echo sanitizeCSV($itemValue).";";
						}	 
						else {
							echo "n/a;";
						}						  
					}
					else if (preg_match("/FILE_/msi", $libelle)) { 
						if ($value <> "") {
							echo "http://".$_SERVER['HTTP_HOST']."/modules/utils/telecharger.php?file=".$value."&chemin=/custom/upload/".$classeName."/&;";	
						}	 
						else {
							echo "n/a;";
						}						  
					}		
					else if (preg_match("/BOOL_/msi", $libelle)) { 
						if ($value <> 1) {
							echo "non;";	
						}	 
						else {
							echo "oui;";
						}
						  
					}				
					else {
						echo sanitizeCSV($value).";";
					}					
					if ($libelle == "ID") $id = $value; 
				}
				$cpt++;
			} 
			$res->MoveNext();  
			eval("$"."oRes = new ".$classeName."(".$id.");");
			include("exportcsv.association.php");  
			echo "\n";
		}
		
	}
}
?>