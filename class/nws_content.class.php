<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/nws_content.class.php')  && (strpos(__FILE__,'/include/bo/class/nws_content.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/nws_content.class.php');
}else{
/*======================================

objet de BDD nws_content :: class nws_content

SQL mySQL:

DROP TABLE IF EXISTS nws_content;
CREATE TABLE nws_content
(
	nws_id			int (11) PRIMARY KEY not null,
	nws_statut			int (11) not null,
	nws_titre_court			int (11),
	nws_titre_long			int (11),
	nws_sous_titre			int (11),
	nws_texte_long			int (11),
	nws_document			int (11),
	nws_vignette			varchar (255),
	nws_diaporama			int (11),
	nws_url			varchar (256),
	nws_remontee			enum ('Y','N') default 'N',
	nws_ordre			int (11),
	nws_date_pub_debut			datetime,
	nws_date_pub_fin			datetime,
	nws_cdate			date,
	nws_mdate			date
)

SQL Oracle:

DROP TABLE nws_content
CREATE TABLE nws_content
(
	nws_id			number (11) constraint nws_pk PRIMARY KEY not null,
	nws_statut			number (11) not null,
	nws_titre_court			number (11),
	nws_titre_long			number (11),
	nws_sous_titre			number (11),
	nws_texte_long			number (11),
	nws_document			number (11),
	nws_vignette			varchar2 (255),
	nws_diaporama			number (11),
	nws_url			varchar2 (256),
	nws_remontee			enum ('Y','N') default 'N',
	nws_ordre			number (11),
	nws_date_pub_debut			datetime,
	nws_date_pub_fin			datetime,
	nws_cdate			date,
	nws_mdate			date
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="nws_content" libelle="Actualités" prefix="nws" display="titre_court" abstract="sous_titre">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
<item name="titre_court" libelle="Titre court" type="int" length="11" list="true" order="true" translate="reference" /> 
<item name="titre_long" libelle="Titre long" type="int" length="11" rss="title" translate="reference" /> 
<item name="sous_titre" libelle="Sous-titre" type="int" length="11" list="true" order="true" option="textarea" translate="reference" /> 
<item name="texte_long" libelle="Corps du texte" type="int" length="11" option="textarea" rss="description" translate="reference" />
<item name="document" libelle="Documentation" type="int" length="11" fkey="cms_pdf"/> 
<item name="vignette" libelle="Vignette" type="varchar" length="255" option="file"/>
<item name="diaporama" libelle="Diaporama" type="int" length="11" option="diaporama" fkey="cms_diaporama"/>
<item name="url" libelle="URL" type="varchar" length="256" option="url" /> 
<item name="remontee" libelle="Remontee" type="enum" length="'Y','N'" default="N" />
<item name="ordre" libelle="Ordre" type="int" length="11" list="true" order="true" />
<item name="date_pub_debut" libelle="Début de publication" type="datetime"  format="l j F Y" rss="pubDate" />
<item name="date_pub_fin" libelle="Fin de publication" type="datetime"  format="l j F Y" rss="pubendDate" /> 
<item name="cdate" libelle="Date de création" type="date" />
<item name="mdate" libelle="Date de modification" type="date" />
</class>



==========================================*/

class nws_content
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $statut;
var $titre_court;
var $titre_long;
var $sous_titre;
var $texte_long;
var $document;
var $vignette;
var $diaporama;
var $url;
var $remontee;
var $ordre;
var $date_pub_debut;
var $date_pub_fin;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"nws_content\" libelle=\"Actualités\" prefix=\"nws\" display=\"titre_court\" abstract=\"sous_titre\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
<item name=\"titre_court\" libelle=\"Titre court\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\" /> 
<item name=\"titre_long\" libelle=\"Titre long\" type=\"int\" length=\"11\" rss=\"title\" translate=\"reference\" /> 
<item name=\"sous_titre\" libelle=\"Sous-titre\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" option=\"textarea\" translate=\"reference\" /> 
<item name=\"texte_long\" libelle=\"Corps du texte\" type=\"int\" length=\"11\" option=\"textarea\" rss=\"description\" translate=\"reference\" />
<item name=\"document\" libelle=\"Documentation\" type=\"int\" length=\"11\" fkey=\"cms_pdf\"/> 
<item name=\"vignette\" libelle=\"Vignette\" type=\"varchar\" length=\"255\" option=\"file\"/>
<item name=\"diaporama\" libelle=\"Diaporama\" type=\"int\" length=\"11\" option=\"diaporama\" fkey=\"cms_diaporama\"/>
<item name=\"url\" libelle=\"URL\" type=\"varchar\" length=\"256\" option=\"url\" /> 
<item name=\"remontee\" libelle=\"Remontee\" type=\"enum\" length=\"'Y','N'\" default=\"N\" />
<item name=\"ordre\" libelle=\"Ordre\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" />
<item name=\"date_pub_debut\" libelle=\"Début de publication\" type=\"datetime\" list=\"true\" order=\"true\"  format=\"l j F Y\" rss=\"pubDate\" />
<item name=\"date_pub_fin\" libelle=\"Fin de publication\" type=\"datetime\" list=\"true\" order=\"true\"  format=\"l j F Y\" rss=\"pubendDate\" /> 
<item name=\"cdate\" libelle=\"Date de création\" type=\"date\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"date\" />
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE nws_content
(
	nws_id			int (11) PRIMARY KEY not null,
	nws_statut			int (11) not null,
	nws_titre_court			int (11),
	nws_titre_long			int (11),
	nws_sous_titre			int (11),
	nws_texte_long			int (11),
	nws_document			int (11),
	nws_vignette			varchar (255),
	nws_diaporama			int (11),
	nws_url			varchar (256),
	nws_remontee			enum ('Y','N') default 'N',
	nws_ordre			int (11),
	nws_date_pub_debut			datetime,
	nws_date_pub_fin			datetime,
	nws_cdate			date,
	nws_mdate			date
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
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->titre_court = -1;
		$this->titre_long = -1;
		$this->sous_titre = -1;
		$this->texte_long = -1;
		$this->document = -1;
		$this->vignette = "";
		$this->diaporama = -1;
		$this->url = "";
		$this->remontee = "N";
		$this->ordre = -1;
		$this->date_pub_debut = date('Y-m-d H:i:s');
		$this->date_pub_fin = date('Y-m-d H:i:s');
		$this->cdate = date("d/m/Y");
		$this->mdate = date("d/m/Y");
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
	$laListeChamps[]=new dbChamp("Nws_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Nws_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Nws_titre_court", "entier", "get_titre_court", "set_titre_court");
	$laListeChamps[]=new dbChamp("Nws_titre_long", "entier", "get_titre_long", "set_titre_long");
	$laListeChamps[]=new dbChamp("Nws_sous_titre", "entier", "get_sous_titre", "set_sous_titre");
	$laListeChamps[]=new dbChamp("Nws_texte_long", "entier", "get_texte_long", "set_texte_long");
	$laListeChamps[]=new dbChamp("Nws_document", "entier", "get_document", "set_document");
	$laListeChamps[]=new dbChamp("Nws_vignette", "text", "get_vignette", "set_vignette");
	$laListeChamps[]=new dbChamp("Nws_diaporama", "entier", "get_diaporama", "set_diaporama");
	$laListeChamps[]=new dbChamp("Nws_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Nws_remontee", "text", "get_remontee", "set_remontee");
	$laListeChamps[]=new dbChamp("Nws_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Nws_date_pub_debut", "date_formatee_timestamp", "get_date_pub_debut", "set_date_pub_debut");
	$laListeChamps[]=new dbChamp("Nws_date_pub_fin", "date_formatee_timestamp", "get_date_pub_fin", "set_date_pub_fin");
	$laListeChamps[]=new dbChamp("Nws_cdate", "date_formatee", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Nws_mdate", "date_formatee", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_statut() { return($this->statut); }
function get_titre_court() { return($this->titre_court); }
function get_titre_long() { return($this->titre_long); }
function get_sous_titre() { return($this->sous_titre); }
function get_texte_long() { return($this->texte_long); }
function get_document() { return($this->document); }
function get_vignette() { return($this->vignette); }
function get_diaporama() { return($this->diaporama); }
function get_url() { return($this->url); }
function get_remontee() { return($this->remontee); }
function get_ordre() { return($this->ordre); }
function get_date_pub_debut() { return($this->date_pub_debut); }
function get_date_pub_fin() { return($this->date_pub_fin); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_nws_id) { return($this->id=$c_nws_id); }
function set_statut($c_nws_statut) { return($this->statut=$c_nws_statut); }
function set_titre_court($c_nws_titre_court) { return($this->titre_court=$c_nws_titre_court); }
function set_titre_long($c_nws_titre_long) { return($this->titre_long=$c_nws_titre_long); }
function set_sous_titre($c_nws_sous_titre) { return($this->sous_titre=$c_nws_sous_titre); }
function set_texte_long($c_nws_texte_long) { return($this->texte_long=$c_nws_texte_long); }
function set_document($c_nws_document) { return($this->document=$c_nws_document); }
function set_vignette($c_nws_vignette) { return($this->vignette=$c_nws_vignette); }
function set_diaporama($c_nws_diaporama) { return($this->diaporama=$c_nws_diaporama); }
function set_url($c_nws_url) { return($this->url=$c_nws_url); }
function set_remontee($c_nws_remontee) { return($this->remontee=$c_nws_remontee); }
function set_ordre($c_nws_ordre) { return($this->ordre=$c_nws_ordre); }
function set_date_pub_debut($c_nws_date_pub_debut) { return($this->date_pub_debut=$c_nws_date_pub_debut); }
function set_date_pub_fin($c_nws_date_pub_fin) { return($this->date_pub_fin=$c_nws_date_pub_fin); }
function set_cdate($c_nws_cdate) { return($this->cdate=$c_nws_cdate); }
function set_mdate($c_nws_mdate) { return($this->mdate=$c_nws_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("nws_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("nws_statut"); }
//
function getTable() { return("nws_content"); }
function getClasse() { return("nws_content"); }
function getDisplay() { return("titre_court"); }
function getAbstract() { return("sous_titre"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content/list_nws_content.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[newSizeOf(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content/maj_nws_content.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content/show_nws_content.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content/rss_nws_content.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content/xml_nws_content.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content/xmlxls_nws_content.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content/export_nws_content.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/nws_content/import_nws_content.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>