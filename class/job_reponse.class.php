<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_reponse.class.php')  && (strpos(__FILE__,'/include/bo/class/job_reponse.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/job_reponse.class.php');
}else{
/*======================================

objet de BDD job_reponse :: class job_reponse

SQL mySQL:

DROP TABLE IF EXISTS job_reponse;
CREATE TABLE job_reponse
(
	job_id			int (11) PRIMARY KEY not null,
	job_candidature			int (11) not null,
	job_candidat			int (11) not null,
	job_lettre			int (11) not null,
	job_offre			int (11) not null,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE job_reponse
CREATE TABLE job_reponse
(
	job_id			number (11) constraint job_pk PRIMARY KEY not null,
	job_candidature			number (11) not null,
	job_candidat			number (11) not null,
	job_lettre			number (11) not null,
	job_offre			number (11) not null,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="job_reponse" libelle="Réponses à candidature" prefix="job" display="candidature" abstract="lettre" def_order_field="cdate" def_order_direction="DESC">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" />
<item name="candidature" libelle="candidature" type="int" length="11"  notnull="true" default="-1" list="true" order="false" fkey="job_candidature" noedit="true" nosearch="true" />
<item name="candidat" libelle="Candidat" type="int" length="11" notnull="true" list="true" order="true" fkey="shp_client" noedit="true" nosearch="true" />
<item name="lettre" libelle="Type de réponse"  type="int" length="11"  notnull="true" default="-1" list="true" order="true" fkey="job_lettre" noedit="true" />
<item name="offre" libelle="offre"  type="int" length="11"  notnull="true" default="-1" list="true" order="false" fkey="job_offre" noedit="true" nosearch="true" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="true" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>


==========================================*/

class job_reponse
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $candidature;
var $candidat;
var $lettre;
var $offre;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"job_reponse\" libelle=\"Réponses à candidature\" prefix=\"job\" display=\"candidature\" abstract=\"lettre\" def_order_field=\"cdate\" def_order_direction=\"DESC\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" />
<item name=\"candidature\" libelle=\"candidature\" type=\"int\" length=\"11\"  notnull=\"true\" default=\"-1\" list=\"true\" order=\"false\" fkey=\"job_candidature\" noedit=\"true\" nosearch=\"true\" />
<item name=\"candidat\" libelle=\"Candidat\" type=\"int\" length=\"11\" notnull=\"true\" list=\"true\" order=\"true\" fkey=\"shp_client\" noedit=\"true\" nosearch=\"true\" />
<item name=\"lettre\" libelle=\"Type de réponse\"  type=\"int\" length=\"11\"  notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"job_lettre\" noedit=\"true\" />
<item name=\"offre\" libelle=\"offre\"  type=\"int\" length=\"11\"  notnull=\"true\" default=\"-1\" list=\"true\" order=\"false\" fkey=\"job_offre\" noedit=\"true\" nosearch=\"true\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"true\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE job_reponse
(
	job_id			int (11) PRIMARY KEY not null,
	job_candidature			int (11) not null,
	job_candidat			int (11) not null,
	job_lettre			int (11) not null,
	job_offre			int (11) not null,
	job_cdate			datetime not null,
	job_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

";

// constructeur
function job_reponse($id=null)
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
		$this->candidature = -1;
		$this->candidat = -1;
		$this->lettre = -1;
		$this->offre = -1;
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
	$laListeChamps[]=new dbChamp("Job_candidature", "entier", "get_candidature", "set_candidature");
	$laListeChamps[]=new dbChamp("Job_candidat", "entier", "get_candidat", "set_candidat");
	$laListeChamps[]=new dbChamp("Job_lettre", "entier", "get_lettre", "set_lettre");
	$laListeChamps[]=new dbChamp("Job_offre", "entier", "get_offre", "set_offre");
	$laListeChamps[]=new dbChamp("Job_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Job_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_candidature() { return($this->candidature); }
function get_candidat() { return($this->candidat); }
function get_lettre() { return($this->lettre); }
function get_offre() { return($this->offre); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_job_id) { return($this->id=$c_job_id); }
function set_candidature($c_job_candidature) { return($this->candidature=$c_job_candidature); }
function set_candidat($c_job_candidat) { return($this->candidat=$c_job_candidat); }
function set_lettre($c_job_lettre) { return($this->lettre=$c_job_lettre); }
function set_offre($c_job_offre) { return($this->offre=$c_job_offre); }
function set_cdate($c_job_cdate) { return($this->cdate=$c_job_cdate); }
function set_mdate($c_job_mdate) { return($this->mdate=$c_job_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("job_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("job_reponse"); }
function getClasse() { return("job_reponse"); }
function getPrefix() { return(""); }
function getDisplay() { return("candidature"); }
function getAbstract() { return("lettre"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse/list_job_reponse.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse/maj_job_reponse.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse/show_job_reponse.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse/rss_job_reponse.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse/xml_job_reponse.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse/xlsx_job_reponse.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse/export_job_reponse.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/job_reponse/import_job_reponse.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>