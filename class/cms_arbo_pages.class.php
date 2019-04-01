<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// patch de migration
if (!ispatched('cms_arbo_pages')){
	$rs = $db->Execute('DESCRIBE `cms_arbo_pages`');
	if ($rs->_numOfRows == 7){
		$rs = $db->Execute('ALTER TABLE `cms_arbo_pages` ADD `node_tag` VARCHAR( 32 ) NOT NULL AFTER `node_description` ;');
	}
	
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_arbo_pages`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			$rs->MoveNext();
		}
		if (!in_array('node_absolute_path_name', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_arbo_pages` CHANGE `absolute_path_name` `node_absolute_path_name` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ' ' ");
		}
		if (!in_array('node_id_site', $names)) {
			$rs = $db->Execute("ALTER TABLE `cms_arbo_pages` CHANGE `id_site` `node_id_site` INT( 5 ) NOT NULL DEFAULT '0' ");
		}	
	}	
}
/*======================================

sponthus 29/06/05
objet de BDD cms_arbo_pages :: class Cms_arbo_pages

==========================================*/

class cms_arbo_pages
{

var $node_id;
var $node_parent_id;
var $node_libelle;
var $absolute_path_name;
var $node_order;
var $node_description;
var $node_tag;
var $id_site;

var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_arbo_pages\" libelle=\"Noeuds d'arborescence\" prefix=\"node\" display=\"libelle\" abstract=\"id\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" asso=\"cms_assoprependarbopages\" />
<item name=\"parent_id\" type=\"int\" length=\"11\" default=\"0\" list=\"true\" order=\"true\" fkey=\"cms_arbo_pages\" />
<item name=\"libelle\" libelle=\"Libelle\" type=\"varchar\" length=\"32\" list=\"true\" order=\"true\" />
<item name=\"absolute_path_name\" libelle=\"absolute path name\" type=\"varchar\" length=\"128\" />
<item name=\"order\" type=\"int\" length=\"11\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"description\" libelle=\"description\" type=\"varchar\" length=\"512\" />
<item name=\"tag\" libelle=\"tag\" type=\"varchar\" length=\"32\" />
<item name=\"id_site\" libelle=\"site\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" oblig=\"true\" fkey=\"cms_site\" />
<langpack lang=\"fr\">
<norecords>Pas de donnée à afficher</norecords>
</langpack>
</class>";
/*
var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_arbo_pages\" libelle=\"Noeuds d'arborescence\" prefix=\"node\" display=\"libelle\" abstract=\"description\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" asso=\"cms_assoprependarbopages\" />
<item name=\"parent_id\" type=\"int\" length=\"11\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"libelle\" libelle=\"Libelle\" type=\"varchar\" length=\"32\" list=\"true\" order=\"true\" />
<item name=\"absolute_path_name\" libelle=\"absolute path name\" type=\"varchar\" length=\"128\" />
<item name=\"order\" type=\"int\" length=\"11\" default=\"0\" list=\"true\" order=\"true\" />
<item name=\"description\" libelle=\"description\" type=\"varchar\" length=\"512\" />
<item name=\"tag\" libelle=\"tag\" type=\"varchar\" length=\"32\" />
<item name=\"id_site\" libelle=\"site\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" oblig=\"true\" fkey=\"cms_site\" />
<langpack lang=\"fr\">
<norecords>Pas de donnée à afficher</norecords>
</langpack>
</class>";*/

// constructeur
function __construct($id=null) 
{
	global $db;
	$this->dbConn = &$db;
	if($id!=null) {
		$this->initValues($id);
	} else {
		$this->node_id = -1;
		$this->node_parent_id='';
		$this->node_libelle='';
		$this->absolute_path_name='';
		$this->node_order=0;
		$this->node_description=NULL;
		$this->node_tag=NULL;
		$this->id_site='';
	}
}

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("node_id", "entier", "getNode_id", "setNode_id");
	$laListeChamps[]=new dbChamp("node_parent_id", "entier", "getNode_parent_id", "setNode_parent_id");
	$laListeChamps[]=new dbChamp("node_libelle", "text", "getNode_libelle", "setNode_libelle");
	$laListeChamps[]=new dbChamp("node_absolute_path_name", "text", "getAbsolute_path_name", "setAbsolute_path_name");
	$laListeChamps[]=new dbChamp("node_order", "entier", "getNode_order", "setNode_order");
	$laListeChamps[]=new dbChamp("node_description", "text", "getNode_description", "setNode_description");
	$laListeChamps[]=new dbChamp("node_tag", "text", "get_tag", "set_tag");
	$laListeChamps[]=new dbChamp("node_id_site", "entier", "getId_site", "setId_site");
	
