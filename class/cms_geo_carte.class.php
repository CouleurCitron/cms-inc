<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_geo_carte :: class cms_geo_carte

SQL mySQL:

DROP TABLE IF EXISTS cms_geo_carte;
CREATE TABLE cms_geo_carte
(
	cms_gca_id			int (12) PRIMARY KEY not null,
	cms_gca_id_site			int (11) not null,
	cms_gca_statut			int (2) not null,
	cms_gca_intitule			varchar (256) not null,
	cms_gca_echelle			int (3),
	cms_gca_pivot			varchar (512),
	cms_gca_fichier			varchar (255),
	cms_gca_cdate			datetime not null,
	cms_gca_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE cms_geo_carte
CREATE TABLE cms_geo_carte
(
	cms_gca_id			number (12) constraint cms_gca_pk PRIMARY KEY not null,
	cms_gca_id_site			number (11) not null,
	cms_gca_statut			number (2) not null,
	cms_gca_intitule			varchar2 (256) not null,
	cms_gca_echelle			number (3),
	cms_gca_pivot			varchar2 (512),
	cms_gca_fichier			varchar2 (255),
	cms_gca_cdate			datetime not null,
	cms_gca_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_geo_carte" libelle="Cartes GoogleMaps" prefix="cms_gca" display="intitule" abstract="fichier">
<item name="id" type="int" length="12" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_site" libelle="Présent dans le site" type="int" length="11" fkey="cms_site" notnull="true" default="0" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="intitule" libelle="Intitulé" type="varchar" length="256" notnull="true" list="true" order="true" default="" nohtml="true" />
<item name="echelle" libelle="Echelle" type="int" length="3" default="-1" list="true" order="true" option="geomapscale" />
<item name="pivot" libelle="Point pivot central" type="varchar" length="512" list="true" order="true" option="geomapcenter" geomapsize="DEF_GMAP_SIZE" />
<item name="fichier" libelle="Fichier image" type="varchar" length="255" option="geomapfile" geomapsize="DEF_GMAP_SIZE">
	<option type="if" item="echelle" value="0" />
	<option type="if" item="pivot" value="0" />
</item>
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class cms_geo_carte
{
var $id;
var $id_site;
var $statut;
var $intitule;
var $echelle;
var $pivot;
var $fichier;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_geo_carte\" libelle=\"Cartes GoogleMaps\" prefix=\"cms_gca\" display=\"intitule\" abstract=\"fichier\">
<item name=\"id\" type=\"int\" length=\"12\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_site\" libelle=\"Présent dans le site\" type=\"int\" length=\"11\" fkey=\"cms_site\" notnull=\"true\" default=\"0\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"intitule\" libelle=\"Intitulé\" type=\"varchar\" length=\"256\" notnull=\"true\" list=\"true\" order=\"true\" default=\"\" nohtml=\"true\" />
<item name=\"echelle\" libelle=\"Echelle\" type=\"int\" length=\"3\" default=\"-1\" list=\"true\" order=\"true\" option=\"geomapscale\" />
<item name=\"pivot\" libelle=\"Point pivot central\" type=\"varchar\" length=\"512\" list=\"true\" order=\"true\" option=\"geomapcenter\" geomapsize=\"DEF_GMAP_SIZE\" />
<item name=\"fichier\" libelle=\"Fichier image\" type=\"varchar\" length=\"255\" option=\"geomapfile\" geomapsize=\"DEF_GMAP_SIZE\">
	<option type=\"if\" item=\"echelle\" value=\"0\" />
	<option type=\"if\" item=\"pivot\" value=\"0\" />
</item>
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE cms_geo_carte
(
	cms_gca_id			int (12) PRIMARY KEY not null,
	cms_gca_id_site			int (11) not null,
	cms_gca_statut			int (2) not null,
	cms_gca_intitule			varchar (256) not null,
	cms_gca_echelle			int (3),
	cms_gca_pivot			varchar (512),
	cms_gca_fichier			varchar (255),
	cms_gca_cdate			datetime not null,
	cms_gca_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_geo_carte") == false){
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
		$this->id_site = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->intitule = "";
		$this->echelle = -1;
		$this->pivot = "";
		$this->fichier = "";
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_gca_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_gca_id_site", "entier", "get_id_site", "set_id_site");
	$laListeChamps[]=new dbChamp("Cms_gca_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Cms_gca_intitule", "text", "get_intitule", "set_intitule");
	$laListeChamps[]=new dbChamp("Cms_gca_echelle", "entier", "get_echelle", "set_echelle");
	$laListeChamps[]=new dbChamp("Cms_gca_pivot", "text", "get_pivot", "set_pivot");
	$laListeChamps[]=new dbChamp("Cms_gca_fichier", "text", "get_fichier", "set_fichier");
	$laListeChamps[]=new dbChamp("Cms_gca_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Cms_gca_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_site() { return($this->id_site); }
function get_statut() { return($this->statut); }
function get_intitule() { return($this->intitule); }
function get_echelle() { return($this->echelle); }
function get_pivot() { return($this->pivot); }
function get_fichier() { return($this->fichier); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_cms_gca_id) { return($this->id=$c_cms_gca_id); }
function set_id_site($c_cms_gca_id_site) { return($this->id_site=$c_cms_gca_id_site); }
function set_statut($c_cms_gca_statut) { return($this->statut=$c_cms_gca_statut); }
function set_intitule($c_cms_gca_intitule) { return($this->intitule=$c_cms_gca_intitule); }
function set_echelle($c_cms_gca_echelle) { return($this->echelle=$c_cms_gca_echelle); }
function set_pivot($c_cms_gca_pivot) { return($this->pivot=$c_cms_gca_pivot); }
function set_fichier($c_cms_gca_fichier) { return($this->fichier=$c_cms_gca_fichier); }
function set_cdate($c_cms_gca_cdate) { return($this->cdate=$c_cms_gca_cdate); }
function set_mdate($c_cms_gca_mdate) { return($this->mdate=$c_cms_gca_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_gca_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_gca_statut"); }
//
function getTable() { return("cms_geo_carte"); }
function getClasse() { return("cms_geo_carte"); }
function getDisplay() { return("intitule"); }
function getAbstract() { return("fichier"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte/list_cms_geo_carte.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte/maj_cms_geo_carte.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte/show_cms_geo_carte.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte/rss_cms_geo_carte.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte/xml_cms_geo_carte.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte/xmlxls_cms_geo_carte.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte/export_cms_geo_carte.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_carte/import_cms_geo_carte.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>