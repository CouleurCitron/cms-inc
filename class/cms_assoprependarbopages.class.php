<?php

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assoprependarbopages.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_assoprependarbopages.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assoprependarbopages.class.php');
}else{
/*======================================

objet de BDD cms_assoprependarbopages :: class cms_assoprependarbopages

SQL mySQL:

DROP TABLE IF EXISTS cms_assoprependarbopages;
CREATE TABLE cms_assoprependarbopages
(
	xpa_id			int (11) PRIMARY KEY not null,
	xpa_cms_prepend			int,
	xpa_cms_arbo_pages			int
)

SQL Oracle:

DROP TABLE cms_assoprependarbopages
CREATE TABLE cms_assoprependarbopages
(
	xpa_id			number (11) constraint xpa_pk PRIMARY KEY not null,
	xpa_cms_prepend			number,
	xpa_cms_arbo_pages			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assoprependarbopages" is_asso="true" libelle="Asso Prepend script et arbo" prefix="xpa" display="cms_prepend" abstract="cms_arbo_pages">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_prepend" libelle="Prepend script" type="int" default="0" order="true" list="true" fkey="cms_prepend" />
<item name="cms_arbo_pages" libelle="Arbo Node" type="int" default="0" order="true" list="true" fkey="cms_arbo_pages" />
</class>


==========================================*/

class cms_assoprependarbopages
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $cms_prepend;
var $cms_arbo_pages;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assoprependarbopages\" is_asso=\"true\" libelle=\"Asso Prepend script et arbo\" prefix=\"xpa\" display=\"cms_prepend\" abstract=\"cms_arbo_pages\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_prepend\" libelle=\"Prepend script\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_prepend\" />
<item name=\"cms_arbo_pages\" libelle=\"Arbo Node\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_arbo_pages\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_assoprependarbopages
(
	xpa_id			int (11) PRIMARY KEY not null,
	xpa_cms_prepend			int,
	xpa_cms_arbo_pages			int
)

";

// constructeur
function cms_assoprependarbopages($id=null)
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
		$this->cms_arbo_pages = -1;
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
	$laListeChamps[]=new dbChamp("Xpa_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xpa_cms_prepend", "entier", "get_cms_prepend", "set_cms_prepend");
	$laListeChamps[]=new dbChamp("Xpa_cms_arbo_pages", "entier", "get_cms_arbo_pages", "set_cms_arbo_pages");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_prepend() { return($this->cms_prepend); }
function get_cms_arbo_pages() { return($this->cms_arbo_pages); }


// setters
function set_id($c_xpa_id) { return($this->id=$c_xpa_id); }
function set_cms_prepend($c_xpa_cms_prepend) { return($this->cms_prepend=$c_xpa_cms_prepend); }
function set_cms_arbo_pages($c_xpa_cms_arbo_pages) { return($this->cms_arbo_pages=$c_xpa_cms_arbo_pages); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xpa_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assoprependarbopages"); }
function getClasse() { return("cms_assoprependarbopages"); }
function getPrefix() { return(""); }
function getDisplay() { return("cms_prepend"); }
function getAbstract() { return("cms_arbo_pages"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages/list_cms_assoprependarbopages.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages/maj_cms_assoprependarbopages.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages/show_cms_assoprependarbopages.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages/rss_cms_assoprependarbopages.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages/xml_cms_assoprependarbopages.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages/xlsx_cms_assoprependarbopages.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages/export_cms_assoprependarbopages.php", "w");
	$exportContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoprependarbopages/import_cms_assoprependarbopages.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>