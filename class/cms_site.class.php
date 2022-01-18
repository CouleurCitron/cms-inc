<?php
/* [Begin patch] */

//
//if(in_array('cms_site', $_SESSION['patches'])){
//    $key = array_search('cms_site', $_SESSION['patches']);
//    
//    unset($_SESSION['patches'][$key]);
//}


// patch de migration
if (!ispatched('cms_site')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_site`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];			
			if ($rs->fields["Field"]=='cms_isminisite'){					
				if($rs->fields["Type"] == "int(1)"){					
					$rs = $db->Execute('ALTER TABLE `cms_site` CHANGE `cms_isminisite` `cms_isminisite` INT( 11 ) NOT NULL DEFAULT \'-1\';');
					$rs = $db->Execute('UPDATE `cms_site` SET `cms_isminisite` = -1;');
					
				}
			}
			$rs->MoveNext();			
		}	
		
                
		if (!in_array('cms_author', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_site` ADD `cms_author` VARCHAR( 255 ) NOT NULL AFTER `cms_robots` ;");
		}	
		if (!in_array('cms_https', $names)) {
			$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_https` INT( 2 ) NOT NULL DEFAULT \'0\' AFTER `cms_doctype` ; ');
		}
                if (!in_array('cms_viewport', $names)) {
			$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_viewport` TEXT AFTER `cms_geoposition` ; ');
		}//cms_jquery_version
                if (!in_array('cms_jquery_version', $names)) {
			$rs = $db->Execute(' ALTER TABLE  `cms_site` ADD  `cms_jquery_version` INT NOT NULL DEFAULT  \'1\' AFTER  `cms_viewport` ; ');
		}
                
	}
	
	// patch old style
	$rs = $db->Execute('DESCRIBE `cms_site`');
	
	if ($rs->_numOfRows == 13){
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_robots` VARCHAR( 255 ) NOT NULL AFTER `cms_langue` , ADD `cms_copyright` VARCHAR( 255 ) NOT NULL AFTER `cms_robots` , ADD `cms_georegion` VARCHAR( 255 ) NOT NULL AFTER `cms_copyright` , ADD `cms_geoplacename` VARCHAR( 255 ) NOT NULL AFTER `cms_georegion` , ADD `cms_geoposition` VARCHAR( 255 ) NOT NULL AFTER `cms_geoplacename` ; ');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_https` INT( 2 ) NOT NULL DEFAULT \'0\' AFTER `cms_doctype` ; ');
	}
	elseif ($rs->_numOfRows == 11){
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_doctype` INT( 2 ) NULL DEFAULT \'0\' AFTER `cms_rep`, ADD `cms_encod` INT( 2 ) NULL DEFAULT \'0\' AFTER `cms_doctype` ;');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_robots` VARCHAR( 255 ) NOT NULL AFTER `cms_langue` , ADD `cms_copyright` VARCHAR( 255 ) NOT NULL AFTER `cms_robots` , ADD `cms_georegion` VARCHAR( 255 ) NOT NULL AFTER `cms_copyright` , ADD `cms_geoplacename` VARCHAR( 255 ) NOT NULL AFTER `cms_georegion` , ADD `cms_geoposition` VARCHAR( 255 ) NOT NULL AFTER `cms_geoplacename` ; ');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_https` INT( 2 ) NOT NULL DEFAULT \'0\' AFTER `cms_doctype` ; ');
	} 
	elseif ($rs->_numOfRows == 10){
		$rs = $db->Execute('ALTER TABLE `cms_site` ADD `cms_statut` INT( 11 ) NOT NULL DEFAULT \'4\' AFTER `cms_langue` ;');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_doctype` INT( 2 ) NULL DEFAULT \'0\' AFTER `cms_rep`, ADD `cms_encod` INT( 2 ) NULL DEFAULT \'0\' AFTER `cms_doctype` ;');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_robots` VARCHAR( 255 ) NOT NULL AFTER `cms_langue` , ADD `cms_copyright` VARCHAR( 255 ) NOT NULL AFTER `cms_robots` , ADD `cms_georegion` VARCHAR( 255 ) NOT NULL AFTER `cms_copyright` , ADD `cms_geoplacename` VARCHAR( 255 ) NOT NULL AFTER `cms_georegion` , ADD `cms_geoposition` VARCHAR( 255 ) NOT NULL AFTER `cms_geoplacename` ; ');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_https` INT( 2 ) NOT NULL DEFAULT \'0\' AFTER `cms_doctype` ; ');
	} 
	elseif ($rs->_numOfRows == 9){
		$rs = $db->Execute('ALTER TABLE `cms_site` ADD `cms_langue` INT( 11 ) AFTER `cms_rep` ;');
		$rs = $db->Execute('ALTER TABLE `cms_site` ADD `cms_statut` INT( 11 ) NOT NULL DEFAULT \'4\' AFTER `cms_langue` ;');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_doctype` INT( 2 ) NULL DEFAULT \'0\' AFTER `cms_rep`, ADD `cms_encod` INT( 2 ) NULL DEFAULT \'0\' AFTER `cms_doctype` ;');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_robots` VARCHAR( 255 ) NOT NULL AFTER `cms_langue` , ADD `cms_copyright` VARCHAR( 255 ) NOT NULL AFTER `cms_robots` , ADD `cms_georegion` VARCHAR( 255 ) NOT NULL AFTER `cms_copyright` , ADD `cms_geoplacename` VARCHAR( 255 ) NOT NULL AFTER `cms_georegion` , ADD `cms_geoposition` VARCHAR( 255 ) NOT NULL AFTER `cms_geoplacename` ; ');
		$rs = $db->Execute(' ALTER TABLE `cms_site` ADD `cms_https` INT( 2 ) NOT NULL DEFAULT \'0\' AFTER `cms_doctype` ; ');
	} 
}

