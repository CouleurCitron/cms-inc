<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_minisite.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_minisite.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_minisite.class.php');
}else{
/*======================================

objet de BDD cms_minisite :: class cms_minisite

SQL mySQL:

DROP TABLE IF EXISTS cms_minisite;
CREATE TABLE cms_minisite
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_name			varchar (255),
	cms_desc			varchar (1024),
	cms_url			varchar (512),
	cms_site			int (11),
	cms_theme			int (11),
	cms_node			int (11),
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_minisite
CREATE TABLE cms_minisite
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_name			varchar2 (255),
	cms_desc			varchar2 (1024),
	cms_url			varchar2 (512),
	cms_site			number (11),
	cms_theme			number (11),
	cms_node			number (11),
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_minisite" libelle="Minisites" prefix="cms" display="name" abstract="site">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true"  skip="true"/>
<item name="name" libelle="Nom du minisite" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="desc" libelle="Description" type="varchar" length="1024" list="false" order="true" option="textarea"/>
<item name="url" libelle="URL" type="varchar" length="512" list="true" order="true"  nohtml="true" />
<item name="site" libelle="Site maître" type="int" length="11" default="-1" list="true" order="true" fkey="cms_site"  />

<item name="theme" libelle="Thème" type="int" length="11" default="-1" list="true" order="true" fkey="cms_theme"  />

<item name="node" libelle="Noeud racine" type="int" length="11" list="true" order="true" option="node"  noedit="true"/>

<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_ID_STATUT_LIGNE" list="true" order="true"  /> 

</class>


==========================================*/

class cms_minisite
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $name;
var $desc;
var $url;
var $site;
var $theme;
var $node;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_minisite\" libelle=\"Minisites\" prefix=\"cms\" display=\"name\" abstract=\"site\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" skip=\"true\" />
<item name=\"name\" libelle=\"Nom du minisite\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\"  />
<item name=\"desc\" libelle=\"Description\" type=\"varchar\" length=\"1024\" list=\"false\" order=\"true\" option=\"textarea\"/>
<item name=\"url\" libelle=\"URL\" type=\"varchar\" length=\"512\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"site\" libelle=\"Site maître\" type=\"int\" length=\"11\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_site\"  />

<item name=\"theme\" libelle=\"Thème\" type=\"int\" length=\"11\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_theme\"  />

<item name=\"node\" libelle=\"Noeud racine\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" option=\"node\"  noedit=\"true\" />

<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_ID_STATUT_LIGNE\" list=\"true\" order=\"true\"   /> 

</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_minisite
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_name			varchar (255),
	cms_desc			varchar (1024),
	cms_url			varchar (512),
	cms_site			int (11),
	cms_theme			int (11),
	cms_node			int (11),
	cms_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
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
		$this->name = "";
		$this->desc = "";
		$this->url = "";
		$this->site = -1;
		$this->theme = -1;
		$this->node = -1;
		$this->statut = DEF_ID_STATUT_LIGNE;
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
	$laListeChamps[]=new dbChamp("Cms_name", "text", "get_name", "set_name");
	$laListeChamps[]=new dbChamp("Cms_desc", "text", "get_desc", "set_desc");
	$laListeChamps[]=new dbChamp("Cms_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Cms_site", "entier", "get_site", "set_site");
	$laListeChamps[]=new dbChamp("Cms_theme", "entier", "get_theme", "set_theme");
	$laListeChamps[]=new dbChamp("Cms_node", "entier", "get_node", "set_node");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_name() { return($this->name); }
function get_desc() { return($this->desc); }
function get_url() { return($this->url); }
function get_site() { return($this->site); }
function get_theme() { return($this->theme); }
function get_node() { return($this->node); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_name($c_cms_name) { return($this->name=$c_cms_name); }
function set_desc($c_cms_desc) { return($this->desc=$c_cms_desc); }
function set_url($c_cms_url) { return($this->url=$c_cms_url); }
function set_site($c_cms_site) { return($this->site=$c_cms_site); }
function set_theme($c_cms_theme) { return($this->theme=$c_cms_theme); }
function set_node($c_cms_node) { return($this->node=$c_cms_node); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_minisite"); }
function getClasse() { return("cms_minisite"); }
function getDisplay() { return("name"); }
function getAbstract() { return("site"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite/list_cms_minisite.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite/maj_cms_minisite.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite/show_cms_minisite.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite/rss_cms_minisite.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite/xml_cms_minisite.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite/xlsx_cms_minisite.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite/export_cms_minisite.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_minisite/import_cms_minisite.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
}
?>