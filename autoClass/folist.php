<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');
if (!isset($idSite)){	
	$idSite = path2idside($db, $referUrl);
} //-------------------------------------------------------------
	 
$oSite = new Cms_site($idSite);
$oLg = new Cms_langue($oSite->get_langue());
$slg = strtolower($oLg->get_libellecourt());
///////////////////////////////////////////////
// sponthus 29/11/2005
//
// 	Affichage d'une table générique
///////////////////////////////////////////////

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
 include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

$bDebug = false;
$sMessage="";

// objet 
eval("$"."oRes = new ".$classeName."();");

$sXML = $oRes->XML;

xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

foreach ($aNodeToSort as $key => $node){	
	if ($node["name"] == "LANGPACK"){
		$classLang = $node["attrs"]["LANG"];
		$langPack = $node["children"];
	}
}

if($oRes) {

//===============================
// operations de BDD
//===============================



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

//tri sur l'ordre
// le premier tri est ôté de la liste pour être placé en premier par la suite
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if (($aNodeToSort[$i]["attrs"]["ORDER"] == "true") && ($aNodeToSort[$i]["attrs"]["TYPE"] != "text")){
				//echo $aNodeToSort[$i]["attrs"]["NAME"];
			if ($aNodeToSort[$i]["attrs"]["NAME"] == "ordre"){
				$sAscDesc = "ASC";
				if ($_SESSION['champTri_res'] != $classePrefixe."_ref") $aListeTri[] = new dbTri($classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $sAscDesc);			
			}
		}
	}
}


// le premier tri est ôté de la liste pour être placé en premier par la suite
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if (($aNodeToSort[$i]["attrs"]["ORDER"] == "true") && ($aNodeToSort[$i]["attrs"]["TYPE"] != "text")){
				//echo $aNodeToSort[$i]["attrs"]["NAME"];
			if ($aNodeToSort[$i]["attrs"]["NAME"] != "ordre"){
				$sAscDesc = "ASC";
				if ($_SESSION['champTri_res'] != $classePrefixe."_ref") $aListeTri[] = new dbTri($classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $sAscDesc);			
			}
		}
	}
}
// tri numéro 1 => celui demandé dans l'interface
if ( isset($_SESSION['fo_champTri_res']) && $_SESSION['fo_champTri_res']!="") {

	if ($_SESSION['fo_champTri_res'] != "") $aGetterOrderBy[] = $_SESSION['fo_champTri_res'];
	if ($_SESSION['fo_sensTri_res']  != "") $aGetterSensOrderBy[] = $_SESSION['fo_sensTri_res'];
}
else{
	// on récupere rien
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
// who rules ?
// 1 POST, 2 GET, 3 SESSION
if (is_post('sTexte', false)){
	$sTexte=trim($_POST['sTexte']);
	$_SESSION['sTexte']=$sTexte;
}
elseif (is_get('sTexte', false)){
	$sTexte=trim($_GET['sTexte']);
	$_SESSION['sTexte']=$sTexte;
}
else{
	$sTexte = $_SESSION['sTexte'];
}

if ($sTexte != "") {
	//$_SESSION['sTexte']=$sTexte;

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
	$aCond = array();
	for ($i=0;$i<count($aNodeToSort);$i++){
		if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
			array_push ($aCond, ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$sTexte."%' ");
		}
	}

	$sRechercheTexte = "(".join(" OR ", $aCond).")";
	$oRech->setJointureBD($sRechercheTexte);
	$oRech->setPureJointure(1);
	$aRecherche[] = $oRech;
	
}//fin if ($sTexte != "")

//**************** modif thao **************************
if(isset($_GET['param'])&&$_GET['param']!=""){
	$nbSub = substr_count($_GET['param'], "statut");
	$oRech->setValeurRecherche("declencher_recherche");
	$oRech->setTableBD($classeName);
	$oRech->setJointureBD(" ".$_GET['param']." ".urldecode($_GET['comparateur'])." ".$_GET[$_GET['param']]);
	$oRech->setPureJointure(1);
	$aRecherche[] = $oRech;
}


