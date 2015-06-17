<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
header("Content-type: text/xml; charset=utf-8");

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbopage.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/xml.parser.inc.php');

echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?".">\n";
//echo "<?xml-stylesheet type=\"text/xsl\" href=\"/backoffice/cms/utils/sitemap.xsl.php\" ?".">\n";
echo '<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	  xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";
echo "<url><loc>http://".$_SERVER['SERVER_NAME']."</loc>\n";
//-----------------------------------------------------------------

$bDebug = false;
//$bDebug = true;
//-----------------------------------------------------------------

// parametrage
//$excludedUrls = array("content/aws2006/reference.php","content/aws2006/inter.php","content/aws2006/reference_temoignage.php",	"content/aws2006/reference_bloc.php","content/aws2006/reference_detailprojet.php", "content/aws2006/index2.php");
//-----------------------------------------------------------------

//-----------------------------------------------------------------

function arboNodeToSiteMapVideo ($oSite, $idNode) {
	$oTemp = new cms_video();
	$oTemp = new cms_assovideopage();
	unset($oTemp);
	
	$sql = 'select distinct cms_video.* from cms_video, cms_assovideopage, cms_page 
							where xvp_cms_video = cms_video.cms_id
							and xvp_cms_page = cms_page.id_page 
							and nodeid_page = '.$idNode.' and 	id_site = '.$oSite->get_id().'
							';

	$aVideo = dbGetObjectsFromRequete('cms_video', $sql);
	
	if (sizeof($aVideo) > 0) {
		foreach ($aVideo as $video) { 
		
			if ($video->get_tag() != '') {
				$tags = str_replace("\"", " ", $video->get_tag());
				$tags = str_replace("'", " ", $tags);
				$tags = str_replace(":", " ", $tags);
				$tags = str_replace(";", " ", $tags);
				$tags = str_replace("?", " ", $tags);
				$tags = str_replace("<", " ", $tags);
				$tags = str_replace(">", " ", $tags);
				$tags = str_replace("(", " ", $tags);
				$tags = str_replace(")", " ", $tags);
				$tags = str_replace("[", " ", $tags);
				$tags = str_replace("]", " ", $tags);
				$tags = preg_split("/[\s,]+/",  $tags);
			}
			
			if ($video->get_family_friendly() == 1) $family_friendly = "yes";
			else $family_friendly = "no";
		 
		  
		  	$content_loc = preg_replace('/http:\/\/[^\/]+hephaistos\.interne/msi', 'http://'.$_SERVER['HTTP_HOST'], $video->get_content_loc());
		  	$player_loc = preg_replace('/http:\/\/[^\/]+hephaistos\.interne/msi', 'http://'.$_SERVER['HTTP_HOST'], $video->get_player_loc());
		  
			echo "<video:video>\n";
			if ($video->get_thumbnail_loc()!='')	echo "<video:thumbnail_loc>http://".$_SERVER['HTTP_HOST']."/custom/upload/cms_video/".$video->get_thumbnail_loc()."</video:thumbnail_loc>\n";  
			if ($video->get_title()!='') 			echo "<video:title>".utf8_encode($video->get_title())."</video:title>\n";
			if ($video->get_description()!='') 		echo "<video:description>".utf8_encode($video->get_description())."</video:description>\n";
			if ($content_loc!='') 		echo "<video:content_loc>".XMLconformeURL($content_loc)."</video:content_loc>\n";
			if ($player_loc!='') 		echo "<video:player_loc allow_embed=\"yes\" autoplay=\"ap=1\">".XMLconformeURL($player_loc)."</video:player_loc>\n";
			if ($video->get_duration()!= '') 		echo "<video:duration>".$video->get_duration()."</video:duration>\n";
			if (sizeof($tags) > 0) {
				foreach ($tags as $tag) { 			echo "<video:tag>".XMLconformeURL(utf8_encode($tag))."</video:tag>\n"; }
			}
			if ($video->get_category()!= '')		echo "<video:category>".XMLconformeURL(utf8_encode($video->get_category()))."</video:category>\n"; 
			echo "<video:family_friendly>".$family_friendly."</video:family_friendly>\n";    
			echo "</video:video>\n";  
		}
	}
}



function arboNodeToSiteMap($oSite, $idNode){
	global $db;
	$aChilden = getNodeChildren($oSite->get_id(), $db, $idNode);
	if (count($aChilden) > 0){
		foreach($aChilden as $kNode => $oNode){
			if (intval($oNode['order']) > 0){
				$fullpath = '/content'.$oNode['path'];
				if (is_file($_SERVER['DOCUMENT_ROOT'].$fullpath.'index.php') ||  is_file($_SERVER['DOCUMENT_ROOT'].$fullpath.'index.html')){
					echo "<url><loc>http://".XMLconformeURL($_SERVER['SERVER_NAME'].$fullpath)."</loc>\n"; 
					
					arboNodeToSiteMapVideo ($oSite, $oNode['id']);  
					
					echo "</url>\n";
				}
							
			}
			arboNodeToSiteMap($oSite, $oNode['id']);
		}	
	}
	return true;
}

$oSiteToMap = hostToSite($_SERVER['HTTP_HOST']);

if ($oSiteToMap==false){
	$aSites = listSite("ALL");
	if (count($aSites) > 0){
		$oSiteToMap = NULL;
		foreach($aSites as $kSite => $oSite){
			if (strpos($oSite->get_url(), $_SERVER['SERVER_NAME']) !== false){
				$oSiteToMap = $oSite;
				break;
			}
		}
		if ($oSiteToMap == NULL){
			$oSiteToMap = new Cms_site(1);
		}	
	}
}

arboNodeToSiteMapVideo($oSiteToMap, 0);
echo "</url>\n";
arboNodeToSiteMap($oSiteToMap, 0);



if (is_file($_SERVER['DOCUMENT_ROOT'].'/include/modules/'.strtolower($oSiteToMap->get_rep()).'/sitemap.inc.php')){
	include('modules/'.strtolower($oSiteToMap->get_rep()).'/sitemap.inc.php');

}


echo "</urlset>";
?>