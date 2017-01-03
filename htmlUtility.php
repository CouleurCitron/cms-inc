<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/lib/getid3/getid3.php');
/*
sponthus 15/07/2005
fonctions de retraitement sur des chaines html

function htmlFCKcompliant($str)
function compliesFCKhtml($str){
function isAllowed ($search, $string)
function matchMedia($sMedia){
function compactJS($pContent) {
function fullDoctype($doctype, $lang)
function makeRandomKey ($type='sha1', $len=20) {
function log_dump($oO){
function trace($str){
function isFilled($str){
function returnIsFilled($str){
function isFilledNotNull($str){
function returnIsFilledNotNull($str){
function htmlFormat()  // formatage HTML pour e-mail
function html2text($str){  // formatage TEXT pour e-mail
function getGreater($int1, $int2){
function dumpObjLite($oO){
function is_session($getParamName, $notempty=true){
function is_as_get($postedvar){
function is_get($getParamName, $notempty){
function is_post($getParamName, $notempty){
function pre_dump($data){
function textToLink($text){
function replaceQuote($sString)
function replaceQuote2($sString)
function MiseEnMaj($sChaine) {
function RetourLigne($sChaine) { // converti les \n en <br>
function cheminAere($sChaine) {
function htmlImpairePaire($i)
function htmlSiImpaireCouleur($i,$couleur1,$couleur2)
function makeTableauModulo($aData) {
function encodePath($chainePath){
function noAccent($texte){
function strtoupperAccent($texte){
function isEmail($email)
function removeXitiForbiddenChars($strChars){
function regionDateBO($strDate){
function regionDateFO($strDate){
function regionDate($strDate,$sDateformat)
function DDMMYYYYtoEnglish($strDate){
function DDMMYYYYtoYYYYMMDD($strDate){
function YYYYMMDDtoDDMMYYYY($strDate)
function ouinon($sLib) {  
function text2url($chaine) { // supprime tous les caractères spéciaux
function truncate($sLib) {
formatFileSize ($_size)	// Format l'affichage d'une taille de fichier
*/


/*
convertis du code html pour compatibiliter avec l'usage dans CK editor
params
$str:string	: html
returns
string		: html converti
*/
function htmlFCKcompliant($str){ 
	$str = preg_replace_callback('/(<script[^>]+>[^<]+AC_FL_RunContent[^<]+<\/script>)/msi', 'swfScriptToEmbed', $str);
	$str = preg_replace('/(<script[^>]+AC_RunActiveContent[^>]+><\/script>)/msi', '', $str);
	
	$str = stripslashes($str);
	
	//$str = mb_convert_encoding($str, "UTF-8", "auto");
	 
	return $str;
}

function swfScriptToEmbed($matches){
	$str = $matches[1];
	 
	
	/*	
	$str='<script type="text/javascript">swfSrcObject = "/custom/swf/fr/anim_accueil?embedUrl="+escape(document.location.href);swfSrcEmbed = "/custom/swf/fr/anim_accueil?embedUrl="+escape(document.location.href);flashvars = "embedUrl="+escape(document.location.href);AC_FL_RunContent( "codebase","https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0","width","'.$aAttribs['width'].'","height","'.$aAttribs['height'].'","src", "'.$aAttribs['src'].'", "quality","high","pluginspage","https://get.adobe.com/flashplayer/","align", "middle", "play", "true", "loop", "true", "scale", "showall", "wmode", "opaque", "devicefont", "false", "id", "'.basename($aAttribs['src']).'", "name", "'.basename($aAttribs['src']).'", "menu", "false", "allowFullScreen", "false", "allowScriptAccess","sameDomain","movie", "'.$aAttribs['src'].'", "salign", "", "flashvars", flashvars );</script>';*/
	
	
	/*
	
	flv
	<p><script src="/backoffice/cms/js/AC_RunActiveContent.js" type="text/javascript"></script><script type="text/javascript">videoSrcObject = "/backoffice/cms/utils/scrubberLarge?_vidName=Firebrake.flv&_vidURL=/custom/video/luzenac/Firebrake.flv&_phpURL=/backoffice/cms/utils/flvprovider.php&_vidThb=&_start=0&_end=0&_autostart=0&_onEnd=&_showUI=1&_loop=0&_volume=&_onRollOver=&embedUrl="+escape(document.location.href);videoSrcEmbed = "/backoffice/cms/utils/scrubberLarge?_vidName=Firebrake.flv&_vidURL=/custom/video/luzenac/Firebrake.flv&_phpURL=/backoffice/cms/utils/flvprovider.php&_vidThb=har.jpg&_start=0&_end=0&_autostart=0&_onEnd=&_showUI=1&_loop=0&_volume=&_onRollOver=&embedUrl="+escape(document.location.href);flashvars = "_vidName=Firebrake.flv&_vidURL=/custom/video/luzenac/Firebrake.flv&_phpURL=/backoffice/cms/utils/flvprovider.php&_vidThb=&_start=0&_end=0&_autostart=0&_onEnd=&_showUI=1&_loop=0&_volume=100&_onRollOver=&embedUrl="+escape(document.location.href);swfSrcFull = "/backoffice/cms/utils/scrubberLarge.swf";swfSrc = "/backoffice/cms/utils/scrubberLarge";AC_FL_RunContent( "codebase","https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0",
	"width","","height","","src", swfSrc, "quality","high","pluginspage","https://get.adobe.com/flashplayer/",
	"align", "middle", "play", "true", "loop", "", "autostart", "", "volume", "", "scale", "showall", "wmode", "opaque", "devicefont", "false",
	"id", "awsvid_1306200206601", "name", "awsvid_1306200206601", "menu", "false", "allowFullScreen", "false",
	"allowScriptAccess","sameDomain","movie", swfSrc, "salign", "", "flashvars", flashvars );function videoSizeUpdate(w, h){
			//alert(w+" / "+h);
			//document.getElementById("awsvid").width = w;
			//document.getElementById("awsvid").height = h;
			a = document.getElementsByTagName("embed");
			for (i in a){
				if (a[i].src != undefined){
					if (a[i].src == swfSrcFull){
						a[i].width = w;
						a[i].height = h; 
					}
				}
			}
			a = document.getElementsByTagName("object");
			for (i in a){
				if (a[i].id != undefined){
					if (a[i].id == "awsvid_1306200206601"){
						a[i].width = w;
						a[i].height = h;					
					}
				}
			}		
			if (document.getElementById("WIDTHcontent") != undefined){
				document.getElementById("WIDTHcontent").value = w;
			}
			if (document.getElementById("HEIGHTcontent") != undefined){
				document.getElementById("HEIGHTcontent").value = h;
			}
		}</script></p>

	*/
		
	if (preg_match ('/\.swf/', $str ) )	$extension = ".swf";
	else if (preg_match ('/\.flv/', $str ) )	$extension = ".flv";
	
	if (preg_match('/_vidURL=([^"&]+)&/msi', $str, $matches_)) {
		$aAttribs['name'] = $matches_[1].'';
	} 
	
	if (preg_match ('/\.swf/', $aAttribs['name'] ) )	$extension = ".swf";
	else if (preg_match ('/\.flv/', $aAttribs['name'] ) )	$extension = ".flv";
	
	if (preg_match ('/\.flv/', $str ) ) { 
		$str = preg_replace ( '/src[", ]+swfSrc/msi', 'src", "'.str_replace (".flv", "", $aAttribs['name']).'"', $str);
	}
	 
		
	$aAttribs = array();		
	
	if (preg_match('/width[", ]+([0-9]*)[", ]+height[", ]+([0-9]*)[", ]+src[", ]+([^"]+)"/msi', $str, $matches)){	
	  
		$aAttribs['src'] = $matches[3].$extension;
		 
		
		if (preg_match ("/swfSrc/", $matches[3])) {
			if (preg_match('/swfSrc[ ="]+([^"]+)\"/msi', $str, $matches_)) {
				//swfSrc = "/backoffice/cms/utils/scrubberLarge"
				//$aAttribs['name'] = $matches_[1].'';
				$aAttribs['src'] = $matches_[1].$extension; 
			} 
		} 
		if (preg_match('/loop[", ]+([^"]+)\"/msi', $str, $matches_)) {
			//swfSrc = "/backoffice/cms/utils/scrubberLarge"
			//$aAttribs['name'] = $matches_[1].'';
			$aAttribs['loop'] = $matches_[1]; 
		} 
		else if (preg_match('/_loop=([^"\&]+)/msi', $str, $matches_)) {
			//swfSrc = "/backoffice/cms/utils/scrubberLarge"
			//$aAttribs['name'] = $matches_[1].'';
			$aAttribs['loop'] = $matches_[1]; 
		} 
		else {
			$aAttribs['loop'] = 'true'; 
		}
		
		 
		if (preg_match('/autostart[", ]+([^"]+)\"/msi', $str, $matches_)) {
			//swfSrc = "/backoffice/cms/utils/scrubberLarge"
			//$aAttribs['name'] = $matches_[1].'';
			$aAttribs['autostart'] = $matches_[1]; 
		} 
		else if (preg_match('/_autostart=([^"\&]+)/msi', $str, $matches_)) {
			//swfSrc = "/backoffice/cms/utils/scrubberLarge"
			//$aAttribs['name'] = $matches_[1].'';
			$aAttribs['autostart'] = $matches_[1]; 
		} 
		else {
			$aAttribs['autostart'] = 'true'; 
		}
		
		
		
		if (preg_match('/volume[", ]+([^"]+)\"/msi', $str, $matches_)) {
			//swfSrc = "/backoffice/cms/utils/scrubberLarge"
			//$aAttribs['name'] = $matches_[1].'';
			$aAttribs['volume'] = $matches_[1]; 
		}
		else if (preg_match('/_volume=([^"\&]+)/msi', $str, $matches_)) {
			//swfSrc = "/backoffice/cms/utils/scrubberLarge"
			//$aAttribs['name'] = $matches_[1].'';
			$aAttribs['volume'] = $matches_[1]; 
			 
		}  
		else {
			$aAttribs['volume'] = '100'; 
		}
		
		
		$aAttribs['width'] = $matches[1];
		$aAttribs['height'] = $matches[2];	
		if (preg_match('/_vidURL=([^"&]+)&/msi', $str, $matches_)) {
			$aAttribs['name'] = $matches_[1].'';
		} 
		
		
		 
		$str = '<embed height="'.$aAttribs['height'].'" type="application/x-shockwave-flash" pluginspage="https://get.adobe.com/flashplayer/" width="'.$aAttribs['width'].'" src="'.$aAttribs['src'].'" name="'.$aAttribs['name'] .'" loop="'.$aAttribs['loop'].'" volume="'.$aAttribs['volume'].'" autostart="'.$aAttribs['autostart'].'"></embed>';	
	}
	else{ // pas de match, on supprime la balise
		$str = '';
	}	
	 
	return $str;
}
 

 /*
convertis du code html issu de CK editor pour usage dans le CMS
params
$str:string	: html
returns
string		: html converti
*/ 

