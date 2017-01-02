<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
//echo "on passe dans arbocart carte";
// A VIRER //
if(!preg_match('/chooseFolder/',$_SERVER['PHP_SELF'])&&!preg_match('/show(picto|arbo)*Carte/',$_SERVER['PHP_SELF'])&&!preg_match('/cartes/',$_SERVER['PHP_SELF'])&&!preg_match('/frontoffice/',$_SERVER['PHP_SELF'])&&!preg_match('/content/',$_SERVER['PHP_SELF'])&&($_SERVER['PHP_SELF']!='/index.php')) { // On affiche pas le menu dans popup et dans script image et pdf
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/prepend.php');
}

/* 

********************************************
*** Gestion de l'arbo des cartes         ***
*** Classeur de cartes                   ***
*** Récupéré du fichier arbopage.lib.php ***
********************************************

*/
// PARAMETRE GENERAL
// Répertoire où est stocké l'arbo




dirExists('/modules/classeur/img');
$cms_classeur_IMG = '/modules/classeur/img';
dirExists('/modules/classeur/content');
$cms_classeur_PATH = '/modules/classeur/content';
if($_SESSION['provenance']=="carte"){
	$cms_classeur_UPLOADFILE = '/pdf/Cartes';
}
elseif($_SESSION['provenance']=="publication"){
	$cms_classeur_UPLOADFILE = '/pdf/Publications';
}
else{
	$cms_classeur_UPLOADFILE = '/pdf/classeur';
}
dirExists($cms_classeur_UPLOADFILE);
dirExists('/modules/classeur/img/fo');
$cms_classeur_UPLOADIMG = '/modules/classeur/img/fo';
$CMS_ROOT_CLASSEUR_UPLOADIMG = $_SERVER['DOCUMENT_ROOT'].$cms_classeur_UPLOADIMG;
$CMS_ROOT_CLASSEUR_UPLOADFILE = $_SERVER['DOCUMENT_ROOT'].$cms_classeur_UPLOADFILE;
$CMS_ROOT_CLASSEUR = $_SERVER['DOCUMENT_ROOT'].$cms_classeur_PATH."/";

 


	///////////////////////////
	// Fonctions Back Office //
	///////////////////////////



function removeRecursDirCarte($directory) {
// Suppression d'un répertoire donné et de tous ses sous répertoires
// Renvoi le nombre de répertoires supprimés
	//$directory = preg_replace('/\'/','\\\'',$directory);
	//$directory = preg_replace('/\ /','\\\ ',$directory);
	$dossier = @opendir($directory);
	$total = 0;
	while($fichier = @readdir($dossier)) {
		$l = array('.','..');
		if (!in_array($fichier, $l)) {
			if(is_dir($directory.'/'.$fichier)){
				$total += removeRecursDirCarte($directory.'/'.$fichier);
			} else {
				if(@unlink($directory.'/'.$fichier)){
					$total++;
				}
				else {
					$total++; // malgré tout
					error_log("Suppression du fichier $directory/$fichier impossible");
				}
			}
		}
	}
	@closedir($dossier);
	if (@rmdir($directory)){
		$total++;
	}
	else{
		$total++; // malgré tout
		error_log("Suppression du répertoire $directory impossible");
		}
	return $total;
}

function deleteNodeCarte($idSite, $db,$virtualPath){
// Suppression d'un noeud et de tous ses enfants à partir du virtualPath
// $db = base en cours
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// Renvoi le parentVirtualPath si ok, sinon false
	global $CMS_ROOT_CLASSEUR;
	global $CMS_ROOT_CLASSEUR_UPLOADIMG;
	if(strpos($virtualPath,",")==false) // Si racine => fait rien
		return false;
	$array_path = explode(',',$virtualPath);
	$node_id = array_pop($array_path);
	$result = false;
	$parentVirtualPath = join(',',$array_path);
	if(strpos($parentVirtualPath,",")==false)
		$parentVirtualPath = 'Racine';
	$children=getNodeChildrenCarte($idSite, $db,$virtualPath);
	foreach($children as $k => $child) {
		if (deleteNodeCarte($db, $virtualPath.','.$child['id'])==false) {
			error_log("Impossible de supprimer le dossier id=$child");
		}
	}
	$sql = "select node_id, node_parent_id, node_libelle, node_absolute_path_name from cms_arbo_classeur where node_id=$node_id;";
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$selectedFolder = $rs->fields['node_absolute_path_name'];
//		$n = removeRecursDirCarte($CMS_ROOT_CLASSEUR.utf8_encode($selectedFolder));
		$n = removeRecursDirCarte($CMS_ROOT_CLASSEUR.$selectedFolder);
		if ($n > 0) {
			
			$sql="delete from cms_arbo_classeur
			where node_id=$node_id;";
			$rs = $db->Execute($sql);
			if($rs!=false) {
				$result = $parentVirtualPath;
				// Si un picto est associé à ce dossier, on le vire
				if($picto = getArbopictoCarte($node_id)) {
					// Un picto existe déjà (gif, png, jpg?)
					// on le supprime pour éviter les conflits
					if(file_exists ($CMS_ROOT_CLASSEUR_UPLOADIMG."/".$picto)) {
						unlink($CMS_ROOT_CLASSEUR_UPLOADIMG."/".$picto);
					}
				}
			} else {
				error_log(" plantage lors de l'execution de la requete ".$sql);
				error_log($db->ErrorMsg());
				$result = false;
			}
			// suppression dans la table d'asso classarbo
			$sql="delete from cms_classarbo where ca_arbo=$node_id;";
			$bRetour = dbExecuteQuery($sql);
			
		} else {
			error_log("Suppression du dossier $CMS_ROOT_CLASSEUR$selectedFolder impossible");
			$result = false;
		}
	} else {
		$result=false;
	}
	return $result;
}

function addNodeCarte($idSite, $db,$virtualPath,$libelle){
// Ajout d'un noeud à la fin des enregistrements de la table à partir du virtualPath
// $db = base en cours
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// $libelle = nom du noeud à ajouter
// Renvoi le nouvel id créé par la base de données si ok, sinon false
	global $CMS_ROOT_CLASSEUR;
	$node_id = array_pop(explode(',',$virtualPath));
	$result = false;
	$libelle = str_replace('"',"'\\'", $libelle); // On remplace les guillemets par des doubles quotes
	$libelle = str_replace('`',"'", $libelle); // On remplace les quotes zarbi
	$libelle = str_replace('/'," - ", $libelle); // On remplace les / par des tirets
	// Les guillements font foirer l'enregistrement du répertoire sur le disque
	
	$sql = "select node_id, node_parent_id, node_libelle, node_absolute_path_name from cms_arbo_classeur where node_id=$node_id ;";

	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		
		//recuperation de la derniere id en base;
		$nextid = getNextVal("cms_arbo_classeur", "node_id");
		$path = addslashes($rs->fields['absolute_path_name']).$libelle.'/';
		
		$sql2="insert into cms_arbo_classeur (node_id, node_parent_id, node_libelle, node_absolute_path_name, node_id_site) VALUES
		($nextid, $node_id, '".$libelle."','".$path."', $idSite);";

		$rs2 = $db->Execute($sql2);		
		//if($rs2!=false && !$rs2->EOF) {
		if($rs2) {
		
			$result =$nextid;
			
			
			
			//$result = $rs->fields['last_id'];
//			if(!mkdir(utf8_encode($CMS_ROOT_CLASSEUR.stripslashes($path)))) {			
			//if(!mkdir()) {
			if(!dirExists($CMS_ROOT_CLASSEUR.substr($path, 1, strlen($path)))) {
			
			
			
				//si echec de la creation du rep, on efface le rep en base...
				$sql3 = "delete from cms_arbo_classeur where node_id = $result";
				$rs3 = $db->Execute($sql);
				error_log(" Erreur lors de la creation du repertoire $CMS_ROOT_CLASSEUR/$path");
				$result = false;
			}
		} else {
			echo "echec insert";
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		}
	} else {
		$result=false;
	}
	
	return $result;
}

