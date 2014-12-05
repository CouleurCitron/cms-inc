<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

function getUrlsForGroupe($gpId){

	$oGpe = new bo_groupes($gpId);
	$sRequete = "SELECT cms_sectionbo.* FROM cms_sectionbo, cms_assosectiongroupe WHERE cms_assosectiongroupe.xsg_cms_sectionbo = cms_sectionbo.sbo_id AND cms_assosectiongroupe.xsg_bo_groupes = ".$gpId.";";
	
	$aSecBo = dbGetObjectsFromRequete("cms_sectionbo", $sRequete);
	$aURL = array();
	foreach($aSecBo as $key => $oSecBo){
		// obvious
		$aURL[] = $oSecBo->get_url();	
	}
	return $aURL;
}


function allowAccesToPage(){
	if ($_SERVER['PHP_SELF'] == "/backoffice/index.php"){
		return true;
	}
	elseif($_SERVER['PHP_SELF'] == "/backoffice/cms/init.php"){
		return true;
	}
	elseif(preg_match('/\/backoffice\/cms\/lib\//', $_SERVER['PHP_SELF']) == 1){
		return true;
	}
	
	if ($_SESSION["user"] == "ccitron"){
		return true;
	}
	
	if ($_SESSION["rank"] == "ADMIN"){
		return true;
	}
	
	// cas où aucune section du bo n'est déclarée. pas d'acl
	$aSec = dbGetObjectsFromFieldValue("cms_sectionbo", array('get_statut'),  array(DEF_ID_STATUT_LIGNE), NULL);

	if ($aClasse==false){
		if ($_SESSION["rank"] == "GEST"){
			return true;
		}
	}
	
		
	if (is_session("groupe")){	
		$gpId = (int)($_SESSION["groupe"]);		
		$oGpe = new bo_groupes($gpId);
		$sectionUrl = str_replace("/".basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']);		
		
		$sRequete = "SELECT cms_sectionbo.* FROM cms_sectionbo, cms_assosectiongroupe WHERE cms_sectionbo.sbo_url LIKE '".$sectionUrl."%' AND cms_assosectiongroupe.xsg_cms_sectionbo = cms_sectionbo.sbo_id AND cms_assosectiongroupe.xsg_bo_groupes = ".$gpId.";";	
		$aSecBo = dbGetObjectsFromRequete("cms_sectionbo", $sRequete);
		foreach($aSecBo as $key => $oSecBo){
			// obvious
			if ($oSecBo->get_url() == $sectionUrl){			
				return true;
			}		
		}
	}
	
	if($_SERVER['PHP_SELF'] == "/backoffice/cms/bo_users/maj_bo_users.php" && $_SESSION["user"] != "ccitron"){
		if ($_GET['id'] != -1)   {
			$oUser = new bo_users($_GET['id']);
			if ($oUser->nom == "ccitron") 
				return false;
		}
	}
	
	// n'autorise pas le module sectionbo pour les users autre que cc
	if((preg_match('/\/backoffice\/cms\/cms_sectionbo/',$_SERVER['PHP_SELF'])==1) && ($_SESSION["user"] != "ccitron")){
		return false;
	}
	
	return false;
}

if (allowAccesToPage() == false){
	die("<script language=\"javascript\" type=\"text/javascript\">alert(\"vous n'êtes habilité(e) à accéder à cette page :\\n\"+document.location.href+\"\\nvotre habilitation : ".$_SESSION["rank"]."\");history.back();</script><noscript>vous n'êtes habilité(e) à accéder à cette page</noscript>");
}

// permet de savoir si un utilisateur a accès une classe
// utiliser pour les tags
function allowClasse($sClasse){
	if ($_SESSION["user"]== "ccitron"){
		return true;
	}
	
	
	if (is_session("groupe")){	
		$gpId = (int)($_SESSION["groupe"]);		
		$oGpe = new bo_groupes($gpId);
		$sectionUrl = str_replace("/".basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']);
		
		 
		$sRequete = "SELECT cms_sectionbo.* FROM cms_sectionbo, cms_assosectiongroupe WHERE cms_sectionbo.sbo_libelle = '".$sClasse."' AND cms_assosectiongroupe.xsg_cms_sectionbo = cms_sectionbo.sbo_id AND cms_assosectiongroupe.xsg_bo_groupes = ".$gpId.";";	
		//echo $sRequete."<br>";
		$aSecBo = dbGetObjectsFromRequete("cms_sectionbo", $sRequete);
		if (sizeof($aSecBo) > 0) {
			return true;
		}
		else {
			return false;
		}
		 
		
	} 
}
?>