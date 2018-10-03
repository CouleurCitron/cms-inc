<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

/*
$Id: pages.lib.php,v 1.1 2013-09-30 09:28:31 raphael Exp $
$Author: raphael $

$Log: pages.lib.php,v $
Revision 1.1  2013-09-30 09:28:31  raphael
*** empty log message ***

Revision 1.34  2013-03-01 10:33:58  pierre
*** empty log message ***

Revision 1.33  2012-05-07 10:16:29  pierre
*** empty log message ***

Revision 1.32  2012-03-30 13:21:34  pierre
*** empty log message ***

Revision 1.31  2011-12-22 18:13:24  pierre
*** empty log message ***

Revision 1.30  2011-07-06 13:16:04  pierre
*** empty log message ***

Revision 1.29  2011-07-04 15:52:31  pierre
*** empty log message ***

Revision 1.28  2011-07-04 13:53:59  pierre
*** empty log message ***

Revision 1.27  2011-07-04 13:40:32  pierre
*** empty log message ***

Revision 1.26  2011-06-30 14:28:47  pierre
*** empty log message ***

Revision 1.25  2011-04-01 15:32:56  pierre
*** empty log message ***

Revision 1.24  2011-03-15 15:14:57  pierre
*** empty log message ***

Revision 1.23  2011-03-15 15:00:10  pierre
*** empty log message ***

Revision 1.22  2011-02-08 17:24:44  pierre
patch url hephaistos dans les sites de prod

Revision 1.21  2011-01-06 15:22:31  pierre
on n'�crit plus la div des hachures dans les pages

Revision 1.20  2010-11-24 14:12:10  pierre
syntaxe php5

Revision 1.19  2010-09-01 13:26:34  pierre
correction des node_id des contenus quand les pages sont d�plac�es

Revision 1.18  2010-08-26 15:40:01  pierre
cms_theme

Revision 1.17  2010-01-08 11:30:22  pierre
*** empty log message ***

Revision 1.16  2009-09-16 10:16:43  pierre
*** empty log message ***

Revision 1.15  2009-09-01 16:12:45  pierre
*** empty log message ***

Revision 1.14  2009-04-28 08:34:33  pierre
*** empty log message ***

Revision 1.13  2008-11-28 15:34:21  pierre
*** empty log message ***

Revision 1.12  2008-10-21 09:20:46  pierre
*** empty log message ***

Revision 1.10  2008-07-30 17:01:40  thao
*** empty log message ***

Revision 1.9  2008-07-30 16:39:18  thao
*** empty log message ***

Revision 1.8  2008-04-21 12:21:13  pierre
*** empty log message ***

Revision 1.6  2008/04/21 09:07:31  pierre
*** empty log message ***

Revision 1.5  2008/01/29 15:57:17  pierre
*** empty log message ***

Revision 1.4  2008/01/25 14:24:04  pierre
*** empty log message ***

Revision 1.3  2007/12/03 14:47:41  thao
*** empty log message ***

Revision 1.2  2007/11/29 16:48:50  pierre
*** empty log message ***

Revision 1.1  2007/08/08 13:07:18  thao
*** empty log message ***

Revision 1.5  2007/07/02 10:44:16  pierre
*** empty log message ***

Revision 1.4  2006/12/19 11:17:41  pierre
*** empty log message ***

Revision 1.3  2006/07/27 10:16:13  pierre
*** empty log message ***

Revision 1.2  2006/07/24 09:23:53  pierre
*** empty log message ***

Revision 1.1.1.1  2006/01/25 15:14:27  pierre
projet CCitron AWS 2006 Nouveau Website

Revision 1.18  2005/12/06 15:26:37  sylvie
*** empty log message ***

Revision 1.17  2005/12/02 15:09:33  sylvie
*** empty log message ***

Revision 1.16  2005/11/28 09:52:52  sylvie
*** empty log message ***

Revision 1.15  2005/11/08 14:04:02  sylvie
*** empty log message ***

Revision 1.14  2005/11/04 15:04:09  sylvie
*** empty log message ***

Revision 1.13  2005/11/04 13:55:09  sylvie
*** empty log message ***

Revision 1.12  2005/11/04 08:37:02  sylvie
*** empty log message ***

Revision 1.11  2005/11/02 10:44:51  sylvie
*** empty log message ***

Revision 1.10  2005/10/28 13:47:10  sylvie
*** empty log message ***

Revision 1.9  2005/10/27 14:42:17  sylvie
*** empty log message ***

Revision 1.7  2005/10/27 12:33:44  sylvie
*** empty log message ***

Revision 1.6  2005/10/27 10:04:44  sylvie
*** empty log message ***

Revision 1.5  2005/10/27 09:18:52  sylvie
*** empty log message ***

Revision 1.4  2005/10/27 09:02:04  sylvie
*** empty log message ***

Revision 1.1.1.1  2005/10/24 13:37:05  pierre
re import fusion espace v2 et ADW v2

Revision 1.2  2005/10/21 10:24:56  sylvie
*** empty log message ***

Revision 1.1.1.1  2005/10/20 13:10:54  pierre
Espace V2

Revision 1.6  2005/06/06 16:23:08  michael
Modif du z-index (car zIndex ça marche que sous IE - et on se demande pourquoi -)

Revision 1.5  2005/06/02 16:18:52  michael
correction message d'erreur

Revision 1.3  2005/04/28 09:43:12  melanie
ecriture des styles des briques dans le header de la page

Revision 1.2  2005/04/27 08:44:30  melanie
corrections en-tetes html

Revision 1.1.1.1  2005/04/18 13:53:29  pierre
again

Revision 1.1.1.1  2005/04/18 09:04:21  pierre
oremip new

Revision 1.3  2004/11/15 15:31:20  ddinside
maj total

Revision 1.2  2004/11/12 08:59:20  ddinside
gabarit interieur + moteur de recherche

Revision 1.1.1.1  2004/11/03 13:49:54  ddinside
lancement du projet - import de adequat

Revision 1.3  2004/09/29 15:46:51  ddinside
modifs

Revision 1.2  2004/06/18 14:10:21  ddinside
corrections diverses en vu de la demo prevention routiere

Revision 1.1.1.1  2004/04/01 09:20:29  ddinside
Cration du projet CMS Couleur Citron nom de code : tipunch

Revision 1.7  2004/02/12 15:56:16  ddinside
mise   jour plein de choses en fait, mais je sais plus quoi parce que a fait longtemps que je l'avais pas fait.
Mea Culpa...

Revision 1.6  2004/02/05 15:56:26  ddinside
ajout fonctionnalite de suppression de pages
ajout des styles dans spaw
debuggauge prob du nom de fichier limite   30 caracteres

Revision 1.5  2004/01/27 12:12:51  ddinside
application de dos2unix sur les scripts SQL
modification des scripts d'ajout pour correction bug lors d'une modif de page
ajout foncitonnalit de modification de page
ajout visu d'une page si cr e

Revision 1.4  2004/01/21 12:33:51  ddinside
integration de la DTD XHTML Transitionnelle
int gration des titresd, description et mots clefs des pages

Revision 1.3  2004/01/20 15:16:38  ddinside
mise  jour de plein de choses
ajout de gabarit vie des quartiers
eclatement gabarits par des includes pour contourner prob des flashs non finalis s

Revision 1.2  2004/01/12 09:40:38  ddinside
ajout config auto selon gabarit dans la creation de page
ajout flashs home
ajout calendrier
ajout doc class fileupload

Revision 1.1  2004/01/07 18:29:22  ddinside
jout derniers fonctionnalites

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


/*
function stripTitre($str)
function divOverflow(){
function saveDatePeremption($id_page, $datePeremption=null) {
function getDatePeremption($id_page) {
function getListPerim() {
function buildDivArrayPreview($divArray)
function generatePage($divArray, $oPage, $oIinfos_page, $sMode="LIGNE", $eIdTravail=-1) 
function storeInfosPage($idPage, $oIinfos_page)
function updatePage($divArray,$titre,$motsclefs,$description,$idPage) 
function savePage($divArray,$node_id,$name_page,$optionsPage="",$validPage="true",$generated="true",$datemep=null,$idPage=null,$titre="",$motsclefs="",$description="") 
function savePage2($divArray, $oPage, $oIinfos_page) 
function saveStruct($id_page,$divArray)
function generateFile($oPage) 
function getPageStructure($id) 
function getPageById($id) 
function movePage($id, $node_id) 
function getListPageToGenerate() 
function getFolderInfos($virtualPath)
function deletePage($id) 
function renamePage($id,$libelle) 

function getRepPage($idSite, $eNode, $sAbsolute_path_name)

*/

