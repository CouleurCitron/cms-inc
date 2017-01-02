<?php
/* [Begin patch] */

// patch de migration
if (!ispatched('cms_prepend')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_prepend`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}

		if (!in_array('ppd_isall', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_prepend` ADD `ppd_isall` INT( 2 ) NOT NULL DEFAULT 0 AFTER `ppd_fichier`  ;");
		}
	}
}
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_prepend.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_prepend.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_prepend.class.php');
}else{
/*======================================

objet de BDD cms_prepend :: class cms_prepend

SQL mySQL:

DROP TABLE IF EXISTS cms_prepend;
CREATE TABLE cms_prepend
(
	ppd_id			int (11) PRIMARY KEY not null,
	ppd_libelle			varchar (255),
	ppd_descriptif			varchar (512),
	ppd_fichier			varchar (255),
	ppd_isall			int (2),
	ppd_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_prepend
CREATE TABLE cms_prepend
(
	ppd_id			number (11) constraint ppd_pk PRIMARY KEY not null,
	ppd_libelle			varchar2 (255),
	ppd_descriptif			varchar2 (512),
	ppd_fichier			varchar2 (255),
	ppd_isall			number (2),
	ppd_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_prepend" libelle="Scripts preprend" prefix="ppd" display="libelle" abstract="fichier">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assoprependarbopages,cms_assoprependcmssite" />
<item name="libelle" libelle="libellé" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="descriptif" libelle="descriptif" type="varchar" length="512" list="false" order="false" />
<item name="fichier" libelle="fichier" type="varchar" length="255" list="true" order="true" nohtml="true" />

<item name="isall" libelle="actif pour tous sites" type="int" length="2" default="0" list="true" order="true" option="bool" />

<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" />
<langpack lang="fr">
<norecords>Pas de Script preprend à afficher</norecords>
</langpack>
</class>


==========================================*/

class cms_prepend
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $libelle;
var $descriptif;
var $fichier;
var $isall;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_prepend\" libelle=\"Scripts preprend\" prefix=\"ppd\" display=\"libelle\" abstract=\"fichier\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assoprependarbopages,cms_assoprependcmssite\" />
<item name=\"libelle\" libelle=\"libellé\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"descriptif\" libelle=\"descriptif\" type=\"varchar\" length=\"512\" list=\"false\" order=\"false\" />
<item name=\"fichier\" libelle=\"fichier\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />

<item name=\"isall\" libelle=\"actif pour tous sites\" type=\"int\" length=\"2\" default=\"0\" list=\"true\" order=\"true\" option=\"bool\" />

<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" />
<langpack lang=\"fr\">
<norecords>Pas de Script preprend à afficher</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_prepend
(
	ppd_id			int (11) PRIMARY KEY not null,
	ppd_libelle			varchar (255),
	ppd_descriptif			varchar (512),
	ppd_fichier			varchar (255),
	ppd_isall			int (2),
	ppd_statut			int (11) not null
)

";

// constructeur
function cms_prepend($id=null)
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
		$this->fichier = "";
		$this->isall = -1;
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
	$laListeChamps[]=new dbChamp("Ppd_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Ppd_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Ppd_descriptif", "text", "get_descriptif", "set_descriptif");
	$laListeChamps[]=new dbChamp("Ppd_fichier", "text", "get_fichier", "set_fichier");
	$laListeChamps[]=new dbChamp("Ppd_isall", "entier", "get_isall", "set_isall");
	$laListeChamps[]=new dbChamp("Ppd_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_descriptif() { return($this->descriptif); }
function get_fichier() { return($this->fichier); }
function get_isall() { return($this->isall); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_ppd_id) { return($this->id=$c_ppd_id); }
function set_libelle($c_ppd_libelle) { return($this->libelle=$c_ppd_libelle); }
function set_descriptif($c_ppd_descriptif) { return($this->descriptif=$c_ppd_descriptif); }
function set_fichier($c_ppd_fichier) { return($this->fichier=$c_ppd_fichier); }
function set_isall($c_ppd_isall) { return($this->isall=$c_ppd_isall); }
function set_statut($c_ppd_statut) { return($this->statut=$c_ppd_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("ppd_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("ppd_statut"); }
//
function getTable() { return("cms_prepend"); }
function getClasse() { return("cms_prepend"); }
function getPrefix() { return(""); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("fichier"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/list_cms_prepend.php", "w");
	$listContent = "<"."?php
include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/maj_cms_prepend.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/show_cms_prepend.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/rss_cms_prepend.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/xml_cms_prepend.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/xlsx_cms_prepend.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_prepend/import_cms_prepend.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>