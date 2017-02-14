<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_asso_produitgamme :: class shp_asso_produitgamme

SQL mySQL:

DROP TABLE IF EXISTS shp_asso_produitgamme;
CREATE TABLE shp_asso_produitgamme
(
	shp_id			int (11) PRIMARY KEY not null,
	shp_produit			int (11),
	shp_gamme			int (11),
	shp_cdate			datetime not null,
	shp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_asso_produitgamme
CREATE TABLE shp_asso_produitgamme
(
	shp_id			number (11) constraint shp_pk PRIMARY KEY not null,
	shp_produit			number (11),
	shp_gamme			number (11),
	shp_cdate			datetime not null,
	shp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_asso_produitgamme" libelle="Gammes associées" prefix="shp" display="produit" abstract="gamme" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false"/>
<item name="produit" libelle="Produit" type="int" length="11" default="-1" order="true" fkey="shp_produit" list="true"/>
<item name="gamme" libelle="Gamme" type="int" length="11" default="-1" order="true" fkey="shp_gamme" list="true"/> 
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class> 


==========================================*/

class shp_asso_produitgamme
{
var $id;
var $produit;
var $gamme;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_asso_produitgamme\" libelle=\"Gammes associées\" prefix=\"shp\" display=\"produit\" abstract=\"gamme\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\"/>
<item name=\"produit\" libelle=\"Produit\" type=\"int\" length=\"11\" default=\"-1\" order=\"true\" fkey=\"shp_produit\" list=\"true\"/>
<item name=\"gamme\" libelle=\"Gamme\" type=\"int\" length=\"11\" default=\"-1\" order=\"true\" fkey=\"shp_gamme\" list=\"true\"/> 
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class> ";

var $sMySql = "CREATE TABLE shp_asso_produitgamme
(
	shp_id			int (11) PRIMARY KEY not null,
	shp_produit			int (11),
	shp_gamme			int (11),
	shp_cdate			datetime not null,
	shp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function __construct($id=null)
{
	if (istable("shp_asso_produitgamme") == false){
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
		$this->produit = -1;
		$this->gamme = -1;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_produit", "entier", "get_produit", "set_produit");
	$laListeChamps[]=new dbChamp("Shp_gamme", "entier", "get_gamme", "set_gamme");
	$laListeChamps[]=new dbChamp("Shp_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_produit() { return($this->produit); }
function get_gamme() { return($this->gamme); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_id) { return($this->id=$c_shp_id); }
function set_produit($c_shp_produit) { return($this->produit=$c_shp_produit); }
function set_gamme($c_shp_gamme) { return($this->gamme=$c_shp_gamme); }
function set_cdate($c_shp_cdate) { return($this->cdate=$c_shp_cdate); }
function set_mdate($c_shp_mdate) { return($this->mdate=$c_shp_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("shp_asso_produitgamme"); }
function getClasse() { return("shp_asso_produitgamme"); }
function getPrefix() { return("shp"); }
function getDisplay() { return("produit"); }
function getAbstract() { return("gamme"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme/list_shp_asso_produitgamme.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme/maj_shp_asso_produitgamme.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme/show_shp_asso_produitgamme.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme/rss_shp_asso_produitgamme.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme/xml_shp_asso_produitgamme.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme/xmlxls_shp_asso_produitgamme.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme/export_shp_asso_produitgamme.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_asso_produitgamme/import_shp_asso_produitgamme.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>