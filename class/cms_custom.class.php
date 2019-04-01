<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_custom :: class cms_custom

SQL mySQL:

DROP TABLE IF EXISTS cms_custom;
CREATE TABLE cms_custom
(
	cus_id			int (11) PRIMARY KEY not null,
	cus_classe			int (11),
	cus_param			varchar (32),
	cus_valeur			varchar (128)
)

SQL Oracle:

DROP TABLE cms_custom
CREATE TABLE cms_custom
(
	cus_id			number (11) constraint cus_pk PRIMARY KEY not null,
	cus_classe			number (11),
	cus_param			varchar2 (32),
	cus_valeur			varchar2 (128)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_custom" libelle="Paramètres custom des classes" prefix="cus" display="param" abstract="classe" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="classe" libelle="Classe associée" type="int" length="11" list="true" order="true" fkey="classe" />
<item name="param" libelle="Paramètre" type="varchar" length="32" default="" order="true" list="true"/> 
<item name="valeur" libelle="Valeur" type="varchar" length="128" default="" order="true" list="true"/> 
<langpack lang="fr">
<norecords>Pas de donnée à afficher</norecords>
</langpack>
</class>  


==========================================*/

class cms_custom
{
var $id;
var $classe;
var $param;
var $valeur;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_custom\" libelle=\"Paramètres custom des classes\" prefix=\"cus\" display=\"param\" abstract=\"classe\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"classe\" libelle=\"Classe associée\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"classe\" />
<item name=\"param\" libelle=\"Paramètre\" type=\"varchar\" length=\"32\" default=\"\" order=\"true\" list=\"true\"/> 
<item name=\"valeur\" libelle=\"Valeur\" type=\"varchar\" length=\"128\" default=\"\" order=\"true\" list=\"true\"/> 
<langpack lang=\"fr\">
<norecords>Pas de donnée à afficher</norecords>
</langpack>
</class>  ";

var $sMySql = "CREATE TABLE cms_custom
(
	cus_id			int (11) PRIMARY KEY not null,
	cus_classe			int (11),
	cus_param			varchar (32),
	cus_valeur			varchar (128)
)

";

// constructeur
function cms_custom($id=null)
{
	if (istable("cms_custom") == false){
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
		$this->param = "";
		$this->valeur = "";
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cus_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cus_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Cus_param", "text", "get_param", "set_param");
	$laListeChamps[]=new dbChamp("Cus_valeur", "text", "get_valeur", "set_valeur");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_classe() { return($this->classe); }
function get_param() { return($this->param); }
function get_valeur() { return($this->valeur); }


// setters
function set_id($c_cus_id) { return($this->id=$c_cus_id); }
function set_classe($c_cus_classe) { return($this->classe=$c_cus_classe); }
function set_param($c_cus_param) { return($this->param=$c_cus_param); }
function set_valeur($c_cus_valeur) { return($this->valeur=$c_cus_valeur); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cus_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_custom"); }
function getClasse() { return("cms_custom"); }
function getDisplay() { return("param"); }
function getAbstract() { return("classe"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom/list_cms_custom.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom/maj_cms_custom.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom/show_cms_custom.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom/rss_cms_custom.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom/xml_cms_custom.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom/export_cms_custom.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_custom/import_cms_custom.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>