<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_binder :: class cms_binder

SQL mySQL:

DROP TABLE IF EXISTS cms_binder;
CREATE TABLE cms_binder
(
	bnd_id			int (11) PRIMARY KEY not null,
	bnd_nom			varchar (128),
	bnd_image			varchar (255),
	bnd_dtcrea			date,
	bnd_dtmod			date,
	bnd_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_binder
CREATE TABLE cms_binder
(
	bnd_id			number (11) constraint bnd_pk PRIMARY KEY not null,
	bnd_nom			varchar2 (128),
	bnd_image			varchar2 (255),
	bnd_dtcrea			date,
	bnd_dtmod			date,
	bnd_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_binder" prefix="bnd" display="nom" abstract="image">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assobinderpdf" />
<item name="nom" libelle="Nom" type="varchar" length="128" list="true" order="true"  />
<item name="image" libelle="Image de couverture" type="varchar" length="255" list="false" order="true" option="file">
<option type="image" maxwidth="120" maxheight="120" />
</item>
<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
</class>


==========================================*/

class cms_binder
{
var $id;
var $nom;
var $image;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_binder\" prefix=\"bnd\" display=\"nom\" abstract=\"image\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assobinderpdf\" />
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"128\" list=\"true\" order=\"true\"  />
<item name=\"image\" libelle=\"Image de couverture\" type=\"varchar\" length=\"255\" list=\"false\" order=\"true\" option=\"file\">
<option type=\"image\" maxwidth=\"120\" maxheight=\"120\" />
</item>
<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
</class>";

var $sMySql = "CREATE TABLE cms_binder
(
	bnd_id			int (11) PRIMARY KEY not null,
	bnd_nom			varchar (128),
	bnd_image			varchar (255),
	bnd_dtcrea			date,
	bnd_dtmod			date,
	bnd_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_binder") == false){
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
		$this->nom = "";
		$this->image = "";
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Bnd_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Bnd_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Bnd_image", "text", "get_image", "set_image");
	$laListeChamps[]=new dbChamp("Bnd_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Bnd_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Bnd_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_image() { return($this->image); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_bnd_id) { return($this->id=$c_bnd_id); }
function set_nom($c_bnd_nom) { return($this->nom=$c_bnd_nom); }
function set_image($c_bnd_image) { return($this->image=$c_bnd_image); }
function set_dtcrea($c_bnd_dtcrea) { return($this->dtcrea=$c_bnd_dtcrea); }
function set_dtmod($c_bnd_dtmod) { return($this->dtmod=$c_bnd_dtmod); }
function set_statut($c_bnd_statut) { return($this->statut=$c_bnd_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("bnd_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("bnd_statut"); }
//
function getTable() { return("cms_binder"); }
function getClasse() { return("cms_binder"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("image"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder/list_cms_binder.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder/maj_cms_binder.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder/show_cms_binder.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder/rss_cms_binder.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder/xml_cms_binder.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder/export_cms_binder.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_binder/import_cms_binder.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>