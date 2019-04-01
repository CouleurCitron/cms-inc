<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('cms_page')){
	$rs = $db->Execute('DESCRIBE `cms_page`');
	if (isset($rs->_numOfRows)){
		if ($rs->_numOfRows == 18){
			$rs = $db->Execute('ALTER TABLE `cms_page` ADD `theme` INT( 11 ) NOT NULL ;');
		} 
		elseif ($rs->_numOfRows == 19){
			$rs = $db->Execute('ALTER TABLE `cms_page` ADD `iscustom` INT( 1 ) NOT NULL DEFAULT \'0\' AFTER `existeligne_page` ;');
		}  
	} 
}
/*======================================

objet de BDD cms_page :: class cms_page

SQL mySQL:

DROP TABLE IF EXISTS cms_page;
CREATE TABLE cms_page
(
	page_id_page			int (11) PRIMARY KEY not null,
	page_name_page			varchar (64),
	page_gabarit_page			int (11),
	page_dateadd_page			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	page_dateupd_page			timestamp not null default 0000-00-00 00:00:00,
	page_datedlt_page			timestamp not null default 0000-00-00 00:00:00,
	page_datemep_page			timestamp not null default 0000-00-00 00:00:00,
	page_isgenerated_page			bool (2),
	page_valid_page			bool (2),
	page_nodeid_page			int (11),
	page_options_page			varchar (512),
	page_html_page			text (1024),
	page_isgabarit_page			bool (2),
	page_width_page			int (5),
	page_height_page			int (5),
	page_id_site			int (11),
	page_toutenligne_page			bool (2),
	page_existeligne_page			bool (2),
	page_iscustom			bool (2),
	page_theme			int (11),
)

SQL Oracle:

DROP TABLE cms_page
CREATE TABLE cms_page
(
	page_id_page			number (11) constraint page_pk PRIMARY KEY not null,
	page_name_page			varchar2 (64),
	page_gabarit_page			number (11),
	page_dateadd_page			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	page_dateupd_page			timestamp not null default 0000-00-00 00:00:00,
	page_datedlt_page			timestamp not null default 0000-00-00 00:00:00,
	page_datemep_page			timestamp not null default 0000-00-00 00:00:00,
	page_isgenerated_page			bool (2),
	page_valid_page			bool (2),
	page_nodeid_page			number (11),
	page_options_page			varchar2 (512),
	page_html_page			text (1024),
	page_isgabarit_page			bool (2),
	page_width_page			number (5),
	page_height_page			number (5),
	page_id_site			number (11),
	page_toutenligne_page			bool (2),
	page_existeligne_page			bool (2),
	page_iscustom			bool (2),
	page_theme			number (11),
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_page" libelle="Pages" prefix="page" display="name_page" abstract="nodeid_page" >
<item name="id_page" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true"  asso="cms_assovideopage"  />
<item name="name_page" libelle="Nom" type="varchar" length="64" list="true" order="true" />
<item name="gabarit_page" libelle="Gabarit" type="int" length="11" default="0" list="true" order="true" fkey="cms_page" />
<item name="dateadd_page" libelle="Date de création" type="timestamp" notnull="true" order="true" list="true" default="CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP" /> 
<item name="dateupd_page" libelle="Date de modification" type="timestamp" notnull="true" order="true" list="true" default="0000-00-00 00:00:00" /> 
<item name="datedlt_page" libelle="Date de suppression" type="timestamp" notnull="true" order="true" list="true" default="0000-00-00 00:00:00" /> 
<item name="datemep_page" libelle="Date de mise en prod" type="timestamp" notnull="true" order="true" list="true" default="0000-00-00 00:00:00" /> 
<item name="isgenerated_page" libelle="Est générée" type="bool" length="2" default="0" list="true" order="true" />
<item name="valid_page" libelle="Est valide" type="bool" length="2" default="0" list="true" order="true" />
<item name="nodeid_page" libelle="Noeud d'arbo." type="int" length="11" default="0" list="true" order="true" fkey="cms_arbo_pages"  />
<item name="options_page" libelle="Options" type="varchar" length="512" nohtml="true" />
<item name="html_page" libelle="Source HTML" type="text" length="1024" option="textarea" />
<item name="isgabarit_page" libelle="Est un gabarit" type="bool" length="2" default="0" list="true" order="true" />
<item name="width_page" libelle="largeur zone de travail" type="int" length="5" default="0"/>
<item name="height_page" libelle="hauteur zone de travail" type="int" length="5" default="0"/>
<item name="id_site" libelle="Site" type="int" length="11" list="true" order="true" oblig="true" fkey="cms_site" />
<item name="toutenligne_page" libelle="Tout en ligne" type="bool" length="2" default="0" list="true" order="true" />
<item name="existeligne_page" libelle="Existe en ligne" type="bool" length="2" default="0" list="true" order="true" />
<item name="iscustom" libelle="Custom (non basé sur les briques)" type="bool" length="2" default="0" list="true" order="true" />
<item name="theme" libelle="Thème" type="int" length="11" list="true" order="true" fkey="cms_theme" />
<langpack lang="fr">
<norecords>Pas de donnée à afficher</norecords>
</langpack>
</class> 


==========================================

function Cms_page() 
function initValues($id)
function getCms_pages_withName($sName) 
function cms_page_insert()
function cms_page_update()
function cms_page_regenerate()
function cms_page_write()
function getPagesFromGabarit() {
function getPageDirectory()

///////////////////////////////////
HORS CLASSES
///////////////////////////////////

function ifTousEnLigne($idPage)
function ifExisteligne($idPage)
function updateRenommageGabarit($sOldName, $sNewName)
function getListPages($aRecherche, $sOrderBy="", $eLimit)
function getCountListPages($aRecherche, $sOrderBy="")
function updateToutenligne($idPage)
function updateExisteligne($idPage)
function pageTravail($idPage, $sMode, $eIdTravail)
function afficheIconePreviewPage($idContent, $sNature, $idPage, $sNomPage, $bToutenligne_page, $bExisteligne_page, $sUrlPageLigne)
function getPagesFromXGabarits($aIdGab, $idSite) {
function getIdPageWithUrl($idNode, $idSite, $sPage)
function analyseUrlToGetIdPage($sUrlComplete)
function getUrlWithIdPage($idPage)

==========================================*/

