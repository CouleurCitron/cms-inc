<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/check_rights.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

// MENUS GENERIQUES AWS

/*
function trimMenu($aMenu){
function makeMenu($idNoeud, $aMenu)
function getChildren($idNoeud, $aMenu)
function getNoeud($idNoeud, $aMenu)
*/

//----------------------------------------
// fonction de purge du menu
//----------------------------------------
function trimMenu($aMenu){
	//echo '<hr />';
	//pre_dump($aMenu);	
	if ($aMenu["content"]!=NULL){		
		if (is_array($aMenu["content"])){
			//pre_dump($aMenu["content"]);
			$allNulls=true;
			foreach($aMenu["content"] as $kSub => $nSub){
				//echo '<br />'.$kSub.' : <br />';				
				//pre_dump($nSub);
				if ($nSub["content"]!=NULL){
					$allNulls=false;
					break;
				}		
			}
			if($allNulls){
				//echo 'allNulls';
				$aMenu["content"]=NULL;
			} 
		}
	}
	//pre_dump($aMenu);

	return $aMenu;
}


//----------------------------------------
// fonction de contruction du menu
//----------------------------------------
function makeMenu($idNoeud, $aMenu){
	$aResult = array();
	if (is_session("aURL")){
		$aURL = $_SESSION["aURL"];
	}
	else{
		$aURL = false;
	}

	// recherche du noeud = idNoeud 
	$oNoeud = getNoeud($idNoeud, $aMenu);

	// test si dossier ou branche avec l'url 
	// prsente seulement pour la branche
	//echo "now testing ".$oNoeud->getTitre()." - ".$oNoeud->getUrl()."<br />";
	if ($oNoeud->getUrl() != "") {
		//$aResult['id'] = "'".$p."'";
		$aResult['id'] = "'".$oNoeud->getId()."'";
		$aResult['label'] = $oNoeud->getTitre();
		$aResult['content'] = $oNoeud->getUrl();
		$sectionUrl = str_replace("/".basename($aResult['content']), "", $aResult['content']);
		//echo $sectionUrl."<br >";
		if ($aURL != false){
			if (in_array($sectionUrl, $aURL)){
				//echo $sectionUrl." ok<br />";
			}
			else{
				//echo $sectionUrl." WHOHOHOHOOHO non!<br />";
				$aResult = NULL;
			}
		}
	} 
	else {	// on est sur un dossier		
		$aResult['id'] = $oNoeud->getId();
		$aResult['label'] = $oNoeud->getTitre();

		// recherche des enfants de ce noeud
		$aChildren = getChildren($idNoeud, $aMenu);		

		for ($i=0; $i<sizeof($aChildren); $i++){
			$oChild = $aChildren[$i];
			$aResult['content'][$oChild->getId()] = makeMenu($oChild->getId(), $aMenu);			
		}		
		
		$nullCount = 0;
		for ($i=0; $i<sizeof($aChildren); $i++){
			$oChild = $aChildren[$i];
			if ($aResult['content'][$oChild->getId()] == NULL){
				$nullCount++;
			}
			elseif ($aResult['content'][$oChild->getId()]["content"] == NULL){
				$nullCount++;
			}
			else{
				//pre_dump($aResult['content'][$oChild->getId()]);
			}			
		}
		//echo $oNoeud->getTitre()." => nullCount ".$nullCount." / ".sizeof($aChildren)."<br />";
		if ($nullCount > sizeof($aChildren)){
			//echo "tous nuls<br />";
			$aResult['content'] = NULL;
		}
	}

	$aResult = trimMenu($aResult);
	
	return $aResult;	
}


//----------------------------------------
// recheche des enfants d'un noeud
//----------------------------------------
function getChildren($idNoeud, $aMenu){
	$aChildren = array();
	for ($p=0; $p<sizeof($aMenu); $p++)
	{
		$oChild = $aMenu[$p];
		if ($oChild->getNoeud() == $idNoeud) {
			$aChildren[] = $oChild;
		}
	}

	return($aChildren);
}

//----------------------------------------
// recherche du noeud
//----------------------------------------
function getNoeud($idNoeud, $aMenu){
	for ($p=0; $p<sizeof($aMenu); $p++)
	{
		$oMenu = $aMenu[$p];

		if ($oMenu->getId() == $idNoeud) {
			$oNoeud = $oMenu;
			$p = sizeof($aMenu); // sortie de la boucle
		}
	}

	return($oNoeud);
}


//----------------------------------------
// menu autoris si fonction active dans le config.php
//----------------------------------------
function isActived($sFonct){
	if ($sFonct == "ON") $return = 1;
	else $return = 0;

	return ($return);
}

//----------------------------------------------------------------------------------------

// Fonctions possible:
// CMS SS PA SEARCH SONDAGE NEWSLETTER
$nameUser = $_SESSION['login'];

$aURL = getUrlsForGroupe((int)$_SESSION["groupe"]);
$_SESSION["aURL"] = $aURL;


// vrifie si on affiche toutes les entres dfinis dans config.php
(defined("DEF_MENU_BRIQUE_BDD_ALLOWED")) ? ((DEF_MENU_BRIQUE_BDD_ALLOWED == "false") ? $brique_bb_allowed = false : $brique_bb_allowed = true)  : $brique_bb_allowed = true;
(defined("DEF_MENU_BRIQUE_GRAPHIQUE_ALLOWED")) ? ((DEF_MENU_BRIQUE_GRAPHIQUE_ALLOWED == "false") ? $brique_graphique_allowed = false : $brique_graphique_allowed = true)  : $brique_graphique_allowed = true;
(defined("DEF_MENU_BRIQUE_CLASSEUR_ALLOWED")) ? ((DEF_MENU_BRIQUE_CLASSEUR_ALLOWED == "false") ? $brique_classeur_allowed = false : $brique_classeur_allowed = true)  : $brique_classeur_allowed = true;
(defined("DEF_MENU_BRIQUE_DIAPORAMA_ALLOWED")) ? ((DEF_MENU_BRIQUE_DIAPORAMA_ALLOWED == "false") ? $brique_diaporama_allowed = false : $brique_diaporama_allowed = true)  : $brique_diaporama_allowed = true;
(defined("DEF_MENU_BRIQUE_CMS_NEWS_ALLOWED")) ? ((DEF_MENU_BRIQUE_CMS_NEWS_ALLOWED == "false") ? $brique_cms_news_allowed = false : $brique_cms_news_allowed = true)  : $brique_cms_news_allowed = true;
(defined("DEF_MENU_BRIQUE_VIDEO_ALLOWED")) ? ((DEF_MENU_BRIQUE_VIDEO_ALLOWED == "false") ? $brique_video_allowed = false : $brique_video_allowed = true)  : $brique_video_allowed = true;
(defined("DEF_MENU_CONTENU_ALLOWED")) ? ((DEF_MENU_CONTENU_ALLOWED == "false") ? $contenu_allowed = false : $contenu_allowed = true)  : $contenu_allowed = true;
(defined("DEF_MENU_CMS_SITE_ALLOWED")) ? ((DEF_MENU_CMS_SITE_ALLOWED == "false") ? $cms_site_allowed = false : $cms_site_allowed = true)  : $cms_site_allowed = true;
(defined("DEF_MENU_DROIT_ALLOWED")) ? ((DEF_MENU_DROIT_ALLOWED == "false") ? $cms_droit_allowed = false : $cms_droit_allowed = true)  : $cms_droit_allowed = true; 

