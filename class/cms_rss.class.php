<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if ($db!=NULL	&&	!ispatched('cms_rss')){
	$rs = $db->Execute('DESCRIBE `cms_rss`');
	if (isset($rs->_numOfRows)){
		if ($rs->_numOfRows == 16){
			$rs = $db->Execute('ALTER TABLE `cms_rss` ADD `rss_url_objet` VARCHAR( 255 ) NULL AFTER `rss_classe` ;');
		}
		elseif ($rs->_numOfRows == 15){
			$rs = $db->Execute('ALTER TABLE `cms_rss` ADD `rss_num_item` VARCHAR( 255 ) NULL AFTER `rss_order_by` ;');
		}
		elseif ($rs->_numOfRows == 14){
			$rs = $db->Execute('ALTER TABLE `cms_rss` ADD `rss_order_by` VARCHAR( 255 ) NULL AFTER `rss_value_where` ;');
			$rs = $db->Execute('ALTER TABLE `cms_rss` ADD `rss_num_item` VARCHAR( 255 ) NULL AFTER `rss_order_by` ;');
		}
	}
}
/*======================================

objet de BDD cms_rss :: class cms_rss

SQL mySQL:

DROP TABLE IF EXISTS cms_rss;
CREATE TABLE cms_rss
(
	rss_id			int (11) PRIMARY KEY not null,
	rss_title			varchar (255),
	rss_description			varchar (1024),
	rss_image_url			varchar (255),
	rss_image_title			varchar (255),
	rss_managing_editor			int (11),
	rss_web_master			int (11),
	rss_guid_base_url			varchar (255),
	rss_param_where			varchar (255),
	rss_value_where			varchar (255),
	rss_order_by			varchar (255),
	rss_num_item			varchar (255),
	rss_classe			int (11),
	rss_url_objet			varchar (255),
	rss_dtcrea			date,
	rss_dtmod			date,
	rss_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_rss
CREATE TABLE cms_rss
(
	rss_id			number (11) constraint rss_pk PRIMARY KEY not null,
	rss_title			varchar2 (255),
	rss_description			varchar2 (1024),
	rss_image_url			varchar2 (255),
	rss_image_title			varchar2 (255),
	rss_managing_editor			number (11),
	rss_web_master			number (11),
	rss_guid_base_url			varchar2 (255),
	rss_param_where			varchar2 (255),
	rss_value_where			varchar2 (255),
	rss_order_by			varchar2 (255),
	rss_num_item			varchar2 (255),
	rss_classe			number (11),
	rss_url_objet			varchar2 (255),
	rss_dtcrea			date,
	rss_dtmod			date,
	rss_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class	name="cms_rss"
		libelle="CMS RSS"
		prefix="rss"
		display="title"
		abstract="description" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" />

<!-- Source des commentaires : spécification du format RSS 2.01 
http://www.rssboard.org/rss-specification#ltguidgtSubelementOfLtitemgt
-->

<!-- ********************************************************************** -->
<!-- Elements obligatoires                                                  -->
<!-- ********************************************************************** -->

<!-- <title> Définit le titre du flux.
The name of the channel.
It's how people refer to your service.
If you have an HTML website that contains the same information as your RSS file, the title of your channel should be the same as the title of your website.
-->
<item	name	= "title"
		libelle	= "title"
		type	= "varchar"
		length	= "255"
		list	= "true"
		oblig	= "true"
		order	= "true" />

<!-- <description> Décrit succinctement le flux.
Phrase or sentence describing the channel.
-->
<item	name	= "description"
		libelle	= "description"
		type	= "varchar"
		length	= "1024"
		list	= "false"
		oblig	= "true"
		order	= "true"
		option	= "textarea" />

<!-- <link > Définit l'URL du site correspondant au flux.
The URL to the HTML website corresponding to the channel.
-->
<!-- Cette information est fournie par le site -->	


<!-- ********************************************************************** -->
<!-- Elements optionnels                                                    -->
<!-- ********************************************************************** -->

<!-- <image> is an optional sub-element of <channel>, which contains three required and three optional sub-elements.
<url> is the URL of a GIF, JPEG or PNG image that represents the channel.
<title> describes the image, it's used in the ALT attribute of the HTML <img> tag when the channel is rendered in HTML.
<link> is the URL of the site, when the channel is rendered, the image is a link to the site. (Note, in practice the image <title> and <link> should have the same value as the channel's <title> and <link>.
Optional elements include <width> and <height>, numbers, indicating the width and height of the image in pixels. <description> contains text that is included in the TITLE attribute of the link formed around the image in the HTML rendering.
Maximum value for width is 144, default value is 88.
Maximum value for height is 400, default value is 31.
-->
	<!-- <url> is the URL of a GIF, JPEG or PNG image that represents the channel. -->
	<item	name	= "image_url"
			libelle	= "image URL"
			type	= "varchar"
			length	= "255"
			list	= "false"
			order	= "true"
			option	= "file" >
		<option	type		= "image"
				maxwidth	= "144"
				maxheight	= "400"
				default_width = "88"
				default_height	= "31"
				/>	
	</item>
	
	<!-- <title> describes the image, it's used in the ALT attribute of the HTML <img> tag when the channel is rendered in HTML. -->
	<item	name	= "image_title"
			libelle	= "Titre de l'image"
			type	= "varchar"
			length	= "255"
			list	= "true"
			order	= "true" />
	
	<!-- <link> -->
	<!-- Cette information est fournie par le site -->	

<!-- <managingEditor>	Email address for person responsible for editorial content. -->
<item	name	= "managing_editor"
		libelle = "E-mail du responsable de l'édition"
		type	= "int"
		length	= "11"
		list	= "false"
		order	= "true"
		oblig	= "false"
		fkey	= "bo_users" />		
		
<!-- <webMaster>		Email address for person responsible for technical issues relating to channel. -->
<item	name	= "web_master"
		libelle = "E-mail du responsable du site"
		type	= "int"
		length	= "11"
		list	= "false"
		order	= "true"
		oblig	= "false"
		fkey	= "bo_users" />
		
<!-- ********************************************************************** -->
<!-- Elements spécifiques (ne faisant pas partie du standard RSS )          -->
<!-- ********************************************************************** -->
<item name="guid_base_url" libelle="URL de base pour le GUID" type="varchar" length="255" list="true" nohtml="true" />
<!-- <guid> stands for globally unique identifier. It's a string that uniquely identifies the item. When present, an aggregator may choose to use this string to determine if an item is new. -->


<item name="param_where" libelle="Paramètre WHERE" type="varchar" length="255" list="true" order="true"  />
<item name="value_where" libelle="Valeur WHERE" type="varchar" length="255" list="true" order="true"  />
<item name="order_by" libelle="Champ ORDER BY" type="varchar" length="255" list="true" order="true"  />
<item name="num_item" libelle="Champ LIMIT" type="varchar" length="255" list="true" order="true"  />

<item	name	= "classe"
		libelle = "Classe"
		type	= "int"
		length	= "11"
		list	= "true"
		order	= "true"
		oblig	= "true"
		fkey	= "classe" />		
		
		
<item name="num_url_objet" libelle="Lien Fiche" type="varchar" length="255" list="true" order="true"  option="link" rss="link"/>
<item name="dtcrea" libelle="Date de création" type="date" list="true" order="true" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 
</class> 


==========================================*/

