<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD news_envoi :: class news_envoi

SQL mySQL:

DROP TABLE IF EXISTS news_envoi;
CREATE TABLE news_envoi
(
	env_id			int (11) not null,
	env_news_id			int (11) not null,
	env_date			date,
	env_html			text,
	env_nbmail			int (11) not null
)

SQL Oracle:

DROP TABLE news_envoi
CREATE TABLE news_envoi
(
	env_id			number (11) not null,
	env_news_id			number (11) not null,
	env_date			date,
	env_html			text,
	env_nbmail			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_envoi" prefix="env" display="news_id" abstract="date">
<item name="id" type="int" length="11" notnull="true" default="-1" />
<item name="news_id" type="int" length="11" notnull="true" fkey="newsletter"/>
<item name="date" type="date" list="true" order="true" default="0000-00-00" />
<item name="html" type="text" list="true" order="true" />
<item name="nbmail" type="int" length="11" notnull="true" default="0" />
</class>



==========================================*/

class news_envoi
{
var $id;
var $news_id;
var $date;
var $html;
var $nbmail;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_envoi\" prefix=\"env\" display=\"news_id\" abstract=\"date\">
<item name=\"id\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" />
<item name=\"news_id\" type=\"int\" length=\"11\" notnull=\"true\" fkey=\"newsletter\"/>
<item name=\"date\" type=\"date\" list=\"true\" order=\"true\" default=\"0000-00-00\" />
<item name=\"html\" type=\"text\" list=\"true\" order=\"true\" />
<item name=\"nbmail\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" />
</class>
";

var $sMySql = "CREATE TABLE news_envoi
(
	env_id			int (11) not null,
	env_news_id			int (11) not null,
	env_date			date,
	env_html			text,
	env_nbmail			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("news_envoi") == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		//$this = dbGetObjectFromPK("news_envoi", $id);
		$tempThis = dbGetObjectFromPK("news_envoi", $id);
		$this->id = $tempThis->id;
		$this->news_id = $tempThis->news_id;
		$this->date = $tempThis->date;
		$this->html = $tempThis->html;
		$this->nbmail = $tempThis->nbmail;
		$tempThis = null;
	} else {
		$this->id = -1;
		$this->news_id = -1;
		$this->date = "0000-00-00";
		$this->html = "";
		$this->nbmail = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Env_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Env_news_id", "entier", "get_news_id", "set_news_id");
	$laListeChamps[]=new dbChamp("Env_date", "date_formatee", "get_date", "set_date");
	$laListeChamps[]=new dbChamp("Env_html", "text", "get_html", "set_html");
	$laListeChamps[]=new dbChamp("Env_nbmail", "entier", "get_nbmail", "set_nbmail");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_news_id() { return($this->news_id); }
function get_date() { return($this->date); }
function get_html() { return($this->html); }
function get_nbmail() { return($this->nbmail); }


// setters
function set_id($c_env_id) { return($this->id=$c_env_id); }
function set_news_id($c_env_news_id) { return($this->news_id=$c_env_news_id); }
function set_date($c_env_date) { return($this->date=$c_env_date); }
function set_html($c_env_html) { return($this->html=$c_env_html); }
function set_nbmail($c_env_nbmail) { return($this->nbmail=$c_env_nbmail); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("env_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("news_envoi"); }
function getClasse() { return("news_envoi"); }
function getDisplay() { return("news_id"); }
function getAbstract() { return("date"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi/list_news_envoi.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi/maj_news_envoi.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi/show_news_envoi.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi/rss_news_envoi.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi/xml_news_envoi.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi/export_news_envoi.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_envoi/import_news_envoi.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>