if ($cms_site_allowed || $nameUser=="ccitron") {
	// gestion du site
	if (isAllowed ($rankUser, "ADMIN;GEST")  ) {
		$aMenu[] = new Menu("main", "gestionsite", $translator->getTransByCode('minisites'), "");
		if ($nameUser=="ccitron") {
			$aMenu[] = new Menu("gestionsite", "cms_site", $translator->getTransByCode('Liste_des_sites'), $URL_ROOT."/backoffice/cms/cms_site/list_cms_site.php");
		}
	} 
	
	if (isAllowed ("CMS", $sFonct)) {
		if (isAllowed ($rankUser, "ADMIN;GEST")) {
			$aMenu[] = new Menu("gestionsite", "cms_langue", $translator->getTransByCode('langues'), $URL_ROOT."/backoffice/cms/cms_langue/list_cms_langue.php");
			$aMenu[] = new Menu("gestionsite", "cms_translation", $translator->getTransByCode('Traductions'), $URL_ROOT."/backoffice/cms/cms_chaine_reference/list_cms_chaine_reference.php");
		}
	}
	
	if ($nameUser=="ccitron"){
		if (isAllowed ("CMS", $sFonct)) {
			if (isAllowed ($rankUser, "ADMIN;GEST")) {
				$aMenu[] = new Menu("gestionsite", "Recherche", $translator->getTransByCode('Recherche'), $URL_ROOT."/backoffice/cms/site/siteSearch.php");
			}
			if (isAllowed ($rankUser, "ADMIN;GEST")) {
				$aMenu[] = new Menu("gestionsite", "cms_filemanager", $translator->getTransByCode('Gestionnaires_de_fichiers'), $URL_ROOT."/backoffice/cms/cms_filemanager/list_cms_filemanager.php");
				$aMenu[] = new Menu("gestionsite", "cms_filemanageruser", $translator->getTransByCode('Comptes_gest_de_fichiers'), $URL_ROOT."/backoffice/cms/cms_filemanageruser/list_cms_filemanageruser.php");
			}
		}
		
		if (isAllowed ($rankUser, "ADMIN;GEST")) {
			$aMenu[] = new Menu("gestionsite", "gestionclasse", $translator->getTransByCode('Fonctions_Admin'), "");
			$aMenu[] = new Menu("gestionclasse", "Classes", $translator->getTransByCode('Classes'), $URL_ROOT."/backoffice/cms/classe/list_classe.php");
			$aMenu[] = new Menu("gestionclasse", "cms_custom", $translator->getTransByCode('Parametres_des_classes'), $URL_ROOT."/backoffice/cms/cms_custom/list_cms_custom.php");
			$aMenu[] = new Menu("gestionclasse", "Classesimp", $translator->getTransByCode('Import_de_classe'), $URL_ROOT."/backoffice/cms/classe/scan_classe.php");
			$aMenu[] = new Menu("gestionclasse", "gestioncms_assoclassenode", $translator->getTransByCode('Asso_nodes_classes'), $URL_ROOT."/backoffice/cms/cms_assoclassenode/list_cms_assoclassenode.php");
			$aMenu[] = new Menu("gestionclasse", "gestioninject_cms_assoclassenode", $translator->getTransByCode('Injecter_des_nodes'), $URL_ROOT."/backoffice/cms/cms_assoclassenode/inject_cms_assoclassenode.php");
			$aMenu[] = new Menu("gestionclasse", "gestioncms_prepend", $translator->getTransByCode('Prepend_scripts'), $URL_ROOT."/backoffice/cms/cms_prepend/list_cms_prepend.php");
			$aMenu[] = new Menu("gestionclasse", "gestioncms_js", $translator->getTransByCode('JS_scripts'), $URL_ROOT."/backoffice/cms/cms_js/list_cms_js.php");
			$aMenu[] = new Menu("gestionclasse", "cms_rss", $translator->getTransByCode('Gestion_des_flux_RSS'), $URL_ROOT."/backoffice/cms/cms_rss/list_cms_rss.php");
			$aMenu[] = new Menu("gestionclasse", "cms_mapskey", $translator->getTransByCode('Maps_API_Keys'), $URL_ROOT."/backoffice/cms/cms_mapskey/list_cms_mapskey.php");
			$aMenu[] = new Menu("gestionclasse", "cms_chartskey", $translator->getTransByCode('SWF_Charts_Keys'), $URL_ROOT."/backoffice/cms/cms_chartskey/list_cms_chartskey.php");
			$aMenu[] = new Menu("gestionclasse", "cms_bw", $translator->getTransByCode('Test_Bande_Passante'), $URL_ROOT."/backoffice/cms/utils/speedtest/speedtest4.php");
			$aMenu[] = new Menu("gestionclasse", "patch", $translator->getTransByCode('Maintenance_CMS'), $URL_ROOT."/backoffice/cms/patch.php");
			$aMenu[] = new Menu("gestionclasse", "metas", $translator->getTransByCode('Changer_les_meta_tags'), $URL_ROOT."/backoffice/cms/site/metaEditor.php");
			$aMenu[] = new Menu("gestionclasse", "dupli", $translator->getTransByCode('Cloner___Migrer_un_site'), $URL_ROOT."/backoffice/cms/dupli.php");
			$aMenu[] = new Menu("gestionclasse", "sql", $translator->getTransByCode('Executer_du_SQL'), $URL_ROOT."/backoffice/cms/utils/sql.php");
			$aMenu[] = new Menu("gestionclasse", "dump", $translator->getTransByCode('Dump_SQL'), $URL_ROOT."/backoffice/cms/utils/dump.php");
			$aMenu[] = new Menu("gestionclasse", "sonde", $translator->getTransByCode('Sondes'), $URL_ROOT."/backoffice/cms/cms_sonde/list_cms_sonde.php");
			$aMenu[] = new Menu("gestionclasse", "email", $translator->getTransByCode('Test_E_mail'), $URL_ROOT."/backoffice/cms/utils/email.php");
			$aMenu[] = new Menu("gestionclasse", "gestioncms_texte", $translator->getTransByCode('Traduction_du_BO'), $URL_ROOT."/backoffice/cms/cms_texte/list_cms_texte.php");
			$aMenu[] = new Menu("gestionclasse", "gestioncms_cron", $translator->getTransByCode('Cron'), $URL_ROOT."/backoffice/cms/cms_cron/list_cms_cron.php");
			$aMenu[] = new Menu("gestionclasse", "gestioncms_cron_generate", $translator->getTransByCode('Generer_le_fichier_Crontab'), $URL_ROOT."/backoffice/cms/cms_cron/generate_cms_cron.php");
		}
	}
}
	
if (isAllowed ("MINISITE", $sFonct)) {
	if (isAllowed ($rankUser, "ADMIN;GEST")) {
		$aMenu[] = new Menu("main", "gestionminisite", "Minisites", "");
		$aMenu[] = new Menu("gestionminisite", "add_cms_minisite", "Ajouter un minisite", $URL_ROOT."/backoffice/cms/cms_minisite/maj_cms_minisite.php");
	}
}

	
if (isAllowed ($rankUser, "ADMIN;GEST")) {$aMenu[] = new Menu("gestionminisite", "list_cms_minisite", "Liste des minisites", $URL_ROOT."/backoffice/cms/cms_minisite/list_cms_minisite.php");}
if (isAllowed ($rankUser, "ADMIN;GEST")) {$aMenu[] = new Menu("gestionminisite", "arbobrowse_minisite", "Parcourir l'arborescence des minisites", $URL_ROOT."/backoffice/cms/site/arboPage_browse.php?source=minisite");}
if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionminisite", "createpageWithNewGab_minisite", "Cr&eacute;er une page", $URL_ROOT."/backoffice/cms/site/pageLiteEditor.php");
if (isActived(DEF_MENUS_CCITRON)) $aMenu[] = new Menu("gestionminisite", "regenerategabarit_minisite", "Reg&eacute;n&eacute;ration des pages", $URL_ROOT."/backoffice/cms/regenerateAll.php");

if (isAllowed ("FORM", $sFonct)) {
	if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique_minisite", "formedit", "Brique Formulaire", $URL_ROOT."/backoffice/cms/formulaire/formulaireEditor.php?step=init");
}
			


// gestion des utilisateurs
if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("main", "gestionuser", $translator->getTransByCode('utilisateurs'), "");
if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionuser", "bo_users", $translator->getTransByCode('Utilisateurs'), $URL_ROOT."/backoffice/cms/bo_users/list_bo_users.php");
if (isAllowed ($rankUser, "ADMIN")) $aMenu[] = new Menu("gestionuser", "bo_groupes", $translator->getTransByCode('Groupes'), $URL_ROOT."/backoffice/cms/bo_groupes/list_bo_groupes.php");

