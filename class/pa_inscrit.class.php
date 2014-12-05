<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD pa_inscrit :: class pa_inscrit

SQL mySQL:

DROP TABLE IF EXISTS pa_inscrit;
CREATE TABLE pa_inscrit
(
	pa_id			int (11) PRIMARY KEY not null,
	pa_prenom			varchar (255),
	pa_nom			varchar (255),
	pa_mail			varchar (255),
	pa_telephone			varchar (12),
	pa_adresse			varchar (255),
	pa_code_postal			varchar (5),
	pa_ville			varchar (255),
	pa_identifiant			varchar (255),
	pa_password			varchar (255),
	pa_est_valide			int (2),
	pa_est_supprime			int (2),
	pa_suppression			date,
	pa_inscription			date,
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			int (11) not null
)

SQL Oracle:

DROP TABLE pa_inscrit
CREATE TABLE pa_inscrit
(
	pa_id			number (11) constraint pa_pk PRIMARY KEY not null,
	pa_prenom			varchar2 (255),
	pa_nom			varchar2 (255),
	pa_mail			varchar2 (255),
	pa_telephone			varchar2 (12),
	pa_adresse			varchar2 (255),
	pa_code_postal			varchar2 (5),
	pa_ville			varchar2 (255),
	pa_identifiant			varchar2 (255),
	pa_password			varchar2 (255),
	pa_est_valide			number (2),
	pa_est_supprime			number (2),
	pa_suppression			date,
	pa_inscription			date,
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class	name 	= "pa_inscrit"
		libelle	= "Inscrit"
		display = "prenom"
		abstract= "nom"
    prefix="pa"
		>
		
<item	name		= "id"
		type		= "int"
		length		= "11"
		isprimary	= "true"
		notnull		= "true"
		default		= "-1"
		list		= "true"
		order		= "true" />

<!-- ============== -->		
<!--     DONNEES	    -->
<!-- ============== -->
    
 <item	name	= "prenom"
		libelle	= "Prénom"
		type	= "varchar"
		length	= "255"
		list	= "true"
    oblig="true"
		order	= "true" />
 
 <item	name	= "nom"
		libelle	= "Nom"
		type	= "varchar"
		length	= "255"
		list	= "true"
    oblig="true"    
		order	= "true" />
    
   <item	name	= "mail"
		libelle	= "e-Mail"
		type	= "varchar"
		length	= "255"
		list	= "true"
    oblig="true"    
		order	= "true" /> 
    
 <item	name	= "telephone"
		libelle	= "Téléphone"
		type	= "varchar"
		length	= "12"
		list	= "false"
    oblig="true"    
		order	= "true" />    
    
 <item	name	= "adresse"
		libelle	= "Adresse"
		type	= "varchar"
		length	= "255"
		list	= "false"
    oblig="true"
		order	= "true" /> 
    
  <item	name	= "code_postal"
		libelle	= "Code Postal"
		type	= "varchar"
		length	= "5"
		list	= "false"
    oblig="true"
		order	= "true" />    

 <item	name	= "ville"
		libelle	= "Ville"
		type	= "varchar"
		length	= "255"
		list	= "true"
    oblig="true"
		order	= "true" /> 
 

    
 <item	name	= "identifiant"
		libelle	= "identifiant"
		type	= "varchar"
		length	= "255"
		list	= "false"
		order	= "true" />

 <item	name	= "password"
		libelle	= "Mot de passe"
		type	= "varchar"
		length	= "255"
		list	= "false"
		order	= "true" />        

<item	name	= "est_valide"
		libelle	= "Valide ?"
		type	= "int"
		length	= "2"
		list	= "true"
		order	= "true"
		option	= "bool" />


<item	name	= "est_supprime"
		libelle	= "Supprimé ?"
		type	= "int"
		length	= "2"
		list	= "true"
		order	= "true"
		option	= "bool" />
    
<item	name	= "suppression"
		libelle	= "Suppression"
		type	= "date"
		list	= "false"
		order	= "false"
		oblig	= "false"		
		format	= "l j F Y" />
    
<item	name	= "inscription"
		libelle	= "Inscription"
		type	= "date"
		list	= "false"
		order	= "false"
		oblig	= "false"		
		format	= "l j F Y" />    

<!-- ============== -->		
<!-- ADMINISTRATION	-->
<!-- ============== -->
<item	name	= "dtcrea"
		libelle	= "Date de création"
		type	= "date"
		list	= "true"
		order	= "true" />
		
<item	name	= "dtmod"
		libelle	= "Date de modification"
		type	= "date"
		list	= "true"
		order	= "true" />
		
<item	name	= "statut"
		libelle	= "Statut"
		type	= "int"
		length	= "11"
		notnull	= "true"
		default	= "DEF_CODE_STATUT_DEFAUT"
		list	= "true"
		order	= "true" />

</class> 


==========================================*/

class pa_inscrit
{
var $id;
var $prenom;
var $nom;
var $mail;
var $telephone;
var $adresse;
var $code_postal;
var $ville;
var $identifiant;
var $password;
var $est_valide;
var $est_supprime;
var $suppression;
var $inscription;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class	name 	= \"pa_inscrit\"
		libelle	= \"Inscrit\"
		display = \"prenom\"
		abstract= \"nom\"
    prefix=\"pa\"
		>
		
<item	name		= \"id\"
		type		= \"int\"
		length		= \"11\"
		isprimary	= \"true\"
		notnull		= \"true\"
		default		= \"-1\"
		list		= \"true\"
		order		= \"true\" />

<!-- ============== -->		
<!--     DONNEES	    -->
<!-- ============== -->
    
 <item	name	= \"prenom\"
		libelle	= \"Prénom\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
    oblig=\"true\"
		order	= \"true\" />
 
 <item	name	= \"nom\"
		libelle	= \"Nom\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
    oblig=\"true\"    
		order	= \"true\" />
    
   <item	name	= \"mail\"
		libelle	= \"e-Mail\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
    oblig=\"true\"    
		order	= \"true\" /> 
    
 <item	name	= \"telephone\"
		libelle	= \"Téléphone\"
		type	= \"varchar\"
		length	= \"12\"
		list	= \"false\"
    oblig=\"true\"    
		order	= \"true\" />    
    
 <item	name	= \"adresse\"
		libelle	= \"Adresse\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"false\"
    oblig=\"true\"
		order	= \"true\" /> 
    
  <item	name	= \"code_postal\"
		libelle	= \"Code Postal\"
		type	= \"varchar\"
		length	= \"5\"
		list	= \"false\"
    oblig=\"true\"
		order	= \"true\" />    

 <item	name	= \"ville\"
		libelle	= \"Ville\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
    oblig=\"true\"
		order	= \"true\" /> 
 

    
 <item	name	= \"identifiant\"
		libelle	= \"identifiant\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"false\"
		order	= \"true\" />

 <item	name	= \"password\"
		libelle	= \"Mot de passe\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"false\"
		order	= \"true\" />        

<item	name	= \"est_valide\"
		libelle	= \"Valide ?\"
		type	= \"int\"
		length	= \"2\"
		list	= \"true\"
		order	= \"true\"
		option	= \"bool\" />


<item	name	= \"est_supprime\"
		libelle	= \"Supprimé ?\"
		type	= \"int\"
		length	= \"2\"
		list	= \"true\"
		order	= \"true\"
		option	= \"bool\" />
    
<item	name	= \"suppression\"
		libelle	= \"Suppression\"
		type	= \"date\"
		list	= \"false\"
		order	= \"false\"
		oblig	= \"false\"		
		format	= \"l j F Y\" />
    
<item	name	= \"inscription\"
		libelle	= \"Inscription\"
		type	= \"date\"
		list	= \"false\"
		order	= \"false\"
		oblig	= \"false\"		
		format	= \"l j F Y\" />    

<!-- ============== -->		
<!-- ADMINISTRATION	-->
<!-- ============== -->
<item	name	= \"dtcrea\"
		libelle	= \"Date de création\"
		type	= \"date\"
		list	= \"true\"
		order	= \"true\" />
		
<item	name	= \"dtmod\"
		libelle	= \"Date de modification\"
		type	= \"date\"
		list	= \"true\"
		order	= \"true\" />
		
<item	name	= \"statut\"
		libelle	= \"Statut\"
		type	= \"int\"
		length	= \"11\"
		notnull	= \"true\"
		default	= \"DEF_CODE_STATUT_DEFAUT\"
		list	= \"true\"
		order	= \"true\" />

</class> ";

var $sMySql = "CREATE TABLE pa_inscrit
(
	pa_id			int (11) PRIMARY KEY not null,
	pa_prenom			varchar (255),
	pa_nom			varchar (255),
	pa_mail			varchar (255),
	pa_telephone			varchar (12),
	pa_adresse			varchar (255),
	pa_code_postal			varchar (5),
	pa_ville			varchar (255),
	pa_identifiant			varchar (255),
	pa_password			varchar (255),
	pa_est_valide			int (2),
	pa_est_supprime			int (2),
	pa_suppression			date,
	pa_inscription			date,
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			int (11) not null
)

";

// constructeur
function pa_inscrit($id=null)
{
	if (istable("pa_inscrit") == false){
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
		$this->prenom = "";
		$this->nom = "";
		$this->mail = "";
		$this->telephone = "";
		$this->adresse = "";
		$this->code_postal = "";
		$this->ville = "";
		$this->identifiant = "";
		$this->password = "";
		$this->est_valide = -1;
		$this->est_supprime = -1;
		$this->suppression = date("d/m/Y");
		$this->inscription = date("d/m/Y");
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Pa_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Pa_prenom", "text", "get_prenom", "set_prenom");
	$laListeChamps[]=new dbChamp("Pa_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Pa_mail", "text", "get_mail", "set_mail");
	$laListeChamps[]=new dbChamp("Pa_telephone", "text", "get_telephone", "set_telephone");
	$laListeChamps[]=new dbChamp("Pa_adresse", "text", "get_adresse", "set_adresse");
	$laListeChamps[]=new dbChamp("Pa_code_postal", "text", "get_code_postal", "set_code_postal");
	$laListeChamps[]=new dbChamp("Pa_ville", "text", "get_ville", "set_ville");
	$laListeChamps[]=new dbChamp("Pa_identifiant", "text", "get_identifiant", "set_identifiant");
	$laListeChamps[]=new dbChamp("Pa_password", "text", "get_password", "set_password");
	$laListeChamps[]=new dbChamp("Pa_est_valide", "entier", "get_est_valide", "set_est_valide");
	$laListeChamps[]=new dbChamp("Pa_est_supprime", "entier", "get_est_supprime", "set_est_supprime");
	$laListeChamps[]=new dbChamp("Pa_suppression", "date_formatee", "get_suppression", "set_suppression");
	$laListeChamps[]=new dbChamp("Pa_inscription", "date_formatee", "get_inscription", "set_inscription");
	$laListeChamps[]=new dbChamp("Pa_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Pa_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Pa_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_prenom() { return($this->prenom); }
function get_nom() { return($this->nom); }
function get_mail() { return($this->mail); }
function get_telephone() { return($this->telephone); }
function get_adresse() { return($this->adresse); }
function get_code_postal() { return($this->code_postal); }
function get_ville() { return($this->ville); }
function get_identifiant() { return($this->identifiant); }
function get_password() { return($this->password); }
function get_est_valide() { return($this->est_valide); }
function get_est_supprime() { return($this->est_supprime); }
function get_suppression() { return($this->suppression); }
function get_inscription() { return($this->inscription); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_pa_id) { return($this->id=$c_pa_id); }
function set_prenom($c_pa_prenom) { return($this->prenom=$c_pa_prenom); }
function set_nom($c_pa_nom) { return($this->nom=$c_pa_nom); }
function set_mail($c_pa_mail) { return($this->mail=$c_pa_mail); }
function set_telephone($c_pa_telephone) { return($this->telephone=$c_pa_telephone); }
function set_adresse($c_pa_adresse) { return($this->adresse=$c_pa_adresse); }
function set_code_postal($c_pa_code_postal) { return($this->code_postal=$c_pa_code_postal); }
function set_ville($c_pa_ville) { return($this->ville=$c_pa_ville); }
function set_identifiant($c_pa_identifiant) { return($this->identifiant=$c_pa_identifiant); }
function set_password($c_pa_password) { return($this->password=$c_pa_password); }
function set_est_valide($c_pa_est_valide) { return($this->est_valide=$c_pa_est_valide); }
function set_est_supprime($c_pa_est_supprime) { return($this->est_supprime=$c_pa_est_supprime); }
function set_suppression($c_pa_suppression) { return($this->suppression=$c_pa_suppression); }
function set_inscription($c_pa_inscription) { return($this->inscription=$c_pa_inscription); }
function set_dtcrea($c_pa_dtcrea) { return($this->dtcrea=$c_pa_dtcrea); }
function set_dtmod($c_pa_dtmod) { return($this->dtmod=$c_pa_dtmod); }
function set_statut($c_pa_statut) { return($this->statut=$c_pa_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("pa_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("pa_statut"); }
//
function getTable() { return("pa_inscrit"); }
function getClasse() { return("pa_inscrit"); }
function getDisplay() { return("prenom"); }
function getAbstract() { return("nom"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit/list_pa_inscrit.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit/maj_pa_inscrit.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit/show_pa_inscrit.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit/rss_pa_inscrit.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit/xml_pa_inscrit.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit/export_pa_inscrit.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_inscrit/import_pa_inscrit.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>