/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_site.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_site.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_site.class.php');
}else{
/*======================================

objet de BDD cms_site :: class cms_site

SQL mySQL:

DROP TABLE IF EXISTS cms_site;
CREATE TABLE cms_site
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_name			varchar (255),
	cms_desc			varchar (1024),
	cms_url			varchar (512),
	cms_fonct			varchar (512),
	cms_isminisite			int (11),
	cms_widthpage			int (11) not null,
	cms_heightpage			int (11) not null,
	cms_rep			varchar (255),
	cms_encod			int (2),
	cms_doctype			int (2),
	cms_https			int (2),
	cms_langue			int (11),
	cms_robots			varchar (255),
	cms_author			varchar (255),
	cms_copyright			varchar (255),
	cms_georegion			varchar (255),
	cms_geoplacename			varchar (255),
	cms_geoposition			varchar (255),
	cms_viewport			text,
	cms_jquery_version			int (11),
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_site
CREATE TABLE cms_site
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_name			varchar2 (255),
	cms_desc			varchar2 (1024),
	cms_url			varchar2 (512),
	cms_fonct			varchar2 (512),
	cms_isminisite			number (11),
	cms_widthpage			number (11) not null,
	cms_heightpage			number (11) not null,
	cms_rep			varchar2 (255),
	cms_encod			number (2),
	cms_doctype			number (2),
	cms_https			number (2),
	cms_langue			number (11),
	cms_robots			varchar2 (255),
	cms_author			varchar2 (255),
	cms_copyright			varchar2 (255),
	cms_georegion			varchar2 (255),
	cms_geoplacename			varchar2 (255),
	cms_geoposition			varchar2 (255),
	cms_viewport			text,
	cms_jquery_version			number (11),
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_site" libelle="Sites" prefix="cms" display="name" abstract="rep">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" asso="cms_assolanguesite" />
<item name="name" libelle="Nom du site" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="desc" libelle="Description" type="varchar" length="1024" list="false" order="true" option="textarea"/>
<item name="url" libelle="URL" type="varchar" length="512" list="true" order="true"  nohtml="true" />
<item name="fonct" libelle="Fonctionnalités" type="varchar" length="512" list="true" order="true"  nohtml="true" />
<item name="isminisite" libelle="Site maître" type="int" length="11" default="-1" list="true" order="true" fkey="cms_site"  />
<item name="widthpage" libelle="Largeur" type="int" length="11" notnull="true" default="0" list="true" />
<item name="heightpage" libelle="Hauteur" type="int" length="11" notnull="true" default="0" list="true" />
<item name="rep" libelle="Répertoire" type="varchar" length="255" list="true" order="true"  nohtml="true" />

<item name="encod" libelle="Type d'encodage" type="int" length="2" list="true" order="true" option="enum" default="0">
<option type="value" value="0" libelle="ISO-8859-1" />
<option type="value" value="1" libelle="UTF-8" />
</item>

<item name="doctype" libelle="Document Type" type="int" length="2" list="true" order="true" option="enum" default="0">
<option type="value" value="0" libelle="XHTML 1.0 Transitional" />
<option type="value" value="1" libelle="XHTML 1.0 Strict" />
<option type="value" value="2" libelle="XHTML 1.1" />
<option type="value" value="3" libelle="XHTML 1.0 Frameset" />
<option type="value" value="4" libelle="XHTML Mobile 1.0" />
<option type="value" value="5" libelle="HTML 5" />
<option type="value" value="6" libelle="XHTML 2.0" />
<option type="value" value="7" libelle="XHTML 1.0 RDFa" />
</item>

<item name="https" libelle="Sécurisé" type="int" length="2" list="true" order="true" option="bool" default="0" />

<item name="langue" libelle="Langue" type="int" length="11" list="true" order="true" nohtml="true" fkey="cms_langue"/>

<item name="robots" libelle="Robots" type="varchar" length="255" list="false" order="false" nohtml="true" default="index, follow" />

<item name="author" libelle="Auteur" type="varchar" length="255" list="false" order="false" nohtml="true" default="" />

<item name="copyright" libelle="Copyright" type="varchar" length="255" list="false" order="false" nohtml="true" default="" />

<item name="georegion" libelle="Région" type="varchar" length="255" list="false" order="false" nohtml="true" default="FR-31" />

<item name="geoplacename" libelle="Placename" type="varchar" length="255" list="false" order="false" nohtml="true" default="Toulouse" />

<item name="geoposition" libelle="Position" type="varchar" length="255" list="false" order="false" nohtml="true" default="43.5522183477, 1.4900636673" />

<item name="viewport" libelle="Viewport" type="text" list="false" order="false" nohtml="true" default="width=device-width,initial-scale=1" option="textarea" />

<item name="jquery_version" type="int" length="11" list="true" order="true" fkey="cms_jquery_version" />


<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_ID_STATUT_LIGNE" list="true" order="true" /> 
<langpack lang="fr">
<norecords>Pas de mini-site à afficher</norecords>
</langpack>
</class>


==========================================*/

class cms_site
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $name;
var $desc;
var $url;
var $fonct;
var $isminisite;
var $widthpage;
var $heightpage;
var $rep;
var $encod;
var $doctype;
var $https;
var $langue;
var $robots;
var $author;
var $copyright;
var $georegion;
var $geoplacename;
var $geoposition;
var $viewport;
var $jquery_version;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_site\" libelle=\"Sites\" prefix=\"cms\" display=\"name\" abstract=\"rep\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" asso=\"cms_assolanguesite\" />
<item name=\"name\" libelle=\"Nom du site\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"desc\" libelle=\"Description\" type=\"varchar\" length=\"1024\" list=\"false\" order=\"true\" option=\"textarea\"/>
<item name=\"url\" libelle=\"URL\" type=\"varchar\" length=\"512\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"fonct\" libelle=\"Fonctionnalités\" type=\"varchar\" length=\"512\" list=\"true\" order=\"true\"  nohtml=\"true\" />
<item name=\"isminisite\" libelle=\"Site maître\" type=\"int\" length=\"11\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_site\"  />
<item name=\"widthpage\" libelle=\"Largeur\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"true\" />
<item name=\"heightpage\" libelle=\"Hauteur\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"true\" />
<item name=\"rep\" libelle=\"Répertoire\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  nohtml=\"true\" />

<item name=\"encod\" libelle=\"Type d'encodage\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" option=\"enum\" default=\"0\">
<option type=\"value\" value=\"0\" libelle=\"ISO-8859-1\" />
<option type=\"value\" value=\"1\" libelle=\"UTF-8\" />
</item>

<item name=\"doctype\" libelle=\"Document Type\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" option=\"enum\" default=\"0\">
<option type=\"value\" value=\"0\" libelle=\"XHTML 1.0 Transitional\" />
<option type=\"value\" value=\"1\" libelle=\"XHTML 1.0 Strict\" />
<option type=\"value\" value=\"2\" libelle=\"XHTML 1.1\" />
<option type=\"value\" value=\"3\" libelle=\"XHTML 1.0 Frameset\" />
<option type=\"value\" value=\"4\" libelle=\"XHTML Mobile 1.0\" />
<option type=\"value\" value=\"5\" libelle=\"HTML 5\" />
<option type=\"value\" value=\"6\" libelle=\"XHTML 2.0\" />
<option type=\"value\" value=\"7\" libelle=\"XHTML 1.0 RDFa\" />
</item>

<item name=\"https\" libelle=\"Sécurisé\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" option=\"bool\" default=\"0\" />

<item name=\"langue\" libelle=\"Langue\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" nohtml=\"true\" fkey=\"cms_langue\"/>

<item name=\"robots\" libelle=\"Robots\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" default=\"index, follow\" />

<item name=\"author\" libelle=\"Auteur\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" default=\"\" />

<item name=\"copyright\" libelle=\"Copyright\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" default=\"\" />

<item name=\"georegion\" libelle=\"Région\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" default=\"FR-31\" />

<item name=\"geoplacename\" libelle=\"Placename\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" default=\"Toulouse\" />

<item name=\"geoposition\" libelle=\"Position\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\" default=\"43.5522183477, 1.4900636673\" />

<item name=\"viewport\" libelle=\"Viewport\" type=\"text\" list=\"false\" order=\"false\" nohtml=\"true\" default=\"width=device-width,initial-scale=1\" option=\"textarea\" />

<item name=\"jquery_version\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_jquery_version\" />


<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_ID_STATUT_LIGNE\" list=\"true\" order=\"true\" /> 
<langpack lang=\"fr\">
<norecords>Pas de mini-site à afficher</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_site
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_name			varchar (255),
	cms_desc			varchar (1024),
	cms_url			varchar (512),
	cms_fonct			varchar (512),
	cms_isminisite			int (11),
	cms_widthpage			int (11) not null,
	cms_heightpage			int (11) not null,
	cms_rep			varchar (255),
	cms_encod			int (2),
	cms_doctype			int (2),
	cms_https			int (2),
	cms_langue			int (11),
	cms_robots			varchar (255),
	cms_author			varchar (255),
	cms_copyright			varchar (255),
	cms_georegion			varchar (255),
	cms_geoplacename			varchar (255),
	cms_geoposition			varchar (255),
	cms_viewport			text,
	cms_jquery_version			int (11),
	cms_statut			int (11) not null
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
		$this->name = "";
		$this->desc = "";
		$this->url = "";
		$this->fonct = "";
		$this->isminisite = -1;
		$this->widthpage = -1;
		$this->heightpage = -1;
		$this->rep = "";
		$this->encod = -1;
		$this->doctype = -1;
		$this->https = -1;
		$this->langue = -1;
		$this->robots = "index, follow";
		$this->author = "";
		$this->copyright = "";
		$this->georegion = "FR-31";
		$this->geoplacename = "Toulouse";
		$this->geoposition = "43.5522183477, 1.4900636673";
		$this->viewport = "width=device-width,initial-scale=1";
		$this->jquery_version = -1;
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
	$laListeChamps[]=new dbChamp("Cms_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_name", "text", "get_name", "set_name");
	$laListeChamps[]=new dbChamp("Cms_desc", "text", "get_desc", "set_desc");
	$laListeChamps[]=new dbChamp("Cms_url", "text", "get_url", "set_url");
	$laListeChamps[]=new dbChamp("Cms_fonct", "text", "get_fonct", "set_fonct");
	$laListeChamps[]=new dbChamp("Cms_isminisite", "entier", "get_isminisite", "set_isminisite");
	$laListeChamps[]=new dbChamp("Cms_widthpage", "entier", "get_widthpage", "set_widthpage");
	$laListeChamps[]=new dbChamp("Cms_heightpage", "entier", "get_heightpage", "set_heightpage");
	$laListeChamps[]=new dbChamp("Cms_rep", "text", "get_rep", "set_rep");
	$laListeChamps[]=new dbChamp("Cms_encod", "entier", "get_encod", "set_encod");
	$laListeChamps[]=new dbChamp("Cms_doctype", "entier", "get_doctype", "set_doctype");
	$laListeChamps[]=new dbChamp("Cms_https", "entier", "get_https", "set_https");
	$laListeChamps[]=new dbChamp("Cms_langue", "entier", "get_langue", "set_langue");
	$laListeChamps[]=new dbChamp("Cms_robots", "text", "get_robots", "set_robots");
	$laListeChamps[]=new dbChamp("Cms_author", "text", "get_author", "set_author");
	$laListeChamps[]=new dbChamp("Cms_copyright", "text", "get_copyright", "set_copyright");
	$laListeChamps[]=new dbChamp("Cms_georegion", "text", "get_georegion", "set_georegion");
	$laListeChamps[]=new dbChamp("Cms_geoplacename", "text", "get_geoplacename", "set_geoplacename");
	$laListeChamps[]=new dbChamp("Cms_geoposition", "text", "get_geoposition", "set_geoposition");
	$laListeChamps[]=new dbChamp("Cms_viewport", "text", "get_viewport", "set_viewport");
	$laListeChamps[]=new dbChamp("Cms_jquery_version", "entier", "get_jquery_version", "set_jquery_version");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_name() { return($this->name); }
function get_desc() { return($this->desc); }
function get_url() { return($this->url); }
function get_fonct() { return($this->fonct); }
function get_isminisite() { return($this->isminisite); }
function get_widthpage() { return($this->widthpage); }
function get_heightpage() { return($this->heightpage); }
function get_rep() { return($this->rep); }
function get_encod() { return($this->encod); }
function get_doctype() { return($this->doctype); }
function get_https() { return($this->https); }
function get_langue() { return($this->langue); }
function get_robots() { return($this->robots); }
function get_author() { return($this->author); }
function get_copyright() { return($this->copyright); }
function get_georegion() { return($this->georegion); }
function get_geoplacename() { return($this->geoplacename); }
function get_geoposition() { return($this->geoposition); }
function get_viewport() { return($this->viewport); }
function get_jquery_version() { return($this->jquery_version); }
function get_statut() { return($this->statut); }

function getWidthpage_site() { return($this->widthpage); }
function getHeightpage_site() { return($this->heightpage); }

// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_name($c_cms_name) { return($this->name=$c_cms_name); }
function set_desc($c_cms_desc) { return($this->desc=$c_cms_desc); }
function set_url($c_cms_url) { return($this->url=$c_cms_url); }
function set_fonct($c_cms_fonct) { return($this->fonct=$c_cms_fonct); }
function set_isminisite($c_cms_isminisite) { return($this->isminisite=$c_cms_isminisite); }
function set_widthpage($c_cms_widthpage) { return($this->widthpage=$c_cms_widthpage); }
function set_heightpage($c_cms_heightpage) { return($this->heightpage=$c_cms_heightpage); }
function set_rep($c_cms_rep) { return($this->rep=$c_cms_rep); }
function set_encod($c_cms_encod) { return($this->encod=$c_cms_encod); }
function set_doctype($c_cms_doctype) { return($this->doctype=$c_cms_doctype); }
function set_https($c_cms_https) { return($this->https=$c_cms_https); }
function set_langue($c_cms_langue) { return($this->langue=$c_cms_langue); }
function set_robots($c_cms_robots) { return($this->robots=$c_cms_robots); }
function set_author($c_cms_author) { return($this->author=$c_cms_author); }
function set_copyright($c_cms_copyright) { return($this->copyright=$c_cms_copyright); }
function set_georegion($c_cms_georegion) { return($this->georegion=$c_cms_georegion); }
function set_geoplacename($c_cms_geoplacename) { return($this->geoplacename=$c_cms_geoplacename); }
function set_geoposition($c_cms_geoposition) { return($this->geoposition=$c_cms_geoposition); }
function set_viewport($c_cms_viewport) { return($this->viewport=$c_cms_viewport); }
function set_jquery_version($c_cms_jquery_version) { return($this->jquery_version=$c_cms_jquery_version); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_site"); }
function getClasse() { return("cms_site"); }
function getPrefix() { return(""); }
function getDisplay() { return("name"); }
function getAbstract() { return("rep"); }


} //class


// liste des sites
function listSite($sTypeSite) {

    global $db;
    
    $oSite = new Cms_site();
    unset($oSite); // force les inits de table

    $result = array();
    
    $sql = " SELECT * ";
    $sql.= " FROM cms_site";

    if ($sTypeSite == "ALL") {
        // rien : tous les sites
    } else if ($sTypeSite == "MINI") {
        $sql.= " WHERE cms_isminisite = 1";
    } else if ($sTypeSite == "PRINCIPAL") {
        $sql.= " WHERE cms_isminisite = 0";        
    }

    $sql.= " ORDER BY cms_id ASC";
    if (DEF_BDD != "ORACLE") $sql.= ";";

    $rs = $db->Execute($sql);        
    
    if($rs) {
        while(!$rs->EOF) {
            $oCms_site = new Cms_site(intval($rs->fields[n('cms_id')]));
            array_push($result, $oCms_site);
            $rs->MoveNext();
        }
    } else {
        echo "<br />Erreur de fonctionnement interne";
        if(DEF_MODE_DEBUG==true) {
            echo "<br />cms_site.class.php > listSite";
            echo "<br /><strong>".$sql."</strong>";
        }
        error_log($_SERVER['PHP_SELF']);
        error_log('erreur lors de l\'execution de la requete');
        error_log($sql);
        error_log($db->ErrorMsg());
        error_log($_SERVER['PHP_SELF']);
    }
    $rs->Close();
    return $result;
}
    
// enregistrement d'un lment site
function storeSite($oCms_site)
{
    $eIdForm = getCount("cms_site", "*", "cms_id", $oCms_site->getcms_id());

    if ($eIdForm == 0) {
        // nouvelle valeur de cl
        $oCms_site->setcms_id(getNextVal("cms_site", "cms_id"));
    
        // INSERT
        $result = $oCms_site->insert();
        
    } else {
        // UPDATE
        $result = $oCms_site->update();
    }

    return $result;
}

// fonction indiquant si le site est un mini site
function isMinisite($id)
{
    if ($id != "") {
    
        $oSite = new Cms_site($id);
        
        $result = $oSite->get_isminisite();
        
    } else $result=0;
    
    return $result;
    
}

// cration des rpertoires d'un mini site
function create_rep_minisite($sRepSite){
    // dupliquer les feuilles de style
    $sRep_css = $_SERVER['DOCUMENT_ROOT']."/custom/css/";
    copy($sRep_css."fo.css", $sRep_css."fo_".strtolower($sRepSite).".css");
    copy($sRep_css."fo.css", $sRep_css."fo_".strtolower($sRepSite)."_spaw.css");

    // - rpertoire content :: /content/site/
    $sRep_content = $_SERVER['DOCUMENT_ROOT']."/".DEF_PAGE_ROOT."/".strtolower($sRepSite)."/";
    dirExists($sRep_content);
        
    // - rpertoire gabarit :: /custom/gabarit/site/
    $sRep_gabarit = $_SERVER['DOCUMENT_ROOT']."/custom/gabarit/".strtolower($sRepSite)."/";
    dirExists($sRep_gabarit);
        
    // - rpertoire img :: /custom/img/site/
    $sRep_img = $_SERVER['DOCUMENT_ROOT']."/custom/img/".strtolower($sRepSite)."/";
    dirExists($sRep_img);
        
    // - rpertoire medias :: /custom/medias/site/
    $sRep_medias = $_SERVER['DOCUMENT_ROOT']."/custom/medias/".strtolower($sRepSite)."/";
    dirExists($sRep_medias);
}

// suppression des rpertoires d'un mini site
function delete_rep_minisite($sRepSite)
{
    // suppression du fichier css
    $sRep_css = $_SERVER['DOCUMENT_ROOT']."/custom/css/";
    @unlink($sRep_css."fo_".strtolower($sRepSite).".css");
    @unlink($sRep_css."fo_".strtolower($sRepSite)."_spaw.css");

    // - rpertoire content :: /content/site/
    $sRep_content = $_SERVER['DOCUMENT_ROOT']."/".DEF_PAGE_ROOT."/".strtolower($sRepSite)."/";
    removeRecursDir($sRep_content);
            
    // - rpertoire gabarit :: /custom/gabarit/site/
    $sRep_gabarit = $_SERVER['DOCUMENT_ROOT']."/custom/gabarit/".strtolower($sRepSite)."/";
    removeRecursDir($sRep_gabarit);
            
    // - rpertoire img :: /custom/img/site/
    $sRep_img = $_SERVER['DOCUMENT_ROOT']."/custom/img/".strtolower($sRepSite)."/";
    removeRecursDir($sRep_img);
            
    // - rpertoire medias :: /custom/medias/site/
    $sRep_medias = $_SERVER['DOCUMENT_ROOT']."/custom/medias/".strtolower($sRepSite)."/";
    removeRecursDir($sRep_medias);
}

function getSiteFrontEnd($oSite){
    if (is_file($_SERVER['DOCUMENT_ROOT'].'/content/'.$oSite->get_rep().'/index.html')){
        $path='/content/'.$oSite->get_rep().'/index.html';
    }
    elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/content/'.$oSite->get_rep().'/index.php')){
        $path='/content/'.$oSite->get_rep().'/index.php';
    }
    elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/frontoffice/'.$oSite->get_rep().'/index.html')){
        $path='/frontoffice/'.$oSite->get_rep().'/index.html';
    }
    elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/frontoffice/'.$oSite->get_rep().'/index.php')){
        $path='/frontoffice/'.$oSite->get_rep().'/index.php';
    }
    elseif (is_dir($_SERVER['DOCUMENT_ROOT'].'/content/'.$oSite->get_rep())){
        $path='/content/'.$oSite->get_rep().'/';
    }
    elseif (is_dir($_SERVER['DOCUMENT_ROOT'].'/frontoffice/'.$oSite->get_rep())){
        $path='/frontoffice/'.$oSite->get_rep().'/';
    }
    else{
        $path='./install/?nodirfound';
    }
    return $path;    
}

