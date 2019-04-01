<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_assocandidatqualification.class.php')  && (strpos(__FILE__,'/include/bo/class/job_assocandidatqualification.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_assocandidatqualification.class.php');
}else{
/*======================================

objet de BDD job_assocandidatqualification :: class job_assocandidatqualification

SQL mySQL:

DROP TABLE IF EXISTS job_assocandidatqualification;
CREATE TABLE job_assocandidatqualification
(
	job_id			int (11) PRIMARY KEY not null,
	job_candidat			int (11),
	job_domaine			int (11),
	job_qualification			int (11),
	job_diplome			varchar (255),
	job_ecole			varchar (255),
	job_annee			varchar (255),
	job_ordre			int (11)
)

SQL Oracle:

DROP TABLE job_assocandidatqualification
CREATE TABLE job_assocandidatqualification
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_candidat			number (11),
	job_domaine			number (11),
	job_qualification			number (11),
	job_diplome			varchar2 (255),
	job_ecole			varchar2 (255),
	job_annee			varchar2 (255),
	job_ordre			number (11)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_assocandidatqualification" libelle="Diplômes candidats"  prefix="job" display="qualification" abstract="diplome" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="candidat" type="int" length="11" default="0" order="false"  fkey="job_candidat" />
<item name="domaine" type="int" length="11" default="0" order="false"  fkey="job_domaine" />
<item name="qualification" type="int" length="11" default="0" order="false"  fkey="job_qualification" />
<item name="diplome" libelle="Diplôme" type="varchar" length="255" list="false" order="true"  nohtml="true"  />
<item name="ecole" libelle="Ecole" type="varchar" length="255" list="false" order="true" nohtml="true"  />
<item name="annee" libelle="Année" type="varchar" length="255" list="false" order="true" nohtml="true"  />
<item name="ordre" libelle="Ordre" type="int" length="11" list="false" order="false" default="0"/>
</class>


==========================================*/

class job_assocandidatqualification
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $candidat;
var $domaine;
var $qualification;
var $diplome;
var $ecole;
var $annee;
var $ordre;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_assocandidatqualification\" libelle=\"Diplômes candidats\"  prefix=\"job\" display=\"qualification\" abstract=\"diplome\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"candidat\" type=\"int\" length=\"11\" default=\"0\" order=\"false\"  fkey=\"job_candidat\" />
<item name=\"domaine\" type=\"int\" length=\"11\" default=\"0\" order=\"false\"  fkey=\"job_domaine\" />
<item name=\"qualification\" type=\"int\" length=\"11\" default=\"0\" order=\"false\"  fkey=\"job_qualification\" />
<item name=\"diplome\" libelle=\"Diplôme\" type=\"varchar\" length=\"255\" list=\"false\" order=\"true\"  nohtml=\"true\"  />
<item name=\"ecole\" libelle=\"Ecole\" type=\"varchar\" length=\"255\" list=\"false\" order=\"true\" nohtml=\"true\"  />
<item name=\"annee\" libelle=\"Année\" type=\"varchar\" length=\"255\" list=\"false\" order=\"true\" nohtml=\"true\"  />
<item name=\"ordre\" libelle=\"Ordre\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" default=\"0\"/>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_assocandidatqualification
(
	job_id			int (11) PRIMARY KEY not null,
	job_candidat			int (11),
	job_domaine			int (11),
	job_qualification			int (11),
	job_diplome			varchar (255),
	job_ecole			varchar (255),
	job_annee			varchar (255),
	job_ordre			int (11)
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
		$this->candidat = -1;
		$this->domaine = -1;
		$this->qualification = -1;
		$this->diplome = "";
		$this->ecole = "";
		$this->annee = "";
		$this->ordre = -1;
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
	$laListeChamps[]=new dbChamp("Job_candidat", "entier", "get_candidat", "set_candidat");
	$laListeChamps[]=new dbChamp("Job_domaine", "entier", "get_domaine", "set_domaine");
	$laListeChamps[]=new dbChamp("Job_qualification", "entier", "get_qualification", "set_qualification");
	$laListeChamps[]=new dbChamp("Job_diplome", "text", "get_diplome", "set_diplome");
	$laListeChamps[]=new dbChamp("Job_ecole", "text", "get_ecole", "set_ecole");
	$laListeChamps[]=new dbChamp("Job_annee", "text", "get_annee", "set_annee");
	$laListeChamps[]=new dbChamp("Job_ordre", "entier", "get_ordre", "set_ordre");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_candidat() { return($this->candidat); }
function get_domaine() { return($this->domaine); }
function get_qualification() { return($this->qualification); }
function get_diplome() { return($this->diplome); }
function get_ecole() { return($this->ecole); }
function get_annee() { return($this->annee); }
function get_ordre() { return($this->ordre); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_candidat($c_job_candidat) { return($this->candidat=$c_job_candidat); }
function set_domaine($c_job_domaine) { return($this->domaine=$c_job_domaine); }
function set_qualification($c_job_qualification) { return($this->qualification=$c_job_qualification); }
function set_diplome($c_job_diplome) { return($this->diplome=$c_job_diplome); }
function set_ecole($c_job_ecole) { return($this->ecole=$c_job_ecole); }
function set_annee($c_job_annee) { return($this->annee=$c_job_annee); }
function set_ordre($c_job_ordre) { return($this->ordre=$c_job_ordre); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("job_assocandidatqualification"); }
function getClasse() { return("job_assocandidatqualification"); }
function getPrefix() { return("job"); }
function getDisplay() { return("qualification"); }
function getAbstract() { return("diplome"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification/list_job_assocandidatqualification.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification/maj_job_assocandidatqualification.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification/show_job_assocandidatqualification.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification/rss_job_assocandidatqualification.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification/xml_job_assocandidatqualification.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification/xlsx_job_assocandidatqualification.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification/export_job_assocandidatqualification.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatqualification/import_job_assocandidatqualification.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>