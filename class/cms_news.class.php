<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('cms_news')){
	$rs = $db->Execute('DESCRIBE `cms_news`');
	if (isset($rs->_numOfRows)){
		if ($rs->_numOfRows == 10){
			$rs = $db->Execute('ALTER TABLE `cms_news` ADD `nws_url` VARCHAR( 256 )  NULL AFTER `nws_diaporama` , ADD `nws_ordre` INT NULL DEFAULT \'0\' AFTER `nws_url` , ADD `nws_cms_site` INT( 11 ) NULL DEFAULT \'1\' AFTER `nws_ordre`,  ADD `nws_ddate_publication` DATETIME NOT NULL AFTER `nws_ordre`, ADD `nws_fdate_publication` DATETIME NOT NULL AFTER `nws_ddate_publication`, ADD `nws_vignette` VARCHAR( 255 ) NOT NULL AFTER `nws_document` ;');
		}  
		elseif ($rs->_numOfRows == 12){
			$rs = $db->Execute('ALTER TABLE `cms_news` ADD `nws_cms_site` INT( 11 ) NULL DEFAULT \'1\' AFTER `nws_ordre` ,  ADD `nws_ddate_publication` DATETIME NOT NULL AFTER `nws_ordre`, ADD `nws_fdate_publication` DATETIME NOT NULL AFTER `nws_ddate_publication` , ADD `nws_vignette` VARCHAR( 255 ) NOT NULL AFTER `nws_document`;');
		} 
		elseif ($rs->_numOfRows == 13){
			$rs = $db->Execute('ALTER TABLE `cms_news` ADD `nws_ddate_publication` DATETIME NOT NULL AFTER `nws_ordre`, ADD `nws_fdate_publication` DATETIME NOT NULL AFTER `nws_ddate_publication` , ADD `nws_vignette` VARCHAR( 255 ) NOT NULL AFTER `nws_document` ;');
		} 
	} 
}
/*======================================

objet de BDD cms_news :: class cms_news

SQL mySQL:

DROP TABLE IF EXISTS cms_news;
CREATE TABLE cms_news
(
	nws_id			int (11) PRIMARY KEY not null,
	nws_titrecourt			varchar (128),
	nws_titrelong			varchar (256),
	nws_soustitre			varchar (256),
	nws_textelong			text (1024),
	nws_document			int (11),
	nws_vignette			varchar (255),
	nws_diaporama			int (11),
	nws_url			varchar (256),
	nws_ordre			int (11),
	nws_ddate_publication			datetime,
	nws_fdate_publication			datetime,
	nws_cms_site			int (11),
	nws_dtcrea			date,
	nws_dtmod			date,
	nws_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_news
CREATE TABLE cms_news
(
	nws_id			number (11) constraint nws_pk PRIMARY KEY not null,
	nws_titrecourt			varchar2 (128),
	nws_titrelong			varchar2 (256),
	nws_soustitre			varchar2 (256),
	nws_textelong			text (1024),
	nws_document			number (11),
	nws_vignette			varchar2 (255),
	nws_diaporama			number (11),
	nws_url			varchar2 (256),
	nws_ordre			number (11),
	nws_ddate_publication			datetime,
	nws_fdate_publication			datetime,
	nws_cms_site			number (11),
	nws_dtcrea			date,
	nws_dtmod			date,
	nws_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_news" libelle="Actualités" prefix="nws" display="titrecourt" abstract="soustitre">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="titrecourt" libelle="Titre court" type="varchar" length="128" list="true" order="true" /> 
<item name="titrelong" libelle="Titre long" type="varchar" length="256" list="true" order="true" rss="title" /> 
<item name="soustitre" libelle="Sous-titre" type="varchar" length="256" list="true" order="true" option="textarea" /> 
<item name="textelong" libelle="Corps du texte" type="text" length="1024" list="true" order="true" option="textarea" rss="description" />
<item name="document" libelle="Documentation" type="int" length="11" list="true" order="true" fkey="cms_pdf"/> 
<item name="vignette" libelle="Vignette" type="varchar" length="255" list="false" order="true" option="file"/>
<item name="diaporama" libelle="Diaporama" type="int" length="11" list="true" order="true" option="diaporama" fkey="cms_diaporama"/>
<item name="url" libelle="URL" type="varchar" length="256" list="true" order="true" option="url" /> 
<item name="ordre" libelle="Ordre" type="int" length="11" list="true" order="true" />

<item name="ddate_publication"	libelle="Date de début de publication"	type="datetime" list="true" order="true"  format="l j F Y" rss="pubDate" />
<item name="fdate_publication"	libelle="Date de fin de publication"	type="datetime" list="true" order="true"  format="l j F Y" rss="pubendDate" /> 
<item name="cms_site" libelle="mini-site" type="int" length="11" list="true" order="true" fkey="cms_site" />

<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" rss="pubDate" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
<langpack lang="fr">
<norecords>Pas de news à afficher</norecords>
</langpack>
</class> 


==========================================*/