if(isset($_GET['param2'])&&$_GET['param2']!=""){
	$nbSub2 = substr_count($_GET['param2'], "statut");
	$oRech3 = new dbRecherche();
	$oRech3->setValeurRecherche("declencher_recherche");
	$oRech3->setTableBD($classeName.";".$_GET['paramtype2']);
	$requete = " ".$_GET['paramtype2']."_".$_GET['param2']." ".urldecode($_GET['comparateur2'])." ".$_GET[$_GET['param2']]." ";
	
	if ($classeName != $_GET['paramtype2']){
		$requete.= "AND ".$classeName."_".$_GET['paramtype2']."=".$_GET['paramtype2']."_id";
	}
	$oRech3->setJointureBD($requete);
	$oRech3->setPureJointure(1);
	$aRecherche[] = $oRech3;
}
//**************** fin modif thao **************************

//////////////////////////
// recherche par statut
//////////////////////////
if (isset($_POST['eStatut'])){
$eStatut=$_POST['eStatut'];
$_SESSION['eStatut']=$eStatut;
}
if($eStatut==""){
$eStatut=DEF_ID_STATUT_LIGNE;
//$eStatut=$_SESSION['eStatut'];
}

// old if ($eStatut != -1 && $eStatut != "") {
if ($eStatut != -1 && $eStatut != "" && $nbSub == 0) {
	$oRech2 = new dbRecherche();
	
	$oRech2->setValeurRecherche("declencher_recherche");
	$oRech2->setTableBD($classeName);
	$oRech2->setJointureBD(" ".ucfirst($classePrefixe)."_statut=".$eStatut." ");
	$oRech2->setPureJointure(1);
	
	$aRecherche[] = $oRech2;
}


if (isset ($oRech_temp)) {
	$aRecherche[] = $oRech_temp;
}
///////////////////////////////
// recherche par filter (enum)
///////////////////////////////

$aPostFilters = getFilterPosts();  
 

