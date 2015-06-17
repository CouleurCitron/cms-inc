<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* 

$Author: raphael $

$Revision: 1.49 $
$Log: arbopage.lib.php,v $
Revision 1.49  2014-09-08 10:29:43  raphael
Modification des traductions

Revision 1.48  2013-04-15 12:05:16  thao
*** empty log message ***

Revision 1.47  2013-03-01 10:33:58  pierre
*** empty log message ***

Revision 1.46  2012-07-31 14:24:45  pierre
*** empty log message ***

Revision 1.45  2012-05-22 15:52:13  pierre
*** empty log message ***

Revision 1.44  2012-05-15 09:08:43  pierre
*** empty log message ***

Revision 1.43  2012-05-07 08:24:04  pierre
*** empty log message ***

Revision 1.42  2012-04-05 13:01:24  thao
modif picto arbo pour minisite

Revision 1.41  2012-04-03 11:20:55  thao
*** empty log message ***

Revision 1.40  2012-02-02 13:58:38  pierre
*** empty log message ***

Revision 1.39  2012-01-31 14:09:23  pierre
*** empty log message ***

Revision 1.38  2011-12-23 10:48:53  pierre
*** empty log message ***

Revision 1.37  2011-12-23 10:42:17  pierre
*** empty log message ***

Revision 1.36  2011-12-22 18:13:23  pierre
*** empty log message ***

Revision 1.35  2011-12-06 16:19:48  pierre
*** empty log message ***

Revision 1.34  2011-12-06 14:10:51  pierre
*** empty log message ***

Revision 1.33  2011-08-17 10:53:09  pierre
*** empty log message ***

Revision 1.32  2011-07-04 13:42:24  pierre
*** empty log message ***

Revision 1.31  2011-06-30 14:06:07  pierre
*** empty log message ***

Revision 1.30  2011-03-08 10:48:50  pierre
*** empty log message ***

Revision 1.29  2010-11-15 09:45:15  pierre
addNode : support des tags html dans les noms de dossiers

Revision 1.28  2010-09-22 15:07:29  pierre
*** empty log message ***

Revision 1.27  2010-01-08 11:30:22  pierre
*** empty log message ***

Revision 1.26  2009-07-24 14:46:23  pierre
*** empty log message ***

Revision 1.25  2009-07-24 14:20:04  pierre
*** empty log message ***

Revision 1.24  2009-07-07 08:12:35  pierre
*** empty log message ***

Revision 1.23  2009-06-22 07:41:03  pierre
*** empty log message ***

Revision 1.22  2009-03-11 08:44:14  pierre
*** empty log message ***

Revision 1.21  2009-02-02 10:29:15  thao
*** empty log message ***

Revision 1.20  2009-02-02 10:24:13  thao
*** empty log message ***

Revision 1.19  2009-01-23 13:52:22  pierre
*** empty log message ***

Revision 1.18  2008-12-18 14:25:06  pierre
*** empty log message ***

Revision 1.17  2008-12-17 10:54:01  pierre
*** empty log message ***

Revision 1.16  2008-12-09 11:13:23  pierre
*** empty log message ***

Revision 1.15  2008-10-21 09:20:46  pierre
*** empty log message ***

Revision 1.13  2008-08-21 13:31:38  pierre
*** empty log message ***

Revision 1.12  2008/07/22 10:09:20  pierre
*** empty log message ***

Revision 1.11  2008/06/26 15:09:11  thao
*** empty log message ***

Revision 1.10  2008/04/21 09:30:21  pierre
*** empty log message ***

Revision 1.9  2007/11/29 16:48:50  pierre
*** empty log message ***

Revision 1.8  2007/11/20 08:41:24  thao
*** empty log message ***

Revision 1.7  2007/09/06 09:34:46  pierre
*** empty log message ***

Revision 1.6  2007/08/28 15:53:32  pierre
*** empty log message ***

Revision 1.5  2007/08/27 10:13:40  pierre
*** empty log message ***

Revision 1.4  2007/08/08 14:14:23  thao
*** empty log message ***

Revision 1.3  2007/08/08 13:53:33  thao
*** empty log message ***

Revision 1.2  2007/08/08 13:23:18  thao
*** empty log message ***

Revision 1.1  2007/08/08 13:07:18  thao
*** empty log message ***

Revision 1.8  2007/06/27 15:30:04  pierre
*** empty log message ***

Revision 1.7  2007/06/27 15:04:28  pierre
*** empty log message ***

Revision 1.6  2007/06/15 09:09:00  pierre
*** empty log message ***

Revision 1.8  2007/06/15 09:08:35  pierre
*** empty log message ***

Revision 1.7  2007/06/05 14:29:40  thao
*** empty log message ***

Revision 1.6  2007/06/01 06:12:34  thao
*** empty log message ***

Revision 1.3  2007/05/21 16:52:35  pierre
*** empty log message ***

Revision 1.3  2007/04/03 07:42:32  remy
*** empty log message ***

Revision 1.2  2007/02/20 13:30:10  pierre
*** empty log message ***

Revision 1.1  2006/12/15 12:31:44  pierre
*** empty log message ***

Revision 1.5  2006/08/01 07:18:33  pierre
*** empty log message ***

Revision 1.4  2006/07/25 07:33:19  pierre
*** empty log message ***

Revision 1.3  2006/07/25 07:12:20  pierre
*** empty log message ***

Revision 1.2  2006/06/06 08:22:26  melanie
*** empty log message ***

Revision 1.1.1.1  2006/01/25 15:14:27  pierre
projet CCitron AWS 2006 Nouveau Website

Revision 1.7  2005/11/24 14:49:07  pierre
connexion de la description des node en urlencodé au parser actionscript

Revision 1.6  2005/11/04 10:29:29  sylvie
*** empty log message ***

Revision 1.5  2005/11/04 08:37:02  sylvie
*** empty log message ***

Revision 1.4  2005/11/02 16:02:34  pierre
*** empty log message ***

Revision 1.3  2005/10/27 13:35:38  sylvie
*** empty log message ***

Revision 1.2  2005/10/25 09:42:11  pierre
*** empty log message ***

Revision 1.1.1.1  2005/10/24 13:37:05  pierre
re import fusion espace v2 et ADW v2

Revision 1.1.1.1  2005/10/20 13:10:54  pierre
Espace V2


Revision 1.20  2005/06/08 10:26:57  michael
Modif pour Moz
les liens des dossiers de l'arbo ne sont plus sur plusieurs lignes (ils poussent le tableau)

Revision 1.19  2005/06/07 08:44:46  michael
Petite Modif de gestion d'erreur

Revision 1.18  2005/06/06 14:47:10  michael
Vive les espaces!


Revision 1.17  2005/06/06 10:20:55  pierre
retrait retours lignes vides en queue de fichier

Revision 1.16  2005/06/02 16:14:29  michael
Modif pour répercuter les titres de page / mot clés / Description dans la page contenante

Revision 1.15  2005/05/30 14:53:11  michael
Correction rename node
renommage récursif des absolutes path en SQL

Revision 1.14  2005/05/30 13:36:00  pierre
correction fonction get NodeInfosReverse (utf8)

Revision 1.13  2005/05/30 13:14:11  michael
Correction utf8

Revision 1.12  2005/05/30 12:17:12  michael
Réparation de fonctions dans les briques et les pages
Réordonner des dossiers

