<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_log_statut.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_log_statut.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_log_statut.class.php');
}else{
/*======================================

objet de BDD cms_log_statut :: class cms_log_statut

SQL mySQL:

DROP TABLE IF EXISTS cms_log_statut;
CREATE TABLE cms_log_statut
(
	log_id			int (11) PRIMARY KEY not null,
	log_bo_users			int (11),
	log_classe			int (11),
	log_record			int (11),
	log_texte			text,
	log_cdate			datetime not null,
	log_mdate			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE cms_log_statut
CREATE TABLE cms_log_statut
(
	log_id			number (11) constraint log_pk PRIMARY KEY not null,
	log_bo_users			number (11),
	log_classe			number (11),
	log_record			number (11),
	log_texte			text,
	log_cdate			datetime not null,
	log_mdate			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_log_statut" libelle="Logs des changements de statut" prefix="log" display="classe" abstract="texte" def_order_field="cdate" def_order_direction="DESC" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="bo_users" libelle="Administrateur" type="int" length="11" list="false" order="false" fkey="bo_users" restrict="loose"/>
<item name="classe" libelle="Classe" type="int" length="11" list="false" order="false" fkey="classe"/>
<item name="record" libelle="Enregistrement" type="int" length="11" list="false" order="false" nohtml="true"/>
<item name="texte" libelle="Texte" type="text" list="true" order="false" truncate="150" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true"  list="true" order="true" /> 
<item name="mdate" libelle="Date de mise à jour" type="timestamp" notnull="true"  list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" /> 
</class>


==========================================*/

class cms_log_statut
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $bo_users;
var $classe;
var $record;
var $texte;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_log_statut\" libelle=\"Logs des changemets de statut\" prefix=\"log\" display=\"classe\" abstract=\"texte\" def_order_field=\"cdate\" def_order_direction=\"DESC\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"bo_users\" libelle=\"Administrateur\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"bo_users\" restrict=\"loose\"/>
<item name=\"classe\" libelle=\"Classe\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" fkey=\"classe\"/>
<item name=\"record\" libelle=\"Enregistrement\" type=\"int\" length=\"11\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"texte\" libelle=\"Texte\" type=\"text\" list=\"true\" order=\"false\" truncate=\"150\"/>
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\"  list=\"true\" order=\"true\" /> 
<item name=\"mdate\" libelle=\"Date de mise à jour\" type=\"timestamp\" notnull=\"true\"  list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" /> 
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_log_statut
(
	log_id			int (11) PRIMARY KEY not null,
	log_bo_users			int (11),
	log_classe			int (11),
	log_record			int (11),
	log_texte			text,
	log_cdate			datetime not null,
	log_mdate			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
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
		$this->classe = -1;
		$this->record = -1;
		$this->texte = "";
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
	$laListeChamps[]=new dbChamp("Log_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Log_bo_users", "entier", "get_bo_users", "set_bo_users");
	$laListeChamps[]=new dbChamp("Log_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Log_record", "entier", "get_record", "set_record");
	$laListeChamps[]=new dbChamp("Log_texte", "text", "get_texte", "set_texte");
	$laListeChamps[]=new dbChamp("Log_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Log_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_bo_users() { return($this->bo_users); }
function get_classe() { return($this->classe); }
function get_record() { return($this->record); }
function get_texte() { return($this->texte); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_log_id) { return($this->id=$c_log_id); }
function set_bo_users($c_log_bo_users) { return($this->bo_users=$c_log_bo_users); }
function set_classe($c_log_classe) { return($this->classe=$c_log_classe); }
function set_record($c_log_record) { return($this->record=$c_log_record); }
function set_texte($c_log_texte) { return($this->texte=$c_log_texte); }
function set_cdate($c_log_cdate) { return($this->cdate=$c_log_cdate); }
function set_mdate($c_log_mdate) { return($this->mdate=$c_log_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("log_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_log_statut"); }
function getClasse() { return("cms_log_statut"); }
function getPrefix() { return(""); }
function getDisplay() { return("classe"); }
function getAbstract() { return("texte"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut/list_cms_log_statut.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut/maj_cms_log_statut.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut/show_cms_log_statut.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut/rss_cms_log_statut.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut/xml_cms_log_statut.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut/xlsx_cms_log_statut.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut/export_cms_log_statut.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_log_statut/import_cms_log_statut.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>