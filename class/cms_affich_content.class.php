<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 02/08/05
objet d'AFFICHAGE :: class Cms_affich_content

cette classe construit un objet d'affichage avec soit :
- un objet CMS_CONTENT
- un objet CMS_ARCHI_CONTENT


function Cms_affich_content($sNature, $id) 
function makeObjet($sNature, $id) 

==========================================*/

class Cms_affich_content
{

var $nature;         // CMS_CONTENT ou CMS_ARCHI_CONTENT
var $id;
var $name;
var $noeud;
var $type;           // HTML, MULTIMEDIA...
var $idSite;
var $version;		// pour CMS_ARCHI_CONTENT uniquement
var $site;
var $width;
var $height;
var $statut_content; // statut de la brique version CMS_CONTENT
var $statut; 		 // statut de la brique version CMS_CONTENT ou CMS_ARCHI_CONTENT
var $date_creat;     // date de création pour un objet CMS_CONTENT
var $date_modif;     // date de modification pour un objet CMS_ARCHI_CONTENT
var $date_publi;     // date de publication pour un objet CMS_ARCHI_CONTENT
var $contributeur;   // peut être vide (une brique non affectée)
var $idPage;         // PAGE :: ATTENTION aux valeurs multiples
					 // normalement ici pas de valeurs multiples possibles
                     // une brique editable n'appartient qu'à une et une seule page
					 // (on ne peut réutiliser de brique editable dans une zone, seulement des briques fixes)
var $toutenligne_page; 
var $existeligne_page; 
var $page;


// constructeur
function __construct($sNature="CMS_CONTENT", $id=NULL) 
{
	global $db;

	$this->nature = '';
	if($id!=NULL){
		$this->makeObjet($sNature, $id);
	}
	
}

// getters
function getNature() { return($this->nature); } 
function getId() { return($this->id); } 
function getName() { return($this->name); } 
function getNoeud() { return($this->noeud); } 
function getIdSite() { return($this->idSite); } 
function getVersion() { return($this->version); } 
function getSite() { return($this->site); } 
function getWidth() { return($this->width); } 
function getHeight() { return($this->height); } 
function getType() { return($this->type); } 
function getStatut_content() { return($this->statut_content); } 
function getStatut() { return($this->statut); } 
function getDate_creat() { return($this->date_creat); } 
function getDate_modif() { return($this->date_modif); } 
function getDate_publi() { return($this->date_publi); } 
function getContributeur() { return($this->contributeur); } 
function getIdPage() { return($this->idPage); } 
function getToutenligne_page() { return($this->toutenligne_page); } 
function getExisteligne_page() { return($this->existeligne_page); } 
function getPage() { return($this->page); } 

// setters
function setNature($c_nature) { return($this->nature=$c_nature); } 
function setId($c_id) { return($this->id=$c_id); } 
function setName($c_name) { return($this->name=$c_name); } 
function setNoeud($c_noeud) { return($this->noeud=$c_noeud); } 
function setIdSite($c_idSite) { return($this->idSite=$c_idSite); } 
function setVersion($c_version) { return($this->version=$c_version); } 
function setSite($c_site) { return($this->site=$c_site); } 
function setWidth($c_width) { return($this->width=$c_width); } 
function setHeight($c_height) { return($this->height=$c_height); } 
function setType($c_type) { return($this->type=$c_type); } 
function setStatut_content($c_statut_content) { return($this->statut_content=$c_statut_content); } 
function setStatut($c_statut) { return($this->statut=$c_statut); } 
function setDate_creat($c_date_creat) { return($this->date_creat=$c_date_creat); } 
function setDate_modif($c_date_modif) { return($this->date_modif=$c_date_modif); } 
function setDate_publi($c_date_publi) { return($this->date_publi=$c_date_publi); } 
function setContributeur($c_contributeur) { return($this->contributeur=$c_contributeur); } 
function setIdPage($c_idPage) { return($this->idPage=$c_idPage); } 
function setToutenligne_page($c_toutenligne_page) { return($this->toutenligne_page=$c_toutenligne_page); } 
function setExisteligne_page($c_existeligne_page) { return($this->existeligne_page=$c_existeligne_page); } 
function setPage($c_page) { return($this->page=$c_page); } 



// construction de l'objet d'affichage
function makeObjet($sNature, $id) 
{
		global $db;
	  	$result = true;
		
		if($id==NULL){
			return false;
		}

		// sélection du nom de la zone, de son chemin, de son type, son statut, ses dates, ses dimensions, sa page
		if ($sNature == "CMS_CONTENT") {
			$sql = " SELECT cms_content.id_content, cms_content.name_content, ";
			$sql.= " cms_arbo_pages.node_absolute_path_name, cms_arbo_pages.node_id, ";
			$sql.= " cms_content.type_content, ";
			$sql.= " cms_content.statut_content AS statut, cms_content.statut_content AS statut_content, ";
			$sql.= " ".from_dbdatetime("dateadd_content").", ".from_dbdatetime("dateupd_content").", ";
			$sql.= " cms_site.cms_name, cms_site.cms_id, cms_content.width_content, cms_content.height_content,";
			$sql.= " cms_page.id_page, cms_page.name_page, cms_page.toutenligne_page, cms_page.existeligne_page";
			$sql.= " FROM cms_content, cms_site, cms_arbo_pages, cms_struct_page, cms_page";
			$sql.= " WHERE cms_content.id_content=".$id;
			$sql.= " AND cms_content.id_site=cms_site.cms_id";
			$sql.= " AND cms_content.nodeid_content=cms_arbo_pages.node_id";
			$sql.= " AND cms_content.id_content=cms_struct_page.id_content ";
			$sql.= " AND cms_struct_page.id_page=cms_page.id_page";
			if (DEF_BDD != "ORACLE") $sql.= ";";
		} else { // $sNature == "CMS_ARCHI_CONTENT"
			$sql = " SELECT cms_content.id_content, cms_content.name_content, ";
			$sql.= " cms_arbo_pages.node_absolute_path_name, cms_arbo_pages.node_id, ";
			$sql.= " cms_content.type_content, ";
			$sql.= " cms_archi_content.statut_archi AS statut, cms_content.statut_content AS statut_content, ";
			$sql.= " cms_archi_content.version_archi , ";
			$sql.= " cms_site.cms_name, cms_site.cms_id, cms_content.width_content, cms_content.height_content, ";
			$sql.= " ".from_dbdatetime("date_archi").", ";
			$sql.= " cms_page.id_page, cms_page.name_page, cms_page.toutenligne_page, cms_page.existeligne_page";
			$sql.= " FROM cms_content, cms_site, cms_archi_content, cms_arbo_pages, cms_struct_page, cms_page";
			$sql.= " WHERE cms_content.id_content = $id ";
			$sql.= " AND cms_content.id_site=cms_site.cms_id";
			$sql.= " AND cms_archi_content.id_content_archi=cms_content.id_content ";
			$sql.= " AND cms_archi_content.statut_archi=".DEF_ID_STATUT_LIGNE;
			$sql.= " AND cms_content.nodeid_content=cms_arbo_pages.node_id";
			$sql.= " AND cms_content.id_content=cms_struct_page.id_content ";
			$sql.= " AND cms_struct_page.id_page=cms_page.id_page";
			if (DEF_BDD != "ORACLE") $sql.= ";";
		}
		
		// sélection du contrinuteur affecté à la zone
		// cette sélection peut renvoyer aucune ligne
		// elle est donc faite à part
		$sql_contributeur = " SELECT bo_users.user_nom";
		$sql_contributeur.= " FROM cms_droit, bo_users";
		$sql_contributeur.= " WHERE cms_droit.id_content = ".$id;
		$sql_contributeur.= "    AND cms_droit.user_id=bo_users.user_id";
		
//print("<br><font color=black>$sql</font>");
//print("<br><font color=gray>$sql_contributeur</font>");

		$rs = $db->Execute($sql);
		
		if($rs && !$rs->EOF) {

			$this->nature = $sNature;
			$this->id = $rs->fields[n('id_content')];
			$this->name = $rs->fields[n('name_content')];
			$this->version = $rs->fields[n('version_archi')];
			$this->idSite = $rs->fields[n('cms_id')];
			$this->site = $rs->fields[n('name_site')];
			$this->width = $rs->fields[n('width_content')];			
			$this->height = $rs->fields[n('height_content')];
			$this->type = $rs->fields[n('type_content')];
			$this->statut_content = $rs->fields[n('statut_content')];
			$this->statut = $rs->fields[n('statut')];
			$this->date_creat = $rs->fields[n('dateadd_content')];
			$this->date_modif = $rs->fields[n('dateupd_content')];
			$this->date_publi = $rs->fields[n('date_archi')];
			$this->idPage = $rs->fields[n('id_page')];
			$this->page = $rs->fields[n('name_page')];
			$this->toutenligne_page = $rs->fields[n('toutenligne_page')];
			$this->existeligne_page = $rs->fields[n('existeligne_page')];
				
			$sPath = getRepPage($rs->fields[n('cms_id')], $rs->fields[n('node_id')], $rs->fields[n('node_absolute_path_name')]);
			$this->noeud = $sPath;			

			$rs = $db->Execute($sql_contributeur);

			if ($rs->fields[n('user_nom')] != "") $this->contributeur = $rs->fields[n('user_nom')];
			else $this->contributeur = "non affecté";

			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql_contributeur);
			
			$result = $this;
			
			$rs->Close();
			
		} else {
			echo "<br />Erreur de fonctionnement interne";
		
			if(DEF_MODE_DEBUG==true) {
				echo "<br />Cms_affich_content.class.php > makeObjet";
				echo "<br /><strong>$sql</strong>";
			}
			error_log($_SERVER['PHP_SELF']);
			error_log('Erreur de fonctionnement interne');
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);

   		  $result = false;
		}		
	    return $result;
	}


}

?>