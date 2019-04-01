<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if (!isset($idSite)){
// param du referer --------------------------------------------
	if ((isset($_REQUEST['refer'])) and ($_REQUEST['refer'] != "")){
		$referUrl = $_REQUEST['refer'];
	}
	else{
		$referUrl = $_SERVER['PHP_SELF'];
		$referUrl = "http://".$_SERVER['HTTP_HOST']."/content/novacom/produits.php?id=10";
	}
	
	$idSite = path2idside($db, $referUrl);
} //---------------------------------------------------------
  

$aChemin = explode('/',$referUrl);
$nomPage = $aChemin[sizeof($aChemin)-1];
$nomPage = str_replace (".php", "", $nomPage);
if($nomPage==''){
	$nomPage='index';
}
if (isset ($_GET["id"]) && $_GET["id"]!="") {
	if(!isset($oPage)){
		$maPage = getPageByName($idSite, $courant["id"], $nomPage) ; 
		$idPage = $maPage["id"]; 
		$oPage = new Cms_page ($idPage); 
	}
	if (preg_match ("/TAG_/msi", $oPage->getOptions_page())) {
		$classe_a_tag = str_replace ("TAG_", "", $oPage->getOptions_page());
		if (getCount_where("classe", array("cms_nom"), array(strtolower($classe_a_tag)), array("TEXT")) >0) {	
			$sql = "select * from classe where cms_nom = '".$classe_a_tag."'";
			$aClasse = dbGetObjectsFromRequete ("classe", $sql);
			$oClasse = $aClasse[0];
			$idClasse = $oClasse->get_id();
			
			$sql = "select * from cms_assodescriptionclasse, cms_description where xdc_classe = ".$idClasse." and xdc_classeid = ".$_GET["id"]. " and xdc_cms_description = des_id and des_cms_site =".$idSite; 
			 
			$aAssodescription = dbGetObjectsFromRequete ("cms_assodescriptionclasse", $sql);
			
			$description=" ";
			if (sizeof($aAssodescription) > 0) {
				for ($i=0; $i<sizeof($aAssodescription);$i++) {
					$oAssodescription = $aAssodescription[$i]; 
					//if ($oAssodescription->get_classeid() == -1 || $oAssodescription->get_classeid() == $_GET["id"]) { 
						$oDescription = new Cms_description ($oAssodescription->get_cms_description());
						$description.=$oDescription->get_description();
						if ($i!=(sizeof($oAssodescription)-1)) $description.=", ";
						 
						 
					//}
					 
				}
			}	
		}
	}  
}else{
	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/modules/meta/description.php')){
		include('modules/meta/description.php');
	}
}
echo html2text($description);
 
?>