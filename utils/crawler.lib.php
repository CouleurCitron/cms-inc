<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/xml.parser.inc.php');

function getIsCrawler($userAgent){
	$crawlers = 'Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|' .
	'AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|' .
	'GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby|'.
	//'Mozilla|Lynx'; // Mozilla est inclut pour tester
	'Lynx'; // Lynx est inclut pour tester
	$isCrawler = (preg_match("/$crawlers/", $userAgent) > 0);
	return $isCrawler;
}

function echoMapLinks(){
	$stack = array();
	global $stack;
	$sXMLsitemap = readXMLfromURL('http://'.$_SERVER['HTTP_HOST'].'/sitemap.php');
	xmlStringParse($sXMLsitemap);
	foreach ($stack[0]['children'] as $k => $node){
		
		if ($node['name']=='URL'){
			$sURL = $node['children'][0]['cdata'];
			echo "\n".'<p><a href="'.$sURL.'">'.$sURL.'</a></p>';
		}
	}
}

function echoRssLinks($sRss){
	if (preg_match('/http.+/', $sRss)==0){
		return false;
	}
	
	$stack = array();
	global $stack;
	$sXMLrss = readXMLfromURL($sRss);
	xmlStringParse($sXMLrss);
	
	if ($stack[0]['name']=='RSS'){	
		//pre_dump($stack[0]['children'][0]['children'] );
			
		foreach ($stack[0]['children'][0]['children'] as $k => $node){
			
			if ($node['name']=='ITEM'){
				$sTitle = '';
				$sDesc= '';
				$sLink = '';
				foreach ($node['children'] as $kK => $rssField){
					//pre_dump($rssField);
					if($rssField['name']=='TITLE'){
						$sTitle= $rssField['cdata'];				
					}
					elseif($rssField['name']=='DESCRIPTION'){
						$sDesc= $rssField['cdata'];				
					}
					elseif($rssField['name']=='LINK'){
						$sLink= $rssField['cdata'];				
					}
				}
				echo "\n".'<div><p><strong>'.$sTitle.'</strong></p><p>'.$sDesc.'</p><p><a href="'.$sLink.'" title="'.$sTitle.'">'.$sLink.'</a></p></div>';
			}
		}	
	}
}

?>