if ($nameUser=="ccitron") {
	if (isAllowed ($rankUser, "ADMIN")) $aMenu[] = new Menu("gestionuser", "bo_rank", $translator->getTransByCode('Rangs'), $URL_ROOT."/backoffice/cms/bo_rank/list_bo_rank.php");
	if (isAllowed ($rankUser, "ADMIN")) $aMenu[] = new Menu("gestionuser", "gestion_cms_statut_content", $translator->getTransByCode('Statuts_de_validation'), $URL_ROOT."/backoffice/cms/cms_statut_content/list_cms_statut_content.php");	

	if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionuser", "gestioncms_sectionbo", $translator->getTransByCode('Gestion_des_sections_du_BO'), "");
	if (isAllowed ($rankUser, "ADMIN")) $aMenu[] = new Menu("gestioncms_sectionbo", "cms_sectionbo", $translator->getTransByCode('Liste_des_sections_du_BO'), $URL_ROOT."/backoffice/cms/cms_sectionbo/list_cms_sectionbo.php");
	if (isAllowed ($rankUser, "ADMIN")) $aMenu[] = new Menu("gestioncms_sectionbo", "cms_sectionboscan", $translator->getTransByCode('Imports_des_sections_du_BO'), $URL_ROOT."/backoffice/cms/cms_sectionbo/scan_cms_sectionbo.php");
}	
	