function compliesFCKhtml($str){ 

	$str = preg_replace ('/(<object[^>]+>)/msi', '', $str); 
	$str = preg_replace ('/(<\/object>)/msi', '', $str); 
	$str = preg_replace('/(<param[^>]+\/>)/msi', '', $str);
	
	$str = preg_replace_callback('/(<embed[^>]+><\/embed>)/msi', 'swfEmbedToScript', $str); 
	return $str;
}

function swfEmbedToScript($matches){
	
	/*$str = $matches[1];
		
	$str='<embed height="400" type="application/x-shockwave-flash" pluginspage="https://get.adobe.com/flashplayer/" width="550" src="/custom/swf/fr/test(7).swf"></embed>'; 
	
	$aBits = explode(' ', $str);
	if ($aBits){ // pas de match, on supprime la balise	
		/*$aAttribs = array();		
		foreach($aBits as $k => $sBit){		
			if (preg_match('/^([a-z0-9]+)="([^"]+)".*$/msi', $sBit, $sSubBits)){
				$aAttribs[$sSubBits[1]]=$sSubBits[2];
			}		
		}		
		//$aAttribs['src'] = str_replace('.swf', '', $aAttribs['src']);		
		$aAttribs['url'] = $aAttribs['name'];	
		$aAttribs['name'] = basename($aAttribs['name']);
		$aAttribs['srcswf'] = $aAttribs['src'];				 
		$aAttribs['src'] = str_replace('.swf', '', $aAttribs['src']);	
		
		$str = '<script type="text/javascript">swfSrcObject = "/custom/swf/fr/anim_accueil?embedUrl="+escape(document.location.href);swfSrcEmbed = "/custom/swf/fr/anim_accueil?embedUrl="+escape(document.location.href);flashvars = "embedUrl="+escape(document.location.href);AC_FL_RunContent( "codebase","https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0","width","'.$aAttribs['width'].'","height","'.$aAttribs['height'].'","src", "'.$aAttribs['src'].'", "quality","high","pluginspage","https://get.adobe.com/flashplayer/","align", "middle", "play", "true", "loop", "true", "scale", "showall", "wmode", "opaque", "devicefont", "false", "id", "'.basename($aAttribs['src']).'", "name", "'.basename($aAttribs['src']).'", "menu", "false", "allowFullScreen", "false", "allowScriptAccess","sameDomain","movie", "'.$aAttribs['src'].'", "salign", "", "flashvars", flashvars );</script>';
	}
	else{
		$str = '';
	}
	*/
	if (preg_match ('/\.flv/', $matches[1]) )
		$str = swfEmbedToScriptForModuleContenu_flv($matches);
	else
		$str = swfEmbedToScriptForModuleContenu($matches);
	
	return $str;
}
 