function stripTitre($str){
	if (preg_match('/<\?php/msi', $str)==1){
		return $str;
	}
	else{
		return strip_tags($str);
	}
}

function divOverflow(){
	if (DEF_SCROLLSPACECONTENT != NULL){
		if (DEF_SCROLLSPACECONTENT == "ON"){
			return "auto";
		}
		else{
			return "visible";
		}
	}
	else{
		return "auto";
	}
}

function saveDatePeremption($id_page, $datePeremption=null) {
	global $db;
	$sql = "BEGIN
	delete from cms_content_perempt where id_content=$id_page;\n";
	if($datePeremption != null) {
		$date = explode('/',$datePeremption);
		$date = $date[2].'-'.$date[1].'-'.$date[0].' 00:00:00.000000';
		$sql.= " INSERT INTO cms_content_perempt (id_content, dateperempt) values ($id_page,".$db->qstr($date).");\n";
	}
	$sql.="END;";
	$rs = $db->Execute($sql);
	if(!$rs) {
		error_log($db->ErrorMsg());
		error_log('Erreur lors de l\'execution de la requete '.$sql);
		return false;
	}
	$rs->Close();
	return true;
}

function getDatePeremption($id_page) {
	global $db;
	$sql = "select dateperempt from cms_content_perempt where id_content=$id_page";
	$rs = $db->Execute($sql);
	if(!$rs) {
                error_log($db->ErrorMsg());
                error_log('Erreur lors de l\'execution de la requete '.$sql);
                return false;
        } else {
		if(!$rs->EOF) {
			$date = explode(' ',$rs->fields[0]);
			$date = explode('-', $date[0]);
			$date = $date[2].'/'.$date[1].'/'.$date[0];
			$rs->Close();
			return $date;
		} else {
			$rs->Close();
			return '';
		}
	}
}

function getListPerim() {
	global $db;
	$result = array();
	$now = date('Y-m-d',strtotime("+1 month")).' 00:00:00.000000';
	$sql = "select id_content, dateperempt from cms_content_perempt where dateperempt < ".$db->qstr($now).' order by dateperempt';
	$rs = $db->Execute($sql);
	if($rs) {
		while(!$rs->EOF) {
			$date = explode(' ',$rs->fields[1]);
			$date = $date[0];
			$array = array();
			$array['id'] = $rs->fields[0];
			$array['date'] = $date;
			array_push($result, $array);
			$rs->MoveNext();
		}
	} else {
		echo "Erreur de fonctionnement interne";
		error_log("Plantage lors de l'execution de la requete");
		error_log($db->ErrorMsg());
		error_log("--------------------------");
		error_log($sql);
		error_log("--------------------------");
		$result = false;
	}
	$rs->Close();
	return $result;
}

function buildDivArrayPreview($divArray) 
{
	global $db;
	$return = $divArray;
	foreach($divArray as $k => $v) {
		if(is_array($v)) {

			$composant = getComposantById($v['id']);
			$return[$k]['content'] = $composant['html'];
		}
	}
	return $return;
}


//-------------------------------------------------------------------------------------
// génération d'une page

// on peut générer une page de 3 façons : 

// 	1. une page avec toutes les briques en ligne
//		== fichier créé
// 	2. une page avec une brique de travail et les autres briques au mieux (en ligne s'il y a)
//		== fichier créé dans /temp/ puis effacé
// 	3. une page avec toutes les briques au mieux (en ligne s'il y a)
//		== fichier créé dans /temp/ puis effacé

