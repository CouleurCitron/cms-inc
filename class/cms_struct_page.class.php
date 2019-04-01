<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 01/06/05
objet de BDD cms_struct_page :: class Cms_struct_page

function Cms_struct_page() 
function initValues($id) 
function getObjet($idPage, $idContent) 
function cms_struct_page_insert()
function cms_struct_page_update() 
function cms_struct_page_delete() 
function cms_struct_page_move() 
function getObjetWithZonedit($idZonedit) 
function getObjetWithContentPage($idContent, $idPage) 
function getOneObjetWithPageZonedit($idPage, $idZonedit) 

==========================================*/

class Cms_struct_page
{

var $id_struct;
var $id_page;
var $id_content;
var $width_content;
var $height_content;
var $top_content;
var $left_content;
var $opacity_content;
var $zindex_content;
var $id_zonedit_content;

// constructeur
function Cms_struct_page($id=null) 
{
	if($id!=null) {
		$this->initValues($id);
	} else {
		$this->id_struct=-1;
		$this->id_page=-1;	
		$this->id_content=-1;
		$this->width_content=-1;	
		$this->height_content=-1;
		$this->top_content=-1;	
		$this->left_content=-1;
		$this->opacity_content=-1;	
		$this->zindex_content=-1;
		$this->id_zonedit_content=-1;
	}

}

// initialisation, obtention d'un  objet cms_struct
function initValues($id) 
{
		global $db;
		$result = true;

		$sql = " SELECT *";
		$sql.= " FROM cms_struct_page";
		$sql.= " WHERE id_struct = $id";
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);
		if($rs && !$rs->EOF) {

			$this->id_struct = $rs->fields[n('id_struct')];
			$this->id_page = $rs->fields[n('id_page')];
			$this->id_content = $rs->fields[n('id_content')];
			$this->width_content = $rs->fields[n('width_content')];
			$this->height_content = $rs->fields[n('height_content')];
			$this->top_content = $rs->fields[n('top_content')];
			$this->left_content = $rs->fields[n('left_content')];
			$this->opacity_content = $rs->fields[n('opacity_content')];
			$this->zindex_content = $rs->fields[n('zindex_content')];
			$this->id_zonedit_content = $rs->fields[n('id_zonedit_content')];			

		} else {
			if (DEF_MODE_DEBUG) {
				echo "<br />cms_struct.class.php > initValues";
				echo "<br /><strong>$sql</strong>";
			}
			echo "<br />Erreur interne de programme";
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}

// obtention d'un objet cms_struct à partir de l'id page et de l'id brique
function getObjet($idPage, $idContent) 
{
		global $db;

		$sql = " SELECT *";
		$sql.= " FROM cms_struct_page";
		$sql.= " WHERE id_content = $idContent AND id_page=$idPage";
		if (DEF_BDD != "ORACLE") $sql.= ";";


		$rs = $db->Execute($sql);
		if($rs && !$rs->EOF) {

			$this->id_struct = $rs->fields[n('id_struct')];
			$this->id_page = $rs->fields[n('id_page')];
			$this->id_content = $rs->fields[n('id_content')];
			$this->width_content = $rs->fields[n('width_content')];
			$this->height_content = $rs->fields[n('height_content')];
			$this->top_content = $rs->fields[n('top_content')];
			$this->left_content = $rs->fields[n('left_content')];
			$this->opacity_content = $rs->fields[n('opacity_content')];
			$this->zindex_content = $rs->fields[n('zindex_content')];
			$this->id_zonedit_content = $rs->fields[n('id_zonedit_content')];			

		} else {
			if (DEF_MODE_DEBUG) {
				echo "<br />cms_struct.class.php > initValues";
				echo "<br /><strong>$sql</strong>";
			}
			echo "<br />Erreur interne de programme";
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("id_struct", "entier", "getId_struct", "setId_struct");
	$laListeChamps[]=new dbChamp("id_page", "entier", "getId_page", "setId_page");
	$laListeChamps[]=new dbChamp("id_content", "entier", "getId_content", "setId_content");
	$laListeChamps[]=new dbChamp("width_content", "entier", "getWidth_content", "setWidth_content");
	$laListeChamps[]=new dbChamp("height_content", "entier", "getHeight_content", "setHeight_content");
	$laListeChamps[]=new dbChamp("top_content", "entier", "getTop_content", "setTop_content");
	$laListeChamps[]=new dbChamp("left_content", "entier", "getLeft_content", "setLeft_content");
	$laListeChamps[]=new dbChamp("opacity_content", "entier", "getOpacity_content", "setOpacity_content");
	$laListeChamps[]=new dbChamp("zindex_content", "entier", "getZindex_content", "setZindex_content");
	$laListeChamps[]=new dbChamp("id_zonedit_content", "entier", "getId_zonedit_content", "setId_zonedit_content");
	
	return($laListeChamps);
}

// getters
function getId_struct() { return($this->id_struct); } 
function get_id() { return($this->id_struct); } 
function getId_page() { return($this->id_page); } 
function getId_content() { return($this->id_content); } 
function getWidth_content() { return($this->width_content); } 
function getHeight_content() { return($this->height_content); } 
function getTop_content() { return($this->top_content); } 
function getLeft_content() { return($this->left_content); } 
function getOpacity_content() { return($this->opacity_content); } 
function getZindex_content() { return($this->zindex_content); } 
function getId_zonedit_content() { return($this->id_zonedit_content); } 

// setters
function setId_struct($c_id_struct) { return($this->id_struct=$c_id_struct); } 
function set_id($c_id_struct) { return($this->id_struct=$c_id_struct); } 
function setId_page($c_id_page) { return($this->id_page=$c_id_page); } 
function setId_content($c_id_content) { return($this->id_content=$c_id_content); } 
function setWidth_content($c_width_content) { return($this->width_content=$c_width_content); } 
function setHeight_content($c_height_content) { return($this->height_content=$c_height_content); } 
function setTop_content($c_top_content) { return($this->top_content=$c_top_content); } 
function setLeft_content($c_left_content) { return($this->left_content=$c_left_content); } 
function setOpacity_content($c_opacity_content) { return($this->opacity_content=$c_opacity_content); } 
function setZindex_content($c_zindex_content) { return($this->zindex_content=$c_zindex_content); } 
function setId_zonedit_content($c_id_zonedit_content) { return($this->id_zonedit_content=$c_id_zonedit_content); } 

// autres getters
function getGetterPK() { return("getId_struct"); }
function getSetterPK() { return("setId_struct"); }
function getFieldPK() { return("id_struct"); }
function getTable() { return("cms_struct_page"); }
function getClasse() { return("Cms_struct_page"); }



// INSERT
function cms_struct_page_insert()
{
	global $db;
    $result = null;
	
	// ATTENTION
	// tous les champs text doivent être envoyés avec to_char (pas de cote)
	// date omises
	
    $sql = " INSERT INTO cms_struct_page (";
	$sql.= " id_struct, id_page, id_content, ";
	$sql.= " width_content, height_content, ";
	$sql.= " top_content, left_content, ";
	$sql.= " opacity_content, zindex_content, id_zonedit_content) ";
	$sql.= " VALUES (";
	$sql.= " ".$this->id_struct.", ".$this->id_page.", ".$this->id_content.",";
	$sql.= " ".$this->width_content.", ".$this->height_content.", ";
	$sql.= " ".$this->top_content.", ".$this->left_content.", ";
	$sql.= " ".$this->opacity_content.", ".$this->zindex_content.", ".$this->id_zonedit_content.")";
	if (DEF_BDD != "ORACLE") $sql.";";
//print("<br>$sql");

	$rs = $db->Execute($sql);

    if($rs != false) {
      $result = $this->id_struct;
    } else {
			error_log("--------------------------------------------------------------------------");
			error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log("--------------------------------------------------------------------------");
      $result = false;
    }
	$rs->Close();
    return $result;
}

} //class 

// obtention d'un objet cms_struct à partir de l'id de la zone editable
function getObjetWithZonedit($idZonedit) 
{
		global $db;
		$result = array();

		$sql = " SELECT *";
		$sql.= " FROM cms_struct_page";
		$sql.= " WHERE id_zonedit_content = $idZonedit";
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);
		
