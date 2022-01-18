<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour gérer les données sondage

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_avis.class.php');

// needs to extend BaseModuleModel
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleModel.php');


class GlossaryModel extends BaseModuleModel {
	
 

	// constructor
	function GlossaryModel () {}

	
	 
	// getABC
	/**
	 * Get all definition
	 *
	 * @param	Bool		$showItemAll		Show Item "All"
	 * @param	Bool		$showAllLetters		Show All Letters or only those with definition
	 */
	function getABC ( $showItemAll, $showAllLetters) {
		
		global $db;
 
		$aGlossary = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");	
		 
		
		$respile = Array();
		
		if ( $showItemAll ) {
			//array_push ( $respile , "Tous") ;
			//$respile["all"] = "Tous";
			
			 
				
			$aMots = array ();
			
			$sql = "select glo_id as id, glo_titrecourt as titrecourt, glo_soustitre as soustitre, glo_textelong as textelong  from cms_glossary where glo_statut = ".DEF_ID_STATUT_LIGNE." ";  
			$sql.= " order by glo_titrecourt ASC ";   
			//print ($sql);
			$rsMots = $db->Execute($sql);
			
			while (!$rsMots->EOF) { 
				//array_push ( $respile ,$rsAvis->fields[0]) ;   
				$aMots[$rsMots->fields["id"]]["titrecourt"] = $rsMots->fields["titrecourt"]; 
				$aMots[$rsMots->fields["id"]]["soustitre"] = $rsMots->fields["soustitre"]; 
				$aMots[$rsMots->fields["id"]]["textelong"] = $rsMots->fields["textelong"]; 
				$rsMots->MoveNext();
				
			}	
			
			
			$respile["all"] = $aMots; 
			
		}
		
		if ( $showAllLetters ) {
			foreach ($aGlossary as $lettre) { 
				
				$aMots = array ();
				
				$sql = "select glo_id as id, glo_titrecourt as titrecourt, glo_soustitre as soustitre, glo_textelong as textelong  from cms_glossary where glo_statut = ".DEF_ID_STATUT_LIGNE." "; 
				$sql.= " and SUBSTRING(LOWER(glo_titrecourt),1,1) = '".$lettre."'"; 
				$sql.= " order by glo_titrecourt ASC ";   
				//print ($sql);
				$rsMots = $db->Execute($sql);
				
				while (!$rsMots->EOF) { 
					 
					//array_push ( $respile ,$rsAvis->fields[0]) ;  
					$aMots[$rsMots->fields["id"]]["titrecourt"] = $rsMots->fields["titrecourt"]; 
					$aMots[$rsMots->fields["id"]]["soustitre"] = $rsMots->fields["soustitre"]; 
					$aMots[$rsMots->fields["id"]]["textelong"] = $rsMots->fields["textelong"]; 
					$rsMots->MoveNext();
				}	
				
				$respile[$lettre] = $aMots;	
				
			}
		}
		else {
			$sql = "SELECT DISTINCT SUBSTRING( LOWER( glo_titrecourt ) , 1, 1 )
					FROM cms_glossary
					WHERE glo_statut =4
					ORDER BY glo_titrecourt ASC ";  
			//print ($sql);
			$rsAvis = $db->Execute($sql);
			while (!$rsAvis->EOF) {  
				$aMots = array ();
				
				$sql = "select glo_id as id, glo_titrecourt as titrecourt, glo_soustitre as soustitre, glo_textelong as textelong  from cms_glossary where glo_statut = ".DEF_ID_STATUT_LIGNE." "; 
				$sql.= " and SUBSTRING(LOWER(glo_titrecourt),1,1) = '".$rsAvis->fields[0]."'"; 
				$sql.= " order by glo_titrecourt ASC ";   
				//print ($sql);
				$rsMots = $db->Execute($sql);
				
				while (!$rsMots->EOF) {  
					$aMots[$rsMots->fields["id"]]["titrecourt"] = $rsMots->fields["titrecourt"]; 
					$aMots[$rsMots->fields["id"]]["soustitre"] = $rsMots->fields["soustitre"]; 
					$aMots[$rsMots->fields["id"]]["textelong"] = $rsMots->fields["textelong"]; 
					$rsMots->MoveNext();
					
				}	
				
				
				$respile[$rsAvis->fields[0]] = $aMots;
				$rsAvis->MoveNext();
				 
				
			}	
		} 
		  
		 
		return (Array) $respile;
	
	
	}
	
	// getAbecedaire
	/**
	 * Get all letters 
	 *
	 * @param	separateur		$text			Separator between each letter
	 * @param	Bool		$showItemAll		Show Item "All"
	 * @param	Bool		$showAllLetters		Show All Letters or only those with definition
	 * @param	Array		$abc		 		Contains all definitions
	 */
	function getAbecedaire ($separateur = "", $showItemAll, $showAllLetters, $abc) {
		
		$params['abc'] = $abc;
		
		$aABC = array();
		foreach ($params['abc'] as $lettre => $mot) {
			if ($lettre == "all"){
				array_push ($aABC,  '<li class="all_letter"><a href="?">Tous</a></li>');
			}
			elseif ( newSizeOf($mot) > 0 ) {
				array_push ($aABC,  '<li><a href="?lettre='.$lettre.'">'.$lettre.'</a></li>');
			}
			else {
				array_push ($aABC, '<li>'.$lettre.'</li>');
			}
		}
		$abecedaire = join ( $separateur, $aABC);
		 
		return $abecedaire;
	
	
	}
	 


}

?>
