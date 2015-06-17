<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('cms_assoclassenode')){
	$rs = $db->Execute('DESCRIBE `cms_assoclassenode`');
	if (isset($rs->_numOfRows)){
		if ($rs->_numOfRows == 4){
			$rs = $db->Execute('ALTER TABLE `cms_cacheclassenode` ADD `xca_arbo_pages_parent` INT( 11 ) NULL AFTER `xca_id` ;');
		} 
	} 
}
/*======================================

objet de BDD cms_cacheclassenode :: class cms_cacheclassenode

SQL mySQL:

DROP TABLE IF EXISTS cms_cacheclassenode;
CREATE TABLE cms_cacheclassenode
(
	xca_id			int (11) PRIMARY KEY not null,
	xca_arbo_pages_parent			int,
	xca_arbo_pages			int,
	xca_classe			varchar (128),
	xca_objet			int (11)
)

SQL Oracle:

DROP TABLE cms_cacheclassenode
CREATE TABLE cms_cacheclassenode
(
	xca_id			number (11) constraint xca_pk PRIMARY KEY not null,
	xca_arbo_pages_parent			number,
	xca_arbo_pages			number,
	xca_classe			varchar2 (128),
	xca_objet			number (11)
)


<?xml version="1.0" encoding="iso-8859-1"?>
<class name="cms_cacheclassenode" libelle="Cache liens objet et arbo" prefix="xca" display="arbo_pages" abstract="classe">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="arbo_pages_parent" libelle="Arbo Node contenant les objets" type="int" default="0" order="true" list="true" fkey="cms_arbo_pages" />

<item name="arbo_pages" libelle="Arbo Node crée" type="int" default="0" order="true" list="true" fkey="cms_arbo_pages" />
<item name="classe" libelle="Nom de la classe" type="varchar" length="128" order="true" list="true"/>
<item name="objet" libelle="Instance de la classe" type="int" length="11" order="true" list="true"/>

</class>


==========================================*/

class cms_cacheclassenode
{
var $id;
var $arbo_pages_parent;
var $arbo_pages;
var $classe;
var $objet;


var $XML = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<class name=\"cms_cacheclassenode\" libelle=\"Cache liens objet et arbo\" prefix=\"xca\" display=\"arbo_pages\" abstract=\"classe\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"arbo_pages_parent\" libelle=\"Arbo Node contenant les objets\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_arbo_pages\" />

<item name=\"arbo_pages\" libelle=\"Arbo Node crée\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_arbo_pages\" />
<item name=\"classe\" libelle=\"Nom de la classe\" type=\"varchar\" length=\"128\" order=\"true\" list=\"true\"/>
<item name=\"objet\" libelle=\"Instance de la classe\" type=\"int\" length=\"11\" order=\"true\" list=\"true\"/>

</class>";

var $sMySql = "CREATE TABLE cms_cacheclassenode
(
	xca_id			int (11) PRIMARY KEY not null,
	xca_arbo_pages_parent			int,
	xca_arbo_pages			int,
	xca_classe			varchar (128),
	xca_objet			int (11)
)

";

// constructeur
function cms_cacheclassenode($id=null)
{
	if (istable("cms_cacheclassenode") == false){
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
		$this->arbo_pages_parent = -1;
		$this->arbo_pages = -1;
		$this->classe = "";
		$this->objet = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xca_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xca_arbo_pages_parent", "entier", "get_arbo_pages_parent", "set_arbo_pages_parent");
	$laListeChamps[]=new dbChamp("Xca_arbo_pages", "entier", "get_arbo_pages", "set_arbo_pages");
	$laListeChamps[]=new dbChamp("Xca_classe", "text", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Xca_objet", "entier", "get_objet", "set_objet");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_arbo_pages_parent() { return($this->arbo_pages_parent); }
function get_arbo_pages() { return($this->arbo_pages); }
function get_classe() { return($this->classe); }
function get_objet() { return($this->objet); }


// setters
function set_id($c_xca_id) { return($this->id=$c_xca_id); }
function set_arbo_pages_parent($c_xca_arbo_pages_parent) { return($this->arbo_pages_parent=$c_xca_arbo_pages_parent); }
function set_arbo_pages($c_xca_arbo_pages) { return($this->arbo_pages=$c_xca_arbo_pages); }
function set_classe($c_xca_classe) { return($this->classe=$c_xca_classe); }
function set_objet($c_xca_objet) { return($this->objet=$c_xca_objet); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xca_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_cacheclassenode"); }
function getClasse() { return("cms_cacheclassenode"); }
function getDisplay() { return("arbo_pages"); }
function getAbstract() { return("classe"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode/list_cms_cacheclassenode.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode/maj_cms_cacheclassenode.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode/show_cms_cacheclassenode.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode/rss_cms_cacheclassenode.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode/xml_cms_cacheclassenode.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode/xmlxls_cms_cacheclassenode.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode/export_cms_cacheclassenode.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cacheclassenode/import_cms_cacheclassenode.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>