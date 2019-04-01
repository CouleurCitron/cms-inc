<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 12/08/05
objet d'AFFICHAGE :: class Cms_affich_droitpage

cette classe construit un objet d'affichage des droits d'une page


==========================================*/

class Cms_affich_droitpage
{

var $id_page;
var $page;
var $id_site;
var $id_noeud;
var $noeud;
var $aContenu;

// constructeur
function __construct($id=NULL) 
{
	global $db;
	if ($id!=NULL){
		$this->makeObjet($id);
	}
}

// getters
function getId_page() { return($this->id_page); } 
function getPage() { return($this->page); } 
function getId_site() { return($this->id_site); } 
function getId_noeud() { return($this->id_noeud); } 
function getNoeud() { return($this->noeud); } 
function getAcontenu() { return($this->aContenu); } 

// setters
function setId_page($c_id_page) { return($this->id_page=$c_id_page); } 
function setPage($c_page) { return($this->page=$c_page); } 
function setId_site($c_id_site) { return($this->id_site=$c_id_site); } 
function setId_noeud($c_id_noeud) { return($this->id_noeud=$c_id_noeud); } 
function setNoeud($c_noeud) { return($this->noeud=$c_noeud); } 
function setAcontenu($c_aContenu) { return($this->aContenu=$c_aContenu); } 


// construction de l'objet d'affichage
function makeObjet($id) 
{
		global $db;
	  	$result = true;

		$sql = " SELECT cms_page.id_page, cms_page.name_page, ";
		$sql.= " cms_page.id_site, cms_page.nodeid_page, ";
		$sql.= " cms_arbo_pages.node_absolute_path_name";
		$sql.= " FROM cms_site, cms_arbo_pages, cms_struct_page, cms_page";
		$sql.= " WHERE cms_page.id_page = $id ";
		$sql.= " AND cms_page.id_page=cms_struct_page.id_page ";
		$sql.= " AND cms_page.nodeid_page=cms_arbo_pages.node_id";

//print("<br>$sql");

		if (DEF_BDD != "ORACLE") $sql.= ";";

//print("<br><font color=green>$sql</font>");
		
		$rs = $db->Execute($sql);

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
		if($rs && !$rs->EOF) {

			$this->id_page = $rs->fields[n('id_page')];
			$this->page = $rs->fields[n('name_page')];			
			$this->id_site = $rs->fields[n('id_site')];

			$this->noeud = $rs->fields[n('node_id')];
						
			$sPath = getRepPage($rs->fields[n('id_site')], $rs->fields[n('nodeid_page')], $rs->fields[n('node_absolute_path_name')]);
			$this->noeud = $sPath;


			// sélection des zones, briques et contributeurs de chaque page
			// cette sélection peut renvoyer aucune ligne ou plusieurs lignes
			// elle est donc faite à part
			
			$sql_briques = " SELECT cms_content.id_content, cms_content.name_content,";
			$sql_briques.= " cms_struct_page.id_zonedit_content";
			$sql_briques.= " FROM cms_content, cms_struct_page";
			$sql_briques.= " WHERE cms_content.isbriquedit_content=1";
			$sql_briques.= " AND cms_content.id_content=cms_struct_page.id_content";
			$sql_briques.= " AND cms_struct_page.id_page=".$id;

//print("<br><font color=orange>$sql_briques</font>");

			$rs_briques = $db->Execute($sql_briques);
			
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql_briques);

			// recherche de tous les contenus de cette page
			// pour chaque contenu
			// 1. recherche de sa zone editable
			// 2. recherche de ses droits
			if($rs_briques) {
		
				while(!$rs_briques->EOF) {
				
					//---------------------------------------
					// objet contenu
					//---------------------------------------
					$oContenu = new Cms_affich_droitcontenu();

					//---------------------------------------
					// la brique
					//---------------------------------------

					$idBrique = $rs_briques->fields[n('id_content')];

					$oContenu->setId_content($rs_briques->fields[n('id_content')]);
					$oContenu->setContent($rs_briques->fields[n('name_content')]);

					
					//---------------------------------------
					// la zone
					//---------------------------------------
					
					if ($rs_briques->fields[n('name_content')] != "") $idZone = $rs_briques->fields[n('id_zonedit_content')];
					else $idZone = -1;

					$sql_zones = " SELECT cms_content.id_content, cms_content.name_content";
					$sql_zones.= " FROM cms_content";
					$sql_zones.= " WHERE cms_content.iszonedit_content=1";
					$sql_zones.= " AND cms_content.id_content=".$idZone;
		
//print("<br><font color=pink>$sql_zones</font>");
			
					$rs_zones = $db->Execute($sql_zones);

					if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql_zones);

					if ($rs_zones->fields[n('name_content')] != "") {
						$oContenu->setId_zone($rs_zones->fields[n('id_content')]);
						$oContenu->setZone($rs_zones->fields[n('name_content')]);
					}


					//---------------------------------------
					// le droit du contenu
					//---------------------------------------
		
					$sql_droit = " SELECT cms_droit.id_content, ";
					$sql_droit.= " bo_users.user_id, bo_users.user_nom, bo_users.user_prenom";
					$sql_droit.= " FROM cms_droit, bo_users";
					$sql_droit.= " WHERE cms_droit.id_content=".$idBrique;
					$sql_droit.= " AND cms_droit.user_id=bo_users.user_id";

					if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql_droit);
//print("<br><font color=green>$sql_droit</font>");


					$rs_droit = $db->Execute($sql_droit);

					if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql_droit);

					if ($rs_droit->fields[n('id_content')] != "") {
						$oContenu->setId_user($rs_droit->fields[n('user_id')]);
						$oContenu->setNom($rs_droit->fields[n('user_prenom')]." ".$rs_droit->fields[n('user_nom')]);
					} else {
						$oContenu->setId_user(-1);
						$oContenu->setNom("non affecté");
					}


					//---------------------------------------
					// objet contenu fini
					//---------------------------------------
					$this->aContenu[] = $oContenu;

					//---------------------------------------
					// brique suivante
					//---------------------------------------

					$rs_briques->MoveNext();
				}
				
				if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
					
			} else {
				echo "<br />Erreur de fonctionnement interne";
				if(DEF_MODE_DEBUG==true) {
					echo "<br />Cms_affich_droitpage.class.php > makeObjet :: rs_briques";
					echo "<br /><strong>$sql_briques</strong>";
				}
				error_log($_SERVER['PHP_SELF']);
				error_log('Erreur de fonctionnement interne');
				error_log($sql_briques);
				error_log($db->ErrorMsg());
				error_log($_SERVER['PHP_SELF']);
	
			  $result = false;
			}




			
			$result = $this;
			
		} else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "<br />Cms_affich_droitpage.class.php > makeObjet";
				echo "<br /><strong>$sql</strong>";
			}
			error_log($_SERVER['PHP_SELF']);
			error_log('Erreur de fonctionnement interne');
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}


}

?>