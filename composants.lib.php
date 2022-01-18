<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
$Id: composants.lib.php,v 1.2 2013-10-02 14:51:06 raphael Exp $
$Author: raphael $

$Log: composants.lib.php,v $
Revision 1.2  2013-10-02 14:51:06  raphael
Modifications de la sauvegarde des traductions pour passer dans la fonction do_rewrite_rules()
 dans laquelle elle ne passait pas auparavant !

Revision 1.1  2013-09-30 09:28:22  raphael
*** empty log message ***

Revision 1.11  2013-03-01 10:33:58  pierre
*** empty log message ***

Revision 1.10  2011-05-16 13:30:39  pierre
*** empty log message ***

Revision 1.9  2010-10-08 09:55:53  pierre
*** empty log message ***

Revision 1.8  2010-01-08 11:30:22  pierre
*** empty log message ***

Revision 1.7  2008-10-21 09:20:46  pierre
*** empty log message ***

Revision 1.5  2008-07-25 17:56:42  pierre
*** empty log message ***

Revision 1.4  2008-07-25 17:55:41  pierre
*** empty log message ***

Revision 1.3  2007/11/29 16:48:50  pierre
*** empty log message ***

Revision 1.2  2007/09/05 13:35:22  pierre
*** empty log message ***

Revision 1.1  2007/08/08 13:07:18  thao
*** empty log message ***

Revision 1.2  2007/08/03 16:06:09  thao
*** empty log message ***

Revision 1.2  2007/05/21 16:52:35  pierre
*** empty log message ***

Revision 1.2  2007/04/03 07:42:32  remy
*** empty log message ***

Revision 1.1  2006/12/15 12:31:44  pierre
*** empty log message ***

Revision 1.1.1.1  2006/01/25 15:14:27  pierre
projet CCitron AWS 2006 Nouveau Website

Revision 1.4  2005/11/08 15:48:15  sylvie
*** empty log message ***

Revision 1.3  2005/11/07 16:28:12  sylvie
*** empty log message ***

Revision 1.2  2005/10/27 16:15:32  pierre
*** empty log message ***

Revision 1.1.1.1  2005/10/24 13:37:05  pierre
re import fusion espace v2 et ADW v2

Revision 1.3  2005/10/21 10:46:46  sylvie
*** empty log message ***

Revision 1.2  2005/10/21 10:24:56  sylvie
*** empty log message ***

Revision 1.1.1.1  2005/10/20 13:10:54  pierre
Espace V2

Revision 1.1.1.1  2005/04/18 13:53:29  pierre
again

Revision 1.1.1.1  2005/04/18 09:04:21  pierre
oremip new

Revision 1.1.1.1  2004/11/03 13:49:54  ddinside
lancement du projet - import de adequat

Revision 1.1.1.1  2004/04/01 09:20:29  ddinside
Création du projet CMS Couleur Citron nom de code : tipunch

Revision 1.5  2004/02/12 15:56:16  ddinside
mise à jour plein de choses en fait, mais je sais plus quoi parce que ça fait longtemps que je l'avais pas fait.
Mea Culpa...

Revision 1.4  2004/02/05 15:56:26  ddinside
ajout fonctionnalite de suppression de pages
ajout des styles dans spaw
debuggauge prob du nom de fichier limite à 30 caracteres

Revision 1.3  2004/01/27 12:12:51  ddinside
application de dos2unix sur les scripts SQL
modification des scripts d'ajout pour correction bug lors d'une modif de page
ajout foncitonnalité de modification de page
ajout visu d'une page si créée

Revision 1.2  2004/01/07 18:27:37  ddinside
première mise à niveau pour plein de choses

Revision 1.1.1.1  2003/10/24 09:08:08  ddinside
nouvel import projet Boulogne apres migration machine

Revision 1.2  2003/10/16 21:19:46  ddinside
suite dev gestio ndes composants
ajout librairies d'images
suppressions fichiers vi
ajout gabarit

Revision 1.1  2003/10/13 16:08:42  ddinside
activation fonction enregistrement composant

*/

// sponthus 20/06/2005
// ajout de id_site dans storecomposant

/*

function storeComposant($idSite, $oCms_content) {
function deleteComposant($id) {
function moveComposant($id,$node_id) {
function getComposantById($id) {
function getComposantArchiById($id) {
function getPageUsingComposant($idSite, $id) {

*/


