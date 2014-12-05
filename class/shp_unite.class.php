<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_unite :: class shp_unite

SQL mySQL:

DROP TABLE IF EXISTS shp_unite;
CREATE TABLE shp_unite
(
	shp_uni_id			int (3) PRIMARY KEY not null,
	shp_uni_libelle			varchar (128) not null,
	shp_uni_code			varchar (6) not null,
	shp_uni_statut			int (2) not null,
	shp_uni_cdate			datetime not null,
	shp_uni_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_unite
CREATE TABLE shp_unite
(
	shp_uni_id			number (3) constraint shp_uni_pk PRIMARY KEY not null,
	shp_uni_libelle			varchar2 (128) not null,
	shp_uni_code			varchar2 (6) not null,
	shp_uni_statut			number (2) not null,
	shp_uni_cdate			datetime not null,
	shp_uni_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_unite" libelle="Unité de mesure" prefix="shp_uni" display="libelle" abstract="code">
<item name="id" type="int" length="3" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="libelle" libelle="Libellé" type="varchar" length="128" notnull="true" default="" list="true" nohtml="true" />
<item name="code" libelle="Code" type="varchar" length="6" notnull="true" default="" list="true" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_unite
{
var $id;
var $libelle;
var $code;
var $statut;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_unite\" libelle=\"Unité de mesure\" prefix=\"shp_uni\" display=\"libelle\" abstract=\"code\">
<item name=\"id\" type=\"int\" length=\"3\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"libelle\" libelle=\"Libellé\" type=\"varchar\" length=\"128\" notnull=\"true\" default=\"\" list=\"true\" nohtml=\"true\" />
<item name=\"code\" libelle=\"Code\" type=\"varchar\" length=\"6\" notnull=\"true\" default=\"\" list=\"true\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_unite
(
	shp_uni_id			int (3) PRIMARY KEY not null,
	shp_uni_libelle			varchar (128) not null,
	shp_uni_code			varchar (6) not null,
	shp_uni_statut			int (2) not null,
	shp_uni_cdate			datetime not null,
	shp_uni_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function shp_unite($id=null)
{
	if (istable("shp_unite") == false){
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
		$this->libelle = "";
		$this->code = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_uni_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_uni_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Shp_uni_code", "text", "get_code", "set_code");
	$laListeChamps[]=new dbChamp("Shp_uni_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_uni_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_uni_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_code() { return($this->code); }
function get_statut() { return($this->statut); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_uni_id) { return($this->id=$c_shp_uni_id); }
function set_libelle($c_shp_uni_libelle) { return($this->libelle=$c_shp_uni_libelle); }
function set_code($c_shp_uni_code) { return($this->code=$c_shp_uni_code); }
function set_statut($c_shp_uni_statut) { return($this->statut=$c_shp_uni_statut); }
function set_cdate($c_shp_uni_cdate) { return($this->cdate=$c_shp_uni_cdate); }
function set_mdate($c_shp_uni_mdate) { return($this->mdate=$c_shp_uni_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_uni_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_uni_statut"); }
//
function getTable() { return("shp_unite"); }
function getClasse() { return("shp_unite"); }
function getPrefix() { return("shp_uni"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("code"); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite/list_shp_unite.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite/maj_shp_unite.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite/show_shp_unite.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite/rss_shp_unite.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite/xml_shp_unite.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite/xmlxls_shp_unite.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite/export_shp_unite.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_unite/import_shp_unite.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>