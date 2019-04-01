<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_description :: class cms_description

SQL mySQL:

DROP TABLE IF EXISTS cms_description;
CREATE TABLE cms_description
(
	des_id			int (11) PRIMARY KEY,
	des_description			text,
	des_cms_site			int (11),
	des_statut			int not null
)

SQL Oracle:

DROP TABLE cms_description
CREATE TABLE cms_description
(
	des_id			number (11) constraint des_pk PRIMARY KEY,
	des_description			text,
	des_cms_site			number (11),
	des_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_description" libelle="Description par objet" prefix="des" display="id" abstract="nom" >
<item name="id" type="int" length="11" list="true" order="true" nohtml="true" isprimary="true" asso="cms_assodescriptionclasse" />
<item name="description" type="text" list="true" order="true"  nohtml="true" oblig="true" option="textarea"/>
<item name="cms_site" libelle="mini-site" type="int" length="11" list="true" order="true" fkey="cms_site" /> 
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>




==========================================*/

class cms_description
{
var $id;
var $description;
var $cms_site;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_description\" libelle=\"Description par objet\" prefix=\"des\" display=\"id\" abstract=\"nom\" >
<item name=\"id\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" nohtml=\"true\" isprimary=\"true\" asso=\"cms_assodescriptionclasse\" />
<item name=\"description\" type=\"text\" list=\"true\" order=\"true\"  nohtml=\"true\" oblig=\"true\" option=\"textarea\"/>
<item name=\"cms_site\" libelle=\"mini-site\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_site\" /> 
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>

";

var $sMySql = "CREATE TABLE cms_description
(
	des_id			int (11) PRIMARY KEY,
	des_description			text,
	des_cms_site			int (11),
	des_statut			int not null
)

";

// constructeur
function cms_description($id=null)
{
	if (istable("cms_description") == false){
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
		$this->description = "";
		$this->cms_site = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Des_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Des_description", "text", "get_description", "set_description");
	$laListeChamps[]=new dbChamp("Des_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Des_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_description() { return($this->description); }
function get_cms_site() { return($this->cms_site); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_des_id) { return($this->id=$c_des_id); }
function set_description($c_des_description) { return($this->description=$c_des_description); }
function set_cms_site($c_des_cms_site) { return($this->cms_site=$c_des_cms_site); }
function set_statut($c_des_statut) { return($this->statut=$c_des_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("des_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("des_statut"); }
//
function getTable() { return("cms_description"); }
function getClasse() { return("cms_description"); }
function getDisplay() { return("id"); }
function getAbstract() { return("nom"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description/list_cms_description.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description/maj_cms_description.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description/show_cms_description.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description/rss_cms_description.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description/xml_cms_description.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description/export_cms_description.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_description/import_cms_description.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>