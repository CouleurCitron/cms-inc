<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if (!defined('DEF_DASH_INSTEAD_OF_UNDERSCORE')){
	define ("DEF_DASH_INSTEAD_OF_UNDERSCORE", "true"); // - ou _ ?	
}
/*
function unlinkRecursive($dir, $deleteRootToo)
function isDownloadable($file){
function removeToSlashDir($str){
function removeAccents($strAccents){
function removeForbiddenChars($strChars, $bUseDashInsteadOfUnderScore=DEF_DASH_INSTEAD_OF_UNDERSCORE){
function removeExtraSpaces($str){
function accentPathToStrictPath($strChars){
function replaceSymbole($strChars){
function dotNotOverWrite($sFile){
function killDir($dirChemin){
function dirExists($dirChemin, $mod=0775) {	
function getKnownExt() {
function dirIsValid($dirName, $filters) {
function fileIsValid($fileName, $filters) {
function isValidEntry($entry, $type) {
function getSousElements($dirName, $type) {
function getSSFiles($dirName) {
function getSSDirs($dirName) {
function cc_mime_content_type($sFile){
*/

function unlinkRecursive($dir, $deleteRootToo)
{
    if(!$dh = @opendir($dir))
    {
        return;
    }
    while (false !== ($obj = readdir($dh)))
    {
        if($obj == '.' || $obj == '..')
        {
            continue;
        }

        if (!@unlink($dir . '/' . $obj))
        {
            unlinkRecursive($dir.'/'.$obj, true);
        }
    }

    closedir($dh);
   
    if ($deleteRootToo)
    {
        @rmdir($dir);
    }
   
    return;
} 

function isDownloadable($file, $aWhiteListCustom = array()){
	
	// à definir dans le fichier de config, s'il y a d'autres téléchargement autres que dans  'content', 'custom', 'documents'
	if (defined ('DEF_AWHITELIST')) {
		$aWhiteListCustom = explode(',', DEF_AWHITELIST);
	}
	else {
		$aWhiteListCustom = array();
	}

	$aWhiteList = array('content', 'custom', 'documents');
	
	if (sizeof($aWhiteListCustom) > 0) {
		$aWhiteList = array_merge ($aWhiteList, $aWhiteListCustom);
	}  
	 
	
	if (strpos($file, $_SERVER['DOCUMENT_ROOT'])===false && sizeof($aWhiteListCustom) == 0){
		 
		return false;
	}
	else{
		foreach($aWhiteList as $k => $sDir){  
			if (strpos($file, $_SERVER['DOCUMENT_ROOT'].'/'.$sDir)!==false){ 
				return true;
			} 
		}
		
		foreach($aWhiteListCustom as $k => $sDir){  
			if (strpos($file, $sDir)!==false){ 
				return true;
			} 
		}		
	}	
	return false;
}

function removeToSlashDir($str){
	$str = preg_replace('/\/[^\/]+\/\.\.\//', '/', $str, 1);	
	if (preg_match('/\/[^\/]+\/\.\.\//', $str)){
		$str = removeToSlashDir($str);
	}
	return $str;
}

function removeAccents($strAccents){
	$accents = array("é", "è", "ê", "à", "ù", "ô", "ö", "â", "ä", "ë", "ï", "î", "ü", "û", "ç", "È", "É", "Ê", "Ë", "À", "ñ", "á", "í", "ó", "ú", "Ñ", "Á", "Í", "Ó", "Ú");
	$replaces = array("e", "e", "e", "a", "u", "o", "o", "a", "a", "e", "i", "i", "u", "u", "c", "E", "E", "E", "E", "A", "n", "a", "i", "o", "u", "N", "A", "I", "O", "U");
	return str_replace($accents, $replaces, $strAccents);
}

