<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']. PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'].'/include');

if(!isset($_SERVER['HTTP_HOST'])){	
	$_SERVER['HTTP_HOST']='cli';
	$sessionOptions = array('cookie_domain' => '', 'cookie_secure' => false, 'cookie_httponly' => true);
}
else{
	if (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off'){
		$sessionOptions = array('cookie_domain' => $_SERVER['HTTP_HOST'], 'cookie_secure' => true, 'cookie_httponly' => true);
	}
	else{
		$sessionOptions = array('cookie_domain' => $_SERVER['HTTP_HOST'], 'cookie_secure' => false, 'cookie_httponly' => true);
	}
}

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utility_define.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/config.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/lib/aodb/adodb.inc.php');	// gère la couche d'abstraction bdd
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/htmlUtility.php');

session_start($sessionOptions);

if (!isset($_SESSION['initiated'])){
    session_regenerate_id();
    $_SESSION['initiated'] = true;
	$_SESSION['confirmed'] = false;
}

//--  control des GET ----------------------------------------------------
if (preg_match('/\/backoffice\//msi', $_SERVER['PHP_SELF'])==1	&&	isset($_SESSION['BO']) 	&&	is_array($_SESSION['BO']) && isset($_SESSION['BO']['LOGGED'])){	
	// user loggé en BO, ok pas de filtrage
}
else{	
	if (isset($getVars) == true){	
		foreach ($_GET as $gKey => $gValue){
			if (!isset($getVars[$gKey])){ //pas autorisé
				$tempBlock = true; // bloque par defaut			
				//cas de GET['_'] ajax
				if ($gKey=='_'){
					// si timestamp dans une fourchette contemporaine => OK
					if (($gValue<(time()+3600))	&&	($gValue>(time()-3600))){
						$tempBlock = false;
						break;
					}
				}
				// tester les wildcards
				foreach ($getVars as $authKey => $authValue){
					$tempBlock = true;
					if (preg_match("/\*/msi", $authKey)==1){
						if (preg_match('/'.$authKey.'/msi', $gKey)==1){							
							if ($authValue == 'int'){ // integer strict
								$_GET[$gKey] = intval($_GET[$gKey]);
							}
							else{ //varchar
								$_GET[$gKey] = inputFilter($_GET[$gKey]);
							}
							$tempBlock = false;
							break;
						}
					}
				}
				if($tempBlock == true){
					unset($_GET[$gKey]);
					error_log('GET["'.$gKey.'"] has been blocked - '.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].' - '.$_SERVER['REMOTE_ADDR']);
				}
			}
			else{
				if ($getVars[$gKey] == 'int'){ // integer strict
					$_GET[$gKey] = intval($_GET[$gKey]);
				}
				else{ //varchar
					$_GET[$gKey] = inputFilter($_GET[$gKey]);
				}
			}
		}
	}
	else{ // pas de liste, on filtre tout en mode varchar
		foreach ($_GET as $gKey => $gValue){
			$_GET[$gKey] = inputFilter($_GET[$gKey]);
		}
	}
	
	// filtrage systématique des posts
	foreach ($_POST as $gKey => $gValue){
		if ($gKey=='id'){ // id forcés au type int
			$_POST[$gKey]=trim($_POST[$gKey]);
			if($_POST[$gKey]!=''){
				$_POST[$gKey] = intval($_POST[$gKey]);				
			}			
		}
		else{
			$_POST[$gKey] = inputFilter($_POST[$gKey]);
		}
	}
}			
//--  fin control des GET -------------------	----------------------	

ini_set('register_globals', false);
ini_set('session.bug_compat_warn', false);
ini_set('allow_call_time_pass_reference', true);
ini_set('allow_url_include', false);


$prevMask=umask(0022);
//error_log($prevMask);
//error_log(umask());

$localRAM = (int)intval(str_replace('M', '', ini_get('memory_limit')));

if (defined('DEF_MEMORY_LIMIT')){
	if ($localRAM < DEF_MEMORY_LIMIT){
		ini_set('memory_limit',DEF_MEMORY_LIMIT.'M'); 
	}
}
else{
	if ($localRAM < 128){
		ini_set('memory_limit','128M'); 
	}
}

if (!defined('DEF_MAX_EXEC_TIME')){
	define ('DEF_MAX_EXEC_TIME', 90); 
}

$local_Exe_time = (int)intval(ini_get('max_execution_time'));
if ($local_Exe_time < DEF_MAX_EXEC_TIME){
	ini_set('max_execution_time', DEF_MAX_EXEC_TIME); 
}



