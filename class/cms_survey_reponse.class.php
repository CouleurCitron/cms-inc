<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_survey_reponse :: class cms_survey_reponse

SQL mySQL:

DROP TABLE IF EXISTS cms_survey_reponse;
CREATE TABLE cms_survey_reponse
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_ip			varchar (255),
	cms_answer			int (11),
	cms_date			date not null
)

SQL Oracle:

DROP TABLE cms_survey_reponse
CREATE TABLE cms_survey_reponse
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_ip			varchar2 (255),
	cms_answer			number (11),
	cms_date			date not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_survey_reponse" libelle="Sondage - réponses user" prefix="cms" display="answer" abstract="date">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="ip" libelle="Titre" type="varchar" length="255" list="false" order="false"/>  
<item name="answer" libelle="Question liée" type="int" length="11" list="false" order="false" fkey="cms_survey_answer"/>  
<item name="date" libelle="Date de réponse" type="date" notnull="false" list="false" order="false" />  
</class>


==========================================*/

class cms_survey_reponse
{
var $id;
var $ip;
var $answer;
var $date;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_survey_reponse\" libelle=\"Sondage - réponses user\" prefix=\"cms\" display=\"answer\" abstract=\"date\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"ip\" libelle=\"Titre\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\"/>  
<item name=\"answer\" libelle=\"Question liée\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"cms_survey_answer\"/>  
<item name=\"date\" libelle=\"Date de réponse\" type=\"date\" notnull=\"false\" list=\"false\" order=\"false\" />  
</class>";

var $sMySql = "CREATE TABLE cms_survey_reponse
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_ip			varchar (255),
	cms_answer			int (11),
	cms_date			date not null
)

";

// constructeur
function cms_survey_reponse($id=null)
{
	if (istable("cms_survey_reponse") == false){
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
		$this->ip = "";
		$this->answer = -1;
		$this->date = date("d/m/Y");
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_ip", "text", "get_ip", "set_ip");
	$laListeChamps[]=new dbChamp("Cms_answer", "entier", "get_answer", "set_answer");
	$laListeChamps[]=new dbChamp("Cms_date", "date_formatee", "get_date", "set_date");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_ip() { return($this->ip); }
function get_answer() { return($this->answer); }
function get_date() { return($this->date); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_ip($c_cms_ip) { return($this->ip=$c_cms_ip); }
function set_answer($c_cms_answer) { return($this->answer=$c_cms_answer); }
function set_date($c_cms_date) { return($this->date=$c_cms_date); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_survey_reponse"); }
function getClasse() { return("cms_survey_reponse"); }
function getDisplay() { return("answer"); }
function getAbstract() { return("date"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse/list_cms_survey_reponse.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse/maj_cms_survey_reponse.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse/show_cms_survey_reponse.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse/rss_cms_survey_reponse.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse/xml_cms_survey_reponse.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse/export_cms_survey_reponse.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_reponse/import_cms_survey_reponse.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>