Revision 1.9  2005/05/30 08:59:18  michael
Réparation dans les briques et les pages de
la possibilité de sélectionner le dossier racine

Revision 1.8  2005/05/24 15:28:55  pierre
ajout de quelques addslashes sur les requetes manipulant les path

Revision 1.7  2005/05/24 14:39:44  pierre
ajout de node_description dans plusieurs fonctions

Revision 1.6  2005/05/23 17:09:01  pierre
encore la description de node, modif getNodeInfos

Revision 1.5  2005/05/23 16:20:38  pierre
ajout de la gestion description de node

Revision 1.4  2005/05/20 15:46:30  pierre
modifs fonctions de conversion vpath <F2> path pour echanges avec flash

Revision 1.3  2005/04/25 13:50:09  pierre
ajout fonction path2nodes

Revision 1.2  2005/04/21 08:52:52  pierre
ajout fonction "getNodeInfosReverse" qui retourne les infos du node en XML à partir du abs path

Revision 1.1.1.1  2005/04/18 13:53:29  pierre
again

Revision 1.1.1.1  2005/04/18 09:04:21  pierre
oremip new

Revision 1.1.1.1  2004/11/03 13:49:54  ddinside
lancement du projet - import de adequat

Revision 1.4  2004/06/18 14:10:21  ddinside
corrections diverses en vu de la demo prevention routiere

Revision 1.3  2004/06/16 15:23:19  ddinside
inclusion corrections

Revision 1.2  2004/04/26 08:07:09  melanie
*** empty log message ***

Revision 1.1.1.1  2004/04/01 09:20:29  ddinside
Création du projet CMS Couleur Citron nom de code : tipunch

Revision 1.5  2004/02/12 15:56:16  ddinside
mise à jour plein de choses en fait, mais je sais plus quoi parce que ça fait longtemps que je l'avais pas fait.
Mea Culpa...

Revision 1.4  2004/02/05 15:56:26  ddinside
ajout fonctionnalite de suppression de pages
ajout des styles dans spaw
debuggauge prob du nom de fichier limite à 30 caracteres

Revision 1.3  2004/01/27 12:12:50  ddinside
application de dos2unix sur les scripts SQL
modification des scripts d'ajout pour correction bug lors d'une modif de page
ajout foncitonnalité de modification de page
ajout visu d'une page si créée

Revision 1.2  2004/01/20 15:16:38  ddinside
mise à jour de plein de choses
ajout de gabarit vie des quartiers
eclatement gabarits par des includes pour contourner prob des flashs non finalisés

Revision 1.1  2003/11/27 14:45:56  ddinside
ajout gestion arbo disque non finie

Revision 1.1.1.1  2003/10/24 09:08:08  ddinside
nouvel import projet Boulogne apres migration machine

Revision 1.3  2003/10/16 21:19:46  ddinside
suite dev gestio ndes composants
ajout librairies d'images
suppressions fichiers vi
ajout gabarit

Revision 1.2  2003/10/10 08:01:52  ddinside
mise à jour modifications
nettoyage fichiers inutiles
ajout gabarits

Revision 1.1  2003/09/29 10:21:39  ddinside
librairies de manipulatio nde l'arbo des composants

*/

/*
sponthus 2005/05/31 12:00
oracle : enlever le ; à la fin de la requette
ajout de la ligne : if (DEF_BDD != "ORACLE") $sql.=";"; à chaque reket
*/

/*
function getPageObjectByNameAndNodeId($name='index', $nodeid=0, $bOnlyValid=true){
function getPageObjectByNodeId($nodeid=0, $bOnlyValid=true){
function getObjetForPage($oPage){
function getObjetForNode($node)
function getClasseForNode($node)
function getNextValidParent($node){
function getFullVirtualPath($idNode)
function getParentId($idNode)
function folderdescriptionToXML($sDescription){
function removeRecursDir($directory) {
function deleteNode($idSite, $db, $virtualPath){
function addNode($idSite, $db, $virtualPath, $libelle){
function renameNode($idSite, $db, $virtualPath, $libelle){
function saveNodeDescription($idSite, $folderdescription, $db, $virtualPath){
function getNodeInfos($db, $virtualPath){
function getNodeInfosReverse($idSite, $db, $absolutePath){
function path2minisiteRepertoire($db, $absolutePath){
function node2idside($db, $virtualPath){
function path2idside($db, $absolutePath){
function path2nodes($idSite, $db, $absolutePath){
function path2nodesReverse($idSite, $db, $virtualPath) {
function drawCompTree($idSite, $db, $virtualPath, $full_path_to_curr_id=null, $destination=null) {
function drawCompTreeMinisite($idSite, $db, $virtualPath, $full_path_to_curr_id=null, $destination=null, $paramSup="") {
function getAbsolutePathString($idSite, $db, $virtualPath,$destination=null) {
function getNodeChildren($idSite, $db, $path) {
function saveNodeOrder($idSite, $orders, $db, $path) {
function moveNode($idSite, $db, $virtualPath, $new_virtualPath) {
function getFolderPages($idSite, $path) {
function generateFlashArboMairie($idSite, $db,$path_entree=0,$paramText='linkText', $paramUrl='linkUrl') {
function generateFlashArboVousEtes($idSite, $db,$path_entree=0,$paramText='text', $paramUrl='link',$niveau=1) {
function getListPortails($idSite) {
function getPageByName($idSite, $id_node, $nompg) {

*/

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

function getPageObjectByNameAndNodeId($name='index', $nodeid=0, $bOnlyValid=true){
	if ($bOnlyValid){
		$aPage = dbGetObjectsFromFieldValue3('cms_page', array('getNodeid_page', 'getName_page', 'getValid_page'), array('equals', 'equals', 'equals'), array($nodeid, $name, 1), array('getId_page'), array('DESC'));
	}
	else{
		$aPage = dbGetObjectsFromFieldValue3('cms_page', array('getNodeid_page', 'getName_page'), array('equals', 'equals'), array($nodeid, $name), array('getId_page'), array('DESC'));
	}
	return $aPage;
}

function getPageObjectByNodeId($nodeid=0, $bOnlyValid=true){
	if ($bOnlyValid){
		$aPage = dbGetObjectsFromFieldValue3('cms_page', array('getNodeid_page', 'getValid_page'), array('equals', 'equals'), array($nodeid, 1), array('getId_page'), array('DESC'));
	}
	else{
		$aPage = dbGetObjectsFromFieldValue3('cms_page', array('getNodeid_page'), array('equals'), array($nodeid), array('getId_page'), array('DESC'));
	}
	
	// tri, l'index en premier
	$aSortedPages = array();	
	for($i=0;$i<count($aPage);$i++){
		$oPage = $aPage[$i];
		if ($oPage->get_name_page() == 'index'){	
			$aIndexPages = array_splice($aPage, $i, 1);
			array_unshift ($aPage, $aIndexPages[0]);
			break;
		}
	}
	$aSortedPages = $aPage;
	
	return $aSortedPages;
}

function orderPageObjects($aO){
	$oO = $aO[0];
	if (is_object($oO) && method_exists($oO, 'get_ordre')){
		$aObyOrder = array();		
		for ($i=0;$i<count($aO);$i++){
			$oO = $aO[$i];
			$aObyOrder[$oO->get_ordre()]=$oO;		
		}		
		ksort($aObyOrder);		
		return $aObyOrder;
	}
	else{
		return $aO;
	}
}