// le mode de génération est "sMode" qui vaut soit 'LIGNE' (par défaut) soit 'TRAVAIL'
// pour le cas de génération d'une page en mode travail, on utilise eIdTravail 
// eIdTravail est une brique à utiliser en mode travail
// si on n'a pas eIdTravail on génère avec toutes les briques au mieux 

// sponthus 08/06/2005
// passage d'objets et non plus de x paramètres
//-------------------------------------------------------------------------------------

function generatePage($divArray, $oPage, $oIinfos_page, $sMode="LIGNE", $eIdTravail=-1) 
{
	//error_log('generatePage(divArray, '.$oPage->get_id().', oIinfos_page, '.$sMode.', '.$eIdTravail.')');
	// taille du div_array pour cette page
	// ensemble des briques de cette page
	if ($divArray[0]['id'] != "") $eIdDivarray = 1;
	else $eIdDivarray = 0;

	// composants
	if ($eIdDivarray > 0) $divArray = buildDivArrayPreview($divArray);

	// dans le div_array on a les contenus des briques de CMS_CONTENT
	// les versions de travail sont dans CMS_CONTENT
	// les versions en ligne sont dans CMS_ARCHI

	// 1. sMode == LIGNE
	// 		on remplace le contenu HTML de chaque brique par le contenu EN LIGNE de chaque brique
	// 2. sMode == TRAVAIL et eIdTravail != -1
	// 		on remplace le contenu HTML de chaque brique par le contenu EN LIGNE de chaque brique si on le trouve
	//		sauf pour la brique eIdTravail que l'on prend bien dans CMS_CONTENT
	// 3. sMode == TRAVAIL et eIdTravail == -1
	// 		on remplace le contenu HTML de chaque brique par le contenu EN LIGNE de chaque brique si on le trouve

	foreach($divArray as $k => $v) {

		if(is_array($v)) {

			if ($v['id'] != "") {

				$oContent = new Cms_content();
				$oArchi = new Cms_archi_content();
				 
				$oContent->initValues($v['id']);
	 
				// si c'est une brique editable
				if ($oContent->getIsbriquedit_content()) {
	
					if ($sMode == "LIGNE") {
						$oArchi = getArchiWithIdContent($oContent->getId_content());
						$contenuHTML = $oArchi->getHtml_archi();
						// correction d'un BUG encore inexpliqu�
						if ($contenuHTML == ''){
							// on n'est pas dans la marde
							$contenuHTML = $oContent->getHtml_content();
							$oArchi->setHtml_archi($contenuHTML);
							dbSauve($oArchi);						
						}				
					}
					else if ($sMode == "TRAVAIL") { 
						// si on a une brique spécifiée à mettre dans sa version de travail
						if ($eIdTravail != -1) {
							// 1. si on est sur la brique spécifiée en travail -> version de travail pour cette brique
							// 2. sinon on essaie d'afficher la version de travail de la brique
							// car on construit ici une page de travail (à défaut la version en ligne)
	
							if ($oContent->getId_content() == $eIdTravail) {
								// version de travail de la brique demandée
								$contenuHTML = $oContent->getHtml_content();
							} else {
								if ($oContent->getId_content() != "" && $oContent->getId_content() != -1) {
									// version de travail des autres briques si elles existent
									$contenuHTML = $oContent->getHtml_content();
								} else {
									// à défaut d'une version de travail -> version en ligne
									$oArchi = getArchiWithIdContent($oContent->getId_content());
									if ($oArchi->getHtml_archi() != "") {
										$contenuHTML = $oArchi->getHtml_archi();
									} else {
										// pas de version de travail ni de version en ligne 
										// == normalement impossible
										// le cas est quand même prévu a voir si cela est nécessaire
										$contenuHTML = "";
									}
								}
							}
	
						}
						else { 
							// on essaie toujours d'afficher la version de travail
							// sinon la version en ligne	
							if ($oContent->getId_content() != "" && $oContent->getId_content() != -1) {
								$contenuHTML = $oContent->getHtml_content();
							} else {
								$oArchi = getArchiWithIdContent($oContent->getId_content());
								if ($oArchi->getHtml_archi() != "") {
									$contenuHTML = $oArchi->getHtml_archi();
								} else {
									// pas de version de travail ni de version en ligne 
									// == normalement impossible
									// le cas est quand même prévu a voir si cela est nécessaire
									$contenuHTML = "";
								}
							}
						} // fin :: if ($eIdTravail != -1) { 
					}// fin :: else if ($sMode == "TRAVAIL") {
	
					$divArray[$k]['content'] = stripslashes($contenuHTML);
					
					// patch URL=http://quentin.luzenac.hephaistos.interne
					$divArray[$k]['content'] = preg_replace('/URL=http:\/\/[^\/]+hephaistos\.interne/msi', 'URL=http://'.$_SERVER['HTTP_HOST'], $divArray[$k]['content']);
					
				} // fin if ($oContent->getIsbriquedit_content()) {
			} // fin if ($v['id'] != "") {
		} // fin if(is_array($v)) {
	}
	 
	// écriture des tamponspage, tampongabarits et des composants
	if ($oPage->getIsgabarit_page() == 1) {
		// création d'un gabarit
		$filecontent = getTamponContent_gabarit($divArray, $oIinfos_page, $oPage);

	} else {
		// création d'une page
		$filecontent = getTamponContent_page($divArray, $oIinfos_page, $oPage);	
	}
 
	@unlink($tmpfile); // c'est une purge => pas besoin de savoir si ça a foiré!
	
	// reecriture des div
	if (preg_match('/(<\/head>.*)(<style.*<\/style>)/msi', $filecontent)==1){
		$filecontent = preg_replace('/(<\/head>.*)(<style.*<\/style>)/msi', '$2$1', $filecontent);
	}
	if (preg_match('/(<\/head>.*)(<script.*window\.onload[^<]*<\/script>)/msi', $filecontent)==1){
		$filecontent = preg_replace('/(<\/head>.*)(<script.*window\.onload[^<]*<\/script>)/msi', '$2$1', $filecontent);
	}
	
	return $filecontent;
}



