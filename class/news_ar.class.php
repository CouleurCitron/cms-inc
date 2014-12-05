<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD news_ar :: class news_ar

SQL mySQL:

DROP TABLE IF EXISTS news_ar;
CREATE TABLE news_ar
(
	ar_id			int (11) PRIMARY KEY not null,
	ar_newsletter			int (11) not null,
	ar_news_inscrit			int (11) not null,
	ar_statut			int (11) not null
)

SQL Oracle:

DROP TABLE news_ar
CREATE TABLE news_ar
(
	ar_id			number (11) constraint ar_pk PRIMARY KEY not null,
	ar_newsletter			number (11) not null,
	ar_news_inscrit			number (11) not null,
	ar_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_ar" prefix="ar" display="nom" abstract="">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false"/>
<item name="newsletter" type="int" length="11" notnull="true" list="true" order="true" fkey="newsletter"/>
<item name="news_inscrit" type="int" length="11" notnull="true" list="true" order="true" fkey="news_inscrit"/>
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_ID_STATUT_LIGNE" list="true" order="true" />
</class>


==========================================*/

class news_ar
{
var $id;
var $newsletter;
var $news_inscrit;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_ar\" prefix=\"ar\" display=\"nom\" abstract=\"\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\"/>
<item name=\"newsletter\" type=\"int\" length=\"11\" notnull=\"true\" list=\"true\" order=\"true\" fkey=\"newsletter\"/>
<item name=\"news_inscrit\" type=\"int\" length=\"11\" notnull=\"true\" list=\"true\" order=\"true\" fkey=\"news_inscrit\"/>
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_ID_STATUT_LIGNE\" list=\"true\" order=\"true\" />
</class>";

var $sMySql = "CREATE TABLE news_ar
(
	ar_id			int (11) PRIMARY KEY not null,
	ar_newsletter			int (11) not null,
	ar_news_inscrit			int (11) not null,
	ar_statut			int (11) not null
)

";

// constructeur
function news_ar($id=null)
{
	if (istable("news_ar") == false){
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
		$this->news_inscrit = -1;
		$this->statut = DEF_ID_STATUT_LIGNE;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Ar_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Ar_newsletter", "entier", "get_newsletter", "set_newsletter");
	$laListeChamps[]=new dbChamp("Ar_news_inscrit", "entier", "get_news_inscrit", "set_news_inscrit");
	$laListeChamps[]=new dbChamp("Ar_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_newsletter() { return($this->newsletter); }
function get_news_inscrit() { return($this->news_inscrit); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_ar_id) { return($this->id=$c_ar_id); }
function set_newsletter($c_ar_newsletter) { return($this->newsletter=$c_ar_newsletter); }
function set_news_inscrit($c_ar_news_inscrit) { return($this->news_inscrit=$c_ar_news_inscrit); }
function set_statut($c_ar_statut) { return($this->statut=$c_ar_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("ar_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("ar_statut"); }
//
function getTable() { return("news_ar"); }
function getClasse() { return("news_ar"); }
function getDisplay() { return("nom"); }
function getAbstract() { return(""); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar/list_news_ar.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar/maj_news_ar.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar/show_news_ar.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar/rss_news_ar.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar/xml_news_ar.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar/export_news_ar.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_ar/import_news_ar.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>