<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbopage.lib.php');

$oSite = detectSite();
$idSite = $oSite->get_id();
$rep = $oSite->get_rep(); 
sitePropsToSession($oSite);


if (defined('WEBSHOP_CUSTOM_LIB') && is_file($_SERVER['DOCUMENT_ROOT'].'/'.WEBSHOP_CUSTOM_LIB)){
	include_once(WEBSHOP_CUSTOM_LIB);
}


if (defined("DEF_APP_USE_CRON") && is_file($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/cron/execute_all.php') ) {
	if (DEF_APP_USE_CRON) include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/cron/execute_all.php');
} 


///--  GESTION des vars en GET -----------------
if (is_get("id"))
	$id = intval($_GET['id']);

// $idSite ------------------------------------------------------------
if ((preg_match('/\/content\//', $_SERVER['PHP_SELF'])==1)||($_SERVER['PHP_SELF'] == '/index.php')) {
	//--------------------------------------
	//$tempPhp_self_clean = explode($_SERVER['DOCUMENT_ROOT'], $_SERVER['PATH_TRANSLATED']);
	$tempPhp_self_clean = explode($_SERVER['DOCUMENT_ROOT'], $_SERVER['SCRIPT_FILENAME']); 
 
	$php_self_clean = stripslashes($tempPhp_self_clean[1]);
	
	$nodepath = stripslashes(substr($php_self_clean,0,strrpos($php_self_clean, "/"))."/"); 
	// patch si pas de / en debut de chaine
	$nodepath = preg_replace('/^([^\/]+.*)$/', '/$1', $nodepath);
	
	if ((stripslashes(substr($nodepath,0,13)) == '/frontoffice/') || (stripslashes(substr($nodepath,0,9))== '/content/')||($_SERVER['PHP_SELF'] == '/index.php')) {
		if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")  === false){
			$nodepath4context = stripslashes(rawurldecode($php_self_clean));
		} else {
			$nodepath4context = stripslashes(($php_self_clean));
		}	
		$tempNodepath4context = stripslashes(substr($nodepath4context,0,strrpos($nodepath4context, "/"))."/");
		$nodeId = path2nodes($idSite, $db, $tempNodepath4context);
		
		// $courant ------------------------------------------------------------ 
		if (!isset($courant)){
			// pour une page donnée, le module retourne la descrition du node courant, sa liste de fils
			if ((isset($_REQUEST['path'])) and ($_REQUEST['path'] != "")){
				$urlToList = urldecode($_REQUEST['path']);
				$pathToList = substr ($urlToList, 0, strrpos ($urlToList, "/") + 1);
			} else {
				$regex = '/.*'.str_replace('/', '\/', $_SERVER['DOCUMENT_ROOT']).'/';
				$urlToList = preg_replace($regex, '', $_SERVER['SCRIPT_FILENAME']);
				
				$pathToList = substr ($urlToList, 0, strrpos ($urlToList, "/") + 1);
			}
			// /content/BBENT/ devient /content/
			if ($pathToList == '/content/'.path2minisiteRepertoire($db, $pathToList).'/')
				$pathToList = '/content/';
			// calcul du path de niveau 1 correspondant à la page en cours
			$aTempPathToList = explode('/', $pathToList);
			if (count($aTempPathToList) > 2)
				$aTempPathToList = array_slice(explode("/", $pathToList),0,4);
			$basePathToList = join($aTempPathToList, "/")."/";
			
			//error_log("fopp ".stripslashes($pathToList));			
			$courant = getNodeInfosReverse($idSite,$db,stripslashes($pathToList));	 
			// patch pour la racine
			if ($courant["id"] == 0 && $courant["id_site"] != $_SESSION["idSite"])  $courant["id_site"] = $_SESSION["idSite"];
			
			$oNode = new cms_arbo_pages($courant['id']);
			
			// ici, on va inclure les eventuels prepends custom
			$aResPrepend = dbGetAssocies($oNode, 'cms_assoprependarbopages', false, false);
			
			foreach($aResPrepend['list'] as $kPp => $aPrepend){
				if (is_file($_SERVER['DOCUMENT_ROOT'].$aPrepend['abstract'])){ // chemin complet
					include_once($_SERVER['DOCUMENT_ROOT'].$aPrepend['abstract']);			
				}
				elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/include/modules/'.$_SESSION['rep_travail'].'/'.$aPrepend['abstract'])){
					include_once($_SERVER['DOCUMENT_ROOT'].'/include/modules/'.$_SESSION['rep_travail'].'/'.$aPrepend['abstract']);			
				}
				elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$_SESSION['rep_travail'].'/'.$aPrepend['abstract'])){
					include_once($_SERVER['DOCUMENT_ROOT'].'/modules/'.$_SESSION['rep_travail'].'/'.$aPrepend['abstract']);			
				}
			}
                        
			// ici, on va inclure les eventuels prepends custom
			$oSiteClass = new cms_site($idSite);
                        
			$aResPrepend = dbGetAssocies($oSiteClass, 'cms_assoprependcmssite', false, false);
			
			foreach($aResPrepend['list'] as $kPp => $aPrepend){
				if (is_file($_SERVER['DOCUMENT_ROOT'].$aPrepend['abstract'])){ // chemin complet
					include_once($_SERVER['DOCUMENT_ROOT'].$aPrepend['abstract']);			
				}
				elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/include/modules/'.$_SESSION['rep_travail'].'/'.$aPrepend['abstract'])){
					include_once($_SERVER['DOCUMENT_ROOT'].'/include/modules/'.$_SESSION['rep_travail'].'/'.$aPrepend['abstract']);			
				}
				elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$_SESSION['rep_travail'].'/'.$aPrepend['abstract'])){
					include_once($_SERVER['DOCUMENT_ROOT'].'/modules/'.$_SESSION['rep_travail'].'/'.$aPrepend['abstract']);			
				}
			}
                        

		} // $courant ------------------------------------------------
	} // fin test fo ou content
	
	// only si CMS actif et pour pages sous content + la racine
	if (preg_match('/CMS/',$_SESSION['fonct'])==1){
		if (class_exists('pageObject')){
			$pageObject = new pageObject();
			$pageObjectData = $pageObject->getObjectsForCurrentPage(); 
		}
	}
}
?>