/*
convertis du code html issu de CK editor pour usage dans le CMS
params
$str:string	: html
returns
string		: html converti
*/ 
function compliesFCKhtmlForModuleContenu($str){
	$str = preg_replace_callback('/(<embed[^>]+><\/embed>)/msi', 'swfEmbedToScriptForModuleContenu', $str);
	return $str;
}

function swfEmbedToScriptForModuleContenu($matches){
	$str = $matches[1]; 
	/*	
	$str='<embed height="400" type="application/x-shockwave-flash" pluginspage="https://get.adobe.com/flashplayer/" width="550" src="/custom/swf/fr/test(7).swf"></embed>';*/
	
	$aBits = explode(' ', $str);
	if ($aBits){ // pas de match, on supprime la balise	
		$aAttribs = array();		
		foreach($aBits as $k => $sBit){		
			if (preg_match('/^([a-z0-9]+)="([^"]+)".*$/msi', $sBit, $sSubBits)){
				$aAttribs[$sSubBits[1]]=$sSubBits[2];
			}		
		} 
		$aAttribs['url'] = $aAttribs['name'];	
		$aAttribs['name'] = basename($aAttribs['name']);
		$aAttribs['srcswf'] = $aAttribs['src'];				 
		$aAttribs['src'] = str_replace('.swf', '', $aAttribs['src']);	
		($aAttribs['loop'] == "true") ? $aAttribs['_loop'] = 1 : $aAttribs['_loop'] = 0;
		
		($aAttribs['autostart'] == "true") ? $aAttribs['_autostart'] = 1 : $aAttribs['_autostart'] = 0;
		
		/*$str = '<script type="text/javascript">swfSrcObject = "/custom/fr/video/'.$aAttribs['name'].'?embedUrl="+escape(document.location.href);swfSrcEmbed = "/custom/fr/video/'.$aAttribs['name'].'?embedUrl="+escape(document.location.href);flashvars = "embedUrl="+escape(document.location.href);AC_FL_RunContent( "codebase","https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0","width","'.$aAttribs['width'].'","height","'.$aAttribs['height'].'","src", "'.$aAttribs['src'].'", "quality","high","pluginspage","https://get.adobe.com/flashplayer/","align", "middle", "play", "true", "loop", "true", "scale", "showall", "wmode", "opaque", "devicefont", "false", "id", "'.basename($aAttribs['src']).'", "name", "'.basename($aAttribs['src']).'", "menu", "false", "allowFullScreen", "false", "allowScriptAccess","sameDomain","movie", "'.$aAttribs['src'].'", "salign", "", "flashvars", flashvars );</script>';*/
		
		$id_unique = date ("ymdhm").rand(0,1000);
		$str = '<script type="text/javascript">flashvars = "_vidName='.$aAttribs['name'].'&_vidURL='.$aAttribs['url'].'&_phpURL='.$aAttribs['url'].'&_vidThb=&_start=0&_end=0&_autostart='.$aAttribs['_autostart'].'&_onEnd=&_showUI=1&_loop='.$aAttribs['_loop'].'&_volume='.$aAttribs['volume'].'&_onRollOver=&embedUrl="+escape(document.location.href);swfSrcFull = "'.$aAttribs['srcswf'].'";swfSrc = "'.$aAttribs['src'].'";AC_FL_RunContent( "codebase","https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0",
							"width","'.$aAttribs['width'].'","height","'.$aAttribs['height'].'","src", swfSrc, "quality","high","pluginspage","https://get.adobe.com/flashplayer/",
							"align", "middle", "play", "true", "loop", "'.$aAttribs['loop'].'", "autostart", "'.$aAttribs['autostart'].'", "volume", "'.$aAttribs['volume'].'", "scale", "showall", "wmode", "opaque", "devicefont", "false",
							"id", "awsvid_'.$id_unique.'", "name", "awsvid_'.$id_unique.'", "menu", "false", "allowFullScreen", "false",
							"allowScriptAccess","sameDomain","movie", swfSrc, "salign", "", "flashvars", flashvars );function videoSizeUpdate(w, h){
									//alert(w+" / "+h);
									//document.getElementById("awsvid").width = w;
									//document.getElementById("awsvid").height = h;
									a = document.getElementsByTagName("embed");
									for (i in a){
										if (a[i].src != undefined){
											if (a[i].src == swfSrcFull){
												a[i].width = w;
												a[i].height = h; 
											}
										}
									}
									a = document.getElementsByTagName("object");
									for (i in a){
										if (a[i].id != undefined){
											if (a[i].id == "awsvid_'.$id_unique.'"){
												a[i].width = w;
												a[i].height = h;					
											}
										}
									}		
									if (document.getElementById("WIDTHcontent") != undefined){
										document.getElementById("WIDTHcontent").value = w;
									}
									if (document.getElementById("HEIGHTcontent") != undefined){
										document.getElementById("HEIGHTcontent").value = h;
									}
								}</script>';
	}
	else{
		$str = '';
	}
	
	return $str;
}


