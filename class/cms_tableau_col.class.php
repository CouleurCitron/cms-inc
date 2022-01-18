<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_tableau_col :: class cms_tableau_col

SQL mySQL:

DROP TABLE IF EXISTS cms_tableau_col;
CREATE TABLE cms_tableau_col
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			varchar (255),
	cms_description			text,
	cms_tableau			int (11),
	cms_ordre			int (11),
	cms_cms_site			int (11),
	cms_statut			int not null
)

SQL Oracle:

DROP TABLE cms_tableau_col
CREATE TABLE cms_tableau_col
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_libelle			varchar2 (255),
	cms_description			text,
	cms_tableau			number (11),
	cms_ordre			number (11),
	cms_cms_site			number (11),
	cms_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_tableau_col" prefix="cms" display="libelle" abstract="tableau">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="libelle" libelle="Libellé de la colonne" type="varchar" length="255" list="true" order="true" />
<item name="description" libelle="Description de la colonne" type="text" list="true" order="true" option="textarea"/>
<item name="tableau" libelle="Tableau auquel appartient la colonne" type="int" length="11"  list="true" order="true" default="1" fkey="cms_tableau"/> 
<item name="ordre" type="int" length="11"  list="true" order="true" default="0"/> 
<item name="cms_site" type="int" length="11"  list="true" order="true" default="1" fkey="cms_site"/> 
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>


==========================================*/

class cms_tableau_col
{
var $id;
var $libelle;
var $description;
var $tableau;
var $ordre;
var $cms_site;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_tableau_col\" prefix=\"cms\" display=\"libelle\" abstract=\"tableau\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"libelle\" libelle=\"Libellé de la colonne\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" />
<item name=\"description\" libelle=\"Description de la colonne\" type=\"text\" list=\"true\" order=\"true\" option=\"textarea\"/>
<item name=\"tableau\" libelle=\"Tableau auquel appartient la colonne\" type=\"int\" length=\"11\"  list=\"true\" order=\"true\" default=\"1\" fkey=\"cms_tableau\"/> 
<item name=\"ordre\" type=\"int\" length=\"11\"  list=\"true\" order=\"true\" default=\"0\"/> 
<item name=\"cms_site\" type=\"int\" length=\"11\"  list=\"true\" order=\"true\" default=\"1\" fkey=\"cms_site\"/> 
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>";

var $sMySql = "CREATE TABLE cms_tableau_col
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			varchar (255),
	cms_description			text,
	cms_tableau			int (11),
	cms_ordre			int (11),
	cms_cms_site			int (11),
	cms_statut			int not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_tableau_col") == false){
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
		$this->libelle = "";
		$this->description = "";
		$this->tableau = 1;
		$this->ordre = -1;
		$this->cms_site = 1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Cms_description", "text", "get_description", "set_description");
	$laListeChamps[]=new dbChamp("Cms_tableau", "entier", "get_tableau", "set_tableau");
	$laListeChamps[]=new dbChamp("Cms_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Cms_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_description() { return($this->description); }
function get_tableau() { return($this->tableau); }
function get_ordre() { return($this->ordre); }
function get_cms_site() { return($this->cms_site); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_libelle($c_cms_libelle) { return($this->libelle=$c_cms_libelle); }
function set_description($c_cms_description) { return($this->description=$c_cms_description); }
function set_tableau($c_cms_tableau) { return($this->tableau=$c_cms_tableau); }
function set_ordre($c_cms_ordre) { return($this->ordre=$c_cms_ordre); }
function set_cms_site($c_cms_cms_site) { return($this->cms_site=$c_cms_cms_site); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_tableau_col"); }
function getClasse() { return("cms_tableau_col"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("tableau"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col/list_cms_tableau_col.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col/maj_cms_tableau_col.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col/show_cms_tableau_col.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col/rss_cms_tableau_col.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col/xml_cms_tableau_col.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col/export_cms_tableau_col.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau_col/import_cms_tableau_col.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>