function hostToSite($sHost=NULL){
    if ($sHost==NULL){
        $sHost=$_SERVER['HTTP_HOST'];
    }
    
    if (getCount_where('cms_site', array('cms_url'), array($sHost), array('TEXT'))) {    
        $sql = 'select * from cms_site where cms_url = \''.$sHost.'\' order by cms_id';
        $aSite = dbGetObjectsFromRequete('cms_site', $sql);        
        return $aSite[0];
    }
    else{
        return false;
    }
}

function pathToSite($sPath){
    global $db;
    
    if ($sPath==NULL){
        //$sPath=$_SERVER['PHP_SELF'];
        $sPath=str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
    }
    
    if (preg_match("/\/content/", $sPath)) {
        $idSite = path2idside2($db, $sPath);
        $oSite = new Cms_site ($idSite);
        return $oSite;
    }
    else{
        return false;
    }
}

function path2idside2($db, $absolutePath){
    if (strpos($absolutePath, "/content/") === false){
        return 1; // cas de merde où on est hors /content/
    }
    else{
        $sMinisiteRepertoire = path2minisiteRepertoire2($db, $absolutePath);
        $sSql = "SELECT cms_id FROM cms_site WHERE cms_rep = '".$sMinisiteRepertoire."'";
        $idSite = dbGetUniqueValueFromRequete($sSql);

        return $idSite;
    }
}