function swfEmbedToScriptForModuleContenu_flv($matches){
 
	$str = $matches[1]; 
	
	
	/*	
	$str='<embed height="400" type="application/x-shockwave-flash" pluginspage="https://get.adobe.com/flashplayer/" width="550" src="/custom/swf/fr/test(7).swf"></embed>';*/
	
	/* <embed pluginspage="http://www.macromedia.com/go/getflashplayer" src="/custom/video/luzenac/Firebrake.flv" type="application/x-shockwave-flash"></embed>*/ 
	$str = trim (str_replace ("<embed", "", $str));
	$str = trim (str_replace ("></embed>", "", $str));
	$aBits = explode(' ', $str); 
	
	if ($aBits){ // pas de match, on supprime la balise	
		$aAttribs = array();		
		foreach($aBits as $k => $sBit){	
		  
			if (preg_match('/^([a-z0-9]+)="([^"]+)".*$/msi', $sBit, $sSubBits)){
				$aAttribs[$sSubBits[1]]=$sSubBits[2];
			}		
		} 
		$aAttribs['url'] = $aAttribs['src'];	
		$aAttribs['name'] = basename($aAttribs['src']);
		$aAttribs['srcswf'] = $aAttribs['src'];				 
		$aAttribs['src'] = str_replace('.flv', '', $aAttribs['src']);	
		
		$aAttribs['thumb'] = basename($aAttribs['thumb']);	
		
		if ($aAttribs['loop'] == '') $aAttribs['loop'] = "true";
		($aAttribs['loop'] == "true") ? $aAttribs['_loop'] = 1 : $aAttribs['_loop'] = 0;
		$aAttribs['autostart'] = $aAttribs['play'] ; 
		if ($aAttribs['autostart'] == '') $aAttribs['autostart'] = "true";
		($aAttribs['autostart'] == "true") ? $aAttribs['_autostart'] = 1 : $aAttribs['_autostart'] = 0;
		
		
		if ($aAttribs['volume'] == '') $aAttribs['volume'] = "100";
		
		//pre_dump($aAttribs);
		
		if ( $aAttribs['height'] == '' || $aAttribs['width'] == '' ) {
			
			$selectedFile = $aAttribs['url'];
			
			if (is_file($_SERVER['DOCUMENT_ROOT'].$selectedFile)){
				$getID3 = new getID3;			
				$ThisFileInfo = $getID3->analyze($_SERVER['DOCUMENT_ROOT'].$selectedFile);	
				
				if ( $aAttribs['width'] == '') {
					if (isset($ThisFileInfo["flv"]["meta"]["onMetaData"]["width"])){
						$width = $ThisFileInfo["flv"]["meta"]["onMetaData"]["width"];
					}
					else{
						$width = $ThisFileInfo["video"]["resolution_x"];
					}
					$aAttribs['width'] = $width;
				}
				if ( $aAttribs['height'] == '') {
					if (isset($ThisFileInfo["flv"]["meta"]["onMetaData"]["height"])){
						$height = $ThisFileInfo["flv"]["meta"]["onMetaData"]["height"];
					}
					else{
						$height = $ThisFileInfo["video"]["resolution_y"];
					}
					$aAttribs['height'] = $height;
				}
			}
		}
		 
		
		/*$str = '<script type="text/javascript">swfSrcObject = "/custom/fr/video/'.$aAttribs['name'].'?embedUrl="+escape(document.location.href);swfSrcEmbed = "/custom/fr/video/'.$aAttribs['name'].'?embedUrl="+escape(document.location.href);flashvars = "embedUrl="+escape(document.location.href);AC_FL_RunContent( "codebase","https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0","width","'.$aAttribs['width'].'","height","'.$aAttribs['height'].'","src", "'.$aAttribs['src'].'", "quality","high","pluginspage","https://get.adobe.com/flashplayer/","align", "middle", "play", "true", "loop", "true", "scale", "showall", "wmode", "opaque", "devicefont", "false", "id", "'.basename($aAttribs['src']).'", "name", "'.basename($aAttribs['src']).'", "menu", "false", "allowFullScreen", "false", "allowScriptAccess","sameDomain","movie", "'.$aAttribs['src'].'", "salign", "", "flashvars", flashvars );</script>';*/
		
		$id_unique = date ("ymdhm").rand(0,1000);
		$str = '<script src="/backoffice/cms/js/AC_RunActiveContent.js" type="text/javascript"></script><script type="text/javascript">videoSrcObject = "/backoffice/cms/utils/scrubberLarge?_vidName='.$aAttribs['name'].'&_vidURL='.$aAttribs['url'].'&_phpURL=/backoffice/cms/utils/flvprovider.php&_vidThb=&_start=0&_end=0&_autostart='.$aAttribs['_autostart'].'&_onEnd=&_showUI=1&_loop='.$aAttribs['_loop'].'&_volume='.$aAttribs['volume'].'&_onRollOver=&embedUrl="+escape(document.location.href);videoSrcEmbed = "/backoffice/cms/utils/scrubberLarge?_vidName='.$aAttribs['name'].'&_vidURL='.$aAttribs['url'].'&_phpURL=/backoffice/cms/utils/flvprovider.php&_vidThb='.$aAttribs['thumb'].'&_start=0&_end=0&_autostart='.$aAttribs['_autostart'].'&_onEnd=&_showUI=1&_loop='.$aAttribs['_loop'].'&_volume='.$aAttribs['volume'].'&_onRollOver=&embedUrl="+escape(document.location.href);flashvars = "_vidName='.$aAttribs['name'].'&_vidURL='.$aAttribs['url'].'&_phpURL=/backoffice/cms/utils/flvprovider.php&_vidThb='.$aAttribs['thumb'].'&_start=0&_end=0&_autostart=0&_onEnd=&_showUI=1&_loop='.$aAttribs['_loop'].'&_volume=100&_onRollOver=&embedUrl="+escape(document.location.href);swfSrcFull = "/backoffice/cms/utils/scrubberLarge.swf";swfSrc = "/backoffice/cms/utils/scrubberLarge";AC_FL_RunContent( "codebase","https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,0,0",
	"width","'.$aAttribs['width'].'","height","'.$aAttribs['height'].'","src", swfSrc, "quality","high","pluginspage","https://get.adobe.com/flashplayer/",
	"align", "middle", "play", "true", "loop", "'.$aAttribs['loop'].'", "autostart", "'.$aAttribs['autostart'].'", "volume", "'.$aAttribs['volume'].'", "scale", "showall", "wmode", "opaque", "devicefont", "false",
	"id", "awsvid_'.$id_unique.'", "name", "awsvid_'.$id_unique.'", "menu", "false", "allowFullScreen", "false",
	"allowScriptAccess","sameDomain","movie", swfSrc, "salign", "", "flashvars", flashvars );function videoSizeUpdate(w, h){
			//alert(w+" / "+h);
			//document.getElementById("awsvid").width = w;
			//document.getElementById("awsvid").height = h;
			a = document.getElementsByTagName("embed");
			for (i in a){
				if (a[i].src != undefined){
					if (a[i].src == swfSrcFull){
						a[i].width = w;
						a[i].height = h; 
					}
				}
			}
			a = document.getElementsByTagName("object");
			for (i in a){
				if (a[i].id != undefined){
					if (a[i].id == "awsvid_'.$id_unique.'"){
						a[i].width = w;
						a[i].height = h;					
					}
				}
			}		
			if (document.getElementById("WIDTHcontent") != undefined){
				document.getElementById("WIDTHcontent").value = w;
			}
			if (document.getElementById("HEIGHTcontent") != undefined){
				document.getElementById("HEIGHTcontent").value = h;
			}
		}</script>';
		
		 
	}
	else{
		$str = '';
	}
	
	return $str;
}



//----------------------------------------
// si menu autoris 
// 1- pour le rang de cet utilisateur
// 2- pour cette fonctionnalit pour ce site
//----------------------------------------
function isAllowed ($search, $string)
{
	if (trim($search) == ""){
		return false;
	}
	elseif($search == $string){
		return true;
	}
	else{
		$aHayStack = explode(';', str_replace(',',';',$string));
		return in_array($search, $aHayStack);		
		//return strstr($string, $search);
	}
}

// retourne true ou false, si le browser match le media speficié
function matchMedia($sMedia){
	$sMedia = preg_replace('/([^,]+),.*/msi', '$1', $sMedia); 
	
	if (preg_match('/Mobi/si', $_SERVER['HTTP_USER_AGENT'])==1){ // browser mobile	
		if ($sMedia == 'handheld'){
			return true;
		}
		else{
			return false;
		}
	}
	else{// screen
		if (($sMedia == 'screen')	||	($sMedia == 'print')){
			return true;
		}
		else{
			return false;
		}	
	}
}


function compactJS($pContent) {
	$pContent = str_replace('\r\n','\n',$pContent);
	$pContent = preg_replace('/^\/\/.*/','',$pContent); // begining w/ //
	$pContent = preg_replace('/[^:]\/\/.*/','',$pContent); // not those http://
	$pContent = str_replace('\t','',$pContent);
	$pContent = str_replace('\n','',$pContent);
	$pContent = preg_replace('/\s+/',' ',$pContent);
	$pContent = preg_replace('/\s?([\{\};\=\)\/\+\*-])\s?/','$1',$pContent);
	return $pContent;
}