function storeInfosPage($idPage, $oIinfos_page)
{
	global $db;
	$return=false;

	$sql = " DELETE FROM cms_infos_pages WHERE page_id=$idPage";
	if (DEF_BDD != "ORACLE") $sql.=";";

	$rs = $db->Execute($sql);
	if($rs) {
		$return = true;
	} else {
		echo "<br />pages.lib.php > storeInfosPage > DELETE";
		echo "<br />Erreur de fonctionnement interne";
		error_log("pages.lib.php > storeInfosPage > DELETE");
		error_log("Plantage lors de l'appel  la proc dure pour mise  jour de l'id $id -- si null, c'est un ajout");
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------");
		error_log("$sql");
		error_log("--------------------------------------------");
		$return=false;
	}

	// clé suivante
	$eId = getNextVal("cms_infos_pages", "id");

	$sql = " INSERT INTO cms_infos_pages (id, page_id, page_titre, page_motsclefs, page_description, page_thumb) ";
	$sql.= " VALUES ($eId, $idPage, ".$db->qstr($oIinfos_page->getPage_titre()).",  ";
	$sql.= " ".$db->qstr($oIinfos_page->getPage_motsclefs()).", ";
	$sql.= " ".$db->qstr($oIinfos_page->getPage_description()).", ";
	$sql.= " ".$db->qstr($oIinfos_page->getPage_thumb()).")";

	if (DEF_BDD != "ORACLE") $sql.=";";

//print("<br>$sql");
	$rs = $db->Execute($sql);

	if($rs) {
		$return = true;
	} else {
		echo "<br />pages.lib.php > storeInfosPage > INSERT";
		echo "<br />$sql";
		echo "<br />Erreur de fonctionnement interne";
		error_log("pages.lib.php > storeInfosPage > INSERT");
		error_log("Plantage lors de l'appel  la proc dure pour mise  jour de l'id $id -- si null, c'est un ajout");
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------");
		error_log("$sql");
		error_log("--------------------------------------------");
		
		$return=false;
	}
	
	$rs->Close();
	return $return;
}


function updatePage($divArray,$titre,$motsclefs,$description,$idPage) 
{
	global $db;
	$return = false;
	$datemep = date("Y-m-d H:m:s");
	$content = generatePage($divArray,$titre,$motsclefs,$description);

	$sql = " UPDATE cms_page";
	$sql.= " SET html_page = to_char(".$db->qstr($content)."),";

	if (DEF_BDD == "ORACLE" || DEF_BDD == "POSTGRES") {
	
		$datemep = date("Y/m/d/H:m:s");
		$datemep = explode('/', $datemep);
		$datemep = "to_date('".$datemep[2]."/".$datemep[1]."/".$datemep[0]."', 'dd/mm/yyyy')";
	
	} else if (DEF_BDD == "MYSQL") {
	
		$datemep = "str_to_date('".getDateNow()."', '%d/%m/%Y')";
	}

	$sql.= " dateupd_page=$datemep,";
	$sql.= " isgenerated_page=to_number(1)";
	$sql.= " WHERE id_page=$idPage";

	if (DEF_BDD != "ORACLE") $sql.= "; ";

	$rs = $db->Execute($sql);

	if($rs && strlen($content) > 0 && is_array(generateFile($idPage, $titre, $motsclefs, $description))) {
		$return = true;
	} else {
		echo "<br />pages.lib.php > updatePage > updatePage";
		echo "<br />$sql";
		echo "<br />Erreur de fonctionnement interne";
		error_log("Plantage lors de l'execution de la requete");
		error_log($db->ErrorMsg());
		error_log("--------------------------");
		error_log($sql);
		error_log("--------------------------");
	}

	return $return;
}

function savePage($divArray,$node_id,$name_page,$optionsPage="",$validPage="true",$generated="true",$datemep=null,$idPage=null,$titre="",$motsclefs="",$description="") 
{
	if($datemep==null)
		$datemep = date("Y-m-d H:m:s");
	global $db;
	if($generated) 
		$generated=1;
	else
		$generated=0;
	
	$return=null;
	if ($idPage==null)
		$idPage='NULL';
	$content = generatePage($divArray,$titre,$motsclefs,$description);
	$name_page = trim($name_page);

	if (DEF_BDD != "ORACLE") 
	{
		$sql="select storePage( CAST(".$db->qstr($name_page)." as VARCHAR),
					CAST(".$db->qstr($divArray['gabarit'])." as VARCHAR ),
					CAST(".$db->qstr($generated)." as BOOLEAN),
					CAST(".$db->qstr($validPage)." as BOOLEAN),
					CAST($node_id as INT),
					CAST(".$db->qstr($optionsPage)." as VARCHAR),
					CAST(".$db->qstr($content)." as TEXT),
					CAST(CAST(".$db->qstr($datemep)." as DATE) as TIMESTAMP),
					CAST($idPage as INT));";
		$rs = $db->Execute($sql);
		if($rs) {
			$return = $rs->fields[n('storepage')];
		} else {
			echo "<br />ERREUR backoffice/cms/pages.lib.php > savePage";
			echo "<br />Erreur de fonctionnement interne";
			error_log("Plantage lors de l'appel   la procdure storePage pour mise   jour de l'id $id -- si null, c'est un ajout");
			error_log($db->ErrorMsg());
			error_log("--------------------------------------------");
			//error_log("$sql");
			error_log("--------------------------------------------");
			$return=false;
		}
	} else {

		// objet cms_page
		$oPage = new Cms_page();
		
		// alimentation objet
		$oPage->setId_page($idPage);

		$oPage->setName_page("to_char(".$db->qstr($name_page).")");
		$oPage->setGabarit_page("to_char(".$db->qstr($divArray['gabarit']).")");
		$oPage->setDatemep_page($db->qstr($datemep));
		$oPage->setIsgenerated_page("to_number(".$db->qstr($generated).")");
		$oPage->setValid_page("to_number(".$db->qstr($validPage).")");
		$oPage->setNodeid_page("to_number(".$node_id.")");
		$oPage->setOptions_page("to_char(".$db->qstr($optionsPage).")");
		$oPage->setHtml_page($db->qstr($content));
	
		$return = proc_storepage($oPage);
	}
	$rs->Close();
	return $return;
}

