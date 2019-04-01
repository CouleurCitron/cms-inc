<?php
/* [Begin patch] */

// patch de migration
if (!ispatched('cms_js')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_js`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('cms_media', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_js` ADD `cms_media` INT( 11 ) NOT NULL AFTER `cms_fichier` , ADD `cms_mediacomp` VARCHAR( 512 ) NOT NULL AFTER `cms_media` ;");
		}
		if (!in_array('cms_isall', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_js` ADD `cms_isall` INT( 2 ) NOT NULL DEFAULT 0 AFTER `cms_ordre`  ;");
		}
	}
}
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_js.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_js.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_js.class.php');
}else{
/*======================================

objet de BDD cms_js :: class cms_js

SQL mySQL:

DROP TABLE IF EXISTS cms_js;
CREATE TABLE cms_js
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			varchar (255),
	cms_descriptif			varchar (512),
	cms_fichier			varchar (255),
	cms_media			int (11),
	cms_mediacomp			varchar (512),
	cms_ordre			int (11) not null,
	cms_isall			int (2),
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_js
CREATE TABLE cms_js
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_libelle			varchar2 (255),
	cms_descriptif			varchar2 (512),
	cms_fichier			varchar2 (255),
	cms_media			number (11),
	cms_mediacomp			varchar2 (512),
	cms_ordre			number (11) not null,
	cms_isall			number (2),
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_js" libelle="Scripts JS" prefix="cms" display="libelle" abstract="fichier" def_order_field="ordre" def_order_direction="ASC">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assojsarbopages,cms_assojscmssite" />
<item name="libelle" libelle="libellé" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="descriptif" libelle="descriptif" type="varchar" length="512" list="false" order="false" />
<item name="fichier" libelle="fichier" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="media" libelle="device (media)" type="int" length="11" default="-1" list="true" order="true" fkey="cms_media"  />
<item name="mediacomp" libelle="complément media" type="varchar" length="512" list="false" order="false" />
<item name="ordre" libelle="ordre" type="int" length="11" notnull="true" default="0" list="true" order="true" />
<item name="isall" libelle="actif pour tous sites" type="int" length="2" default="0" list="true" order="true" option="bool" />
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>


==========================================*/

class cms_js
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $descriptif;
var $fichier;
var $media;
var $mediacomp;
var $ordre;
var $isall;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_js\" libelle=\"Scripts JS\" prefix=\"cms\" display=\"libelle\" abstract=\"fichier\" def_order_field=\"ordre\" def_order_direction=\"ASC\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assojsarbopages,cms_assojscmssite\" />
<item name=\"libelle\" libelle=\"libellé\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"descriptif\" libelle=\"descriptif\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" />
<item name=\"fichier\" libelle=\"fichier\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"media\" libelle=\"device (media)\" type=\"int\" length=\"11\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_media\"  />
<item name=\"mediacomp\" libelle=\"complément media\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" />
<item name=\"ordre\" libelle=\"ordre\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"isall\" libelle=\"actif pour tous sites\" type=\"int\" length=\"2\" default=\"0\" list=\"true\" order=\"true\" option=\"bool\" />
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_js
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			varchar (255),
	cms_descriptif			varchar (512),
	cms_fichier			varchar (255),
	cms_media			int (11),
	cms_mediacomp			varchar (512),
	cms_ordre			int (11) not null,
	cms_isall			int (2),
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
		$this->libelle = "";
		$this->descriptif = "";
		$this->fichier = "";
		$this->media = -1;
		$this->mediacomp = "";
		$this->ordre = -1;
		$this->isall = -1;
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
	$laListeChamps[]=new dbChamp("Cms_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Cms_descriptif", "text", "get_descriptif", "set_descriptif");
	$laListeChamps[]=new dbChamp("Cms_fichier", "text", "get_fichier", "set_fichier");
	$laListeChamps[]=new dbChamp("Cms_media", "entier", "get_media", "set_media");
	$laListeChamps[]=new dbChamp("Cms_mediacomp", "text", "get_mediacomp", "set_mediacomp");
	$laListeChamps[]=new dbChamp("Cms_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Cms_isall", "entier", "get_isall", "set_isall");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_descriptif() { return($this->descriptif); }
function get_fichier() { return($this->fichier); }
function get_media() { return($this->media); }
function get_mediacomp() { return($this->mediacomp); }
function get_ordre() { return($this->ordre); }
function get_isall() { return($this->isall); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_libelle($c_cms_libelle) { return($this->libelle=$c_cms_libelle); }
function set_descriptif($c_cms_descriptif) { return($this->descriptif=$c_cms_descriptif); }
function set_fichier($c_cms_fichier) { return($this->fichier=$c_cms_fichier); }
function set_media($c_cms_media) { return($this->media=$c_cms_media); }
function set_mediacomp($c_cms_mediacomp) { return($this->mediacomp=$c_cms_mediacomp); }
function set_ordre($c_cms_ordre) { return($this->ordre=$c_cms_ordre); }
function set_isall($c_cms_isall) { return($this->isall=$c_cms_isall); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_js"); }
function getClasse() { return("cms_js"); }
function getPrefix() { return(""); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("fichier"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js/list_cms_js.php", "w");
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
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js/maj_cms_js.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js/show_cms_js.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js/rss_cms_js.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js/xml_cms_js.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js/xlsx_cms_js.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_js/import_cms_js.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>