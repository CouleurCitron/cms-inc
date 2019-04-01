<?php
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assoprependcmssite.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_assoprependcmssite.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assoprependcmssite.class.php');
}else{
/*======================================

objet de BDD cms_assoprependcmssite :: class cms_assoprependcmssite

SQL mySQL:

DROP TABLE IF EXISTS cms_assoprependcmssite;
CREATE TABLE cms_assoprependcmssite
(
	xps_id			int (11) PRIMARY KEY not null,
	xps_cms_prepend			int,
	xps_cms_site			int
)

SQL Oracle:

DROP TABLE cms_assoprependcmssite
CREATE TABLE cms_assoprependcmssite
(
	xps_id			number (11) constraint xps_pk PRIMARY KEY not null,
	xps_cms_prepend			number,
	xps_cms_site			number
)


<?xml version="1.0" encoding="iso-8859-1"?>
<class name="cms_assoprependcmssite" is_asso="true" libelle="Asso Prepend scripts et site" prefix="xps" display="cms_prepend" abstract="cms_site">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_prepend" libelle="Prepend script" type="int" default="0" order="true" list="true" fkey="cms_prepend" />
<item name="cms_site" libelle="Site" type="int" default="0" order="true" list="true" fkey="cms_site" />
</class>


==========================================*/

class cms_assoprependcmssite
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $cms_prepend;
var $cms_site;


var $XML = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<class name=\"cms_assoprependcmssite\" is_asso=\"true\" libelle=\"Asso Prepend scripts et site\" prefix=\"xps\" display=\"cms_prepend\" abstract=\"cms_site\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_prepend\" libelle=\"Prepend script\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_prepend\" />
<item name=\"cms_site\" libelle=\"Site\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_site\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_assoprependcmssite
(
	xps_id			int (11) PRIMARY KEY not null,
	xps_cms_prepend			int,
	xps_cms_site			int
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
		$this->cms_prepend = -1;
		$this->cms_site = -1;
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
	$laListeChamps[]=new dbChamp("Xps_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xps_cms_prepend", "entier", "get_cms_prepend", "set_cms_prepend");
	$laListeChamps[]=new dbChamp("Xps_cms_site", "entier", "get_cms_site", "set_cms_site");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_prepend() { return($this->cms_prepend); }
function get_cms_site() { return($this->cms_site); }


// setters
function set_id($c_xps_id) { return($this->id=$c_xps_id); }
function set_cms_prepend($c_xps_cms_prepend) { return($this->cms_prepend=$c_xps_cms_prepend); }
function set_cms_site($c_xps_cms_site) { return($this->cms_site=$c_xps_cms_site); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xps_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assoprependcmssite"); }
function getClasse() { return("cms_assoprependcmssite"); }
function getPrefix() { return(""); }
function getDisplay() { return("cms_prepend"); }
function getAbstract() { return("cms_site"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite/list_cms_assoprependcmssite.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite/maj_cms_assoprependcmssite.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite/show_cms_assoprependcmssite.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite/rss_cms_assoprependcmssite.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite/xml_cms_assoprependcmssite.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite/xlsx_cms_assoprependcmssite.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite/export_cms_assoprependcmssite.php", "w");
	$exportContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependcmssite/import_cms_assoprependcmssite.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>