function renameNodeCarte($db,$virtualPath,$libelle){
// Renommage d'un noeud à partir du virtualPath
// $db = base en cours
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// $libelle = nom du noeud à ajouter
// Renvoi true si ok, sinon false
	global $CMS_ROOT_CLASSEUR;
	$node_id = array_pop(explode(',',$virtualPath));
	$result = false;
	$libelle = str_replace('"',"'\\'", $libelle); // On remplace les guillemets par des doubles quotes
	$libelle = str_replace('`',"'", $libelle); // On remplace les quotes zarbi
	$libelle = str_replace('/'," - ", $libelle); // On remplace les / par des tirets
	// Les guillements font foirer l'enregistrement du répertoire sur le disque
	$sql = "select node_id, node_parent_id, node_libelle, node_absolute_path_name from cms_arbo_classeur where node_id=$node_id;";
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		//echo $CMS_ROOT_CLASSEUR.$rs->fields['absolute_path_name'];
		$path = str_replace("//", "/", $CMS_ROOT_CLASSEUR.$rs->fields['node_absolute_path_name']);
		$relativepath=$rs->fields['node_absolute_path_name'];
		$path = preg_replace('/\\/'.$rs->fields['node_libelle'].'\/$/','/',$path);
		$relativepath = preg_replace('/\/'.$rs->fields['node_libelle'].'\/$/','/',$relativepath);

		$old_libelle = $rs->fields['node_libelle'];

		$sql="update cms_arbo_classeur set node_libelle='".$libelle."', node_absolute_path_name='".addslashes($relativepath).$libelle."/'
						where node_id=$node_id;";
		//echo $sql;
		$rs = $db->Execute($sql);
		$sql="select node_id, node_parent_id, node_libelle, node_absolute_path_name from cms_arbo_classeur where node_parent_id=$node_id;";
		$rs = $db->Execute($sql);

		if($rs!=false && !$rs->EOF) {
			
			while(!$rs->EOF) {
				$oldAbsolute_path_name = $rs->fields['node_absolute_path_name'];
				$newAbsolute_path_name = str_replace ($old_libelle, $libelle, $rs->fields['node_absolute_path_name']);

				$sql="update cms_arbo_classeur set node_absolute_path_name='".$newAbsolute_path_name."' 
				where node_absolute_path_name = '".$oldAbsolute_path_name."';";
				//echo $sql;
				$rs2 = $db->Execute($sql);
				if($rs2!=false) {
					$result = true;
				}
				$rs->MoveNext();
			}
			
			// Il faut répercuter cette modif sur tous les node_absolute_path_name enfants de la base
			
		} else {
			$result = true;
		}
	} else {
		$result=false;
	}
	return $result;
}


function saveNodeDescriptionCarte($folderdescription,$db,$virtualPath){
// Enregistre une decription à partir du virtualpath
// $db = base en cours
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// Renvoi "true" si ok, "false" sinon
	$node_id = array_pop(explode(',',$virtualPath));
	$result = false;
	$sql = "select node_id, node_parent_id, node_libelle, node_absolute_path_name from cms_arbo_classeur where node_id=$node_id;";
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$sql="update cms_arbo_classeur set node_description='".addslashes($folderdescription)."' where node_id=$node_id;";
		$rs = $db->Execute($sql);
		$sql="select node_id, node_parent_id, node_libelle from cms_arbo_classeur where node_id=$node_id;";
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
	return $result;
}


function getNodeInfosCarte($idSite, $db,$virtualPath){
// Récupération des champs d'un enregistrement "noeud" à partir du virtualpath
// $db = base en cours
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// Renvoi un tableau avec les champs 'id', 'libelle', 'parent', 'path' si l'enregistrement est trouvé
// retourne "false" sinon
	$node_id = array_pop(explode(',',$virtualPath));
	$result = null;
	$sql = "select node_id, node_parent_id, node_libelle, node_absolute_path_name, node_description from cms_arbo_classeur where node_id=$node_id and id_site=$idSite;";

	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$result = array(
			'id' => $rs->fields['node_id'],
			'libelle' => $rs->fields['node_libelle'],
			'parent' => $rs->fields['node_parent_id'],
			'path' => $rs->fields['node_absolute_path_name'],
			'description' => $rs->fields['node_description']
		);
	} else {
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
	}
	return $result;
}

function getNodeInfosReverseCarte($idSite, $db,$absolutePath){
// Récupération des champs d'un enregistrement "noeud" à partir de l'absolutepath
// $db = base en cours
// $absolutepath = arbo réelle
// Renvoi un tableau avec les champs 'id', 'libelle', 'parent', 'path' si l'enregistrement est trouvé
// retourne "false" sinon
	global $cms_classeur_PATH;
	$absolutePath = str_replace($cms_classeur_PATH, "", $absolutePath);
	$absolutePath = urldecode(substr ($absolutePath, 0, strrpos ($absolutePath, "/") + 1));
	$result = null;
	$sql = "select node_id, node_parent_id, node_libelle, node_absolute_path_name, node_description from cms_arbo_classeur where node_absolute_path_name='".addslashes($absolutePath)."' and node_id_site=$idSite;";

	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$result = array(
			'id' => $rs->fields['node_id'],
			'libelle' => $rs->fields['node_libelle'],
			'parent' => $rs->fields['node_parent_id'],
			'path' => $rs->fields['node_absolute_path_name'],
			'description' => $rs->fields['node_description']
		);
	} else {
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;		
	}
	return $result;
}

function path2nodesCarte($idSite, $db, $absolutePath){
// Reconstitution d'un virtualPath à partir d'un absolutePath
// $absolutepath = arbo réelle
// Retourne le virtualPath
	$entree = getNodeInfosReverseCarte($idSite, $db,$absolutePath);
	$nodeItems = array();
	$nodeStr = $entree['id'];
	array_push($nodeItems, $nodeStr);
	// si le node parent n'est pas 0 (root) on recherche encore
	while($entree['parent'] != 0){
		$entree = getNodeInfosCarte($idSite, $db,$entree['parent']);
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


function path2nodesReverseCarte($db, $virtualPath) {
// Reconstitution d'un absolutePath à partir d'un virtualPath
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// Retourne l'absolutePath

	$strPath = '/';
	foreach(explode(',',$virtualPath) as $id){
		if ($id!="0") {
			$sql = "select node_libelle from cms_arbo_classeur where node_id=$id;";
			$rs = $db->Execute($sql);
			if($rs!=false && !$rs->EOF) {
				$strPath.=  $rs->fields['node_libelle'].'/';
			} else {
				error_log(" plantage lors de l'execution de la requete ".$sql);
				error_log($db->ErrorMsg());
				$strPath.='??????';
			}
		}
	}
	return $strPath;
}


function drawCompTreeCarte($idSite, $db,$virtualPath,$full_path_to_curr_id=null,$destination=null) {
// Affichage de l'arbo "à parcourir" par click
// fonction récursive: afficche l'id courant (si racine) et les enfants directs à chaque itération
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// $full_path_to_curr_id
// $destination
// Retourne l'arbo en html
	if($destination==null)
		$destination=$_SERVER['PHP_SELF'];
	$OP = (preg_match('/\?/',$destination)) ? '&' : '?' ; // Si la page a déjà des arguments => on ajoute
	$spacerStr = '&nbsp;&nbsp;';
	$strHTML = '';
	$tree_depth='1';	
	
	if ($full_path_to_curr_id==null || $full_path_to_curr_id=="0"|| $full_path_to_curr_id=="13"|| $full_path_to_curr_id=="14" || !isset($full_path_to_curr_id)) {
		
		// cas particulier de la racine où il faut dessiner le père en plus des fils		
		//$full_path_to_curr_id=$_GET['v_comp_path'];
		if($_SESSION['provenance']=="carte"){
			if(!isset($_GET['v_comp_path']) && !isset($_POST['v_comp_path'])){
				$full_path_to_curr_id='0,13';
			}			
			$strHTML .= "<a class=\"arbo\" href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=14\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><b>Racine carte</b></a><br/></td></tr><tr><td>\n";
		}
		elseif($_SESSION['provenance']=="publication"){
			if(!isset($_GET['v_comp_path']) && !isset($_POST['v_comp_path'])){
				$full_path_to_curr_id='0,14';
			}		
			$strHTML .= "<a class=\"arbo\" href=\"".$destination.$OP."v_comp_path=13\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><b>Racine publication</b></a><br/></td></tr><tr><td>\n";
		}
		else{
			$full_path_to_curr_id="0";
			$strHTML .= "<a class=\"arbo\" href=\"".$destination.$OP."v_comp_path=".$full_path_to_curr_id."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><b>Racine</b></a><br/></td></tr><tr><td>\n";
		}	
	}
	else{
		$tree_depth = sizeof(explode(',',$full_path_to_curr_id));
	}
	$children = getNodeChildrenCarte($idSite,$db,$full_path_to_curr_id);
	
	//indentation :
	$indent='';
	for($i=0;$i<$tree_depth;$i++){
		$indent.=$spacerStr;
	}
	//pre_dump($children);
	foreach ($children as $k=>$v) {
		
		$id = $v['id'];
		$libelle = $v['libelle'];
		$description = $v['description'];
		//debut de ligne...
		$OP = (preg_match('/\?/',$destination)) ? '&' : '?' ; // Si la page a déjà des arguments => on ajoute

		if (!in_array($id,explode(',',$virtualPath))) {
			//dossier ferme
			$strHTML .= "<span style=\"white-space:nowrap\">$indent<a href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=$full_path_to_curr_id,$id\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier.png\"><small>".strip_tags(str_replace(' ','&nbsp;',$libelle))."</small></a></span><br/>\n";
		} else {
			//dossier ouvert
			if(array_pop(explode(',',$virtualPath))==$id)
				$strHTML .= "<span style=\"white-space:nowrap\">$indent<a href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=$full_path_to_curr_id,$id\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><small><span class=\"arbo\">".strip_tags(str_replace(' ','&nbsp;',$libelle))."</span></small></a></span><br/>\n";
			else
				$strHTML .= "<span style=\"white-space:nowrap\">$indent<a href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=$full_path_to_curr_id,$id\" class=\"arbo\" title=\"".str_replace('"', "''", $description)."\"><img border=\"0\" src=\"$URL_ROOT/backoffice/cms/img/2013/ico_dossier_opened.png\"><small>".strip_tags(str_replace(' ','&nbsp;',$libelle))."</small></a></span><br/>\n";
			//echo $full_path_to_curr_id.','.$id;
			$strHTML.=drawCompTreeCarte($idSite, $db,$virtualPath,$full_path_to_curr_id.','.$id,$destination);
		}
	}
	return $strHTML;
}


function getAbsolutePathStringCarte($db, $virtualPath, $destination=null) {
// Reconstitution d'un chemin de nav cliquable à partir d'un virtualPath
// fonction récursive: afficche l'id courant (si racine) et les enfants directs à chaque itération
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// $destination
// Retourne le code html du chemin de nav
	if($destination==null)
		$destination=$_SERVER['PHP_SELF'];
	$OP = (preg_match('/\?/',$destination))?'&':'?'; // Si la page a déjà des arguments => on ajoute
	$strPath = '<a href="'.$destination.$OP.'v_comp_path=0" class="arbo"><b>Racine</b></a>';
	$localPath='0';
	foreach(explode(',',$virtualPath) as $id){
		if ($id!="0") {
			$localPath.=",$id";
			$sql = "select node_libelle from cms_arbo_classeur where node_id=$id;";
			$rs = $db->Execute($sql);
			if($rs!=false && !$rs->EOF) {
				$strPath.='&nbsp;&nbsp;>&nbsp;&nbsp;<a href="'.$destination.$OP.'v_comp_path='.$localPath.'" class="arbo">'.$rs->fields['node_libelle'].'</a>';
			} else {
				error_log(" plantage lors de l'execution de la requete ".$sql);
				error_log($db->ErrorMsg());
				$strPath.='&nbsp;&nbsp;>&nbsp;&nbsp;??????';
			}
		}
	}
	return $strPath;
}

function getNodeChildrenCarte($idSite,$db,$virtualPath) {

// Récupération des champs des enregistrements "noeud" enfant à partir d'un noeud parent
// $db = base en cours
// $path = virtualpath parent
// Renvoi un tableau de noeuds enfants
// chaque noeud enfant est un tableau qui contient les champs 'id', 'libelle', 'path', 'order', 'description' si au moins un enfant est trouvé
// retourne "false" sinon
	if(isset($virtualPath)){
		$node_id = array_pop(explode(',',$virtualPath));
	}


	else{
		$node_id = "0";
	}
	$result = array();
	$sql = "select node_id, node_libelle , node_absolute_path_name, node_order, node_description from cms_arbo_classeur
		where node_parent_id = $node_id	and node_id <> 0 and id_site = $idSite order by node_order, node_libelle;";

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
					'id' => $rs->fields['node_id'],
					'libelle' => $rs->fields['node_libelle'],
					'path' => $rs->fields['node_absolute_path_name'],
					'order' => $rs->fields['node_order'],
					'description' => $rs->fields['node_description']
				);
				array_push($result, $tmparray);
				$rs->MoveNext();
			}
		}
	}
	return $result;
}