function getObjetForPage($oPage){
	$aRetour = false;
		
	$aAssoClasses = dbGetAssocies($oPage, 'cms_assoclassepage', false, true);
	
	$prevSerial = NULL;
	
	if ($aAssoClasses['list']){			
		foreach($aAssoClasses['list'] as $kX => $aClasse){	
			if (serialize($aClasse)!=$prevSerial){
				if (is_array($aClassRetour)){ // merge de la précedente pile dans retour	
					if ($aRetour){
						$aRetour = array_merge($aRetour, orderPageObjects($aClassRetour));
					}
					else{
						$aRetour = orderPageObjects($aClassRetour);
					}
				}
				$aClassRetour = array();
				$xClassName = $aClasse['display'];
				$xClassId = $aClasse['ref_id'];	
				$aAssoObjets = dbGetObjectsFromFieldValue3('cms_assoclassepage', array('get_cms_page', 'get_classe'), array('equals', 'equals'), array($oPage->get_id(), $xClassId), NULL, NULL);				
				if($aAssoObjets){				
					foreach($aAssoObjets as $kOx => $oX){	
						$oXres = dbGetObjectFromPK($xClassName, $oX->get_objet(), true);			
						//$oXres = new $xClassName($oX->get_objet());			
						if($oXres){
							if (!method_exists($oXres, 'get_statut')){
								$aClassRetour[] = $oXres;
							}
							elseif ($oXres->get_statut() == DEF_ID_STATUT_LIGNE){
								$aClassRetour[] = $oXres;	
							}
						}
						else{
							//echo '<p>asso missing id '.$oX->get_objet().'</p>';
							dbDelete($oX);
						}
					}				
				}
				$prevSerial = serialize($aClasse);
			}
		}
	}
	
	if (is_array($aClassRetour)){ // merge de la dernière pile dans retour					
		if ($aRetour){
			$aRetour = array_merge($aRetour, orderPageObjects($aClassRetour));
		}
		else{
			$aRetour = orderPageObjects($aClassRetour);
		}
	}

	return $aRetour;
}

function getObjetForNode($node){
	$aRetour = false;

	$aPage = dbGetObjectsFromFieldValue3('cms_page', array('getNodeid_page', 'getName_page', 'getValid_page'), array('equals', 'equals', 'equals'), array($node['id'], 'index', 1), array('getId_page'), array('DESC'));
	if ($aPage){								
		$oPage = $aPage[0];		
	
		return getObjetForPage($oPage);
	}
	
	
	return $aRetour;
}

function getClasseForNode($node){
	$aRetour = false;

	$aPage = dbGetObjectsFromFieldValue3('cms_page', array('getNodeid_page', 'getName_page', 'getValid_page'), array('equals', 'equals', 'equals'), array($node['id'], 'index', 1), array('getId_page'), array('DESC'));
	if ($aPage){								
		$oPage = $aPage[0];
	
		$aAssoClasses = dbGetAssocies($oPage, 'cms_assoclassepage', false, true);

		if ($aAssoClasses['list']){			
			foreach($aAssoClasses['list'] as $kX => $aClasse){				
				$aRetour[]=$aClasse["display"];
			}
		}
	}	
	
	return $aRetour;
}

function getNextValidParent($node){
	if (intval($node["parent"]) == 0){		
		return $node;
	}
	// fin cas trivial
	global $db;
	$parent = getNodeInfos($db, $node["parent"]);
	if (intval($parent["parent"]) != 0){		
		return getNextValidParent($parent);
	}
	else{
		return $parent;
	}
}

function getFullVirtualPath($idNode){
	//error_log('getFullVirtualPath('.$idNode.')');
	global $db;
	
	if (strval($idNode)=='0'){
		return '0';
	}

	$tempNode = getNodeInfos($db,$idNode);
	
	if ($tempNode['parent'] != "0"){
		$sVirtualPath = $idNode;
		while($tempNode['parent'] != "0"){
			if (trim($tempNode['parent'])==''){
				error_log('getFullVirtualPath >> pas de node parent valide '.__FILE__.':'.__LINE__);
				break;
			}
			$tempNode = getNodeInfos($db,$tempNode['parent']);
			$sVirtualPath = $tempNode['id'].",".$sVirtualPath;
		}
		return "0,".$sVirtualPath;		
	}
	else{
		return "0,".$idNode;
	}
}

function getFullTagPath($idNode){
	global $db;

	$tempNode = getNodeInfos($db,$idNode);
	
	if ($tempNode['parent'] != "0"){
		$sTagPath = $tempNode['tag'];
		while($tempNode['parent'] != "0"){
			$tempNode = getNodeInfos($db,$tempNode['parent']);
			if ($tempNode['tag']!=''){
				$sTagPath = $tempNode['tag']."::".$sTagPath;
			}
			else{
				$sTagPath = $tempNode['libelle']."::".$sTagPath;
			}			
		}
		return "home::".$sTagPath;		
	}
	else{
		if ($tempNode['tag']!=''){
			return "home::".$tempNode['tag'];
		}
		else{
			return "home::".$tempNode['libelle'];
		}
	}
}

function getParentId($idNode){
	global $db;
	$tempNode = getNodeInfos($db,$idNode);
	$tempNode = getNodeInfos($db,$tempNode['parent']);
	return $tempNode['id'];
}

function folderdescriptionToXML($sDescription){
	// fichier%20swf=ariane.swf&batiment=1
	// doit devenir
	// fichier_swf="ariane.swf" batiment="1"

	$sTemp = "";
	$aFolderDescriptionVariable = array();
	$aFolderDescriptionValue = array();
	
	$aFolderdescription = split("&", $sDescription);
	$ePaireCounter = 0;
	foreach ($aFolderdescription as $paire => $paireAspliter) {
		$aTempPaireSplit = split("=", $paireAspliter);
		$ePaireCounter++;
		$aFolderDescriptionVariable[$ePaireCounter] = rawurldecode($aTempPaireSplit[0]);
		$aFolderDescriptionValue[$ePaireCounter] = rawurldecode($aTempPaireSplit[1]);
		$sTemp .= removeForbiddenChars($aFolderDescriptionVariable[$ePaireCounter])."=\"".utf8_encode($aFolderDescriptionValue[$ePaireCounter])."\" ";
		
	}
	return $sTemp;
}

if( !function_exists( "removeRecursDir" ) ){
    function removeRecursDir($directory) {
            //$directory = preg_replace('/\'/','\\\'',$directory);
            //$directory = preg_replace('/\ /','\\\ ',$directory);
            $dossier = opendir($directory);
            $total = 0;
            while($fichier = readdir($dossier)) {
                    $l = array('.','..');
                    if (!in_array($fichier, $l)) {
                            if(is_dir($directory.'/'.$fichier)){
                                    $total += removeRecursDir($directory.'/'.$fichier);
                            } else {
                                    if(unlink($directory.'/'.$fichier))
                                            $total++;
                                    else 
                                            error_log("Suppression du fichier $directory/$fichier impossible");
                            }
                    }
            }
            @closedir($dossier);
            if (rmdir($directory))
                    $total++;
            else
                    error_log("Suppression du répertoire $directory impossible");
            return $total;
    }
}