//-- control exec derriere un proxy
if (!isset($_SERVER['HTTP_X_FORWARDED_HOST']) || (trim($_SERVER['HTTP_X_FORWARDED_HOST'])=='')){ // pour compitibilité
	$_SERVER['HTTP_X_FORWARDED_HOST'] = $_SERVER['HTTP_HOST'];
}
elseif(trim($_SERVER['HTTP_X_FORWARDED_HOST'])!=trim($_SERVER['HTTP_HOST'])){ // derrière un proxy
	$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_X_FORWARDED_HOST'];
}

// en cas  $_SERVER['DOCUMENT_ROOT'] avec un trailing /
$_SERVER['DOCUMENT_ROOT']=preg_replace('/\/$/si', '', $_SERVER['DOCUMENT_ROOT']);

global $URL,$URL_ROOT,$CMS_ROOT, $NEWS_ROOT;

$tbMois =array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
$NEWS_DIR = $_SERVER['DOCUMENT_ROOT'].'/frontoffice/newsletter';

$URL_ROOT = ''; // config pour dev
//$CMS_ROOT = $_SERVER['DOCUMENT_ROOT'].'/content';
//$URL_ROOT = '';		     // config pour prod

$URL_ROOT = ''; // config pour dev
$CMS_ROOT = $_SERVER['DOCUMENT_ROOT'].'/'.DEF_PAGE_ROOT;
//$URL_ROOT = '';		     // config pour prod

// langage switch and storage
if (isset($_SESSION['id_langue'])){
	// nada
}
elseif (defined('DEF_APP_LANGUE') && (DEF_APP_LANGUE > 0)){
	$_SESSION['id_langue'] = DEF_APP_LANGUE;
}
else{
	$_SESSION['id_langue'] = 1;
	if (!defined('DEF_APP_LANGUE')){
		define('DEF_APP_LANGUE', 1);
	}
}




// X-UA-Compatible IE=EmulateIE7
//if (preg_match('/backoffice/msi', $_SERVER['PHP_SELF'])){
if (preg_match('/FCKeditor/msi', $_SERVER['PHP_SELF'])){
	header('X-UA-Compatible: IE=EmulateIE7');
	// pour pouvoir faire BACK / SUIVANT sans erreur ou message de post data...
	header('Cache-control: private');
}
elseif (preg_match('/ckeditor\/Filemanager-master/msi', $_SERVER['PHP_SELF'])){
	
	
}
elseif (preg_match('/downloadGenerator/msi', $_SERVER['PHP_SELF'])){
	// pas de header
}
else{
	//<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	header('X-UA-Compatible: IE=edge');	
	// pour pouvoir faire BACK / SUIVANT sans erreur ou message de post data...
	header('Cache-control: private');
}

//error_reporting(E_ALL);		// positionne le niveau de rapport d'erreur au maximum

//error_reporting :
// 3 cas :
if (preg_match('/pierre\..+\..+/', $_SERVER['HTTP_HOST'])==1){// - pierre dev
		//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    error_reporting(E_ALL);
	}
elseif (preg_match('/php7/', $_SERVER['HTTP_HOST'])==1){// - dev
		error_reporting(E_ALL);
	}
elseif (preg_match('/emulgator|zout|ccbr/', $_SERVER['HTTP_HOST'])==1){// - dev
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
	}
elseif (preg_match('/preprod/', $_SERVER['HTTP_HOST'])==1){// - dev
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING & ~E_DEPRECATED);
	}
else{// - prod
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING & ~E_DEPRECATED);
	}

$aCcIps = array('82.243.117.135', '82.228.89.184', '82.234.79.170');

if (!ini_get('display_errors')) {
	if (in_array($_SERVER['REMOTE_ADDR'], $aCcIps)){
   		ini_set('display_errors', 'On');
	}
	else{
		ini_set('display_errors', 'Off');
	}
}


/*include_once('error.lib.php');*/

$_SESSION['dbname'] = DEF_BDD_DBNAME;

global $db;
$db = ADONewConnection(DEF_DRIVER); 
$db->debug = DEF_BDD_DEBUG;
$db->connectSID = true;
$resCon = $db->Connect(DEF_BDD_SERVER, DEF_BDD_USER, DEF_BDD_PWD, DEF_BDD_DBNAME);
if ($resCon==false){
	echo '<p>database connection has failed, please alert the site admin: <a href="mailto:'.DEF_USERMAIL.'">'.DEF_USERMAIL.'</a></p>';
	if($db->_errorMsg!=''){
		echo '<!-- <p>'.$db->_errorMsg.'</p> -->';
	}
}
else{
	if (defined("DEF_BDD_CHARSET")){
		$db->SetCharSet(DEF_BDD_CHARSET);
	}
	else{
		$db->SetCharSet('latin1');
	}
}