class Cms_page
{

var $id_page;
var $name_page;
var $gabarit_page;
var $dateadd_page;
var $dateupd_page;
var $datedlt_page;
var $datemep_page;
var $isgenerated_page;
var $valid_page;
var $nodeid_page;
var $options_page;
var $html_page;
var $isgabarit_page;
var $width_page;
var $height_page;
var $id_site;
var $toutenligne_page;
var $existeligne_page;
var $iscustom;
var $theme;

var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_page\" libelle=\"Pages\" prefix=\"page\" display=\"name_page\" abstract=\"nodeid_page\" >
<item name=\"id_page\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" asso=\"cms_assovideopage\"  />
<item name=\"name_page\" libelle=\"Nom\" type=\"varchar\" length=\"64\" list=\"true\" order=\"true\" />
<item name=\"gabarit_page\" libelle=\"Gabarit\" type=\"int\" length=\"11\" default=\"0\" list=\"true\" order=\"true\" fkey=\"cms_page\" />
<item name=\"dateadd_page\" libelle=\"Date de création\" type=\"timestamp\" notnull=\"true\" order=\"true\" list=\"true\" default=\"CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP\" /> 
<item name=\"dateupd_page\" libelle=\"Date de modification\" type=\"timestamp\" notnull=\"true\" order=\"true\" list=\"true\" default=\"0000-00-00 00:00:00\" /> 
<item name=\"datedlt_page\" libelle=\"Date de suppression\" type=\"timestamp\" notnull=\"true\" order=\"true\" list=\"true\" default=\"0000-00-00 00:00:00\" /> 
<item name=\"datemep_page\" libelle=\"Date de mise en prod\" type=\"timestamp\" notnull=\"true\" order=\"true\" list=\"true\" default=\"0000-00-00 00:00:00\" /> 
<item name=\"isgenerated_page\" libelle=\"Est générée\" type=\"bool\" length=\"2\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"valid_page\" libelle=\"Est valide\" type=\"bool\" length=\"2\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"nodeid_page\" libelle=\"Noeud d'arbo.\" type=\"int\" length=\"11\" default=\"0\" list=\"true\" order=\"true\" fkey=\"cms_arbo_pages\"  />
<item name=\"options_page\" libelle=\"Options\" type=\"varchar\" length=\"512\" nohtml=\"true\" />
<item name=\"html_page\" libelle=\"Source HTML\" type=\"text\" length=\"1024\" option=\"textarea\" />
<item name=\"isgabarit_page\" libelle=\"Est un gabarit\" type=\"bool\" length=\"2\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"width_page\" libelle=\"largeur zone de travail\" type=\"int\" length=\"5\" default=\"0\"/>
<item name=\"height_page\" libelle=\"hauteur zone de travail\" type=\"int\" length=\"5\" default=\"0\"/>
<item name=\"id_site\" libelle=\"Site\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" oblig=\"true\" fkey=\"cms_site\" />
<item name=\"toutenligne_page\" libelle=\"Tout en ligne\" type=\"bool\" length=\"2\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"existeligne_page\" libelle=\"Existe en ligne\" type=\"bool\" length=\"2\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"iscustom\" libelle=\"Custom (non basé sur les briques)\" type=\"bool\" length=\"2\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"theme\" libelle=\"Thème\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" fkey=\"cms_theme\" />
<langpack lang=\"fr\">
<norecords>Pas de donnée à afficher</norecords>
</langpack>
</class> ";

var $sMySql = "CREATE TABLE cms_page
(
	page_id_page			int (11) PRIMARY KEY not null,
	page_name_page			varchar (64),
	page_gabarit_page			int (11),
	page_dateadd_page			timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	page_dateupd_page			timestamp not null default 0000-00-00 00:00:00,
	page_datedlt_page			timestamp not null default 0000-00-00 00:00:00,
	page_datemep_page			timestamp not null default 0000-00-00 00:00:00,
	page_isgenerated_page			bool (2),
	page_valid_page			bool (2),
	page_nodeid_page			int (11),
	page_options_page			varchar (512),
	page_html_page			text (1024),
	page_isgabarit_page			bool (2),
	page_width_page			int (5),
	page_height_page			int (5),
	page_id_site			int (11),
	page_toutenligne_page			bool (2),
	page_existeligne_page			bool (2),
	page_iscustom			bool (2),
	page_theme			int (11)
)

";

var $oldstyleclass;


// constructeur
function __construct($id=null) 
{
	global $db;
	if($id!=null) {
		$this->initValues($id);
	} else {
		$this->id_page=-1;
		$this->name_page='';
		$this->gabarit_page='';
		$this->dateadd_page='';
		$this->dateupd_page='';
		$this->datedlt_page='';
		$this->datemep_page='';	
		$this->isgenerated_page=0;
		$this->valid_page=0;
		$this->nodeid_page=0;
		$this->options_page='';
		$this->html_page='';
		$this->isgabarit_page=0;
		$this->width_page=0;
		$this->height_page=0;
		$this->id_site=-1;
		$this->toutenligne_page=0;
		$this->existeligne_page=0;
		$this->iscustom=0;
		$this->theme=0;
		$this->oldstyleclass=1;
	}
}

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("id_page", "entier", "getId_page", "setId_page");
	$laListeChamps[]=new dbChamp("name_page", "text", "getName_page", "setName_page");
	$laListeChamps[]=new dbChamp("gabarit_page", "text", "getGabarit_page", "setGabarit_page");
	$laListeChamps[]=new dbChamp("dateadd_page", "date", "getDateadd_page", "setDateadd_page");
	$laListeChamps[]=new dbChamp("dateupd_page", "date", "getDateupd_page", "setDateupd_page");
	$laListeChamps[]=new dbChamp("datedlt_page", "date", "getDatedlt_page", "setDatedlt_page");
	$laListeChamps[]=new dbChamp("datemep_page", "date", "getDatemep_page", "setDatemep_page");
	$laListeChamps[]=new dbChamp("isgenerated_page", "entier", "getIsgenerated_page", "setIsgenerated_page");
	$laListeChamps[]=new dbChamp("valid_page", "entier", "getValid_page", "setValid_page");
	$laListeChamps[]=new dbChamp("nodeid_page", "entier", "getNodeid_page", "setNodeid_page");
	$laListeChamps[]=new dbChamp("options_page", "text", "getOptions_page", "setOptions_page");
	$laListeChamps[]=new dbChamp("html_page", "text", "getHtml_page", "setHtml_page");
	$laListeChamps[]=new dbChamp("isgabarit_page", "entier", "getIsgabarit_page", "setIsgabarit_page");
	$laListeChamps[]=new dbChamp("width_page", "entier", "getWidth_page", "setWidth_page");
	$laListeChamps[]=new dbChamp("height_page", "entier", "getHeight_page", "setHeight_page");
	$laListeChamps[]=new dbChamp("id_site", "entier", "getId_site", "setId_site");
	$laListeChamps[]=new dbChamp("toutenligne_page", "entier", "getToutenligne_page", "setToutenligne_page");
	$laListeChamps[]=new dbChamp("existeligne_page", "entier", "getExisteligne_page", "setExisteligne_page");
	$laListeChamps[]=new dbChamp("iscustom", "entier", "get_iscustom", "set_iscustom");
	$laListeChamps[]=new dbChamp("theme", "entier", "get_theme", "set_theme");
	
