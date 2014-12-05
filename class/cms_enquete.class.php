<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_enquete :: class cms_enquete

SQL mySQL:

DROP TABLE IF EXISTS cms_enquete;
CREATE TABLE cms_enquete
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_datecrea			date,
	cms_titre			varchar (255)
)

SQL Oracle:

DROP TABLE cms_enquete
CREATE TABLE cms_enquete
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_datecrea			date,
	cms_titre			varchar2 (255)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_enquete" libelle="Brique Enquete" prefix="cms" display="titre" abstract="id" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" asso="cms_assoenquetequestion"/>
<item name="datecrea" libelle="Date de l'actualité" type="date" list="true" order="true" format="l j F Y"/>  
<item name="titre" libelle="Titre" type="varchar" length="255" list="true" order="true" />
<langpack lang="fr">
<norecords>Aucune enquête à afficher</norecords>
</langpack>
</class>


==========================================*/

class cms_enquete
{
var $id;
var $datecrea;
var $titre;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_enquete\" libelle=\"Brique Enquete\" prefix=\"cms\" display=\"titre\" abstract=\"id\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" asso=\"cms_assoenquetequestion\"/>
<item name=\"datecrea\" libelle=\"Date de l'actualité\" type=\"date\" list=\"true\" order=\"true\" format=\"l j F Y\"/>  
<item name=\"titre\" libelle=\"Titre\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" />
<langpack lang=\"fr\">
<norecords>Aucune enquête à afficher</norecords>
</langpack>
</class>";

var $sMySql = "CREATE TABLE cms_enquete
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_datecrea			date,
	cms_titre			varchar (255)
)

";

// constructeur
function cms_enquete($id=null)
{
	if (istable("cms_enquete") == false){
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
		$this->datecrea = date("d/m/Y");
		$this->titre = "";
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_datecrea", "date_formatee", "get_datecrea", "set_datecrea");
	$laListeChamps[]=new dbChamp("Cms_titre", "text", "get_titre", "set_titre");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_datecrea() { return($this->datecrea); }
function get_titre() { return($this->titre); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_datecrea($c_cms_datecrea) { return($this->datecrea=$c_cms_datecrea); }
function set_titre($c_cms_titre) { return($this->titre=$c_cms_titre); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_enquete"); }
function getClasse() { return("cms_enquete"); }
function getDisplay() { return("titre"); }
function getAbstract() { return("id"); }


} //class
/*

// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete/list_cms_enquete.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete/maj_cms_enquete.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete/show_cms_enquete.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete/rss_cms_enquete.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete/xml_cms_enquete.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete/xmlxls_cms_enquete.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete/export_cms_enquete.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_enquete/import_cms_enquete.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>