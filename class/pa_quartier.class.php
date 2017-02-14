<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD pa_quartier :: class pa_quartier

SQL mySQL:

DROP TABLE IF EXISTS pa_quartier;
CREATE TABLE pa_quartier
(
	pa_id			int (11) PRIMARY KEY not null,
	pa_nom			varchar (255),
	pa_est_limitrophe			int (2),
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			int (11) not null
)

SQL Oracle:

DROP TABLE pa_quartier
CREATE TABLE pa_quartier
(
	pa_id			number (11) constraint pa_pk PRIMARY KEY not null,
	pa_nom			varchar2 (255),
	pa_est_limitrophe			number (2),
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class	name 	= "pa_quartier"
		libelle	= "Quartier"
		display = "nom"
		abstract= "id"
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
    
 <item	name	= "nom"
		libelle	= "Nom"
		type	= "varchar"
		length	= "255"
		list	= "true"
    oblig="true"    
		order	= "true" />

 <item	name	= "est_limitrophe"
		libelle	= "Quartier limitrophe ?"
		type	= "int"
		length	= "2"
		list	= "true"
		order	= "true"
		option	= "bool" />   
    
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

class pa_quartier
{
var $id;
var $nom;
var $est_limitrophe;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class	name 	= \"pa_quartier\"
		libelle	= \"Quartier\"
		display = \"nom\"
		abstract= \"id\"
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
    
 <item	name	= \"nom\"
		libelle	= \"Nom\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
    oblig=\"true\"    
		order	= \"true\" />

 <item	name	= \"est_limitrophe\"
		libelle	= \"Quartier limitrophe ?\"
		type	= \"int\"
		length	= \"2\"
		list	= \"true\"
		order	= \"true\"
		option	= \"bool\" />   
    
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

var $sMySql = "CREATE TABLE pa_quartier
(
	pa_id			int (11) PRIMARY KEY not null,
	pa_nom			varchar (255),
	pa_est_limitrophe			int (2),
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("pa_quartier") == false){
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
		$this->nom = "";
		$this->est_limitrophe = -1;
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
	$laListeChamps[]=new dbChamp("Pa_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Pa_est_limitrophe", "entier", "get_est_limitrophe", "set_est_limitrophe");
	$laListeChamps[]=new dbChamp("Pa_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Pa_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Pa_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom() { return($this->nom); }
function get_est_limitrophe() { return($this->est_limitrophe); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_pa_id) { return($this->id=$c_pa_id); }
function set_nom($c_pa_nom) { return($this->nom=$c_pa_nom); }
function set_est_limitrophe($c_pa_est_limitrophe) { return($this->est_limitrophe=$c_pa_est_limitrophe); }
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
function getTable() { return("pa_quartier"); }
function getClasse() { return("pa_quartier"); }
function getDisplay() { return("nom"); }
function getAbstract() { return("id"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier/list_pa_quartier.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier/maj_pa_quartier.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier/show_pa_quartier.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier/rss_pa_quartier.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier/xml_pa_quartier.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier/export_pa_quartier.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_quartier/import_pa_quartier.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>