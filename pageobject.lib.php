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
		
		if( newSizeOf($aPages) > 0 && $aPages!=false) { 
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
			//sinon, uniquement le type d'objet demandÃ©
			$sql = "select cms_assoclassepage.*, xcp_objet as id, '".$nomClasse."' as 'cms_nom' from cms_assoclassepage where xcp_cms_page=".$idPage;
			$sql .= " AND xcp_classe=(Select cms_id from classe where cms_nom='".$nomClasse."')";			
		}
		
		//on ordonne
		$sql .= " ORDER BY xcp_classe, xcp_order";
		//echo $sql;
		if(is_null($nomClasse)){
			$aResultat = array();
			//on rÃ©cupÃ¨re le retour de la requÃªte puis pour chaque Ã©lÃ©ment, on crÃ©e le tableaux d'objets
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
                        
                        
                        //on récupère les objets parents
                        $sSqlParent = "SELECT * FROM cms_assoclassepage WHERE xcp_cms_page = '".$idPage."' AND xcp_parent != '0' AND xcp_parent != '-1' ORDER BY xcp_classe, xcp_order";
                        $aParent = dbGetObjectsFromRequete('cms_assoclassepage',$sSqlParent);
            
                        foreach($aParent as $oParent){
                            //pre_dump($oParent);
                            $oClass = new classe($oParent->get_classe());
                            $oObjectParent = new cms_assoclassepage($oParent->get_parent());
                            $aResultat[$oClass->get_nom()]['parents'][$oParent->get_objet()] = $oObjectParent->get_objet();
                        }
                        
			//die();
			return $aResultat;
		}else{
			//on crÃ©e directement le tableau d'objets
			//$aResultat = dbGetObjectsFromRequete($nomClasse,$sql);
			$aResultat = array();
			//on rÃ©cupÃ¨re le retour de la requÃªte puis pour chaque Ã©lÃ©ment, on crÃ©e le tableaux d'objets
			$rs = $db->Execute($sql);
			if($rs) {
				while(!$rs->EOF) {
				
					if ($rs->fields['id'] !=-1) {
						$nom = $rs->fields['cms_nom'];
						if(!isset($aResultat)){
							$aResultat = array();
						}
						if (class_exists($nom)){
							array_push($aResultat,new $nom($rs->fields['id']));
						}
						else{
							echo 'WARNING: unable to access class '.$nom.'<br />'.$sql.'<br />';
						}
					}
					$rs->MoveNext();
				}
				$rs->Close();
			}
                        
                        //on récupère les éléments parents
                        //on récupère les objets parents
                        $sSqlParent = "SELECT * FROM cms_assoclassepage AS a, classe AS c WHERE a.xcp_cms_page = '".$idPage."' AND a.xcp_parent != '0' AND a.xcp_classe = c.cms_id AND c.cms_nom = '".$nomClasse."' ORDER BY xcp_classe, xcp_order";
                        $aParent = dbGetObjectsFromRequete('cms_assoclassepage',$sSqlParent);
                        
                        foreach($aParent as $oParent){
                            $oClass = new classe($oParent->get_classe());
                            $oObjectParent = new cms_assoclassepage($oParent->get_parent());
                            $aResultat[$oClass->get_nom()]['parents'][$oParent->get_objet()] = $oObjectParent->get_objet();
                        }
                        
                        return $aResultat;
		}

		// die();
	}
}

// $pageObject = new pageObject();
// $pageObjectData = $pageObject->getObjectsForCurrentPage(); 

?>