function removeForbiddenChars($strChars, $bUseDashInsteadOfUnderScore=DEF_DASH_INSTEAD_OF_UNDERSCORE){
	//echo 'removeForbiddenChars('.$strChars.')';
	$strChars = preg_replace('/<[^>]+>/', '', $strChars);
	$strChars = html_entity_decode($strChars);
	
	$strChars = russianToLatin(entitiesToUtf8($strChars)); // pour le russe &#xxxx; => uft8, puis russe utf8 => latin
	
	$forbidden = array(" ", "/", "'", "\"", "?", "!", ",", ";", ":", "(", ")", "@", "®", "™", html_entity_decode("&nbsp;"), "«", "»", "¿", "¡", "?", "?");
	$replaces = array( "_", "-", "_",  "",  ".", ".", ".", ".", ".", "",  "",  "a",  "",  "", "_",                          "",  "",  "",  "",  "",  "");
	$strChars = str_replace($forbidden, $replaces, removeAccents($strChars)); 
	 
	if ((string)$bUseDashInsteadOfUnderScore==='true'){
		$strChars = str_replace('_', '-', $strChars);
	}
	else{
		$strChars = str_replace('-', '_', $strChars);
	}
	return $strChars;
}

function removeExtraSpaces($str){
	return preg_replace('/\s+/', ' ', $str);
}

function accentPathToStrictPath($strChars){
	$forbidden = array(" ", "/", "%20", "%E0", "%E8", "%E9", "%EA",	"'", "\"", "?", "!", ",", ";", ":", "(", ")", "@");
	$replaces = array("_", "/", "_", "a", "è", "é", "ê", "_", "", ".", ".", ".", ".", ".", "", "", "a");
	return str_replace($forbidden, $replaces, removeAccents($strChars));
}

function replaceSymbole($strChars){
	$forbidden = array("%u20AC", "%u201A", "%u0192", "%u201E", "%u2026", "%u2020", "%u2021", "%u02C6", "%u2030", "%u0160", "%u2039", "%u0152", "%u017D", "%u2018", "%u2019", "%u201C", "%u201D", "%u2022", "%u2013" , "%u2014", "%u02DC", "%u2122", "%u0161", "%u203A", "%u0153", "%u017E", "%u0178");
	$replaces  = array("€"     , "‚"     , "%u0192", "„"     , "%u2026", "†"     , "‡"     , "ˆ"     , "‰"     , "Š"     , "‹"     , "Œ"     , "%u017D", "'"     , "'"     , "\""    , "\""    , "%u2022", "–"      , "—"     , "˜"     , "%u2122", "š"     , "›"     , "œ"     , "%u017E", "Ÿ"     );
	return str_replace($forbidden, $replaces, $strChars);
}

function dotNotOverWrite($sFile){
	if (is_file($sFile)){
		$attempt = 1;
		$sNewFile = $sFile;
		while (is_file($sNewFile)){			
			$sNewFile = ereg_replace("(.*)\.([^\.]{3,4})$", "\\1_".$attempt.".\\2",$sFile);
			$attempt++;
		}
		return $sNewFile;
	}
	else{
		return $sFile;
	}	
}

function killDir($dirChemin){
	//echo "killDir(".$dirChemin.")<br />";
	// les chemin peuvent etre en absolu du file system server ou en absolu du site
	if (strpos($dirChemin, $_SERVER['DOCUMENT_ROOT']) !== false){
		$dirCheminTested = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dirChemin);
	}
	else{
		$dirCheminTested = $dirChemin;
	}
	
	if ($dir = @opendir($dirChemin)) {
		while (($file = readdir($dir)) !== false) {			
			if ((is_dir($dirChemin."/".$file)) && ($file != ".") && ($file != "..")) { // dir
				killDir($dirChemin."/".$file);			
			}
			elseif ((is_file($dirChemin."/".$file)) && ($file != ".") && ($file != "..")) { // file
				//echo "unlink ".$dirChemin."/".$file."<br />";
				unlink($dirChemin."/".$file);			
			}			
		}		
	}
	closedir($dir);
	//echo "rmdir ".$dirChemin."<br />";
	return rmdir($dirChemin);
}

