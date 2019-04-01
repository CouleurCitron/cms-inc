<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_tableau_perempt :: class cms_tableau_perempt

SQL mySQL:

DROP TABLE IF EXISTS cms_tableau_perempt;
CREATE TABLE cms_tableau_perempt
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_tableau			int (11),
	cms_version			int (11),
	cms_html			text
)

SQL Oracle:

DROP TABLE cms_tableau_perempt
CREATE TABLE cms_tableau_perempt
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_tableau			number (11),
	cms_version			number (11),
	cms_html			text
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_tableau_perempt" prefix="cms" display="libelle" abstract="tableau">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="tableau" libelle="Tableau correspond" type="int" length="11"  list="true" order="true" default="1" fkey="cms_tableau"/> 
<item name="version" libelle="Version" type="int" length="11"  list="true" order="true" default="0" />
<item name="html" libelle="HTML du tableau" type="text" list="false" order="false" option="textarea" nohtml="false"/>
</class>


==========================================*/

class cms_tableau_perempt
{
var $id;
var $tableau;
var $version;
var $html;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_tableau_perempt\" prefix=\"cms\" display=\"libelle\" abstract=\"tableau\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"tableau\" libelle=\"Tableau correspond\" type=\"int\" length=\"11\"  list=\"true\" order=\"true\" default=\"1\" fkey=\"cms_tableau\"/> 
<item name=\"version\" libelle=\"Version\" type=\"int\" length=\"11\"  list=\"true\" order=\"true\" default=\"0\" />
<item name=\"html\" libelle=\"HTML du tableau\" type=\"text\" list=\"false\" order=\"false\" option=\"textarea\" nohtml=\"false\"/>
</class>";

var $sMySql = "CREATE TABLE cms_tableau_perempt
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_tableau			int (11),
	cms_version			int (11),
	cms_html			text
)

";

// constructeur
function cms_tableau_perempt($id=null)
{
	if (istable("cms_tableau_perempt") == false){
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
		$this->tableau = 1;
		$this->version = -1;
		$this->html = "";
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_tableau", "entier", "get_tableau", "set_tableau");
	$laListeChamps[]=new dbChamp("Cms_version", "entier", "get_version", "set_version");
	$laListeChamps[]=new dbChamp("Cms_html", "text", "get_html", "set_html");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_tableau() { return($this->tableau); }
function get_version() { return($this->version); }
function get_html() { return($this->html); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_tableau($c_cms_tableau) { return($this->tableau=$c_cms_tableau); }
function set_version($c_cms_version) { return($this->version=$c_cms_version); }
function set_html($c_cms_html) { return($this->html=$c_cms_html); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_tableau_perempt"); }
function getClasse() { return("cms_tableau_perempt"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("tableau"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt/list_cms_tableau_perempt.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt/maj_cms_tableau_perempt.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt/show_cms_tableau_perempt.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt/rss_cms_tableau_perempt.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt/xml_cms_tableau_perempt.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt/export_cms_tableau_perempt.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_perempt/import_cms_tableau_perempt.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>