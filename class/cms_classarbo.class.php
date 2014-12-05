<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_classarbo :: class cms_classarbo

SQL mySQL:

DROP TABLE IF EXISTS cms_classarbo;
CREATE TABLE cms_classarbo
(
	ca_id			int (11) PRIMARY KEY,
	ca_classe			int (11),
	ca_classeid			int (11),
	ca_arbo			int (11)
)

SQL Oracle:

DROP TABLE cms_classarbo
CREATE TABLE cms_classarbo
(
	ca_id			number (11) constraint ca_pk PRIMARY KEY,
	ca_classe			number (11),
	ca_classeid			number (11),
	ca_arbo			number (11)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_classarbo" libelle="Classeur arborescence" prefix="ca" display="classe" abstract="arbo">
<item name="id" type="int" length="11" list="true" order="true" nohtml="true" isprimary="true" />
<item name="classe" type="int" length="11" list="true" order="true" nohtml="true" />
<item name="classeid" type="int" length="11" list="true" order="true" />
<item name="arbo"  type="int" length="11" list="true" order="true"  nohtml="true" />
</class>



==========================================*/

class cms_classarbo
{
var $id;
var $classe;
var $classeid;
var $arbo;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_classarbo\" libelle=\"Classeur arborescence\" prefix=\"ca\" display=\"classe\" abstract=\"arbo\">
<item name=\"id\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" nohtml=\"true\" isprimary=\"true\" />
<item name=\"classe\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"classeid\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" />
<item name=\"arbo\"  type=\"int\" length=\"11\" list=\"true\" order=\"true\"  nohtml=\"true\" />
</class>
";

var $sMySql = "CREATE TABLE cms_classarbo
(
	ca_id			int (11) PRIMARY KEY,
	ca_classe			int (11),
	ca_classeid			int (11),
	ca_arbo			int (11)
)

";

// constructeur
function cms_classarbo($id=null)
{
	if (istable("cms_classarbo") == false){
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
		$this->classe = -1;
		$this->classeid = -1;
		$this->arbo = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Ca_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Ca_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Ca_classeid", "entier", "get_classeid", "set_classeid");
	$laListeChamps[]=new dbChamp("Ca_arbo", "entier", "get_arbo", "set_arbo");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_classe() { return($this->classe); }
function get_classeid() { return($this->classeid); }
function get_arbo() { return($this->arbo); }


// setters
function set_id($c_ca_id) { return($this->id=$c_ca_id); }
function set_classe($c_ca_classe) { return($this->classe=$c_ca_classe); }
function set_classeid($c_ca_classeid) { return($this->classeid=$c_ca_classeid); }
function set_arbo($c_ca_arbo) { return($this->arbo=$c_ca_arbo); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("ca_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_classarbo"); }
function getClasse() { return("cms_classarbo"); }
function getDisplay() { return("classe"); }
function getAbstract() { return("arbo"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo/list_cms_classarbo.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo/maj_cms_classarbo.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo/show_cms_classarbo.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo/rss_cms_classarbo.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo/xml_cms_classarbo.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo/export_cms_classarbo.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_classarbo/import_cms_classarbo.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>