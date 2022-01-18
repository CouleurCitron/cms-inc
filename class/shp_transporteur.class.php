<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('shp_transporteur')){
	$rs = $db->Execute('SHOW COLUMNS FROM `shp_transporteur`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			//pre_dump($rs->fields);
			$rs->MoveNext();
		
		}
		if (!in_array('shp_tsp_delai', $names))	
			$rs = $db->Execute("ALTER TABLE `shp_transporteur` ADD `shp_tsp_delai` INT( 11 ) NULL AFTER `shp_tsp_libelle` ;");
	}
}
/*======================================

objet de BDD shp_transporteur :: class shp_transporteur

SQL mySQL:

DROP TABLE IF EXISTS shp_transporteur;
CREATE TABLE shp_transporteur
(
	shp_tsp_id			int (3) PRIMARY KEY not null,
	shp_tsp_libelle			varchar (128) not null,
	shp_tsp_delai			int (11),
	shp_tsp_statut			int (2) not null,
	shp_tsp_ordre			int (11),
	shp_tsp_url_suivi		varchar (255) not null,
	shp_tsp_cdate			datetime not null,
	shp_tsp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_transporteur
CREATE TABLE shp_transporteur
(
	shp_tsp_id			number (3) constraint shp_tsp_pk PRIMARY KEY not null,
	shp_tsp_libelle			varchar2 (128) not null,
	shp_tsp_delai			number (11),
	shp_tsp_statut			number (2) not null,
	shp_tsp_ordre			number (11),
	shp_tsp_url_suivi		varchar2 (255) not null,
	shp_tsp_cdate			datetime not null,
	shp_tsp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_transporteur" libelle="Transporteur" prefix="shp_tsp" display="libelle" abstract="statut">
<item name="id" type="int" length="3" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="libelle" libelle="Identification" type="varchar" length="128" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="delai" libelle="Délai de livraison" type="int" length="11" default="-1" list="true" translate="reference" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="ordre" libelle="Ordre d'apparition" type="int" length="11" />
<item name="url_suivi" libelle="Url du site de suivi" type="varchar" length="255" notnull="true" default="" nohtml="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_transporteur
{
var $id;
var $libelle;
var $delai;
var $statut;
var $ordre;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_transporteur\" libelle=\"Transporteur\" prefix=\"shp_tsp\" display=\"libelle\" abstract=\"statut\">
<item name=\"id\" type=\"int\" length=\"3\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"libelle\" libelle=\"Identification\" type=\"varchar\" length=\"128\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"delai\" libelle=\"Délai de livraison\" type=\"int\" length=\"11\" default=\"-1\" list=\"true\" translate=\"reference\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"ordre\" libelle=\"Ordre d\'apparition\" type=\"int\" length=\"11\" />
<item name=\"url_suivi\" libelle=\"URL du site de suivi\" type=\"varchar\" length=\"255\" notnull=\"true\" default=\"\" nohtml=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_transporteur
(
	shp_tsp_id			int (3) PRIMARY KEY not null,
	shp_tsp_libelle			varchar (128) not null,
	shp_tsp_delai			int (11),
	shp_tsp_statut			int (2) not null,
	shp_tsp_ordre			int (11),
	shp_tsp_url_suivi		varchar (255) not null,
	shp_tsp_cdate			datetime not null,
	shp_tsp_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function __construct($id=null)
{
	if (istable("shp_transporteur") == false){
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
		$this->delai = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->ordre = -1;
		$this->url_suivi = "";
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_tsp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_tsp_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Shp_tsp_delai", "entier", "get_delai", "set_delai");
	$laListeChamps[]=new dbChamp("Shp_tsp_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_tsp_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Shp_tsp_url_suivi", "text", "get_url_suivi", "set_url_suivi");
	$laListeChamps[]=new dbChamp("Shp_tsp_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_tsp_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_delai() { return($this->delai); }
function get_statut() { return($this->statut); }
function get_ordre() { return($this->ordre); }
function get_url_suivi() { return($this->url_suivi); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_tsp_id) { return($this->id=$c_shp_tsp_id); }
function set_libelle($c_shp_tsp_libelle) { return($this->libelle=$c_shp_tsp_libelle); }
function set_delai($c_shp_tsp_delai) { return($this->delai=$c_shp_tsp_delai); }
function set_statut($c_shp_tsp_statut) { return($this->statut=$c_shp_tsp_statut); }
function set_ordre($c_shp_tsp_ordre) { return($this->ordre=$c_shp_tsp_ordre); }
function set_url_suivi($c_shp_tsp_url_suivi) { return($this->url_suivi=$c_shp_tsp_url_suivi); }
function set_cdate($c_shp_tsp_cdate) { return($this->cdate=$c_shp_tsp_cdate); }
function set_mdate($c_shp_tsp_mdate) { return($this->mdate=$c_shp_tsp_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_tsp_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_tsp_statut"); }
//
function getTable() { return("shp_transporteur"); }
function getClasse() { return("shp_transporteur"); }
function getPrefix() { return("shp_tsp"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("delai"); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur/list_shp_transporteur.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur/maj_shp_transporteur.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur/show_shp_transporteur.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur/rss_shp_transporteur.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur/xml_shp_transporteur.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur/xmlxls_shp_transporteur.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur/export_shp_transporteur.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_transporteur/import_shp_transporteur.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>