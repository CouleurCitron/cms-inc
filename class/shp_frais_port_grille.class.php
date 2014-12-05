<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_frais_port_grille :: class shp_frais_port_grille

SQL mySQL:

DROP TABLE IF EXISTS shp_frais_port_grille;
CREATE TABLE shp_frais_port_grille
(
	shp_fpg_id			int (12) PRIMARY KEY not null,
	shp_fpg_id_transporteur			int (3),
	shp_fpg_id_pays			int (3) not null,
	shp_fpg_type			enum ('NUM','CHAR') not null default 'NUM',
	shp_fpg_match			enum ('PREFIX','FULL') not null default 'PREFIX',
	shp_fpg_unite_poids			varchar (64) not null,
	shp_fpg_zone			text not null,
	shp_fpg_statut			int (2) not null,
	shp_fpg_cdate			datetime not null,
	shp_fpg_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_frais_port_grille
CREATE TABLE shp_frais_port_grille
(
	shp_fpg_id			number (12) constraint shp_fpg_pk PRIMARY KEY not null,
	shp_fpg_id_transporteur			number (3),
	shp_fpg_id_pays			number (3) not null,
	shp_fpg_type			enum ('NUM','CHAR') not null default 'NUM',
	shp_fpg_match			enum ('PREFIX','FULL') not null default 'PREFIX',
	shp_fpg_unite_poids			varchar2 (64) not null,
	shp_fpg_zone			text not null,
	shp_fpg_statut			number (2) not null,
	shp_fpg_cdate			datetime not null,
	shp_fpg_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_frais_port_grille" libelle="Grille de frais de port" prefix="shp_fpg" display="id_transporteur" abstract="zone">
<item name="id" type="int" length="12" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_transporteur" libelle="Transporteur" type="int" length="3" fkey="shp_transporteur" list="true" order="true" />
<item name="id_pays" libelle="Pays" type="int" length="3" fkey="cms_pays" notnull="true" default="0" list="true" order="true" /> 
<item name="type" libelle="Syntaxe" type="enum" length="'NUM','CHAR'" notnull="true" default="NUM" />
<item name="match" libelle="Rapprochement" type="enum" length="'PREFIX','FULL'" notnull="true" default="PREFIX" />
<item name="unite_poids" libelle="Unité de poids" type="varchar" length="64" notnull="true" default="" nohtml="true" />
<item name="zone" libelle="Définition des zones" type="text" notnull="true" default="" nohtml="true" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_frais_port_grille
{
var $id;
var $id_transporteur;
var $id_pays;
var $type;
var $match;
var $unite_poids;
var $zone;
var $statut;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_frais_port_grille\" libelle=\"Grille de frais de port\" prefix=\"shp_fpg\" display=\"id_transporteur\" abstract=\"zone\">
<item name=\"id\" type=\"int\" length=\"12\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_transporteur\" libelle=\"Transporteur\" type=\"int\" length=\"3\" fkey=\"shp_transporteur\" list=\"true\" order=\"true\" />
<item name=\"id_pays\" libelle=\"Pays\" type=\"int\" length=\"3\" fkey=\"cms_pays\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" /> 
<item name=\"type\" libelle=\"Syntaxe\" type=\"enum\" length=\"'NUM','CHAR'\" notnull=\"true\" default=\"NUM\" />
<item name=\"match\" libelle=\"Rapprochement\" type=\"enum\" length=\"'PREFIX','FULL'\" notnull=\"true\" default=\"PREFIX\" />
<item name=\"unite_poids\" libelle=\"Unité de poids\" type=\"varchar\" length=\"64\" notnull=\"true\" default=\"\" nohtml=\"true\" />
<item name=\"zone\" libelle=\"Définition des zones\" type=\"text\" notnull=\"true\" default=\"\" nohtml=\"true\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_frais_port_grille
(
	shp_fpg_id			int (12) PRIMARY KEY not null,
	shp_fpg_id_transporteur			int (3),
	shp_fpg_id_pays			int (3) not null,
	shp_fpg_type			enum ('NUM','CHAR') not null default 'NUM',
	shp_fpg_match			enum ('PREFIX','FULL') not null default 'PREFIX',
	shp_fpg_unite_poids			varchar (64) not null,
	shp_fpg_zone			text not null,
	shp_fpg_statut			int (2) not null,
	shp_fpg_cdate			datetime not null,
	shp_fpg_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function shp_frais_port_grille($id=null)
{
	if (istable("shp_frais_port_grille") == false){
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
		$this->id_transporteur = -1;
		$this->id_pays = -1;
		$this->type = "NUM";
		$this->match = "PREFIX";
		$this->unite_poids = "";
		$this->zone = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_fpg_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_fpg_id_transporteur", "entier", "get_id_transporteur", "set_id_transporteur");
	$laListeChamps[]=new dbChamp("Shp_fpg_id_pays", "entier", "get_id_pays", "set_id_pays");
	$laListeChamps[]=new dbChamp("Shp_fpg_type", "text", "get_type", "set_type");
	$laListeChamps[]=new dbChamp("Shp_fpg_match", "text", "get_match", "set_match");
	$laListeChamps[]=new dbChamp("Shp_fpg_unite_poids", "text", "get_unite_poids", "set_unite_poids");
	$laListeChamps[]=new dbChamp("Shp_fpg_zone", "text", "get_zone", "set_zone");
	$laListeChamps[]=new dbChamp("Shp_fpg_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_fpg_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_fpg_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_transporteur() { return($this->id_transporteur); }
function get_id_pays() { return($this->id_pays); }
function get_type() { return($this->type); }
function get_match() { return($this->match); }
function get_unite_poids() { return($this->unite_poids); }
function get_zone() { return($this->zone); }
function get_statut() { return($this->statut); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_fpg_id) { return($this->id=$c_shp_fpg_id); }
function set_id_transporteur($c_shp_fpg_id_transporteur) { return($this->id_transporteur=$c_shp_fpg_id_transporteur); }
function set_id_pays($c_shp_fpg_id_pays) { return($this->id_pays=$c_shp_fpg_id_pays); }
function set_type($c_shp_fpg_type) { return($this->type=$c_shp_fpg_type); }
function set_match($c_shp_fpg_match) { return($this->match=$c_shp_fpg_match); }
function set_unite_poids($c_shp_fpg_unite_poids) { return($this->unite_poids=$c_shp_fpg_unite_poids); }
function set_zone($c_shp_fpg_zone) { return($this->zone=$c_shp_fpg_zone); }
function set_statut($c_shp_fpg_statut) { return($this->statut=$c_shp_fpg_statut); }
function set_cdate($c_shp_fpg_cdate) { return($this->cdate=$c_shp_fpg_cdate); }
function set_mdate($c_shp_fpg_mdate) { return($this->mdate=$c_shp_fpg_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_fpg_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_fpg_statut"); }
//
function getTable() { return("shp_frais_port_grille"); }
function getClasse() { return("shp_frais_port_grille"); }
function getPrefix() { return("shp_fpg"); }
function getDisplay() { return("id_transporteur"); }
function getAbstract() { return("zone"); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille/list_shp_frais_port_grille.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille/maj_shp_frais_port_grille.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille/show_shp_frais_port_grille.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille/rss_shp_frais_port_grille.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille/xml_shp_frais_port_grille.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille/xmlxls_shp_frais_port_grille.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille/export_shp_frais_port_grille.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_grille/import_shp_frais_port_grille.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>