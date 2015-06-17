<?php

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_cron.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_cron.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_cron.class.php');
}else{

//if ( ispatched('cms_cron')){
	$rs = $db->Execute('DESCRIBE `cms_cron`');
	if (isset($rs->_numOfRows)){
		if ($rs->_numOfRows == 17){
			$rs = $db->Execute('ALTER TABLE `cms_cron` ADD `cms_nextdate` datetime AFTER `cms_lastdate` ;');
		} 
		 
	} 
//}


/*======================================

objet de BDD cms_cron :: class cms_cron

SQL mySQL:

DROP TABLE IF EXISTS cms_cron;
CREATE TABLE cms_cron
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			varchar (256) not null,
	cms_descriptif			varchar (256) not null,
	cms_mm			varchar (128),
	cms_hh			varchar (128),
	cms_jj			varchar (128),
	cms_mmm			varchar (128),
	cms_jjj			varchar (128),
	cms_file			varchar (256),
	cms_ordre			int (11),
	cms_date_pub_debut			datetime,
	cms_date_pub_fin			datetime,
	cms_lastdate			datetime,
	cms_nextdate			datetime,
	cms_cdate			date,
	cms_mdate			date,
	cms_cms_site			int (11) not null,
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_cron
CREATE TABLE cms_cron
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_libelle			varchar2 (256) not null,
	cms_descriptif			varchar2 (256) not null,
	cms_mm			varchar2 (128),
	cms_hh			varchar2 (128),
	cms_jj			varchar2 (128),
	cms_mmm			varchar2 (128),
	cms_jjj			varchar2 (128),
	cms_file			varchar2 (256),
	cms_ordre			number (11),
	cms_date_pub_debut			datetime,
	cms_date_pub_fin			datetime,
	cms_lastdate			datetime,
	cms_nextdate			datetime,
	cms_cdate			date,
	cms_mdate			date,
	cms_cms_site			number (11) not null,
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_cron" libelle="Tâche" prefix="cms" display="libelle" abstract="libelle">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false"   />
<item name="libelle" libelle="Nom du cron" type="varchar" length="256"  list="true" order="true" notnull="true" nohtml="true"  />
<item name="descriptif" libelle="Descriptif" type="varchar" length="256"  list="true" order="true" notnull="true" nohtml="true" option="textarea" />
<item name="mm" libelle="Minutes" type="varchar" length="128"   nohtml="true"/>
<item name="hh" libelle="Heures" type="varchar" length="128"  nohtml="true" />
<item name="jj" libelle="Jour du mois" type="varchar" length="128"  nohtml="true" />
<item name="mmm" libelle="Mois" type="varchar" length="128"  nohtml="true" />
<item name="jjj" libelle="Jour de la semaine" type="varchar" length="128"  nohtml="true"  />

<item name="file" libelle="Tâche" type="varchar" length="256" option="link" />

<item name="ordre" libelle="Ordre" type="int" length="11" list="true" order="true" default="0"/>
<item name="date_pub_debut" libelle="Date de début d'utilisation" type="datetime"  format="l j F Y"   />
<item name="date_pub_fin" libelle="Date de fin d'utilisation" type="datetime"  format="l j F Y" /> 
<item name="lastdate" libelle="Dernière utilisation" type="datetime"  format="l j F Y"  /> 

<item name="nextdate" libelle="Prochaine utilisation" type="datetime"  format="l j F Y"  /> 

<item name="cdate" libelle="Date de création" type="date" />
<item name="mdate" libelle="Date de modification" type="date" />
<item name="cms_site" libelle="Mini site" type="int" length="11" notnull="true" default="-1" list="true" order="true" fkey="cms_site" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
</class>



==========================================*/

class cms_cron
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $descriptif;
var $mm;
var $hh;
var $jj;
var $mmm;
var $jjj;
var $file;
var $ordre;
var $date_pub_debut;
var $date_pub_fin;
var $lastdate;
var $nextdate;
var $cdate;
var $mdate;
var $cms_site;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_cron\" libelle=\"Tâche\" prefix=\"cms\" display=\"libelle\" abstract=\"libelle\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\"   />
<item name=\"libelle\" libelle=\"Nom du cron\" type=\"varchar\" length=\"256\"  list=\"true\" order=\"true\" notnull=\"true\" nohtml=\"true\"  />
<item name=\"descriptif\" libelle=\"Descriptif\" type=\"varchar\" length=\"256\"  list=\"true\" order=\"true\" notnull=\"true\" nohtml=\"true\" option=\"textarea\" />
<item name=\"mm\" libelle=\"Minutes\" type=\"varchar\" length=\"128\"   nohtml=\"true\"/>
<item name=\"hh\" libelle=\"Heures\" type=\"varchar\" length=\"128\"  nohtml=\"true\" />
<item name=\"jj\" libelle=\"Jour du mois\" type=\"varchar\" length=\"128\"  nohtml=\"true\" />
<item name=\"mmm\" libelle=\"Mois\" type=\"varchar\" length=\"128\"  nohtml=\"true\" />
<item name=\"jjj\" libelle=\"Jour de la semaine\" type=\"varchar\" length=\"128\"  nohtml=\"true\"  />

<item name=\"file\" libelle=\"Tâche\" type=\"varchar\" length=\"256\" option=\"link\" />

<item name=\"ordre\" libelle=\"Ordre\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" default=\"0\"/>
<item name=\"date_pub_debut\" libelle=\"Date de début d'utilisation\" type=\"datetime\"  format=\"l j F Y\"   />
<item name=\"date_pub_fin\" libelle=\"Date de fin d'utilisation\" type=\"datetime\"  format=\"l j F Y\" /> 
<item name=\"lastdate\" libelle=\"Dernière utilisation\" type=\"datetime\"  format=\"l j F Y\"  /> 

<item name=\"nextdate\" libelle=\"Prochaine utilisation\" type=\"datetime\"  format=\"l j F Y\"  /> 

<item name=\"cdate\" libelle=\"Date de création\" type=\"date\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"date\" />
<item name=\"cms_site\" libelle=\"Mini site\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_site\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_cron
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_libelle			varchar (256) not null,
	cms_descriptif			varchar (256) not null,
	cms_mm			varchar (128),
	cms_hh			varchar (128),
	cms_jj			varchar (128),
	cms_mmm			varchar (128),
	cms_jjj			varchar (128),
	cms_file			varchar (256),
	cms_ordre			int (11),
	cms_date_pub_debut			datetime,
	cms_date_pub_fin			datetime,
	cms_lastdate			datetime,
	cms_nextdate			datetime,
	cms_cdate			date,
	cms_mdate			date,
	cms_cms_site			int (11) not null,
	cms_statut			int (11) not null
)

