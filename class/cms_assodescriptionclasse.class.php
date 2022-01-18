<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assodescriptionclasse :: class cms_assodescriptionclasse

SQL mySQL:

DROP TABLE IF EXISTS cms_assodescriptionclasse;
CREATE TABLE cms_assodescriptionclasse
(
	xdc_id			int (11) PRIMARY KEY not null,
	xdc_cms_description			int,
	xdc_classe			int,
	xdc_classeid			int
)

SQL Oracle:

DROP TABLE cms_assodescriptionclasse
CREATE TABLE cms_assodescriptionclasse
(
	xdc_id			number (11) constraint xdc_pk PRIMARY KEY not null,
	xdc_cms_description			number,
	xdc_classe			number,
	xdc_classeid			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assodescriptionclasse" is_asso="true" libelle="Classe description" prefix="xdc" display="cms_description" abstract="classe">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_description" libelle="description" type="int" default="0" order="true" list="true" fkey="cms_description" />
<item name="classe" libelle="Classe" type="int" default="0" order="true" list="true" fkey="classe" />
<item name="classeid" libelle="Id de la classe" type="int" default="0" order="true" />
</class>



==========================================*/

class cms_assodescriptionclasse
{
var $id;
var $cms_description;
var $classe;
var $classeid;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assodescriptionclasse\" is_asso=\"true\" libelle=\"Classe description\" prefix=\"xdc\" display=\"cms_description\" abstract=\"classe\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_description\" libelle=\"description\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_description\" />
<item name=\"classe\" libelle=\"Classe\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"classe\" />
<item name=\"classeid\" libelle=\"Id de la classe\" type=\"int\" default=\"0\" order=\"true\" />
</class>
";

var $sMySql = "CREATE TABLE cms_assodescriptionclasse
(
	xdc_id			int (11) PRIMARY KEY not null,
	xdc_cms_description			int,
	xdc_classe			int,
	xdc_classeid			int
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_assodescriptionclasse") == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id = -1;
		$this->cms_description = -1;
		$this->classe = -1;
		$this->classeid = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xdc_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xdc_cms_description", "entier", "get_cms_description", "set_cms_description");
	$laListeChamps[]=new dbChamp("Xdc_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Xdc_classeid", "entier", "get_classeid", "set_classeid");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_description() { return($this->cms_description); }
function get_classe() { return($this->classe); }
function get_classeid() { return($this->classeid); }


// setters
function set_id($c_xdc_id) { return($this->id=$c_xdc_id); }
function set_cms_description($c_xdc_cms_description) { return($this->cms_description=$c_xdc_cms_description); }
function set_classe($c_xdc_classe) { return($this->classe=$c_xdc_classe); }
function set_classeid($c_xdc_classeid) { return($this->classeid=$c_xdc_classeid); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xdc_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assodescriptionclasse"); }
function getClasse() { return("cms_assodescriptionclasse"); }
function getDisplay() { return("cms_description"); }
function getAbstract() { return("classe"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse/list_cms_assodescriptionclasse.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse/maj_cms_assodescriptionclasse.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse/show_cms_assodescriptionclasse.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse/rss_cms_assodescriptionclasse.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse/xml_cms_assodescriptionclasse.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse/export_cms_assodescriptionclasse.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_assodescriptionclasse/import_cms_assodescriptionclasse.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>