function path2minisiteRepertoire2($db, $absolutePath){
    if (strpos($absolutePath, "/content/") === false){
        return "/"; // cas de merde où on est hors /content/
    }
    else{
        $sMinisiteRepertoire = preg_replace('/(.)*\/content\/([^\/]+)\/.*/msi', "$2", $absolutePath);
        return $sMinisiteRepertoire;
    }
}

function detectSite($sPath=NULL){
    $oSite = pathToSite($sPath);
    
    if ($oSite != false){
        return $oSite;
    }
    else{
        $oSite = hostToSite();
        if ($oSite != false){
            return $oSite;
        }
        else{
            $oSite = defaultSite();
            if ($oSite != false){
                return $oSite;
            }
            else{
                return false;
            }
        }
    }
}

function defaultSite(){
	// default : chercher le premier site en ligne
    $sql = 'select * from cms_site where cms_statut = '.DEF_ID_STATUT_LIGNE.' order by cms_id';
    $aSite = dbGetObjectsFromRequete('cms_site', $sql);
    if ($aSite!=false){
        return $aSite[0];
    }
    else{// ni no res : chercher le premier site sans souci du statut
    	$sql = 'select * from cms_site order by cms_id';
		$aSite = dbGetObjectsFromRequete('cms_site', $sql);
		if ($aSite!=false){
			return $aSite[0];
		}
		else{
			false;
		}
    }
}