if (isAllowed ("CMS", $sFonct) ) {
	// gestion des gabarits
	if (isAllowed ("MINISITE", $sFonct) && $nameUser!="ccitron"	&&	!isAllowed ($rankUser, "ADMIN")) {
		//cas minisite, on n'affiche pas la gestion des gab / pages / briques sauf pour les admin parce que quand même, merde			
		
	}
	else {
		
		if (isAllowed ($rankUser, "ADMIN")) $aMenu[] = new Menu("main", "gestiongabarit", $translator->getTransByCode('gabarits'), "");
		
		if ($nameUser=="ccitron") { 
			if (isAllowed ($rankUser, "ADMIN")) $aMenu[] = new Menu("gestiongabarit", "GabSite", $translator->getTransByCode('Gabarits'), $URL_ROOT."/backoffice/cms/site/gabaritList.php");
			if (isAllowed ($rankUser, "ADMIN")) $aMenu[] = new Menu("gestiongabarit", "creategabarit", $translator->getTransByCode('Creer_un_gabarit'), $URL_ROOT."/backoffice/cms/site/gabaritEditor.php");
		}
		if (isActived(DEF_MENUS_CCITRON)) $aMenu[] = new Menu("gestiongabarit", "regenerategabarit", $translator->getTransByCode('Regeneration_gabarits'), $URL_ROOT."/backoffice/cms/regenerateAll.php");
		if (isActived(DEF_MENUS_CCITRON)) $aMenu[] = new Menu("gestiongabarit", "regeneratemanifest", $translator->getTransByCode('Regeneration_manifest'), $URL_ROOT."/backoffice/cms/regenerateManifest.php");
		
		// gestion des pages
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("main", "gestionpage", $translator->getTransByCode('pages'), "");
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionpage", "arbopagecontent", $translator->getTransByCode('Arborescence_et_pages'), $URL_ROOT."/backoffice/cms/site/arboPage_browse.php");
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionpage", "createpageWithNewGab", $translator->getTransByCode('Creer_une_page'), $URL_ROOT."/backoffice/cms/site/pageLiteEditor.php");
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionpage", "gestioncms_theme", $translator->getTransByCode('Themes'), $URL_ROOT."/backoffice/cms/cms_theme/list_cms_theme.php");
		if (isAllowed ($rankUser, "ADMIN;GEST") && $cms_droit_allowed ) $aMenu[] = new Menu("gestionpage", "gestiondroit", $translator->getTransByCode('Droits'), $URL_ROOT."/backoffice/cms/arboPageDroit_browse.php");
			
		// gestion des briques
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("main", "gestionbrique", $translator->getTransByCode('briques'), "");
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "arbobrowse", $translator->getTransByCode('Parcourir_l_arborescence_des_briques'), $URL_ROOT."/backoffice/cms/briques/arbo_browse.php");
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "contentedit", $translator->getTransByCode('Brique_HTML'), $URL_ROOT."/backoffice/cms/briques/contentEditor.php");
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "flashedit", $translator->getTransByCode('Brique_Flash'), $URL_ROOT."/backoffice/cms/briques/flashEditor.php");
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "videoedit", $translator->getTransByCode('Brique_Video'), $URL_ROOT."/backoffice/cms/briques/videoEditor.php");
		if (isAllowed ($rankUser, "ADMIN;GEST") && $brique_bb_allowed) $aMenu[] = new Menu("gestionbrique", "databaseedit", $translator->getTransByCode('Brique_Objet_Base_de_donnees'), $URL_ROOT."/backoffice/cms/briques/databaseEditor.php");
	
			
		if (isAllowed ("GRAPHICCUSTOM", $sFonct)) {
			if (isAllowed ($rankUser, "ADMIN;GEST") && $brique_graphique_allowed) $aMenu[] = new Menu("gestionbrique", "graphic", $translator->getTransByCode('Brique_Graphique'), $URL_ROOT."/backoffice/cms_custom/graphicEditor.php");
		}
		else {
			if (isAllowed ($rankUser, "ADMIN;GEST") && $brique_graphique_allowed) $aMenu[] = new Menu("gestionbrique", "graphic", $translator->getTransByCode('Brique_Graphique'), $URL_ROOT."/backoffice/cms/briques/graphicEditor.php");
		}
		//if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "contentmultimedia", "Brique Multimdia", $URL_ROOT."/backoffice/cms/multimediaEditor.php");
		//if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "contentdiaporama", "Brique Diaporama", $URL_ROOT."/backoffice/cms/diaporamaEditor.php");
		//if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "contentimageanimee", "Brique Image Anime", $URL_ROOT."/backoffice/cms/imageanimeeEditor.php");	
		//if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "chatedit", "xxx Brique Chat", $URL_ROOT."/backoffice/cms/chatEditor.php");
		//if (isAllowed ("FORUM", $sFonct)) {
		//if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "forumedit", "xxx Brique Forum", $URL_ROOT."/backoffice/cms/forumEditor.php");
		//}
		//if (isAllowed ("SONDAGE", $sFonct)) {
		//if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "surveyedit", "Brique Sondage", $URL_ROOT."/backoffice/cms/surveyEditor.php");
		//}
		//if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "formmailedit", "xxx Brique Formulaire (mail)", $URL_ROOT."/backoffice/cms/formEditor.php?step=init");
		
		// gestion de contenu		
		if ($contenu_allowed) {
			if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) {$aMenu[] = new Menu("main", "gestioncontenu", $translator->getTransByCode('contenus'), "");}
			if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) {$aMenu[] = new Menu("gestioncontenu", "listeContenus", $translator->getTransByCode('Tous_les_contenus'), $URL_ROOT."/backoffice/cms/contenus/listeContenus.php");}
			if (isAllowed ($rankUser, "ADMIN;GEST")) {$aMenu[] = new Menu("gestioncontenu", "listeContenusATTEN",  $translator->getTransByCode('Contenus_En_attente'), $URL_ROOT."/backoffice/cms/contenus/listeContenus.php?idStatut=".DEF_ID_STATUT_ATTEN);}
			if (isAllowed ($rankUser, "ADMIN;GEST")) {$aMenu[] = new Menu("gestioncontenu", "listeContenusREDACT", $translator->getTransByCode('Contenus_A_valider'), $URL_ROOT."/backoffice/cms/contenus/listeContenus.php?idStatut=".DEF_ID_STATUT_REDACT);}
			if (isAllowed ($rankUser, "ADMIN;GEST")) {$aMenu[] = new Menu("gestioncontenu", "listeContenusGEST", $translator->getTransByCode('Contenus_Valide'), $URL_ROOT."/backoffice/cms/contenus/listeContenus.php?idStatut=".DEF_ID_STATUT_GEST);}
			if (isAllowed ($rankUser, "ADMIN;GEST")) {$aMenu[] = new Menu("gestioncontenu", "listeContenusLIGNE", $translator->getTransByCode('Contenus_En_ligne'), $URL_ROOT."/backoffice/cms/contenus/listeContenusSauv.php?idStatut=".DEF_ID_STATUT_LIGNE);}
			if (isAllowed ($rankUser, "ADMIN;GEST")) {$aMenu[] = new Menu("gestioncontenu", "listeContenusARCHI", $translator->getTransByCode('Contenus_Archive'), $URL_ROOT."/backoffice/cms/contenus/listeContenus.php?idStatut=".DEF_ID_STATUT_ARCHI);}
			if (isAllowed ($rankUser, "ADMIN;GEST")) {$aMenu[] = new Menu("gestioncontenu", "listeContenusSAUV", $translator->getTransByCode('Contenus_Sauvegardes'), $URL_ROOT."/backoffice/cms/contenus/listeContenusSauv.php?idStatut=".DEF_ID_STATUT_ARCHI);}
		}
	}
	
	
	if (isAllowed ("FORM", $sFonct)) {
		if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "formedit", "Brique Formulaire", $URL_ROOT."/backoffice/cms/formulaire/formulaireEditor.php?step=init");
	}
	if (isAllowed ("SONDAGE", $sFonct)) {
		 if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionbrique", "surveyedit", "Brique Sondage", $URL_ROOT."/backoffice/cms/survey/surveyEditor.php");
	}
	
	if (isAllowed ("SONDAGE", $sFonct)) {
		if (isAllowed ($rankUser, "ADMIN;GEST")) {
			$aMenu[] = new Menu("gestionbrique", "gestionenquete", "Gestion des enquetes", "");
			$aMenu[] = new Menu("gestionenquete", "enqueteedit", "Liste des enquetes", "/backoffice/cms/cms_enquete/list_cms_enquete.php");
			$aMenu[] = new Menu("gestionenquete", "questionedit", "Liste des questions", "/backoffice/cms/cms_question/list_cms_question.php");
			$aMenu[] = new Menu("gestionenquete", "list_cms_assoenquetequestion", "Asso Enquete/Questions", "/backoffice/cms/cms_assoenquetequestion/list_cms_assoenquetequestion.php");
		}
	}	
	
	if (isAllowed ("GLOSSARY", $sFonct)) {	 // glossary 
		$aMenu[] = new Menu("main", "cms_glossary", "Glossaire", "");		
		$aMenu[] = new Menu("cms_glossary", "list_cms_glossary", "Liste des définitions", "/backoffice/cms/cms_glossary/list_cms_glossary.php");
	}
	
	if (isAllowed ("ACCOUNT", $sFonct)) {// comptes
		if (isAllowed ($rankUser, "ADMIN;GEST")) {
			$aMenu[] = new Menu("main", "account", $translator->getTransByCode('comptes'), "");
			$aMenu[] = new Menu("account", "account_user", $translator->getTransByCode('utilisateurs'), $URL_ROOT."/backoffice/cms/shp_client/list_shp_client.php");
			$aMenu[] = new Menu("account", "account_address", $translator->getTransByCode('adresses'), $URL_ROOT."/backoffice/cms/shp_adresse/list_shp_adresse.php");
		}
	}	
	elseif (isAllowed ("SHOP", $sFonct)) {// boutique			 
		$aMenu[] = new Menu("main", "shop", "Boutique", "");		 
		$aMenu[] = new Menu("shop", "shop_admin", "Administration", "");		 
		$aMenu[] = new Menu("shop", "gestionshp_produit", "Fonctionnement", "");		 
		$aMenu[] = new Menu("shop_admin", "list_shp_statut", "Liste des statuts de commande", "/backoffice/cms/shp_commande_statut/list_shp_commande_statut.php");		
		$aMenu[] = new Menu("shop_admin", "list_shp_gamme", "Liste des ".getClassLibelle('shp_gamme'), "/backoffice/cms/shp_gamme/list_shp_gamme.php");
		$aMenu[] = new Menu("shop_admin", "list_shp_asso_produitgamme", "Liste des ".getClassLibelle('shp_asso_produitgamme'), "/backoffice/cms/shp_asso_produitgamme/list_shp_asso_produitgamme.php");		
		$aMenu[] = new Menu("shop_admin", "list_shp_produit_type", "Liste des ".getClassLibelle('shp_produit_type'), "/backoffice/cms/shp_produit_type/list_shp_produit_type.php");	
		$aMenu[] = new Menu("shop_admin", "list_shp_unite", "Liste des ".getClassLibelle('shp_unite'), "/backoffice/cms/shp_unite/list_shp_unite.php");	
		$aMenu[] = new Menu("shop_admin", "list_shp_transporteur", "Liste des transporteurs", "/backoffice/cms/shp_transporteur/list_shp_transporteur.php");	
		$aMenu[] = new Menu("shop_admin", "list_shp_frais_port_grille", "Liste des grilles de frais de port", "/backoffice/cms/shp_frais_port_grille/list_shp_frais_port_grille.php");	
		$aMenu[] = new Menu("shop_admin", "list_shp_frais_port_valeur", "Liste des valeurs de frais de port", "/backoffice/cms/shp_frais_port_valeur/list_shp_frais_port_valeur.php");	
		$aMenu[] = new Menu("gestionshp_produit", "liste_shp_produit", "Liste des ".getClassLibelle('shp_produit'), "/backoffice/cms/shp_produit/list_shp_produit.php");
		$aMenu[] = new Menu("gestionshp_produit", "liste_shp_tarif", "Liste des ".getClassLibelle('shp_tarif'), "/backoffice/cms/shp_tarif/list_shp_tarif.php");
		$aMenu[] = new Menu("gestionshp_produit", "liste_shp_commande", "Liste des commandes", "/backoffice/cms/shp_commande/list_shp_commande.php");	
		$aMenu[] = new Menu("gestionshp_produit", "liste_shp_client", "Liste des clients", "/backoffice/cms/shp_client/list_shp_client.php");	
		$aMenu[] = new Menu("gestionshp_produit", "liste_shp_adresse", "Liste des adresses des clients", "/backoffice/cms/shp_adresse/list_shp_adresse.php");	
	}		
	elseif (isAllowed ("CATALOG", $sFonct)) {	 // catalogue	
		$aMenu[] = new Menu("main", "catalog", "Gestion des produits", "");		 
		$aMenu[] = new Menu("catalog", "liste_shp_produit", "Liste des ".getClassLibelle('shp_produit'), "/backoffice/cms/shp_produit/list_shp_produit.php");
		$aMenu[] = new Menu("catalog", "list_shp_produit_type", "Liste des ".getClassLibelle('shp_produit_type'), "/backoffice/cms/shp_produit_type/list_shp_produit_type.php");
		$aMenu[] = new Menu("catalog", "list_shp_gamme", "Liste des ".getClassLibelle('shp_gamme'), "/backoffice/cms/shp_gamme/list_shp_gamme.php");
		$aMenu[] = new Menu("catalog", "list_shp_asso_produitgamme", "Liste des ".getClassLibelle('shp_asso_produitgamme'), "/backoffice/cms/shp_asso_produitgamme/list_shp_asso_produitgamme.php");		
		$aMenu[] = new Menu("catalog", "list_shp_unite", "Liste des ".getClassLibelle('shp_unite'), "/backoffice/cms/shp_unite/list_shp_unite.php");		
		$aMenu[] = new Menu("gestionshp_produit", "liste_shp_tarif", "Liste des ".getClassLibelle('shp_tarif'), "/backoffice/cms/shp_tarif/list_shp_tarif.php");
	}
	
	if (isAllowed ("TAG", $sFonct)) { 
		if (isAllowed($rankUser, "ADMIN;GEST;REDACT")) {
			$aMenu[] = new Menu("main", "tag", "Tags", "");
			$aMenu[] = new Menu("tag", "list_tag", "Liste des tags", $URL_ROOT."/backoffice/cms/cms_tag/list_cms_tag.php");
			$aMenu[] = new Menu("tag", "list_titre", "Liste des titres", $URL_ROOT."/backoffice/cms/cms_title/list_cms_title.php");
			$aMenu[] = new Menu("tag", "list_description", "Liste des descriptions", $URL_ROOT."/backoffice/cms/cms_description/list_cms_description.php");
		}
	}
	
	if (isAllowed ("RSS", $sFonct)) { 
		if (isAllowed($rankUser, "ADMIN;GEST;REDACT")) {
			$aMenu[] = new Menu("main", "rss_url", "Flux RSS", ""); 
			$aMenu[] = new Menu("rss_url", "liste_cms_rss_url", "Liste des flux", $URL_ROOT."/backoffice/cms/cms_rss_url/list_cms_rss_url.php"); 
		}
	}
	
	if (isAllowed ("JOB", $sFonct)) {	 
		$aMenu[] = new Menu("main", "cms_job", "Job", "");		
		$aMenu[] = new Menu("cms_job", "list_job_offre", $translator->getTransByCode('Offres'), "/backoffice/cms/job_offre/list_job_offre.php"); 
		$urlCandidate = $URL_ROOT."/backoffice/cms/job_candidature/list_job_candidature.php?param=job_contrat&job_contrat=".urlencode('(1,5)')."&comparateur=".urlencode("in")."&titre=".$translator->getTransByCode('Candidatures_aux_stages');
		$aMenu[] = new Menu("cms_job", "list_job_candidature_1", $translator->getTransByCode('Candidatures_aux_stages'), $urlCandidate);
                /*
                $urlCandidate = $URL_ROOT."/backoffice/cms/job_candidature/list_job_candidature.php?param=job_contrat&job_contrat=5&comparateur=".urlencode("=")."&titre=".$translator->getTransByCode('Candidatures_apprentissage');
		$aMenu[] = new Menu("cms_job", "list_job_candidature_1_b", $translator->getTransByCode('Candidatures_apprentissage'), $urlCandidate);
                */
                
		$urlCandidate = $URL_ROOT."/backoffice/cms/job_candidature/list_job_candidature.php?param=job_contrat&job_contrat=1&comparateur=".urlencode(">")."&paramtype2=job_candidature&param2=contrat&contrat=5&comparateur2=".urlencode("<>")."&titre=".$translator->getTransByCode('Candidatures_aux_emplois');
		$aMenu[] = new Menu("cms_job", "list_job_candidature_2", $translator->getTransByCode('Candidatures_aux_emplois'), $urlCandidate);
		$urlCandidate = $URL_ROOT."/backoffice/cms/job_candidature/list_job_candidature.php?param=job_contrat&job_contrat=-1&comparateur=".urlencode("=")."&titre=".$translator->getTransByCode('Candidatures_spontanees');
		$aMenu[] = new Menu("cms_job", "list_job_candidature_3", $translator->getTransByCode('Candidatures_spontanees'), $urlCandidate);
		$aMenu[] = new Menu("cms_job", "list_job_reponse", $translator->getTransByCode('Reponses'), "/backoffice/cms/job_reponse/list_job_reponse.php");
		$aMenu[] = new Menu("cms_job", "list_cms_log_statut", $translator->getTransByCode('Historique_des_changements_de_statut'), "/backoffice/cms/cms_log_statut/list_cms_log_statut.php");
		
		$aMenu[] = new Menu("cms_job", "cms_jobextra", $translator->getTransByCode('Extras'), "");	
		$aMenu[] = new Menu("cms_jobextra", "list_job_destinataire", $translator->getTransByCode('Destinataires'), "/backoffice/cms/job_destinataire/list_job_destinataire.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_lieu", $translator->getTransByCode('Sites'), "/backoffice/cms/job_lieu/list_job_lieu.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_lettre", $translator->getTransByCode('Modeles_de_reponse'), "/backoffice/cms/job_lettre/list_job_lettre.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_contrat", $translator->getTransByCode('Types_de_contrat'), "/backoffice/cms/job_contrat/list_job_contrat.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_metier", $translator->getTransByCode('Metiers'), "/backoffice/cms/job_metier/list_job_metier.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_domaine", $translator->getTransByCode('Domaines_de_formation'), "/backoffice/cms/job_domaine/list_job_domaine.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_qualification", $translator->getTransByCode('Niveaux_de_qualification'), "/backoffice/cms/job_qualification/list_job_qualification.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_experience", $translator->getTransByCode('Niveaux_d_experience'), "/backoffice/cms/job_experience/list_job_experience.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_langue", $translator->getTransByCode('Langues_parlees'), "/backoffice/cms/job_langue/list_job_langue.php");
		$aMenu[] = new Menu("cms_jobextra", "list_job_niveaulangue", $translator->getTransByCode('Niveaux_de_langue'), "/backoffice/cms/job_niveaulangue/list_job_niveaulangue.php");	
	}
}

