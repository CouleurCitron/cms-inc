<?php

ini_set ('max_execution_time', 0); // Aucune limite d'execution
ini_set("memory_limit","-1");

include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------

// permet de dérouler le menu contextuellement
if (strpos($_SERVER['PHP_SELF'], "backoffice") !== false){
	if (function_exists('activateMenu')){
	activateMenu('gestion'.$classeName);
}  
}

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/htmlUtility.php'); 
/*
$neededRAM = 512; // Mo

$localRAM = (int)intval(str_replace('M', '', ini_get('memory_limit')));
if ($localRAM < $neededRAM){
	@ini_set('memory_limit', $neededRAM.'M'); 
}*/

// objet 
eval("$"."oRes = new ".$classeName."();");

$sXML = $oRes->XML;
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

if($oRes) {



require_once 'cms-inc/lib/Excel/reader.php';

// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('CP1251');
//$data->setOutputEncoding('ISO88591');


$bDebug = false;
$bDebug = true;

$sMessage="";
$sRapportMailDejaPresent = "";
$sRapportMailMauvaiseSyntaxe = "";
$eMailDejaPresent = 0;
$eMailMauvaiseSyntaxe = 0;
$eMailSucces = 0;
$eImportSucces = 0;
$bIsinscrit = false;
$aChamps = array();

for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_VIEW"] || $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]) {
		if ($aNodeToSort[$i]["attrs"]["ASSO"])
			$liste_asso = $aNodeToSort[$i]["attrs"]["ASSO"];
		elseif ($aNodeToSort[$i]["attrs"]["ASSO_VIEW"])
			$liste_asso = $aNodeToSort[$i]["attrs"]["ASSO_VIEW"];
		elseif ($aNodeToSort[$i]["attrs"]["ASSO_EDIT"])
			$liste_asso = $aNodeToSort[$i]["attrs"]["ASSO_EDIT"];
	}
}




$uploadRep = $_SERVER['DOCUMENT_ROOT']."/custom/upload/";

//------------------------------------------------
function printCheck($postedValue){
	if (!isset($postedValue)){
		echo "";	
	}
	elseif(intval($postedValue) > 0){
		echo " checked";
	}
	elseif(((bool)($postedValue)) == true){
		echo " checked";
	}
	elseif (strval($postedValue) == "true"){
		echo " checked";
	}
	else{
		echo "";
	}
}
//---------------------------------------------

$sRapport = "";

if (count($_POST) == 0){	
	if (count($_GET) > 0){
	
		$_POST = $_GET;
		
		if (isset($_GET['importfile'])){
			if (is_file($_SERVER['DOCUMENT_ROOT'].$_GET['importfile'])){
				$_FILES['importfile']['tmp_name'] = $_SERVER['DOCUMENT_ROOT'].$_GET['importfile'];
				$saveFile = $_SERVER['DOCUMENT_ROOT'].$_GET['importfile'];
				
			}
		}
	}
}
else{
	$ext = preg_replace('/.*\.([^\.]+)/si', '$1', $_FILES['importfile']['name']);
	$saveFile = $uploadRep.'import.'.$ext;
}
	

