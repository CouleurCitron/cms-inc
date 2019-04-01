<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


$oRech = new dbRecherche();

$aPostAssos = getFilterPostsAsso();
 
if (!empty($aPostAssos)) {
	foreach ($aPostAssos as $kFilter => $aPostFilter) {
		foreach ($aPostFilter as $filterName => $filterValue) {
			 
			$_SESSION[$filterName] = $filterValue;	
			//echo $filterName.' '.$filterValue.' '.'<br />';	
			//if($eStatut==""){
			//$eStatut=$_SESSION['eStatut'];
			//}	
			
			$classeNameAsso = str_replace ("assofiltre", "", $filterName);
			//echo $classeNameAsso.' '.'<br />';		
			if (isset($classeNameAsso) && $classeNameAsso != "" && $filterValue!= -1) { 
					$asso = dbGetAssocProps($oRes, $classeNameAsso) ;
	 				 
					$oRech3 = new dbRecherche();				
					$oRech3->setValeurRecherche("declencher_recherche");
					$oRech3->setTableBD($classeNameAsso);
					 
					if (preg_match ("/,/", $filterValue)) {
						$oRech3->setJointureBD("( {$classeName}.".ucfirst($classePrefixe)."_id={$classeNameAsso}.".$asso["prefix"]."_".$asso["in_name"]." AND {$classeNameAsso}.".$asso["prefix"]."_".$asso["out_name"]." IN (".$filterValue.") )");
					}
					else {
						$oRech3->setJointureBD("( {$classeName}.".ucfirst($classePrefixe)."_id={$classeNameAsso}.".$asso["prefix"]."_".$asso["in_name"]." AND {$classeNameAsso}.".$asso["prefix"]."_".$asso["out_name"]."=".$filterValue.")");
					}
					$oRech3->setPureJointure(1);	
					$aRecherche[] = $oRech3;		
					 
				 
			}
			else {
				$filterNameInit = str_replace (array("assofiltre", $classeNameAsso), array("assoFiltre", ucfirst ($classeNameAsso)), $filterName);  
				unset($_POST[$filterNameInit]);
				unset($_SESSION[$filterNameInit]);
			}
			
		}
	}
} else	$classeNameAsso = "";
  

?>