if (isAllowed ("CMS", $sFonct)){ // fichiers
	if (isAllowed ($rankUser, "ADMIN;GEST")){
		$aMenu[] = new Menu("main", "fichiers", $translator->getTransByCode('fichiers'), "");	
		if ($brique_classeur_allowed) {
			$aMenu[] = new Menu("fichiers", "cms_binder", $translator->getTransByCode('Classeurs_de_PDF'), "");
			$aMenu[] = new Menu("cms_binder", "list_cms_binder", $translator->getTransByCode('Classeurs'), "/backoffice/cms/cms_binder/list_cms_binder.php");
			$aMenu[] = new Menu("cms_binder", "list_cms_pdf", $translator->getTransByCode('Liste_des_PDF'), "/backoffice/cms/cms_pdf/list_cms_pdf.php");
			$aMenu[] = new Menu("cms_binder", "list_cms_assobinderpdf", $translator->getTransByCode('Asso_Classeur_PDF'), "/backoffice/cms/cms_assobinderpdf/list_cms_assobinderpdf.php");
		}
		if ($brique_diaporama_allowed) {
			$aMenu[] = new Menu("fichiers", "cms_diaporama", $translator->getTransByCode('Diaporamas'), "");
			$aMenu[] = new Menu("cms_diaporama", "list_cms_diaporama", $translator->getTransByCode('Diaporamas'), "/backoffice/cms/cms_diaporama/list_cms_diaporama.php");
			$aMenu[] = new Menu("cms_diaporama", "list_cms_diaporama_type", $translator->getTransByCode('Types_de_diaporamas'), "/backoffice/cms/cms_diaporama_type/list_cms_diaporama_type.php");
			$aMenu[] = new Menu("cms_diaporama", "list_cms_diapo", $translator->getTransByCode('Images'), "/backoffice/cms/cms_diapo/list_cms_diapo.php");	
			$aMenu[] = new Menu("cms_diaporama", "fileimport_cms_diapo", $translator->getTransByCode('Importer_des_images'), "/backoffice/cms/cms_diapo/fileimport_cms_diapo.php");	
			$aMenu[] = new Menu("cms_diaporama", "list_cms_assodiapodiaporama", $translator->getTransByCode('Asso_images_diaporama'), "/backoffice/cms/cms_assodiapodiaporama/list_cms_assodiapodiaporama.php");		
		}	
		
		// video
		$aMenu[] = new Menu("main", "videos", $translator->getTransByCode('Video_sitemap'), "");	
		if ($brique_video_allowed) {
			$aMenu[] = new Menu("videos", "list_video", $translator->getTransByCode('Videos'), "/backoffice/cms/cms_video/list_cms_video.php"); 
			$aMenu[] = new Menu("videos", "scan_video", $translator->getTransByCode('Import_de_videos'), $URL_ROOT."/backoffice/cms/cms_video/scan_cms_video.php"); 
		}			
	}
}

if (isAllowed ("FM", $sFonct)||isAllowed ("CMS", $sFonct)){	// fichiers
	if (!isAllowed ("CMS", $sFonct)){ // fichiers
		$aMenu[] = new Menu("main", "fichiers", "Gestion des fichiers", "");
	}
	
	if (is_dir($_SERVER['DOCUMENT_ROOT'].'/documents')	||	isAllowed ($rankUser, "ADMIN")	){	
		$aMenu[] = new Menu("fichiers", "filemanager", $translator->getTransByCode('Gestionnaire_de_fichiers'), "");		
		if (is_dir($_SERVER['DOCUMENT_ROOT'].'/documents')){			
			$aMenu[] = new Menu("filemanager", "documents", "Documents", $URL_ROOT."/backoffice/cms/filemanager/admin.php?dir_id=0");
		}
		if (isAllowed ($rankUser, "ADMIN")) {
			if (is_dir($_SERVER['DOCUMENT_ROOT'].'/pdf')){
				$aMenu[] = new Menu("filemanager", "pdf", "PDF", $URL_ROOT."/backoffice/cms/filemanager/admin.php?dir_id=1");
			}
			if (is_dir($_SERVER['DOCUMENT_ROOT'].'/custom/img')){			
				$aMenu[] = new Menu("filemanager", "imagemanagementsUpload", $translator->getTransByCode('Images_telechargees'), $URL_ROOT."/backoffice/cms/filemanager/admin.php?dir_id=4");
			}
		}
	}
	
	// recherche de cms_filemanager
	if (class_exists('cms_filemanager')){
		$aFM = dbGetObjectsFromFieldValue("cms_filemanager", array('get_statut', 'get_cms_site'),  array(DEF_ID_STATUT_LIGNE, $_SESSION['idSite']), NULL);
		if ((count($aFM) > 0)&&($aFM!=false)){
			foreach($aFM as $cKey => $oFM){
				$aMenu[] = new Menu("filemanager", "filemanagerUpload".$oFM->get_id(), $oFM->get_nom(), $URL_ROOT."/backoffice/cms/filemanager/admin.php?dir_id=-".$oFM->get_id()); // appel en id negatif
				
				// check des fichiers necessaires
				dirExists('/content/'.$_SESSION['rep_travail']);
				if (!is_file($_SERVER['DOCUMENT_ROOT'].'/content/'.$_SESSION['rep_travail'].'/index.php')){					
					$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/content/'.$_SESSION['rep_travail'].'/index.php', "w");
					if ($fh){
						$sContent = "<"."?php include('backoffice/cms/filemanager/frontoffice/ls.php'); ?".">";
						fwrite($fh, $sContent);
						fclose($fh);
					}				
				}	//if (!is_file($			
			}//foreach($aFM as $cKey => $oFM){		
		}//if ((count($aFM) > 0)&&($aFM!=false)){
	}//if (class_exists('cms_filemanager')){
}

