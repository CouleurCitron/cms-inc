<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_moderation :: class cms_moderation

SQL mySQL:

DROP TABLE IF EXISTS cms_moderation;
CREATE TABLE cms_moderation
(
	mod_id			int (11) PRIMARY KEY not null,
	mod_titre			varchar (128),
	mod_textelong			text (1024),
	mod_cms_content			int (11),
	mod_dtcrea			date,
	mod_bo_users			int (11) not null
)

SQL Oracle:

DROP TABLE cms_moderation
CREATE TABLE cms_moderation
(
	mod_id			number (11) constraint mod_pk PRIMARY KEY not null,
	mod_titre			varchar2 (128),
	mod_textelong			text (1024),
	mod_cms_content			number (11),
	mod_dtcrea			date,
	mod_bo_users			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_moderation" libelle="Modération" prefix="mod" display="titre" abstract="dtcrea">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="titre" libelle="Titre" type="varchar" length="128" list="true" order="true" /> 
<item name="textelong" libelle="Commentaire" type="text" length="1024" list="true" order="true" option="textarea" rss="description" />
<item name="cms_content" libelle="Contenu" type="int" length="11" list="true" order="true" fkey="cms_content"/>
<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="bo_users" libelle="Modérateur" type="int"  length="11" notnull="true"  list="true" fkey="bo_users" />
<langpack lang="fr">
<norecords>Pas de modération à afficher</norecords>
</langpack>
</class> 


==========================================*/

class cms_moderation
{
var $id;
var $titre;
var $textelong;
var $cms_content;
var $dtcrea;
var $bo_users;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_moderation\" libelle=\"Modération\" prefix=\"mod\" display=\"titre\" abstract=\"dtcrea\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"titre\" libelle=\"Titre\" type=\"varchar\" length=\"128\" list=\"true\" order=\"true\" /> 
<item name=\"textelong\" libelle=\"Commentaire\" type=\"text\" length=\"1024\" list=\"true\" order=\"true\" option=\"textarea\" rss=\"description\" />
<item name=\"cms_content\" libelle=\"Contenu\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_content\"/>
<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"bo_users\" libelle=\"Modérateur\" type=\"int\"  length=\"11\" notnull=\"true\"  list=\"true\" fkey=\"bo_users\" />
<langpack lang=\"fr\">
<norecords>Pas de modération à afficher</norecords>
</langpack>
</class> ";

var $sMySql = "CREATE TABLE cms_moderation
(
	mod_id			int (11) PRIMARY KEY not null,
	mod_titre			varchar (128),
	mod_textelong			text (1024),
	mod_cms_content			int (11),
	mod_dtcrea			date,
	mod_bo_users			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_moderation") == false){
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
		$this->titre = "";
		$this->textelong = "";
		$this->cms_content = -1;
		$this->dtcrea = date("d/m/Y");
		$this->bo_users = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Mod_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Mod_titre", "text", "get_titre", "set_titre");
	$laListeChamps[]=new dbChamp("Mod_textelong", "text", "get_textelong", "set_textelong");
	$laListeChamps[]=new dbChamp("Mod_cms_content", "entier", "get_cms_content", "set_cms_content");
	$laListeChamps[]=new dbChamp("Mod_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Mod_bo_users", "entier", "get_bo_users", "set_bo_users");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_titre() { return($this->titre); }
function get_textelong() { return($this->textelong); }
function get_cms_content() { return($this->cms_content); }
function get_dtcrea() { return($this->dtcrea); }
function get_bo_users() { return($this->bo_users); }


// setters
function set_id($c_mod_id) { return($this->id=$c_mod_id); }
function set_titre($c_mod_titre) { return($this->titre=$c_mod_titre); }
function set_textelong($c_mod_textelong) { return($this->textelong=$c_mod_textelong); }
function set_cms_content($c_mod_cms_content) { return($this->cms_content=$c_mod_cms_content); }
function set_dtcrea($c_mod_dtcrea) { return($this->dtcrea=$c_mod_dtcrea); }
function set_bo_users($c_mod_bo_users) { return($this->bo_users=$c_mod_bo_users); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("mod_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_moderation"); }
function getClasse() { return("cms_moderation"); }
function getDisplay() { return("titre"); }
function getAbstract() { return("dtcrea"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation/list_cms_moderation.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation/maj_cms_moderation.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation/show_cms_moderation.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation/rss_cms_moderation.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation/xml_cms_moderation.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation/export_cms_moderation.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_moderation/import_cms_moderation.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>