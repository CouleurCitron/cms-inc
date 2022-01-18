<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

unset($_SESSION['BO']['CACHE']);

$neededRAM = 512; // Mo
$sliceSize=2000;

if (!isset($foMode)){
	if (strpos($_SERVER['PHP_SELF'], "frontoffice") !== false){
		$foMode = true;
	}
	else{
		$foMode = false;
	}
}

if (!isset($bOpenXML)){
	$bOpenXML = true;
}
if (!isset($bCloseXML)){
	$bCloseXML = true;
}
///////////////////////////////////////////////

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

$bDebug = false;
$sMessage="";

// objet 
eval("$"."oRes = new ".$classeName."();");

if(!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else
	$sXML = $oRes->XML;


$stack = array();// init stack

xmlClassParse($sXML);

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
		if ($aNodeToSort[$i]["attrs"]["NAME"] == "ordre"){
			 $aListeTri[] = new dbTri($classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], "ASC");		
		}
	}
}

// tri numéro 1 => celui demandé dans l'interface
if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) !== false) {

	//if ($_SESSION['champTri_res'] != "") $aGetterOrderBy[] = $_SESSION['champTri_res'];
	//if ($_SESSION['sensTri_res']  != "") $aGetterSensOrderBy[] = $_SESSION['sensTri_res'];
}
else{
	// on récupere rien
}

// autres tris
for ($i=0; $i < newSizeOf($aListeTri); $i++){

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
		}//fin if ($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")
	}// fin for ($i=0;$i<count($aNodeToSort);$i++)	

	$oRech->setJointureBD($sRechercheTexte);
	$oRech->setPureJointure(1);
	$aRecherche[] = $oRech;
	
}//fin if ($sTexte != "")


//////////////////////////
// recherche par statut // only EN LIGNE
//////////////////////////
if ($oRes->getGetterStatut() != "none"){ // si l'objet a un champs statut
	if ($foMode == true){ // et si on est en frontoffice
		$oRech2 = new dbRecherche();
		$oRech2->setValeurRecherche("declencher_recherche");
		$oRech2->setTableBD($classeName);
		$oRech2->setJointureBD(" ".ucfirst($classePrefixe)."_statut=".DEF_ID_STATUT_LIGNE." ");
		$oRech2->setPureJointure(1);
		
		$aRecherche[] = $oRech2;	
	}			
}
else{ // sinon, on s'en fout
	//
}

//paramètre 
 
$k=0;
while (isset($_GET['champ'.$k])&& $_GET['champ'.$k]!=""){ 
	if (isset($_GET['operateur'.$k]) && $_GET['operateur'.$k]!="" && isset($_GET['valeur'.$k]) && $_GET['valeur'.$k]!="") 
	$oRech3 = new dbRecherche();
	$oRech3->setValeurRecherche("declencher_recherche");
	$oRech3->setTableBD($classeName);
	$oRech3->setJointureBD(" ".$_GET['champ'.$k]." ".urldecode($_GET['operateur'.$k])." '".$_GET['valeur'.$k]."' ");
	$oRech3->setPureJointure(1);
	$aRecherche[] = $oRech3;
	$k++;
}

$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);

unset($aRecherche);
unset($aGetterOrderBy);
unset($aGetterSensOrderBy);

// execution de la requette 
//$aListe_res1 = dbGetObjectsFromRequete($classeName, $sql);


if ($bOpenXML == true){
	header("Content-type: application/xml");
	header("Content-Disposition: attachment; filename=\"".$classeName.".xml\"");
	
	echo "<?xml version='1.0' encoding='utf-8' ?".">\n";
	echo "<".$classeName.">\n";
}
// faire un count
$sFieldPK = $oRes->getFieldPK();
$sqlCount = preg_replace('/SELECT.+FROM/msi', 'SELECT COUNT('.$sFieldPK.') FROM', $sql);

$rs = $db->Execute($sqlCount);
if($rs) {
	$eCount = $rs->fields[0];
}
//$aListe_res1 est le tableau de référence contenant l'ensemble des résultats

//on récupère 1000 enregistrements pour boucler dessus par la suite
//$aListe_res = array_slice($aListe_res1,0,1000);

unset($_SESSION['BO']['CACHE']);

$localRAM = (int)intval(str_replace('M', '', ini_get('memory_limit')));
if ($localRAM < $neededRAM){
	@ini_set('memory_limit', $neededRAM.'M'); 
}

//et on supprime les 1000 enregistrements du tableau de référence qui vont être traités dans la boucle suivante
$start = 0;
$end = $sliceSize;
$numSlices = floor($eCount/$sliceSize);
$slicesCount = 0;
error_log($eCount.' records = '.$numSlices.' slices of '.$sliceSize);

while($slicesCount <= $numSlices){
	set_time_limit ( 0 );
	error_log($sql.' LIMIT '.$start.','.$end);
	$aListe_res = dbGetObjectsFromRequete($classeName, $sql.' LIMIT '.$start.','.$end);
	// s'il y a des enregistrements à afficher
	if(newSizeOf($aListe_res)>0) {
		// liste
		for($k=0; $k<newSizeOf($aListe_res); $k++) {
			$oRes = $aListe_res[$k];
			$id = $oRes->get_id();
		
			echo "<item ";
		
		   for ($i=0;$i<count($aNodeToSort);$i++){
			if ($aNodeToSort[$i]["name"] == "ITEM"){			
				//if ($aNodeToSort[$i]["attrs"]["LIST"] == "true"){
					echo $aNodeToSort[$i]["attrs"]["NAME"]."=\"";
					$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
					if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key
						$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
						if ($eKeyValue > -1){
							//$oTemp = cacheObject($sTempClasse, $eKeyValue);
							$oTemp = cacheObject($sTempClasse, $eKeyValue);
							echo makeHTMLcodeXMLfriendly(getItemValue($oTemp, $oTemp->getDisplay()));
						}
						else{
							echo "n/a";
						}
					}
					elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum		
						if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
							foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
								if($childNode["name"] == "OPTION"){ // on a un node d'option				
									if ($childNode["attrs"]["TYPE"] == "value"){
										if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
											echo makeHTMLcodeXMLfriendly(stripslashes($childNode["attrs"]["LIBELLE"]));
											break;
										}
									} //fin type  == value				
								}
							}
						}		
					} // fin cas enum
					else{ // cas typique
						if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){ // cas statut
							echo lib($eKeyValue);
						}
						else{
							if ($eKeyValue > -1){ // cas typique typique
								if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
									echo $eKeyValue;
								}
								elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp"){ // cas file
									echo timestampFormat($eKeyValue);
								}
								else{// cas typique typique typique
									// on converti br en \n et on remove les tags 
									echo makeHTMLcodeXMLfriendly($eKeyValue);
								}
							}
							else{
								echo "n/a";
							}
						}
					}
					echo "\" ";
				//}
			}
		}	
		
		//-----------------------------------------------------------
		echo ">\n";
		
		// recherche d'eventuelles asso
		include("xml.association.php"); 
		 
		//------------------------------------------------------------
		echo "</item>\n";	
		}
	}
	
	$start += $sliceSize;
	$slicesCount++;
	$aListe_res = null;
	
	
}

// restore de la ram
@ini_set('memory_limit',$localRAM.'M');
	
if ($bCloseXML == true){
	echo "</".$classeName.">\n";
}
?>