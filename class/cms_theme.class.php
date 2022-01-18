<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_theme :: class cms_theme

SQL mySQL:

DROP TABLE IF EXISTS cms_theme;
CREATE TABLE cms_theme
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_nom			varchar (128),
	cms_desc			varchar (512),
	cms_vignette			varchar (255),
	cms_classes			text (1024),
	cms_tags			text (1024),
	cms_editor			text (1024),
	cms_ie			text (1024),
	cms_editortargs			text (1024),
	cms_site			int (11),
	cms_dtcrea			date,
	cms_dtmod			date,
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_theme
CREATE TABLE cms_theme
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_nom			varchar2 (128),
	cms_desc			varchar2 (512),
	cms_vignette			varchar2 (255),
	cms_classes			text (1024),
	cms_tags			text (1024),
	cms_editor			text (1024),
	cms_ie			text (1024),
	cms_editortargs			text (1024),
	cms_site			number (11),
	cms_dtcrea			date,
	cms_dtmod			date,
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_theme" libelle="Thèmes" prefix="cms" display="nom" abstract="site">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="nom" libelle="Nom" type="varchar" length="128" list="true" order="true" /> 
<item name="desc" libelle="Description" type="varchar" length="512" option="textarea" /> 
<item name="vignette" libelle="Vignette" type="varchar" length="255" option="file"/>
<item name="classes" libelle="Classes" type="text" length="1024" option="textarea" /> 
<item name="tags" libelle="Tags" type="text" length="1024" option="textarea" /> 
<item name="editor" libelle="Classes pour l'éditeur" type="text" length="1024" option="textarea" /> 
<item name="ie" libelle="CSS pour IE" type="text" length="1024" option="textarea" /> 
<item name="editortargs" libelle="Tags pour l'éditeur" type="text" length="1024" list="true" order="true" default="" oblig="true" option="objectset">
<object>{tag:nom}</object>
</item>
<item name="site" libelle="Site" type="int" length="11" list="true" order="true" fkey="cms_site"/>
<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
<langpack lang="fr">
<norecords>Pas de theme à afficher</norecords>
</langpack>
</class> 



==========================================*/

