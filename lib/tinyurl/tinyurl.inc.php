<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
//http://tinyurl.com/create.php?url=http://www.liendujour.net/backoffice/cms/utils/feed_cms_rss.php?id=1
//[<a href="http://tinyurl.com/ktpe45" target="_blank">Open in new window</a>]

function getTinyURL($sLong){	
	$fh = fopen('http://tinyurl.com/create.php?url='.$sLong,'r');
	if ($fh != false){
		$sBodyHTML="";
		
		while(!feof($fh)) {
			$gets = fgets($fh);
			if (ereg('<a href="http://tinyurl.com/[^"]+" target="_blank">Open in new window</a>', $gets)){
				return ereg_replace('.*<a href="(http://tinyurl.com/[^"]+)" target="_blank">Open in new window</a>.*', '\\1', $gets);			
			}			
			$sBodyHTML.=$gets;
		}
		fclose($fh);	
	}
	
	return false;
}

//echo getTinyURL('http://www.liendujour.net/backoffice/cms/utils/feed_cms_rss.php?id=1');
?>