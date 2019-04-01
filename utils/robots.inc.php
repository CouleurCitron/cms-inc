<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
header("Content-type: text/plain; charset=utf-8");

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

//-----------------------------------------------------------------

$bDebug = false;
//$bDebug = true;
//-----------------------------------------------------------------

if(!isset($oSiteToMap)	||	$oSiteToMap==false	|| $oSiteToMap==NULL){
	$oSiteToMap = hostToSite($_SERVER['HTTP_HOST']);
}

if ($oSiteToMap==false){
	$aSites = listSite("ALL");
	if (count($aSites) > 0){
		$oSiteToMap = NULL;
		foreach($aSites as $kSite => $oSite){
			if (strpos($oSite->get_url(), $_SERVER['HTTP_HOST']) !== false){
				$oSiteToMap = $oSite;
				break;
			}
		}
		if ($oSiteToMap == NULL){
			
			// conditions spés pour éviter d'indexer des sites en pre-prod 
			// regle *.cuoleur-citron.com mais pas www.couleur-citron.com
			if ((preg_match('/(.)+\.couleur-citron\.com/', $_SERVER['HTTP_HOST'])===false)	||  (preg_match('/hephaistos/', $_SERVER['HTTP_HOST'])===false)	||  ($_SERVER['HTTP_HOST']=='www.couleur-citron.com')){
				// oui, on indexe			
				$oSiteToMap = new Cms_site(1);
			
			}
			else{
				// on indexe pas
				$oSiteToMap = NULL;
			}
		}	
	}
}
?>
User-agent: *
<?php
//Allow: /
if ($oSiteToMap != NULL){
?>
Disallow: /custom/upload/
Disallow: /frontoffice/
Disallow: /modules/
Disallow: /include/
Disallow: /tmp/
Disallow: /*.bak$
Disallow: /content/
<?php
	$aSite = dbGetObjectsFromRequete('cms_site', 'SELECT * FROM cms_site');
	$bAllow = false;
	
	if ((count($aSite) > 0)&&($aSite!=false)){
		foreach($aSite as $cKey => $oSite){			
			if ($oSite->get_url()==''){
				$oSite->set_url($_SERVER['HTTP_HOST']);
			}
			
			// on disallow les archivés/en attente
			if ($oSite->get_statut() != DEF_ID_STATUT_LIGNE){
				echo 'Disallow: /content/'.utf8_encode($oSite->get_rep())."/\n";
			}
			// on disallow les autre sites, si ils ont un host differents du site en cours
			elseif (($oSiteToMap->get_id() != $oSite->get_id())	&&	($oSiteToMap->get_url() != $oSite->get_url())){
				echo 'Disallow: /content/'.utf8_encode($oSite->get_rep())."/\n";
			}			
			// allow du site en cours 
			elseif (($oSiteToMap->get_id() == $oSite->get_id())	&&	($oSiteToMap->get_url() == $oSite->get_url())){
				//test sir noindex, nofollow ==> disallow
				if (preg_match('/noindex, nofollow/msi', $oSite->get_robots())){
					echo 'Disallow: /content/'.utf8_encode($oSite->get_rep())."/\n";
					echo 'Disallow: /'."\n";
				}
				else{
					echo 'Allow: /content/'.utf8_encode($oSite->get_rep())."/\n";
					$bAllow = true;
				}
				//echo 'Allow: /'."\n";
			}
			else{		
				//if (is_dir($_SERVER['DOCUMENT_ROOT'].'/content/'.utf8_encode($oSite->get_rep()))){
				//	echo 'Allow: /content/'.utf8_encode($oSite->get_rep())."/\n";
				//}
				//if (is_dir($_SERVER['DOCUMENT_ROOT'].'/frontoffice/'.utf8_encode($oSite->get_rep()))){
				//	echo 'Allow: /frontoffice/'.utf8_encode($oSite->get_rep())."/\n";
				//}
				if (is_dir($_SERVER['DOCUMENT_ROOT'].'/content/'.utf8_encode($oSite->get_rep()).'/panier')){
					echo 'Disallow: /content/'.utf8_encode($oSite->get_rep()).'/panier'."/\n";
					echo 'Disallow: /*?clicksvenantde=PRODUITRAMA'."/\n";
					
				}
			}
		}
	}
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/robots.txt')){
		include($_SERVER['DOCUMENT_ROOT'].'/robots.txt');
		echo "\n";
	}
	
	if ($bAllow == true){
    echo "Sitemap: http";
    if($_SERVER['HTTPS'] == 'on'){
      echo 's';
    }
		echo '://'.$_SERVER['HTTP_HOST'].'/sitemap.xml'."\n";
	}
}
else{
	echo 'Disallow: *'."/\n";
}
?>
User-agent: SwishSpider
Allow: /