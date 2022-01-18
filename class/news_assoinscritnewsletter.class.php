<?php
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_assoinscritnewsletter.class.php')  && (strpos(__FILE__,'/include/bo/class/news_assoinscritnewsletter.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_assoinscritnewsletter.class.php');
}else{
/*======================================

objet de BDD news_assoinscritnewsletter :: class news_assoinscritnewsletter

SQL mySQL:

DROP TABLE IF EXISTS news_assoinscritnewsletter;
CREATE TABLE news_assoinscritnewsletter
(
	xin_id			int (11) PRIMARY KEY not null,
	xin_news_inscrit			int,
	xin_newsletter			int
)

SQL Oracle:

DROP TABLE news_assoinscritnewsletter
CREATE TABLE news_assoinscritnewsletter
(
	xin_id			number (11) constraint xin_pk PRIMARY KEY not null,
	xin_news_inscrit			number,
	xin_newsletter			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_assoinscritnewsletter" libelle="Inscrits à une newsletter" prefix="xin" display="news_inscrit" abstract="newsletter" is_asso="true">
    <item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" />
    <item name="news_inscrit" libelle="Inscrit" type="int" default="0" order="true" list="true" fkey="news_inscrit" />
    <item name="newsletter" libelle="Newsletter" type="int" default="0" order="true" list="true" fkey="newsletter" />
</class>


==========================================*/

class news_assoinscritnewsletter
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $news_inscrit;
var $newsletter;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_assoinscritnewsletter\" libelle=\"Inscrits à une newsletter\" prefix=\"xin\" display=\"news_inscrit\" abstract=\"newsletter\" is_asso=\"true\">
    <item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" />
    <item name=\"news_inscrit\" libelle=\"Inscrit\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"news_inscrit\" />
    <item name=\"newsletter\" libelle=\"Newsletter\" type=\"int\" default=\"0\" order=\"true\" list=\"true\" fkey=\"newsletter\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE news_assoinscritnewsletter
(
	xin_id			int (11) PRIMARY KEY not null,
	xin_news_inscrit			int,
	xin_newsletter			int
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
		$this->news_inscrit = -1;
		$this->newsletter = -1;
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
	$laListeChamps[]=new dbChamp("Xin_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xin_news_inscrit", "entier", "get_news_inscrit", "set_news_inscrit");
	$laListeChamps[]=new dbChamp("Xin_newsletter", "entier", "get_newsletter", "set_newsletter");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_news_inscrit() { return($this->news_inscrit); }
function get_newsletter() { return($this->newsletter); }


// setters
function set_id($c_xin_id) { return($this->id=$c_xin_id); }
function set_news_inscrit($c_xin_news_inscrit) { return($this->news_inscrit=$c_xin_news_inscrit); }
function set_newsletter($c_xin_newsletter) { return($this->newsletter=$c_xin_newsletter); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xin_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("news_assoinscritnewsletter"); }
function getClasse() { return("news_assoinscritnewsletter"); }
function getPrefix() { return(""); }
function getDisplay() { return("news_inscrit"); }
function getAbstract() { return("newsletter"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter/list_news_assoinscritnewsletter.php", "w");
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
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter/maj_news_assoinscritnewsletter.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter/show_news_assoinscritnewsletter.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter/rss_news_assoinscritnewsletter.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter/xml_news_assoinscritnewsletter.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter/xlsx_news_assoinscritnewsletter.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_assoinscritnewsletter/import_news_assoinscritnewsletter.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>