function getNodeChildrenCarteAndClassArbo($idSite,$db,$virtualPath) {

// Récupération des champs des enregistrements "noeud" enfant à partir d'un noeud parent
// $db = base en cours
// $path = virtualpath parent
// Renvoi un tableau de noeuds enfants
// chaque noeud enfant est un tableau qui contient les champs 'id', 'libelle', 'path', 'order', 'description' si au moins un enfant est trouvé
// retourne "false" sinon
	if(isset($virtualPath)){
		$node_id = array_pop(explode(',',$virtualPath));
	}


	else{
		$node_id = "0";
	}
	$result = array();
	$sql = "select node_id, node_libelle, node_absolute_path_name, node_order, node_description, ca_classeid 
		from cms_arbo_classeur, cms_classarbo
		where node_id = ca_arbo 
		and node_parent_id = $node_id	
		and node_id <> 0 and node_id_site = $idSite order by node_order, node_libelle;";
		//echo $sql;

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
					'id' => $rs->fields['node_id'],
					'libelle' => $rs->fields['node_libelle'],
					'path' => $rs->fields['node_absolute_path_name'],
					'order' => $rs->fields['node_order'],
					'description' => $rs->fields['node_description'],
					'objetid' => $rs->fields['ca_classeid']
				);
				array_push($result, $tmparray);
				$rs->MoveNext();
			}
		}
	}
	return $result;
}


function saveNodeOrderCarte($orders, $db, $virtualPath) {
// Enregistre un ordre d'affichage des noeuds
// $orders = ordre des noeuds sous la forme d'un tableau à 2 dimensions
// [ [id_noeud1,ordre_noeud1], [id_noeud2,ordre_noeud2], ... ]
// $path = inutilisé!!
// Renvoi "true" si ok, "false" sinon
	$result = true;
	foreach ($orders as $id => $ordre){
		$sql="UPDATE cms_arbo_classeur SET node_order=$ordre WHERE node_id=$id;";
		$rs = $db->Execute($sql);
	}
	if($rs==false) {
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$result = false;
	}
	return $result;
}

function moveNodeCarte($idSite, $db, $virtualPath, $new_virtualPath) {
// Déplace un noeud dans l'arbo et sur le disque
// $virtualPath = path source
// $new_virtualPath = path destination
// Renvoi "true" si ok, "false" sinon
	global $CMS_ROOT_CLASSEUR;
	$node_id = array_pop(explode(',',$virtualPath));
	$result = false;
	$nodeInfos=getNodeInfosCarte($idSite, $db,$virtualPath);
	$new_nodeInfos=getNodeInfosCarte($idSite, $db,$new_virtualPath);
	if($new_nodeInfos['id']==0) { // Le dossier de destination est la racine!
		$new_nodeInfos['path']='/';
	}
	$sql="update cms_arbo_classeur set node_parent_id=".$new_nodeInfos['id'].",
					node_absolute_path_name='".addslashes($new_nodeInfos['path']).addslashes($nodeInfos['libelle'])."/' where node_id=$node_id;\n";
	$rs = $db->Execute($sql);

	$oldpath = $CMS_ROOT_CLASSEUR.preg_replace("/[\/]?$/msi","",$nodeInfos['path']);
	$newpath = $CMS_ROOT_CLASSEUR.$new_nodeInfos['path'].$nodeInfos['libelle'];
	dirExists($oldpath);
	dirExists($newpath);
	if($rs!=false) {
		if(file_exists($newpath)) // Si le nom existe déjà on fait rien
			return false;
		if(!rename($oldpath,$newpath))
		// Une erreur est survenue lors du renommage de larbo physique => on annule
			return false;

	} else {
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$result = false;
	}
	return $result;

}


function getFolderComposantsCarte($virtualPath) {
        if(strlen($virtualPath)>0)
                $nodeId=array_pop(explode(',',$virtualPath));
        else
                return false;
        global $db;
        $return = array();
        $sql="select id_classeur, nom_classeur, motsclefs_classeur, description_classeur, chemin_absolu_classeur, 
		nodeid_classeur, date_etude_classeur, date_publication_classeur, lieu_classeur, poids_classeur
        from cms_classeur, cms_classarbo
        where ca_arbo=$nodeId
		and ca_classeur = id_classeur
        order by nom_classeur DESC;
        ";
        $rs = $db->Execute($sql);
        if($rs) {
                if(!$rs->EOF) {
                        while (!$rs->EOF) {
                                $tmparray = array(
                                        'id' => $rs->fields['id_classeur'],
                                        'nom' => $rs->fields['nom_classeur'],
                                        'motsclefs' => $rs->fields['motsclefs_classeur'],
                                        'description' => $rs->fields['description_classeur'],
                                        'chemin_absolu' => $rs->fields['chemin_absolu_classeur'],
                                        'nodeid' => $rs->fields['nodeid_classeur'],
										'datetude' => $rs->fields['date_etude_classeur'],
										'datepubli' => $rs->fields['date_publication_classeur'],
										'lieu' => $rs->fields['lieu_classeur'],
										'poids' => $rs->fields['poids_classeur'],
                                );
                                array_push($return, $tmparray);
                                $rs->MoveNext();
                        }
                } else {
                        $return=false;
                }
        } else {
                error_log("Plantage lors de l'execution de la requete\n $sql");
                error_log($db->ErrorMsg());
                $return = false;
        }
        return $return;
}


