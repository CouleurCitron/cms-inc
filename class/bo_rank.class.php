<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD bo_rank :: class bo_rank

SQL mySQL:

DROP TABLE IF EXISTS bo_rank;
CREATE TABLE bo_rank
(
	rank_id			int (11) PRIMARY KEY not null,
	rank_libelle			varchar (16),
	rank_description			varchar (255),
	rank_statut			int not null
)

SQL Oracle:

DROP TABLE bo_rank
CREATE TABLE bo_rank
(
	rank_id			number (11) constraint rank_pk PRIMARY KEY not null,
	rank_libelle			varchar2 (16),
	rank_description			varchar2 (255),
	rank_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="bo_rank" libelle="Rang des utilisateurs" prefix="rank" display="libelle" abstract="id">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="libelle" libelle="libelle" type="varchar" length="16" list="true" order="true" nohtml="true" />
<item name="description" libelle="description" type="varchar" length="255" list="false" order="false"/>
<item name="statut" type="int" notnull="true" list="true" order="true" default="DEF_CODE_STATUT_DEFAUT" />
</class>


==========================================*/

class bo_rank
{
var $id;
var $libelle;
var $description;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"bo_rank\" libelle=\"Rang des utilisateurs\" prefix=\"rank\" display=\"libelle\" abstract=\"id\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"libelle\" libelle=\"libelle\" type=\"varchar\" length=\"16\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"description\" libelle=\"description\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\"/>
<item name=\"statut\" type=\"int\" notnull=\"true\" list=\"true\" order=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" />
</class>";

var $sMySql = "CREATE TABLE bo_rank
(
	rank_id			int (11) PRIMARY KEY not null,
	rank_libelle			varchar (16),
	rank_description			varchar (255),
	rank_statut			int not null
)

";

// constructeur
function bo_rank($id=null)
{
	if (istable("bo_rank") == false){
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
		$this->description = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Rank_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Rank_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Rank_description", "text", "get_description", "set_description");
	$laListeChamps[]=new dbChamp("Rank_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_description() { return($this->description); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_rank_id) { return($this->id=$c_rank_id); }
function set_libelle($c_rank_libelle) { return($this->libelle=$c_rank_libelle); }
function set_description($c_rank_description) { return($this->description=$c_rank_description); }
function set_statut($c_rank_statut) { return($this->statut=$c_rank_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("rank_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("rank_statut"); }
//
function getTable() { return("bo_rank"); }
function getClasse() { return("bo_rank"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("id"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank/list_bo_rank.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank/maj_bo_rank.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank/show_bo_rank.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank/rss_bo_rank.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank/xml_bo_rank.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank/export_bo_rank.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_rank/import_bo_rank.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>