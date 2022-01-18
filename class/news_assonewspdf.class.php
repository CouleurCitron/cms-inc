<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_assonewspdf.class.php')  && (strpos(__FILE__,'/include/bo/class/news_assonewspdf.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_assonewspdf.class.php');
}else{
/*======================================

objet de BDD news_assonewspdf :: class news_assonewspdf

SQL mySQL:

DROP TABLE IF EXISTS news_assonewspdf;
CREATE TABLE news_assonewspdf
(
	nws_id			int (11) PRIMARY KEY not null,
	nws_newsletter			int,
	nws_cms_pdf			int
)

SQL Oracle:

DROP TABLE news_assonewspdf
CREATE TABLE news_assonewspdf
(
	nws_id			number (11) constraint nws_pk PRIMARY KEY not null,
	nws_newsletter			number,
	nws_cms_pdf			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_assonewspdf" libelle="Documents associés" prefix="nws" display="newsletter" abstract="cms_pdf" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" />
<item name="newsletter" libelle="Newsletter"  type="int" default="0" order="true" list="true" fkey="newsletter" />
<item name="cms_pdf" libelle="Document"  type="int" default="0" order="true" list="true" fkey="cms_pdf" />  
<langpack lang="fr">
<norecords>Pas d'asso newsletter/document à afficher</norecords>
</langpack>
</class>


==========================================*/

class news_assonewspdf
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $newsletter;
var $cms_pdf;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_assonewspdf\" libelle=\"Documents associés\" prefix=\"nws\" display=\"newsletter\" abstract=\"cms_pdf\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" />
<item name=\"newsletter\" libelle=\"Newsletter\"  type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"newsletter\" />
<item name=\"cms_pdf\" libelle=\"Document\"  type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_pdf\" />  
<langpack lang=\"fr\">
<norecords>Pas d'asso newsletter/document à afficher</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE news_assonewspdf
(
	nws_id			int (11) PRIMARY KEY not null,
	nws_newsletter			int,
	nws_cms_pdf			int
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
		$this->newsletter = -1;
		$this->cms_pdf = -1;
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
	$laListeChamps[]=new dbChamp("Nws_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Nws_newsletter", "entier", "get_newsletter", "set_newsletter");
	$laListeChamps[]=new dbChamp("Nws_cms_pdf", "entier", "get_cms_pdf", "set_cms_pdf");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_newsletter() { return($this->newsletter); }
function get_cms_pdf() { return($this->cms_pdf); }


// setters
function set_id($c_nws_id) { return($this->id=$c_nws_id); }
function set_newsletter($c_nws_newsletter) { return($this->newsletter=$c_nws_newsletter); }
function set_cms_pdf($c_nws_cms_pdf) { return($this->cms_pdf=$c_nws_cms_pdf); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("nws_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("news_assonewspdf"); }
function getClasse() { return("news_assonewspdf"); }
function getDisplay() { return("newsletter"); }
function getAbstract() { return("cms_pdf"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf/list_news_assonewspdf.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[newSizeOf(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf/maj_news_assonewspdf.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf/show_news_assonewspdf.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf/rss_news_assonewspdf.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf/xml_news_assonewspdf.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf/xlsx_news_assonewspdf.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf/export_news_assonewspdf.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assonewspdf/import_news_assonewspdf.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>