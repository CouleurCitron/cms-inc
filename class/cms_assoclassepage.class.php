<?php
/* [Begin patch] */

// patch de migration
if (!ispatched('cms_assoclassepage')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_assoclassepage`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('xcp_order', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_assoclassepage` ADD `xcp_order` INT( 11 ) NULL DEFAULT NULL AFTER `xcp_objet`;");
		} 
		if (!in_array('xcp_parent', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_assoclassepage` ADD `xcp_parent` INT( 11 ) NULL DEFAULT -1 AFTER `xcp_order`;");
		}
	}
}

// patch de migration
if (!ispatched('cms_assoclassepage')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_assoclassepage`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('xcp_parent', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_assoclassepage` ADD `xcp_parent` INT( 11 ) NULL DEFAULT NULL AFTER `xcp_order`;");
		} 
	}
}

/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assoclassepage.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_assoclassepage.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assoclassepage.class.php');
}else{
/*======================================

objet de BDD cms_assoclassepage :: class cms_assoclassepage

SQL mySQL:

DROP TABLE IF EXISTS cms_assoclassepage;
CREATE TABLE cms_assoclassepage
(
	xcp_id			int (11) PRIMARY KEY not null,
	xcp_cms_page			int,
	xcp_classe			int (11),
	xcp_objet			int (11),
	xcp_order			int (11),
	xcp_parent			int (11)
)

SQL Oracle:

DROP TABLE cms_assoclassepage
CREATE TABLE cms_assoclassepage
(
	xcp_id			number (11) constraint xcp_pk PRIMARY KEY not null,
	xcp_cms_page			number,
	xcp_classe			number (11),
	xcp_objet			number (11),
	xcp_order			number (11),
	xcp_parent			number (11)
)


<?xml version="1.0" encoding="iso-8859-1"?>
<class name="cms_assoclassepage" is_asso="true" libelle="liens objet et page" prefix="xcp" display="cms_page" abstract="classe">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_page" libelle="Page liée" type="int" default="0" order="true" list="true" fkey="cms_page" />
<item name="classe" libelle="Objet liée" type="int" length="11" order="true" list="true" fkey="classe" />
<item name="objet" libelle="Instance de l'objet" type="int" length="11" order="true" list="true"/>
<item name="order" libelle="Ordre" type="int" length="11" default="-1" list="true" order="true" />

<item name="parent" libelle="Parent" type="int" length="11" default="-1" list="true" order="true" />
</class>


==========================================*/

class cms_assoclassepage
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $cms_page;
var $classe;
var $objet;
var $order;
var $parent;


var $XML = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<class name=\"cms_assoclassepage\" is_asso=\"true\" libelle=\"liens objet et page\" prefix=\"xcp\" display=\"cms_page\" abstract=\"classe\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_page\" libelle=\"Page liée\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_page\" />
<item name=\"classe\" libelle=\"Objet liée\" type=\"int\" length=\"11\" order=\"true\" list=\"true\" fkey=\"classe\" />
<item name=\"objet\" libelle=\"Instance de l'objet\" type=\"int\" length=\"11\" order=\"true\" list=\"true\"/>
<item name=\"order\" libelle=\"Ordre\" type=\"int\" length=\"11\" default=\"-1\" list=\"true\" order=\"true\" />

<item name=\"parent\" libelle=\"Parent\" type=\"int\" length=\"11\" default=\"-1\" list=\"true\" order=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_assoclassepage
(
	xcp_id			int (11) PRIMARY KEY not null,
	xcp_cms_page			int,
	xcp_classe			int (11),
	xcp_objet			int (11),
	xcp_order			int (11),
	xcp_parent			int (11)
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
		$this->cms_page = -1;
		$this->classe = -1;
		$this->objet = -1;
		$this->order = -1;
		$this->parent = -1;
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
	$laListeChamps[]=new dbChamp("Xcp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xcp_cms_page", "entier", "get_cms_page", "set_cms_page");
	$laListeChamps[]=new dbChamp("Xcp_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Xcp_objet", "entier", "get_objet", "set_objet");
	$laListeChamps[]=new dbChamp("Xcp_order", "entier", "get_order", "set_order");
	$laListeChamps[]=new dbChamp("Xcp_parent", "entier", "get_parent", "set_parent");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_page() { return($this->cms_page); }
function get_classe() { return($this->classe); }
function get_objet() { return($this->objet); }
function get_order() { return($this->order); }
function get_parent() { return($this->parent); }


// setters
function set_id($c_xcp_id) { return($this->id=$c_xcp_id); }
function set_cms_page($c_xcp_cms_page) { return($this->cms_page=$c_xcp_cms_page); }
function set_classe($c_xcp_classe) { return($this->classe=$c_xcp_classe); }
function set_objet($c_xcp_objet) { return($this->objet=$c_xcp_objet); }
function set_order($c_xcp_order) { return($this->order=$c_xcp_order); }
function set_parent($c_xcp_parent) { return($this->parent=$c_xcp_parent); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xcp_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assoclassepage"); }
function getClasse() { return("cms_assoclassepage"); }
function getPrefix() { return(""); }
function getDisplay() { return("cms_page"); }
function getAbstract() { return("classe"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage/list_cms_assoclassepage.php", "w");
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
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage/maj_cms_assoclassepage.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage/show_cms_assoclassepage.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage/rss_cms_assoclassepage.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage/xml_cms_assoclassepage.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage/xlsx_cms_assoclassepage.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassepage/import_cms_assoclassepage.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>