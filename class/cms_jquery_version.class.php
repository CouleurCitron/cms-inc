<?php
//pre_dump("jquery_version");
/* [Begin patch] */

//pre_dump(ispatched('cms_jquery_version'));
if (!ispatched('cms_jquery_version')){
    //pre_dump("patch");
    //pre_dump(istable("cms_jquery_version"));
    if(!istable("cms_jquery_version")){
        $ctable = "CREATE TABLE cms_jquery_version
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_name			varchar (255),
	cms_filename			varchar (255),
	cms_statut			int (11) not null
)";
        
        dbExecuteQuery($ctable);
    }
        //pre_dump("ok table");
        
        $sSQl = "SELECT * FROM cms_jquery_version";
        $result = mysqli_query($sSQl);
        $number = mysqli_num_rows($result);
        //pre_dump($number);
        if($number == "0"){
            //pre_dump("no data");
            
            $sInsert = "INSERT INTO  `cms_jquery_version` (
            `cms_id` ,
            `cms_name` ,
            `cms_filename` ,
            `cms_statut`
    )
    VALUES 
    (
            '1',  'jquery 1.6.4',  'jquery-1.6.4.min.js',  '4'
    ),
    (
            '2',  'jquery 1.2.6',  'jquery-1.2.6.min.js',  '4'
    ), 
    (
            '3',  'jquery 1.4.2',  'jquery-1.4.2.min.js',  '4'
    ),
    (
            '4',  'jquery 1.7.2',  'jquery-1.7.2.min.js',  '4'
    ), 
    (
            '5',  'jquery 1.10.2',  'jquery-1.10.2.min.js',  '4'
    ),
    (
            '6',  'jquery 2.0.3',  'jquery-2.0.3.min.js',  '4'
    )";



            $rs = $db->Execute($sInsert);

            //pre_dump($rs);
        }
}
/*
INSERT INTO  `cms_jquery_version` (
        `cms_id` ,
        `cms_name` ,
        `cms_filename` ,
        `cms_statut`
)
VALUES 
(
        '1',  'jquery 1.6.4',  'jquery-1.6.4.min.js',  '4'
),
(
        '2',  'jquery 1.2.6',  'jquery-1.2.6.min.js',  '4'
), 
(
        '3',  'jquery 1.4.2',  'jquery-1.4.2.min.js',  '4'
),
(
        '4',  'jquery 1.7.2',  'jquery-1.7.2.min.js',  '4'
), 
(
        '5',  'jquery 1.10.2',  'jquery-1.10.2.min.js',  '4'
),
(
        '6',  'jquery 2.0.3',  'jquery-2.0.3.min.js',  '4'
);

*/
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_jquery_version.class.php')  && (strpos(__FILE__,'/include/bo/class/cms_jquery_version.class.php')===FALSE) ){
    //pre_dump("choix1");
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/cms_jquery_version.class.php');
}else{
    //pre_dump("choix2");
/*======================================

objet de BDD cms_jquery_version :: class cms_jquery_version

SQL mySQL:

DROP TABLE IF EXISTS cms_jquery_version;
CREATE TABLE cms_jquery_version
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_name			varchar (255),
	cms_filename			varchar (255),
	cms_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_jquery_version
CREATE TABLE cms_jquery_version
(
	cms_id			number (11) constraint cms_pk PRIMARY KEY not null,
	cms_name			varchar2 (255),
	cms_filename			varchar2 (255),
	cms_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_jquery_version" libelle="Version jquery" prefix="cms" display="name" abstract="filename">

<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />

<item name="name" libelle="Nom de la version" type="varchar" length="255" list="true" order="true" nohtml="true" />

<item name="filename" libelle="Nom du fichier" type="varchar" length="255" list="true" order="true" nohtml="true" />

<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_ID_STATUT_LIGNE" list="true" order="true" /> 
<langpack lang="fr">
<norecords>Pas de version de jquery à afficher</norecords>
</langpack>
</class>


==========================================*/

class cms_jquery_version
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $name;
var $filename;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_jquery_version\" libelle=\"Version jquery\" prefix=\"cms\" display=\"name\" abstract=\"filename\">

<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />

<item name=\"name\" libelle=\"Nom de la version\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />

<item name=\"filename\" libelle=\"Nom du fichier\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />

<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_ID_STATUT_LIGNE\" list=\"true\" order=\"true\" /> 
<langpack lang=\"fr\">
<norecords>Pas de version de jquery à afficher</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE cms_jquery_version
(
	cms_id			int (11) PRIMARY KEY not null,
	cms_name			varchar (255),
	cms_filename			varchar (255),
	cms_statut			int (11) not null
)";
// constructeur
function cms_jquery_version($id=null)
{
    //pre_dump(istable(get_class($this)));
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
		$this->filename = "";
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
	$laListeChamps[]=new dbChamp("Cms_filename", "text", "get_filename", "set_filename");
	$laListeChamps[]=new dbChamp("Cms_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_name() { return($this->name); }
function get_filename() { return($this->filename); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_cms_id) { return($this->id=$c_cms_id); }
function set_name($c_cms_name) { return($this->name=$c_cms_name); }
function set_filename($c_cms_filename) { return($this->filename=$c_cms_filename); }
function set_statut($c_cms_statut) { return($this->statut=$c_cms_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("cms_statut"); }
//
function getTable() { return("cms_jquery_version"); }
function getClasse() { return("cms_jquery_version"); }
function getPrefix() { return(""); }
function getDisplay() { return("name"); }
function getAbstract() { return("filename"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version/list_cms_jquery_version.php", "w");
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
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version/maj_cms_jquery_version.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version/show_cms_jquery_version.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version/rss_cms_jquery_version.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version/xml_cms_jquery_version.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version/xlsx_cms_jquery_version.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_jquery_version/import_cms_jquery_version.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>