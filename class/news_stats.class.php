<?php
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_stats.class.php')  && (strpos(__FILE__,'/include/bo/class/news_stats.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_stats.class.php');
}else{
/*======================================

objet de BDD news_stats :: class news_stats

SQL mySQL:

DROP TABLE IF EXISTS news_stats;
CREATE TABLE news_stats
(
	news_id			int (11) PRIMARY KEY not null,
	news_newsletter			int (11),
	news_inscrit			int (11),
	news_action			varchar (512),
	news_datec			datetime
)

SQL Oracle:

DROP TABLE news_stats
CREATE TABLE news_stats
(
	news_id			number (11) constraint news_pk PRIMARY KEY not null,
	news_newsletter			number (11),
	news_inscrit			number (11),
	news_action			varchar2 (512),
	news_datec			datetime
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_stats" prefix="news" display="newsletter" abstract="inscrit" def_order_field="datec" def_order_direction="DESC">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />

<item name="newsletter" libelle="Newsletter" type="int" length="11" list="true" order="true" fkey="newsletter"/>
<item name="inscrit" libelle="Inscrit" type="int" length="11" list="true" order="true" fkey="news_inscrit"/>
<item name="action" libelle="Action" type="varchar" length="512" list="true" order="true" />

<item name="datec" libelle="Date" type="datetime" list="true" order="true" />

<langpack lang="fr">
<norecords>Pas de stat à afficher</norecords>
</langpack>
</class>


==========================================*/

class news_stats
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $newsletter;
var $inscrit;
var $action;
var $datec;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_stats\" prefix=\"news\" display=\"newsletter\" abstract=\"inscrit\" def_order_field=\"datec\" def_order_direction=\"DESC\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />

<item name=\"newsletter\" libelle=\"Newsletter\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"newsletter\"/>
<item name=\"inscrit\" libelle=\"Inscrit\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"news_inscrit\"/>
<item name=\"action\" libelle=\"Action\" type=\"varchar\" length=\"512\" list=\"true\" order=\"true\" />

<item name=\"datec\" libelle=\"Date\" type=\"datetime\" list=\"true\" order=\"true\" />

<langpack lang=\"fr\">
<norecords>Pas de stat à afficher</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE news_stats
(
	news_id			int (11) PRIMARY KEY not null,
	news_newsletter			int (11),
	news_inscrit			int (11),
	news_action			varchar (512),
	news_datec			datetime
)

";

// constructeur
function news_stats($id=null)
{
	if (istable(get_class($this)) == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!==null) {
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
		$this->newsletter = -1;
		$this->inscrit = -1;
		$this->action = "";
		$this->datec = date('Y-m-d H:i:s');
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
	// ATTENTION, respecter l'ordre des champs de la table dans la base de donn�es pour construire laListeChamp
	$laListeChamps[]=new dbChamp("News_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("News_newsletter", "entier", "get_newsletter", "set_newsletter");
	$laListeChamps[]=new dbChamp("News_inscrit", "entier", "get_inscrit", "set_inscrit");
	$laListeChamps[]=new dbChamp("News_action", "text", "get_action", "set_action");
	$laListeChamps[]=new dbChamp("News_datec", "date_formatee_timestamp", "get_datec", "set_datec");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_newsletter() { return($this->newsletter); }
function get_inscrit() { return($this->inscrit); }
function get_action() { return($this->action); }
function get_datec() { return($this->datec); }


// setters
function set_id($c_news_id) { return($this->id=$c_news_id); }
function set_newsletter($c_news_newsletter) { return($this->newsletter=$c_news_newsletter); }
function set_inscrit($c_news_inscrit) { return($this->inscrit=$c_news_inscrit); }
function set_action($c_news_action) { return($this->action=$c_news_action); }
function set_datec($c_news_datec) { return($this->datec=$c_news_datec); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("news_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("news_stats"); }
function getClasse() { return("news_stats"); }
function getPrefix() { return(""); }
function getDisplay() { return("newsletter"); }
function getAbstract() { return("inscrit"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats/list_news_stats.php", "w");
	$listContent = "<"."?php
include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[newSizeOf(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats/maj_news_stats.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats/show_news_stats.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats/rss_news_stats.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats/xml_news_stats.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats/xlsx_news_stats.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_stats/import_news_stats.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>