	return($laListeChamps);
}

// getters
function getId_page() { return($this->id_page); } 
function get_id() { return($this->id_page); } 
function getName_page() { return($this->name_page); } 
function getGabarit_page() { return($this->gabarit_page); } 
function getDateadd_page() { return($this->dateadd_page); } 
function getDateupd_page() { return($this->dateupd_page); } 
function getDatedlt_page() { return($this->datedlt_page); } 
function getDatemep_page() { return($this->datemep_page); } 
function getIsgenerated_page() { return($this->isgenerated_page); } 
function getValid_page() { return($this->valid_page); } 
function getNodeid_page() { return($this->nodeid_page); } 
function getOptions_page() { return($this->options_page); } 
function getHtml_page() { return($this->html_page); } 
function getIsgabarit_page() { return($this->isgabarit_page); } 
function getWidth_page() { return($this->width_page); } 
function getHeight_page() { return($this->height_page); } 
function getId_site() { return($this->id_site); } 
function getToutenligne_page() { return($this->toutenligne_page); } 
function getExisteligne_page() { return($this->existeligne_page); } 
// xml generated
function get_id_page() { return($this->id_page); }
function get_name_page() { return($this->name_page); }
function get_gabarit_page() { return($this->gabarit_page); }
function get_dateadd_page() { return($this->dateadd_page); }
function get_dateupd_page() { return($this->dateupd_page); }
function get_datedlt_page() { return($this->datedlt_page); }
function get_datemep_page() { return($this->datemep_page); }
function get_isgenerated_page() { return($this->isgenerated_page); }
function get_valid_page() { return($this->valid_page); }
function get_nodeid_page() { return($this->nodeid_page); }
function get_options_page() { return($this->options_page); }
function get_html_page() { return($this->html_page); }
function get_isgabarit_page() { return($this->isgabarit_page); }
function get_width_page() { return($this->width_page); }
function get_height_page() { return($this->height_page); }
function get_id_site() { return($this->id_site); }
function get_toutenligne_page() { return($this->toutenligne_page); }
function get_existeligne_page() { return($this->existeligne_page); }
function get_iscustom() { return($this->iscustom); }
function get_theme() { return($this->theme); }



// setters
function setId_page($c_id_page) { return($this->id_page=$c_id_page); } 
function set_id($c_id_page) { return($this->id_page=$c_id_page); } 
function setName_page($c_name_page) { return($this->name_page=$c_name_page); } 
function setGabarit_page($c_gabarit_page) { return($this->gabarit_page=$c_gabarit_page); } 
function setDateadd_page($c_dateadd_page) { return($this->dateadd_page=$c_dateadd_page); } 
function setDateupd_page($c_dateupd_page) { return($this->dateupd_page=$c_dateupd_page); } 
function setDatedlt_page($c_datedlt_page) { return($this->datedlt_page=$c_datedlt_page); } 
function setDatemep_page($c_datemep_page) { return($this->datemep_page=$c_datemep_page); } 
function setIsgenerated_page($c_isgenerated_page) { return($this->isgenerated_page=$c_isgenerated_page); } 
function setValid_page($c_valid_page) { return($this->valid_page=$c_valid_page); } 
function setNodeid_page($c_nodeid_page) { return($this->nodeid_page=$c_nodeid_page); } 
function setOptions_page($c_options_page) { return($this->options_page=$c_options_page); } 
function setHtml_page($c_html_page) { return($this->html_page=$c_html_page); } 
function setIsgabarit_page($c_isgabarit_page) { return($this->isgabarit_page=$c_isgabarit_page); } 
function setWidth_page($c_width_page) { return($this->width_page=$c_width_page); } 
function setHeight_page($c_height_page) { return($this->height_page=$c_height_page); } 
function setId_site($c_id_site) { return($this->id_site=$c_id_site); } 
function setToutenligne_page($c_toutenligne_page) { return($this->toutenligne_page=$c_toutenligne_page); } 
function setExisteligne_page($c_existeligne_page) { return($this->existeligne_page=$c_existeligne_page); } 
//xml generated
function set_id_page($c_page_id_page) { return($this->id_page=$c_page_id_page); }
function set_name_page($c_page_name_page) { return($this->name_page=$c_page_name_page); }
function set_gabarit_page($c_page_gabarit_page) { return($this->gabarit_page=$c_page_gabarit_page); }
function set_dateadd_page($c_page_dateadd_page) { return($this->dateadd_page=$c_page_dateadd_page); }
function set_dateupd_page($c_page_dateupd_page) { return($this->dateupd_page=$c_page_dateupd_page); }
function set_datedlt_page($c_page_datedlt_page) { return($this->datedlt_page=$c_page_datedlt_page); }
function set_datemep_page($c_page_datemep_page) { return($this->datemep_page=$c_page_datemep_page); }
function set_isgenerated_page($c_page_isgenerated_page) { return($this->isgenerated_page=$c_page_isgenerated_page); }
function set_valid_page($c_page_valid_page) { return($this->valid_page=$c_page_valid_page); }
function set_nodeid_page($c_page_nodeid_page) { return($this->nodeid_page=$c_page_nodeid_page); }
function set_options_page($c_page_options_page) { return($this->options_page=$c_page_options_page); }
function set_html_page($c_page_html_page) { return($this->html_page=$c_page_html_page); }
function set_isgabarit_page($c_page_isgabarit_page) { return($this->isgabarit_page=$c_page_isgabarit_page); }
function set_width_page($c_page_width_page) { return($this->width_page=$c_page_width_page); }
function set_height_page($c_page_height_page) { return($this->height_page=$c_page_height_page); }
function set_id_site($c_page_id_site) { return($this->id_site=$c_page_id_site); }
function set_toutenligne_page($c_page_toutenligne_page) { return($this->toutenligne_page=$c_page_toutenligne_page); }
function set_existeligne_page($c_page_existeligne_page) { return($this->existeligne_page=$c_page_existeligne_page); }
function set_iscustom($c_iscustom) { return($this->iscustom=$c_iscustom); }
function set_theme($c_theme) { return($this->theme=$c_theme); }

