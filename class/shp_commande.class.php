<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD shp_commande :: class shp_commande

SQL mySQL:

DROP TABLE IF EXISTS shp_commande;
CREATE TABLE shp_commande
(
	shp_cmd_id			int (4) PRIMARY KEY not null,
	shp_cmd_id_client			int (8) not null,
	shp_cmd_id_pays			int (3) not null,
	shp_cmd_id_transporteur			int (3) not null,
	shp_cmd_id_statut			int (2) not null,
	shp_cmd_mode_paiement			enum ('CREDIT_CARD','PAYPAL','CHECK','TRANSFER','DIRECT','FREE') default 'CREDIT_CARD',
	shp_cmd_reference			varchar (32) not null,
	shp_cmd_suivi_colis			varchar (256),
	shp_cmd_num_facture			varchar (256),
	shp_cmd_pay_adresse			text not null,
	shp_cmd_exp_adresse			text not null,
	shp_cmd_structure			text not null,
	shp_cmd_message			text,
	shp_cmd_total_ht			decimal (10,2) not null,
	shp_cmd_tva			decimal (10,2),
	shp_cmd_total_ttc			decimal (10,2) not null,
	shp_cmd_port			decimal (10,2),
	shp_cmd_total_pay			decimal (10,2) not null,
	shp_cmd_infos_tpi			text not null,
	shp_cmd_date_commande			datetime,
	shp_cmd_date_paiement			datetime,
	shp_cmd_date_liv_prevue			datetime,
	shp_cmd_date_liv_effective			datetime,
	shp_cmd_date_reception			datetime,
	shp_cmd_cdate			datetime not null,
	shp_cmd_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_commande
CREATE TABLE shp_commande
(
	shp_cmd_id			number (4) constraint shp_cmd_pk PRIMARY KEY not null,
	shp_cmd_id_client			number (8) not null,
	shp_cmd_id_pays			number (3) not null,
	shp_cmd_id_transporteur			number (3) not null,
	shp_cmd_id_statut			number (2) not null,
	shp_cmd_mode_paiement			enum ('CREDIT_CARD','PAYPAL','CHECK','TRANSFER','DIRECT','FREE') default 'CREDIT_CARD',
	shp_cmd_reference			varchar2 (32) not null,
	shp_cmd_suivi_colis			varchar2 (256),
	shp_cmd_num_facture			varchar2 (256),
	shp_cmd_pay_adresse			text not null,
	shp_cmd_exp_adresse			text not null,
	shp_cmd_structure			text not null,
	shp_cmd_message			text,
	shp_cmd_total_ht			decimal (10,2) not null,
	shp_cmd_tva			decimal (10,2),
	shp_cmd_total_ttc			decimal (10,2) not null,
	shp_cmd_port			decimal (10,2),
	shp_cmd_total_pay			decimal (10,2) not null,
	shp_cmd_infos_tpi			text not null,
	shp_cmd_date_commande			datetime,
	shp_cmd_date_paiement			datetime,
	shp_cmd_date_liv_prevue			datetime,
	shp_cmd_date_liv_effective			datetime,
	shp_cmd_date_reception			datetime,
	shp_cmd_cdate			datetime not null,
	shp_cmd_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_commande" libelle="Commande d'un client" prefix="shp_cmd" display="reference" abstract="total_pay" def_order_field="mdate" def_order_direction="DESC" statut="id_statut">
<item name="id" type="int" length="4" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_client" libelle="Client" type="int" length="8" fkey="shp_client" notnull="true" default="0" list="true" order="true" noedit="true" />
<item name="id_pays" libelle="Pays" type="int" length="3" fkey="cms_pays" notnull="true" default="0" list="true" order="true" noedit="true" /> 
<item name="id_transporteur" libelle="Transporteur" type="int" length="3" fkey="shp_transporteur" notnull="true" default="0" list="true" order="true" noedit="true" />
<item name="id_statut" libelle="Statut" type="int" length="2" fkey="shp_commande_statut" notnull="true" default="1" list="true" order="true" /> 
<item name="mode_paiement" libelle="Mode de paiement" type="enum" length="'CREDIT_CARD','PAYPAL','CHECK','TRANSFER','DIRECT','FREE'" default="CREDIT_CARD" list="true" order="true" />
<item name="reference" libelle="Référence" type="varchar" length="32" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="suivi_colis" libelle="Code de suivi" type="varchar" length="256" default="" list="true" order="true" nohtml="true" />
<item name="num_facture" libelle="Numéro" type="varchar" length="256" default="" list="true" order="true" nohtml="true" />
<item name="pay_adresse" libelle="Adresse de facturation" type="text" notnull="true" default="" option="textarea" noedit="true" />
<item name="exp_adresse" libelle="Adresse d'expédition" type="text" notnull="true" default="" option="textarea" noedit="true" />
<item name="structure" libelle="Structure" type="text" notnull="true" default="" noedit="true" serialized="true" />
<item name="message" libelle="Comentaires" type="text" default="" option="textarea" />
<item name="total_ht" libelle="Total HT" type="decimal" length="10,2" notnull="true" default="0.00" noedit="true" />
<item name="tva" libelle="TVA" type="decimal" length="10,2" default="0.00" noedit="true" />
<item name="total_ttc" libelle="Total TTC" type="decimal" length="10,2" notnull="true" default="0.00" noedit="true" />
<item name="port" libelle="Frais de port" type="decimal" length="10,2" default="0.00" noedit="true" />
<item name="total_pay" libelle="Total à payer" list="true" type="decimal" length="10,2" notnull="true" default="0.00" noedit="true" />
<item name="infos_tpi" libelle="Infos de paiement (TPI)" type="text" notnull="true" default="" option="textarea" noedit="true" serialized="true" />
<item name="date_commande" libelle="Date de commande" type="datetime" list="true" order="true" default="NULL" noedit="true" />
<item name="date_paiement" libelle="Date de paiement" type="datetime" default="NULL" noedit="true" />
<item name="date_liv_prevue" libelle="Date de livraison prévue" type="datetime" default="NULL" />
<item name="date_liv_effective" libelle="Date de livraison effective" type="datetime" default="NULL" />
<item name="date_reception" libelle="Date de réception" type="datetime" default="" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_commande
{
var $id;
var $id_client;
var $id_pays;
var $id_transporteur;
var $id_statut;
var $mode_paiement;
var $reference;
var $suivi_colis;
var $num_facture;
var $pay_adresse;
var $exp_adresse;
var $structure;
var $message;
var $total_ht;
var $tva;
var $total_ttc;
var $port;
var $total_pay;
var $infos_tpi;
var $date_commande;
var $date_paiement;
var $date_liv_prevue;
var $date_liv_effective;
var $date_reception;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_commande\" libelle=\"Commande d'un client\" prefix=\"shp_cmd\" display=\"reference\" abstract=\"total_pay\" def_order_field=\"date_commande\" def_order_direction=\"DESC\" statut=\"id_statut\">
<item name=\"id\" type=\"int\" length=\"4\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_client\" libelle=\"Client\" type=\"int\" length=\"8\" fkey=\"shp_client\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" noedit=\"true\" />
<item name=\"id_pays\" libelle=\"Pays\" type=\"int\" length=\"3\" fkey=\"cms_pays\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" noedit=\"true\" /> 
<item name=\"id_transporteur\" libelle=\"Transporteur\" type=\"int\" length=\"3\" fkey=\"shp_transporteur\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" noedit=\"true\" />
<item name=\"id_statut\" libelle=\"Statut\" type=\"int\" length=\"2\" fkey=\"shp_commande_statut\" notnull=\"true\" default=\"1\" list=\"true\" order=\"true\" /> 
<item name=\"mode_paiement\" libelle=\"Mode de paiement\" type=\"enum\" length=\"'CREDIT_CARD','PAYPAL','CHECK','TRANSFER','DIRECT','FREE'\" default=\"CREDIT_CARD\" list=\"true\" order=\"true\" />
<item name=\"reference\" libelle=\"Référence\" type=\"varchar\" length=\"32\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"suivi_colis\" libelle=\"Code de suivi\" type=\"varchar\" length=\"256\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"num_facture\" libelle=\"Numéro\" type=\"varchar\" length=\"256\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"pay_adresse\" libelle=\"Adresse de facturation\" type=\"text\" notnull=\"true\" default=\"\" option=\"textarea\" noedit=\"true\" />
<item name=\"exp_adresse\" libelle=\"Adresse d'expédition\" type=\"text\" notnull=\"true\" default=\"\" option=\"textarea\" noedit=\"true\" />
<item name=\"structure\" libelle=\"Structure\" type=\"text\" notnull=\"true\" default=\"\" noedit=\"true\" serialized=\"true\" />
<item name=\"message\" libelle=\"Comentaires\" type=\"text\" default=\"\" option=\"textarea\" />
<item name=\"total_ht\" libelle=\"Total HT\" type=\"decimal\" length=\"10,2\" notnull=\"true\" default=\"0.00\" noedit=\"true\" />
<item name=\"tva\" libelle=\"TVA\" type=\"decimal\" length=\"10,2\" default=\"0.00\" noedit=\"true\" />
<item name=\"total_ttc\" libelle=\"Total TTC\" type=\"decimal\" length=\"10,2\" notnull=\"true\" default=\"0.00\" noedit=\"true\" />
<item name=\"port\" libelle=\"Frais de port\" type=\"decimal\" length=\"10,2\" default=\"0.00\" noedit=\"true\" />
<item name=\"total_pay\" libelle=\"Total à payer\" list=\"true\" type=\"decimal\" length=\"10,2\" notnull=\"true\" default=\"0.00\" noedit=\"true\" />
<item name=\"infos_tpi\" libelle=\"Infos de paiement (TPI)\" type=\"text\" notnull=\"true\" default=\"\" option=\"textarea\" noedit=\"true\" serialized=\"true\" />
<item name=\"date_commande\" libelle=\"Date de commande\" type=\"datetime\" list=\"true\" order=\"true\" default=\"NULL\" noedit=\"true\" />
<item name=\"date_paiement\" libelle=\"Date de paiement\" type=\"datetime\" default=\"NULL\" noedit=\"true\" />
<item name=\"date_liv_prevue\" libelle=\"Date de livraison prévue\" type=\"datetime\" default=\"NULL\" />
<item name=\"date_liv_effective\" libelle=\"Date de livraison effective\" type=\"datetime\" default=\"NULL\" />
<item name=\"date_reception\" libelle=\"Date de réception\" type=\"datetime\" default=\"\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $sMySql = "CREATE TABLE shp_commande
(
	shp_cmd_id			int (4) PRIMARY KEY not null,
	shp_cmd_id_client			int (8) not null,
	shp_cmd_id_pays			int (3) not null,
	shp_cmd_id_transporteur			int (3) not null,
	shp_cmd_id_statut			int (2) not null,
	shp_cmd_mode_paiement			enum ('CREDIT_CARD','PAYPAL','CHECK','TRANSFER','DIRECT') default 'CREDIT_CARD',
	shp_cmd_reference			varchar (32) not null,
	shp_cmd_suivi_colis			varchar (256),
	shp_cmd_num_facture			varchar (256),
	shp_cmd_pay_adresse			text not null,
	shp_cmd_exp_adresse			text not null,
	shp_cmd_structure			text not null,
	shp_cmd_message			text,
	shp_cmd_total_ht			decimal (10,2) not null,
	shp_cmd_tva			decimal (10,2),
	shp_cmd_total_ttc			decimal (10,2) not null,
	shp_cmd_port			decimal (10,2),
	shp_cmd_total_pay			decimal (10,2) not null,
	shp_cmd_infos_tpi			text not null,
	shp_cmd_date_commande			datetime,
	shp_cmd_date_paiement			datetime,
	shp_cmd_date_liv_prevue			datetime,
	shp_cmd_date_liv_effective			datetime,
	shp_cmd_date_reception			datetime,
	shp_cmd_cdate			datetime not null,
	shp_cmd_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function __construct($id=null)
{
	if (istable("shp_commande") == false){
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
		$this->id_transporteur = -1;
		$this->id_statut = 1;
		$this->mode_paiement = "CREDIT_CARD";
		$this->reference = "";
		$this->suivi_colis = "";
		$this->num_facture = "";
		$this->pay_adresse = "";
		$this->exp_adresse = "";
		$this->structure = "";
		$this->message = "";
		$this->total_ht = 0.00;
		$this->tva = 0.00;
		$this->total_ttc = 0.00;
		$this->port = 0.00;
		$this->total_pay = 0.00;
		$this->infos_tpi = "";
		$this->date_commande = 'NULL';
		$this->date_paiement = 'NULL';
		$this->date_liv_prevue = 'NULL';
		$this->date_liv_effective = 'NULL';
		$this->date_reception = date('Y-m-d H:i:s');
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Shp_cmd_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_cmd_id_client", "entier", "get_id_client", "set_id_client");
	$laListeChamps[]=new dbChamp("Shp_cmd_id_pays", "entier", "get_id_pays", "set_id_pays");
	$laListeChamps[]=new dbChamp("Shp_cmd_id_transporteur", "entier", "get_id_transporteur", "set_id_transporteur");
	$laListeChamps[]=new dbChamp("Shp_cmd_id_statut", "entier", "get_id_statut", "set_id_statut");
	$laListeChamps[]=new dbChamp("Shp_cmd_mode_paiement", "text", "get_mode_paiement", "set_mode_paiement");
	$laListeChamps[]=new dbChamp("Shp_cmd_reference", "text", "get_reference", "set_reference");
	$laListeChamps[]=new dbChamp("Shp_cmd_suivi_colis", "text", "get_suivi_colis", "set_suivi_colis");
	$laListeChamps[]=new dbChamp("Shp_cmd_num_facture", "text", "get_num_facture", "set_num_facture");
	$laListeChamps[]=new dbChamp("Shp_cmd_pay_adresse", "text", "get_pay_adresse", "set_pay_adresse");
	$laListeChamps[]=new dbChamp("Shp_cmd_exp_adresse", "text", "get_exp_adresse", "set_exp_adresse");
	$laListeChamps[]=new dbChamp("Shp_cmd_structure", "text", "get_structure", "set_structure");
	$laListeChamps[]=new dbChamp("Shp_cmd_message", "text", "get_message", "set_message");
	$laListeChamps[]=new dbChamp("Shp_cmd_total_ht", "decimal", "get_total_ht", "set_total_ht");
	$laListeChamps[]=new dbChamp("Shp_cmd_tva", "decimal", "get_tva", "set_tva");
	$laListeChamps[]=new dbChamp("Shp_cmd_total_ttc", "decimal", "get_total_ttc", "set_total_ttc");
	$laListeChamps[]=new dbChamp("Shp_cmd_port", "decimal", "get_port", "set_port");
	$laListeChamps[]=new dbChamp("Shp_cmd_total_pay", "decimal", "get_total_pay", "set_total_pay");
	$laListeChamps[]=new dbChamp("Shp_cmd_infos_tpi", "text", "get_infos_tpi", "set_infos_tpi");
	$laListeChamps[]=new dbChamp("Shp_cmd_date_commande", "date_formatee_timestamp", "get_date_commande", "set_date_commande");
	$laListeChamps[]=new dbChamp("Shp_cmd_date_paiement", "date_formatee_timestamp", "get_date_paiement", "set_date_paiement");
	$laListeChamps[]=new dbChamp("Shp_cmd_date_liv_prevue", "date_formatee_timestamp", "get_date_liv_prevue", "set_date_liv_prevue");
	$laListeChamps[]=new dbChamp("Shp_cmd_date_liv_effective", "date_formatee_timestamp", "get_date_liv_effective", "set_date_liv_effective");
	$laListeChamps[]=new dbChamp("Shp_cmd_date_reception", "date_formatee_timestamp", "get_date_reception", "set_date_reception");
	$laListeChamps[]=new dbChamp("Shp_cmd_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_cmd_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_client() { return($this->id_client); }
function get_id_pays() { return($this->id_pays); }
function get_id_transporteur() { return($this->id_transporteur); }
function get_id_statut() { return($this->id_statut); }
function get_mode_paiement() { return($this->mode_paiement); }
function get_reference() { return($this->reference); }
function get_suivi_colis() { return($this->suivi_colis); }
function get_num_facture() { return($this->num_facture); }
function get_pay_adresse() { return($this->pay_adresse); }
function get_exp_adresse() { return($this->exp_adresse); }
function get_structure() { return($this->structure); }
function get_message() { return($this->message); }
function get_total_ht() { return($this->total_ht); }
function get_tva() { return($this->tva); }
function get_total_ttc() { return($this->total_ttc); }
function get_port() { return($this->port); }
function get_total_pay() { return($this->total_pay); }
function get_infos_tpi() { return($this->infos_tpi); }
function get_date_commande() { return($this->date_commande); }
function get_date_paiement() { return($this->date_paiement); }
function get_date_liv_prevue() { return($this->date_liv_prevue); }
function get_date_liv_effective() { return($this->date_liv_effective); }
function get_date_reception() { return($this->date_reception); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_cmd_id) { return($this->id=$c_shp_cmd_id); }
function set_id_client($c_shp_cmd_id_client) { return($this->id_client=$c_shp_cmd_id_client); }
function set_id_pays($c_shp_cmd_id_pays) { return($this->id_pays=$c_shp_cmd_id_pays); }
function set_id_transporteur($c_shp_cmd_id_transporteur) { return($this->id_transporteur=$c_shp_cmd_id_transporteur); }
function set_id_statut($c_shp_cmd_id_statut) { return($this->id_statut=$c_shp_cmd_id_statut); }
function set_mode_paiement($c_shp_cmd_mode_paiement) { return($this->mode_paiement=$c_shp_cmd_mode_paiement); }
function set_reference($c_shp_cmd_reference) { return($this->reference=$c_shp_cmd_reference); }
function set_suivi_colis($c_shp_cmd_suivi_colis) { return($this->suivi_colis=$c_shp_cmd_suivi_colis); }
function set_num_facture($c_shp_cmd_num_facture) { return($this->num_facture=$c_shp_cmd_num_facture); }
function set_pay_adresse($c_shp_cmd_pay_adresse) { return($this->pay_adresse=$c_shp_cmd_pay_adresse); }
function set_exp_adresse($c_shp_cmd_exp_adresse) { return($this->exp_adresse=$c_shp_cmd_exp_adresse); }
function set_structure($c_shp_cmd_structure) { return($this->structure=$c_shp_cmd_structure); }
function set_message($c_shp_cmd_message) { return($this->message=$c_shp_cmd_message); }
function set_total_ht($c_shp_cmd_total_ht) { return($this->total_ht=$c_shp_cmd_total_ht); }
function set_tva($c_shp_cmd_tva) { return($this->tva=$c_shp_cmd_tva); }
function set_total_ttc($c_shp_cmd_total_ttc) { return($this->total_ttc=$c_shp_cmd_total_ttc); }
function set_port($c_shp_cmd_port) { return($this->port=$c_shp_cmd_port); }
function set_total_pay($c_shp_cmd_total_pay) { return($this->total_pay=$c_shp_cmd_total_pay); }
function set_infos_tpi($c_shp_cmd_infos_tpi) { return($this->infos_tpi=$c_shp_cmd_infos_tpi); }
function set_date_commande($c_shp_cmd_date_commande) { return($this->date_commande=$c_shp_cmd_date_commande); }
function set_date_paiement($c_shp_cmd_date_paiement) { return($this->date_paiement=$c_shp_cmd_date_paiement); }
function set_date_liv_prevue($c_shp_cmd_date_liv_prevue) { return($this->date_liv_prevue=$c_shp_cmd_date_liv_prevue); }
function set_date_liv_effective($c_shp_cmd_date_liv_effective) { return($this->date_liv_effective=$c_shp_cmd_date_liv_effective); }
function set_date_reception($c_shp_cmd_date_reception) { return($this->date_reception=$c_shp_cmd_date_reception); }
function set_cdate($c_shp_cmd_cdate) { return($this->cdate=$c_shp_cmd_cdate); }
function set_mdate($c_shp_cmd_mdate) { return($this->mdate=$c_shp_cmd_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_cmd_id"); }
// statut
function getGetterStatut() {return("get_id_statut"); }
function getFieldStatut() {return("shp_id_statut"); }
//
function getTable() { return("shp_commande"); }
function getClasse() { return("shp_commande"); }
function getPrefix() { return("shp_cmd"); }
function getDisplay() { return("reference"); }
function getAbstract() { return("total_pay"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande/list_shp_commande.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande/maj_shp_commande.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande/show_shp_commande.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande/rss_shp_commande.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande/xml_shp_commande.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande/xmlxls_shp_commande.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande/export_shp_commande.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_commande/import_shp_commande.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>