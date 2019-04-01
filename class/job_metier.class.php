<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_metier.class.php')  && (strpos(__FILE__,'/include/bo/class/job_metier.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_metier.class.php');
}else{
/*======================================

objet de BDD job_metier :: class job_metier

SQL mySQL:

DROP TABLE IF EXISTS job_metier;
CREATE TABLE job_metier
(
	job_id			int (11) PRIMARY KEY not null,
	job_libelle			int (11),
	job_statut			int not null
)

SQL Oracle:

DROP TABLE job_metier
CREATE TABLE job_metier
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_libelle			number (11),
	job_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_metier" libelle="Métiers" prefix="job" display="libelle" abstract="">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="libelle" type="int" length="11" list="true" order="true" translate="reference"  />
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
</class>


==========================================*/

class job_metier
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_metier\" libelle=\"métiers\" prefix=\"job\" display=\"libelle\" abstract=\"\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"libelle\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\"  />
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_metier
(
	job_id			int (11) PRIMARY KEY not null,
	job_libelle			int (11),
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
		$this->libelle = -1;
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
	$laListeChamps[]=new dbChamp("Job_libelle", "entier", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Job_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_libelle($c_job_libelle) { return($this->libelle=$c_job_libelle); }
function set_statut($c_job_statut) { return($this->statut=$c_job_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("job_statut"); }
//
function getTable() { return("job_metier"); }
function getClasse() { return("job_metier"); }
function getPrefix() { return("job"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return(""); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier/list_job_metier.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier/maj_job_metier.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier/show_job_metier.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier/rss_job_metier.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier/xml_job_metier.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier/xlsx_job_metier.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier/export_job_metier.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_metier/import_job_metier.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>