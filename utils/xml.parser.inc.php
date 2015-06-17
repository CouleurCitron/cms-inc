<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
//function html_to_rss($string){
//function apos($sStr){
//function XMLconforme($strtext){
//function XMLconformeURL($strtext){
//function xmlArray2Node($aA, $bUseCDATA=true, $bUseCR=true, $bUpperCase=false){
//function str2textnode($rawstr){
//function makeHTMLcodeXMLfriendly($htmlcode){
//function makeEmailAddyXMLfriendly($email){
//function xml_header($nom="adequat", $charset="utf-8"){
//function xml_footer($nom="adequat"){
//function array2node($aArray, $sNodeName, $id, $encode=false, $fermer=true, $onlyAttribs=NULL, $butAttribs=NULL, $laisserOuvert=false){
//function xmlStringParse($string){
//function xmlFileParse($file){
//function xmlUrlParse($string){
//function readXMLfromURL($str){
//global $stack;


function html_to_rss($string){
	$aHtmlBr = array("<br>", "<br />", "<br/>", "<BR>", "</div>", "</p>", "</DIV>", "</p>");
	//var_dump($string);
	$string = preg_replace('/<[^<>]+>/msi', '', str_replace($aHtmlBr, "\n", $string));
	//var_dump($string);
	$string = html_entity_decode($string);
	//var_dump($string);
	$string = utf8_encode($string);
	//var_dump($string);
	$string = str_replace("&euro;", "€", $string);
	//var_dump($string);
	return $string;
}

function apos($sStr){
	//return str_replace("'", "&apos;", $sStr);
	return str_replace("'", "´", $sStr);
	//return str_replace("'", " ", $sStr);
	//return $sStr;
}

// xml conforme
function XMLconforme($strtext){
	$search = array("&", "<br>", "gif'>");
	$replace = array("&amp;", "<br />", "gif' />");
	return str_replace($search, $replace, $strtext);
}

function XMLconformeURL($strtext){
	$search = array("&", " ");
	$replace = array("%26", "%20");
	return str_replace($search, $replace, $strtext);
}

$stack = array();

function xmlArray2Node($aA, $bUseCDATA=true, $bUseCR=true, $bUpperCase=false){
	$sXML = "";	
	if($bUseCR){
		$sGlue="\n";
	}
	else{
		$sGlue="";
	}
	if (is_array($aA)){
		foreach($aA as $key => $aNode){
			if ($bUpperCase===true){ // par defaut en lower
				$sXML .= "<".strtoupper($aNode["name"]);
			}
			else{
				$sXML .= "<".strtolower($aNode["name"]);
			}
			
			// attributs
			foreach($aNode["attrs"] as $attrK => $attrV){
				if ($bUpperCase===true){ // par defaut en lower
					$sXML .= ' '.strtoupper($attrK).'="'.$attrV.'"';
				}
				else{
					$sXML .= ' '.strtolower($attrK).'="'.$attrV.'"';
				}
					
			}
			// node fermé ou ouvert ?
			if($aNode["children"]||$aNode["cdata"]){
				// ouvert
				$sXML .=">".$sGlue;
				// children
				if($aNode["children"]){
					$sXML .= xmlArray2Node($aNode["children"], $bUseCDATA, $bUseCR, $bUpperCase);
				}
				// cdata
				if($aNode["cdata"]){
					if ($bUseCDATA){
						$sXML .="<![CDATA[".$aNode["cdata"]."]]>".$sGlue;
					}
					else{
						$sXML .= $aNode["cdata"].$sGlue;
					}
				}
				if ($bUpperCase===true){ // par defaut en lower
					$sXML .="</".strtoupper($aNode["name"]).">".$sGlue;
				}
				else{
					$sXML .="</".strtolower($aNode["name"]).">".$sGlue;
				}				
			}
			else{
				// fermé			
				$sXML .=" />".$sGlue;
			}		
		}
	}
	else{
		error_log( 'not array');
		//var_dump($aA);
	}
	return $sXML;
}

function str2textnode($rawstr){
	$rawstr = str_replace("&", "&amp;", $rawstr);
	$forbidden = array("\"", "<");
	$replace = array("&quot;", "&lt;");
	$rawstr = str_replace($forbidden, $replace, $rawstr);
	$rawstr =  str_replace("&amp;rsquo;", "´", $rawstr);
	$rawstr =  str_replace("&amp;oelig;", "œ", $rawstr);
	$rawstr =  str_replace("&amp;OElig;", "Œ", $rawstr);
	
	$rawstr =  str_replace("&amp;amp;", "&amp;", $rawstr);
	
	return $rawstr;
}


