<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('shp_client')){
	$rs = $db->Execute('SHOW COLUMNS FROM `shp_client`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('shp_clt_langue', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_client` ADD `shp_clt_langue` INT(2) NOT NULL AFTER `shp_clt_id`;");
		}
		if (in_array('shp_clt_key', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_client` CHANGE `shp_clt_key` `shp_clt_act_key` VARCHAR( 64 ) NULL DEFAULT NULL;");
		} elseif (!in_array('shp_clt_act_key', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_client` ADD `shp_clt_act_key` VARCHAR( 64 ) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL AFTER `shp_clt_pwd`;");
		}
		if (!in_array('shp_clt_last_connected', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_client` ADD `shp_clt_last_connected` DATETIME NOT NULL AFTER `shp_clt_commentaires`;");
		}
		if (!in_array('shp_clt_last_reminded', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_client` ADD `shp_clt_last_reminded` DATETIME NOT NULL AFTER `shp_clt_last_connected`;");
		}
	}
}

/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shp_client.class.php')  && (strpos(__FILE__,'/include/bo/class/shp_client.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shp_client.class.php');
}else{
/*======================================

objet de BDD shp_client :: class shp_client

SQL mySQL:

DROP TABLE IF EXISTS shp_client;
CREATE TABLE shp_client
(
	shp_clt_id			int (4) PRIMARY KEY not null,
	shp_clt_langue			int (2) not null,
	shp_clt_statut			int (2) not null,
	shp_clt_civilite			enum ('Monsieur','Madame','Mademoiselle') not null default 'Monsieur',
	shp_clt_prenom			varchar (256) not null,
	shp_clt_nom			varchar (256) not null,
	shp_clt_professionnel			enum ('Y','N') not null default 'N',
	shp_clt_societe			varchar (256),
	shp_clt_naissance			date,
	shp_clt_email			varchar (128) not null,
	shp_clt_pwd			varchar (128) not null,
	shp_clt_act_key			varchar (64),
	shp_clt_portable			varchar (64),
	shp_clt_tel			varchar (64) not null,
	shp_clt_commentaires			text,
	shp_clt_last_connected			datetime not null,
	shp_clt_last_reminded			datetime not null,
	shp_clt_cdate			datetime not null,
	shp_clt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_client
CREATE TABLE shp_client
(
	shp_clt_id			number (4) constraint shp_clt_pk PRIMARY KEY not null,
	shp_clt_langue			number (2) not null,
	shp_clt_statut			number (2) not null,
	shp_clt_civilite			enum ('Monsieur','Madame','Mademoiselle') not null default 'Monsieur',
	shp_clt_prenom			varchar2 (256) not null,
	shp_clt_nom			varchar2 (256) not null,
	shp_clt_professionnel			enum ('Y','N') not null default 'N',
	shp_clt_societe			varchar2 (256),
	shp_clt_naissance			date,
	shp_clt_email			varchar2 (128) not null,
	shp_clt_pwd			varchar2 (128) not null,
	shp_clt_act_key			varchar2 (64),
	shp_clt_portable			varchar2 (64),
	shp_clt_tel			varchar2 (64) not null,
	shp_clt_commentaires			text,
	shp_clt_last_connected			datetime not null,
	shp_clt_last_reminded			datetime not null,
	shp_clt_cdate			datetime not null,
	shp_clt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_client" libelle="Client de la boutique" prefix="shp_clt" display="nom" abstract="prenom">
<item name="id" type="int" length="4" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="langue" type="int" length="2" notnull="true" default="DEF_APP_LANGUE" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="civilite" libelle="Civilité" type="enum" length="'Monsieur','Madame','Mademoiselle'" notnull="true" default="Monsieur" nohtml="true" translate="value" />
<item name="prenom" libelle="Prénom" type="varchar" length="256" notnull="true" default="" list="true" nohtml="true" />
<item name="nom" libelle="Nom" type="varchar" length="256" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="professionnel" libelle="Professionnel" type="enum" length="'Y','N'" notnull="true" default="N" />
<item name="societe" libelle="Société" type="varchar" length="256" default="" list="true" nohtml="true" />
<item name="naissance" libelle="Date de naissance" type="date" default="" />
<item name="email" libelle="Email" type="varchar" length="128" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="pwd" libelle="Mot de passe" type="varchar" length="128" notnull="true" default="" nohtml="true" />
<item name="act_key" libelle="Clé d'activation" type="varchar" length="64" default="" noedit="true" />
<item name="portable" libelle="N° de portable" type="varchar" length="64" default="" nohtml="true" />
<item name="tel" libelle="N° de fixe" type="varchar" length="64" notnull="true" default="" nohtml="true" />
<item name="commentaires" libelle="Commentaires" type="text" default="" option="textarea" />
<item name="last_connected" libelle="Date de dernière connexion" type="datetime" notnull="true" list="false" default="" noedit="true" />
<item name="last_reminded" libelle="Date de dernière relance" type="datetime" notnull="true" list="false" default="" noedit="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_client
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $langue;
var $statut;
var $civilite;
var $prenom;
var $nom;
var $professionnel;
var $societe;
var $naissance;
var $email;
var $pwd;
var $act_key;
var $portable;
var $tel;
var $commentaires;
var $last_connected;
var $last_reminded;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_client\" libelle=\"Client de la boutique\" prefix=\"shp_clt\" display=\"nom\" abstract=\"prenom\">
<item name=\"id\" type=\"int\" length=\"4\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"langue\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_APP_LANGUE\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"civilite\" libelle=\"Civilité\" type=\"enum\" length=\"'Monsieur','Madame','Mademoiselle'\" notnull=\"true\" default=\"Monsieur\" nohtml=\"true\" translate=\"value\" />
<item name=\"prenom\" libelle=\"Prénom\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" nohtml=\"true\" />
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"professionnel\" libelle=\"Professionnel\" type=\"enum\" length=\"'Y','N'\" notnull=\"true\" default=\"N\" />
<item name=\"societe\" libelle=\"Société\" type=\"varchar\" length=\"256\" default=\"\" list=\"true\" nohtml=\"true\" />
<item name=\"naissance\" libelle=\"Date de naissance\" type=\"date\" default=\"\" />
<item name=\"email\" libelle=\"Email\" type=\"varchar\" length=\"128\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"pwd\" libelle=\"Mot de passe\" type=\"varchar\" length=\"128\" notnull=\"true\" default=\"\" nohtml=\"true\" />
<item name=\"act_key\" libelle=\"Clé d'activation\" type=\"varchar\" length=\"64\" default=\"\" noedit=\"true\" />
<item name=\"portable\" libelle=\"N° de portable\" type=\"varchar\" length=\"64\" default=\"\" nohtml=\"true\" />
<item name=\"tel\" libelle=\"N° de fixe\" type=\"varchar\" length=\"64\" notnull=\"true\" default=\"\" nohtml=\"true\" />
<item name=\"commentaires\" libelle=\"Commentaires\" type=\"text\" default=\"\" option=\"textarea\" />
<item name=\"last_connected\" libelle=\"Date de dernière connexion\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" noedit=\"true\" />
<item name=\"last_reminded\" libelle=\"Date de dernière relance\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" noedit=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE shp_client
(
	shp_clt_id			int (4) PRIMARY KEY not null,
	shp_clt_langue			int (2) not null,
	shp_clt_statut			int (2) not null,
	shp_clt_civilite			enum ('Monsieur','Madame','Mademoiselle') not null default 'Monsieur',
	shp_clt_prenom			varchar (256) not null,
	shp_clt_nom			varchar (256) not null,
	shp_clt_professionnel			enum ('Y','N') not null default 'N',
	shp_clt_societe			varchar (256),
	shp_clt_naissance			date,
	shp_clt_email			varchar (128) not null,
	shp_clt_pwd			varchar (128) not null,
	shp_clt_act_key			varchar (64),
	shp_clt_portable			varchar (64),
	shp_clt_tel			varchar (64) not null,
	shp_clt_commentaires			text,
	shp_clt_last_connected			datetime not null,
	shp_clt_last_reminded			datetime not null,
	shp_clt_cdate			datetime not null,
	shp_clt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
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
		$this->langue = DEF_APP_LANGUE;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->civilite = "Monsieur";
		$this->prenom = "";
		$this->nom = "";
		$this->professionnel = "N";
		$this->societe = "";
		$this->naissance = date("d/m/Y");
		$this->email = "";
		$this->pwd = "";
		$this->act_key = "";
		$this->portable = "";
		$this->tel = "";
		$this->commentaires = "";
		$this->last_connected = "";
		$this->last_reminded = "";
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
	$laListeChamps[]=new dbChamp("Shp_clt_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_clt_langue", "entier", "get_langue", "set_langue");
	$laListeChamps[]=new dbChamp("Shp_clt_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_clt_civilite", "text", "get_civilite", "set_civilite");
	$laListeChamps[]=new dbChamp("Shp_clt_prenom", "text", "get_prenom", "set_prenom");
	$laListeChamps[]=new dbChamp("Shp_clt_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Shp_clt_professionnel", "text", "get_professionnel", "set_professionnel");
	$laListeChamps[]=new dbChamp("Shp_clt_societe", "text", "get_societe", "set_societe");
	$laListeChamps[]=new dbChamp("Shp_clt_naissance", "date_formatee", "get_naissance", "set_naissance");
	$laListeChamps[]=new dbChamp("Shp_clt_email", "text", "get_email", "set_email");
	$laListeChamps[]=new dbChamp("Shp_clt_pwd", "text", "get_pwd", "set_pwd");
	$laListeChamps[]=new dbChamp("Shp_clt_act_key", "text", "get_act_key", "set_act_key");
	$laListeChamps[]=new dbChamp("Shp_clt_portable", "text", "get_portable", "set_portable");
	$laListeChamps[]=new dbChamp("Shp_clt_tel", "text", "get_tel", "set_tel");
	$laListeChamps[]=new dbChamp("Shp_clt_commentaires", "text", "get_commentaires", "set_commentaires");
	$laListeChamps[]=new dbChamp("Shp_clt_last_connected", "date_formatee_timestamp", "get_last_connected", "set_last_connected");
	$laListeChamps[]=new dbChamp("Shp_clt_last_reminded", "date_formatee_timestamp", "get_last_reminded", "set_last_reminded");
	$laListeChamps[]=new dbChamp("Shp_clt_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_clt_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_langue() { return($this->langue); }
function get_statut() { return($this->statut); }
function get_civilite() { return($this->civilite); }
function get_prenom() { return($this->prenom); }
function get_nom() { return($this->nom); }
function get_professionnel() { return($this->professionnel); }
function get_societe() { return($this->societe); }
function get_naissance() { return($this->naissance); }
function get_email() { return($this->email); }
function get_pwd() { return($this->pwd); }
function get_act_key() { return($this->act_key); }
function get_portable() { return($this->portable); }
function get_tel() { return($this->tel); }
function get_commentaires() { return($this->commentaires); }
function get_last_connected() { return($this->last_connected); }
function get_last_reminded() { return($this->last_reminded); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_clt_id) { return($this->id=$c_shp_clt_id); }
function set_langue($c_shp_clt_langue) { return($this->langue=$c_shp_clt_langue); }
function set_statut($c_shp_clt_statut) { return($this->statut=$c_shp_clt_statut); }
function set_civilite($c_shp_clt_civilite) { return($this->civilite=$c_shp_clt_civilite); }
function set_prenom($c_shp_clt_prenom) { return($this->prenom=$c_shp_clt_prenom); }
function set_nom($c_shp_clt_nom) { return($this->nom=$c_shp_clt_nom); }
function set_professionnel($c_shp_clt_professionnel) { return($this->professionnel=$c_shp_clt_professionnel); }
function set_societe($c_shp_clt_societe) { return($this->societe=$c_shp_clt_societe); }
function set_naissance($c_shp_clt_naissance) { return($this->naissance=$c_shp_clt_naissance); }
function set_email($c_shp_clt_email) { return($this->email=$c_shp_clt_email); }
function set_pwd($c_shp_clt_pwd) { return($this->pwd=$c_shp_clt_pwd); }
function set_act_key($c_shp_clt_act_key) { return($this->act_key=$c_shp_clt_act_key); }
function set_portable($c_shp_clt_portable) { return($this->portable=$c_shp_clt_portable); }
function set_tel($c_shp_clt_tel) { return($this->tel=$c_shp_clt_tel); }
function set_commentaires($c_shp_clt_commentaires) { return($this->commentaires=$c_shp_clt_commentaires); }
function set_last_connected($c_job_last_connected) { return($this->last_connected=$c_job_last_connected); }
function set_last_reminded($c_job_last_reminded) { return($this->last_reminded=$c_job_last_reminded); }
function set_cdate($c_shp_clt_cdate) { return($this->cdate=$c_shp_clt_cdate); }
function set_mdate($c_shp_clt_mdate) { return($this->mdate=$c_shp_clt_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_clt_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_clt_statut"); }
//
function getTable() { return("shp_client"); }
function getClasse() { return("shp_client"); }
function getPrefix() { return("shp_clt"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("prenom"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client/list_shp_client.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client/maj_shp_client.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client/show_shp_client.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client/rss_shp_client.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client/xml_shp_client.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client/xmlxls_shp_client.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client/export_shp_client.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_client/import_shp_client.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>