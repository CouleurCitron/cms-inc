<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_langue.class.php')  && (strpos(__FILE__,'/include/bo/class/job_langue.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_langue.class.php');
}else{
/*======================================

objet de BDD job_langue :: class job_langue

SQL mySQL:

DROP TABLE IF EXISTS job_langue;
CREATE TABLE job_langue
(
	job_id			int (11) PRIMARY KEY not null,
	job_libelle			int (11),
	job_ordre			int (11),
	job_statut			int not null
)

SQL Oracle:

DROP TABLE job_langue
CREATE TABLE job_langue
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_libelle			number (11),
	job_ordre			number (11),
	job_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_langue" libelle="Langues parl�es" prefix="job" display="libelle" abstract="">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="libelle" libelle="Libell�" type="int" length="11" list="true" order="false" translate="reference" /> 
<item name="ordre" libelle="Ordre" type="int" length="11" list="false" order="false" default="0"/>
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
</class>


==========================================*/

class job_langue
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $ordre;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_langue\" libelle=\"langues parl�es\" prefix=\"job\" display=\"libelle\" abstract=\"\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"libelle\" libelle=\"Libell�\" type=\"int\" length=\"11\" list=\"true\" order=\"false\" translate=\"reference\" /> 
<item name=\"ordre\" libelle=\"Ordre\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" default=\"0\"/>
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_langue
(
	job_id			int (11) PRIMARY KEY not null,
	job_libelle			int (11),
	job_ordre			int (11),
	job_statut			int not null
)

";

// constructeur
function job_langue($id=null)
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
		$this->libelle = -1;
		$this->ordre = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
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
	// ATTENTION, respecter l'ordre des champs de la table dans la base de donn�es pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Job_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Job_libelle", "entier", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Job_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Job_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_ordre() { return($this->ordre); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_libelle($c_job_libelle) { return($this->libelle=$c_job_libelle); }
function set_ordre($c_job_ordre) { return($this->ordre=$c_job_ordre); }
function set_statut($c_job_statut) { return($this->statut=$c_job_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("job_statut"); }
//
function getTable() { return("job_langue"); }
function getClasse() { return("job_langue"); }
function getPrefix() { return("job"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return(""); }


} //class

 
}
?>
