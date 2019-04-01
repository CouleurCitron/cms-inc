<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD news_assonewscron :: class news_assonewscron

SQL mySQL:

DROP TABLE IF EXISTS news_assonewscron;
CREATE TABLE news_assonewscron
(
	nws_id			int (11) PRIMARY KEY not null,
	nws_newsletter			int,
	nws_cms_cron			int,
	nws_statut			int (11) not null
)

SQL Oracle:

DROP TABLE news_assonewscron
CREATE TABLE news_assonewscron
(
	nws_id			number (11) constraint nws_pk PRIMARY KEY not null,
	nws_newsletter			number,
	nws_cms_cron			number,
	nws_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_assonewscron" libelle="Périodicité d'envoi" prefix="nws" display="newsletter" abstract="cms_cron" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" />
<item name="newsletter" libelle="Newsletter"  type="int" default="0" order="true" list="true" fkey="newsletter" />
<item name="cms_cron" libelle="Périodicité d'application"  type="int" default="0" order="true" list="true" fkey="cms_cron" /> 
<langpack lang="fr">
<norecords>Pas d\'asso newsletter/Périodicité d\'application à afficher</norecords>
</langpack>
</class>


==========================================*/

class news_assonewscron
{
var $id;
var $newsletter;
var $cms_cron;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_assonewscron\" libelle=\"Périodicité d'envoi\" prefix=\"nws\" display=\"newsletter\" abstract=\"cms_cron\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" />
<item name=\"newsletter\" libelle=\"Newsletter\"  type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"newsletter\" />
<item name=\"cms_cron\" libelle=\"Périodicité d'application\"  type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_cron\" />  
<langpack lang=\"fr\">
<norecords>Pas d\'asso newsletter/Périodicité d\'application à afficher</norecords>
</langpack>
</class>";

var $sMySql = "CREATE TABLE news_assonewscron
(
	nws_id			int (11) PRIMARY KEY not null,
	nws_newsletter			int,
	nws_cms_cron			int,
	nws_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("news_assonewscron") == false){
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
		$this->cms_cron = -1;
		$this->statut = DEF_ID_STATUT_LIGNE;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("nws_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("nws_newsletter", "entier", "get_newsletter", "set_newsletter");
	$laListeChamps[]=new dbChamp("nws_cms_cron", "entier", "get_cms_cron", "set_cms_cron");
	$laListeChamps[]=new dbChamp("nws_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_newsletter() { return($this->newsletter); }
function get_cms_cron() { return($this->cms_cron); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_nws_id) { return($this->id=$c_nws_id); }
function set_newsletter($c_nws_newsletter) { return($this->newsletter=$c_nws_newsletter); }
function set_cms_cron($c_nws_cms_cron) { return($this->cms_cron=$c_nws_cms_cron); }
function set_statut($c_nws_statut) { return($this->statut=$c_nws_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("nws_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("nws_statut"); }
//
function getTable() { return("news_assonewscron"); }
function getClasse() { return("news_assonewscron"); }
function getDisplay() { return("newsletter"); }
function getAbstract() { return("cms_cron"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron")){
	mkdir($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron");
	$list = fopen($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron/list_news_assonewscron.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron/maj_news_assonewscron.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron/show_news_assonewscron.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron/rss_news_assonewscron.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron/xml_news_assonewscron.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron/xmlxls_news_assonewscron.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron/export_news_assonewscron.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['Périodicité d'application_ROOT']."/backoffice/news_assonewscron/import_news_assonewscron.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>