class cms_news
{
var $id;
var $titrecourt;
var $titrelong;
var $soustitre;
var $textelong;
var $document;
var $vignette;
var $diaporama;
var $url;
var $ordre;
var $ddate_publication;
var $fdate_publication;
var $cms_site;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_news\" libelle=\"Actualités\" prefix=\"nws\" display=\"titrecourt\" abstract=\"soustitre\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"titrecourt\" libelle=\"Titre court\" type=\"varchar\" length=\"128\" list=\"true\" order=\"true\" /> 
<item name=\"titrelong\" libelle=\"Titre long\" type=\"varchar\" length=\"256\" list=\"true\" order=\"true\" rss=\"title\" /> 
<item name=\"soustitre\" libelle=\"Sous-titre\" type=\"varchar\" length=\"256\" list=\"true\" order=\"true\" option=\"textarea\" /> 
<item name=\"textelong\" libelle=\"Corps du texte\" type=\"text\" length=\"1024\" list=\"true\" order=\"true\" option=\"textarea\" rss=\"description\" />
<item name=\"document\" libelle=\"Documentation\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_pdf\"/> 
<item name=\"vignette\" libelle=\"Vignette\" type=\"varchar\" length=\"255\" list=\"false\" order=\"true\" option=\"file\"/>
<item name=\"diaporama\" libelle=\"Diaporama\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" option=\"diaporama\" fkey=\"cms_diaporama\"/>
<item name=\"url\" libelle=\"URL\" type=\"varchar\" length=\"256\" list=\"true\" order=\"true\" option=\"url\" /> 
<item name=\"ordre\" libelle=\"Ordre\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" />

<item name=\"ddate_publication\"	libelle=\"Date de début de publication\"	type=\"datetime\" list=\"true\" order=\"true\"  format=\"l j F Y\" rss=\"pubDate\" />
<item name=\"fdate_publication\"	libelle=\"Date de fin de publication\"	type=\"datetime\" list=\"true\" order=\"true\"  format=\"l j F Y\" rss=\"pubendDate\" /> 
<item name=\"cms_site\" libelle=\"mini-site\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_site\" />

<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" rss=\"pubDate\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
<langpack lang=\"fr\">
<norecords>Pas de news à afficher</norecords>
</langpack>
</class> ";

var $sMySql = "CREATE TABLE cms_news
(
	nws_id			int (11) PRIMARY KEY not null,
	nws_titrecourt			varchar (128),
	nws_titrelong			varchar (256),
	nws_soustitre			varchar (256),
	nws_textelong			text (1024),
	nws_document			int (11),
	nws_vignette			varchar (255),
	nws_diaporama			int (11),
	nws_url			varchar (256),
	nws_ordre			int (11),
	nws_ddate_publication			datetime,
	nws_fdate_publication			datetime,
	nws_cms_site			int (11),
	nws_dtcrea			date,
	nws_dtmod			date,
	nws_statut			int (11) not null
)

";

// constructeur
function cms_news($id=null)
{
	if (istable("cms_news") == false){
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
		$this->titrecourt = "";
		$this->titrelong = "";
		$this->soustitre = "";
		$this->textelong = "";
		$this->document = -1;
		$this->vignette = "";
		$this->diaporama = -1;
		$this->url = "";
		$this->ordre = -1;
		$this->ddate_publication = date('Y-m-d H:i:s');
		$this->fdate_publication = date('Y-m-d H:i:s');
		$this->cms_site = -1;
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Nws_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Nws_titrecourt", "text", "get_titrecourt", "set_titrecourt");
	$laListeChamps[]=new dbChamp("Nws_titrelong", "text", "get_titrelong", "set_titrelong");
	$laListeChamps[]=new dbChamp("Nws_soustitre", "text", "get_soustitre", "set_soustitre");
	$laListeChamps[]=new dbChamp("Nws_textelong", "text", "get_textelong", "set_textelong");
	$laListeChamps[]=new dbChamp("Nws_document", "entier", "get_document", "set_document");
	$laListeChamps[]=new dbChamp("Nws_vignette", "text", "get_vignette", "set_vignette");
	$laListeChamps[]=new dbChamp("Nws_diaporama", "entier", "get_diaporama", "set_diaporama");
	$laListeChamps[]=new dbChamp("Nws_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Nws_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Nws_ddate_publication", "date_formatee_timestamp", "get_ddate_publication", "set_ddate_publication");
	$laListeChamps[]=new dbChamp("Nws_fdate_publication", "date_formatee_timestamp", "get_fdate_publication", "set_fdate_publication");
	$laListeChamps[]=new dbChamp("Nws_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Nws_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Nws_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Nws_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_titrecourt() { return($this->titrecourt); }
function get_titrelong() { return($this->titrelong); }
function get_soustitre() { return($this->soustitre); }
function get_textelong() { return($this->textelong); }
function get_document() { return($this->document); }
function get_vignette() { return($this->vignette); }
function get_diaporama() { return($this->diaporama); }
function get_url() { return($this->url); }
function get_ordre() { return($this->ordre); }
function get_ddate_publication() { return($this->ddate_publication); }
function get_fdate_publication() { return($this->fdate_publication); }
function get_cms_site() { return($this->cms_site); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_nws_id) { return($this->id=$c_nws_id); }
function set_titrecourt($c_nws_titrecourt) { return($this->titrecourt=$c_nws_titrecourt); }
function set_titrelong($c_nws_titrelong) { return($this->titrelong=$c_nws_titrelong); }
function set_soustitre($c_nws_soustitre) { return($this->soustitre=$c_nws_soustitre); }
function set_textelong($c_nws_textelong) { return($this->textelong=$c_nws_textelong); }
function set_document($c_nws_document) { return($this->document=$c_nws_document); }
function set_vignette($c_nws_vignette) { return($this->vignette=$c_nws_vignette); }
function set_diaporama($c_nws_diaporama) { return($this->diaporama=$c_nws_diaporama); }
function set_url($c_nws_url) { return($this->url=$c_nws_url); }
function set_ordre($c_nws_ordre) { return($this->ordre=$c_nws_ordre); }
function set_ddate_publication($c_nws_ddate_publication) { return($this->ddate_publication=$c_nws_ddate_publication); }
function set_fdate_publication($c_nws_fdate_publication) { return($this->fdate_publication=$c_nws_fdate_publication); }
function set_cms_site($c_nws_cms_site) { return($this->cms_site=$c_nws_cms_site); }
function set_dtcrea($c_nws_dtcrea) { return($this->dtcrea=$c_nws_dtcrea); }
function set_dtmod($c_nws_dtmod) { return($this->dtmod=$c_nws_dtmod); }
function set_statut($c_nws_statut) { return($this->statut=$c_nws_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("nws_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("nws_statut"); }
//
function getTable() { return("cms_news"); }
function getClasse() { return("cms_news"); }
function getDisplay() { return("titrecourt"); }
function getAbstract() { return("soustitre"); }


} //class

 
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news/list_cms_news.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news/maj_cms_news.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news/show_cms_news.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news/rss_cms_news.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news/xml_cms_news.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news/export_cms_news.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_news/import_cms_news.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>