function storeComposantCarte($nomCarte, $motsclesCarte, $descriptionCarte, $cheminrelatifCarte, $nodeIdCarte, $nodeIdCarteold, $dateetude, $datepub, $lieu, $page, $poids, $idCarte=null) {
// Enregistre toutes les infos sur une carte
// $nomCarte => nom donné à la carte (titre)
// $motsclesCarte => liste des mots-clés séparés par des point-virgules
// $descriptionCarte => Description de la carte
// $cheminrelatifCarte => Chemin relatif de la carte (nom du fichier physique stocké dans modules/classeur_classeur/files)
// $nodeIdCarte => Node à laquelle est rattaché la carte (position dans l'arbo du classeur)
// $idCarte => ID de l'enregistrement "carte", si renseigné > modif, sinon création
// Renvoi l'id de l'enregistrement nouvellement créé ou modifié, false sinon
	if ($annee==""){
		$jour=getdate();
		$annee=$jour[year];
		}
	if($page==""){$page="0";}
	global $db;
	if($idCarte==null)
		$idCarte='NULL'; // si null => ajout, sinon update (voir comment faire)
	$return=false;	
	
	$path = addslashes($rs->fields['node_absolute_path_name']).$libelle.'/';
	if($idCarte!='NULL') { // update
		$sql="update cms_classeur set nom_classeur='".addslashes($nomCarte)."',
					motsclefs_classeur='".addslashes($motsclesCarte)."',
					description_classeur='".addslashes($descriptionCarte)."',
					chemin_absolu_classeur='".$cheminrelatifCarte."',
					nodeid_classeur='".$nodeIdCarte."',
					date_etude_classeur='".$dateetude."',
					date_publication_classeur='".$datepub."',
					lieu_classeur='".addslashes($lieu)."',					
					poids_classeur='".$poids."',
					pages_classeur='".$page."'
					where id_classeur=$idCarte;\n";
		//$sql2="select id_classeur as max from cms_classeur where id_classeur=".$idCarte.";";
		
		$sql2="update cms_classarbo set ca_arbo=".$nodeIdCarte." where ca_classeur=".$idCarte." and ca_arbo=".$nodeIdCarteold.";";
		$bRetour = dbExecuteQuery($sql2);
		
	}
	else {
		$nextid = getNextVal("cms_classeur", "id_classeur");
		$sql="insert into cms_classeur (id_classeur, nom_classeur, motsclefs_classeur, description_classeur, chemin_absolu_classeur, nodeid_classeur, date_etude_classeur, date_publication_classeur, lieu_classeur, poids_classeur, pages_classeur) VALUES (".$nextid.", '".addslashes($nomCarte)."', '".addslashes($motsclesCarte)."', '".addslashes($descriptionCarte)."', '".$cheminrelatifCarte."', $nodeIdCarte , '".$dateetude."' , '".$datepub."' , '".$lieu."' , '".$poids."' , '".$page."');";
		$sql2="insert into cms_classarbo VALUES (".$nextid.", ".$nodeIdCarte." );";
		$bRetour = dbExecuteQuery($sql2);
		
		
	}
	//echo "<br />$sql<br>";
	$rs = $db->Execute($sql);
	
	$nextid = getNextVal("cms_classeur", "id_classeur");
	if($rs->fields['last_id']==$nextid) $result=$nextid;
	
	if($rs) {
		$result = intval($nextid)-1;
	} else {
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$result = false;
	}
//echo "return=".$result;
	return $result;

}


function moveComposantCarte($idCarte, $old_nodeIdCarte, $new_nodeIdCarte) {
// Déplace une carte dans l'arborescence
// $new_nodeIdCarte => Node à laquelle est rattaché la carte (position dans l'arbo du classeur)
// $idCarte => ID de l'enregistrement "carte"
// Renvoi true si ok, false sinon
	global $db;
	$result=true;

	$sql="update cms_classarbo set ca_arbo=".$new_nodeIdCarte." where ca_classeur=".$idCarte." AND ca_arbo=".$old_nodeIdCarte.";";
	$bRetour = dbExecuteQuery($sql);
	return $bRetour;

}

function renameCarte($idCarte, $new_nomCarte) {
// Renomme une carte
// $$new_nomCarte => Nouveau nom de la carte
// $idCarte => ID de l'enregistrement "carte"
// Renvoi true si ok, false sinon
	global $db;
	$result=true;

	$sql="update cms_classeur set nom_classeur='".$new_nomCarte."' where id_classeur=$idCarte;\n";
	$rs = $db->Execute($sql);
	if($rs==false) {
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$result = false;
	}
	return $result;

}


function deleteCarte($idCarte, $idNode) {
			
	$sql="delete from cms_classarbo where ca_id=".$idCarte."";
	$bRetour = dbExecuteQuery($sql);
	
	if ($bRetour) {
		$result = true;
	} else {
		error_log("Suppression de la carte $fichier_classeur impossible");
		$result = false;
	}

	return $result;
}


function getCarteById($idCarte, $idNode){
// Récupération des champs d'un enregistrement "carte" à partir d'un id
// $idCarte => ID de l'enregistrement "carte"
// Renvoi un tableau avec les champs 'id', 'nom', 'motsclefs', 'description', 'chemin_absolu', 'node_id' si l'enregistrement est trouvé
// retourne "false" sinon
	global $db;
	$result=null;
	$node_id = array_pop(explode(',',$virtualPath));
	$sql = "select id_classeur, nom_classeur, motsclefs_classeur, description_classeur, chemin_absolu_classeur, nodeid_classeur, date_etude_classeur , date_publication_classeur, lieu_classeur , poids_classeur , pages_classeur, poids_classeur, ca_arbo
			from cms_classeur , cms_classarbo
			where ca_arbo = ".$idNode."
			and ca_classeur = id_classeur
			and ca_classeur=".$idCarte.";";
	$rs = $db->Execute($sql);
	
	if($rs!=false && !$rs->EOF) {
		$result = array(
			'id' => $rs->fields['id_classeur'],
			'nom' => $rs->fields['nom_classeur'],
			'motsclefs' => $rs->fields['motsclefs_classeur'],
			'description' => $rs->fields['description_classeur'],
			'chemin_absolu' => $rs->fields['chemin_absolu_classeur'],
			'node_id' => $rs->fields['ca_arbo'],
			'date_etude' => $rs->fields['date_etude_classeur'],
			'date_etude' => $rs->fields['date_etude_classeur'],
			'date_publication' => $rs->fields['date_publication_classeur'],
			'lieu' => $rs->fields['lieu_classeur'],
			'poids' => $rs->fields['poids_classeur'],
			'pages' => $rs->fields['pages_classeur']
		);
	} else {
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		
	}
	return $result;
}


function getCarteById2($idCarte){
// Récupération des champs d'un enregistrement "carte" à partir d'un id
// $idCarte => ID de l'enregistrement "carte"
// Renvoi un tableau avec les champs 'id', 'nom', 'motsclefs', 'description', 'chemin_absolu', 'node_id' si l'enregistrement est trouvé
// retourne "false" sinon
	global $db;
	$result=null;
	$node_id = array_pop(explode(',',$virtualPath));
	$sql = "select id_classeur, nom_classeur, motsclefs_classeur, description_classeur, chemin_absolu_classeur, nodeid_classeur, date_etude_classeur , date_publication_classeur, lieu_classeur , poids_classeur , pages_classeur, poids_classeur
			from cms_classeur
			where id_classeur=".$idCarte.";";
	$rs = $db->Execute($sql);
	
	if($rs!=false && !$rs->EOF) {
		$result = array(
			'id' => $rs->fields['id_classeur'],
			'nom' => $rs->fields['nom_classeur'],
			'motsclefs' => $rs->fields['motsclefs_classeur'],
			'description' => $rs->fields['description_classeur'],
			'chemin_absolu' => $rs->fields['chemin_absolu_classeur'],
			'node_id' => $rs->fields['ca_arbo'],
			'date_etude' => $rs->fields['date_etude_classeur'],
			'date_etude' => $rs->fields['date_etude_classeur'],
			'date_publication' => $rs->fields['date_publication_classeur'],
			'lieu' => $rs->fields['lieu_classeur'],
			'poids' => $rs->fields['poids_classeur'],
			'pages' => $rs->fields['pages_classeur']
		);
	} else {
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		
	}
	return $result;
}

function getArbopictoCarte($id) {
// On cherche le picto associé à un dossier s'il existe
// Retourne le nom du fichier picto si trouvé
// false sinon
	global $CMS_ROOT_CLASSEUR_UPLOADIMG;
	if ($handle = opendir($CMS_ROOT_CLASSEUR_UPLOADIMG)) {
	   /* Ceci est la façon correcte de traverser un dossier. */
	   while (false !== ($file = readdir($handle))&& (!preg_match('/^picto_'.$id.'\./',$file))) { null; }
	   closedir($handle);
	   if(preg_match('/^picto_'.$id.'\./',$file)) return $file;
	}
	return false;
}




