<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_tag.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_tag.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_tag.class.php');
}else{
/*======================================

objet de BDD cms_tag :: class cms_tag

SQL mySQL:

DROP TABLE IF EXISTS cms_tag;
CREATE TABLE cms_tag
(
	tag_id			int (11) PRIMARY KEY,
	tag_nom			int (11),
	tag_cms_site			int (11),
	tag_statut			int not null
)

SQL Oracle:

DROP TABLE cms_tag
CREATE TABLE cms_tag
(
	tag_id			number (11) constraint tag_pk PRIMARY KEY,
	tag_nom			number (11),
	tag_cms_site			number (11),
	tag_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_tag" libelle="Mots clé par objet" prefix="tag" display="id" abstract="nom" >
<item name="id" type="int" length="11" list="true" order="true" nohtml="true" isprimary="true" asso="cms_assotagclasse" />
<item name="nom"  type="int" length="11" list="true" order="true"  nohtml="true" oblig="true"  translate="reference" option="textarea"/>
<item name="cms_site" libelle="mini-site" type="int" length="11" list="true" order="true" fkey="cms_site" /> 
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>


==========================================*/

class cms_tag
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $nom;
var $cms_site;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_tag\" libelle=\"Mots clé par objet\" prefix=\"tag\" display=\"id\" abstract=\"nom\" >
<item name=\"id\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" nohtml=\"true\" isprimary=\"true\" asso=\"cms_assotagclasse\" />
<item name=\"nom\"  type=\"int\" length=\"11\" list=\"true\" order=\"true\"  nohtml=\"true\" oblig=\"true\"  translate=\"reference\" option=\"textarea\"/>
<item name=\"cms_site\" libelle=\"mini-site\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_site\" /> 
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_tag
(
	tag_id			int (11) PRIMARY KEY,
	tag_nom			int (11),
	tag_cms_site			int (11),
	tag_statut			int not null
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
		$this->nom = -1;
		$this->cms_site = -1;
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
	$laListeChamps[]=new dbChamp("Tag_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Tag_nom", "entier", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Tag_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Tag_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_cms_site() { return($this->cms_site); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_tag_id) { return($this->id=$c_tag_id); }
function set_nom($c_tag_nom) { return($this->nom=$c_tag_nom); }
function set_cms_site($c_tag_cms_site) { return($this->cms_site=$c_tag_cms_site); }
function set_statut($c_tag_statut) { return($this->statut=$c_tag_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("tag_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("tag_statut"); }
//
function getTable() { return("cms_tag"); }
function getClasse() { return("cms_tag"); }
function getDisplay() { return("id"); }
function getAbstract() { return("nom"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag/list_cms_tag.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag/maj_cms_tag.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag/show_cms_tag.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag/rss_cms_tag.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag/xml_cms_tag.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag/xmlxls_cms_tag.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag/export_cms_tag.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_tag/import_cms_tag.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>