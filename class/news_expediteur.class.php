<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD news_expediteur :: class news_expediteur

SQL mySQL:

DROP TABLE IF EXISTS news_expediteur;
CREATE TABLE news_expediteur
(
	exp_id			int (11) PRIMARY KEY not null,
	exp_nom			varchar (255),
	exp_prenom			varchar (255),
	exp_mail			varchar (255),
	exp_statut			int (11) not null
)

SQL Oracle:

DROP TABLE news_expediteur
CREATE TABLE news_expediteur
(
	exp_id			number (11) constraint exp_pk PRIMARY KEY not null,
	exp_nom			varchar2 (255),
	exp_prenom			varchar2 (255),
	exp_mail			varchar2 (255),
	exp_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_expediteur" prefix="exp" display="mail" abstract="nom">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="nom" libelle="Nom" type="varchar" length="255" list="true" order="true" nohtml="true"/>
<item name="prenom" libelle="Prénom" type="varchar" length="255" list="true" order="true"  nohtml="true"/>
<item name="mail" libelle="Email" type="varchar" length="255" list="true" order="true"  nohtml="true"/>
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
</class>


==========================================*/

class news_expediteur
{
var $id;
var $nom;
var $prenom;
var $mail;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_expediteur\" prefix=\"exp\" display=\"mail\" abstract=\"nom\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\"/>
<item name=\"prenom\" libelle=\"Prénom\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\"/>
<item name=\"mail\" libelle=\"Email\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\"/>
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
</class>";

var $sMySql = "CREATE TABLE news_expediteur
(
	exp_id			int (11) PRIMARY KEY not null,
	exp_nom			varchar (255),
	exp_prenom			varchar (255),
	exp_mail			varchar (255),
	exp_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("news_expediteur") == false){
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
		$this->prenom = "";
		$this->mail = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Exp_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Exp_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Exp_prenom", "text", "get_prenom", "set_prenom");
	$laListeChamps[]=new dbChamp("Exp_mail", "text", "get_mail", "set_mail");
	$laListeChamps[]=new dbChamp("Exp_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_prenom() { return($this->prenom); }
function get_mail() { return($this->mail); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_exp_id) { return($this->id=$c_exp_id); }
function set_nom($c_exp_nom) { return($this->nom=$c_exp_nom); }
function set_prenom($c_exp_prenom) { return($this->prenom=$c_exp_prenom); }
function set_mail($c_exp_mail) { return($this->mail=$c_exp_mail); }
function set_statut($c_exp_statut) { return($this->statut=$c_exp_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("exp_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("exp_statut"); }
//
function getTable() { return("news_expediteur"); }
function getClasse() { return("news_expediteur"); }
function getDisplay() { return("mail"); }
function getAbstract() { return("nom"); }


} //class
/*

// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur/list_news_expediteur.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur/maj_news_expediteur.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur/show_news_expediteur.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur/rss_news_expediteur.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur/xml_news_expediteur.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur/export_news_expediteur.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_expediteur/import_news_expediteur.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/
?>