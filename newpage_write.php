<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/htmlUtility.php');

/*
sponthus 08/06/2005

Mkl 07/06/2005
Correction Bug : Attention les classes (et id) des divs doivent commencer par une lettre
pour pouvoir appliquer un style à ces div (sinon bug de positionnement).


contenu (tampon) qui sert Ã  crÃ©er d'autres pages HTML

*/

function getPageDependencies($oPage){
	$oNode = new cms_arbo_pages($oPage->getNodeid_page());		
	// ici, on va ecrire les eventuels js custom pour le node
	$aResJSarbo = dbGetAssocies($oNode, 'cms_assojsarbopages', false, false);	
	// ici, on va ecrire les eventuels js custom pour le site
	$oSite = new cms_site();
	$oSite->set_id($_SESSION['idSite']);		
	$aResJSsite = dbGetAssocies($oSite , 'cms_assojscmssite', false, false);	
	// on merge		
	$aResJS['list'] = array_merge($aResJSarbo['list'], $aResJSsite['list']);	
	
	/// dédoublonner ($aResJS['list']);			
	$aResJSunique = array();
	$aResMediaByPath = array();	
	
	foreach($aResJS['list'] as $kJs => $aJs){	
		$aJs['abstract'] = str_replace('&amp;', '&', $aJs['abstract']);	
		$aJspath = explode(';', $aJs['abstract']);

		$oDep = cacheObject('cms_js', $aJs['ref_id']);
		if((int)$oDep->get_media()>0){
			$oMedia = cacheObject('cms_media', $oDep->get_media());
			$sMedia = $oMedia->get_libelle();
		}
		else{
			$sMedia = '';
		}
		if (trim($oDep->get_mediacomp())!=''){
			$sMedia .= ', '.$oDep->get_mediacomp();
		}		
		
		foreach($aJspath as $kJsP => $sJspath){		
			if (!in_array($sJspath, $aResJSunique)){
				$aResJSunique[]=$sJspath;
				$aResMediaByPath[$sJspath] = $sMedia;
			}
		}
	}	
	return array($aResJSunique, $aResMediaByPath);
}

