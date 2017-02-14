<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_lettre.class.php')  && (strpos(__FILE__,'/include/bo/class/job_lettre.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_lettre.class.php');
}else{
/*======================================

objet de BDD job_lettre :: class job_lettre

SQL mySQL:

DROP TABLE IF EXISTS job_lettre;
CREATE TABLE job_lettre
(
	job_id			int (11) PRIMARY KEY not null,
	job_type			int (11) not null,
	job_titre			int (11),
	job_resume			int (11),
	job_texte			int (11),
	job_statut			int (11) not null,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE job_lettre
CREATE TABLE job_lettre
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_type			number (11) not null,
	job_titre			number (11),
	job_resume			number (11),
	job_texte			number (11),
	job_statut			number (11) not null,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_lettre" libelle="Modèles de lettre" prefix="job" display="titre" abstract="resume">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" />
<item name="type" libelle="Type de réponse" type="int" length="1" notnull="true" default="-1" list="true" order="true" option="enum" >
	<option type="value" value="3" libelle="écartée - réponse négative" />
	<option type="value" value="4" libelle="écartée - vivier" />
	<option type="value" value="5" libelle="entretien proposé" />
	<option type="value" value="6" libelle="écartée suite à entretien" />
	<option type="value" value="7" libelle="désistement" />
	<option type="value" value="8" libelle="proposition faite" />
	<option type="value" value="9" libelle="candidature retenue" />
</item>
<item name="titre" libelle="Type" type="int" length="11" list="true" order="true" translate="reference" />
<item name="resume" libelle="Sujet" type="int" length="11" list="false" order="false" translate="reference" />
<item name="texte" libelle="Message" option="textarea" type="int" length="11" list="false" order="false" translate="reference" />
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="true" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>



==========================================*/

class job_lettre
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $type;
var $titre;
var $resume;
var $texte;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_lettre\" libelle=\"Modèles de lettre\" prefix=\"job\" display=\"titre\" abstract=\"resume\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" />
<item name=\"type\" libelle=\"Type de réponse\" type=\"int\" length=\"1\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" option=\"enum\" >
	<option type=\"value\" value=\"3\" libelle=\"écartée - réponse négative\" />
	<option type=\"value\" value=\"4\" libelle=\"écartée - vivier\" />
	<option type=\"value\" value=\"5\" libelle=\"entretien proposé\" />
	<option type=\"value\" value=\"6\" libelle=\"écartée suite à entretien\" />
	<option type=\"value\" value=\"7\" libelle=\"désistement\" />
	<option type=\"value\" value=\"8\" libelle=\"proposition faite\" />
	<option type=\"value\" value=\"9\" libelle=\"candidature retenue\" />
</item>
<item name=\"titre\" libelle=\"Type\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\" />
<item name=\"resume\" libelle=\"Sujet\" type=\"int\" length=\"11\" list=\"true\" order=\"false\" translate=\"reference\" />
<item name=\"texte\" libelle=\"Message\" option=\"textarea\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" translate=\"reference\" />
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" list=\"true\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_lettre
(
	job_id			int (11) PRIMARY KEY not null,
	job_type			int (11) not null,
	job_titre			int (11),
	job_resume			int (11),
	job_texte			int (11),
	job_statut			int (11) not null,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
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
		$this->type = -1;
		$this->titre = -1;
		$this->resume = -1;
		$this->texte = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
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
	$laListeChamps[]=new dbChamp("Job_type", "entier", "get_type", "set_type");
	$laListeChamps[]=new dbChamp("Job_titre", "entier", "get_titre", "set_titre");
	$laListeChamps[]=new dbChamp("Job_resume", "entier", "get_resume", "set_resume");
	$laListeChamps[]=new dbChamp("Job_texte", "entier", "get_texte", "set_texte");
	$laListeChamps[]=new dbChamp("Job_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Job_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Job_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_type() { return($this->type); }
function get_titre() { return($this->titre); }
function get_resume() { return($this->resume); }
function get_texte() { return($this->texte); }
function get_statut() { return($this->statut); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_type($c_job_type) { return($this->type=$c_job_type); }
function set_titre($c_job_titre) { return($this->titre=$c_job_titre); }
function set_resume($c_job_resume) { return($this->resume=$c_job_resume); }
function set_texte($c_job_texte) { return($this->texte=$c_job_texte); }
function set_statut($c_job_statut) { return($this->statut=$c_job_statut); }
function set_cdate($c_job_cdate) { return($this->cdate=$c_job_cdate); }
function set_mdate($c_job_mdate) { return($this->mdate=$c_job_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("job_statut"); }
//
function getTable() { return("job_lettre"); }
function getClasse() { return("job_lettre"); }
function getPrefix() { return("job"); }
function getDisplay() { return("titre"); }
function getAbstract() { return("resume"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre/list_job_lettre.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre/maj_job_lettre.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre/show_job_lettre.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre/rss_job_lettre.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre/xml_job_lettre.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre/xlsx_job_lettre.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre/export_job_lettre.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_lettre/import_job_lettre.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>