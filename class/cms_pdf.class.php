<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_pdf :: class cms_pdf

SQL mySQL:

DROP TABLE IF EXISTS cms_pdf;
CREATE TABLE cms_pdf
(
	pdf_id			int (11) PRIMARY KEY not null,
	pdf_titre			varchar (64),
	pdf_src			varchar (255),
	pdf_vignette			varchar (255),
	pdf_metadata			varchar (512),
	pdf_cms_site			int (11) not null,
	pdf_dtcrea			date,
	pdf_dtmod			date,
	pdf_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_pdf
CREATE TABLE cms_pdf
(
	pdf_id			number (11) constraint pdf_pk PRIMARY KEY not null,
	pdf_titre			varchar2 (64),
	pdf_src			varchar2 (255),
	pdf_vignette			varchar2 (255),
	pdf_metadata			varchar2 (512),
	pdf_cms_site			number (11) not null,
	pdf_dtcrea			date,
	pdf_dtmod			date,
	pdf_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_pdf" libelle="PDF du gestionnaire" prefix="pdf" display="src" abstract="titre">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" asso="cms_assobinderpdf" />
<item name="titre" libelle="Titre" type="varchar" length="64" list="true" order="true" />
<item name="src" libelle="Fichier source" type="varchar" length="255" list="true" order="true" option="file">
<option type="pdf" />
</item>
<item name="vignette" libelle="Vignette" type="varchar" length="255" list="false" order="false" option="file">
<option type="image" maxwidth="200" maxheight="200" />
</item> 
<item name="metadata" libelle="meta-données" type="varchar" length="512" list="false" order="false" /> 
<item name="cms_site" libelle="Mini site" type="int" length="11" notnull="true" default="-1" list="true" order="true" fkey="cms_site" />

<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 

<langpack lang="fr">
<norecords>Pas de pdf à afficher</norecords>
</langpack>
</class> 


==========================================*/

class cms_pdf
{
var $id;
var $titre;
var $src;
var $vignette;
var $metadata;
var $cms_site;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_pdf\" libelle=\"PDF du gestionnaire\" prefix=\"pdf\" display=\"src\" abstract=\"titre\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" asso=\"cms_assobinderpdf\" />
<item name=\"titre\" libelle=\"Titre\" type=\"varchar\" length=\"64\" list=\"true\" order=\"true\" />
<item name=\"src\" libelle=\"Fichier source\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" option=\"file\">
<option type=\"pdf\" />
</item>
<item name=\"vignette\" libelle=\"Vignette\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" option=\"file\">
<option type=\"image\" maxwidth=\"200\" maxheight=\"200\" />
</item> 
<item name=\"metadata\" libelle=\"meta-données\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" /> 
<item name=\"cms_site\" libelle=\"Mini site\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_site\" />

<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 

<langpack lang=\"fr\">
<norecords>Pas de pdf à afficher</norecords>
</langpack>
</class> ";

var $sMySql = "CREATE TABLE cms_pdf
(
	pdf_id			int (11) PRIMARY KEY not null,
	pdf_titre			varchar (64),
	pdf_src			varchar (255),
	pdf_vignette			varchar (255),
	pdf_metadata			varchar (512),
	pdf_cms_site			int (11) not null,
	pdf_dtcrea			date,
	pdf_dtmod			date,
	pdf_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_pdf") == false){
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
		$this->titre = "";
		$this->src = "";
		$this->vignette = "";
		$this->metadata = "";
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
	$laListeChamps[]=new dbChamp("Pdf_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Pdf_titre", "text", "get_titre", "set_titre");
	$laListeChamps[]=new dbChamp("Pdf_src", "text", "get_src", "set_src");
	$laListeChamps[]=new dbChamp("Pdf_vignette", "text", "get_vignette", "set_vignette");
	$laListeChamps[]=new dbChamp("Pdf_metadata", "text", "get_metadata", "set_metadata");
	$laListeChamps[]=new dbChamp("Pdf_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Pdf_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Pdf_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Pdf_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_titre() { return($this->titre); }
function get_src() { return($this->src); }
function get_vignette() { return($this->vignette); }
function get_metadata() { return($this->metadata); }
function get_cms_site() { return($this->cms_site); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_pdf_id) { return($this->id=$c_pdf_id); }
function set_titre($c_pdf_titre) { return($this->titre=$c_pdf_titre); }
function set_src($c_pdf_src) { return($this->src=$c_pdf_src); }
function set_vignette($c_pdf_vignette) { return($this->vignette=$c_pdf_vignette); }
function set_metadata($c_pdf_metadata) { return($this->metadata=$c_pdf_metadata); }
function set_cms_site($c_pdf_cms_site) { return($this->cms_site=$c_pdf_cms_site); }
function set_dtcrea($c_pdf_dtcrea) { return($this->dtcrea=$c_pdf_dtcrea); }
function set_dtmod($c_pdf_dtmod) { return($this->dtmod=$c_pdf_dtmod); }
function set_statut($c_pdf_statut) { return($this->statut=$c_pdf_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("pdf_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("pdf_statut"); }
//
function getTable() { return("cms_pdf"); }
function getClasse() { return("cms_pdf"); }
function getDisplay() { return("src"); }
function getAbstract() { return("titre"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf/list_cms_pdf.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf/maj_cms_pdf.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf/show_cms_pdf.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf/rss_cms_pdf.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf/xml_cms_pdf.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf/export_cms_pdf.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_pdf/import_cms_pdf.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>