if ($aPostFilters != false){
	
	foreach($aPostFilters as $kFilter => $aPostFilter){
		
		foreach($aPostFilter as $filterName => $filterValue){
			$_SESSION[$filterName]=$filterValue;
			$_SESSION["filter".ucfirst($filterName)]=$filterValue; 
			 
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
				 
				if(ereg($classePrefixe, $filterName)) {
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
}
else {
	$classeNameAsso = "";
}

$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);

unset($aRecherche);
unset($aGetterOrderBy);
unset($aGetterSensOrderBy);


//echo $sql;

// execution de la requette avec pagination

$sParam = "";
$pager = new Pagination($db, $sql, $sParam, $idSite);
if ($rows_per_page=="") $rows_per_page=20;
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
for ($m=0; $m<sizeof($aId); $m++)
{
	eval("$"."aListe_res[] = new ".$classeName."($"."aId[$"."m]);");
}

/* Pour plus tard au cas où
<div align="left"><a href="javascript:addEmp()" class="arbo">Ajouter un nouvel enregistrement</a></div>
 */
 
// s'il y a des enregistrements à afficher
if(sizeof($aListe_res)>0) {

	if (is_file("export_".$classeName.".php")){
		echo "<a href=\"export_".$classeName.".php\" class=\"arbo\">export .xls</a> | ";
	}
	if (is_file("xml_".$classeName.".php")){
		echo "<a href=\"xml_".$classeName.".php\" class=\"arbo\" target=\"_blank\">xml</a> | ";
	}
	// du rss ?
	$rssFields = 0;
	for ($iFile=0;$iFile<count($aNodeToSort);$iFile++){
		if ($aNodeToSort[$iFile]["name"] == "ITEM"){
			if (isset($aNodeToSort[$iFile]["attrs"]["RSS"]) && ($aNodeToSort[$iFile]["attrs"]["RSS"] != "")){ 
				$rssFields++;
			}
		}
	}
	if (is_file("rss_".$classeName.".php") && ($rssFields > 0)){
		echo "<a href=\"rss_".$classeName.".php\" class=\"arbo\" target=\"_blank\">rss</a> | ";
	}
}
?>
<script src="/backoffice/cms/js/openBrWindow.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" language="javascript">
// fonction de tri
	function doTri(sElementTri, sSensTri) {
		document.<?php echo $classePrefixe; ?>_list_form.champTri.value = sElementTri;

		// on change de tri
		if (document.<?php echo $classePrefixe; ?>_list_form.sensTri.value == "ASC") sSensTri = "DESC";
		else if (document.<?php echo $classePrefixe; ?>_list_form.sensTri.value == "DESC") sSensTri = "ASC";
		else if (document.<?php echo $classePrefixe; ?>_list_form.sensTri.value == "") sSensTri = "ASC";

		document.<?php echo $classePrefixe; ?>_list_form.sensTri.value = sSensTri;
		document.<?php echo $classePrefixe; ?>_list_form.eStatut.value = "<?php echo $_POST['eStatut']; ?>";
		document.<?php echo $classePrefixe; ?>_list_form.sTexte.value = "<?php echo $_POST['sTexte']; ?>";

		document.<?php echo $classePrefixe; ?>_list_form.action = "folist_<?php echo $classeName; ?>.php<?php if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>";
		
		document.<?php echo $classePrefixe; ?>_list_form.submit();
	}

// visu de l'enregistrement
	function visuEmp(id) 
	{
		document.<?php echo $classePrefixe; ?>_list_form.id.value = id;
		document.<?php echo $classePrefixe; ?>_list_form.display.value = null;
		document.<?php echo $classePrefixe; ?>_list_form.actiontodo.value = "";
		document.<?php echo $classePrefixe; ?>_list_form.action = "http://oramip.couleur-citron.com/frontoffice/actualite/foshow_<?php echo $classeName; ?>.php?id="+id+"<?php if($_SERVER['QUERY_STRING']!="") echo "&".str_replace("id=", "idprev=",ereg_replace("idprev=[^&]*&", "", $_SERVER['QUERY_STRING']));?>";
		document.<?php echo $classePrefixe; ?>_list_form.submit();
	}
	
//visu pop-up
	function visupopup(id)
	{
	window.open("http://<?php echo $_SERVER['SERVER_NAME']; ?>/frontoffice/<?php echo $classeName; ?>/foshow_<?php echo $classeName; ?>.php?id="+id+"<?php if($_SERVER['QUERY_STRING']!="") echo "&".str_replace("id=", "idprev=",ereg_replace("idprev=[^&]*&", "", $_SERVER['QUERY_STRING']));?>","", "width=520, height=500, scrollbars=yes");
	}	

</script>
<!-- Pour plus tard au cas où
<div class="arbo" align="center"><strong><?//=$sMessage?></strong></div>

<script>
	
	// ajout d'un enregistrement
	function addEmp()
	{
		document.<?//=$classePrefixe?>_list_form.actiontodo.value = "MODIF";
		document.<?//=$classePrefixe?>_list_form.display.value = null;
		document.<?//=$classePrefixe?>_list_form.action = "maj_<?//=$classeName?>.php?id=-1<?php //if($_SERVER['QUERY_STRING']!="") echo "&".str_replace("id=", "idprev=",ereg_replace("idprev=[^&]*&", "", $_SERVER['QUERY_STRING']));?>";
		document.<?//=$classePrefixe?>_list_form.submit();
	}
	
	// modification de l'enregistrement
	function modifEmp(id) 
	{
		document.<?//=$classePrefixe?>_list_form.id.value = id;
		document.<?//=$classePrefixe?>_list_form.display.value = null;
		document.<?//=$classePrefixe?>_list_form.actiontodo.value = "MODIF";
		document.<?//=$classePrefixe?>_list_form.action = "maj_<?//=$classeName?>.php?id="+id+"<?php //if($_SERVER['QUERY_STRING']!="") echo "&".str_replace("id=", "idprev=",ereg_replace("idprev=[^&]*&", "", $_SERVER['QUERY_STRING']));?>";
		document.<?//=$classePrefixe?>_list_form.submit();
	}

	// suppression de l'enregtistrement
	function deleteEmp(id)
	{
		sMessage = "Etes vous sur(e) de vouloir supprimer cet enregistrement ?";
  		if (confirm(sMessage)) {

			document.<?//=$classePrefixe?>_list_form.action = "list_<?//=$classeName?>.php<?php //if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>";
			document.<?//=$classePrefixe?>_list_form.operation.value = "DELETE";
			document.<?//=$classePrefixe?>_list_form.id.value = id;
			document.<?//=$classePrefixe?>_list_form.display.value = null;
			document.<?//=$classePrefixe?>_list_form.submit();
		}
	}
	
	// change le statut de plusieurs records
	function changeStatut(idStatut)
	{
		cbToChange = "";

		<?php
//for ($m=0; $m<sizeof($aListe_res); $m++) {
	//$oRes = $aListe_res[$m];
	//$cb = "cb_".ucfirst($classePrefixe)."_".$oRes->get_id();
	
?>
if (document.getElementById("<?//=$cb?>").checked == true) cbToChange+= "<?//=$oRes->get_id();?>";
<?php
//}
?>
		if (cbToChange != "") {
			document.<?//=$classePrefixe?>_list_form.cbToChange.value = cbToChange;
			document.<?//=$classePrefixe?>_list_form.idStatut.value = idStatut;
			document.<?//=$classePrefixe?>_list_form.action = "list_<?//=$classeName?>.php<?php //if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>";
			document.<?//=$classePrefixe?>_list_form.operation.value = "CHANGE_STATUT";
			document.<?//=$classePrefixe?>_list_form.submit();

		} else {
			msg = "Sélectionnez au moins un enregistrement";
			alert(msg);		
		}
	}


function sel(nomForm, i, l)
{
    if (eval(nomForm+"."+i+".checked"))

    {
        eval("document.all."+l+".className='EnrSelectionne'");
    }
    else
    {   
        var noLigne=l.substring(5, l.length);

        var classe="impair";
        if (noLigne%2==0) classe="pair";   
   
        eval("document.all."+l+".className='"+classe+"'");
    }   
}


</script>
-->
<?php
if (isset($aCustom["JS"]) && ($aCustom["JS"] != "")){
	echo "<script type=\"text/javascript\" language=\"javascript\">\n";
	//##classePrefixe## -> $classePrefixe
	//##classeName##    -> $classeName
	$search = array("##classePrefixe##", "##classeName##", "##id##");
	$replace = array($classePrefixe, $classeName, $oRes->get_id());
	echo str_replace($search, $replace, $aCustom["JS"]);
	echo "\n</script>\n";
}
?>
<!--
<style>
.pair {background-color: #E6E6E6;}
.impair {background-color: #EEEEEE;}
.EnrSelectionne {background-color: #FBFBEC;}
</style>
-->
<form name="<?php echo $classePrefixe; ?>_list_form" method="post">
<input type="hidden" name="urlRetour" id="urlRetour" value="<?php echo  $_SERVER['REQUEST_URI'] ; ?>" />
<input type="hidden" name="id" id="id" value="" />
<input type="hidden" name="display" id="display" value="" />
<input type="hidden" name="actionUser" id="actionUser" value="" />
<input type="hidden" name="operation" id="operation" value="<?php echo $operation; ?>" />
<input type="hidden" name="actiontodo" id="actiontodo" value="" />
<input type="hidden" name="sensTri" id="sensTri" value="<?php echo $sensTri; ?>" />
<input type="hidden" name="champTri" id="champTri" value="<?php echo $champTri; ?>" />
<input type="hidden" name="idStatut" id="idStatut" value="" />
<input type="hidden" name="cbToChange" id="cbToChange" value="" />
<input type="hidden" name="eStatut" id="eStatut" value="" />
<input type="hidden" name="sTexte" id="sTexte" value="" />
</form>
<?php 
//===============================
// Affichage
//===============================
echo "<div class=\"".replaceBadCarsInStr($classeName)."\" id=\"".replaceBadCarsInStr($classeName)."\">\n";
$tempStyles = ".".replaceBadCarsInStr($classeName)."{\n";
$tempStyles .= "}\n";

echo "<div class='title'>Liste des documents de ".$classeName."</div>\n";
$tempStyles .= ".title"."{\n";
$tempStyles .= "}\n";

echo "<div class='recherche'>\n";
$tempStyles .= ".recherche"."{\n";
$tempStyles .= "}\n";
echo "<form name='".$classePrefixe."_rech_form' method='post'>\n";
echo "<input type='hidden' name='urlRetour' id='urlRetour' value=\"".$_SERVER['REQUEST_URI']."\" />\n";
echo "<input type='hidden' name='id' id='id' value='' />\n";
echo "<input type='hidden' name='display' id='display' value='' />\n";
echo "<input type='hidden' name='actionUser' id='actionUser' value='' />\n";
echo "<input type='hidden' name='operation' id='operation' value=\"".$operation."\" />\n";
echo "<input type='hidden' name='actiontodo' id='actiontodo' value='' />\n";
echo "<input type='hidden' name='sensTri' id='sensTri' value=\"".$sensTri."\" />\n";
echo "<input type='hidden' name='champTri' id='champTri' value=\"".$champTri."\" />\n";
echo "<input type='hidden' name='idStatut' id='idStatut' value='' />\n";
echo "<input type='hidden' name='cbToChange' id='cbToChange' value='' />\n";

echo "<div class='motcle'>recherche par mot clé</div>\n";
$tempStyles .= ".motcle"."{\n";
$tempStyles .= "}\n";
echo "<div class='inputrech'>\n";
$tempStyles .= ".inputrech"."{\n";
$tempStyles .= "}\n";
echo "<input type='text' name='sTexte' id='sTexte' value=\"".$sTexte."\">\n";
echo "</div>\n";
echo "<div class='boutonrech'>\n";
$tempStyles .= ".boutonrech"."{\n";
$tempStyles .= "}\n";
echo "<input type='button' name='btChercher' id='btChercher' onClick=javascript:rechercher(); value='chercher'>\n";
echo "</div></form></div>\n";
?>
<script  type="text/javascript">
// comme c'est écrit dessus
	function rechercher() 
	{
		document.<?php echo $classePrefixe; ?>_rech_form.action = "<?php echo $_SERVER['PHP_SELF']; ?>";
		document.<?php echo $classePrefixe; ?>_rech_form.submit();
	}
</script>
<?php
//Pagination
echo "<div class='pagination'>".$pager->bandeau."</div>\n";
$tempStyles .= ".pagination"."{\n";
$tempStyles .= "}\n";

// s'il y a des enregistrements à afficher
  if(sizeof($aListe_res)>0) {
	eval("$"."oRes = new ".$classeName."();");
	$sXML = $oRes->XML;
	xmlClassParse($sXML);
	
	$classeName = $stack[0]["attrs"]["NAME"];
	$classePrefixe = $stack[0]["attrs"]["PREFIX"];
	$aNodeToSort = $stack[0]["children"]; 
?>
<!-- Pour plus tard au cas où
<table border="0" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo" width="100%">
	<tr>
		<td colspan="6">
		<?php
		
		/* for ($i=0;$i<count($aNodeToSort);$i++){
			if ($aNodeToSort[$i]["name"] == "ITEM"){
				if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){
					$aStatutNode = $aNodeToSort[$i];
					break;
				}
			}
		}

		if (isset($aStatutNode["children"]) && (count($aStatutNode["children"]) > 0)){			
			foreach ($aStatutNode["children"] as $childKey => $childNode){
				if($childNode["name"] == "OPTION"){ // on a un node d'option			
					if ($childNode["attrs"]["TYPE"] == "value"){ */
						?>
						<input type="button" name="btATTEN" id="btATTEN" value="<?//=$childNode["attrs"]["LIBELLE"]?>" class="arbo" style="width:100px" onclick="changeStatut(<?//=$childNode["attrs"]["VALUE"]?>)" />&nbsp;
						<?php
					 //} //fin type  == value				
				//}
			//}
		//} // if nodes children
		//else{	
			?>
			<input type="button" name="btATTEN" id="btATTEN" value="<?//=lib(DEF_ID_STATUT_ATTEN)?>" class="arbo" style="width:100px" onclick="changeStatut(<?//=DEF_ID_STATUT_ATTEN?>)" />&nbsp;
			<input type="button" name="btLIGNE" id="btLIGNE" value="<?//=lib(DEF_ID_STATUT_LIGNE)?>" class="arbo" style="width:100px" onclick="changeStatut(<?//=DEF_ID_STATUT_LIGNE?>)" />&nbsp;
			<input type="button" name="btARCHI" id="btARCHI" value="<?//=lib(DEF_ID_STATUT_ARCHI)?>" class="arbo" style="width:100px" onclick="changeStatut(<?//=DEF_ID_STATUT_ARCHI?>)" />&nbsp; 
			<?php
		//}
		?>
</td>
	</tr>
</table> 

<table border="0" align="center" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo" width="100%">
<tr>
<td align="center" nowrap>&nbsp;</td>-->

<?php
echo "<div class='actions'>Actions</div>";
$tempStyles .= ".actions"."{\n";
$tempStyles .= "}\n";
$tempStyles .= ".".$classeName."_tris"."{\n";
$tempStyles .= "}\n";

echo "<div id=\"".$classeName."_tris\" class=\"".$classeName."_tris\" ><!-- tris -->\n";

for ($i=0;$i<count($aNodeToSort);$i++){	

	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if(($aNodeToSort[$i]["attrs"]["NAME"]=="ordre")||($aNodeToSort[$i]["attrs"]["NAME"]=="statut")){ // enlever l'affichage de ordre et statut
			$aNodeToSort[$i]["attrs"]["LIST"]="false";
		}
		if ($aNodeToSort[$i]["attrs"]["LIST"] == "true"){
			if (isset($aNodeToSort[$i]["attrs"]["ORDER"]) && ($aNodeToSort[$i]["attrs"]["ORDER"] == "true")){
				echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Tri\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Tri\">";
				echo "<a href=\"javascript:doTri('".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]."')\">";
				$stylcherch=strpos($tempStyles,($aNodeToSort[$i]["attrs"]["NAME"])); //pour skip les doublons de style
				if($stylcherch===false){
					$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Tri{\n";
					$tempStyles .= "}\n";
				}
			}			
			if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && ($aNodeToSort[$i]["attrs"]["LIBELLE"] != "")){
				echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."TriLabel\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."TriLabel\">";			
				echo $aNodeToSort[$i]["attrs"]["LIBELLE"];
				$stylcherch=strpos($tempStyles,($aNodeToSort[$i]["attrs"]["NAME"])."Label");
				if($stylcherch===false){
					$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."TriLabel{\n";
					$tempStyles .= "}\n";
				}
			}
			else{
				echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."TriLabel\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."TriLabel\">";			
				echo $aNodeToSort[$i]["attrs"]["NAME"];
				$stylcherch=strpos($tempStyles,($aNodeToSort[$i]["attrs"]["NAME"])."TriLabel");
				if($stylcherch===false){
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."TriLabel"."{\n";
				$tempStyles .= "}\n";
				}
			}
			if (isset($aNodeToSort[$i]["attrs"]["ORDER"]) && ($aNodeToSort[$i]["attrs"]["ORDER"] == "true")){
				echo "</div>\n";
				echo "</a>";
			}
			echo "</div>\n";			
		}
	}	
}
echo "</div><!-- fin  tris -->\n";

// liste
for($k=0; $k<sizeof($aListe_res); $k++) {
	$oRes = $aListe_res[$k];
	
	echo "<div id=\"".$classeName."_record\" class=\"".$classeName."_record\" ><!-- debut record -->\n";
	$tempStyles .= ".".$classeName."_record"."{\n";
	$tempStyles .= "}\n";

if (isset($aCustom["Action"]) && ($aCustom["Action"] != "")){
	// ##id## => id
	$search = array("##classePrefixe##", "##classeName##", "##id##");
	$replace = array($classePrefixe, $classeName, $oRes->get_id());
	echo str_replace($search, $replace, $aCustom["Action"]);	
}

$tempGroup = "nogroup";

for ($i=0;$i<count($aNodeToSort);$i++){

	if ($aNodeToSort[$i]["name"] == "ITEM"){	
	
		// - test group debut ---------------------------------------
		if(nouveauGroup($aNodeToSort[$i], $tempGroup) != false){
			//echo " # new group ";
			$tempGroup = nouveauGroup($aNodeToSort[$i], $tempGroup);
			$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."{\n";
			$tempStyles .= "}\n";
			echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["GROUP"])."\">\n";
		}
				
		if ($aNodeToSort[$i]["attrs"]["LIST"] == "true"){
			echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."\">\n";			
			echo "<div class=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value\" id=\"".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value\">\n";			
			$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
			$stylcherch=strpos($tempStyles,($aNodeToSort[$i]["attrs"]["NAME"])."Value");
				if($stylcherch===false){
				$tempStyles .= ".".replaceBadCarsInStr($aNodeToSort[$i]["attrs"]["NAME"])."Value"."{\n";
				$tempStyles .= "}\n";
				}
			if ($aNodeToSort[$i]["attrs"]["FKEY"]){ // cas de foregin key
				$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
				if ($eKeyValue > -1){
					$oTemp = cacheObject($sTempClasse, $eKeyValue);
					$fKeyViewer = str_replace(basename($_SERVER['PHP_SELF']), "../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php", $_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF']);
					
					if (is_file($fKeyViewer)){
						echo "<a href=\"../".$oTemp->getClasse()."/show_".$oTemp->getClasse().".php?id=".$oTemp->get_id()."\">";
						echo getItemValue($oTemp, $oTemp->getDisplay());
						echo "</a>";
					}
					else{
						
						if (ereg($slg, $oTemp->getDisplay())) $myValue = getItemValue($oTemp, $oTemp->getDisplay());
						else if (ereg($slg, $oTemp->getAbstract())) $myValue = getItemValue($oTemp, $oTemp->getAbstract());
						else $myValue = getItemValue($oTemp, $oTemp->getDisplay());
						
						if (eregi("\.pdf$",$myValue) ) 
							echo "<a href=\"/modules/utils/telecharger.php?file=".$myValue."&chemin=/custom/upload/".$oTemp->getClasse()."/&\" title=\"".$myValue."\">".$myValue."</a>" ;
						else 
							echo $myValue ;
					}
				}
				else{
					echo "";
				}
			}// fin fkey
			elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum"){ // cas enum		
				if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
					foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
						if($childNode["name"] == "OPTION"){ // on a un node d'option				
							if ($childNode["attrs"]["TYPE"] == "value"){
								if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
									echo $childNode["attrs"]["LIBELLE"];
									break;
								}
							} //fin type  == value				
						}
					}
				}		
			} // fin cas enum
			else{ // cas typique
				if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){ // cas statut	
					if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "value"){
									if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){							
										echo $childNode["attrs"]["LIBELLE"];
										break;
									}
								} //fin type  == value				
							}
						}
					} // if nodes children
					else{	
						echo lib($eKeyValue);
					}
				}
				else{
					if ($eKeyValue > -1){ // cas typique typique
						if ($aNodeToSort[$i]["attrs"]["OPTION"] == "file"){ // cas file
							$libelle = $eKeyValue;				
							if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){ // node options
								foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
									if($childNode["name"] == "OPTION"){ // on a un node d'option	
										if (isset($childNode["attrs"]["LIBELLE"])){	
											$libelle = $childNode["attrs"]["LIBELLE"];
										}
									}
								}
							}							
							if (eregi("\.gif$",$eKeyValue) || eregi("\.png$",$eKeyValue) || eregi("\.jpg$",$eKeyValue) || eregi("\.jpeg$",$eKeyValue)){ // image	
							
								if (is_file ($_SERVER['DOCUMENT_ROOT']."/custom/upload/".$classeName."/".$eKeyValue)) {
									echo "<img border=\"0\" alt=\"".$eKeyValue."\" src=\"/custom/upload/".$classeName."/".$eKeyValue."\" />\n";
								}
							}
							else {
								echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$eKeyValue."\" title=\"Visualiser le fichier : '".$eKeyValue."'\">".$libelle."</a>\n";
							}
						}
						else if ($aNodeToSort[$i]["attrs"]["OPTION"] == "bool"){ // boolean
							if (intval($eKeyValue) == 1){
								echo "oui";
							}
							else{
								echo "non";
							}						
						}
						elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "link"){ // cas link			
							echo "<a href=\"".$eKeyValue."\" target=\"_blank\" title=\"Lien édité\">".$eKeyValue."</a><br />\n";					
						}	
						elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp" || $aNodeToSort[$i]["attrs"]["TYPE"] == "datetime") {
							 
							if (ereg("[0-9]{2}[-/]{1}[0-9]{2}[-/]{1}[0-9]{4}[- ]{1}[0-9]{2}[-:]{1}[0-9]{2}[-:]{1}[0-9]{2}", $eKeyValue)){ // FR 2 US 
								$eKeyValue = ereg_replace("([0-9]{2}[-/]{1}[0-9]{2}[-/]{1}[0-9]{4})[- ]{1}[0-9]{2}[-:]{1}[0-9]{2}[-:]{1}[0-9]{2}", "\\1", $eKeyValue);
								
							}
						 
							echo $eKeyValue;
						}
						else{// cas typique typique typique
						
							if (isFieldTranslate($oRes, $aNodeToSort[$i]["attrs"]["NAME"])){
								echo $translator->getByID($eKeyValue, $_SESSION["id_langue"]);	
							
							}
							else {
								echo $eKeyValue;
							}
						}	
					}
					else{
						echo "";
					}
				}
			}
			echo "</div></div>\n";
		}
		
		// test fin de groupe
		if (finGroup($aNodeToSort[$i+1], $tempGroup) == true){
			$tempGroup = "nogroup";
			//echo " # fin de group #";
			echo "</div><!-- fin groupe -->\n";
		}
	}
	
	
}