function getPageHeader($oInfos_page=NULL, $oPage=NULL) {

	global $translator; 
	if (!isset($translator)){ $translator =& TslManager::getInstance(); }
	
	if($oInfos_page) {
		$titre=utf8IfNeeded(stripTitre($oInfos_page->getPage_titre()));
		$motsclefs=utf8IfNeeded($oInfos_page->getPage_motsclefs());
		$description=utf8IfNeeded($oInfos_page->getPage_description());
		$thumb=utf8IfNeeded($oInfos_page->getPage_thumb());
	}
	
	$sHeader = '<'.'?php'."\n";
	$sHeader .= '	include_once($_SERVER[\'DOCUMENT_ROOT\'].\'/include/autoprepend.php\');'."\n";
	$sHeader .= '	header(\'Content-Type: text/html; charset='.$_SESSION['encod'].'\');'."\n";
	$sHeader .= '?'.">\n";

	$sHeader .= fullDoctype($_SESSION['doctype'], $_SESSION['offline'], $_SESSION['site_langue'])."\n";

	$sHeader .= '	<head>'."\n";
	$sHeader .= '	<meta name="Copyright" content="'.$_SESSION['copyright'].'" />'."\n";
	$sHeader .= '	<meta name="Generator" content="Adequat\'WEBSITE - Couleur Citron CMS" />'."\n";
	$sHeader .= '	<meta name="Author" content="'.$_SESSION['author'].'" />'."\n";
	$sHeader .= '	<meta name="KEYWORDS" content="'.stripslashes($motsclefs).'" />'."\n";
	$sHeader .= '	<meta name="DESCRIPTION" content="'.stripslashes($description).'" />'."\n";
	$sHeader .= '	<meta http-equiv="Content-Type" content="text/html; charset='.$_SESSION['encod'].'" />'."\n";
	$sHeader .= '	<meta http-equiv="Content-Language" content="'.$_SESSION['site_langue'].'" />'."\n";
	$sHeader .= '	<meta name="Robots" content="'.$_SESSION['robots'].'" />'."\n";
	$sHeader .= '	<meta name="geo.region" content="'.$_SESSION['georegion'].'" />'."\n";
	$sHeader .= '	<meta name="geo.placename" content="'.$_SESSION['geoplacename'].'" />'."\n";
	$sHeader .= '	<meta name="geo.position" content="'.$_SESSION['geoposition'].'" />'."\n";
	$sHeader .= '	<meta name="ICBM" content="'.str_replace(';', ', ', $_SESSION['geoposition']).'" />'."\n";
	$sHeader .= '	<meta name="format-detection" content="telephone=no">'."\n";
        
        if(file_exists( $_SERVER[ 'DOCUMENT_ROOT' ] . '/favicon.ico' ) ) $sHeader .= '	<link rel="icon" href="/favicon.ico" />'."\n";
        else if( file_exists( $_SERVER[ 'DOCUMENT_ROOT' ] . '/favicon.png' ) ) $sHeader .= '	<link rel="icon" type="image/png" href="/favicon.png" />'."\n";

    if(intval($_SESSION['offline']) == 1) {
		 $sHeader .= '<meta name="apple-mobile-web-app-capable" content="yes">';
	}
	
	if (intval($_SESSION['https'])==1){
		$protocol = 'https';
	}
	else{
		$protocol = 'http';
	}

		
	if($oPage && ($oPage->getNodeid_page()==0) && ($oPage->getName_page()=='index')){
		
		$sHeader .= '	<meta name="Identifier-url" content="'.$protocol.'://'.$_SESSION['site_host'].'/" />'."\n";
	} 
        if($_SESSION['viewport'] != ""){
            $sHeader .= '	<meta name="viewport" content="'.$_SESSION['viewport'].'" />'."\n";
        } else {
            $sHeader .= '	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />'."\n";
        }
	$sHeader .= '	<meta http-equiv="Content-Style-Type" content="text/css" />'."\n";
	$sHeader .= '	<meta http-equiv="Content-Script-Type" content="text/javascript" />'."\n";
	
	if (!$_SESSION['mobile']){ // http://msdn.microsoft.com/en-us/library/ie/jj193557%28v=vs.85%29.aspx
	//	$sHeader .= '	<!--[if IE 10]><meta http-equiv="X-UA-Compatible" content="requiresActiveX=true" /><![endif]-->'."\n";	 // SID IE10
	}
	$sHeader .= '	<title>'.stripslashes($titre).'</title>'."\n";
	
	$sHeader .= '	<meta property="og:title" content="'.stripslashes($titre).'" />'."\n";
	$sHeader .= '	<meta property="og:type" content="article" />'."\n";	
	$sHeader .= '	<meta property="og:description" content="'.stripslashes($description).'" />'."\n";
	$sHeader .= '	<meta property="og:site_name" content="'.$_SESSION['site_host'].'" />'."\n";
	
	if ((is_file($_SERVER['DOCUMENT_ROOT'].$thumb)) || (preg_match('/<\?php/msi', $thumb)==1)){
		$sHeader .= '	<meta property="og:image" content="'.$protocol.'://'.$_SESSION['site_host'].$thumb.'" />'."\n";
		$sHeader .= '	<link rel="image_src" href="'.$thumb.'" />'."\n";
                $sHeader .= '   <link rel="apple-touch-icon" href="'.$thumb.'" />'."\n";
	}
 
        
        $sSqlJquery = "SELECT v.* FROM cms_site s, cms_jquery_version v WHERE s.cms_id='".$_SESSION['idSite']."' AND v.cms_id = s.cms_jquery_version AND v.cms_statut = '".DEF_ID_STATUT_LIGNE."'";
	$aJquery = dbGetObjectsFromRequete("cms_jquery_version", $sSqlJquery);
        
        if(count($aJquery)){
            $sHeader .= '	<script src="/backoffice/cms/js/jquery/'.$aJquery[0]->get_filename().'" type="text/javascript"></script>'."\n";
        } else {
            $sHeader .= '	<script src="/backoffice/cms/js/jquery-1.6.4.min.js" type="text/javascript"></script>'."\n";
        }
        

	if($aJquery[0]->get_filename() == 'jquery-1.6.4.min.js'){
            if ($_SESSION['mobile']){
                    if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/js/mobile/jquery.mobile-1.2.0.min.js')){
                            $sHeader .= '	<link rel="stylesheet" href="/backoffice/cms/css/mobile/jquery.mobile.structure-1.2.0.min.css" /> '."\n";
                            $sHeader .= '	<script src="/backoffice/cms/js/mobile/jquery.mobile-1.2.0.min.js"></script>'."\n";
                    }
                    elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/js/mobile/jquery.mobile-1.0.1.min.js')){
                            $sHeader .= '	<link rel="stylesheet" href="/backoffice/cms/css/mobile/jquery.mobile.structure-1.0.1.min.css" /> '."\n";
                            $sHeader .= '	<script src="/backoffice/cms/js/mobile/jquery.mobile-1.0.1.min.js"></script>'."\n";
                    } 
            }

            // fancybox
            if (!$_SESSION['mobile']){
                    if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js')){
                            $sHeader .= '	<link href="/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet" type="text/css" />'."\n";
                            $sHeader .= '	<script src="/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>'."\n";			
                    }
                    elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.pack.js')){
                            $sHeader .= '	<link href="/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.css" media="screen" rel="stylesheet" type="text/css" />'."\n";
                            $sHeader .= '	<script src="/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.pack.js" type="text/javascript"></script>'."\n";			
                    }
                    elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.pack.js')){
                            $sHeader .= '	<link href="/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.css" media="screen" rel="stylesheet" type="text/css" />'."\n";
                            $sHeader .= '	<script src="/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.pack.js" type="text/javascript"></script>'."\n";
                    }
                    else{
                            // ben, rien.
                    }
            }
        }
	
	// inclus un fichier custom pour le header s'il existe
	// utilisé pour les liens canonical
	if (is_file($_SERVER['DOCUMENT_ROOT']."/include/modules/".$_SESSION['rep_travail']."/meta/meta.inc.php")){
		 
		//include ($_SERVER['DOCUMENT_ROOT']."/include/modules/".$_SESSION['rep_travail']."/meta/meta.inc.php");  
		$sHeader .= '<'.'?php'."\n";
		$sHeader .= '	include($_SERVER[\'DOCUMENT_ROOT\'].\'/include/modules/'.$_SESSION['rep_travail'].'/meta/meta.inc.php\');'."\n"; 
		$sHeader .= '?'.">\n";
		
	}
	else { 
	

	}


	
	$sHeader .= '	<script src="/backoffice/cms/js/fojsutils.js" type="text/javascript"></script>'."\n";
	$sHeader .= '	<script type="text/javascript" src="/backoffice/cms/js/XHRConnector.js"></script>'."\n";
	$sHeader .= '	<script type="text/javascript" src="/backoffice/cms/js/ancre.js.php"></script>'."\n";
	
	// flash
	if (defined('DEF_FLASHPLAYERREQUIRED')	&&	((int)DEF_FLASHPLAYERREQUIRED>0)	&&	!$_SESSION['mobile']){
		$sHeader .= '	<script type="text/javascript" src="/backoffice/cms/js/flashDetection.js.php"></script>'."\n";
		$sHeader .= '	<script type="text/vbscript" src="/backoffice/cms/js/flashDetection.vbs.php"></script>'."\n";
		$sHeader .= '	<script type="text/javascript" src="/backoffice/cms/js/flashDetection.php"></script>'."\n";
		$sHeader .= '	<script type="text/javascript" src="/backoffice/cms/js/AC_RunActiveContent.js"></script>'."\n";  
	}
	
	// cookie banner
	// wiki.adequation.cc/index.php?title=Cookie_opt%27in
	
	
	if ( defined("DEF_JS_COOKIESBAN_USE") && DEF_JS_COOKIESBAN_USE ) {
	
		if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/js/cookiebanner.min.js') || is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/js/cookiebanner.min.js.php')){
		
			if (defined("DEF_JS_COOKIESBAN_OPTIONS_".$_SESSION['idSite']."")) {
				eval ( "$"."options_minisite = "."DEF_JS_COOKIESBAN_OPTIONS_".$_SESSION['idSite']." ; ");
			}
			else {
				$options_minisite = "";
			}
			 
			  
			if ( defined("DEF_JS_COOKIESBAN_MINISITE_USE") && DEF_JS_COOKIESBAN_MINISITE_USE  && $options_minisite == '' ) {
			
				// les options ne sont pas personnalisées - pour un mini site donné => pas cookie
				// echo $options_minisite. "ici"; 
			
			}
			else {  
				
				
				if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/js/cookiebanner.min.js.php') ) {
				
					$sHeader .= '	<script type="text/javascript" src="/backoffice/cms/js/cookiebanner.min.js.php" id="cookiebanner" '; 
					
				}
				else if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/js/cookiebanner.min.js') ) {
				
					$sHeader .= '	<script type="text/javascript" src="/backoffice/cms/js/cookiebanner.min.js" id="cookiebanner" '; 
					
				}
				else {
				}
				
				if ( defined("DEF_JS_COOKIESBAN_MINISITE_USE") && DEF_JS_COOKIESBAN_MINISITE_USE ) {
				
					// les options sont personnalisées - pour un mini site donné  
					$sHeader .=   $options_minisite;
				
				}
				else {
				
					if (!defined("DEF_JS_COOKIESBAN_OPTIONS")) {
						// les options ne sont pas personnalisées - à appliquer à tous les sites
						$sHeader .=  ' data-message="'.$translator->getText('Nous utilisons des cookies pour vous garantir la meilleure exp&eacute;rience sur notre site. Si vous continuez &agrave; utiliser ce dernier, nous consid&eacute;rerons que vous acceptez l\'utilisation des cookies.').'" ';
						
					}
					else { 
						// les options sont personnalisées - à appliquer à tous les sites
						//$sHeader .=  $translator->getText(DEF_JS_COOKIESBAN_OPTIONS);
						$sHeader .=  DEF_JS_COOKIESBAN_OPTIONS;
					
					}
				
				}
				 
				$sHeader .= ' data-expires="'.gmdate("M d Y H:i:s", mktime(0,0,0,date("n") ,date("j") ,date("Y")+1 )).'" data-cookie="AWS-cookies-optin-'.$_SESSION['idSite'].'"  data-moreinfo="'.$translator->getText('http://www.cnil.fr/vos-obligations/sites-web-cookies-et-autres-traceurs/').'" data-linkmsg="'.$translator->getText('En savoir plus').'" ';
				$sHeader .= ' >';
				$sHeader .= '</script>'."\n";  
				
			}
			
		}
		
	}
		
	
	if($oPage){ // js + css custom
		list($aResJSunique, $aResMediaByPath) = getPageDependencies($oPage);		
		
		foreach($aResJSunique as $kJsP => $sJspath){
			
			// print only, name based
			if (preg_match('/^.*print\.css$/', $sJspath)==1){ // css
				$sHeader .= '	<link href="'.$sJspath.'" rel="stylesheet" type="text/css" media="print" />'."\n";
			}
			elseif (preg_match('/^.*\.css$/', $sJspath)==1){ // css
				if (trim($aResMediaByPath[$sJspath])!=''){
					$media = ' media="'.$aResMediaByPath[$sJspath].'"';
				}				
				$sHeader .= '	<link href="'.$sJspath.'" rel="stylesheet" type="text/css"'.$media.' />'."\n";
			} 
			else{			
				if ((preg_match('/^[^\/]+\.[^\.]{2,4}$/', $sJspath)==1)){ // fichier seul
					$sJspath = '/custom/js/'.$_SESSION['rep_travail'].'/'.$sJspath;
					/*$sHeader .= '	<script type="text/javascript" src="/custom/js/'.$_SESSION['rep_travail'].'/'.$sJspath.'"></script>'."\n";*/
				}
				elseif (preg_match('/^http.*maps\.google\.com/', $sJspath)==1){ // maps.google.com
					// querir la bonne clef.				
					$aKeys = dbGetObjectsFromFieldValue('cms_mapskey', array('get_host'), array($_SERVER['HTTP_HOST']), NULL);
					if ((count($aKeys) > 0)&&($aKeys!=false)){
						$oKey = $aKeys[0];
						$sKey = $oKey->get_key();
						$sJspath = preg_replace('/key=.*/', 'key='.$sKey, $sJspath);
						$sJspath = str_replace('&amp;', '&', $sJspath);
						$sJspath = str_replace('&', '&amp;', $sJspath);
					}				
				}
				elseif (preg_match('/^http/', $sJspath)==1){ // autre http - non maps.google.com
					$sJspath = str_replace('&amp;', '&', $sJspath);
					$sJspath = str_replace('&', '&amp;', $sJspath);
				}			
				elseif(is_file($_SERVER['DOCUMENT_ROOT'].$sJspath)){ // local path
					// ras
				}
				
				if (trim($aResMediaByPath[$sJspath])!=''){
					$sHeader .= '<'.'?php if(matchMedia("'.$aResMediaByPath[$sJspath].'")){ ?'.'>'."\n";
				}
				$sHeader .= '	<script type="text/javascript" src="'.$sJspath.'"></script>'."\n";
				if (trim($aResMediaByPath[$sJspath])!=''){
					$sHeader .= '<'.'?php } ?'.'>'."\n";
				}
			}

		}	//foreach(aJspath as $kJsP => $sJspath){	

	}
	// JS site
	$sHeader .= '	<script src="/custom/js/'.strtolower($_SESSION['site_travail']).'/fojsutils.js" type="text/javascript"></script>'."\n";
	
	// CSS site	
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/css/fo_'.strtolower($_SESSION['site_travail']).'.css')){
		$sHeader .= '	<link href="/custom/css/fo_'.strtolower($_SESSION['site_travail']).'.css" rel="stylesheet" type="text/css" />'."\n";
	}
	// CSS Site Mobile
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/css/'.strtolower($_SESSION['site_travail']).'/mobile/mobile.css')){
		$sHeader .= '	<link href="/custom/css/'.strtolower($_SESSION['site_travail']).'/mobile/mobile.css" media="screen" rel="stylesheet" type="text/css" />'."\n";
	}
	// IE
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_ie6.css')){
		$sHeader .= '	<!--[if lt IE 7]><link href="/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->'."\n";
	}
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_ie7.css')){
		$sHeader .= '	<!--[if lt IE 8]><link href="/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_ie7.css" rel="stylesheet" type="text/css" /><![endif]-->'."\n";
	}
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_ie8.css')){
		$sHeader .= '	<!--[if lt IE 9]><link href="/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_ie8.css" rel="stylesheet" type="text/css" /><![endif]-->'."\n";
	}
	
	// CSS theme	
	if(($oPage)	&&	(intval($oPage->get_theme())>0)	){ 
		$aThemes = dbGetObjectsFromFieldValue("cms_theme", array("get_id"),  array($oPage->get_theme()), NULL);
		
		if (($aThemes!=false) && (sizeof($aThemes) == 1)){
			$oThemes = $aThemes[0];
			
			$cssTheme = '/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_'.strtolower(removeForbiddenChars($oThemes->get_nom(),false)).'.css';
			$cssThemeIE = '/custom/css/fo_'.strtolower($_SESSION['site_travail']).'_'.strtolower(removeForbiddenChars($oThemes->get_nom(),false)).'_ie.css';
			 
			if (is_file($_SERVER['DOCUMENT_ROOT'].$cssTheme)){
				$sHeader .= '	<link href="'.$cssTheme.'" rel="stylesheet" type="text/css" />'."\n";
			}
			if (is_file($_SERVER['DOCUMENT_ROOT'].$cssThemeIE)){
				$sHeader .= '	<!--[if IE]><link href="'.$cssThemeIE.'" rel="stylesheet" type="text/css" /><![endif]-->'."\n";
			}
		}	
	}
	
	// glossaire
	$jsGlossaire = '/backoffice/cms/js/glossary.js.php';
	if (isAllowed("GLOSSARY", $_SESSION['fonct'])	&&	is_file($_SERVER['DOCUMENT_ROOT'].$jsGlossaire)) {	
		$sHeader .= '	<script src="'.$jsGlossaire.'" type="text/javascript"></script>'."\n";
	}
	
	// tracking par minisite
	$sHeader .= getCodeTrackingByMinisite( $_SESSION['idSite'] ) ; 
	return $sHeader;
}

