<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('news_theme')){
	$rs = $db->Execute('SHOW COLUMNS FROM `news_theme`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('theme_abon_criteres', $names)) {
			$rs = $db->Execute("ALTER TABLE `news_theme` ADD `theme_abon_criteres` ENUM ('Y','N') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'N' AFTER `theme_libelle`;");
		}
		if (!in_array('theme_abon_multiple', $names)) {
			$rs = $db->Execute("ALTER TABLE `news_theme` ADD `theme_abon_multiple` ENUM ('Y','N') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'N' AFTER `theme_abon_criteres`;");
		}
		if (!in_array('theme_allow_edit', $names)) {
			$rs = $db->Execute("ALTER TABLE `news_theme` ADD `theme_allow_edit` ENUM ('Y','N') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'Y' AFTER `theme_abon_criteres`;");
		}
	}
}

/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_theme.class.php')  && (strpos(__FILE__,'/include/bo/class/news_theme.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_theme.class.php');
}else{
/*======================================

objet de BDD news_theme :: class news_theme

SQL mySQL:

DROP TABLE IF EXISTS news_theme;
CREATE TABLE news_theme
(
	theme_id			int (11) PRIMARY KEY not null,
	theme_libelle			varchar (255),
	theme_abon_criteres			enum ('Y','N') not null default 'N',
	theme_allow_edit			enum ('Y','N') not null default 'Y',
	theme_abon_multiple			enum ('Y','N') not null default 'N',
	theme_html_fichier			varchar (255),
	theme_date_crea			date,
	theme_statut			int (11) not null
)

SQL Oracle:

DROP TABLE news_theme
CREATE TABLE news_theme
(
	theme_id			number (11) constraint theme_pk PRIMARY KEY not null,
	theme_libelle			varchar2 (255),
	theme_abon_criteres			enum ('Y','N') not null default 'N',
	theme_allow_edit			enum ('Y','N') not null default 'Y',
	theme_abon_multiple			enum ('Y','N') not null default 'N',
	theme_html_fichier			varchar2 (255),
	theme_date_crea			date,
	theme_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_theme" libelle="Catégorie" prefix="theme" display="libelle" abstract="libelle">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="libelle" libelle="Libellé de la catégorie" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="abon_criteres" libelle="Utiliser les critères d'abonnement" type="enum" length="'Y','N'" notnull="true" default="N" />
<item name="allow_edit" libelle="Autoriser l'édition wysiwyg" type="enum" length="'Y','N'" notnull="true" default="Y" />
<item name="abon_multiple" libelle="Permettre les abonnements multiples" type="enum" length="'Y','N'" notnull="true" default="N" />
<item name="html_fichier" libelle="Nom du fichier html" type="varchar" length="255" list="true" order="true"  nohtml="true"/>
<item name="date_crea" type="date" list="true" order="false" />
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>


==========================================*/

class news_theme
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $abon_criteres;
var $allow_edit;
var $abon_multiple;
var $html_fichier;
var $date_crea;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_theme\" libelle=\"Catégorie\" prefix=\"theme\" display=\"libelle\" abstract=\"libelle\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"libelle\" libelle=\"Libellé de la catégorie\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"abon_criteres\" libelle=\"Utiliser les critères d'abonnement\" type=\"enum\" length=\"'Y','N'\" notnull=\"true\" default=\"N\" />
<item name=\"allow_edit\" libelle=\"Autoriser l'édition wysiwyg\" type=\"enum\" length=\"'Y','N'\" notnull=\"true\" default=\"Y\" />
<item name=\"abon_multiple\" libelle=\"Permettre les abonnements multiples\" type=\"enum\" length=\"'Y','N'\" notnull=\"true\" default=\"N\" />
<item name=\"html_fichier\" libelle=\"Nom du fichier html\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\"/>
<item name=\"date_crea\" type=\"date\" list=\"true\" order=\"false\" />
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE news_theme
(
	theme_id			int (11) PRIMARY KEY not null,
	theme_libelle			varchar (255),
	theme_abon_criteres			enum ('Y','N') not null default 'N',
	theme_allow_edit			enum ('Y','N') not null default 'Y',
	theme_abon_multiple			enum ('Y','N') not null default 'N',
	theme_html_fichier			varchar (255),
	theme_date_crea			date,
	theme_statut			int (11) not null
)

";

// constructeur
function news_theme($id=null)
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
		$this->libelle = "";
		$this->abon_criteres = "N";
		$this->allow_edit = "Y";
		$this->abon_multiple = "N";
		$this->html_fichier = "";
		$this->date_crea = date("d/m/Y");
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
	$laListeChamps[]=new dbChamp("Theme_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Theme_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Theme_abon_criteres", "text", "get_abon_criteres", "set_abon_criteres");
	$laListeChamps[]=new dbChamp("Theme_allow_edit", "text", "get_allow_edit", "set_allow_edit");
	$laListeChamps[]=new dbChamp("Theme_abon_multiple", "text", "get_abon_multiple", "set_abon_multiple");
	$laListeChamps[]=new dbChamp("Theme_html_fichier", "text", "get_html_fichier", "set_html_fichier");
	$laListeChamps[]=new dbChamp("Theme_date_crea", "date_formatee", "get_date_crea", "set_date_crea");
	$laListeChamps[]=new dbChamp("Theme_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_abon_criteres() { return($this->abon_criteres); }
function get_allow_edit() { return($this->allow_edit); }
function get_abon_multiple() { return($this->abon_multiple); }
function get_html_fichier() { return($this->html_fichier); }
function get_date_crea() { return($this->date_crea); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_theme_id) { return($this->id=$c_theme_id); }
function set_libelle($c_theme_libelle) { return($this->libelle=$c_theme_libelle); }
function set_abon_criteres($c_theme_abon_criteres) { return($this->abon_criteres=$c_theme_abon_criteres); }
function set_allow_edit($c_theme_allow_edit) { return($this->allow_edit=$c_theme_allow_edit); }
function set_abon_multiple($c_theme_abon_multiple) { return($this->abon_multiple=$c_theme_abon_multiple); }
function set_html_fichier($c_theme_html_fichier) { return($this->html_fichier=$c_theme_html_fichier); }
function set_date_crea($c_theme_date_crea) { return($this->date_crea=$c_theme_date_crea); }
function set_statut($c_theme_statut) { return($this->statut=$c_theme_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("theme_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("theme_statut"); }
//
function getTable() { return("news_theme"); }
function getClasse() { return("news_theme"); }
function getPrefix() { return(""); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("libelle"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme/list_news_theme.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme/maj_news_theme.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme/show_news_theme.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme/rss_news_theme.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme/xml_news_theme.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme/xlsx_news_theme.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme/export_news_theme.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_theme/import_news_theme.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>