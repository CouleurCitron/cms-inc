<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================



<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_arbo_classeur" libelle="Sections du Back-Office" prefix="node" display="parent_id" abstract="libelle">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="parent_id" type="int" length="11" list="true" order="true" />
<item name="libelle" type="varchar" length="512" list="false" order="false" />
<item name="node_absolute_path_name" type="varchar" length="512" list="false" order="false" />
<item name="order" type="int" length="11" notnull="true" default="-1" list="true" />
<item name="description" type="int" length="11" list="true" order="true" />
<item name="id_site" libelle="statut" type="int" length="11" notnull="true" default="1" list="true" />
</class>


==========================================*/

class cms_arbo_classeur
{
var $id;
var $parent_id;
var $libelle;
var $absolute_path_name;
var $order;
var $description;
var $id_site;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_arbo_classeur\" libelle=\"Sections du Back-Office\" prefix=\"node\" display=\"parent_id\" abstract=\"libelle\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"parent_id\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" />
<item name=\"libelle\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" />
<item name=\"node_absolute_path_name\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" />
<item name=\"order\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"description\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" />
<item name=\"id_site\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"1\" list=\"true\" />
</class>";

var $sMySql = "CREATE TABLE cms_arbo_classeur
(
	node_id			int (11) PRIMARY KEY not null,
	node_parent_id			int (11),
	node_libelle			varchar (512),
	node_absolute_path_name			varchar (512),
	node_order			int (11) not null,
	node_description			int (11),
	node_id_site			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_arbo_classeur") == false){
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
		$this->parent_id = -1;
		$this->libelle = "";
		$this->absolute_path_name = "";
		$this->order = -1;
		$this->description = -1;
		$this->id_site = 1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Node_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Node_parent_id", "entier", "get_parent_id", "set_parent_id");
	$laListeChamps[]=new dbChamp("Node_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Node_absolute_path_name", "text", "get_absolute_path_name", "set_absolute_path_name");
	$laListeChamps[]=new dbChamp("Node_order", "entier", "get_order", "set_order");
	$laListeChamps[]=new dbChamp("Node_description", "entier", "get_description", "set_description");
	$laListeChamps[]=new dbChamp("Node_id_site", "entier", "get_id_site", "set_id_site");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_parent_id() { return($this->parent_id); }
function get_libelle() { return($this->libelle); }
function get_absolute_path_name() { return($this->absolute_path_name); }
function get_order() { return($this->order); }
function get_description() { return($this->description); }
function get_id_site() { return($this->id_site); }


// setters
function set_id($c_node_id) { return($this->id=$c_node_id); }
function set_parent_id($c_node_parent_id) { return($this->parent_id=$c_node_parent_id); }
function set_libelle($c_node_libelle) { return($this->libelle=$c_node_libelle); }
function set_absolute_path_name($c_node_absolute_path_name) { return($this->absolute_path_name=$c_node_absolute_path_name); }
function set_order($c_node_order) { return($this->order=$c_node_order); }
function set_description($c_node_description) { return($this->description=$c_node_description); }
function set_id_site($c_node_id_site) { return($this->id_site=$c_node_id_site); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("node_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_arbo_classeur"); }
function getClasse() { return("cms_arbo_classeur"); }
function getDisplay() { return("parent_id"); }
function getAbstract() { return("libelle"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur/list_cms_arbo_classeur.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur/maj_cms_arbo_classeur.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur/show_cms_arbo_classeur.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur/rss_cms_arbo_classeur.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur/xml_cms_arbo_classeur.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur/export_cms_arbo_classeur.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_arbo_classeur/import_cms_arbo_classeur.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>