		if($rs) {
			while(!$rs->EOF) {

				$oStruct = new Cms_struct_page();
				$oStruct->initValues($rs->fields[n('id_struct')]);
				array_push($result, $oStruct);
					
				$rs->MoveNext();
			}
		} else {
			if (DEF_MODE_DEBUG) {
				echo "<br />cms_struct.class.php > getObjetWithZonedit";
				echo "<br /><strong>$sql</strong>";
			}
			echo "<br />Erreur interne de programme";
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}


// obtention d'un objet cms_struct à partir de l'id du content et de l'id de la page
function getObjetWithContentPage($idContent, $idPage) 
{
		global $db;
		$result = array();

		$sql = " SELECT *";
		$sql.= " FROM cms_struct_page";
		$sql.= " WHERE id_content = ".$idContent;
		$sql.= " AND id_page = ".$idPage;
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {

				$oStruct = new Cms_struct_page();
				$oStruct->initValues($rs->fields[n('id_struct')]);
				array_push($result, $oStruct);
					
				$rs->MoveNext();
			}
		} else {
			if (DEF_MODE_DEBUG) {
				echo "<br />cms_struct.class.php > getObjetWithZonedit";
				echo "<br /><strong>$sql</strong>";
			}
			echo "<br />Erreur interne de programme";
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}

// obtention d'un objet cms_struct à partir de l'id du content et de l'id de la page
// à la différence de la fonction ci dessus
// cette fonction s'assure que la brique est une brique editable
function getObjetWithContentBEditPage($idContent, $idPage) 
{
		global $db;
		$result = array();

		$sql = " SELECT *";
		$sql.= " FROM cms_struct_page";
		$sql.= " WHERE id_content = ".$idContent;
		$sql.= " AND id_page = ".$idPage;

		if (DEF_BDD == "ORACLE" || DEF_BDD == "POSTGRES") $sql.= " AND NOT id_zonedit_content = -1";
		else if (DEF_BDD == "MYSQL") $sql.= " AND id_zonedit_content <> -1";

		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);