class cms_rss
{
var $id;
var $title;
var $description;
var $image_url;
var $image_title;
var $managing_editor;
var $web_master;
var $guid_base_url;
var $param_where;
var $value_where;
var $order_by;
var $num_item;
var $classe;
var $url_objet;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class	name=\"cms_rss\"
		libelle=\"CMS RSS\"
		prefix=\"rss\"
		display=\"title\"
		abstract=\"description\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" />

<!-- Source des commentaires : spécification du format RSS 2.01 
http://www.rssboard.org/rss-specification#ltguidgtSubelementOfLtitemgt
-->

<!-- ********************************************************************** -->
<!-- Elements obligatoires                                                  -->
<!-- ********************************************************************** -->

<!-- <title> Définit le titre du flux.
The name of the channel.
It\'s how people refer to your service.
If you have an HTML website that contains the same information as your RSS file, the title of your channel should be the same as the title of your website.
-->
<item	name	= \"title\"
		libelle	= \"title\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
		oblig	= \"true\"
		order	= \"true\" />

<!-- <description> Décrit succinctement le flux.
Phrase or sentence describing the channel.
-->
<item	name	= \"description\"
		libelle	= \"description\"
		type	= \"varchar\"
		length	= \"1024\"
		list	= \"false\"
		oblig	= \"true\"
		order	= \"true\"
		option	= \"textarea\" />

<!-- <link > Définit l\'URL du site correspondant au flux.
The URL to the HTML website corresponding to the channel.
-->
<!-- Cette information est fournie par le site -->	


<!-- ********************************************************************** -->
<!-- Elements optionnels                                                    -->
<!-- ********************************************************************** -->

<!-- <image> is an optional sub-element of <channel>, which contains three required and three optional sub-elements.
<url> is the URL of a GIF, JPEG or PNG image that represents the channel.
<title> describes the image, it\'s used in the ALT attribute of the HTML <img> tag when the channel is rendered in HTML.
<link> is the URL of the site, when the channel is rendered, the image is a link to the site. (Note, in practice the image <title> and <link> should have the same value as the channel\'s <title> and <link>.
Optional elements include <width> and <height>, numbers, indicating the width and height of the image in pixels. <description> contains text that is included in the TITLE attribute of the link formed around the image in the HTML rendering.
Maximum value for width is 144, default value is 88.
Maximum value for height is 400, default value is 31.
-->
	<!-- <url> is the URL of a GIF, JPEG or PNG image that represents the channel. -->
	<item	name	= \"image_url\"
			libelle	= \"image URL\"
			type	= \"varchar\"
			length	= \"255\"
			list	= \"false\"
			order	= \"true\"
			option	= \"file\" >
		<option	type		= \"image\"
				maxwidth	= \"144\"
				maxheight	= \"400\"
				default_width = \"88\"
				default_height	= \"31\"
				/>	
	</item>
	
	<!-- <title> describes the image, it\'s used in the ALT attribute of the HTML <img> tag when the channel is rendered in HTML. -->
	<item	name	= \"image_title\"
			libelle	= \"Titre de l\'image\"
			type	= \"varchar\"
			length	= \"255\"
			list	= \"true\"
			order	= \"true\" />
	
	<!-- <link> -->
	<!-- Cette information est fournie par le site -->	

<!-- <managingEditor>	Email address for person responsible for editorial content. -->
<item	name	= \"managing_editor\"
		libelle = \"E-mail du responsable de l\'édition\"
		type	= \"int\"
		length	= \"11\"
		list	= \"false\"
		order	= \"true\"
		oblig	= \"false\"
		fkey	= \"bo_users\" />		
		
<!-- <webMaster>		Email address for person responsible for technical issues relating to channel. -->
<item	name	= \"web_master\"
		libelle = \"E-mail du responsable du site\"
		type	= \"int\"
		length	= \"11\"
		list	= \"false\"
		order	= \"true\"
		oblig	= \"false\"
		fkey	= \"bo_users\" />
		
<!-- ********************************************************************** -->
<!-- Elements spécifiques (ne faisant pas partie du standard RSS )          -->
<!-- ********************************************************************** -->
<item name=\"guid_base_url\" libelle=\"URL de base pour le GUID\" type=\"varchar\" length=\"255\" list=\"true\" nohtml=\"true\" />
<!-- <guid> stands for globally unique identifier. It\'s a string that uniquely identifies the item. When present, an aggregator may choose to use this string to determine if an item is new. -->


<item name=\"param_where\" libelle=\"Paramètre WHERE\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  />
<item name=\"value_where\" libelle=\"Valeur WHERE\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  />
<item name=\"order_by\" libelle=\"Champ ORDER BY\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  />
<item name=\"num_item\" libelle=\"Champ LIMIT\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  />

<item	name	= \"classe\"
		libelle = \"Classe\"
		type	= \"int\"
		length	= \"11\"
		list	= \"true\"
		order	= \"true\"
		oblig	= \"true\"
		fkey	= \"classe\" />		
		
<item name=\"url_objet\" libelle=\"Lien fiche\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"   option=\"link\" rss=\"link\"/>		

<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 
</class> ";

var $sMySql = "CREATE TABLE cms_rss
(
	rss_id			int (11) PRIMARY KEY not null,
	rss_title			varchar (255),
	rss_description			varchar (1024),
	rss_image_url			varchar (255),
	rss_image_title			varchar (255),
	rss_managing_editor			int (11),
	rss_web_master			int (11),
	rss_guid_base_url			varchar (255),
	rss_param_where			varchar (255),
	rss_value_where			varchar (255),
	rss_order_by			varchar (255),
	rss_num_item			varchar (255),
	rss_classe			int (11),
	rss_url_objet			varchar (255),
	rss_dtcrea			date,
	rss_dtmod			date,
	rss_statut			int (11) not null
)

";

// constructeur
function cms_rss($id=null)
{
	if (istable("cms_rss") == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id = -1;
		$this->title = "";
		$this->description = "";
		$this->image_url = "";
		$this->image_title = "";
		$this->managing_editor = -1;
		$this->web_master = -1;
		$this->guid_base_url = "";
		$this->param_where = "";
		$this->value_where = "";
		$this->order_by = "";
		$this->num_item = "";
		$this->classe = -1;
		$this->url_objet = "";
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Rss_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Rss_title", "text", "get_title", "set_title");
	$laListeChamps[]=new dbChamp("Rss_description", "text", "get_description", "set_description");
	$laListeChamps[]=new dbChamp("Rss_image_url", "text", "get_image_url", "set_image_url");
	$laListeChamps[]=new dbChamp("Rss_image_title", "text", "get_image_title", "set_image_title");
	$laListeChamps[]=new dbChamp("Rss_managing_editor", "entier", "get_managing_editor", "set_managing_editor");
	$laListeChamps[]=new dbChamp("Rss_web_master", "entier", "get_web_master", "set_web_master");
	$laListeChamps[]=new dbChamp("Rss_guid_base_url", "text", "get_guid_base_url", "set_guid_base_url");
	$laListeChamps[]=new dbChamp("Rss_param_where", "text", "get_param_where", "set_param_where");
	$laListeChamps[]=new dbChamp("Rss_value_where", "text", "get_value_where", "set_value_where");
	$laListeChamps[]=new dbChamp("Rss_order_by", "text", "get_order_by", "set_order_by");
	$laListeChamps[]=new dbChamp("Rss_num_item", "text", "get_num_item", "set_num_item");
	$laListeChamps[]=new dbChamp("Rss_classe", "entier", "get_classe", "set_classe");
	$laListeChamps[]=new dbChamp("Rss_url_objet", "text", "get_url_objet", "set_url_objet");
	$laListeChamps[]=new dbChamp("Rss_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Rss_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Rss_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_title() { return($this->title); }
function get_description() { return($this->description); }
function get_image_url() { return($this->image_url); }
function get_image_title() { return($this->image_title); }
function get_managing_editor() { return($this->managing_editor); }
function get_web_master() { return($this->web_master); }
function get_guid_base_url() { return($this->guid_base_url); }
function get_param_where() { return($this->param_where); }
function get_value_where() { return($this->value_where); }
function get_order_by() { return($this->order_by); }
function get_num_item() { return($this->num_item); }
function get_classe() { return($this->classe); }
function get_url_objet() { return($this->url_objet); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_rss_id) { return($this->id=$c_rss_id); }
function set_title($c_rss_title) { return($this->title=$c_rss_title); }
function set_description($c_rss_description) { return($this->description=$c_rss_description); }
function set_image_url($c_rss_image_url) { return($this->image_url=$c_rss_image_url); }
function set_image_title($c_rss_image_title) { return($this->image_title=$c_rss_image_title); }
function set_managing_editor($c_rss_managing_editor) { return($this->managing_editor=$c_rss_managing_editor); }
function set_web_master($c_rss_web_master) { return($this->web_master=$c_rss_web_master); }
function set_guid_base_url($c_rss_guid_base_url) { return($this->guid_base_url=$c_rss_guid_base_url); }
function set_param_where($c_rss_param_where) { return($this->param_where=$c_rss_param_where); }
function set_value_where($c_rss_value_where) { return($this->value_where=$c_rss_value_where); }
function set_order_by($c_rss_order_by) { return($this->order_by=$c_rss_order_by); }
function set_num_item($c_rss_num_item) { return($this->num_item=$c_rss_num_item); }
function set_classe($c_rss_classe) { return($this->classe=$c_rss_classe); }
function set_url_objet($c_rss_url_objet) { return($this->url_objet=$c_rss_url_objet); }
function set_dtcrea($c_rss_dtcrea) { return($this->dtcrea=$c_rss_dtcrea); }
function set_dtmod($c_rss_dtmod) { return($this->dtmod=$c_rss_dtmod); }
function set_statut($c_rss_statut) { return($this->statut=$c_rss_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("rss_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("rss_statut"); }
//
function getTable() { return("cms_rss"); }
function getClasse() { return("cms_rss"); }
function getDisplay() { return("title"); }
function getAbstract() { return("description"); }


} //class



// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss/list_cms_rss.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss/maj_cms_rss.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss/show_cms_rss.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss/rss_cms_rss.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss/xml_cms_rss.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss/export_cms_rss.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_rss/import_cms_rss.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>