// sponthus 07/06/2005
// doublon avec la pécédente fonction
// a terme savePage2 doit remplacer savePage
function savePage2($divArray, $oPage, $oIinfos_page) 
{
	if($datemep==null)
		$datemep = date("Y-m-d H:m:s");
	global $db;
	if($generated) 
		$generated=1;
	else
		$generated=0;


	$return=null;
	if ($idPage==null)
		$idPage='NULL';

	$content = generatePage($divArray, $oPage, $oIinfos_page);
	
	

//	$oPage->setDatemep_page($db->qstr($datemep));
//	$oPage->setIsgenerated_page("CAST(".$db->qstr($generated)." as BOOLEAN)");
	if (DEF_BDD == "MYSQL") 
		$oPage->setHtml_page($content);
	else if (DEF_BDD == "POSTGRES") 
		$oPage->setHtml_page("CAST(".$content." as TEXT)");
	else if (DEF_BDD == "ORACLE") 
		$oPage->setHtml_page($content);
		
	$return = proc_storepage($oPage);
	
	// en cas de création, id_page n'est pas encore renseigné, renvoyé par return de storepage
	$oPage->setId_page($return);


	return $return;
}


function saveStruct($id_page, $divArray) 
{
	global $db;
	$globaltest = true;
	$divArray=buildDivArrayPreview($divArray);

	$sql = " DELETE FROM cms_struct_page WHERE id_page = $id_page";

	if (DEF_BDD != "ORACLE") $sql.=";";

	$rs = $db->Execute($sql);
	//pre_dump($divArray);
	foreach($divArray as $k => $v) {
		if (is_int($k)) {

			$idContent = $v['id'];
			$widthContent = preg_replace('/px$/','',$v['width']);
			$heightContent = preg_replace('/px$/','',$v['height']);
			$topContent = preg_replace('/px$/','',$v['top']);
			$leftContent = preg_replace('/px$/','',$v['left']);
			$opacity = $v['-moz-opacity']*100;
			$zindex = $v['zIndex'];
			$zonedit = $v['zonedit'];
			if ($zonedit == "") $zonedit=-1;
			
			// objet cms_struct_page
			$oStruct_page = new Cms_struct_page();
				
			// alimentation objet
			$oStruct_page->setId_page($id_page);
			$oStruct_page->setId_content($idContent);
			$oStruct_page->setWidth_content($widthContent);
			$oStruct_page->setHeight_content($heightContent);
			$oStruct_page->setTop_content($topContent);
			$oStruct_page->setLeft_content($leftContent);
			$oStruct_page->setOpacity_content($opacity);
			$oStruct_page->setZindex_content($zindex);
			$oStruct_page->setId_zonedit_content($zonedit);

			$return = proc_storestruct($oStruct_page);
		}
	}
	$rs->Close();
	return $globaltest;
}


// sponthus 08/06/2005
// passage d'un objet oPage et non plus de x paramètres

// génération d'une page : du fichier physique

function generateFile($oPage)
{
	global $db;
	$return=false;

	$sql = " SELECT id_page, name_page, nodeid_page, node_libelle, node_absolute_path_name, html_page, cms_page.id_site";
	$sql.= " FROM cms_page, cms_arbo_pages";
	$sql.= " WHERE nodeid_page=node_id";
	$sql.= " AND id_page=".$oPage->getId_page();

	if (DEF_BDD != "ORACLE") $sql.=";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	
	if($rs && !$rs->EOF) {

		$array = array();

		$array['filename'] = $rs->fields[n('name_page')].'.php';
		$array['foldername'] = $rs->fields[n('node_libelle')];
		$array['html'] = $rs->fields[n('html_page')];

		// site de la page
		$oSite = new Cms_site($rs->fields[n('id_site')]);

		//-------------------------------
		// chemin du fichier à générer
		//-------------------------------
		if ($oPage->getIsgabarit_page()) {

			// chemin du gabarit
			//-----------------------

			$sRep = getRepGabarit($oSite->get_id());
			dirExists($sRep); // check existence du dir
			$finalfile = $sRep.$array['filename'];
		}
		else {
			// chemin de la page
			//-----------------------

			if ($rs->fields[n('node_absolute_path_name')] == "/") {
				$sRepMinisite="/".$oSite->get_rep();
			}

			$array['path'] = $rs->fields[n('node_absolute_path_name')];

			$sRepFile = "/".DEF_PAGE_ROOT.$sRepMinisite;
			dirExists($sRepFile); // check existence du dir

			$finalfile = $_SERVER['DOCUMENT_ROOT'].$sRepFile.$array['path'].$array['filename'];
			controlNewPagePath($finalfile);
		}


		if(is_file($finalfile)) {
			$filesaved = $finalfile.'.'.date('d-m-Y_H-i-s').'.bak';;

			copy($finalfile, $filesaved);
		}
		
		

		$finalfilehandle = fopen($finalfile,'w');
		if ($finalfilehandle){			
			foreach(explode("\n", $array['html']) as $k=>$v) {
				fputs($finalfilehandle,$v."\n");
			}
			$return = $array;
		}
		else{
			error_log('pages.lib.php - ecriture impossible dans '.$finalfile);
			die('Ecriture impossible dans '.str_replace($_SERVER['DOCUMENT_ROOT'], '', $finalfile).'.<br />Veuillez contacter l\'administrateur.');
			
		}
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />backoffice/cms/pages.lib.php > generateFile";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
	}
	$rs->Close();
	return $return;
	
}