function dirExists($dirChemin, $mod=0775) {	
	// les chemin peuvent etre en absolu du file system server ou en absolu du site
	if (strpos($dirChemin, $_SERVER['DOCUMENT_ROOT']) !== false){	
		// trick avec * à cause de DOCUMENT_ROOT sur lien symbolique (hephaistos)
		$dirCheminTested = preg_replace('/.*\*/', '', str_replace($_SERVER['DOCUMENT_ROOT'], '*', $dirChemin));	
	}
	else{
		$dirCheminTested = $dirChemin;
	}
	
	if (substr($dirCheminTested, 0, 1)!='/'){
		$dirCheminTested = '/'.$dirCheminTested;
	}

	$champs = explode ("/", $dirCheminTested); 
	$boucle=1;
	$testPath="/".$champs[$boucle];
	while ($boucle < (sizeof($champs))) {
		//echo "test sur ".$_SERVER['DOCUMENT_ROOT'].$testPath."<br />\n";
		//error_log($_SERVER['DOCUMENT_ROOT'].$testPath . ' \\ ' .$dirCheminTested . ' - ' .$_SERVER['PHP_SELF']);
		if(!is_dir($_SERVER['DOCUMENT_ROOT'].$testPath))
			{
			mkdir ($_SERVER['DOCUMENT_ROOT'].$testPath);
			}
		$boucle++;
		if (isset($champs[$boucle])){
			$testPath=$testPath."/".$champs[$boucle]; 
		}
	} 
	
	@chmod($_SERVER['DOCUMENT_ROOT'].$dirCheminTested, $mod);
	
	// test final de return
	if (is_dir($_SERVER['DOCUMENT_ROOT'].$dirCheminTested)){
		return true;
	}
	else{
		return false;
	}	
}

function getKnownExt() {
	return array(asp,swf,gif,bmp,png,bmp,css,csv,doc,exe,fla,folder,htm,jpg,js,lnk,midi,mov,mp3,pdf,php,ppt,psd,rm,rtf,txt,ukn,up,wmv,xml,zip);
}

function dirIsValid($dirName, $filters) {
	$result = true;
	global $_SERVER;
	foreach($filters as $ctrl => $type) {
		if($type=='exact') {
			if(array_pop(split('/',$dirName))==$ctrl)
				$result = false;
		} elseif($type=='pattern') {
			if(preg_match("/".$ctrl."/",$dirName))
				$result = false;
		}
	}
	return $result;
}

function fileIsValid($fileName, $filters) {
	$result = true;
	foreach($filters as $ctrl => $type) {
		if($type=='exact') {
			if(array_pop(split('/',$fileName))==$ctrl)
				$result = false;
		} elseif($type=='pattern') {
			if(preg_match($ctrl,$fileName))
				$result = false;
		}
	}
	return $result;
}

function isValidEntry($entry, $type) {
	global $fileExcludeList;
	global $dirExcludeList;
	$return  = false;
	if($type=='dir') {
		$return = dirIsValid($entry,$dirExcludeList);
	} elseif($type=='file') {
		$return = fileIsValid($entry,$fileExcludeList);
	}
	return $return;
}

function getSousElements($dirName, $type) {
	$result = array();
	if(!is_dir($dirName))
		return false;
	$dirNameFh = opendir($dirName);
	//if ($dirNameFh) echo "ok"; else echo "ko";
	while($entry = readdir($dirNameFh)) {
		if( ( filetype($dirName.'/'.$entry) == $type ) && (isValidEntry($dirName.'/'.$entry, $type)) )
			array_push($result, $entry);
	}
	sort($result);
	return $result;
	
}

function getSSFiles($dirName) {
	return getSousElements($dirName, 'file');
}

function getSSDirs($dirName) {
	return getSousElements($dirName, 'dir');
}

function cc_mime_content_type($sFile){
	if (function_exists("mime_content_type")) {
		return mime_content_type($file);
	}
	elseif (function_exists("finfo_open")) { 
		$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
		
		if (!$finfo) {
			return 'unknown/unknown';
		}
		
		return finfo_file($finfo, $sFile);
	}
	else{
		return 'unknown/unknown';
	}	
}
?>