function storeComposant($idSite, $oCms_content) {
	// repertoire du minisite
	if ($idSite == $_SESSION["idSite"]){
		$rep = $_SESSION["rep_travail"];
	}
	else{
		$oSite = new cms_site($idSite);
		$rep = $oSite->get_rep();
	}
	pre_dump($oCms_content);
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/include/modules/'.$rep.'/mod_rewrite.inc.php')){	
		include_once('modules/'.$rep.'/mod_rewrite.inc.php');		
		if (function_exists('do_rewrite_rule')){
			// oui, on a de quoi faire du rewrite		
			$oCms_content->setHtml_content(do_rewrite_rule($oCms_content->getHtml_content()));		
		}
	}


	$oCms_content->setId_site($idSite);
	
	if ($oCms_content->getId_content() != "")
		$eIdContent = getCount("cms_content", "*", "id_content", $oCms_content->getId_content());
	else 
		$eIdContent = 0;

	if ($eIdContent == 0) {
		// nouvelle valeur de clé
		$oCms_content->setId_content(getNextVal("cms_content", "id_content"));

		// INSERT
		$result = $oCms_content->cms_content_insert();
	} else {
		// UPDATE
		$result = $oCms_content->cms_content_update();
	}

	if ($result > 0)
		$return = true;
	else 
		$return = false;
		
	return $return;
}

function deleteComposant($id) {
	global $db;
	$return = false;

	// objet cms_content
	$oCms_content = new Cms_content();

	// alimentation objet
	$oCms_content->setId_content("$id");

	// delete
	$return = $oCms_content->cms_content_delete();

	return $return;
}

function moveComposant($id, $node_id) {
	global $db;
	$return = false;

	$oContent = new Cms_content($id);
	$oContent->setNodeid_content($node_id);

	$return = proc_movecomposant($oContent);

	return $return;
}


function getComposantById($id) {
	global $db;
	$return = array();

	if (DEF_BDD == "POSTGRES") {

		$sql = " SELECT id_content, name_content, type_content, width_content, height_content, ";
		$sql.= " html_content, nodeid_content, id_site, obj_table_content, obj_id_content ";
		$sql.= " FROM cms_content ";
		$sql.= " WHERE id_content=$id ";
		$sql.= " AND valid_content='t' ";
		$sql.= " AND actif_content='t' ";
		$sql.= " ORDER BY type_content, name_content;";

	} else if (DEF_BDD == "MYSQL" || DEF_BDD == "ORACLE") {

		$sql = " SELECT id_content, name_content, type_content, width_content, height_content, ";
		$sql.= " html_content, nodeid_content, id_site, obj_table_content, obj_id_content ";
		$sql.= " FROM cms_content ";
		$sql.= " WHERE id_content=$id ";
		$sql.= " AND valid_content=1 ";
		$sql.= " AND actif_content=1 ";
		$sql.= " ORDER BY type_content, name_content";
	}

//print("<br>$sql");

	$rs = $db->Execute($sql);

	if($rs) {
		if(!$rs->EOF) {
			$tmparray = array(
				'id' => $rs->fields[n('id_content')],
				'name' => $rs->fields[n('name_content')],
				'type' => $rs->fields[n('type_content')],
				'width' => $rs->fields[n('width_content')],
				'height' => $rs->fields[n('height_content')],
				'html' => $rs->fields[n('html_content')],
			    'node_id' => $rs->fields[n('nodeid_content')],
				'id_site' => $rs->fields[n('id_site')],
				'obj_table_content' => $rs->fields[n('obj_table_content')],
				'obj_id_content' => $rs->fields[n('obj_id_content')]
			);
			$return = $tmparray;
		} else {
			$return=false;
		}
	} else {
		echo "<br />=========================";
		echo "<br />ERROR composants.lib.php > getComposantById";
		echo "<br />Erreur de fonctionnement interne";
		echo "<br /><strong>$sql</strong>";
		echo "<br />=========================";
		error_log("Plantage lors de l'execution de la requete\n $sql");
		error_log($db->ErrorMsg());
		$return = false;
	}
	$rs->Close();
	return $return;
}

