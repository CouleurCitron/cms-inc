<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shr_track.class.php')  && (strpos(__FILE__,'/include/bo/class/shr_track.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shr_track.class.php');
}else{
/*======================================

objet de BDD shr_track :: class shr_track

SQL mySQL:

DROP TABLE IF EXISTS shr_track;
CREATE TABLE shr_track
(
	shr_id			int (11) PRIMARY KEY not null,
	shr_lastname			varchar (255),
	shr_firstname			varchar (255),
	shr_email			varchar (255),
	shr_tracked			enum ('Y','N') not null default 'N',
	shr_count			int (11),
	shr_created			datetime not null,
	shr_updated			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shr_track
CREATE TABLE shr_track
(
	shr_id			number (11) constraint shr_pk PRIMARY KEY not null,
	shr_lastname			varchar2 (255),
	shr_firstname			varchar2 (255),
	shr_email			varchar2 (255),
	shr_tracked			enum ('Y','N') not null default 'N',
	shr_count			number (11),
	shr_created			datetime not null,
	shr_updated			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shr_track" libelle="Envoi à un ami" prefix="shr" display="lastname" abstract="email" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" /> 
<item name="lastname" libelle="Nom" type="varchar" length="255" list="true" order="true" nohtml="true"/>
<item name="firstname" libelle="Prénom" type="varchar" length="255" list="true" nohtml="true"/>
<item name="email" libelle="Email" type="varchar" length="255" list="true" order="true" nohtml="true"/>
<item name="tracked" libelle="Averti" type="enum" length="'Y','N'" list="true" notnull="true" default="N" />
<item name="count" libelle="Décompte" type="int" length="11" list="true" order="false" nohtml="true"/>
<item name="created" libelle="Date de création" type="datetime" notnull="true" list="true" order="true" /> 
<item name="updated" libelle="Date de mise à jour" type="timestamp" notnull="true"  list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" /> 
</class>



==========================================*/

class shr_track
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $lastname;
var $firstname;
var $email;
var $tracked;
var $count;
var $created;
var $updated;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shr_track\" libelle=\"Envoi à un ami\" prefix=\"shr\" display=\"lastname\" abstract=\"email\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" /> 
<item name=\"lastname\" libelle=\"Nom\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\"/>
<item name=\"firstname\" libelle=\"Prénom\" type=\"varchar\" length=\"255\" list=\"true\" nohtml=\"true\"/>
<item name=\"email\" libelle=\"Email\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\"/>
<item name=\"tracked\" libelle=\"Averti\" type=\"enum\" length=\"'Y','N'\" list=\"true\" notnull=\"true\" default=\"N\" />
<item name=\"count\" libelle=\"Décompte\" type=\"int\" length=\"11\" list=\"true\" order=\"false\" nohtml=\"true\"/>
<item name=\"created\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"true\" order=\"true\" /> 
<item name=\"updated\" libelle=\"Date de mise à jour\" type=\"timestamp\" notnull=\"true\"  list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" /> 
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE shr_track
(
	shr_id			int (11) PRIMARY KEY not null,
	shr_lastname			varchar (255),
	shr_firstname			varchar (255),
	shr_email			varchar (255),
	shr_tracked			enum ('Y','N') not null default 'N',
	shr_count			int (11),
	shr_created			datetime not null,
	shr_updated			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
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
		$this->lastname = "";
		$this->firstname = "";
		$this->email = "";
		$this->tracked = "N";
		$this->count = -1;
		$this->created = date('Y-m-d H:i:s');
		$this->updated = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
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
	$laListeChamps[]=new dbChamp("Shr_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shr_lastname", "text", "get_lastname", "set_lastname");
	$laListeChamps[]=new dbChamp("Shr_firstname", "text", "get_firstname", "set_firstname");
	$laListeChamps[]=new dbChamp("Shr_email", "text", "get_email", "set_email");
	$laListeChamps[]=new dbChamp("Shr_tracked", "text", "get_tracked", "set_tracked");
	$laListeChamps[]=new dbChamp("Shr_count", "entier", "get_count", "set_count");
	$laListeChamps[]=new dbChamp("Shr_created", "date_formatee_timestamp", "get_created", "set_created");
	$laListeChamps[]=new dbChamp("Shr_updated", "date_formatee_timestamp", "get_updated", "set_updated");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_lastname() { return($this->lastname); }
function get_firstname() { return($this->firstname); }
function get_email() { return($this->email); }
function get_tracked() { return($this->tracked); }
function get_count() { return($this->count); }
function get_created() { return($this->created); }
function get_updated() { return($this->updated); }


// setters
function set_id($c_shr_id) { return($this->id=$c_shr_id); }
function set_lastname($c_shr_lastname) { return($this->lastname=$c_shr_lastname); }
function set_firstname($c_shr_firstname) { return($this->firstname=$c_shr_firstname); }
function set_email($c_shr_email) { return($this->email=$c_shr_email); }
function set_tracked($c_shr_tracked) { return($this->tracked=$c_shr_tracked); }
function set_count($c_shr_count) { return($this->count=$c_shr_count); }
function set_created($c_shr_created) { return($this->created=$c_shr_created); }
function set_updated($c_shr_updated) { return($this->updated=$c_shr_updated); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shr_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("shr_track"); }
function getClasse() { return("shr_track"); }
function getPrefix() { return("shr"); }
function getDisplay() { return("titre"); }
function getAbstract() { return("type_reference"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track/list_shr_track.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track/maj_shr_track.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track/show_shr_track.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track/rss_shr_track.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track/xml_shr_track.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track/xmlxls_shr_track.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track/export_shr_track.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shr_track/import_shr_track.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>