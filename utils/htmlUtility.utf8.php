<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// ce fichier DOIT etre sauvé en UTF8 //

function utf8_decode_recursive($input) {
	$return = array();
	foreach ($input as $key => $val) {
	  if (is_array($val)) $return[$key] = utf8_decode_recursive($val);
	  else $return[$key] = utf8_decode($val);
	}
	return $return;          
}

function utf8_decode_post_if_needed() {
	// Force encoding for jQuery AJAX posted data
	if (!empty($_POST)) {
		$flag_unicoded = false;
		
		if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'charset=utf-8') !== false)
			$flag_unicoded = true;
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && strpos(strtolower($_SERVER['CONTENT_TYPE']), 'charset') === false)
			$flag_unicoded = true;
		if ($flag_unicoded)
			$_POST = utf8_decode_recursive($_POST);
	}	
}

function utf8IfNeeded($str){ // basé sur $_SESSION['encod']
	if (preg_match('/UTF-8/msi', $_SESSION['encod'])){
		$str = utf8_encode($str);	
	}
	return $str;
}

function entitiesToUtf8($str){
	return preg_replace_callback ('/&#[0-9]{4};/msi' , 'entityToUtf8',  $str);
}

function entityToUtf8($entity) {
	if (is_array($entity)){
		$entity = $entity[0];
	}

	if (preg_match('/^[0-9]+$/si', $entity)){
		$entity = '&#' . intval($entity) . ';';
	}

    return mb_convert_encoding($entity , 'UTF-8', 'HTML-ENTITIES');
}


function russianToLatin($str){
    $tr = array(
    "А"=>"a", "Б"=>"b", "В"=>"v", "Г"=>"g", "Д"=>"d",
    "Е"=>"e", "Ё"=>"yo", "Ж"=>"zh", "З"=>"z", "И"=>"i", 
    "Й"=>"j", "К"=>"k", "Л"=>"l", "М"=>"m", "Н"=>"n", 
    "О"=>"o", "П"=>"p", "Р"=>"r", "С"=>"s", "Т"=>"t", 
    "У"=>"u", "Ф"=>"f", "Х"=>"kh", "Ц"=>"ts", "Ч"=>"ch", 
    "Ш"=>"sh", "Щ"=>"sch", "Ъ"=>"", "Ы"=>"y", "Ь"=>"", 
    "Э"=>"e", "Ю"=>"yu", "Я"=>"ya", "а"=>"a", "б"=>"b", 
    "в"=>"v", "г"=>"g", "д"=>"d", "е"=>"e", "ё"=>"yo", 
    "ж"=>"zh", "з"=>"z", "и"=>"i", "й"=>"j", "к"=>"k", 
    "л"=>"l", "м"=>"m", "н"=>"n", "о"=>"o", "п"=>"p", 
    "р"=>"r", "с"=>"s", "т"=>"t", "у"=>"u", "ф"=>"f", 
    "х"=>"kh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"sch", 
    "ъ"=>"", "ы"=>"y", "ь"=>"", "э"=>"e", "ю"=>"yu", 
    "я"=>"ya", " "=>"-", "."=>".", ","=>".", "/"=>"-",  
    ":"=>".", ";"=>".","—"=>"-", "–"=>"-"
    );
	return strtr($str,$tr);
}
?>
