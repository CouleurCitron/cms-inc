<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// sponthus 03/08/2005
// include de toutes les fonctions utilitaires du CMS

// sql persistant
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/sql_persistant.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/sql_persistant2.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/sql_persistant_date.php');

 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/dbChamp.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/htmlUtility.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/htmlUtility.utf8.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/preview.lib.php');

// associations
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/sql_asso.lib.php');

// envoi mail
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/send.lib.php');

// upload
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/upload.php');

// XML
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/xml.parser.inc.php');

// eval de code mixed html php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/phpsrcEval.php');

// calendrier
//include_once('bo/horaires/calendrier.lib.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/graphic.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/dir.lib.php');

// class
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoclass.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/classe.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_rss.class.php');

// utilisateurs
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/bo_users.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/bo_groupes.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/bo_rank.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_statut_content.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assobo_userscms_statut_content.class.php');

// menu
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_sectionbo.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assosectiongroupe.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_menu.class.php');

// site
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_site.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_jquery_version.class.php');
// Traductions
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_langue.class.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assolanguesite.class.php'); 

if (!isset($_SESSION['cms_langues'])){
	$aLg = dbGetObjectsFromFieldValue3('cms_langue', array('get_statut'), array('equals'), array(DEF_ID_STATUT_LIGNE), NULL, NULL);
	$aLgUsed = array ();
	if ((count($aLg) > 0)&&($aLg!=false)){
		foreach($aLg as $oLg){
			$aLgUsed[strtolower($oLg->get_libellecourt())]=$oLg->get_id();
		}
	}
	$_SESSION['cms_langues'] = $aLgUsed;
}

if (!isset($_SESSION['idSite'])||($_SESSION['idSite']==-1)){ // si change of site
	$oSite = detectSite();
	sitePropsToSession($oSite);
	$_SESSION['id_langue']=$_SESSION['cms_langues'][$_SESSION['site_langue']];
}

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_chaine_reference.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_chaine_traduite.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/TslManager.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_texte.class.php');

 

// cms
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_page.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assoclassepage.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_content.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_moderation.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_struct_page.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_archi_content.class.php'); // sauvegardes des content en ligne et archivés
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_infos_pages.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_arbo_pages.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_theme.class.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_affich_content.class.php'); // objet d'affichage de contenu
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_affich_droitpage.class.php'); // objet d'affichage des droits des pages
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_affich_droitcontenu.class.php'); // objet d'affichage d'un contenu et de son droit

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_classarbo.class.php'); // objet d'affichage d'un contenu et de son droit

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_prepend.class.php'); // objet prepend script custom
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assoprependarbopages.class.php'); // objet asso prepend script custom // arbo node
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assoprependcmssite.class.php'); // objet asso prepend script custom // cms site

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_js.class.php'); // objet js script/css custom
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_media.class.php'); // objet media (device)
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assojsarbopages.class.php'); // objet asso js script custom // arbo node
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assojscmssite.class.php'); // objet asso js script custom // cms site

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assoclassenode.class.php'); // Lien objet et arbo
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_cacheclassenode.class.php'); // Lien objet et arbo

//tableau
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_tableau.class.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_tableau_perempt.class.php'); 

// classe génerique

// tri
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/bean/tri.class.php');
// recherche
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/bean/recherche.class.php');
// pagination
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/bean/pagination.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/bean/pagination_array.class.php');

// workflow
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_droit.class.php');

// avis
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_avis.class.php');

// formulaire
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_form.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_champform.class.php');

// zip
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/zip.lib.php');

// flv
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/flv.inc.php');

// flash
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/htmlentities4flash.lib.php');

// chaines et chars
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/chars.lib.php');

//tag
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_tag.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assotagclasse.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_title.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assotitleclasse.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_description.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assodescriptionclasse.class.php');

//classeurs + diaporamas
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_binder.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_pdf.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assobinderpdf.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_diaporama.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_diapo.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assodiapodiaporama.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_diaporama_type.class.php');

//news
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_news.class.php');

//gmaps
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_mapskey.class.php');

//charts
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_chartskey.class.php');

// rss generique
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_rss_url.class.php');
 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/price_format.lib.php');


// pays EN et FR
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_pays.class.php');

// glossaire
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_glossary.class.php');

// sonde
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_sonde.class.php');

//video
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_video.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assovideopage.class.php');

// Logs statuts
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_log_statut.class.php');


// Admin Logs
// Added by Luc - 28 oct. 2009
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/log.lib.php');

//enquete
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_enquete.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_question.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_assoenquetequestion.class.php');

//unset ($_SESSION['cms_classes']);
if (!isset($_SESSION['cms_classes'])){
	$aClasse = dbGetObjectsFromFieldValue3('classe', array('get_statut', 'get_iscms'), array('equals', 'equals'), array(DEF_ID_STATUT_LIGNE, 1), NULL, NULL);
	$aClasseUsed = array ();
	if ((count($aClasse) > 0)&&($aClasse!=false)){		
		foreach($aClasse as $oClasse){
			$aClasseUsed[$oClasse->get_id()]=$oClasse->get_nom();
		}
	}
	$_SESSION['cms_classes'] = $aClasseUsed;
}

