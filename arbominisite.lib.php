<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* 

$Author: pierre $

$Revision: 1.4 $
$Log: arbominisite.lib.php,v $
Revision 1.4  2014-09-11 09:20:35  pierre
*** empty log message ***

Revision 1.3  2013-11-06 14:49:09  raphael
*** empty log message ***

Revision 1.2  2013-11-06 10:26:27  raphael
*** empty log message ***

Revision 1.1  2013-09-30 09:28:20  raphael
*** empty log message ***

Revision 1.29  2013-05-16 13:18:49  raphael
*** empty log message ***

Revision 1.28  2013-04-23 08:51:23  thao
*** empty log message ***

Revision 1.27  2013-03-01 10:33:58  pierre
*** empty log message ***

Revision 1.26  2012-05-07 13:45:42  pierre
*** empty log message ***

Revision 1.25  2012-04-03 11:20:55  thao
*** empty log message ***

Revision 1.24  2012-04-03 10:39:50  thao
*** empty log message ***

Revision 1.23  2011-08-17 10:52:54  pierre
*** empty log message ***

Revision 1.22  2011-07-05 07:53:09  pierre
*** empty log message ***

Revision 1.21  2011-07-04 13:42:24  pierre
*** empty log message ***

Revision 1.20  2011-07-04 13:40:32  pierre
*** empty log message ***

Revision 1.19  2011-06-30 14:30:45  pierre
*** empty log message ***

Revision 1.18  2011-06-30 14:28:47  pierre
*** empty log message ***

Revision 1.17  2011-03-08 10:48:50  pierre
*** empty log message ***

Revision 1.16  2010-11-15 09:45:15  pierre
addNode : support des tags html dans les noms de dossiers

Revision 1.15  2010-11-03 10:56:27  pierre
renommage : support des tags html dans les noms de dossiers

Revision 1.14  2010-01-08 11:30:22  pierre
*** empty log message ***

Revision 1.13  2009-09-16 10:16:43  pierre
*** empty log message ***

Revision 1.12  2009-07-24 13:45:40  pierre
*** empty log message ***

Revision 1.11  2009-03-17 16:20:17  thao
*** empty log message ***

Revision 1.10  2008-10-21 09:20:46  pierre
*** empty log message ***

Revision 1.8  2008/07/22 10:09:20  pierre
*** empty log message ***

Revision 1.7  2008/06/11 16:09:13  thao
*** empty log message ***

Revision 1.6  2008/05/23 08:08:09  pierre
*** empty log message ***

Revision 1.5  2007/11/29 17:06:12  pierre
*** empty log message ***

Revision 1.4  2007/11/29 16:48:50  pierre
*** empty log message ***

Revision 1.3  2007/08/08 14:14:23  thao
*** empty log message ***

Revision 1.2  2007/08/08 13:53:33  thao
*** empty log message ***

Revision 1.1  2007/08/08 13:07:18  thao
*** empty log message ***

Revision 1.6  2007/06/27 15:30:04  pierre
*** empty log message ***

Revision 1.5  2007/06/27 15:25:03  pierre
*** empty log message ***

Revision 1.4  2007/03/27 13:09:29  pierre
*** empty log message ***

Revision 1.2  2007/03/27 10:12:38  pierre
*** empty log message ***

Revision 1.3  2007/03/12 18:09:03  pierre
*** empty log message ***

Revision 1.3  2007/03/12 18:03:58  pierre
*** empty log message ***

Revision 1.2  2007/03/05 10:37:34  pierre
*** empty log message ***

Revision 1.1  2006/12/15 12:31:44  pierre
*** empty log message ***

Revision 1.1.1.1  2006/01/25 15:14:27  pierre
projet CCitron AWS 2006 Nouveau Website

Revision 1.9  2005/12/21 11:25:28  pierre
removeXitiForbiddenChars($strChars)

Revision 1.8  2005/11/04 10:56:06  sylvie
*** empty log message ***

Revision 1.7  2005/11/04 10:38:07  pierre
NEW RENAME

Revision 1.3  2005/11/04 08:37:02  sylvie
*** empty log message ***

Revision 1.2  2005/10/27 13:35:38  sylvie
*** empty log message ***

Revision 1.1.1.1  2005/10/24 13:37:05  pierre
re import fusion espace v2 et ADW v2

Revision 1.4  2005/10/24 07:17:33  pierre
*** empty log message ***

Revision 1.3  2005/10/21 13:08:07  pierre
suppression des refs à cms_arbo_composants