class cms_theme
{
var $id;
var $nom;
var $desc;
var $vignette;
var $classes;
var $tags;
var $editor;
var $ie;
var $editortargs;
var $site;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_theme\" libelle=\"Thèmes\" prefix=\"cms\" display=\"nom\" abstract=\"site\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"128\" list=\"true\" order=\"true\" /> 
<item name=\"desc\" libelle=\"Description\" type=\"varchar\" length=\"512\" option=\"textarea\" /> 
<item name=\"vignette\" libelle=\"Vignette\" type=\"varchar\" length=\"255\" option=\"file\"/>
<item name=\"classes\" libelle=\"Classes\" type=\"text\" length=\"1024\" option=\"textarea\" /> 
<item name=\"tags\" libelle=\"Tags\" type=\"text\" length=\"1024\" option=\"textarea\" /> 
<item name=\"editor\" libelle=\"Classes pour l'éditeur\" type=\"text\" length=\"1024\" option=\"textarea\" /> 
<item name=\"ie\" libelle=\"CSS pour IE\" type=\"text\" length=\"1024\" option=\"textarea\" /> 
<item name=\"editortargs\" libelle=\"Tags pour l'éditeur\" type=\"text\" length=\"1024\" list=\"true\" order=\"true\" default=\"\" oblig=\"true\" option=\"objectset\">
<object>{tag:nom}</object>
</item>
<item name=\"site\" libelle=\"Site\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_site\"/>
<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
<langpack lang=\"fr\">
<norecords>Pas de theme à afficher</norecords>
</langpack>
</class> 
";

var $sMySql = "CREATE TABLE cms_theme
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_nom			varchar (128),
	cms_desc			varchar (512),
	cms_vignette			varchar (255),
	cms_classes			text (1024),
	cms_tags			text (1024),
	cms_editor			text (1024),
	cms_ie			text (1024),
	cms_editortargs			text (1024),
	cms_site			int (11),
	cms_dtcrea			date,
	cms_dtmod			date,
	cms_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_theme") == false){
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
		$this->nom = "";
		$this->desc = "";
		$this->vignette = "";
		$this->classes = "";
		$this->tags = "";
		$this->editor = "";
		$this->ie = "";
		$this->editortargs = "";
		$this->site = -1;
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Cms_desc", "text", "get_desc", "set_desc");
	$laListeChamps[]=new dbChamp("Cms_vignette", "text", "get_vignette", "set_vignette");
	$laListeChamps[]=new dbChamp("Cms_classes", "text", "get_classes", "set_classes");
	$laListeChamps[]=new dbChamp("Cms_tags", "text", "get_tags", "set_tags");
	$laListeChamps[]=new dbChamp("Cms_editor", "text", "get_editor", "set_editor");
	$laListeChamps[]=new dbChamp("Cms_ie", "text", "get_ie", "set_ie");
	$laListeChamps[]=new dbChamp("Cms_editortargs", "text", "get_editortargs", "set_editortargs");
	$laListeChamps[]=new dbChamp("Cms_site", "entier", "get_site", "set_site");
	$laListeChamps[]=new dbChamp("Cms_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Cms_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_desc() { return($this->desc); }
function get_vignette() { return($this->vignette); }
function get_classes() { return($this->classes); }
function get_tags() { return($this->tags); }
function get_editor() { return($this->editor); }
function get_ie() { return($this->ie); }
function get_editortargs() { return($this->editortargs); }
function get_site() { return($this->site); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_nom($c_cms_nom) { return($this->nom=$c_cms_nom); }
function set_desc($c_cms_desc) { return($this->desc=$c_cms_desc); }
function set_vignette($c_cms_vignette) { return($this->vignette=$c_cms_vignette); }
function set_classes($c_cms_classes) { return($this->classes=$c_cms_classes); }
function set_tags($c_cms_tags) { return($this->tags=$c_cms_tags); }
function set_editor($c_cms_editor) { return($this->editor=$c_cms_editor); }
function set_ie($c_cms_ie) { return($this->ie=$c_cms_ie); }
function set_editortargs($c_cms_editortargs) { return($this->editortargs=$c_cms_editortargs); }
function set_site($c_cms_site) { return($this->site=$c_cms_site); }
function set_dtcrea($c_cms_dtcrea) { return($this->dtcrea=$c_cms_dtcrea); }
function set_dtmod($c_cms_dtmod) { return($this->dtmod=$c_cms_dtmod); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_theme"); }
function getClasse() { return("cms_theme"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("site"); }

function saveToFile(){
	$file = '/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_'.strtolower(removeForbiddenChars($this->nom, false)).'.css';
	$fileIE = '/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_'.strtolower(removeForbiddenChars($this->nom, false)).'_ie.css';
	
	// MOZ
	$css = '/***** classes *****/'."\n";
	$css .= $this->classes;
	$css .= "\n".'/***** tags *****/'."\n";
	$css .= $this->tags;	
	$fh = fopen($_SERVER['DOCUMENT_ROOT'].$file, "w");
	fwrite($fh, $css);	
	fclose($fh);
	
	// IE
	$css = '/***** ie *****/'."\n";
	$css .= $this->ie;	
	if (trim($css)!='/***** ie *****/'){
		$fh = fopen($_SERVER['DOCUMENT_ROOT'].$fileIE, "w");
		fwrite($fh, $css);	
		fclose($fh);
	}
	return array($file, $fileIE);
}


function loadFromFile(){
	$file = '/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_'.strtolower(removeForbiddenChars($this->nom)).'.css';
	$fileIE = '/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_'.strtolower(removeForbiddenChars($this->nom)).'_ie.css';
	
	// MOZ
	$css = file_get_contents($_SERVER['DOCUMENT_ROOT'].$file);
	$aCss = explode('/***** tags *****/', $css);	
	$this->classes = trim(str_replace('/***** classes *****/', '', $aCss[0]));
	$this->tags = trim($aCss[1]);
	
	// IE
	$css = file_get_contents($_SERVER['DOCUMENT_ROOT'].$fileIE);
	$this->ie = trim(str_replace('/***** ie *****/', '', $css));
	
	return true;
}


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme/list_cms_theme.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme/maj_cms_theme.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme/show_cms_theme.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme/rss_cms_theme.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme/xml_cms_theme.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme/xmlxls_cms_theme.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme/export_cms_theme.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_theme/import_cms_theme.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>