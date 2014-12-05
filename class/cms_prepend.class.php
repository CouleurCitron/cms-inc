<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_prepend :: class cms_prepend

SQL mySQL:

DROP TABLE IF EXISTS cms_prepend;
CREATE TABLE cms_prepend
(
	ppd_id			int (11) PRIMARY KEY not null,
	ppd_libelle			varchar (255),
	ppd_descriptif			varchar (512),
	ppd_fichier			varchar (255),
	ppd_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_prepend
CREATE TABLE cms_prepend
(
	ppd_id			number (11) constraint ppd_pk PRIMARY KEY not null,
	ppd_libelle			varchar2 (255),
	ppd_descriptif			varchar2 (512),
	ppd_fichier			varchar2 (255),
	ppd_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_prepend" libelle="Scripts preprend" prefix="ppd" display="libelle" abstract="fichier">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assoprependarbopages,cms_assoprependcmssite" />
<item name="libelle" libelle="libellé" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="descriptif" libelle="descriptif" type="varchar" length="512" list="false" order="false" />
<item name="fichier" libelle="fichier" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
<langpack lang="fr">
<norecords>Pas de Script preprend à afficher</norecords>
</langpack>
</class>


==========================================*/

class cms_prepend
{
var $id;
var $libelle;
var $descriptif;
var $fichier;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_prepend\" libelle=\"Scripts preprend\" prefix=\"ppd\" display=\"libelle\" abstract=\"fichier\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assoprependarbopages,cms_assoprependcmssite\" />
<item name=\"libelle\" libelle=\"libellé\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"descriptif\" libelle=\"descriptif\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" />
<item name=\"fichier\" libelle=\"fichier\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
<langpack lang=\"fr\">
<norecords>Pas de Script preprend à afficher</norecords>
</langpack>
</class>";

var $sMySql = "CREATE TABLE cms_prepend
(
	ppd_id			int (11) PRIMARY KEY not null,
	ppd_libelle			varchar (255),
	ppd_descriptif			varchar (512),
	ppd_fichier			varchar (255),
	ppd_statut			int (11) not null
)

";

// constructeur
function cms_prepend($id=null)
{
	if (istable("cms_prepend") == false){
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
		$this->descriptif = "";
		$this->fichier = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Ppd_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Ppd_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Ppd_descriptif", "text", "get_descriptif", "set_descriptif");
	$laListeChamps[]=new dbChamp("Ppd_fichier", "text", "get_fichier", "set_fichier");
	$laListeChamps[]=new dbChamp("Ppd_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_descriptif() { return($this->descriptif); }
function get_fichier() { return($this->fichier); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_ppd_id) { return($this->id=$c_ppd_id); }
function set_libelle($c_ppd_libelle) { return($this->libelle=$c_ppd_libelle); }
function set_descriptif($c_ppd_descriptif) { return($this->descriptif=$c_ppd_descriptif); }
function set_fichier($c_ppd_fichier) { return($this->fichier=$c_ppd_fichier); }
function set_statut($c_ppd_statut) { return($this->statut=$c_ppd_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("ppd_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("ppd_statut"); }
//
function getTable() { return("cms_prepend"); }
function getClasse() { return("cms_prepend"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("fichier"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/list_cms_prepend.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/maj_cms_prepend.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/show_cms_prepend.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/rss_cms_prepend.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/xml_cms_prepend.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/xmlxls_cms_prepend.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/export_cms_prepend.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/import_cms_prepend.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>