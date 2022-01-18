<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('cms_assoclassenode')){
	$rs = $db->Execute('DESCRIBE `cms_assoclassenode`');
	if (isset($rs->_numOfRows)){
		if ($rs->_numOfRows == 3){
			$rs = $db->Execute('ALTER TABLE `cms_assoclassenode` ADD `xca_iffield` VARCHAR( 128 ) NULL ,
	ADD `xca_ifvalue` VARCHAR( 128 ) NULL ;');
		} 
		elseif ($rs->_numOfRows == 5){
			$rs = $db->Execute('ALTER TABLE `cms_assoclassenode` ADD `xca_nodemodele` INT NULL ;');
		}
	} 
}
/*======================================


objet de BDD cms_assoclassenode :: class cms_assoclassenode

SQL mySQL:

DROP TABLE IF EXISTS cms_assoclassenode;
CREATE TABLE cms_assoclassenode
(
	xca_id			int (11) PRIMARY KEY not null,
	xca_classe			int,
	xca_arbo_pages			int,
	xca_iffield			varchar (128),
	xca_ifvalue			varchar (128),
	xca_nodemodele			int
)

SQL Oracle:

DROP TABLE cms_assoclassenode
CREATE TABLE cms_assoclassenode
(
	xca_id			number (11) constraint xca_pk PRIMARY KEY not null,
	xca_classe			number,
	xca_arbo_pages			number,
	xca_iffield			varchar2 (128),
	xca_ifvalue			varchar2 (128),
	xca_nodemodele			number
)


<?xml version="1.0" encoding="iso-8859-1"?>
<class name="cms_assoclassenode" is_asso="true" libelle="Lien objet et arbo" prefix="xca" display="classe" abstract="arbo_pages">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="classe" libelle="Objet" type="int" default="0" order="true" list="true" fkey="classe" />
<item name="arbo_pages" libelle="Arbo Node contenant les objets" type="int" default="0" order="true" list="true" fkey="cms_arbo_pages" />

<item name="iffield" libelle="Champ de condition" type="varchar" length="128" nohtml="true" /> 
<item name="ifvalue" libelle="Valeur de condition" type="varchar" length="128" nohtml="true" />

<item name="nodemodele" libelle="Node contenant les pages modèles" type="int" default="0" order="true" list="true" fkey="cms_arbo_pages" />

</class>


==========================================*/

class cms_assoclassenode
{
var $id;
var $classe;
var $arbo_pages;
var $iffield;
var $ifvalue;
var $nodemodele;


var $XML = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<class name=\"cms_assoclassenode\" is_asso=\"true\" libelle=\"Lien objet et arbo\" prefix=\"xca\" display=\"classe\" abstract=\"arbo_pages\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"classe\" libelle=\"Objet\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"classe\" />
<item name=\"arbo_pages\" libelle=\"Arbo Node contenant les objets\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_arbo_pages\" />

<item name=\"iffield\" libelle=\"Champ de condition\" type=\"varchar\" length=\"128\" nohtml=\"true\" /> 
<item name=\"ifvalue\" libelle=\"Valeur de condition\" type=\"varchar\" length=\"128\" nohtml=\"true\" />

<item name=\"nodemodele\" libelle=\"Node contenant les pages modèles\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"cms_arbo_pages\" />

</class>";

var $sMySql = "CREATE TABLE cms_assoclassenode
(
	xca_id			int (11) PRIMARY KEY not null,
	xca_classe			int,
	xca_arbo_pages			int,
	xca_iffield			varchar (128),
	xca_ifvalue			varchar (128),
	xca_nodemodele			int
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_assoclassenode") == false){
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
		$this->arbo_pages = -1;
		$this->iffield = "";
		$this->ifvalue = "";
		$this->nodemodele = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xca_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xca_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Xca_arbo_pages", "entier", "get_arbo_pages", "set_arbo_pages");
	$laListeChamps[]=new dbChamp("Xca_iffield", "text", "get_iffield", "set_iffield");
	$laListeChamps[]=new dbChamp("Xca_ifvalue", "text", "get_ifvalue", "set_ifvalue");
	$laListeChamps[]=new dbChamp("Xca_nodemodele", "entier", "get_nodemodele", "set_nodemodele");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_classe() { return($this->classe); }
function get_arbo_pages() { return($this->arbo_pages); }
function get_iffield() { return($this->iffield); }
function get_ifvalue() { return($this->ifvalue); }
function get_nodemodele() { return($this->nodemodele); }


// setters
function set_id($c_xca_id) { return($this->id=$c_xca_id); }
function set_classe($c_xca_classe) { return($this->classe=$c_xca_classe); }
function set_arbo_pages($c_xca_arbo_pages) { return($this->arbo_pages=$c_xca_arbo_pages); }
function set_iffield($c_xca_iffield) { return($this->iffield=$c_xca_iffield); }
function set_ifvalue($c_xca_ifvalue) { return($this->ifvalue=$c_xca_ifvalue); }
function set_nodemodele($c_xca_nodemodele) { return($this->nodemodele=$c_xca_nodemodele); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xca_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assoclassenode"); }
function getClasse() { return("cms_assoclassenode"); }
function getDisplay() { return("classe"); }
function getAbstract() { return("arbo_pages"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode/list_cms_assoclassenode.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode/maj_cms_assoclassenode.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode/show_cms_assoclassenode.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode/rss_cms_assoclassenode.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode/xml_cms_assoclassenode.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode/xmlxls_cms_assoclassenode.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode/export_cms_assoclassenode.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoclassenode/import_cms_assoclassenode.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>