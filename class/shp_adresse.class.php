<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('shp_adresse')){
	$rs = $db->Execute('DESCRIBE `shp_adresse`');
	if (isset($rs->_numOfRows)){
		if ($rs->_numOfRows == 17){
			$rs = $db->Execute('ALTER TABLE `shp_adresse` ADD `shp_adr_societe` VARCHAR( 256 )  NULL AFTER `shp_adr_nom` , ADD `shp_adr_isaddressdefault` INT(2) NULL DEFAULT \'0\' AFTER `shp_adr_type` ;');
		}
	}
} 
/*======================================

objet de BDD shp_adresse :: class shp_adresse

SQL mySQL:

DROP TABLE IF EXISTS shp_adresse;
CREATE TABLE shp_adresse
(
	shp_adr_id			int (4) PRIMARY KEY not null,
	shp_adr_id_client			int (8) not null,
	shp_adr_id_pays			int (3) not null,
	shp_adr_type			enum ('facturation','expédition','commune') default 'expédition',
	shp_adr_isaddressdefault			int (2),
	shp_adr_statut			int (2) not null,
	shp_adr_civilite			enum ('Monsieur','Madame','Mademoiselle') not null default 'Monsieur',
	shp_adr_prenom			varchar (256) not null,
	shp_adr_nom			varchar (256) not null,
	shp_adr_societe			varchar (256) not null,
	shp_adr_tel			varchar (64),
	shp_adr_detail_1			varchar (256) not null,
	shp_adr_detail_2			varchar (256),
	shp_adr_detail_3			varchar (256),
	shp_adr_ville			varchar (256) not null,
	shp_adr_cp			varchar (64) not null,
	shp_adr_commentaires			text,
	shp_adr_cdate			datetime not null,
	shp_adr_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_adresse
CREATE TABLE shp_adresse
(
	shp_adr_id			number (4) constraint shp_adr_pk PRIMARY KEY not null,
	shp_adr_id_client			number (8) not null,
	shp_adr_id_pays			number (3) not null,
	shp_adr_type			enum ('facturation','expÃ©dition','commune') default 'expÃ©dition',
	shp_adr_isaddressdefault			number (2),
	shp_adr_statut			number (2) not null,
	shp_adr_civilite			enum ('Monsieur','Madame','Mademoiselle') not null default 'Monsieur',
	shp_adr_prenom			varchar2 (256) not null,
	shp_adr_nom			varchar2 (256) not null,
	shp_adr_societe			varchar2 (256) not null,
	shp_adr_tel			varchar2 (64),
	shp_adr_detail_1			varchar2 (256) not null,
	shp_adr_detail_2			varchar2 (256),
	shp_adr_detail_3			varchar2 (256),
	shp_adr_ville			varchar2 (256) not null,
	shp_adr_cp			varchar2 (64) not null,
	shp_adr_commentaires			text,
	shp_adr_cdate			datetime not null,
	shp_adr_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_adresse" libelle="Adresse d'un client" prefix="shp_adr" display="nom" abstract="ville">
<item name="id" type="int" length="4" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_client" libelle="Client" type="int" length="8" fkey="shp_client" notnull="true" default="0" list="true" order="true" noedit="true" />
<item name="id_pays" libelle="Pays" type="int" length="3" fkey="cms_pays" notnull="true" default="0" list="true" order="true" noedit="true" /> 
<item name="type" libelle="Type" type="enum" length="'facturation','expédition','commune'" default="expédition" />
<item name="isaddressdefault" libelle="Addresse commune par défaut" type="int" length="2" default="0" option="bool" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="civilite" libelle="Civilité" type="enum" length="'Monsieur','Madame','Mademoiselle'" notnull="true" default="Monsieur" nohtml="true" translate="value" noedit="true" />
<item name="prenom" libelle="Prénom" type="varchar" length="256" notnull="true" default="" list="true" nohtml="true" />
<item name="nom" libelle="Nom" type="varchar" length="256" notnull="true" default="" list="true" nohtml="true" />
<item name="societe" libelle="Société" type="varchar" length="256" notnull="true" default="" list="true" nohtml="true" />
<item name="tel" libelle="Téléphone" type="varchar" length="64" default="NULL" nohtml="true" />
<item name="detail_1" libelle="Adresse 1" type="varchar" length="256" notnull="true" default="" nohtml="true" />
<item name="detail_2" libelle="Adresse 2" type="varchar" length="256" default="" nohtml="true" />
<item name="detail_3" libelle="Adresse 3" type="varchar" length="256" default="" nohtml="true" />
<item name="ville" libelle="Ville" type="varchar" length="256" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="cp" libelle="Code postal" type="varchar" length="64" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="commentaires" libelle="Commentaires" type="text" default="" option="textarea" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_adresse
{
var $id;
var $id_client;
var $id_pays;
var $type;
var $isaddressdefault;
var $statut;
var $civilite;
var $prenom;
var $nom;
var $societe;
var $tel;
var $detail_1;
var $detail_2;
var $detail_3;
var $ville;
var $cp;
var $commentaires;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_adresse\" libelle=\"Adresse d'un client\" prefix=\"shp_adr\" display=\"nom\" abstract=\"ville\">
<item name=\"id\" type=\"int\" length=\"4\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_client\" libelle=\"Client\" type=\"int\" length=\"8\" fkey=\"shp_client\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" noedit=\"true\" />
<item name=\"id_pays\" libelle=\"Pays\" type=\"int\" length=\"3\" fkey=\"cms_pays\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" noedit=\"true\" /> 
<item name=\"type\" libelle=\"Type\" type=\"enum\" length=\"'facturation','expédition','commune'\" default=\"expédition\" />
<item name=\"isaddressdefault\" libelle=\"Addresse commune par défaut\" type=\"int\" length=\"2\" default=\"0\" option=\"bool\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"civilite\" libelle=\"Civilité\" type=\"enum\" length=\"'Monsieur','Madame','Mademoiselle'\" notnull=\"true\" default=\"Monsieur\" nohtml=\"true\" translate=\"value\" noedit=\"true\" />
<item name=\"prenom\" libelle=\"Prénom\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" nohtml=\"true\" />
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" nohtml=\"true\" />
<item name=\"societe\" libelle=\"Société\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" nohtml=\"true\" />
<item name=\"tel\" libelle=\"Téléphone\" type=\"varchar\" length=\"64\" default=\"NULL\" nohtml=\"true\" />
<item name=\"detail_1\" libelle=\"Adresse 1\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" nohtml=\"true\" />
<item name=\"detail_2\" libelle=\"Adresse 2\" type=\"varchar\" length=\"256\" default=\"\" nohtml=\"true\" />
<item name=\"detail_3\" libelle=\"Adresse 3\" type=\"varchar\" length=\"256\" default=\"\" nohtml=\"true\" />
<item name=\"ville\" libelle=\"Ville\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"cp\" libelle=\"Code postal\" type=\"varchar\" length=\"64\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"commentaires\" libelle=\"Commentaires\" type=\"text\" default=\"\" option=\"textarea\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_adresse
(
	shp_adr_id			int (4) PRIMARY KEY not null,
	shp_adr_id_client			int (8) not null,
	shp_adr_id_pays			int (3) not null,
	shp_adr_type			enum ('facturation','expÃ©dition','commune') default 'expÃ©dition',
	shp_adr_isaddressdefault			int (2),
	shp_adr_statut			int (2) not null,
	shp_adr_civilite			enum ('Monsieur','Madame','Mademoiselle') not null default 'Monsieur',
	shp_adr_prenom			varchar (256) not null,
	shp_adr_nom			varchar (256) not null,
	shp_adr_societe			varchar (256) not null,
	shp_adr_tel			varchar (64),
	shp_adr_detail_1			varchar (256) not null,
	shp_adr_detail_2			varchar (256),
	shp_adr_detail_3			varchar (256),
	shp_adr_ville			varchar (256) not null,
	shp_adr_cp			varchar (64) not null,
	shp_adr_commentaires			text,
	shp_adr_cdate			datetime not null,
	shp_adr_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function shp_adresse($id=null)
{
	if (istable("shp_adresse") == false){
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
		$this->id_client = -1;
		$this->id_pays = -1;
		$this->type = "expÃ©dition";
		$this->isaddressdefault = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->civilite = "Monsieur";
		$this->prenom = "";
		$this->nom = "";
		$this->societe = "";
		$this->tel = "NULL";
		$this->detail_1 = "";
		$this->detail_2 = "";
		$this->detail_3 = "";
		$this->ville = "";
		$this->cp = "";
		$this->commentaires = "";
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_adr_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_adr_id_client", "entier", "get_id_client", "set_id_client");
	$laListeChamps[]=new dbChamp("Shp_adr_id_pays", "entier", "get_id_pays", "set_id_pays");
	$laListeChamps[]=new dbChamp("Shp_adr_type", "text", "get_type", "set_type");
	$laListeChamps[]=new dbChamp("Shp_adr_isaddressdefault", "entier", "get_isaddressdefault", "set_isaddressdefault");
	$laListeChamps[]=new dbChamp("Shp_adr_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_adr_civilite", "text", "get_civilite", "set_civilite");
	$laListeChamps[]=new dbChamp("Shp_adr_prenom", "text", "get_prenom", "set_prenom");
	$laListeChamps[]=new dbChamp("Shp_adr_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Shp_adr_societe", "text", "get_societe", "set_societe");
	$laListeChamps[]=new dbChamp("Shp_adr_tel", "text", "get_tel", "set_tel");
	$laListeChamps[]=new dbChamp("Shp_adr_detail_1", "text", "get_detail_1", "set_detail_1");
	$laListeChamps[]=new dbChamp("Shp_adr_detail_2", "text", "get_detail_2", "set_detail_2");
	$laListeChamps[]=new dbChamp("Shp_adr_detail_3", "text", "get_detail_3", "set_detail_3");
	$laListeChamps[]=new dbChamp("Shp_adr_ville", "text", "get_ville", "set_ville");
	$laListeChamps[]=new dbChamp("Shp_adr_cp", "text", "get_cp", "set_cp");
	$laListeChamps[]=new dbChamp("Shp_adr_commentaires", "text", "get_commentaires", "set_commentaires");
	$laListeChamps[]=new dbChamp("Shp_adr_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_adr_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_client() { return($this->id_client); }
function get_id_pays() { return($this->id_pays); }
function get_type() { return($this->type); }
function get_isaddressdefault() { return($this->isaddressdefault); }
function get_statut() { return($this->statut); }
function get_civilite() { return($this->civilite); }
function get_prenom() { return($this->prenom); }
function get_nom() { return($this->nom); }
function get_societe() { return($this->societe); }
function get_tel() { return($this->tel); }
function get_detail_1() { return($this->detail_1); }
function get_detail_2() { return($this->detail_2); }
function get_detail_3() { return($this->detail_3); }
function get_ville() { return($this->ville); }
function get_cp() { return($this->cp); }
function get_commentaires() { return($this->commentaires); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_adr_id) { return($this->id=$c_shp_adr_id); }
function set_id_client($c_shp_adr_id_client) { return($this->id_client=$c_shp_adr_id_client); }
function set_id_pays($c_shp_adr_id_pays) { return($this->id_pays=$c_shp_adr_id_pays); }
function set_type($c_shp_adr_type) { return($this->type=$c_shp_adr_type); }
function set_isaddressdefault($c_shp_adr_isaddressdefault) { return($this->isaddressdefault=$c_shp_adr_isaddressdefault); }
function set_statut($c_shp_adr_statut) { return($this->statut=$c_shp_adr_statut); }
function set_civilite($c_shp_adr_civilite) { return($this->civilite=$c_shp_adr_civilite); }
function set_prenom($c_shp_adr_prenom) { return($this->prenom=$c_shp_adr_prenom); }
function set_nom($c_shp_adr_nom) { return($this->nom=$c_shp_adr_nom); }
function set_societe($c_shp_adr_societe) { return($this->societe=$c_shp_adr_societe); }
function set_tel($c_shp_adr_tel) { return($this->tel=$c_shp_adr_tel); }
function set_detail_1($c_shp_adr_detail_1) { return($this->detail_1=$c_shp_adr_detail_1); }
function set_detail_2($c_shp_adr_detail_2) { return($this->detail_2=$c_shp_adr_detail_2); }
function set_detail_3($c_shp_adr_detail_3) { return($this->detail_3=$c_shp_adr_detail_3); }
function set_ville($c_shp_adr_ville) { return($this->ville=$c_shp_adr_ville); }
function set_cp($c_shp_adr_cp) { return($this->cp=$c_shp_adr_cp); }
function set_commentaires($c_shp_adr_commentaires) { return($this->commentaires=$c_shp_adr_commentaires); }
function set_cdate($c_shp_adr_cdate) { return($this->cdate=$c_shp_adr_cdate); }
function set_mdate($c_shp_adr_mdate) { return($this->mdate=$c_shp_adr_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_adr_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_adr_statut"); }
//
function getTable() { return("shp_adresse"); }
function getClasse() { return("shp_adresse"); }
function getPrefix() { return("shp_adr"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("ville"); }


} //class

/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse/list_shp_adresse.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse/maj_shp_adresse.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse/show_shp_adresse.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse/rss_shp_adresse.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse/xml_shp_adresse.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse/xmlxls_shp_adresse.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse/export_shp_adresse.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/shp_adresse/import_shp_adresse.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
} */
?>