function deleteNode($idSite, $db, $virtualPath){
	global $CMS_ROOT;
	if( ($virtualPath=='0') || (strlen($virtualPath)=='0'))
		return false;
	$array_path = split(',',$virtualPath);
	$node_id = array_pop($array_path);
	$result = false;
	$parentVirtualPath = join(',',$array_path);
	if($parentVirtualPath==0)
		$parentVirtualPath = 'Racine';
	$children=getNodeChildren($idSite, $db,$virtualPath);

	foreach($children as $k => $child) {
		if (deleteNode($idSite, $db, $virtualPath.','.$child['id'])==false) {
			error_log("Impossible de supprimer le dossier id=$child");
		}
	}

	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_id=$node_id";
	// une seule racine pour tous les arbres
	if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;
	if (DEF_BDD != "ORACLE") $sql.=";";
	
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$selectedFolder = $rs->fields[n('node_absolute_path_name')];
		$n = removeRecursDir($CMS_ROOT.$selectedFolder);
		if ($n > 0) {

			$sql = " DELETE FROM cms_arbo_pages";
			$sql.= " WHERE node_id=$node_id";
			// une seule racine pour tous les arbres
			if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;

			if (DEF_BDD != "ORACLE") $sql.=";";	
			
			$rs = $db->Execute($sql);
			if($rs!=false) {
				$result = $parentVirtualPath;
			} else {
				echo "include/cms-inc/arbopage.lib.php > deleteNode";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>$sql</strong>";
				error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
				error_log($db->ErrorMsg());
				$result = false;
			}
		} else {
			echo "include/cms-inc/arbopage.lib.php > deleteNode";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
			error_log("Suppression du dossier $CMS_ROOT$selectedFolder impossible");
			$result = false;
		}
	} else {
		$result=false;
	}
	$rs->Close();
	return $result;
}

function addNode($idSite, $db, $virtualPath, $libelle){

	global $CMS_ROOT;
	$node_id = array_pop(split(',',$virtualPath));
	$result = false;

	// Les guillements font foirer l'enregistrement du répertoire sur le disque
	$libelle = str_replace('"',"'\\'", $libelle); // On remplace les guillemets par des doubles quotes
	$libelle = str_replace('`',"'", $libelle); // On remplace les quotes zarbi
	//$libelle = str_replace('/'," - ", $libelle); // On remplace les / par des tirets

	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_id=$node_id";
	// une seule racine pour tous les arbres
	if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;

	if (DEF_BDD != "ORACLE") $sql.=";";	
				
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		if(preg_match('/Racine/i',$rs->fields[n('node_absolute_path_name')])) { // on est à la racine
			$path = '/'.removeForbiddenChars($libelle).'/';
		}
		else $path = addslashes($rs->fields[n('node_absolute_path_name')]).removeForbiddenChars($libelle).'/';

		$eNode_id = getNextVal("cms_arbo_pages", "node_id");
		$sql = " INSERT INTO cms_arbo_pages (node_id, node_parent_id, node_libelle, node_absolute_path_name, node_id_site)
		VALUES (".$eNode_id.", ".$node_id.", '".$libelle."', '".to_dbquote($path)."', ".$idSite.")";
		if (DEF_BDD != "ORACLE") $sql.=";";	
		
		$rs = $db->Execute($sql);
		if($rs!=false) {
			$result = $eNode_id;
			if(!mkdir($CMS_ROOT.stripslashes($path))) {

				// si echec de la creation du rep, on efface le rep en base...
				$sql = " DELETE FROM cms_arbo_pages WHERE node_id = ".$result;
				// une seule racine pour tous les arbres
				if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;
				if (DEF_BDD != "ORACLE") $sql.=";";
				$rs = $db->Execute($sql);

				echo "include/cms-inc/arbopage.lib.php > addNode";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>$sql</strong>";
				error_log(" Erreur lors de la creation du repertoire $CMS_ROOT/$path");
				$result = false;
			}
		} else {
			echo "<br />include/cms-inc/arbopage.lib.php > addNode";
			echo "<br />Erreur de fonctionnement interne";
			echo "<br /><strong>$sql</strong>";
			error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
			error_log($db->ErrorMsg());
			$result = false;
		}
	} else {
		$result=false;
	}
	$rs->Close();
	return $result;
}

// rename un noeud pour la table : cms_arbo_pages 
function renameNode_cms_arbo_pages($idSite, $db, $virtualPath, $libelle, $node_id, $isFirstNode=true) {

	global $CMS_ROOT;

	$oSite = new Cms_site($idSite);

	// Les guillements font foirer l'enregistrement du répertoire sur le disque
	$libelle = str_replace('"',"'\\'", $libelle); // On remplace les guillemets par des doubles quotes
	$libelle = str_replace('`',"'", $libelle); // On remplace les quotes zarbi
	//$libelle = str_replace('/'," - ", $libelle); // On remplace les / par des tirets

	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_id=$node_id";
	// une seule racine pour tous les arbres
	if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;

	if (DEF_BDD != "ORACLE") $sql.=";";	

	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {

		$result = true;

		$old_path = $CMS_ROOT.'/'.$rs->fields[n('node_absolute_path_name')];
		$old_relativepath = $rs->fields[n('node_absolute_path_name')];

		// nom noeud BDD
		//====================

		$sql = " UPDATE cms_arbo_pages set node_libelle='".$libelle."'
						WHERE node_id=$node_id";
		
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;

		if (DEF_BDD != "ORACLE") $sql.=";";	
		$rs = $db->Execute($sql);

		// reconstitution de l'absolute path grâce aux bons libelles en BDD
		//====================
		
		$absolute = path2nodesReverse($idSite, $db, $virtualPath);
		$path = $CMS_ROOT.$absolute;
		$relativepath = $absolute;
		
		// chemin physique
		//=====================
		
		// si ce n'est pas un enfant (premier noeud)
		// renommage du dossier physique à faire une fois seulement
		// le récursif sert à renommer le champ absolute_path en BDD

		if ($isFirstNode) {

			if(file_exists($path)) // Si le nom existe déjà on fait rien
				return false;
	
			$old_libelle = $rs->fields[n('node_libelle')];
	
			// Une erreur est survenue lors du renommage de larbo physique => on annule
			if(!rename($old_path.$old_libelle, $path))
				return false;
		}

		// chemin BDD
		//==================

		$sql = " UPDATE cms_arbo_pages set node_absolute_path_name='".addslashes($relativepath)."' where node_id=".$node_id;
		
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;

		if (DEF_BDD != "ORACLE") $sql.=";";	
		$rs = $db->Execute($sql);


	// recherche tous les enfants du noeud
		$sql = " SELECT node_id, node_libelle , node_absolute_path_name, node_order, node_description";
		$sql.= " FROM cms_arbo_pages";
		$sql.= " WHERE node_parent_id=".$node_id;
		$sql.= " AND node_id<>0";
		$sql.= " AND node_id_site=".$idSite;
		$sql.= " ORDER BY node_order, node_libelle";
	
		$rs = $db->Execute($sql);
	
		$aChildren = array();
		if($rs==false) {
	
			echo "include/cms-inc/arbominisite.lib.php > renameNode_cms_arbo_pages";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
			error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
			error_log($db->ErrorMsg());
			$result = false;
		} else {
				while(!$rs->EOF) {
					$tmparray = array(
						'id' => $rs->fields[n('node_id')],
						'libelle' => $rs->fields[n('node_libelle')],
						'path' => $rs->fields[n('node_absolute_path_name')],
						'order' => $rs->fields[n('node_order')],
						'description' => $rs->fields[n('node_description')]
					);
					array_push($aChildren, $tmparray);
					$rs->MoveNext();
			}
		}

		// renommage des absolute path des enfants
		foreach($aChildren as $k => $v) {
			// récursif : renommage des enfants de chaque noeud
			renameNode_cms_arbo_pages($idSite, $db, $virtualPath.','.$v['id'], $v['libelle'], $v['id'], false);
		}

	} else {
		$result=false;
	}

	if ($result == true) $result=$node_id;
	
	$rs->Close();
	return $result;
}

