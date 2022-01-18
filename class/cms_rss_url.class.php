<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


$rs = $db->Execute('DESCRIBE `cms_rss_url`');
if (isset($rs->_numOfRows)){ 
	if ($rs->_numOfRows == 8){
		$rs = $db->Execute('ALTER TABLE `cms_rss_url` ADD `rssurl_set` VARCHAR( 255 ) NULL DEFAULT \'\' AFTER `rssurl_classe` ;'); 	
		$rs = $db->Execute('ALTER TABLE `cms_rss_url` ADD `rssurl_value` VARCHAR( 255 ) NULL DEFAULT \'\' AFTER `rssurl_set` ;'); 
		$rs = $db->Execute('ALTER TABLE `cms_rss_url` ADD `rssurl_langue` INT( 2 ) NULL DEFAULT \'1\' AFTER `rssurl_is_firstfeed` ;'); 
		$rs = $db->Execute('ALTER TABLE `cms_rss_url` ADD `rssurl_destinataire` VARCHAR( 255 ) NULL DEFAULT \'1\' AFTER `rssurl_langue` ;'); 
	} 
} 
 

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_rss_url.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_rss_url.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_rss_url.class.php');
}else{
/*======================================

objet de BDD cms_rss_url :: class cms_rss_url

SQL mySQL:

DROP TABLE IF EXISTS cms_rss_url;
CREATE TABLE cms_rss_url
(
	rssurl_id			int (11) PRIMARY KEY not null,
	rssurl_libelle			varchar (128),
	rssurl_url			varchar (255),
	rssurl_classe			int (4),
	rssurl_set			varchar (255),
	rssurl_value			varchar (255),
	rssurl_is_firstfeed			int (2),
	rssurl_langue			int (2),
	rssurl_destinataire			varchar (255),
	rssurl_dtcrea			date,
	rssurl_dtmod			date,
	rssurl_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_rss_url
CREATE TABLE cms_rss_url
(
	rssurl_id			number (11) constraint rssurl_pk PRIMARY KEY not null,
	rssurl_libelle			varchar2 (128),
	rssurl_url			varchar2 (255),
	rssurl_classe			number (4),
	rssurl_set			varchar2 (255),
	rssurl_value			varchar2 (255),
	rssurl_is_firstfeed			number (2),
	rssurl_langue			number (2),
	rssurl_destinataire			varchar2 (255),
	rssurl_dtcrea			date,
	rssurl_dtmod			date,
	rssurl_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_rss_url" libelle="Flux rss à télécharger" prefix="rssurl" display="id" abstract="client">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true"/> 
<item name="libelle" libelle="Libellé du flux" type="varchar" length="128" list="true"  order="true" /> 
<item name="url" libelle="URL du flux" type="varchar" length="255" list="true"  order="true" />
<item name="classe" libelle="Objet" type="int"  length="4" list="true"  order="true" fkey="classe" isfkeyfilter="true"/>
<item name="set" libelle="set" type="varchar"  length="255" list="false"  order="true" noedit="true" />
<item name="value" libelle="value" type="varchar"  length="255" list="false"  order="true" noedit="true" />
<item name="is_firstfeed" libelle="A déjà été mis à jour" type="int"  length="2" list="false"  order="false" default="0" option="bool"/>
<item name="langue" libelle="Langue" type="int"  length="2" list="true"  order="true" default="1" fkey="cms_langue"/>
<item name="destinataire" libelle="E-mail" type="varchar"  length="255" list="true"  order="true" default="" />
<item name="dtcrea" libelle="Date de création" type="date" list="false" order="false" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
</class> 


==========================================*/

class cms_rss_url
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $url;
var $classe;
var $set;
var $value;
var $is_firstfeed;
var $langue;
var $destinataire;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_rss_url\" libelle=\"Flux rss à télécharger\" prefix=\"rssurl\" display=\"id\" abstract=\"client\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\"/> 
<item name=\"libelle\" libelle=\"Libellé du flux\" type=\"varchar\" length=\"128\" list=\"true\"  order=\"true\" /> 
<item name=\"url\" libelle=\"URL du flux\" type=\"varchar\" length=\"255\" list=\"true\"  order=\"true\" />
<item name=\"classe\" libelle=\"Objet\" type=\"int\"  length=\"4\" list=\"true\"  order=\"true\" fkey=\"classe\" isfkeyfilter=\"true\"/>
<item name=\"set\" libelle=\"set\" type=\"varchar\"  length=\"255\" list=\"false\"  order=\"true\" noedit=\"true\" />
<item name=\"value\" libelle=\"value\" type=\"varchar\"  length=\"255\" list=\"false\"  order=\"true\" noedit=\"true\" />
<item name=\"is_firstfeed\" libelle=\"A déjà été mis à jour\" type=\"int\"  length=\"2\" list=\"false\"  order=\"false\" default=\"0\" option=\"bool\"/>
<item name=\"langue\" libelle=\"Langue\" type=\"int\"  length=\"2\" list=\"true\"  order=\"true\" default=\"1\" fkey=\"cms_langue\"/>
<item name=\"destinataire\" libelle=\"E-mail\" type=\"varchar\"  length=\"255\" list=\"true\"  order=\"true\" default=\"\" />
<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"false\" order=\"false\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
</class> ";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_rss_url
(
	rssurl_id			int (11) PRIMARY KEY not null,
	rssurl_libelle			varchar (128),
	rssurl_url			varchar (255),
	rssurl_classe			int (4),
	rssurl_set			varchar (255),
	rssurl_value			varchar (255),
	rssurl_is_firstfeed			int (2),
	rssurl_langue			int (2),
	rssurl_destinataire			varchar (255),
	rssurl_dtcrea			date,
	rssurl_dtmod			date,
	rssurl_statut			int (11) not null
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
		$this->libelle = "";
		$this->url = "";
		$this->classe = -1;
		$this->set = "";
		$this->value = "";
		$this->is_firstfeed = -1;
		$this->langue = 1;
		$this->destinataire = "";
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
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
	$laListeChamps[]=new dbChamp("Rssurl_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Rssurl_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Rssurl_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Rssurl_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Rssurl_set", "text", "get_set", "set_set");
	$laListeChamps[]=new dbChamp("Rssurl_value", "text", "get_value", "set_value");
	$laListeChamps[]=new dbChamp("Rssurl_is_firstfeed", "entier", "get_is_firstfeed", "set_is_firstfeed");
	$laListeChamps[]=new dbChamp("Rssurl_langue", "entier", "get_langue", "set_langue");
	$laListeChamps[]=new dbChamp("Rssurl_destinataire", "text", "get_destinataire", "set_destinataire");
	$laListeChamps[]=new dbChamp("Rssurl_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Rssurl_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Rssurl_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_url() { return($this->url); }
function get_classe() { return($this->classe); }
function get_set() { return($this->set); }
function get_value() { return($this->value); }
function get_is_firstfeed() { return($this->is_firstfeed); }
function get_langue() { return($this->langue); }
function get_destinataire() { return($this->destinataire); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_rssurl_id) { return($this->id=$c_rssurl_id); }
function set_libelle($c_rssurl_libelle) { return($this->libelle=$c_rssurl_libelle); }
function set_url($c_rssurl_url) { return($this->url=$c_rssurl_url); }
function set_classe($c_rssurl_classe) { return($this->classe=$c_rssurl_classe); }
function set_set($c_rssurl_set) { return($this->set=$c_rssurl_set); }
function set_value($c_rssurl_value) { return($this->value=$c_rssurl_value); }
function set_is_firstfeed($c_rssurl_is_firstfeed) { return($this->is_firstfeed=$c_rssurl_is_firstfeed); }
function set_langue($c_rssurl_langue) { return($this->langue=$c_rssurl_langue); }
function set_destinataire($c_rssurl_destinataire) { return($this->destinataire=$c_rssurl_destinataire); }
function set_dtcrea($c_rssurl_dtcrea) { return($this->dtcrea=$c_rssurl_dtcrea); }
function set_dtmod($c_rssurl_dtmod) { return($this->dtmod=$c_rssurl_dtmod); }
function set_statut($c_rssurl_statut) { return($this->statut=$c_rssurl_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("rssurl_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("rssurl_statut"); }
//
function getTable() { return("cms_rss_url"); }
function getClasse() { return("cms_rss_url"); }
function getDisplay() { return("id"); }
function getAbstract() { return("client"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url/list_cms_rss_url.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[newSizeOf(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url/maj_cms_rss_url.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url/show_cms_rss_url.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url/rss_cms_rss_url.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url/xml_cms_rss_url.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url/xmlxls_cms_rss_url.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url/export_cms_rss_url.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss_url/import_cms_rss_url.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>