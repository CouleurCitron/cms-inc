<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 01/06/05
objet de BDD Cms_champform :: class Cms_champform

function deleteAllChampsForm($id)

==========================================*/

$rs = $db->Execute('DESCRIBE `cms_champform`');
if($rs!=false && !$rs->EOF) { 
	while(!$rs->EOF) {
		$Field = $rs->fields['Field'];
		$Type = $rs->fields['Type'];
		
		if ($Field == "type_champ" && $Type  == "varchar(5)") {
			$rs2 = $db->Execute('ALTER TABLE `cms_champform` CHANGE `type_champ` `type_champ` VARCHAR( 10 ) NOT NULL DEFAULT \'\';'); 	 
		}
		$rs->MoveNext();
	}
}	 
 
if (isset($rs->_numOfRows)){ 
	if ($rs->_numOfRows == 14){
		$rs2 = $db->Execute('ALTER TABLE `cms_champform` ADD `params_champ` TEXT  DEFAULT \'\' AFTER `texte_champ` ;'); 	 
		$rs2 = $db->Execute('ALTER TABLE `cms_champform` ADD `pattern_champ` TEXT  DEFAULT \'\' AFTER `params_champ` ;'); 	
	}
	elseif  ($rs->_numOfRows == 15){
		$rs2 = $db->Execute('ALTER TABLE `cms_champform` ADD `pattern_champ` TEXT  DEFAULT \'\' AFTER `params_champ` ;'); 	
	}
} 
 

class Cms_champform
{

var $id_champ;
var $id_form;
var $name_champ;
var $desc_champ;
var $type_champ;
var $valeur_champ;
var $valdefaut_champ;
var $largeur_champ;
var $hauteur_champ;
var $taillemax_champ;
var $obligatoire_champ;
var $objet_champ;
var $objetid_champ;
var $texte_champ;
var $params_champ;
var $pattern_champ;

// constructeur
function __construct($id=null) 
{
	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;

	} else {
		$this->id_champ=-1;
		$this->id_form=-1;
		$this->name_champ='';
		$this->desc_champ='';
		$this->type_champ='INUTIL';
		$this->valeur_champ='';
		$this->valdefaut_champ='';
		$this->largeur_champ=0;
		$this->hauteur_champ=0;
		$this->taillemax_champ=0;
		$this->obligatoire_champ=0;
		$this->objet_champ="";
		$this->objetid_champ=-1;
		$this->texte_champ="";
		$this->params_champ="";
		$this->pattern_champ="";
	}
}

// getters
function getId_champ() { return($this->id_champ); } 
function getId_form() { return($this->id_form); } 
function getName_champ() { return($this->name_champ); } 
function getDesc_champ() { return($this->desc_champ); } 
function getType_champ() { return($this->type_champ); } 
function getValeur_champ() { return($this->valeur_champ); } 
function getValdefaut_champ() { return($this->valdefaut_champ); } 
function getLargeur_champ() { return($this->largeur_champ); } 
function getHauteur_champ() { return($this->hauteur_champ); } 
function getTaillemax_champ() { return($this->taillemax_champ); } 
function getObligatoire_champ() { return($this->obligatoire_champ); } 
function getObjet_champ() { return($this->objet_champ); } 
function getObjetId_champ() { return($this->objetid_champ); } 
function getTexte_champ() { return($this->texte_champ); } 
function getParams_champ() { return($this->params_champ); } 
function getPattern_champ() { return($this->pattern_champ); } 