function getPageStructure($id) 
{
	global $db;
	$return=false;

	$sql = " SELECT id_content, width_content, height_content, top_content,";
	$sql.= " left_content, opacity_content , zindex_content, id_zonedit_content";
	$sql.= " FROM cms_struct_page";
	$sql.= " WHERE id_page=$id";

	if (DEF_BDD != "ORACLE") $sql.=";";

	$divArray = array();

	$rs = $db->Execute($sql);

	if($rs){
		while(!$rs->EOF) {
			$array = array();
			$array['width']=$rs->fields[n('width_content')];
			$array['height']=$rs->fields[n('height_content')];
			$array['top']=$rs->fields[n('top_content')];
			$array['left']=$rs->fields[n('left_content')];
			$array['filter']='alpha(opacity='.$rs->fields[n('opacity_content')].')';
			$array['-moz-opacity']=$rs->fields[n('opacity_content')]/100;
			$array['zIndex']=$rs->fields[n('zindex_content')];
			$array['id'] = $rs->fields[n('id_content')];

			// si c'est un contenu de zone Ã©ditable on rajoute l'id de cette zone
			$array['zonedit'] = $rs->fields[n('id_zonedit_content')];
			
			$composant = getComposantbyId($rs->fields[n('id_content')]);
			$array['content']=$composant;
			$divArray[$rs->fields[n('id_content')]] = $array;
			$rs->MoveNext();
		}

		$sql = " SELECT gabarit_page, page_titre, page_motsclefs, page_description, page_thumb";
		$sql.= " FROM cms_page, cms_infos_pages";
		$sql.= " WHERE cms_page.id_page=$id";
		$sql.= " AND cms_page.id_page = cms_infos_pages.page_id";

		if (DEF_BDD != "ORACLE") $sql.=";";		

//print("<br>$sql");
		
		$rs2 = $db->Execute($sql);
		if($rs2 and !$rs2->EOF) {
			$divArray['gabarit']=$rs2->fields[n('gabarit_page')];
			$divArray['titre']=$rs2->fields[n('page_titre')];
			$divArray['motscles']=$rs2->fields[n('page_motsclefs')];
			$divArray['description']=$rs2->fields[n('page_description')];
			$divArray['thumb']=$rs2->fields[n('page_thumb')];
			$return = $divArray;
		} else if($rs2->EOF) {
// si on est en création de page
// on a créé oPage et pas encore oInfosPage
// et on n'a pas besoin d'un div_array avec les infos pages
// alors -> ne pas planter (même si div_array incomplet)
			$divArray['gabarit']="";
			$divArray['titre']="";
			$divArray['motscles']="";
			$divArray['description']="";
			$divArray['thumb']="";
			$return = $divArray;
		} else {
			error_log("Recuperation du gabarit impossible pour la page id = $id");
			error_log("Plantage lors de l'execution de la requete");
			error_log($db->ErrorMsg());
			error_log("--------------------------------------------");
			error_log("$sql");
			error_log("--------------------------------------------");
			
		}
	} else {
		echo "<br />ERREUR backoffice/cms/pages.lib.php > getPageStructure";
		echo "<br />Erreur de fonctionnement interne";
		error_log("Plantage lors de l'execution de la requete");
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------");
		error_log("$sql");
		error_log("--------------------------------------------");
	}
	$rs->Close();
	return $return;
}

function getPageById($id) 
{
	global $db;
	$return=false;

	$sql = " SELECT * FROM cms_page, cms_arbo_pages, cms_infos_pages";
	$sql.= " WHERE cms_page.id_page=$id";
	$sql.= " AND cms_page.id_page = cms_infos_pages.page_id";
	$sql.= " AND nodeid_page=node_id";
	$sql.= " ORDER BY isgabarit_page";
	if (DEF_BDD != "ORACLE") $sql.=";";

//print("<br>$sql<br><br>");

	$rs = $db->Execute($sql);
	if($rs && !$rs->EOF) {
		$array = array();
		$array['name']=$rs->fields[n('name_page')];
		$array['gabarit']=$rs->fields[n('gabarit_page')];
		$array['libelle']=$rs->fields[n('node_libelle')];
		$array['node_id']=$rs->fields[n('nodeid_page')];
		$array['titre']=$rs->fields[n('page_titre')];
		$array['motsclefs']=$rs->fields[n('page_motsclefs')];
		$array['description']=$rs->fields[n('page_description')];
		$array['isgabarit_page']=$rs->fields[n('isgabarit_page')];
		$array['iscustom']=$rs->fields[n('iscustom')];
		$return = $array;
	} else {
		$sqlInfos = " SELECT * FROM cms_infos_pages";
		$sqlInfos.= " WHERE cms_infos_pages.page_id=$id";
		if (DEF_BDD != "ORACLE") $sqlInfos.=";";
		$rs = $db->Execute($sqlInfos);
		if($rs && !$rs->EOF) {
			echo "<br />pages.lib.php > getPageById";
			echo "<br />SQL=>$sql";
			echo "<br />Erreur de fonctionnement interne";
			error_log("Plantage lors de l'execution de la requete");
			error_log($db->ErrorMsg());
			error_log("--------------------------------------------");
			error_log("$sql");
			error_log("--------------------------------------------");
		}
		else {
			echo '<br />cms_infos_pages manquant pour page id='.$id;
			$oInfo = new Cms_infos_page();
			
			$sqlGab = " SELECT * FROM cms_page";
			$sqlGab.= " WHERE cms_page.id_page=$id";
			if (DEF_BDD != "ORACLE") $sqlGab.=";";
			$rs = $db->Execute($sqlGab);
			if($rs && !$rs->EOF) {
				$array = array();
				$array['name']=$rs->fields[n('name_page')];
				$array['gabarit']=$rs->fields[n('gabarit_page')];
				$array['libelle']=$rs->fields[n('node_libelle')];
				$array['node_id']=$rs->fields[n('nodeid_page')];
				
				$array['isgabarit_page']=$rs->fields[n('isgabarit_page')];
				$array['iscustom']=$rs->fields[n('iscustom')];
				$aGabInfo = dbGetObjectsFromFieldValue3('Cms_infos_page', array('getPage_id'), array('equals'), array($array['gabarit']),NULL,NULL);
				if ($aGabInfo){
					$oGabInfo = $aGabInfo[0];			
		
					$oInfo->setPage_id($id);
					$oInfo->setPage_titre($oGabInfo->getPage_titre());
					$oInfo->setPage_motsclefs($oGabInfo->getPage_motsclefs());
					$oInfo->setPage_description($oGabInfo->getPage_description());
					$oInfo->setPage_thumb($oGabInfo->getPage_thumb());
					$idInfo = dbSauve($oInfo);
					$array['titre']=$rs->fields[$oGabInfo->getPage_titre()];
					$array['motsclefs']=$rs->fields[$oGabInfo->getPage_motsclefs()];
					$array['description']=$rs->fields[$oGabInfo->getPage_description()];
					$return = $array;
				}
			}
			else {
				echo '<br />pas de cms_page manquant pour page id='.$id;
			}			
		}
	}
	$rs->Close();
	return $return;
}