// autres getters
function getGetterPK() { return("getId_page"); }
function getSetterPK() { return("setId_page"); }
function getFieldPK() { return("id_page"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_page"); }
function getClasse() { return("cms_page"); }
function getDisplay() { return("name_page"); }
function getAbstract() { return("nodeid_page"); }


//-------------------------
// a_voir sponthus
// a réfléchir
// pour des problèmes d'optimisation de requete, 
// il faudrait constituer un objet d'affichage extrait de cet objet
// en effet ce qui est lourd est le fait de retourner tout l'objet dans les listes
// pour l'instant juste un getter mais je suppose qu'il faudrait faire des getListeChamps d'affichage
function getFieldAffichage() { return("name_page"); }
//-------------------------




function initValues($id) 
{
	global $db;
	$result = true;

	$sql = " SELECT * FROM cms_page";
	$sql.= " WHERE id_page = $id";
	if (DEF_BDD != "ORACLE") $sql.= ";";

	$rs = $db->Execute($sql);
	if($rs && !$rs->EOF) {

		$this->name_page = $rs->fields[n('name_page')];
		$this->gabarit_page = $rs->fields[n('gabarit_page')];			
		$this->dateadd_page = $rs->fields[n('dateadd_page')];
		$this->dateupd_page = $rs->fields[n('dateupd_page')];			
		$this->datedlt_page = $rs->fields[n('datedlt_page')];
		$this->datemep_page = $rs->fields[n('datemep_page')];			
		$this->isgenerated_page = $rs->fields[n('isgenerated_page')];
		$this->valid_page = $rs->fields[n('valid_page')];			
		$this->nodeid_page = $rs->fields[n('nodeid_page')];
		$this->options_page = $rs->fields[n('options_page')];			
		$this->html_page = $rs->fields[n('html_page')];
		$this->isgabarit_page = $rs->fields[n('isgabarit_page')];			
		$this->width_page = $rs->fields[n('width_page')];			
		$this->height_page = $rs->fields[n('height_page')];			
		$this->id_page = $rs->fields[n('id_page')];
		$this->id_site = $rs->fields[n('id_site')];			
		$this->toutenligne_page = $rs->fields[n('toutenligne_page')];
		$this->existeligne_page = $rs->fields[n('existeligne_page')];
		$this->iscustom = $rs->fields[n('iscustom')];
		$this->theme = $rs->fields[n('theme')];
		
		$this->oldstyleclass=1;
					
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > initValues";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);

	  $result = false;
	}
	$rs->Close();
	return $result;
}




function getCms_pages_withName($sName) 
{
	global $db;
	$result = true;

	$sql = " SELECT * FROM cms_page";
	$sql.= " WHERE name_page = '".$sName."'";
	if (DEF_BDD != "ORACLE") $sql.= ";";
			
	$rs = $db->Execute($sql);
	if($rs && !$rs->EOF) {

		$this->name_page = $rs->fields[n('name_page')];
		$this->gabarit_page = $rs->fields[n('gabarit_page')];			
		$this->dateadd_page = $rs->fields[n('dateadd_page')];
		$this->dateupd_page = $rs->fields[n('dateupd_page')];			
		$this->datedlt_page = $rs->fields[n('datedlt_page')];
		$this->datemep_page = $rs->fields[n('datemep_page')];			
		$this->isgenerated_page = $rs->fields[n('isgenerated_page')];
		$this->valid_page = $rs->fields[n('valid_page')];			
		$this->nodeid_page = $rs->fields[n('nodeid_page')];
		$this->options_page = $rs->fields[n('options_page')];			
		$this->html_page = $rs->fields[n('html_page')];
		$this->isgabarit_page = $rs->fields[n('isgabarit_page')];			
		$this->width_page = $rs->fields[n('width_page')];			
		$this->height_page = $rs->fields[n('height_page')];			
		$this->id_page = $rs->fields[n('id_page')];
		$this->id_site = $rs->fields[n('id_site')];
		$this->toutenligne_page = $rs->fields[n('toutenligne_page')];
		$this->existeligne_page = $rs->fields[n('existeligne_page')];
		$this->iscustom = $rs->fields[n('iscustom')];
		$this->theme = $rs->fields[n('theme')];
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > initValues";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);

	  $result = false;
	}
	$rs->Close();
	return $result;
}





// INSERT
function cms_page_insert()
{
	global $db;
    $result = null;
	
	// ATTENTION
	// tous les champs text doivent être envoyés avec to_char (pas de cote)
	// date omises
	
	if($this->theme==''||$this->theme==NULL){
		$this->theme=0;
	}

    $sql = " INSERT INTO cms_page (";
	$sql.= " id_page, name_page, gabarit_page,";
	$sql.= " datemep_page, isgenerated_page, valid_page, ";
	$sql.= " nodeid_page, options_page, isgabarit_page, ";
	$sql.= " width_page, height_page, id_site, ";
	$sql.= " toutenligne_page, existeligne_page, iscustom, theme)";
	$sql.= " VALUES (";
	$sql.= " '".$this->id_page."', ".$db->qstr($this->name_page).", ".$db->qstr($this->gabarit_page).",";
	$sql.= " ".$this->datemep_page.", '".$this->isgenerated_page."', '".$this->valid_page."',";
	$sql.= " '".$this->nodeid_page."', ".$db->qstr($this->options_page).", '".$this->isgabarit_page."',";
	$sql.= " '".$this->width_page."', '".$this->height_page."', '".$this->id_site."', ";
	$sql.= " '".$this->toutenligne_page."', '".$this->existeligne_page."', '".$this->iscustom."', '".$this->theme."')";

	$rs = $db->Execute($sql);

    if($rs != false) {
      $result = $this->id_page;
	  
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
  
    } else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "<br />cms_page.class.php > cms_page_insert";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("--------------------------------------------------------------------------");
			error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log("--------------------------------------------------------------------------");
      $result = false;
    }

	if (DEF_BDD != "ORACLE") {
		$sql = " UPDATE cms_page SET html_page=".$db->qstr($this->html_page)." WHERE id_page=".$this->id_page;

		$rs = $db->Execute($sql);
	}
	else {
		// spécifique ORACLE
		// le champ CLOB est envoyé après
		// car s'il est trop gros > 4000, la requete plante en ORA-01704
		// a_voir sponthus : ajouter un test sur la longueur de la chaine, sinon passer en INSERT
		$stmt = OCIParse($db->_connectionID, "UPDATE cms_page SET html_page=:gros_champ_clob WHERE id_page=".$this->id_page);
		OCIBindByName($stmt, ":gros_champ_clob", $this->html_page, -1);
		OCIExecute($stmt); 
	}

	$rs->Close();
    return $result;

}


// UPDATE
function cms_page_update() 
{
	global $db;

	if(! (($this->id_page !=null) && ($this->id_page>0)) )
		return false;
		
	$sql = " UPDATE cms_page";
	$sql.= " SET name_page = '".to_dbquote($this->name_page)."', ";
	$sql.= " gabarit_page = '".$this->gabarit_page."', ";

	if (DEF_BDD == "ORACLE" || DEF_BDD == "POSTGRES") {
	
		$datemep = date("Y/m/d/H:m:s");
		$datemep = split('/', $datemep);
		$datemep = "to_date('".$datemep[2]."/".$datemep[1]."/".$datemep[0]."', 'dd/mm/yyyy')";
	
	} else if (DEF_BDD == "MYSQL") {
	
		$datemep = "str_to_date('".getDateNow()."', '%d/%m/%Y')";
	}

	$sql.=" dateupd_page = $datemep, ";


	$sql.= " datemep_page = ".$this->datemep_page.", ";
	$sql.= " valid_page = ".$this->valid_page.", ";
	$sql.= " width_page = ".$this->width_page.", ";
	$sql.= " height_page = ".$this->height_page.", ";
	$sql.= " id_site = ".$this->id_site.", ";
	$sql.= " toutenligne_page = ".$this->toutenligne_page.", ";
	$sql.= " existeligne_page = ".$this->existeligne_page.", ";	
	$sql.= " iscustom = ".$this->iscustom.", ";	
	$sql.= " theme = ".$this->theme;	
	$sql.= " WHERE id_page = ".$this->id_page;

	$rs = $db->Execute($sql);

//print("<br>$sql");

	if($rs) {
		$result = $this->id_page;
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > cms_page_update";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("--------------------------------------------------------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------------------------------------");
		$result = false;
	}

	if (DEF_BDD != "ORACLE") {
		$sql = "UPDATE cms_page SET html_page=".$db->qstr($this->html_page)." WHERE id_page=".$this->id_page;
		$rs = $db->Execute($sql);
	}
	else {
		// spécifique ORACLE
		// le champ CLOB est envoyé après
		// car s'il est trop gros > 4000, la requete plante en ORA-01704
		// a_voir sponthus : ajouter un test sur la longueur de la chaine, sinon passer en INSERT
		$stmt = OCIParse($db->_connectionID, "UPDATE cms_page SET html_page=:gros_champ_clob WHERE id_page=".$this->id_page);
		OCIBindByName($stmt, ":gros_champ_clob", $this->html_page, -1);
		OCIExecute($stmt); 
	}
	$rs->Close();
    return $result;
}


//////////////////////////////
// REGENERATION DE PAGE    
// 
// Régénère une page à partir de l'objet page
// Modifie le HTML et les dates de MAJ
//////////////////////////////

function cms_page_regenerate() {

	global $db;

	
	// Recherche toutes les briques liées à la page (briques fixes / éditables / zone édit)
	
	// Utilisation du divArray
	$divArray = getPageStructure($this->id_page);
	
	// Préparation du divArray ;-)
	$divArray = array_values ($divArray); // le divArray avait pour indice les ID de briques , or ça coit être l'indice par défaut (0, 1, 2...)		
	$divArray["id_gabarit"] = $this->gabarit_page;
	$divArray["valid"] = 1;
		
	//----------------------------------------
	// ANALYSE DU DIV_ARRAY
	// 

	// 1. vérification que TOUTES les briques editables soient EN LIGNE
	//    il faut qu'il existe pour chaque brique une version dans CMS_ARCHI_CONTENT en ligne
	// 
	// 2. dans chacune de ces briques mettre le html de son équivalent dans CMS_ARCHI_CONTENT
	//
	// si une brique éditable n'a pas de contenu "en ligne" dans cms_archi => on fait rien

	// 1. briques en ligne
	$bExisteLigne = ifExisteligne($this->id_page);

	// 2. remplacement HTML
	// le remplacement HTML pourrait être fait ici sauf que dans pages.lib.php > function generatePage
	// on fait un buildDivArrayPreview qui récupère les contenus HTML des cms_content de la BDD
	// donc le remplacement HTML est fait directement dans pages.lib.php > function generatePage
	
	//-----------------------------------------

	//print("<br>id_page=>".$this->id_page);
	$oInfos_page = new Cms_infos_page();
	$oInfos_page->getCmsinfospages_with_pageid($this->id_page);

	// génération de la page si tous les composants ont une version en ligne

	if ($bExisteLigne) {
		// on génère la page
		// ici en BDD : on fait un nouveau contenu avec les briques en ligne
		$content = generatePage($divArray, $this, $oInfos_page);

	}
	else {
		// on ne génère pas la page
		// on renvoie le contenu actuel de l'objet page
		$content = $this->html_page;
	}

// A uniformiser => là c'est pas très propre!
	if (DEF_BDD == "ORACLE" || DEF_BDD == "POSTGRES") {
	
		$datemep = date("Y/m/d/H:m:s");
		$datemep = split('/', $datemep);
		$datemep = "to_date('".$datemep[2]."/".$datemep[1]."/".$datemep[0]."', 'dd/mm/yyyy')";
	
	} else if (DEF_BDD == "MYSQL") {
	
		$datemep = "str_to_date('".getDateNow()."', '%d/%m/%Y')";
	}


	$this->dateupd_page=datemep;
	$this->datemep_page="'".$this->datemep_page."'"; // A MODIFIER dans le update (et voir les conséquences) => Ouu ce que c crade
	$this->isgenerated_page=1;
	$this->html_page=$content;

	$this->toutenligne_page = ifTousEnLigne($this->id_page);


	$this->cms_page_update();

	return $content;
}


// Ecrit une page à partir de l'objet page
// Récupère le HTML et créé un page physiquement au bon endroit
function cms_page_write() {
	return generateFile($this);
}

// récupère toutes les pages VALIDES liées à un gabarit
function getPagesFromGabarit() {

	global $db;
	$result = array();

	$sql = " SELECT id_page";
	$sql.= " FROM cms_page";
	$sql.= " WHERE gabarit_page = '".$this->getId_page()."' AND valid_page=1";
	if (DEF_BDD != "ORACLE") $sql.= ";";

//print("<br>$sql");

	$rs = $db->Execute($sql);
	if($rs) {

		while(!$rs->EOF) {
			array_push($result, $rs->fields[n('id_page')]);
			$rs->MoveNext();
		}
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > getPagesFromGabarit";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);

  		$result = false;
	}
	$rs->Close();
    return $result;
}

// Récupération du répertoire courant de la page
function getPageDirectory() { 
	$infoDir = getFolderInfos($this->nodeid_page);

	if($infoDir["id"]=="0"){ // C'est la racine => ajout du minisite
		$oSite = new Cms_site($this->id_site);
		$infoDir["path"]= $infoDir["path"].$oSite->get_rep()."/";
	}	
	if($this->isgabarit_page=="1") return "";
	else return $infoDir["path"];
}


} //class 


