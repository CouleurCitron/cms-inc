<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_mapskey :: class cms_mapskey

SQL mySQL:

DROP TABLE IF EXISTS cms_mapskey;
CREATE TABLE cms_mapskey
(
	key_id			int (11) PRIMARY KEY not null,
	key_host			varchar (256),
	key_key			varchar (256)
)

SQL Oracle:

DROP TABLE cms_mapskey
CREATE TABLE cms_mapskey
(
	key_id			number (11) constraint key_pk PRIMARY KEY not null,
	key_host			varchar2 (256),
	key_key			varchar2 (256)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_mapskey" libelle="API Key Google Map" prefix="key" display="host" abstract="key">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="host" libelle="Nom d'h�te" type="varchar" length="256" list="true" order="true"  /> 
<item name="key" libelle="API Key" type="varchar" length="256" list="true" order="true"  /> 
</class> 


==========================================*/

class cms_mapskey
{
var $id;
var $host;
var $key;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_mapskey\" libelle=\"API Key Google Map\" prefix=\"key\" display=\"host\" abstract=\"key\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"host\" libelle=\"Nom d\'h�te\" type=\"varchar\" length=\"256\" list=\"true\" order=\"true\"  /> 
<item name=\"key\" libelle=\"API Key\" type=\"varchar\" length=\"256\" list=\"true\" order=\"true\"  /> 
</class> ";

var $sMySql = "CREATE TABLE cms_mapskey
(
	key_id			int (11) PRIMARY KEY not null,
	key_host			varchar (256),
	key_key			varchar (256)
)

";

// constructeur
function cms_mapskey($id=null)
{
	if (istable("cms_mapskey") == false){
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
		$this->host = "";
		$this->key = "";
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de donn�es pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Key_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Key_host", "text", "get_host", "set_host");
	$laListeChamps[]=new dbChamp("Key_key", "text", "get_key", "set_key");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_host() { return($this->host); }
function get_key() { return($this->key); }


// setters
function set_id($c_key_id) { return($this->id=$c_key_id); }
function set_host($c_key_host) { return($this->host=$c_key_host); }
function set_key($c_key_key) { return($this->key=$c_key_key); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("key_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_mapskey"); }
function getClasse() { return("cms_mapskey"); }
function getDisplay() { return("host"); }
function getAbstract() { return("key"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey/list_cms_mapskey.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey/maj_cms_mapskey.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey/show_cms_mapskey.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey/rss_cms_mapskey.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey/xml_cms_mapskey.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey/export_cms_mapskey.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_mapskey/import_cms_mapskey.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>