function getPageHeaderWithBody($oInfos_page=null) {
	return getPageHeader($oInfos_page)."\n".getHeadFooter($oInfos_page);
}

function getHeadFooter($oInfos_page) {
	$oPage = new Cms_page($oInfos_page->getPage_id()); 
	// le gabarit de cette page 
	$oGab = new Cms_page($oPage->getGabarit_page());
	// le node
	$oNode = new Cms_arbo_pages($oPage->getNodeid_page());	
	$oSite = new Cms_site($oNode->getId_site());
	$absolutePath = $oNode->getAbsolute_path_name(); 
	$sMinisiteRepertoire = trim(str_replace('/', ' ', preg_replace('/\/'.$oSite->get_rep().'\//si', '', $absolutePath)));
	if ($sMinisiteRepertoire== "") $sMinisiteRepertoire = "home";
        
        $class_langue = stripslashes(utf8IfNeeded(stripTitre('<?php $id_langue = $_SESSION[\'id_langue\']; $oLang = new cms_langue($id_langue); echo $oLang->get_libellecourt();  ?>')));
        
        
	return '</head>
	<body id="'.$oGab->getName_page().'" class="'.str_replace ("&", "_", strtolower($sMinisiteRepertoire)).' i-' . $class_langue . '">';
}

function getPageFooter() {
	return '</body>
	</html>	';
}

