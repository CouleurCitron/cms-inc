<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_texte.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_texte.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_texte.class.php');
}else{
/*======================================

objet de BDD cms_texte :: class cms_texte

SQL mySQL:

DROP TABLE IF EXISTS cms_texte;
CREATE TABLE cms_texte
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_code			varchar (128),
	cms_chaine			int (11) not null,
	cms_init			int (2),
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_texte
CREATE TABLE cms_texte
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_code			varchar2 (128),
	cms_chaine			number (11) not null,
	cms_init			number (2),
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_texte" libelle="Textes" prefix="cms" display="code" abstract="chaine" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="code" libelle="Code" type="varchar" length="128" list="true" order="true" />
<item name="chaine" libelle="Chaine" type="int" length="11" notnull="true" default="0" translate="reference" />
<item name="init" libelle="Chargé à l'init" type="int" length="2" list="false" order="false" default="0" option="bool" />
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_ID_STATUT_LIGNE" list="true" />
</class> 


==========================================*/

class cms_texte
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $code;
var $chaine;
var $init;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_texte\" libelle=\"Textes\" prefix=\"cms\" display=\"code\" abstract=\"chaine\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"code\" libelle=\"Code\" type=\"varchar\" length=\"128\" list=\"true\" order=\"true\" />
<item name=\"chaine\" libelle=\"Chaine\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" translate=\"reference\" />
<item name=\"init\" libelle=\"Chargé à l'init\" type=\"int\" length=\"2\" list=\"false\" order=\"false\" default=\"0\" option=\"bool\" />
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_ID_STATUT_LIGNE\" list=\"true\" />
</class> ";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_texte
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_code			varchar (128),
	cms_chaine			int (11) not null,
	cms_init			int (2),
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
		$this->code = "";
		$this->chaine = -1;
		$this->init = -1;
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
	$laListeChamps[]=new dbChamp("Cms_code", "text", "get_code", "set_code");
	$laListeChamps[]=new dbChamp("Cms_chaine", "entier", "get_chaine", "set_chaine");
	$laListeChamps[]=new dbChamp("Cms_init", "entier", "get_init", "set_init");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_code() { return($this->code); }
function get_chaine() { return($this->chaine); }
function get_init() { return($this->init); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_code($c_cms_code) { return($this->code=$c_cms_code); }
function set_chaine($c_cms_chaine) { return($this->chaine=$c_cms_chaine); }
function set_init($c_cms_init) { return($this->init=$c_cms_init); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_texte"); }
function getClasse() { return("cms_texte"); }
function getDisplay() { return("code"); }
function getAbstract() { return("chaine"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte/list_cms_texte.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[newSizeOf(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte/maj_cms_texte.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte/show_cms_texte.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte/rss_cms_texte.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte/xml_cms_texte.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte/xmlxls_cms_texte.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte/export_cms_texte.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_texte/import_cms_texte.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>