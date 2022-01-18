<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 01/06/05
objet de BDD cms_form :: class Cms_form

function getChampsForm($id)

==========================================*/

$rs = $db->Execute('DESCRIBE `cms_formulaire`');

if (isset($rs->_numOfRows)){ 
	if ($rs->_numOfRows == 6){
		$rs2 = $db->Execute('ALTER TABLE `cms_formulaire` ADD `post_form` TEXT  DEFAULT \'\' AFTER `ar_form` ;'); 	  
	}
	 
} 
 


class Cms_form
{

var $id_form;
var $name_form;
var $desc_form;
var $comm_form;
var $ar_form;
var $post_form;
var $id_site;

var $sMySql = "CREATE TABLE IF NOT EXISTS `cms_formulaire` (
  `id_form` int(11) NOT NULL default '0',
  `name_form` varchar(255) NOT NULL default '',
  `desc_form` varchar(255) NOT NULL default '',
  `comm_form` text NOT NULL,
  `ar_form` text NOT NULL,
  `post_form` text NOT NULL,
  `id_site` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id_form`),
  KEY `id_site` (`id_site`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_formulaire\" libelle=\"Formulaire\" prefix=\"\" display=\"name_form\" abstract=\"id_form\">
<item name=\"id_form\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\"  asso=\"cms_assobo_userscms_statut_content\"/>
<item name=\"name_form\" libelle=\"Nom\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"desc_form\" libelle=\"Description\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"comm_form\" libelle=\"Commentaires\" type=\"varchar\" length=\"255\" nohtml=\"true\" option=\"password\"/>
<item name=\"ar_form\" libelle=\"Texte AR\" type=\"varchar\" length=\"14\" nohtml=\"true\" />
<item name=\"post_form\" libelle=\"URL post\" type=\"varchar\" list=\"true\" order=\"true\" length=\"50\" nohtml=\"true\" /> 
<item name=\"id_site\" libelle=\"Site\" type=\"int\" length=\"11\" notnull=\"true\"  list=\"true\" fkey=\"cms_site\" /> 
<langpack lang=\"fr\">
<norecords>Pas d\'user à afficher</norecords>
</langpack>
</class>";



// constructeur
function __construct($id=null) 
{
	
	if (istable(get_class($this)) == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		//$this = dbGetObjectFromPK("Cms_form", $id);
		$tempThis = dbGetObjectFromPK("Cms_form", $id);
		$this->id_form = $tempThis->id_form;
		$this->name_form = $tempThis->name_form;
		$this->desc_form = $tempThis->desc_form;
		$this->comm_form = $tempThis->comm_form;
		$this->ar_form = $tempThis->ar_form;
		$this->post_form = $tempThis->post_form;
		$this->id_site = $tempThis->id_site;	
		$tempThis = null;
	} else {
		$this->id_form = -1;
		$this->name_form = '';
		$this->desc_form = '';
		$this->comm_form = '';
		$this->ar_form = '';
		$this->post_form = '';
		$this->id_site = -1;	
	}
}

// getters
function getId_form() { return($this->id_form); } 
function getName_form() { return($this->name_form); } 
function getDesc_form() { return($this->desc_form); } 
function getComm_form() { return($this->comm_form); } 
function getAr_form() { return($this->ar_form); } 
function getPost_form() { return($this->post_form); } 
function getId_site() { return($this->id_site); } 

function get_id_form() { return($this->id_form); } 
function get_name_form() { return($this->name_form); } 
function get_desc_form() { return($this->desc_form); } 
function get_comm_form() { return($this->comm_form); } 
function get_ar_form() { return($this->ar_form); } 
function get_post_form() { return($this->post_form); } 
function get_id_site() { return($this->id_site); } 

// setters
function setId_form($c_id_form) { return($this->id_form=$c_id_form); } 
function setName_form($c_name_form) { return($this->name_form=$c_name_form); } 
function setDesc_form($c_desc_form) { return($this->desc_form=$c_desc_form); } 
function setComm_form($c_comm_form) { return($this->comm_form=$c_comm_form); } 
function setAr_form($c_ar_form) { return($this->ar_form=$c_ar_form); } 
function setPost_form($c_post_form) { return($this->post_form=$c_post_form); } 
function setId_site($c_id_site) { return($this->id_site=$c_id_site); } 

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("id_form", "entier", "getId_form", "setId_form");
	$laListeChamps[]=new dbChamp("name_form", "text", "getName_form", "setName_form");
	$laListeChamps[]=new dbChamp("desc_form", "text", "getDesc_form", "setDesc_form");
	$laListeChamps[]=new dbChamp("comm_form", "text", "getComm_form", "setComm_form");
	$laListeChamps[]=new dbChamp("ar_form", "text", "getAr_form", "setAr_form");
	$laListeChamps[]=new dbChamp("post_form", "text", "getPost_form", "setPost_form");
	$laListeChamps[]=new dbChamp("id_site", "entier", "getId_site", "setId_site");
				
	return($laListeChamps);
}

// autres getters
function getGetterPK() { return("getId_form"); }
function getSetterPK() { return("setId_form"); }
function getFieldPK() { return("id_form"); }
function getTable() { return("cms_formulaire"); }
function getClasse() { return("Cms_form"); }
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
function getDisplay() { return("name_form"); }
function getAbstract() { return("name_form"); }

} //class 

// sélection des champs d'un formulaire
function getChampsForm($id)
{
	$aGetterWhere[] = "getId_form";
	$aValeurChamp[] = $id;
	
	$aChamps = dbGetObjectsFromFieldValue("Cms_champform", $aGetterWhere, $aValeurChamp, "getId_champ");
	
	return $aChamps;
}

// recherche d'un formulaire avec un nom
function checkUniqueName($oForm)
{
	$aGetterWhere[] = "getName_form";
	$aValeurChamp[] = $oForm->getName_form();

	$aForm = dbGetObjectsFromFieldValue("Cms_form", $aGetterWhere, $aValeurChamp, "getId_form");

	// si un formulaire du même nom a été trouvé
	if (newSizeOf($aForm) == 1) {
		if ($oForm->getId_form() != "") {
			// si l'on est en modif
			// le formulaire trouvé avec le même nom est celui sur lequel on est
			// donc contrôle d'unicité ok
			$result = true;
		} else {
			// on a trouvé un formulaire avec le même nom
			// et l'on n'est pas en modif
			// donc contrôle d'unicité ko
			$result = false;
		}
	} else {
		// pas de formulaire avec ce nom
		$result = true;
	}

	return $result;
}




?>