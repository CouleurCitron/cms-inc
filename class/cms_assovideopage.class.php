<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assovideopage.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_assovideopage.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assovideopage.class.php');
}else{
/*======================================

objet de BDD cms_assovideopage :: class cms_assovideopage

SQL mySQL:

DROP TABLE IF EXISTS cms_assovideopage;
CREATE TABLE cms_assovideopage
(
	xvp_id			int (11) PRIMARY KEY not null,
	xvp_cms_page			int,
	xvp_cms_video			int (11)
)

SQL Oracle:

DROP TABLE cms_assovideopage
CREATE TABLE cms_assovideopage
(
	xvp_id			number (11) constraint xvp_pk PRIMARY KEY not null,
	xvp_cms_page			number,
	xvp_cms_video			number (11)
)


<?xml version="1.0" encoding="iso-8859-1"?>
<class name="cms_assovideopage" is_asso="true" libelle="liens vidéo et page" prefix="xvp" display="cms_page" abstract="cms_video">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_page" libelle="Page liée" type="int" default="0" order="true" list="true" fkey="cms_page" />
<item name="cms_video" libelle="Vidéo liée" type="int" length="11" order="true" list="true" fkey="cms_video" /> 
</class>


==========================================*/

class cms_assovideopage
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $cms_page;
var $cms_video;


var $XML = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<class name=\"cms_assovideopage\" is_asso=\"true\" libelle=\"liens vidéo et page\" prefix=\"xvp\" display=\"cms_page\" abstract=\"cms_video\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_page\" libelle=\"Page liée\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_page\" />
<item name=\"cms_video\" libelle=\"Vidéo liée\" type=\"int\" length=\"11\" order=\"true\" list=\"true\" fkey=\"cms_video\" /> 
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_assovideopage
(
	xvp_id			int (11) PRIMARY KEY not null,
	xvp_cms_page			int,
	xvp_cms_video			int (11)
)

";

// constructeur
function cms_assovideopage($id=null)
{
	if (istable(get_class($this)) == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
		if(array_key_exists('0',$this->inherited_list)){
			foreach($this->inherited_list as $class){
				if(!is_object($class))
					$this->inherited[$class] = dbGetObjectFromPK($class, $id);
			}
		}
	} else {
		$this->id = -1;
		$this->cms_page = -1;
		$this->cms_video = -1;
		if(array_key_exists('0',$this->inherited_list)){
			foreach($this->inherited_list as $class){
				if(!is_object($class))
					$this->inherited[$class] = new $class();
			}
		}
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xvp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xvp_cms_page", "entier", "get_cms_page", "set_cms_page");
	$laListeChamps[]=new dbChamp("Xvp_cms_video", "entier", "get_cms_video", "set_cms_video");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_page() { return($this->cms_page); }
function get_cms_video() { return($this->cms_video); }


// setters
function set_id($c_xvp_id) { return($this->id=$c_xvp_id); }
function set_cms_page($c_xvp_cms_page) { return($this->cms_page=$c_xvp_cms_page); }
function set_cms_video($c_xvp_cms_video) { return($this->cms_video=$c_xvp_cms_video); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xvp_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assovideopage"); }
function getClasse() { return("cms_assovideopage"); }
function getDisplay() { return("cms_page"); }
function getAbstract() { return("cms_video"); }


} //class


}
?>