// rename un noeud 
function renameNode($idSite, $db, $virtualPath, $libelle){
	$node_id = array_pop(split(',',$virtualPath));

	// arbo pages
	$result_arbopages = renameNode_cms_arbo_pages($idSite, $db, $virtualPath, $libelle, $node_id);

	return $result_arbopages;
}


function saveNodeDescription($idSite, $folderdescription, $db, $virtualPath){
	$node_id = array_pop(split(',',$virtualPath));
	$result = false;

	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_id=".$node_id;
	// une seule racine pour tous les arbres
	if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;
	
	if (DEF_BDD != "ORACLE") $sql.=";";		

	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {

		$sql = " UPDATE cms_arbo_pages set node_description='".$folderdescription."' WHERE node_id=".$node_id;
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;
		
		if (DEF_BDD != "ORACLE") $sql.=";";
		$rs = $db->Execute($sql);

		$sql = " SELECT node_id, node_parent_id, node_libelle";
		$sql.= " FROM cms_arbo_pages WHERE node_id=".$node_id;
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND id_site=".$idSite;

		if (DEF_BDD != "ORACLE") $sql.=";";
		$rs = $db->Execute($sql);
		
		if($rs!=false && !$rs->EOF) {
			$result = true;
		} else {
			echo "include/cms-inc/arbopage.lib.php > saveNodeDescription";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
			error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
			error_log($db->ErrorMsg());
			$result = false;
		}
	} else {
		$result=false;
	}
	$rs->Close();
	return $result;
}

function getNodeInfos($db, $virtualPath){

	$node_id = array_pop(explode(',',$virtualPath));
	$result = null;
	if (trim($node_id)==''){
		//error_log('getNodeInfos('.$virtualPath.') appel incorrect '.__FILE__.':'.__LINE__);
		return false;
	}
	
	$sql = " SELECT * FROM cms_arbo_pages WHERE node_id=".$node_id;
	
	if (DEF_BDD != "ORACLE") $sql.=";";
		
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$result = array(
			'id' => $rs->fields[n('node_id')],
			'libelle' => $rs->fields[n('node_libelle')],
			'parent' => $rs->fields[n('node_parent_id')],
			'path' => $rs->fields[n('node_absolute_path_name')],
			'order' => $rs->fields[n('node_order')],
			'description' => $rs->fields[n('node_description')],
			'tag' => $rs->fields[n('node_tag')],
			'id_site' => $rs->fields[n('node_id_site')]			
		);
	} else {
			//echo "include/cms-inc/arbopage.lib.php > getNodeInfos";
			//echo "<br />Erreur interne de programme";
			//echo "<br /><strong>".$sql."</strong>";
			error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
			error_log($db->ErrorMsg());
			$result = false;
		
	}
	//$rs->Close();
	return $result;
}

function getNodeInfosReverse($idSite, $db, $absolutePath){	
	//error_log('getNodeInfosReverse('.$idSite.', '.$absolutePath.')');
	$absolutePath = preg_replace("/^\/*content/", "", $absolutePath);
	// la racine est la même pour tous
	// donc si le chemin est la racine, ajouter le site dans le chemin
	$oSite = new Cms_site($idSite);
	$absolutePath = rawurldecode(substr ($absolutePath, 0, strrpos ($absolutePath, "/") + 1));
	
	if (( $absolutePath== "/".$oSite->get_rep()."/") || ($absolutePath == "/")) {
		$result = getNodeInfos($db, "0");
		return $result;
	}
	else{
		//$absolutePath = rawurldecode(substr ($absolutePath, 0, strrpos ($absolutePath, "/") + 1));
		$result = NULL;	
	
		$sql = " SELECT * FROM cms_arbo_pages WHERE node_absolute_path_name='".addslashes($absolutePath)."' AND node_id_site=".$idSite;
		if (DEF_BDD != "ORACLE") $sql.=";";
	
		$rs = $db->Execute($sql);
		if($rs!=false && !$rs->EOF) {
			$result = array(
				'id' => $rs->fields[n('node_id')],
				'libelle' => $rs->fields[n('node_libelle')],
				'parent' => $rs->fields[n('node_parent_id')],
				'path' => $rs->fields[n('node_absolute_path_name')],
				'description' => $rs->fields[n('node_description')],
				'tag' => $rs->fields[n('node_tag')],
				'id_site' => $rs->fields[n('node_id_site')]
			);
			$rs->Close();
		} else {
				//echo "include/cms-inc/arbopage.lib.php > getNodeInfosReverse";
				//echo "<br />Erreur interne de programme";
				//echo "<br /><strong>$sql</strong>";
				error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
				error_log($db->ErrorMsg());
				$result = false;		
		}		
		return $result;
	}
}

function path2minisiteRepertoire($db, $absolutePath){
	if (strpos($absolutePath, "/content/") === false){
		return "/"; // cas de merde où on est hors /content/
	}
	else{
		$sMinisiteRepertoire = preg_replace("/(.)*\/content\/([^\/]+)\/.*/", "$2", $absolutePath);
		return $sMinisiteRepertoire;
	}
}
function path2idside($db, $absolutePath){
	if (strpos($absolutePath, "/content/") === false){
		return 1; // cas de merde où on est hors /content/
	}
	else{
		$sMinisiteRepertoire = path2minisiteRepertoire($db, $absolutePath);
		$sSql = "SELECT cms_id FROM cms_site WHERE cms_rep = '".$sMinisiteRepertoire."'";
		$idSite = dbGetUniqueValueFromRequete($sSql);

		return $idSite;
	}
}

function node2idside($db, $virtualPath){
	
	$eCurrNode = end(split(',',$virtualPath));
	
	$sSql = "SELECT node_absolute_path_name FROM cms_arbo_pages WHERE node_id =".$eCurrNode;
	$absolutePath = dbGetUniqueValueFromRequete($sSql);
	
	return path2idside($db, "/content".$absolutePath);

}

function path2nodes($idSite, $db, $absolutePath){
	//error_log('path2nodes('.$idSite.', '.$absolutePath.')');
	if ($absolutePath == "/"){
		return "0";
	}
	else{
		$entree = getNodeInfosReverse($idSite,$db,$absolutePath);
		$nodeItems = array();
		$nodeStr = $entree['id'];
		array_push($nodeItems, $nodeStr);
		// si le node parent n'est pas 0 (root) on recherche encore
		while($entree['parent'] != 0){
			if (trim($entree['parent'])==''){
				error_log('path2nodes >> pas de node parent valide '.__FILE__.':'.__LINE__);
				break;
			}
			$entree = getNodeInfos($db, $entree['parent']);
			$nodeStr = $entree['id'];
			//error_log('push '.$entree['id']);
			array_push($nodeItems, $nodeStr);
		}
		//-----------
		$nodeStr = "0";
		for ($i=(count($nodeItems)-1);$i>=0;$i--){
			$nodeStr .= ",".$nodeItems[$i];
		}
		return $nodeStr;
	}
}

