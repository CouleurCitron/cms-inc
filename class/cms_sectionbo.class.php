<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_sectionbo :: class cms_sectionbo

SQL mySQL:

DROP TABLE IF EXISTS cms_sectionbo;
CREATE TABLE cms_sectionbo
(
	sbo_id			int (11) PRIMARY KEY not null,
	sbo_libelle			varchar (255),
	sbo_descriptif			varchar (512),
	sbo_url			varchar (255),
	sbo_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_sectionbo
CREATE TABLE cms_sectionbo
(
	sbo_id			number (11) constraint sbo_pk PRIMARY KEY not null,
	sbo_libelle			varchar2 (255),
	sbo_descriptif			varchar2 (512),
	sbo_url			varchar2 (255),
	sbo_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_sectionbo" libelle="Sections du Back-Office" prefix="sbo" display="libelle" abstract="url">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assosectiongroupe" />
<item name="libelle" libelle="libellé" type="varchar" length="255" list="true" order="true" />
<item name="descriptif" libelle="descriptif" type="varchar" length="512" list="false" order="false" />
<item name="url" libelle="url" type="varchar" length="255" list="true" order="true" option="link" />
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
<langpack lang="fr">
<norecords>Pas de dections de Back-Office à afficher</norecords>
</langpack>
</class>


==========================================*/

class cms_sectionbo
{
var $id;
var $libelle;
var $descriptif;
var $url;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_sectionbo\" libelle=\"Sections du Back-Office\" prefix=\"sbo\" display=\"libelle\" abstract=\"url\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assosectiongroupe\" />
<item name=\"libelle\" libelle=\"libellé\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" />
<item name=\"descriptif\" libelle=\"descriptif\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" />
<item name=\"url\" libelle=\"url\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" option=\"link\" />
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
<langpack lang=\"fr\">
<norecords>Pas de dections de Back-Office à afficher</norecords>
</langpack>
</class>";

var $sMySql = "CREATE TABLE cms_sectionbo
(
	sbo_id			int (11) PRIMARY KEY not null,
	sbo_libelle			varchar (255),
	sbo_descriptif			varchar (512),
	sbo_url			varchar (255),
	sbo_statut			int (11) not null
)

";

// constructeur
function cms_sectionbo($id=null)
{
	if (istable("cms_sectionbo") == false){
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
		$this->descriptif = "";
		$this->url = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Sbo_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Sbo_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Sbo_descriptif", "text", "get_descriptif", "set_descriptif");
	$laListeChamps[]=new dbChamp("Sbo_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Sbo_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_descriptif() { return($this->descriptif); }
function get_url() { return($this->url); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_sbo_id) { return($this->id=$c_sbo_id); }
function set_libelle($c_sbo_libelle) { return($this->libelle=$c_sbo_libelle); }
function set_descriptif($c_sbo_descriptif) { return($this->descriptif=$c_sbo_descriptif); }
function set_url($c_sbo_url) { return($this->url=$c_sbo_url); }
function set_statut($c_sbo_statut) { return($this->statut=$c_sbo_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("sbo_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("sbo_statut"); }
//
function getTable() { return("cms_sectionbo"); }
function getClasse() { return("cms_sectionbo"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("url"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo/list_cms_sectionbo.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo/maj_cms_sectionbo.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo/show_cms_sectionbo.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo/rss_cms_sectionbo.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo/xml_cms_sectionbo.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo/export_cms_sectionbo.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sectionbo/import_cms_sectionbo.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>