function searchCarte($db,$tofind){
// Récupération des champs d'un enregistrement de motsclés
// La recherche s'effectue dans le champs motscles et description
// $db = base en cours
// $tofind = chaines à rechercher dans la base
// Renvoi un tableau de chaque enregistrement trouvés
// ces enregistrements sont sous la forme d'un tableau
// avec les champs 'id', 'nom', 'motsclefs', 'description', 'chemin_absolu', 'node_id' si l'enregistrement est trouvé
// retourne "false" sinon
	global $db;
	$result = array();
	$node_id = array_pop(explode(',',$virtualPath));
	$tofind = addslashes($tofind);
	// Tous les mots sont individuellement recherchés
	// c'est un ou logique!
	
	$sql= "select cc.id_classeur, cc.nom_classeur, cc.motsclefs_classeur, cc.description_classeur, 
cc.chemin_absolu_classeur, cc.nodeid_classeur, ca.ca_arbo, cac.absolute_path_name, cc.date_etude_classeur, cc.date_publication_classeur, cc.lieu_classeur, cc.poids_classeur
	from cms_classeur as cc, cms_classarbo as ca, cms_arbo_classeur as cac
	where match (cc.motsclefs_classeur,cc.description_classeur) against ('£$tofind%' IN BOOLEAN MODE)
	and cc.nodeid_classeur=ca.ca_arbo 
	and cc.id_classeur =ca.ca_classeur 
	and ca.ca_arbo=cac.node_id
	group by cc.chemin_absolu_classeur order by cc.nom_classeur desc";
	$rs = $db->Execute($sql);
	if($rs!=false) {
                if(!$rs->EOF) {
                        while (!$rs->EOF) {
								//on remplace cas "/" dans la recherche car créé erreur lors mise en gras résultat
								$tofind=str_replace("/","",$tofind);
                                $tmparray = array(
                                        'id' => $rs->fields['id_classeur'],
										'nom' => $rs->fields['nom_classeur'],
										'datetude' => $rs->fields['date_etude_classeur'],
										'datepubli' => $rs->fields['date_publication_classeur'],
										'lieu' => $rs->fields['lieu_classeur'],
										'poids' => $rs->fields['poids_classeur'],
										// Mise en valeur (en gras) des résultats trouvés
										'motsclefs' => preg_replace('/'.$tofind.'/i',"<b>\${0}</b>",$rs->fields['motsclefs_classeur']),
										'description' => preg_replace('/'.$tofind.'/i',"<b>\${0}</b>",$rs->fields['description_classeur']),
										'chemin_absolu' => $rs->fields['chemin_absolu_classeur'],
										'node_id' => $rs->fields['nodeid_classeur']
                                );
                                array_push($result, $tmparray);
                                $rs->MoveNext();
                        }
                } else {
                        $result=false;
                }
	} else {
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;
		
	}
	return $result;
}


function drawCompTreeCarteHTML($idSite, $db,$virtualPath,$full_path_to_curr_id=null,$destination=null, $debutArbo) {
// Affichage de l'arbo "à parcourir" par click
// fonction récursive: afficche l'id courant (si racine) et les enfants directs à chaque itération
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// $full_path_to_curr_id
// $destination
// Retourne l'arbo en html
	global $cms_classeur_UPLOADIMG;
	if($destination==null) $destination=$_SERVER['PHP_SELF'];
	$OP = (preg_match('/\?/',$destination)) ? '&' : '?' ; // Si la page a déjà des arguments => on ajoute
	$spacerStr = '&nbsp;&nbsp;';
	$strHTML = '';
	$tree_depth='1';
	if ($full_path_to_curr_id==null || $full_path_to_curr_id=="0") {
		// cas particulier de la racine où il faut dessiner le père en plus des fils
		$full_path_to_curr_id=0;
	} else {
		$tree_depth = sizeof(explode(',',$full_path_to_curr_id));
	}
	$children = getNodeChildrenCarte($idSite, $db,$full_path_to_curr_id);

	foreach ($children as $k=>$v) {
	
		$id = $v['id'];
		$libelle = $v['libelle'];
		$path = $v['path'];
		$description = $v['description'];
		$pathReverse = path2nodesCarte($idSite, $db, $path);
		if (!in_array($debutArbo,explode(',',$pathReverse)) || $debutArbo == $id) {
			$display=" style=\"display:none;\"";
		}
		else {
			$display="";
		}
		//debut de ligne...
		//echo "<br />libelle=$libelle provenance=".$_SESSION['provenance'];
		
		//code a revoir... pas propre du tout mais ca marche!
		if(($_SESSION['provenance']=="cartefo" && $libelle=="Racine publications")||($_SESSION['provenance']=="publicationfo" && $libelle=="Racine carte")){
		}else{
		
		$OP = (preg_match('/\?/',$destination)) ? '&' : '?' ; // Si la page a déjà des arguments => on ajoute
		if (!in_array($id,explode(',',$virtualPath))) {
			$boolLastNode = getBoolLastNode($id, $idSite, $db);
			$boolEnregistrementAssocie = getBoolEnregistrementAssocie($id, $idSite, $db);
			//dossier ferme

			$srcStr = "";
			$sql = "select * from cms_classarbo where ca_arbo=".$id."";
			$aClassearbo =  dbGetObjectsFromRequeteID("cms_classarbo", $sql);
			$idEnregistrement = "";
			if (sizeof($aClassearbo) > 0) {
				$oClassearbo = $aClassearbo[0];
				$classeId = $oClassearbo->get_classe();
				$idEnregistrement = $oClassearbo->get_classeid();
				
				$oClasse = dbGetObjectFromPK("classe", $classeId);
				$classeName = $oClasse->get_nom();
				
				$classePrefixe = "com";
				
				$sql2 = "select * from ".$classeName." where ".$classePrefixe."_statut=4";
				$aClasse =  dbGetObjectsFromRequete($classeName, $sql2);
				$nombreEnregistrement = sizeof($aClasse);
				
				if ($nombreEnregistrement > 0) {
					$boolEnregistrementTrouve=false;
					for ($i = 0; $i<sizeof($aClasse) ;$i++) {
						$oClasse = $aClasse[$i];
						if ($oClasse->get_id() == $idEnregistrement) {
							$imgEnregistrement = $oClasse->get_img();
							$arrayImage = explode(' ', $imgEnregistrement);
							
							for ($m=0; $m<sizeof($arrayImage); $m++) {
								if (preg_match("/src/msi", $arrayImage[$m])) {
									$srcStr = $arrayImage[$m];
									$srcStr = str_replace('"', '', $srcStr);
									$srcStr = str_replace('src=', '', $srcStr);
								}
							}
							$boolEnregistrementTrouve=true;							
						}
					}
				}
			}
			//if ($nombreEnregistrement>0) {
				if ($boolLastNode == false || $idEnregistrement =="" ) {
				
					if ($boolEnregistrementAssocie) {
						$strHTML .= "<div class=\"titleacco\"><div class=\"listcarte".($tree_depth-1)." \" ><div id=\"arbo".$id."\" ".$display."><h".($tree_depth-1)."><a href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=$full_path_to_curr_id,$id\" title=\"".str_replace('"', "''", $description)."\" >";
						$strHTML .= (getArbopictoCarte($id)) ? '<img src="'.$cms_classeur_UPLOADIMG.'/'.getArbopictoCarte($id).'" alt="" hspace="3" border=\"0\"/>':'';
						$strHTML .= "".$libelle."</a></h".($tree_depth-1)."></div></div></div>\n";
					}
					
	
				} else {
					if ($boolEnregistrementTrouve == true) {
						$virtualPathTemp = getVirtualPathByIdNode($idSite, $db, $id);
						$strHTML .= "<div class=\"titleacco\"><div class=\"listcarte".($tree_depth-1)." \" ><div id=\"arbo".$id."\" ".$display."><h".($tree_depth-1).">";
						$strHTML .= "<a href=\"../".$classeName."/foshow_".$classeName.".php?idSite=".$idSite."&id=".$idEnregistrement."&v_comp_path=".$virtualPathTemp."\" onmouseover=\"affiche_infobulle(".$idEnregistrement.")\" onmouseout=\"cache_infobulle()\">";
						$strHTML .= (getArbopictoCarte($id)) ? '<img src="'.$cms_classeur_UPLOADIMG.'/'.getArbopictoCarte($id).'" alt="" hspace="3" border=\"0\"/>':'';
						$strHTML .= "".$libelle."</a></h".($tree_depth-1)."></div></div></div>\n";
						$strHTML .= "<input type=\"hidden\" id=\"arboImgtest".$idEnregistrement."\" name=\"arboImgtest".$idEnregistrement."\" value=\"".$srcStr."\">\n";
					}	
				}			
			
			
		} else {
			//dossier ouvert
			if(array_pop(explode(',',$virtualPath))==$id) { // Dossier courant
				$strHTML .= "<div class=\"accoblock\"><div class=\"titleacco\"><div class=\"listcarte".($tree_depth-1)."\" ".$display."><a href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=$full_path_to_curr_id,$id\" title=\"".str_replace('"', "''", $description)."\"  ><h".($tree_depth-1)." >";
				$strHTML .= (getArbopictoCarte($id)) ? '<img src="'.$cms_classeur_UPLOADIMG.'/'.getArbopictoCarte($id).'" hspace="3" alt=""  border=\"0\"/>':'';
				$strHTML .= "".$libelle."</h".($tree_depth-1)."></a></div></div>\n";
				if (drawCompTreeCarteHTML($idSite, $db,$virtualPath,$full_path_to_curr_id.','.$id,$destination, $debutArbo) == "") {
					
					
				}
				else {
				
					$strHTML.= drawCompTreeCarteHTML($idSite, $db,$virtualPath,$full_path_to_curr_id.','.$id,$destination, $debutArbo);
				}
				$strHTML.="</div>";
			}
			else { // Dossiers contenant parents
				$strHTML .= "<div class=\"listcarte".($tree_depth-1)."\" ".$display."><a href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=$full_path_to_curr_id,$id\" title=\"".str_replace('"', "''", $description)."\"><h".($tree_depth-1).">";
				$strHTML .= (getArbopictoCarte($id)) ? '<img src="'.$cms_classeur_UPLOADIMG.'/'.getArbopictoCarte($id).'" hspace="3" alt=""  border=\"0\"/>':'';
				$strHTML .= "".$libelle."</h".($tree_depth-1)."></a></div>\n";
				$strHTML .= drawCompTreeCarteHTML($idSite, $db,$virtualPath,$full_path_to_curr_id.','.$id,$destination, $debutArbo);
				
			}
		}//fin if session...
		}
		// Que le dossier soit ouvert ou fermé, on affiche l'arbo
	}
	return $strHTML;
}