// setters
function setId_champ($c_id_champ) { return($this->id_champ=$c_id_champ); } 
function setId_form($c_id_form) { return($this->id_form=$c_id_form); } 
function setName_champ($c_name_champ) { return($this->name_champ=$c_name_champ); } 
function setDesc_champ($c_desc_champ) { return($this->desc_champ=$c_desc_champ); } 
function setType_champ($c_type_champ) { return($this->type_champ=$c_type_champ); } 
function setValeur_champ($c_valeur_champ) { return($this->valeur_champ=$c_valeur_champ); } 
function setValdefaut_champ($c_valdefaut_champ) { return($this->valdefaut_champ=$c_valdefaut_champ); } 
function setLargeur_champ($c_largeur_champ) { return($this->largeur_champ=$c_largeur_champ); } 
function setHauteur_champ($c_hauteur_champ) { return($this->hauteur_champ=$c_hauteur_champ); } 
function setTaillemax_champ($c_taillemax_champ) { return($this->taillemax_champ=$c_taillemax_champ); } 
function setObligatoire_champ($c_obligatoire_champ) { return($this->obligatoire_champ=$c_obligatoire_champ); } 
function setObjet_champ($c_objet_champ) { return($this->objet_champ=$c_objet_champ); } 
function setObjetid_champ($c_objetid_champ) { return($this->objetid_champ=$c_objetid_champ); } 
function setTexte_champ($c_texte_champ) { return($this->texte_champ=$c_texte_champ); } 
function setParams_champ($c_params_champ) { return($this->params_champ=$c_params_champ); } 
function setPattern_champ($c_pattern_champ) { return($this->pattern_champ=$c_pattern_champ); } 

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("id_champ", "entier", "getId_champ", "setId_champ");
	$laListeChamps[]=new dbChamp("id_form", "entier", "getId_form", "setId_form");
	$laListeChamps[]=new dbChamp("name_champ", "text", "getName_champ", "setName_champ");
	$laListeChamps[]=new dbChamp("desc_champ", "text", "getDesc_champ", "setDesc_champ");
	$laListeChamps[]=new dbChamp("type_champ", "text", "getType_champ", "setType_champ");
	$laListeChamps[]=new dbChamp("valeur_champ", "text", "getValeur_champ", "setValeur_champ");
	$laListeChamps[]=new dbChamp("valdefaut_champ", "text", "getValdefaut_champ", "setValdefaut_champ");								
	$laListeChamps[]=new dbChamp("largeur_champ", "entier", "getLargeur_champ", "setLargeur_champ");								
	$laListeChamps[]=new dbChamp("hauteur_champ", "entier", "getHauteur_champ", "setHauteur_champ");								
	$laListeChamps[]=new dbChamp("taillemax_champ", "entier", "getTaillemax_champ", "setTaillemax_champ");								
	$laListeChamps[]=new dbChamp("obligatoire_champ", "entier", "getObligatoire_champ", "setObligatoire_champ");	
	$laListeChamps[]=new dbChamp("objet_champ", "text", "getObjet_champ", "setObjet_champ");								
	$laListeChamps[]=new dbChamp("objetid_champ", "entier", "getObjetid_champ", "setObjetid_champ");								
	$laListeChamps[]=new dbChamp("texte_champ", "text", "getTexte_champ", "setTexte_champ");	
	$laListeChamps[]=new dbChamp("params_champ", "text", "getParams_champ", "setParams_champ");		
	$laListeChamps[]=new dbChamp("pattern_champ", "text", "getPattern_champ", "setPattern_champ");						
		
	return($laListeChamps);
}

// autres getters
function getGetterPK() { return("getId_champ"); }
function getSetterPK() { return("setId_champ"); }
function getFieldPK() { return("id_champ"); }
function getTable() { return("cms_champform"); }
function getClasse() { return("Cms_champform"); }

} //class 

// suppression de tous les champs d'un formulaire

function deleteAllChampsForm($id)
{
	global $db;
    $result = null;

	$sql = "DELETE FROM cms_champform WHERE id_form=".$id;

//print("<br>sql=".$sql);
//exit();

	$rs = $db->Execute($sql);

    if($rs != false) {

      $result = true;
		
    } else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />deleteAllChampsForm($id)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
    }
	$rs->Close();
    return $result;
}

?>