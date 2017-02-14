<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_frais_port_valeur :: class shp_frais_port_valeur

SQL mySQL:

DROP TABLE IF EXISTS shp_frais_port_valeur;
CREATE TABLE shp_frais_port_valeur
(
	shp_fpv_id			int (8) PRIMARY KEY not null,
	shp_fpv_id_grille			int (5) not null,
	shp_fpv_coef_poids		enum ('Y','N') default 'N',
	shp_fpv_minimum			int (5) not null,
	shp_fpv_maximum			int (5) not null,
	shp_fpv_valeur			decimal (10,2) not null,
	shp_fpv_statut			int (2) not null,
	shp_fpv_cdate			datetime not null,
	shp_fpv_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_frais_port_valeur
CREATE TABLE shp_frais_port_valeur
(
	shp_fpv_id			number (8) constraint shp_fpv_pk PRIMARY KEY not null,
	shp_fpv_id_grille			number (5) not null,
	shp_fpv_coef_poids		enum ('Y','N') default 'N',
	shp_fpv_minimum			number (5) not null,
	shp_fpv_maximum			number (5) not null,
	shp_fpv_valeur			decimal (10,2) not null,
	shp_fpv_statut			number (2) not null,
	shp_fpv_cdate			datetime not null,
	shp_fpv_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_frais_port_valeur" libelle="Valeur dans grille de frais de port" prefix="shp_fpv" display="minimum" abstract="maximum">
<item name="id" type="int" length="8" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_grille" libelle="Grille" type="int" length="5" fkey="shp_frais_port_grille" notnull="true" default="0" list="true" order="true" />
<item name="coef_poids" libelle="Applique le coefficient de poids" type="enum" length="'Y','N'" default="N" list="true" />
<item name="minimum" libelle="Minimum" type="int" length="5" notnull="true" default="0" list="true" order="true" />
<item name="maximum" libelle="Maximum" type="int" length="5" notnull="true" default="0" list="true" order="true" />
<item name="valeur" libelle="Valeur" type="decimal" length="10,2" notnull="true" default="0.00" list="true" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull=\"true\" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_frais_port_valeur
{
var $id;
var $id_grille;
var $coef_poids;
var $minimum;
var $maximum;
var $valeur;
var $statut;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_frais_port_valeur\" libelle=\"Valeur dans grille de frais de port\" prefix=\"shp_fpv\" display=\"minimum\" abstract=\"maximum\">
<item name=\"id\" type=\"int\" length=\"8\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_grille\" libelle=\"Grille\" type=\"int\" length=\"5\" fkey=\"shp_frais_port_grille\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"coef_poids\" libelle=\"Applique le coefficient de poids\" type=\"enum\" length=\"'Y','N'\" default=\"N\" list=\"true\" />
<item name=\"minimum\" libelle=\"Minimum\" type=\"int\" length=\"5\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"maximum\" libelle=\"Maximum\" type=\"int\" length=\"5\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"valeur\" libelle=\"Valeur\" type=\"decimal\" length=\"10,2\" notnull=\"true\" default=\"0.00\" list=\"true\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_frais_port_valeur
(
	shp_fpv_id			int (8) PRIMARY KEY not null,
	shp_fpv_id_grille			int (5) not null,
	shp_fpv_coef_poids		enum ('Y','N') default 'N',
	shp_fpv_minimum			int (5) not null,
	shp_fpv_maximum			int (5) not null,
	shp_fpv_valeur			decimal (10,2) not null,
	shp_fpv_statut			int (2) not null,
	shp_fpv_cdate			datetime not null,
	shp_fpv_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function __construct($id=null)
{
	if (istable("shp_frais_port_valeur") == false){
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
		$this->id_grille = -1;
		$this->coef_poids = 'N';
		$this->minimum = -1;
		$this->maximum = -1;
		$this->valeur = 0.00;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_fpv_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_fpv_id_grille", "entier", "get_id_grille", "set_id_grille");
	$laListeChamps[]=new dbChamp("Shp_fpv_coef_poids", "text", "get_coef_poids", "set_coef_poids");
	$laListeChamps[]=new dbChamp("Shp_fpv_minimum", "entier", "get_minimum", "set_minimum");
	$laListeChamps[]=new dbChamp("Shp_fpv_maximum", "entier", "get_maximum", "set_maximum");
	$laListeChamps[]=new dbChamp("Shp_fpv_valeur", "decimal", "get_valeur", "set_valeur");
	$laListeChamps[]=new dbChamp("Shp_fpv_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_fpv_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_fpv_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_grille() { return($this->id_grille); }
function get_coef_poids() { return($this->coef_poids); }
function get_minimum() { return($this->minimum); }
function get_maximum() { return($this->maximum); }
function get_valeur() { return($this->valeur); }
function get_statut() { return($this->statut); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_fpv_id) { return($this->id=$c_shp_fpv_id); }
function set_id_grille($c_shp_fpv_id_grille) { return($this->id_grille=$c_shp_fpv_id_grille); }
function set_coef_poids($c_shp_fpv_coef_poids) { return($this->coef_poids=$c_shp_fpv_coef_poids); }
function set_minimum($c_shp_fpv_minimum) { return($this->minimum=$c_shp_fpv_minimum); }
function set_maximum($c_shp_fpv_maximum) { return($this->maximum=$c_shp_fpv_maximum); }
function set_valeur($c_shp_fpv_valeur) { return($this->valeur=$c_shp_fpv_valeur); }
function set_statut($c_shp_fpv_statut) { return($this->statut=$c_shp_fpv_statut); }
function set_cdate($c_shp_fpv_cdate) { return($this->cdate=$c_shp_fpv_cdate); }
function set_mdate($c_shp_fpv_mdate) { return($this->mdate=$c_shp_fpv_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_fpv_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_fpv_statut"); }
//
function getTable() { return("shp_frais_port_valeur"); }
function getClasse() { return("shp_frais_port_valeur"); }
function getPrefix() { return("shp_fpv"); }
function getDisplay() { return("minimum"); }
function getAbstract() { return("maximum"); }


} //class


// ecriture des fichiers
/*
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur/list_shp_frais_port_valeur.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur/maj_shp_frais_port_valeur.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur/show_shp_frais_port_valeur.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur/rss_shp_frais_port_valeur.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur/xml_shp_frais_port_valeur.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur/xmlxls_shp_frais_port_valeur.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur/export_shp_frais_port_valeur.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_frais_port_valeur/import_shp_frais_port_valeur.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
*/
?>