///////////////////////////////////
///////////////////////////////////
// FONCTIONS HORS CLASSES
///////////////////////////////////
///////////////////////////////////

// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page/list_cms_page.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page/maj_cms_page.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page/show_cms_page.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page/rss_cms_page.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page/xml_cms_page.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page/xmlxls_cms_page.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page/export_cms_page.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_page/import_cms_page.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}



///////////////////////////////////
// fonction qui regarde si toutes 
// les briques editables d'une page sont en ligne
///////////////////////////////////

function ifTousEnLigne($idPage)
{
	// Utilisation du divArray
	$divArray = getPageStructure($idPage);

	// Préparation du divArray ;-)
	$divArray = array_values ($divArray); // le divArray avait pour indice les ID de briques , or ça coit être l'indice par défaut (0, 1, 2...)		
	$oPage = new Cms_page($idPage);
	$divArray["id_gabarit"] = $oPage->gabarit_page;
	$divArray["valid"] = 1;

	// taille du div_array pour cette page :: ensemble des briques de cette page

	if ($divArray[0]['id'] != "") $eIdDivarray = 1;
	else $eIdDivarray = 0;

	// composants
	if ($eIdDivarray > 0) $divArray_tosee = buildDivArrayPreview($divArray);

	// tous les div_array
	$bTousEnLigne = 1;
	foreach($divArray_tosee as $k => $v) {
		if(is_array($v)){
	
			$oContent = new Cms_content();
			$oArchi = new Cms_archi_content();
			
			$oContent->initValues($v['id']);
			$oArchi = getArchiWithIdContent($oContent->getId_content());

			// si c'est une brique editable
			if ($oContent->getIsbriquedit_content()) {
	
				// s'il existe une version en ligne de CMS_CONTENT
				// ET que cms_content est en ligne
				// -> alors tout est en ligne, pas de version de travail en cours
				
				// teste ici si cms_content != ligne ou archi != cms_archi_ligne
				
				$sStatut_archi = $oArchi->getStatut_archi();
				$sStatut_content = $oContent->getStatut_content();

				if ($sStatut_content != DEF_ID_STATUT_LIGNE || $sStatut_archi != DEF_ID_STATUT_LIGNE) {

					$bTousEnLigne = 0;
				}
			}
			
		}
	}

	return $bTousEnLigne;
}




