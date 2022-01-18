<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
	$Id: Categorie.class.php,v 1.1 2013-09-30 09:30:48 raphael Exp $
	$Author: raphael $

	$Log: Categorie.class.php,v $
	Revision 1.1  2013-09-30 09:30:48  raphael
	*** empty log message ***

	Revision 1.2  2013-03-01 10:33:59  pierre
	*** empty log message ***

	Revision 1.1  2009-02-16 15:01:39  pierre
	*** empty log message ***

	Revision 1.2  2009-02-16 14:59:58  pierre
	*** empty log message ***

	Revision 1.3  2008/03/04 14:55:54  pierre
	*** empty log message ***
	
	Revision 1.2  2007/06/06 14:54:39  pierre
	*** empty log message ***
	
	Revision 1.1  2006/04/12 07:26:22  sylvie
	*** empty log message ***
	
	Revision 1.2  2004/06/04 12:35:30  ddinside
	fin petites annonces
	
	Revision 1.1  2004/06/01 14:25:19  ddinside
	ajout petites annonce dont gardes finies
	newslettrer
	
	Revision 1.1  2004/05/18 15:20:31  ddinside
	classe gestino des inscrits
	
	Revision 1.1  2004/05/18 13:47:12  ddinside
	creation module petites annonces
	
*/

class Categorie {

	// Propriétés
	var $dbConn = null;

	var $id = null;
	var $libelle = '';
	var $isGarde = 0;
	var $ordre = 0;
	var $id_site = 0;

	var $listAnnonces = array();


// constructeur
function Categorie($id=null) 
{
	global $db;
	$this->dbConn = &$db;

	if($id!=null) {
		//$this = dbGetObjectFromPK("Categorie", $id);
		$temp = dbGetObjectFromPK("Categorie", $id);
		foreach ($temp as $key => $val){
			$this->$key = $val;		
		}
	} else {

		$this->id = '';
		$this->libelle = '';
		$this->isGarde = '';
		$this->ordre = 99;
		$this->id_site = $_SESSION['idSite_travail'];						
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("categorie_id", "entier", "getId", "setId");
	$laListeChamps[]=new dbChamp("categorie_libelle", "text", "getLibelle", "setLibelle");
	$laListeChamps[]=new dbChamp("categorie_is_garde", "entier", "getIs_garde", "setIs_garde");
	$laListeChamps[]=new dbChamp("categorie_ordre", "entier", "getOrdre", "setOrdre");

	$laListeChamps[]=new dbChamp("id_site", "entier", "getId_site", "setId_site");

	return($laListeChamps);
}


function getId() { return($this->id); }
function getLibelle() { return($this->libelle); }
function getIs_garde() { return($this->isGarde); }
function getOrdre() { return($this->ordre); }
function getId_site() { return($this->id_site); }

function setId($c_id) { return($this->id=$c_id); }
function setLibelle($c_libelle) { return($this->libelle=$c_libelle); }
function setIs_garde($c_isGarde) { return($this->isGarde=$c_isGarde); }
function setOrdre($c_ordre) { return($this->ordre=$c_ordre); }
function setId_site($c_id_site) { return($this->id_site=$c_id_site); }


// autres getters
function getGetterPK() { return("getId"); }
function getSetterPK() { return("setId"); }
function getFieldPK() { return("categorie_id"); }
function getTable() { return("pa_categories"); }
function getClasse() { return("Categorie"); }



	function initValues($id) {
		$sql = "select categorie_id, categorie_libelle, categorie_is_garde, categorie_ordre from pa_categories where categorie_id = $id";
		$rs = $this->dbConn->Execute($sql);
		if($rs && !$rs->EOF) {
			$this->id = $rs->fields[0];
			$this->libelle = $rs->fields[1];
			$this->isGarde = $rs->fields[2];
			$this->ordre = $rs->fields[3];
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
		}
	}

	function listToutesCategories() {
		$result = array();

		$sql = " SELECT categorie_id";
		$sql.= " FROM pa_categories";
		$sql.= " ORDER BY categorie_is_garde, categorie_ordre, categorie_id";
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {
				$uneCategorie = new Categorie($rs->fields[0]);
				array_push($result,$uneCategorie);
				$rs->MoveNext();
			}
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
		}
		return $result;
	}

	function listCategories($isGarde=0) {

		$result = array();

		$sql = " SELECT categorie_id from pa_categories";
		$sql.= " WHERE categorie_is_garde=$isGarde AND id_site=".$_SESSION['idSite_pa'];
		$sql.= " ORDER BY categorie_ordre, categorie_id";
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $this->dbConn->Execute($sql);

		if($rs) {
			while(!$rs->EOF) {
				$uneCategorie = new Categorie($rs->fields[0]);
				array_push($result,$uneCategorie);
				$rs->MoveNext();
			}
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
		}
		return $result;
	}

	function listAnnonces($type='garde') {
		$result = array();
		$sql = '';
		if($type=='garde') {
			$sql = "select annonce_id from pa_annonces where annonce_categorie_id = ".$this->id." and annonce_valid=1 order by annonce_dt_debut desc, annonce_dt_perim";
		} elseif($type=='offre') {
			$sql = "select annonce_id from pa_annonces where annonce_categorie_id = ".$this->id." and annonce_valid=1 and annonce_is_offre = 1 order by annonce_dt_debut desc, annonce_dt_perim";
		} elseif($type=='demande') {
			$sql = "select annonce_id from pa_annonces where annonce_categorie_id = ".$this->id." and annonce_valid=1 and annonce_is_offre = 0 order by annonce_dt_debut desc, annonce_dt_perim";
		}
		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {
				$uneAnnonce = new Annonce($rs->fields[0]);
				array_push($result,$uneAnnonce);
				$rs->MoveNext();
			}
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
		}
		return $result;
	}


	function setListAnnonces($type='garde') {

		if(newSizeOf($this->listAnnonces)==0) {
			$sql = '';

			if($type == 'garde') {
				$sql = " SELECT annonce_id FROM pa_annonces ";
				$sql.= " WHERE annonce_valid=1 AND annonce_categorie_id=".$this->id;
				$sql.= " AND id_site=".$_SESSION['idSite_pa'];
				$sql.= " ORDER BY annonce_dt_debut desc, annonce_dt_perim";
			} elseif ($type == 'offre') {
				$sql = " SELECT annonce_id FROM pa_annonces ";
				$sql.= " WHERE annonce_valid=1 AND annonce_categorie_id=".$this->id." AND annonce_is_offre=1 ";
				$sql.= " AND id_site=".$_SESSION['idSite_pa'];
				$sql.= " ORDER BY annonce_dt_debut desc, annonce_dt_perim";
			} elseif ($type == 'demande') {
				$sql = " SELECT annonce_id FROM pa_annonces WHERE annonce_valid=1 ";
				$sql.= " AND annonce_categorie_id=".$this->id." AND annonce_is_offre=0 ";
				$sql.= " AND id_site=".$_SESSION['idSite_pa'];
				$sql.= " ORDER BY annonce_dt_debut desc, annonce_dt_perim";
			}

			$rs = $this->dbConn->Execute($sql);

			if($rs) {
				while(!$rs->EOF){
					$uneAnnonce = new Annonce($rs->fields[0]);
					array_push($this->listAnnonces, $uneAnnonce);
					$rs->MoveNext();
				}
				return true;
			} else {
				error_log($_SERVER['PHP_SELF']);
				error_log('erreur lors de l\'execution de la requete');
				error_log($sql);
				error_log($this->dbConn->ErrorMsg());
				error_log($_SERVER['PHP_SELF']);
				
			}
		}
		return true;
	}
}
?>