// petites annonces
if (isAllowed ("PA", $sFonct)) {
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("main", "gestionpa", "Petites annonces", "");
  
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestionpa", "gestion_pa_inscrits", "Inscrits", "");
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestion_pa_inscrits", "listInscrip", "Tous", $URL_ROOT."/backoffice/cms/pa_inscrit/list_pa_inscrit.php");
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestion_pa_inscrits", "listInscripValidees", "Valid&eacute;s", $URL_ROOT."/backoffice/cms/pa_inscrit/list_pa_inscrit.php?param=pa_est_valide&pa_est_valide=1&comparateur=%3D");
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestion_pa_inscrits", "listInscripAttenteValidation", "En attente de validation", $URL_ROOT."/backoffice/cms/pa_inscrit/list_pa_inscrit.php?param=pa_est_valide&pa_est_valide=0&comparateur=%3D");
  
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestionpa", "gestion_pa_annonces", "Annonces", "");
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestion_pa_annonces", "demAnn", "Toutes", $URL_ROOT."/backoffice/cms/pa_annonce/list_pa_annonce.php");
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestion_pa_annonces", "demAnnValidees", "Valid&eacute;es", $URL_ROOT."/backoffice/cms/pa_annonce/list_pa_annonce.php?param=pa_est_validee&pa_est_validee=1&comparateur=%3D");
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestion_pa_annonces", "demAnnAttenteValidation", "En attente de validation", $URL_ROOT."/backoffice/cms/pa_annonce/list_pa_annonce.php?param=pa_est_validee&pa_est_validee=0&comparateur=%3D");
  
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestionpa", "gestion_pa_quartiers", "Quartiers", "");
  if (isAllowed ($rankUser, "ADMIN;GEST;REDACT")) $aMenu[] = new Menu("gestion_pa_quartiers", "demQuartiers", "Liste", $URL_ROOT."/backoffice/cms/pa_quartier/list_pa_quartier.php");
}

// actus

if (isAllowed ("NEWS", $sFonct)) {
	if ($translator->getTransByCode("liste_news") == '') { 
		$translator->addTransByCode("liste_news", "Liste des news"); 
	}
	if (class_exists('nws_content')){
		$aMenu[] = new Menu("main", "nws_content", $translator->getTransByCode('actualites'), "");		
		$aMenu[] = new Menu("nws_content", "list_nws_content", $translator->getTransByCode("liste_news"), "/backoffice/cms/nws_content/list_nws_content.php");
	}
	elseif (class_exists('cms_news')){
		$aMenu[] = new Menu("main", "cms_news", $translator->getTransByCode('actualites'), "");	
		$aMenu[] = new Menu("cms_news", "list_cms_news", $translator->getTransByCode("liste_news"), "/backoffice/cms/cms_news/list_cms_news.php");	
	}
}

// newsletter
if (isAllowed ("NEWSLETTER", $sFonct)) {
	
	$aMenu[] = new Menu("main", "gestion_newsletter", $translator->getTransByCode('newsletter'), "");
	
	if (in_array("newsletter", $_SESSION['cms_classes']))
		$aMenu[] = new Menu("gestion_newsletter", "liste_newsletter", $translator->getTransByCode('Newsletters'), $URL_ROOT."/backoffice/cms/newsletter/list_newsletter.php");
	else 
		$aMenu[] = new Menu("gestion_newsletter", "liste_newsletter", $translator->getTransByCode('Newsletters'), $URL_ROOT."/backoffice/newsletter/list_newsletter.php");
	if (isAllowed ($rankUser, "ADMIN")) {
		if (in_array("news_theme", $_SESSION['cms_classes']))
			$aMenu[] = new Menu("gestion_newsletter", "liste_theme", $translator->getTransByCode('Themes'), $URL_ROOT."/backoffice/cms/news_theme/list_news_theme.php");
		else 
			$aMenu[] = new Menu("gestion_newsletter", "liste_theme", $translator->getTransByCode('Themes'), $URL_ROOT."/backoffice/news_theme/list_news_theme.php");
	}	
	if (in_array("news_inscrit", $_SESSION['cms_classes']))	
		$aMenu[] = new Menu("gestion_newsletter", "liste_inscrit", $translator->getTransByCode('Inscrits'), $URL_ROOT."/backoffice/cms/news_inscrit/list_news_inscrit.php");
	else 
		$aMenu[] = new Menu("gestion_newsletter", "liste_inscrit", $translator->getTransByCode('Inscrits'), $URL_ROOT."/backoffice/news_inscrit/list_news_inscrit.php");
	
	if (in_array("news_inscrit", $_SESSION['cms_classes']))		
		$aMenu[] = new Menu("gestion_newsletter", "import_inscrit", $translator->getTransByCode('Import_d_inscrits'), $URL_ROOT."/backoffice/cms/news_inscrit/import_news_inscrit.php");
	else 
		$aMenu[] = new Menu("gestion_newsletter", "import_inscrit", $translator->getTransByCode('Import_d_inscrits'), $URL_ROOT."/backoffice/news_inscrit/import_news_inscrit.php");
	
	if (in_array("news_assoinscrittheme", $_SESSION['cms_classes']))			
		$aMenu[] = new Menu("gestion_newsletter", "liste_assoinscrittheme", $translator->getTransByCode('Abonnements'), $URL_ROOT."/backoffice/cms/news_assoinscrittheme/list_news_assoinscrittheme.php");
	else 
		$aMenu[] = new Menu("gestion_newsletter", "liste_assoinscrittheme", $translator->getTransByCode('Abonnements'), $URL_ROOT."/backoffice/news_assoinscrittheme/list_news_assoinscrittheme.php");
	
	if (isAllowed ($rankUser, "ADMIN")) {
		if (in_array("news_expediteur", $_SESSION['cms_classes']))
			$aMenu[] = new Menu("gestion_newsletter", "liste_expediteur", $translator->getTransByCode('E_mails_en_reply_to_des_mails'), $URL_ROOT."/backoffice/cms/news_expediteur/list_news_expediteur.php");
		else
			$aMenu[] = new Menu("gestion_newsletter", "liste_expediteur", $translator->getTransByCode('E_mails_en_reply_to_des_mails'), $URL_ROOT."/backoffice/news_expediteur/list_news_expediteur.php");
	}
}

if (isAllowed ("SONDAGE", $sFonct)) {
	if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("main", "survey", "Sondage", "");
	if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("survey", "ask", "Questions", $URL_ROOT."/backoffice/cms/survey/question.php");
}

if (isAllowed ("COMMENT", $sFonct)) {
	if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("main", "comment", "Avis", "");
	if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("comment", "list_comment", "Liste des commentaires", $URL_ROOT."/backoffice/cms/cms_avis/list_cms_avis.php");
}

if (isAllowed ("NEWSLETTER2006", $sFonct) && DEF_FONCT_NEWSLETTER == "ON") {
	$aMenu[] = new Menu("", "gestiondesnewsletter", $translator->getTransByCode('newsletter'), "");// gestion des inscrits
	$aMenu[] = new Menu("gestiondesnewsletter", "listeinscrits", $translator->getTransByCode('Inscrits'), $URL_ROOT."/backoffice/inscrit/inscritList.php");
	if (DEF_LITE_NEWSLETTER == "OFF") {
	$aMenu[] = new Menu("gestiondesnewsletter", "liste_newsletter", $translator->getTransByCode('Newsletters'), $URL_ROOT."/backoffice/newsletter/news_list.php");
	$aMenu[] = new Menu("gestiondesnewsletter", "sendnews_int", "Envoyer la newsletter", $URL_ROOT."/backoffice/newsletter/envoi_newsletter.php");
	$aMenu[] = new Menu("gestiondesnewsletter", "list_envois", "Liste des envois", $URL_ROOT."/backoffice/newsletter/envoi_list.php");
	}
}

