<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_sonde.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_sonde.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_sonde.class.php');
}else{
/*======================================

objet de BDD cms_sonde :: class cms_sonde

SQL mySQL:

DROP TABLE IF EXISTS cms_sonde;
CREATE TABLE cms_sonde
(
	cms_id			int (4) PRIMARY KEY not null,
	cms_nom			varchar (256) not null,
	cms_sondeurl			varchar (512) not null,
	cms_bo_users			int (11) not null,
	cms_email			varchar (128) not null,
	cms_lastres			int (2),
	cms_lastrun			datetime,
	cms_cdate			datetime not null,
	cms_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	cms_statut			int (2) not null
)

SQL Oracle:

DROP TABLE cms_sonde
CREATE TABLE cms_sonde
(
	cms_id			number (4) constraint cms_pk PRIMARY KEY not null,
	cms_nom			varchar2 (256) not null,
	cms_sondeurl			varchar2 (512) not null,
	cms_bo_users			number (11) not null,
	cms_email			varchar2 (128) not null,
	cms_lastres			number (2),
	cms_lastrun			datetime,
	cms_cdate			datetime not null,
	cms_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	cms_statut			number (2) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_sonde" libelle="Sondes de services" prefix="cms" display="nom" abstract="lastres">
<item name="id" type="int" length="4" isprimary="true" notnull="true" default="-1" list="true" /> 

<item name="nom" libelle="Nom" type="varchar" length="256" notnull="true" default="" list="true" order="true" nohtml="true" />

<item name="sondeurl" libelle="URL de la sonde" type="varchar" length="512" notnull="true" default="" list="true" order="true" option="url" />

<item name="bo_users" libelle="Utilisateur" type="int"  length="11" notnull="true"  list="true" fkey="bo_users" />

<item name="email" libelle="Email" type="varchar" length="128" notnull="true" default="" list="true" order="true" option="email" />

<item name="lastres" libelle="Dernier résultat" type="int" length="2" list="true" order="true" option="bool" />

<item name="lastrun" libelle="Dernière exécution" type="datetime" list="true" order="true" default="1970-01-01 00:00:00" />

<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="true" order="true" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="true" order="true" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
</class>


==========================================*/

class cms_sonde
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $nom;
var $sondeurl;
var $bo_users;
var $email;
var $lastres;
var $lastrun;
var $cdate;
var $mdate;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_sonde\" libelle=\"Sondes de services\" prefix=\"cms\" display=\"nom\" abstract=\"lastres\">
<item name=\"id\" type=\"int\" length=\"4\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 

<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />

<item name=\"sondeurl\" libelle=\"URL de la sonde\" type=\"varchar\" length=\"512\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" option=\"url\" />

<item name=\"bo_users\" libelle=\"Utilisateur\" type=\"int\"  length=\"11\" notnull=\"true\"  list=\"true\" fkey=\"bo_users\" />

<item name=\"email\" libelle=\"Email\" type=\"varchar\" length=\"128\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" option=\"email\" />

<item name=\"lastres\" libelle=\"Dernier résultat\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" option=\"bool\" />

<item name=\"lastrun\" libelle=\"Dernière exécution\" type=\"datetime\" list=\"true\" order=\"true\" default=\"1970-01-01 00:00:00\" />

<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"true\" order=\"true\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"true\" order=\"true\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_sonde
(
	cms_id			int (4) PRIMARY KEY not null,
	cms_nom			varchar (256) not null,
	cms_sondeurl			varchar (512) not null,
	cms_bo_users			int (11) not null,
	cms_email			varchar (128) not null,
	cms_lastres			int (2),
	cms_lastrun			datetime,
	cms_cdate			datetime not null,
	cms_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	cms_statut			int (2) not null
)

";

// constructeur
function cms_sonde($id=null)
{
	if (istable(get_class($this)) == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
		if(array_key_exists('0',$this->inherited_list)){
			foreach($this->inherited_list as $class){
				if(!is_object($class))
					$this->inherited[$class] = dbGetObjectFromPK($class, $id);
			}
		}
	} else {
		$this->id = -1;
		$this->nom = "";
		$this->sondeurl = "";
		$this->bo_users = -1;
		$this->email = "";
		$this->lastres = -1;
		$this->lastrun = '1970-01-01 00:00:00';
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		if(array_key_exists('0',$this->inherited_list)){
			foreach($this->inherited_list as $class){
				if(!is_object($class))
					$this->inherited[$class] = new $class();
			}
		}
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Cms_sondeurl", "text", "get_sondeurl", "set_sondeurl");
	$laListeChamps[]=new dbChamp("Cms_bo_users", "entier", "get_bo_users", "set_bo_users");
	$laListeChamps[]=new dbChamp("Cms_email", "text", "get_email", "set_email");
	$laListeChamps[]=new dbChamp("Cms_lastres", "entier", "get_lastres", "set_lastres");
	$laListeChamps[]=new dbChamp("Cms_lastrun", "date_formatee_timestamp", "get_lastrun", "set_lastrun");
	$laListeChamps[]=new dbChamp("Cms_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Cms_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_sondeurl() { return($this->sondeurl); }
function get_bo_users() { return($this->bo_users); }
function get_email() { return($this->email); }
function get_lastres() { return($this->lastres); }
function get_lastrun() { return($this->lastrun); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_nom($c_cms_nom) { return($this->nom=$c_cms_nom); }
function set_sondeurl($c_cms_sondeurl) { return($this->sondeurl=$c_cms_sondeurl); }
function set_bo_users($c_cms_bo_users) { return($this->bo_users=$c_cms_bo_users); }
function set_email($c_cms_email) { return($this->email=$c_cms_email); }
function set_lastres($c_cms_lastres) { return($this->lastres=$c_cms_lastres); }
function set_lastrun($c_cms_lastrun) { return($this->lastrun=$c_cms_lastrun); }
function set_cdate($c_cms_cdate) { return($this->cdate=$c_cms_cdate); }
function set_mdate($c_cms_mdate) { return($this->mdate=$c_cms_mdate); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_sonde"); }
function getClasse() { return("cms_sonde"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("lastres"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde/list_cms_sonde.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde/maj_cms_sonde.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde/show_cms_sonde.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde/rss_cms_sonde.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde/xml_cms_sonde.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde/xmlxls_cms_sonde.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde/export_cms_sonde.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_sonde/import_cms_sonde.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>