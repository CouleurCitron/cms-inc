<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_assooffrepdf.class.php')  && (strpos(__FILE__,'/include/bo/class/job_assooffrepdf.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_assooffrepdf.class.php');
}else{
/*======================================

objet de BDD job_assooffrepdf :: class job_assooffrepdf

SQL mySQL:

DROP TABLE IF EXISTS job_assooffrepdf;
CREATE TABLE job_assooffrepdf
(
	job_id			int (11) PRIMARY KEY not null,
	job_offre			int,
	job_cms_pdf			int
)

SQL Oracle:

DROP TABLE job_assooffrepdf
CREATE TABLE job_assooffrepdf
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_offre			number,
	job_cms_pdf			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_assooffrepdf" libelle="Documents associés"  prefix="job" display="offre" abstract="cms_pdf" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="offre" type="int" default="0" order="true"  fkey="job_offre" />
<item name="cms_pdf" type="int" default="0" order="true"  fkey="cms_pdf" />
</class>


==========================================*/

class job_assooffrepdf
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $offre;
var $cms_pdf;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_assooffrepdf\" libelle=\"Documents associés\" prefix=\"job\" display=\"offre\" abstract=\"cms_pdf\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"offre\" type=\"int\" default=\"0\" order=\"true\"  fkey=\"job_offre\" />
<item name=\"cms_pdf\" type=\"int\" default=\"0\" order=\"true\"  fkey=\"cms_pdf\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_assooffrepdf
(
	job_id			int (11) PRIMARY KEY not null,
	job_offre			int,
	job_cms_pdf			int
)

";

// constructeur
function __construct($id=null)
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
		$this->offre = -1;
		$this->cms_pdf = -1;
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
	$laListeChamps[]=new dbChamp("Job_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Job_offre", "entier", "get_offre", "set_offre");
	$laListeChamps[]=new dbChamp("Job_cms_pdf", "entier", "get_cms_pdf", "set_cms_pdf");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_offre() { return($this->offre); }
function get_cms_pdf() { return($this->cms_pdf); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_offre($c_job_offre) { return($this->offre=$c_job_offre); }
function set_cms_pdf($c_job_cms_pdf) { return($this->cms_pdf=$c_job_cms_pdf); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("job_assooffrepdf"); }
function getClasse() { return("job_assooffrepdf"); }
function getPrefix() { return("job"); }
function getDisplay() { return("offre"); }
function getAbstract() { return("cms_pdf"); }


} //class

}
?>
