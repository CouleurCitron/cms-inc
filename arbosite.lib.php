<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* 
$Author: raphael $
$Revision: 1.3 $

$Log: arbosite.lib.php,v $
Revision 1.3  2013-11-06 14:49:09  raphael
*** empty log message ***

Revision 1.2  2013-11-06 10:26:27  raphael
*** empty log message ***

Revision 1.1  2013-09-30 09:28:21  raphael
*** empty log message ***

Revision 1.8  2013-03-01 10:33:58  pierre
*** empty log message ***

Revision 1.7  2008-10-21 09:20:46  pierre
*** empty log message ***

Revision 1.5  2008-07-25 17:56:42  pierre
*** empty log message ***

Revision 1.4  2008-07-25 17:55:41  pierre
*** empty log message ***

Revision 1.3  2007/11/29 16:48:50  pierre
*** empty log message ***

Revision 1.2  2007/08/08 14:14:23  thao
*** empty log message ***

Revision 1.1  2007/08/08 13:07:18  thao
*** empty log message ***

Revision 1.1.1.1  2006/01/25 15:14:27  pierre
projet CCitron AWS 2006 Nouveau Website

Revision 1.2  2005/10/28 07:53:14  sylvie
*** empty log message ***

Revision 1.1.1.1  2005/10/20 13:10:54  pierre
Espace V2

Revision 1.1.1.1  2005/04/18 13:53:29  pierre
again

Revision 1.1.1.1  2005/04/18 09:04:21  pierre
oremip new

Revision 1.1.1.1  2004/11/03 13:49:54  ddinside
lancement du projet - import de adequat

Revision 1.3  2004/06/16 15:23:19  ddinside
inclusion corrections

Revision 1.2  2004/04/26 08:07:09  melanie
*** empty log message ***

Revision 1.1.1.1  2004/04/01 09:20:29  ddinside
Création du projet CMS Couleur Citron nom de code : tipunch

Revision 1.2  2004/02/05 15:56:26  ddinside
ajout fonctionnalite de suppression de pages
ajout des styles dans spaw
debuggauge prob du nom de fichier limite à 30 caracteres

Revision 1.1  2004/01/20 15:16:38  ddinside
mise à jour de plein de choses
ajout de gabarit vie des quartiers
eclatement gabarits par des includes pour contourner prob des flashs non finalisés

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

