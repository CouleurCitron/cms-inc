<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_chaine_traduite :: class cms_chaine_traduite

SQL mySQL:

DROP TABLE IF EXISTS cms_chaine_traduite;
CREATE TABLE cms_chaine_traduite
(
	cms_ctd_id			int (12) PRIMARY KEY not null,
	cms_ctd_id_reference			int (11) not null,
	cms_ctd_id_langue			int (3) not null,
	cms_ctd_chaine			text not null,
	cms_ctd_cdate			datetime not null,
	cms_ctd_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE cms_chaine_traduite
CREATE TABLE cms_chaine_traduite
(
	cms_ctd_id			number (12) constraint cms_ctd_pk PRIMARY KEY not null,
	cms_ctd_id_reference			number (11) not null,
	cms_ctd_id_langue			number (3) not null,
	cms_ctd_chaine			text not null,
	cms_ctd_cdate			datetime not null,
	cms_ctd_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_chaine_traduite" libelle="Chaines traduites" prefix="cms_ctd" display="chaine" abstract="">
<item name="id" type="int" length="12" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_reference" libelle="Chaine de référence" type="int" length="11" fkey="cms_chaine_reference" notnull="true" /> 
<item name="id_langue" libelle="Langue" type="int" length="3" fkey="cms_langue" notnull="true" list="true" order="true" /> 
<item name="chaine" libelle="Chaine traduite" type="text" notnull="true" default="" list="true" order="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>



==========================================*/

class cms_chaine_traduite
{
var $id;
var $id_reference;
var $id_langue;
var $chaine;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_chaine_traduite\" libelle=\"Chaines traduites\" prefix=\"cms_ctd\" display=\"chaine\" abstract=\"\">
<item name=\"id\" type=\"int\" length=\"12\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_reference\" libelle=\"Chaine de référence\" type=\"int\" length=\"11\" fkey=\"cms_chaine_reference\" notnull=\"true\" order=\"true\" /> 
<item name=\"id_langue\" libelle=\"Langue\" type=\"int\" length=\"3\" fkey=\"cms_langue\" notnull=\"true\" list=\"true\" order=\"true\" /> 
<item name=\"chaine\" libelle=\"Chaine traduite\" type=\"text\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>
";

var $sMySql = "CREATE TABLE cms_chaine_traduite
(
	cms_ctd_id			int (12) PRIMARY KEY not null,
	cms_ctd_id_reference			int (11) not null,
	cms_ctd_id_langue			int (3) not null,
	cms_ctd_chaine			text not null,
	cms_ctd_cdate			datetime not null,
	cms_ctd_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function cms_chaine_traduite($id=null)
{
	if (istable("cms_chaine_traduite") == false){
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
		$this->id_reference = -1;
		$this->id_langue = -1;
		$this->chaine = "";
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_ctd_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_ctd_id_reference", "entier", "get_id_reference", "set_id_reference");
	$laListeChamps[]=new dbChamp("Cms_ctd_id_langue", "entier", "get_id_langue", "set_id_langue");
	$laListeChamps[]=new dbChamp("Cms_ctd_chaine", "text", "get_chaine", "set_chaine");
	$laListeChamps[]=new dbChamp("Cms_ctd_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Cms_ctd_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_reference() { return($this->id_reference); }
function get_id_langue() { return($this->id_langue); }
function get_chaine() { return($this->chaine); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_cms_ctd_id) { return($this->id=$c_cms_ctd_id); }
function set_id_reference($c_cms_ctd_id_reference) { return($this->id_reference=$c_cms_ctd_id_reference); }
function set_id_langue($c_cms_ctd_id_langue) { return($this->id_langue=$c_cms_ctd_id_langue); }
function set_chaine($c_cms_ctd_chaine) { return($this->chaine=$c_cms_ctd_chaine); }
function set_cdate($c_cms_ctd_cdate) { return($this->cdate=$c_cms_ctd_cdate); }
function set_mdate($c_cms_ctd_mdate) { return($this->mdate=$c_cms_ctd_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_ctd_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_chaine_traduite"); }
function getClasse() { return("cms_chaine_traduite"); }
function getDisplay() { return("chaine"); }
function getAbstract() { return(""); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite/list_cms_chaine_traduite.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite/maj_cms_chaine_traduite.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite/show_cms_chaine_traduite.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite/rss_cms_chaine_traduite.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite/xml_cms_chaine_traduite.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite/xmlxls_cms_chaine_traduite.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite/export_cms_chaine_traduite.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_chaine_traduite/import_cms_chaine_traduite.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>