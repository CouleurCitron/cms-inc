<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_diaporama_type.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_diaporama_type.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_diaporama_type.class.php');
}else{
/*======================================

objet de BDD cms_diaporama_type :: class cms_diaporama_type

SQL mySQL:

DROP TABLE IF EXISTS cms_diaporama_type;
CREATE TABLE cms_diaporama_type
(
	dia_id			int (11) PRIMARY KEY not null,
	dia_nom			int (11),
	dia_width			int (11),
	dia_height			int (11),
	dia_viewer			varchar (512),
	dia_dtcrea			date,
	dia_dtmod			date,
	dia_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_diaporama_type
CREATE TABLE cms_diaporama_type
(
	dia_id			number (11) constraint dia_pk PRIMARY KEY not null,
	dia_nom			number (11),
	dia_width			number (11),
	dia_height			number (11),
	dia_viewer			varchar2 (512),
	dia_dtcrea			date,
	dia_dtmod			date,
	dia_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_diaporama_type" prefix="dia" display="nom" abstract="width">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_diaporama"  libelle="Type de diaporama"/>
<item name="nom" libelle="Nom" type="int" length="11" list="true" order="true" translate="reference" /> 

<item name="width" libelle="Largeur" type="int" length="11" list="true" order="true" nohtml="true" /> 
<item name="height" libelle="Hauteur" type="int" length="11" list="true" order="true" nohtml="true" /> 

<item name="viewer" libelle="Code visionneuse" type="varchar" length="512" list="false" option="textarea"/>

<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
</class>


==========================================*/

class cms_diaporama_type
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $nom;
var $width;
var $height;
var $viewer;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_diaporama_type\" prefix=\"dia\" display=\"nom\" abstract=\"width\" libelle=\"Type de diaporama\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_diaporama\" />
<item name=\"nom\" libelle=\"Nom\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\" /> 

<item name=\"width\" libelle=\"Largeur\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" nohtml=\"true\" /> 
<item name=\"height\" libelle=\"Hauteur\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" nohtml=\"true\" /> 

<item name=\"viewer\" libelle=\"Code visionneuse\" type=\"varchar\" length=\"512\" list=\"false\" option=\"textarea\"/>

<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_diaporama_type
(
	dia_id			int (11) PRIMARY KEY not null,
	dia_nom			int (11),
	dia_width			int (11),
	dia_height			int (11),
	dia_viewer			varchar (512),
	dia_dtcrea			date,
	dia_dtmod			date,
	dia_statut			int (11) not null
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
		$this->nom = -1;
		$this->width = -1;
		$this->height = -1;
		$this->viewer = "";
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
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
	$laListeChamps[]=new dbChamp("Dia_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Dia_nom", "entier", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Dia_width", "entier", "get_width", "set_width");
	$laListeChamps[]=new dbChamp("Dia_height", "entier", "get_height", "set_height");
	$laListeChamps[]=new dbChamp("Dia_viewer", "text", "get_viewer", "set_viewer");
	$laListeChamps[]=new dbChamp("Dia_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Dia_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Dia_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_width() { return($this->width); }
function get_height() { return($this->height); }
function get_viewer() { return($this->viewer); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_dia_id) { return($this->id=$c_dia_id); }
function set_nom($c_dia_nom) { return($this->nom=$c_dia_nom); }
function set_width($c_dia_width) { return($this->width=$c_dia_width); }
function set_height($c_dia_height) { return($this->height=$c_dia_height); }
function set_viewer($c_dia_viewer) { return($this->viewer=$c_dia_viewer); }
function set_dtcrea($c_dia_dtcrea) { return($this->dtcrea=$c_dia_dtcrea); }
function set_dtmod($c_dia_dtmod) { return($this->dtmod=$c_dia_dtmod); }
function set_statut($c_dia_statut) { return($this->statut=$c_dia_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("dia_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("dia_statut"); }
//
function getTable() { return("cms_diaporama_type"); }
function getClasse() { return("cms_diaporama_type"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("width"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type/list_cms_diaporama_type.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[newSizeOf(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type/maj_cms_diaporama_type.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type/show_cms_diaporama_type.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type/rss_cms_diaporama_type.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type/xml_cms_diaporama_type.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type/xmlxls_cms_diaporama_type.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type/export_cms_diaporama_type.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama_type/import_cms_diaporama_type.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>