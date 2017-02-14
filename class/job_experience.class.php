<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_experience.class.php')  && (strpos(__FILE__,'/include/bo/class/job_experience.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_experience.class.php');
}else{
/*======================================

objet de BDD job_experience :: class job_experience

SQL mySQL:

DROP TABLE IF EXISTS job_experience;
CREATE TABLE job_experience
(
	job_id			int (11) PRIMARY KEY not null,
	job_libelle			int (11),
	job_statut			int not null,
	job_ordre			int (11)
)

SQL Oracle:

DROP TABLE job_experience
CREATE TABLE job_experience
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_libelle			number (11),
	job_statut			number not null,
	job_ordre			number (11)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_experience" libelle="Niveau d'expérience" prefix="job" display="libelle" abstract="libelle">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="libelle" type="int" length="11" list="true" order="true" translate="reference"  />
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="ordre" libelle="Ordre d'apparition" type="int" length="11" list="true" />
</class>


==========================================*/

class job_experience
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $statut;
var $ordre;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_experience\" libelle=\"Niveau d'expérience\" prefix=\"job\" display=\"libelle\" abstract=\"libelle\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"libelle\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\"  />
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"ordre\" libelle=\"Ordre d'apparition\" type=\"int\" length=\"11\" list=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_experience
(
	job_id			int (11) PRIMARY KEY not null,
	job_libelle			int (11),
	job_statut			int not null,
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
		$this->libelle = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
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
	$laListeChamps[]=new dbChamp("Job_libelle", "entier", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Job_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Job_ordre", "entier", "get_ordre", "set_ordre");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_statut() { return($this->statut); }
function get_ordre() { return($this->ordre); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_libelle($c_job_libelle) { return($this->libelle=$c_job_libelle); }
function set_statut($c_job_statut) { return($this->statut=$c_job_statut); }
function set_ordre($c_job_ordre) { return($this->ordre=$c_job_ordre); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("job_statut"); }
//
function getTable() { return("job_experience"); }
function getClasse() { return("job_experience"); }
function getPrefix() { return("job"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("libelle"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience/list_job_experience.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience/maj_job_experience.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience/show_job_experience.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience/rss_job_experience.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience/xml_job_experience.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience/xlsx_job_experience.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience/export_job_experience.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_experience/import_job_experience.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>