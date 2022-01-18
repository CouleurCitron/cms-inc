<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 10/08/05
objet de BDD CMS_AVIS :: class Cms_avis


==========================================*/

class Cms_avis
{

var $id_avis;
var $id_page_avis;
var $titre_avis;
var $texte_avis;
var $nomcontact_avis;
var $mailcontact_avis;
var $nomweb1_avis;
var $web1_avis;
var $nomweb2_avis;
var $web2_avis;
var $note_avis;
var $statut_avis;
var $dcreat_avis;
var $dmaj_avis;
var $ucreat_avis;
var $umaj_avis;
var $id_site;

// constructeur
function __construct($id=null) 
{
	if($id!=null) {
		$this = dbGetObjectFromPK("Cms_avis", $id);
	} else {
		$this->id_avis = -1;
		$this->id_page_avis = -1;
		$this->titre_avis = '';		
		$this->texte_avis = '';
		$this->nomcontact_avis = '';		
		$this->mailcontact_avis = '';
		$this->nomweb1_avis = '';
		$this->web1_avis = '';
		$this->nomweb2_avis = '';		
		$this->web2_avis = '';		
		$this->note_avis = '';				
		$this->statut_avis = DEF_CODE_STATUT_DEFAUT;						
		$this->dcreat_avis = '';						
		$this->dmaj_avis = '';						
		$this->ucreat_avis = '';						
		$this->umaj_avis = '';						
		$this->id_site = '';						
	}
}

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("id_avis", "entier", "getId_avis", "setId_avis");
	$laListeChamps[]=new dbChamp("id_page_avis", "entier", "getId_page_avis", "setId_page_avis");
	$laListeChamps[]=new dbChamp("titre_avis", "text", "getTitre_avis", "setTitre_avis");
	$laListeChamps[]=new dbChamp("texte_avis", "text", "getTexte_avis", "setTexte_avis");
	$laListeChamps[]=new dbChamp("nomcontact_avis", "text", "getNomcontact_avis", "setNomcontact_avis");
	$laListeChamps[]=new dbChamp("mailcontact_avis", "text", "getMailcontact_avis", "setMailcontact_avis");
	$laListeChamps[]=new dbChamp("nomweb1_avis", "text", "getNomweb1_avis", "setNomweb1_avis");
	$laListeChamps[]=new dbChamp("web1_avis", "text", "getWeb1_avis", "setWeb1_avis");
	$laListeChamps[]=new dbChamp("nomweb2_avis", "text", "getNomweb2_avis", "setNomweb2_avis");
	$laListeChamps[]=new dbChamp("web2_avis", "text", "getWeb2_avis", "setWeb2_avis");
	$laListeChamps[]=new dbChamp("note_avis", "text", "getNote_avis", "setNote_avis");
	$laListeChamps[]=new dbChamp("statut_avis", "entier", "getStatut_avis", "setStatut_avis");
	$laListeChamps[]=new dbChamp("dcreat_avis", "date_formatee", "getDcreat_avis", "setDcreat_avis");
	$laListeChamps[]=new dbChamp("dmaj_avis", "date_formatee", "getDmaj_avis", "setDmaj_avis");
	$laListeChamps[]=new dbChamp("ucreat_avis", "entier", "getUcreat_avis", "setUcreat_avis");
	$laListeChamps[]=new dbChamp("umaj_avis", "entier", "getUmaj_avis", "setUmaj_avis");
	$laListeChamps[]=new dbChamp("id_site", "entier", "getId_site", "setId_site");

	return($laListeChamps);
}

// getters
function getId_avis() { return($this->id_avis); }
function getId_page_avis() { return($this->id_page_avis); }
function getTitre_avis() { return($this->titre_avis); }
function getTexte_avis() { return($this->texte_avis); }
function getNomcontact_avis() { return($this->nomcontact_avis); }
function getMailcontact_avis() { return($this->mailcontact_avis); }
function getNomweb1_avis() { return($this->nomweb1_avis); }
function getWeb1_avis() { return($this->web1_avis); }
function getNomweb2_avis() { return($this->nomweb2_avis); }
function getWeb2_avis() { return($this->web2_avis); }
function getNote_avis() { return($this->note_avis); }
function getStatut_avis() { return($this->statut_avis); }
function getDcreat_avis() { return($this->dcreat_avis); }
function getDmaj_avis() { return($this->dmaj_avis); }
function getUcreat_avis() { return($this->ucreat_avis); }
function getUmaj_avis() { return($this->umaj_avis); }
function getId_site() { return($this->id_site); }

// setters
function setId_avis($c_id_avis) { return($this->id_avis=$c_id_avis); }
function setId_page_avis($c_id_page_avis) { return($this->id_page_avis=$c_id_page_avis); }
function setTitre_avis($c_titre_avis) { return($this->titre_avis=$c_titre_avis); }
function setTexte_avis($c_texte_avis) { return($this->texte_avis=$c_texte_avis); }
function setNomcontact_avis($c_nomcontact_avis) { return($this->nomcontact_avis=$c_nomcontact_avis); }
function setMailcontact_avis($c_mailcontact_avis) { return($this->mailcontact_avis=$c_mailcontact_avis); }
function setNomweb1_avis($c_nomweb1_avis) { return($this->nomweb1_avis=$c_nomweb1_avis); }
function setWeb1_avis($c_web1_avis) { return($this->web1_avis=$c_web1_avis); }
function setNomweb2_avis($c_nomweb2_avis) { return($this->nomweb2_avis=$c_nomweb2_avis); }
function setWeb2_avis($c_web2_avis) { return($this->web2_avis=$c_web2_avis); }
function setNote_avis($c_note_avis) { return($this->note_avis=$c_note_avis); }
function setStatut_avis($c_statut_avis) { return($this->statut_avis=$c_statut_avis); }
function setDcreat_avis($c_dcreat_avis) { return($this->dcreat_avis=$c_dcreat_avis); }
function setDmaj_avis($c_dmaj_avis) { return($this->dmaj_avis=$c_dmaj_avis); }
function setUcreat_avis($c_ucreat_avis) { return($this->ucreat_avis=$c_ucreat_avis); }
function setUmaj_avis($c_umaj_avis) { return($this->umaj_avis=$c_umaj_avis); }
function setId_site($c_id_site) { return($this->id_site=$c_id_site); }


// autres getters
function getGetterPK() { return("getId_avis"); }
function getSetterPK() { return("setId_avis"); }
function getFieldPK() { return("id_avis"); }
function getGetterStatut() {return("getStatut_avis"); }
function getFieldStatut() {return("statut_avis"); }
function getTable() { return("cms_avis"); }
function getClasse() { return("Cms_avis"); }


} //class

?>