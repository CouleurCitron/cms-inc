<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_produit_type :: class shp_produit_type

SQL mySQL:

DROP TABLE IF EXISTS shp_produit_type;
CREATE TABLE shp_produit_type
(
	shp_typ_id			int (4) PRIMARY KEY not null,
	shp_typ_id_diaporama			int (11),
	shp_typ_statut			int (2) not null,
	shp_typ_titre_court			int (11) not null,
	shp_typ_titre_long			int (11),
	shp_typ_sous_titre			int (11),
	shp_typ_texte_long			int (11),
	shp_typ_vignette			varchar (256),
	shp_typ_ordre			int (11),
	shp_typ_cdate			datetime not null,
	shp_typ_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_produit_type
CREATE TABLE shp_produit_type
(
	shp_typ_id			number (4) constraint shp_typ_pk PRIMARY KEY not null,
	shp_typ_id_diaporama			number (11),
	shp_typ_statut			number (2) not null,
	shp_typ_titre_court			number (11) not null,
	shp_typ_titre_long			number (11),
	shp_typ_sous_titre			number (11),
	shp_typ_texte_long			number (11),
	shp_typ_vignette			varchar2 (256),
	shp_typ_ordre			number (11),
	shp_typ_cdate			datetime not null,
	shp_typ_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_produit_type" libelle="Types de produit" prefix="shp_typ" display="titre_court" abstract="statut">
<item name="id" type="int" length="4" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_diaporama" libelle="Diaporama" type="int" length="11" fkey="cms_diaporama" list="true" order="true" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="titre_court" libelle="Titre court" type="int" length="11" notnull="true" default="" list="true" order="true" nohtml="true" translate="reference" />
<item name="titre_long" libelle="Titre long" type="int" length="11" default="" nohtml="true" translate="reference" />
<item name="sous_titre" libelle="Sous-titre" type="int" length="11" default="" nohtml="true" translate="reference" />
<item name="texte_long" libelle="Texte long" type="int" length="11" default="" translate="reference" />
<item name="vignette" libelle="Vignette" type="varchar" length="256" default="" option="file" />
<item name="ordre" libelle="Ordre d'apparition" type="int" length="11" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_produit_type
{
var $id;
var $id_diaporama;
var $statut;
var $titre_court;
var $titre_long;
var $sous_titre;
var $texte_long;
var $vignette;
var $ordre;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_produit_type\" libelle=\"Types de produit\" prefix=\"shp_typ\" display=\"titre_court\" abstract=\"statut\">
<item name=\"id\" type=\"int\" length=\"4\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_diaporama\" libelle=\"Diaporama\" type=\"int\" length=\"11\" fkey=\"cms_diaporama\" list=\"true\" order=\"true\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"titre_court\" libelle=\"Titre court\" type=\"int\" length=\"11\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" translate=\"reference\" />
<item name=\"titre_long\" libelle=\"Titre long\" type=\"int\" length=\"11\" default=\"\" nohtml=\"true\" translate=\"reference\" />
<item name=\"sous_titre\" libelle=\"Sous-titre\" type=\"int\" length=\"11\" default=\"\" nohtml=\"true\" translate=\"reference\" />
<item name=\"texte_long\" libelle=\"Texte long\" type=\"int\" length=\"11\" default=\"\" translate=\"reference\" />
<item name=\"vignette\" libelle=\"Vignette\" type=\"varchar\" length=\"256\" default=\"\" option=\"file\" />
<item name=\"ordre\" libelle=\"Ordre d'apparition\" type=\"int\" length=\"11\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_produit_type
(
	shp_typ_id			int (4) PRIMARY KEY not null,
	shp_typ_id_diaporama			int (11),
	shp_typ_statut			int (2) not null,
	shp_typ_titre_court			int (11) not null,
	shp_typ_titre_long			int (11),
	shp_typ_sous_titre			int (11),
	shp_typ_texte_long			int (11),
	shp_typ_vignette			varchar (256),
	shp_typ_ordre			int (11),
	shp_typ_cdate			datetime not null,
	shp_typ_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function __construct($id=null)
{
	if (istable("shp_produit_type") == false){
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
		$this->id_diaporama = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->titre_court = -1;
		$this->titre_long = -1;
		$this->sous_titre = -1;
		$this->texte_long = -1;
		$this->vignette = "";
		$this->ordre = -1;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = date('Y-m-d H:i:s');
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_typ_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_typ_id_diaporama", "entier", "get_id_diaporama", "set_id_diaporama");
	$laListeChamps[]=new dbChamp("Shp_typ_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_typ_titre_court", "entier", "get_titre_court", "set_titre_court");
	$laListeChamps[]=new dbChamp("Shp_typ_titre_long", "entier", "get_titre_long", "set_titre_long");
	$laListeChamps[]=new dbChamp("Shp_typ_sous_titre", "entier", "get_sous_titre", "set_sous_titre");
	$laListeChamps[]=new dbChamp("Shp_typ_texte_long", "entier", "get_texte_long", "set_texte_long");
	$laListeChamps[]=new dbChamp("Shp_typ_vignette", "text", "get_vignette", "set_vignette");
	$laListeChamps[]=new dbChamp("Shp_typ_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Shp_typ_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_typ_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_diaporama() { return($this->id_diaporama); }
function get_statut() { return($this->statut); }
function get_titre_court() { return($this->titre_court); }
function get_titre_long() { return($this->titre_long); }
function get_sous_titre() { return($this->sous_titre); }
function get_texte_long() { return($this->texte_long); }
function get_vignette() { return($this->vignette); }
function get_ordre() { return($this->ordre); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_typ_id) { return($this->id=$c_shp_typ_id); }
function set_id_diaporama($c_shp_typ_id_diaporama) { return($this->id_diaporama=$c_shp_typ_id_diaporama); }
function set_statut($c_shp_typ_statut) { return($this->statut=$c_shp_typ_statut); }
function set_titre_court($c_shp_typ_titre_court) { return($this->titre_court=$c_shp_typ_titre_court); }
function set_titre_long($c_shp_typ_titre_long) { return($this->titre_long=$c_shp_typ_titre_long); }
function set_sous_titre($c_shp_typ_sous_titre) { return($this->sous_titre=$c_shp_typ_sous_titre); }
function set_texte_long($c_shp_typ_texte_long) { return($this->texte_long=$c_shp_typ_texte_long); }
function set_vignette($c_shp_typ_vignette) { return($this->vignette=$c_shp_typ_vignette); }
function set_ordre($c_shp_typ_ordre) { return($this->ordre=$c_shp_typ_ordre); }
function set_cdate($c_shp_typ_cdate) { return($this->cdate=$c_shp_typ_cdate); }
function set_mdate($c_shp_typ_mdate) { return($this->mdate=$c_shp_typ_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_typ_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_typ_statut"); }
//
function getTable() { return("shp_produit_type"); }
function getClasse() { return("shp_produit_type"); }
function getPrefix() { return("shp_typ"); }
function getDisplay() { return("titre_court"); }
function getAbstract() { return("statut"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type")){
/*
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type/list_shp_produit_type.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type/maj_shp_produit_type.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type/show_shp_produit_type.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type/rss_shp_produit_type.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type/xml_shp_produit_type.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type/xmlxls_shp_produit_type.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type/export_shp_produit_type.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_produit_type/import_shp_produit_type.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
*/
}

?>