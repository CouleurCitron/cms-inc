<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
 include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

$bDebug = false;
$sMessage="";

// objet 
eval("$"."oRes = new ".$classeName."();");

$sXML = $oRes->XML;

xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];
if (DEF_APP_USE_TRANSLATIONS)
	$translator =& TslManager::getInstance();


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
$sql = "SELECT ".$classeName.".* ";
//$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);

if ($bDebug) print("<br>$sql");

$sTexte = $_SESSION['S_BO_sTexte_ref'];
$eStatut = $_SESSION['S_BO_select3_ref'];
$eType = $_SESSION['S_BO_select2_ref'];
$eHomepage = $_SESSION['S_BO_select_ref'];
$aRecherche = array();


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
	$oRech->setTableBD($classeName);
	
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
$oRech2->setTableBD($classeName);
$oRech2->setJointureBD(" ".ucfirst($classePrefixe)."_statut=".$eStatut." ");
$oRech2->setPureJointure(1);

$aRecherche[] = $oRech2;
}

$aPostFilters = getFilterPosts();    
if ($aPostFilters != false){
	foreach($aPostFilters as $kFilter => $aPostFilter){
		foreach($aPostFilter as $filterName => $filterValue){
			$_SESSION[$filterName]=$filterValue;	
			//if($eStatut==""){
			//$eStatut=$_SESSION['eStatut'];
			//}		
			if (isset($classeNameAsso) && $classeNameAsso!="") {
					
				// on récupére le préfixe de l'asso
				$filterNameTemp = ereg_replace("([^_]+)_(.*)", "\\2", $filterName);
				if (in_array($filterNameTemp, $itemToCheckForAsso)) {
					$filterName = ereg_replace("([^_]+)_(.*)", $classePrefixeAsso."_\\2", $filterName);
				}
				else {
					$classeNameAsso = "";
				}
				if (isset($classeNameAsso) && $classeNameAsso!="") {

						$oRech3 = new dbRecherche();				
						$oRech3->setValeurRecherche("declencher_recherche");
						$oRech3->setTableBD($classeNameAsso);
						$oRech3->setJointureBD(" ".$classeName.".".ucfirst($classePrefixe)."_id=".$classeNameAsso.".".$classePrefixeAsso."_".$classeName." ");
						$oRech3->setPureJointure(1);				
						$aRecherche[] = $oRech3;
							
				}
			}
			if ($filterValue != -1 &&( $filterValue != "" || $filterValue == 0) && $nbSub == 0) {
				$oRech3 = new dbRecherche();				
				$oRech3->setValeurRecherche("declencher_recherche");
				$oRech3->setTableBD($classeNameAsso);
				$oRech3->setJointureBD(" ".$filterName."=".$filterValue." ");
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
	$oRech4->setTableBD($classeName);
	$oRech4->setJointureBD(" ".$_GET['champ'.$k]." ".urldecode($_GET['operateur'.$k])." '".$_GET['valeur'.$k]."' ");
	$oRech4->setPureJointure(1);
	$aRecherche[] = $oRech4;
	$k++;
}

$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);
$aListe_res = dbGetObjectsFromRequete($classeName, $sql);

// SQL generation
//header("Content-type: text/plain");
//header("Content-Disposition: attachment; filename=\"".$classeName.".sql\"");
?>
<pre style="border:dashed;left:200px">
<?php
// s'il y a des enregistrements à afficher
if(sizeof($aListe_res)>0) {

	eval("$"."oRes = new ".$classeName."();");
	$sXML = $oRes->XML;
	xmlClassParse($sXML);
	
	$classeName = $stack[0]["attrs"]["NAME"];
	$classePrefixe = $stack[0]["attrs"]["PREFIX"];
	$aNodeToSort = $stack[0]["children"];

	// liste
	for($k=0; $k<sizeof($aListe_res); $k++) {
		$oRes = $aListe_res[$k];	
	
	    echo dbMakeInsertReq($oRes).";\n";
	}
}
?></pre>