if(isset($_SESSION['fonct'])){
	// fm - file manager
	if (preg_match('/CMS|FM|BLOG/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_filemanager.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_filemanageruser.class.php');
	}
	
	// MINISITE
	//if (preg_match('/MINISITE/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_minisite.class.php');
	//}
	
	// SS3
	if (preg_match('/SS3/',$_SESSION['fonct'])==1){
		include_once('backoffice/adss/slideshow3/includes/aws-slideshow.functions.php');
	}
	
	// SS4
	if (preg_match('/SS4/',$_SESSION['fonct'])){
		if (preg_match('/\/adss\//', $_SERVER['PHP_SELF'])	||	preg_match('/\/content\//', $_SERVER['PHP_SELF'])		||	preg_match('/^\/index\.php/', $_SERVER['PHP_SELF'])	){
			include_once($_SERVER['DOCUMENT_ROOT'].'/backoffice/adss/slideshow4/includes/aws-slideshow.functions.php');
		}
		else{
			include_once($_SERVER['DOCUMENT_ROOT'].'/backoffice/adss/slideshow4/includes/aws-slideshow.classes.php');
		}
	}
	
	// SS5
	if (preg_match('/SS5/',$_SESSION['fonct'])){
		if (preg_match('/\/adss\//', $_SERVER['PHP_SELF'])	||	preg_match('/\/content\//', $_SERVER['PHP_SELF'])		||	preg_match('/^\/index\.php/', $_SERVER['PHP_SELF'])	){
			include_once($_SERVER['DOCUMENT_ROOT'].'/backoffice/adss/slideshow5/includes/aws-slideshow.functions.php');
		}
		else{
			include_once($_SERVER['DOCUMENT_ROOT'].'/backoffice/adss/slideshow5/includes/aws-slideshow.classes.php');
		}
	}


	// NEWSLETTER
	if (preg_match('/NEWSLETTER/',$_SESSION['fonct'])==1){
		if (in_array("newsletter", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/newsletter.class.php');
		if (in_array("news_ar", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_ar.class.php');
		if (in_array("news_assoinscrittheme", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_assoinscrittheme.class.php');
		if (in_array("news_envoi", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_envoi.class.php');
		if (in_array("news_expediteur", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_expediteur.class.php');
		if (in_array("news_inscrit", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_inscrit.class.php');
		if (in_array("news_select", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_select.class.php');
		if (in_array("news_theme", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_theme.class.php');	
		if (in_array("news_assonewspdf", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_assonewspdf.class.php');
		if (in_array("news_assonewscron", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_assonewscron.class.php');
		if (in_array("news_queue", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_queue.class.php');
 	}
	else {
		if (in_array("newsletter", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/newsletter.class.php');
		if (in_array("news_ar", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_ar.class.php');
		if (in_array("news_assoinscrittheme", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_assoinscrittheme.class.php');
		if (in_array("news_envoi", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_envoi.class.php');
		if (in_array("news_expediteur", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_expediteur.class.php');
		if (in_array("news_inscrit", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_inscrit.class.php');
		if (in_array("news_select", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_select.class.php');
		if (in_array("news_theme", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_theme.class.php');	
		if (in_array("news_assonewspdf", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_assonewspdf.class.php');
		if (in_array("news_assonewscron", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_assonewscron.class.php');
		if (in_array("news_queue", $_SESSION['cms_classes'])) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/news_queue.class.php');
	} 

	// news
	if (preg_match('/NEWS/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/nws_content.class.php');
	}

	// geomap
	if (preg_match('/GEO/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/geomap.lib.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_geo_carte.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_geo_pictogramme.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_geo_point.class.php');
	}

	//survey
	if (preg_match('/SONDAGE/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_survey_ask.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_survey_answer.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_survey_reponse.class.php');
	}

	//Petites annonces
	if (preg_match('/PA/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/pa_annonce.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/pa_inscrit.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/pa_quartier.class.php');
	}

	// comptes
	if (preg_match('/ACCOUNT/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_client.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_adresse.class.php');
	}

	// boutique (mod comptes inclus)
	if (preg_match('/SHOP/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_produit.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_produit_type.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_asso_gammes.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_asso_produits.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_gamme.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_unite.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_tarif.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_transporteur.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_frais_port_grille.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_frais_port_valeur.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_commande.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_commande_statut.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_client.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_adresse.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_asso_produitgamme.class.php');
		
		//TPI
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/webshop/generator/systempay_APIv1.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/webshop/generator/e_transactions_APIv6.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/webshop/generator/PayPal.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/webshop/generator/webaffaires_API.php');
	}
	// catalog, version light de shop
	if (preg_match('/CATALOG/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_produit.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_produit_type.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_asso_gammes.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_asso_produits.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_gamme.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_unite.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_tarif.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_asso_produitgamme.class.php');
	}
	
	
	if (preg_match('/JOB/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_offre.class.php'); 
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_candidat.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_metier.class.php'); 
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_candidature.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_destinataire.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_assooffredestinataire.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_rubrique.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_contrat.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_domaine.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_lieu.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_lettre.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_reponse.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_experience.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_qualification.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_assooffrepdf.class.php');
		
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_langue.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_niveaulangue.class.php');
		
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_assocandidatdomaine.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_assocandidatlangue.class.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_assocandidatqualification.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_assocandidatmetier.class.php');	
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/job_assocandidatlieu.class.php');	
	}

	// share it / Envoi à un ami
	if (preg_match('/SHAREIT/',$_SESSION['fonct'])==1){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shr_track.class.php');
	}

} // fin if(isset($_SESSION['fonct'])){


// cron

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_cron.class.php');

//objet Page
if(PHP_VERSION >= 5)
    include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/pageobject.lib.php');
else
    include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/pageobject.php4.lib.php');
?>