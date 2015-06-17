<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('cms_filemanageruser')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_filemanageruser`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('fmu_chroot', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_filemanageruser` ADD `fmu_chroot` INT( 2 ) NOT NULL AFTER `fmu_passwd` ;");
		}
	}
}
/* [End patch] */

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_filemanageruser.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_filemanageruser.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_filemanageruser.class.php');
}else{
/*======================================

objet de BDD cms_filemanageruser :: class cms_filemanageruser

SQL mySQL:

DROP TABLE IF EXISTS cms_filemanageruser;
CREATE TABLE cms_filemanageruser
(
	fmu_id			int (11) PRIMARY KEY not null,
	fmu_login			varchar (255),
	fmu_passwd			varchar (255),
	fmu_chroot			int (2),
	fmu_cms_filemanager			int (11) not null,
	fmu_dtcrea			date,
	fmu_dtmod			date,
	fmu_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_filemanageruser
CREATE TABLE cms_filemanageruser
(
	fmu_id			number (11) constraint fmu_pk PRIMARY KEY not null,
	fmu_login			varchar2 (255),
	fmu_passwd			varchar2 (255),
	fmu_chroot			number (2),
	fmu_cms_filemanager			number (11) not null,
	fmu_dtcrea			date,
	fmu_dtmod			date,
	fmu_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_filemanageruser" libelle="Comptes gestionnaire de fichiers" prefix="fmu" display="login" abstract="cms_filemanager">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />

<item name="login" libelle="Login" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="passwd" libelle="Mot de passe" type="varchar" length="255" nohtml="true" option="password"/>

<item name="chroot" libelle="Accès restreint" type="int" length="2" list="true" order="true" option="bool" default="1" />

<item name="cms_filemanager" libelle="Gestionnaire de fichiers" type="int" length="11" notnull="true" default="-1" list="true" order="true" fkey="cms_filemanager" />

<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 

<langpack lang="fr">
<norecords>Pas de compte de gestionnaire de fichiers à afficher</norecords>
</langpack>
</class> 


==========================================*/

class cms_filemanageruser
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $login;
var $passwd;
var $chroot;
var $cms_filemanager;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_filemanageruser\" libelle=\"Comptes gestionnaire de fichiers\" prefix=\"fmu\" display=\"login\" abstract=\"cms_filemanager\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />

<item name=\"login\" libelle=\"Login\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"passwd\" libelle=\"Mot de passe\" type=\"varchar\" length=\"255\" nohtml=\"true\" option=\"password\"/>

<item name=\"chroot\" libelle=\"Accès restreint\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" option=\"bool\" default=\"1\" />

<item name=\"cms_filemanager\" libelle=\"Gestionnaire de fichiers\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_filemanager\" />

<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 

<langpack lang=\"fr\">
<norecords>Pas de compte de gestionnaire de fichiers à afficher</norecords>
</langpack>
</class> ";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_filemanageruser
(
	fmu_id			int (11) PRIMARY KEY not null,
	fmu_login			varchar (255),
	fmu_passwd			varchar (255),
	fmu_chroot			int (2),
	fmu_cms_filemanager			int (11) not null,
	fmu_dtcrea			date,
	fmu_dtmod			date,
	fmu_statut			int (11) not null
)

";

// constructeur
function cms_filemanageruser($id=null)
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
		$this->login = "";
		$this->passwd = "";
		$this->chroot = 1;
		$this->cms_filemanager = -1;
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
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
	$laListeChamps[]=new dbChamp("Fmu_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Fmu_login", "text", "get_login", "set_login");
	$laListeChamps[]=new dbChamp("Fmu_passwd", "text", "get_passwd", "set_passwd");
	$laListeChamps[]=new dbChamp("Fmu_chroot", "entier", "get_chroot", "set_chroot");
	$laListeChamps[]=new dbChamp("Fmu_cms_filemanager", "entier", "get_cms_filemanager", "set_cms_filemanager");
	$laListeChamps[]=new dbChamp("Fmu_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Fmu_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Fmu_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_login() { return($this->login); }
function get_passwd() { return($this->passwd); }
function get_chroot() { return($this->chroot); }
function get_cms_filemanager() { return($this->cms_filemanager); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_fmu_id) { return($this->id=$c_fmu_id); }
function set_login($c_fmu_login) { return($this->login=$c_fmu_login); }
function set_passwd($c_fmu_passwd) { return($this->passwd=$c_fmu_passwd); }
function set_chroot($c_fmu_chroot) { return($this->chroot=$c_fmu_chroot); }
function set_cms_filemanager($c_fmu_cms_filemanager) { return($this->cms_filemanager=$c_fmu_cms_filemanager); }
function set_dtcrea($c_fmu_dtcrea) { return($this->dtcrea=$c_fmu_dtcrea); }
function set_dtmod($c_fmu_dtmod) { return($this->dtmod=$c_fmu_dtmod); }
function set_statut($c_fmu_statut) { return($this->statut=$c_fmu_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("fmu_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("fmu_statut"); }
//
function getTable() { return("cms_filemanageruser"); }
function getClasse() { return("cms_filemanageruser"); }
function getDisplay() { return("login"); }
function getAbstract() { return("cms_filemanager"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser/list_cms_filemanageruser.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser/maj_cms_filemanageruser.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser/show_cms_filemanageruser.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser/rss_cms_filemanageruser.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser/xml_cms_filemanageruser.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser/xmlxls_cms_filemanageruser.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser/export_cms_filemanageruser.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_filemanageruser/import_cms_filemanageruser.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>