	return($laListeChamps);
}

// getters
function getNode_id() { return($this->node_id); } // obsolète
function get_id() { return($this->node_id); }// ajout compatibilité autoClass
function getNode_parent_id() { return($this->node_parent_id); } 
function get_parent_id() { return($this->node_parent_id); } 
function getNode_libelle() { return($this->node_libelle); } // obsolète
function get_libelle() { return($this->node_libelle); } // ajout compatibilité autoClass
function getAbsolute_path_name() { return($this->absolute_path_name); } // obsolète
function get_absolute_path_name() { return($this->absolute_path_name); } // ajout compatibilité autoClass
function getNode_order() { return($this->node_order); } // obsolète
function get_order() { return($this->node_order); } // ajout compatibilité autoClass
function getNode_description() { return($this->node_description); }	// obsolète
function get_description() { return($this->node_description); }		// ajout compatibilité autoClass
function get_tag() { return($this->node_tag); }		// ajout compatibilité autoClass
function getId_site() { return($this->id_site); }
function get_id_site() { return($this->id_site); }

// setters
function setNode_id($c_node_id) { return($this->node_id=$c_node_id); } // obsolète
function set_id($c_node_id) { return($this->node_id=$c_node_id); } // ajout compatibilité autoClass
function setNode_parent_id($c_node_parent_id) { return($this->node_parent_id=$c_node_parent_id); }
function set_parent_id($c_node_parent_id) { return($this->node_parent_id=$c_node_parent_id); }
function setNode_libelle($c_node_libelle) { return($this->node_libelle=$c_node_libelle); } // obsolète
function set_libelle($c_node_libelle) { return($this->node_libelle=$c_node_libelle); }// ajout compatibilité autoClass
function setAbsolute_path_name($c_absolute_path_name) { return($this->absolute_path_name=$c_absolute_path_name); } 
function set_absolute_path_name($c_absolute_path_name) { return($this->absolute_path_name=$c_absolute_path_name); } 
function setNode_order($c_node_order) { return($this->node_order=$c_node_order); }
function set_order($c_node_order) { return($this->node_order=$c_node_order); }
function setNode_description($c_node_description) { return($this->node_description=$c_node_description); }	// obsolète
function set_description($c_node_description) { return($this->node_description=$c_node_description); }		// ajout compatibilité autoClass
function set_tag($c_node_tag) { return($this->node_tag=$c_node_tag); }		// ajout compatibilité autoClass
function setId_site($c_id_site) { return($this->id_site=$c_id_site); } 
function set_id_site($c_id_site) { return($this->id_site=$c_id_site); } 

// autres getters
function getGetterPK() { return("getNode_id"); }
function getSetterPK() { return("setNode_id"); }
function getFieldPK() { return("node_id"); }
function getTable() { return("cms_arbo_pages"); }
function getClasse() { return("cms_arbo_pages"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("id"); }


// initialisation, obtention d'un  objet cms_content
function initValues($id) 
{
		global $db;
		$result = true;

		$sql = " SELECT * FROM cms_arbo_pages WHERE node_id = $id";
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);
		if($rs && !$rs->EOF) {

			$this->node_id = $rs->fields[n('node_id')];
			$this->node_parent_id = $rs->fields[n('node_parent_id')];
			$this->node_libelle = $rs->fields[n('node_libelle')];
			$this->absolute_path_name = $rs->fields[n('node_absolute_path_name')];
			$this->node_order = $rs->fields[n('node_order')];
			$this->node_description = $rs->fields[n('node_description')];
			$this->node_tag = $rs->fields[n('node_tag')];
			$this->id_site = $rs->fields[n('node_id_site')];

		} else {
			echo "<br />Cms_arbo_pages > initValues";
			echo "<br />Erreur interne";
			echo "<br /><strong>$sql</strong>";
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}

} // class
?>
