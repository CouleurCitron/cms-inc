<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('shp_client')){
	$rs = $db->Execute('SHOW COLUMNS FROM `shp_produit`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('shp_pdt_poids_brut', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_produit` ADD `shp_pdt_poids_brut` DECIMAL( 10, 3 ) DEFAULT '0.000' NOT NULL AFTER `shp_pdt_poids_unite`;");
		}
		if (!in_array('shp_pdt_ref_titre', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_produit` ADD `shp_pdt_ref_titre` int (11) AFTER `shp_pdt_texte_long`;");
			$rs = $db->Execute("ALTER TABLE `shp_produit` ADD `shp_pdt_ref_keywords` int (11) AFTER `shp_pdt_ref_titre`;");
			$rs = $db->Execute("ALTER TABLE `shp_produit` ADD `shp_pdt_ref_description` int (11) AFTER `shp_pdt_ref_keywords`;");
		}
		if (!in_array('shp_pdt_rem_ordre', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_produit` ADD `shp_pdt_rem_ordre` INT( 11 ) DEFAULT NULL AFTER `shp_pdt_remontee`;");
		}
	}
}

/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shp_produit.class.php')  && (strpos(__FILE__,'/include/bo/class/shp_produit.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shp_produit.class.php');
}else{
/*======================================

objet de BDD shp_produit :: class shp_produit

SQL mySQL:

DROP TABLE IF EXISTS shp_produit;
CREATE TABLE shp_produit
(
	shp_pdt_id			int (11) PRIMARY KEY not null,
	shp_pdt_id_gamme			int (4) not null,
	shp_pdt_id_type			int (4) not null,
	shp_pdt_id_unite			int (3) not null,
	shp_pdt_id_diaporama			int (11),
	shp_pdt_statut			int (2) not null,
	shp_pdt_reference			varchar (128) not null,
	shp_pdt_remontee			enum ('Y','N') not null default 'N',
	shp_pdt_rem_ordre			int (11),
	shp_pdt_echantillon			enum ('Y','N') not null default 'N',
	shp_pdt_titre_court			int (11) not null,
	shp_pdt_titre_long			int (11),
	shp_pdt_sous_titre			int (11),
	shp_pdt_texte_long			int (11),
	shp_pdt_ref_titre			int (11),
	shp_pdt_ref_keywords			int (11),
	shp_pdt_ref_description			int (11),
	shp_pdt_dimensions			varchar (256),
	shp_pdt_infos			int (11),
	shp_pdt_vignette			varchar (256),
	shp_pdt_visuel			varchar (256),
	shp_pdt_document			int (11),
	shp_pdt_url			varchar (256),
	shp_pdt_pieces_unite			decimal (10,3) not null,
	shp_pdt_poids_unite			decimal (10,3) not null,
	shp_pdt_poids_brut			decimal (10,3) not null,
	shp_pdt_delai_livraison			int (3),
	shp_pdt_quantite_stock			int (11) not null,
	shp_pdt_alerte_stock			int (11) not null,
	shp_pdt_ordre			int (11),
	shp_pdt_cdate			datetime not null,
	shp_pdt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_produit
CREATE TABLE shp_produit
(
	shp_pdt_id			number (11) constraint shp_pdt_pk PRIMARY KEY not null,
	shp_pdt_id_gamme			number (4) not null,
	shp_pdt_id_type			number (4) not null,
	shp_pdt_id_unite			number (3) not null,
	shp_pdt_id_diaporama			number (11),
	shp_pdt_statut			number (2) not null,
	shp_pdt_reference			varchar2 (128) not null,
	shp_pdt_remontee			enum ('Y','N') not null default 'N',
	shp_pdt_rem_ordre			number (11),
	shp_pdt_echantillon			enum ('Y','N') not null default 'N',
	shp_pdt_titre_court			number (11) not null,
	shp_pdt_titre_long			number (11),
	shp_pdt_sous_titre			number (11),
	shp_pdt_texte_long			number (11),
	shp_pdt_ref_titre			number (11),
	shp_pdt_ref_keywords			number (11),
	shp_pdt_ref_description			number (11),
	shp_pdt_dimensions			varchar2 (256),
	shp_pdt_infos			number (11),
	shp_pdt_vignette			varchar2 (256),
	shp_pdt_visuel			varchar2 (256),
	shp_pdt_document			number (11),
	shp_pdt_url			varchar2 (256),
	shp_pdt_pieces_unite			decimal (10,3) not null,
	shp_pdt_poids_unite			decimal (10,3) not null,
	shp_pdt_poids_brut			decimal (10,3) not null,
	shp_pdt_delai_livraison			number (3),
	shp_pdt_quantite_stock			number (11) not null,
	shp_pdt_alerte_stock			number (11) not null,
	shp_pdt_ordre			number (11),
	shp_pdt_cdate			datetime not null,
	shp_pdt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_produit" libelle="Produit de la boutique" prefix="shp_pdt" display="reference" abstract="titre_court">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="shp_tarif"/> 
<item name="id_gamme" libelle="Gamme" type="int" length="4" fkey="shp_gamme" notnull="true" default="0" list="true" order="true" /> 
<item name="id_type" libelle="Type" type="int" length="4" fkey="shp_produit_type" notnull="true" default="0" list="true" order="true" />
<item name="id_unite" libelle="Unité de mesure" type="int" length="3" fkey="shp_unite" notnull="true" default="0" list="true" order="true" /> 
<item name="id_diaporama" libelle="Diaporama" type="int" length="11" fkey="cms_diaporama" list="true" order="true" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="reference" libelle="Référence" type="varchar" length="128" notnull="true" default="" list="true" order="true" nohtml="true"  />
<item name="remontee" libelle="Remontée en page d'accueil" type="enum" length="'Y','N'" default="N" notnull="true" />
<item name="rem_ordre" libelle="Ordre en remontée" type="int" length="11" />
<item name="echantillon" libelle="Vendu en échantillon" type="enum" length="'Y','N'" default="N" notnull="true" />
<item name="titre_court" libelle="Titre court" type="int" length="11" notnull="true" default="" list="true" order="true" nohtml="true" translate="reference" />
<item name="titre_long" libelle="Titre long" type="int" length="11" default="" nohtml="true" translate="reference" />
<item name="sous_titre" libelle="Sous-titre" type="int" length="11" default="" nohtml="true" translate="reference" />
<item name="texte_long" libelle="Texte long" type="int" length="11" default="" translate="reference" option="textarea" />
<item name="ref_titre" libelle="Titre référencement" type="int" length="11" default="" translate="reference" />
<item name="ref_keywords" libelle="Mots clés référencement" type="int" length="11" default="" translate="reference" />
<item name="ref_description" libelle="Description référencement" type="int" length="11" default="" translate="reference" option="textarea" />
<item name="dimensions" libelle="Dimensions" type="varchar" length="256" default="" list="true" order="true" nohtml="true" />
<item name="infos" libelle="Informations" type="int" length="11" default="" translate="reference" option="textarea" />
<item name="vignette" libelle="Vignette" type="varchar" length="256" default="" option="file" />
<item name="visuel" libelle="Visuel" type="varchar" length="256" default="" option="file" />
<item name="document" libelle="Document" type="int" length="11" />
<item name="url" libelle="URL" type="varchar" length="256" default="" option="link" />
<item name="pieces_unite" libelle="Nombre de pièces à l'unité de mesure" type="decimal" length="10,3" notnull="true" default="1.000" />
<item name="poids_unite" libelle="Poids à l'unité de mesure (net)" type="decimal" length="10,3" notnull="true" default="0.000" />
<item name="poids_brut" libelle="Poids total (brut)" type="decimal" length="10,3" notnull="true" default="0.000" />
<item name="delai_livraison" libelle="Délai de livraison (jours)" type="int" length="3" default="" nohtml="true" />
<item name="quantite_stock" libelle="Quantité en stock" type="int" length="11" notnull="true" />
<item name="alerte_stock" libelle="Alerte sur stock minimal" type="int" length="11" notnull="true" />
<item name="ordre" libelle="Ordre d'apparition" type="int" length="11" list="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class shp_produit
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $id_gamme;
var $id_type;
var $id_unite;
var $id_diaporama;
var $statut;
var $reference;
var $remontee;
var $rem_ordre;
var $echantillon;
var $titre_court;
var $titre_long;
var $sous_titre;
var $texte_long;
var $ref_titre;
var $ref_keywords;
var $ref_description;
var $dimensions;
var $infos;
var $vignette;
var $visuel;
var $document;
var $url;
var $pieces_unite;
var $poids_unite;
var $poids_brut;
var $delai_livraison;
var $quantite_stock;
var $alerte_stock;
var $ordre;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_produit\" libelle=\"Produit de la boutique\" prefix=\"shp_pdt\" display=\"reference\" abstract=\"titre_court\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"shp_tarif\"/> 
<item name=\"id_gamme\" libelle=\"Gamme\" type=\"int\" length=\"4\" fkey=\"shp_gamme\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" /> 
<item name=\"id_type\" libelle=\"Type\" type=\"int\" length=\"4\" fkey=\"shp_produit_type\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"id_unite\" libelle=\"Unité de mesure\" type=\"int\" length=\"3\" fkey=\"shp_unite\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" /> 
<item name=\"id_diaporama\" libelle=\"Diaporama\" type=\"int\" length=\"11\" fkey=\"cms_diaporama\" list=\"true\" order=\"true\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"reference\" libelle=\"Référence\" type=\"varchar\" length=\"128\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\"  />
<item name=\"remontee\" libelle=\"Remontée en page d'accueil\" type=\"enum\" length=\"'Y','N'\" default=\"N\" notnull=\"true\" />
<item name=\"rem_ordre\" libelle=\"Ordre en remontée\" type=\"int\" length=\"11\" />
<item name=\"echantillon\" libelle=\"Vendu en échantillon\" type=\"enum\" length=\"'Y','N'\" default=\"N\" notnull=\"true\" />
<item name=\"titre_court\" libelle=\"Titre court\" type=\"int\" length=\"11\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" translate=\"reference\" />
<item name=\"titre_long\" libelle=\"Titre long\" type=\"int\" length=\"11\" default=\"\" nohtml=\"true\" translate=\"reference\" />
<item name=\"sous_titre\" libelle=\"Sous-titre\" type=\"int\" length=\"11\" default=\"\" nohtml=\"true\" translate=\"reference\" />
<item name=\"texte_long\" libelle=\"Texte long\" type=\"int\" length=\"11\" default=\"\" translate=\"reference\" option=\"textarea\" />
<item name=\"ref_titre\" libelle=\"Titre référencement\" type=\"int\" length=\"11\" default=\"\" translate=\"reference\" />
<item name=\"ref_keywords\" libelle=\"Mots clés référencement\" type=\"int\" length=\"11\" default=\"\" translate=\"reference\" />
<item name=\"ref_description\" libelle=\"Description référencement\" type=\"int\" length=\"11\" default=\"\" translate=\"reference\" option=\"textarea\" />
<item name=\"dimensions\" libelle=\"Dimensions\" type=\"varchar\" length=\"256\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"infos\" libelle=\"Informations\" type=\"int\" length=\"11\" default=\"\" translate=\"reference\" option=\"textarea\" />
<item name=\"vignette\" libelle=\"Vignette\" type=\"varchar\" length=\"256\" default=\"\" option=\"file\" />
<item name=\"visuel\" libelle=\"Visuel\" type=\"varchar\" length=\"256\" default=\"\" option=\"file\" />
<item name=\"document\" libelle=\"Document\" type=\"int\" length=\"11\" />
<item name=\"url\" libelle=\"URL\" type=\"varchar\" length=\"256\" default=\"\" option=\"link\" />
<item name=\"pieces_unite\" libelle=\"Nombre de pièces à l'unité de mesure\" type=\"decimal\" length=\"10,3\" notnull=\"true\" default=\"1.000\" />
<item name=\"poids_unite\" libelle=\"Poids à l'unité de mesure (net)\" type=\"decimal\" length=\"10,3\" notnull=\"true\" default=\"0.000\" />
<item name=\"poids_brut\" libelle=\"Poids total (brut)\" type=\"decimal\" length=\"10,3\" notnull=\"true\" default=\"0.000\" />
<item name=\"delai_livraison\" libelle=\"Délai de livraison (jours)\" type=\"int\" length=\"3\" default=\"\" nohtml=\"true\" />
<item name=\"quantite_stock\" libelle=\"Quantité en stock\" type=\"int\" length=\"11\" notnull=\"true\" />
<item name=\"alerte_stock\" libelle=\"Alerte sur stock minimal\" type=\"int\" length=\"11\" notnull=\"true\" />
<item name=\"ordre\" libelle=\"Ordre d'apparition\" type=\"int\" length=\"11\" list=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE shp_produit
(
	shp_pdt_id			int (11) PRIMARY KEY not null,
	shp_pdt_id_gamme			int (4) not null,
	shp_pdt_id_type			int (4) not null,
	shp_pdt_id_unite			int (3) not null,
	shp_pdt_id_diaporama			int (11),
	shp_pdt_statut			int (2) not null,
	shp_pdt_reference			varchar (128) not null,
	shp_pdt_remontee			enum ('Y','N') not null default 'N',
	shp_pdt_rem_ordre			int (11),
	shp_pdt_echantillon			enum ('Y','N') not null default 'N',
	shp_pdt_titre_court			int (11) not null,
	shp_pdt_titre_long			int (11),
	shp_pdt_sous_titre			int (11),
	shp_pdt_texte_long			int (11),
	shp_pdt_ref_titre			int (11),
	shp_pdt_ref_keywords			int (11),
	shp_pdt_ref_description			int (11),
	shp_pdt_dimensions			varchar (256),
	shp_pdt_infos			int (11),
	shp_pdt_vignette			varchar (256),
	shp_pdt_visuel			varchar (256),
	shp_pdt_document			int (11),
	shp_pdt_url			varchar (256),
	shp_pdt_pieces_unite			decimal (10,3) not null,
	shp_pdt_poids_unite			decimal (10,3) not null,
	shp_pdt_poids_brut			decimal (10,3) not null,
	shp_pdt_delai_livraison			int (3),
	shp_pdt_quantite_stock			int (11) not null,
	shp_pdt_alerte_stock			int (11) not null,
	shp_pdt_ordre			int (11),
	shp_pdt_cdate			datetime not null,
	shp_pdt_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function shp_produit($id=null)
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
		$this->id_gamme = -1;
		$this->id_type = -1;
		$this->id_unite = -1;
		$this->id_diaporama = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->reference = "";
		$this->remontee = "N";
		$this->rem_ordre = -1;
		$this->echantillon = "N";
		$this->titre_court = -1;
		$this->titre_long = -1;
		$this->sous_titre = -1;
		$this->texte_long = -1;
		$this->ref_titre = -1;
		$this->ref_keywords = -1;
		$this->ref_description = -1;
		$this->dimensions = "";
		$this->infos = -1;
		$this->vignette = "";
		$this->visuel = "";
		$this->document = -1;
		$this->url = "";
		$this->pieces_unite = 1.000;
		$this->poids_unite = 0.000;
		$this->poids_brut = 0.000;
		$this->delai_livraison = -1;
		$this->quantite_stock = -1;
		$this->alerte_stock = -1;
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
	$laListeChamps[]=new dbChamp("Shp_pdt_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_pdt_id_gamme", "entier", "get_id_gamme", "set_id_gamme");
	$laListeChamps[]=new dbChamp("Shp_pdt_id_type", "entier", "get_id_type", "set_id_type");
	$laListeChamps[]=new dbChamp("Shp_pdt_id_unite", "entier", "get_id_unite", "set_id_unite");
	$laListeChamps[]=new dbChamp("Shp_pdt_id_diaporama", "entier", "get_id_diaporama", "set_id_diaporama");
	$laListeChamps[]=new dbChamp("Shp_pdt_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_pdt_reference", "text", "get_reference", "set_reference");
	$laListeChamps[]=new dbChamp("Shp_pdt_remontee", "text", "get_remontee", "set_remontee");
	$laListeChamps[]=new dbChamp("Shp_pdt_rem_ordre", "entier", "get_rem_ordre", "set_rem_ordre");
	$laListeChamps[]=new dbChamp("Shp_pdt_echantillon", "text", "get_echantillon", "set_echantillon");
	$laListeChamps[]=new dbChamp("Shp_pdt_titre_court", "entier", "get_titre_court", "set_titre_court");
	$laListeChamps[]=new dbChamp("Shp_pdt_titre_long", "entier", "get_titre_long", "set_titre_long");
	$laListeChamps[]=new dbChamp("Shp_pdt_sous_titre", "entier", "get_sous_titre", "set_sous_titre");
	$laListeChamps[]=new dbChamp("Shp_pdt_texte_long", "entier", "get_texte_long", "set_texte_long");
	$laListeChamps[]=new dbChamp("Shp_pdt_ref_titre", "entier", "get_ref_titre", "set_ref_titre");
	$laListeChamps[]=new dbChamp("Shp_pdt_ref_keywords", "entier", "get_ref_keywords", "set_ref_keywords");
	$laListeChamps[]=new dbChamp("Shp_pdt_ref_description", "entier", "get_ref_description", "set_ref_description");
	$laListeChamps[]=new dbChamp("Shp_pdt_dimensions", "text", "get_dimensions", "set_dimensions");
	$laListeChamps[]=new dbChamp("Shp_pdt_infos", "entier", "get_infos", "set_infos");
	$laListeChamps[]=new dbChamp("Shp_pdt_vignette", "text", "get_vignette", "set_vignette");
	$laListeChamps[]=new dbChamp("Shp_pdt_visuel", "text", "get_visuel", "set_visuel");
	$laListeChamps[]=new dbChamp("Shp_pdt_document", "entier", "get_document", "set_document");
	$laListeChamps[]=new dbChamp("Shp_pdt_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Shp_pdt_pieces_unite", "decimal", "get_pieces_unite", "set_pieces_unite");
	$laListeChamps[]=new dbChamp("Shp_pdt_poids_unite", "decimal", "get_poids_unite", "set_poids_unite");
	$laListeChamps[]=new dbChamp("Shp_pdt_poids_brut", "decimal", "get_poids_brut", "set_poids_brut");
	$laListeChamps[]=new dbChamp("Shp_pdt_delai_livraison", "entier", "get_delai_livraison", "set_delai_livraison");
	$laListeChamps[]=new dbChamp("Shp_pdt_quantite_stock", "entier", "get_quantite_stock", "set_quantite_stock");
	$laListeChamps[]=new dbChamp("Shp_pdt_alerte_stock", "entier", "get_alerte_stock", "set_alerte_stock");
	$laListeChamps[]=new dbChamp("Shp_pdt_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Shp_pdt_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_pdt_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_gamme() { return($this->id_gamme); }
function get_id_type() { return($this->id_type); }
function get_id_unite() { return($this->id_unite); }
function get_id_diaporama() { return($this->id_diaporama); }
function get_statut() { return($this->statut); }
function get_reference() { return($this->reference); }
function get_remontee() { return($this->remontee); }
function get_rem_ordre() { return($this->rem_ordre); }
function get_echantillon() { return($this->echantillon); }
function get_titre_court() { return($this->titre_court); }
function get_titre_long() { return($this->titre_long); }
function get_sous_titre() { return($this->sous_titre); }
function get_texte_long() { return($this->texte_long); }
function get_ref_titre() { return($this->ref_titre); }
function get_ref_keywords() { return($this->ref_keywords); }
function get_ref_description() { return($this->ref_description); }
function get_dimensions() { return($this->dimensions); }
function get_infos() { return($this->infos); }
function get_vignette() { return($this->vignette); }
function get_visuel() { return($this->visuel); }
function get_document() { return($this->document); }
function get_url() { return($this->url); }
function get_pieces_unite() { return($this->pieces_unite); }
function get_poids_unite() { return($this->poids_unite); }
function get_poids_brut() { return($this->poids_brut); }
function get_delai_livraison() { return($this->delai_livraison); }
function get_quantite_stock() { return($this->quantite_stock); }
function get_alerte_stock() { return($this->alerte_stock); }
function get_ordre() { return($this->ordre); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_pdt_id) { return($this->id=$c_shp_pdt_id); }
function set_id_gamme($c_shp_pdt_id_gamme) { return($this->id_gamme=$c_shp_pdt_id_gamme); }
function set_id_type($c_shp_pdt_id_type) { return($this->id_type=$c_shp_pdt_id_type); }
function set_id_unite($c_shp_pdt_id_unite) { return($this->id_unite=$c_shp_pdt_id_unite); }
function set_id_diaporama($c_shp_pdt_id_diaporama) { return($this->id_diaporama=$c_shp_pdt_id_diaporama); }
function set_statut($c_shp_pdt_statut) { return($this->statut=$c_shp_pdt_statut); }
function set_reference($c_shp_pdt_reference) { return($this->reference=$c_shp_pdt_reference); }
function set_remontee($c_shp_pdt_remontee) { return($this->remontee=$c_shp_pdt_remontee); }
function set_rem_ordre($c_shp_pdt_rem_ordre) { return($this->rem_ordre=$c_shp_pdt_rem_ordre); }
function set_echantillon($c_shp_pdt_echantillon) { return($this->echantillon=$c_shp_pdt_echantillon); }
function set_titre_court($c_shp_pdt_titre_court) { return($this->titre_court=$c_shp_pdt_titre_court); }
function set_titre_long($c_shp_pdt_titre_long) { return($this->titre_long=$c_shp_pdt_titre_long); }
function set_sous_titre($c_shp_pdt_sous_titre) { return($this->sous_titre=$c_shp_pdt_sous_titre); }
function set_texte_long($c_shp_pdt_texte_long) { return($this->texte_long=$c_shp_pdt_texte_long); }
function set_ref_titre($c_shp_pdt_ref_titre) { return($this->ref_titre=$c_shp_pdt_ref_titre); }
function set_ref_keywords($c_shp_pdt_ref_keywords) { return($this->ref_keywords=$c_shp_pdt_ref_keywords); }
function set_ref_description($c_shp_pdt_ref_description) { return($this->ref_description=$c_shp_pdt_ref_description); }
function set_dimensions($c_shp_pdt_dimensions) { return($this->dimensions=$c_shp_pdt_dimensions); }
function set_infos($c_shp_pdt_infos) { return($this->infos=$c_shp_pdt_infos); }
function set_vignette($c_shp_pdt_vignette) { return($this->vignette=$c_shp_pdt_vignette); }
function set_visuel($c_shp_pdt_visuel) { return($this->visuel=$c_shp_pdt_visuel); }
function set_document($c_shp_pdt_document) { return($this->document=$c_shp_pdt_document); }
function set_url($c_shp_pdt_url) { return($this->url=$c_shp_pdt_url); }
function set_pieces_unite($c_shp_pdt_pieces_unite) { return($this->pieces_unite=$c_shp_pdt_pieces_unite); }
function set_poids_unite($c_shp_pdt_poids_unite) { return($this->poids_unite=$c_shp_pdt_poids_unite); }
function set_poids_brut($c_shp_pdt_poids_brut) { return($this->poids_brut=$c_shp_pdt_poids_brut); }
function set_delai_livraison($c_shp_pdt_delai_livraison) { return($this->delai_livraison=$c_shp_pdt_delai_livraison); }
function set_quantite_stock($c_shp_pdt_quantite_stock) { return($this->quantite_stock=$c_shp_pdt_quantite_stock); }
function set_alerte_stock($c_shp_pdt_alerte_stock) { return($this->alerte_stock=$c_shp_pdt_alerte_stock); }
function set_ordre($c_shp_pdt_ordre) { return($this->ordre=$c_shp_pdt_ordre); }
function set_cdate($c_shp_pdt_cdate) { return($this->cdate=$c_shp_pdt_cdate); }
function set_mdate($c_shp_pdt_mdate) { return($this->mdate=$c_shp_pdt_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_pdt_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_pdt_statut"); }
//
function getTable() { return("shp_produit"); }
function getClasse() { return("shp_produit"); }
function getPrefix() { return("shp_pdt"); }
function getDisplay() { return("reference"); }
function getAbstract() { return("titre_court"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit/list_shp_produit.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit/maj_shp_produit.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit/show_shp_produit.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit/rss_shp_produit.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit/xml_shp_produit.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit/xmlxls_shp_produit.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit/export_shp_produit.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_produit/import_shp_produit.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>