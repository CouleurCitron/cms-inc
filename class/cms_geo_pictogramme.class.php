<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_geo_pictogramme :: class cms_geo_pictogramme

SQL mySQL:

DROP TABLE IF EXISTS cms_geo_pictogramme;
CREATE TABLE cms_geo_pictogramme
(
	cms_gpc_id			int (12) PRIMARY KEY not null,
	cms_gpc_id_site			int (11) not null,
	cms_gpc_statut			int (2) not null,
	cms_gpc_intitule			varchar (256) not null,
	cms_gpc_fichier			varchar (256),
	cms_gpc_commentaire			text,
	cms_gpc_cdate			datetime not null,
	cms_gpc_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE cms_geo_pictogramme
CREATE TABLE cms_geo_pictogramme
(
	cms_gpc_id			number (12) constraint cms_gpc_pk PRIMARY KEY not null,
	cms_gpc_id_site			number (11) not null,
	cms_gpc_statut			number (2) not null,
	cms_gpc_intitule			varchar2 (256) not null,
	cms_gpc_fichier			varchar2 (256),
	cms_gpc_commentaire			text,
	cms_gpc_cdate			datetime not null,
	cms_gpc_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_geo_pictogramme" libelle="Pictogrammes" prefix="cms_gpc" display="intitule" abstract="fichier">
<item name="id" type="int" length="12" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_site" libelle="Présent dans le site" type="int" length="11" fkey="cms_site" notnull="true" default="0" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="intitule" libelle="Intitulé" type="varchar" length="256" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="fichier" libelle="Fichier" type="varchar" length="256" default="" list="true" option="file" />
<item name="commentaire" libelle="Commentaire" type="text" default="" order="true" option="textarea" nohtml="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class cms_geo_pictogramme
{
var $id;
var $id_site;
var $statut;
var $intitule;
var $fichier;
var $commentaire;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_geo_pictogramme\" libelle=\"Pictogrammes\" prefix=\"cms_gpc\" display=\"intitule\" abstract=\"fichier\">
<item name=\"id\" type=\"int\" length=\"12\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_site\" libelle=\"Présent dans le site\" type=\"int\" length=\"11\" fkey=\"cms_site\" notnull=\"true\" default=\"0\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"intitule\" libelle=\"Intitulé\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"fichier\" libelle=\"Fichier\" type=\"varchar\" length=\"256\" default=\"\" list=\"true\" option=\"file\" />
<item name=\"commentaire\" libelle=\"Commentaire\" type=\"text\" default=\"\" order=\"true\" option=\"textarea\" nohtml=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE cms_geo_pictogramme
(
	cms_gpc_id			int (12) PRIMARY KEY not null,
	cms_gpc_id_site			int (11) not null,
	cms_gpc_statut			int (2) not null,
	cms_gpc_intitule			varchar (256) not null,
	cms_gpc_fichier			varchar (256),
	cms_gpc_commentaire			text,
	cms_gpc_cdate			datetime not null,
	cms_gpc_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function cms_geo_pictogramme($id=null)
{
	if (istable("cms_geo_pictogramme") == false){
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
		$this->fichier = "";
		$this->commentaire = "";
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_gpc_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_gpc_id_site", "entier", "get_id_site", "set_id_site");
	$laListeChamps[]=new dbChamp("Cms_gpc_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Cms_gpc_intitule", "text", "get_intitule", "set_intitule");
	$laListeChamps[]=new dbChamp("Cms_gpc_fichier", "text", "get_fichier", "set_fichier");
	$laListeChamps[]=new dbChamp("Cms_gpc_commentaire", "text", "get_commentaire", "set_commentaire");
	$laListeChamps[]=new dbChamp("Cms_gpc_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Cms_gpc_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_site() { return($this->id_site); }
function get_statut() { return($this->statut); }
function get_intitule() { return($this->intitule); }
function get_fichier() { return($this->fichier); }
function get_commentaire() { return($this->commentaire); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_cms_gpc_id) { return($this->id=$c_cms_gpc_id); }
function set_id_site($c_cms_gpc_id_site) { return($this->id_site=$c_cms_gpc_id_site); }
function set_statut($c_cms_gpc_statut) { return($this->statut=$c_cms_gpc_statut); }
function set_intitule($c_cms_gpc_intitule) { return($this->intitule=$c_cms_gpc_intitule); }
function set_fichier($c_cms_gpc_fichier) { return($this->fichier=$c_cms_gpc_fichier); }
function set_commentaire($c_cms_gpc_commentaire) { return($this->commentaire=$c_cms_gpc_commentaire); }
function set_cdate($c_cms_gpc_cdate) { return($this->cdate=$c_cms_gpc_cdate); }
function set_mdate($c_cms_gpc_mdate) { return($this->mdate=$c_cms_gpc_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_gpc_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_gpc_statut"); }
//
function getTable() { return("cms_geo_pictogramme"); }
function getClasse() { return("cms_geo_pictogramme"); }
function getDisplay() { return("intitule"); }
function getAbstract() { return("fichier"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme/list_cms_geo_pictogramme.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme/maj_cms_geo_pictogramme.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme/show_cms_geo_pictogramme.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme/rss_cms_geo_pictogramme.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme/xml_cms_geo_pictogramme.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme/xmlxls_cms_geo_pictogramme.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme/export_cms_geo_pictogramme.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_geo_pictogramme/import_cms_geo_pictogramme.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>