if(isset($_FILES['importfile'])){
	// Upload du fichier
	
	if(move_uploaded_file($_FILES['importfile']['tmp_name'], $saveFile) || is_file($_SERVER['DOCUMENT_ROOT'].$_GET['importfile'])){
		$status .= "Téléchargement du fichier : OK <br /><br />"; 
		if (preg_match('/xls/', $ext)){
			//echo ' import de XLS<br />';
		}
		else{
			echo ' import de type non excel<br />';
			die(false);
		}	
		
		
		// controle ficher XLS HTML
		$fh = @fopen($uploadRep.'import.xls','r');
		if ($fh){
			while(!feof($fh)) {
				$sBodyXLS.=fgets($fh);
			}
		}
		
		if (strpos($sBodyXLS, "<!-- htmlxls -->") !== false){
			echo "fichier XLS HTML en provenance d'un export obsolète\n<br>";

			/*
			$rows = split("<tr>", $sBodyXLS);
			
			// check ligne 0
			if (strpos($rows[0], "</tr>") === false){
				//$rows = array_slice($rows, 1); 
			}
			
			// traitement des lignes
			for ($i = 0;$i < count($rows);$i++){
				$rows[$i] = str_replace("</tr>", "", $rows[$i]);
				$rows[$i] = split("<td>", $rows[$i]);
				//check cell 0
				if (strpos($rows[$i][0], "</td>") === false){
					$rows[$i][0] = "";// = array_slice($rows[$i], 1); 
				}
				// traitement des cells
				for ($j = 0;$j < count($rows[$i]);$j++){
					$rows[$i][$j] = str_replace("</td>", "", $rows[$i][$j]);
					$rows[$i][$j] = trim($rows[$i][$j]);
				}
			}
			
			$data->sheets[0]['cells'] = $rows;
			$data->sheets[0]['numRows'] = count($rows)-1;
			$data->sheets[0]['numCols'] = count($rows[1])-1;
			*/
		
		}
		else{			
			//$data->read($uploadRep.'import.csv');		
		}
		
		//error_reporting(E_ALL ^ E_NOTICE);
		$bIntegre = true;
		// chercher une signature d'intégrité FINYYYY-MM-DD
		 
		if (isset($_POST["signature"]) && (intval($_POST["signature"]) == 1)){ 
			$bSignature = true; 
			$bIntegre = false;
			$fh = fopen($saveFile,'r');
			if ($fh){
				while(!feof($fh)) {
					$ligne = fgets($fh);
					//echo $ligne;
					if (preg_match ("/FIN/", $ligne)){ 
						$bIntegre = true; 
						break;					
					}
				}
			}
			fclose($fh);
		}
		if ($bIntegre == false){
			die("false");
		}
		//----------------------------------------------------------	

		// do purge ??
		if (isset($_POST["dopurge"]) && (intval($_POST["dopurge"]) == 1)){
			$purgeSQL = "truncate ".$oRes->getTable();
			//echo $purgeSQL."<br />";
			dbExecuteQuery($purgeSQL);
			
			// vide les table d'asso
			if ($liste_asso != '') {
				$aTempClasse = split(',', $liste_asso);		
				for ($m=0; $m<sizeof($aTempClasse);$m++) {
					$sTempClasse = $aTempClasse[$m];  
					$purgeSQL = "truncate ".$sTempClasse;
					//echo $purgeSQL."<br />";
					dbExecuteQuery($purgeSQL);
				}
			}
					
 		
		}
		else{
			// nada
		}
		//----------------------------------------------------------
		
		// skip line 1 ??
		if (isset($_POST["ignoreline1"]) && (intval($_POST["ignoreline1"]) == 1)){
			$startLine = 1;
		}
		else{
			$startLine = 0;
		}
		//----------------------------------------------------------
		
		// import ids ??
		if (isset($_POST["importids"]) && (intval($_POST["importids"]) == 1)){
			$bImportIds = true;
		}
		else{
			$bImportIds = false;
		}
		//----------------------------------------------------------
		
		// remplir les données vides avec le précédent record ??
		if (isset($_POST["fillnull"]) && (intval($_POST["fillnull"]) == 1)){
			$bFillNull = true;
		}
		else{
			$bFillNull = false;
		}
		//----------------------------------------------------------		
		
		if (preg_match('/xls/si', $ext)){
			include('import.xlsx.php');
		}
		elseif (preg_match('/csv/si', $ext)){
			include('import.csv.php');
		}

		$status .= "Suppression du fichier après importation OK <br /><br />";
		
		$status = "<span class=\"arbo2\"><strong>".$status."</strong></span>";
		
		if (is_file($_SERVER['DOCUMENT_ROOT'].DEF_ROOT_IMPORTREPORT."export_inscrit_doublon.csv")) {
			unlink($_SERVER['DOCUMENT_ROOT'].DEF_ROOT_IMPORTREPORT."export_inscrit_doublon.csv");
		}
		
		if (is_file($_SERVER['DOCUMENT_ROOT'].DEF_ROOT_IMPORTREPORT."export_inscrit_syntaxe.csv")) {
			unlink($_SERVER['DOCUMENT_ROOT'].DEF_ROOT_IMPORTREPORT."export_inscrit_syntaxe.csv");
		}
		// resultat de l'inport
		//--------------------------------------------
		echo "<table  border=\"0\" cellpadding=\"10\" cellspacing=\"0\" class=\"arbo\" width=\"600\" bgcolor=\"#cccccc\">";
		echo "<tr><td>"; 
		
		if ($error != '')  {
			echo "<p class=\"error\">".$error."</p>"; 
		}
		else {
		
			echo "<span class=\"arbo2\"><strong>Résultats de l'import : </strong></span><br><br>";
			if ($bIsinscrit) {
				echo "<span class=\"arbo2\">".$eMailSucces." inscrits importés avec succès</span><br><br>";
				if (($eMailDejaPresent>0 || $eMailMauvaiseSyntaxe>0) && $bIsEmail) { 
					if (!defined(DEF_ROOT_IMPORTREPORT)) {
						define ("DEF_ROOT_IMPORTREPORT", '/custom/export/');
						
					}
					 
					if(!is_dir($_SERVER['DOCUMENT_ROOT'].DEF_ROOT_IMPORTREPORT)){
						mkdir ($_SERVER['DOCUMENT_ROOT'].DEF_ROOT_IMPORTREPORT);
						
					}
					
					if ($eMailDejaPresent>0) {
						$sRetour1 = export_list_inscrits($_SERVER['DOCUMENT_ROOT'].DEF_ROOT_IMPORTREPORT, $sRapportMailDejaPresent, $aChamps, "doublon");
						if ($sRetour1!="") {
							echo "<span class=\"\"><a href=\"".$_SERVER['HOST_NAME'].DEF_ROOT_IMPORTREPORT."export_inscrit_doublon.csv\">Exporter les doublons (".$eMailDejaPresent." inscrit(s))</a></span><br>";
						}
						
					}
					if ($eMailMauvaiseSyntaxe>1) {
						$sRetour2 = export_list_inscrits($_SERVER['DOCUMENT_ROOT'].DEF_ROOT_IMPORTREPORT, $sRapportMailMauvaiseSyntaxe, $aChamps, "syntaxe");
						if ($sRetour2!="") {
				
							echo "<span class=\"\"><a href=\"".$_SERVER["HOST_NAME"].DEF_ROOT_IMPORTREPORT."export_inscrit_syntaxe.csv\">Exporter les mails avec une mauvaise syntaxe (".($eMailMauvaiseSyntaxe-1)." inscrit(s))</a></span><br>";
						}
					}
				}
			
			}
			else {
				echo "<span class=\"arbo2\">".$eImportSucces." imports réussis</span><br><br>";	
			}
		}
		echo "</td></tr></table>";
		// ----------------------------------------------
		
		
	}else{
		$status = "<span class=\"alert\"><strong>Erreur lors de du téléchargement du fichier</strong></span>";
	}
}


