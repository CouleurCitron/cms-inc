<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_candidat.class.php')  && (strpos(__FILE__,'/include/bo/class/job_candidat.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_candidat.class.php');
}else{
/*======================================

objet de BDD job_candidat :: class job_candidat

SQL mySQL:

DROP TABLE IF EXISTS job_candidat;
CREATE TABLE job_candidat
(
	job_id			int (11) PRIMARY KEY not null,
	job_anonyme			enum ('Y','N') not null default 'N',
	job_situation			varchar (255),
	job_nationalite			varchar (255),
	job_adresse_1			varchar (256) not null,
	job_adresse_2			varchar (256),
	job_adresse_3			varchar (256),
	job_ville			varchar (256) not null,
	job_cp			varchar (64) not null,
	job_pays			int (3) not null,
	job_statut			int not null,
	job_contrat			int (11) not null,
	job_experience			int (11) not null,
	job_salaire			varchar (128) not null,
	job_parcours			text,
	job_competences			text,
	job_interets			text,
	job_fichier_cv			varchar (255),
	job_commentaire			text,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE job_candidat
CREATE TABLE job_candidat
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_anonyme			enum ('Y','N') not null default 'N',
	job_situation			varchar2 (255),
	job_nationalite			varchar2 (255),
	job_adresse_1			varchar2 (256) not null,
	job_adresse_2			varchar2 (256),
	job_adresse_3			varchar2 (256),
	job_ville			varchar2 (256) not null,
	job_cp			varchar2 (64) not null,
	job_pays			number (3) not null,
	job_statut			number not null,
	job_contrat			number (11) not null,
	job_experience			number (11) not null,
	job_salaire			varchar2 (128) not null,
	job_parcours			text,
	job_competences			text,
	job_interets			text,
	job_fichier_cv			varchar2 (255),
	job_commentaire			text,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_candidat" libelle="Liste des candidats" prefix="job" display="nom" abstract="prenom" def_order_field="nom" def_order_direction="DESC" inherits_from="shp_client" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso_view="job_assocandidatlangue,job_assocandidatqualification,job_candidature"/>
<item name="anonyme" libelle="Anonyme" type="enum" length="'Y','N'" notnull="true" default="N"  noedit="true" skip="true" />
<item name="situation" libelle="Situation familiale"  type="varchar" length="255" list="false" order="true" nohtml="true" inherit_position="commentaires" />
<item name="nationalite" libelle="Nationalité"  type="varchar" length="255" list="false" order="true"  oblig="true" nohtml="true"  />
<item name="adresse_1" libelle="Adresse 1" type="varchar" length="256" notnull="true" default="" nohtml="true" />
<item name="adresse_2" libelle="Adresse 2" type="varchar" length="256" default="" nohtml="true" />
<item name="adresse_3" libelle="Adresse 3" type="varchar" length="256" default="" nohtml="true" />
<item name="ville" libelle="Ville" type="varchar" length="256" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="cp" libelle="Code postal" type="varchar" length="64" notnull="true" default="" list="true" order="true" nohtml="true" />
<item name="pays" libelle="Pays" type="int" length="3" fkey="cms_pays" notnull="true" default="0" list="true" order="true" noedit="true" /> 
<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="contrat" libelle="Type de contrat recherché" type="int" length="11" fkey="job_contrat" notnull="true" default="0" list="false" order="false" noedit="true" />
<item name="experience" libelle="Nombre d'années d'expérience" type="int" length="11" fkey="job_experience" notnull="true" default="0" list="false" order="false" noedit="true" />
<item name="salaire" libelle="Salaire" type="varchar" length="128" notnull="true" default="" nohtml="true" noedit="true" />
<item name="parcours" libelle="Parcours professionnel" type="text" list="false" order="true" nohtml="true" option="textarea" noedit="true"  />
<item name="competences" libelle="Compétences" type="text" list="false" order="false" nohtml="true" option="textarea" noedit="true" />
<item name="interets" libelle="Centre d'intérêts" type="text" list="false" order="false" nohtml="true"  option="textarea" noedit="true" />
<item name="fichier_cv" libelle="CV" type="varchar" length="255" default="0" order="false" option="file" dir="/custom/upload/job_candidature/" oblig="true" />
<item name="commentaire" libelle="Commentaire libre" type="text" list="false" nohtml="true"  option="textarea"/>
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class job_candidat
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $anonyme;
var $situation;
var $nationalite;
var $adresse_1;
var $adresse_2;
var $adresse_3;
var $ville;
var $cp;
var $pays;
var $statut;
var $contrat;
var $experience;
var $salaire;
var $parcours;
var $competences;
var $interets;
var $fichier_cv;
var $commentaire;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_candidat\" libelle=\"Liste des candidats\" prefix=\"job\" display=\"nom\" abstract=\"prenom\" def_order_field=\"nom\" def_order_direction=\"DESC\" inherits_from=\"shp_client\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso_view=\"job_assocandidatlangue,job_assocandidatqualification,job_candidature\"/>
<item name=\"anonyme\" libelle=\"Anonyme\" type=\"enum\" length=\"'Y','N'\" notnull=\"true\" default=\"N\"  noedit=\"true\" skip=\"true\" />
<item name=\"situation\" libelle=\"Situation familiale\"  type=\"varchar\" length=\"255\" list=\"false\" order=\"true\" nohtml=\"true\" inherit_position=\"commentaires\" />
<item name=\"nationalite\" libelle=\"Nationalité\"  type=\"varchar\" length=\"255\" list=\"false\" order=\"true\"  oblig=\"true\" nohtml=\"true\"  />
<item name=\"adresse_1\" libelle=\"Adresse 1\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" nohtml=\"true\" />
<item name=\"adresse_2\" libelle=\"Adresse 2\" type=\"varchar\" length=\"256\" default=\"\" nohtml=\"true\" />
<item name=\"adresse_3\" libelle=\"Adresse 3\" type=\"varchar\" length=\"256\" default=\"\" nohtml=\"true\" />
<item name=\"ville\" libelle=\"Ville\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"cp\" libelle=\"Code postal\" type=\"varchar\" length=\"64\" notnull=\"true\" default=\"\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"pays\" libelle=\"Pays\" type=\"int\" length=\"3\" fkey=\"cms_pays\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" noedit=\"true\" /> 
<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"contrat\" libelle=\"Type de contrat recherché\" type=\"int\" length=\"11\" fkey=\"job_contrat\" notnull=\"true\" default=\"0\" list=\"false\" order=\"false\" noedit=\"true\" />
<item name=\"experience\" libelle=\"Nombre d'années d'expérience\" type=\"int\" length=\"11\" fkey=\"job_experience\" notnull=\"true\" default=\"0\" list=\"false\" order=\"false\" noedit=\"true\" />
<item name=\"salaire\" libelle=\"Salaire\" type=\"varchar\" length=\"128\" notnull=\"true\" default=\"\" nohtml=\"true\" noedit=\"true\" />
<item name=\"parcours\" libelle=\"Parcours professionnel\" type=\"text\" list=\"false\" order=\"true\" nohtml=\"true\" option=\"textarea\" noedit=\"true\"  />
<item name=\"competences\" libelle=\"Compétences\" type=\"text\" list=\"false\" order=\"false\" nohtml=\"true\" option=\"textarea\" noedit=\"true\" />
<item name=\"interets\" libelle=\"Centre d'intérêts\" type=\"text\" list=\"false\" order=\"false\" nohtml=\"true\"  option=\"textarea\" noedit=\"true\" />
<item name=\"fichier_cv\" libelle=\"CV\" type=\"varchar\" length=\"255\" default=\"0\" order=\"false\" option=\"file\" dir=\"/custom/upload/job_candidature/\" oblig=\"true\" />
<item name=\"commentaire\" libelle=\"Commentaire libre\" type=\"text\" list=\"false\" nohtml=\"true\"  option=\"textarea\"/>
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_candidat
(
	job_id			int (11) PRIMARY KEY not null,
	job_anonyme			enum ('Y','N') not null default 'N',
	job_situation			varchar (255),
	job_nationalite			varchar (255),
	job_adresse_1			varchar (256) not null,
	job_adresse_2			varchar (256),
	job_adresse_3			varchar (256),
	job_ville			varchar (256) not null,
	job_cp			varchar (64) not null,
	job_pays			int (3) not null,
	job_statut			int not null,
	job_contrat			int (11) not null,
	job_experience			int (11) not null,
	job_salaire			varchar (128) not null,
	job_parcours			text,
	job_competences			text,
	job_interets			text,
	job_fichier_cv			varchar (255),
	job_commentaire			text,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
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
		$this->anonyme = "N";
		$this->situation = "";
		$this->nationalite = "";
		$this->adresse_1 = "";
		$this->adresse_2 = "";
		$this->adresse_3 = "";
		$this->ville = "";
		$this->cp = "";
		$this->pays = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->contrat = -1;
		$this->experience = -1;
		$this->salaire = "";
		$this->parcours = "";
		$this->competences = "";
		$this->interets = "";
		$this->fichier_cv = "";
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
	$laListeChamps[]=new dbChamp("Job_anonyme", "text", "get_anonyme", "set_anonyme");
	$laListeChamps[]=new dbChamp("Job_situation", "text", "get_situation", "set_situation");
	$laListeChamps[]=new dbChamp("Job_nationalite", "text", "get_nationalite", "set_nationalite");
	$laListeChamps[]=new dbChamp("Job_adresse_1", "text", "get_adresse_1", "set_adresse_1");
	$laListeChamps[]=new dbChamp("Job_adresse_2", "text", "get_adresse_2", "set_adresse_2");
	$laListeChamps[]=new dbChamp("Job_adresse_3", "text", "get_adresse_3", "set_adresse_3");
	$laListeChamps[]=new dbChamp("Job_ville", "text", "get_ville", "set_ville");
	$laListeChamps[]=new dbChamp("Job_cp", "text", "get_cp", "set_cp");
	$laListeChamps[]=new dbChamp("Job_pays", "entier", "get_pays", "set_pays");
	$laListeChamps[]=new dbChamp("Job_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Job_contrat", "entier", "get_contrat", "set_contrat");
	$laListeChamps[]=new dbChamp("Job_experience", "entier", "get_experience", "set_experience");
	$laListeChamps[]=new dbChamp("Job_salaire", "text", "get_salaire", "set_salaire");
	$laListeChamps[]=new dbChamp("Job_parcours", "text", "get_parcours", "set_parcours");
	$laListeChamps[]=new dbChamp("Job_competences", "text", "get_competences", "set_competences");
	$laListeChamps[]=new dbChamp("Job_interets", "text", "get_interets", "set_interets");
	$laListeChamps[]=new dbChamp("Job_fichier_cv", "text", "get_fichier_cv", "set_fichier_cv");
	$laListeChamps[]=new dbChamp("Job_commentaire", "text", "get_commentaire", "set_commentaire");
	$laListeChamps[]=new dbChamp("Job_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Job_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_anonyme() { return($this->anonyme); }
function get_situation() { return($this->situation); }
function get_nationalite() { return($this->nationalite); }
function get_adresse_1() { return($this->adresse_1); }
function get_adresse_2() { return($this->adresse_2); }
function get_adresse_3() { return($this->adresse_3); }
function get_ville() { return($this->ville); }
function get_cp() { return($this->cp); }
function get_pays() { return($this->pays); }
function get_statut() { return($this->statut); }
function get_contrat() { return($this->contrat); }
function get_experience() { return($this->experience); }
function get_salaire() { return($this->salaire); }
function get_parcours() { return($this->parcours); }
function get_competences() { return($this->competences); }
function get_interets() { return($this->interets); }
function get_fichier_cv() { return($this->fichier_cv); }
function get_commentaire() { return($this->commentaire); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_anonyme($c_job_anonyme) { return($this->anonyme=$c_job_anonyme); }
function set_situation($c_job_situation) { return($this->situation=$c_job_situation); }
function set_nationalite($c_job_nationalite) { return($this->nationalite=$c_job_nationalite); }
function set_adresse_1($c_job_adresse_1) { return($this->adresse_1=$c_job_adresse_1); }
function set_adresse_2($c_job_adresse_2) { return($this->adresse_2=$c_job_adresse_2); }
function set_adresse_3($c_job_adresse_3) { return($this->adresse_3=$c_job_adresse_3); }
function set_ville($c_job_ville) { return($this->ville=$c_job_ville); }
function set_cp($c_job_cp) { return($this->cp=$c_job_cp); }
function set_pays($c_job_pays) { return($this->pays=$c_job_pays); }
function set_statut($c_job_statut) { return($this->statut=$c_job_statut); }
function set_contrat($c_job_contrat) { return($this->contrat=$c_job_contrat); }
function set_experience($c_job_experience) { return($this->experience=$c_job_experience); }
function set_salaire($c_job_salaire) { return($this->salaire=$c_job_salaire); }
function set_parcours($c_job_parcours) { return($this->parcours=$c_job_parcours); }
function set_competences($c_job_competences) { return($this->competences=$c_job_competences); }
function set_interets($c_job_interets) { return($this->interets=$c_job_interets); }
function set_fichier_cv($c_job_fichier_cv) { return($this->fichier_cv=$c_job_fichier_cv); }
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
function getTable() { return("job_candidat"); }
function getClasse() { return("job_candidat"); }
function getPrefix() { return("job"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("prenom"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat/list_job_candidat.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat/maj_job_candidat.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat/show_job_candidat.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat/rss_job_candidat.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat/xml_job_candidat.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat/xlsx_job_candidat.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat/export_job_candidat.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidat/import_job_candidat.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>