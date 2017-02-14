<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */

// patch de migration
if (!ispatched('shp_tarif')){
	$rs = $db->Execute('SHOW COLUMNS FROM `shp_tarif`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('shp_trf_standard', $names)) {
			$rs = $db->Execute("ALTER TABLE `shp_tarif` ADD `shp_trf_standard` ENUM( 'Y', 'N' ) DEFAULT 'N' NOT NULL AFTER `shp_trf_prix` ;");
		}
	}
}

/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shp_tarif.class.php')  && (strpos(__FILE__,'/include/bo/class/shp_tarif.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/shp_tarif.class.php');
}else{
/*======================================

objet de BDD shp_tarif :: class shp_tarif

SQL mySQL:

DROP TABLE IF EXISTS shp_tarif;
CREATE TABLE shp_tarif
(
	shp_trf_id			int (12) PRIMARY KEY not null,
	shp_trf_id_produit			int (11),
	shp_trf_id_unite			int (3),
	shp_trf_statut			int (2) not null,
	shp_trf_intitule			varchar (256) not null,
	shp_trf_prix			decimal (10,2),
	shp_trf_standard			enum ('Y','N') not null default 'N',
	shp_trf_ordre			int (11),
	shp_trf_cdate			datetime not null,
	shp_trf_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)

SQL Oracle:

DROP TABLE shp_tarif
CREATE TABLE shp_tarif
(
	shp_trf_id			number (12) constraint shp_trf_pk PRIMARY KEY not null,
	shp_trf_id_produit			number (11),
	shp_trf_id_unite			number (3),
	shp_trf_statut			number (2) not null,
	shp_trf_intitule			varchar2 (256) not null,
	shp_trf_prix			decimal (10,2),
	shp_trf_standard			enum ('Y','N') not null default 'N',
	shp_trf_ordre			number (11),
	shp_trf_cdate			datetime not null,
	shp_trf_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="shp_tarif" libelle="Tarifs produit" prefix="shp_trf" display="intitule" abstract="prix">
<item name="id" type="int" length="12" isprimary="true" notnull="true" default="-1" list="true" /> 
<item name="id_produit" libelle="Produit" type="int" length="11" fkey="shp_produit" list="true" order="true" />
<item name="id_unite" libelle="Unité de mesure" type="int" length="3" fkey="shp_unite" list="true" order="true" />
<item name="statut" type="int" length="2" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" />
<item name="intitule" libelle="Intitulé" type="varchar" length="256" notnull="true" default="" list="true" nohtml="true" />
<item name="prix" libelle="Valeur tarif" type="decimal" length="10,2" default="0.00" list="true" order="true" nohtml="true" />
<item name="standard" libelle="Est standard" type="enum" length="'Y','N'" default="N" list="true" notnull="true" />
<item name="ordre" libelle="Ordre d'apparition" type="int" length="11" />
<item name="cdate" libelle="Date de création" type="datetime" notnull="true" list="false" default="" />
<item name="mdate" libelle="Date de modification" type="timestamp" list="false" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" />
</class>



==========================================*/

class shp_tarif
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $id_produit;
var $id_unite;
var $statut;
var $intitule;
var $prix;
var $standard;
var $ordre;
var $cdate;
var $mdate;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"shp_tarif\" libelle=\"Tarifs produit\" prefix=\"shp_trf\" display=\"intitule\" abstract=\"prix\">
<item name=\"id\" type=\"int\" length=\"12\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" /> 
<item name=\"id_produit\" libelle=\"Produit\" type=\"int\" length=\"11\" fkey=\"shp_produit\" list=\"true\" order=\"true\" />
<item name=\"id_unite\" libelle=\"Unité de mesure\" type=\"int\" length=\"3\" fkey=\"shp_unite\" list=\"true\" order=\"true\" />
<item name=\"statut\" type=\"int\" length=\"2\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" />
<item name=\"intitule\" libelle=\"Intitulé\" type=\"varchar\" length=\"256\" notnull=\"true\" default=\"\" list=\"true\" nohtml=\"true\" />
<item name=\"prix\" libelle=\"Valeur tarif\" type=\"decimal\" length=\"10,2\" default=\"0.00\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"standard\" libelle=\"Est standard\" type=\"enum\" length=\"'Y','N'\" default=\"N\" list=\"true\" notnull=\"true\" />
<item name=\"ordre\" libelle=\"Ordre d'apparition\" type=\"int\" length=\"11\" />
<item name=\"cdate\" libelle=\"Date de création\" type=\"datetime\" notnull=\"true\" list=\"false\" default=\"\" />
<item name=\"mdate\" libelle=\"Date de modification\" type=\"timestamp\" list=\"false\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" />
</class>
";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE shp_tarif
(
	shp_trf_id			int (12) PRIMARY KEY not null,
	shp_trf_id_produit			int (11),
	shp_trf_id_unite			int (3),
	shp_trf_statut			int (2) not null,
	shp_trf_intitule			varchar (256) not null,
	shp_trf_prix			decimal (10,2),
	shp_trf_standard			enum ('Y','N') not null default 'N',
	shp_trf_ordre			int (11),
	shp_trf_cdate			datetime not null,
	shp_trf_mdate			timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
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
		$this->id_produit = -1;
		$this->id_unite = -1;
		$this->statut = DEF_CODE_STATUT_DEFAUT;
		$this->intitule = "";
		$this->prix = 0.00;
		$this->standard = "N";
		$this->ordre = -1;
		$this->cdate = date('Y-m-d H:i:s');
		$this->mdate = 'CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
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
	$laListeChamps[]=new dbChamp("Shp_trf_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Shp_trf_id_produit", "entier", "get_id_produit", "set_id_produit");
	$laListeChamps[]=new dbChamp("Shp_trf_id_unite", "entier", "get_id_unite", "set_id_unite");
	$laListeChamps[]=new dbChamp("Shp_trf_statut", "entier", "get_statut", "set_statut");
	$laListeChamps[]=new dbChamp("Shp_trf_intitule", "text", "get_intitule", "set_intitule");
	$laListeChamps[]=new dbChamp("Shp_trf_prix", "decimal", "get_prix", "set_prix");
	$laListeChamps[]=new dbChamp("Shp_trf_standard", "text", "get_standard", "set_standard");
	$laListeChamps[]=new dbChamp("Shp_trf_ordre", "entier", "get_ordre", "set_ordre");
	$laListeChamps[]=new dbChamp("Shp_trf_cdate", "date_formatee_timestamp", "get_cdate", "set_cdate");
	$laListeChamps[]=new dbChamp("Shp_trf_mdate", "date_formatee_timestamp", "get_mdate", "set_mdate");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_id_produit() { return($this->id_produit); }
function get_id_unite() { return($this->id_unite); }
function get_statut() { return($this->statut); }
function get_intitule() { return($this->intitule); }
function get_prix() { return($this->prix); }
function get_standard() { return($this->standard); }
function get_ordre() { return($this->ordre); }
function get_cdate() { return($this->cdate); }
function get_mdate() { return($this->mdate); }


// setters
function set_id($c_shp_trf_id) { return($this->id=$c_shp_trf_id); }
function set_id_produit($c_shp_trf_id_produit) { return($this->id_produit=$c_shp_trf_id_produit); }
function set_id_unite($c_shp_trf_id_unite) { return($this->id_unite=$c_shp_trf_id_unite); }
function set_statut($c_shp_trf_statut) { return($this->statut=$c_shp_trf_statut); }
function set_intitule($c_shp_trf_intitule) { return($this->intitule=$c_shp_trf_intitule); }
function set_prix($c_shp_trf_prix) { return($this->prix=$c_shp_trf_prix); }
function set_standard($c_shp_trf_standard) { return($this->standard=$c_shp_trf_standard); }
function set_ordre($c_shp_trf_ordre) { return($this->ordre=$c_shp_trf_ordre); }
function set_cdate($c_shp_trf_cdate) { return($this->cdate=$c_shp_trf_cdate); }
function set_mdate($c_shp_trf_mdate) { return($this->mdate=$c_shp_trf_mdate); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("shp_trf_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("shp_trf_statut"); }
//
function getTable() { return("shp_tarif"); }
function getClasse() { return("shp_tarif"); }
function getPrefix() { return("shp_trf"); }
function getDisplay() { return("intitule"); }
function getAbstract() { return("prix"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif/list_shp_tarif.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif/maj_shp_tarif.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif/show_shp_tarif.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif/rss_shp_tarif.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif/xml_shp_tarif.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif/xmlxls_shp_tarif.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif/export_shp_tarif.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/shp_tarif/import_shp_tarif.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>