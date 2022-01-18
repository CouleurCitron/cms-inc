<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_destinataire.class.php')  && (strpos(__FILE__,'/include/bo/class/job_destinataire.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_destinataire.class.php');
}else{
/*======================================

objet de BDD job_destinataire :: class job_destinataire

SQL mySQL:

DROP TABLE IF EXISTS job_destinataire;
CREATE TABLE job_destinataire
(
	job_id			int (11) PRIMARY KEY not null,
	job_nom			varchar (255),
	job_prenom			varchar (255),
	job_mail			varchar (255),
	job_fonction			int (11),
	job_statut			int not null
)

SQL Oracle:

DROP TABLE job_destinataire
CREATE TABLE job_destinataire
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_nom			varchar2 (255),
	job_prenom			varchar2 (255),
	job_mail			varchar2 (255),
	job_fonction			number (11),
	job_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_destinataire" libelle="destinataire" prefix="job" display="nom" abstract="prenom">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true"  asso="job_assooffredestinataire" />
<item name="nom" type="varchar" length="255" list="true" order="true"  nohtml="true" />
<item name="prenom" type="varchar" length="255" list="true" order="true"  nohtml="true" />
<item name="mail" type="varchar" length="255" list="true" order="true"  nohtml="true" />
<item name="fonction" type="int" length="11" list="true" order="true" translate="reference" nohtml="true"/>
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
</class>


==========================================*/

class job_destinataire
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $nom;
var $prenom;
var $mail;
var $fonction;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_destinataire\" libelle=\"destinataire\" prefix=\"job\" display=\"nom\" abstract=\"prenom\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\"  asso=\"job_assooffredestinataire\" />
<item name=\"nom\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"prenom\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"mail\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"fonction\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\" nohtml=\"true\"/>
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_destinataire
(
	job_id			int (11) PRIMARY KEY not null,
	job_nom			varchar (255),
	job_prenom			varchar (255),
	job_mail			varchar (255),
	job_fonction			int (11),
	job_statut			int not null
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
		$this->nom = "";
		$this->prenom = "";
		$this->mail = "";
		$this->fonction = -1;
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
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Job_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Job_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Job_prenom", "text", "get_prenom", "set_prenom");
	$laListeChamps[]=new dbChamp("Job_mail", "text", "get_mail", "set_mail");
	$laListeChamps[]=new dbChamp("Job_fonction", "entier", "get_fonction", "set_fonction");
	$laListeChamps[]=new dbChamp("Job_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_prenom() { return($this->prenom); }
function get_mail() { return($this->mail); }
function get_fonction() { return($this->fonction); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_nom($c_job_nom) { return($this->nom=$c_job_nom); }
function set_prenom($c_job_prenom) { return($this->prenom=$c_job_prenom); }
function set_mail($c_job_mail) { return($this->mail=$c_job_mail); }
function set_fonction($c_job_fonction) { return($this->fonction=$c_job_fonction); }
function set_statut($c_job_statut) { return($this->statut=$c_job_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("job_statut"); }
//
function getTable() { return("job_destinataire"); }
function getClasse() { return("job_destinataire"); }
function getPrefix() { return("job"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("prenom"); }


} //class

 
}
?>