function fullDoctype($doctype, $lang){
	//$lang = strtoupper( $lang);
	$lang = 'EN'; // il semblerait que suel EN soit valide
	
	if ($doctype == 'XHTML 1.0 Strict'){
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//'.$lang.'" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang.'">';
	}
	elseif ($doctype == 'XHTML 1.1'){
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//'.$lang.'" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang.'">';
	}
	elseif ($doctype == 'XHTML 1.0 RDFa'){
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//'.$lang.'" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang.'">';
	}	
	elseif ($doctype == 'XHTML 1.0 Frameset'){
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//'.$lang.'" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang.'">';
	}
	elseif ($doctype == 'XHTML Mobile 1.0'){
		'<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//'.$lang.'" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang.'">';
	}
	elseif ($doctype == 'HTML 5'){
		return '<!DOCTYPE html>
<html manifest="/cache-' . $_SESSION['idSite'] . '.manifest">';
	}
	elseif ($doctype == 'XHTML 2.0'){
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 2.0//'.$lang.'" "http://www.w3.org/MarkUp/DTD/xhtml2.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang.'">';
	}	
	else{ //XHTML 1.0 Transitional
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//'.$lang.'" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang.'" lang="'.$lang.'">';
	}
}

// makeRandomKey
/**
 * Generate a random transaction_id, password or validation key
 *
 * Examples :
 * echo makeRandomKey();
 * > 51d8b448ad289a8b2ff50219ddd8e67936f4a555
 * echo makeRandomKey('numeric', 80);
 * > 13969129691829473350905578362711065674284852774190392980483833740698116793831161
 *
 * @param    Int        $type        The desired output type (basic, alpha, numeric, nozero, md5 or sha1)
 * @param    Bool        $len        The desired output length
 * @return    String        The random chain
 */
function makeRandomKey ($type='sha1', $len=20) {
	if (phpversion() >= 4.2)
		mt_srand();
	else    mt_srand(hexdec(substr(md5(microtime()), - $len)) & 0x7fffffff);

	switch ($type) {
		case 'basic':    return mt_rand();
				break;
		case 'alpha':
		case 'numeric':
		case 'nozero':    switch ($type) {
					case 'alpha':    $param = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
					case 'numeric':    $param = '0123456789';
							break;
					case 'nozero':    $param = '123456789';
							break;
				}
				$str = '';
				for ($i = 0; $i < $len; $i ++)
					$str .= substr($param, mt_rand(0, strlen($param) - 1), 1);
				return $str;
				break;
		case 'md5':    return md5(uniqid(mt_rand(), TRUE));
				break;
		case 'sha1':    return sha1(uniqid(mt_rand(), TRUE));
				break;
	}
}

function log_dump($oO){
	if (is_object($oO)){
		error_log(get_class($oO));
	}
	foreach ($oO as $key => $val){
		if (preg_match('/html/si', $key)==0){
			error_log( $key." => ".$val);
		}	
	}
}

function trace($str){
	echo $str;
}

function isFilled($str){
	$str = (string)strval($str);
	$str = trim($str);
	if (($str != '') && ($str != '-') && ($str != '-1') && ($str != 'n/a')){
		return true;
	}
	return false;
}

function returnIsFilled($str){
	$str = (string)strval($str);
	$str = trim($str);
	if (isFilled($str)){
		return $str;
	}
	return "";
}

function isFilledNotNull($str){
	$str = (string)strval($str);
	$str = trim($str);
	if (isFilled($str)){
		if ($str != '0'){
			return true;
		}
	}
	return false;
}

function returnIsFilledNotNull($str){
	$str = (string)strval($str);
	$str = trim($str);
	if (isFilledNotNull($str)){
		return $str;
	}
	return "";
}

function htmlFormat($str){ // formatage HTML pour e-mail
	// virer les espaces au début des lignes
	$str =  preg_replace('/\r\n[\s]+/msi', "\r\n", $str);
	// virer les espaces a la fin des lignes
	$str =  preg_replace('/[\s]+\r\n/msi', "\r\n", $str);
	
	// virer tout les retours
	$str =  preg_replace('/\r*\n*/msi', '', $str);
	
	// recreer des retours de lecture
	$str =  str_replace('<p', "\r\n<p", $str);
	$str =  str_replace('<tr', "\r\n<tr", $str);
	$str =  str_replace('<td', "\r\n<td", $str);
	$str =  str_replace('</p>', "</p>\r\n", $str);
	$str =  str_replace('</tr>', "</tr>\r\n", $str);
	$str =  str_replace('</td>', "</td>\r\n", $str);
	$str =  preg_replace('/<br[^>]*>/msi', "\r\n<br />\r\n", $str);
	
	// virer les espaces au début des lignes
	$str =  preg_replace('/\r\n[\s]+/msi', "\r\n", $str);
	// virer les espaces après les tags
	$str =  preg_replace('/>[\s]+([0-9a-z<])/msi', ">$1", $str);

	$str =  preg_replace('/(<a[^>]+>)([^<]+)(<\/a>)/msi', "$1\r\n$2\r\n$3\r\n", $str);
	
	// wordwrap de compatibilité
	$str =  wordwrap(trim($str), 78, "\r\n");
	
	return $str;
}

function html2text($str){// formatage TEXT pour e-mail
	// virer les espaces au début des lignes
	$str =  preg_replace('/\r\n[\s]+/msi', "\r\n", $str);
	// virer tout les retours
	$str =  preg_replace('/\r*\n*/msi', '', $str);
	
	// recreer des retours de lecture
	$str =  str_replace('<p', "\r\n<p", $str);
	$str =  str_replace('<tr', "\r\n<tr", $str);
	$str =  str_replace('</p>', "</p>\r\n", $str);
	$str =  str_replace('</tr>', "</tr>\r\n", $str);
	$str =  preg_replace('/<br[^>]*>/msi', "<br />\r\n", $str);

	// virer les tags
	$str = preg_replace( "/<style[^<>]*>.*<\/style>/msi", "", $str );
	$str = preg_replace( "/<[^<>]+>/msi", "", $str );	
	$str = preg_replace( "/<[^<>]+$/msi", "", $str );	
	
	
	// virer les espaces au début des lignes
	$str =  preg_replace('/\r\n[\s]+/msi', "\r\n", $str);
	// virer les espaces a la fin des lignes
	$str =  preg_replace('/[\s]+\r\n/msi', "\r\n", $str);
	$str = trim(html_entity_decode($str));
	// wordwrap de compatibilité
	$str =  wordwrap($str, 78, "\r\n")."\r\n";
	
	
	return $str;
}

function getGreater($int1, $int2){
	if ($int1 > $int2){
		return $int1;
	}
	else{
		return $int2;
	}
}

