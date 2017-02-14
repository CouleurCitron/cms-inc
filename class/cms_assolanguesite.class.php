<?php
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assolanguesite.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_assolanguesite.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assolanguesite.class.php');
}else{
/*======================================

objet de BDD cms_assolanguesite :: class cms_assolanguesite

SQL mySQL:

DROP TABLE IF EXISTS cms_assolanguesite;
CREATE TABLE cms_assolanguesite
(
	xls_id			int (11) PRIMARY KEY not null,
	xls_langue			int,
	xls_site			int
)

SQL Oracle:

DROP TABLE cms_assolanguesite
CREATE TABLE cms_assolanguesite
(
	xls_id			number (11) constraint xls_pk PRIMARY KEY not null,
	xls_langue			number,
	xls_site			number
)


<?xml version="1.0" encoding="iso-8859-1"?>
<class name="cms_assolanguesite" is_asso="true" libelle="Asso langues et sites" prefix="xls" display="cms_langue" abstract="cms_site">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="langue" libelle="Langue" type="int" default="0" order="true" list="true" fkey="cms_langue" />
<item name="site" libelle="Site" type="int" default="0" order="true" list="true" fkey="cms_site" />
</class>


==========================================*/

class cms_assolanguesite
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $langue;
var $site;


var $XML = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<class name=\"cms_assolanguesite\" is_asso=\"true\" libelle=\"Asso langues et sites\" prefix=\"xls\" display=\"cms_langue\" abstract=\"cms_site\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"langue\" libelle=\"Langue\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_langue\" />
<item name=\"site\" libelle=\"Site\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_site\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_assolanguesite
(
	xls_id			int (11) PRIMARY KEY not null,
	xls_langue			int,
	xls_site			int
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
		$this->langue = -1;
		$this->site = -1;
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
	$laListeChamps[]=new dbChamp("Xls_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xls_langue", "entier", "get_langue", "set_langue");
	$laListeChamps[]=new dbChamp("Xls_site", "entier", "get_site", "set_site");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_langue() { return($this->langue); }
function get_site() { return($this->site); }


// setters
function set_id($c_xls_id) { return($this->id=$c_xls_id); }
function set_langue($c_xls_langue) { return($this->langue=$c_xls_langue); }
function set_site($c_xls_site) { return($this->site=$c_xls_site); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xls_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assolanguesite"); }
function getClasse() { return("cms_assolanguesite"); }
function getPrefix() { return(""); }
function getDisplay() { return("cms_langue"); }
function getAbstract() { return("cms_site"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite/list_cms_assolanguesite.php", "w");
	$listContent = "<"."?php
include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite/maj_cms_assolanguesite.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite/show_cms_assolanguesite.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite/rss_cms_assolanguesite.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite/xml_cms_assolanguesite.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite/xlsx_cms_assolanguesite.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assolanguesite/import_cms_assolanguesite.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>