///////////////////////////////////
// fonction qui regarde s'il existe une version en ligne
// de toutes les briques editables d'une page ->
// il existera donc forcément une version en ligne de cette page
///////////////////////////////////

function ifExisteligne($idPage)
{
	// Utilisation du divArray
	$divArray = getPageStructure($idPage);

	// Préparation du divArray ;-)
	$divArray = array_values ($divArray); // le divArray avait pour indice les ID de briques , or ça coit être l'indice par défaut (0, 1, 2...)	
	$oPage = new Cms_page($idPage);
	$divArray["id_gabarit"] = $oPage->gabarit_page;	
	$divArray["valid"] = 1;

	// taille du div_array pour cette page :: ensemble des briques de cette page

	if (isset($divArray[0]['id'])	&&	$divArray[0]['id'] != "") $eIdDivarray = 1;
	else $eIdDivarray = 0;

	// composants
	if ($eIdDivarray > 0) $divArray_tosee = buildDivArrayPreview($divArray);

	// tous les div_array
	$bExisteLigne = 1;
	foreach($divArray_tosee as $k => $v) {
		if(is_array($v)){
	
			$oContent = new Cms_content();
			$oArchi = new Cms_archi_content();
			
			$oContent->initValues($v['id']);
			$oArchi = getArchiWithIdContent($oContent->getId_content());

			// si c'est une brique editable
			if ($oContent->getIsbriquedit_content()) {
	
				// s'il existe une version en ligne de CMS_CONTENT
				$sStatut = $oArchi->getStatut_archi();
				if ($sStatut != DEF_ID_STATUT_LIGNE) $bExisteLigne = 0;
			}
			
		}
	}

	return $bExisteLigne;
}



///////////////////////////////////
// renommage d'un gabarit ->
// mise à jour de toutes les pages utilisant ce gabarit
///////////////////////////////////

function updateRenommageGabarit($sOldName, $sNewName)
{
	global $db;

	$sql = " UPDATE cms_page";
	$sql.= " SET gabarit_page = '".$sNewName."'";
	$sql.= " WHERE gabarit_page = '".$sOldName."'";

	$rs = $db->Execute($sql);
	
	if($rs) {
		$result = true;
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > updateRenommageGabarit($sOldName, $sNewName)";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("--------------------------------------------------------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------------------------------------");
		$result = false;
	}
	$rs->Close();
    return $result;

}


///////////////////////////////////
// fonction renvoyant une liste de pages
// avec des critères de recherche
///////////////////////////////////

function getListPages($aRecherche, $sOrderBy="", $eLimit)
{

	$aPage = dbGetListRech("Cms_page", $aRecherche, $sOrderBy, $eLimit);

	return $aPage;

}



///////////////////////////////////
// fonction renvoyant le nombre de pages d'une requete
// avec des critères de recherche
///////////////////////////////////

function getCountListPages($aRecherche, $sOrderBy="")
{

	$ePage = dbGetCountIdListRech("Cms_page", $aRecherche, $sOrderBy);

	return $ePage;

}



///////////////////////////////////
// fonction mettant à jour uniquement l'attribut tout en ligne
///////////////////////////////////

function updateToutenligne($idPage)
{
	global $db;

	$bToutenligne = ifTousEnLigne($idPage);

	$sql = " UPDATE cms_page";
	$sql.= " SET toutenligne_page = ".$bToutenligne;
	$sql.= " WHERE id_page = ".$idPage;

//print("<br>$sql");

	$rs = $db->Execute($sql);
	
	if($rs) {
		$result = $bToutenligne;
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > updateToutenligne($idPage)";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("--------------------------------------------------------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------------------------------------");
		$result = false;
	}

	// pour bien comprendre....

	// si la page n'a pas de version en ligne -> supprimer le fichier physique ??
	
	// normalement ce n'est pas la peine car :
	// 	1. si on créé une page 
	// 		:: il n'existe pas encore de version en ligne de la page
	//  2. si on modifie une page qui a des content en ligne 
	//		:: il existe une version en ligne de la page
	//  3. si on modifie une page qui n'a que des content en brouillon
	//		:: les content précédemment en ligne existent tjs et donc la version de la page en ligne aussi

	// il n'existe donc pas de cas où l'on doive supprimer le fichier physique d'une page en ligne 
	// car même dans le cas de content en BROUILLON, les content en ligne doivent tjrs exister
	$rs->Close();
    return $result;

}