function siteByidLangue ($id_langue) {

	$sql = 'select * from cms_site where cms_langue = '.$id_langue.' order by cms_id';
	
    $aSite = dbGetObjectsFromRequete('cms_site', $sql);
    if ($aSite!=false){
        return $aSite;
    }
    else{
        false;
    }	
	
}

function sitePropsToSession($oSite){
    $_SESSION['minisite'] = $oSite->get_isminisite();
    $_SESSION['fonct'] = str_replace('BLOG','BLOG;CMS;COMMENT',$oSite->get_fonct());
    // site connecté
    $_SESSION['site'] = $oSite->get_name();
    // id du site connecté
    $_SESSION['idSite'] = $oSite->get_id();
    // id du site de travail
    $_SESSION['idSite_travail'] = $oSite->get_id();
    // nom site travail
    $_SESSION['site_travail'] = $oSite->get_name();
    $_SESSION['rep_travail'] = $oSite->get_rep();
        
    //langue
    //$_SESSION['id_langue']=$oSite->get_langue(); // => on set $_SESSION['id_langue'] par la langue de user
    
    if (    ($oSite->get_url()!='*')    &&    (trim($oSite->get_url())!='')    ){
        $_SESSION['site_host']=$oSite->get_url();
    }
    else{
        $_SESSION['site_host']=$_SERVER['HTTP_HOST'];
    }
    
    if ((int)$oSite->get_langue()>0){
        $oLangue = new Cms_langue($oSite->get_langue());    
        $_SESSION['site_langue']=strtolower($oLangue->get_libellecourt());    
        $_SESSION['id_langue']=$_SESSION['cms_langues'][$_SESSION['site_langue']];
    }    
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');
                
    $stack = array();
    global $stack;
    $sXML = $oSite->XML;
    xmlStringParse($sXML);    
    $aNodeToSort = $stack[0]["children"];

    $nEncodeNode = getItemByName($aNodeToSort, 'encod');
    foreach($nEncodeNode["children"] as $nK => $nOptionNode){
        if ($nOptionNode["attrs"]["VALUE"] == $oSite->get_encod()){
            $_SESSION['encod'] = $nOptionNode["attrs"]["LIBELLE"];            
        }
    }
    
    $nDoctypeNode = getItemByName($aNodeToSort, 'doctype');

    foreach($nDoctypeNode["children"] as $nK => $nOptionNode){
        if ($nOptionNode["attrs"]["VALUE"] == $oSite->get_doctype()){
            $_SESSION['doctype'] = $nOptionNode["attrs"]["LIBELLE"];            
        }
    }
    
    $_SESSION['mobile']=false;
    if ($oSite->get_isminisite()!=-1){
        $oMaitre = new cms_site($oSite->get_isminisite());
        if ($oMaitre){
            $_SESSION['mobile']=true;
        }    
    }
        
    $_SESSION['https'] = $oSite->get_https();    
    $_SESSION['robots'] = $oSite->get_robots();
    $_SESSION['author'] = $oSite->get_author();
    $_SESSION['copyright'] = $oSite->get_copyright();
    $_SESSION['georegion'] = $oSite->get_georegion();
    $_SESSION['geoplacename'] = $oSite->get_geoplacename();
    $_SESSION['geoposition'] = $oSite->get_geoposition();
    $_SESSION['viewport'] = $oSite->get_viewport();

}



// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site/list_cms_site.php", "w");
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
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site/maj_cms_site.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site/show_cms_site.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site/rss_cms_site.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site/xml_cms_site.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site/xlsx_cms_site.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_site/import_cms_site.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>