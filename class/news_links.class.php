<?php
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_links.class.php')  && (strpos(__FILE__,'/include/bo/class/news_links.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_links.class.php');
}else{
/*======================================

objet de BDD news_links :: class news_links

SQL mySQL:

DROP TABLE IF EXISTS news_links;
CREATE TABLE news_links
(
	news_id			int (11) PRIMARY KEY not null,
	news_url			varchar (512),
	news_md5			varchar (32)
)

SQL Oracle:

DROP TABLE news_links
CREATE TABLE news_links
(
	news_id			number (11) constraint news_pk PRIMARY KEY not null,
	news_url			varchar2 (512),
	news_md5			varchar2 (32)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_links" prefix="news" display="url" abstract="md5">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />

<item name="url" libelle="URL" type="varchar" length="512" list="true" order="true" />
<item name="md5" libelle="MD5" type="varchar" length="32" list="true" order="true" />

<langpack lang="fr">
<norecords>Pas d'url a afficher</norecords>
</langpack>
</class>



==========================================*/

class news_links
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $url;
var $md5;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_links\" prefix=\"news\" display=\"url\" abstract=\"md5\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />

<item name=\"url\" libelle=\"URL\" type=\"varchar\" length=\"512\" list=\"true\" order=\"true\" />
<item name=\"md5\" libelle=\"MD5\" type=\"varchar\" length=\"32\" list=\"true\" order=\"true\" />

<langpack lang=\"fr\">
<norecords>Pas d'url a afficher</norecords>
</langpack>
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE news_links
(
	news_id			int (11) PRIMARY KEY not null,
	news_url			varchar (512),
	news_md5			varchar (32)
)

";

// constructeur
function news_links($id=null)
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
		$this->url = "";
		$this->md5 = "";
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
	$laListeChamps[]=new dbChamp("News_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("News_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("News_md5", "text", "get_md5", "set_md5");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_url() { return($this->url); }
function get_md5() { return($this->md5); }


// setters
function set_id($c_news_id) { return($this->id=$c_news_id); }
function set_url($c_news_url) { return($this->url=$c_news_url); }
function set_md5($c_news_md5) { return($this->md5=$c_news_md5); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("news_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("news_links"); }
function getClasse() { return("news_links"); }
function getPrefix() { return(""); }
function getDisplay() { return("url"); }
function getAbstract() { return("md5"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links/list_news_links.php", "w");
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
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links/maj_news_links.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links/show_news_links.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links/rss_news_links.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links/xml_news_links.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links/xlsx_news_links.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_links/import_news_links.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>