Revision 1.2  2005/10/21 08:44:21  sylvie
*** empty log message ***

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
function removeRecursDir($directory) {
function deleteNode_cms_arbo_pages($idSite, $db, $virtualPath, $parentVirtualPath, $node_id){
function deleteNode($idSite, $db, $virtualPath){
function addNode_cms_arbo_pages($idSite, $db, $virtualPath, $libelle, $node_id){
function addNode($idSite, $db, $virtualPath, $libelle){
function renameNode_cms_arbo_pages($idSite, $db, $virtualPath, $libelle, $node_id) {
function renameNode($idSite, $db, $virtualPath, $libelle){
function saveNodeDescription_cms_arbo_pages($idSite, $folderdescription, $db, $virtualPath, $node_id){
function saveNodeDescription($idSite, $folderdescription, $db, $virtualPath){
function getNodeInfos($db, $virtualPath){

function getNodeInfosReverse($idSite, $db, $absolutePath){
function path2nodes($idSite, $db, $absolutePath){
function path2nodesReverse($idSite, $db, $virtualPath) {
function drawCompTree($idSite, $db, $virtualPath, $full_path_to_curr_id=null, $destination=null, $paramSup="") {
function getAbsolutePathString($idSite, $db, $virtualPath,$destination=null) {
function getNodeChildren($idSite, $db, $path) {
function saveNodeOrder($idSite, $orders, $db, $path) {

function moveNode_cms_arbo_pages($idSite, $db, $virtualPath, $new_virtualPath)

function moveNode($idSite, $db, $virtualPath, $new_virtualPath) {
function getFolderPages($idSite, $path) {
function generateFlashArboMairie($idSite, $db,$path_entree=0,$paramText='linkText', $paramUrl='linkUrl') {
function generateFlashArboVousEtes($idSite, $db,$path_entree=0,$paramText='text', $paramUrl='link',$niveau=1) {
function getListPortails($idSite) {
function getPageByName($idSite, $id_node, $nompg) {


function getFolderComposants($idSite, $nodeId) {
*/

// sponthus 17/06/05
// arbo des mini sites
// duplication d'une arbo dans cms_arbo_pages 
if( !function_exists( "removeRecursDir" ) ){
function removeRecursDir($directory) {
	//$directory = preg_replace('/\'/','\\\'',$directory);
	//$directory = preg_replace('/\ /','\\\ ',$directory);
	$dossier = @opendir($directory);
	if ($dossier){
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
	else{
		return 1;	// si le dossier n'existe pas, on estime qu'il est détruit, donc retourne 1
	}
}
}

// delete node spécifique cms_arbo_pages
if( !function_exists( "deleteNode_cms_arbo_pages" ) ){
function deleteNode_cms_arbo_pages($idSite, $db, $virtualPath, $parentVirtualPath, $node_id){
	global $CMS_ROOT;

	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_id=$node_id";
	// une seule racine pour tous les arbres
	if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
        
        //pre_dump($sql);
        
	if (DEF_BDD != "ORACLE") $sql.=";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$selectedFolder = $rs->fields[n('node_absolute_path_name')];
		$n = removeRecursDir($CMS_ROOT.$selectedFolder);
		if ($n > 0) {
		
		
			dbArchiveArboObjects($node_id, true);
		
		

			$sql = " DELETE FROM cms_arbo_pages";
			$sql.= " WHERE node_id=$node_id";
			// une seule racine pour tous les arbres
			if ($node_id != 0) $sql.= " AND node_id_site=$idSite";

			if (DEF_BDD != "ORACLE") $sql.=";";	

			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

			$rs = $db->Execute($sql);
			if($rs!=false) {
				$result = $parentVirtualPath;
			} else {
				if(DEF_MODE_DEBUG==true) {
					echo "include/cms-inc/arbominisite.lib.php > deleteNode";
					echo "<br />Erreur interne de programme";
					echo "<br /><strong>$sql</strong>";
				}
				error_log(" plantage lors de l'execution de la requete ".$sql);
				error_log($db->ErrorMsg());
				$result = false;
			}
		} else {
			if(DEF_MODE_DEBUG==true) {
				echo "include/cms-inc/arbominisite.lib.php > deleteNode";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("Suppression du dossier $CMS_ROOT$selectedFolder impossible");
			$result = false;
		}
	} else {
		$result=false;
	}
	$rs->Close();
	return $result;
}
}

if( !function_exists( "dbArchiveArboObjects" ) ){
function dbArchiveArboObjects($idNode, $bDelete=false) {

	// cms_pages
	$sql = "SELECT * FROM `cms_page` where nodeid_page = ".$idNode.";";
	$aPages = dbGetObjectsFromRequete("Cms_page", $sql);
	
	foreach($aPages as $key => $page){
	
		$page->setNodeid_page(-1);
		$page->setToutenligne_page(0);
		$page->setExisteligne_page(0);
		
		dbUpdate ($page);
		  
		// contenus 		
		$aContent = getContentFromPage($page->get_id_page(), 0);
		
		if($aContent){
			for ($a=0; $a<sizeof($aContent); $a++){
				$oContent = $aContent[$a];				
				if (intval($oContent->get_isbriquedit_content())==1){
					$oContent->updateNoeud(-1);
				}
			}
		}  
	}
}
}


if( !function_exists( "deleteNode" ) ){
// supprimer un noeud
function deleteNode($idSite, $db, $virtualPath){
    //die(delete node);
	global $CMS_ROOT;

	if( ($virtualPath=='0') || (strlen($virtualPath)=='0'))
		return false;
	$array_path = split(',',$virtualPath);
	$node_id = array_pop($array_path);
	$result = false;
	$parentVirtualPath = join(',',$array_path);
	if($parentVirtualPath==0)
		$parentVirtualPath = 'Racine';
	$children=getNodeChildren($idSite, $db, $virtualPath);

	foreach($children as $k => $child) {
		if (deleteNode($idSite, $db, $virtualPath.','.$child['id'])==false) {
			error_log("Impossible de supprimer le dossier id=$child");
		}
	}

	// arbo pages
	$result_arbopages = deleteNode_cms_arbo_pages($idSite, $db, $virtualPath, $parentVirtualPath, $node_id);
	

	if ($result_arbopages) $result=true;
	else $result = false;

	return $result;
}
}

if( !function_exists( "addNode_cms_arbo_pages" ) ){
// ajoute un node spécifique cms_arbo_pages
function addNode_cms_arbo_pages($idSite, $db, $virtualPath, $libelle, $node_id, $eNode_id){
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
	if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
	
	if (DEF_BDD != "ORACLE") $sql.=";";	

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
				
	$rs = $db->Execute($sql);

	if($rs!=false && !$rs->EOF) {

		if(preg_match('/Racine/', $rs->fields[n('node_libelle')])) { // on est à la racine
			$path = '/'.$oSite->get_name().'/'.to_dbquote(removeForbiddenChars($libelle)).'/';
		}
		else {
			$path = $rs->fields[n('node_absolute_path_name')].to_dbquote(removeForbiddenChars($libelle)).'/';
		}

		$sql = " INSERT INTO cms_arbo_pages (node_id, node_parent_id, node_libelle, node_absolute_path_name, node_id_site)
		VALUES ($eNode_id, $node_id, '".to_dbquote($libelle)."', '".to_dbquote($path)."', $idSite)";
		if (DEF_BDD != "ORACLE") $sql.=";";	

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
		$rs = $db->Execute($sql);
		
		if($rs!=false) {
			$result = $eNode_id;

			if(DEF_MODE_DEBUG==true) error_log("TRACE :: MKDIR : ".$CMS_ROOT.stripslashes($path));
			
			//dirExists($CMS_ROOT);

			//if(!mkdir($CMS_ROOT.stripslashes($path))) {
			if(!dirExists($CMS_ROOT.stripslashes($path))) {

				if(DEF_MODE_DEBUG==true) error_log("TRACE :: MKDIR : ECHEC");

				// si echec de la creation du rep, on efface le rep en base...
				$sql = " DELETE FROM cms_arbo_pages WHERE node_id = $result";
				// une seule racine pour tous les arbres
				if ($node_id != 0) $sql.= " AND id_site=$idSite";
				if (DEF_BDD != "ORACLE") $sql.=";";
				$rs = $db->Execute($sql);

				if(DEF_MODE_DEBUG==true) {
					echo "include/cms-inc/arbominisite.lib.php > addNode_cms_arbo_pages";
					echo "<br />Erreur interne de programme";
					echo "<br /><strong>$sql</strong>";
				}
				error_log(" Erreur lors de la creation du repertoire $CMS_ROOT/$path");
				$result = false;
			} else {
				if(DEF_MODE_DEBUG==true) error_log("TRACE :: MKDIR : SUCCES");			
			}
		} else {
			if(DEF_MODE_DEBUG==true) {
				echo "<br />include/cms-inc/arbominisite.lib.php > addNode_cms_arbo_pages";
				echo "<br />Erreur de fonctionnement interne";
				echo "<br /><strong>$sql</strong>";
			}
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		}
	} else {
		$result=false;
	}
	$rs->Close();
	return $result;
}
}

if( !function_exists( "addNode" ) ){
// ajoute un noeud 
function addNode($idSite, $db, $virtualPath, $libelle){
	$node_id = array_pop(split(',',$virtualPath));
	$result = false;

	// créer des noeuds à partir de node_id=1000......

	// les 2 getNextVal ne renvoient pas le même node_id à créer
	// donc on commence arbitrairement à créer des noeus à partir de node_id=1000

	// noeud à créer identique pour les deux
	$eNode_id = getNextVal("cms_arbo_pages", "node_id");
	if ($eNode_id <= 1000) $eNode_id = 1000;

	// arbo pages
	$result_arbopages = addNode_cms_arbo_pages($idSite, $db, $virtualPath, $libelle, $node_id, $eNode_id);

	if ($result_arbopages) $result=$result_arbopages;
	else $result = false;

	return $result;
}
}

if( !function_exists( "renameNode_cms_arbo_pages" ) ){
// rename un noeud pour la table : cms_arbo_pages 
function renameNode_cms_arbo_pages($idSite, $db, $virtualPath, $libelle, $node_id, $isFirstNode=true) {

	global $CMS_ROOT;

	$oSite = new Cms_site($idSite);
	
	$result = false;
	$resultLog = "";
	// Les guillements font foirer l'enregistrement du répertoire sur le disque
	$libelle = str_replace('"',"'\\'", $libelle); // On remplace les guillemets par des doubles quotes
	$libelle = str_replace('`',"'", $libelle); // On remplace les quotes zarbi
	//$libelle = str_replace('/'," - ", $libelle); // On remplace les / par des tirets
	
	$sql = "select node_id, node_parent_id, node_libelle, node_absolute_path_name from cms_arbo_pages where node_id=$node_id;";
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		
		
		$path = $CMS_ROOT.$rs->fields[n('node_absolute_path_name')];
		$pathParent = substr($path, 0, strrpos(substr($path,0,-1), "/"));
		
		$relativepath=$rs->fields[n('node_absolute_path_name')];
		$relativepathParent = substr($relativepath, 0, strrpos(substr($relativepath,0,-1), "/"));
		
		if(file_exists($pathParent.stripslashes($libelle))) {// Si le nom existe déjà on fait rien
			//return false;
			echo "Le dossier existait déjà avec ce nom.\n<br /><br />";
		}
		$old_libelle = $rs->fields['node_libelle'];
		
		$oldPath = substr($path,0,-1);
		$newPath = $pathParent."/".stripslashes(removeForbiddenChars($libelle));
		
		
		$oldrelativePath = $relativepath; 
		$newrelativePath = $relativepathParent."/".stripslashes(removeForbiddenChars($libelle))."/";
		
		// filet de sécu
		if (($path == $_SERVER['DOCUMENT_ROOT'].'/content') or ($path == $_SERVER['DOCUMENT_ROOT'].'/content/')){
			echo "erreur critique<br />";
			echo $oldPath."\n<br />";		
			echo $newPath."\n<br />";
			return false;
		}
		
		$bFileSystemEdit = false;
		if (is_dir($oldPath)){ // si le dossier existe, on renomme
			$bFileSystemEdit = rename($oldPath,$newPath);
		}
		else{ // si le dossier n'existe pas, on crée
			$bFileSystemEdit = mkdir($newPath);
		}
		
		if(!$bFileSystemEdit){
		// Une erreur est survenue lors du renommage de larbo physique => on annule
			echo "erreur de renommage sur le file system.";
			return false;
		}
		else{ // si le renale est bien passé
			
			$newrelativepathSQL = to_dbquote($newrelativePath);
			$oldrelativepathSQL = to_dbquote($oldrelativePath);
			$libelleSQL = to_dbquote($libelle);
	
			$sql = "UPDATE cms_arbo_pages SET node_libelle='".$libelleSQL."',";
			$sql.= " node_absolute_path_name='".$newrelativepathSQL."'";
			$sql.= " WHERE node_id=$node_id;";

			$rs = $db->Execute($sql);
			
			$sql = "SELECT node_id, node_parent_id, node_libelle from cms_arbo_pages where node_id=$node_id;";
	
			$rs = $db->Execute($sql);
	
			if($rs!=false && !$rs->EOF) {
	
				// Il faut répercuter cette modif sur tous les node_absolute_path_name enfants de la base
				$sql = " UPDATE cms_arbo_pages ";

				$sql.= " SET node_absolute_path_name=(".dbConcat("'".$newrelativepathSQL."'", "substring(node_absolute_path_name from (length( '".$oldrelativepathSQL."' )+1))")." )";

				$sql.= " WHERE node_absolute_path_name like '".$oldrelativepathSQL."%';";
	
				$rs = $db->Execute($sql);
				if($rs!=false) {
					$result = true;
					$resultLog .= "Renommage réussi<br /><br />\n";
				}
				// Il faut répercuter cette modif sur les briques
				$sql = " SELECT id_content, html_content, name_content FROM cms_content WHERE html_content LIKE '%".$oldrelativepathSQL."%'; ";
				$rs = $db->Execute($sql);
				
				if($rs!=false) {
					$resultLog .= "Contrôle des liens des briques<br />\n";
					while(!$rs->EOF) {
						$tempNewHMTML = str_replace($oldrelativepathSQL, $newrelativepathSQL, $rs->fields['html_content']);
						$upSql = "UPDATE cms_content SET html_content = '".to_dbquote($tempNewHMTML)."' WHERE id_content LIKE ".$rs->fields['id_content'].";";
						$upRs = $db->Execute($upSql);

						if($upRs!=false) {
							$result = true;
							$resultLog .= "- les liens de la brique '".$rs->fields['name_content']."' (".$rs->fields['id_content'].") ont été corrigés<br />\n";
						}
						$rs->MoveNext();
					}
				}
				else{
					$resultLog .= "Pas de briques corrigées suite au renommage<br />\n";
				}
				$resultLog .= "Fin du contrôle des liens des briques<br /><br />\n";
				//----------------------------------------------------------------
				// Il faut répercuter cette modif sur les briques archi
				$sql = " SELECT id_content_archi, html_archi, id_archi FROM cms_archi_content WHERE html_archi LIKE '%".$oldrelativepathSQL."%'; ";
				$rs = $db->Execute($sql);
				
				if($rs!=false) {
					$resultLog .= "Contrôle des liens des briques archivées<br />\n";
					while(!$rs->EOF) {
						$tempNewHMTML = str_replace($oldrelativepathSQL, $newrelativepathSQL, $rs->fields['html_content']);
						$upSql = "UPDATE cms_archi_content SET html_archi = '".to_dbquote($tempNewHMTML)."' WHERE id_archi LIKE ".$rs->fields['id_archi'].";";
						$upRs = $db->Execute($upSql);
						
						if($upRs!=false) {
							$result = true;
							$resultLog .= "- Les liens de la brique archivée '".$rs->fields['id_content_archi']."' (".$rs->fields['id_archi'].") ont été corrigés<br />\n";
						}
						$rs->MoveNext();
					}
				}
				else{
					$resultLog .= "Pas de briques archivées corrigées suite au renommage<br />\n";
				}
				$resultLog .= "Fin du contrôle des liens des briques archivées<br /><br />\n";
				//----------------------------------------------------------------
				// Il faut répercuter cette modif sur les pages
				$sql = " SELECT id_page, html_page, name_page FROM cms_page WHERE html_page LIKE '%".$oldrelativepathSQL."%'; ";
				$rs = $db->Execute($sql);
				
				if($rs!=false) {
					$resultLog .= "Contrôle des liens des pages<br />\n";
					while(!$rs->EOF) {
						$tempNewHMTML = str_replace($oldrelativepathSQL, $newrelativepathSQL, $rs->fields['html_content']);
						$upSql = "UPDATE cms_page SET html_page = '".to_dbquote($tempNewHMTML)."' WHERE id_page LIKE ".$rs->fields['id_page'].";";
						$upRs = $db->Execute($upSql);

						if($upRs!=false) {
							$result = true;
							$resultLog .= "- Les liens de la page '".$rs->fields['name_page']."' (".$rs->fields['id_page'].") ont été corrigés<br />\n";
						}
						$rs->MoveNext();
					}
				}
				else{
					$resultLog .= "Pas de pages corrigées suite au renommage<br />\n";
				}
				$resultLog .= "Fin du contrôle des liens des pages<br /><br />\n";
				//----------------------------------------------------------------
				
				$result = true;
			} else {
				if (DEF_MODE_DEBUG == true) {
					echo "<br />------------";
					echo "<br />*** renameNode($db,$virtualPath,$libelle)";
					echo "<br />".$sql;
					echo "<br />".$db->ErrorMsg();
					echo "<br />------------";
				}
				error_log(" plantage lors de l'execution de la requete ".$sql);
				error_log($db->ErrorMsg());
				$result = false;
			}
		}// fin if du rename physique
	} else {
		$result=false;
	}
	//conformeLinks($db);
	
	$aResult = array($result, $resultLog);
	$rs->Close();
	return $aResult;
}
}

if( !function_exists( "renameNode" ) ){
// rename un noeud 
function renameNode($idSite, $db, $virtualPath, $libelle){ 
	$node_id = array_pop(split(',',$virtualPath));
	
	$result = false;

	// arbo pages
	$result_arbopages = renameNode_cms_arbo_pages($idSite, $db, $virtualPath, $libelle, $node_id);

	if ($result_arbopages[0]) $result=$result_arbopages[0];
	else $result = false;

	$aResult = array($result_arbopages[0], $result_arbopages[1]);
	return $aResult;
}
}

if( !function_exists( "saveNodeDescription_cms_arbo_pages" ) ){

// saveNodeDescription pour la table : cms_arbo_pages
function saveNodeDescription_cms_arbo_pages($idSite, $folderdescription, $db, $virtualPath, $node_id){
	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_id=$node_id";
	// une seule racine pour tous les arbres
	if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
	if (DEF_BDD != "ORACLE") $sql.=";";		

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {

		$sql = " UPDATE cms_arbo_pages set node_description='".addslashes($folderdescription)."'";
		$sql.= " WHERE node_id=$node_id";
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
		
		if (DEF_BDD != "ORACLE") $sql.=";";
		$rs = $db->Execute($sql);

		$sql = " SELECT node_id, node_parent_id, node_libelle";
		$sql.= " FROM cms_arbo_pages WHERE node_id=$node_id";
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
		if (DEF_BDD != "ORACLE") $sql.=";";
	 
		//echo $sql;
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$rs = $db->Execute($sql);
		
		if($rs!=false && !$rs->EOF) {
			$result = true;
		} else {
			if(DEF_MODE_DEBUG==true) {
				echo "include/cms-inc/arbominisite.lib.php > saveNodeDescription_cms_arbo_pages";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>$sql</strong>";
			}
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		}
	} else {
		$result=false;
	}
	$rs->Close();
	return $result;
}
}

if( !function_exists( "saveNodeDescription" ) ){

// saveNodeDescription 
function saveNodeDescription($idSite, $folderdescription, $db, $virtualPath){
	$node_id = array_pop(split(',', $virtualPath));
	$result = false;

	// arbo pages
	$result_arbopages = saveNodeDescription_cms_arbo_pages($idSite, $folderdescription, $db, $virtualPath, $node_id);
	
	if ($result_arbopages) $result = true;
	else $result = false;

	return $result;
}
}

if( !function_exists( "saveNodeTag_cms_arbo_pages" ) ){
// saveNodeTag pour la table : cms_arbo_pages
function saveNodeTag_cms_arbo_pages($idSite, $foldertag, $db, $virtualPath, $node_id){
	$sql = " SELECT node_id, node_parent_id, node_libelle, node_absolute_path_name";
	$sql.= " FROM cms_arbo_pages";
	$sql.= " WHERE node_id=$node_id";
	// une seule racine pour tous les arbres
	if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
	if (DEF_BDD != "ORACLE") $sql.=";";		

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {

		$sql = " UPDATE cms_arbo_pages set node_tag='".addslashes($foldertag)."'";
		$sql.= " WHERE node_id=$node_id";
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND node_id_site=$idSite";
		
		if (DEF_BDD != "ORACLE") $sql.=";";
		$rs = $db->Execute($sql);

		$sql = " SELECT node_id, node_parent_id, node_libelle";
		$sql.= " FROM cms_arbo_pages WHERE node_id=$node_id";
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND id_site=$idSite";
		if (DEF_BDD != "ORACLE") $sql.=";";
	 
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$rs = $db->Execute($sql);
		
		if($rs!=false && !$rs->EOF) {
			$result = true;
		} else {
			if(DEF_MODE_DEBUG==true) {
				echo "include/cms-inc/arbominisite.lib.php > saveNodeTag_cms_arbo_pages";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>$sql</strong>";
			}
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		}
	} else {
		$result=false;
	}
	$rs->Close();
	return $result;
}
}

if( !function_exists( "saveNodeTag" ) ){

// saveNodeTag 
function saveNodeTag($idSite, $foldertag, $db, $virtualPath){
	$node_id = array_pop(split(',', $virtualPath));
	$result = false;

	// arbo pages
	$result_arbopages = saveNodeTag_cms_arbo_pages($idSite, $foldertag, $db, $virtualPath, $node_id);
	
	if ($result_arbopages) $result = true;
	else $result = false;

	return $result;
}
}

if( !function_exists( "getNodeInfos" ) ){
// gestNodeInfos 
// on travaille par défaut sur la table cms_arbo_pages
function getNodeInfos($db, $virtualPath){
	$node_id = array_pop(split(',',$virtualPath));
	if (trim($node_id)==''){
		error_log('getNodeInfos('.$virtualPath.') appel incorrect '.__FILE__.':'.__LINE__);
		return false;
	}
	
	$result = null;

	$sql = " SELECT * FROM cms_arbo_pages WHERE node_id=$node_id";
	if (DEF_BDD != "ORACLE") $sql.=";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$result = array(
			'id' => $rs->fields[n('node_id')],
			'libelle' => $rs->fields[n('node_libelle')],
			'parent' => $rs->fields[n('node_parent_id')],
			'path' => $rs->fields[n('node_absolute_path_name')],
			'description' => $rs->fields[n('node_description')],
			'tag' => $rs->fields[n('node_tag')]
		);
	} else {
			if(DEF_MODE_DEBUG==true) {
				echo "include/cms-inc/arbominisite.lib.php > getNodeInfos";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>$sql</strong>";
			}
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		
	}
	$rs->Close();
	return $result;
}
}

if( !function_exists( "getNodeInfosReverse" ) ){
function getNodeInfosReverse($idSite, $db, $absolutePath){
	$absolutePath = str_replace("/content", "", $absolutePath);
	$absolutePath = rawurldecode(substr ($absolutePath, 0, strrpos ($absolutePath, "/") + 1));
	$result = null;

	// la racine est la même pour tous
	// donc si le chemin est la racine, ajouter le site dans le chemin
	$oSite = new Cms_site($idSite);
	if ($absolutePath == "/".$oSite->get_rep()."/") $absolutePath = "/";

	$sql = " SELECT * FROM cms_arbo_pages node_absolute_path_name='".addslashes($absolutePath)."' AND node_id_site=$idSite";
	if (DEF_BDD != "ORACLE") $sql.=";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

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
	} else {
			if(DEF_MODE_DEBUG==true) {
				echo "include/cms-inc/arbominisite.lib.php > getNodeInfosReverse";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>$sql</strong>";
			}
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;		
	}
	$rs->Close();
	return $result;
}
}

if( !function_exists( "path2nodes" ) ){
function path2nodes($idSite, $db, $absolutePath){
	$entree = getNodeInfosReverse($db,$absolutePath);
	$nodeItems = array();
	$nodeStr = $entree['id'];
	array_push($nodeItems, $nodeStr);
	// si le node parent n'est pas 0 (root) on recherche encore
	while($entree['parent'] != 0){
		$entree = getNodeInfos($db,$entree['parent']);
		$nodeStr = $entree['id'];
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

if( !function_exists( "path2nodesReverse" ) ){
// path2nodesReverse 
function path2nodesReverse($idSite, $db, $virtualPath) {
// Reconstitution d'un absolutePath à partir d'un virtualPath
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// Retourne l'absolutePath

	$oSite = new Cms_site($idSite);

	$strPath = '/'.$oSite->get_name().'/';
	foreach(split(',',$virtualPath) as $id){
		if ($id!="0") {
			$sql = " SELECT node_libelle FROM cms_arbo_pages WHERE node_id=$id";
			// une seule racine pour tous les arbres
			if ($node_id != 0) $sql.= " AND id_site=$idSite";
			if (DEF_BDD != "ORACLE") $sql.=";";

			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
			$rs = $db->Execute($sql);
			if($rs!=false && !$rs->EOF) {
				$strPath.=  $rs->fields[n('node_libelle')].'/';
			} else {
				if(DEF_MODE_DEBUG==true) {
					echo "include/cms-inc/arbominisite.lib.php > path2nodesReverse";
					echo "<br />Erreur interne de programme";
					echo "<br /><strong>$sql</strong>";
				}
				error_log(" plantage lors de l'execution de la requete ".$sql);
				error_log($db->ErrorMsg());
				$strPath.='??????';
			}	
			$rs->Close();
		}
	}

	return $strPath;
}
}

if( !function_exists( "drawCompTree" ) ){
// drawCompTree 
function drawCompTree($idSite, $db, $virtualPath, $full_path_to_curr_id=null, $destination=null, $paramSup="") {

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
			$strHTML .= "<a class=\"arbo\" href=\"".$destination.$OP."idSite=".$idSite."".$paramSup."&v_comp_path=0\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><b>Racine</b></a><br/></td></tr><tr><td>\n";

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
			$strHTML .= "<span>$indent<a href=\"".$destination.$OP."idSite=".$idSite."".$paramSup."&v_comp_path=$full_path_to_curr_id,$id\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier.png\"><small>".strip_tags($libelle, '<br><sup><ub>')."</small></a><br/></span>\n";
		} else {
			//dossier ouvert
			if(array_pop(split(',',$virtualPath))==$id)
				$strHTML .= "<span>$indent<a class=\"arbo\" href=\"".$destination."?idSite=".$idSite."".$paramSup."&v_comp_path=$full_path_to_curr_id,$id\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><small><span class=\"arbo\">".strip_tags($libelle, '<br><sup><ub>')."</span></small></a><br/></span>\n";
			else
				$strHTML .= "<span>$indent<a href=\"".$destination."?idSite=".$idSite."".$paramSup."&v_comp_path=$full_path_to_curr_id,$id\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><small>".strip_tags($libelle, '<br><sup><ub>')."</small></a><br/></span>\n";
			$strHTML.=drawCompTree($idSite, $db,$virtualPath,$full_path_to_curr_id.','.$id,$destination, $paramSup);
		}
	}
	return $strHTML;
}
}

if( !function_exists( "getAbsolutePathString" ) ){

function getAbsolutePathString($idSite, $db, $virtualPath, $destination=null) {
	if($destination==null)
		$destination=$_SERVER['PHP_SELF'];
	$OP = '?';
	if(preg_match('/\?/',$destination))
		$OP = '&';
	$strPath = '<a href="'.$destination.$OP.'idSite='.$idSite.'&v_comp_path=0" class="arbo"><b>Racine</b></a>';
	$localPath='0';
	foreach(split(',',$virtualPath) as $id){
		if ($id!="0") {
			$localPath.=",$id";

			$sql = " SELECT node_libelle FROM cms_arbo_pages WHERE node_id=$id";
			// une seule racine pour tous les arbres
			if ($node_id != 0) $sql.= " AND id_site=$idSite";
			if (DEF_BDD != "ORACLE") $sql.=";";

//print("<br />$sql");

			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
			$rs = $db->Execute($sql);

			if($rs!=false && !$rs->EOF) {
				$strPath.='&nbsp;&nbsp;>&nbsp;&nbsp;<a href="'.$destination.$OP.'idSite='.$idSite.'&v_comp_path='.$localPath.'" class="arbo">'.$rs->fields[n('node_libelle')].'</a>';
			} else {
				if(DEF_MODE_DEBUG==true) {
					echo "include/cms-inc/arbominisite.lib.php > getAbsolutePathString($idSite, $db, $virtualPath, $destination)";
					echo "<br />Erreur interne de programme";
					echo "<br /><strong>$sql</strong>";
				}
				error_log(" plantage lors de l'execution de la requete ".$sql);
				error_log($db->ErrorMsg());
				$strPath.='&nbsp;&nbsp;>&nbsp;&nbsp;??????';
			}
			$rs->Close();
		}
	}
	
	return $strPath;
}
}


if( !function_exists( "getNodeChildren" ) ){
function getNodeChildren($idSite, $db, $path) {
	if (($idSite == "") || !isset($idSite)){
		$idSite = 1;
	}
	$node_id = array_pop(split(',',$path));
	$result = array();

	$sql = " SELECT * FROM cms_arbo_pages";
	$sql.= " WHERE node_parent_id=$node_id";
	$sql.= " AND node_id<>0";
	$sql.= " AND node_id_site=".$idSite;
	$sql.= " ORDER BY node_order, node_libelle";
	if (DEF_BDD != "ORACLE") $sql.=";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	if($rs==false) {

		if(DEF_MODE_DEBUG==true) {

			echo "include/cms-inc/arbominisite.lib.php > getNodeChildren";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$result = false;
	} else {
		if ($rs->EOF) {
			$result = array();
		} else {
			while(!$rs->EOF) {
				$tmparray = array(
					'id' => $rs->fields[n('node_id')],
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
}


if( !function_exists( "saveNodeOrder" ) ){
function saveNodeOrder($idSite, $orders, $db, $path) {
	$result = true;
	$sql="";
	foreach ($orders as $id => $ordre)
	{
		if($ordre=="") $ordre = 100;

		$sql = " UPDATE cms_arbo_pages SET node_order=$ordre WHERE node_id=$id";
		// une seule racine pour tous les arbres
		if ($node_id != 0) $sql.= " AND id_site=$idSite";
		if (DEF_BDD != "ORACLE") $sql.=";";

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$rs = $db->Execute($sql);
		if($rs==false) {
			if(DEF_MODE_DEBUG==true) {
				echo "include/cms-inc/arbominisite.lib.php > saveNodeOrder";
				echo "<br />Erreur interne de programme";
				echo "<br /><strong>$sql</strong>";
			}
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		}
	}
	$rs->Close();
	return $result;
}
}


if( !function_exists( "moveNode_cms_arbo_pages" ) ){
// moveNode pour 1 table : cms_arbo pages
function moveNode_cms_arbo_pages($idSite, $db, $virtualPath, $new_virtualPath)
{
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
	if (DEF_BDD != "ORACLE") $sql.=";";

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

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
		if(DEF_MODE_DEBUG==true) {
			echo "<br />include/cms-inc/arbominisite.lib.php > moveNode";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$result = false;
	}
	$rs->Close();
	return $result;
}
}

if( !function_exists( "moveNode" ) ){
// moveNode 
function moveNode($idSite, $db, $virtualPath, $new_virtualPath) {
	// arbo pages
	$result = moveNode_cms_arbo_pages($idSite, $db, $virtualPath, $new_virtualPath);

	if ($result_arbopages && $result_arbocomposants) $result = true;
	else $result = false;

	return $result;
}
}


if( !function_exists( "getFolderPages" ) ){
function getFolderPages($idSite, $path) {
	global $db;
	$node_id = array_pop(split(',',$path));
	$return = array();

	if (DEF_BDD != "ORACLE") {
		$sql = " SELECT id_page, name_page, gabarit_page, cast(dateadd_page as date) as dateadd_page, ";
		$sql.= " cast(coalesce(dateupd_page, dateadd_page) as date) as dateupd_page, ";
		$sql.= " datedlt_page, cast(datemep_page as date) as datemep_page, isgenerated_page, ";
		$sql.= " valid_page, nodeid_page, options_page, id_site";
		$sql.= " FROM cms_page";
		$sql.= " WHERE nodeid_page=$node_id";
		$sql.= " AND datedlt_page is null";
		$sql.= " AND valid_page=1 AND id_site=$idSite";
		$sql.= " ORDER BY name_page ASC;";
	} else {
		$sql = " SELECT id_page, name_page, gabarit_page, cast(dateadd_page as date) as dateadd_page, ";
		$sql.= " cast(coalesce(dateupd_page, dateadd_page) as date) as dateupd_page, ";
		$sql.= " datedlt_page, cast(datemep_page as date) as datemep_page, isgenerated_page, ";
		$sql.= " valid_page, nodeid_page, options_page, id_site";
		$sql.= " FROM cms_page";
		$sql.= " WHERE nodeid_page=$node_id";
		$sql.= " AND datedlt_page is null";
		$sql.= " AND valid_page=1 AND id_site=$idSite";
		$sql.= " ORDER BY name_page ASC";		
	}

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);

	if($rs==false) {
		if(DEF_MODE_DEBUG==true) {
			echo "include/cms-inc/arbominisite.lib.php > getFolderPages";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$return = false;
	} else {
		while(!$rs->EOF) {
			$modif = date('d/m/Y',strtotime($rs->fields[n('dateupd_page')]));
			if ($modif == date('d/m/Y',strtotime($rs->fields[n('dateadd_page')])))
				$modif = '---';
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
}


if( !function_exists( "generateFlashArboMairie" ) ){
function generateFlashArboMairie($idSite, $db,$path_entree=0,$paramText='linkText', $paramUrl='linkUrl') {
	$flashString = '';
	$numParam = 0;
	$children = getNodeChildren($idSite, $db, $path_entree);
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
}


if( !function_exists( "generateFlashArboVousEtes" ) ){
function generateFlashArboVousEtes($idSite, $db,$path_entree=0,$paramText='text', $paramUrl='link',$niveau=1) {
	$flashString = '';
	$numParam = 0;
	$children = getNodeChildren($idSite, $db, $path_entree);
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
}

if( !function_exists( "getListPortails" ) ){
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

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
	
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
		if(DEF_MODE_DEBUG==true) {
			echo "include/cms-inc/arbominisite.lib.php > getListPortails";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
		}
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$return = false;
	}
	$rs->Close();
	return $return;
}
}

if( !function_exists( "getPageByName" ) ){
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

	if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	$rs = $db->Execute($sql);
	if($rs) {
		$array = array();
		$array['name']=$rs->fields[n('name_page')];
		$array['libelle']=$rs->fields[n('node_libelle')];
		$array['node_id']=$rs->fields[n('nodeid_page')];
		$array['titre']=$rs->fields[n('page_titre')];
		$array['motsclefs']=$rs->fields[n('page_motsclefs')];
		$array['description']=$rs->fields[n('page_description')];
		$return = $array;
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "include/cms-inc/arbominisite.lib.php > getPageByName";
			echo "<br />Erreur interne de programme";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Plantage lors de l'execution de la requete");
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------");
		error_log("$sql");
		error_log("--------------------------------------------");
	}
	$rs->Close();
	return $return;
}
}

///////////////// COMPOSANTS ///////////////////
if( !function_exists( "getFolderComposants" ) ){
// Vient de arbo.lib.php
// Liste des composants (briques) d'un dossier (noeud)
// on ne renvoi pas les zones éditables et les briques éditables
function getFolderComposants($idSite, $nodeId) {

	if(strlen($nodeId)>0)
			$nodeId=array_pop(split(',',$nodeId));
	else
			return false;
	global $db;
	$return = array();

	if (DEF_BDD == "POSTGRES") {
		$sql = " SELECT id_content, name_content, type_content, width_content, height_content, id_site";
		$sql.= " FROM cms_content
		where nodeid_content=$nodeId
		and valid_content='t'
		and actif_content='t'
		and id_site=$idSite
		and isbriquedit_content <> 1
		and iszonedit_content <> 1
		order by type_content, name_content;";
	} else {
		$sql = " SELECT id_content, name_content, type_content, width_content, height_content, id_site
		FROM cms_content
		WHERE nodeid_content=$nodeId
		AND valid_content = 1
		AND actif_content = 1
		AND id_site=$idSite 
		AND isbriquedit_content <> 1
		AND iszonedit_content <> 1
		ORDER BY type_content, name_content";
	}// AND isbriquedit_content = 0
	
	$rs = $db->Execute($sql);
	if($rs) {
			if(!$rs->EOF) {
					while (!$rs->EOF) {
							$tmparray = array(
									'id' => $rs->fields[n('id_content')],
									'name' => $rs->fields[n('name_content')],
									'type' => $rs->fields[n('type_content')],
									'width' => $rs->fields[n('width_content')],
									'height' => $rs->fields[n('height_content')],
									'id_site' => $rs->fields[n('id_site')],
							);
							array_push($return, $tmparray);
							$rs->MoveNext();
					}
			} else {
					$return=false;
			}
	} else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "<br />/include/cms-inc/arbominisite.lib.php > getFolderComposants";
				print("<br /><strong>$sql</strong>");
			}			
			error_log("Plantage lors de l'execution de la requete\n $sql");
			error_log($db->ErrorMsg());
			$return = false;
	}
	$rs->Close();
	return $return;
}
}

?>