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
  
if (!isset($translator)){
	$translator =& TslManager::getInstance(); 
}
	
	
$aChemin = split('/',$referUrl);
$nomPage = $aChemin[newSizeOf($aChemin)-1];
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
			
			$sql = "select distinct * from cms_assotagclasse, cms_tag where xtc_classe = ".$idClasse." and xtc_cms_tag = tag_id and tag_cms_site =".$idSite." and xtc_classeid = ".$_GET["id"]. " group by tag_id"; 
			 
			$aAssotag = dbGetObjectsFromRequete ("cms_assotagclasse", $sql);
			 
			$keywords=", "; 
			if (newSizeOf($aAssotag) > 0) {
				for ($i=0; $i<newSizeOf($aAssotag);$i++) {
					$oAssotag = $aAssotag[$i];  
					$oTag = new Cms_tag ($oAssotag->get_cms_tag());
 
					//$keywords.=$oTag->get_nom();
					$keywords.=$translator->getByID($oTag->get_nom(), $_SESSION["id_langue"]);	
					$keywords.=", ";  
				}
			}	
		}
	}  
} else{
	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/modules/meta/tag.php')){
		include_once('modules/meta/tag.php');
	}
} 
echo html2text($keywords);
 
?>