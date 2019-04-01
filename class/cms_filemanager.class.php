<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_filemanager :: class cms_filemanager

SQL mySQL:

DROP TABLE IF EXISTS cms_filemanager;
CREATE TABLE cms_filemanager
(
	fma_id			int (11) PRIMARY KEY not null,
	fma_nom			varchar (64),
	fma_cms_site			int (11) not null,
	fma_login			varchar (255),
	fma_passwd			varchar (255),
	fma_url			varchar (255),
	fma_dtcrea			date,
	fma_dtmod			date,
	fma_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_filemanager
CREATE TABLE cms_filemanager
(
	fma_id			number (11) constraint fma_pk PRIMARY KEY not null,
	fma_nom			varchar2 (64),
	fma_cms_site			number (11) not null,
	fma_login			varchar2 (255),
	fma_passwd			varchar2 (255),
	fma_url			varchar2 (255),
	fma_dtcrea			date,
	fma_dtmod			date,
	fma_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_filemanager" libelle="Gestionnaire de fichiers" prefix="fma" display="nom" abstract="cms_site">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="nom" libelle="Nom" type="varchar" length="64" list="true" order="true" />
<item name="cms_site" libelle="Mini site" type="int" length="11" notnull="true" default="-1" list="true" order="true" fkey="cms_site" />
<item name="login" libelle="Login" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="passwd" libelle="Mot de passe" type="varchar" length="255" nohtml="true" />
<item name="url" libelle="url" type="varchar" length="255" list="true" order="true" option="link" />

<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 

<langpack lang="fr">
<norecords>Pas de gestionnaire de fichiers à afficher</norecords>
</langpack>
</class> 


==========================================*/

class cms_filemanager
{
var $id;
var $nom;
var $cms_site;
var $login;
var $passwd;
var $url;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_filemanager\" libelle=\"Gestionnaire de fichiers\" prefix=\"fma\" display=\"nom\" abstract=\"cms_site\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"64\" list=\"true\" order=\"true\" />
<item name=\"cms_site\" libelle=\"Mini site\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_site\" />
<item name=\"login\" libelle=\"Login\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"passwd\" libelle=\"Mot de passe\" type=\"varchar\" length=\"255\" nohtml=\"true\" />
<item name=\"url\" libelle=\"url\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" option=\"link\" />

<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 

<langpack lang=\"fr\">
<norecords>Pas de gestionnaire de fichiers à afficher</norecords>
</langpack>
</class> ";

var $sMySql = "CREATE TABLE cms_filemanager
(
	fma_id			int (11) PRIMARY KEY not null,
	fma_nom			varchar (64),
	fma_cms_site			int (11) not null,
	fma_login			varchar (255),
	fma_passwd			varchar (255),
	fma_url			varchar (255),
	fma_dtcrea			date,
	fma_dtmod			date,
	fma_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_filemanager") == false){
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
		$this->cms_site = -1;
		$this->login = "";
		$this->passwd = "";
		$this->url = "";
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Fma_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Fma_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Fma_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Fma_login", "text", "get_login", "set_login");
	$laListeChamps[]=new dbChamp("Fma_passwd", "text", "get_passwd", "set_passwd");
	$laListeChamps[]=new dbChamp("Fma_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Fma_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Fma_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Fma_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_cms_site() { return($this->cms_site); }
function get_login() { return($this->login); }
function get_passwd() { return($this->passwd); }
function get_url() { return($this->url); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_fma_id) { return($this->id=$c_fma_id); }
function set_nom($c_fma_nom) { return($this->nom=$c_fma_nom); }
function set_cms_site($c_fma_cms_site) { return($this->cms_site=$c_fma_cms_site); }
function set_login($c_fma_login) { return($this->login=$c_fma_login); }
function set_passwd($c_fma_passwd) { return($this->passwd=$c_fma_passwd); }
function set_url($c_fma_url) { return($this->url=$c_fma_url); }
function set_dtcrea($c_fma_dtcrea) { return($this->dtcrea=$c_fma_dtcrea); }
function set_dtmod($c_fma_dtmod) { return($this->dtmod=$c_fma_dtmod); }
function set_statut($c_fma_statut) { return($this->statut=$c_fma_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("fma_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("fma_statut"); }
//
function getTable() { return("cms_filemanager"); }
function getClasse() { return("cms_filemanager"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("cms_site"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager/list_cms_filemanager.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager/maj_cms_filemanager.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager/show_cms_filemanager.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager/rss_cms_filemanager.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager/xml_cms_filemanager.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager/export_cms_filemanager.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanager/import_cms_filemanager.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>