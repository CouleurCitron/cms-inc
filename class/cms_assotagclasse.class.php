<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assotagclasse.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_assotagclasse.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_assotagclasse.class.php');
}else{
/*======================================

objet de BDD cms_assotagclasse :: class cms_assotagclasse

SQL mySQL:

DROP TABLE IF EXISTS cms_assotagclasse;
CREATE TABLE cms_assotagclasse
(
	xtc_id			int (11) PRIMARY KEY not null,
	xtc_cms_tag			int,
	xtc_classe			int,
	xtc_classeid			int
)

SQL Oracle:

DROP TABLE cms_assotagclasse
CREATE TABLE cms_assotagclasse
(
	xtc_id			number (11) constraint xtc_pk PRIMARY KEY not null,
	xtc_cms_tag			number,
	xtc_classe			number,
	xtc_classeid			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assotagclasse" is_asso="true" libelle="Classe Tag" prefix="xtc" display="cms_tag" abstract="classe" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_tag" libelle="Tag" type="int" default="0" order="true" list="true" fkey="cms_tag" />
<item name="classe" libelle="Classe" type="int" default="0" order="true" list="true" fkey="classe" />
<item name="classeid" libelle="Id de la classe" type="int" default="0" order="true" />
</class>


==========================================*/

class cms_assotagclasse
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $cms_tag;
var $classe;
var $classeid;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assotagclasse\" is_asso=\"true\" libelle=\"Classe Tag\" prefix=\"xtc\" display=\"cms_tag\" abstract=\"classe\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_tag\" libelle=\"Tag\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_tag\" />
<item name=\"classe\" libelle=\"Classe\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"classe\" />
<item name=\"classeid\" libelle=\"Id de la classe\" type=\"int\" default=\"0\" order=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_assotagclasse
(
	xtc_id			int (11) PRIMARY KEY not null,
	xtc_cms_tag			int,
	xtc_classe			int,
	xtc_classeid			int
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
		$this->cms_tag = -1;
		$this->classe = -1;
		$this->classeid = -1;
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
	$laListeChamps[]=new dbChamp("Xtc_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xtc_cms_tag", "entier", "get_cms_tag", "set_cms_tag");
	$laListeChamps[]=new dbChamp("Xtc_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Xtc_classeid", "entier", "get_classeid", "set_classeid");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_tag() { return($this->cms_tag); }
function get_classe() { return($this->classe); }
function get_classeid() { return($this->classeid); }


// setters
function set_id($c_xtc_id) { return($this->id=$c_xtc_id); }
function set_cms_tag($c_xtc_cms_tag) { return($this->cms_tag=$c_xtc_cms_tag); }
function set_classe($c_xtc_classe) { return($this->classe=$c_xtc_classe); }
function set_classeid($c_xtc_classeid) { return($this->classeid=$c_xtc_classeid); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xtc_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assotagclasse"); }
function getClasse() { return("cms_assotagclasse"); }
function getDisplay() { return("cms_tag"); }
function getAbstract() { return("classe"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse/list_cms_assotagclasse.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[newSizeOf(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse/maj_cms_assotagclasse.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse/show_cms_assotagclasse.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse/rss_cms_assotagclasse.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse/xml_cms_assotagclasse.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse/xmlxls_cms_assotagclasse.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse/export_cms_assotagclasse.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotagclasse/import_cms_assotagclasse.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>