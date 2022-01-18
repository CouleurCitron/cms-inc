<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
function mso_html_cleanup($html){


// Corrections spécifiques...
	// IE (dyslexique) créé des vAlign=center align=middle 
	// Alors que ça devrait être align="center" et valign="middle"
	// A ne faire que sur les TD!! car dans IMG c'est autorisé! valign=middle
		// Mark all = align=middle
		$html = eregi_replace ( "align=middle", "§§§align=middle", $html );
		// Change mark for all align=middle in TD
		$html = eregi_replace ( "(<td[^§>]*)§§§([^>]*>)", "\\1µµµ\\2", $html );
		// Replace
		$html = eregi_replace ( "µµµalign=middle", "align=center", $html );
		// Mark all = align=middle
		$html = eregi_replace ( "§§§", "", $html );	
		
	$html = eregi_replace ( "vAlign=center", "valign=middle", $html );
	
	// Del all head of doc
	$html = eregi_replace ( ".*<body[^>]*>", "", $html );
	
	// Del all comment
	$html = eregi_replace ( "<\!\-\-[^>]*>", "", $html );
	$html = eregi_replace ( "<[^>\-]*\-\->", "", $html );

	// Del word comment
	$html = eregi_replace ( "<\![^>]*>", "", $html );
		
	// Del Class and Style attribute
	$html = eregi_replace ( "class=[\"]?Mso[^[:space:]]+[[:space:]]*style=[\'\"]+[^\'\"]+[\'\"]+", "", $html );
	
	// Del Class attribute
	//$html = eregi_replace ( "[\ ]*class=Mso[^\ >\"\']+", "", $html );

	// Del end of doc
	$html = eregi_replace ( "</body.*", "", $html );

	// Supp v:shape(s) attribute
	$html = eregi_replace ( "[\ ]*v\:shape[s]?=[\"\']+[^\"\']+[\"\']+", "", $html );
	
	// Supp v: and o: tag
	$html = eregi_replace ( "<[\/]?(v|o)\:[^>]*>", "", $html );

	// supp space in tag
	$html = eregi_replace ( "[ \f\n\r]+>", ">", $html );
	
	// Supp span/xmp tag
	$html = eregi_replace ( "<[\/]?(span|xml)[^>]*>", "", $html );

	// Supp st1:metricconverter attribute
	$html = eregi_replace ( "<[\/]?st1:[^>]*>", "", $html );

	// Replace word quote
	$html = eregi_replace ( "’", "'", $html );
	 
	// Replace word special caracter
	$html = eregi_replace ( "\&\#128\;", "&euro;", $html );
	$html = eregi_replace ( "–", "-", $html );
	$html = eregi_replace ( "…", "...", $html );



//// make XMTML 1.0 Strict ////


	// Mise en minuscule des tags (pas des attributs)
	$html = preg_replace("`(</?[[:alnum:]]+)`e", "strtolower('\\1')", $html);
	
	// Remove clear attribute to BR
	$html = eregi_replace ( "<(br|hr)[^>]*>", "<\\1/>", $html );
	
	// Add quote to alphanumeric attribute and put in lower case
		// Mark all = caracter
		$html = eregi_replace ( "µµµ", "", $html );	
		
		// control sur des attributs perdus : class=noir11""  >>  class="noir11"
		$html = eregi_replace ( "class=(\w*)\"\"", "class=\"\\1\"", $html );		
		$html = eregi_replace ( "src=(\w*)\"\"", "src=\"\\1\"", $html );		
		$html = eregi_replace ( "href=(\w*)\"\"", "href=\"\\1\"", $html );		
		$html = eregi_replace ( "title=(\w*)\"\"", "title=\"\\1\"", $html );	
		$html = eregi_replace ( "alt=(\w*)\"\"", "alt=\"\\1\"", $html );	
		
		// correctif pour les a href comportant des ?param=value
		//$html = eregi_replace ( "href=\"([^\"]*)=\"([^ ]*) ", "href=\"\\1=\\2\" ", $html );	
		// cas url a un param
		$html = eregi_replace ( "\?([^\"]*)=\"([^ \"]*) ", "?\\1=\\2\" ", $html );
		// cas url a X param - premier param
		$html = eregi_replace ( "\?([^\"]*)=\"([^&\"]*) ", "?\\1=\\2\"&", $html );
		// cas url a X param - params suivants
		$html = eregi_replace ( "&([^\";]*)=\"([^&\"]*) ", "&\\1=\\2\"&", $html );
		// cas url a X param - dernier param
		$html = eregi_replace ( "&([^\";]*)=\"([^ \"]*) ", "&\\1=\\2\" ", $html );	
		
		// /lib/spaw.1.1rc1/pute.php?id=&menuOpen=true
		$html = eregi_replace ( "/lib/spaw.1.1rc1/", "", $html );
		
		// href="pute.php?id="test""
		$html = eregi_replace ( "href=\"([^\?]*)\?([^=]*)=\"([^\"]*)\"\"", "href=\"\\1?\\2=\\3\"", $html );	
		
		// on vire les empty.html
		$html = eregi_replace ( "empty.html", "", $html );
		// Remove Mark from "=" outside a tag
		$html = eregi_replace ( "(>[^<µ]+)µµµ=", "\\1=", $html );
		// Pass attribute in lowercase
		$html = preg_replace("`([ \f\n\r]+)([[:alnum:]]+)(µµµ=)`e", "'\\1'.strtolower('\\2').'\\3'", $html);
		// Remove Mark from "=" with quote
		$html = eregi_replace ( "µµµ(=[\"\']+)", "\\1", $html );
		$html = eregi_replace ( "µµµ=([[:alnum:]]+)(.?[,)])", "=\\1\\2", $html ); // special attribut like popup javascript
		$html = eregi_replace ( "µµµ=([[:space:]])", "=\\1", $html ); // special attribut vide like adresse dans javascript
		// Add quote to Marked
		$html = eregi_replace ( "µµµ=([^[:space:]>]*)", "=\"\\1\"", $html );
		// Remove rest Mark
		//$html = eregi_replace ( "µµµ", "", $html );	

	// Add slash at AREA INPUT IMG BR HR META tags
		$html = eregi_replace ( "(<)(area|input|img|br|hr|meta)([^>]*)(>)", "\\1\\2\\3/\\4", $html );
		$html = eregi_replace ( "[\/]{2,}>", " />", $html );

	// Add alt attribute to image
		// Mark existing Alt
		$html = eregi_replace ( "(alt=)", "§§§\\1§§§", $html );
		// Search Img without Alt
		$html = eregi_replace ( "(<img)([^>§]*)(\/>)", "\\1\\2 alt=\"\"\\3", $html );
		// Unmark existing Alt
		$html = eregi_replace ( "§§§", "", $html );
		
	// Clean P tag before and after table/div
		$html = eregi_replace ( "<p[^>]*>[ \f\n\r]*(<br/>)*[ \f\n\r]*<(table|div)", "\\1<\\2", $html );
		$html = eregi_replace ( "</(table|div)>[ \f\n\r]*(<img[^>]*>)*[ \f\n\r]*(<br/>)*[ \f\n\r]*(\&nbsp\;)*</p>", "</\\1>\\2\\3", $html );


	// Suppression des tags vides
	$motif = '#<(\w+)></\1>#';
	$html = preg_replace ( $motif, "", $html );
	
	// Traitement special apres spaw => code incompatible à la sortie avec spaw
	// C'est un choix soit compatible et modifiable avec spaw, soit W3C
	// convert attributes in style not good for spaw (only for XHTML 1.0 Strict compatibility)
/*
		// height attribute
		$html = eregi_replace ( "<(table|tr|td)([^>]*) height=\"([^\"]*)\"", "<\\1 style=\"height:\\3\"\\2", $html );

		// width attribute
		$html = eregi_replace ( "<(table|tr|td)([^>]*) width=\"([^\"]*)\"", "<\\1 style=\"width:\\3\"\\2", $html );
	
		// bgcolor attribute
		$html = eregi_replace ( "<(table|tr|td)([^>]*) bgcolor=\"([^\"]*)\"", "<\\1 style=\"background-color:\\3\"\\2", $html );

		// background attribute (image)
		$html = eregi_replace ( "<(table|tr|td)([^>]*) background=\"[[:space:]]*([^\"]*)\"", "<\\1 style=\"background-image:url(\'\\3\')\"\\2", $html );
		
		// align attribute
		$html = eregi_replace ( "<(table|tr|td|p)([^>]*) align=\"([^\"]*)\"", "<\\1 style=\"text-align:\\3\"\\2", $html );

		// align attribute
		$html = eregi_replace ( "<(td)([^>]*) nowrap", "<\\1 style=\"white-space:nowrap\"\\2", $html );

		// remove image name attribute
		$html = eregi_replace ( "<(img)([^>]*) name=\"([^\"]*)\"", "<\\1\\2", $html );

		// type attribute
		$html = eregi_replace ( "<(ul)([^>]*) type=\"([^\"]*)\"", "<\\1 style=\"list-style-type:\\3\"\\2", $html );

		// color attribute
		//$html = eregi_replace ( "<(font|span|table|td|div|p)([^>]*) color=\"([^\"]*)\"", "<\\1 style=\"color:\\3\"\\2", $html );

		// size attribute PEUX pas non modifiable
		//$tab_size = array("xx-small", "x-small", "small", "medium", "large", "x-large", "xx-large");
//		$html = preg_replace ( "`<(font)([^>]*) size=\"([^\"]*)\"`e", "'<'.'\\1'.' style=\"font-size:'.'$tab_size['.'\\3'.']'.'\"\\2'", $html );
		//$html = eregi_replace ( "<(font)([^>]*) size=\"([^\"]*)\"", "<\\1 style=\"font-size:\\3\"\\2", $html );
		
		// border attribute
		$html = eregi_replace ( "<(table|img)([^>]*) border=\"([^\"]*)\"", "<\\1 style=\"border:\\3\"\\2", $html );
		
		// vspace attribute
		$html = eregi_replace ( "<img([^>]*) vspace=\"([^\"]*)\"", "<img style=\"margin-top:\\2;margin-bottom:\\2\"\\1", $html );

		// hspace attribute
		$html = eregi_replace ( "<img([^>]*) hspace=\"([^\"]*)\"", "<img style=\"margin-right:\\2;margin-left:\\2\"\\1", $html );
		
		// Image align attribute!!! Incomplete in XHTML (only left/right)
		$html = eregi_replace ( "<(img)([^>]*) align=\"([^\"]*)\"", "<\\1 style=\"float:\\3\"\\2", $html );
	
		// Clean duplicate style tag
		$html = eregi_replace ( "style=\"([^\"]*)\" style=\"([^\"]*)\"", "style=\"\\1; \\2\"", $html );
		$html = eregi_replace ( "style=\"([^\"]*)\" style=\"([^\"]*)\"", "style=\"\\1; \\2\"", $html );

		// underline tag
		$html = eregi_replace ( "<u>([^<]*)</u>", "<span style=\"text-decoration:underline\">\\1</span>", $html );

		// font tag à éviter car non modifiable
		$html = eregi_replace ( "<font([^>]*)>([^<]*)</font>", "<span\\1>\\2</span>", $html );
*/

	return $html;
	/*
	// class=Mso...
	$html = eregi_replace ( "class=Mso[^>]+", "", $html );
	// Style=...
	$html = eregi_replace ( "style=[^>]+", "", $html );
	//$html = eregi_replace ( "<(.+) style=[^>]+", "<\\1>", $html );
	// dir=...
	$html = eregi_replace ( "dir=[^>]+", "", $html );
	// lang=...
	$html = eregi_replace ( "lang=[^>]+", "", $html );
	// <o:i>...</o:i>
	$html = eregi_replace ( "<o:[^<]+>", "", $html );
	$html = eregi_replace ( "</o:[^<]+>", "", $html );
	// <![if> ...
	$html = eregi_replace ( "<!\[[^<]+>", "", $html );
	// espace dans les balises
	$html = eregi_replace ( "[ \f\n\r]+>", ">", $html );
	// <span> vides
	$html = eregi_replace ( "<SPAN", "<span", $html );
	$html = eregi_replace ( "</SPAN", "</span", $html );
	$html = eregi_replace ( "<span>([^<]*)</span>", "\\1", $html );
	$html = eregi_replace ( "<span>([^<]*)</span>", "\\1", $html );
	while (strpos ($html, "<span>") > -1){
		$html = substr( $html, 0, strpos ($html, "<span>")).substr( $html, strpos ($html, "<span>") + 6);
		$html = substr( $html, 0, strpos ($html, "</span>")).substr( $html, strpos ($html, "</span>") + 7);
	}
	
	return $html;
	*/
}
?>
