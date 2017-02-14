<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_candidature.class.php')  && (strpos(__FILE__,'/include/bo/class/job_candidature.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_candidature.class.php');
}else{
/*======================================

objet de BDD job_candidature :: class job_candidature

SQL mySQL:

DROP TABLE IF EXISTS job_candidature;
CREATE TABLE job_candidature
(
	job_id			int (11) PRIMARY KEY not null,
	job_bo_users			int (11),
	job_offre			int (11) not null,
	job_contrat			int (11) not null,
	job_candidat			int (11) not null,
	job_reference			varchar (100),
	job_details			text,
	job_motivation			text,
	job_date_dispo_debut			varchar (255),
	job_date_dispo_fin			varchar (255),
	job_fichier_motivation			varchar (255),
	job_statut			int (11) not null,
	job_commentaire			text,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE job_candidature
CREATE TABLE job_candidature
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_bo_users			number (11),
	job_offre			number (11) not null,
	job_contrat			number (11) not null,
	job_candidat			number (11) not null,
	job_reference			varchar2 (100),
	job_details			text,
	job_motivation			text,
	job_date_dispo_debut			varchar2 (255),
	job_date_dispo_fin			varchar2 (255),
	job_fichier_motivation			varchar2 (255),
	job_statut			number (11) not null,
	job_commentaire			text,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_candidature" libelle="Candidatures" prefix="job" display="candidat" abstract="offre" def_order_field="cdate" def_order_direction="DESC" log_status_change="true" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false"/>
<item name="bo_users" libelle="Administrateur" type="int" length="11" notnull="true" list="false" order="false" fkey="bo_users" restrict="loose"/>
<item name="offre" libelle="Offre" type="int" length="11" default="0" notnull="true" order="true" list="true" fkey="job_offre""  />
<item name="contrat"  libelle="Type de contrat"  type="int" length="11" notnull="true" list="false" order="false" fkey="job_contrat" noedit="true" nosearch="true" oblig="true" />
<item name="candidat" libelle="Candidat" type="int" length="11" notnull="true" list="true" order="true" fkey="shp_client" noedit="true" nosearch="true" />
<item name="reference" libelle="Référence" type="varchar" length="100" list="true" order="true" noedit="true" />
<item name="details" libelle="Détails" type="text" list="false" nohtml="true" option="textarea" noedit="true" />
<item name="motivation" libelle="Motivation" type="text" list="false" nohtml="true"  option="textarea" />
<item name="date_dispo_debut"  libelle="Date de disponibilité" type="varchar" length="255" list="false" order="false" nohtml="true"  />
<item name="date_dispo_fin" libelle="Date de fin de disponibilité" type="varchar" length="255" list="false" order="false" nohtml="true"  />
<item name="fichier_motivation" libelle="Lettre de motivation" type="varchar" length="255" default="0" list="false" order="false" option="file" dir="/custom/upload/job_candidature/" oblig="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" >
	<option type="value" value="1" libelle="en attente" />
	<option type="value" value="2" libelle="transférée pour avis à l'opérationnel" />
	<option type="value" value="3" libelle="écartée - réponse négative" />
	<option type="value" value="4" libelle="écartée - vivier" />
	<option type="value" value="5" libelle="entretien proposé" />
	<option type="value" value="6" libelle="écartée suite à entretien" />
	<option type="value" value="7" libelle="désistement" />
	<option type="value" value="8" libelle="proposition faite" />
	<option type="value" value="9" libelle="candidature retenue" />
</item>
<item name="commentaire" libelle="Commentaire" type="text"   list="false" order="true"   nohtml="true"   option="textarea"/>
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="true" order="true" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class job_candidature
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $bo_users;
var $offre;
var $contrat;
var $candidat;
var $reference;
var $details;
var $motivation;
var $date_dispo_debut;
var $date_dispo_fin;
var $fichier_motivation;
var $statut;
var $commentaire;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_candidature\" libelle=\"Candidatures\" prefix=\"job\" display=\"candidat\" abstract=\"offre\" def_order_field=\"cdate\" def_order_direction=\"DESC\" log_status_change=\"true\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\"/>
<item name=\"bo_users\" libelle=\"Administrateur\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"bo_users\" restrict=\"loose\" />
<item name=\"offre\" libelle=\"Offre\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" order=\"true\" list=\"true\" fkey=\"job_offre\" noedit=\"true\" />
<item name=\"contrat\"  libelle=\"Type de contrat\"  type=\"int\" notnull=\"true\" length=\"11\" list=\"false\" order=\"false\" fkey=\"job_contrat\" noedit=\"true\" oblig=\"true\" />
<item name=\"candidat\" libelle=\"Candidat\" type=\"int\" length=\"11\" notnull=\"true\" list=\"true\" order=\"true\" fkey=\"shp_client\" noedit=\"true\" nosearch=\"true\" />
<item name=\"reference\" libelle=\"Référence\" type=\"varchar\" length=\"100\" list=\"true\" order=\"true\" noedit=\"true\" />
<item name=\"details\" libelle=\"Détails\" type=\"text\" list=\"false\" nohtml=\"true\"  option=\"textarea\" noedit=\"true\" />
<item name=\"motivation\" libelle=\"Motivation\" type=\"text\" list=\"false\" nohtml=\"true\"  option=\"textarea\" />
<item name=\"date_dispo_debut\"  libelle=\"Date de disponibilité\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"  />
<item name=\"date_dispo_fin\" libelle=\"Date de fin de disponibilité\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"  />
<item name=\"fichier_motivation\" libelle=\"Lettre de motivation\" type=\"varchar\" length=\"255\" default=\"0\" list=\"false\" order=\"false\" option=\"file\" dir=\"/custom/upload/job_candidature/\" oblig=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" >
	<option type=\"value\" value=\"1\" libelle=\"en attente\" />
	<option type=\"value\" value=\"2\" libelle=\"transférée pour avis à l'opérationnel\" />
	<option type=\"value\" value=\"3\" libelle=\"écartée - réponse négative\" />
	<option type=\"value\" value=\"4\" libelle=\"écartée - vivier\" />
	<option type=\"value\" value=\"5\" libelle=\"entretien proposé\" />
	<option type=\"value\" value=\"6\" libelle=\"écartée suite à entretien\" />
	<option type=\"value\" value=\"7\" libelle=\"désistement\" />
	<option type=\"value\" value=\"8\" libelle=\"proposition faite\" />
	<option type=\"value\" value=\"9\" libelle=\"candidature retenue\" />
</item>
<item name=\"commentaire\" libelle=\"Commentaire\" type=\"text\" list=\"false\" order=\"true\"   nohtml=\"true\"   option=\"textarea\"/>
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" list=\"true\" order=\"true\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_candidature
(
	job_id			int (11) PRIMARY KEY not null,
	job_bo_users			int (11),
	job_offre			int (11) not null,
	job_contrat			int (11) not null,
	job_candidat			int (11) not null,
	job_reference			varchar (100),
	job_details			text,
	job_motivation			text,
	job_date_dispo_debut			varchar (255),
	job_date_dispo_fin			varchar (255),
	job_fichier_motivation			varchar (255),
	job_statut			int (11) not null,
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
		$this->bo_users = -1;
		$this->offre = -1;
		$this->contrat = -1;
		$this->candidat = -1;
		$this->reference = "";
		$this->details = "";
		$this->motivation = "";
		$this->date_dispo_debut = "";
		$this->date_dispo_fin = "";
		$this->fichier_motivation = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
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
	$laListeChamps[]=new dbChamp("Job_offre", "entier", "get_offre", "set_offre");
	$laListeChamps[]=new dbChamp("Job_contrat", "entier", "get_contrat", "set_contrat");
	$laListeChamps[]=new dbChamp("Job_candidat", "entier", "get_candidat", "set_candidat");
	$laListeChamps[]=new dbChamp("Job_reference", "text", "get_reference", "set_reference");
	$laListeChamps[]=new dbChamp("Job_details", "text", "get_details", "set_details");
	$laListeChamps[]=new dbChamp("Job_motivation", "text", "get_motivation", "set_motivation");
	$laListeChamps[]=new dbChamp("Job_date_dispo_debut", "text", "get_date_dispo_debut", "set_date_dispo_debut");
	$laListeChamps[]=new dbChamp("Job_date_dispo_fin", "text", "get_date_dispo_fin", "set_date_dispo_fin");
	$laListeChamps[]=new dbChamp("Job_fichier_motivation", "text", "get_fichier_motivation", "set_fichier_motivation");
	$laListeChamps[]=new dbChamp("Job_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Job_commentaire", "text", "get_commentaire", "set_commentaire");
	$laListeChamps[]=new dbChamp("Job_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Job_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_bo_users() { return($this->bo_users); }
function get_offre() { return($this->offre); }
function get_contrat() { return($this->contrat); }
function get_candidat() { return($this->candidat); }
function get_reference() { return($this->reference); }
function get_details() { return($this->details); }
function get_motivation() { return($this->motivation); }
function get_date_dispo_debut() { return($this->date_dispo_debut); }
function get_date_dispo_fin() { return($this->date_dispo_fin); }
function get_fichier_motivation() { return($this->fichier_motivation); }
function get_statut() { return($this->statut); }
function get_commentaire() { return($this->commentaire); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_bo_users($c_job_bo_users) { return($this->bo_users=$c_job_bo_users); }
function set_offre($c_job_offre) { return($this->offre=$c_job_offre); }
function set_contrat($c_job_contrat) { return($this->contrat=$c_job_contrat); }
function set_candidat($c_job_candidat) { return($this->candidat=$c_job_candidat); }
function set_reference($c_job_reference) { return($this->reference=$c_job_reference); }
function set_details($c_job_details) { return($this->details=$c_job_details); }
function set_motivation($c_job_motivation) { return($this->motivation=$c_job_motivation); }
function set_date_dispo_debut($c_job_date_dispo_debut) { return($this->date_dispo_debut=$c_job_date_dispo_debut); }
function set_date_dispo_fin($c_job_date_dispo_fin) { return($this->date_dispo_fin=$c_job_date_dispo_fin); }
function set_fichier_motivation($c_job_fichier_motivation) { return($this->fichier_motivation=$c_job_fichier_motivation); }
function set_statut($c_job_statut) { return($this->statut=$c_job_statut); }
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
function getTable() { return("job_candidature"); }
function getClasse() { return("job_candidature"); }
function getPrefix() { return("job"); }
function getDisplay() { return("candidat"); }
function getAbstract() { return("offre"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature/list_job_candidature.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature/maj_job_candidature.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature/show_job_candidature.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature/rss_job_candidature.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature/xml_job_candidature.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature/xlsx_job_candidature.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature/export_job_candidature.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_candidature/import_job_candidature.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>