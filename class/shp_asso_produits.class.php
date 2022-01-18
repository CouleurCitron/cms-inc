<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_asso_produits :: class shp_asso_produits

SQL mySQL:

DROP TABLE IF EXISTS shp_asso_produits;
CREATE TABLE shp_asso_produits
(
	shp_xpp_id			int (12) PRIMARY KEY not null,
	shp_xpp_id_produit1			int (11) not null,
	shp_xpp_id_produit2			int (11) not null,
	shp_xpp_cdate			datetime not null,
	shp_xpp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_asso_produits
CREATE TABLE shp_asso_produits
(
	shp_xpp_id			number (12) constraint shp_xpp_pk PRIMARY KEY not null,
	shp_xpp_id_produit1			number (11) not null,
	shp_xpp_id_produit2			number (11) not null,
	shp_xpp_cdate			datetime not null,
	shp_xpp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_asso_produits" libelle="Association entre produits" is_asso="true" prefix="shp_xpp" display="" abstract="">
<item name="id" type="int" length="12" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_produit1" libelle="Produit 1" type="int" length="11" fkey="shp_produit" notnull="true" default="0" list="true" order="true" />
<item name="id_produit2" libelle="Produit 2" type="int" length="11" fkey="shp_produit" notnull="true" default="0" list="true" order="true" /> 
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_asso_produits
{
var $id;
var $id_produit1;
var $id_produit2;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_asso_produits\" libelle=\"Association entre produits\" is_asso=\"true\" prefix=\"shp_xpp\" display=\"\" abstract=\"\">
<item name=\"id\" type=\"int\" length=\"12\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_produit1\" libelle=\"Produit 1\" type=\"int\" length=\"11\" fkey=\"shp_produit\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"id_produit2\" libelle=\"Produit 2\" type=\"int\" length=\"11\" fkey=\"shp_produit\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" /> 
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_asso_produits
(
	shp_xpp_id			int (12) PRIMARY KEY not null,
	shp_xpp_id_produit1			int (11) not null,
	shp_xpp_id_produit2			int (11) not null,
	shp_xpp_cdate			datetime not null,
	shp_xpp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function __construct($id=null)
{
	if (istable("shp_asso_produits") == false){
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
		$this->id_produit1 = -1;
		$this->id_produit2 = -1;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_xpp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_xpp_id_produit1", "entier", "get_id_produit1", "set_id_produit1");
	$laListeChamps[]=new dbChamp("Shp_xpp_id_produit2", "entier", "get_id_produit2", "set_id_produit2");
	$laListeChamps[]=new dbChamp("Shp_xpp_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_xpp_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_produit1() { return($this->id_produit1); }
function get_id_produit2() { return($this->id_produit2); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_xpp_id) { return($this->id=$c_shp_xpp_id); }
function set_id_produit1($c_shp_xpp_id_produit1) { return($this->id_produit1=$c_shp_xpp_id_produit1); }
function set_id_produit2($c_shp_xpp_id_produit2) { return($this->id_produit2=$c_shp_xpp_id_produit2); }
function set_cdate($c_shp_xpp_cdate) { return($this->cdate=$c_shp_xpp_cdate); }
function set_mdate($c_shp_xpp_mdate) { return($this->mdate=$c_shp_xpp_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_xpp_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("shp_asso_produits"); }
function getClasse() { return("shp_asso_produits"); }
function getPrefix() { return("shp_xpp"); }
function getDisplay() { return(""); }
function getAbstract() { return(""); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits/list_shp_asso_produits.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits/maj_shp_asso_produits.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits/show_shp_asso_produits.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits/rss_shp_asso_produits.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits/xml_shp_asso_produits.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits/xmlxls_shp_asso_produits.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits/export_shp_asso_produits.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produits/import_shp_asso_produits.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>