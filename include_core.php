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





// cron

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_cron.class.php');

//objet Page
if(PHP_VERSION >= 5)
    include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/pageobject.lib.php');
else
    include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/pageobject.php4.lib.php');
?>