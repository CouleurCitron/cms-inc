<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assojscmssite.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_assojscmssite.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assojscmssite.class.php');
}else{
/*======================================

objet de BDD cms_assojscmssite :: class cms_assojscmssite

SQL mySQL:

DROP TABLE IF EXISTS cms_assojscmssite;
CREATE TABLE cms_assojscmssite
(
	xjs_id			int (11) PRIMARY KEY not null,
	xjs_cms_js			int,
	xjs_cms_site			int
)

SQL Oracle:

DROP TABLE cms_assojscmssite
CREATE TABLE cms_assojscmssite
(
	xjs_id			number (11) constraint xjs_pk PRIMARY KEY not null,
	xjs_cms_js			number,
	xjs_cms_site			number
)


<?xml version="1.0" encoding="iso-8859-1"?>
<class name="cms_assojscmssite" is_asso="true" libelle="Asso JS script et site" prefix="xjs" display="cms_js" abstract="cms_site">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_js" libelle="JS script" type="int" default="0" order="true" list="true" fkey="cms_js" />
<item name="cms_site" libelle="Site" type="int" default="0" order="true" list="true" fkey="cms_site" />
</class>


==========================================*/

class cms_assojscmssite
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $cms_js;
var $cms_site;


var $XML = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<class name=\"cms_assojscmssite\" is_asso=\"true\" libelle=\"Asso JS script et site\" prefix=\"xjs\" display=\"cms_js\" abstract=\"cms_site\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_js\" libelle=\"JS script\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_js\" />
<item name=\"cms_site\" libelle=\"Site\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_site\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_assojscmssite
(
	xjs_id			int (11) PRIMARY KEY not null,
	xjs_cms_js			int,
	xjs_cms_site			int
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
		$this->cms_js = -1;
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
	$laListeChamps[]=new dbChamp("Xjs_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xjs_cms_js", "entier", "get_cms_js", "set_cms_js");
	$laListeChamps[]=new dbChamp("Xjs_cms_site", "entier", "get_cms_site", "set_cms_site");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_js() { return($this->cms_js); }
function get_cms_site() { return($this->cms_site); }


// setters
function set_id($c_xjs_id) { return($this->id=$c_xjs_id); }
function set_cms_js($c_xjs_cms_js) { return($this->cms_js=$c_xjs_cms_js); }
function set_cms_site($c_xjs_cms_site) { return($this->cms_site=$c_xjs_cms_site); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xjs_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assojscmssite"); }
function getClasse() { return("cms_assojscmssite"); }
function getDisplay() { return("cms_js"); }
function getAbstract() { return("cms_site"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite/list_cms_assojscmssite.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite/maj_cms_assojscmssite.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite/show_cms_assojscmssite.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite/rss_cms_assojscmssite.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite/xml_cms_assojscmssite.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite/xmlxls_cms_assojscmssite.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite/export_cms_assojscmssite.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assojscmssite/import_cms_assojscmssite.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>