<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assojsarbopages :: class cms_assojsarbopages

SQL mySQL:

DROP TABLE IF EXISTS cms_assojsarbopages;
CREATE TABLE cms_assojsarbopages
(
	xja_id			int (11) PRIMARY KEY not null,
	xja_cms_js			int,
	xja_cms_arbo_pages			int
)

SQL Oracle:

DROP TABLE cms_assojsarbopages
CREATE TABLE cms_assojsarbopages
(
	xja_id			number (11) constraint xja_pk PRIMARY KEY not null,
	xja_cms_js			number,
	xja_cms_arbo_pages			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assojsarbopages" is_asso="true" libelle="Asso JS script et arbo" prefix="xja" display="cms_js" abstract="cms_arbo_pages">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_js" libelle="JS script" type="int" default="0" order="true" list="true" fkey="cms_js" />
<item name="cms_arbo_pages" libelle="Arbo Node" type="int" default="0" order="true" list="true" fkey="cms_arbo_pages" />
</class>


==========================================*/

class cms_assojsarbopages
{
var $id;
var $cms_js;
var $cms_arbo_pages;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assojsarbopages\" is_asso=\"true\" libelle=\"Asso JS script et arbo\" prefix=\"xja\" display=\"cms_js\" abstract=\"cms_arbo_pages\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_js\" libelle=\"JS script\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_js\" />
<item name=\"cms_arbo_pages\" libelle=\"Arbo Node\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_arbo_pages\" />
</class>";

var $sMySql = "CREATE TABLE cms_assojsarbopages
(
	xja_id			int (11) PRIMARY KEY not null,
	xja_cms_js			int,
	xja_cms_arbo_pages			int
)

";

// constructeur
function cms_assojsarbopages($id=null)
{
	if (istable("cms_assojsarbopages") == false){
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
		$this->cms_js = -1;
		$this->cms_arbo_pages = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xja_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xja_cms_js", "entier", "get_cms_js", "set_cms_js");
	$laListeChamps[]=new dbChamp("Xja_cms_arbo_pages", "entier", "get_cms_arbo_pages", "set_cms_arbo_pages");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_js() { return($this->cms_js); }
function get_cms_arbo_pages() { return($this->cms_arbo_pages); }


// setters
function set_id($c_xja_id) { return($this->id=$c_xja_id); }
function set_cms_js($c_xja_cms_js) { return($this->cms_js=$c_xja_cms_js); }
function set_cms_arbo_pages($c_xja_cms_arbo_pages) { return($this->cms_arbo_pages=$c_xja_cms_arbo_pages); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xja_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assojsarbopages"); }
function getClasse() { return("cms_assojsarbopages"); }
function getDisplay() { return("cms_js"); }
function getAbstract() { return("cms_arbo_pages"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages/list_cms_assojsarbopages.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages/maj_cms_assojsarbopages.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages/show_cms_assojsarbopages.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages/rss_cms_assojsarbopages.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages/xml_cms_assojsarbopages.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages/xmlxls_cms_assojsarbopages.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages/export_cms_assojsarbopages.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojsarbopages/import_cms_assojsarbopages.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>