// creation des gabartis d'import


if (isClassCMS($classeName)) {
	if (!is_file ($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/".$classeName."/list_".$classeName.".php")) {
		$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/".$classeName."/list_".$classeName.".php", "w");
		$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
		fwrite($list, $listContent);
		fclose($list);
	}
	if (!is_file ($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/".$classeName."/exportcsv_".$classeName.".php")) {
		$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/".$classeName."/exportcsv_".$classeName.".php", "w");
		$listContent = "<"."?php include('cms-inc/autoClass/exportcsv.php'); ?".">";
		fwrite($list, $listContent);
		fclose($list);
	}
	if (!is_file ($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/".$classeName."/xlsx_".$classeName.".php")) {
		$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/".$classeName."/xlsx_".$classeName.".php", "w");
		$listContent = "<"."?php include_once(".$_SERVER['DOCUMENT_ROOT']."'/include/autoprepend.php'); ?".">";
		$listContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
		fwrite($list, $listContent);
		fclose($list);
	}
	$lien_gabarit = "<a href='/backoffice/cms/".$classeName."/exportcsv_".$classeName.".php?Type=template'>Télécharger un modèle de fichier d'import - format csv </a>";
	$lien_gabarit_xlsx  = "<a href='/backoffice/cms/".$classeName."/xlsx_".$classeName.".php?Type=template'>Télécharger un modèle de fichier d'import - format xls </a>";
}
else {
	if (!is_file ($_SERVER['DOCUMENT_ROOT']."/backoffice/".$classeName."/list_".$classeName.".php")) {
		$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/".$classeName."/list_".$classeName.".php", "w");
		$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
		fwrite($list, $listContent);
		fclose($list);
	}
	if (!is_file ($_SERVER['DOCUMENT_ROOT']."/backoffice/".$classeName."/exportcsv_".$classeName.".php")) {
		$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/".$classeName."/exportcsv_".$classeName.".php", "w");
		$listContent = "<"."?php include('cms-inc/autoClass/exportcsv.php'); ?".">";
		fwrite($list, $listContent);
		fclose($list);
	}
	if (!is_file ($_SERVER['DOCUMENT_ROOT']."/backoffice/".$classeName."/xlsx_".$classeName.".php")) {
		$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/".$classeName."/xlsx_".$classeName.".php", "w");
		$listContent = "<"."?php include_once(".$_SERVER['DOCUMENT_ROOT']."'/include/autoprepend.php'); ?".">";
		$listContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
		fwrite($list, $listContent);
		fclose($list);
	}
	$lien_gabarit = "<a href='/backoffice/".$classeName."/exportcsv_".$classeName.".php?Type=template'>Télécharger un modèle de fichier d'import - format csv </a>";
	$lien_gabarit_xlsx = "<a href='/backoffice/cms/".$classeName."/xlsx_".$classeName.".php?Type=template'>Télécharger un modèle de fichier d'import - format xls </a>";
}



?>
<style >
type="text/css">
	.lignevalidee {
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 11px;
		text-decoration: none;
		font-weight: normal;
		letter-spacing: normal;
	}
	tr.lignevalidee:hover {
		background-color: #DDEAAB;
	}
	.lignenonvalidee {
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 11px;
		text-decoration: none;
		font-weight: bold;
		letter-spacing: normal;
	}
	tr.lignenonvalidee:hover {
		background-color: #DDEAAB;
	}
</style>
<p><span class="arbo2"><strong>Import d'un fichier de données</strong></span><br />

  <br />
</p>
<p><strong>Upload d'un fichier CSV</strong><br />

  <br />
</p>
<p>
	<span class="export_template"><img align="top" border="0" alt="Import" src="/backoffice/cms/img/go.gif">&nbsp;<strong><?php echo $lien_gabarit_xlsx; ?></strong></span><br />

  <br />
</p>

<table  border="1" cellpadding="5" cellspacing="0" class="arbo" width="600">
<form name="formFile" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

 <tr bgcolor="#CCCCCC" valign="middle">
  <td nowrap align="center">&nbsp;<b>S&eacute;lection du fichier</b>&nbsp;</td>
  <td nowrap align="left"><input type="file" name="importfile" id="importfile" class="arbo" pattern=".+(\.(csv|xls))" errorMsg="Vous devez sélectionner un fichier qui porte l'extension .csv ou .xls" value="*.csv"></td>
 </tr>
 <tr valign="middle" bgcolor="#CCCCCC">
   <td nowrap="nowrap" align="center">purger avant l'import </td>
   <td nowrap="nowrap" align="left"><input type="checkbox" id="dopurge" name="dopurge" value="1" <?php echo printCheck($_POST["dopurge"])?> /></td>
 </tr>
 <tr bgcolor="#CCCCCC" valign="middle">
   <td nowrap align="center">ignorer la premi&egrave;re ligne (nom des champs) </td>
   <td nowrap align="left"><input type="checkbox" id="ignoreline1" name="ignoreline1" value="1" <?php echo printCheck($_POST["ignoreline1"])?> /></td>
 </tr>
 <tr bgcolor="#CCCCCC" valign="middle">
   <td nowrap align="center">la premi&egrave;re colonne contient les ids </td>
   <td nowrap align="left"><input type="checkbox" id="importids" name="importids" value="1" <?php echo printCheck($_POST["importids"])?> /></td>
 </tr>
 <tr valign="middle" bgcolor="#CCCCCC">
   <td nowrap="nowrap" align="center">remplir les champs vides avec les donn&eacute;es de la ligne pr&eacute;c&eacute;dente</td>
   <td nowrap="nowrap" align="left"><input type="checkbox" id="fillnull" name="fillnull" value="1" <?php echo printCheck($_POST["fillnull"])?> /></td>
 </tr>
 <tr valign="middle" bgcolor="#CCCCCC">
   <td nowrap="nowrap" align="center">signature de fin de fichier de type <b>FIN</b></td>
   <td nowrap="nowrap" align="left"><input type="checkbox" id="signature" name="signature" value="1" <?php echo printCheck($_POST["signature"])?> /></td>
 </tr>
 <tr bgcolor="#CCCCCC" valign="middle">
   <td nowrap align="center">&nbsp;</td>
   <td nowrap align="left"><input type="button" value="Importer" name="import" onclick="javascript:if (validate_form(0)) submit();" class="arbo" /></td>
 </tr>
</form>
</table> 
<p>&nbsp;</p>
<p><strong><br />
  <br />
  Traitement d'un fichier pr&eacute;sent sur le serveur (traitement automatisable)</strong><br />
  <br />
</p>
<table  border="1" cellpadding="5" cellspacing="0" class="arbo" width="600">
<form name="formFile" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">

 <tr bgcolor="#CCCCCC" valign="middle">
  <td width="388" align="center" nowrap>&nbsp;<b>S&eacute;lection du fichier</b>&nbsp;</td>
  <td width="186" align="left" nowrap><input type="text" name="importfile" id="importfile" class="arbo" size="35" value="<?php echo $_GET['importfile']?>"></td>
 </tr>
 <tr valign="middle" bgcolor="#CCCCCC">
   <td nowrap="nowrap" align="center">purger avant l'import </td>
   <td nowrap="nowrap" align="left"><input type="checkbox" id="dopurge" name="dopurge" value="1" <?php echo printCheck($_GET["dopurge"])?> /></td>
 </tr>
 <tr bgcolor="#CCCCCC" valign="middle">
   <td nowrap align="center">ignorer la premi&egrave;re ligne (nom des champs) </td>
   <td nowrap align="left"><input type="checkbox" id="ignoreline1" name="ignoreline1" value="1" <?php echo printCheck($_GET["ignoreline1"])?> /></td>
 </tr>
 <tr bgcolor="#CCCCCC" valign="middle">
   <td nowrap align="center">la premi&egrave;re colonne contient les ids </td>
   <td nowrap align="left"><input type="checkbox" id="importids" name="importids" value="1" <?php echo printCheck($_GET["importids"])?> /></td>
 </tr>
 <tr valign="middle" bgcolor="#CCCCCC">
   <td nowrap="nowrap" align="center">remplir les champs vides avec les donn&eacute;es de la ligne pr&eacute;c&eacute;dente</td>
   <td nowrap="nowrap" align="left"><input type="checkbox" id="fillnull" name="fillnull" value="1" <?php echo printCheck($_GET["fillnull"])?> /></td>
 </tr>
 <tr bgcolor="#CCCCCC" valign="middle">
   <td nowrap align="center">&nbsp;</td>
   <td nowrap align="left"><input type="button" value="Importer" name="import" onclick="javascript:submit();" class="arbo" /></td>
 </tr>
</form>
</table>

<?php 

} // fin if oRes

// on supprime les fichiers

if (is_file($saveFile)){
	unlink($saveFile);

}

// restore de la ram
@ini_set('memory_limit',$localRAM.'M');

//\">Exporter les mails avec une mauvaise syntaxe (".($eMailMauvaiseSyntaxe-1)." inscrit(s))</a></span><br>";
			
// je crée le dossier custom/rapport

/*echo "mails deja present : ".$eMailDejaPresent."<br>";
//echo $sRapportMailDejaPresent;
$sFilename = $sRep."export_inscrit_".$sTypeExport."_".$sDate.".csv";
echo "mails MauvaiseSyntaxe : ".$eMailMauvaiseSyntaxe."<br>";
//echo $sRapportMailMauvaiseSyntaxe; 
}

echo $sRapport */
?>