if (isAllowed ("SS", $sFonct)) {
	// gestion des slides
	$aMenu[] = new Menu("main", "gestionslide", "Gestion des slides", "");
	$aMenu[] = new Menu("gestionslide", "slide", "Liste des slides", $URL_ROOT."/backoffice/slide/list_slide.php");
	$aMenu[] = new Menu("gestionslide", "optionsslides", "Options", "");
	$aMenu[] = new Menu("optionsslides", "importslides", "Import de nouvelles slides", $URL_ROOT."/backoffice/slide/swfimport/swfimport_slide.php");
	
	// gestion des theme
	$aMenu[] = new Menu("main", "gestionvideo", "Gestion des vidéos", "");
	$aMenu[] = new Menu("gestionvideo", "lis_video", "Vidéos", $URL_ROOT."/backoffice/video/list_video.php");
	$aMenu[] = new Menu("gestionvideo", "import_video", "Import de nouvelles vidéos", $URL_ROOT."/backoffice/video/flvimport_video.php");
		
	// gestion des playlists
	$aMenu[] = new Menu("main", "gestionplaylist", "Gestion des playlists", "");
	$aMenu[] = new Menu("gestionplaylist", "lis_playlist", "Playlists", $URL_ROOT."/backoffice/playlist/list_playlist.php");
	
	// gestion des slideshow
	$aMenu[] = new Menu("main", "gestionslideshow", "Gestion des slideshows", "");
	$aMenu[] = new Menu("gestionslideshow", "lis_slideshow", "Slideshow", $URL_ROOT."/backoffice/slideshow/list_slideshow.php");
	
	// gestion des theme
	$aMenu[] = new Menu("main", "gestiontheme", "Gestion des themes", "");
	$aMenu[] = new Menu("gestiontheme", "lis_theme", "Theme", $URL_ROOT."/backoffice/theme/list_theme.php");

}

if (isAllowed ("SS3", $sFonct)) {
	$aMenu[] = new Menu("main", "ss3", "Slideshow 3", "");

	// gestion des slides
	$aMenu[] = new Menu("ss3", "gestionss3_slide3", "Planches", "");
	$aMenu[] = new Menu("gestionss3_slide3", "ss3_slide3", "Liste des slides", $URL_ROOT."/backoffice/adss/ss3_slide3/list_ss3_slide3.php");
	$aMenu[] = new Menu("gestionss3_slide3", "ss3_gabarit3", "Gabarits", $URL_ROOT."/backoffice/adss/ss3_gabarit3/list_ss3_gabarit3.php");
	$aMenu[] = new Menu("gestionss3_slide3", "ss3_typegab", "Types de gabarits", $URL_ROOT."/backoffice/adss/ss3_typegab/list_ss3_typegab.php");	
	
	$aMenu[] = new Menu("gestionss3_slide3", "ss3_ztype", "Editeurs", $URL_ROOT."/backoffice/adss/ss3_ztype/list_ss3_ztype.php");	
	$aMenu[] = new Menu("gestionss3_slide3", "ss3_theme3", "Thèmes", $URL_ROOT."/backoffice/adss/ss3_theme3/list_ss3_theme3.php");
	$aMenu[] = new Menu("gestionss3_slide3", "optionsss3_slide3", "Options", "");
	$aMenu[] = new Menu("optionsss3_slide3", "preview_ss3_slides3", "Preview du XML des slides", $URL_ROOT."/backoffice/adss/ss3_slide3/preview_ss3_slide3.php");
	
	// gestion des videos
	$aMenu[] = new Menu("ss3", "gestionss3_video", "Vid&eacute;os et MP3", "");
	$aMenu[] = new Menu("gestionss3_video", "list_ss3_video", "Vid&eacute;os", $URL_ROOT."/backoffice/adss/ss3_video/list_ss3_video.php");
	$aMenu[] = new Menu("gestionss3_video", "list_ss3_audio", "MP3", $URL_ROOT."/backoffice/adss/ss3_audio/list_ss3_audio.php");
		
	// gestion des playlists
	$aMenu[] = new Menu("ss3", "gestionss3_playlist3", "Présentations", "");
	$aMenu[] = new Menu("gestionss3_playlist3", "list_ss3_playlist3", "Playlists", $URL_ROOT."/backoffice/adss/ss3_playlist3/list_ss3_playlist3.php");
	$aMenu[] = new Menu("gestionss3_playlist3", "list_ss3_pltheme", "Thèmes", $URL_ROOT."/backoffice/adss/ss3_pltheme/list_ss3_pltheme.php");
	
	$aMenu[] = new Menu("gestionss3_playlist3", "optionsss3_playlist3", "Options", "");
	$aMenu[] = new Menu("optionsss3_playlist3", "preview_ss3_playlist3", "Preview du XML des playlists", $URL_ROOT."/backoffice/adss/ss3_playlist3/preview_ss3_playlist3.php");
	$aMenu[] = new Menu("optionsss3_playlist3", "purgecache_ss3_playlist3", "Purger le cache", $URL_ROOT."/backoffice/adss/ss3_playlist3/purgecache_ss3_playlist3.php");
	$aMenu[] = new Menu("optionsss3_playlist3", "importfromzip_ss3_playlist3", "Importer une présentation (zip)", $URL_ROOT."/backoffice/adss/ss3_playlist3/importfromzip_ss3_playlist3.php");

	// gestion des slideshow
	$aMenu[] = new Menu("ss3", "gestionss3_slideshow3", "Slideshows", "");
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_slideshow3", "Liste des slideshows", $URL_ROOT."/backoffice/adss/ss3_slideshow3/list_ss3_slideshow3.php");
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_couleur", "Couleurs", $URL_ROOT."/backoffice/adss/ss3_couleur/list_ss3_couleur.php");	
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_graphcolset", "Sets de Couleurs (Graphes)", $URL_ROOT."/backoffice/adss/ss3_graphcolset/list_ss3_graphcolset.php");
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_picto", "Pictos", $URL_ROOT."/backoffice/adss/ss3_picto/list_ss3_picto.php");
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_mapfond", "Fonds de cartes", $URL_ROOT."/backoffice/adss/ss3_mapfond/list_ss3_mapfond.php");
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_mapcategorie", "Catégories de cartes", $URL_ROOT."/backoffice/adss/ss3_mapcategorie/list_ss3_mapcategorie.php");
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_font", "Polices", $URL_ROOT."/backoffice/adss/ss3_font/list_ss3_font.php");
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_style", "Styles", $URL_ROOT."/backoffice/adss/ss3_style/list_ss3_style.php");
	$aMenu[] = new Menu("gestionss3_slideshow3", "ss3_piecejointe", "Pièces jointes", $URL_ROOT."/backoffice/adss/ss3_piecejointe/list_ss3_piecejointe.php");	
	
	if (isAllowed ($rankUser, "ADMIN")){
		$aMenu[] = new Menu("gestionss3_slideshow3", "start_ss3_slideshow3", "Démarrer un nouv. Slideshow", $URL_ROOT."/backoffice/adss/ss3_slideshow3/start_ss3_slideshow3.php");
		$aMenu[] = new Menu("gestionss3_slideshow3", "addusers_ss3_slideshow3", "Créer des utilisateurs", $URL_ROOT."/backoffice/adss/ss3_slideshow3/addusers_ss3_slideshow3.php");
	}
	
	// gestion des images
	$aMenu[] = new Menu("ss3", "gestionss3_banque", "Banque d'Images", "");
	$aMenu[] = new Menu("gestionss3_banque", "gestionss3_image", "Images", $URL_ROOT."/backoffice/adss/ss3_image/list_ss3_image.php");
	$aMenu[] = new Menu("gestionss3_banque", "gestionss3_imagefolder", "Dossiers d'images", $URL_ROOT."/backoffice/adss/ss3_imagefolder/list_ss3_imagefolder.php");

	// gestion des cartes
	if (isAllowed ("GEO", $sFonct)) {
		$aMenu[] = new Menu("ss3", "gestionss3_maps", "GoogleMaps", "");
		$aMenu[] = new Menu("gestionss3_maps", "ss3_cartes", "Liste des cartes", $URL_ROOT."/backoffice/cms/cms_geo_carte/list_cms_geo_carte.php");
		$aMenu[] = new Menu("gestionss3_maps", "ss3_points", "Liste des points g&eacute;olocalis&eacute;s", $URL_ROOT."/backoffice/cms/cms_geo_point/list_cms_geo_point.php");
		$aMenu[] = new Menu("gestionss3_maps", "ss3_pictos", "Liste des pictogrammes", $URL_ROOT."/backoffice/cms/cms_geo_pictogramme/list_cms_geo_pictogramme.php");
	}
	
	// gestion des traductions
	$aMenu[] = new Menu("ss3", "gestionss3_texte", "Traductions", "");
	$aMenu[] = new Menu("gestionss3_texte", "ss3_texte", "Liste des textes", $URL_ROOT."/backoffice/adss/ss3_texte/list_ss3_texte.php");
}