		if($rs) {
			while(!$rs->EOF) {

				$oStruct = new Cms_struct_page();
				$oStruct->initValues($rs->fields[n('id_struct')]);
				array_push($result, $oStruct);
					
				$rs->MoveNext();
			}
		} else {
			if (DEF_MODE_DEBUG) {
				echo "<br />cms_struct.class.php > getObjetWithZonedit";
				echo "<br /><strong>$sql</strong>";
			}
			echo "<br />Erreur interne de programme";
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}


// obtention d'un objet cms_struct à partir de l'id du content BRIQUE EDITABLE
// cette fonction ne renvoie qu'un seul cms_struct 
// CAR une brique editable n'appartient qu'à UNE seule et une seule page
function getObjetWithContentBEdit($idContent) 
{
		global $db;
		$result = array();

		$sql = " SELECT *";
		$sql.= " FROM cms_struct_page";
		$sql.= " WHERE id_content = ".$idContent;

		if (DEF_BDD == "ORACLE" || DEF_BDD == "POSTGRES") $sql.= " AND NOT id_zonedit_content = -1";
		else if (DEF_BDD == "MYSQL") $sql.= " AND id_zonedit_content <> -1";
		
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {

				$oStruct = new Cms_struct_page();
				$oStruct->initValues($rs->fields[n('id_struct')]);
				array_push($result, $oStruct);
					
				$rs->MoveNext();
			}
		} else {
			if (DEF_MODE_DEBUG) {
				echo "<br />cms_struct.class.php > getObjetWithZonedit";
				echo "<br /><strong>$sql</strong>";
			}
			echo "<br />Erreur interne de programme";
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}



// obtention d'un SEUL objet cms_struct à partir de l'id de la zone editable
// en option limite la recherche aux briques dont le noeud est >-1
function getOneObjetWithPageZonedit($idPage, $idZonedit) 
{
	// obtention d'un objet (ou plusieurs dans un tableau)
	// cms_struct à partir de l'id de la zone editable
	$aStruct = getObjetWithZonedit($idZonedit);

	if(sizeof($aStruct)>0) {
		$oStruct = new Cms_struct_page();
		
		// Recherche de la liaison dans la page en cours
		// et Récupération de la brique existante
		for($i=0;$i<sizeof($aStruct);$i++) {
			$oStruct = $aStruct[$i];
			if($oStruct->id_page == $idPage)
				break;
		}
	
		$oBrique = new Cms_content( $oStruct->getId_content() );
		
		return $oBrique;
	}
	else return false;
}

?>