///////////////////////////////////
// fonction mettant à jour uniquement l'attribut existe ligne
///////////////////////////////////

function updateExisteligne($idPage)
{
	global $db;

	$bExisteligne = ifExisteLigne($idPage);

	$sql = " UPDATE cms_page";
	$sql.= " SET existeligne_page = ".$bExisteligne;
	$sql.= " WHERE id_page = ".$idPage;

//print("<br>$sql");

	$rs = $db->Execute($sql);
	
	if($rs) {
		$result = $bExisteligne;
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > updateExisteligne($idPage)";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("--------------------------------------------------------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("--------------------------------------------------------------------------");
		$result = false;
	}
	$rs->Close();
    return $result;

}



///////////////////////////////////
// fabrique un preview d'une page de travail
// cette page n'existe pas physiquement car toutes ses briques ne sont pas en ligne

// cette page de travail est fabriquée avec soit :
// 1. les briques en ligne s'il y en a
// 2. les briques en ligne et une brique spécifiée en travail
///////////////////////////////////

function pageTravail($idPage, $sMode, $eIdTravail)
{
	// Utilisation du divArray
	$divArray = getPageStructure($idPage);

	// page et info page
	$oPage = new Cms_page($idPage); 
	//$oInfoPage = new Cms_infos_page($idPage); 
	$oInfoPage = new Cms_infos_page();
	$oInfoPage->getCmsinfospages_with_pageid($idPage);  
	// Préparation du divArray ;-)
	$divArray = array_values ($divArray); // le divArray avait pour indice les ID de briques , or ça coit être l'indice par défaut (0, 1, 2...)		

	// génération du HTML de la page
	$contentPage = generatePage($divArray, $oPage, $oInfoPage, $sMode, $eIdTravail);

	return $contentPage;
}



///////////////////////////////////
// fonction affichant les icones de preview d'une page
// preview d'une page de travail
// preview d'une page en ligne
///////////////////////////////////

function afficheIconePreviewPage($idContent, $sNature, $idPage, $sNomPage, $bToutenligne_page, $bExisteligne_page, $sUrlPageLigne)
{

/*
PROBLEME : 

Au lieu d'avoir :: 	http://.../content/BBMIX/Musiques%20dans%20la%20ville/index.php
On a :: 			http://.../content/BBMIX/Musiques%2520dans%2520la%2520ville/index.php

je ne sais pas bien à quoi c'est du...
sûrement un double encodage d'url...
genre quand tu encode un espace, ça donne %20..et si tu réencode un %20, ça donne un %2520 
qui lui n'est pas interprété par le navigateur...un truc dans le genre je pense.
*/

$sUrlPageLigne = str_replace("%2F","/", rawurlencode($sUrlPageLigne));

// on remplace ce réencodage faute de mieux
$sUrlPageLigne = str_replace("%2520", "%20", $sUrlPageLigne);
$sUrlPageLigne = str_replace("%2527", "%27", $sUrlPageLigne);
$sUrlPageLigne = str_replace("%25E9", "%E9", $sUrlPageLigne);
//print("<br>sUrlPageLigne=>".$sUrlPageLigne);


	// preview de la page de travail
	if (!$bToutenligne_page) {
		/*print("<?php ");
		print("// s'il toutes les briques ne sont pas en ligne");
		print("// alors il existe une version de travail de cette page");
		print(" ?>");*/

		print("<a href=\"/backoffice/cms/previewPageTravail.php?idPage=".$idPage."&idContent=".$idContent."&nature=".$sNature."\"");
		print(" target=\"_blank\" title=\"Voir la page en attente\"><img ");
		print(" src=\"/backoffice/cms/img/page_attente.gif\" border=\"0\"></a>&nbsp;");
	}
	
	// preview de la page en ligne
	if ($bExisteligne_page) {
		/*print("<?php ");
		print("// si toutes les briques sont en ligne");
		print("// alors il existe une version fichier de la page (la page a été générée)");
		print(" ?>");*/
		
		print("<a href=\"".$sUrlPageLigne."\" target=\"_blank\" title=\"Voir la page en ligne\"><img ");
		print("src=\"/backoffice/cms/img/2013/icone/visualiser.png\" border=\"0\"></a>&nbsp;");
	} 
	
}


///////////////////////////////////
// récupère toutes les pages VALIDES liées à un PLUSIEURS gabarits
// + les gabarits eux mêmes
///////////////////////////////////

function getPagesFromXGabarits($aIdGab, $idSite) {

	global $db;
	$result = array();

	$sql = " SELECT id_page";
	$sql.= " FROM cms_page";
	$sql.= " WHERE valid_page=1 AND ";

	// grande parenthèse englobant les pages des gabarits ET les gabarits
	if (sizeof($aIdGab)) $sql.=" ( ";

	// première parenthèse englobant les pages des gabarits
	if (sizeof($aIdGab)) $sql.=" ( ";
	
	// pages pour les gabarits
	for ($p=0; $p<sizeof($aIdGab); $p++) {
		$sql.= " gabarit_page = '".$aIdGab[$p]."' ";
		if ($p != sizeof($aIdGab)-1) $sql.= " OR ";
		else $sql.=" ) "; // fin première parenthèse
	}
	
	// deuxième parenthèse englobant les gabarits
	if (sizeof($aIdGab)) $sql.=" OR ( ";
	 
	// gabarits eux mêmes
	for ($p=0; $p<sizeof($aIdGab); $p++) {
		$sql.= " id_page = ".$aIdGab[$p]." ";
		if ($p != sizeof($aIdGab)-1) $sql.= " OR ";
		else $sql.=" ) "; // fin deuxième parenthèse
	}
	
	
	
	// fin grande parenthèse
	if (sizeof($aIdGab)) $sql.=" ) ";	
	
	$sql.= " AND id_site=$idSite ";
	
	$sql.=" ORDER BY isgabarit_page DESC, gabarit_page ASC";
	
	if (DEF_BDD != "ORACLE") $sql.= ";";

//print("<br>$sql");

	$rs = $db->Execute($sql);
	if($rs) {

		while(!$rs->EOF) {
			array_push($result, $rs->fields[n('id_page')]);
			$rs->MoveNext();
		}
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > getPagesFromXGabarits";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);

  		$result = false;
	}
	$rs->Close();
    return $result;
}


///////////////////////////////////
// recherche d'une page avec un noeud, un site et un libelle de page
// renvoie juste l'id de la page
///////////////////////////////////