function path2nodesReverse($idSite, $db, $virtualPath) {
// Reconstitution d'un absolutePath à partir d'un virtualPath
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// Retourne l'absolutePath
	$strPath = '/';
	foreach(split(',',$virtualPath) as $id){
		if ($id!="0") {
			$sql = " SELECT node_libelle FROM cms_arbo_pages WHERE node_id=".$id;
			// une seule racine pour tous les arbres
			if ($node_id != 0) $sql.= " AND node_id_site=".$idSite;
			
			if (DEF_BDD != "ORACLE") $sql.=";";				
			$rs = $db->Execute($sql);
			if($rs!=false && !$rs->EOF) {
				$strPath.=  $rs->fields[n('node_libelle')].'/';
			} else {
				echo "include/cms-inc/arbopage.lib.php > path2nodesReverse";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>".$sql."</strong>";
				error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
				error_log($db->ErrorMsg());
				$strPath.='??????';
			}
			$rs->Close();
		}
	}
	
	return $strPath;
}


function drawCompTree($idSite, $db, $virtualPath, $full_path_to_curr_id=null, $destination=null) {
	if($destination==null)
		$destination=$_SERVER['PHP_SELF'];
	$OP = '?';
	if(preg_match('/\?/',$destination))
		$OP = '&';
	$spacerStr = '&nbsp;&nbsp;';
	$strHTML = '';
	$tree_depth='1';
	if ($full_path_to_curr_id==null || $full_path_to_curr_id=="0") {
		// cas particulier de la racine où il faut dessiner le père en plus des fils
		$full_path_to_curr_id=0;
			$strHTML .= "<a class=\"arbo\" href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=0\"><img border=\"0\" src=\"".$URL_ROOT."/backoffice/cms/img/ico_dossier_opened.gif\"><b>Racine</b></a><br/></td></tr><tr><td>\n";

	} else {
		$tree_depth = sizeof(split(',',$full_path_to_curr_id));
	}

	$children = getNodeChildren($idSite, $db, $full_path_to_curr_id);
	//indentation :
	$indent='';
	for($i=0;$i<$tree_depth;$i++){
		$indent.=$spacerStr;
	}

	foreach ($children as $k=>$v) {
		$id = $v['id'];
		$libelle = $v['libelle'];
		$description = $v['description'];
		//debut de ligne...
		if (!in_array($id,split(',',$virtualPath))) {
			//dossier ferme
			$strHTML .= "<span style=\"white-space:nowrap\">$indent<a href=\"".$destination.$OP."idSite=$idSite&v_comp_path=$full_path_to_curr_id,$id\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_dossier.gif\"><small>".strip_tags(str_replace(' ','&nbsp;',$libelle))."</small></a><br/></span>\n";
		} else {
			//dossier ouvert
			if(array_pop(split(',',$virtualPath))==$id)
				$strHTML .= "<span style=\"white-space:nowrap\">$indent<a class=\"arbo\" href=\"".$destination."?idSite=$idSite&v_comp_path=$full_path_to_curr_id,$id\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_dossier_opened.gif\"><small><span class=\"arbo\">".strip_tags(str_replace(' ','&nbsp;',$libelle))."</span></small></a><br/></span>\n";
			else
				$strHTML .= "<span style=\"white-space:nowrap\">$indent<a href=\"".$destination."?idSite=$idSite&v_comp_path=$full_path_to_curr_id,$id\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_dossier_opened.gif\"><small>".strip_tags(str_replace(' ','&nbsp;',$libelle))."</small></a><br/></span>\n";
			$strHTML.=drawCompTree($idSite, $db, $virtualPath,$full_path_to_curr_id.','.$id,$destination);
		}
	}
	return $strHTML;
}



// drawCompTree 
function drawCompTreeMinisite($idSite, $db, $virtualPath, $full_path_to_curr_id=null, $destination=null, $paramSup="") {

	if($destination==null)
		$destination=$_SERVER['PHP_SELF'];
	$OP = '?';
	if(preg_match('/\?/',$destination))
		$OP = '&';
	$spacerStr = '&nbsp;&nbsp;';
	$strHTML = '';
	$tree_depth='1';
	if ($full_path_to_curr_id==null || $full_path_to_curr_id=="0") {
		// cas particulier de la racine où il faut dessiner le père en plus des fils
		$full_path_to_curr_id=0;
			$strHTML .= "<a class=\"arbo\" href=\"".$destination.$OP."idSite=".$idSite."".$paramSup."&v_comp_path=0&source=minisite\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_dossier_opened.gif\"><b>Racine</b></a><br/></td></tr><tr><td>\n";

	} else {
		$tree_depth = sizeof(split(',',$full_path_to_curr_id));
	}

	$children = getNodeChildren($idSite, $db, $full_path_to_curr_id);
	//indentation :
	$indent='';
	for($i=0;$i<$tree_depth;$i++){
		$indent.=$spacerStr;
	}

	foreach ($children as $k=>$v) {
		$id = $v['id'];
		$libelle = $v['libelle'];
		$description = $v['description'];
		//debut de ligne...
		if (!in_array($id,split(',',$virtualPath))) {
			//dossier ferme
			$strHTML .= "<span style=\"white-space:nowrap\">$indent<a href=\"".$destination.$OP."idSite=".$idSite."".$paramSup."&v_comp_path=$full_path_to_curr_id,$id&source=minisite\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\">";
			if ($tree_depth == 1 && is_cms_minisite ($idSite , $id) ) $strHTML .= "<img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_minisite_off.gif\">&nbsp;";
			else $strHTML .= "<img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_dossier.gif\">&nbsp;" ;
			$strHTML .= "<small>".str_replace(' ','&nbsp;',$libelle)."</small></a><br/></span>\n";
		} else {
			//dossier ouvert
			if(array_pop(split(',',$virtualPath))== $id && is_cms_minisite ($idSite , $id) ) {
				$strHTML .= "<span style=\"white-space:nowrap\">$indent<a class=\"arbo\" href=\"".$destination."?idSite=".$idSite."".$paramSup."&v_comp_path=$full_path_to_curr_id,$id&source=minisite\" title=\"".str_replace('"', "''", $description)."\">";
				if ($tree_depth == 1) $strHTML .= "<img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_minisite_on_opened.gif\">&nbsp;";
				else $strHTML .= "<img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_dossier_opened.gif\">&nbsp;";
				$strHTML .= "<small><span class=\"arbo\">".str_replace(' ','&nbsp;',$libelle)."</span></small></a><br/></span>\n";
			}
			else {
				$strHTML .= "<span style=\"white-space:nowrap\">$indent<a href=\"".$destination."?idSite=".$idSite."".$paramSup."&v_comp_path=$full_path_to_curr_id,$id&source=minisite\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\">";
				if ($tree_depth == 1 && is_cms_minisite ($idSite , $id)  ) $strHTML .= "<img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_minisite_on.gif\">&nbsp;";
				else $strHTML .= "<img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/ico_dossier_opened.gif\">&nbsp;";
				$strHTML .= "<small>".str_replace(' ','&nbsp;',$libelle)."</small></a><br/></span>\n";
			}
			$strHTML.=drawCompTreeMinisite($idSite, $db,$virtualPath,$full_path_to_curr_id.','.$id,$destination, $paramSup);
		}
	}
	return $strHTML;
}

