<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_offre.class.php')  && (strpos(__FILE__,'/include/bo/class/job_offre.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_offre.class.php');
}else{
/*======================================

objet de BDD job_offre :: class job_offre

SQL mySQL:

DROP TABLE IF EXISTS job_offre;
CREATE TABLE job_offre
(
	job_id			int (11) PRIMARY KEY not null,
	job_bo_users			int (11),
	job_statut			int not null,
	job_reference			varchar (255),
	job_libelle			int (11),
	job_entreprise			int (11),
	job_detail			int (11),
	job_profil			int (11),
	job_contrat			int (11),
	job_domaine			int,
	job_metier			int (11),
	job_lieu			int,
	job_lieuplus			int (11),
	job_qualification			int (11),
	job_experience			int (11),
	job_remuneration			int (11),
	job_langue			int (11),
	job_date_debut			varchar (2),
	job_date_pub_debut			datetime,
	job_date_pub_fin			datetime,
	job_commentaire			text,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE job_offre
CREATE TABLE job_offre
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_bo_users			number (11),
	job_statut			number not null,
	job_reference			varchar2 (255),
	job_libelle			number (11),
	job_entreprise			number (11),
	job_detail			number (11),
	job_profil			number (11),
	job_contrat			number (11),
	job_domaine			number,
	job_metier			number (11),
	job_lieu			number,
	job_lieuplus			number (11),
	job_qualification			number (11),
	job_experience			number (11),
	job_remuneration			number (11),
	job_langue			number (11),
	job_date_debut			varchar2 (2),
	job_date_pub_debut			datetime,
	job_date_pub_fin			datetime,
	job_commentaire			text,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_offre" libelle="Offres d'emploi" prefix="job" display="libelle" abstract="lieu" def_order_field="date_pub_debut" def_order_direction="DESC">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" asso="job_assooffredestinataire,job_candidature" />
<item name="bo_users" libelle="Administrateur" type="int" length="11" list="false" order="false" fkey="bo_users" restrict="loose" />
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true">
	<option type="value" value="1" libelle="en attente" />
	<option type="value" value="3" libelle="hors-ligne" />
	<option type="value" value="4" libelle="en ligne" />
	<option type="value" value="5" libelle="archivé" />
</item>
<item name="reference" libelle="Référence" type="varchar" length="255" list="true" order="true" oblig="true" nohtml="true"/>
<item name="libelle" libelle="Nom du poste"  type="int" length="11" list="true" order="true" translate="reference" oblig="true" nohtml="true" />
<item name="entreprise" libelle="Présentation de l'entreprise"  option="textarea" type="int" length="11" list="false" order="false" translate="reference" />
<item name="detail" libelle="Détails de la mission"  option="textarea" type="int" length="11" list="false" order="true" translate="reference" oblig="true" />
<item name="profil" libelle="Détails du profil"  option="textarea" type="int" length="11" list="false" order="false" translate="reference" oblig="true" />
<item name="contrat"  libelle="Type de contrat"  type="int" length="11" list="true" order="true" fkey="job_contrat" oblig="true" nolink="true" />
<item name="domaine" libelle="Domaine" type="int" default="-1" order="false" fkey="job_domaine" />
<item name="metier"  libelle="Métier"  type="int" length="11" list="true" order="true" fkey="job_metier" nolink="true" />
<item name="lieu" libelle="Lieu" type="int" default="-1" order="false"  fkey="job_lieu" oblig="true" />
<item name="lieuplus" libelle="Lieu détails" type="int" length="11" list="false" order="false" translate="reference" />
<item name="qualification" libelle="Niveau d'études" type="int" length="11" list="false" order="false" translate="reference" />
<item name="experience" libelle="Expérience" type="int" length="11" list="false" order="false" fkey="job_experience"  />
<item name="remuneration" libelle="Rémuneration" type="int" length="11" list="false" order="false" translate="reference"  />
<item name="langue" libelle="Langues" type="int" length="11" list="false" order="false" translate="reference"  />
<item name="date_debut" libelle="Date début de mission" type="varchar" length="2" list="false" order="false" option="enum" >
	<option type="value" value="0" libelle="immediate" />
	<option type="value" value="3-" libelle="moins de 3 mois" />
	<option type="value" value="3=" libelle="3 mois" />
	<option type="value" value="3+" libelle="plus de 3 mois" />
</item>
<item name="date_pub_debut" libelle="Date de début de publication" type="datetime" oblig="true" />
<item name="date_pub_fin" libelle="Date de fin de publication" type="datetime" /> 
<item name="commentaire" libelle="Commentaire libre" type="text" list="false" nohtml="true"  option="textarea"/>
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="true" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="true" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>



==========================================*/

class job_offre
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $bo_users;
var $statut;
var $reference;
var $libelle;
var $entreprise;
var $detail;
var $profil;
var $rubrique;
var $contrat;
var $domaine;
var $lieu;
var $lieuplus;
var $qualification;
var $experience;
var $remuneration;
var $langue;
var $date_debut;
var $date_pub_debut;
var $date_pub_fin;
var $commentaire;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_offre\" libelle=\"Offres d'emploi\" prefix=\"job\" display=\"libelle\" abstract=\"lieu\" def_order_field=\"date_pub_debut\" def_order_direction=\"DESC\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" asso=\"job_assooffredestinataire,job_candidature\" />
<item name=\"bo_users\" libelle=\"Administrateur\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"bo_users\" restrict=\"loose\" />
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\">
	<option type=\"value\" value=\"1\" libelle=\"en attente\" />
	<option type=\"value\" value=\"3\" libelle=\"hors-ligne\" />
	<option type=\"value\" value=\"4\" libelle=\"en ligne\" />
	<option type=\"value\" value=\"5\" libelle=\"archivé\" />
</item>
<item name=\"reference\" libelle=\"Référence\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" oblig=\"true\" nohtml=\"true\"/>
<item name=\"libelle\" libelle=\"Nom du poste\"  type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\" oblig=\"true\" nohtml=\"true\" />
<item name=\"entreprise\" libelle=\"Présentation de l'entreprise\"  option=\"textarea\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" translate=\"reference\" />
<item name=\"detail\" libelle=\"Détails de la mission\"  option=\"textarea\" type=\"int\" length=\"11\" list=\"false\" order=\"true\" translate=\"reference\" oblig=\"true\" />
<item name=\"profil\" libelle=\"Détails du profil\"  option=\"textarea\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" translate=\"reference\" oblig=\"true\" />
<item name=\"contrat\"  libelle=\"Type de contrat\"  type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"job_contrat\" oblig=\"true\" nolink=\"true\" />
<item name=\"domaine\" libelle=\"Domaine\" type=\"int\" default=\"-1\" order=\"false\" fkey=\"job_domaine\" />
<item name=\"metier\"  libelle=\"Métier\"  type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"job_metier\" nolink=\"true\" />
<item name=\"lieu\" libelle=\"Lieu\" type=\"int\" default=\"-1\" order=\"false\"  fkey=\"job_lieu\" oblig=\"true\" />
<item name=\"lieuplus\" libelle=\"Lieu détails\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" translate=\"reference\" />
<item name=\"qualification\" libelle=\"Niveau d'études\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" translate=\"reference\" />
<item name=\"experience\" libelle=\"Expérience\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"job_experience\"  />
<item name=\"remuneration\" libelle=\"Rémuneration\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" translate=\"reference\"  />
<item name=\"langue\" libelle=\"Langues\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" translate=\"reference\" />
<item name=\"date_debut\" libelle=\"Date début de mission\" type=\"varchar\" length=\"2\" list=\"false\" order=\"false\" option=\"enum\" >
	<option type=\"value\" value=\"0\" libelle=\"immediate\" />
	<option type=\"value\" value=\"3-\" libelle=\"moins de 3 mois\" />
	<option type=\"value\" value=\"3=\" libelle=\"3 mois\" />
	<option type=\"value\" value=\"3+\" libelle=\"plus de 3 mois\" />
</item>
<item name=\"date_pub_debut\" libelle=\"Date de début de publication\" type=\"datetime\" oblig=\"true\" />
<item name=\"date_pub_fin\" libelle=\"Date de fin de publication\" type=\"datetime\" /> 
<item name=\"commentaire\" libelle=\"Commentaire libre\" type=\"text\" list=\"false\" nohtml=\"true\"  option=\"textarea\"/>
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" list=\"true\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"true\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_offre
(
	job_id			int (11) PRIMARY KEY not null,
	job_bo_users			int (11),
	job_statut			int not null,
	job_reference			varchar (255),
	job_libelle			int (11),
	job_entreprise			int (11),
	job_detail			int (11),
	job_profil			int (11),
	job_contrat			int (11),
	job_domaine			int,
	job_metier			int (11),
	job_lieu				int,
	job_lieuplus			int (11),
	job_qualification			int (11),
	job_experience			int (11),
	job_remuneration			int (11),
	job_langue			int (11),
	job_date_debut			varchar (2),
	job_date_pub_debut			datetime,
	job_date_pub_fin			datetime,
	job_commentaire			text,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function job_offre($id=null)
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
		$this->bo_users = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->reference = "";
		$this->libelle = -1;
		$this->entreprise = -1;
		$this->detail = -1;
		$this->profil = -1;
		$this->contrat = -1;
		$this->domaine = -1;
		$this->metier = -1;
		$this->lieu = -1;
		$this->lieuplus = -1;
		$this->qualification = -1;
		$this->experience = -1;
		$this->remuneration = -1;
		$this->langue = -1;
		$this->date_debut = "";
		$this->date_pub_debut = date('Y-m-d H:i:s');
		$this->date_pub_fin = date('Y-m-d H:i:s', strtotime('+1 years'));
		$this->commentaire = "";
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
	$laListeChamps[]=new dbChamp("Job_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Job_bo_users", "entier", "get_bo_users", "set_bo_users");
	$laListeChamps[]=new dbChamp("Job_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Job_reference", "text", "get_reference", "set_reference");
	$laListeChamps[]=new dbChamp("Job_libelle", "entier", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Job_entreprise", "entier", "get_entreprise", "set_entreprise");
	$laListeChamps[]=new dbChamp("Job_detail", "entier", "get_detail", "set_detail");
	$laListeChamps[]=new dbChamp("Job_profil", "entier", "get_profil", "set_profil");
	$laListeChamps[]=new dbChamp("Job_contrat", "entier", "get_contrat", "set_contrat");
	$laListeChamps[]=new dbChamp("Job_domaine", "entier", "get_domaine", "set_domaine");
	$laListeChamps[]=new dbChamp("Job_metier", "entier", "get_metier", "set_metier");
	$laListeChamps[]=new dbChamp("Job_lieu", "entier", "get_lieu", "set_lieu");
	$laListeChamps[]=new dbChamp("Job_lieuplus", "entier", "get_lieuplus", "set_lieuplus");
	$laListeChamps[]=new dbChamp("Job_qualification", "entier", "get_qualification", "set_qualification");
	$laListeChamps[]=new dbChamp("Job_experience", "entier", "get_experience", "set_experience");
	$laListeChamps[]=new dbChamp("Job_remuneration", "entier", "get_remuneration", "set_remuneration");
	$laListeChamps[]=new dbChamp("Job_langue", "entier", "get_langue", "set_langue");
	$laListeChamps[]=new dbChamp("Job_date_debut", "text", "get_date_debut", "set_date_debut");
	$laListeChamps[]=new dbChamp("Job_date_pub_debut", "date_formatee_timestamp", "get_date_pub_debut", "set_date_pub_debut");
	$laListeChamps[]=new dbChamp("Job_date_pub_fin", "date_formatee_timestamp", "get_date_pub_fin", "set_date_pub_fin");
	$laListeChamps[]=new dbChamp("Job_commentaire", "text", "get_commentaire", "set_commentaire");
	$laListeChamps[]=new dbChamp("Job_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Job_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_bo_users() { return($this->bo_users); }
function get_statut() { return($this->statut); }
function get_reference() { return($this->reference); }
function get_libelle() { return($this->libelle); }
function get_entreprise() { return($this->entreprise); }
function get_detail() { return($this->detail); }
function get_profil() { return($this->profil); }
function get_contrat() { return($this->contrat); }
function get_domaine() { return($this->domaine); }
function get_metier() { return($this->metier); }
function get_lieu() { return($this->lieu); }
function get_lieuplus() { return($this->lieuplus); }
function get_qualification() { return($this->qualification); }
function get_experience() { return($this->experience); }
function get_remuneration() { return($this->remuneration); }
function get_langue() { return($this->langue); }
function get_date_debut() { return($this->date_debut); }
function get_date_pub_debut() { return($this->date_pub_debut); }
function get_date_pub_fin() { return($this->date_pub_fin); }
function get_commentaire() { return($this->commentaire); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_bo_users($c_job_bo_users) { return($this->bo_users=$c_job_bo_users); }
function set_statut($c_job_statut) { return($this->statut=$c_job_statut); }
function set_reference($c_job_reference) { return($this->reference=$c_job_reference); }
function set_libelle($c_job_libelle) { return($this->libelle=$c_job_libelle); }
function set_entreprise($c_job_entreprise) { return($this->entreprise=$c_job_entreprise); }
function set_detail($c_job_detail) { return($this->detail=$c_job_detail); }
function set_profil($c_job_profil) { return($this->profil=$c_job_profil); }
function set_contrat($c_job_contrat) { return($this->contrat=$c_job_contrat); }
function set_domaine($c_job_domaine) { return($this->domaine=$c_job_domaine); }
function set_metier($c_job_metier) { return($this->metier=$c_job_metier); }
function set_lieu($c_job_lieu) { return($this->lieu=$c_job_lieu); }
function set_lieuplus($c_job_lieuplus) { return($this->lieuplus=$c_job_lieuplus); }
function set_qualification($c_job_qualification) { return($this->qualification=$c_job_qualification); }
function set_experience($c_job_experience) { return($this->experience=$c_job_experience); }
function set_remuneration($c_job_remuneration) { return($this->remuneration=$c_job_remuneration); }
function set_langue($c_job_langue) { return($this->langue=$c_job_langue); }
function set_date_debut($c_job_date_debut) { return($this->date_debut=$c_job_date_debut); }
function set_date_pub_debut($c_job_date_pub_debut) { return($this->date_pub_debut=$c_job_date_pub_debut); }
function set_date_pub_fin($c_job_date_pub_fin) { return($this->date_pub_fin=$c_job_date_pub_fin); }
function set_commentaire($c_job_commentaire) { return($this->commentaire=$c_job_commentaire); }
function set_cdate($c_job_cdate) { return($this->cdate=$c_job_cdate); }
function set_mdate($c_job_mdate) { return($this->mdate=$c_job_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("job_statut"); }
//
function getTable() { return("job_offre"); }
function getClasse() { return("job_offre"); }
function getPrefix() { return("job"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("lieu"); }


} //class


 
}
?>
