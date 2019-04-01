<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('cms_infos_pages')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_infos_pages`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('cms_infos_pages', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_infos_pages` ADD `page_thumb` VARCHAR( 256 ) NOT NULL ; ;");
		}
	}
}
/* [End patch] */
/*======================================

sponthus 01/06/05
objet de BDD cms_infos_page :: class Cms_infos_page

function Cms_infos_page() 
function initValues($id) 
 
==========================================*/

class Cms_infos_page
{

var $id;
var $page_id;
var $page_titre;
var $page_motsclefs;
var $page_description;
var $page_thumb;

// constructeur
function __construct() 
{
	$this->id = -1;
	$this->page_id = -1;
	$this->page_titre = "";
	$this->page_motsclefs = "";
	$this->page_description = "";
	$this->page_thumb = "";
}

// liste des champs de l'objet
function getListeChamps(){
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("id", "entier", "getId", "setId");
	$laListeChamps[]=new dbChamp("page_id", "entier", "getPage_id", "setPage_id");
	$laListeChamps[]=new dbChamp("page_titre", "text", "getPage_titre", "setPage_titre");
	$laListeChamps[]=new dbChamp("page_motsclefs", "text", "getPage_motsclefs", "setPage_motsclefs");
	$laListeChamps[]=new dbChamp("page_description", "text", "getPage_description", "setPage_description");
	$laListeChamps[]=new dbChamp("page_thumb", "text", "getPage_thumb", "setPage_thumb");
	return $laListeChamps;
}

// getters
function getId() { return($this->id); } 
function get_id() { return($this->id); } 
function getPage_id() { return($this->page_id); } 
function getPage_titre() { return($this->page_titre); } 
function getPage_motsclefs() { return($this->page_motsclefs); } 
function getPage_description() { return($this->page_description); } 
function getPage_thumb() { return($this->page_thumb); } 

// setters
function setId($c_id) { return($this->id=$c_id); } 
function set_id($c_id) { return($this->id=$c_id); } 
function setPage_id($c_page_id) { return($this->page_id=$c_page_id); } 
function setPage_titre($c_page_titre) { return($this->page_titre=$c_page_titre); } 
function setPage_motsclefs($c_page_motsclefs) { return($this->page_motsclefs=$c_page_motsclefs); } 
function setPage_description($c_page_description) { return($this->page_description=$c_page_description); } 
function setPage_thumb($c_page_thumb) { return($this->page_thumb=$c_page_thumb); } 

// autres getters
function getGetterPK() { return("getId"); }
function getSetterPK() { return("setId"); }
function getFieldPK() { return("id"); }
function getTable() { return("cms_infos_pages"); }
function getClasse() { return("Cms_infos_page"); }

// get objet avec idPage
function getCmsinfospages_with_pageid($id) 
{
	global $db;
	$result = true;

	$sql = " SELECT id, page_id, page_titre, page_motsclefs, page_description, page_thumb";
	$sql.= " FROM cms_infos_pages";
	$sql.= " WHERE page_id = $id";
	if (DEF_BDD != "ORACLE") $sql.= ";";

	//print("<br>$sql");

	$rs = $db->Execute($sql);
	if($rs && !$rs->EOF) {
		$this->id = $rs->fields[n('id')];			
		$this->page_id = $rs->fields[n('page_id')];
		$this->page_titre = $rs->fields[n('page_titre')];			
		$this->page_motsclefs = $rs->fields[n('page_motsclefs')];
		$this->page_description = $rs->fields[n('page_description')];		
		$this->page_thumb = $rs->fields[n('page_thumb')];			
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_infos_pages.class.php > getCmsinfospages_with_pageid";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);
	  $result = false;
	}
	$rs->Close();
	return $result;
}


} //class 

?>