// sélection d'un composant de CMS_ARCHI_CONTENT pour un id et une version particulière
// en effet ici il y a plusieurs versions de id_content
function getComposantArchiById($id, $version) {
	global $db;
	$return = array();
	
	//if ($version==DEF_ID_STATUT_LIGNE){	
	//	return getComposantById($id);
	//}

	$sql = " SELECT * ";
	$sql.= " FROM cms_archi_content ";
	$sql.= " WHERE id_content_archi=$id AND version_archi = $version";

	if (DEF_BDD != "ORACLE") $sql.=";";

//print("<br>id=".$id);
	
	$rs = $db->Execute($sql);

	if($rs) {
		if(!$rs->EOF) {
			$tmparray = array(
				'id' => $rs->fields[n('id_archi')],
				'html' => $rs->fields[n('html_archi')],
			);
			$return = $tmparray;
		} else {
			$return=false;
		}
	} else {
		echo "<br />=========================";
		echo "<br />ERROR composants.lib.php > getComposantArchiById";
		echo "<br />Erreur de fonctionnement interne";
		echo "<br /><strong>$sql</strong>";
		echo "<br />=========================";
		error_log("Plantage lors de l'execution de la requete\n $sql");
		error_log($db->ErrorMsg());
		$return = false;
	}
	$rs->Close();
	return $return;
}

//-----------------------------------------------------------
// toutes les pages utilisant cette brique =>>>
// 1. toutes les pages valides utilisant cette brique +
// 2. toutes les pages valides de tous les gabarits utilisant cette brique
// 3. on dédoublonne ces résultats pour le cas où une page utilise une brique et son gabarit aussi
// (peu probable mais ne théorie oui)
//-----------------------------------------------------------

function getPageUsingComposant($idSite, $id) {
	global $db;
	$return = array();


	// toutes les pages ayant cette brique

	$sql = " SELECT DISTINCT cms_struct_page.id_page ";
	$sql.= " FROM cms_struct_page, cms_page ";
	$sql.= " WHERE id_content=$id ";
	$sql.= " AND cms_struct_page.id_page=cms_page.id_page ";
	$sql.= " AND id_site=$idSite ";
	$sql.= " AND valid_page=1 AND isgabarit_page=0";


	$rs = $db->Execute($sql);

	if($rs) {
		while(!$rs->EOF) { 
			array_push($return, $rs->fields[n('id_page')]);
			$rs->MoveNext();
		}
	} else {
		echo "<br />ERROR composants.lib.php > getPageUsingComposant <br>";
		echo "Erreur de fonctionnement interne";
		error_log("Plantage lors de l'execution de la requete\n $sql");
		error_log($db->ErrorMsg());
		$return = false;
	}

	// tous les gabarits ayant cette brique

	$sql = " SELECT DISTINCT cms_struct_page.id_page ";
	$sql.= " FROM cms_struct_page, cms_page ";
	$sql.= " WHERE id_content=$id ";
	$sql.= " AND cms_struct_page.id_page=cms_page.id_page ";
	$sql.= " AND id_site=$idSite ";
	$sql.= " AND valid_page=1 AND isgabarit_page=1";

	if (DEF_BDD == "MYSQL" || DEF_BDD == "POSTGRES") $sql.= "; ";
	if (DEF_BDD == "ORACLE") $sql.= " ";

//print("<br>$sql");
	
	$rs = $db->Execute($sql);

	if($rs) {
		while(!$rs->EOF) { 

			// toutes les pages valides faites avec ce gabarit

			$sql = " SELECT DISTINCT cms_page.id_page ";
			$sql.= " FROM cms_page ";
			$sql.= " WHERE gabarit_page=".$rs->fields[n('id_page')];
			$sql.= " AND valid_page=1 AND isgabarit_page=0";

			$rs = $db->Execute($sql);
		
			if($rs) {
				while(!$rs->EOF) { 
					array_push($return, $rs->fields[n('id_page')]);
					$rs->MoveNext();
				}
			} else {
				echo "<br />ERROR composants.lib.php > getPageUsingComposant <br>";
				echo "Erreur de fonctionnement interne";
				error_log("Plantage lors de l'execution de la requete\n $sql");
				error_log($db->ErrorMsg());
				$return = false;
			}

		}
	} else {
		echo "<br />ERROR composants.lib.php > getPageUsingComposant <br>";
		echo "Erreur de fonctionnement interne";
		error_log("Plantage lors de l'execution de la requete\n $sql");
		error_log($db->ErrorMsg());
		$return = false;
	}

	$rs->Close();
	$return = dedoublonne($return);	
	return $return;
}

?>