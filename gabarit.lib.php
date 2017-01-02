<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
sponthus 09/06/2005
fonctions de manipulation des gabarits

function getListGabarits($idSite="") 
function getGabaritByName($name, $idSite="") 
function getCountGabaritByName($name, $idSite="") 
function getGabaritByPage($oPage) 
function generateGabarit($divArray, $oInfos_page, $oPage);
function getNbGabarits($idSite, $sName) 
function getRepGabarit($idSite)
*/



// liste de gabarits
// un gabarit est une page avec isgabarit_page = 1 ET nodeid_page = -1
// le gabarit de départ n'est pas sélectionné
function getListGabarits($idSite="") 
{
	global $db;
	$return = array();

	$sql = " SELECT id_page, name_page, gabarit_page, cast(dateadd_page as date) as dateadd_page, ";
	$sql.= " cast(dateupd_page as date) as dateupd_page, ";
	$sql.= " datedlt_page, cast(datemep_page as date) as datemep_page, isgenerated_page, ";
	$sql.= " valid_page, nodeid_page, options_page, isgabarit_page, width_page, height_page, id_site";
	
	$sql.= " FROM cms_page";

//	$sql.= " WHERE datedlt_page is null";

	$sql.= " WHERE isgabarit_page=1";
	$sql.= " AND valid_page=1";

	if (DEF_BDD == "ORACLE" || DEF_BDD == "POSTGRES") {
		$sql.= " AND NOT name_page LIKE '".DEF_GABINIT."%'";
	} else if (DEF_BDD == "MYSQL") {
		$sql.= " AND name_page NOT LIKE '".DEF_GABINIT."%'";
	}

	// site du gabarit (s'il est spécifié)
	if ($idSite != "" && $idSite != -1){
		$sql.= " AND (id_site=".$idSite." OR id_site=-1) ";
	}

	$sql.= " ORDER BY name_page ASC";		

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	//print("<br>".$sql);

	$rs = $db->Execute($sql);

	if($rs==false) {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />gabarit.lib.php > getListGabarits";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$return = false;
	} else {
		while(!$rs->EOF) {
			if (($rs->fields[n('dateupd_page')] != "")&&($rs->fields[n('dateupd_page')] != "0000-00-00")){
				$modif = date('d/m/Y', strtotime($rs->fields[n('dateupd_page')]));
			}
			else{
				$modif = "n/a";
			}
			
			$addDate = date('d/m/Y', strtotime($rs->fields[n('dateadd_page')]));

			$tmparray = array(
				'name' => $rs->fields[n('name_page')].'.php',
				'id' => $rs->fields[n('id_page')],
				'gabarit' => $rs->fields[n('gabarit_page')],
				'creation' => date('d/m/Y',strtotime($rs->fields[n('dateadd_page')])),
				'mep' => date('d/m/Y',strtotime($rs->fields[n('datemep_page')])),
				'modification' => $modif,
				'isgabarit' => $rs->fields[n('isgabarit_page')],
				'width' => $rs->fields[n('width_page')],
				'height' => $rs->fields[n('height_page')],				
				'id_site' => $rs->fields[n('id_site')],				
			);
			array_push($return, $tmparray);
			$rs->MoveNext();
		}
	}
	$rs->Close();
	return $return;
}



// recherche d'un gabarit avec son nom

function getGabaritByName($name, $idSite="") 
{
	global $db;
	// par défautr objet non trouvé -> objet vide
	$result = new Cms_page();

	$sql = " SELECT id_page";
	$sql.= " FROM cms_page";
	$sql.= " WHERE cms_page.name_page = '$name'";
	if ($idSite != "") $sql.= " AND id_site=".$idSite;
	if (DEF_BDD != "ORACLE") $sql.=";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	
	if($rs && !$rs->EOF) {
		
		$oGabarit = new Cms_page($rs->fields[n('id_page')]);

		$result = $oGabarit;

	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />gabarit.lib.php > getGabaritByName";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
	}
	$rs->Close();
	return $result;
}


// count le nombre de gabarits avec ce nom et ce site

function getCountGabaritByName($name, $idSite="") 
{
	global $db;
	$result = 0;

	$sql = " SELECT count(*) as nb_gab";
	$sql.= " FROM cms_page";
	$sql.= " WHERE cms_page.name_page = '$name'";
	if ($idSite != "") $sql.= " AND id_site=".$idSite;
	if (DEF_BDD != "ORACLE") $sql.=";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	
	if($rs && !$rs->EOF) {
		
		$result = $rs->fields[n('nb_gab')];

	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />gabarit.lib.php > getCountGabaritByName";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
	}
	$rs->Close();
	return $result;
}

// recherche d'un gabarit avec un objet page

function getGabaritByPage($oPage) 
{
	global $db;
	$return=false;

	$sNomGab = $oPage->getGabarit_page();

	$sql = " SELECT id_page";
	$sql.= " FROM cms_page";
	$sql.= " WHERE id_page=".$sNomGab;
	$sql.= " AND valid_page=1 AND isgabarit_page=1";
	if (DEF_BDD != "ORACLE") $sql.=";";

//print("<br>$sql");

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	if($rs && !$rs->EOF) {

		$oGab = new Cms_page($rs->fields[n('id_page')]);
		
		$return = $oGab;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />gabarit.lib.php > getGabaritByPage";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
	}
	$rs->Close();
	return $return;
}


// génération d'un gabarit
// INSERT ou UPDATE en BDD tables cms_page et cms_infos_pages
// génération de la page html

function generateGabarit($divArray, $oInfos_page, $oPage)
{
	global $db;

	// enregistrement de la page en BDD : cms_page
	$content = generatePage($divArray, $oPage, $oInfos_page);

	// attention spécifique oracle
	$oPage->setHtml_page($content);
	$id_page = proc_storepage($oPage);
	$oPage->setId_page($id_page);

	// enregistrement de la page en BDD : cms_infos_page
	storeInfosPage($id_page, $oInfos_page); 
	
	// génération du fichier
	generateFile($oPage);
	
	return $id_page;
}


// compte combien de gabarits existent pour ce site et pour ce nom de gabarit

function getNbGabarits($idSite, $sName) {
	$result = 0;
	global $db;

	$sql = " SELECT count(*) as nb_gabarit";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE id_site = $idSite ";
	$sql.= " AND name_page='$sName' AND isgabarit_page=1";
	if (DEF_BDD != "ORACLE") $sql.= ";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
	$rs = $db->Execute($sql);
	
	if($rs && !$rs->EOF) {
		$result = $rs->fields[n('nb_gabarit')];
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />gabarit.lib.php > getGabaritByPage";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());

	  $result = false;
	}
	$rs->Close();
	return $result;
}


// répertoire des gabarits (selon le site)

function getRepGabarit($idSite)
{
	// répertoire des gabarits
	$sRep = $_SERVER['DOCUMENT_ROOT']."/".DEF_GABARIT_ROOT."/";

	// site
	$oSite = new Cms_site($idSite);

	$sRep.= strtolower($oSite->get_rep())."/";

	return $sRep;
}

?>