<?php

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_queue.class.php')  && (strpos(__FILE__,'/include/bo/class/news_queue.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_queue.class.php');
}else{
/*======================================

objet de BDD news_queue :: class news_queue

SQL mySQL:

DROP TABLE IF EXISTS news_queue;
CREATE TABLE news_queue
(
	news_id			int (11) PRIMARY KEY not null,
	news_inscrit			int (11),
	news_newsletter			int (11),
	news_to			varchar (256),
	news_from			varchar (256),
	news_replyto			varchar (256),
	news_subject			varchar (256),
	news_files			text,
	news_headers			text,
	news_html			text,
	news_date_queue			timestamp,
	news_date_send			timestamp,
	news_statut			int (11) not null
)

SQL Oracle:

DROP TABLE news_queue
CREATE TABLE news_queue
(
	news_id			number (11) constraint news_pk PRIMARY KEY not null,
	news_inscrit			number (11),
	news_newsletter			number (11),
	news_to			varchar2 (256),
	news_from			varchar2 (256),
	news_replyto			varchar2 (256),
	news_subject			varchar2 (256),
	news_files			text,
	news_headers			text,
	news_html			text,
	news_date_queue			timestamp,
	news_date_send			timestamp,
	news_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_queue" libelle="Envoie en cours" prefix="news" display="news_inscrit" abstract="newsletter" >

<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" />

<item name="inscrit" libelle="Inscrit"  type="int" length="11"   order="true" list="true" fkey="news_inscrit"  />

<item name="newsletter" libelle="Newsletter"  type="int" length="11" order="true" list="true" fkey="newsletter"  />

<item name="to" libelle="To"  type="varchar" length="256"   order="true" list="true"  />

<item name="from" libelle="From"  type="varchar" length="256"   order="true" list="true"  /> 

<item name="replyto" libelle="Reply-to"  type="varchar" length="256"   order="true" list="true"  /> 

<item name="subject" libelle="Subject"  type="varchar" length="256"   order="true" list="true"  /> 

<item name="files" libelle="Attached files"  type="text" order="true" list="true"  />

<item name="headers" libelle="Headers"  type="text" order="true" list="true"  />

<item name="html" libelle="HTML"  type="text" order="true" list="true"  />

<item name="date_queue" libelle="date" type="timestamp" list="true" order="true" />

<item name="date_send" libelle="date" type="timestamp" list="true" order="true" />

<item name="statut" libelle="statut" type="int" length="11" notnull="true" default="DEF_ID_STATUT_LIGNE" order="true" list="true" >
<option type="value" value="1" libelle="en attente" />
<option type="value" value="4" libelle="envoyé" />
<option type="value" value="5" libelle="annulé" />
</item>


<langpack lang="fr">
<norecords>Pas d'asso inscrit/newsletter à envoyer</norecords>
</langpack>
</class>


==========================================*/

class news_queue
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $inscrit;
var $newsletter;
var $to;
var $from;
var $replyto;
var $subject;
var $files;
var $headers;
var $html;
var $date_queue;
var $date_send;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_queue\" libelle=\"Envoie en cours\" prefix=\"news\" display=\"news_inscrit\" abstract=\"newsletter\" >

<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" />

<item name=\"inscrit\" libelle=\"Inscrit\"  type=\"int\" length=\"11\"   order=\"true\" list=\"true\" fkey=\"news_inscrit\"  />

<item name=\"newsletter\" libelle=\"Newsletter\"  type=\"int\" length=\"11\" order=\"true\" list=\"true\" fkey=\"newsletter\"  />

<item name=\"to\" libelle=\"To\"  type=\"varchar\" length=\"256\"   order=\"true\" list=\"true\"  />

<item name=\"from\" libelle=\"From\"  type=\"varchar\" length=\"256\"   order=\"true\" list=\"true\"  /> 

<item name=\"replyto\" libelle=\"Reply-to\"  type=\"varchar\" length=\"256\"   order=\"true\" list=\"true\"  /> 

<item name=\"subject\" libelle=\"Subject\"  type=\"varchar\" length=\"256\"   order=\"true\" list=\"true\"  /> 

<item name=\"files\" libelle=\"Attached files\"  type=\"text\" order=\"true\" list=\"true\"  />

<item name=\"headers\" libelle=\"Headers\"  type=\"text\" order=\"true\" list=\"true\"  />

<item name=\"html\" libelle=\"HTML\"  type=\"text\" order=\"true\" list=\"true\"  />

<item name=\"date_queue\" libelle=\"date\" type=\"timestamp\" list=\"true\" order=\"true\" />

<item name=\"date_send\" libelle=\"date\" type=\"timestamp\" list=\"true\" order=\"true\" />

<item name=\"statut\" libelle=\"statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_ID_STATUT_LIGNE\" order=\"true\" list=\"true\" >
<option type=\"value\" value=\"1\" libelle=\"en attente\" />
<option type=\"value\" value=\"4\" libelle=\"envoyé\" />
<option type=\"value\" value=\"5\" libelle=\"annulé\" />
</item>


<langpack lang=\"fr\">
<norecords>Pas d'asso inscrit/newsletter à envoyer</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE news_queue
(
	news_id			int (11) PRIMARY KEY not null,
	news_inscrit			int (11),
	news_newsletter			int (11),
	news_to			varchar (256),
	news_from			varchar (256),
	news_replyto			varchar (256),
	news_subject			varchar (256),
	news_files			text,
	news_headers			text,
	news_html			text,
	news_date_queue			timestamp,
	news_date_send			timestamp,
	news_statut			int (11) not null
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
		$this->inscrit = -1;
		$this->newsletter = -1;
		$this->to = "";
		$this->from = "";
		$this->replyto = "";
		$this->subject = "";
		$this->files = "";
		$this->headers = "";
		$this->html = "";
		$this->date_queue = date('Y-m-d H:i:s');
		$this->date_send = date('Y-m-d H:i:s');
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
	$laListeChamps[]=new dbChamp("News_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("News_inscrit", "entier", "get_inscrit", "set_inscrit");
	$laListeChamps[]=new dbChamp("News_newsletter", "entier", "get_newsletter", "set_newsletter");
	$laListeChamps[]=new dbChamp("News_to", "text", "get_to", "set_to");
	$laListeChamps[]=new dbChamp("News_from", "text", "get_from", "set_from");
	$laListeChamps[]=new dbChamp("News_replyto", "text", "get_replyto", "set_replyto");
	$laListeChamps[]=new dbChamp("News_subject", "text", "get_subject", "set_subject");
	$laListeChamps[]=new dbChamp("News_files", "text", "get_files", "set_files");
	$laListeChamps[]=new dbChamp("News_headers", "text", "get_headers", "set_headers");
	$laListeChamps[]=new dbChamp("News_html", "text", "get_html", "set_html");
	$laListeChamps[]=new dbChamp("News_date_queue", "date_formatee_timestamp", "get_date_queue", "set_date_queue");
	$laListeChamps[]=new dbChamp("News_date_send", "date_formatee_timestamp", "get_date_send", "set_date_send");
	$laListeChamps[]=new dbChamp("News_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_inscrit() { return($this->inscrit); }
function get_newsletter() { return($this->newsletter); }
function get_to() { return($this->to); }
function get_from() { return($this->from); }
function get_replyto() { return($this->replyto); }
function get_subject() { return($this->subject); }
function get_files() { return($this->files); }
function get_headers() { return($this->headers); }
function get_html() { return($this->html); }
function get_date_queue() { return($this->date_queue); }
function get_date_send() { return($this->date_send); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_news_id) { return($this->id=$c_news_id); }
function set_inscrit($c_news_inscrit) { return($this->inscrit=$c_news_inscrit); }
function set_newsletter($c_news_newsletter) { return($this->newsletter=$c_news_newsletter); }
function set_to($c_news_to) { return($this->to=$c_news_to); }
function set_from($c_news_from) { return($this->from=$c_news_from); }
function set_replyto($c_news_replyto) { return($this->replyto=$c_news_replyto); }
function set_subject($c_news_subject) { return($this->subject=$c_news_subject); }
function set_files($c_news_files) { return($this->files=$c_news_files); }
function set_headers($c_news_headers) { return($this->headers=$c_news_headers); }
function set_html($c_news_html) { return($this->html=$c_news_html); }
function set_date_queue($c_news_date_queue) { return($this->date_queue=$c_news_date_queue); }
function set_date_send($c_news_date_send) { return($this->date_send=$c_news_date_send); }
function set_statut($c_news_statut) { return($this->statut=$c_news_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("news_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("news_statut"); }
//
function getTable() { return("news_queue"); }
function getClasse() { return("news_queue"); }
function getPrefix() { return(""); }
function getDisplay() { return("news_inscrit"); }
function getAbstract() { return("newsletter"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue/list_news_queue.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue/maj_news_queue.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue/show_news_queue.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue/rss_news_queue.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue/xml_news_queue.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue/xlsx_news_queue.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue/export_news_queue.php", "w");
	$exportContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_queue/import_news_queue.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>