// redir auto de homepage
include_once('homepage.inc.php');

// remplacement des quotes office par des quotes propres
// --- par extension on va traiter ausis les chars bizarres
// --- non-suporté par spaw : oe, ae, dash, etc...
// le petit dash devient un tiret normal, et non &#8211;
// le grand dash devient un tiret normal, et non &#8212;
replaceBadCar();

// on ne va pas inclure les mêmes fichiers prepend et append selon les sections du site...
$sections = array(
     '/content/' => array(
				'prepend' => $_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/fo_prepend.php',
				 'append' => $_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/fo_append.php'
			 ),
     '/frontoffice/' => array(
				'prepend' => $_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/fo_prepend.php',
				 'append' => $_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/fo_append.php'
			 ),
	'/modules/menu' => array(
				'prepend' => $_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/fo_prepend.php',
				 'append' => $_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/fo_append.php'
			 ),
	'/modules/' => array(
				'prepend' => '',
				 'append' => ''
			 ),
	'/backoffice/' => array(
				'prepend' => $_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/prepend.php',
				 'append' => $_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/append.php'
			 ),
	'/backoffice/cms/lib/' => array(
				'prepend' => '',
				 'append' => ''
			 ),	
	'/lib/' => array(
				'prepend' => '',
				 'append' => ''
			 ),
	'/tmp/' => array(
				'prepend' => '',
				 'append' => ''
			 ),
	    );
