<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_glossary :: class cms_glossary

SQL mySQL:

DROP TABLE IF EXISTS cms_glossary;
CREATE TABLE cms_glossary
(
	glo_id			int (11) PRIMARY KEY not null,
	glo_titrecourt			varchar (128),
	glo_soustitre			varchar (256),
	glo_textelong			text (1024),
	glo_document			int (11),
	glo_diaporama			int (11),
	glo_url			varchar (256),
	glo_cms_site			int (11),
	glo_dtcrea			date,
	glo_dtmod			date,
	glo_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_glossary
CREATE TABLE cms_glossary
(
	glo_id			number (11) constraint glo_pk PRIMARY KEY not null,
	glo_titrecourt			varchar2 (128),
	glo_soustitre			varchar2 (256),
	glo_textelong			text (1024),
	glo_document			number (11),
	glo_diaporama			number (11),
	glo_url			varchar2 (256),
	glo_cms_site			number (11),
	glo_dtcrea			date,
	glo_dtmod			date,
	glo_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_glossary" libelle="Glossaire" prefix="glo" display="titrecourt" abstract="soustitre">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="titrecourt" libelle="Titre court" type="varchar" length="128" list="true" order="true" />  
<item name="soustitre" libelle="Sous-titre" type="varchar" length="256" list="true" order="true"  /> 
<item name="textelong" libelle="Corps du texte" type="text" length="1024" list="true" order="true" option="textarea" rss="description" />
<item name="document" libelle="Documentation" type="int" length="11" list="false" order="false" option="binder" fkey="cms_binder"/> 
<item name="diaporama" libelle="Diaporama" type="int" length="11" list="false" order="false" option="diaporama" fkey="cms_diaporama"/>
<item name="url" libelle="URL" type="varchar" length="256" list="false" order="false" option="url" /> 
<item name="cms_site" libelle="mini-site" type="int" length="11" list="false" order="false" fkey="cms_site" /> 
<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="false" order="false" rss="pubDate" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
<langpack lang="fr">
<norecords>Pas de définition à afficher</norecords>
</langpack>
<langpack lang="en">
<norecords>No glossary</norecords>
</langpack>
</class> 


==========================================*/

class cms_glossary
{
var $id;
var $titrecourt;
var $soustitre;
var $textelong;
var $document;
var $diaporama;
var $url;
var $cms_site;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_glossary\" libelle=\"Glossaire\" prefix=\"glo\" display=\"titrecourt\" abstract=\"soustitre\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"titrecourt\" libelle=\"Titre court\" type=\"varchar\" length=\"128\" list=\"true\" order=\"true\" />  
<item name=\"soustitre\" libelle=\"Sous-titre\" type=\"varchar\" length=\"256\" list=\"true\" order=\"true\"  /> 
<item name=\"textelong\" libelle=\"Corps du texte\" type=\"text\" length=\"1024\" list=\"true\" order=\"true\" option=\"textarea\" rss=\"description\" />
<item name=\"document\" libelle=\"Documentation\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" option=\"binder\" fkey=\"cms_binder\"/> 
<item name=\"diaporama\" libelle=\"Diaporama\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" option=\"diaporama\" fkey=\"cms_diaporama\"/>
<item name=\"url\" libelle=\"URL\" type=\"varchar\" length=\"256\" list=\"false\" order=\"false\" option=\"url\" /> 
<item name=\"cms_site\" libelle=\"mini-site\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"cms_site\" /> 
<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"false\" order=\"false\" rss=\"pubDate\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
<langpack lang=\"fr\">
<norecords>Pas de définition à afficher</norecords>
</langpack>
<langpack lang=\"en\">
<norecords>No glossary</norecords>
</langpack>
</class> ";

var $sMySql = "CREATE TABLE cms_glossary
(
	glo_id			int (11) PRIMARY KEY not null,
	glo_titrecourt			varchar (128),
	glo_soustitre			varchar (256),
	glo_textelong			text (1024),
	glo_document			int (11),
	glo_diaporama			int (11),
	glo_url			varchar (256),
	glo_cms_site			int (11),
	glo_dtcrea			date,
	glo_dtmod			date,
	glo_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_glossary") == false){
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
		$this->soustitre = "";
		$this->textelong = "";
		$this->document = -1;
		$this->diaporama = -1;
		$this->url = "";
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
	$laListeChamps[]=new dbChamp("Glo_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Glo_titrecourt", "text", "get_titrecourt", "set_titrecourt");
	$laListeChamps[]=new dbChamp("Glo_soustitre", "text", "get_soustitre", "set_soustitre");
	$laListeChamps[]=new dbChamp("Glo_textelong", "text", "get_textelong", "set_textelong");
	$laListeChamps[]=new dbChamp("Glo_document", "entier", "get_document", "set_document");
	$laListeChamps[]=new dbChamp("Glo_diaporama", "entier", "get_diaporama", "set_diaporama");
	$laListeChamps[]=new dbChamp("Glo_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Glo_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Glo_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Glo_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Glo_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_titrecourt() { return($this->titrecourt); }
function get_soustitre() { return($this->soustitre); }
function get_textelong() { return($this->textelong); }
function get_document() { return($this->document); }
function get_diaporama() { return($this->diaporama); }
function get_url() { return($this->url); }
function get_cms_site() { return($this->cms_site); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_glo_id) { return($this->id=$c_glo_id); }
function set_titrecourt($c_glo_titrecourt) { return($this->titrecourt=$c_glo_titrecourt); }
function set_soustitre($c_glo_soustitre) { return($this->soustitre=$c_glo_soustitre); }
function set_textelong($c_glo_textelong) { return($this->textelong=$c_glo_textelong); }
function set_document($c_glo_document) { return($this->document=$c_glo_document); }
function set_diaporama($c_glo_diaporama) { return($this->diaporama=$c_glo_diaporama); }
function set_url($c_glo_url) { return($this->url=$c_glo_url); }
function set_cms_site($c_glo_cms_site) { return($this->cms_site=$c_glo_cms_site); }
function set_dtcrea($c_glo_dtcrea) { return($this->dtcrea=$c_glo_dtcrea); }
function set_dtmod($c_glo_dtmod) { return($this->dtmod=$c_glo_dtmod); }
function set_statut($c_glo_statut) { return($this->statut=$c_glo_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("glo_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("glo_statut"); }
//
function getTable() { return("cms_glossary"); }
function getClasse() { return("cms_glossary"); }
function getDisplay() { return("titrecourt"); }
function getAbstract() { return("soustitre"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary/list_cms_glossary.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary/maj_cms_glossary.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary/show_cms_glossary.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary/rss_cms_glossary.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary/xml_cms_glossary.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary/export_cms_glossary.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_glossary/import_cms_glossary.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>