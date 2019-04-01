<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_statut_content :: class cms_statut_content

SQL mySQL:

DROP TABLE IF EXISTS cms_statut_content;
CREATE TABLE cms_statut_content
(
	sta_id			int (11) PRIMARY KEY not null,
	sta_libelle			varchar (255),
	sta_rank			int (11) not null,
	sta_value			int (11),
	sta_description			varchar (255),
	sta_statut			int not null
)

SQL Oracle:

DROP TABLE cms_statut_content
CREATE TABLE cms_statut_content
(
	sta_id			number (11) constraint sta_pk PRIMARY KEY not null,
	sta_libelle			varchar2 (255),
	sta_rank			number (11) not null,
	sta_value			number (11),
	sta_description			varchar2 (255),
	sta_statut			number not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_statut_content" libelle="Liste des alertes" prefix="sta" display="libelle" abstract="libelle">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" />
<item name="libelle" libelle="Libelle" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="rank" libelle="Rang" type="int" length="11" notnull="true"  nohtml="true" list="true" order="true" fkey="bo_rank" />
<item name="value" libelle="Valeur" type="int" length="11" list="false" order="false"  />
<item name="description" libelle="Description" type="varchar" length="255" list="false" order="false"/>
<item name="statut" type="int" notnull="true" list="true" order="true" default="DEF_CODE_STATUT_DEFAUT" />
</class>


==========================================*/

class cms_statut_content
{
var $id;
var $libelle;
var $rank;
var $value;
var $description;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_statut_content\" libelle=\"Liste des alertes\" prefix=\"sta\" display=\"libelle\" abstract=\"libelle\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" />
<item name=\"libelle\" libelle=\"Libelle\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"rank\" libelle=\"Rang\" type=\"int\" length=\"11\" notnull=\"true\"  nohtml=\"true\" list=\"true\" order=\"true\" fkey=\"bo_rank\" />
<item name=\"value\" libelle=\"Valeur\" type=\"int\" length=\"11\" list=\"false\" order=\"false\"  />
<item name=\"description\" libelle=\"Description\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\"/>
<item name=\"statut\" type=\"int\" notnull=\"true\" list=\"true\" order=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" />
</class>";

var $sMySql = "CREATE TABLE cms_statut_content
(
	sta_id			int (11) PRIMARY KEY not null,
	sta_libelle			varchar (255),
	sta_rank			int (11) not null,
	sta_value			int (11),
	sta_description			varchar (255),
	sta_statut			int not null
)

";

// constructeur
function __construct($id=null)
{
	 
	if (istable("cms_statut_content") == false){
		dbExecuteQuery($this->sMySql);
		
		$sqlinsert = "INSERT INTO `cms_statut_content` (`sta_id`, `sta_libelle`, `sta_rank`, `sta_value`, `sta_description`, `sta_statut`) VALUES
		(5, 'Alertes lors d''une modification par un rédacteur', 3, 2, 'REDAC - A valider', 4),
		(6, 'Alertes à chaque validation par un rédacteur', 3, 3, 'REDAC - validé', 4),
		(7, 'Alertes à chaque archivage par un rédacteur', 3, 5, 'REDAC - Archivé', 4),
		(1, 'Alertes à chaque mise à jour par un valideur', 2, 1, 'VALIDEUR - Mise à jour', 4),
		(2, 'Alertes à chaque demande de validation par un valideur', 2, 2, 'VALIDEUR - A valider', 4),
		(3, 'Alertes à chaque validation par un valideur', 2, 3, 'VALIDEUR - Validé', 4),
		(4, 'Alertes à chaque archivage par un valideur', 2, 5, 'VALIDEUR - Archivé', 4),
		(8, 'Alertes à chaque mise en attente par un rédacteur', 3, 1, 'REDAC - En attente', 4),
		(9, 'Alertes à chaque mise en attente par un valideur', 2, 1, 'VALIDEUR - En attente', 4),
		(10, 'Alertes à chaque modification d''un module par un rédacteur', 3, -1, '', 4),
		(11, 'Alertes à chaque modification d''un module par un valideur', 2, -1, '', 4);";
		
		dbExecuteQuery($sqlinsert);

	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id = -1;
		$this->libelle = "";
		$this->rank = -1;
		$this->value = -1;
		$this->description = "";
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Sta_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Sta_libelle", "text", "get_libelle", "set_libelle");
	$laListeChamps[]=new dbChamp("Sta_rank", "entier", "get_rank", "set_rank");
	$laListeChamps[]=new dbChamp("Sta_value", "entier", "get_value", "set_value");
	$laListeChamps[]=new dbChamp("Sta_description", "text", "get_description", "set_description");
	$laListeChamps[]=new dbChamp("Sta_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_libelle() { return($this->libelle); }
function get_rank() { return($this->rank); }
function get_value() { return($this->value); }
function get_description() { return($this->description); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_sta_id) { return($this->id=$c_sta_id); }
function set_libelle($c_sta_libelle) { return($this->libelle=$c_sta_libelle); }
function set_rank($c_sta_rank) { return($this->rank=$c_sta_rank); }
function set_value($c_sta_value) { return($this->value=$c_sta_value); }
function set_description($c_sta_description) { return($this->description=$c_sta_description); }
function set_statut($c_sta_statut) { return($this->statut=$c_sta_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("sta_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("sta_statut"); }
//
function getTable() { return("cms_statut_content"); }
function getClasse() { return("cms_statut_content"); }
function getDisplay() { return("libelle"); }
function getAbstract() { return("libelle"); }

// cherche tous les users d'un site
// plus les administrateurs




} //class

function getStatutByRankOperation($rank,$operation) {
 
	$sql = " SELECT *";
	$sql.= " FROM cms_statut_content";
	$sql.= " WHERE sta_rank=".$rank." AND sta_value=".$operation."";
	$sql.= " AND sta_statut = ".DEF_ID_STATUT_LIGNE;
	
	return (dbGetObjectsFromRequete("cms_statut_content", $sql));
}
 

?>