if (isAllowed ("SS4", $sFonct)) {
	$aMenu[] = new Menu("main", "ss4", "Slideshow 4", "");

	// gestion des slides
	$aMenu[] = new Menu("ss4", "gestionss4_slide", "Planches", "");
	$aMenu[] = new Menu("gestionss4_slide", "ss4_slide", "Liste des slides", $URL_ROOT."/backoffice/adss/ss4_slide/list_ss4_slide.php");
	$aMenu[] = new Menu("gestionss4_slide", "ss4_gabarit", "Gabarits", $URL_ROOT."/backoffice/adss/ss4_gabarit/list_ss4_gabarit.php");
	$aMenu[] = new Menu("gestionss4_slide", "ss4_typegab", "Types de gabarits", $URL_ROOT."/backoffice/adss/ss4_typegab/list_ss4_typegab.php");	
	
	$aMenu[] = new Menu("gestionss4_slide", "ss4_ztype", "Editeurs", $URL_ROOT."/backoffice/adss/ss4_ztype/list_ss4_ztype.php");	
	$aMenu[] = new Menu("gestionss4_slide", "ss4_theme", "Thèmes", $URL_ROOT."/backoffice/adss/ss4_theme/list_ss4_theme.php");
	$aMenu[] = new Menu("gestionss4_slide", "optionsss4_slide", "Options", "");
	$aMenu[] = new Menu("optionsss4_slide", "preview_ss4_slides", "Preview du XML des slides", $URL_ROOT."/backoffice/adss/ss4_slide/preview_ss4_slide.php");
	
	// gestion des videos
	$aMenu[] = new Menu("ss4", "gestionss4_video", "Vid&eacute;os et MP", "");
	$aMenu[] = new Menu("gestionss4_video", "list_ss4_video", "Vid&eacute;os", $URL_ROOT."/backoffice/adss/ss4_video/list_ss4_video.php");
	$aMenu[] = new Menu("gestionss4_video", "list_ss4_audio", "MP", $URL_ROOT."/backoffice/adss/ss4_audio/list_ss4_audio.php");
		
	// gestion des playlists
	$aMenu[] = new Menu("ss4", "gestionss4_playlist", "Présentations", "");
	$aMenu[] = new Menu("gestionss4_playlist", "list_ss4_playlist", "Playlists", $URL_ROOT."/backoffice/adss/ss4_playlist/list_ss4_playlist.php");
	$aMenu[] = new Menu("gestionss4_playlist", "list_ss4_pltheme", "Thèmes", $URL_ROOT."/backoffice/adss/ss4_pltheme/list_ss4_pltheme.php");
	
	$aMenu[] = new Menu("gestionss4_playlist", "optionsss4_playlist", "Options", "");
	$aMenu[] = new Menu("optionsss4_playlist", "preview_ss4_playlist", "Preview du XML des playlists", $URL_ROOT."/backoffice/adss/ss4_playlist/preview_ss4_playlist.php");
	$aMenu[] = new Menu("optionsss4_playlist", "purgecache_ss4_playlist", "Purger le cache", $URL_ROOT."/backoffice/adss/ss4_playlist/purgecache_ss4_playlist.php");
	$aMenu[] = new Menu("optionsss4_playlist", "importfromzip_ss4_playlist", "Importer une présentation (zip)", $URL_ROOT."/backoffice/adss/ss4_playlist/importfromzip_ss4_playlist.php");
	
	// gestion des users
	$aMenu[] = new Menu("ss4", "gestionss4_user", "Utilisateurs", "");
	if (isAllowed ($rankUser, "ADMIN;GEST")) $aMenu[] = new Menu("gestionss4_user", "bo_users", $translator->getTransByCode('Utilisateurs'), $URL_ROOT."/backoffice/cms/bo_users/list_bo_users.php");
	
	$aMenu[] = new Menu("gestionss4_user", "ss4_usergroup", $translator->getTransByCode('Groupes'), $URL_ROOT."/backoffice/adss/ss4_usergroup/list_ss4_usergroup.php");


	// gestion des slideshow
	$aMenu[] = new Menu("ss4", "gestionss4_slideshow", "Slideshows", "");
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_slideshow", "Liste des slideshows", $URL_ROOT."/backoffice/adss/ss4_slideshow/list_ss4_slideshow.php");
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_couleur", "Couleurs", $URL_ROOT."/backoffice/adss/ss4_couleur/list_ss4_couleur.php");	
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_graphcolset", "Sets de Couleurs (Graphes)", $URL_ROOT."/backoffice/adss/ss4_graphcolset/list_ss4_graphcolset.php");
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_picto", "Pictos", $URL_ROOT."/backoffice/adss/ss4_picto/list_ss4_picto.php");
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_mapfond", "Fonds de cartes", $URL_ROOT."/backoffice/adss/ss4_mapfond/list_ss4_mapfond.php");
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_mapcategorie", "Catégories de cartes", $URL_ROOT."/backoffice/adss/ss4_mapcategorie/list_ss4_mapcategorie.php");
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_font", "Polices", $URL_ROOT."/backoffice/adss/ss4_font/list_ss4_font.php");
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_style", "Styles", $URL_ROOT."/backoffice/adss/ss4_style/list_ss4_style.php");
	$aMenu[] = new Menu("gestionss4_slideshow", "ss4_piecejointe", "Pièces jointes", $URL_ROOT."/backoffice/adss/ss4_piecejointe/list_ss4_piecejointe.php");	
	
	if (isAllowed ($rankUser, "ADMIN")){
		$aMenu[] = new Menu("gestionss4_slideshow", "start_ss4_slideshow", "Démarrer un nouv. Slideshow", $URL_ROOT."/backoffice/adss/ss4_slideshow/start_ss4_slideshow.php");
		$aMenu[] = new Menu("gestionss4_slideshow", "addusers_ss4_slideshow", "Créer des utilisateurs", $URL_ROOT."/backoffice/adss/ss4_slideshow/addusers_ss4_slideshow.php");
	}
	
	// gestion des images
	$aMenu[] = new Menu("ss4", "gestionss4_banque", "Banque d'Images", "");
	$aMenu[] = new Menu("gestionss4_banque", "gestionss4_image", "Images", $URL_ROOT."/backoffice/adss/ss4_image/list_ss4_image.php");
	$aMenu[] = new Menu("gestionss4_banque", "gestionss4_imagefolder", "Dossiers d'images", $URL_ROOT."/backoffice/adss/ss4_imagefolder/list_ss4_imagefolder.php");

	// gestion des cartes
	if (isAllowed ("GEO", $sFonct)) {
		$aMenu[] = new Menu("ss4", "gestionss4_maps", "GoogleMaps", "");
		$aMenu[] = new Menu("gestionss4_maps", "ss4_cartes", "Liste des cartes", $URL_ROOT."/backoffice/cms/cms_geo_carte/list_cms_geo_carte.php");
		$aMenu[] = new Menu("gestionss4_maps", "ss4_points", "Liste des points g&eacute;olocalis&eacute;s", $URL_ROOT."/backoffice/cms/cms_geo_point/list_cms_geo_point.php");
		$aMenu[] = new Menu("gestionss4_maps", "ss4_pictos", "Liste des pictogrammes", $URL_ROOT."/backoffice/cms/cms_geo_pictogramme/list_cms_geo_pictogramme.php");
	}
	
	// gestion des traductions
	$aMenu[] = new Menu("ss4", "gestionss4_texte", "Traductions", "");
	$aMenu[] = new Menu("gestionss4_texte", "ss4_texte", "Liste des textes", $URL_ROOT."/backoffice/adss/ss4_texte/list_ss4_texte.php");
}
?>