function is_cms_minisite ($idSite, $node) {
  
	$aMS = dbGetObjectsFromFieldValue("cms_minisite", array('get_site', 'get_node'), array($idSite, $node), NULL);

	if (sizeof($aMS) > 0) { 
		return true;
	}
	else { 
		return false;
	}
}


function getAbsolutePathString($idSite, $db, $virtualPath,$destination=null) {
	if($destination==null)
		$destination=$_SERVER['PHP_SELF'];
	$OP = '?';
	if(preg_match('/\?/',$destination))
		$OP = '&';
	$strPath = '<a href="'.$destination.$OP.'v_comp_path=0" class="arbo"><b>Racine</b></a>';
	$localPath='0';
	foreach(split(',',$virtualPath) as $id){
		if ($id!="0") {
			$localPath.=",$id";

			$sql = " SELECT node_libelle FROM cms_arbo_pages WHERE node_id=$id";
			// une seule racine pour tous les arbres
			if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
			
			if (DEF_BDD != "ORACLE") $sql.=";";
			
			$rs = $db->Execute($sql);
			if($rs!=false && !$rs->EOF) {
				$strPath.='&nbsp;&nbsp;>&nbsp;&nbsp;<a href="'.$destination.$OP.'v_comp_path='.$localPath.'" class="arbo">'.$rs->fields[n('node_libelle')].'</a>';
			} else {
				//echo "include/cms-inc/arbopage.lib.php > getAbsolutePathString";
				//echo "<br />Erreur interne de programme";
				//echo "<br /><strong>$sql</strong>";
				error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
				error_log($db->ErrorMsg());
				$strPath.='&nbsp;&nbsp;>&nbsp;&nbsp;??????';
			}
			$rs->Close();
		}
	}
	return $strPath;
}

function getNodeChildren($idSite, $db, $path) {
	$node_id = array_pop(split(',',$path));
	$result = array();

	$sql = " SELECT * FROM cms_arbo_pages";
	$sql.= " WHERE node_parent_id=".$node_id;
	$sql.= " AND node_id<>0";
	$sql.= " AND node_id_site=".$idSite;
	$sql.= " ORDER BY node_order, node_libelle";
	if (DEF_BDD != "ORACLE") $sql.=";";
//print("<br>$sql");

	$rs = $db->Execute($sql);
	if($rs==false) {

		echo "include/cms-inc/arbopage.lib.php > getNodeChildren";
		echo "<br />Erreur interne de programme";
		echo "<br /><strong>$sql</strong>";
		error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
		error_log($db->ErrorMsg());
		$result = false;
	} else {
		if ($rs->EOF) {
			$result = array();
		} else {
			while(!$rs->EOF) {
				$tmparray = array(
					'id' => $rs->fields[n('node_id')],
					'parent' => $rs->fields[n('node_parent_id')],
					'libelle' => $rs->fields[n('node_libelle')],
					'path' => $rs->fields[n('node_absolute_path_name')],
					'order' => $rs->fields[n('node_order')],
					'description' => $rs->fields[n('node_description')],
					'tag' => $rs->fields[n('node_tag')]
				);
				array_push($result, $tmparray);
				$rs->MoveNext();
			}
		}
	}
	$rs->Close();
	return $result;
}

function saveNodeOrder($idSite, $orders, $db, $path) {
	$result = true;
	$sql="";
	foreach ($orders as $id => $ordre)
	{
		if($ordre=="") $ordre = 100;

		$sql = " UPDATE cms_arbo_pages SET node_order=$ordre WHERE node_id=$id";
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
		
		if (DEF_BDD != "ORACLE") $sql.=";\n";

		$rs = $db->Execute($sql);
		if($rs==false) {
			echo "include/cms-inc/arbopage.lib.php > saveNodeOrder";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
			error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
			error_log($db->ErrorMsg());
			$result = false;
		}
		$rs->Close();
	}
	return $result;
}

function moveNode($idSite, $db, $virtualPath, $new_virtualPath) {
// Déplace un noeud dans l'arbo et sur le disque
// $virtualPath = path source
// $new_virtualPath = path destination
// Renvoi "true" si ok, "false" sinon
	global $CMS_ROOT;
	$node_id = array_pop(split(',',$virtualPath));
	$result = false;
	$nodeInfos = getNodeInfos($db, $virtualPath);
	$new_nodeInfos = getNodeInfos($db, $new_virtualPath);
	if($new_nodeInfos['id']==0) { // Le dossier de destination est la racine!
		$new_nodeInfos['path']='/';
	}
	$sql = " UPDATE cms_arbo_pages SET node_parent_id=".$new_nodeInfos['id'].",
					node_absolute_path_name='".addslashes($new_nodeInfos['path']).addslashes($nodeInfos['libelle'])."/' 
					WHERE node_id=$node_id";
	// une seule racine pour tous les arbres
	if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
					
	if (DEF_BDD != "ORACLE") $sql.=";\n";

	$rs = $db->Execute($sql);
//	$oldpath = $CMS_ROOT.utf8_encode(ereg_replace("[/]?$","",$nodeInfos['path']));
//	$newpath = $CMS_ROOT.utf8_encode($new_nodeInfos['path']).utf8_encode($nodeInfos['libelle']);
	$oldpath = $CMS_ROOT.ereg_replace("[/]?$","",$nodeInfos['path']);
	$newpath = $CMS_ROOT.$new_nodeInfos['path'].$nodeInfos['libelle'];
	if($rs!=false) {
		if(file_exists($newpath)) // Si le nom existe déjà on fait rien
			return false;
		if(!rename($oldpath,$newpath))
		// Une erreur est survenue lors du renommage de larbo physique => on annule
			return false;
	} else {
		echo "<br />include/cms-inc/arbopage.lib.php > moveNode";
		echo "<br />Erreur interne de programme";
		echo "<br /><strong>$sql</strong>";
		error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
		error_log($db->ErrorMsg());
		$result = false;
	}
	$rs->Close();
	return $result;
}


function getFolderPages($idSite, $path) {
	global $db;
	$node_id = array_pop(split(',',$path));
	$return = array();

	$sql = " SELECT id_page, name_page, gabarit_page, cast(dateadd_page as date) as dateadd_page, ";
	$sql.= " cast(dateupd_page as date) as dateupd_page, ";
	$sql.= " datedlt_page, cast(datemep_page as date) as datemep_page, isgenerated_page, ";
	$sql.= " valid_page, nodeid_page, options_page, id_site";
	$sql.= " FROM cms_page";
	$sql.= " WHERE nodeid_page=$node_id";
	$sql.= " AND ".isDdateNull("datedlt_page")." ";
	$sql.= " AND valid_page=1 AND id_site=$idSite AND isgabarit_page=0";
	$sql.= " ORDER BY name_page ASC";		

	if (DEF_BDD != "ORACLE") $sql.=";";

	$rs = $db->Execute($sql);

	if($rs==false) {
		echo "include/cms-inc/arbopage.lib.php > getFolderPages";
		echo "<br />Erreur interne de programme";
		echo "<br /><strong>$sql</strong>";
		error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
		error_log($db->ErrorMsg());
		$return = false;
	} else {
		while(!$rs->EOF) {

			if ($rs->fields[n('dateupd_page')] != "") {
				$modif = date('d/m/Y',strtotime($rs->fields[n('dateupd_page')]));
			} else {
				$modif = '---';			
			}

			$tmparray = array(
				'name' => $rs->fields[n('name_page')].'.php',
				'id' => $rs->fields[n('id_page')],
				'gabarit' => $rs->fields[n('gabarit_page')],
				'creation' => date('d/m/Y',strtotime($rs->fields[n('dateadd_page')])),
				'mep' => date('d/m/Y',strtotime($rs->fields[n('datemep_page')])),
				'modification' => $modif,
				'id_site' => $rs->fields[n('id_site')],
			);
			array_push($return, $tmparray);
			$rs->MoveNext();
		}
	}
	$rs->Close();
	return $return;
}

