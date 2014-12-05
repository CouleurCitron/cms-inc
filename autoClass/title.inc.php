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
  

$aChemin = split('/',$referUrl);
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
	if (ereg ("TAG_", $oPage->getOptions_page())) {
		$classe_a_tag = str_replace ("TAG_", "", $oPage->getOptions_page());
		if (getCount_where("classe", array("cms_nom"), array(strtolower($classe_a_tag)), array("TEXT")) >0) {	
			
			$sql = "select * from classe where cms_nom = '".$classe_a_tag."'";
			
			$aClasse = dbGetObjectsFromRequete ("classe", $sql);
			$oClasse = $aClasse[0];
			$idClasse = $oClasse->get_id();
			
			$sql = "select * from cms_assotitleclasse, cms_title where xtc_classe = ".$idClasse." and xtc_cms_title = tit_id and xtc_classeid = ".$_GET["id"]. " and tit_cms_site =".$idSite;  
			
			$aAssotitle = dbGetObjectsFromRequete ("cms_assotitleclasse", $sql);
			 //echo "ici".sizeof($aAssotitle);
			$title=" ";
			if (sizeof($aAssotitle) > 0) {
				for ($i=0; $i<sizeof($aAssotitle);$i++) {
					 
					$oAssotitle = $aAssotitle[$i];  
					$oTitle = new Cms_title ($oAssotitle->get_cms_title());
					
					 
					$title.=$oTitle->get_nom();
					if ($i!=(sizeof($oAssotitle)-1)) $title.=", "; 
					 
				}
			}	
		}
	}  
}else{
	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/modules/meta/title.php')){
		include_once('modules/meta/title.php');
	}
} 
echo html2text($title);
 
?>