function getCodeTrackingByMinisite( $id_minisite ) {

	$oSite = new cms_site($id_minisite); 
	$codegooana = "";  
	if (method_exists ($oSite, "get_codegooana")) {
 
		if ($oSite->get_codegooana() != '') {
			$codegooana = $oSite->get_codegooana(); 
			// tester si il s'agit que du code UA de google analytics 
			/*UA-52442960-3*/
			if (preg_match("/^\bUA-\d{4,9}-\d{1,4}\b/i", $codegooana)) {  
				$codegooana_final =			"	<script>"."\n";
				$codegooana_final.=			"	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){"."\n";
				$codegooana_final.=		  	"	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),"."\n";
				$codegooana_final.=		  	"	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)"."\n";
				$codegooana_final.=		  	"	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');"."\n";
				$codegooana_final.=		  	"	 	"."\n";
				$codegooana_final.=		  	"	ga('create', '".$codegooana."', 'auto');"."\n";
				$codegooana_final.=		  	"	ga('send', 'pageview');"."\n";
				$codegooana_final.=			"		"."\n";
				$codegooana_final.=			"	</script> "; 
						
			}
			elseif (preg_match("/<script\b[^>]*>([\s\S]*?)<\/script>/i", $codegooana)) {  
				$codegooana_final =	($codegooana);
			
			}
			 
		}	 
	}
	
	return $codegooana_final;
	
}


