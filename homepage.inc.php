<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
$regex = '/.*'.str_replace('/', '\/', $_SERVER['DOCUMENT_ROOT']).'/';
$urlToList = preg_replace($regex, '', $_SERVER['SCRIPT_FILENAME']);

if (preg_match('/^\/*index\.php$/si', $urlToList)){ // only sur homepage
	//error_log('home en  /');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/fo_prepend.php');

	if (isset($includesOk) && ($includesOk == true)){ 
		if (preg_match('/^id=([0-9]+)$/msi', $_SERVER['QUERY_STRING'], $regs)==1) { 
			$oSite = new Cms_site ($regs[1]);
			if ($oSite) {			 
				if (@$oSite->getClasse() == 'cms_site'){					
					$path=getSiteFrontEnd($oSite);
				}
				else{
					$path='./install/?nodomainfound';
				}
			}
		}
		else {		
			// ----------------------------------------------------------------------------
			//error_log('home detectSite');
			$oSite = detectSite();
			if ($oSite) {			 
				if (@$oSite->getClasse() == 'cms_site'){					
					$path=getSiteFrontEnd($oSite);
				}
				else{
					$path='./install/?nodomainfound';
				}
			}
			else{ 
				if (getCount_where('cms_site', NULL, NULL, array('TEXT'))) {	
					$sql = 'select * from cms_site order by cms_id';
					$aSite = dbGetObjectsFromRequete('cms_site', $sql);
					$oSite = $aSite[0];					
					if (@$oSite->getClasse() == 'cms_site'){
						$path=getSiteFrontEnd($oSite);
					}
					else{
						$path='./install/?nodomainfound';
					}
				}
				else{
					$path='./install/?nocmssitefound';
				}		
			}
		}	
	}
	else{
		$path='./install/?noincludefound';
	}
	//error_log('path : '.$path);
	if (preg_match('/\.php/i', $path)==0){ // path en dossier
		if (is_file($_SERVER['DOCUMENT_ROOT'].$path.'/index.html')){
			include($_SERVER['DOCUMENT_ROOT'].$path.'/index.html');	
		}
		elseif (is_file($_SERVER['DOCUMENT_ROOT'].$path.'/index.php')){
			//error_log('include '.$path.' adresse en dossiers only (no .php)');
			include($_SERVER['DOCUMENT_ROOT'].$path.'/index.php');	
		}else{
			//error_log('redirect adresse en dossiers only (no .html)');
			@header('Location: '.$path);
		}
	}
	else{ // path en fichier
		if (is_file($_SERVER['DOCUMENT_ROOT'].$path)){
			//error_log('include '.$path.' adresse en fichiers only (no .php)');
			include($_SERVER['DOCUMENT_ROOT'].$path);	
		}else{
			//error_log('redirect adresse en fichiers only (no .php)');
			@header('Location: '.$path);
		}
	}
}
elseif ((preg_match('/^\/content\/[^\/]+\/$/msi', $_SERVER['REQUEST_URI'])==1)||(preg_match('/^\/content\/[^\/]+\/index\.php$/msi', $_SERVER['REQUEST_URI'])==1)){ // only sur homepage
	//error_log('home en  /content/repertoire ');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/fo_prepend.php');	
	
	$oSite = detectSite();
	$siteUrl = trim($oSite->get_url());
	if ((preg_match('/hephaistos/', $siteUrl)==0)&&($siteUrl!='')&&(preg_match('/SwishSpider/', $_SERVER['HTTP_USER_AGENT'])==0)){ // pas de controle sur sites en dev, ni vers site n'ayant pas de nom d'hote, ni lors du passage de swish-e
		if ($siteUrl != $_SERVER['HTTP_HOST']){
			if($_SERVER['HTTPS'] == 'on'){
				$redirUrl = 'https://';
			}
			else{
				$redirUrl = 'http://';
			}
			$redirUrl.=$siteUrl.'/';
			//error_log('redirect  /content/repertoire vers / sur mismatch domaine');
			header('Location: '.$redirUrl,TRUE,301);
		}
		else{
			//error_log('redirect  /content/repertoire vers / sur matching domaine');			
			header('Location: /',TRUE,301);
		}		
	}
	else{
		//error_log('NO redirect siteUrl = '.$siteUrl);		
	}
}
?>