function html2charscodes($strAccents, $reverse=false){
	$htmlcodes = array("&lsquo;", 
"&rsquo;", 
"&sbquo;", 
"&ldquo;", 
"&rdquo;", 
"&bdquo;", 
"&dagger;", 
"&Dagger;", 
"&permil;", 
"&lsaquo;", 
"&rsaquo;", 
"&spades;", 
"&clubs;", 
"&hearts;", 
"&diams;", 
"&oline;", 
"&larr;", 
"&uarr;", 
"&rarr;", 
"&darr;", 
"&trade;", 
"&quot;", 
"&amp;", 
"&frasl;", 
"&lt;", 
"&gt;", 
"&ndash;", 
"&mdash;", 
"&nbsp;", 
"&iexcl;", 
"&cent;", 
"&pound;", 
"&curren;", 
"&yen;", 
"&brvbar;",
"&sect;", 
"&uml;", 
"&copy;", 
"&ordf;", 
"&laquo;", 
"&not;", 
"&shy;", 
"&reg;", 
"&macr;",
"&deg;", 
"&plusmn;", 
"&sup2;", 
"&sup3;", 
"&acute;", 
"&micro;", 
"&para;", 
"&middot;", 
"&cedil;", 
"&sup1;", 
"&ordm;", 
"&raquo;", 
"&frac14;", 
"&frac12;", 
"&frac34;", 
"&iquest;", 
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
"ä", 
"å", 
"æ", 
"ç", 
"è", 
"é", 
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
"&yuml;");

$replaces = array("‘", 
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
"”", 
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
"ÿ");
if($reverse==false){
	return str_replace($htmlcodes, $replaces, $strAccents);
}
else{
	return str_replace($replaces, $htmlcodes, $strAccents);
}
}


function makeHTMLcodeXMLfriendly($htmlcode){
	$htmlcode = utf8_encode(html_entity_decode(preg_replace('/<[^<>]+>/msi', '', preg_replace('/<br[^<>]*>/msi', '\n', $htmlcode))));
	$htmlcode =  str2textnode($htmlcode); 
	return $htmlcode;
}

function makeEmailAddyXMLfriendly($email){
	$email = preg_replace('/[^<]+</msi', '', preg_replace('/>.*/msi', '', $email));
	return trim($email);
}

function xml_header($nom="adequat", $charset="utf-8"){
	@header("Content-type: text/xml; charset=".$charset);	
	echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?".">\n";
	echo "<".$nom.">\n";
}
function xml_footer($nom="adequat"){
	echo "</".$nom.">\n";
}

function array2node($aArray, $sNodeName, $id, $encode=false, $fermer=true, $onlyAttribs=NULL, $butAttribs=NULL, $laisserOuvert=false){

	$sNode = '<'.$sNodeName.' ';
	$foundAnyId = false;
	if (is_array($aArray)||is_object($aArray)){
		foreach ($aArray as $key => $value) {			
		
			if (preg_match('/id/i', $key)==1){
				$foundAnyId = true;
			}
			if ($onlyAttribs!=NULL){
				if (in_array($key, $onlyAttribs)){
					$bEchoAttribute = true;
				}
				else{
					$bEchoAttribute = false;
				}
			}
			else{
				$bEchoAttribute = true;
			}
			
			if ($butAttribs!=NULL){
				if (in_array($key, $butAttribs)){
					$bEchoAttribute = false;
				}
				else{
					$bEchoAttribute = true;
				}
			}
			
			if (is_string($value) && (preg_match("/^[0-9]+$/msi", $key)==0) && (preg_match("/xml/msi",$key)==0) && (preg_match("/smysql/msi",$key)==0) && ($bEchoAttribute == true)){
				$value = str_replace("&nbsp;", " ", $value);	
				if (preg_match('/<[^<>]+>/msi', $value)==1){ // CAS HTML
					$value = str_replace(chr(128), "€", $value);
					$value = preg_replace('/<(\/*)strong>/msi', '<$1b>', $value);	
					$value = html_entity_decode($value);				
					$value = str_replace("&rsquo;", "&apos;", $value);
					$value = str_replace("€", "Euros", $value);	
					$value = str_replace("&euro;", "Euros", $value);	
					if (strpos($value, "<") !== false){
						if ($encode == false){			
							$value = rawurlencode($value);
						}
						else{
							$value = rawurlencode(utf8_encode($value));
						}
						$sNode .= strtolower($key)."=\"".$value."\" ";
					}		
					else{	
						$value = str_replace(array("\"", "&", "<", ">", "'"), array("&quot;", "&amp;", "&lt;", "&gt;", "&apos;"), $value);		
						if ($encode == false){			
							$sNode .= strtolower($key)."=\"".$value."\" ";
						}
						else{
							$sNode .= strtolower($key)."=\"".utf8_encode($value)."\" ";
						}
					}
				}// FIN CAS HTML
				else{ // CAS TEXT RAW
					$value = str_replace(chr(128), "€", $value);
					$value = (html_entity_decode(preg_replace('/<[^<>]+>/msi', '', preg_replace('/<br[^<>]*>/msi', '\n', $value))));
					$value = str_replace("&rsquo;", "&apos;", $value);
					$value = str_replace("€", "Euros", $value);	
					$value = str_replace("&euro;", "Euros", $value);	
					if (strpos($value, "<") !== false){
						if ($encode == false){			
							$value = rawurlencode($value);
						}
						else{
							$value = rawurlencode(utf8_encode($value));
						}
						$sNode .= strtolower($key)."=\"".$value."\" ";
					}		
					else{	
						$value = str_replace(array("\"", "&", "<", ">", "'"), array("&quot;", "&amp;", "&lt;", "&gt;", "&apos;"), $value);		
						if ($encode == false){			
							$sNode .= strtolower($key)."=\"".$value."\" ";
						}
						else{
							$sNode .= strtolower($key)."=\"".utf8_encode($value)."\" ";
						}
					}
				}// FIN CAS TEXT RAW
		   }
		} // fin foreach
	}
	
	if ($foundAnyId == false){
		$sNode .= "id=\"".$id."\" ";
	}
	if ($laisserOuvert == false){
		if ($fermer==true){
			$sNode .=  "/>\n";	
		}
		else{
			$sNode .=  ">\n";
		}
	}
	else{
			$sNode .=  ' ';
		}
	return $sNode;
}

