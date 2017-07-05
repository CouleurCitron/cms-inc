<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('newsletter')){
	$rs = $db->Execute('SHOW COLUMNS FROM `newsletter`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];	
			if($rs->fields["Field"]=='news_html'){
			 	if (preg_match('/varchar/', $rs->fields["Type"])==1){
					$db->Execute('ALTER TABLE `newsletter` CHANGE `news_html` `news_html` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL');
				}
			}
			$rs->MoveNext();
		}
		
		if (!in_array('news_nbrecu', $names)) {
			$rs = $db->Execute("ALTER TABLE `newsletter` ADD `news_nbrecu` INT( 11 ) NOT NULL DEFAULT \'0\'  AFTER `news_nbmail` ;");
		}
	}
}

/* [End patch] */

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/newsletter.class.php')  && (strpos(__FILE__,'/include/bo/class/newsletter.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/newsletter.class.php');
}else{
/*======================================

objet de BDD newsletter :: class newsletter

SQL mySQL:

DROP TABLE IF EXISTS newsletter;
CREATE TABLE newsletter
(
	news_id			int (11) PRIMARY KEY not null,
	news_datecrea			datetime,
	news_dateenvoi			datetime,
	news_theme			int (11),
	news_libelle			varchar (255),
	news_html			text (1024),
	news_nbmail			int (11) not null,
	news_nbrecu			int (11) not null,
	news_test			int (4) not null,
	news_expediteur			int (11),
	news_statut			int (11) not null
)

SQL Oracle:

DROP TABLE newsletter
CREATE TABLE newsletter
(
	news_id			number (11) constraint news_pk PRIMARY KEY not null,
	news_datecrea			datetime,
	news_dateenvoi			datetime,
	news_theme			number (11),
	news_libelle			varchar2 (255),
	news_html			text (1024),
	news_nbmail			number (11) not null,
	news_nbrecu			number (11) not null,
	news_test			number (4) not null,
	news_expediteur			number (11),
	news_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="newsletter" prefix="news" display="theme" abstract="libelle" def_order_field="datecrea" def_order_direction="DESC">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" asso="news_assonewspdf,news_assonewscron" />
<item name="datecrea" libelle="Date de création" type="datetime" list="true" order="true" />
<item name="dateenvoi" libelle="Date d'envoi" type="datetime" list="true" order="true" />
<item name="theme" libelle="Thème" type="int" length="11" list="true" order="true" fkey="news_theme"/>
<item name="libelle" libelle="Titre" type="varchar" length="255" list="true" order="true" />
<item name="html" libelle="HTML" type="text" length="1024" list="false" order="false" />
<item name="nbmail" libelle="Nombre de mails envoyés" type="int" length="11" notnull="true" default="0" list="true" order="true" />
<item name="nbrecu" libelle="Nombre de mails reçus" type="int" length="11" notnull="true" default="0" list="true" order="true" />
<item name="test" libelle="En phase de test" type="int" length="4" notnull="true" default="0" option="bool"/>
<item name="expediteur" libelle="Expéditeur" type="int" length="11" list="true" order="true" fkey="news_expediteur"/>
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_NEWS_DEFAUT" list="true" order="true">
<option type="value" value="1" libelle="En création" />
<option type="value" value="4" libelle="Validée" />
<option type="value" value="6" libelle="En cours d'envoi" />
<option type="value" value="5" libelle="Envoyée" />
</item>
<langpack lang="fr">
<norecords>Pas de newsletter à afficher</norecords>
</langpack>
</class>


==========================================*/

class newsletter
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $datecrea;
var $dateenvoi;
var $theme;
var $libelle;
var $html;
var $nbmail;
var $nbrecu;
var $test;
var $expediteur;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"newsletter\" prefix=\"news\" display=\"theme\" abstract=\"libelle\" def_order_field=\"datecrea\" def_order_direction=\"DESC\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" asso=\"news_assonewspdf,news_assonewscron\" />
<item name=\"datecrea\" libelle=\"Date de création\" type=\"datetime\" list=\"true\" order=\"true\" />
<item name=\"dateenvoi\" libelle=\"Date d'envoi\" type=\"datetime\" list=\"true\" order=\"true\" />
<item name=\"theme\" libelle=\"Thème\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"news_theme\"/>
<item name=\"libelle\" libelle=\"Titre\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" />
<item name=\"html\" libelle=\"HTML\" type=\"text\" length=\"1024\" list=\"false\" order=\"false\" />
<item name=\"nbmail\" libelle=\"Nombre de mails envoyés\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"nbrecu\" libelle=\"Nombre de mails reçus\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"test\" libelle=\"En phase de test\" type=\"int\" length=\"4\" notnull=\"true\" default=\"0\"  option=\"bool\"/>
<item name=\"expediteur\" libelle=\"Expéditeur\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"news_expediteur\"/>
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_NEWS_DEFAUT\" list=\"true\" order=\"true\">
<option type=\"value\" value=\"1\" libelle=\"En création\" />
<option type=\"value\" value=\"4\" libelle=\"Validée\" />
<option type=\"value\" value=\"6\" libelle=\"En cours d'envoi\" />
<option type=\"value\" value=\"5\" libelle=\"Envoyée\" />
</item>
<langpack lang=\"fr\">
<norecords>Pas de newsletter à afficher</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE newsletter
(
	news_id			int (11) PRIMARY KEY not null,
	news_datecrea			datetime,
	news_dateenvoi			datetime,
	news_theme			int (11),
	news_libelle			varchar (255),
	news_html			text (1024),
	news_nbmail			int (11) not null,
	news_nbrecu			int (11) not null,
	news_test			int (4) not null,
	news_expediteur			int (11),
	news_statut			int (11) not null
)

";

// constructeur
function newsletter($id=null)
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
		$this->datecrea = date('Y-m-d H:i:s');
		$this->dateenvoi = date('Y-m-d H:i:s');
		$this->theme = -1;
		$this->libelle = "";
		$this->html = "";
		$this->nbmail = -1;
		$this->nbrecu = -1;
		$this->test = -1;
		$this->expediteur = -1;
		$this->statut = DEF_CODE_STATUT_NEWS_DEFAUT;
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
	$laListeChamps[]=new dbChamp("News_datecrea", "date_formatee_timestamp", "get_datecrea", "set_datecrea");
	$laListeChamps[]=new dbChamp("News_dateenvoi", "date_formatee_timestamp", "get_dateenvoi", "set_dateenvoi");
	$laListeChamps[]=new dbChamp("News_theme", "entier", "get_theme", "set_theme");
	$laListeChamps[]=new dbChamp("News_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("News_html", "text", "get_html", "set_html");
	$laListeChamps[]=new dbChamp("News_nbmail", "entier", "get_nbmail", "set_nbmail");
	$laListeChamps[]=new dbChamp("News_nbrecu", "entier", "get_nbrecu", "set_nbrecu");
	$laListeChamps[]=new dbChamp("News_test", "entier", "get_test", "set_test");
	$laListeChamps[]=new dbChamp("News_expediteur", "entier", "get_expediteur", "set_expediteur");
	$laListeChamps[]=new dbChamp("News_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_datecrea() { return($this->datecrea); }
function get_dateenvoi() { return($this->dateenvoi); }
function get_theme() { return($this->theme); }
function get_libelle() { return($this->libelle); }
function get_html() { return($this->html); }
function get_nbmail() { return($this->nbmail); }
function get_nbrecu() { return($this->nbrecu); }
function get_test() { return($this->test); }
function get_expediteur() { return($this->expediteur); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_news_id) { return($this->id=$c_news_id); }
function set_datecrea($c_news_datecrea) { return($this->datecrea=$c_news_datecrea); }
function set_dateenvoi($c_news_dateenvoi) { return($this->dateenvoi=$c_news_dateenvoi); }
function set_theme($c_news_theme) { return($this->theme=$c_news_theme); }
function set_libelle($c_news_libelle) { return($this->libelle=$c_news_libelle); }
function set_html($c_news_html) { return($this->html=$c_news_html); }
function set_nbmail($c_news_nbmail) { return($this->nbmail=$c_news_nbmail); }
function set_nbrecu($c_news_nbrecu) { return($this->nbrecu=$c_news_nbrecu); }
function set_test($c_news_test) { return($this->test=$c_news_test); }
function set_expediteur($c_news_expediteur) { return($this->expediteur=$c_news_expediteur); }
function set_statut($c_news_statut) { return($this->statut=$c_news_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("news_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("news_statut"); }
//
function getTable() { return("newsletter"); }
function getClasse() { return("newsletter"); }
function getPrefix() { return(""); }
function getDisplay() { return("theme"); }
function getAbstract() { return("libelle"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter/list_newsletter.php", "w");
	$listContent = "<"."?php
include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."_SERVER['PHP_SELF']))
	require_once('include/bo/cms/prepend.'.\$"."_SERVER['PHP_SELF']);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter/maj_newsletter.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter/show_newsletter.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter/rss_newsletter.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter/xml_newsletter.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter/xmlxls_newsletter.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter/export_newsletter.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/newsletter/import_newsletter.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}

dirExists('/custom/newsletter/');
dirExists('/frontoffice/newsletter/template/');
}
?>