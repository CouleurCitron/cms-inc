<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD bo_groupes :: class bo_groupes

SQL mySQL:

DROP TABLE IF EXISTS bo_groupes;
CREATE TABLE bo_groupes
(
	grp_id			int (11) PRIMARY KEY not null,
	grp_titre			varchar (255),
	grp_desc			varchar (255),
	grp_ordre			int (11) not null
)

SQL Oracle:

DROP TABLE bo_groupes
CREATE TABLE bo_groupes
(
	grp_id			number (11) constraint grp_pk PRIMARY KEY not null,
	grp_titre			varchar2 (255),
	grp_desc			varchar2 (255),
	grp_ordre			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="bo_groupes" libelle="Groupes d'user de back-office" prefix="grp" display="titre" abstract="desc">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assosectiongroupe" />
<item name="titre" libelle="Nom du groupe" type="varchar" length="255" list="true" order="true"  nohtml="true" />
<item name="desc" libelle="Description du groupe" type="varchar" length="255" list="true" order="true"  nohtml="true" />
<item name="ordre" type="int" length="11" notnull="true" default="0" list="true" order="true" />
<langpack lang="fr">
<norecords>Pas de groupe à afficher</norecords>
</langpack>
</class>



==========================================*/

class bo_groupes
{
var $id;
var $titre;
var $desc;
var $ordre;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"bo_groupes\" libelle=\"Groupes d\'user de back-office\" prefix=\"grp\" display=\"titre\" abstract=\"desc\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assosectiongroupe\" />
<item name=\"titre\" libelle=\"Nom du groupe\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"desc\" libelle=\"Description du groupe\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"ordre\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<langpack lang=\"fr\">
<norecords>Pas de groupe à afficher</norecords>
</langpack>
</class>
";

var $sMySql = "CREATE TABLE bo_groupes
(
	grp_id			int (11) PRIMARY KEY not null,
	grp_titre			varchar (255),
	grp_desc			varchar (255),
	grp_ordre			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("bo_groupes") == false){
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
		$this->desc = "";
		$this->ordre = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Grp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Grp_titre", "text", "get_titre", "set_titre");
	$laListeChamps[]=new dbChamp("Grp_desc", "text", "get_desc", "set_desc");
	$laListeChamps[]=new dbChamp("Grp_ordre", "entier", "get_ordre", "set_ordre");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_titre() { return($this->titre); }
function get_desc() { return($this->desc); }
function get_ordre() { return($this->ordre); }


// setters
function set_id($c_grp_id) { return($this->id=$c_grp_id); }
function set_titre($c_grp_titre) { return($this->titre=$c_grp_titre); }
function set_desc($c_grp_desc) { return($this->desc=$c_grp_desc); }
function set_ordre($c_grp_ordre) { return($this->ordre=$c_grp_ordre); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("grp_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("bo_groupes"); }
function getClasse() { return("bo_groupes"); }
function getDisplay() { return("titre"); }
function getAbstract() { return("desc"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes/list_bo_groupes.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes/maj_bo_groupes.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes/show_bo_groupes.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes/rss_bo_groupes.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes/xml_bo_groupes.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes/export_bo_groupes.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_groupes/import_bo_groupes.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>