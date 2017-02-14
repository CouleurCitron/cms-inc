<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_question :: class cms_question

SQL mySQL:

DROP TABLE IF EXISTS cms_question;
CREATE TABLE cms_question
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_titre			varchar (255),
	cms_type			int (2),
	cms_valeur			varchar (255),
	cms_obligatoire			int (2)
)

SQL Oracle:

DROP TABLE cms_question
CREATE TABLE cms_question
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_titre			varchar2 (255),
	cms_type			number (2),
	cms_valeur			varchar2 (255),
	cms_obligatoire			number (2)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_question" libelle="Brique Enquete - Question" prefix="cms" display="id" abstract="titre" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1"/>
<item name="titre" libelle="Titre" type="varchar" length="255" list="true" order="true" />
<item name="type" type="int" length="2" list="true" order="true" option="enum">
<option type="value" value="0" libelle="champ texte" />
<option type="value" value="1" libelle="champ case à cocher" />
<option type="value" value="2" libelle="champ bouton radio" />
<option type="value" value="3" libelle="champ liste déroulante" />
<option type="value" value="4" libelle="champ zone de texte" />
</item>
<item name="valeur" libelle="Valeur" type="varchar" length="255" list="true" order="true" />
<item name="obligatoire" libelle="Obligatoire" type="int" length="2" list="true" order="true" option="bool"/>
<langpack lang="fr">
<norecords>Aucune enquête à afficher</norecords>
</langpack>
</class>


==========================================*/

class cms_question
{
var $id;
var $titre;
var $type;
var $valeur;
var $obligatoire;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_question\" libelle=\"Brique Enquete - Question\" prefix=\"cms\" display=\"id\" abstract=\"titre\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\"/>
<item name=\"titre\" libelle=\"Titre\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" />
<item name=\"type\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" option=\"enum\">
<option type=\"value\" value=\"0\" libelle=\"champ texte\" />
<option type=\"value\" value=\"1\" libelle=\"champ case à cocher\" />
<option type=\"value\" value=\"2\" libelle=\"champ bouton radio\" />
<option type=\"value\" value=\"3\" libelle=\"champ liste déroulante\" />
<option type=\"value\" value=\"4\" libelle=\"champ zone de texte\" />
</item>
<item name=\"valeur\" libelle=\"Valeur\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" />
<item name=\"obligatoire\" libelle=\"Obligatoire\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" option=\"bool\"/>
<langpack lang=\"fr\">
<norecords>Aucune enquête à afficher</norecords>
</langpack>
</class>";

var $sMySql = "CREATE TABLE cms_question
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_titre			varchar (255),
	cms_type			int (2),
	cms_valeur			varchar (255),
	cms_obligatoire			int (2)
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_question") == false){
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
		$this->type = -1;
		$this->valeur = "";
		$this->obligatoire = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_titre", "text", "get_titre", "set_titre");
	$laListeChamps[]=new dbChamp("Cms_type", "entier", "get_type", "set_type");
	$laListeChamps[]=new dbChamp("Cms_valeur", "text", "get_valeur", "set_valeur");
	$laListeChamps[]=new dbChamp("Cms_obligatoire", "entier", "get_obligatoire", "set_obligatoire");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_titre() { return($this->titre); }
function get_type() { return($this->type); }
function get_valeur() { return($this->valeur); }
function get_obligatoire() { return($this->obligatoire); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_titre($c_cms_titre) { return($this->titre=$c_cms_titre); }
function set_type($c_cms_type) { return($this->type=$c_cms_type); }
function set_valeur($c_cms_valeur) { return($this->valeur=$c_cms_valeur); }
function set_obligatoire($c_cms_obligatoire) { return($this->obligatoire=$c_cms_obligatoire); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_question"); }
function getClasse() { return("cms_question"); }
function getDisplay() { return("id"); }
function getAbstract() { return("titre"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question/list_cms_question.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question/maj_cms_question.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question/show_cms_question.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question/rss_cms_question.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question/xml_cms_question.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question/xmlxls_cms_question.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question/export_cms_question.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_question/import_cms_question.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>