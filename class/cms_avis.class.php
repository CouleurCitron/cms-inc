<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('cms_avis')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_avis`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			//pre_dump($rs->fields);
			$rs->MoveNext();
		
		}
		if (in_array('id_site', $names))	
			$rs = $db->Execute("ALTER TABLE `cms_avis` DROP `id_site`");
		if (!in_array('type_reference', $names)&&in_array('id_avis', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_avis` ADD `type_reference` ENUM( 'page', 'mod_news', 'mod_survey' ) DEFAULT 'page' NOT NULL AFTER `id_avis`;");
			$rs = $db->Execute("ALTER TABLE `cms_avis` CHANGE `id_page_avis` `cms_avs_id_reference` INT( 11 ) NULL DEFAULT '0';");
		}
		if (!in_array('cms_avs_type_reference', $names)) { 
			$rs = $db->Execute("ALTER TABLE `cms_avis`		CHANGE `id_avis` `cms_avs_id` INT( 11 ) NULL DEFAULT '0',
									CHANGE `type_reference` `cms_avs_type_reference` ENUM( 'page', 'mod_news', 'mod_survey' ) NULL DEFAULT 'page',
									CHANGE `id_reference` `cms_avs_id_reference` INT( 11 ) NULL DEFAULT '0',
									CHANGE `titre_avis` `cms_avs_titre` VARCHAR( 250 ) NULL ,
									CHANGE `texte_avis` `cms_avs_texte` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
									CHANGE `nomcontact_avis` `cms_avs_nomcontact` VARCHAR( 250 ) NULL ,
									CHANGE `mailcontact_avis` `cms_avs_mailcontact` VARCHAR( 250 ) NULL ,
									CHANGE `nomweb1_avis` `cms_avs_nomweb1` VARCHAR( 250 ) NULL ,
									CHANGE `web1_avis` `cms_avs_web1` VARCHAR( 250 ) NULL ,
									CHANGE `nomweb2_avis` `cms_avs_nomweb2` VARCHAR( 250 ) NULL ,
									CHANGE `web2_avis` `cms_avs_web2` VARCHAR( 250 ) NULL ,
									CHANGE `note_avis` `cms_avs_note` INT( 5 ) NULL DEFAULT '0',
									CHANGE `statut_avis` `cms_avs_statut` INT( 5 ) NULL DEFAULT '0',
									CHANGE `dcreat_avis` `cms_avs_created` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
									CHANGE `dmaj_avis` `cms_avs_updated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;");
			$rs = $db->Execute("ALTER TABLE `cms_avis`		DROP `ucreat_avis` ,
									DROP `umaj_avis`;");
		}
		
		if (!in_array('cms_avs_id', $names)) { 
			$rs = $db->Execute("ALTER TABLE `cms_avis`		CHANGE `id_avis` `cms_avs_id` INT( 11 ) NULL DEFAULT '0',
									CHANGE `type_reference` `cms_avs_type_reference` ENUM( 'page', 'mod_news', 'mod_survey' ) NULL DEFAULT 'page', 
									CHANGE `titre_avis` `cms_avs_titre` VARCHAR( 250 ) NULL ,
									CHANGE `texte_avis` `cms_avs_texte` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
									CHANGE `nomcontact_avis` `cms_avs_nomcontact` VARCHAR( 250 ) NULL ,
									CHANGE `mailcontact_avis` `cms_avs_mailcontact` VARCHAR( 250 ) NULL ,
									CHANGE `nomweb1_avis` `cms_avs_nomweb1` VARCHAR( 250 ) NULL ,
									CHANGE `web1_avis` `cms_avs_web1` VARCHAR( 250 ) NULL ,
									CHANGE `nomweb2_avis` `cms_avs_nomweb2` VARCHAR( 250 ) NULL ,
									CHANGE `web2_avis` `cms_avs_web2` VARCHAR( 250 ) NULL ,
									CHANGE `note_avis` `cms_avs_note` INT( 5 ) NULL DEFAULT '0',
									CHANGE `statut_avis` `cms_avs_statut` INT( 5 ) NULL DEFAULT '0',
									CHANGE `dcreat_avis` `cms_avs_created` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
									CHANGE `dmaj_avis` `cms_avs_updated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;");
			$rs = $db->Execute("ALTER TABLE `cms_avis`		DROP `ucreat_avis` ,
									DROP `umaj_avis`;");
		}
		if (!in_array('cms_avs_societe', $names))
			$rs = $db->Execute("ALTER TABLE `cms_avis` ADD `cms_avs_societe` VARCHAR(250) NULL DEFAULT NULL AFTER `cms_avs_mailcontact`;");
		if (!in_array('cms_avs_fonction', $names))
			$rs = $db->Execute("ALTER TABLE `cms_avis` ADD `cms_avs_fonction` VARCHAR(250) NULL DEFAULT NULL AFTER `cms_avs_societe`;");

	}
}
/*======================================

objet de BDD cms_avis :: class cms_avis

SQL mySQL:

DROP TABLE IF EXISTS cms_avis;
CREATE TABLE cms_avis
(
	cms_avs_id			int (11) PRIMARY KEY not null,
	cms_avs_type_reference			enum ('page','mod_news','mod_survey','mod_produit') not null default 'page',
	cms_avs_id_reference			int (11),
	cms_avs_titre			varchar (255),
	cms_avs_texte			text,
	cms_avs_nomcontact			varchar (255),
	cms_avs_mailcontact			varchar (255),
	cms_avs_societe			varchar (255),
	cms_avs_fonction			varchar (255),
	cms_avs_nomweb1			varchar (255),
	cms_avs_web1			varchar (255),
	cms_avs_nomweb2			varchar (255),
	cms_avs_web2			varchar (255),
	cms_avs_note			int (11),
	cms_avs_statut			int not null,
	cms_avs_created			datetime not null,
	cms_avs_updated			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE cms_avis
CREATE TABLE cms_avis
(
	cms_avs_id			number (11) constraint cms_avs_pk PRIMARY KEY not null,
	cms_avs_type_reference			enum ('page','mod_news','mod_survey','mod_produit') not null default 'page',
	cms_avs_id_reference			number (11),
	cms_avs_titre			varchar2 (255),
	cms_avs_texte			text,
	cms_avs_nomcontact			varchar2 (255),
	cms_avs_mailcontact			varchar2 (255),
	cms_avs_societe			varchar2 (255),
	cms_avs_fonction			varchar2 (255),
	cms_avs_nomweb1			varchar2 (255),
	cms_avs_web1			varchar2 (255),
	cms_avs_nomweb2			varchar2 (255),
	cms_avs_web2			varchar2 (255),
	cms_avs_note			number (11),
	cms_avs_statut			number not null,
	cms_avs_created			datetime not null,
	cms_avs_updated			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_avis" libelle="Avis" prefix="cms_avs" display="titre" abstract="type_reference" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true"  order="true" /> 
<item name="type_reference" libelle="Type de liaison" type="enum" length="'page','mod_news','mod_survey','mod_produit'" notnull="true" default="page" list="true" />
<item name="id_reference" libelle="Elément lié" fkey_switch="type_reference" type="int" length="11" list="true" order="true">
	<option type="page" table="cms_page" />
	<option type="mod_news" table="cms_news" />
	<option type="mod_survey" table="cms_survey_ask" />
	<option type="mod_produit" table="shp_produit" />
</item>
<item name="titre" libelle="Titre" type="varchar" length="255" list="true" order="true" nohtml="true"/>
<item name="texte" libelle="Commentaires" type="text" list="false" order="false"/>
<item name="nomcontact" libelle="Contact" type="varchar" length="255" list="true" order="true" nohtml="true"/>
<item name="mailcontact" libelle="Email" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="societe" libelle="Société" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="fonction" libelle="Fonction" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="nomweb1" libelle="Nom web 1" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="web1" libelle="web 1" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="nomweb2" libelle="Nom web 2" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="web2" libelle="web 2" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="note" libelle="Note" type="int" length="11" list="false" order="false" nohtml="true"/>
<item name="statut" type="int" notnull="true" list="true" order="true" default="DEF_CODE_STATUT_DEFAUT" />
<item name="created" libelle="Date de création" type="datetime" notnull="true"  list="true" order="true" /> 
<item name="updated" libelle="Date de mise à jour" type="timestamp" notnull="true"  list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" /> 
</class>



==========================================*/