";

// constructeur
function cms_cron($id=null)
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
		$this->descriptif = "";
		$this->mm = "";
		$this->hh = "";
		$this->jj = "";
		$this->mmm = "";
		$this->jjj = "";
		$this->file = "";
		$this->ordre = -1;
		$this->date_pub_debut = date('Y-m-d H:i:s');
		$this->date_pub_fin = date('Y-m-d H:i:s');
		$this->lastdate = date('Y-m-d H:i:s');
		$this->nextdate = date('Y-m-d H:i:s');
		$this->cdate = date("d/m/Y");
		$this->mdate = date("d/m/Y");
		$this->cms_site = -1;
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
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Cms_descriptif", "text", "get_descriptif", "set_descriptif");
	$laListeChamps[]=new dbChamp("Cms_mm", "text", "get_mm", "set_mm");
	$laListeChamps[]=new dbChamp("Cms_hh", "text", "get_hh", "set_hh");
	$laListeChamps[]=new dbChamp("Cms_jj", "text", "get_jj", "set_jj");
	$laListeChamps[]=new dbChamp("Cms_mmm", "text", "get_mmm", "set_mmm");
	$laListeChamps[]=new dbChamp("Cms_jjj", "text", "get_jjj", "set_jjj");
	$laListeChamps[]=new dbChamp("Cms_file", "text", "get_file", "set_file");
	$laListeChamps[]=new dbChamp("Cms_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Cms_date_pub_debut", "date_formatee_timestamp", "get_date_pub_debut", "set_date_pub_debut");
	$laListeChamps[]=new dbChamp("Cms_date_pub_fin", "date_formatee_timestamp", "get_date_pub_fin", "set_date_pub_fin");
	$laListeChamps[]=new dbChamp("Cms_lastdate", "date_formatee_timestamp", "get_lastdate", "set_lastdate");
	$laListeChamps[]=new dbChamp("Cms_nextdate", "date_formatee_timestamp", "get_nextdate", "set_nextdate");
	$laListeChamps[]=new dbChamp("Cms_cdate", "date_formatee", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Cms_mdate", "date_formatee", "get_mdate", "set_mdate");
	$laListeChamps[]=new dbChamp("Cms_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_descriptif() { return($this->descriptif); }
function get_mm() { return($this->mm); }
function get_hh() { return($this->hh); }
function get_jj() { return($this->jj); }
function get_mmm() { return($this->mmm); }
function get_jjj() { return($this->jjj); }
function get_file() { return($this->file); }
function get_ordre() { return($this->ordre); }
function get_date_pub_debut() { return($this->date_pub_debut); }
function get_date_pub_fin() { return($this->date_pub_fin); }
function get_lastdate() { return($this->lastdate); }
function get_nextdate() { return($this->nextdate); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }
function get_cms_site() { return($this->cms_site); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_libelle($c_cms_libelle) { return($this->libelle=$c_cms_libelle); }
function set_descriptif($c_cms_descriptif) { return($this->descriptif=$c_cms_descriptif); }
function set_mm($c_cms_mm) { return($this->mm=$c_cms_mm); }
function set_hh($c_cms_hh) { return($this->hh=$c_cms_hh); }
function set_jj($c_cms_jj) { return($this->jj=$c_cms_jj); }
function set_mmm($c_cms_mmm) { return($this->mmm=$c_cms_mmm); }
function set_jjj($c_cms_jjj) { return($this->jjj=$c_cms_jjj); }
function set_file($c_cms_file) { return($this->file=$c_cms_file); }
function set_ordre($c_cms_ordre) { return($this->ordre=$c_cms_ordre); }
function set_date_pub_debut($c_cms_date_pub_debut) { return($this->date_pub_debut=$c_cms_date_pub_debut); }
function set_date_pub_fin($c_cms_date_pub_fin) { return($this->date_pub_fin=$c_cms_date_pub_fin); }
function set_lastdate($c_cms_lastdate) { return($this->lastdate=$c_cms_lastdate); }
function set_nextdate($c_cms_nextdate) { return($this->nextdate=$c_cms_nextdate); }
function set_cdate($c_cms_cdate) { return($this->cdate=$c_cms_cdate); }
function set_mdate($c_cms_mdate) { return($this->mdate=$c_cms_mdate); }
function set_cms_site($c_cms_cms_site) { return($this->cms_site=$c_cms_cms_site); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_cron"); }
function getClasse() { return("cms_cron"); }
function getPrefix() { return(""); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("libelle"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron/list_cms_cron.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron/maj_cms_cron.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron/show_cms_cron.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron/rss_cms_cron.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron/xml_cms_cron.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron/xlsx_cms_cron.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron/export_cms_cron.php", "w");
	$exportContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_cron/import_cms_cron.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>