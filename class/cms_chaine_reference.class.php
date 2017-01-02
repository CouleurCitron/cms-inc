<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_chaine_reference :: class cms_chaine_reference

SQL mySQL:

DROP TABLE IF EXISTS cms_chaine_reference;
CREATE TABLE cms_chaine_reference
(
	cms_crf_id			int (11) PRIMARY KEY not null,
	cms_crf_chaine			text not null,
	cms_crf_md5			varchar (60) UNIQUE KEY not null,
	cms_crf_cdate			datetime not null,
	cms_crf_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


SQL Oracle:

DROP TABLE cms_chaine_reference
CREATE TABLE cms_chaine_reference
(
	cms_crf_id			number (11) constraint cms_crf_pk PRIMARY KEY not null,
	cms_crf_chaine			text not null,
	cms_crf_md5			varchar2 (60) UNIQUE KEY not null,
	cms_crf_cdate			datetime not null,
	cms_crf_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_chaine_reference" libelle="Chaines références" prefix="cms_crf" display="chaine" abstract="">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="chaine" libelle="Chaine texte" type="text" notnull="true" default="" list="true" />
<item name="md5" libelle="Hash MD5" type="varchar" length="60" notnull="true" default="" list="true" noedit="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>



==========================================*/

class cms_chaine_reference
{
var $id;
var $chaine;
var $md5;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_chaine_reference\" libelle=\"Chaines références\" prefix=\"cms_crf\" display=\"chaine\" abstract=\"\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"chaine\" libelle=\"Chaine texte\" type=\"text\" notnull=\"true\" default=\"\" list=\"true\" />
<item name=\"md5\" libelle=\"Hash MD5\" type=\"varchar\" length=\"60\" notnull=\"true\" default=\"\" list=\"true\" noedit=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>
";

var $sMySql = "CREATE TABLE cms_chaine_reference
(
	cms_crf_id			int (11) PRIMARY KEY not null,
	cms_crf_chaine			text not null,
	cms_crf_md5			varchar (60) UNIQUE KEY not null,
	cms_crf_cdate			datetime not null,
	cms_crf_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function cms_chaine_reference($id=null)
{
	if (istable("cms_chaine_reference") == false){
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
		$this->chaine = "";
		$this->md5 = "";
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_crf_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_crf_chaine", "text", "get_chaine", "set_chaine");
	$laListeChamps[]=new dbChamp("Cms_crf_md5", "text", "get_md5", "set_md5");
	$laListeChamps[]=new dbChamp("Cms_crf_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Cms_crf_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_chaine() { return($this->chaine); }
function get_md5() { return($this->md5); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_cms_crf_id) { return($this->id=$c_cms_crf_id); }
function set_chaine($c_cms_crf_chaine) { return($this->chaine=$c_cms_crf_chaine); }
function set_md5($c_cms_crf_md5) { return($this->md5=$c_cms_crf_md5); }
function set_cdate($c_cms_crf_cdate) { return($this->cdate=$c_cms_crf_cdate); }
function set_mdate($c_cms_crf_mdate) { return($this->mdate=$c_cms_crf_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_crf_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_chaine_reference"); }
function getClasse() { return("cms_chaine_reference"); }
function getDisplay() { return("chaine"); }
function getAbstract() { return(""); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference/list_cms_chaine_reference.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference/maj_cms_chaine_reference.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference/show_cms_chaine_reference.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference/rss_cms_chaine_reference.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference/xml_cms_chaine_reference.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference/xmlxls_cms_chaine_reference.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference/export_cms_chaine_reference.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_reference/import_cms_chaine_reference.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>