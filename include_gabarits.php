<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// fonction incluant le gabarit dans un fichier temporaire
// inclusion des composants (styles, div) qui vont être ajoutés au gabarit

function getInclude_gabarit($gabarit, $gabfilehandle, $tmpfilehandle, $composants, $oCms_page_gabdepart, $widthGab, $heightGab)
{	
	/*while((!feof($gabfilehandle)) && !$stop){
		$tampon_gabarit=fgets($gabfilehandle, $buffer);
		fputs($tmpfilehandle, $tampon_gabarit);
		if(preg_match('/<\!--DEBUTCMS--\>/', $tampon_gabarit)) {
			$stop=1;
		}
	}*/
	$tmpfilehandle = $tmpfilehandle;
	//========================
	// debut div space, div content
	//========================
	$composants = $_SESSION[$_SESSION['GabaritName']];
	$tampon_debutdiv = '<div id="space" class="space">
	<div id="content" class="content">';
	$tmpfilehandle.=$tampon_debutdiv;
	//fputs($tmpfilehandle, $tampon_debutdiv);

	//========================
	// div composants
	//========================
	foreach($composants as $k => $v) {
		$composant = getComposantById($v);
		$tamponHTML = $composant['html'];

		if( ($composant['type']=='formulaire') || ($composant['type']=='HTML')) {
		
			$tamponHTML = preg_replace('/\<form/i', '<uniform', $tamponHTML);
			$tamponHTML = preg_replace('/\<\/form\>/i', '</uniform>', $tamponHTML);
		}

		$tampon_div = '';
		$tampon_div.= "<script language=\"javascript\">divArray.push(".$v.");</script>";
		$tampon_div.= "<div style=\"border-width: 1px; border-style: dotted;z-index: 3;\" id=\"".$composant['id']."\" class=\"".$composant['id']."\">\n";
		$tampon_div.= $tamponHTML;
		$tampon_div.="\n</div>\n";
		//fputs($tmpfilehandle, $tampon_div);
		$tmpfilehandle.=$tampon_div;
	}

	//========================
	// fin div space, div content
	//========================
	$tampon_findiv='  </div>
	</div>';
	//fputs($tmpfilehandle, $tampon_findiv);
	$tmpfilehandle.=$tampon_findiv;

	//========================
	// styles
	//========================
	$styles='
	<style type="text/css">
	
	.remoteCellControl{
		background-color: #ffffff;
		font-family: arial;
		font-size: 11px;
	}
	
	.remoteCellControlTable{
		background-color: #000000;
	}
	
	.remoteCellControlTitle{
		background-color: #fcd4a5;
		font-family: arial;
		font-size: 11px;
		text-align: center;
	}
	
	.space {
		position: relative;
		background-color: #ffffff;
		width: '.$widthGab.'px;
		height: '.$heightGab.'px;
		overflow: '.divOverflow().';
		overflow-x: '.divOverflow().';
		overflow-y: '.divOverflow().';
		text-align: left;
	};
	
	.content {
		position: relative;
		width: '.$widthGab.'px;
		height: '.$heightGab.'px;
		overflow: '.divOverflow().';
		overflow-x: '.divOverflow().';
		overflow-y: '.divOverflow().';
		text-align: left;
	};
	
	';
	

	foreach($composants as $k => $v) {
		$styles.='
	.'.$v.' {
		position: relative;
		overflow-x: visible;
		overflow-y: visible;
		text-align: left;
	};
	
		';
	}

	$styles.='
	</style>';
	//fputs($tmpfilehandle, $styles);
	$tmpfilehandle.=$styles;

	//========================
	// scripts
	//========================

	$scripts='
	<script type="text/javascript">
		var tmpStyle = null;
	';
	foreach($composants as $k => $v) {
		$composant = getComposantById($v);
		$scripts.='
		tmpStyle = document.getElementById("'.$composant['id'].'").style;		
		tmpStyle["width"] = "'.$composant['width'].'px";		
		tmpStyle["height"] ="'.$composant['height'].'px";		
		tmpStyle["top"] = "0px";		
		tmpStyle["left"] = "0px";		
		tmpStyle["filter"] ="alpha(opacity=100)";	
		tmpStyle["-moz-opacity"] = "1";		
		tmpStyle["zIndex"] = "1";		
		tmpStyle["position"] = "absolute";		
		tmpStyle["background"] = "#dddddd";		
		tmpStyle["visibility"] = "hidden";
		';
	}
	$scripts.='
	</script>
	';
	//fputs($tmpfilehandle, $scripts);
	$tmpfilehandle.=$scripts;
	// on se repositionne au début du fichier
	
	//$gabfilehandle = fopen($gabarit,'r') or (error_log("Soit lecture impossible dans $gabarit") && die('Erreur irrécupérable. Veuillez contacter l\'administrateur.'));

//print("<br>INIT=>".$gabarit);


	$fin_trouve=0;

	// lecture première ligne
	//$tampon_gabarit=fgets($gabfilehandle, $buffer);
	$tampon_gabarit=$gabfilehandle;

	// parcours de tout le fichier
	/*while((!feof($gabfilehandle))){

		// si on a trouvé la chaine FINCMS, chaine à partir 
		// de laquelle on va commencer à écrire la fin du gabarit
		if (preg_match('/<\!--FINCMS--\>/', $tampon_gabarit)) { 
			// print("<br>TROUVE\n"); 
			$fin_trouve = 1; 
		}

		// lecture du fichier
		$tampon_gabarit=fgets($gabfilehandle, $buffer);
//print("<br>--------tampon_gabarit AAA--------------<br>");
//var_dump($tampon_gabarit);
//print("<br>--------tampon_gabarit XXX--------------<br>");

		// écriture dans le gabarit
		if ($fin_trouve == 1) {

//print("<br>ET ECRIS\n");
			fputs($tmpfilehandle, $tampon_gabarit);
		}
	}*/
	$tmpfilehandle.=$tampon_gabarit;

	// fermeture fichier temporaire
	//fclose($tmpfilehandle);
	return $tmpfilehandle;

}

?>