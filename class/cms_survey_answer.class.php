<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('cms_survey_answear')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_survey_answear`');
	if ($rs) {
		$fields = Array();
		while(!$rs->EOF) {
			$fields[$rs->fields["Field"]] = $rs->fields;
			//pre_dump($rs->fields);
			$rs->MoveNext();
		
		}
		$names = array_keys($fields);
		if ($fields['cms_libelle']['Type'] != 'int(11)')	
			$rs = $db->Execute(" ALTER TABLE `cms_survey_answer` CHANGE `cms_libelle` `cms_libelle` INT( 11 ) NOT NULL;");
	}
}
/*======================================

objet de BDD cms_survey_answer :: class cms_survey_answer

SQL mySQL:

DROP TABLE IF EXISTS cms_survey_answer;
CREATE TABLE cms_survey_answer
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			int (11) not null,
	cms_ask			int (11),
	cms_dateadd			date not null,
	cms_dateupd			date not null
)

SQL Oracle:

DROP TABLE cms_survey_answer
CREATE TABLE cms_survey_answer
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_libelle			number (11) not null,
	cms_ask			number (11),
	cms_dateadd			date not null,
	cms_dateupd			date not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_survey_answer" libelle="Sondage - réponses" prefix="cms" display="libelle" abstract="dateadd">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="libelle" libelle="Titre" type="int" length="11" notnull="true" list="true" order="false" translate="reference" />  
<item name="ask" libelle="Question liée" type="int" length="11" list="false" order="false" fkey="cms_survey_ask"/>  
<item name="dateadd" libelle="Date de créa" type="date" notnull="false" list="false" order="false" />  
<item name="dateupd" libelle="Date de maj" type="date"  notnull="false" list="false" order="false" />  
</class>


==========================================*/

class cms_survey_answer
{
var $id;
var $libelle;
var $ask;
var $dateadd;
var $dateupd;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_survey_answer\" libelle=\"Sondage - réponses\" prefix=\"cms\" display=\"libelle\" abstract=\"dateadd\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"libelle\" libelle=\"Titre\" type=\"int\" length=\"11\" notnull=\"true\" list=\"true\" order=\"false\" translate=\"reference\" />  
<item name=\"ask\" libelle=\"Question liée\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"cms_survey_ask\"/>  
<item name=\"dateadd\" libelle=\"Date de créa\" type=\"date\" notnull=\"false\" list=\"false\" order=\"false\" />  
<item name=\"dateupd\" libelle=\"Date de maj\" type=\"date\"  notnull=\"false\" list=\"false\" order=\"false\" />  
</class>";

var $sMySql = "CREATE TABLE cms_survey_answer
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			int (11) not null,
	cms_ask			int (11),
	cms_dateadd			date not null,
	cms_dateupd			date not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_survey_answer") == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id = -1;
		$this->libelle = -1;
		$this->ask = -1;
		$this->dateadd = date("d/m/Y");
		$this->dateupd = date("d/m/Y");
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_libelle", "entier", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Cms_ask", "entier", "get_ask", "set_ask");
	$laListeChamps[]=new dbChamp("Cms_dateadd", "date_formatee", "get_dateadd", "set_dateadd");
	$laListeChamps[]=new dbChamp("Cms_dateupd", "date_formatee", "get_dateupd", "set_dateupd");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_ask() { return($this->ask); }
function get_dateadd() { return($this->dateadd); }
function get_dateupd() { return($this->dateupd); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_libelle($c_cms_libelle) { return($this->libelle=$c_cms_libelle); }
function set_ask($c_cms_ask) { return($this->ask=$c_cms_ask); }
function set_dateadd($c_cms_dateadd) { return($this->dateadd=$c_cms_dateadd); }
function set_dateupd($c_cms_dateupd) { return($this->dateupd=$c_cms_dateupd); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_survey_answer"); }
function getClasse() { return("cms_survey_answer"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("dateadd"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer/list_cms_survey_answer.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer/maj_cms_survey_answer.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer/show_cms_survey_answer.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer/rss_cms_survey_answer.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer/xml_cms_survey_answer.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer/export_cms_survey_answer.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_answer/import_cms_survey_answer.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>