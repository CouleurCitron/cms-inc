<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_commande_statut :: class shp_commande_statut

SQL mySQL:

DROP TABLE IF EXISTS shp_commande_statut;
CREATE TABLE shp_commande_statut
(
	shp_stt_id			int (8) PRIMARY KEY not null,
	shp_stt_code			varchar (45) not null,
	shp_stt_libelle			int (11) not null
)

SQL Oracle:

DROP TABLE shp_commande_statut
CREATE TABLE shp_commande_statut
(
	shp_stt_id			number (8) constraint shp_stt_pk PRIMARY KEY not null,
	shp_stt_code			varchar2 (45) not null,
	shp_stt_libelle			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_commande_statut" libelle="Statut de commande" prefix="shp_stt" display="libelle" abstract="code">
<item name="id" type="int" length="8" isprimary="true" notnull="true" default="-1" order="true" list="true" /> 
<item name="code" libelle="Code" type="varchar" length="45" notnull="true" default="" list="true" nohtml="true" />
<item name="libelle" libelle="Libellé" type="int" length="11" notnull="true" default="0" list="true" nohtml="true" oblig="true" translate="reference" />
</class>


==========================================*/

class shp_commande_statut
{
var $id;
var $code;
var $libelle;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_commande_statut\" libelle=\"Statut de commande\" prefix=\"shp_stt\" display=\"libelle\" abstract=\"code\">
<item name=\"id\" type=\"int\" length=\"8\" isprimary=\"true\" notnull=\"true\" default=\"-1\" order=\"true\" list=\"true\" /> 
<item name=\"code\" libelle=\"Code\" type=\"varchar\" length=\"45\" notnull=\"true\" default=\"\" list=\"true\" nohtml=\"true\" />
<item name=\"libelle\" libelle=\"Libellé\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"true\" nohtml=\"true\" oblig=\"true\" translate=\"reference\" />
</class>";

var $sMySql = "CREATE TABLE shp_commande_statut
(
	shp_stt_id			int (8) PRIMARY KEY not null,
	shp_stt_code			varchar (45) not null,
	shp_stt_libelle			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("shp_commande_statut") == false){
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
		$this->code = "";
		$this->libelle = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_stt_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_stt_code", "text", "get_code", "set_code");
	$laListeChamps[]=new dbChamp("Shp_stt_libelle", "entier", "get_libelle", "set_libelle");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_code() { return($this->code); }
function get_libelle() { return($this->libelle); }


// setters
function set_id($c_shp_stt_id) { return($this->id=$c_shp_stt_id); }
function set_code($c_shp_stt_code) { return($this->code=$c_shp_stt_code); }
function set_libelle($c_shp_stt_libelle) { return($this->libelle=$c_shp_stt_libelle); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_stt_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("shp_commande_statut"); }
function getClasse() { return("shp_commande_statut"); }
function getPrefix() { return("shp_stt"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("code"); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut/list_shp_commande_statut.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut/maj_shp_commande_statut.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut/show_shp_commande_statut.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut/rss_shp_commande_statut.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut/xml_shp_commande_statut.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut/xmlxls_shp_commande_statut.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut/export_shp_commande_statut.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_commande_statut/import_shp_commande_statut.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>