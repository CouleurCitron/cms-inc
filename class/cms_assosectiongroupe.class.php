<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assosectiongroupe :: class cms_assosectiongroupe

SQL mySQL:

DROP TABLE IF EXISTS cms_assosectiongroupe;
CREATE TABLE cms_assosectiongroupe
(
	xsg_id			int (11) PRIMARY KEY not null,
	xsg_cms_sectionbo			int,
	xsg_bo_groupes			int
)

SQL Oracle:

DROP TABLE cms_assosectiongroupe
CREATE TABLE cms_assosectiongroupe
(
	xsg_id			number (11) constraint xsg_pk PRIMARY KEY not null,
	xsg_cms_sectionbo			number,
	xsg_bo_groupes			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assosectiongroupe" is_asso="true" prefix="xsg" display="cms_sectionbo" abstract="bo_groupes">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_sectionbo" type="int" default="0" order="true" fkey="cms_sectionbo" />
<item name="bo_groupes" type="int" default="0" order="true" fkey="bo_groupes" />
</class>


==========================================*/

class cms_assosectiongroupe
{
var $id;
var $cms_sectionbo;
var $bo_groupes;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assosectiongroupe\" is_asso=\"true\" prefix=\"xsg\" display=\"cms_sectionbo\" abstract=\"bo_groupes\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_sectionbo\" type=\"int\" default=\"0\" order=\"true\" fkey=\"cms_sectionbo\" />
<item name=\"bo_groupes\" type=\"int\" default=\"0\" order=\"true\" fkey=\"bo_groupes\" />
</class>";

var $sMySql = "CREATE TABLE cms_assosectiongroupe
(
	xsg_id			int (11) PRIMARY KEY not null,
	xsg_cms_sectionbo			int,
	xsg_bo_groupes			int
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_assosectiongroupe") == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id = -1;
		$this->cms_sectionbo = -1;
		$this->bo_groupes = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xsg_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xsg_cms_sectionbo", "entier", "get_cms_sectionbo", "set_cms_sectionbo");
	$laListeChamps[]=new dbChamp("Xsg_bo_groupes", "entier", "get_bo_groupes", "set_bo_groupes");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_sectionbo() { return($this->cms_sectionbo); }
function get_bo_groupes() { return($this->bo_groupes); }


// setters
function set_id($c_xsg_id) { return($this->id=$c_xsg_id); }
function set_cms_sectionbo($c_xsg_cms_sectionbo) { return($this->cms_sectionbo=$c_xsg_cms_sectionbo); }
function set_bo_groupes($c_xsg_bo_groupes) { return($this->bo_groupes=$c_xsg_bo_groupes); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xsg_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assosectiongroupe"); }
function getClasse() { return("cms_assosectiongroupe"); }
function getDisplay() { return("cms_sectionbo"); }
function getAbstract() { return("bo_groupes"); }


} //class

?>