function deleteNode($db,$virtualPath){
	$array_path = explode(',',$virtualPath);
	$node_id = array_pop($array_path);
	$result = false;
	$parentVirtualPath = implode(',',$array_path);
	$children=getNodeChildren($db,$virtualPath);
	foreach($children as $k => $child) {
		if (deleteNode($db, $virtualPath.','.$child['id'])==false) {
			error_log("Impossible de supprimer le dossier id=$child");
		}
	}
	$sql = "select node_id, node_parent_id, node_libelle from cms_arbo_site where node_id=$node_id";
	if (DEF_BDD != "ORACLE") $sql.=";";
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$sql="delete from cms_arbo_site
		 where node_id=$node_id";
		if (DEF_BDD != "ORACLE") $sql.=";";
		$rs = $db->Execute($sql);
		if($rs!=false) {
			$result = $parentVirtualPath;
		} else {
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

function addNode($db,$virtualPath,$libelle,$urlHome){
	$node_id = array_pop(explode(',',$virtualPath));
	$result = false;

	$sql = " select node_id, node_parent_id, node_libelle from cms_arbo_site where node_id=$node_id";
	if (DEF_BDD != "ORACLE") $sql.=";";	
	$rs = $db->Execute($sql);

	if($rs!=false && !$rs->EOF) {

		$eNextVal = getNextVal("cms_arbo_site", "node_id");
		
		$sql = " INSERT INTO cms_arbo_site (node_id, node_parent_id, node_libelle, url_home_categorie) VALUES
		( $eNextVal, $node_id, '$libelle','$urlHome')";
		if (DEF_BDD != "ORACLE") $sql.=";";	
		$rs = $db->Execute($sql);
		
		if($rs!=false) {
			$result = true;
		} else {
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

function renameNode($db,$virtualPath,$libelle){
	$node_id = array_pop(explode(',',$virtualPath));
	$result = false;
	$sql = "select node_id, node_parent_id, node_libelle from cms_arbo_site where node_id=$node_id";
	if (DEF_BDD != "ORACLE") $sql.=";";	
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$sql="update cms_arbo_site set node_libelle='$libelle' where node_id=$node_id;
		select node_id, node_parent_id, node_libelle from cms_arbo_site where node_id=$node_id;";
		$rs = $db->Execute($sql);
		if($rs!=false && !$rs->EOF) {
			$result = true;
		} else {
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

function savehome($db,$virtualPath,$url) {
	$node_id = array_pop(explode(',',$virtualPath));
	$result = null;
	$sql = "update cms_arbo_site 
	set url_home_categorie='$url'
	where node_id=$node_id;";
	$rs = $db->Execute($sql);
	if($rs) {
		$result =true;
	} else {
		$result =false;
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
	}
	$rs->Close();
	return $return;
}

function getNodeInfos($db,$virtualPath){
	$node_id = array_pop(explode(',',$virtualPath));
	$result = null;
	$sql = "select node_id, node_parent_id, node_libelle, url_home_categorie from cms_arbo_site where node_id=$node_id";
	if (DEF_BDD != "ORACLE") $sql.=";";	
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$result = array(
			'id' => $rs->fields[n('node_id')],
			'libelle' => $rs->fields[n('node_libelle')],
			'parent' => $rs->fields[n('node_parent_id')],
			'home' => $rs->fields[n('url_home_categorie')]
		);
	}
	$rs->Close();
	return $result;
}

function drawCompTree($db,$virtualPath,$full_path_to_curr_id=null,$destination=null) {
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
		$strHTML .= "<a href=\"".$destination."\" class=\"arbo\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><b>Racine</b></a><br/></td></tr><tr><td>\n";
		
	} else {
		$tree_depth = sizeof(explode(',',$full_path_to_curr_id));
	}
	$children = getNodeChildren($db,$full_path_to_curr_id);
	//indentation :
	$indent='';
	for($i=0;$i<$tree_depth;$i++){
		$indent.=$spacerStr;
	}
	foreach ($children as $k=>$v) {
		$id = $v['id'];
		$libelle = $v['libelle'];
		//debut de ligne...
		if (!in_array($id,explode(',',$virtualPath))) {
			//dossier ferme
			$strHTML .= "$indent<a href=\"".$destination.$OP."v_comp_path=$full_path_to_curr_id,$id\" class=\"arbo\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier.png\">".strip_tags($libelle, '<br><sup><ub>')."</a><br/>\n";
		} else {
			//dossier ouvert
			if(array_pop(explode(',',$virtualPath))==$id)
				$strHTML .= "$indent<a class=\"arbo\" href=\"".$destination."?v_comp_path=$full_path_to_curr_id,$id\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><span class=\"arbo\">".strip_tags($libelle, '<br><sup><ub>')."</span></a><br/>\n";
			else
				$strHTML .= "$indent<a class=\"arbo\" href=\"".$destination."?v_comp_path=$full_path_to_curr_id,$id\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\">".strip_tags($libelle, '<br><sup><ub>')."</a><br/>\n";
			$strHTML.=drawCompTree($db,$virtualPath,$full_path_to_curr_id.','.$id,$destination);
		}
	}
	return $strHTML;
}


function getAbsolutePathString($db, $virtualPath,$destination=null) {
	if($destination==null)
		$destination=$_SERVER['PHP_SELF'];
	$OP = '?';
	if(preg_match('/\?/',$destination))
		$OP = '&';
	$strPath = '<a href="'.$destination.'" class="arbo"><b>Racine</b></a>';
	$localPath='0';
	foreach(explode(',',$virtualPath) as $id){
		if ($id!="0") {
			$localPath.=",$id";
			$sql = "select node_libelle from cms_arbo_site where node_id=$id";
			if (DEF_BDD != "ORACLE") $sql.=";";			
			$rs = $db->Execute($sql);
			if($rs!=false && !$rs->EOF) {
				$strPath.='&nbsp;&nbsp;>&nbsp;&nbsp;<a href="'.$destination.$OP.'v_comp_path='.$localPath.'" class="arbo">'.$rs->fields[n('node_libelle')].'</a>';
			} else {
				error_log(" plantage lors de l'execution de la requete ".$sql);
				error_log($db->ErrorMsg());
				$strPath.='&nbsp;&nbsp;>&nbsp;&nbsp;??????';
			}
			$rs->Close();
		}
	}
	return $strPath;
}

function getNodeChildren($db,$path) {
	$node_id = array_pop(explode(',',$path));
	$result = array();
	$sql = "select node_id, node_libelle from cms_arbo_site
		where node_parent_id=$node_id
		and node_id<>0
		order by node_libelle;";
	$rs = $db->Execute($sql);
	if($rs==false) {
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
					'libelle' => $rs->fields[n('node_libelle')]
				);
				array_push($result, $tmparray);
				$rs->MoveNext();
			}
		}
	}
	$rs->Close();
	return $result;
}