function generateFlashArboMairie($idSite, $db,$path_entree=0,$paramText='linkText', $paramUrl='linkUrl') {
	$flashString = '';
	$numParam = 0;
	$children = getNodeChildren($idSite, $db,$path_entree);
	foreach($children as $k => $v) {
		if (!preg_match('/^cache$/',$v['libelle'])) {
			$numParam++;
			if($paramText=="linkText" && ( ($numParam==4) || ($numParam==8) || ($numParam==12) || ($numParam==18) || ($numParam==22))) {
				$flashString .= '&'.$paramText."_$numParam=none&".$paramUrl."_$numParam=none";
				$numParam++;
				
			}
			$flashString .= '&'.$paramText."_$numParam=".utf8_encode($v['libelle'])."&".$paramUrl."_$numParam=".utf8_encode('/content'.$v['path']);
			$tmp = generateFlashArboMairie($db,$path_entree.','.$v['id'], $paramText."_$numParam", $paramUrl."_$numParam");
			$flashString .= $tmp[0];
		}
	}
	return array($flashString,$numParam);
}

function generateFlashArboVousEtes($idSite, $db,$path_entree=0,$paramText='text', $paramUrl='link',$niveau=1) {
	$flashString = '';
	$numParam = 0;
	$children = getNodeChildren($idSite, $db,$path_entree);
	foreach($children as $k => $v) {
		if (!preg_match('/^cache$/',$v['libelle'])) {
			$numParam++;
			$flashString .= '&'.$paramText."_$numParam=".utf8_encode($v['libelle'])."&".$paramUrl."_$numParam=".utf8_encode('/content'.$v['path']);
			if($niveau<2) {
				$tmp = generateFlashArboVousEtes($db,$path_entree.','.$v['id'], $paramText."_$numParam", $paramUrl."_$numParam",$niveau+1);
				$flashString .= $tmp[0];
			}
		}
	}
	$flashString .= '&endOfData=1';
	return array($flashString,$numParam);
}

function getListPortails($idSite) {
	global $db;
	$return = array();
	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_parent_id in (select node_id
				 from cms_arbo_pages
				 where node_parent_id=0
				 and node_id<>0 AND id_site=$idSite) AND node_id_site=$idSite
	order by node_parent_id, node_libelle";
	if (DEF_BDD != "ORACLE") $sql.=";";	
	
	$rs = $db->Execute($sql);
	if($rs) {
		array_push($return, array(
			'id' => 0,
		 'parent_id' => 0,
		   'libelle' => 'Home',
		      'path' => '/'
		));
		while(!$rs->EOF) {
			$tmparray = array(
				       'id' => $rs->fields[n('node_id')],
				'parent_id' => $rs->fields[n('node_parent_id')],
				  'libelle' => $rs->fields[n('node_libelle')],
				     'path' => $rs->fields[n('node_absolute_path_name')],
			);
			array_push($return,$tmparray);
			$rs->MoveNext();
		}
	} else {
		echo "include/cms-inc/arbopage.lib.php > getListPortails";
		echo "<br />Erreur interne de programme";
		echo "<br /><strong>$sql</strong>";
		error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
		error_log($db->ErrorMsg());
		$return = false;
	}
	$rs->Close();
	return $return;
}

// Récupération des infos d'une page
// à partir de l'id_node et du nom de la page (index) sans le .php
function getPageByName($idSite, $id_node, $nompg) {
	global $db;
	$return=false;
	$sql=" select id_page, page_titre, page_motsclefs, page_description, name_page, nodeid_page
	from cms_page, cms_infos_pages
	where cms_page.nodeid_page=$id_node
	and cms_page.id_page=cms_infos_pages.page_id
	and cms_page.name_page='".$nompg."' AND id_site=$idSite";
	if (DEF_BDD != "ORACLE") $sql.=";";	
	$rs = $db->Execute($sql);
	if($rs) {
		$array = array();
		$array['id']=$rs->fields[n('id_page')];
		$array['name']=$rs->fields[n('name_page')];
		$array['libelle']=$rs->fields[n('node_libelle')];
		$array['node_id']=$rs->fields[n('nodeid_page')];
		$array['titre']=$rs->fields[n('page_titre')];
		$array['motsclefs']=$rs->fields[n('page_motsclefs')];
		$array['description']=$rs->fields[n('page_description')];
		$return = $array;
	} else {
		echo "include/cms-inc/arbopage.lib.php > getPageByName";
		echo "<br />Erreur interne de programme";
		echo "<br /><strong>$sql</strong>";
		error_log("Plantage lors de l'execution de la requete");
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------");
		error_log("$sql");
		error_log("--------------------------------------------");
	}
	$rs->Close();
	return $return;
}



// à partir de l'id_node et du nom de la page (index) sans le .php
function getLangue($idSite) {
	$oSite = new Cms_site ($idSite);
	$oLangue = new Cms_langue($oSite->get_langue());
	$site_langue=strtolower($oLangue->get_libellecourt());
	return $site_langue;
}


function getNodesByKeyword($idSite, $db, $keyword) {
	$node_id = array_pop(split(',',$path));
	$result = array();

	$sql = " SELECT node_id, node_parent_id, node_libelle , node_absolute_path_name, node_order, node_description";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_libelle LIKE '%".$keyword."%' ";
	$sql.= " AND node_id<>0";
	$sql.= " AND node_id_site=".$idSite;
	$sql.= " ORDER BY node_order";
	if (DEF_BDD != "ORACLE") $sql.=";";
//print("<br>$sql");

	$rs = $db->Execute($sql);
	if($rs==false) {

		echo "include/cms-inc/arbopage.lib.php > getNodeChildren";
		echo "<br />Erreur interne de programme";
		echo "<br /><strong>$sql</strong>";
		error_log(" plantage lors de l'execution de la requete ".$sql.' '.__FILE__.':'.__LINE__);
		error_log($db->ErrorMsg());
		$result = false;
	} else {
		if ($rs->EOF) {
			$result = array();
		} else {
			while(!$rs->EOF) {
				$tmparray = array(
					'id' => $rs->fields[n('node_id')],
					'parent' => $rs->fields[n('node_parent_id')],
					'libelle' => $rs->fields[n('node_libelle')],
					'path' => $rs->fields[n('node_absolute_path_name')],
					'order' => $rs->fields[n('node_order')],
					'description' => $rs->fields[n('node_description')]
				);
				array_push($result, $tmparray);
				$rs->MoveNext();
			}
		}
	}
	$rs->Close();
	return $result;
}
?>