function getIdPageWithUrl($idNode, $idSite, $sPage)
{
	global $db;
	$result = true;

	$sql = " SELECT id_page";
	$sql.= " FROM cms_page";
	$sql.= " WHERE nodeid_page = $idNode";
	$sql.= " AND id_site=".$idSite;
	$sql.= " AND lower(name_page)='".$sPage."'";
	$sql.= " AND valid_page=1";

//print("<br>$sql");

	if (DEF_BDD != "ORACLE") $sql.= ";";

	$rs = $db->Execute($sql);
	if($rs) {

		$result = $rs->fields[n('id_page')];
						
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
	}
	$rs->Close();	
    return $result;
}



///////////////////////////////////
// analyse une url complète 
// retour : tableau avec id_site et id_page
///////////////////////////////////

function analyseUrlToGetIdPage($sUrlComplete)
{
	// chemin de la page appelante
	$sPath = $sUrlComplete;
		
	// à partir de content/
	$sPath = strstr($sPath, "content/");

	// on enlève content/
	$sPath = str_replace("content/", "", $sPath);

	// découpage des répertoires
	$aPath = split("/", $sPath);

	// recherche des noeuds et de l'id page
	$idSite = "";
	$sRep = "";
	for ($p=0; $p<sizeof($aPath); $p++) {

		// on est sur un répertoire
		$bFile = strstr ($aPath[$p], ".php");

		if (!$bFile) {
			if ($aPath[$p] != "") {

				// le premier répertoire après content est le SITE
				if ($idSite == "") {
					// objet Site
					$aGetterWhere = array();
					$aValeurChamp = array();
					$aGetterWhere[] = "getName_site";
					$aValeurChamp[] = $aPath[$p];
					$sGetterOrderBy = "getName_site";
					$aSite = dbGetObjectsFromFieldValue("Cms_site", $aGetterWhere, $aValeurChamp, $sGetterOrderBy);
					if (sizeof($aSite) == 0) {
						print("<br>Erreur interne de programme");
						error_log("ERROR :: ANALYSE URL :: plusieurs site avec le même nom rep ".$aPath[$p]." ???");
						exit();
					}
					$oSite = $aSite[0];

					$idSite = $oSite->get_id();
					$sRep = "/".$oSite->get_rep();

					// tous les noeuds pour un site
					$aGetterWhere = array();
					$aValeurChamp = array();
					$aGetterWhere[] = "getId_site";
					$aValeurChamp[] = $idSite;
					$sGetterOrderBy = "getId_site";
					$aNode = dbGetObjectsFromFieldValue("Cms_arbo_pages", $aGetterWhere, $aValeurChamp, $sGetterOrderBy);
					if (sizeof($aNode) == 0) {
						print("<br>Erreur interne de programme");
						error_log("ERROR :: ANALYSE URL :: aucun noeud pour le site ".$idSite." ???");
						exit();
					}
				} else  {
					// construction du chemin avec tous les répertoires uniquement
					$sRep.= "/".$aPath[$p];
					
				} // fin :: if ($idSite == "") {
			} // fin :: if ($aPath[$p] != "") {
		} else {
			// on est sur le fichier

			// il n'y a plus de répertoire, il faut finir la chaine avec "/"
			$sRep.="/";

/*
			// nom de la page
			if (substr($aPath[$p], 0, 8) == "portrait" || substr($aPath[$p], 0, 5) == "index")
				$sPage = strtolower(substr($aPath[$p], 0, strlen($aPath[$p])-4));
			else if (substr($aPath[$p], 0, 5) == "fiche")
				$sPage = strtolower(strrchr($aPath[$p], ".php"));

			// recherche de l'id noeud avec le nom du répertoire complet
			for ($t=0; $t<sizeof($aNode); $t++) {
				$oNode = $aNode[$t];
				if (strtolower($sRep) == strtolower($oNode->getAbsolute_path_name())) {
					$eNode = $oNode->getNode_id();
					$t = sizeof($aNode);// fin de la boucle
				}
			}

			$eId = getIdPageWithUrl($eNode, $idSite, $sPage);

			// recherche de l'id de la page avec l'id_dite, le noeud et le nom de la page
			// avec tous ses paramètres il ne doit y avoir qu'une seule page correspondante
			if ($eId == "") {
				print("<br>Erreur interne de programme");
				error_log("ERROR :: ANALYSE URL :: pas de page pour le site ".$idSite.", le noeud ".$eNode." et le libelle de page ".$aPath[$p]." ???");
				exit();
			}

			$idPage = $eId;
*/		
		} // fin :: if (substr($aPath, -1, 4) != ".php") {
	}

	$aReturn[] = $idSite;
	$aReturn[] = $idPage;
	
	return $aReturn;
}



//////////////////////////////////
// obtention d'un chemin aere d'une page
//////////////////////////////////

function getUrlWithIdPage($idPage)
{
	global $db;
	$result = true;

	$sql = " SELECT cms_arbo_pages.node_absolute_path_name, cms_page.name_page";
	$sql.= " FROM cms_page, cms_arbo_pages";
	$sql.= " WHERE cms_page.id_page=".$idPage;
	$sql.= " AND cms_arbo_pages.node_id = cms_page.nodeid_page";

//print("<br>$sql");

	if (DEF_BDD != "ORACLE") $sql.= ";";

	$rs = $db->Execute($sql);

	if($rs && !$rs->EOF) {

		$result = $rs->fields[n('node_absolute_path_name')].$rs->fields[n('name_page')].".php";
						
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > getUrlWithIdPage($idPage)";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);

	  $result = false;
	}
	$rs->Close();

	// arere le chemin reçu (enlève le site, espace les /)
	$result = cheminAere($result);
	
    return $result;
}

function getUrlWithIdPage_2($idPage)
{
	global $db;
	$result = true;

	$sql = " SELECT cms_arbo_pages.node_absolute_path_name, cms_page.name_page";
	$sql.= " FROM cms_page, cms_arbo_pages";
	$sql.= " WHERE cms_page.id_page=".$idPage;
	$sql.= " AND cms_arbo_pages.node_id = cms_page.nodeid_page";

//print("<br>$sql");

	if (DEF_BDD != "ORACLE") $sql.= ";";

	$rs = $db->Execute($sql);

	if($rs && !$rs->EOF) {

		$result = $rs->fields[n('node_absolute_path_name')].$rs->fields[n('name_page')].".php";
						
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_page.class.php > getUrlWithIdPage($idPage)";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);

	  $result = false;
	}
	$rs->Close();

	// arere le chemin reçu (enlève le site, espace les /)
	//$result = cheminAere($result);
	
    return $result;
}
 
?>