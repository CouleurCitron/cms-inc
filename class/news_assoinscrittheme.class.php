<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('news_assoinscrittheme')){
	$rs = $db->Execute('SHOW COLUMNS FROM `news_assoinscrittheme`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('xit_criteres', $names)) {
			$rs = $db->Execute("ALTER TABLE `news_assoinscrittheme` ADD `xit_criteres` TEXT CHARACTER SET latin1 COLLATE latin1_general_ci NULL AFTER `xit_news_theme`;");
		}
	}
}

/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_assoinscrittheme.class.php')  && (strpos(__FILE__,'/include/bo/class/news_assoinscrittheme.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_assoinscrittheme.class.php');
}else{
/*======================================

objet de BDD news_assoinscrittheme :: class news_assoinscrittheme

SQL mySQL:

DROP TABLE IF EXISTS news_assoinscrittheme;
CREATE TABLE news_assoinscrittheme
(
	xit_id			int (11) PRIMARY KEY not null,
	xit_news_inscrit			int,
	xit_news_theme			int,
	xit_criteres			text not null,
	xit_statut			int (11) not null
)

SQL Oracle:

DROP TABLE news_assoinscrittheme
CREATE TABLE news_assoinscrittheme
(
	xit_id			number (11) constraint xit_pk PRIMARY KEY not null,
	xit_news_inscrit			number,
	xit_news_theme			number,
	xit_criteres			text not null,
	xit_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_assoinscrittheme" libelle="Abonnement" prefix="xit" display="news_inscrit" abstract="news_theme" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" />
<item name="news_inscrit" libelle="Inscrit"  type="int" default="0" order="true" list="true" fkey="news_inscrit" />
<item name="news_theme" libelle="Thème"  type="int" default="0" order="true" list="true" fkey="news_theme" />
<item name="criteres" libelle="Valeurs des critères" type="text" notnull="true" default="" option="textarea" noedit="true" serialized="true" />
<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_ID_STATUT_LIGNE" order="true" list="true" >
<option type="value" value="1" libelle="en attente" />
<option type="value" value="4" libelle="abonné" />
<option type="value" value="5" libelle="désabonné" />
</item>
<langpack lang="fr">
<norecords>Pas d'asso inscrit/theme à afficher</norecords>
</langpack>
</class>



==========================================*/

class news_assoinscrittheme
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $news_inscrit;
var $news_theme;
var $criteres;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_assoinscrittheme\" libelle=\"Abonnement\" prefix=\"xit\" display=\"news_inscrit\" abstract=\"news_theme\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" />
<item name=\"news_inscrit\" libelle=\"Inscrit\"  type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"news_inscrit\" />
<item name=\"news_theme\" libelle=\"Thème\"  type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"news_theme\" />
<item name=\"criteres\" libelle=\"Valeurs des critères\" type=\"text\" notnull=\"true\" default=\"\" option=\"textarea\" noedit=\"true\" serialized=\"true\" />
<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_ID_STATUT_LIGNE\" order=\"true\" list=\"true\" >
<option type=\"value\" value=\"1\" libelle=\"en attente\" />
<option type=\"value\" value=\"4\" libelle=\"abonné\" />
<option type=\"value\" value=\"5\" libelle=\"désabonné\" />
</item>
<langpack lang=\"fr\">
<norecords>Pas d'asso inscrit/theme à afficher</norecords>
</langpack>
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE news_assoinscrittheme
(
	xit_id			int (11) PRIMARY KEY not null,
	xit_news_inscrit			int,
	xit_news_theme			int,
	xit_criteres			text not null,
	xit_statut			int (11) not null
)

";

// constructeur
function news_assoinscrittheme($id=null)
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
		$this->news_inscrit = -1;
		$this->news_theme = -1;
		$this->criteres = "";
		$this->statut = DEF_ID_STATUT_LIGNE;
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
	$laListeChamps[]=new dbChamp("Xit_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xit_news_inscrit", "entier", "get_news_inscrit", "set_news_inscrit");
	$laListeChamps[]=new dbChamp("Xit_news_theme", "entier", "get_news_theme", "set_news_theme");
	$laListeChamps[]=new dbChamp("Xit_criteres", "text", "get_criteres", "set_criteres");
	$laListeChamps[]=new dbChamp("Xit_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_news_inscrit() { return($this->news_inscrit); }
function get_news_theme() { return($this->news_theme); }
function get_criteres() { return($this->criteres); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_xit_id) { return($this->id=$c_xit_id); }
function set_news_inscrit($c_xit_news_inscrit) { return($this->news_inscrit=$c_xit_news_inscrit); }
function set_news_theme($c_xit_news_theme) { return($this->news_theme=$c_xit_news_theme); }
function set_criteres($c_xit_criteres) { return($this->criteres=$c_xit_criteres); }
function set_statut($c_xit_statut) { return($this->statut=$c_xit_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xit_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("xit_statut"); }
//
function getTable() { return("news_assoinscrittheme"); }
function getClasse() { return("news_assoinscrittheme"); }
function getPrefix() { return(""); }
function getDisplay() { return("news_inscrit"); }
function getAbstract() { return("news_theme"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme/list_news_assoinscrittheme.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme/maj_news_assoinscrittheme.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme/show_news_assoinscrittheme.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme/rss_news_assoinscrittheme.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme/xml_news_assoinscrittheme.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme/xlsx_news_assoinscrittheme.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme/export_news_assoinscrittheme.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscrittheme/import_news_assoinscrittheme.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>