<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_assocandidatlangue.class.php')  && (strpos(__FILE__,'/include/bo/class/job_assocandidatlangue.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_assocandidatlangue.class.php');
}else{
/*======================================

objet de BDD job_assocandidatlangue :: class job_assocandidatlangue

SQL mySQL:

DROP TABLE IF EXISTS job_assocandidatlangue;
CREATE TABLE job_assocandidatlangue
(
	job_id			int (11) PRIMARY KEY not null,
	job_candidat			int,
	job_langue			int,
	job_niveaulangue			int,
	job_ordre			int (11)
)

SQL Oracle:

DROP TABLE job_assocandidatlangue
CREATE TABLE job_assocandidatlangue
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_candidat			number,
	job_langue			number,
	job_niveaulangue			number,
	job_ordre			number (11)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_assocandidatlangue" libelle="Langues parlées"  prefix="job" display="langue" abstract="niveaulangue" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="candidat" type="int" default="0" order="true" fkey="job_candidat" />
<item name="langue" type="int" default="0" order="true"  fkey="job_langue" />
<item name="niveaulangue" type="int" default="0" order="true" fkey="job_niveaulangue" /> 
<item name="ordre" libelle="Ordre" type="int" length="11" list="false" order="false" default="0"/>
</class>


==========================================*/

class job_assocandidatlangue
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $candidat;
var $langue;
var $niveaulangue;
var $ordre;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_assocandidatlangue\" libelle=\"Langues parlées\"  prefix=\"job\" display=\"langue\" abstract=\"niveaulangue\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"candidat\" type=\"int\" default=\"0\" order=\"true\" fkey=\"job_candidat\" />
<item name=\"langue\" type=\"int\" default=\"0\" order=\"true\" fkey=\"job_langue\" />
<item name=\"niveaulangue\" type=\"int\" default=\"0\" order=\"true\" fkey=\"job_niveaulangue\" /> 
<item name=\"ordre\" libelle=\"Ordre\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" default=\"0\"/>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_assocandidatlangue
(
	job_id			int (11) PRIMARY KEY not null,
	job_candidat			int,
	job_langue			int,
	job_niveaulangue			int,
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
		$this->langue = -1;
		$this->niveaulangue = -1;
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
	$laListeChamps[]=new dbChamp("Job_langue", "entier", "get_langue", "set_langue");
	$laListeChamps[]=new dbChamp("Job_niveaulangue", "entier", "get_niveaulangue", "set_niveaulangue");
	$laListeChamps[]=new dbChamp("Job_ordre", "entier", "get_ordre", "set_ordre");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_candidat() { return($this->candidat); }
function get_langue() { return($this->langue); }
function get_niveaulangue() { return($this->niveaulangue); }
function get_ordre() { return($this->ordre); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_candidat($c_job_candidat) { return($this->candidat=$c_job_candidat); }
function set_langue($c_job_langue) { return($this->langue=$c_job_langue); }
function set_niveaulangue($c_job_niveaulangue) { return($this->niveaulangue=$c_job_niveaulangue); }
function set_ordre($c_job_ordre) { return($this->ordre=$c_job_ordre); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("job_assocandidatlangue"); }
function getClasse() { return("job_assocandidatlangue"); }
function getPrefix() { return("job"); }
function getDisplay() { return("langue"); }
function getAbstract() { return("niveaulangue"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue/list_job_assocandidatlangue.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue/maj_job_assocandidatlangue.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue/show_job_assocandidatlangue.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue/rss_job_assocandidatlangue.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue/xml_job_assocandidatlangue.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue/xlsx_job_assocandidatlangue.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue/export_job_assocandidatlangue.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_assocandidatlangue/import_job_assocandidatlangue.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>