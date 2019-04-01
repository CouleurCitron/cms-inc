<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assoportdepartement :: class cms_assoportdepartement

SQL mySQL:

DROP TABLE IF EXISTS cms_assoportdepartement;
CREATE TABLE cms_assoportdepartement
(
	xpd_id			int (11) PRIMARY KEY not null,
	xpd_livport			int,
	xpd_livdepartement			int
)

SQL Oracle:

DROP TABLE cms_assoportdepartement
CREATE TABLE cms_assoportdepartement
(
	xpd_id			number (11) constraint xpd_pk PRIMARY KEY not null,
	xpd_livport			number,
	xpd_livdepartement			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assoportdepartement" is_asso="true" libelle="Associations produit" prefix="xpd" display="livport" abstract="livdepartement">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="livport" libelle="Frais de port" type="int" default="0" order="true" list="true" fkey="cms_livport" />
<item name="livdepartement" libelle="Département associé" type="int" default="0" order="true" list="true" fkey="cms_livdepartement" />
</class>


==========================================*/

class cms_assoportdepartement
{
var $id;
var $livport;
var $livdepartement;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assoportdepartement\" is_asso=\"true\" libelle=\"Associations produit\" prefix=\"xpd\" display=\"livport\" abstract=\"livdepartement\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"livport\" libelle=\"Frais de port\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_livport\" />
<item name=\"livdepartement\" libelle=\"Département associé\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_livdepartement\" />
</class>";

var $sMySql = "CREATE TABLE cms_assoportdepartement
(
	xpd_id			int (11) PRIMARY KEY not null,
	xpd_livport			int,
	xpd_livdepartement			int
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_assoportdepartement") == false){
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
		$this->livport = -1;
		$this->livdepartement = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xpd_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xpd_livport", "entier", "get_livport", "set_livport");
	$laListeChamps[]=new dbChamp("Xpd_livdepartement", "entier", "get_livdepartement", "set_livdepartement");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_livport() { return($this->livport); }
function get_livdepartement() { return($this->livdepartement); }


// setters
function set_id($c_xpd_id) { return($this->id=$c_xpd_id); }
function set_livport($c_xpd_livport) { return($this->livport=$c_xpd_livport); }
function set_livdepartement($c_xpd_livdepartement) { return($this->livdepartement=$c_xpd_livdepartement); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xpd_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assoportdepartement"); }
function getClasse() { return("cms_assoportdepartement"); }
function getDisplay() { return("livport"); }
function getAbstract() { return("livdepartement"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement/list_cms_assoportdepartement.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement/maj_cms_assoportdepartement.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement/show_cms_assoportdepartement.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement/rss_cms_assoportdepartement.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement/xml_cms_assoportdepartement.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement/export_cms_assoportdepartement.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assoportdepartement/import_cms_assoportdepartement.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>