function drawCarteListHTML($db,$virtualPath){
// Affichage des cartes que contient le virtualpath
	$strHTML='<table class="resultsearch" border="0" cellpadding="0" cellspacing="0">';
//<td align="center" bgcolor="EEEEEE"><strong>&nbsp;&nbsp;Mots&nbsp;clés&nbsp;</strong></td>
	$contenus = getFolderComposantsCarte($virtualPath);
	if((is_array($contenus)) && (sizeof($contenus)!=0)) {
		$strHTML.='<tr>
					<td class="titresearch">Etudes trouvées</td>
					<td class="titresearch">Description</td>
					<td class="titresearch">Date du début de l\'étude</td>
					<td class="titresearch">Date de fin de l\'étude</td>
					<td class="titresearch">Lieu</td>
					<td class="titresearch">Taille du fichier</td>
			  </tr>';
		foreach ($contenus as $k => $carte) { // Laisser le &bugMoz=0 réparation d'un bug aléatoire de FFox qui n'affiche pas bien le PDF sinon
			if ($carte['datetude'] == "") $datetude="&nbsp;"; else $datetude=$carte['datetude'];
			if ($carte['datepubli'] == "") $datepubli="&nbsp;"; else $datepubli=$carte['datepubli'];
			$taille = filesize("../../../pdf/classeur/".$carte['chemin_absolu']);
			$taille = round($taille/10.24)/100;
			
			$strHTML.='<tr>
						<td id="cartenom" class="noir10"><img src="/custom/img/pdf_black.gif" alt=""/><a href="/backoffice/classeur/showCarte.php?id='.$carte['id'].'&bugMoz=0" class="noir10" target="_blank">'.$carte['nom'].'</a></td>
						<td id="cartedesc" class="noir10">'.$carte['description'].'</td>
						<td id="cartedesc" class="noir10">'.$datetude.'</td>
						<td id="cartedesc" class="noir10">'.$datepubli.'</td>
						<td id="cartedesc" class="noir10">'.$carte['lieu'].'</td>
						<td id="cartedesc" class="noir10">'.$taille.' Ko</td>
						
				  </tr>';
//						<td align="center" bgcolor="EEEEEE">'.$carte['motsclefs'].'</td>
		}
	}
	
	$strHTML.='</table>';
	
	return $strHTML;
}

function drawSearchCarteHTML($db,$tab_result){
// Renvoi le code HTML d'affichage des résultats de recherche
// $tab_result = tableau contenant les résultats de recherche
// avec les champs 'id', 'nom', 'motsclefs', 'description', 'chemin_absolu', 'node_id'
// pour chaque résultat de recherche

	$strHTML='';
	$strHTML.='<table class="resultsearch" id="resultmoteur" border="0" cellpadding="0" cellspacing="0">
			  <tr>
					<td class="titresearch">Etudes trouvées</td>
					<td class="titresearch">Description</td>
					<td class="titresearch">Date du début de l\'étude</td>
					<td class="titresearch">Date de fin de l\'étude</td>
					<td class="titresearch">Lieu</td>
					<td class="titresearch">Taille du fichier</td>
			  </tr>';
//					<td align="center" bgcolor="EEEEEE"><strong>&nbsp;&nbsp;Mots&nbsp;clés&nbsp;</strong></td>

	foreach ($tab_result as $carte) { // Laisser le &bugMoz=0 réparation d'un bug aléatoire de FFox qui n'affiche pas bien le PDF sinon
		if ($carte['datetude'] == "") $datetude="&nbsp;"; else $datetude=$carte['datetude'];
		if ($carte['datepubli'] == "") $datepubli="&nbsp;"; else $datepubli=$carte['datepubli'];
		$taille = filesize("../../../pdf/classeur/".$carte['chemin_absolu']);
		$taille = round($taille/10.24)/100;
		$strHTML.='<tr>
						<td id="cartenom" class="noir10"><img src="/custom/img/pdf_black.gif" alt=""/><a href="/backoffice/classeur/showCarte.php?id='.$carte['id'].'&bugMoz=0" class="noir10" target="_blank">'.$carte['nom'].'</a></td>
						<td id="cartedesc" class="noir10">'.$carte['description'].'</td>
						<td id="cartedesc" class="noir10">'.$datetude.'</td>
						<td id="cartedesc" class="noir10">'.$datepubli.'</td>
						<td id="cartedesc" class="noir10">'.$carte['lieu'].'</td>
						<td id="cartedesc" class="noir10">'.$taille.' Ko</td>
				  </tr>';
//						<td align="center" bgcolor="EEEEEE">'.$carte['motsclefs'].'</td>
	}
	$strHTML.='</table>';
	return $strHTML;
}			