// liste des urls où il ne faut pas mettre de append/prepend
$exclude_list = array(
	'/modules/chat/',
	'/modules/forum/',
	'/backoffice/cms/init.php',
	'/backoffice/cms/arbo_check.php',
	'/backoffice/cms/call_maj_fkey.php',
	'/backoffice/cms/call_list_fkey.php',
	'/backoffice/cms/call_show_association.php',
	'/backoffice/cms/call_maj_association.php',
	'/backoffice/cms/call_list.filters.id.php',
	'/backoffice/cms/page_infos_newasso.php',
	'/backoffice/cms/dupliOffsets.php',
	'/backoffice/cms/site/chooseFolder.php',
	'/backoffice/cms/chooseFolder.php',
	'/backoffice/cms/choosePage.php',	
	'/backoffice/cms/choosePageFolder.php',
	'/backoffice/cms/survey/surveyList.php',
	'/backoffice/cms/previewComposant.php',
	'/backoffice/cms/site/previewGabarit.php',
	'/backoffice/cms/getComposant.php',
	'/backoffice/cms/previewPage.php',
	'/backoffice/cms/previewPageTravail.php',	
	'/backoffice/cms/gabarits',
	'/backoffice/cms/graphiques/charts.php',
	'/backoffice/cms/graphiques/charts_arg.php',
	'/backoffice/cms/graphiques/charts_arg_preview.php',
	'/backoffice/cms/viewMedia.php',	
	'/backoffice/cms/dialMedia.php',
	'/backoffice/cms/js/glossary.js.php',	
	'/backoffice/cms/shp_commande/print_shp_commande.php',
	'/backoffice/cms/formulaire/popup/text.php',
	'/backoffice/cms/formulaire/popup/checkbox.php',
	'/backoffice/cms/formulaire/popup/combobox.php',
	'/backoffice/cms/formulaire/popup/html.php',
	'/backoffice/cms/formulaire/popup/radio.php',
	'/backoffice/cms/formulaire/popup/textarea.php',
	'/backoffice/cms/formulaire/popup/text2.php',
	'/backoffice/cms/formulaire/popup/checkbox2.php',
	'/backoffice/cms/formulaire/popup/checkboxdest.php',
	'/backoffice/cms/formulaire/popup/combobox2.php',
	'/backoffice/cms/formulaire/popup/html2.php',
	'/backoffice/cms/formulaire/popup/radio2.php',
	'/backoffice/cms/formulaire/popup/textarea2.php',
	'/backoffice/cms/formulaire/popup/lienabo.php',
	'/backoffice/cms/formulaire/popup/email.php',
	'/backoffice/cms/formulaire/popup/upload.php',
	'/backoffice/cms/formulaire/popup/from2.php',
	'/backoffice/cms/formulaire/popup/update_field.php',
	'/backoffice/cms/formulaire/maj_select.php', 
	'/backoffice/cms/formulaire/captcha.php', 
	'/backoffice/cms/formvierge.php',
	'/backoffice/cms/lib/FCKeditor/editor/filemanager/upload/php/upload.php',
	'/backoffice/cms/site/choosePageFolder.php',
	'/backoffice/adss/slideshow/css.php',
	'/backoffice/adss/slideshow/hub.php',
	'/backoffice/adss/slideshow/skin.php',
	'/backoffice/adss/slideshow/intro.php',
	'/backoffice/adss/slideshow/viewer.php',
	'/backoffice/adss/slideshow/download.php',
	'/backoffice/adss/slideshow/downloadGenerator.php',
	'/backoffice/adss/slideshow/playlistGenerator.php',
	'/backoffice/adss/slideshow3/hub.php',
	'/backoffice/adss/slideshow3/viewer.php',
	'/backoffice/adss/slideshow3/download.php',
	'/backoffice/adss/slideshow3/downloadGenerator.php',
	'/backoffice/adss/slideshow3/download/downloadGenerator.php',
	'/backoffice/adss/slideshow3/playlistGenerator.php',
	'/backoffice/adss/ss3_slideshow3/offsets_ss3_slideshow3.php',
	'/backoffice/adss/slideshow4/hub.php',
	'/backoffice/adss/slideshow4/dl.php',
	'/backoffice/adss/slideshow4/viewer.php',
	'/backoffice/adss/slideshow4/mobile/slideshow4.plist.php',
	'/backoffice/adss/slideshow4/download.php',
	'/backoffice/adss/slideshow4/downloadGenerator.php',
	'/backoffice/adss/slideshow4/download/downloadGenerator.php',	
	'/backoffice/adss/slideshow4/download/downloadDocument.php',
	'/backoffice/adss/slideshow4/download/downloadGeneratorNew.php',
	'/backoffice/adss/slideshow4/playlistGenerator.php',
	'/backoffice/adss/ss4_slideshow/offsets_ss4_slideshow.php',	
	'/backoffice/adss/slideshow5/hub.php',
	'/backoffice/adss/slideshow5/dl.php',
	'/backoffice/adss/slideshow5/viewer.php',
	'/backoffice/adss/slideshow5/mobile/slideshow4.plist.php',
	'/backoffice/adss/slideshow5/download.php',
	'/backoffice/adss/slideshow5/downloadGenerator.php',
	'/backoffice/adss/slideshow5/download/downloadGenerator.php',	
	'/backoffice/adss/slideshow5/download/downloadDocument.php',
	'/backoffice/adss/slideshow5/download/downloadGeneratorNew.php',
	'/backoffice/adss/slideshow5/playlistGenerator.php',
	'/backoffice/cms/utils/popup',	
	'/backoffice/cms/utils/arbo.php',
	'/backoffice/cms/utils/popup_gmaps.php',
	'/backoffice/cms/utils/popup_gpaths.php',
	'/backoffice/cms/utils/popup_objectset.php',
	'/backoffice/cms/popup_arbo_browse.php',
	'/backoffice/cms/popup_arbo_browse_edits.php',
	'/backoffice/cms/popup_arbo_browse_node.php',
	'/backoffice/cms/contentLiteEditor.php',
	'/backoffice/avis/showAvis.php', 	
	'/backoffice/cms/utils/telecharger.php',
	'/backoffice/cms/utils/passthru.php',
	'/backoffice/cms/utils/mpeg.php',
	'/backoffice/cms/utils/viewer.php',
	'/backoffice/cms/utils/flvprovider.php',
	'/backoffice/cms/utils/flvmetadata.php',
	'/backoffice/cms/utils/swfmetadata.php',
	'/backoffice/cms/utils/streamvideo.php',
	'/backoffice/cms/utils/awsVideo.php',
	'/backoffice/cms/utils/streamvideoLarge.php',
	'/backoffice/cms/utils/popup_drag.php',
	'/backoffice/cms/utils/spy.php',
	'/backoffice/cms/utils/img.php',
	'/backoffice/cms/utils/get_wysiwyg_css.php',
	'/backoffice/cms/utils/get_wysiwyg_stylexml.php',
	'/backoffice/cms/utils/sitemap.xsl.php',
	'/backoffice/petites_annonces/detailannonce.php',
	'/backoffice/petites_annonces/detailgarde.php',
	'/backoffice/horaires/popup_calendrier.php',
	'/backoffice/cms/utils/popup_wysiwyg.php',
	'/backoffice/newsletter/news_show_popup.php',
	'/backoffice/horaires/popup_calendrier_simple.php',
	'/backoffice/newsletter/popup_actu.php',
	'/backoffice/newsletter/modele.php',
	'/backoffice/newsletter/sendmail_list.php',
	'/backoffice/newsletter/auto_envoi.php',
	'/backoffice/reference/pop_reference.php',
	'/backoffice/cms/filemanager/admin/ZUpload/js.inc.php',
	'/backoffice/cms/filemanager/frontoffice/serve.php',
	'/backoffice/cms/filemanager/frontoffice/secure.inc.php',
	'/backoffice/cms/filemanager/frontoffice/ls.php',
	'/backoffice/cms/filemanager/frontoffice/ls.inc.php',
	'/backoffice/newsletter/newsletter_modele.php',
	'/backoffice/newsperso/telecharger_newsperso.php',
	'/backoffice/cms/classeur/maj_select.php',
	'/backoffice/cms/classeur/getfile.php',
	'/backoffice/candidature/exportpdf_candidature.php',
	'/backoffice/event/mailinvitationcustom.php',
	'/backoffice/job_candidature/exportpdf_job_candidature.php' ,
	'/backoffice/commande/mailclient_commande.php',
	'/backoffice/commande/mail_commande.php',
	'/backoffice/commande/facture.html.php',
	'/backoffice/commande/save_facture.php', 
	'/backoffice/commande/save_commande.php', 
	'/backoffice/cms/cms_rss_url/check_scan_cms_rss_url.php',  
	'/backoffice/customer/importit_customer.php',  
	'/backoffice/member/importit_member.php',
	'/backoffice/purchaseorder/importquantum_purchaseorder.php', 
	'/backoffice/purchaseorder/importmovex_purchaseorder.php', 
	'/backoffice/repairorder/importquantum_repairorder.php', 
	'/backoffice/repairorder/importmovex_repairorder.php', 	
	'/backoffice/cms/lib/ckeditor/Filemanager-master/index.php' 
);

