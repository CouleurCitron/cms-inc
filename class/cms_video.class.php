<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_video.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_video.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_video.class.php');
}else{
/*======================================

objet de BDD cms_video :: class cms_video

SQL mySQL:

DROP TABLE IF EXISTS cms_video;
CREATE TABLE cms_video
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_type			int (2),
	cms_title			varchar (255),
	cms_description			text,
	cms_thumbnail_loc			varchar (255),
	cms_file			varchar (255),
	cms_youtube			text,
	cms_content_loc			text,
	cms_player_loc			text,
	cms_duration			varchar (255),
	cms_tag			varchar (255),
	cms_category			varchar (255),
	cms_family_friendly			int (2),
	cms_cms_site			int (11),
	cms_statut			int not null
)

SQL Oracle:

DROP TABLE cms_video
CREATE TABLE cms_video
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_type			number (2),
	cms_title			varchar2 (255),
	cms_description			text,
	cms_thumbnail_loc			varchar2 (255),
	cms_file			varchar2 (255),
	cms_youtube			text,
	cms_content_loc			text,
	cms_player_loc			text,
	cms_duration			varchar2 (255),
	cms_tag			varchar2 (255),
	cms_category			varchar2 (255),
	cms_family_friendly			number (2),
	cms_cms_site			number (11),
	cms_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_video" prefix="cms" display="title" abstract="title">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assovideopage" />



<item name="type" libelle="Type de vidéo" type="int" length="2" default="1"   list="true" order="true" nohtml="true" option="enum" >
<option type="value" value="1" libelle="CMS" />
<option type="value" value="2" libelle="Brique vidéo" />
<option type="value" value="3" libelle="Fichier vidéo / diapo" />
<option type="value" value="4" libelle="Youtube" />
</item>

<item name="title" libelle="Title" type="varchar" length="255" list="true" order="true" nohtml="true" />

<item name="description" libelle="Description" type="text" list="false" order="false" option="textarea" nohtml="true" />

<item name="thumbnail_loc" libelle="Vignette illustrant la vidéo" type="varchar" length="255" list="true" order="true" option="file"   />

<item name="file" libelle="Fichier source" type="varchar" length="255" list="false" order="false" option="file" /> 

<item name="youtube" libelle="Code Youtube" type="text" list="false" order="false" option="textarea" nohtml="true" displayif="type" >
<option type="if" item="type" value="4" />
</item>

<item name="content_loc" libelle="Location (format .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv)" type="text"   list="true" order="true" nohtml="true" option="textarea" />



<item name="player_loc" libelle="Player Flash" type="text"  list="true" order="true" nohtml="true" option="textarea" />

<item name="duration" libelle="Durée de la vidéo (en secondes)" type="varchar" length="255" list="false" order="false" nohtml="true" />


<item name="tag" libelle="Tags associés à la vidéo (séparé par des virgules" type="varchar" length="255" list="false" order="false" nohtml="true" />

<item name="category" libelle="Catégorie de la vidéo" type="varchar" length="255" list="false" order="false" nohtml="true" />

<item name="family_friendly" libelle="Vidéo uniquement accessible aux utilisateurs ayant désactivé SafeSearch" type="int" length="2" default="1" option="bool" list="false" order="false" nohtml="true" />





<item name="cms_site" libelle="Mini-site" type="int" length="11" default="1"  list="false" order="false" nohtml="true" fkey="cms_site" />

<item name="statut" type="int" notnull="true" default="DEF_CODE_STATUT_DEFAUT"  list="true" order="true" />
</class>


==========================================*/

class cms_video
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $type;
var $title;
var $description;
var $thumbnail_loc;
var $file;
var $youtube;
var $content_loc;
var $player_loc;
var $duration;
var $tag;
var $category;
var $family_friendly;
var $cms_site;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_video\" prefix=\"cms\" display=\"title\" abstract=\"title\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assovideopage\" />



<item name=\"type\" libelle=\"Type de vidéo\" type=\"int\" length=\"2\" default=\"1\"   list=\"true\" order=\"true\" nohtml=\"true\" option=\"enum\" >
<option type=\"value\" value=\"1\" libelle=\"CMS\" />
<option type=\"value\" value=\"2\" libelle=\"Brique vidéo\" />
<option type=\"value\" value=\"3\" libelle=\"Fichier vidéo / diapo\" />
<option type=\"value\" value=\"4\" libelle=\"Youtube\" />
</item>

<item name=\"title\" libelle=\"Title\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />

<item name=\"description\" libelle=\"Description\" type=\"text\" list=\"false\" order=\"false\" option=\"textarea\" nohtml=\"true\" />

<item name=\"thumbnail_loc\" libelle=\"Vignette illustrant la vidéo\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" option=\"file\"   />

<item name=\"file\" libelle=\"Fichier source\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" option=\"file\" /> 

<item name=\"youtube\" libelle=\"Code Youtube\" type=\"text\" list=\"false\" order=\"false\" option=\"textarea\" nohtml=\"true\" displayif=\"type\" >
<option type=\"if\" item=\"type\" value=\"4\" />
</item>

<item name=\"content_loc\" libelle=\"Location (format .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv)\" type=\"text\"   list=\"true\" order=\"true\" nohtml=\"true\" option=\"textarea\" />



<item name=\"player_loc\" libelle=\"Player Flash\" type=\"text\"  list=\"true\" order=\"true\" nohtml=\"true\" option=\"textarea\" />

<item name=\"duration\" libelle=\"Durée de la vidéo (en secondes)\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" />


<item name=\"tag\" libelle=\"Tags associés à la vidéo (séparé par des virgules\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" />

<item name=\"category\" libelle=\"Catégorie de la vidéo\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" />

<item name=\"family_friendly\" libelle=\"Vidéo uniquement accessible aux utilisateurs ayant désactivé SafeSearch\" type=\"int\" length=\"2\" default=\"1\" option=\"bool\" list=\"false\" order=\"false\" nohtml=\"true\" />





<item name=\"cms_site\" libelle=\"Mini-site\" type=\"int\" length=\"11\" default=\"1\"  list=\"false\" order=\"false\" nohtml=\"true\" fkey=\"cms_site\" />

<item name=\"statut\" type=\"int\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\"  list=\"true\" order=\"true\" />
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_video
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_type			int (2),
	cms_title			varchar (255),
	cms_description			text,
	cms_thumbnail_loc			varchar (255),
	cms_file			varchar (255),
	cms_youtube			text,
	cms_content_loc			text,
	cms_player_loc			text,
	cms_duration			varchar (255),
	cms_tag			varchar (255),
	cms_category			varchar (255),
	cms_family_friendly			int (2),
	cms_cms_site			int (11),
	cms_statut			int not null
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
		$this->type = 1;
		$this->title = "";
		$this->description = "";
		$this->thumbnail_loc = "";
		$this->file = "";
		$this->youtube = "";
		$this->content_loc = "";
		$this->player_loc = "";
		$this->duration = "";
		$this->tag = "";
		$this->category = "";
		$this->family_friendly = 1;
		$this->cms_site = 1;
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
	$laListeChamps[]=new dbChamp("Cms_type", "entier", "get_type", "set_type");
	$laListeChamps[]=new dbChamp("Cms_title", "text", "get_title", "set_title");
	$laListeChamps[]=new dbChamp("Cms_description", "text", "get_description", "set_description");
	$laListeChamps[]=new dbChamp("Cms_thumbnail_loc", "text", "get_thumbnail_loc", "set_thumbnail_loc");
	$laListeChamps[]=new dbChamp("Cms_file", "text", "get_file", "set_file");
	$laListeChamps[]=new dbChamp("Cms_youtube", "text", "get_youtube", "set_youtube");
	$laListeChamps[]=new dbChamp("Cms_content_loc", "text", "get_content_loc", "set_content_loc");
	$laListeChamps[]=new dbChamp("Cms_player_loc", "text", "get_player_loc", "set_player_loc");
	$laListeChamps[]=new dbChamp("Cms_duration", "text", "get_duration", "set_duration");
	$laListeChamps[]=new dbChamp("Cms_tag", "text", "get_tag", "set_tag");
	$laListeChamps[]=new dbChamp("Cms_category", "text", "get_category", "set_category");
	$laListeChamps[]=new dbChamp("Cms_family_friendly", "entier", "get_family_friendly", "set_family_friendly");
	$laListeChamps[]=new dbChamp("Cms_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_type() { return($this->type); }
function get_title() { return($this->title); }
function get_description() { return($this->description); }
function get_thumbnail_loc() { return($this->thumbnail_loc); }
function get_file() { return($this->file); }
function get_youtube() { return($this->youtube); }
function get_content_loc() { return($this->content_loc); }
function get_player_loc() { return($this->player_loc); }
function get_duration() { return($this->duration); }
function get_tag() { return($this->tag); }
function get_category() { return($this->category); }
function get_family_friendly() { return($this->family_friendly); }
function get_cms_site() { return($this->cms_site); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_type($c_cms_type) { return($this->type=$c_cms_type); }
function set_title($c_cms_title) { return($this->title=$c_cms_title); }
function set_description($c_cms_description) { return($this->description=$c_cms_description); }
function set_thumbnail_loc($c_cms_thumbnail_loc) { return($this->thumbnail_loc=$c_cms_thumbnail_loc); }
function set_file($c_cms_file) { return($this->file=$c_cms_file); }
function set_youtube($c_cms_youtube) { return($this->youtube=$c_cms_youtube); }
function set_content_loc($c_cms_content_loc) { return($this->content_loc=$c_cms_content_loc); }
function set_player_loc($c_cms_player_loc) { return($this->player_loc=$c_cms_player_loc); }
function set_duration($c_cms_duration) { return($this->duration=$c_cms_duration); }
function set_tag($c_cms_tag) { return($this->tag=$c_cms_tag); }
function set_category($c_cms_category) { return($this->category=$c_cms_category); }
function set_family_friendly($c_cms_family_friendly) { return($this->family_friendly=$c_cms_family_friendly); }
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
function getTable() { return("cms_video"); }
function getClasse() { return("cms_video"); }
function getDisplay() { return("title"); }
function getAbstract() { return("title"); }


} //class


}
?>