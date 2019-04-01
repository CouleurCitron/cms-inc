<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_diapo :: class cms_diapo

SQL mySQL:

DROP TABLE IF EXISTS cms_diapo;
CREATE TABLE cms_diapo
(
	img_id			int (11) PRIMARY KEY not null,
	img_titre			varchar (64),
	img_src			varchar (255),
	img_vignette			varchar (255),
	img_metadata			int (11),
	img_cms_site			int (11) not null,
	img_dtcrea			date,
	img_dtmod			date,
	img_statut			int (11) not null
)

SQL Oracle:

DROP TABLE cms_diapo
CREATE TABLE cms_diapo
(
	img_id			number (11) constraint img_pk PRIMARY KEY not null,
	img_titre			varchar2 (64),
	img_src			varchar2 (255),
	img_vignette			varchar2 (255),
	img_metadata			number (11),
	img_cms_site			number (11) not null,
	img_dtcrea			date,
	img_dtmod			date,
	img_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_diapo" libelle="Images des diaporamas" prefix="img" display="src" abstract="titre">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" asso="cms_assodiapodiaporama" />
<item name="titre" libelle="Titre" type="varchar" length="64" list="true" order="true" />
<item name="src" libelle="Fichier source" type="varchar" length="255" list="true" order="true" option="file">
<option type="image" maxwidth="800" maxheight="800" />
</item>
<item name="vignette" libelle="Vignette" type="varchar" length="255" list="false" order="false" option="file">
<option type="image" maxwidth="200" maxheight="200" />
</item>  
<item name="metadata" libelle="meta-données" type="int" length="11" list="true" order="true" translate="reference" /> 
<item name="cms_site" libelle="Mini site" type="int" length="11" notnull="true" default="-1" list="true" order="true" fkey="cms_site" />

<item name="dtcrea" libelle="Date de création" type="date" list="false" order="false" />
<item name="dtmod" libelle="Date de modification" type="date" list="true" order="true" />
<item name="statut" libelle="Statut" type="int" length="11" notnull="true" default="DEF_CODE_STATUT_DEFAUT" list="true" order="true" /> 

<langpack lang="fr">
<norecords>Pas d'image à afficher</norecords>
</langpack>
</class> 


==========================================*/

class cms_diapo
{
var $id;
var $titre;
var $src;
var $vignette;
var $metadata;
var $cms_site;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_diapo\" libelle=\"Images des diaporamas\" prefix=\"img\" display=\"src\" abstract=\"titre\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" asso=\"cms_assodiapodiaporama\" />
<item name=\"titre\" libelle=\"Titre\" type=\"varchar\" length=\"64\" list=\"true\" order=\"true\" />
<item name=\"src\" libelle=\"Fichier source\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" option=\"file\">
<option type=\"image\" maxwidth=\"800\" maxheight=\"800\" />
</item>
<item name=\"vignette\" libelle=\"Vignette\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" option=\"file\">
<option type=\"image\" maxwidth=\"200\" maxheight=\"200\" />
</item>  
<item name=\"metadata\" libelle=\"meta-données\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" translate=\"reference\" /> 
<item name=\"cms_site\" libelle=\"Mini site\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" fkey=\"cms_site\" />

<item name=\"dtcrea\" libelle=\"Date de création\" type=\"date\" list=\"false\" order=\"false\" />
<item name=\"dtmod\" libelle=\"Date de modification\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"statut\" libelle=\"Statut\" type=\"int\" length=\"11\" notnull=\"true\" default=\"DEF_CODE_STATUT_DEFAUT\" list=\"true\" order=\"true\" /> 

<langpack lang=\"fr\">
<norecords>Pas d'image à afficher</norecords>
</langpack>
</class> ";

var $sMySql = "CREATE TABLE cms_diapo
(
	img_id			int (11) PRIMARY KEY not null,
	img_titre			varchar (64),
	img_src			varchar (255),
	img_vignette			varchar (255),
	img_metadata			int (11),
	img_cms_site			int (11) not null,
	img_dtcrea			date,
	img_dtmod			date,
	img_statut			int (11) not null
)

";

// constructeur
function cms_diapo($id=null)
{
	if (istable("cms_diapo") == false){
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
		$this->titre = "";
		$this->src = "";
		$this->vignette = "";
		$this->metadata = -1;
		$this->cms_site = -1;
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Img_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Img_titre", "text", "get_titre", "set_titre");
	$laListeChamps[]=new dbChamp("Img_src", "text", "get_src", "set_src");
	$laListeChamps[]=new dbChamp("Img_vignette", "text", "get_vignette", "set_vignette");
	$laListeChamps[]=new dbChamp("Img_metadata", "entier", "get_metadata", "set_metadata");
	$laListeChamps[]=new dbChamp("Img_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Img_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Img_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Img_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_titre() { return($this->titre); }
function get_src() { return($this->src); }
function get_vignette() { return($this->vignette); }
function get_metadata() { return($this->metadata); }
function get_cms_site() { return($this->cms_site); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_img_id) { return($this->id=$c_img_id); }
function set_titre($c_img_titre) { return($this->titre=$c_img_titre); }
function set_src($c_img_src) { return($this->src=$c_img_src); }
function set_vignette($c_img_vignette) { return($this->vignette=$c_img_vignette); }
function set_metadata($c_img_metadata) { return($this->metadata=$c_img_metadata); }
function set_cms_site($c_img_cms_site) { return($this->cms_site=$c_img_cms_site); }
function set_dtcrea($c_img_dtcrea) { return($this->dtcrea=$c_img_dtcrea); }
function set_dtmod($c_img_dtmod) { return($this->dtmod=$c_img_dtmod); }
function set_statut($c_img_statut) { return($this->statut=$c_img_statut); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("img_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("img_statut"); }
//
function getTable() { return("cms_diapo"); }
function getClasse() { return("cms_diapo"); }
function getDisplay() { return("src"); }
function getAbstract() { return("titre"); }


} //class

 
?>