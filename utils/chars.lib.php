<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if (!function_exists('http_build_query')) {
    function http_build_query($data, $prefix='', $sep='', $key='') {
        $ret = array();
        foreach ((array)$data as $k => $v) {
            if (is_int($k) && $prefix != null) {
                $k = urlencode($prefix . $k);
            }
            if ((!empty($key)) || ($key === 0))  $k = $key.'['.urlencode($k).']';
            if (is_array($v) || is_object($v)) {
                array_push($ret, http_build_query($v, '', $sep, $k));
            } else {
                array_push($ret, $k.'='.urlencode($v));
            }
        }
        if (empty($sep)) $sep = ini_get('arg_separator.output');
        return implode($sep, $ret);
    }
}

function utf8dec ( $s_String )
    {
    $s_String = html_entity_decode(htmlentities($s_String." ", ENT_COMPAT, 'UTF-8'));
    return substr($s_String, 0, strlen($s_String)-1);
    }
	
function fixed_utf8($str){
	$str = str_replace('™', 'XX-TMARK-XX', $str);
	$str = str_replace('€', 'XX-EURO-XX', $str);
	$str = str_replace('©', 'XX-COPY-XX', $str);
	$str = str_replace('®', 'XX-REGIS-XX', $str);	
	
	$str = str_replace(array('&trade;', '&#153;'), 'XX-TMARK-XX', $str);
	$str = str_replace(array('&copy;', '&#169;'), 'XX-COPY-XX', $str);
	$str = str_replace(array('&reg;', '&#174;'), 'XX-REGIS-XX', $str);
	$str = str_replace('&euro;', 'XX-EURO-XX', $str);
	
	$str = utf8_encode($str);
	$str = str_replace('XX-TMARK-XX', chr(hexdec('E2')).chr(hexdec('84')).chr(hexdec('A2')), $str);
	$str = str_replace('XX-EURO-XX', chr(hexdec('E2')).chr(hexdec('82')).chr(hexdec('AC')), $str);
	$str = str_replace('XX-COPY-XX', chr(hexdec('C2')).chr(hexdec('A9')), $str);
	$str = str_replace('XX-REGIS-XX', chr(hexdec('C2')).chr(hexdec('AE')), $str);
	
	return $str;
}

function fixed_latin($str){
	$str = str_replace(chr(hexdec('E2')).chr(hexdec('84')).chr(hexdec('A2')), 'XX-TMARK-XX', $str);
	$str = str_replace(chr(hexdec('E2')).chr(hexdec('82')).chr(hexdec('AC')), 'XX-EURO-XX', $str);
	$str = str_replace(chr(hexdec('C2')).chr(hexdec('A9')), 'XX-COPY-XX', $str);
	$str = str_replace(chr(hexdec('C2')).chr(hexdec('AE')), 'XX-REGIS-XX', $str);			
	
	//$str = utf8_decode($str);
	$str = iconv("UTF-8", "ISO-8859-1//IGNORE", $str);
	
	$str = str_replace('XX-TMARK-XX', '™', $str);
	$str = str_replace('XX-EURO-XX', '€', $str);
	$str = str_replace('XX-COPY-XX', '©', $str);
	$str = str_replace('XX-REGIS-XX', '®', $str);	
	
	//error_log('fixed_latin');
	//error_log($str);
	return $str;
}

function speUtf8ToHtml($str){
	//mail('pierre@couleur-citron.com', 'xml', $str);
	/*
	$str = str_replace(chr(hexdec('E2')).chr(hexdec('84')).chr(hexdec('A2')), htmlentities('™'), $str);
	$str = str_replace(chr(hexdec('E2')).chr(hexdec('82')).chr(hexdec('AC')), htmlentities('€'), $str);
	$str = str_replace(chr(hexdec('C2')).chr(hexdec('A9')), htmlentities('©'), $str);
	$str = str_replace(chr(hexdec('C2')).chr(hexdec('AE')), htmlentities('®'), $str);
	*/
	
	 $str=utf8dec($str);
	
	return $str;
}


