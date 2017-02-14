<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('cms_diaporama')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_diaporama`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('dia_diaporama_type', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_diaporama` ADD `dia_diaporama_type` INT( 11 ) NULL AFTER `dia_nom` ;");
		}
	}
}


/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_diaporama.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_diaporama.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_diaporama.class.php');
}else{
/*======================================

objet de BDD cms_diaporama :: class cms_diaporama

SQL mySQL:

DROP TABLE IF EXISTS cms_diaporama;
CREATE TABLE cms_diaporama
(
	dia_id			int (11) PRIMARY KEY not null,
	dia_nom			varchar (128),
	dia_diaporama_type			int (11),
	dia_image			varchar (255),
	dia_viewer			varchar (255),
	dia_dtcrea			date,
	dia_dtmod			date,
	dia_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_diaporama
CREATE TABLE cms_diaporama
(
	dia_id			number (11) constraint dia_pk PRIMARY KEY not null,
	dia_nom			varchar2 (128),
	dia_diaporama_type			number (11),
	dia_image			varchar2 (255),
	dia_viewer			varchar2 (255),
	dia_dtcrea			date,
	dia_dtmod			date,
	dia_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_diaporama" prefix="dia" display="nom" abstract="viewer">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assodiapodiaporama" />
<item name="nom" libelle="Nom" type="varchar" length="128" list="true" order="true"  />
<item name="diaporama_type" libelle="Type de diaporama" type="int" length="11" list="true" order="true" fkey="cms_diaporama_type" />
<item name="image" libelle="Image de couverture" type="varchar" length="255" list="false" order="true" option="file" multiple="true" >
<option type="image" maxwidth="120" maxheight="120" />
</item>
<item name="viewer" libelle="Visionneuse" type="varchar" length="255" list="false" order="true" option="file">
<option type="swf" />
</item>
<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
</class>


==========================================*/

class cms_diaporama
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $nom;
var $diaporama_type;
var $image;
var $viewer;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_diaporama\" prefix=\"dia\" display=\"nom\" abstract=\"viewer\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assodiapodiaporama\" />
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"128\" list=\"true\" order=\"true\"  />
<item name=\"diaporama_type\" libelle=\"Type de diaporama\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_diaporama_type\" />
<item name=\"image\" libelle=\"Image de couverture\" type=\"varchar\" length=\"255\" list=\"false\" order=\"true\" option=\"file\" multiple=\"true\" >
<option type=\"image\" maxwidth=\"120\" maxheight=\"120\" />
</item>
<item name=\"viewer\" libelle=\"Visionneuse\" type=\"varchar\" length=\"255\" list=\"false\" order=\"true\" option=\"file\">
<option type=\"swf\" />
</item>
<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_diaporama
(
	dia_id			int (11) PRIMARY KEY not null,
	dia_nom			varchar (128),
	dia_diaporama_type			int (11),
	dia_image			varchar (255),
	dia_viewer			varchar (255),
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
		$this->nom = "";
		$this->diaporama_type = -1;
		$this->image = "";
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
	$laListeChamps[]=new dbChamp("Dia_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Dia_diaporama_type", "entier", "get_diaporama_type", "set_diaporama_type");
	$laListeChamps[]=new dbChamp("Dia_image", "text", "get_image", "set_image");
	$laListeChamps[]=new dbChamp("Dia_viewer", "text", "get_viewer", "set_viewer");
	$laListeChamps[]=new dbChamp("Dia_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Dia_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Dia_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_diaporama_type() { return($this->diaporama_type); }
function get_image() { return($this->image); }
function get_viewer() { return($this->viewer); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_dia_id) { return($this->id=$c_dia_id); }
function set_nom($c_dia_nom) { return($this->nom=$c_dia_nom); }
function set_diaporama_type($c_dia_diaporama_type) { return($this->diaporama_type=$c_dia_diaporama_type); }
function set_image($c_dia_image) { return($this->image=$c_dia_image); }
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
function getTable() { return("cms_diaporama"); }
function getClasse() { return("cms_diaporama"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("viewer"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama/list_cms_diaporama.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama/maj_cms_diaporama.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama/show_cms_diaporama.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama/rss_cms_diaporama.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama/xml_cms_diaporama.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama/xmlxls_cms_diaporama.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama/export_cms_diaporama.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_diaporama/import_cms_diaporama.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>