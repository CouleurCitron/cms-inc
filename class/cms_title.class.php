<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_title :: class cms_title

SQL mySQL:

DROP TABLE IF EXISTS cms_title;
CREATE TABLE cms_title
(
	tit_id			int (11) PRIMARY KEY,
	tit_nom			varchar (255),
	tit_cms_site			int (11),
	tit_statut			int not null
)

SQL Oracle:

DROP TABLE cms_title
CREATE TABLE cms_title
(
	tit_id			number (11) constraint tit_pk PRIMARY KEY,
	tit_nom			varchar2 (255),
	tit_cms_site			number (11),
	tit_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_title" libelle="Titre par objet" prefix="tit" display="id" abstract="nom" >
<item name="id" type="int" length="11" list="true" order="true" nohtml="true" isprimary="true" asso="cms_assotitleclasse" />
<item name="nom"  type="varchar" length="255" list="true" order="true"  nohtml="true" oblig="true"/>
<item name="cms_site" libelle="mini-site" type="int" length="11" list="true" order="true" fkey="cms_site" /> 
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>




==========================================*/

class cms_title
{
var $id;
var $nom;
var $cms_site;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_title\" libelle=\"Titre par objet\" prefix=\"tit\" display=\"id\" abstract=\"nom\" >
<item name=\"id\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" nohtml=\"true\" isprimary=\"true\" asso=\"cms_assotitleclasse\" />
<item name=\"nom\"  type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" oblig=\"true\"/>
<item name=\"cms_site\" libelle=\"mini-site\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_site\" /> 
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>

";

var $sMySql = "CREATE TABLE cms_title
(
	tit_id			int (11) PRIMARY KEY,
	tit_nom			varchar (255),
	tit_cms_site			int (11),
	tit_statut			int not null
)

";

// constructeur
function cms_title($id=null)
{
	if (istable("cms_title") == false){
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
		$this->nom = "";
		$this->cms_site = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Tit_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Tit_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Tit_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Tit_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_cms_site() { return($this->cms_site); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_tit_id) { return($this->id=$c_tit_id); }
function set_nom($c_tit_nom) { return($this->nom=$c_tit_nom); }
function set_cms_site($c_tit_cms_site) { return($this->cms_site=$c_tit_cms_site); }
function set_statut($c_tit_statut) { return($this->statut=$c_tit_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("tit_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("tit_statut"); }
//
function getTable() { return("cms_title"); }
function getClasse() { return("cms_title"); }
function getDisplay() { return("id"); }
function getAbstract() { return("nom"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title/list_cms_title.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title/maj_cms_title.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title/show_cms_title.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title/rss_cms_title.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title/xml_cms_title.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title/export_cms_title.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_title/import_cms_title.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>