// liste des urls où il ne faut pas mettre de append/prepend
$exclude_patterns = array(
	'/xml_',
	'/xmlxls_',
	'/xlsx_',
	'/syncout_',
	'/rss_',
	'/export[^_]*_',
	'.js.php',
	'/scoopit_',
	'/flush_',
	'/sonde_',
	'/feed_',
	'/lib/',
	'/js/'
);

$excludePattern = '/'.str_replace(array('/', '.'), array('\/', '\.'), implode('|', $exclude_patterns)).'/si';

$include_prepend = '';
$include_append = '';
$URL = str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
$url_section = str_replace('/www','',$URL);

if (strpos($url_section, '/')!=0){
	$url_section = '/'.$url_section;
}

if (!in_array($url_section,$exclude_list) && (!preg_match($excludePattern, $url_section))	)  {	
	if (preg_match('/\.php/',$url_section)) {
		$file = strrchr($url_section,'/');
		$url_section=str_replace($file,'',$url_section);
	}
		
	if (!in_array($url_section,$exclude_list)){
		$len = 0;
		$key = '';
		foreach($sections as $k => $v) {
			$url_reg = $url_section.'/';
			//$url_reg = str_replace('/','\/',$url_reg);
			$k_reg = str_replace('/','\/',$k);
			
			if ((preg_match('/^'.$k_reg.'/',$url_reg)) and (strlen($k) > $len)) {
				$key = $k;
				break;
			}
		}
		if ((bool)($key) != false){
			$include_prepend=trim($sections[$key]['prepend']);
			$include_append=trim($sections[$key]['append']);
			if ($include_prepend!=''){
				include_once($include_prepend);
			}
			
			if ($key == '/backoffice/') {
				$script = explode('/',$_SERVER['PHP_SELF']);
				$script = $script[newSizeOf($script)-1];
				$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));

				// Inclusion prepend global sur la classe
				// prepend.ma_classe.php
				if (is_file($_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.$classeName.'.php'))
					require_once('include/bo/cms/prepend.'.$classeName.'.php');

				// Inclusion prepend sur une vue de la classe
				// prepend.vue_ma_classe.php
				if (is_file($_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.$script))
					require_once('include/bo/cms/prepend.'.$script);
			}
		}
	}

}