function drawCompTreeCarteHTMLComponent($idSite, $db,$virtualPath,$full_path_to_curr_id=null,$destination=null, $debutArbo) {
// Affichage de l'arbo "à parcourir" par click
// fonction récursive: afficche l'id courant (si racine) et les enfants directs à chaque itération
// $virtualpath = arbo numérique $node_parent_parent_id,$node_parent_id,$current_node_id
// $full_path_to_curr_id
// $destination
// Retourne l'arbo en html

	global $cms_classeur_UPLOADIMG;
	
	$spacerStr = '&nbsp;&nbsp;';
	$strHTML = '';
	//$tree_depth='1';
	if($destination==null) $destination=$_SERVER['PHP_SELF'];
	$OP = (preg_match('/\?/',$destination)) ? '&' : '?' ;

	$tree_depth = sizeof(explode(',',$virtualPath));
	$nodeId=array_pop(explode(',',$virtualPath)); 
	//echo "<br />tree_depth".$tree_depth;
	if ($tree_depth > 3) {
		
		$virtualPathTemp = str_replace(",$nodeId", "", $virtualPath);
		$children = getNodeChildrenCarte($idSite, $db,$virtualPathTemp);
	}
	else {
		
		$children = getNodeChildrenCarte($idSite, $db,$virtualPath);
	} 
    //pre_dump($children);
	foreach ($children as $k=>$v) {
		 
		$id = $v['id'];
		$libelle = $v['libelle'];
		$path = $v['path'];
		$description = $v['description'];
		//$pathReverse = path2nodesCarte($idSite, $db, $path);
		 
		 
		$strHTML .= "<div id=\"arbo".$id."\" ".$display."><h2>";
		//if ($tree_depth != 4 && $nodeId != $id) 
		$strHTML .= "<a href=\"".$destination.$OP."idSite=".$idSite."&v_comp_path=$full_path_to_curr_id,$id\" title=\"".str_replace('"', "''", $description)."\" >";
		$strHTML .= (getArbopictoCarte($id)) ? '<img src="'.$cms_classeur_UPLOADIMG.'/'.getArbopictoCarte($id).'" alt="" hspace="3" border=\"0\"/>':'';
		$strHTML .= "".$libelle."";
		//if ($tree_depth != 4 && $nodeId != $id) 
		$strHTML .= "</a>";
		$strHTML .= "</h2></div>\n"; 
		if ($tree_depth == 4 && $nodeId == $id) {
			$children_ = getNodeChildrenCarteAndClassArbo($idSite, $db,$virtualPath); 
			foreach ($children_ as $k_=>$v_) {
				$id = $v_['id'];
				$libelle = $v_['libelle'];
				$path = $v_['path'];
				$description = $v_['description'];
				$idEnregistrement = $v_['objetid'];
				$classeName = "composant"; 
				if (getCount_where("composant", array("com_id", "com_statut"), array($idEnregistrement, 4), array( "NUMBER", "NUMBER"))>0) {
		
					$oComponent = dbGetObjectFromPK("composant", $idEnregistrement); 
					$imgEnregistrement = $oComponent->get_img();
					/*$srcStr = str_replace(' ', '', $imgEnregistrement);
					$srcStr = str_replace('<img', '', $srcStr);
					$srcStr = str_replace('src="', '', $srcStr);
					$srcStr = str_replace('alt=""', '', $srcStr);
					$srcStr = str_replace('/>', '', $srcStr);
					$srcStr = str_replace('"', '', $srcStr);*/
					$arrayImage = explode(' ', $imgEnregistrement);
					
					for ($m=0; $m<sizeof($arrayImage); $m++) {
						if (preg_match("/src/msi", $arrayImage[$m])) {
							$srcStr = $arrayImage[$m];
							$srcStr = str_replace('"', '', $srcStr);
							$srcStr = str_replace('src=', '', $srcStr);
						}
					}
					$strHTML .= "<div id=\"arbo".$id."\" ".$display."><h".($tree_depth-1).">";
					$strHTML .= "<a href=\"../".$classeName."/foshow_".$classeName.".php?idSite=".$idSite."&id=".$idEnregistrement."&v_comp_path=".$virtualPath.",$id\" onmouseover=\"affiche_infobulle(".$idEnregistrement.")\" onmouseout=\"cache_infobulle()\">";
					$strHTML .= (getArbopictoCarte($id)) ? '<img src="'.$cms_classeur_UPLOADIMG.'/'.getArbopictoCarte($id).'" alt="" hspace="3" border=\"0\"/>':'';
					$strHTML .= "".$libelle."</a></h".($tree_depth-1)."></div>\n";
					$strHTML .= "<input type=\"hidden\" id=\"arboImgtest".$idEnregistrement."\" name=\"arboImgtest".$idEnregistrement."\" value=\"".$srcStr."\">\n";
				}
			}
			
		}
	}
	return $strHTML;
}

function storeEnregistrement( $nodeIdCarte, $nodeIdCarteold, $idCarte=null, $idClasse=null) {
// Enregistre toutes les infos sur une carte
// $nomCarte => nom donné à la carte (titre)
// $motsclesCarte => liste des mots-clés séparés par des point-virgules
// $descriptionCarte => Description de la carte
// $cheminrelatifCarte => Chemin relatif de la carte (nom du fichier physique stocké dans modules/classeur_classeur/files)
// $nodeIdCarte => Node à laquelle est rattaché la carte (position dans l'arbo du classeur)
// $idCarte => ID de l'enregistrement "carte", si renseigné > modif, sinon création
// Renvoi l'id de l'enregistrement nouvellement créé ou modifié, false sinon
	
	if($idCarte==null)
		$idCarte='NULL'; // si null => ajout, sinon update (voir comment faire)
	$return=false;	  
	
	//vérifier existence
	
	$eCount = getCount_where("cms_classarbo", array("ca_classe", "ca_classeid", "ca_arbo"), array( $idClasse, $idCarte, $nodeIdCarte), array( "NUMBER", "NUMBER", "NUMBER"));
	
	if ($eCount == 0) {
		if($idCarte!='NULL') { // update
			
			$oClassarbo = new Cms_classarbo();
			$oClassarbo->set_classe($idClasse);
			$oClassarbo->set_classeid($idCarte);
			$oClassarbo->set_arbo($nodeIdCarte);
			$bRetour = dbInsertWithAutoKey($oClassarbo);
		}
	}
	else {
		return true;
	}

	if($bRetour) {
		$result = true;
	} else {
		error_log(" plantage lors de l'execution de la requete ".$sql);
		error_log($db->ErrorMsg());
		$result = false;
	}
//echo "return=".$result;
	return $result;

}


function getEnregistrement($virtualPath) {
        if(strlen($virtualPath)>0)
                $nodeId=array_pop(explode(',',$virtualPath));
        else  
                return false;
        global $db;

        $return = array();
        $sql="select *
        from cms_classarbo
        where ca_arbo=$nodeId
        ";
        $rs = $db->Execute($sql);
        if($rs) {
                if(!$rs->EOF) {
                        while (!$rs->EOF) {
						
								$oObjet = new Classe ($rs->fields['ca_classe']);
								$nomObjet = $oObjet->get_nom();
								
								$sql2="select * from ".$oObjet->get_nom()." ";
								$aObjet = dbGetObjectsFromRequete($oObjet->get_nom(), $sql2);
								
								for($i=0;$i<sizeof($aObjet);$i++){
									
									$oObjet=$aObjet[$i];
									if ($oObjet->get_id() == $rs->fields['ca_classeid']) {	
										$idValue= $oObjet->get_id();
										eval ("$"."displayValue = $"."oObjet->get_".strval($oObjet->getDisplay())."();");
										$displayValueShort = substr($displayValue, 0, 50);
										if (strlen($displayValue) > 50 ) $displayValueShort.= " ... ";
										eval ("$"."abstractValue = $"."oObjet->get_".strval($oObjet->getAbstract())."();");
										$abstractValueShort = substr($abstractValue, 0, 50);
										if (strlen($abstractValue) > 50 ) $abstractValueShort.= " ... ";
									}
								}
                                $tmparray = array(
									'nom_classe' => $nomObjet,
									'id_classe' => $rs->fields['ca_classe'],
									'description_enregistrement' => $displayValueShort." - ".$abstractValueShort,
									'id_enregistrement' => $idValue,
									'idArbo' => $rs->fields['ca_arbo'],
									'id' =>  $rs->fields['ca_id']
                                       
                                );
                                array_push($return, $tmparray);
                                $rs->MoveNext();
                        }
                } else {
                        $return=false;
                }
        } else {
                error_log("Plantage lors de l'execution de la requete\n $sql");
                error_log($db->ErrorMsg());
                $return = false;
        }
        return $return;
}

function afficheEnregistrement($idSite, $db,$virtualPath,$full_path_to_curr_id,$destination) {
	$node_id = array_pop(explode(',',$virtualPath));
	$sql= "select * from cms_classarbo where ca_arbo = ".$node_id. "";
	$aClassearbo =  dbGetObjectsFromRequete("cms_classarbo", $sql);
	$str="";
	for ($i=0; $i<sizeof($aClassearbo); $i++) {
		$oClassearbo = $aClassearbo[$i];
		$classeId = $oClassearbo->get_classe();
		$oClasse = new Classe ($classeId);
		$classeName = $oClasse->get_nom();
		//$sInclude = "http://".$_SERVER['SERVER_NAME']."/frontoffice/".$classeName."/foshow_".$classeName.".php?id=".$oClassearbo->get_classeid()."";
		//include($sInclude);
		echo $classeName;
	}
}

function getChemindeFer($idSite, $db, $path, $debutArbo,$url) {
	$arrayChemin = explode('/',$path);
	$cheminToFind = "/";
	$cheminDeFer = "";
	for ($i=0;$i<sizeof($arrayChemin);$i++) {
		if ($arrayChemin[$i]!="") {
			$cheminToFind.= $arrayChemin[$i]."/";
			
			$pathReverse = path2nodesCarte($idSite, $db, $cheminToFind);
			if (in_array($debutArbo,explode(',',$pathReverse))) {
				$cheminDeFer.= "><h2><a href=\"".$url.".php?idSite=".$idSite."&v_comp_path=".$pathReverse."\">".$arrayChemin[$i]."</a></h2>";
			}
		}
	}
	
	return $cheminDeFer;
}

function getChemindeFer2($idSite, $db, $path, $debutArbo) {
	$arrayChemin = explode('/',$path);
	$cheminToFind = "/";
	$cheminDeFer = "";

	
	for ($i=$debutArbo;$i<sizeof($arrayChemin);$i++) {
		if ($arrayChemin[$i]!="") {
			$cheminDeFer.= $arrayChemin[$i]." / ";
		}
	}
	if (substr($cheminDeFer, strlen($cheminDeFer)-2, strlen($cheminDeFer))=="/ ")
		$cheminDeFer = substr($cheminDeFer, 0,strlen($cheminDeFer)-2);
	else if (substr($cheminDeFer, strlen($cheminDeFer)-1, strlen($cheminDeFer))=="/")
		$cheminDeFer = substr($cheminDeFer, 0,strlen($cheminDeFer)-1);
	return $cheminDeFer;
}


