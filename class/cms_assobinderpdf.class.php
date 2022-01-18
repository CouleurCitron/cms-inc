<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assobinderpdf :: class cms_assobinderpdf

SQL mySQL:

DROP TABLE IF EXISTS cms_assobinderpdf;
CREATE TABLE cms_assobinderpdf
(
	xbp_id			int (11) PRIMARY KEY not null,
	xbp_cms_binder			int,
	xbp_cms_pdf			int
)

SQL Oracle:

DROP TABLE cms_assobinderpdf
CREATE TABLE cms_assobinderpdf
(
	xbp_id			number (11) constraint xbp_pk PRIMARY KEY not null,
	xbp_cms_binder			number,
	xbp_cms_pdf			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assobinderpdf" libelle="Classeur PDF" is_asso="true" prefix="xbp" display="cms_binder" abstract="cms_pdf">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_binder" libelle="Classeur" type="int" default="0" order="true" list="true" fkey="cms_binder" />
<item name="cms_pdf" libelle="PDF" type="int" default="0" order="true" list="true" fkey="cms_pdf" />
</class>


==========================================*/

class cms_assobinderpdf
{
var $id;
var $cms_binder;
var $cms_pdf;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assobinderpdf\" libelle=\"Classeur PDF\" is_asso=\"true\" prefix=\"xbp\" display=\"cms_binder\" abstract=\"cms_pdf\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_binder\" libelle=\"Classeur\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_binder\" />
<item name=\"cms_pdf\" libelle=\"PDF\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_pdf\" />
</class>";

var $sMySql = "CREATE TABLE cms_assobinderpdf
(
	xbp_id			int (11) PRIMARY KEY not null,
	xbp_cms_binder			int,
	xbp_cms_pdf			int
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_assobinderpdf") == false){
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
		$this->cms_binder = -1;
		$this->cms_pdf = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xbp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xbp_cms_binder", "entier", "get_cms_binder", "set_cms_binder");
	$laListeChamps[]=new dbChamp("Xbp_cms_pdf", "entier", "get_cms_pdf", "set_cms_pdf");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_binder() { return($this->cms_binder); }
function get_cms_pdf() { return($this->cms_pdf); }


// setters
function set_id($c_xbp_id) { return($this->id=$c_xbp_id); }
function set_cms_binder($c_xbp_cms_binder) { return($this->cms_binder=$c_xbp_cms_binder); }
function set_cms_pdf($c_xbp_cms_pdf) { return($this->cms_pdf=$c_xbp_cms_pdf); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xbp_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assobinderpdf"); }
function getClasse() { return("cms_assobinderpdf"); }
function getDisplay() { return("cms_binder"); }
function getAbstract() { return("cms_pdf"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf/list_cms_assobinderpdf.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf/maj_cms_assobinderpdf.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf/show_cms_assobinderpdf.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf/rss_cms_assobinderpdf.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf/xml_cms_assobinderpdf.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf/export_cms_assobinderpdf.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assobinderpdf/import_cms_assobinderpdf.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>