<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('cms_survey_ask')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_survey_ask`');
	if ($rs) {
		$fields = Array();
		while(!$rs->EOF) {
			$fields[$rs->fields["Field"]] = $rs->fields;
			//pre_dump($rs->fields);
			$rs->MoveNext();
		
		}
		$names = array_keys($fields);
		if ($fields['cms_libelle']['Type'] != 'int(11)')	
			$rs = $db->Execute(" ALTER TABLE `cms_survey_ask` CHANGE `cms_libelle` `cms_libelle` INT( 11 ) NOT NULL;");
		if (!in_array('cms_multiple', $names))	
			$rs = $db->Execute("ALTER TABLE `cms_survey_ask` ADD `cms_multiple` ENUM( 'Y', 'N' ) DEFAULT 'N' NOT NULL AFTER `cms_libelle`;");
		if (!in_array('cms_active', $names))
			$rs = $db->Execute("ALTER TABLE `cms_survey_ask` ADD `cms_active` ENUM( 'Y', 'N' ) DEFAULT 'N' NOT NULL AFTER `cms_multiple`;");
		if (in_array('cms_site', $names))
			$rs = $db->Execute("ALTER TABLE `cms_survey_ask` CHANGE `cms_site` `cms_id_site` INT( 5 ) NULL DEFAULT NULL;");
	}
}
/*======================================


objet de BDD cms_survey_ask :: class cms_survey_ask

SQL mySQL:

DROP TABLE IF EXISTS cms_survey_ask;
CREATE TABLE cms_survey_ask
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			int(11) not null,
	cms_multiple			enum ('Y','N') default 'N' not null,
	cms_active			enum ('Y','N') default 'N' not null,
	cms_dateadd			date not null,
	cms_dateupd			date not null,
	cms_id_site			int (5)
)

SQL Oracle:

DROP TABLE cms_survey_ask
CREATE TABLE cms_survey_ask
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_libelle			number (11) not null,
	cms_multiple			enum ('Y','N') default 'N' not null,
	cms_active			enum ('Y','N') default 'N' not null,
	cms_dateadd			date not null,
	cms_dateupd			date not null,
	cms_id_site			number (5)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_survey_ask" libelle="Sondage - questions" prefix="cms" display="libelle" abstract="dateadd">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" notnull="true" list="true" asso="cms_avis" />
<item name="libelle" libelle="Titre" type="int" length="11" list="true" order="false" translate="reference" />  
<item name="multiple" libelle="Multiple réponse" type="enum" length="'Y','N'" notnull="true" default="N" />
<item name="active" libelle="Activée" type="enum" length="'Y','N'" notnull="true" default="N" />
<item name="dateadd" libelle="Date de créa" type="date" notnull="false" list="false" order="false" />  
<item name="dateupd" libelle="Date de maj" type="date"  notnull="false" list="false" order="false" /> 
<item name="id_site" libelle="Image" type="int" length="5" list="false" order="false" fkey="cms_site"/>   
</class>


==========================================*/

class cms_survey_ask
{
var $id;
var $libelle;
var $multiple;
var $active;
var $dateadd;
var $dateupd;
var $site;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_survey_ask\" libelle=\"Sondage - questions\" prefix=\"cms\" display=\"libelle\" abstract=\"dateadd\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_avis\" />
<item name=\"libelle\" libelle=\"Titre\" type=\"int\" length=\"11\" list=\"true\" order=\"false\" translate=\"reference\" />  
<item name=\"multiple\" libelle=\"Multiple réponse\" type=\"enum\" length=\"'Y','N'\" notnull=\"true\" default=\"N\" />
<item name=\"active\" libelle=\"Activée\" type=\"enum\" length=\"'Y','N'\" notnull=\"true\" default=\"N\" />
<item name=\"dateadd\" libelle=\"Date de créa\" type=\"date\" notnull=\"false\" list=\"false\" order=\"false\" />  
<item name=\"dateupd\" libelle=\"Date de maj\" type=\"date\"  notnull=\"false\" list=\"false\" order=\"false\" /> 
<item name=\"id_site\" libelle=\"Image\" type=\"int\" length=\"5\" list=\"false\" order=\"false\" fkey=\"cms_site\"/>   
</class>";

var $sMySql = "CREATE TABLE cms_survey_ask
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			int (11) not null,
	cms_multiple			enum ('Y','N') default 'N',
	cms_active			enum ('Y','N') default 'N',
	cms_dateadd			date not null,
	cms_dateupd			date not null,
	cms_id_site			int (5)
)

";

// constructeur
function cms_survey_ask($id=null)
{
	if (istable("cms_survey_ask") == false){
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
		$this->libelle = -1;
		$this->multiple = "N";
		$this->active = "N";
		$this->dateadd = date("d/m/Y");
		$this->dateupd = date("d/m/Y");
		$this->id_site = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("cms_libelle", "entier", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("cms_multiple", "text", "get_multiple", "set_multiple");
	$laListeChamps[]=new dbChamp("cms_active", "text", "get_active", "set_active");
	$laListeChamps[]=new dbChamp("cms_dateadd", "date_formatee", "get_dateadd", "set_dateadd");
	$laListeChamps[]=new dbChamp("cms_dateupd", "date_formatee", "get_dateupd", "set_dateupd");
	$laListeChamps[]=new dbChamp("cms_id_site", "entier", "get_id_site", "set_id_site");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_multiple() { return($this->multiple); }
function get_active() { return($this->active); }
function get_dateadd() { return($this->dateadd); }
function get_dateupd() { return($this->dateupd); }
function get_id_site() { return($this->id_site); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_libelle($c_cms_libelle) { return($this->libelle=$c_cms_libelle); }
function set_multiple($c_cms_multiple) { return($this->multiple=$c_cms_multiple); }
function set_active($c_cms_active) { return($this->active=$c_cms_active); }
function set_dateadd($c_cms_dateadd) { return($this->dateadd=$c_cms_dateadd); }
function set_dateupd($c_cms_dateupd) { return($this->dateupd=$c_cms_dateupd); }
function set_id_site($c_cms_id_site) { return($this->id_site=$c_cms_id_site); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_survey_ask"); }
function getClasse() { return("cms_survey_ask"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("dateadd"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask/list_cms_survey_ask.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask/maj_cms_survey_ask.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask/show_cms_survey_ask.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask/rss_cms_survey_ask.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask/xml_cms_survey_ask.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask/export_cms_survey_ask.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_survey_ask/import_cms_survey_ask.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>