function extendedAsciiToHtml($str){
// 139 et 155 doivent etre skippés
/*
	$search=array();
	$replace=array();
	for($i=130;$i<=192;$i++){
		if ($i!=139	&& $i!=155){
			$search[]=chr($i);
			//$replace[]=htmlentities(chr($i));	
			$replace[]='&#'.$i.';';	
		}
	}*/
	
	/*
	$search=array('™', '€', '©', '®');
	$replace=array('&#153;', '&euro;', '&#169;', '&reg;');
	$str=str_replace($search,$replace,$str);*/

 return $str;
}

function extendedAsciiToChar($str){
// 139 et 155 doivent etre skippés

	$search=array();
	$replace=array();
	for($i=130;$i<=192;$i++){
		if ($i!=139	&& $i!=155){
			$search[]='&#'.$i;	
			$replace[]=chr($i);			
		}
	}
	$str=str_replace($search,$replace,$str);

 return $str;
}

function getExtendedAsciiTable($iIn, $iOut){
	$aReturn = array();
	for ($i=$iIn; $i<$iOut;$i++){
		$aReturn[] = chr($i);	
	}
	return $aReturn;
}

function getExtendedAsciiTableReplaces($iIn, $iOut){
	$aReturn = array();
	for ($i=$iIn; $i<$iOut;$i++){
		$aReturn[] = '&#'.$i.';';	
	}
	return $aReturn;
}

function getExtendedAsciiTableUTF8($iIn, $iOut){
	$aReturn = array();
	for ($i=$iIn; $i<$iOut;$i++){
		$aReturn[] = utf8_encode(chr($i));	
	}
	return $aReturn;
}
 
// fonction qui permet de simplifier une chaine de caractères
// enleve accents
// remplace espace par _ 
function html2simple($strAccents){

$htmlcodes = array("‘", 
"’", 
"‚", 
"“", 
"”", 
"„", 
"†", 
"‡", 
"‰", 
"‹", 
"›", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"™", 
"\"", 
"&", 
"/", 
"<", 
">", 
"–", 
"—", 
" ", 
"¡", 
"¢", 
"£", 
"¤", 
"¥", 
"¦", 
"§", 
"¨", 
"©", 
"ª", 
"«", 
"¬", 
"­", 
"®", 
"¯", 
"°", 
"±", 
"²", 
"³", 
"´", 
"µ", 
"¶", 
"·", 
"¸", 
"¹", 
"º", 
"»", 
"¼", 
"½", 
"¾", 
"¿", 
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
"%", 
"'",
",",
"?",
" ");

$replaces = array("_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"?", 
"_", 
"_", 
"_", 
"_", 
"<", 
">", 
"–", 
"—", 
" ", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"_", 
"A", 
"A", 
"A", 
"A", 
"A", 
"A", 
"AE", 
"C", 
"E", 
"E", 
"E", 
"E", 
"I", 
"I", 
"I", 
"I", 
"D", 
"N", 
"O", 
"O", 
"O", 
"O", 
"O", 
"_", 
"_", 
"U", 
"U", 
"U", 
"U", 
"Y", 
"_", 
"b", 
"a", 
"a", 
"a", 
"a", 
"a", 
"a", 
"_", 
"c", 
"e", 
"e", 
"e", 
"e", 
"i", 
"i", 
"i", 
"i", 
"_", 
"n", 
"o", 
"o", 
"o", 
"o", 
"o", 
"_", 
"_", 
"u", 
"u", 
"u", 
"u", 
"y", 
"_", 
"y",
"_",
"_",
"_",
"_",
"_");

return str_replace($htmlcodes, $replaces, $strAccents);
}


define('_is_utf8_split',5000);

function is_utf8($string) { // v1.01
    if (strlen($string) > _is_utf8_split) {
        // Based on: http://mobile-website.mobi/php-utf8-vs-iso-8859-1-59
        for ($i=0,$s=_is_utf8_split,$j=ceil(strlen($string)/_is_utf8_split);$i < $j;$i++,$s+=_is_utf8_split) {
            if (is_utf8(substr($string,$s,_is_utf8_split)))
                return true;
        }
        return false;
    } else {
        // From http://w3.org/International/questions/qa-forms-utf-8.html
        return preg_match('%^(?:
                [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )*$%xs', $string);
    }
} 

?>