function getChemindeFer3($idSite, $db, $path, $virtualPath, $debutArbo, $finArbo, $url, $charReplace) {
	$arrayChemin = explode('/',$path); 
	$arrayIdChemin = explode(',',$virtualPath); 
	$cheminDeFer = ""; 
	$arrayChemin_ = array();
	$virtualPath="";
	for ($i=0;$i<sizeof($arrayIdChemin);$i++) {
		if ($arrayIdChemin[$i]==0) {  
			$virtualPath.="0,";
			$arrayIdChemin[$i] = $arrayIdChemin[$i+1];
		}
		else if ($virtualPath!="") {
			$arrayIdChemin[$i] = $arrayIdChemin[$i+1];
		}
	}
	
	for ($i=0;$i<sizeof($arrayChemin);$i++) {
		if ($arrayChemin[$i]!="") {  
			array_push ($arrayChemin_, $arrayChemin[$i]);
		}
	} 
	
	for ($i=$debutArbo;$i<$finArbo;$i++) {
		if ($arrayChemin_[$i]!="") { 
		if ($i!=$debutArbo) $virtualPath.=",";
			$virtualPath.= $arrayIdChemin[$i]; 
			$cheminDeFer.= $charReplace."<h2><a href=\"".$url.".php?idSite=".$idSite."&v_comp_path=".$virtualPath."\">".$arrayChemin_[$i]."</a></h2>";
		}
	}
	
	return $cheminDeFer;
}



function getChemindeFer4($idSite, $db, $path, $page, $charReplace) {
	 
	$sql = "select * from cms_arbo_pages where node_id = ".$path;
	
	$arrayChemin = "";
	 
	$rs = $db->Execute($sql);
 
	if($rs!=false) { 
		 
			$tmparray = array(
				'id' => $rs->fields[n('node_id')],
				'parent_id' => $rs->fields[n('node_parent_id')],
				'libelle' => $rs->fields[n('node_libelle')],
				'path' => $rs->fields[n('node_absolute_path_name')],
				'order' => $rs->fields[n('node_order')],
				'description' => $rs->fields[n('node_description')]
			); 
			$url = "/content".$tmparray['path'].$page;
			$arrayChemin = "<a href=\"".$url.".php\">".$tmparray['libelle']."</a>";
			$parent_id = $tmparray['parent_id'];
			while ($parent_id!=0) {
				$sql2 = "select * from cms_arbo_pages where node_id = ".$parent_id;
				$rs2 = $db->Execute($sql2);
				if($rs2!=false) { 
					$tmparray2 = array(
						'id' => $rs2->fields[n('node_id')],
						'parent_id' => $rs2->fields[n('node_parent_id')],
						'libelle' => $rs2->fields[n('node_libelle')],
						'path' => $rs2->fields[n('node_absolute_path_name')],
						'order' => $rs2->fields[n('node_order')],
						'description' => $rs2->fields[n('node_description')]
					); 
					$url = "/content".$tmparray2['path'].$page;
					$parent_id = $tmparray2['parent_id'];
					$arrayChemin = "<a href=\"".$url.".php\">".$tmparray2['libelle']."</a>".$charReplace.$arrayChemin;
				} 
			} 
	} else {
		$result=false;
	} 
	 
	
	return $arrayChemin;
}

function getIdByLibelle($idSite, $db, $libelle, $virtualPath, $categorie) {

	$sql= "select * from cms_arbo_classeur where node_libelle='$libelle' and node_id_site = $idSite";

	$rs = $db->Execute($sql);
	$result = array();
	 while($rs!=false && !$rs->EOF) {
		$tmparray = array(
			'id' => $rs->fields['node_id'],
			'libelle' => $rs->fields['node_libelle'],
			'path' => $rs->fields['node_absolute_path_name'],
			'order' => $rs->fields['node_order'],
			'description' => $rs->fields['node_description']
		);
		$pathreverse = getVirtualPathByIdNode($idSite, $db, $tmparray['id']) ;

		if ($categorie=="") 
			array_push($result, $tmparray);
		elseif (in_array($categorie, explode(',',$pathreverse)) && $categorie!="")
			array_push($result, $tmparray);

		$rs->MoveNext(); 
	} 
	
	return $result[0]['id'];

}

function getVirtualPathByIdNode($idSite, $db, $node_id) {

	$sql= "select * from cms_arbo_classeur where node_id=$node_id and node_id_site = $idSite";
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$result = array(
			'id' => $rs->fields['node_id'],
			'libelle' => $rs->fields['node_libelle'],
			'parent' => $rs->fields['node_parent_id'],
			'path' => $rs->fields['node_absolute_path_name'],
			'description' => $rs->fields['node_description']
		);
	} else {
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;		
	}
	
	return path2nodesCarte($idSite, $db, $result['path']);

}

function getBoolLastNode ($node_id, $idSite, $db) {
	
	$sql= "select count(*) as nbNode from cms_arbo_classeur where node_parent_id=$node_id and node_id_site = $idSite";
	$rs = $db->Execute($sql);
	if($rs!=false && !$rs->EOF) {
		$nbNode = $rs->fields['nbNode'];
	} else {
			error_log(" plantage lors de l'execution de la requete ".$sql);
			error_log($db->ErrorMsg());
			$result = false;		
	}
	
	if ($nbNode == 0) return true;
	else return false;
}

function getBoolEnregistrementAssocie ($node_id, $idSite, $db) {
	
	$sql= "select * from cms_arbo_classeur where node_parent_id=$node_id and node_id_site = $idSite";
	$rs = $db->Execute($sql);
	$result = array();
	 while($rs!=false && !$rs->EOF) {
		$tmparray = array(
			'id' => $rs->fields['node_id'],
			'libelle' => $rs->fields['node_libelle'],
			'path' => $rs->fields['node_absolute_path_name'],
			'order' => $rs->fields['node_order'],
			'description' => $rs->fields['node_description']
		);
		$pathreverse = getVirtualPathByIdNode($idSite, $db, $tmparray['id']) ;

		if ($categorie=="") 
			array_push($result, $tmparray);
		elseif (in_array($categorie, explode(',',$pathreverse)) && $categorie!="")
			array_push($result, $tmparray);

		$rs->MoveNext(); 
	} 
	$nombreEnregistrement=0;
	for ($i=0;$i<sizeof($result);$i++) {
		$sql = "select * from cms_classarbo where ca_arbo=".$result[$i]['id']."";
			$aClassearbo =  dbGetObjectsFromRequeteID("cms_classarbo", $sql);
			
			if (sizeof($aClassearbo) > 0) {
				$oClassearbo = $aClassearbo[0];
				$classeId = $oClassearbo->get_classe();
				$idEnregistrement = $oClassearbo->get_classeid();
				
				$oClasse = dbGetObjectFromPK("classe", $classeId);
				$classeName = $oClasse->get_nom();
				$classePrefixe = "com";
				$sql2 = "select * from ".$classeName." where ".$classePrefixe."_statut=4 and ".$classePrefixe."_id=".$idEnregistrement;
				$aClasse =  dbGetObjectsFromRequete($classeName, $sql2);
				$nombreEnregistrement = $nombreEnregistrement+sizeof($aClasse);
			}
	}
	if ($nombreEnregistrement == 0) return false;
	else return true;
}


function getLienRecherche($idSite, $db, $path, $debutArbo,$url, $classe, $idComposant) {
	$arrayChemin = explode('/',$path);
	$cheminToFind = "/";
	$cheminDeFer = "";
	for ($i=0;$i<sizeof($arrayChemin);$i++) {
		if ($arrayChemin[$i]!="") {
			$cheminToFind.= $arrayChemin[$i]."/";
			$pathReverse = path2nodesCarte($idSite, $db, $cheminToFind);
			if (in_array($debutArbo,explode(',',$pathReverse))) {
				$cheminDeFer.= "><h2>".$arrayChemin[$i]."</h2>";
				
			}
		}
	}
	
	$oComposant = dbGetObjectFromPK($classe, $idComposant);
	$sDescription = substr($oComposant->get_texte(), 0, 230)." ...";

	$cheminDeFer="<a href=\"\" onclick=\"javascript:go_n_close_ifpage('".$url.".php?idSite=".$idSite."&id=".$idComposant."&v_comp_path=".$pathReverse."')\">".ltrim($cheminDeFer)."</a><br>";
	$cheminDeFer.="<p style=\"margin:0 0 0 0;padding-bottom:5px; color:#888888\">".$sDescription."</p>";
	return $cheminDeFer;
}
?>