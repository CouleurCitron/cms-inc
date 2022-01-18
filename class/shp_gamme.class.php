<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shp_gamme.class.php')  && (strpos(__FILE__,'/include/bo/class/shp_gamme.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shp_gamme.class.php');
}else{
/*======================================

objet de BDD shp_gamme :: class shp_gamme

SQL mySQL:

DROP TABLE IF EXISTS shp_gamme;
CREATE TABLE shp_gamme
(
	shp_gam_id			int (4) PRIMARY KEY not null,
	shp_gam_id_site			int (11) not null,
	shp_gam_id_gamme			int (4),
	shp_gam_id_left			int (6) not null,
	shp_gam_id_right			int (6) not null,
	shp_gam_id_diaporama			int (11),
	shp_gam_statut			int (2) not null,
	shp_gam_titre_court			int (11) not null,
	shp_gam_titre_long			int (11),
	shp_gam_sous_titre			int (11),
	shp_gam_texte_long			int (11),
	shp_gam_couleur_hex			varchar (6),
	shp_gam_vignette			varchar (256),
	shp_gam_visuel			varchar (256),
	shp_gam_delai_livraison			int (3),
	shp_gam_ordre			int (11),
	shp_gam_cdate			datetime not null,
	shp_gam_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_gamme
CREATE TABLE shp_gamme
(
	shp_gam_id			number (4) constraint shp_gam_pk PRIMARY KEY not null,
	shp_gam_id_site			number (11) not null,
	shp_gam_id_gamme			number (4),
	shp_gam_id_left			number (6) not null,
	shp_gam_id_right			number (6) not null,
	shp_gam_id_diaporama			number (11),
	shp_gam_statut			number (2) not null,
	shp_gam_titre_court			number (11) not null,
	shp_gam_titre_long			number (11),
	shp_gam_sous_titre			number (11),
	shp_gam_texte_long			number (11),
	shp_gam_couleur_hex			varchar2 (6),
	shp_gam_vignette			varchar2 (256),
	shp_gam_visuel			varchar2 (256),
	shp_gam_delai_livraison			number (3),
	shp_gam_ordre			number (11),
	shp_gam_cdate			datetime not null,
	shp_gam_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_gamme" libelle="Gamme de la boutique" prefix="shp_gam" display="titre_court" abstract="titre_court">
<item name="id" type="int" length="4" isprimary="true" notnull="true" default="-1" list="true" asso="shp_asso_gammes" /> 
<item name="id_site" libelle="Présent dans le site" type="int" length="11" fkey="cms_site" notnull="true" default="0" />
<item name="id_gamme" libelle="Gamme parente" type="int" length="4" fkey="shp_gamme" default="0" list="true" order="true" /> 
<item name="id_left" libelle="Tri gauche" type="int" length="6" notnull="true" default="0" display="false" noedit="true" />
<item name="id_right" libelle="Tri droit" type="int" length="6" notnull="true" default="0" display="false" noedit="true" /> 
<item name="id_diaporama" libelle="Diaporama" type="int" length="11" fkey="cms_diaporama" list="true" order="true" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="titre_court" libelle="Titre court" type="int" length="11" notnull="true" default="0" list="true" order="true" nohtml="true" oblig="true" translate="reference" />
<item name="titre_long" libelle="Titre long" type="int" length="11" default="0" nohtml="true" translate="reference" />
<item name="sous_titre" libelle="Sous-titre" type="int" length="11" default="0" translate="reference" option="textarea" />
<item name="texte_long" libelle="Texte long" type="int" length="11" default="0" translate="reference" option="textarea" />
<item name="couleur_hex" libelle="Teinte personnalisée" type="varchar" length="6" default="" nohtml="true" />
<item name="vignette" libelle="Vignette" type="varchar" length="256" default="" option="file" />
<item name="visuel" libelle="Visuel" type="varchar" length="256" default="" option="file" />
<item name="delai_livraison" libelle="Délai de livraison (jours)" type="int" length="3" default="12" />
<item name="ordre" libelle="Ordre d'apparition" type="int" length="11" list="true" order="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_gamme
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $id_site;
var $id_gamme;
var $id_left;
var $id_right;
var $id_diaporama;
var $statut;
var $titre_court;
var $titre_long;
var $sous_titre;
var $texte_long;
var $couleur_hex;
var $vignette;
var $visuel;
var $delai_livraison;
var $ordre;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_gamme\" libelle=\"Gamme de la boutique\" prefix=\"shp_gam\" display=\"titre_court\" abstract=\"titre_court\">
<item name=\"id\" type=\"int\" length=\"4\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"shp_asso_gammes\" /> 
<item name=\"id_site\" libelle=\"Présent dans le site\" type=\"int\" length=\"11\" fkey=\"cms_site\" notnull=\"true\" default=\"0\" />
<item name=\"id_gamme\" libelle=\"Gamme parente\" type=\"int\" length=\"4\" fkey=\"shp_gamme\" default=\"0\" list=\"true\" order=\"true\" /> 
<item name=\"id_left\" libelle=\"Tri gauche\" type=\"int\" length=\"6\" notnull=\"true\" default=\"0\" display=\"false\" noedit=\"true\" />
<item name=\"id_right\" libelle=\"Tri droit\" type=\"int\" length=\"6\" notnull=\"true\" default=\"0\" display=\"false\" noedit=\"true\" /> 
<item name=\"id_diaporama\" libelle=\"Diaporama\" type=\"int\" length=\"11\" fkey=\"cms_diaporama\" list=\"true\" order=\"true\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"titre_court\" libelle=\"Titre court\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" nohtml=\"true\" oblig=\"true\" translate=\"reference\" />
<item name=\"titre_long\" libelle=\"Titre long\" type=\"int\" length=\"11\" default=\"0\" nohtml=\"true\" translate=\"reference\" />
<item name=\"sous_titre\" libelle=\"Sous-titre\" type=\"int\" length=\"11\" default=\"0\" translate=\"reference\" option=\"textarea\" />
<item name=\"texte_long\" libelle=\"Texte long\" type=\"int\" length=\"11\" default=\"0\" translate=\"reference\" option=\"textarea\" />
<item name=\"couleur_hex\" libelle=\"Teinte personnalisée\" type=\"varchar\" length=\"6\" default=\"\" nohtml=\"true\" />
<item name=\"vignette\" libelle=\"Vignette\" type=\"varchar\" length=\"256\" default=\"\" option=\"file\" />
<item name=\"visuel\" libelle=\"Visuel\" type=\"varchar\" length=\"256\" default=\"\" option=\"file\" />
<item name=\"delai_livraison\" libelle=\"Délai de livraison (jours)\" type=\"int\" length=\"3\" default=\"12\" />
<item name=\"ordre\" libelle=\"Ordre d'apparition\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE shp_gamme
(
	shp_gam_id			int (4) PRIMARY KEY not null,
	shp_gam_id_site			int (11) not null,
	shp_gam_id_gamme			int (4),
	shp_gam_id_left			int (6) not null,
	shp_gam_id_right			int (6) not null,
	shp_gam_id_diaporama			int (11),
	shp_gam_statut			int (2) not null,
	shp_gam_titre_court			int (11) not null,
	shp_gam_titre_long			int (11),
	shp_gam_sous_titre			int (11),
	shp_gam_texte_long			int (11),
	shp_gam_couleur_hex			varchar (6),
	shp_gam_vignette			varchar (256),
	shp_gam_visuel			varchar (256),
	shp_gam_delai_livraison			int (3),
	shp_gam_ordre			int (11),
	shp_gam_cdate			datetime not null,
	shp_gam_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function __construct($id=null)
{
	if (istable(get_class($this)) == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
		if(array_key_exists('0',$this->inherited_list)){
			foreach($this->inherited_list as $class){
				if(!is_object($class))
					$this->inherited[$class] = dbGetObjectFromPK($class, $id);
			}
		}
	} else {
		$this->id = -1;
		$this->id_site = -1;
		$this->id_gamme = -1;
		$this->id_left = -1;
		$this->id_right = -1;
		$this->id_diaporama = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->titre_court = -1;
		$this->titre_long = -1;
		$this->sous_titre = -1;
		$this->texte_long = -1;
		$this->couleur_hex = "";
		$this->vignette = "";
		$this->visuel = "";
		$this->delai_livraison = 12;
		$this->ordre = -1;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
		if(array_key_exists('0',$this->inherited_list)){
			foreach($this->inherited_list as $class){
				if(!is_object($class))
					$this->inherited[$class] = new $class();
			}
		}
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_gam_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_gam_id_site", "entier", "get_id_site", "set_id_site");
	$laListeChamps[]=new dbChamp("Shp_gam_id_gamme", "entier", "get_id_gamme", "set_id_gamme");
	$laListeChamps[]=new dbChamp("Shp_gam_id_left", "entier", "get_id_left", "set_id_left");
	$laListeChamps[]=new dbChamp("Shp_gam_id_right", "entier", "get_id_right", "set_id_right");
	$laListeChamps[]=new dbChamp("Shp_gam_id_diaporama", "entier", "get_id_diaporama", "set_id_diaporama");
	$laListeChamps[]=new dbChamp("Shp_gam_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_gam_titre_court", "entier", "get_titre_court", "set_titre_court");
	$laListeChamps[]=new dbChamp("Shp_gam_titre_long", "entier", "get_titre_long", "set_titre_long");
	$laListeChamps[]=new dbChamp("Shp_gam_sous_titre", "entier", "get_sous_titre", "set_sous_titre");
	$laListeChamps[]=new dbChamp("Shp_gam_texte_long", "entier", "get_texte_long", "set_texte_long");
	$laListeChamps[]=new dbChamp("Shp_gam_couleur_hex", "text", "get_couleur_hex", "set_couleur_hex");
	$laListeChamps[]=new dbChamp("Shp_gam_vignette", "text", "get_vignette", "set_vignette");
	$laListeChamps[]=new dbChamp("Shp_gam_visuel", "text", "get_visuel", "set_visuel");
	$laListeChamps[]=new dbChamp("Shp_gam_delai_livraison", "entier", "get_delai_livraison", "set_delai_livraison");
	$laListeChamps[]=new dbChamp("Shp_gam_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Shp_gam_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_gam_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_site() { return($this->id_site); }
function get_id_gamme() { return($this->id_gamme); }
function get_id_left() { return($this->id_left); }
function get_id_right() { return($this->id_right); }
function get_id_diaporama() { return($this->id_diaporama); }
function get_statut() { return($this->statut); }
function get_titre_court() { return($this->titre_court); }
function get_titre_long() { return($this->titre_long); }
function get_sous_titre() { return($this->sous_titre); }
function get_texte_long() { return($this->texte_long); }
function get_couleur_hex() { return($this->couleur_hex); }
function get_vignette() { return($this->vignette); }
function get_visuel() { return($this->visuel); }
function get_delai_livraison() { return($this->delai_livraison); }
function get_ordre() { return($this->ordre); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_gam_id) { return($this->id=$c_shp_gam_id); }
function set_id_site($c_shp_gam_id_site) { return($this->id_site=$c_shp_gam_id_site); }
function set_id_gamme($c_shp_gam_id_gamme) { return($this->id_gamme=$c_shp_gam_id_gamme); }
function set_id_left($c_shp_gam_id_left) { return($this->id_left=$c_shp_gam_id_left); }
function set_id_right($c_shp_gam_id_right) { return($this->id_right=$c_shp_gam_id_right); }
function set_id_diaporama($c_shp_gam_id_diaporama) { return($this->id_diaporama=$c_shp_gam_id_diaporama); }
function set_statut($c_shp_gam_statut) { return($this->statut=$c_shp_gam_statut); }
function set_titre_court($c_shp_gam_titre_court) { return($this->titre_court=$c_shp_gam_titre_court); }
function set_titre_long($c_shp_gam_titre_long) { return($this->titre_long=$c_shp_gam_titre_long); }
function set_sous_titre($c_shp_gam_sous_titre) { return($this->sous_titre=$c_shp_gam_sous_titre); }
function set_texte_long($c_shp_gam_texte_long) { return($this->texte_long=$c_shp_gam_texte_long); }
function set_couleur_hex($c_shp_gam_couleur_hex) { return($this->couleur_hex=$c_shp_gam_couleur_hex); }
function set_vignette($c_shp_gam_vignette) { return($this->vignette=$c_shp_gam_vignette); }
function set_visuel($c_shp_gam_visuel) { return($this->visuel=$c_shp_gam_visuel); }
function set_delai_livraison($c_shp_gam_delai_livraison) { return($this->delai_livraison=$c_shp_gam_delai_livraison); }
function set_ordre($c_shp_gam_ordre) { return($this->ordre=$c_shp_gam_ordre); }
function set_cdate($c_shp_gam_cdate) { return($this->cdate=$c_shp_gam_cdate); }
function set_mdate($c_shp_gam_mdate) { return($this->mdate=$c_shp_gam_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_gam_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_gam_statut"); }
//
function getTable() { return("shp_gamme"); }
function getClasse() { return("shp_gamme"); }
function getPrefix() { return("shp_gam"); }
function getDisplay() { return("titre_court"); }
function getAbstract() { return("titre_court"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme/list_shp_gamme.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[newSizeOf(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme/maj_shp_gamme.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme/show_shp_gamme.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme/rss_shp_gamme.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme/xml_shp_gamme.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme/xlsx_shp_gamme.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme/export_shp_gamme.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_gamme/import_shp_gamme.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>