function getTamponPage($divArray, $oInfos_page, $oPage, $gabGenerated="") 
{
	$tampon = getPageHeader($oInfos_page, $oPage);

$tampon.='
	<style type="text/css">';
	
if ($gabGenerated == "") 
{
	// styles des briques ------------------------- à externaliser dans fo_<nom mini site>.css?
	$tampon.='
.space {
	position:absolute;
	width: '.$oPage->getWidth_page().'px;
	height: '.$oPage->getHeight_page().'px;
	overflow: '.divOverflow().';
	overflow-x: '.divOverflow().';
	overflow-y: '.divOverflow().';	
	text-align: left;
}

.content {
	position:absolute;
	width: '.$oPage->getWidth_page().'px;
	height: '.$oPage->getHeight_page().'px;
	overflow: '.divOverflow().';
	overflow-x: '.divOverflow().';
	overflow-y: '.divOverflow().';
	text-align: left;
}';
	}

	foreach($divArray as $k => $v) {
		if(is_array($v)){
			$top=$v['top'];
			$left=$v['left'];
			$height=$v['height'];
			$width=$v['width'];

			if(!preg_match('/px$/',$top)) $top.='px';
			if(!preg_match('/px$/',$left)) $left.='px';
			if(!preg_match('/px$/',$width)) $width.='px';
			if(!preg_match('/px$/',$height)) $height.='px';

		// L/aisser le .div pour éviter bug!
			$tampon.='
	.div'.$v['id'].'{
	
		position: static;
		overflow: visible;
		overflow-y: visible;
		overflow-x: visible;
		text-align: left;
		top:0px;
		left:0px;
		width:100%;
		height:100%;
		filter:'.$filter.';
		-moz-opacity: '.$v['-moz-opacity'].';
		z-index: '.$v['zIndex'].';
		visibility: visible;
	}';	
		}

	}


	$tampon.='
	</style>
	';
	// ------------------------- styles des briques 

	// </head><body> seulement pour les pages
	// a terme il faudrait un new content pour les pages et un pour les gabarits
	if ($oPage->getIsgabarit_page()) {
		// on est sur un gabarit
		// on ne fait rien
	} else {
		// on est sur une page	
		$tampon .= getHeadFooter($oInfos_page); //getPageFooter();
	}
	
	return $tampon;
}
?>