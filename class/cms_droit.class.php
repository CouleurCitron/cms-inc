<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
sponthus 07/07/2005
classe droit représentant un enregistrement de la table cms_droit

*/

class Droit
{

// attributs de la classe
var $id_droit;
var $id_content;
var $user_id;

// constructeur
function Droit($id=null)
{
	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id_droit = -1;
		$this->id_content = -1;
		$this->user_id = -1;
	}
}

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("id_droit", "entier", "getId_droit", "setId_droit");
	$laListeChamps[]=new dbChamp("id_content", "entier", "getId_content", "setId_content");
	$laListeChamps[]=new dbChamp("user_id", "entier", "getUser_id", "setUser_id");

	return($laListeChamps);
}

// getters
function getId_droit() { return($this->id_droit); } 
function getId_content() { return($this->id_content); } 
function getUser_id() { return($this->user_id); } 

// setters
function setId_droit($c_id_droit) { return($this->id_droit=$c_id_droit); } 
function setId_content($c_id_content) { return($this->id_content=$c_id_content); } 
function setUser_id($c_user_id) { return($this->user_id=$c_user_id); } 

// autres getters
function getGetterPK() { return("getId_droit"); }
function getSetterPK() { return("setId_droit"); }
function getFieldPK() { return("id_droit"); }
function getTable() { return("cms_droit"); }
function getClasse() { return("Cms_droit"); }


} //class 

// donne le droit attribué à une brique
function getDroit($idContent)
{
	$aNameGetter[] = "getId_content";
	$aValueGetter[] = $idContent;

	$aDroits = dbGetObjectsFromFieldValue("Droit", $aNameGetter, $aValueGetter, "getId_content");

	if (sizeof($aDroits) == 1) $result = $aDroits[0];
	else $result = new Droit();

	return $result;
}

?>
