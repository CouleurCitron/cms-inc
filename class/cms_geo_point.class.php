<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_geo_point :: class cms_geo_point

SQL mySQL:

DROP TABLE IF EXISTS cms_geo_point;
CREATE TABLE cms_geo_point
(
	cms_gpt_id			int (12) PRIMARY KEY not null,
	cms_gpt_id_site			int (11) not null,
	cms_gpt_id_picto			int (11) not null,
	cms_gpt_statut			int (2) not null,
	cms_gpt_intitule			varchar (256) not null,
	cms_gpt_coords			varchar (512),
	cms_gpt_adresse			text,
	cms_gpt_commentaire			text,
	cms_gpt_cdate			datetime not null,
	cms_gpt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE cms_geo_point
CREATE TABLE cms_geo_point
(
	cms_gpt_id			number (12) constraint cms_gpt_pk PRIMARY KEY not null,
	cms_gpt_id_site			number (11) not null,
	cms_gpt_id_picto			number (11) not null,
	cms_gpt_statut			number (2) not null,
	cms_gpt_intitule			varchar2 (256) not null,
	cms_gpt_coords			varchar2 (512),
	cms_gpt_adresse			text,
	cms_gpt_commentaire			text,
	cms_gpt_cdate			datetime not null,
	cms_gpt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_geo_point" libelle="Points sur cartes GoogleMaps" prefix="cms_gpt" display="intitule" abstract="coords">
<item name="id" type="int" length="12" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_site" libelle="Présent dans le site" type="int" length="11" fkey="cms_site" notnull="true" default="0" />
<item name="id_picto" libelle="Pictogramme" type="int" length="11" fkey="cms_geo_pictogramme" notnull="true" default="0" list="true" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="intitule" libelle="Intitulé" type="varchar" length="256" notnull="true" list="true" order="true" default="" nohtml="true" />
<item name="coords" libelle="Coordonnées" type="varchar" length="512" list="true" order="true" option="geocoords" />
<item name="adresse" libelle="Adresse" type="text" default="" order="true" option="textarea" nohtml="true" />
<item name="commentaire" libelle="Commentaire" type="text" default="" order="true" option="textarea" nohtml="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class cms_geo_point
{
var $id;
var $id_site;
var $id_picto;
var $statut;
var $intitule;
var $coords;
var $adresse;
var $commentaire;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_geo_point\" libelle=\"Points sur cartes GoogleMaps\" prefix=\"cms_gpt\" display=\"intitule\" abstract=\"coords\">
<item name=\"id\" type=\"int\" length=\"12\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_site\" libelle=\"Présent dans le site\" type=\"int\" length=\"11\" fkey=\"cms_site\" notnull=\"true\" default=\"0\" />
<item name=\"id_picto\" libelle=\"Pictogramme\" type=\"int\" length=\"11\" fkey=\"cms_geo_pictogramme\" notnull=\"true\" default=\"0\" list=\"true\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"intitule\" libelle=\"Intitulé\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"coords\" libelle=\"Coordonnées\" type=\"varchar\" length=\"512\" list=\"true\" order=\"true\" option=\"geocoords\" />
<item name=\"adresse\" libelle=\"Adresse\" type=\"text\" default=\"\" order=\"true\" option=\"textarea\" nohtml=\"true\" />
<item name=\"commentaire\" libelle=\"Commentaire\" type=\"text\" default=\"\" order=\"true\" option=\"textarea\" nohtml=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE cms_geo_point
(
	cms_gpt_id			int (12) PRIMARY KEY not null,
	cms_gpt_id_site			int (11) not null,
	cms_gpt_id_picto			int (11) not null,
	cms_gpt_statut			int (2) not null,
	cms_gpt_intitule			varchar (256) not null,
	cms_gpt_coords			varchar (512),
	cms_gpt_adresse			text,
	cms_gpt_commentaire			text,
	cms_gpt_cdate			datetime not null,
	cms_gpt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function cms_geo_point($id=null)
{
	if (istable("cms_geo_point") == false){
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
		$this->id_picto = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->intitule = "";
		$this->coords = "";
		$this->adresse = "";
		$this->commentaire = "";
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_gpt_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_gpt_id_site", "entier", "get_id_site", "set_id_site");
	$laListeChamps[]=new dbChamp("Cms_gpt_id_picto", "entier", "get_id_picto", "set_id_picto");
	$laListeChamps[]=new dbChamp("Cms_gpt_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Cms_gpt_intitule", "text", "get_intitule", "set_intitule");
	$laListeChamps[]=new dbChamp("Cms_gpt_coords", "text", "get_coords", "set_coords");
	$laListeChamps[]=new dbChamp("Cms_gpt_adresse", "text", "get_adresse", "set_adresse");
	$laListeChamps[]=new dbChamp("Cms_gpt_commentaire", "text", "get_commentaire", "set_commentaire");
	$laListeChamps[]=new dbChamp("Cms_gpt_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Cms_gpt_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_site() { return($this->id_site); }
function get_id_picto() { return($this->id_picto); }
function get_statut() { return($this->statut); }
function get_intitule() { return($this->intitule); }
function get_coords() { return($this->coords); }
function get_adresse() { return($this->adresse); }
function get_commentaire() { return($this->commentaire); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_cms_gpt_id) { return($this->id=$c_cms_gpt_id); }
function set_id_site($c_cms_gpt_id_site) { return($this->id_site=$c_cms_gpt_id_site); }
function set_id_picto($c_cms_gpt_id_picto) { return($this->id_picto=$c_cms_gpt_id_picto); }
function set_statut($c_cms_gpt_statut) { return($this->statut=$c_cms_gpt_statut); }
function set_intitule($c_cms_gpt_intitule) { return($this->intitule=$c_cms_gpt_intitule); }
function set_coords($c_cms_gpt_coords) { return($this->coords=$c_cms_gpt_coords); }
function set_adresse($c_cms_gpt_adresse) { return($this->adresse=$c_cms_gpt_adresse); }
function set_commentaire($c_cms_gpt_commentaire) { return($this->commentaire=$c_cms_gpt_commentaire); }
function set_cdate($c_cms_gpt_cdate) { return($this->cdate=$c_cms_gpt_cdate); }
function set_mdate($c_cms_gpt_mdate) { return($this->mdate=$c_cms_gpt_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_gpt_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_gpt_statut"); }
//
function getTable() { return("cms_geo_point"); }
function getClasse() { return("cms_geo_point"); }
function getDisplay() { return("intitule"); }
function getAbstract() { return("coords"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point/list_cms_geo_point.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point/maj_cms_geo_point.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point/show_cms_geo_point.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point/rss_cms_geo_point.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point/xml_cms_geo_point.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point/xmlxls_cms_geo_point.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point/export_cms_geo_point.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_point/import_cms_geo_point.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>