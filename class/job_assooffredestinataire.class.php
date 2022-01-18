<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD job_assooffredestinataire :: class job_assooffredestinataire

SQL mySQL:

DROP TABLE IF EXISTS job_assooffredestinataire;
CREATE TABLE job_assooffredestinataire
(
	job_id			int (11) PRIMARY KEY not null,
	job_offre			int,
	job_destinataire			int
)

SQL Oracle:

DROP TABLE job_assooffredestinataire 
CREATE TABLE job_assooffredestinataire
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_offre			number,
	job_destinataire			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_assooffredestinataire" libelle=\"Destinataire des réponses à l'offre\"  prefix="job" display="offre" abstract="destinataire" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="offre" type="int" default="0" order="true"  fkey="job_offre" />
<item name="destinataire" type="int" default="0" order="true"  fkey="job_destinataire" />
</class>


==========================================*/

class job_assooffredestinataire
{
var $id;
var $offre;
var $destinataire;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_assooffredestinataire\" libelle=\"Destinataire des réponses à l'offre\"  prefix=\"job\" display=\"offre\" abstract=\"destinataire\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"offre\" type=\"int\" default=\"0\" order=\"true\"  fkey=\"job_offre\" />
<item name=\"destinataire\" type=\"int\" default=\"0\" order=\"true\"  fkey=\"job_destinataire\" />
</class>";

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
		$this->id = -1;
		$this->offre = -1;
		$this->destinataire = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("job_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("job_offre", "entier", "get_offre", "set_offre");
	$laListeChamps[]=new dbChamp("job_destinataire", "entier", "get_destinataire", "set_destinataire");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_offre() { return($this->offre); }
function get_destinataire() { return($this->destinataire); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_offre($c_job_offre) { return($this->offre=$c_job_offre); }
function set_destinataire($c_job_destinataire) { return($this->destinataire=$c_job_destinataire); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("job_assooffredestinataire"); }
function getClasse() { return("job_assooffredestinataire"); }
function getPrefix() { return("job"); }
function getDisplay() { return("offre"); }
function getAbstract() { return("destinataire"); }


} //class
?>