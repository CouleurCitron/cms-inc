<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 05/10/05
objet de BDD Cms_valeurform :: class Cms_valeurform

==========================================*/

/*
CREATE TABLE CMS_VALEURFORM (
ID_VAL NUMBER PRIMARY KEY,
ID_REP NUMBER,
ID_FORM NUMBER,
ID_CHAMP NUMBER,
VALEUR_VAL CLOB);
*/

class Cms_valeurform
{

var $id_val;
var $id_rep;
var $id_form;
var $id_champ;
var $valeur_val;


// constructeur
function Cms_valeurform($id=null) 
{
	if($id!=null) {
		$this = dbGetObjectFromPK("Cms_valeurform", $id);
	} else {
		$this->id_val=-1;
		$this->id_rep=-1;
		$this->id_form=-1;
		$this->id_champ=-1;
		$this->valeur_val=-1;
	}
}

// getters
function getId_val() { return($this->id_val); } 
function getId_rep() { return($this->id_rep); } 
function getId_form() { return($this->id_form); } 
function getId_champ() { return($this->id_champ); } 
function getValeur_val() { return($this->valeur_val); } 


// setters
function setId_val($c_id_val) { return($this->id_val=$c_id_val); } 
function setId_rep($c_id_rep) { return($this->id_rep=$c_id_rep); } 
function setId_form($c_id_form) { return($this->id_form=$c_id_form); } 
function setId_champ($c_id_champ) { return($this->id_champ=$c_id_champ); } 
function setValeur_val($c_valeur_val) { return($this->valeur_val=$c_valeur_val); } 


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("id_val", "entier", "getId_val", "setId_val");
	$laListeChamps[]=new dbChamp("id_rep", "entier", "getId_rep", "setId_rep");
	$laListeChamps[]=new dbChamp("id_form", "entier", "getId_form", "setId_form");
	$laListeChamps[]=new dbChamp("id_champ", "entier", "getId_champ", "setId_champ");
	$laListeChamps[]=new dbChamp("valeur_val", "entier", "getValeur_val", "setValeur_val");

		
	return($laListeChamps);
}

// autres getters
function getGetterPK() { return("getId_val"); }
function getSetterPK() { return("setId_val"); }
function getFieldPK() { return("id_val"); }
function getTable() { return("Cms_valeurform"); }
function getClasse() { return("Cms_valeurform"); }

} //class 