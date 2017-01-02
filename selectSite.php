<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
sponthus 12/07/2005
fonction permettant de changer de site de connection et de site de travail


function putChangeSiteTravail()
function putChangeSiteConnected()
function putAfficheSite()

*/


$oXsg = new cms_assosectiongroupe();
unset($oXsg); // pour init table




$oSbo = new cms_sectionbo();
unset($oSbo); // pour init table


if (dbGetCount("bo_groupes")==0){
	$oGrp = new bo_groupes();
	$oGrp->set_id(1);
	$oGrp->set_titre("groupe administrateur");
	$oGrp->set_desc("groupe administrateur");
	$oGrp->set_ordre(10);
	dbSauve($oGrp);
	$oUsr = new bo_users(1);
	$oUsr->set_bo_groupes(1);
	dbSauve($oUsr);
}


if (dbGetCount("bo_rank")==0){
	$oRk = new bo_rank();
	$oRk->set_id(1);
	$oRk->set_libelle("ADMIN");
	$oRk->set_description("Tout droit");
	$oRk->set_statut(DEF_ID_STATUT_LIGNE);
	dbSauve($oRk);
	$oRk = new bo_rank();
	$oRk->set_id(2);
	$oRk->set_libelle("GEST");
	$oRk->set_description("Pas de droit sur la gestion du CMS, valide contenu");
	$oRk->set_statut(DEF_ID_STATUT_LIGNE);
	dbSauve($oRk);
	$oRk = new bo_rank();
	$oRk->set_id(3);
	$oRk->set_libelle("REDACT");
	$oRk->set_description("Ne peut que créer de nouvel enregistrement, ne peut les mettre en ligne");
	$oRk->set_statut(DEF_ID_STATUT_LIGNE);
	dbSauve($oRk);
	
	$oUsr = new bo_users(1);
	$oUsr->set_rank(1);
	dbSauve($oUsr);
}




// fonction permettant d'afficher un select pour choisir le site de connexion

function putChangeSiteConnected()
{
	global $translator;
	
	// liste des mini sites
	$aListeSite = listSite("ALL");
	
	// s'il y a +sieurs sites -> affichage d'un select permettant de faire une sélection
	if (sizeof($aListeSite) > 1) {
		
		$sContent='<div align="left" class="arbo">';
	
		// select
		if ($_SESSION['idSite'] == -1 || $_SESSION['idSite'] == "") $selected="selected"; else $selected="";
	
		$sContent.= '<select id="connectSite" name="connectSite" class="arbo">';
	
		for ($a=0; $a < sizeof($aListeSite); $a++) {
			$oSite = $aListeSite[$a];
		
			if ($_SESSION['idSite'] == $oSite->get_id()) $selected="selected"; else $selected="";
	
			$sContent.= '<option value="'.$oSite->get_id().'" '.$selected.' >'.$oSite->get_name().'</option>';
		}
			
		$sContent.= '</select>';

		// bouton changer
		$sContent.= '&nbsp;<input type="button" name="btRecherche" value="'.$translator->getTransByCode('changerdesite').'" class="arbo" onclick="changer_site_connect()"></div>';
	
	} else {
		$oSite = $aListeSite[0];

		$sContent='<div align="left" class="arbo">Site '.$oSite->get_name().'</div>
		<input type="hidden" name="searchSite" value="'.$oSite->get_id().'">';
	}

	// fonction javascript changer()
	// le site de connection est mis à jour avec le select connectSite dans /backoffice/index.php
	$sContent.= '<script type="text/javascript">
	function changer_site_connect() { 
		document.accueilForm.action="index.php";
		document.accueilForm.submit();
	}
	</script>';

	return $sContent;
}


// bandeau d'affichage du site de travail

function putAfficheSite(){
	global $translator;
	//$oSite = new Cms_site($_SESSION['idSite_travail']);
	print("<br /><div class='arbo' align='left'>".$translator->getTransByCode('sitedetravail').": ".$_SESSION['site']."</div><br />");
}

?>