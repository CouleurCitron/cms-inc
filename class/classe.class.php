<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('classe')){
	$rs = $db->Execute('DESCRIBE `classe`');
	if (isset($rs->_numOfRows)){
		if ($rs->_numOfRows == 4){
			$rs = $db->Execute('ALTER TABLE `classe` ADD `cms_iscms` INT( 2 ) NULL DEFAULT \'0\' AFTER `cms_cms_site` ;');
			$rs = $db->Execute('UPDATE `classe` set cms_iscms = 0 WHERE cms_iscms = -1;');
			$rs = $db->Execute('UPDATE `classe` set cms_iscms = 1 WHERE `cms_nom` LIKE \'news%\';');
			$rs = $db->Execute('UPDATE `classe` set cms_iscms = 1 WHERE `cms_nom` LIKE \'cms%\';');
			$rs = $db->Execute('UPDATE `classe` set cms_iscms = 1 WHERE `cms_nom` LIKE \'bo_%\';');
		} 
	} 
}
/*======================================

objet de BDD classe :: class classe

SQL mySQL:

DROP TABLE IF EXISTS classe;
CREATE TABLE classe
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_nom			varchar (255),
	cms_cms_site			int (11) not null,
	cms_iscms			int (2),
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE classe
CREATE TABLE classe
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_nom			varchar2 (255),
	cms_cms_site			number (11) not null,
	cms_iscms			number (2),
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="classe" libelle="Classes" prefix="cms" display="nom" abstract="iscms">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assoclassepage" />
<item name="nom" libelle="Nom de la classe" type="varchar" length="255" list="true" order="true"  nohtml="true" />
<item name="cms_site" libelle="Mini site" type="int" length="11" notnull="true" default="-1" list="true" order="true" fkey="cms_site" />
<item name="iscms" libelle="CMS" type="int" length="2" list="true" order="true" default="0" option="bool" />

<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<langpack lang="fr">
<norecords>Pas de classe à afficher</norecords>
</langpack>
</class>


==========================================*/

class classe
{
var $id;
var $nom;
var $cms_site;
var $iscms;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"classe\" libelle=\"Classes\" prefix=\"cms\" display=\"nom\" abstract=\"iscms\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assoclassepage\" />
<item name=\"nom\" libelle=\"Nom de la classe\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"cms_site\" libelle=\"Mini site\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_site\" />
<item name=\"iscms\" libelle=\"CMS\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" default=\"0\" option=\"bool\" />

<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<langpack lang=\"fr\">
<norecords>Pas de classe à afficher</norecords>
</langpack>
</class>";

var $sMySql = "CREATE TABLE classe
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_nom			varchar (255),
	cms_cms_site			int (11) not null,
	cms_iscms			int (2),
	cms_statut			int (11) not null
)

";

// constructeur
function classe($id=null)
{
	if (istable("classe") == false){
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
		$this->cms_site = -1;
		$this->iscms = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Cms_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Cms_iscms", "entier", "get_iscms", "set_iscms");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_cms_site() { return($this->cms_site); }
function get_iscms() { return($this->iscms); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_nom($c_cms_nom) { return($this->nom=$c_cms_nom); }
function set_cms_site($c_cms_cms_site) { return($this->cms_site=$c_cms_cms_site); }
function set_iscms($c_cms_iscms) { return($this->iscms=$c_cms_iscms); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("classe"); }
function getClasse() { return("classe"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("iscms"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe/list_classe.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe/maj_classe.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe/show_classe.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe/rss_classe.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe/xml_classe.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe/xmlxls_classe.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe/export_classe.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/classe/import_classe.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>