<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_media.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_media.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_media.class.php');
}else{
/*======================================

objet de BDD cms_media :: class cms_media

SQL mySQL:

DROP TABLE IF EXISTS cms_media;
CREATE TABLE cms_media
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			varchar (255),
	cms_descriptif			varchar (512),
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_media
CREATE TABLE cms_media
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_libelle			varchar2 (255),
	cms_descriptif			varchar2 (512),
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_media" libelle="Type de devices" prefix="cms" display="libelle" abstract="libelle">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="libelle" libelle="libellé" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="descriptif" libelle="descriptif" type="varchar" length="512" list="false" order="false" />
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>



==========================================*/

class cms_media
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $descriptif;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_media\" libelle=\"Type de devices\" prefix=\"cms\" display=\"libelle\" abstract=\"libelle\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"libelle\" libelle=\"libellé\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"descriptif\" libelle=\"descriptif\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" />
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_media
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			varchar (255),
	cms_descriptif			varchar (512),
	cms_statut			int (11) not null
)

";

// constructeur
function cms_media($id=null)
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
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_descriptif() { return($this->descriptif); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_libelle($c_cms_libelle) { return($this->libelle=$c_cms_libelle); }
function set_descriptif($c_cms_descriptif) { return($this->descriptif=$c_cms_descriptif); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_media"); }
function getClasse() { return("cms_media"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("libelle"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media/list_cms_media.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media/maj_cms_media.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media/show_cms_media.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media/rss_cms_media.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media/xml_cms_media.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media/xlsx_cms_media.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media/export_cms_media.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_media/import_cms_media.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
}
?>