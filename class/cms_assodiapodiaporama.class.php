<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
 
  
$rs = $db->Execute('DESCRIBE `cms_assodiapodiaporama`');
if (isset($rs->_numOfRows)){ 
	if ($rs->_numOfRows == 3){
		$rs = $db->Execute('ALTER TABLE `cms_assodiapodiaporama` ADD `xdp_ordre` INT( 2 ) NULL DEFAULT \'0\' AFTER `xdp_cms_diapo` ;'); 	} 
} 
 

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assodiapodiaporama.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_assodiapodiaporama.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assodiapodiaporama.class.php');
}else{
/*======================================

objet de BDD cms_assodiapodiaporama :: class cms_assodiapodiaporama

SQL mySQL:

DROP TABLE IF EXISTS cms_assodiapodiaporama;
CREATE TABLE cms_assodiapodiaporama
(
	xdp_id			int (11) PRIMARY KEY not null,
	xdp_cms_diaporama			int,
	xdp_cms_diapo			int,
	xdp_ordre			int (11)
)

SQL Oracle:

DROP TABLE cms_assodiapodiaporama
CREATE TABLE cms_assodiapodiaporama
(
	xdp_id			number (11) constraint xdp_pk PRIMARY KEY not null,
	xdp_cms_diaporama			number,
	xdp_cms_diapo			number,
	xdp_ordre			number (11)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assodiapodiaporama" is_asso="true" libelle="Diapo Diaporama" prefix="xdp" display="cms_diaporama" abstract="cms_diapo" inherits_from="cms_assodiapodiaporama">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_diaporama" libelle="Diaporama" type="int" default="0" order="true" list="true" fkey="cms_diaporama" />
<item name="cms_diapo" libelle="Diapo" type="int" default="0" order="true" list="true" fkey="cms_diapo" />
<item name="ordre" libelle="Ordre" type="int" length="11" default="0" order="true" list="true"   />
</class>


==========================================*/

class cms_assodiapodiaporama
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $cms_diaporama;
var $cms_diapo;
var $ordre;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assodiapodiaporama\" is_asso=\"true\" libelle=\"Diapo Diaporama\" prefix=\"xdp\" display=\"cms_diaporama\" abstract=\"cms_diapo\" inherits_from=\"cms_assodiapodiaporama\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_diaporama\" libelle=\"Diaporama\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_diaporama\" />
<item name=\"cms_diapo\" libelle=\"Diapo\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_diapo\" />
<item name=\"ordre\" libelle=\"Ordre\" type=\"int\" length=\"11\" default=\"0\" order=\"true\" list=\"true\"   />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_assodiapodiaporama
(
	xdp_id			int (11) PRIMARY KEY not null,
	xdp_cms_diaporama			int,
	xdp_cms_diapo			int,
	xdp_ordre			int (11)
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
		$this->cms_diaporama = -1;
		$this->cms_diapo = -1;
		$this->ordre = -1;
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
	$laListeChamps[]=new dbChamp("Xdp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xdp_cms_diaporama", "entier", "get_cms_diaporama", "set_cms_diaporama");
	$laListeChamps[]=new dbChamp("Xdp_cms_diapo", "entier", "get_cms_diapo", "set_cms_diapo");
	$laListeChamps[]=new dbChamp("Xdp_ordre", "entier", "get_ordre", "set_ordre");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_diaporama() { return($this->cms_diaporama); }
function get_cms_diapo() { return($this->cms_diapo); }
function get_ordre() { return($this->ordre); }


// setters
function set_id($c_xdp_id) { return($this->id=$c_xdp_id); }
function set_cms_diaporama($c_xdp_cms_diaporama) { return($this->cms_diaporama=$c_xdp_cms_diaporama); }
function set_cms_diapo($c_xdp_cms_diapo) { return($this->cms_diapo=$c_xdp_cms_diapo); }
function set_ordre($c_xdp_ordre) { return($this->ordre=$c_xdp_ordre); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xdp_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assodiapodiaporama"); }
function getClasse() { return("cms_assodiapodiaporama"); }
function getDisplay() { return("cms_diaporama"); }
function getAbstract() { return("cms_diapo"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama/list_cms_assodiapodiaporama.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama/maj_cms_assodiapodiaporama.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama/show_cms_assodiapodiaporama.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama/rss_cms_assodiapodiaporama.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama/xml_cms_assodiapodiaporama.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama/xmlxls_cms_assodiapodiaporama.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama/export_cms_assodiapodiaporama.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assodiapodiaporama/import_cms_assodiapodiaporama.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>