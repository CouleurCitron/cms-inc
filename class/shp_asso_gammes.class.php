<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_asso_gammes :: class shp_asso_gammes

SQL mySQL:

DROP TABLE IF EXISTS shp_asso_gammes;
CREATE TABLE shp_asso_gammes
(
	shp_xgg_id			int (12) PRIMARY KEY not null,
	shp_xgg_id_gamme1			int (11) not null,
	shp_xgg_id_gamme2			int (11) not null,
	shp_xgg_cdate			datetime not null,
	shp_xgg_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_asso_gammes
CREATE TABLE shp_asso_gammes
(
	shp_xgg_id			number (12) constraint shp_xgg_pk PRIMARY KEY not null,
	shp_xgg_id_gamme1			number (11) not null,
	shp_xgg_id_gamme2			number (11) not null,
	shp_xgg_cdate			datetime not null,
	shp_xgg_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_asso_gammes" libelle="Association entre gammes" is_asso="true" prefix="shp_xgg" display="" abstract="">
<item name="id" type="int" length="12" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_gamme1" libelle="Gamme 1" type="int" length="11" fkey="shp_gamme" notnull="true" default="0" list="true" order="true" />
<item name="id_gamme2" libelle="Gamme 2" type="int" length="11" fkey="shp_gamme" notnull="true" default="0" list="true" order="true" /> 
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_asso_gammes
{
var $id;
var $id_gamme1;
var $id_gamme2;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_asso_gammes\" libelle=\"Association entre gammes\" is_asso=\"true\" prefix=\"shp_xgg\" display=\"\" abstract=\"\">
<item name=\"id\" type=\"int\" length=\"12\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_gamme1\" libelle=\"Gamme 1\" type=\"int\" length=\"11\" fkey=\"shp_gamme\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"id_gamme2\" libelle=\"Gamme 2\" type=\"int\" length=\"11\" fkey=\"shp_gamme\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" /> 
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_asso_gammes
(
	shp_xgg_id			int (12) PRIMARY KEY not null,
	shp_xgg_id_gamme1			int (11) not null,
	shp_xgg_id_gamme2			int (11) not null,
	shp_xgg_cdate			datetime not null,
	shp_xgg_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function shp_asso_gammes($id=null)
{
	if (istable("shp_asso_gammes") == false){
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
		$this->id_gamme1 = -1;
		$this->id_gamme2 = -1;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_xgg_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_xgg_id_gamme1", "entier", "get_id_gamme1", "set_id_gamme1");
	$laListeChamps[]=new dbChamp("Shp_xgg_id_gamme2", "entier", "get_id_gamme2", "set_id_gamme2");
	$laListeChamps[]=new dbChamp("Shp_xgg_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_xgg_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_gamme1() { return($this->id_gamme1); }
function get_id_gamme2() { return($this->id_gamme2); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_xgg_id) { return($this->id=$c_shp_xgg_id); }
function set_id_gamme1($c_shp_xgg_id_gamme1) { return($this->id_gamme1=$c_shp_xgg_id_gamme1); }
function set_id_gamme2($c_shp_xgg_id_gamme2) { return($this->id_gamme2=$c_shp_xgg_id_gamme2); }
function set_cdate($c_shp_xgg_cdate) { return($this->cdate=$c_shp_xgg_cdate); }
function set_mdate($c_shp_xgg_mdate) { return($this->mdate=$c_shp_xgg_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_xgg_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("shp_asso_gammes"); }
function getClasse() { return("shp_asso_gammes"); }
function getPrefix() { return("shp_xgg"); }
function getDisplay() { return(""); }
function getAbstract() { return(""); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes/list_shp_asso_gammes.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes/maj_shp_asso_gammes.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes/show_shp_asso_gammes.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes/rss_shp_asso_gammes.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes/xml_shp_asso_gammes.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes/xmlxls_shp_asso_gammes.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes/export_shp_asso_gammes.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_gammes/import_shp_asso_gammes.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>