function movePage($id, $node_id) 
{
	global $db;
	$return=false;
	$filename='';
	$old_node_id='';
	$olddest = '';
	$newdest = '';

	$sql = " SELECT nodeid_page, name_page, node_absolute_path_name from cms_page, cms_arbo_pages";
	$sql.= " WHERE nodeid_page=node_id";
	$sql.= " AND id_page=$id";

	if (DEF_BDD != "ORACLE") $sql.=";";

	$rsTemp = $db->Execute($sql);

	if($rsTemp && !$rsTemp->EOF) {


		// si la page à générer est à générer dans un mini site
		// et que l'on génère à la racine 
		// -> ajouter le répertoire du mini site
		$oPage = new Cms_page($id);
		if ($oPage->getNodeid_page() == 0) {
			$oSite = new Cms_site($oPage->getId_site());

			$sOldRepMinisite="/".$oSite->get_rep();
		} else {
			$sOldRepMinisite="";
		}

		$old_node_id = $rsTemp->fields[n('nodeid_page')];
		$olddest = '/content'.$sOldRepMinisite.$rsTemp->fields[n('node_absolute_path_name')];
		$filename = $rsTemp->fields[n('name_page')].'.php';
	}


	// si la page à générer est à générer dans un mini site
	// et que l'on génère à la racine 
	// -> ajouter le répertoire du mini site
	if ($node_id == 0) {
		$oSite = new Cms_site($oPage->getId_site());
		$sNewRepMinisite="/".$oSite->get_rep();
	} else {
		$sNewRepMinisite="";
	}

	$sql = " SELECT node_absolute_path_name FROM cms_arbo_pages WHERE node_id=$node_id";

	if (DEF_BDD != "ORACLE") $sql.=";";
	
	$rsTemp = $db->Execute($sql);
	if($rsTemp && !$rsTemp->EOF) {
		$newdest = '/content'.$sNewRepMinisite.$rsTemp->fields[n('node_absolute_path_name')];
	}
	if( (strlen($newdest)>0) && (strlen($olddest)>0) && (strlen($filename)>0)) {
		$source = $_SERVER['DOCUMENT_ROOT'].$olddest.$filename;
		$destination = $_SERVER['DOCUMENT_ROOT'].$newdest.$filename;
		if( copy($source,$destination) && unlink($source) ) {
			$source = $olddest.$filename;
			$destination = $newdest.$filename;
			$return = array($source,$destination);
		}
	}
	if ($return==false){
		error_log("deplacement du fichier interrompu par une erreur de traitement");
	} else {
		$sql = " UPDATE cms_page SET nodeid_page=$node_id WHERE id_page=$id";

		if (DEF_BDD != "ORACLE") $sql.=";";

		$rs = $db->Execute($sql);
		
		// contenus 		
		$aContent = getContentFromPage($oPage->get_id_page(), 0);
		
		if($aContent){
			for ($a=0; $a<sizeof($aContent); $a++){
				$oContent = $aContent[$a];				
				if (intval($oContent->get_isbriquedit_content())==1){			
					$oContent->updateNoeud($node_id);
				}
			}
		}
		
	}
	$rs->Close();
	return $return;
}

function getListPageToGenerate() 
{
	global $db;
	$date = date('Y-m-d');
	//$date = date('Y-m-d',strtotime('2004-01-29'));
	$result = array();	

	if (DEF_BDD == "POSTGRES") {

		$sql = " SELECT isgenerated_page, datemep_page, valid_page, id_page, name_page 
				FROM cms_page
				WHERE isgenerated_page='f'
				AND valid_page='t'
				AND datedlt_page IS NULL
				AND datemep_page=CAST(CAST('$date' as DATE) as TIMESTAMP)";

	} else if (DEF_BDD == "MYSQL" || DEF_BDD == "ORACLE") {

		$sql = " SELECT isgenerated_page, datemep_page, valid_page, id_page, name_page 
				FROM cms_page
				WHERE isgenerated_page=0
				AND valid_page=1
				AND datedlt_page IS NULL
				AND datemep_page=CAST(CAST('$date' as DATE) as TIMESTAMP)";
	}

	if (DEF_BDD != "ORACLE") $sql.=";";

	$rs = $db->Execute($sql);

	if($rs) {
		while (!$rs->EOF) {
			array_push($result,$rs->fields[n('id_page')]);
			$rs->MoveNext();
		}
	} else {
		echo "<br />ERROR pages.lib.php > getListPageToGenerate <br>";
		echo "Erreur de fonctionnement interne";
		error_log("Plantage lors de l'execution de la requete");
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------");
		error_log("$sql");
		error_log("--------------------------------------------");
		$result = false;
	}
	$rs->Close();
	return $result;
}

