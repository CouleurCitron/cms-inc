<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_tableau :: class cms_tableau

SQL mySQL:

DROP TABLE IF EXISTS cms_tableau;
CREATE TABLE cms_tableau
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_reference			varchar (255),
	cms_libelle			varchar (255),
	cms_description			text,
	cms_html			text,
	cms_htmltmp			text,
	cms_basdepage			text,
	cms_composant			int (11),
	cms_cms_site			int (11),
	cms_ordre			int not null,
	cms_statut			int not null
)

SQL Oracle:

DROP TABLE cms_tableau
CREATE TABLE cms_tableau
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_reference			varchar2 (255),
	cms_libelle			varchar2 (255),
	cms_description			text,
	cms_html			text,
	cms_htmltmp			text,
	cms_basdepage			text,
	cms_composant			number (11),
	cms_cms_site			number (11),
	cms_ordre			number not null,
	cms_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_tableau" prefix="cms" display="libelle" abstract="description">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" order="true" list="true" />
<item name="reference" libelle="Référence" type="varchar" length="255" list="true" order="true" />
<item name="libelle" libelle="Libellé du tableau" type="varchar" length="255" list="true" order="true" />
<item name="description" libelle="Description du tableau" type="text" list="false" order="true" option="textarea"/>
<item name="html" libelle="HTML du tableau" type="text" list="false" order="false" option="textarea" nohtml="false"/>
<item name="htmltmp" libelle="HTML du tableau" type="text" list="false" order="false" option="module" module="create" nohtml="false"/>
<item name="basdepage" libelle="Notes base de page" type="text" list="false" order="true" option="textarea"/>
<item name="composant" type="int" length="11" list="true" order="true" fkey="composant" oblig="true"/>
<item name="cms_site" type="int" length="11" list="false" order="false" fkey="cms_site"/>
<item name="ordre" type="int" notnull="false" default="0" list="false" order="false" />
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>


==========================================*/

class cms_tableau
{
var $id;
var $reference;
var $libelle;
var $description;
var $html;
var $htmltmp;
var $basdepage;
var $composant;
var $cms_site;
var $ordre;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_tableau\" prefix=\"cms\" display=\"libelle\" abstract=\"description\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" order=\"true\" list=\"true\" />
<item name=\"reference\" libelle=\"Référence\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" />
<item name=\"libelle\" libelle=\"Libellé du tableau\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" />
<item name=\"description\" libelle=\"Description du tableau\" type=\"text\" list=\"false\" order=\"true\" option=\"textarea\"/>
<item name=\"html\" libelle=\"HTML du tableau\" type=\"text\" list=\"false\" order=\"false\" option=\"textarea\" nohtml=\"false\"/>
<item name=\"htmltmp\" libelle=\"HTML du tableau\" type=\"text\" list=\"false\" order=\"false\" option=\"module\" module=\"create\" nohtml=\"false\"/>
<item name=\"basdepage\" libelle=\"Notes base de page\" type=\"text\" list=\"false\" order=\"true\" option=\"textarea\"/>
<item name=\"composant\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"composant\" oblig=\"true\"/>
<item name=\"cms_site\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"cms_site\"/>
<item name=\"ordre\" type=\"int\" notnull=\"false\" default=\"0\" list=\"false\" order=\"false\" />
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>";

var $sMySql = "CREATE TABLE cms_tableau
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_reference			varchar (255),
	cms_libelle			varchar (255),
	cms_description			text,
	cms_html			text,
	cms_htmltmp			text,
	cms_basdepage			text,
	cms_composant			int (11),
	cms_cms_site			int (11),
	cms_ordre			int not null,
	cms_statut			int not null
)

";

// constructeur
function cms_tableau($id=null)
{
	if (istable("cms_tableau") == false){
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
		$this->reference = "";
		$this->libelle = "";
		$this->description = "";
		$this->html = "";
		$this->htmltmp = "";
		$this->basdepage = "";
		$this->composant = -1;
		$this->cms_site = -1;
		$this->ordre = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_reference", "text", "get_reference", "set_reference");
	$laListeChamps[]=new dbChamp("Cms_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Cms_description", "text", "get_description", "set_description");
	$laListeChamps[]=new dbChamp("Cms_html", "text", "get_html", "set_html");
	$laListeChamps[]=new dbChamp("Cms_htmltmp", "text", "get_htmltmp", "set_htmltmp");
	$laListeChamps[]=new dbChamp("Cms_basdepage", "text", "get_basdepage", "set_basdepage");
	$laListeChamps[]=new dbChamp("Cms_composant", "entier", "get_composant", "set_composant");
	$laListeChamps[]=new dbChamp("Cms_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Cms_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_reference() { return($this->reference); }
function get_libelle() { return($this->libelle); }
function get_description() { return($this->description); }
function get_html() { return($this->html); }
function get_htmltmp() { return($this->htmltmp); }
function get_basdepage() { return($this->basdepage); }
function get_composant() { return($this->composant); }
function get_cms_site() { return($this->cms_site); }
function get_ordre() { return($this->ordre); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_reference($c_cms_reference) { return($this->reference=$c_cms_reference); }
function set_libelle($c_cms_libelle) { return($this->libelle=$c_cms_libelle); }
function set_description($c_cms_description) { return($this->description=$c_cms_description); }
function set_html($c_cms_html) { return($this->html=$c_cms_html); }
function set_htmltmp($c_cms_htmltmp) { return($this->htmltmp=$c_cms_htmltmp); }
function set_basdepage($c_cms_basdepage) { return($this->basdepage=$c_cms_basdepage); }
function set_composant($c_cms_composant) { return($this->composant=$c_cms_composant); }
function set_cms_site($c_cms_cms_site) { return($this->cms_site=$c_cms_cms_site); }
function set_ordre($c_cms_ordre) { return($this->ordre=$c_cms_ordre); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_tableau"); }
function getClasse() { return("cms_tableau"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("description"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau/list_cms_tableau.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau/maj_cms_tableau.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau/show_cms_tableau.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau/rss_cms_tableau.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau/xml_cms_tableau.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau/export_cms_tableau.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tableau/import_cms_tableau.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>