function dumpObjLite($oO){
	echo "<p>\n";
	echo "<br />\n";
	echo "<strong>".get_class($oO)."</strong><br />\n";
	//print_r($oO);
	foreach ($oO as $key => $val){
		if (!preg_match("/html/msi", $key)){
			echo $key." => ".$val."<br />\n";
		}	
	}
	echo "<br />\n";
	echo "</p>\n";
}

function is_session($getParamName, $notempty=true){
	if ($notempty){
		if (isset($_SESSION[$getParamName]) && ($_SESSION[$getParamName] != "")){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		if (isset($_SESSION[$getParamName])){
			return true;
		}
		else{
			return false;
		}
	}
}

function is_as_get($postedvar){
	if ((is_get($postedvar) == true) && ($_GET[$postedvar] != "undefined")){
		return true;
	}
	else{
		return false;
	}
}


function is_get($getParamName, $notempty=true){
	if ($notempty){
		if (isset($_GET[$getParamName]) && ($_GET[$getParamName] != "")){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		if (isset($_GET[$getParamName])){
			return true;
		}
		else{
			return false;
		}
	}
}

function is_post($getParamName, $notempty=true){
	if ($notempty){
		if (isset($_POST[$getParamName]) && ($_POST[$getParamName] != "")){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		if (isset($_POST[$getParamName])){
			return true;
		}
		else{
			return false;
		}
	}
}
function pre_dump($data){
	echo "<pre>\n";
	var_dump($data);
	echo "</pre>\n";
}

function textToLink($text){
	$text = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $text);
	$text = ereg_replace('[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]+', "<a href=\"mailto:\\0\">\\0</a>", $text);
	return $text;
}


// viewArray
/**
 * Render a visual display of an array
 *
 * @param	Array	$arr		The array to render
 * @param	String	$title		A title to identify the array
 * @param	Boolean	$echo		Whether to directly display the render or to hold it for display in debugbox
 */
function viewArray ($arr, $title='array', $echo=false) {
	if (is_array($arr)){
		$output = '<table class="debug_array">';
		$output .= '<tr bgcolor="#DDDDDD">';
		$output .= '<td colspan="2" class="common vertpadding"><b>&nbsp;'.$title.' </b></td>';
		$output .= '</tr><tr bgcolor="#DDDDDD">';
		$output .= '<td class="common_small">&nbsp;KEY </td><td class="common_small">&nbsp;VAL </td>';
		foreach ($arr as $key1 => $elem1) {
			$output .= '</tr><tr>';
			$output .= '<td class="common_small" valign="top">&nbsp;'.$key1.'&nbsp;&nbsp;&nbsp;</td>';
			if (is_array($elem1))
				$output .= extArray($elem1);
			else	$output .= '<td class="common_small" width="90%">&nbsp;'.$elem1.'&nbsp;</td>';
		}
		$output .= '</tr>';
		$output .= '</table>';
	
		echo $output;
	}
}

// extArray
/**
 * Render sub arrays of an array
 *
 * @param	Array	$arr		The sub array to render
 * @return	String			The visual display of the subarray
 */
function extArray ($arr) {
	$output = '<td>';
	$output .= '<table cellpadding="0" class="debug_array" border="1">';
	$output .= '<tr bgcolor="#DDDDDD">';
	$output .= '<td class="common_small">&nbsp;KEY </td><td class="common_small">&nbsp;VAL </td>';
	foreach ($arr as $key => $elem) {
		$output .= '</tr><tr>';
		$output .= '<td class="common_small" valign="top">&nbsp;'.$key.'&nbsp;&nbsp;&nbsp;</td>';
		if (is_array($elem))
			    $output .= extArray($elem);
		else	$output .= '<td class="common_small" width="90%">&nbsp;'.htmlspecialchars($elem).'&nbsp;</td>';
	}
	$output .= '</tr>';
	$output .= '</table>';
	$output .= '</td>';

	return (String) $output;
}




// remplacement de cote '
function replaceQuote($sString)
{
	$sString = str_replace( "'", "`", $sString);

	return $sString;
}

// remplacement de cote '
function replaceQuote2($sString)
{
	$sString = str_replace( "`", "'", $sString);
	$sString = str_replace( "’", "'", $sString);
	$sString = str_replace("<","&lt;", $sString);
	$sString = str_replace(">","&gt;", $sString);
	$sString = str_replace("'","&#39;", $sString);
	$sString = str_replace("\"","&quot;", $sString);
	$sString = str_replace("\\\\","&#92;", $sString);
	$sString = str_replace("\\","", $sString);
	//$sString = str_replace("\n","<br>", $sString);

	return $sString;
}


function MiseEnMaj($sChaine) {
	return ucfirst ( strtolower($sChaine) );
}


// Transforme les retour chariot \n en <br>
function RetourLigne($sChaine) { // converti les \n en <br>
	return str_replace( "\n", "<br>"."\n", $sChaine );
}


// enlève dans une chaine de chemin 
// - le site de travail
// - aere les /
// cette chaine peut alors s'insérer dans une colonne 
// (si elle est trop longue elle n'élargira pas la colonne)
function cheminAere($sChaine) {
 
	$sChaine = str_replace("/".$_SESSION['site_travail']."", "", $sChaine);
	$sChaine = str_replace("/", " / ", $sChaine);

	return($sChaine);
}

function replaceBadCar()
{
	// remplacement des quotes office par des quotes propres
	// --- par extension on va traiter ausis les chars bizarres
	// --- non-suporté par spaw : oe, ae, dash, etc...

	$aBadChars = Array("’", "—", "–", "œ", 'Œ', "æ", "\'", "\\\"", "“", "”");
	$aGoodChars = Array("\'", "-", "-", "&oelig;", '&OElig;', "&aelig;", "'", "\"", "\"", "\"");

	// le petit dash devient un tiret normal, et non &#8211;
	// le grand dash devient un tiret normal, et non &#8212;
	
	foreach ($_REQUEST as $sReqKey => $sReqValue) {
	   $_REQUEST[$sReqKey] = str_replace($aBadChars, $aGoodChars, $sReqValue);
	}
	foreach ($_POST as $sReqKey => $sReqValue) {
	   $_POST[$sReqKey] = str_replace($aBadChars, $aGoodChars, $sReqValue);
	}
	foreach ($_GET as $sReqKey => $sReqValue) {
	   $_GET[$sReqKey] = str_replace($aBadChars, $aGoodChars, $sReqValue);
	}
}

function replaceBadCarsInStr($str)
{
	// remplacement des quotes office par des quotes propres
	// --- par extension on va traiter ausis les chars bizarres
	// --- non-suporté par spaw : oe, ae, dash, etc...

	$aBadChars = Array("’", "—", "–", "œ", 'Œ', "æ", "\'", "\\\"", "“", "”");
	$aGoodChars = Array("\'", "-", "-", "&oelig;", "&OElig;", "&aelig;", "'", "\"", "\"", "\"");

	// le petit dash devient un tiret normal, et non &#8211;
	// le grand dash devient un tiret normal, et non &#8212;
	
	$str = str_replace($aBadChars, $aGoodChars, $str);
	return $str;
}

// renvoie l'information paire ou impaire
function htmlImpairePaire($i)
	{
	if (($i % 2)==1)
		{
		return("impair");
		}
	else
		{
		return("pair");
		}
	}

// renvoie la valeur $couleur1 si $i est impaire, $couleur2 sinon
function htmlSiImpaireCouleur($i,$couleur1,$couleur2)
	{
	if (($i % 2)==1)
		{
		return($couleur1);
		}
	else
		{
		return($couleur2);
		}
	}


// les cases à cocher ne sont pas postés si elles ne sont pas sélectionnées
// cette fonction renvoie 0 dans le cas où les cases ne sont pas postées pour l'enregistrement en bdd
function postCC($postCC)
{
	if ($postCC == "") $cc = 0;
	else $cc = $postCC;
	
	return($cc);
}

//--------------------------------------
// construction dynamique d'un tableau
// affichant les données aData selon un nombre de colonne sécifié
// calcul des <tr> </tr> <td> </td> en fonction du modulo du nombre de colonnes spécifié
//--------------------------------------
function makeTableauModulo($aData, $eCol) {

	$sTab = "";

	$sTab.= "<table border=\"0\" width=\"100%\">";

	for ($p=0; $p<sizeof($aData); $p++) 
	{
		// découpage des dates en x colonnes
		$eMod = $eCol;
		$modulo = $p % $eMod;
	
		// date à afficher dans le tableau
		$sData = $aData[$p];
	
		if ($modulo == 0 && $p == 0) {
			// occurence modulo et première ligne -> début ligne + début colonne + fin colonne
	
			$sTab.= "<tr><td>".$sData;
	
	//		print("<br>DEBUT LIGNE");
	//		print("<br>DEBUT COLONNE");
	//		print("<br>FIN COLONNE");
	
			$sTab.= "</td>";
	
		} else if ($modulo == 0 && $p > 0) {
			// occurence modulo et ligne suivante -> fin ligne + début colonne + fin colonne
	
			$sTab.= "</tr><tr><td>".$sData;
	
	//		print("<br>FIN LIGNE");
	//		print("<br>DEBUT LIGNE");
	//		print("<br>DEBUT COLONNE");
	//		print("<br>FIN COLONNE");
	
			$sTab.= "</td>";
	
		} else if ($modulo != 0 && ($p != sizeof($aData) -1)) {
			// pas d'occurence modulo et pas la fin des afffichages -> début colonne + fin colonne
	
			$sTab.= "<td>".$sData;
	
	//		print("<br>DEBUT COLONNE");
	//		print("<br>FIN COLONNE");
	
			$sTab.= "</td>";
	
		} else if ($modulo != 0 && ($p == sizeof($aData) -1)) {
			// pas d'occurence modulo et fin des affichages -> fin colonne + fin ligne
			$colspan = $eMod - $eCol;
	
			$sTab.= "<td colspan=\"".$colspan."\">".$sData;
	
	//		print("<br>DEBUT COLONNE");
	//		print("<br>FIN COLONNE");
	//		print("<br>FIN LIGNE");
	
			$sTab.= "</td></td></tr>";
		}
	}

	$sTab.= "</table>";

	return $sTab;
}

function encodePath($chainePath){
	return implode("/", array_map("rawurlencode", explode("/", $chainePath)));
}

function accent2Html($strAccents, $reverse=false){

$htmlcodes = array(
"&Agrave;", 
"&Aacute;", 
"&Acirc;", 
"&Atilde;", 
"&Auml;", 
"&Aring;", 
"&AElig;", 
"&Ccedil;", 
"&Egrave;", 
"&Eacute;", 
"&Ecirc;", 
"&Euml;", 
"&Igrave;", 
"&Iacute;", 
"&Icirc;", 
"&Iuml;", 
"&ETH;", 
"&Ntilde;", 
"&Ograve;", 
"&Oacute;", 
"&Ocirc;", 
"&Otilde;", 
"&Ouml;", 
"&times;", 
"&Oslash;", 
"&Ugrave;", 
"&Uacute;", 
"&Ucirc;", 
"&Uuml;", 
"&Yacute;", 
"&THORN;", 
"&szlig;", 
"&agrave;", 
"&aacute;", 
"&acirc;", 
"&atilde;", 
"&auml;", 
"&aring;", 
"&aelig;", 
"&ccedil;", 
"&egrave;", 
"&eacute;", 
"&ecirc;", 
"&euml;", 
"&igrave;", 
"&iacute;", 
"&icirc;", 
"&iuml;", 
"&eth;", 
"&ntilde;", 
"&ograve;", 
"&oacute;", 
"&ocirc;", 
"&otilde;", 
"&ouml;", 
"&divide;", 
"&oslash;", 
"&ugrave;", 
"&uacute;", 
"&ucirc;", 
"&uuml;", 
"&yacute;", 
"&thorn;", 
"&yuml;",
'&oelig;',
'&OElig;',
'&aelig;');

$replaces = array(
"À", 
"Á", 
"Â", 
"Ã", 
"Ä", 
"Å", 
"Æ", 
"Ç", 
"È", 
"É", 
"Ê", 
"Ë", 
"Ì", 
"Í", 
"Î", 
"Ï", 
"Ð", 
"Ñ", 
"Ò", 
"Ó", 
"Ô", 
"Õ", 
"Ö", 
"×", 
"Ø", 
"Ù", 
"Ú", 
"Û", 
"Ü", 
"Ý", 
"Þ", 
"ß", 
"à", 
"á", 
"â", 
"ã", 
"ä", 
"å", 
"æ", 
"ç", 
"è", 
"é", 
"ê", 
"ë", 
"ì", 
"í", 
"î", 
"ï", 
"ð", 
"ñ", 
"ò", 
"ó", 
"ô", 
"õ", 
"ö", 
"÷", 
"ø", 
"ù", 
"ú", 
"û", 
"ü", 
"ý", 
"þ", 
"ÿ",
'œ',
'Œ',
'æ');

	if ($reverse==false){
		return str_replace($replaces, $htmlcodes, $strAccents);
	}
	else{// html 2 accents
		return str_replace($htmlcodes, $replaces, $strAccents);
	}
}


function noAccent($texte){

	$accent='ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËéèêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ °%';
	$noaccent='AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn---';
	$texte = strtr($texte,$accent,$noaccent);
	return $texte;

} 

function strtoupperAccent($texte){

	$do='àáâãäåòóôõöøéèêëçìíîïùúûüÿñ';
	$up='ÀÁÂÃÄÅÒÓÔÕÖØÉÈÊËÇÌÍÎÏÙÚÛÜYÑ';
	$texte = strtr(strtoupper($texte),$do,$up);
	return $texte;

}

function isEmail($email)
{ 
 
   //$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$#i';
    $Syntaxe="/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$/"; 
   //$Syntaxe='#^[A-Z]+\@[A-Z]+$#';
   //$Syntaxe = '#^[a-zA-Z]+[a-zA-Z\\-0-9\\.]+@(?:[a-zA-Z\\-0-9]+\\.)+[a-zA-Z]+$#';

   if(preg_match($Syntaxe,$email))
      return true;
   else
     return false;
}

function removeXitiForbiddenChars($strChars){
	$forbidden = array(" ", "/", "'", "\"", "?", "!", ",", ";", "(", ")", "@", "©");
	$replaces = array("_", "_", "_", "", ".", ".", ".", ".", "", "", "a", "c");
	return str_replace($forbidden, $replaces, removeAccents($strChars));
}


function regionDateBO($strDate){
	if($_SESSION['BO']['site_langue']=='fr'){
		$sDateformat = "DDMMYYYY";
	}
	else{
		$sDateformat = "YYYYMMDD";
	}
	return  regionDate($strDate,$sDateformat);
}

function regionDateFO($strDate){
	if($_SESSION['site_langue']=='fr'){
		$sDateformat = "DDMMYYYY";
	}
	else{
		$sDateformat = "YYYYMMDD";
	}
	return  regionDate($strDate,$sDateformat);
}

function regionDate($strDate,$sDateformat){
	if (preg_match("/([0-9]{4})(\/|-)([0-9]{1,2})(\/|-)([0-9]{1,2})/msi", $strDate, $regs)) {
		//echo "date is YYYYMMDD";
		// date is YYYYMMDD
		if ($sDateformat == "DDMMYYYY"){
			return $regs[5].$regs[4].$regs[3].$regs[2].$regs[1];
		
		}
		elseif ($sDateformat == "YYYYMMDD"){
			return $regs[1].$regs[2].$regs[3].$regs[4].$regs[5];
		
		}
		else{
			return $strDate;
		}
	}
	elseif (preg_match("/([0-9]{2})(\/|-)([0-9]{1,2})(\/|-)([0-9]{1,4})/msi", $strDate, $regs)) {
		//echo "date is DDMMYYYY";
		// date is DDMMYYYY
		if ($sDateformat == "YYYYMMDD"){
			return $regs[5].$regs[4].$regs[3].$regs[2].$regs[1];
		
		}
		elseif ($sDateformat == "DDMMYYYY"){
			return $regs[1].$regs[2].$regs[3].$regs[4].$regs[5];
		}
		else{
			return $strDate;
		}
	}
	else{
		//echo "date is ???????";
		return $strDate;
	}
}

function DDMMYYYYtoEnglish($strDate){

//$sDateUS = ereg_replace("^(\d{2})/(\d{2})/(\d{4})$", "\\3/\\2/\\1", $strDate);
$sDateUS = ereg_replace("(.+)/(.+)/(.+)", "\\3/\\2/\\1", $strDate);

$eTimeStamp = strtotime($sDateUS);
$sLongEnglishDate = date ( r , $eTimeStamp);
return ereg_replace("(.*)00:00:00.*", "\\1", $sLongEnglishDate);

}

function DDMMYYYYtoYYYYMMDD($strDate){
	$sDateUS = ereg_replace("([0-9]{2})(/|-)([0-9]{2})(/|-)([0-9]{4})", "\\5\\2\\3\\4\\1", $strDate);
	//$sDateUS = ereg_replace("(.+)/(.+)/(.+)", "\\3/\\2/\\1", $strDate);	
	return $sDateUS;
}

function YYYYMMDDtoDDMMYYYY($strDate){
	$sDateFR = ereg_replace("([0-9]{4})(/|-)([0-9]{2})(/|-)([0-9]{2})", "\\5\\2\\3\\4\\1", $strDate);
	//$sDateFR = ereg_replace("(.+)/(.+)/(.+)", "\\3/\\2/\\1", $strDate);	
	return $sDateFR;
}

function ouinon($eLib) {
	$sLib = "";
	if ($eLib == 0) $sLib = "non";
	else if ($eLib >= 1) $sLib = "oui";
	return $sLib;
}

// supprime tous les caractères spéciaux
function text2url($chaine) {
	$chaine = str_replace ("\r", "", $chaine);
	$chaine = str_replace ("\n", "", $chaine); 
	return removeXitiForbiddenChars(html2text(noAccent($chaine)));
}

// Fonction coupant à x caractère
function truncate($chaine,$debut,$max) {
	if (strlen($chaine) >= $max) {
		$chaine = substr($chaine, $debut, $max);
		$espace = strrpos($chaine, " ");
		$chaine = substr($chaine, $debut, $espace);
		return $chaine."...";
	} else {
		return $chaine;
	}	
	
}

function formatFileSize ($_size) {
	if ($_size>1073741824)
		return round(($_size / 1073741824), 2).'GB';
	elseif ($_size>1048576)
		return round(($_size / 1048576), 2).'MB';
	elseif ($_size>1024)
		return round(($_size / 1024), 2).'KB';
	else	return $_size.'B';
}


/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string  $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param mixed $ending If string, will be used as Ending and appended to the trimmed string. Can also be an associative array that can contain the last three params of this method.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 */

function truncateHTML($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
	if (is_array($ending)) {
		extract($ending);
	}
	if ($considerHtml) {
		if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		$totalLength = mb_strlen($ending);
		$openTags = array();
		$truncate = '';
		preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
		foreach ($tags as $tag) {
			if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
				if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
					array_unshift($openTags, $tag[2]);
				} else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
					$pos = array_search($closeTag[1], $openTags);
					if ($pos !== false) {
						array_splice($openTags, $pos, 1);
					}
				}
			}
			$truncate .= $tag[1];

			$contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
			if ($contentLength + $totalLength > $length) {
				$left = $length - $totalLength;
				$entitiesLength = 0;
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
					foreach ($entities[0] as $entity) {
						if ($entity[1] + 1 - $entitiesLength <= $left) {
							$left--;
							$entitiesLength += mb_strlen($entity[0]);
						} else {
							break;
						}
					}
				}

				$truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
				break;
			} else {
				$truncate .= $tag[3];
				$totalLength += $contentLength;
			}
			if ($totalLength >= $length) {
				break;
			}
		}

	} else {
		if (mb_strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = mb_substr($text, 0, $length - strlen($ending));
		}
	}
	if (!$exact) {
		$spacepos = mb_strrpos($truncate, ' ');
		if (isset($spacepos)) {
			if ($considerHtml) {
				$bits = mb_substr($truncate, $spacepos);
				preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
				if (!empty($droppedTags)) {
					foreach ($droppedTags as $closingTag) {
						if (!in_array($closingTag[1], $openTags)) {
							array_unshift($openTags, $closingTag[1]);
						}
					}
				}
			}
			$truncate = mb_substr($truncate, 0, $spacepos);
		}
	}

	$truncate .= $ending;

	if ($considerHtml) {
		foreach ($openTags as $tag) {
			$truncate .= '</'.$tag.'>';
		}
	}

	return $truncate;
}

?>