function getFolderInfos($virtualPath)
{
	global $db;
	$avirtualPath = explode(',', $virtualPath);
	$node_id = array_pop($avirtualPath);
	$result = null; 

	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name ";
	$sql.= " FROM cms_arbo_pages WHERE node_id=$node_id";

	if (DEF_BDD != "ORACLE") $sql.=";";

	$rs = $db->Execute($sql);

	if($rs!=false && !$rs->EOF) {
			$result = array(
					'id' => $rs->fields[n('node_id')],
					'libelle' => $rs->fields[n('node_libelle')],
					'parent' => $rs->fields[n('node_parent_id')],
					'path' => $rs->fields[n('node_absolute_path_name')]
			);      
	}
	$rs->Close();
	return $result;
}

function deletePage($id) 
{
	global $db;
	$result = true;
	$date = date('Y-m-d');
	$page = getPageById($id);

	if (DEF_BDD == "POSTGRES") {

		$sql = " UPDATE cms_page";
		$sql.= " SET valid_page='f',";
		$sql.= " datedlt_page = CAST(CAST('$date' as DATE) as TIMESTAMP)";
		$sql.= " where id_page = $id";

	} else if (DEF_BDD == "MYSQL" || DEF_BDD == "POSTGRES") {

		$sql = " UPDATE cms_page";
		$sql.= " SET valid_page=0,";

		$aDate = explode("-", $date);
		$sql.= " datedlt_page = ".to_dbdate($aDate[2]."/".$aDate[1]."/".$aDate[0]);

		$sql.= " where id_page = $id";
	}

	if (DEF_BDD != "ORACLE") $sql.=";";

	$rs = $db->Execute($sql);

	if($rs) {
		$path_to_file = $_SERVER['DOCUMENT_ROOT'].'/content';
		$folder = getFolderInfos($page['node_id']);
		$path_to_file .= $folder['path'].$page['name'].'.php';
		if(is_file($path_to_file)) {
			$filesaved = $path_to_file.'.'.date('d-m-Y_H:i:s');
			copy($path_to_file,$filesaved);
			unlink($path_to_file) or die("Erreur lors de la suppression du fichier $path_to_file");
		}
	} else {
		echo "<br />pages.lib.php > deletePage";
		echo "<br />$sql";
		echo "<br />Erreur de fonctionnement interne";
		error_log("Plantage lors de l'execution de la requete");
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------");
		error_log("$sql");
		error_log("--------------------------------------------");
		$result = false;
	}
	$rs->Close();
	return $result;
}

function renamePage($id, $libelle) 
{
	global $db;

	// répertoire gabarit
	$repGab = getRepGabarit($_SESSION['idSite_travail']);

	$return = array();
	
	$page = getPageById($id);
	$folder = getFolderInfos($page['node_id']);

	// objet page
	$oPage = new Cms_page($id);

	// cas spécial du gabarit placé dans le répertoire DEF_GABARIT_ROOT
	// attention un gabarit est stocué en base "name" et créé sous le nom "gb_"name
	// a_voir sponthus
	if ($oPage->getIsgabarit_page()) {

		$path_to_file .= $repGab.$page['name'].'.php';
		$path_to_newfile .= $repGab.$libelle.'.php';

		// renommer tous les noms de gabarit des pages utilisant ce gabarit
		// colonne "gabarit_page" dans cms_page
		$sOldFile = $page['name'].'.php';
		$sNewFile = $libelle.'.php';
		
		updateRenommageGabarit($sOldFile, $sNewFile);

	} else {

		// si la page à générer est à générer dans un mini site
		// et que l'on génère à la racine 
		// -> ajouter le répertoire du mini site
		if ($oPage->getNodeid_page() == 0) {
			$oSite = new Cms_site($oPage->getId_site());
			$sRepMinisite="/".$oSite->get_rep();
		} else {
			$sRepMinisite="";
		}

		$path_to_file = $_SERVER['DOCUMENT_ROOT'].'/content'.$sRepMinisite;
		$path_to_newfile = $_SERVER['DOCUMENT_ROOT'].'/content'.$sRepMinisite;

		$path_to_file .= $folder['path'].$page['name'].'.php';
		$path_to_newfile .= $folder['path'].$libelle.'.php';
	}	

	rename($path_to_file, $path_to_newfile);

	$sql = " UPDATE cms_page";
	$sql.= " SET name_page='$libelle'";
	$sql.= " WHERE id_page = $id";

	if (DEF_BDD != "ORACLE") $sql.=";";

	$rs = $db->Execute($sql);
	if($rs) {
			$result = array($path_to_file,$path_to_newfile);
	} else {
		echo "<br />pages.lib.php > renamePage";
		echo "<br />Erreur de fonctionnement interne";
		error_log("Plantage lors de l'execution de la requete");
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------");
		error_log("$sql");
		error_log("--------------------------------------------");
		$result = false;
	}
	$rs->Close();
	return $result;
}


// répertoire d'une page

function getRepPage($idSite, $eNode, $sAbsolute_path_name){
	// ajouter toujours le répertoire du mini site (même si on a un seul site)
	$result = "";
	
	// recherche si le site est un mini site
	$oSite = new Cms_site($idSite);
	$sRep = $oSite->get_rep();

	if ($eNode == 0) {
		$result ="/".$sRep;
		$result = $result.$sAbsolute_path_name;
	} else {
		$result = $sAbsolute_path_name;
	}

	return $result;
}

function controlNewPagePath($finalfilepathacontroler){
	$finalfilepathacontroler = str_replace($_SERVER['DOCUMENT_ROOT'], "", $finalfilepathacontroler);
	$aRepList = explode("/", $finalfilepathacontroler);
	$repCurrentlyControled = $_SERVER['DOCUMENT_ROOT'];
	for($irep=0;$irep<(count($aRepList)-1);$irep++){
		$repCurrentlyControled .= "/".$aRepList[$irep];
		if (!is_dir($repCurrentlyControled)){
			mkdir($repCurrentlyControled);
			//echo "dir ".$aRepList[$irep]." has been created<br />\n";
		}
		else{
			//echo "dir ".$repCurrentlyControled." is ok<br />\n";
		}
	}
}
?>