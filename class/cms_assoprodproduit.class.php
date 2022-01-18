<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assoprodproduit :: class cms_assoprodproduit

SQL mySQL:

DROP TABLE IF EXISTS cms_assoprodproduit;
CREATE TABLE cms_assoprodproduit
(
	xpp_id			int (11) PRIMARY KEY not null,
	xpp_cms_produit			int,
	xpp_cms_produit2			int
)

SQL Oracle:

DROP TABLE cms_assoprodproduit
CREATE TABLE cms_assoprodproduit
(
	xpp_id			number (11) constraint xpp_pk PRIMARY KEY not null,
	xpp_cms_produit			number,
	xpp_cms_produit2			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assoprodproduit" is_asso="true" libelle="Associations produit" prefix="xpp" display="cms_produit" abstract="cms_produit2">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_produit" libelle="Produit" type="int" default="0" order="true" list="true" fkey="cms_produit" />
<item name="cms_produit2" libelle="Produit associé" type="int" default="0" order="true" list="true" fkey="cms_produit" />
</class>


==========================================*/

class cms_assoprodproduit
{
var $id;
var $cms_produit;
var $cms_produit2;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assoprodproduit\" is_asso=\"true\" libelle=\"Associations produit\" prefix=\"xpp\" display=\"cms_produit\" abstract=\"cms_produit2\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_produit\" libelle=\"Produit\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_produit\" />
<item name=\"cms_produit2\" libelle=\"Produit associé\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_produit\" />
</class>";

var $sMySql = "CREATE TABLE cms_assoprodproduit
(
	xpp_id			int (11) PRIMARY KEY not null,
	xpp_cms_produit			int,
	xpp_cms_produit2			int
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_assoprodproduit") == false){
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
		$this->cms_produit = -1;
		$this->cms_produit2 = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xpp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xpp_cms_produit", "entier", "get_cms_produit", "set_cms_produit");
	$laListeChamps[]=new dbChamp("Xpp_cms_produit2", "entier", "get_cms_produit2", "set_cms_produit2");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_produit() { return($this->cms_produit); }
function get_cms_produit2() { return($this->cms_produit2); }


// setters
function set_id($c_xpp_id) { return($this->id=$c_xpp_id); }
function set_cms_produit($c_xpp_cms_produit) { return($this->cms_produit=$c_xpp_cms_produit); }
function set_cms_produit2($c_xpp_cms_produit2) { return($this->cms_produit2=$c_xpp_cms_produit2); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xpp_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assoprodproduit"); }
function getClasse() { return("cms_assoprodproduit"); }
function getDisplay() { return("cms_produit"); }
function getAbstract() { return("cms_produit2"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit/list_cms_assoprodproduit.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit/maj_cms_assoprodproduit.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit/show_cms_assoprodproduit.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit/rss_cms_assoprodproduit.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit/xml_cms_assoprodproduit.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit/export_cms_assoprodproduit.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoprodproduit/import_cms_assoprodproduit.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>