class cms_avis
{
var $id;
var $type_reference;
var $id_reference;
var $titre;
var $texte;
var $nomcontact;
var $mailcontact;
var $societe;
var $fonction;
var $nomweb1;
var $web1;
var $nomweb2;
var $web2;
var $note;
var $statut;
var $created;
var $updated;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_avis\" libelle=\"Avis\" prefix=\"cms_avs\" display=\"titre\" abstract=\"type_reference\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\"  order=\"true\" /> 
<item name=\"type_reference\" libelle=\"Type de liaison\" type=\"enum\" length=\"'page','mod_news','mod_survey','mod_produit'\" notnull=\"true\" default=\"page\" list=\"true\" />
<item name=\"id_reference\" libelle=\"Elément lié\" fkey_switch=\"type_reference\" type=\"int\" length=\"11\" list=\"true\" order=\"true\">
	<option type=\"page\" table=\"cms_page\" />
	<option type=\"mod_news\" table=\"cms_news\" />
	<option type=\"mod_survey\" table=\"cms_survey_ask\" />
	<option type=\"mod_produit\" table=\"shp_produit\" />
</item>
<item name=\"titre\" libelle=\"Titre\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\"/>
<item name=\"texte\" libelle=\"Commentaires\" type=\"text\" list=\"false\" order=\"false\"/>
<item name=\"nomcontact\" libelle=\"Contact\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\"/>
<item name=\"mailcontact\" libelle=\"Email\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"societe\" libelle=\"Société\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"fonction\" libelle=\"Fonction\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"nomweb1\" libelle=\"Nom web 1\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"web1\" libelle=\"web 1\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"nomweb2\" libelle=\"Nom web 2\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"web2\" libelle=\"web 2\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"note\" libelle=\"Note\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"statut\" type=\"int\" notnull=\"true\" list=\"true\" order=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" />
<item name=\"created\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\"  list=\"true\" order=\"true\" /> 
<item name=\"updated\" libelle=\"Date de mise à jour\" type=\"timestamp\" notnull=\"true\"  list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" /> 
</class>
";

var $sMySql = "CREATE TABLE cms_avis
(
	cms_avs_id			int (11) PRIMARY KEY not null,
	cms_avs_type_reference			enum ('page','mod_news','mod_survey','mod_produit') not null default 'page',
	cms_avs_id_reference			int (11),
	cms_avs_titre			varchar (255),
	cms_avs_texte			text,
	cms_avs_nomcontact			varchar (255),
	cms_avs_mailcontact			varchar (255),
	cms_avs_societe			varchar (255),
	cms_avs_fonction			varchar (255),
	cms_avs_nomweb1			varchar (255),
	cms_avs_web1			varchar (255),
	cms_avs_nomweb2			varchar (255),
	cms_avs_web2			varchar (255),
	cms_avs_note			int (11),
	cms_avs_statut			int not null,
	cms_avs_created			datetime not null,
	cms_avs_updated			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function cms_avis($id=null)
{
	if (istable("cms_avis") == false){
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
		$this->type_reference = "page";
		$this->id_reference = -1;
		$this->titre = "";
		$this->texte = "";
		$this->nomcontact = "";
		$this->mailcontact = "";
		$this->societe = "";
		$this->fonction = "";
		$this->nomweb1 = "";
		$this->web1 = "";
		$this->nomweb2 = "";
		$this->web2 = "";
		$this->note = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->created = date('Y-m-d H:i:s');
		$this->updated = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_avs_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_avs_type_reference", "text", "get_type_reference", "set_type_reference");
	$laListeChamps[]=new dbChamp("Cms_avs_id_reference", "entier", "get_id_reference", "set_id_reference");
	$laListeChamps[]=new dbChamp("Cms_avs_titre", "text", "get_titre", "set_titre");
	$laListeChamps[]=new dbChamp("Cms_avs_texte", "text", "get_texte", "set_texte");
	$laListeChamps[]=new dbChamp("Cms_avs_nomcontact", "text", "get_nomcontact", "set_nomcontact");
	$laListeChamps[]=new dbChamp("Cms_avs_mailcontact", "text", "get_mailcontact", "set_mailcontact");
	$laListeChamps[]=new dbChamp("Cms_avs_societe", "text", "get_societe", "set_societe");
	$laListeChamps[]=new dbChamp("Cms_avs_fonction", "text", "get_fonction", "set_fonction");
	$laListeChamps[]=new dbChamp("Cms_avs_nomweb1", "text", "get_nomweb1", "set_nomweb1");
	$laListeChamps[]=new dbChamp("Cms_avs_web1", "text", "get_web1", "set_web1");
	$laListeChamps[]=new dbChamp("Cms_avs_nomweb2", "text", "get_nomweb2", "set_nomweb2");
	$laListeChamps[]=new dbChamp("Cms_avs_web2", "text", "get_web2", "set_web2");
	$laListeChamps[]=new dbChamp("Cms_avs_note", "entier", "get_note", "set_note");
	$laListeChamps[]=new dbChamp("Cms_avs_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Cms_avs_created", "date_formatee_timestamp", "get_created", "set_created");
	$laListeChamps[]=new dbChamp("Cms_avs_updated", "date_formatee_timestamp", "get_updated", "set_updated");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_type_reference() { return($this->type_reference); }
function get_id_reference() { return($this->id_reference); }
function get_titre() { return($this->titre); }
function get_texte() { return($this->texte); }
function get_nomcontact() { return($this->nomcontact); }
function get_mailcontact() { return($this->mailcontact); }
function get_societe() { return($this->societe); }
function get_fonction() { return($this->fonction); }
function get_nomweb1() { return($this->nomweb1); }
function get_web1() { return($this->web1); }
function get_nomweb2() { return($this->nomweb2); }
function get_web2() { return($this->web2); }
function get_note() { return($this->note); }
function get_statut() { return($this->statut); }
function get_created() { return($this->created); }
function get_updated() { return($this->updated); }


// setters
function set_id($c_cms_avs_id) { return($this->id=$c_cms_avs_id); }
function set_type_reference($c_cms_avs_type_reference) { return($this->type_reference=$c_cms_avs_type_reference); }
function set_id_reference($c_cms_avs_id_reference) { return($this->id_reference=$c_cms_avs_id_reference); }
function set_titre($c_cms_avs_titre) { return($this->titre=$c_cms_avs_titre); }
function set_texte($c_cms_avs_texte) { return($this->texte=$c_cms_avs_texte); }
function set_nomcontact($c_cms_avs_nomcontact) { return($this->nomcontact=$c_cms_avs_nomcontact); }
function set_mailcontact($c_cms_avs_mailcontact) { return($this->mailcontact=$c_cms_avs_mailcontact); }
function set_societe($c_cms_avs_societe) { return($this->societe=$c_cms_avs_societe); }
function set_fonction($c_cms_avs_fonction) { return($this->fonction=$c_cms_avs_fonction); }
function set_nomweb1($c_cms_avs_nomweb1) { return($this->nomweb1=$c_cms_avs_nomweb1); }
function set_web1($c_cms_avs_web1) { return($this->web1=$c_cms_avs_web1); }
function set_nomweb2($c_cms_avs_nomweb2) { return($this->nomweb2=$c_cms_avs_nomweb2); }
function set_web2($c_cms_avs_web2) { return($this->web2=$c_cms_avs_web2); }
function set_note($c_cms_avs_note) { return($this->note=$c_cms_avs_note); }
function set_statut($c_cms_avs_statut) { return($this->statut=$c_cms_avs_statut); }
function set_created($c_cms_avs_created) { return($this->created=$c_cms_avs_created); }
function set_updated($c_cms_avs_updated) { return($this->updated=$c_cms_avs_updated); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_avs_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_avs_statut"); }
//
function getTable() { return("cms_avis"); }
function getClasse() { return("cms_avis"); }
function getDisplay() { return("titre"); }
function getAbstract() { return("type_reference"); }


} //class



// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis/list_cms_avis.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis/maj_cms_avis.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis/show_cms_avis.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis/rss_cms_avis.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis/xml_cms_avis.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis/xmlxls_cms_avis.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis/export_cms_avis.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_avis/import_cms_avis.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>