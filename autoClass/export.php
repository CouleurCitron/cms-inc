<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/xlsx.php');

/*
// DE-FUCKING-PRECATED 
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

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


	
if(!is_null($oRes->XML_inherited)){
	$aListe_res = dbGetObjects($classeName);
}else{
	$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);
	$aListe_res = dbGetObjectsFromRequete($classeName, $sql);
}
	

// XLS generation
//header("Content-type: text/plain");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"".$classeName.".xls\"");
header('Cache-Control: private, max-age=0, must-revalidate'); // ajout dans le cas SSL
header('Pragma: public'); // ajout dans le cas SSL 
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<?php //<meta http-equiv=Content-Type content="text/html; charset=windows-1252"> ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Language" content="FR-FR">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 11">
<style>
.xl25
	{mso-style-parent:style0;
	mso-number-format:\@;
	white-space:normal;}
.xl26
	{mso-style-parent:style0;
	mso-number-format:"Short Time";
	white-space:normal;}
</style>
</head>
<body>
<!-- htmlxls -->
<?php
// s'il y a des enregistrements à afficher
if(sizeof($aListe_res)>0) {
?>
<table border="0" align="center" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo" width="100%">
<tr class="col_titre">
<?php
eval("$"."oRes = new ".$classeName."();");
if(!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else
	$sXML = $oRes->XML;
//$sXML = $oRes->XML;
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

for ($i=0; $i<count($aNodeToSort); $i++) {
	if ($aNodeToSort[$i]["name"] == "ITEM") {
		//if ($aNodeToSort[$i]["attrs"]["LIST"] == "true"){
			echo "<td align=\"center\" nowrap><b>";
			//echo "<a href=\"javascript:doTri('".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]."')\">";
			if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && $aNodeToSort[$i]["attrs"]["LIBELLE"]!="") 
				echo $aNodeToSort[$i]["attrs"]["LIBELLE"];
			else 
				echo $aNodeToSort[$i]["attrs"]["NAME"];
			//echo "</a>";
			echo "</b></td>\n";
		//}
	}
}

// recherche d'eventuelles asso
for ($i=0; $i<count($aNodeToSort); $i++) {
	if ($aNodeToSort[$i]["name"] == "ITEM") {
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
		
		
				echo "<td align=\"center\" nowrap><b>";
				echo "".$assoLibelle;
				echo "</b></td>\n";
			}
		}	
	}
}

  



?>
</tr>
<?php
// liste
for($k=0; $k<sizeof($aListe_res); $k++) {
	$oRes = $aListe_res[$k];
?>
 <tr class="<?php echo htmlImpairePaire($k);?>">
   <?php
   for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){			
		//if ($aNodeToSort[$i]["attrs"]["LIST"] == "true"){
			echo "<td align=\"center\" class=\"arbo\">";
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
			if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key
				$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
				if ($eKeyValue > -1){
					$oTemp = cacheObject($sTempClasse, $eKeyValue);
					//echo "<a href=\"../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php?id=".$oTemp->get_id()."\">";
					
					
					
					
					
					
					if ($oTemp != NULL){
						// fkey display in record list
						// Added by Luc - 12 oct. 2009
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
								if ($nodeValue["attrs"]["NAME"] == $oTemp->getAbstract()){	
									$valueAbstract = $nodeValue["attrs"]["NAME"];
									$typeAbstract = $nodeValue["attrs"]["TYPE"];	 
									$translateAbstract = $nodeValue["attrs"]["TRANSLATE"]; 			

								}
								if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){	
									$valueDisplay = $nodeValue["attrs"]["NAME"];
									$typeDisplay = $nodeValue["attrs"]["TYPE"]; 
									$translateDisplay = $nodeValue["attrs"]["TRANSLATE"]; 	
								}
								 
							}
						}
					}
					
					$itemValue = getItemValue($oTemp, $oTemp->getDisplay());
					if (DEF_APP_USE_TRANSLATIONS && $translateDisplay) {
						if ($typeDisplay == "int") {
							if ($translateDisplay == 'reference')
								echo $translator->getByID($itemValue);
						} elseif ($typeDisplay == "enum") {
							if ($translateDisplay == "value")
								echo $translator->getText($itemValue);
						} else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";
					}
					else {
						echo $itemValue;
					}
					
					
					
					
					
					
					
					
					
					if (  $oTemp->getDisplay() <>  $oTemp->getAbstract() && $oTemp->getAbstract() != -1) {
						$itemValue = getItemValue($oTemp, $oTemp->getAbstract());
						if (DEF_APP_USE_TRANSLATIONS && $translateAbstract) {
							if ($typeAbstract == "int") {
								if ($translateAbstract == 'reference')
									echo " - ".$translator->getByID($itemValue);
							} elseif ($typeAbstract == "enum") {
								if ($translateAbstract == "value")
									echo " - ".$translator->getText($itemValue);
							} else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";
						}
						else {
							echo " - ".$itemValue;
						}
					}
					//echo "</a>";
				}
				else{
					echo "n/a";
				}
			} elseif (DEF_APP_USE_TRANSLATIONS && isset($aNodeToSort[$i]["attrs"]["TRANSLATE"]) && $aNodeToSort[$i]["attrs"]["TRANSLATE"]=="reference"){ // cas de foregin key
				
				$eKeyValue = $translator->getByID ($eKeyValue) ;
				echo $eKeyValue;
			
			} else { // cas typique
				if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){ // cas statut
					echo lib($eKeyValue);
				}
				else{
					if ($eKeyValue > -1){ // cas typique typique
						if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
							
						}
						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "bool"){ // cas enum		
							if ($eKeyValue == 1)  echo "oui"; else  echo "non" ;	 	
						} // fin cas enum
						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum		
							if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
								foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
									if($childNode["name"] == "OPTION"){ // on a un node d'option				
										if ($childNode["attrs"]["TYPE"] == "value"){
											if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
												//echo makeHTMLcodeXMLfriendly($childNode["attrs"]["LIBELLE"]);
												echo stripslashes($childNode["attrs"]["LIBELLE"]);
												break;
											}
										} //fin type  == value				
									}
								}
							}		
						} // fin cas enum
						else{// cas typique typique typique
							// on converti br en \n et on remove les tags 
							echo ereg_replace("<[^<>]+>", "", eregi_replace("<br[^<>]*>", "\n", $eKeyValue));
						}
						if ($aNodeToSort[$i]["attrs"]["NAME"] == "id") $id = $eKeyValue; // on récupére l'id
					}
					else{
						echo "n/a";
					}
				}
			}
			echo "</td>\n";
		//}
	}
}


include("export.association.php"); 
?>
</tr>
<?php
}
?>
</table>
<?php
}
*/
?>