function xmlFileParse($file){
	//$file = 'classe.xml';
	global $stack;
	$stack = Array();

	$xml_parser = xml_parser_create("ISO-8859-1");
	xml_set_element_handler($xml_parser, "startTag", "endTag");
	xml_set_character_data_handler($xml_parser, "cdata");
	
	$data = xml_parse($xml_parser,file_get_contents($file));
	if(!$data) {
	   die(sprintf("XML error: %s at line %d",
	xml_error_string(xml_get_error_code($xml_parser)),
	xml_get_current_line_number($xml_parser)));
	}
	
	xml_parser_free($xml_parser);
	
	return $stack;
}

function xmlStringParse($string){
	
	global $stack;
	$stack = Array();
	
	$xmlTag = '<?xml version="1.0" encoding="ISO-8859-1" ?'.'>';
	
	if (strpos($string, '<?xml')===false){
		$string = $xmlTag.$string;
	}	
	
	// sid : replace des apos typo
	$string = str_replace(array('&#8217;', 'â€™', '’'), array('\'', '\'', '\''), $string);

	$xml_parser = xml_parser_create("ISO-8859-1");
	xml_set_element_handler($xml_parser, "startTag", "endTag");
	xml_set_character_data_handler($xml_parser, "cdata");
	
	$data = xml_parse($xml_parser,$string);

	if(!$data) {
		//mail('pierre@couleur-citron.com', 'xml', $string);
	   error_log(sprintf("XML error: %s at line %d",
	xml_error_string(xml_get_error_code($xml_parser)),
	xml_get_current_line_number($xml_parser)));
	
		if (defined('DEF_BDD_DEBUG')&&DEF_BDD_DEBUG){
			 print(sprintf("XML error: %s at line %d",
	xml_error_string(xml_get_error_code($xml_parser)),
	xml_get_current_line_number($xml_parser)));
		}
	}
	//	mail('pierre@couleur-citron.com', 'xml parsed ok', $string);
	
	xml_parser_free($xml_parser);

}


/// -- pre requis ---------

function startTag($parser, $name, $attrs) 
{
   global $stack;
   $tag=array("name"=>$name,"attrs"=>$attrs);  
   array_push($stack,$tag);
  
}

function cdata($parser, $cdata)
{
   global $stack;

   if(trim($cdata))
   {    
   		if (isset($stack[count($stack)-1]['cdata'])&&(strlen($stack[count($stack)-1]['cdata'])>0)){ // cas des cdata over 1024 chars
			 $stack[count($stack)-1]['cdata'].=$cdata;
		}
		else{
			 $stack[count($stack)-1]['cdata']=$cdata;
		}	  
   }
}

function endTag($parser, $name) 
{
   global $stack;  
   $stack[count($stack)-2]['children'][] = $stack[count($stack)-1];
   array_pop($stack);
}



function xmlUrlParse($string){
	$xml = readXMLfromURL($string);
	
	xmlStringParse($xml);
	
	return $xml;
}

function readXMLfromURL($str){
	if(function_exists(curl_init)){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $str);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds	   
		//curl_setopt($ch, CURLOPT_POST, true); 
		//curl_setopt($ch, CURLOPT_POSTFIELDS, 'dowhat=adddemo&email='.$from); 
		
		$cRetour = curl_exec($ch);

		$update = curl_getinfo($ch, CURLINFO_FILETIME);

		curl_close($ch);

		return $cRetour;
	}
	else{
		$res = fopen($str, "r");
		if ($res){
			$sBody = "";
			while(!feof($res)) {
				$sBody.=fgets($res);
			}
			fclose($res);	
			return $sBody;
		}
		else{
			return "";
		}
	}
}


?>