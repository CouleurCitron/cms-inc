<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

class pageObject {
	function __construct(){
	
	}

	function getIdPage($courant,$returnPageData = false){
		global $db;
		$referUrl = $_SERVER['PHP_SELF'];
		if ($courant['id_site'] == '') $courant['id_site'] = path2idside($db, $referUrl);
		$page = basename ($_SERVER["PHP_SELF"]); 
		$aPages = dbGetObjectsFromFieldValue2("cms_page", 
			array("getName_page", "getNodeid_page" , "getId_site", "getValid_page", "getIsgabarit_page") , 
			array(str_replace (".php", "", $page) , $courant["id"] , $courant['id_site'], 1, 0) , array(), array());
		
		if( sizeof($aPages) > 0 && $aPages!=false) { 
			$oPage = $aPages[0];
			if($returnPageData){
				return $oPage;
			}else{
				$id_page = $oPage->getId_page(); 
				return $id_page;
			}
		}
		else return false;  
	}
	
	function getPageData($data = null){
		if(is_null($data)){
			global $courant;
		}else{
			$courant = $data;
		}
		return $this->getIdPage($courant, true);
	}
	
	function getObjectsForCurrentPage($nomClasse = null, $idPage = null){
		global $db;
		if(is_null($idPage)){
			global $courant;
			
			$idPage = $this->getIdPage($courant);
		}
		
		if(is_null($nomClasse)){
			//si $nomClasse est null, on retourne un tableau contenant l'ensemble des objets
			$sql = "select xcp_objet as id, (Select cms_nom from classe where cms_id=cms_assoclassepage.xcp_classe) as cms_nom from cms_assoclassepage where xcp_cms_page=".$idPage;
		}else{
			//sinon, uniquement le type d'objet demandé
			$sql = "select cms_assoclassepage.*, '".$nomClasse."' as 'cms_nom' from cms_assoclassepage where xcp_cms_page=".$idPage;
			$sql .= " AND xcp_classe=(Select cms_id from classe where cms_nom='".$nomClasse."')";
		}
		
		//on ordonne
		$sql .= " ORDER BY xcp_classe, xcp_order";
		// echo $sql;
		if(is_null($nomClasse)){
			$aResultat = array();
			//on récupère le retour de la requête puis pour chaque élément, on crée le tableaux d'objets
			$rs = $db->Execute($sql);
			if($rs) {
				while(!$rs->EOF) {
				
					if ($rs->fields['id'] !=-1) {
						$nom = $rs->fields['cms_nom'];
						if(!isset($aResultat[$nom])){
							$aResultat[$nom] = array();
						}
						if (class_exists($nom)){
							array_push($aResultat[$nom],new $nom($rs->fields['id']));
						}
						else{
							echo 'WARNING: unable to access class '.$nom.'<br />'.$sql.'<br />';
						}
					}
					$rs->MoveNext();
				}
				$rs->Close();
			}
			
			return $aResultat;
		}else{
			//on crée directement le tableau d'objets
			return dbGetObjectsFromRequete($nomClasse,$sql);
		}

		// die();
	}
}

// $pageObject = new pageObject();
// $pageObjectData = $pageObject->getObjectsForCurrentPage(); 

?>