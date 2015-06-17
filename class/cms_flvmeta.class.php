<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_flvmeta :: class cms_flvmeta

SQL mySQL:

DROP TABLE IF EXISTS cms_flvmeta;
CREATE TABLE cms_flvmeta
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_src			varchar (255),
	cms_filemdate			varchar(32),
	cms_w			int (6),
	cms_h			int (6),
	cms_data			varchar (256)
)

SQL Oracle:

DROP TABLE cms_flvmeta
CREATE TABLE cms_flvmeta
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_src			varchar2 (255),
	cms_filemdate			varchar(32),
	cms_w			number (6),
	cms_h			number (6),
	cms_data			varchar2 (256)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_flvmeta" libelle="Metadonnées des FLV-MOV" prefix="cms" display="src" abstract="filemdate">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="src" libelle="fichier média" type="varchar" length="255" list="true" order="true" option="file" />
<item name="filemdate" libelle="Date de modification" type="varchar" length="32" list="true" order="true" />
<item name="w" libelle="Largeur" type="int" length="6" list="true" order="true" />
<item name="h" libelle="Hauteur" type="int" length="6" list="true" order="true" />
<item name="data" libelle="Autre donnée" type="varchar" length="256" list="false" order="false" />
</class>


==========================================*/

class cms_flvmeta
{
var $id;
var $src;
var $filemdate;
var $w;
var $h;
var $data;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_flvmeta\" libelle=\"Metadonnées des FLV-MOV\" prefix=\"cms\" display=\"src\" abstract=\"filemdate\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"src\" libelle=\"fichier média\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" option=\"file\" />
<item name=\"filemdate\" libelle=\"Date de modification\" type=\"varchar\" length=\"32\" list=\"true\" order=\"true\" />
<item name=\"w\" libelle=\"Largeur\" type=\"int\" length=\"6\" list=\"true\" order=\"true\" />
<item name=\"h\" libelle=\"Hauteur\" type=\"int\" length=\"6\" list=\"true\" order=\"true\" />
<item name=\"data\" libelle=\"Autre donnée\" type=\"varchar\" length=\"256\" list=\"false\" order=\"false\" />
</class>";

var $sMySql = "CREATE TABLE cms_flvmeta
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_src			varchar (255),
	cms_filemdate			varchar(32),
	cms_w			int (6),
	cms_h			int (6),
	cms_data			varchar (256)
)

";

// constructeur
function cms_flvmeta($id=null)
{
	if (istable("cms_flvmeta") == false){
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
		$this->src = "";
		$this->filemdate = date('Y-m-d H:i:s');
		$this->w = -1;
		$this->h = -1;
		$this->data = "";
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_src", "text", "get_src", "set_src");
	$laListeChamps[]=new dbChamp("Cms_filemdate", "text", "get_filemdate", "set_filemdate");
	$laListeChamps[]=new dbChamp("Cms_w", "entier", "get_w", "set_w");
	$laListeChamps[]=new dbChamp("Cms_h", "entier", "get_h", "set_h");
	$laListeChamps[]=new dbChamp("Cms_data", "text", "get_data", "set_data");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_src() { return($this->src); }
function get_filemdate() { return($this->filemdate); }
function get_w() { return($this->w); }
function get_h() { return($this->h); }
function get_data() { return($this->data); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_src($c_cms_src) { return($this->src=$c_cms_src); }
function set_filemdate($c_cms_filemdate) { return($this->filemdate=$c_cms_filemdate); }
function set_w($c_cms_w) { return($this->w=$c_cms_w); }
function set_h($c_cms_h) { return($this->h=$c_cms_h); }
function set_data($c_cms_data) { return($this->data=$c_cms_data); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_flvmeta"); }
function getClasse() { return("cms_flvmeta"); }
function getDisplay() { return("src"); }
function getAbstract() { return("filemdate"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta/list_cms_flvmeta.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta/maj_cms_flvmeta.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta/show_cms_flvmeta.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta/rss_cms_flvmeta.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta/xml_cms_flvmeta.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta/xmlxls_cms_flvmeta.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta/export_cms_flvmeta.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_flvmeta/import_cms_flvmeta.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>