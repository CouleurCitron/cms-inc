<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assotitleclasse :: class cms_assotitleclasse

SQL mySQL:

DROP TABLE IF EXISTS cms_assotitleclasse;
CREATE TABLE cms_assotitleclasse
(
	xtc_id			int (11) PRIMARY KEY not null,
	xtc_cms_title			int,
	xtc_classe			int,
	xtc_classeid			int
)

SQL Oracle:

DROP TABLE cms_assotitleclasse
CREATE TABLE cms_assotitleclasse
(
	xtc_id			number (11) constraint xtc_pk PRIMARY KEY not null,
	xtc_cms_title			number,
	xtc_classe			number,
	xtc_classeid			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assotitleclasse" is_asso="true" libelle="Classe title" prefix="xtc" display="cms_title" abstract="classe">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="cms_title" libelle="title" type="int" default="0" order="true" list="true" fkey="cms_title" />
<item name="classe" libelle="Classe" type="int" default="0" order="true" list="true" fkey="classe" />
<item name="classeid" libelle="Id de la classe" type="int" default="0" order="true" />
</class>


==========================================*/

class cms_assotitleclasse
{
var $id;
var $cms_title;
var $classe;
var $classeid;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assotitleclasse\" is_asso=\"true\" libelle=\"Classe title\" prefix=\"xtc\" display=\"cms_title\" abstract=\"classe\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"cms_title\" libelle=\"title\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_title\" />
<item name=\"classe\" libelle=\"Classe\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"classe\" />
<item name=\"classeid\" libelle=\"Id de la classe\" type=\"int\" default=\"0\" order=\"true\" />
</class>";

var $sMySql = "CREATE TABLE cms_assotitleclasse
(
	xtc_id			int (11) PRIMARY KEY not null,
	xtc_cms_title			int,
	xtc_classe			int,
	xtc_classeid			int
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_assotitleclasse") == false){
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
		$this->cms_title = -1;
		$this->classe = -1;
		$this->classeid = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xtc_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xtc_cms_title", "entier", "get_cms_title", "set_cms_title");
	$laListeChamps[]=new dbChamp("Xtc_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Xtc_classeid", "entier", "get_classeid", "set_classeid");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_title() { return($this->cms_title); }
function get_classe() { return($this->classe); }
function get_classeid() { return($this->classeid); }


// setters
function set_id($c_xtc_id) { return($this->id=$c_xtc_id); }
function set_cms_title($c_xtc_cms_title) { return($this->cms_title=$c_xtc_cms_title); }
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
function getTable() { return("cms_assotitleclasse"); }
function getClasse() { return("cms_assotitleclasse"); }
function getDisplay() { return("cms_title"); }
function getAbstract() { return("classe"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse/list_cms_assotitleclasse.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse/maj_cms_assotitleclasse.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse/show_cms_assotitleclasse.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse/rss_cms_assotitleclasse.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse/xml_cms_assotitleclasse.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse/export_cms_assotitleclasse.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assotitleclasse/import_cms_assotitleclasse.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>