/*  
<tr class="<?php echo htmlImpairePaire($k);?>">

 <td align="center" nowrap class="arbo" >&nbsp;<input type="checkbox" name="cb_<?php echo ucfirst($classePrefixe); ?>_<?php echo $oRes->get_id(); ?>" id="cb_<?php echo ucfirst($classePrefixe); ?>_<?php echo $oRes->get_id(); ?>" value="<?php echo $oRes->get_id(); ?>" class="arbo" onclick="sel('<?php echo ucfirst($classePrefixe); ?>_list_form','cb_<?php echo ucfirst($classePrefixe); ?>_<?php echo $oRes->get_id(); ?>','ligne<?php echo $k; ?>')" /></td>

<td align="center" nowrap class="arbo">
 */
	echo "<div class='lienvisu'>\n";
	echo "<a href=javascript:visupopup(".$oRes->get_id().") title='Visualiser'>Visualiser</a>\n";
	$tempStyles .= ".lienvisu"."{\n";
	$tempStyles .= "}\n";
	echo "</div>\n";
// pour plus tard au cas où
//echo "<a href=\"javascript:modifEmp('".$oRes->get_id()."')\" title='Modifier'><img src='/backoffice/cms/img/modifier.gif' border='0' alt='Modifier' align='top' /></a>&nbsp";
//echo "<a href=\"javascript:deleteEmp('".$oRes->get_id()."')\" title='Supprimer'><img src='/backoffice/cms/img/supprimer.gif' border='0' alt='Supprimer' align='top' /></a>&nbsp";


echo "<br style=\"clear: both;\"/></div><!-- fin div id=\"".$classeName."_record\" class=\"".$classeName."_record\" > -->\n";
}
} else {
	if (isset($langPack)){
		$norecords = getNodeByName($langPack, "NORECORDS");
		if (isset($norecords["cdata"])){
			echo "<div>".stripslashes($norecords["cdata"])."</div>";
		}
		else{
			echo "<div>Aucun enregistrement à afficher</div>";
		}
	}
	else{
		echo "<div>Aucun enregistrement à afficher</div>";
	}

}
echo "</div>\n";
echo "<!-- styles -- sample --\n";
echo "<style type=\"text/css\">\n";
echo $tempStyles;
echo ".fermer{\n";
echo "}\n";
echo "</style>\n";
echo "-- styles -- sample -->\n";
} else {
	die("Erreur ".$classeName." non trouvé");
}
?>