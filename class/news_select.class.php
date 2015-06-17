<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD news_select :: class news_select

SQL mySQL:

DROP TABLE IF EXISTS news_select;
CREATE TABLE news_select
(
	slc_id			int (11) not null,
	slc_newsletter			int (11) not null,
	slc_inscrit			int (11) not null,
	slc_datecrea			date,
	slc_dateenvoi			date,
	slc_recu			int (11) not null,
	slc_statut			int not null
)

SQL Oracle:

DROP TABLE news_select
CREATE TABLE news_select
(
	slc_id			number (11) not null,
	slc_newsletter			number (11) not null,
	slc_inscrit			number (11) not null,
	slc_datecrea			date,
	slc_dateenvoi			date,
	slc_recu			number (11) not null,
	slc_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_select" prefix="slc" display="newsletter" abstract="inscrit">
<item name="id" type="int" length="11" notnull="true" default="-1" />
<item name="newsletter" type="int" length="11" notnull="true" fkey="newsletter"  default="-2" />
<item name="inscrit" type="int" length="11" notnull="true" fkey="inscrit"/>
<item name="datecrea" type="date" list="true" order="true" default="0000-00-00" />
<item name="dateenvoi" type="date" list="true" order="true" default="0000-00-00" />
<item name="recu" type="int" length="11" default="0"  notnull="true"/>
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_NEWS_DEFAUT" list="true" order="true" />
</class>


==========================================*/

class news_select
{
var $id;
var $newsletter;
var $inscrit;
var $datecrea;
var $dateenvoi;
var $recu;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_select\" prefix=\"slc\" display=\"newsletter\" abstract=\"inscrit\">
<item name=\"id\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" />
<item name=\"newsletter\" type=\"int\" length=\"11\" notnull=\"true\" fkey=\"newsletter\"  default=\"-2\" />
<item name=\"inscrit\" type=\"int\" length=\"11\" notnull=\"true\" fkey=\"inscrit\"/>
<item name=\"datecrea\" type=\"date\" list=\"true\" order=\"true\" default=\"0000-00-00\" />
<item name=\"dateenvoi\" type=\"date\" list=\"true\" order=\"true\" default=\"0000-00-00\" />
<item name=\"recu\" type=\"int\" length=\"11\" default=\"0\"  notnull=\"true\"/>
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_NEWS_DEFAUT\" list=\"true\" order=\"true\" />
</class>";

var $sMySql = "CREATE TABLE news_select
(
	slc_id			int (11) not null,
	slc_newsletter			int (11) not null,
	slc_inscrit			int (11) not null,
	slc_datecrea			date,
	slc_dateenvoi			date,
	slc_recu			int (11) not null,
	slc_statut			int not null
)

";

// constructeur
function news_select($id=null)
{
	if (istable("news_select") == false){
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
		$this->newsletter = -1;
		$this->inscrit = -1;
		$this->datecrea = "0000-00-00";
		$this->dateenvoi = "0000-00-00";
		$this->recu = 0;
		$this->statut = DEF_CODE_STATUT_NEWS_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Slc_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Slc_newsletter", "entier", "get_newsletter", "set_newsletter");
	$laListeChamps[]=new dbChamp("Slc_inscrit", "entier", "get_inscrit", "set_inscrit");
	$laListeChamps[]=new dbChamp("Slc_datecrea", "date_formatee", "get_datecrea", "set_datecrea");
	$laListeChamps[]=new dbChamp("Slc_dateenvoi", "date_formatee", "get_dateenvoi", "set_dateenvoi");
	$laListeChamps[]=new dbChamp("Slc_recu", "entier", "get_recu", "set_recu");
	$laListeChamps[]=new dbChamp("Slc_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_newsletter() { return($this->newsletter); }
function get_inscrit() { return($this->inscrit); }
function get_datecrea() { return($this->datecrea); }
function get_dateenvoi() { return($this->dateenvoi); }
function get_recu() { return($this->recu); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_slc_id) { return($this->id=$c_slc_id); }
function set_newsletter($c_slc_newsletter) { return($this->newsletter=$c_slc_newsletter); }
function set_inscrit($c_slc_inscrit) { return($this->inscrit=$c_slc_inscrit); }
function set_datecrea($c_slc_datecrea) { return($this->datecrea=$c_slc_datecrea); }
function set_dateenvoi($c_slc_dateenvoi) { return($this->dateenvoi=$c_slc_dateenvoi); }
function set_recu($c_slc_recu) { return($this->recu=$c_slc_recu); }
function set_statut($c_slc_statut) { return($this->statut=$c_slc_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("slc_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("slc_statut"); }
//
function getTable() { return("news_select"); }
function getClasse() { return("news_select"); }
function getDisplay() { return("newsletter"); }
function getAbstract() { return("inscrit"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select/list_news_select.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select/maj_news_select.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select/show_news_select.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select/rss_news_select.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select/xml_news_select.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select/export_news_select.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_select/import_news_select.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>