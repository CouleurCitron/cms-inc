<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_langue :: class cms_langue

SQL mySQL:

DROP TABLE IF EXISTS cms_langue;
CREATE TABLE cms_langue
(
	lan_id			int (11) PRIMARY KEY not null,
	lan_libelle			varchar (255),
	lan_libellecourt			varchar (255),
	lan_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_langue
CREATE TABLE cms_langue
(
	lan_id			number (11) constraint lan_pk PRIMARY KEY not null,
	lan_libelle			varchar2 (255),
	lan_libellecourt			varchar2 (255),
	lan_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_langue" libelle="Langue" prefix="lan" display="libelle" abstract="libellecourt">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assolanguesite" />
<item name="libelle" libelle="Libelle" type="varchar" length="255" list="true" order="true"  nohtml="true" />
<item name="libellecourt" libelle="Raccourci" type="varchar" length="255" list="true" order="true"  nohtml="true" />
<item name="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
<langpack lang="fr">
<norecords>Pas de langue à afficher</norecords>
</langpack>
</class>


==========================================*/

class cms_langue
{
var $id;
var $libelle;
var $libellecourt;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_langue\" libelle=\"Langue\" prefix=\"lan\" display=\"libelle\" abstract=\"libellecourt\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assolanguesite\" />
<item name=\"libelle\" libelle=\"Libelle\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"libellecourt\" libelle=\"Raccourci\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
<langpack lang=\"fr\">
<norecords>Pas de langue à afficher</norecords>
</langpack>
</class>";

var $sMySql = "CREATE TABLE cms_langue
(
	lan_id			int (11) PRIMARY KEY not null,
	lan_libelle			varchar (255),
	lan_libellecourt			varchar (255),
	lan_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_langue") == false){
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
		$this->libellecourt = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Lan_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Lan_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Lan_libellecourt", "text", "get_libellecourt", "set_libellecourt");
	$laListeChamps[]=new dbChamp("Lan_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_libellecourt() { return($this->libellecourt); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_lan_id) { return($this->id=$c_lan_id); }
function set_libelle($c_lan_libelle) { return($this->libelle=$c_lan_libelle); }
function set_libellecourt($c_lan_libellecourt) { return($this->libellecourt=$c_lan_libellecourt); }
function set_statut($c_lan_statut) { return($this->statut=$c_lan_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("lan_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("lan_statut"); }
//
function getTable() { return("cms_langue"); }
function getClasse() { return("cms_langue"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("libellecourt"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue/list_cms_langue.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue/maj_cms_langue.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue/show_cms_langue.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue/rss_cms_langue.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue/xml_cms_langue.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue/export_cms_langue.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_langue/import_cms_langue.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>